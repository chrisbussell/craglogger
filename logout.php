<?php

	// Execute common code to connection to the database and start the session
	require("includes/common.php");

	// We remove the user's data from the session
	unset($_SESSION['user']);

	// We redirect them to the login page
	header("Location: index.php");
	die("Redirecting to: index.php"); 
