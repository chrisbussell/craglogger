<?php
	// Execute common code
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
	$template = $twig->loadTemplate('reset.tmpl');

	$passed = '';
	$email = '';
	$error = '';
	
	if(!empty($_POST))
	{
		// Set query params for sql call
		$query_params = array(
			':email' => $_POST['email']
		);

		// Check if email address has already been used
		$stmt = checkemail($db, $query_params);

		$row = $stmt->fetch();

		if($row)
		{
			$code= md5(rand(100,999));

			$email = $_POST['email'];
			
			$now = date('Y-m-d');

			// set password expiry day to now +24 hours
			$expiry = date('Y-m-d', strtotime('+24 hours'));

			// Set query params for sql call
			$query_params = array(
				':email' => $email,
				':code' => $code,
				':expiry' => $expiry
			);

			//Add code and expiry to db
			$passed = updatepasswordreset($db, $query_params);

			$encodeemail = urlencode($email);

			//echo"YES WE KNOW THIS USER, SEND ACTIVATION CODE";
			$resetlink = "http://ccgi.chrisbussell.plus.com/craglogger/resetconfirm.php?email=$encodeemail&code=$code&expiry=$expiry";
			
			//Email admin account details to approve
			$mail = new PHPMailer();
			$mail->IsHTML(true);

			$mail->From     = "chrisbussell@gmail.com";
			$mail->AddAddress("$email");

			$mail->Subject  = "Tuesday Nighters password reset";
			$mail->Body     = "Hi, <p>Your password reset link is $resetlink <p>Thanks<br>The Craglogger Team.";
			$mail->WordWrap = 50;

			if(!$mail->Send()) {
				echo 'Message was not sent.';
				echo 'Mailer error: ' . $mail->ErrorInfo;
			} else {
				//	echo 'Message has been sent.';
			}
		}
		else{
			$error = "Sorry we don't have that email address registered";
		}
	}


	// set template variables
	// render template
	echo $template->render(array (
		'updated' => $lastupdated,
		'passed' => $passed,
		'error' => $error,
		'email' => $email,
		'pageTitle' => 'Reset Password',
		'php_self' =>$_SERVER['PHP_SELF'],
	));
		
?>
