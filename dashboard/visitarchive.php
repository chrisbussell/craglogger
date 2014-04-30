<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$years = '';
	$data = '';

	$user_id = $_SESSION['user']['user_id'];
	if(!isset($_GET['year'])){
		$_GET['year'] = '';
	}

	if(!isset($_GET['month'])){
		$_GET['month'] = '';
	}

	if(empty($_SESSION['user']))
	{	
		header("Location: /craglogger/login.php");
		die("Redirecting to login/login.php");
	}

	// Check if user has admin perms - will remove this section on live release
        if($_SESSION['user']['admin'] == 0){
                header("Location: /craglogger/dashboard/craglist.php");
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
	$template = $twig->loadTemplate('dashboard/visitarchive.tmpl');

	$stmt = getalltimesummary($db);
	$allsummary = $stmt->fetchAll();

	$stmt = getvisithistoryyear($db);

	while ($row = $stmt->fetchObject()) {
                $years[] = $row;
        }

	//Get list of crags available this year
	$query_params = array(
		':year' => $_GET['year']
	);

	$stmt = getendtermreport($db, $query_params);
	$termreport = $stmt -> fetch();
	print_r($termreport);

	//Get list of months for this years visits
	$stmt = getvisitmonths($db, $query_params);
	$months = $stmt->fetchAll();

	if(isset($_GET['month'])){
		$query_params = array(
			':year' => $_GET['year'],
			':month' => $_GET['month']
		);
	}
	else{
		$query_params = array(
			':year' => $_GET['year']
		);
	}

	//Get list of crags for given year and month
	$stmt = getcragdata($db, $query_params);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}
		$query_params = array(
			':year' => $_GET['year']
		);

	// GET MEMBERS LIST	
	$membersresults = getmembersattended($db, $query_params);
        $membersrows = $membersresults->fetchAll();

	 // GET LIST OF ATTENDED CRAGS BY USERS
	$results = getattended($db, $query_params);
	$rows = $results->fetchAll();


	$selectedmonth = $_GET['month'];
	$selectmonth = getmonth($selectedmonth);


		// set template variables
		// render template
		echo $template->render(array (
			'pageTitle' => 'Visit Archive',
			'php_self' =>$_SERVER['PHP_SELF'],
			'updated' => $lastupdated,
			'sid' => $_SESSION['user'],
			'username' =>$_SESSION['user']['username'],
			'admin' =>$_SESSION['user']['admin'],
			'firstname' =>$_SESSION['user']['firstname'],
			'viewyear' => $_GET['year'],
			'viewmonth' => $_GET['month'],
			'showreport' => $_GET['showreport'],
			'monthname' => $selectmonth,
			'data' => $data,
			'termreport' => $termreport['report'],
			'allsummary' => $allsummary,
			'years' => $years,
			'months' => $months,
			'attended' => $rows,
			'member' =>$membersrows
		));
?>
