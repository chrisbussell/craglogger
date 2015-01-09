<?php

	// Execute common code to connection to the database and start the session
	require("../includes/common.php");
	require("../includes/functions.php");

	// include and register Twig auto-loader
	include '../Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
	$loader = new Twig_Loader_Filesystem('../templates');

	// initialize Twig environment
	$twig = new Twig_Environment($loader);

	// load template
	$template = $twig->loadTemplate('dashboard/editaccount.tmpl');

	$firstname = '';
	$surname = '';
	$emailshow = '';
	$editaccount = '';
	$iserror = '';
	$errFirstname = '';
	$errSurname = '';
	$errPassword = '';

	// Check if user logged in or not
	if(empty($_SESSION['user'])){
		// If they are not, redirect them to the login page.
		header("Location: login.php");
	
		die("Redirecting to login.php");
	}

	// Has edit been submited
	if(!empty($_POST))
	{
		if($_POST['firstname'] == null){
			$iserror = 1;
			$errFirstname= "Please enter a firstname";
		//	die("Invalid firstname");
		}	

		if($_POST['surname'] == null){
			$iserror = 1;
			$errSurname= "Please enter a surname";
			//die("Invalid surname");
		}		
		// Make sure the user entered a valid E-Mail address
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$iserror = 1;
			$errEmail= "Invalid E-Mail Address";
			//die("Invalid E-Mail Address");
		}
				
		// check if email being updated has already been used
		if($_POST['email'] != $_SESSION['user']['email']){
			// Define our query parameter values
			$query_params = array(':email' => $_POST['email']);
			
			// check if email has been used before
			$stmt = checkemail($db, $query_params);
						
			// Retrieve results (if any)
			$row = $stmt->fetch();
			if($row){
				$iserror = 1;
				$errEmail = "This e-mail address is already in use";
				//die("This E-Mail address is already in use");
			}
		}		

		// If the user entered a new password, hash it and generate a fresh salt
		if(!empty($_POST['password'])){
			$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
			$password = hash('sha256', $_POST['password'] . $salt);
			for($round = 0; $round < 65536; $round++){
				$password = hash('sha256', $password . $salt);
			}
		}
		else
		{
			// If the user did not enter a new password not update their old one.
			$password = null;
			$salt = null;
		}
		
		// Initial query parameter values
		$query_params = array(
			':email' => $_POST['email'],
			':user_id' => $_SESSION['user']['user_id'],
			':firstname' => $_POST['firstname'],
			':surname' => $_POST['surname']
		);
		
		// If the user is changing their password, then we need parameter values
		// for the new password hash and salt too.
		if($password !== null){
			$query_params[':password'] = $password;
			$query_params[':salt'] = $salt;
		}
		
		if(!$iserror){

			// update firstname/surname/email/password
			updateuserdetails($db, $query_params);

			// Set email show preference 
			if (isset($_POST['emailshow']))
			{
				$query_params = array(
					':emailshow' => 1,
					':user_id' => $_SESSION['user']['user_id']);
			}
			else
			{
				$query_params = array(
					':emailshow' => 0,
					':user_id' => $_SESSION['user']['user_id']);
			}

			// Set emailshow preference
			updateuserconfig($db, $query_params);

			// Now that the user's E-Mail address has changed, the data stored in the $_SESSION
			// array is stale; we need to update it so that it is accurate.
			$_SESSION['user']['email'] = $_POST['email'];
		
			$editaccount = "Thank you, your details have been updated.";

		}
		else
		{
			$query_params = array(':user_id' => $_SESSION['user']['user_id']);
	
			// Get details for this user
			$stmt = getalluserdetails($db, $query_params);	
			$rows = $stmt->fetch();
	
			if (!empty($rows)) {
				$firstname = $rows['firstname'];
				$surname = $rows['surname'];
				$emailshow = $rows['emailshow'];
				$admin = $rows['admin'];
				$approved = $rows['approved'];
			}

			echo $template->render(array (
				'pageTitle' => 'Edit your account',
				'sid' => $_SESSION['user'],
				'updated' => $lastupdated,
				'admin' => $_SESSION['user']['admin'],
				'php_self' => $_SERVER['PHP_SELF'],
				'firstname' =>$firstname,
				'surname' =>$surname,
				'emailshow' =>$emailshow,
				'email' =>$_SESSION['user']['email'],
				'errPassword' => $errPassword,
				'errFirstname' => $errFirstname,
				'errSurname' => $errSurname,
				'errEmail' => $errEmail));
				die();
		}
	}

	$query_params = array(':user_id' => $_SESSION['user']['user_id']);
	
	// Get details for this user
	$stmt = getalluserdetails($db, $query_params);	

	$rows = $stmt->fetch();
	
	if (!empty($rows)) {
		$firstname = $rows['firstname'];
		$surname = $rows['surname'];
		$emailshow = $rows['emailshow'];
		$admin = $rows['admin'];
		$approved = $rows['approved'];
	}

	// set template variables
	// render template
	echo $template->render(array (
		'pageTitle' => 'Edit your account',
		'sid' => $_SESSION['user'],
		'updated' => $lastupdated,
		'admin' => $_SESSION['user']['admin'],
		'php_self' => $_SERVER['PHP_SELF'],
		'firstname' => $firstname,
		'surname' => $surname,
		'emailshow' => $emailshow,
		'editaccount' => $editaccount,
		'email' =>$_SESSION['user']['email']
	));

	