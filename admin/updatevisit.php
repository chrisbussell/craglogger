<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];

	if(empty($_SESSION['user'])){	
		header("Location: /craglogger/login.php");
		die("Redirecting to login.php");
	}

	// Check if user has admin perms
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
	$template = $twig->loadTemplate('admin/updatevisit.tmpl');

	// get list of years we have data for, displayed as dropdown
	$stmt = getvisithistoryyear($db);
	$years = $stmt->fetchAll();

	if(!empty($_POST))
	{
		$chosenyear = $_POST['year'];
	}
	else
	{
		$chosenyear = '2015';
	}

	// Update Crag Detail Data
	if(isset($_POST['update'])){
	
		if ($_POST['rainedoff'] != 1){
			$_POST['rainedoff'] = 0;
		}

		// Initial query parameter values
		$query_params = array(
			':cragvisit_id' => $_POST['cragvisit_id'],
			':conditions' => $_POST['conditions'],
			':date' => $_POST['date'],
			':event' => $_POST['event'],
			':rainedoff' => $_POST['rainedoff'],
			':pub' => $_POST['pub'],
		);

		// update crag details
		updatecragvisit($db, $query_params);

		$chosenyear = '2015';
	}

	$query_params = array(
			':year' => $chosenyear);

	// get all crag details
	$stmt = getcragdata($db, $query_params);

	while ($row = $stmt->fetchObject()){
		$data[] = $row;
	}

	if (empty($data)){
		$data = '';
	}

	$stmt = getcragreport($db, $query_params = null);

        while ($row = $stmt->fetchObject()){
                $cragreport[] = $row;
        }

	if (empty($cragreport)){
		$cragreport = '';
	}

		// set template variables
		// render template
		echo $template->render(array (
			'data' => $data,
			'cragreport' => $cragreport,
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'updated' => $lastupdated,
			'php_self' =>$_SERVER['PHP_SELF'],
			'pageTitle' => 'Update visit',
			'years' => $years,
			'viewyear' => $chosenyear,
			'firstname' =>$_SESSION['user']['firstname']
		));
?>
