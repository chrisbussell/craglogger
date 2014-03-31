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
	$template = $twig->loadTemplate('index.tmpl');

	$results = getnextcrag($db);
	$nextcrag = $results->fetch();

	$query_params = array(
                        ':date' => $nextcrag['date']);

	//get sunset time for this visit (ie date)
	$stmt = getsunsettime($db, $query_params);
	$sunset = $stmt->fetch();

	$stmt = getlatestcragreport($db);
	$data = $stmt->fetchAll();

	$template->display(array(
		'updated' => $lastupdated,
		'sid' => isset($_SESSION['user']),
		'pageTitle' => 'Home',
		'cragvisit_id' => $nextcrag['cragvisit_id'],
		'date' => $nextcrag['date'],
		'venue' => $nextcrag['venue'],
		'area' => $nextcrag['area'],
		'event' => $nextcrag['event'],
		'sunset' => $sunset['sunsettime'],
		'data' => $data,
		'username' =>$_SESSION['user']['username'],
		'admin' => $_SESSION['user']['admin'],
		'firstname' =>$_SESSION['user']['firstname']
	));

?>

