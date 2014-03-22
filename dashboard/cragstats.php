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

	// get list of all crags this year
	$stmt = getcragdata($db, $query_params = null);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}
	
	// set query params for user attended data
	$query_params = array(
		':user_id' => $_SESSION['user']['user_id']
	);

	$results = getuserattended($db, $query_params);

	$rows = $results->fetchAll();

	$date = date('Y-m-d H:i:s');

		// set template variables
		// render template
		echo $template->render(array (
			'data' => $data,
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'updated' => '14 Feb 2014',
			'date' => $date,
			'attended' => $rows,
			'php_self' =>$_SERVER['PHP_SELF'],
			'crag_visited' =>$cragvisited,
			'pageTitle' => 'Crag Stats 2014',
			'username' =>$_SESSION['user']['username'],
			'firstname' =>$_SESSION['user']['firstname']
		));
?>
