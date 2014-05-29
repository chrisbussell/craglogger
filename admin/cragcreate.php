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
	$template = $twig->loadTemplate('admin/cragcreate.tmpl');

	// At the top of the page we check to see whether the user is logged in or not
	if(empty($_SESSION['user'])){
		// If they are not, we redirect them to the login page.
		header("Location: /craglogger/login.php");

		// Remember that this die statement is absolutely critical.  Without it,
		// people can view your members-only content without logging in.
		die("Redirecting to login.php");
	}

	// Check if user has admin perms
        if($_SESSION['user']['admin'] == 0){
                header("Location: /craglogger/dashboard/craglist.php");
                die("Redirecting to login.php");
        }

	
	if(!empty($_POST)){ 
		$query_params = array(
			':venue' => $_POST['venue'],
			':area' => $_POST['area'],
			':crag' => $_POST['crag'],
			':rock' => $_POST['rock'],
			':country' => $_POST['country'],
			':county' => $_POST['county'],
			':altitude' => $_POST['altitude'],
			':faces' => $_POST['faces'],
			':web' => $_POST['web'],
			':lat' => $_POST['lat'],
			':lng' => $_POST['lng']
		);

		// Insert crag data
		insertcragdata($db, $query_params);
		
		header("Location: ../admin/admin.php");
		die("Redirecting to login.php");
	}

	// set template variables
	// render template
	echo $template->render(array (
		'sid' => $_SESSION['user'],
		'admin' => $_SESSION['user']['admin'],
		'updated' => $lastupdated,
		'php_self' =>$_SERVER['PHP_SELF'],
		'username' =>$_SESSION['user']['username'],
		'pageTitle' => 'Add new crag',
		'firstname' =>$_SESSION['user']['firstname']
	));
	
?>
