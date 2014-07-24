<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$years = '';
	$data = '';
	$showlink = '';
	$showreport1 = '';
	$tag = '';
	$chosenyear = '';
	$viewyear = '';
	$year = '';

	if(!isset($_GET['showreport'])){
		$_GET['showreport'] = '';
	}

	$user_id = $_SESSION['user']['user_id'];

	if(empty($_GET['year'])){
		$_GET['year'] = '';
	}

	if(empty($_GET['month'])){
		$_GET['month'] = '';
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
	$template = $twig->loadTemplate('dashboard/visitarchive.tmpl');

	// Get summary of all time visits data - Visit Stats
	$stmt = getalltimesummary($db);
	$allsummary = $stmt->fetchAll();

	// get list of years we have data for, displayed as dropdown
	$stmt = getvisithistoryyear($db);
	$years = $stmt->fetchAll();

	if(!empty($_POST))
	{
		$chosenyear = $_POST['year'];
	}
	elseif(!empty($_GET))
	{
		$chosenyear = $_GET['year'];
	}

	//Get list of crags available this year
	$query_params = array(
		':year' => $chosenyear
	);

	// Get chosen years Summary stats - 
	$stmt = getyearstats($db, $query_params);
	$yearstats = $stmt->fetchAll();

	$stmt = getendtermreport($db, $query_params);
	$termreport = $stmt -> fetch();

	if(!empty($termreport)){

		$showlink = '1';
		$showreport1 = '1';
		$tag = 'View';
	}

	if($_GET['showreport'] == 1){
		$tag = 'Hide';
		$showreport1 = '0';
	}

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
		':year' => $chosenyear
	);

	$result = mappingdetails($db, $query_params);
	$mapdetails = $result->fetchAll();

	$locations = array();

	foreach ($mapdetails as $mapdetail) {
    $locations[] = array(
      $mapdetail['venue'],
   //   $mapdetail['area'], 
   //   $mapdetail['crag'],
      $mapdetail['rock'],
      $mapdetail['date'],
      $mapdetail['lat'], 
      $mapdetail['lng'],
      $mapdetail['cragvisit_id']
      );
	}

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
			'viewyear' => $chosenyear,
			'viewmonth' => $_GET['month'],
			'showreport' => $_GET['showreport'],
			'showreport1' => $showreport1,
			'monthname' => $selectmonth,
			'data' => $data,
			'yearstats' => $yearstats,
			'showlink' => $showlink,
			'termreport' => $termreport['report'],
			'allsummary' => $allsummary,
			'tag' => $tag,
			'years' => $years,
			'months' => $months,
			'attended' => $rows,
			'locations' => $locations,
			'member' =>$membersrows
	));
?>
