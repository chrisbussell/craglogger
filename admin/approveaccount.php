<?php
	require("../includes/common.php");
	require("../includes/functions.php");
	require("../includes/PHPMailer/class.phpmailer.php");

	$user_id = $_SESSION['user']['user_id'];

	if(empty($_SESSION['user']))
	{	
		header("Location: ../login.php");
		die("Redirecting to login.php");
	}

	// Check if user has admin perms
	if($_SESSION['user']['admin'] == 0){
		header("Location: craglist.php");
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
	$template = $twig->loadTemplate('admin/approveaccount.tmpl');

	// Set account to approved & send approval email
	if(isset($_POST['submit'])){
	// Initial query parameter values
		$query_params = array(
				':user_id' => $_POST['user_id'],
				':admin' => 0,
				':approved' => 1,
		);

		updateaccounts($db, $query_params);

		// Set variables for email send
		$firstname = $_POST['firstname'];
		$surname = $_POST['surname'];
		$username = $_POST['username'];
		$email = $_POST['email'];

		//Email admin account details to approve
		$mail = new PHPMailer();
		$mail->IsHTML(true);

		$mail->From     = "chrisbussell@gmail.com";
		$mail->AddAddress("$email");

		$mail->Subject  = "Tuesday Nighters Account Approved";
		$mail->Body     = "Hi $firstname, <p> Your Tuesday Nighters CragLogger account has been approved and you can start logging your crag visits for this season.<p>Your account details are:<br>Name:<b>$firstname $surname</b><br>Username: <b>$username</b><br>Email:<b>$email</b><p> Click <a href='ccgi.chrisbussell.plus.com/craglogger/login.php'>here</a> to start log in and get started<p>Thanks<br>The CragLogger Team.";
		$mail->WordWrap = 50;

		if(!$mail->Send()) {
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent.';
			header("Location: /craglogger/admin/approveaccount.php");
	//   	die("Redirecting to approval.php");
		}
	}

	if(isset($_POST['submitupdate'])){
		// Initial query parameter values
		$query_params = array(
			':user_id' => $_POST['user_id'],
			':admin' => $_POST['admin'],
			':approved' => $_POST['approved'],
		);

		//Update accounts based on set query_params
		updateaccounts($db, $query_params);
	}
	
	// Get list of member account which need to be approved
	$stmt = getaccounts($db, $getapproved=0);	

	//Put returned data in $data array
	while ($row = $stmt->fetchObject()){
		$data[] = $row;
	}

	//Get full list of member accounts
	$getall = getaccountsall($db, $query_params = null);	

	//Put returned data in $fulldata array
	while ($row = $getall->fetchObject()){
		$fulldata[] = $row;
	}

	$date = date('Y-m-d H:i:s');

	// set template variables
	// render template
	echo $template->render(array (
		'data' => $data,
		'fulldata' => $fulldata,
		'sid' => $_SESSION['user'],
		'admin' => $_SESSION['user']['admin'],
		'updated' => $lastupdated,
		'date' => $date,
		'php_self' =>$_SERVER['PHP_SELF'],
		'pageTitle' => 'Approve Accounts',
		'username' =>$_SESSION['user']['username'],
		'firstname' =>$_SESSION['user']['firstname']
	));
?>
