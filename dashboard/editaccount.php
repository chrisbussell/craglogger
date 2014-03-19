<?php

	// Execute common code to connection to the database and start the session
    	require("../includes/common.php");
    	require("../includes/functions.php");

	// include and register Twig auto-loader
        include '../Twig/Autoloader.php';
        Twig_Autoloader::register();

        // define template directory location
        $loader = new Twig_Loader_Filesystem('../templates');

        // initialize Twig environment
        $twig = new Twig_Environment($loader);

        // load template
        $template = $twig->loadTemplate('editaccount.tmpl');

    	// Check if user logged in or not
    	if(empty($_SESSION['user'])){
        	// If they are not, redirect them to the login page.
        	header("Location: login.php");
        
        	die("Redirecting to login.php");
    	}	

	// Define our query parameter values
	$query_params = array(
        	':user_id' => $_SESSION['user']['user_id']);

	$stmt = getaccountsall($db, $username = null, $query_params);	

	$rows = $stmt->fetchAll();

	// Has edit been submited
    	if(!empty($_POST))
    	{
		if($_POST['firstname'] == null){
            		die("Invalid firstname");
		}	

		if($_POST['surname'] == null){
            		die("Invalid surname");
		}		

        	// Make sure the user entered a valid E-Mail address
        	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            		die("Invalid E-Mail Address");
        	}
        
		// check if email being updated has already been used
        	if($_POST['email'] != $_SESSION['user']['email']){
            		// Define our query parameter values
            		$query_params = array(
                		':email' => $_POST['email']
            		);
			
			// check if email has been used before
			$stmt = checkemail($db, $query_params);
            
            		// Retrieve results (if any)
            		$row = $stmt->fetch();
            		if($row)
            		{
                		die("This E-Mail address is already in use");
            		}
        	}
        
        // If the user entered a new password, hash it and generate a fresh salt
        if(!empty($_POST['password']))
        {
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
            $password = hash('sha256', $_POST['password'] . $salt);
            for($round = 0; $round < 65536; $round++){
                $password = hash('sha256', $password . $salt);
            }
        }
        else
        {
            // If the user did not enter a new password not update their old one.
            $password = null;
            $salt = null;
        }
        
        // Initial query parameter values
        $query_params = array(
            ':email' => $_POST['email'],
            ':user_id' => $_SESSION['user']['user_id'],
            ':firstname' => $_POST['firstname'],
            ':surname' => $_POST['surname'],
        );
        
        // If the user is changing their password, then we need parameter values
        // for the new password hash and salt too.
        if($password !== null)
        {
            $query_params[':password'] = $password;
            $query_params[':salt'] = $salt;
        }
        
        $query = "
            UPDATE users
            SET
		firstname = :firstname,
		surname = :surname,
                email = :email
        ";
        
        if($password !== null)
        {
            $query .= "
                , password = :password
                , salt = :salt
            ";
        }
        
        $query .= "
            WHERE
                user_id = :user_id
        ";
        
        try
        {
            // Execute the query
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
        
        // Now that the user's E-Mail address has changed, the data stored in the $_SESSION
        // array is stale; we need to update it so that it is accurate.
        $_SESSION['user']['email'] = $_POST['email'];
        
        // This redirects the user back to the edit page after submit
        header("Location: editaccount.php");
        
        die("Redirecting to editaccount.php");
    }

	// set template variables
        // render template
        echo $template->render(array (
        'sid' => $_SESSION['user'],
        'admin' => $_SESSION['user']['admin'],
        'updated' => '14 Feb 2014',
        'php_self' =>$_SERVER['PHP_SELF'],
        'username' =>$_SESSION['user']['username'],
        'firstname' =>$rows['0']['firstname'],
        'surname' =>$rows['0']['surname'],
        'email' =>$_SESSION['user']['email']
  ));

    
?>
