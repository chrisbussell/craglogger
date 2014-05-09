<?php
	// Execute common code
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
        $template = $twig->loadTemplate('login.tmpl');

	$submitted_username = '';
	$errApproved = '';
	$errFailed = '';

	// Check if form has been submitted
	if(!empty($_POST)){

		$username = test_input($_POST["username"]);

		// Set query params for sql call
                $query_params = array(
			':username' => $username
			);

		// get member account details
		$stmt = getaccountsall($db, $query_params);

		// Set login to false, variable set to true on succesful login
		$login_ok = false;

		// Retrieve the user data from the database.  If $row is false, then the username
		// they entered is not registered.
		$row = $stmt->fetch();
			
		if($row){
			// Ensure that the user has entered a non-empty password
			if(empty($_POST['password'])){
				$errFailed = '1';//"login failed, please check the details you have entered";
			}
			else{
				// Get and check password submited by comparing it to hashed version stored in db.
				$check_password = hash('sha256', $_POST['password'] . $row['salt']);

				for($round = 0; $round < 65536; $round++){
					$check_password = hash('sha256', $check_password . $row['salt']);
				}

				// Check is account is approved for login
				if($row['approved'] === '1'){
					if($check_password === $row['password']){
						// If they do, then we flip this to true
						$login_ok = true;
					}
					else{
						$errFailed = '1';//"login failed, please check the details you have entered";
					}
				}
				else{
					$errApproved = "Sorry your account has not been approved, please try again later"; 
				}
			}
		}
		else
		{
			$errFailed = '1';//"login failed, please check the details you have entered";

		}

		if($errFailed == '1'){

			$errFailed = "login failed, please check the details you have entered";		
		}
		
		// If user logged in succesfully, send customer to main craglist.php page else show login failed.
		if($login_ok){
			// Removing any sensitive data from $row
			unset($row['salt']);
			unset($row['password']);
	
			// Store the user's data into the session at the index 'user'.
			$_SESSION['user'] = $row;
	
			$user_id = test_input($_SESSION["user"]["user_id"]);

			// Set query params for sql call
                $query_params = array(
			':user_id' => $user_id
			);

			//log login to db
			insertlastlogin($db, $query_params);


			// Redirect the user to the members-only page craglist.php.
			header("Location: /craglogger/dashboard/craglist.php");
			die("Redirecting to: craglist.php");
		}
		else{
	
			// Show them their username again so all they have to do is enter a new password. 
			$submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
		}
	}

	// set template variables
	// render template
	echo $template->render(array (
		'pageTitle' => 'Login',
		'updated' => $lastupdated,
		'php_self' =>$_SERVER['PHP_SELF'],
		'submitted_username' => $submitted_username,
		'errApproved' => $errApproved,
		'errFailed' => $errFailed
	));
	
?>
