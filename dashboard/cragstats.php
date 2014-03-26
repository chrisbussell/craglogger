<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];
	$cragvisited = '';

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

	// get count of user attendence
	$stmt = getuserattendence($db, $query_params = null);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}

	// get top attended crag
	$stmt = gettopattendedcrag($db, $query_params = null);

	while ($row = $stmt->fetchObject()) {
		$attendedcrag[] = $row;
	}

	// get total rained off
        $stmt = gettotalrainedoff($db, $query_params = null);

        while ($row = $stmt->fetchObject()) {
                $rainedoff[] = $row;
        }

	$date = date('Y-m-d H:i:s');

		// set template variables
		// render template
		echo $template->render(array (
			'data' => $data,
			'attendedcrag' => $attendedcrag,
			'rainedoff' => $rainedoff,
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
