<?php
	require("../includes/common.php");
	require("../includes/functions.php");
	//require("../includes/PHPMailer/class.phpmailer.php");

	$user_id = $_SESSION['user']['user_id'];

	if(empty($_SESSION['user']))
	{
		header("Location: ../login.php");
		die("Redirecting to login.php");
	}

	// Check if user has admin perms
        if($_SESSION['user']['admin'] == 0){
                header("Location: /craglogger/dashboard/craglist.php");
                die("Redirecting to login.php");
        }

	// include and register Twig auto-loader
        include '../Twig/Autoloader.php';
        Twig_Autoloader::register();

        // define template directory location
        $loader = new Twig_Loader_Filesystem('../templates');

        // initialize Twig environment
        $twig = new Twig_Environment($loader);

        // load template
        $template = $twig->loadTemplate('admin/createvirtualuser.tmpl');

	$error = '';
	$errFirstname = '';
	$errSurname = '';
	$errEmail = '';
	$success = '';
		
	if(!empty($_POST)){
		// Ensure that the user has entered a non-empty firstname
		if(empty($_POST['firstname'])){
			$error = 1;
			$errFirstname= "Please enter a firstname";
		}

		// Ensure that the user has entered a non-empty surname
		//if(empty($_POST['surname'])){
		//	$error = 1;
		//	$errSurname= "Please enter a surname";
		//}

		if(!empty($_POST['password']))
		{
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				$error = 1;
				$errEmail= "Invalid E-Mail Address";
			}

			// Set query params for sql call
			$query_params = array(
					':email' => $_POST['email']
					);

			// Check if email address has already been used
			$stmt = checkemail($db, $query_params);

			$row = $stmt->fetch();
	
			if($row){
				$error = 1;
				$errEmail= "This email address is already registered";
			}
		}

		// Add a fake email
		if(empty($_POST['email'])){

			$rand = rand(1,100);
			
			$_POST['email'] = $_POST['firstname'].$rand.$_POST['surname'].$rand; 
		}
	
		if(!$error){
			$query_params = array(
				':firstname' => $_POST['firstname'],
				':surname' => $_POST['surname'],
				':password' => md5(rand(10,999)),
				':salt' => '',
				':email' => $_POST['email']);

			// Add new user signup to the database ready for approval
			$user_id = insertuser($db, $query_params);
	
			// Prepare variables for userconfig insert				
			$query_params = array(
						':user_id' => $user_id,
						':emailshow' => '0',
						':usertype_id' => '2');

			// Insert user config data
			insertuserconfig($db, $query_params);

			if (!empty($_POST['nickname'])){

			//$stmt = getlastsignup($db);
			//$result = $stmt->fetch();

			$query_params = array(
				':user_id' => $user_id,
				'nickname' => $_POST['nickname']);
			
			insertusernickname($db, $query_params);
		}	
		$success = 1;		
	}
		else{
			echo $template->render(array ( 
                	'pageTitle' => 'Create Virtual Member',
                        'php_self' => $_SERVER['PHP_SELF'],
                        'errFirstname' => $errFirstname,
                        'errSurname' => $errSurname,
                        'errEmail' => $errEmail));
                        die();
		}

	}

	// set template variables
        // render template
        echo $template->render(array (
                'pageTitle' => 'Create Virtual Member',
                'updated' => $lastupdated,
                'php_self' =>$_SERVER['PHP_SELF'],
                'sid' => $_SESSION['user'],
                'admin' => $_SESSION['user']['admin'],
                'firstname' =>$_SESSION['user']['firstname'],
                'success' =>$success
        ));
