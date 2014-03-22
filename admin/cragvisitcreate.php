<?php

	// Execute our common code to connection to the database and start the session
	require("../includes/common.php");
	require("../includes/functions.php");

	// include and register Twig auto-loader
	include '../Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
	$loader = new Twig_Loader_Filesystem('../templates');

	// initialize Twig environment
	$twig = new Twig_Environment($loader);

	// load template
	$template = $twig->loadTemplate('admin/cragvisitcreate.tmpl');
	
	$nocragselected = '';
	$success = '';
	$nodate = '';

	// At the top of the page we check to see whether the user is logged in or not
	if(empty($_SESSION['user'])){
		// If they are not, we redirect them to the login page.
		header("Location: /craglogger/login.php");
		die("Redirecting to login.php");
	}

	// does the member have admin perms
	if($_SESSION['user']['admin'] == 0){
		header("Location: craglist.php");
		die("Redirecting to craglist.php");
	}
	else{
		// get list of available crags
		$stmt = getcragdetail($db);

		while ($row = $stmt->fetchObject()) {
			$data[] = $row;
		}

		//	foreach ($stmt as $cragdetail)
		//	{
		//			$dropdown = '<option value="'.$cragdetail['id'].'"';
		//			$dropdown .= '>'.$cragdetail['venue'].' - '. $cragdetail['area'] . '</option>'."\n";
		//			echo $dropdown;
		//	}
	if(isset($_POST['submit'])){

			//check if crag selected
			if(!isset($_POST['cragdetail_id'])){
				$nocragselected = 1;
			}

			// check date format
			$date = $_POST['date'];
			list($y, $m, $d) = explode('-', $date);

			if(checkdate($m, $d, $y)){
				$nodate = 0;
			}
			else{
				$nodate = 1;
			}

			if(!empty($_POST)){ 
				$query_params = array(
					':cragdetail_id' => $_POST['cragdetail_id'],
					':date' => $_POST['date'],
					':conditions' => $_POST['conditions'],
					':pub' => $_POST['pub'],
					':rainedoff' => $_POST['rainedoff']
				);
			// Insert crag data
				$success = insertcragvisit($db, $query_params);
			}
		}
	}
	// set template variables
	// render template
	echo $template->render(array (
		'sid' => $_SESSION['user'],
		'admin' => $_SESSION['user']['admin'],
		'updated' => '14 Feb 2014',
		'php_self' =>$_SERVER['PHP_SELF'],
		'data' =>$data,
		'nocragselected' =>$nocragselected,
		'nodate' =>$nodate,
		'success' =>$success,
		'username' =>$_SESSION['user']['username'],
		'firstname' =>$_SESSION['user']['firstname']
	));
	
?>
