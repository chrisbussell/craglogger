<?php
	ob_start();

	require("includes/common.php");
	require("includes/functions.php");
	require("includes/PHPMailer/class.phpmailer.php");

	// include and register Twig auto-loader
        include 'Twig/Autoloader.php';
        Twig_Autoloader::register();

        // define template directory location
        $loader = new Twig_Loader_Filesystem('templates');

        // initialize Twig environment
        $twig = new Twig_Environment($loader);

        // load template
        $template = $twig->loadTemplate('register.tmpl');

	$error = '';
	$errFirstname = '';
	$errSurname = '';
	$errUsername = '';
	$errPassword = '';
	$errEmail = '';
		
	if(!empty($_POST)){

		// Ensure that the user has entered a non-empty firstname
		if(empty($_POST['firstname'])){
//			die("Please enter a firstname.");
			$error = 1;
			$errFirstname= "Please enter a firstname";
		}

		// Ensure that the user has entered a non-empty surname
		if(empty($_POST['surname'])){
			///die("Please enter your surname.");
			$error = 1;
			$errSurname= "Please enter a surname";
		}

		// Ensure that the user has entered a non-empty username
		if(empty($_POST['username'])){
			//die("Please enter a username.");
			$error = 1;
			$errUsername= "Please enter a username";
		}

		// Ensure that the user has entered a non-empty password
		if( strlen($_POST['password']) < 6 ) {
			$error = 1;
			$errPassword= "Password must be 6 characters or more";
			}

		// set email show to false
		if(empty($_POST['emailshow'])){
			$_POST['emailshow'] = 0;
		}

		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$error = 1;
			$errEmail= "Invalid E-Mail Address";
			//die("Invalid E-Mail Address");
		}

		// Set query params for sql call
		$query_params = array(
			':username' => $_POST['username']
		);

		// Call check username
		$stmt = checkusername($db,$query_params);

		$row = $stmt->fetch();
	
		// If we get a result then username already used
		if($row){
			$error = 1;
			$errUsername= "This username is already is use";
			//die("This username is already in use");
		}

		// Set query params for sql call
		$query_params = array(
				':email' => $_POST['email']
		);

		// Check if email address has already been used
		$stmt = checkemail($db, $query_params);

		$row = $stmt->fetch();
	
		if($row){
			//die("This email address is already registered");
			$error = 1;
			$errEmail= "This email address is already registered";
		}

		//prepare the password for encrytion
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	
		$password = hash('sha256', $_POST['password'] . $salt);
	
		for($round = 0; $round < 65536; $round++){
			$password = hash('sha256', $password . $salt);
		}
	
		if(!$error){
			$query_params = array(
				':firstname' => $_POST['firstname'],
				':surname' => $_POST['surname'],
				':username' => $_POST['username'],
				':password' => $password,
				':salt' => $salt,
				':email' => $_POST['email'],
				':emailshow' => $_POST['emailshow']);

		// All ok, so lets add the user to the database
		insertuser($db, $query_params);

		////////////////////////////////////////////////////////////
		// Set variables for email send
		$firstname = $_POST['firstname'];
		$surname = $_POST['surname'];
		$username = $_POST['username'];
		$email = $_POST['email'];

		//Email admin account details to approve
		$mail = new PHPMailer();
		$mail->IsHTML(true);

		$mail->From     = "chrisbussell@gmail.com";
		$mail->AddAddress("chrisbussell@gmail.com");

		$mail->Subject  = "CragLogger Account Approval Required";
		$mail->Body     = "Hi Admin, <p> The following account has been registered on CragLogger and needs you to approve it.<p> Name:<b>$firstname $surname</b><br>Username: <b>$username</b><br>Email:<b>$email</b><p> To approve please click <a href='cgi.chrisbussell.plus.com/craglogger/dashboard/approveaccount.php'>here</a><p>Thanks<br>The CragLogger Team.";
		$mail->WordWrap = 50;

		if(!$mail->Send()) {
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent.';
		}

		//Email new signup welcome details
		$mail = new PHPMailer();
		$mail->IsHTML(true);

		$mail->From     = "chrisbussell@gmail.com";
		$mail->AddAddress("$email");

		$mail->Subject  = "Tuesday Nighters Account Signup";
		$mail->Body     = "Hi $firstname, <p> Thank you for signing up to Tuesday Nighters CragLogger.<br> Your account has been created and is waiting for approval.  You will shortly get an email confirming that your account has been approved.<p>Once approved you will be able to log which crags you have attended over the Tuesday Nighters Season of 2014.<p>Thank you<br>The CragLogger Team.";
		$mail->WordWrap = 50;

		if(!$mail->Send()) {
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent.';
			header("Location: /craglogger/approval.php");
			die("Redirecting to approval.php");
		}
	}
	else
	{
		echo $template->render(array (
			'updated' => '14 Feb 2014',
			'errPassword' => $errPassword,
			'errFirstname' => $errFirstname,
			'errSurname' => $errSurname,
			'errEmail' => $errEmail,
			'errUsername' => $errUsername,
			'pageTitle' => 'Register',
			'php_self' =>$_SERVER['PHP_SELF']));
	}
	}

	// set template variables
	// render template
	echo $template->render(array (
		'updated' => '14 Feb 2014',
		'pageTitle' => 'Register',
		'php_self' =>$_SERVER['PHP_SELF']
	));
