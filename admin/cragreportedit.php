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
	$template = $twig->loadTemplate('admin/cragreportedit.tmpl');
	
	$nocragselected = '';
	$success = '';
	$nodate = '';
/*
	if(!isset($_GET['cragvisit_id']))
	{
		$_GET['cragvisit_id'] = '';
	}
*/
	// At the top of the page we check to see whether the user is logged in or not
	if(empty($_SESSION['user'])){
		// If they are not, we redirect them to the login page.
		header("Location: /craglogger/login.php");
		die("Redirecting to login.php");
	}

	// does the member have admin perms if not kick them back out
	if($_SESSION['user']['admin'] == 0){
		header("Location: craglist.php");
		die("Redirecting to craglist.php");
	}
	else{
		$query_params = array(
			':cragvisit_id' => $_GET['cragvisit_id']
		);

		// get some information about the crag
		$stmt = getcragdata($db, $query_params);
		$result = $stmt -> fetch();

		$stmt = getcragreport($db, $query_params); 
		$cragreport = $stmt -> fetch();
	}

	if(isset($_POST['submit'])){

		if(!empty($_POST)){ 
			$query_params = array(
				':cragvisit_id' => $_POST['cragvisit_id'],
				':cragreport' => $_POST['report']
				);
			// Insert crag data
			$success = updatecragreport($db, $query_params);

			$query_params = array(
				':cragvisit_id' => $_POST['cragvisit_id']
				);

			$stmt = getcragreport($db, $query_params); 
			$cragreport = $stmt -> fetch();

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
		'cragreport' => $cragreport['cragreport'],
		'admin' => $_SESSION['user']['admin'],
		'updated' => $lastupdated,
		'php_self' =>$_SERVER['PHP_SELF'],
		'success' =>$success,
		'username' =>$_SESSION['user']['username'],
		'firstname' =>$_SESSION['user']['firstname']
	));
	
?>
