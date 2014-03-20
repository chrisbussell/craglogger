<?php
	function clean_string($string) 
	{
		$bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
        }

	function checkpasswordcode($db, $query_params)
	{
		$query = "SELECT 1 FROM users WHERE email = :email AND activation_code = :code";

		try{
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                        die("Failed to run query: " . $ex->getMessage());
                }
                return $stmt;
	}

	//update member pasword
	function updatememberpassword($db, $query_params)
	{
		$query = "
                        UPDATE users
                        SET
                        password = :password,
                        salt = :salt
                        WHERE
                        email = :email";
                try{
                        // Execute the query
                        $stmt = $db->prepare($query);
                        $result = $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                        die("Failed to run query: " . $ex->getMessage());
                }
	return true;
	}
	
	
	//update user password reset activation code
	function updatepasswordreset($db, $query_params)
	{
		$query = "UPDATE users SET activation_code = :code WHERE email = :email";

		try{
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                        die("Failed to run query: " . $ex->getMessage());
                }
                return true;
	
	}

	// Get all member accounts if approved or not
	function getaccounts($db, $getapproved)
        {
        	// Get Accounts that need approving List Data
        	$query = "
                	SELECT
                	user_id,
			username,
			password,
			salt,
                	firstname,
                	surname,
                	username,
                	email,
                	admin,
                	approved
                	FROM users";
		if($getapproved == 0){
			$query .="
				WHERE approved = 0
                	";
		}
		elseif($getapproved == 1){
			$query .="
				WHERE approved = 1
                	";
		}
		
        	try{
                	$stmt = $db->prepare($query);
                	$stmt->execute();
        	}
        	catch(PDOException $ex){
                	die("Failed to run query: " . $ex->getMessage());
        	}
        	return $stmt;
        }

	// Get all member accounts
	function getaccountsall($db, $username, $query_params)
        {
                $query = "
                        SELECT
                        user_id,
			username,
			password,
			salt,
                        firstname,
                        surname,
                        username,
                        email,
                        admin,
                        approved
                        FROM users";
		if(isset($username)){
			$query .="
				WHERE username = '$username'
                	";
		}
		if(isset($query_params)){
			$query .="
				WHERE user_id = :user_id
                	";
		}
                try{
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                        die("Failed to run query: " . $ex->getMessage());
                }
                return $stmt;
        }

	// Update member account set admin / approved status
	function updateaccounts($db, $query_params)
	{
		$update="
                        UPDATE users SET
                        admin = :admin,
                        approved = :approved
                        WHERE user_id = :user_id
                        ";
		try
               	{
                	$stmt = $db->prepare($update);
                       	$stmt->execute($query_params);
                }
                catch(PDOException $ex)
                {
                	die("Failed to run query: " . $ex->getMessage());
                }
	}

	function checkusername($db,$query_params)
	{
		$query = "
            	SELECT
                1
            	FROM users
            	WHERE
                username = :username
       		";

        	try{
            		$stmt = $db->prepare($query);
            		$result = $stmt->execute($query_params);
        	}
        	catch(PDOException $ex){
            		die("Failed to run query: " . $ex->getMessage());
        	}
                return $stmt;
	}

	function checkemail($db, $query_params)
	{
		$query = "
           	 SELECT
                1
            	FROM users
            	WHERE
                email = :email";
        	
		try{
            		$stmt = $db->prepare($query);
            		$result = $stmt->execute($query_params);
        	}
        	catch(PDOException $ex){
            		die("Failed to run query: " . $ex->getMessage());
        	}
		return $stmt;
	}

	function insertuser($db, $query_params)
	{
		$query = "
            INSERT INTO users (
                firstname,
                surname,
                username,
                password,
                salt,
                email
            ) VALUES (
                :firstname,
                :surname,
                :username,
                :password,
                :salt,
                :email
            )
        	";

        	try{
            		// Execute the query to create the user
            		$stmt = $db->prepare($query);
            		$result = $stmt->execute($query_params);
        	}
        	catch(PDOException $ex){
            		die("Failed to run query: " . $ex->getMessage());
        	}
	}
	
	function getcragdata($db, $query_params)
	{
		// Get Crag List Data
        	$query = "
                	SELECT
                	crag_id,
                	date,
                	venue,
			area,
			web,
                	rock,
                	conditions,
                	rainedoff,
			pub
                	FROM craglist";
		if(isset($query_params)){
			$query .=" WHERE crag_id = :crag_id";
		}
        	try{
                	$stmt = $db->prepare($query);
                	$stmt->execute($query_params);
        	}
        	catch(PDOException $ex){
                	die("Failed to run query: " . $ex->getMessage());
        	}

		return $stmt;
	}

	function getattended($db)
	{
		$attendsql = "SELECT 
				a.user_id, 
				a.crag_id 
			      FROM attended a, 
				   users u 
			      WHERE u.user_id = a.user_id 
			      ORDER BY u.user_id"; //query the db

        	$results = $db->prepare($attendsql);
        	$results->execute();
	
		return $results;
	}

	function getattendendbycragid($db, $query_params)
	{
		$query = "SELECT u.user_id, u.firstname, u.surname 
			FROM users as u, attended as a 
			WHERE u.user_id = a.user_id 
			AND a.crag_id = :crag_id";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}

	function updatecragdetails($db, $query_params)
	{
		$update=("
                        UPDATE craglist SET
                        venue = :venue,
                        area = :area,
                        web = :web,
                        conditions = :conditions,
                        date = :date,
                        rock = :rock, 
                        rainedoff = :rainedoff, 
                        pub = :pub 
                        WHERE crag_id = :crag_id
                        ");

                        try{
                                $stmt = $db->prepare($update);
                                $stmt->execute($query_params);
                        }
                        catch(PDOException $ex){
                                die("Failed to run query: " . $ex->getMessage());
                        }
	}

	function insertcragdata($db, $query_params)
	{
		$query = "
            INSERT INTO craglist (
                venue,
                area,
                web,
                conditions,
                date,
                rock
            ) VALUES (
                :venue,
                :area,
                :web,
                :conditions,
                :date,
                :rock
            )
        	";
        	try{
            		// Execute the query to create the crag
            		$stmt = $db->prepare($query);
            		$result = $stmt->execute($query_params);
        	}
        	catch(PDOException $ex){
            		die("Failed to run query bugger: " . $ex->getMessage());
        	}
	}

	function insertattendeddata($db, $user_id, $value)
	{
		$insert=
		("INSERT INTO attended
                (user_id, crag_id) 
                VALUES ('$user_id','$value')");
		try{
                	$stmt = $db->prepare($insert);
			$stmt->execute();
                }
		catch(PDOException $ex){
			die("Failed to run query: " . $ex->getMessage());
		}
	}

	function getuserattended($db, $query_params)
	{
		$attendsql = "SELECT crag_id 
			       FROM attended 
			       WHERE user_id = :user_id "; //query the db

        	$results = $db->prepare($attendsql);
        	$results->execute($query_params);

		return $results;
	}

	function removeattdence($db, $query_params)
	{
		$sql = " DELETE FROM attended 
			 WHERE user_id = :user_id 
			 AND crag_id = :crag_id
			       "; //query the db

        	$results = $db->prepare($sql);
        	$results->execute($query_params);

		return $results;
	}
?>
