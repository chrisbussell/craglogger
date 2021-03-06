<?php
	require("../includes/common.php");
	require("../includes/functions.php");
	require("../includes/PHPMailer/class.phpmailer.php");

	$user_id = $_SESSION['user']['user_id'];

	if(empty($_SESSION['user'])){	
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
	$template = $twig->loadTemplate('admin/approveaccount.tmpl');

	// Set account to approved & send approval email
	if(isset($_POST['submit']))
	{
		// Initial query parameter values
		$query_params = array(
				':user_id' => $_POST['user_id'],
				':admin' => 0,
				':approved' => 1);

		// Update userconfig set user as approved
		updateuserconfig($db, $query_params);

		// Set variables for email send
		$firstname = $_POST['firstname'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];

		//Email admin account details to approve
		$mail = new PHPMailer();
		$mail->IsHTML(true);

		$mail->From     = $emailaddress; //domain from address -> common.php
		$mail->FromName = "Craglogger Team";
		$mail->AddAddress("$email"); //recepitants email
		$mail->AddCC ("");
		$mail->AddBCC ("$bccaddress");
		$mail->Subject  = "Tuesday Nighters Account Approved";
		$mail->Body     = "Hi $firstname, <p> Your Tuesday Nighters Craglogger account has been approved.  You can now start logging your crag visits for this season.<p>Your account details are:<br>Name:<b>$firstname $surname</b><br>Email:<b>$email</b><p> Click <a href='http://www.chrisbussell.co.uk/craglogger/login.php'>here</a> to <b>log in</b> and get started<p>Thanks<br>The Craglogger Team.";
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

	if(isset($_POST['submitupdate']))
	{
		// Initial query parameter values
		$query_params = array(
			':user_id' => $_POST['user_id'],
			':admin' => $_POST['admin'],
			':approved' => $_POST['approved'],
		);

		//Update userconfig
		updateuserconfig($db, $query_params);
	}

	//////////////////////////////////////////////////////////////////////
	// Get user details to display on screen

	//Get full list of user accounts, all status
	$getall = getalluserdetails($db, $query_params = null);	

	//Put returned data in $fulldata array
	while ($row = $getall->fetchObject()){
		$fulldata[] = $row;
	}
	
	// Get list of users that need to be Approved
	$stmt = getuserbyoption($db, $getapproved=0, $getvirtual=0, $flag=0);	

	//Put returned data in $data array
	while ($row = $stmt->fetchObject()){
		$needapproval[] = $row;
	}

	// Get list of Virtual Users
	$stmt = getuserbyoption($db, $getapproved=0, $getvirtual=1, $flag=0);	

	//Put returned data in $virtualmembers array
	while ($row = $stmt->fetchObject()){
		$virtualmembers[] = $row;
	}

	// set template variables
	// render template
	echo $template->render(array (
		'needapproval' => $needapproval,
		'virtualmember' => $virtualmembers,
		'fulldata' => $fulldata,
		'sid' => $_SESSION['user'],
		'admin' => $_SESSION['user']['admin'],
		'updated' => $lastupdated,
		'php_self' =>$_SERVER['PHP_SELF'],
		'pageTitle' => 'Approve Accounts',
		'firstname' =>$_SESSION['user']['firstname']
	));
?>
