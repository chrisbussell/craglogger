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
	$rainedoffdetail = '';

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
	$template = $twig->loadTemplate('dashboard/alltimestats.tmpl');

	// Get summary of all time visits data - Visit Stats
	$stmt = getalltimesummary($db);
	$allsummary = $stmt->fetchAll();

	// get count of user attendence
	$stmt = getuserattendencealltime($db);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}
	//Get list of crags available this year
	$query_params = array(
		':year' => $_GET['year']
	);

	// get top attended crag
	$stmt = gettopattendedcragalltime($db);

	while ($row = $stmt->fetchObject()) {
		$attendedcrag[] = $row;
	}

	// Rock Tyes
	$stmt = getrocktotalsalltime($db);

	while ($row = $stmt->fetchObject()) {
                $rocktypes[] = $row;
        }

        // Countys visited
	$stmt = getcountyalltime($db);

        while ($row = $stmt->fetchObject()) {
                $counties[] = $row;
        }

        // Get rained off crags
	$stmt = getrainedoffdetailalltime($db);
		while ($row = $stmt->fetchObject()) {
                $rainedoffdetail[] = $row;
        }

		// set template variables
		// render template
		echo $template->render(array (
			'pageTitle' => 'All Time Crag Stats',
			'data' => $data,
			'attendedcrag' => $attendedcrag,
			'allsummary' => $allsummary,
			'rainedoff' => $rainedoff,
			'yearstats' => $yearstats,
			'counties' => $counties,
			'rocktype' => $rocktypes,
			'weeksleft' => $numWeeks,
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'updated' => $lastupdated,
			'rainedoffdetail' => $rainedoffdetail,
			'date' => $date,
			'php_self' =>$_SERVER['PHP_SELF'],
			'crag_visited' =>$cragvisited,
			'username' =>$_SESSION['user']['username'],
			'firstname' =>$_SESSION['user']['firstname']
		));
?>
