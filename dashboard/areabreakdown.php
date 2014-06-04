<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];
	$noarea = '';
	$datebreakdown = '';
	$dates = '';
	$venuearea = '';
	
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
	$template = $twig->loadTemplate('dashboard/areabreakdown.tmpl');

	// Get summary of all time visits data - Visit Stats
	$stmt = getalltimesummary($db);
	$allsummary = $stmt->fetchAll();

	//Set venue location for sql
	$query_params = array(
		':venue' => $_GET['venue']
	);

	$stmt = getareabreakdown($db, $query_params);
	$areabreakdown = $stmt->fetchAll();

	if (empty($areabreakdown['0']['area']))
	{
		$stmt = getdatebyvenue($db, $query_params);
		$dates = $stmt->fetchAll();
		$noarea = 1;
	}

	if (!empty($_GET['break1']))
	{
		if (!empty($_GET['crag']))
		{
		//Get list of crags available this year
		$query_params = array(
			':venue' => $_GET['venue'],
			':area' => $_GET['area'],
			':crag' => $_GET['crag']);
		}
		else
		{
			$query_params = array(
			':venue' => $_GET['venue'],
			':area' => $_GET['area']);
		}

		$stmt = get3rdlevel($db, $query_params);
		$venuearea = $stmt->fetchAll();
		$datebreakdown = 1;
	}

	if (!empty($_GET['break']))
	{
		//Get list of crags available this year
		$query_params = array(
			':venue' => $_GET['venue'],
			':area' => $_GET['area']);
	
		$stmt = getdatebyvenuearea($db, $query_params);
		$venuearea = $stmt->fetchAll();
		$datebreakdown = 1;	
	}
	
		// set template variables
		// render template
		echo $template->render(array (
			'pageTitle' => 'All Time Crag Stats',
			'dates' => $dates,
			'venuearea' => $venuearea,
			'noarea' => $noarea,
			'venue' => $_GET['venue'],
			'area' => $_GET['area'],
			'datebreakdown' => $datebreakdown,
			'allsummary' => $allsummary,
			'areabreakdown' => $areabreakdown,
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'updated' => $lastupdated,
			'php_self' =>$_SERVER['PHP_SELF'],
			'username' =>$_SESSION['user']['username'],
			'firstname' =>$_SESSION['user']['firstname']
		));
?>