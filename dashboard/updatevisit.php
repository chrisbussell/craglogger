<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];

    	if(empty($_SESSION['user']))
    	{	
        	header("Location: ../login.php");
        	die("Redirecting to login.php");
    	}
	
	if($_SESSION['user']['admin'] == 0)
   	{
        	header("Location: craglist.php");
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
        $template = $twig->loadTemplate('updatevisit.tmpl');

	// Update Crag Detail Data
	if(isset($_POST['submit']))
	{
		// Initial query parameter values
        	$query_params = array(
            	':cragvisit_id' => $_POST['cragvisit_id'],
            	':conditions' => $_POST['conditions'],
            	':date' => $_POST['date'],
            	':rainedoff' => $_POST['rainedoff'],
            	':pub' => $_POST['pub'],
        	);

		// update crag details
		updatecragvisit($db, $query_params);
	}

	// get all crag details
	$stmt = getcragdata($db, $query_params = null);

        while ($row = $stmt->fetchObject())
        {
                $data[] = $row;
        }

	$date = date('Y-m-d H:i:s');

  	// set template variables
  	// render template
  	echo $template->render(array (
    	'data' => $data,
    	'sid' => $_SESSION['user'],
    	'admin' => $_SESSION['user']['admin'],
	'updated' => '14 Feb 2014',
	'date' => $date,
	'php_self' =>$_SERVER['PHP_SELF'],
	'username' =>$_SESSION['user']['username']
  ));
?>
