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
	$dates = '';

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
	$template = $twig->loadTemplate('dashboard/mystats.tmpl');

	if(!isset($_GET['dates'])){
		$_GET['dates'] = '';
	}

	if(!isset($_GET['cragdetail_id'])){
		$_GET['cragdetail_id'] = '';
	}

	// set params to be used for function calls
	$query_params = array(':user_id' => $user_id);

	// get count of user attendence
	$stmt = getcragvisitsbyuser($db, $query_params);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}

	//get number of weeks of summer left
	$results = weeksleftofsummer($db);
	
	$numWeeks = $results['0'];
	$summertime = $results['1'];

	$stmt = getvisithistoryyear($db);
	$totalyears = $stmt->fetchAll();

	$stmt = gettotalvisitsbyuser($db, $query_params);
	$totalvisits = $stmt->fetchAll();

	$stmt = gettotalvisitsbymonthbyuser($db, $query_params);
	$monthvisits = $stmt->fetchAll();

	// set params to be used for function calls
	$query_params = array(
						':user_id' => $user_id,
						':cragdetail_id' => $_GET['cragdetail_id']);

	$stmt = getcragdatevisits($db, $query_params);
	$visitdates = $stmt->fetchAll();

	// set template variables
	// render template
	echo $template->render(array (
			'pageTitle' => 'My Stats',
			'php_self' =>$_SERVER['PHP_SELF'],
			'updated' => $lastupdated,
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'firstname' =>$_SESSION['user']['firstname'],
			'user_id' =>$_SESSION['user']['user_id'],
			'dates' =>$_GET['dates'],
			'data' => $data,
			'totalvisits' => $totalvisits,
			'monthvisits' => $monthvisits,
			'visitdates' => $visitdates,
			'years' => $years,
			'weeksleft' => $numWeeks,
			'summertime' => $summertime
	));
?>
