<?php
    	// First we execute our common code to connection to the database and start the session
    	require("../includes/common.php");
    	require("../includes/functions.php");

    	// At the top of the page we check to see whether the user is logged in or not
    	if(empty($_SESSION['user']))
    	{
        	header("Location: ../login.php");
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
        $template = $twig->loadTemplate('memberlist.tmpl');
   
	//Get all approved accounts 
	$stmt = getaccounts($db, $getapproved=1);
      
    	// Finally, we can retrieve all of the found rows into an array using fetchAll
    	$rows = $stmt->fetchAll();

	// set template variables
        // render template
        echo $template->render(array (
	'members' => $rows,
	'username' => $_SESSION['user']['username'],
	'admin' => $_SESSION['user']['admin'],
	'sid' => $_SESSION['user']));
?>
