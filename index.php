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
	
	$cragreport = '';

	$results = getnextcrag($db);
	$nextcrag = $results->fetch();

	$query_params = array(
                        ':date' => $nextcrag['date']);

	// Build array of details for map points
	$locations = array();

	$locations[] = array(
      $nextcrag['venue'],
      $nextcrag['area'], 
      $nextcrag['crag'],
      $nextcrag['rock'],
      $nextcrag['date'],
      $nextcrag['lat'], 
      $nextcrag['lng'],
      $nextcrag['cragvisit_id']);

	//get sunset time for this visit (ie date)
	$stmt = getsunsettime($db, $query_params);
	$sunset = $stmt->fetch();

	$stmt = getmoonphase($db, $query_params);
	$moonphase = $stmt->fetch();

	$stmt = getlatestcragreport($db);
	$data = $stmt->fetchAll();

	if (!empty($data)){
		$cragreport = nl2br($data['0']['cragreport']);
	}

	$template->display(array(
		'pageTitle' => 'Home',
		'updated' => $lastupdated,
		'sid' => $_SESSION['user'],
		'username' => $_SESSION['user']['username'],
		'admin' => $_SESSION['user']['admin'],
		'firstname' => $_SESSION['user']['firstname'],
		'cragvisit_id' => $nextcrag['cragvisit_id'],
		'date' => $nextcrag['date'],
		'venue' => $nextcrag['venue'],
		'area' => $nextcrag['area'],
		'crag' => $nextcrag['crag'],
		'event' => $nextcrag['event'],
		'sunset' => $sunset['sunsettime'],
		'moonphase' => $moonphase['phase'],
		'mooncoverage' => $moonphase['coverage'],
		'data' => $data,
		'locations' => $locations,
		'cragreport' => $cragreport
	));

?>

