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
	$template = $twig->loadTemplate('dashboard/cragstats.tmpl');

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

	$query_params = array(
                        ':year' => $chosenyear);

	$stmt = getcragbyyear($db,$query_params);
	$visitedcrags = $stmt->fetchAll();

	$stmt = getyearstats($db, $query_params);
	$yearstats = $stmt->fetchAll();

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

	if ($chosenyear == '2015')
	{
		//get number of weeks of summer left
		$numWeeks = weeksleftofsummer();
	}

	//$stmt = getvisithistoryyear($db);

      //  while ($row = $stmt->fetchObject()) {
        //        $years[] = $row;
        //}

	$stmt = getcountytotals($db, $query_params);

        while ($row = $stmt->fetchObject()) {
                $counties[] = $row;
        }

	$stmt = getrainedoffdetail($db, $query_params);
		while ($row = $stmt->fetchObject()) {
                $rainedoffdetail[] = $row;
        }

	$date = date('Y-m-d H:i:s');

		// set template variables
		// render template
		echo $template->render(array (
			'pageTitle' => 'Crag Stats 2015',
			'data' => $data,
			'attendedcrag' => $attendedcrag,
			'year' => $chosenyear,
			'years' => $years,
			'rainedoff' => $rainedoff,
			'yearstats' => $yearstats,
			'counties' => $counties,
			'rocktype' => $rocktypes,
			'weeksleft' => $numWeeks,
			'visitedcrags' => $visitedcrags,
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'updated' => $lastupdated,
			'rainedoffdetail' => $rainedoffdetail,
			'date' => $date,
			'php_self' =>$_SERVER['PHP_SELF'],
			'crag_visited' =>$cragvisited,
			'firstname' =>$_SESSION['user']['firstname']
		));
?>
