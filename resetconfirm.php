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
	$template = $twig->loadTemplate('resetconfirm.tmpl');

	$passed = '';
	$passcodefail = '';
	
	if(isset($_POST['submit'])){

		// Ensure that the user has entered a non-empty password
		if(empty($_POST['password'])){
			echo "Please enter a password.";
		}
		
		// If the user entered a new password, hash it and generate a fresh salt
		if(!empty($_POST['password'])){
			$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
			$password = hash('sha256', $_POST['password'] . $salt);

			for($round = 0; $round < 65536; $round++){
				$password = hash('sha256', $password . $salt);
			}
		}
		else{
			// If the user did not enter a new password not update their old one.
			$password = null;
			$salt = null;
		}

		// Initial query parameter values
		$query_params = array(
			':email' => $_POST['email'],
		);

		// If the user is changing their password, then we need parameter values
		// for the new password hash and salt too.
		if($password !== null){
			$query_params[':password'] = $password;
			$query_params[':salt'] = $salt;
		}

		//update member password with new values
		$passed = updatememberpassword($db, $query_params);

		if($passed == true)
		{
			$linky = "http://ccgi.chrisbussell.plus.com/craglogger/login.php";
			//Email admin account details to approve
			$mail = new PHPMailer();
			$mail->IsHTML(true);

			$mail->From     = "chrisbussell@gmail.com";
			$mail->AddAddress($_POST['email']);

			$mail->Subject  = "Tuesday Nighters password changed";
			$mail->Body     = "Hi, <p> You have changed your password for Tuesday Nighers Craglogger.<p> To login please click $linky<p>Thanks<br>The Craglogger Team.";
			$mail->WordWrap = 50;

			if(!$mail->Send()) {
				echo 'Message was not sent.';
				echo 'Mailer error: ' . $mail->ErrorInfo;
			} else {
				echo 'Message has been sent.';
			}
		}
	}
	else{
		// Set query params for sql call
		$query_params = array(
			':email' => $_GET['email'],
			':expiry' => $_GET['expiry'],
			':code' => $_GET['code']
		);

		// First we check if the code and email match what we have in the DB
		$stmt = checkpasswordcode($db, $query_params);

		$row = $stmt->fetch();

		// If we get a result then everything matches and we can continue
		if(!$row){
			// set error variable to true for template to handle
			$passcodefail = 1;
		}
	}
	
	// set template variables
	// render template
	echo $template->render(array (
		'code' => $_GET['code'],
		'email' => $_GET['email'],
		'passed' => $passed,
		'passcodefail' => $passcodefail,
		'updated' => $lastupdated,
		'pageTitle' => 'Confirm Reset Password',
		'php_self' =>$_SERVER['PHP_SELF'],
	));
		
?>
