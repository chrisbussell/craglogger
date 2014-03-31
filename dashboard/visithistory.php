<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];
	if(!isset($_GET['year']))
	{
		$_GET['year'] = '';
	}

	if(empty($_SESSION['user']))
	{	
		header("Location: /craglogger/login.php");
		die("Redirecting to login/login.php");
	}

	// include and register Twig auto-loader
	include '../Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
	$loader = new Twig_Loader_Filesystem('../templates');

	// initialize Twig environment
	$twig = new Twig_Environment($loader);

	// load template
	$template = $twig->loadTemplate('dashboard/visithistory.tmpl');

	$stmt = getvisithistoryyear($db);

	while ($row = $stmt->fetchObject()) {
                $years[] = $row;
        }

	//Get list of crags available this year
	$query_params = array(
		':year' => $_GET['year']
	);

	//Get list of crags
	$stmt = getcragdata($db, $query_params);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}

	// GET MEMBERS LIST	
	$membersresults = getattended($db, $query_params);
	$membersrows = $membersresults->fetchAll();

	 // GET LIST OF ATTENDED CRAGS BY USERS
	$results = getattended($db, $query_params);

	$rows = $results->fetchAll();

	$date = date('Y-m-d H:i:s');

		// set template variables
		// render template
		echo $template->render(array (
			'data' => $data,
			'years' => $years,
			'sid' => $_SESSION['user'],
			'updated' => $lastupdated,
			'date' => $date,
			'attended' => $rows,
			'php_self' =>$_SERVER['PHP_SELF'],
			'username' =>$_SESSION['user']['username'],
			'admin' =>$_SESSION['user']['admin'],
			'pageTitle' => 'Crag Attendance 2014',
			'member' =>$membersrows,
			'firstname' =>$_SESSION['user']['firstname']
		));
?>
