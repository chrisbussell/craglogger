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
	$template = $twig->loadTemplate('dashboard/mystats.tmpl');

	$query_params = array(
                        ':user_id' => $user_id);

	// get count of user attendence
	$stmt = getcragvisitsbyuser($db, $query_params);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}

	//get number of weeks of summer left
	$numWeeks = weeksleftofsummer();

	$stmt = getvisithistoryyear($db);

        while ($row = $stmt->fetchObject()) {
                $years[] = $row;
        }

	$stmt = gettotalvisitsbyuser($db, $query_params);
	$totalvisits = $stmt->fetchAll();


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
			'data' => $data,
			'totalvisits' => $totalvisits,
			'years' => $years,
			'weeksleft' => $numWeeks,
		));
?>
