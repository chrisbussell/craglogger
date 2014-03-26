<?php

	// Execute our common code to connection to the database and start the session
	require("../includes/common.php");

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
		$template = $twig->loadTemplate('admin/admin.tmpl');

		// set template variables
		// render template
		echo $template->render(array (
			'sid' => $_SESSION['user'],
			'admin' => $_SESSION['user']['admin'],
			'updated' => '14 Feb 2014',
			'username' =>$_SESSION['user']['username'],
			'firstname' =>$_SESSION['user']['firstname'],
			'pageTitle' => 'Admin'
		));
	}
	else {
		die("Sorry you don't have permission to view this page");
	}
?>
