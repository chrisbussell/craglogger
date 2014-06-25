<?php
	// First we execute our common code to connection to the database and start the session
	require("includes/common.php");
	require("includes/functions.php");

	// include and register Twig auto-loader
	include 'Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
	$loader = new Twig_Loader_Filesystem('templates');

	// initialize Twig environment
	$twig = new Twig_Environment($loader);

	// load template
	$template = $twig->loadTemplate('therules.tmpl');
	

	$template->display(array(
		'pageTitle' => 'The Rules',
		'updated' => $lastupdated,
		'sid' => $_SESSION['user'],
		'username' => $_SESSION['user']['username'],
		'admin' => $_SESSION['user']['admin'],
		'firstname' => $_SESSION['user']['firstname']
	));

?>

