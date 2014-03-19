<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];

    	if(empty($_SESSION['user']))
    	{	
        	header("Location: ../login.php");
        	die("Redirecting to login.php");
    	}

	// include and register Twig auto-loader
	include '../Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
        $loader = new Twig_Loader_Filesystem('../templates');

        // initialize Twig environment
        $twig = new Twig_Environment($loader);

        // load template
        $template = $twig->loadTemplate('cragdetail.tmpl');
	
	$query_params = array(
                ':crag_id' => $_GET['crag_id']
                );

	$stmt = getcragdata($db, $query_params);

	while ($row = $stmt->fetchObject()) 
	{
		$data[] = $row;
	}

	$results = getattendendbycragid($db, $query_params);

	while ($row = $results->fetchObject()) 
	{
		$visiteddata[] = $row;
	}

	$date = date('Y-m-d H:i:s');

  	// set template variables
  	// render template
  	echo $template->render(array (
    	'data' => $data,
    	'visiteddata' => $visiteddata,
    	'sid' => $_SESSION['user'],
    	'user_id' => $_SESSION['user']['user_id'],
    	'admin' => $_SESSION['user']['admin'],
	'updated' => '14 Feb 2014',
	'date' => $date,
	'php_self' =>$_SERVER['PHP_SELF'],
	'username' =>$_SESSION['user']['username']
  ));
?>