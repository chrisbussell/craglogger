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
	$template = $twig->loadTemplate('admin/cragupdate.tmpl');

	// Update Crag Detail Data
	if(isset($_POST['submit'])){
		// Initial query parameter values
		$query_params = array(
			':cragdetail_id' => $_POST['cragdetail_id'],
			':venue' => $_POST['venue'],
			':area' => $_POST['area'],
			':crag' => $_POST['crag'],
			':rock' => $_POST['rock'],
			':country' => $_POST['country'],
			':county' => $_POST['county'],
			':altitude' => $_POST['altitude'],
			':faces' => $_POST['faces'],
			':lat' => $_POST['lat'],
			':lng' => $_POST['lng'],
			':web' => $_POST['web']
		);

		// update crag details
		updatecragdetails($db, $query_params);
	}

	// get all crag details
	$stmt = getcragdetail($db);

	while ($row = $stmt->fetchObject()){
		$data[] = $row;
	}

	$date = date('Y-m-d H:i:s');

	// set template variables
	// render template
	echo $template->render(array (
		'data' => $data,
		'sid' => $_SESSION['user'],
		'admin' => $_SESSION['user']['admin'],
		'updated' => $lastupdated,
		'date' => $date,
		'php_self' =>$_SERVER['PHP_SELF'],
		'pageTitle' => 'Crag Update',
		'username' =>$_SESSION['user']['username'],
		'firstname' =>$_SESSION['user']['firstname']
	));
?>
