<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$data = '';

	$user_id = $_SESSION['user']['user_id'];

	//set year as now if no other year has been passed
	if(!isset($_GET['year']))
	{
		$_GET['year'] = '2015';
	}

	if(!isset($_GET['month']))
	{
		$month = date('n');
		$_GET['month'] = $month;
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
	$template = $twig->loadTemplate('dashboard/cragattendence.tmpl');

	//Get list of crags available this year
	$query_params = array(
		':year' => $_GET['year']
	);

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

	//Get list of crags
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

	 // GET LIST OF ATTENDED CRAGS BY USERS BY YEAR
	$results = getattended($db, $query_params);

	$rows = $results->fetchAll();

	$selectedmonth = $_GET['month'];
        $selectmonth = getmonth($selectedmonth);

	$date = date('Y-m-d H:i:s');

		// set template variables
		// render template
		echo $template->render(array (
			'pageTitle' => 'Crag Attendance 2015',
			'data' => $data,
			'sid' => $_SESSION['user'],
			'updated' => $lastupdated,
			'date' => $date,
			'attended' => $rows,
			'viewyear' => $_GET['year'],
			'php_self' =>$_SERVER['PHP_SELF'],
			'admin' =>$_SESSION['user']['admin'],
			'member' =>$membersrows,
			'months' =>$months,
			'viewmonth' =>$selectmonth,
			'firstname' =>$_SESSION['user']['firstname']
		));
?>
