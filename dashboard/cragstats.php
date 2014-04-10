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
	$template = $twig->loadTemplate('dashboard/cragstats.tmpl');

	$query_params = array(
                        ':year' => $_GET['year']);

	// get count of user attendence
	$stmt = getuserattendence($db, $query_params);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}


	// get top attended crag
	$stmt = gettopattendedcrag($db, $query_params);

	while ($row = $stmt->fetchObject()) {
		$attendedcrag[] = $row;
	}


	// get total rained off
        $stmt = gettotalrainedoff($db, $query_params);

        while ($row = $stmt->fetchObject()) {
                $rainedoff[] = $row;
        }

	$stmt = getrocktotals($db, $query_params);

	while ($row = $stmt->fetchObject()) {
                $rocktypes[] = $row;
        }

	if ($_GET['year'] == '2014')
	{
		//get number of weeks of summer left
		$numWeeks = weeksleftofsummer();
	}

	$stmt = getvisithistoryyear($db);

        while ($row = $stmt->fetchObject()) {
                $years[] = $row;
        }
	
	$date = date('Y-m-d H:i:s');

		// set template variables
		// render template
		echo $template->render(array (
			'data' => $data,
			'attendedcrag' => $attendedcrag,
			'year' => $_GET['year'],
			'years' => $years,
			'rainedoff' => $rainedoff,
			'rocktype' => $rocktypes,
			'weeksleft' => $numWeeks,
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'updated' => $lastupdated,
			'date' => $date,
			'php_self' =>$_SERVER['PHP_SELF'],
			'crag_visited' =>$cragvisited,
			'pageTitle' => 'Crag Stats 2014',
			'username' =>$_SESSION['user']['username'],
			'firstname' =>$_SESSION['user']['firstname']
		));
?>
