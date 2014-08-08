<?php

	// Execute our common code to connection to the database and start the session
	require("../includes/common.php");
	require("../includes/functions.php");

	// At the top of the page we check to see whether the user is logged in or not
	if(empty($_SESSION['user'])) {
		// If they are not, we redirect them to the login page.
		header("Location: login.php");

		// Remember that this die statement is absolutely critical.  Without it,
		// people can view your members-only content without logging in.
		die("Redirecting to login.php");
	}

	if($_SESSION['user']['admin']== 1) {
		// include and register Twig auto-loader
		include '../Twig/Autoloader.php';
		Twig_Autoloader::register();

		// define template directory location
		$loader = new Twig_Loader_Filesystem('../templates');

		// initialize Twig environment
		$twig = new Twig_Environment($loader);

		// load template
		$template = $twig->loadTemplate('admin/lastlogin.tmpl');

		// Initial query parameter values
		$query_params = array(
				':user_id' => $_SESSION['user_id']
		);

		$stmt = getlastlogin($db, $query_params);

		//Put returned data in $data array
		while ($row = $stmt->fetchObject()){
			$lastlogin[] = $row;
		}

		// set template variables
		// render template
		echo $template->render(array (
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'updated' => $lastupdated,
			'username' =>$_SESSION['user']['username'],
			'firstname' =>$_SESSION['user']['firstname'],
			'lastlogin' => $lastlogin,
			'pageTitle' => 'Admin: Last Login'
		));
	}
	else {
		die("Sorry you don't have permission to view this page");
	}
?>
