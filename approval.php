<?php
	// Execute common code to connection to the database and start the session
	require("includes/common.php");

	// include and register Twig auto-loader
	include 'Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
	$loader = new Twig_Loader_Filesystem('templates');

	// initialize Twig environment
	$twig = new Twig_Environment($loader);

	// load template
	$template = $twig->loadTemplate('approval.tmpl');

	$template->display(array(
		'pageTitle' => 'Approval',
		'updated' => $lastupdated,
		'sid' => isset($_SESSION['user']),
		'username' => isset($_SESSION['user']['username'])
	));

?>
