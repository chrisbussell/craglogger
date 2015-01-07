<?php
	// First we execute our common code to connection to the database and start the session
	require("../includes/common.php");
	require("../includes/functions.php");

	// At the top of the page we check to see whether the user is logged in or not
	if(empty($_SESSION['user']))
	{
		header("Location: /craglogger/login.php");
		die("Redirecting to login.php");
	}	

	// Everything below this point in the file is secured by the login system

	// include and register Twig auto-loader
	include '../Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
	$loader = new Twig_Loader_Filesystem('../templates');

	// initialize Twig environment
	$twig = new Twig_Environment($loader);

	// load template
	$template = $twig->loadTemplate('dashboard/memberlist.tmpl');

	//Get FULL members approved accounts 
	$stmt = getuserbyoption($db, $getapproved=1, $getvirtual=0, $flag=0);
			
	// Finally, we can retrieve all of the found rows into an array using fetchAll
	$rows = $stmt->fetchAll();

	//Get Virtual members approved accounts 
	$stmt = getuserbyoption($db, $getapproved=0, $getvirtual=1, $flag=1);
			
	// Finally, we can retrieve all of the found rows into an array using fetchAll
	$virtual = $stmt->fetchAll();

	$totalmembers = array_merge($rows, $virtual);

	// set template variables
	// render template
	echo $template->render(array (
		'pageTitle' => 'Members',
		'updated' => $lastupdated,
		'members' => $rows,
		'virtual' => $virtual,
		'totalmembers' => $totalmembers,
		'firstname' =>$_SESSION['user']['firstname'],
		'admin' => $_SESSION['user']['admin'],
		'sid' => $_SESSION['user'])
	);
?>
