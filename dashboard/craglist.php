<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$user_id = $_SESSION['user']['user_id'];
	$cragvisited = '';

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
        $template = $twig->loadTemplate('craglist.tmpl');

	// Insert crag attended data
	if(isset($_POST['submit'])){

		if(isset($_POST['crag'])){
			$name = $_POST['crag'];

        		foreach ($name as $names=>$value) {
				// if user attended insert row
				insertattendeddata($db, $user_id, $value);
        		}
			$cragvisited = "Thanks, your attendance has been logged and appreciated";
		}
	}


	if(isset($_GET['undo']) == true)
	{
		$query_params = array(
                ':user_id' => $_SESSION['user']['user_id'],
                ':crag_id' => $_GET['crag_id']
                );

		//remove db entry for this attdence by user
		removeattdence($db, $query_params);
	}

	// get list of all crags this year
	$stmt = getcragdata($db, $query_params = null);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}
	
	// set query params for user attended data
	$query_params = array(
                ':user_id' => $_SESSION['user']['user_id']
                );

	$results = getuserattended($db, $query_params);

	$rows = $results->fetchAll();

	$date = date('Y-m-d H:i:s');

  	// set template variables
  	// render template
  	echo $template->render(array (
    	'data' => $data,
    	'sid' => $_SESSION['user'],
    	'admin' => $_SESSION['user']['admin'],
	'updated' => '14 Feb 2014',
	'date' => $date,
	'attended' => $rows,
	'php_self' =>$_SERVER['PHP_SELF'],
	'crag_visited' =>$cragvisited,
	'username' =>$_SESSION['user']['username']
  	));
?>
