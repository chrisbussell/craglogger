<?php

	// Execute our common code to connection to the database and start the session
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
	$template = $twig->loadTemplate('admin/termreportadd.tmpl');
	
	$nocragselected = '';
	$success = '';
	$nodate = '';

	// At the top of the page we check to see whether the user is logged in or not
	if(empty($_SESSION['user'])){
		// If they are not, we redirect them to the login page.
		header("Location: /craglogger/login.php");
		die("Redirecting to login.php");
	}

	// Check if user has admin perms
	if($_SESSION['user']['admin'] == 0){
		header("Location: /craglogger/dashboard/craglist.php");
		die("Redirecting to login.php");
	}
	print_r($_SESSION);
	
	// Get list of years available	
	$stmt = getvisithistoryyear($db);
	while ($row = $stmt->fetchObject()) {
		$years[] = $row;
	}

	// Get report for chosen year
	$query_params = array(
		':year' => $_GET['viewyear']
	);

	$stmt = getendtermreport($db, $query_params);
	$termreport = $stmt -> fetch();

	if(!empty($termreport)){
		$success = 1;
	}

	if(isset($_POST['submit'])){

		if(!empty($_POST)){ 
			$query_params = array(
				':year' => $_POST['viewyear'],
				':report' => $_POST['report']
				);

			// Insert term report
			$success = insertendoftermreport($db, $query_params);

			$query_params = array(
					':year' => $_POST['viewyear']
					);

			$stmt = getendtermreport($db, $query_params);
			$termreport = $stmt -> fetch();
		}
	}

	if(isset($_POST['update'])){

		if(!empty($_POST)){ 
			$query_params = array(
				':year' => $_POST['viewyear'],
				':report' => $_POST['report']
				);

			// Insert crag data
			$success = updatetermreport($db, $query_params);
			echo"success = $success";
			print_r($success);

			$query_params = array(
				':year' => $_POST['viewyear']
				);

			$stmt = getendtermreport($db, $query_params);
			$termreport = $stmt -> fetch();

			}
		}

	
	// set template variables
	// render template
	echo $template->render(array (
		'sid' => $_SESSION['user'],
		'cragvisit_id' => $result['cragvisit_id'],
		'venue' => $result['venue'],
		'area' => $result['area'],
		'date' => $result['date'],
		'years' => $years,
		'viewyear' => $_GET['viewyear'],
		'termreport' => $termreport['report'],
		'admin' => $_SESSION['user']['admin'],
		'updated' => $lastupdated,
		'php_self' =>$_SERVER['PHP_SELF'],
		'success' =>$success,
		'username' =>$_SESSION['user']['username'],
		'firstname' =>$_SESSION['user']['firstname']
	));
	
?>
