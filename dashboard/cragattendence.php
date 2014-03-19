<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];

    	if(empty($_SESSION['user']))
    	{	
        	header("Location: ../login.php");
        	die("Redirecting to login/login.php");
    	}

	// include and register Twig auto-loader
	include '../Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
        $loader = new Twig_Loader_Filesystem('../templates');

        // initialize Twig environment
        $twig = new Twig_Environment($loader);

        // load template
        $template = $twig->loadTemplate('cragattendence.tmpl');

	//Get list of crags
	$stmt = getcragdata($db, $query_params = null);

	while ($row = $stmt->fetchObject()) 
	{
		$data[] = $row;
	}

	// GET MEMBERS LIST	
	$membersresults = getaccounts($db, $getapproved = 1);
        $membersrows = $membersresults->fetchAll();

	 // GET LIST OF ATTENDED CRAGS BY USERS
	$results = getattended($db);
        $rows = $results->fetchAll();

	$date = date('Y-m-d H:i:s');

  	// set template variables
  	// render template
  	echo $template->render(array (
    	'data' => $data,
    	'sid' => $_SESSION['user'],
	'updated' => '14 Feb 2014',
	'date' => $date,
	'attended' => $rows,
	'php_self' =>$_SERVER['PHP_SELF'],
	'username' =>$_SESSION['user']['username'],
	'admin' =>$_SESSION['user']['admin'],
	'member' =>$membersrows
  ));
?>