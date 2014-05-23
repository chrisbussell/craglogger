<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];
	$cragvisited = '';
	$numWeeks = '';
	$data = '';
	$attendedcrag = '';
	$years = '';
	$rocktypes = '';

	if(empty($_SESSION['user']))
	{	
		header("Location: /craglogger/login.php");
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
	$template = $twig->loadTemplate('dashboard/myvisits.tmpl');

	// set params to be used for function calls
	$query_params = array(
						':user_id' => $user_id,
						':year' => $_GET['year']);

	$stmt = getcragsbyyearbyuser($db, $query_params);
	$visitbyyear = $stmt->fetchAll();

	// set template variables
	// render template
	echo $template->render(array (
			'pageTitle' => 'My Stats',
			'php_self' =>$_SERVER['PHP_SELF'],
			'updated' => $lastupdated,
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'username' =>$_SESSION['user']['username'],
			'firstname' =>$_SESSION['user']['firstname'],
			'user_id' =>$_SESSION['user']['user_id'],
			'data' => $data,
			'visitbyyear' => $visitbyyear,
			'monthvisits' => $monthvisits,
			'years' => $years,
			'blah' => $_GET['blah'],
			'weeksleft' => $numWeeks,
	));
?>