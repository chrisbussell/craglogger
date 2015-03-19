<?php
	require("../includes/common.php");
	require("../includes/functions.php");

	$data = '';
	$cragvisited = '';

	if(empty($_SESSION['user']))
	{	
		header("Location: /craglogger/login.php");
		die("Redirecting to login/login.php");
	}

	// Check if user has admin perms
	if($_SESSION['user']['admin'] == 0){
		header("Location: /craglogger/dashboard/craglist.php");
        die("Redirecting to login.php");
    }
    
	$user_id = $_SESSION['user']['user_id'];

	// include and register Twig auto-loader
	include '../Twig/Autoloader.php';
	Twig_Autoloader::register();

	// define template directory location
	$loader = new Twig_Loader_Filesystem('../templates');

	// initialize Twig environment
	$twig = new Twig_Environment($loader);

	// load template
	$template = $twig->loadTemplate('admin/logmemberattendence.tmpl');

	// Insert crag attended data
	if(isset($_POST['submit'])){
		if(isset($_POST['visited'])){
			$name = $_POST['visited'];
			$cragvisit_id = $_POST['cragvisit_id'];

			foreach ($name as $names=>$user_id) {
				// if user attended insert row
				insertattendeddata($db, $user_id, $cragvisit_id);
			}

			$cragvisited = "Thanks, attendance for these members has been logged.";

			$_GET['cragvisit_id'] = $_POST['cragvisit_id'];
		}
	}

	if(isset($_GET['undo']) == true){
		$query_params = array(':user_id' => $_GET['user_id'],
                        ':cragvisit_id' => $_GET['cragvisit_id']);

		//remove db entry for this attdence by user
		removeattdence($db, $query_params);
   	}

	//Get list of crags available this year
	$query_params = array(
		':cragvisit_id' => $_GET['cragvisit_id']
	);

	//Get list of crags
	$stmt = getcragdata($db, $query_params);

	while ($row = $stmt->fetchObject()) {
		$data[] = $row;
	}

	// GET MEMBERS LIST	
	//$membersresults = getuserbyoption($db, $getapproved = 1, $getvirtual=1, $flag=1);

	$membersresults = usersbyalltimevisits($db);

	$membersrows = $membersresults->fetchAll();

	$results = getattendendbycragid($db, $query_params);
	$visiteddata = $results->fetchAll();


	// set template variables
	// render template
	echo $template->render(array (
		'pageTitle' => 'Add Member Visits',
		'updated' => $lastupdated,
		'php_self' =>$_SERVER['PHP_SELF'],
		'sid' => $_SESSION['user'],
		'admin' =>$_SESSION['user']['admin'],
		'firstname' =>$_SESSION['user']['firstname'],
		'data' => $data,
		'attended' => $visiteddata,
		'member' => $membersrows,
		'crag_visited' =>$cragvisited
	));
?>
