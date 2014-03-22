<?php
	// First we execute our common code to connection to the database and start the session
	require("includes/common.php");

	// include and register Twig auto-loader
	include 'Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
	$loader = new Twig_Loader_Filesystem('templates');

	// initialize Twig environment
	$twig = new Twig_Environment($loader);

	// load template
	$template = $twig->loadTemplate('index.tmpl');

	$template->display(array(
		'updated' => '14 Feb 2014',
		'sid' => isset($_SESSION['user']),
		'pageTitle' => 'Home',
		'username' =>$_SESSION['user']['username'],
		'admin' => $_SESSION['user']['admin'],
		'firstname' =>$_SESSION['user']['firstname']
	));

?>
