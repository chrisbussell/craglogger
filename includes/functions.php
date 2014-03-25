<?php

	function clean_string($string)
	{
		$bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
	}


	function checkpasswordcode($db, $query_params)
	{
		$query = "SELECT 1 FROM users WHERE email = :email AND activation_code = :code AND :expiry <= expiry";

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
		$query = "UPDATE users SET activation_code = :code, expiry = :expiry WHERE email = :email";

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
							approved,
							emailshow
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
							approved,
							emailshow
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

		try{
			$stmt = $db->prepare($update);
			$stmt->execute($query_params);
		}
		catch(PDOException $ex){
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
							email = :email
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

	function insertuser($db, $query_params)
	{
		$query = "
							INSERT INTO users (
								firstname,
								surname,
								username,
								password,
								salt,
								email,
								emailshow
						) VALUES (
								:firstname,
								:surname,
								:username,
								:password,
								:salt,
								:email,
								:emailshow
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

	////////////////////////////////////////
	function getcragdata($db, $query_params)
	{
		$query = "SELECT 
				cd.cragdetail_id, 
				cv.cragvisit_id, 
				cv.date, 
				cd.venue, 
				cd.area, 
				cd.web, 
				cd.rock, 
				cv.conditions, 
				cv.rainedoff, 
				cv.pub 
			  FROM cragdetail as cd, 
			       cragvisit as cv 
			  WHERE cd.cragdetail_id = cv.cragdetail_id";

		if(isset($query_params[':cragdetail_id'])){
			$query .=" AND cd.cragdetail_id = :cragdetail_id";
		}

		if(isset($query_params[':cragvisit_id'])){
			$query .=" AND cv.cragvisit_id = :cragvisit_id";
		}

		if(isset($query_params[':year'])){
			$query .=" AND YEAR(cv.date) = :year";
		}

		$query .=" ORDER BY cv.date ASC";

		try{
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);
		}
		catch(PDOException $ex){
			die("Failed to run query: " . $ex->getMessage());
		}

		return $stmt;
	}

	function getcragdetail($db)
	{
		$query = "SELECT cd.cragdetail_id, 
			    	cd.venue, 
								cd.area, 
								cd.rock, 
								cd.country, 
								cd.county, 
								cd.altitude, 
								cd.faces, 
								cd.web, 
								cd.lat, 
								cd.lng
							FROM cragdetail as cd 
							ORDER BY cd.venue";

		try{
			$stmt = $db->prepare($query);
			$stmt->execute();
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
										a.cragvisit_id 
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
			AND a.cragvisit_id = :cragvisit_id";

		$results = $db->prepare($query);
								$results->execute($query_params);

		return $results;
	}

	function updatecragdetails($db, $query_params)
	{
		$update=("
			UPDATE cragdetail SET
				venue = :venue,
				area = :area,
				web = :web,
				rock = :rock, 
				country = :country, 
				county = :county, 
				altitude = :altitude, 
				faces = :faces, 
				lat = :lat, 
				lng = :lng, 
				web = :web 
			WHERE cragdetail_id = :cragdetail_id
							");

		try{
			$stmt = $db->prepare($update);
			$stmt->execute($query_params);
		}
		catch(PDOException $ex){
			die("Failed to run query: " . $ex->getMessage());
		}
	}

	function updatecragvisit($db, $query_params)
	{
		$update=("
							UPDATE cragvisit SET
							date = :date,
							conditions = :conditions,
							pub = :pub, 
							rainedoff = :rainedoff 
							WHERE cragvisit_id = :cragvisit_id
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
							INSERT INTO cragdetail (
									venue,
									area,
									rock,
									country,
									county,
									altitude,
									faces,
									web,
									lat,
									lng
							) VALUES (
									:venue,
									:area,
									:rock,
									:country,
									:county,
									:altitude,
									:faces,
									:web,
									:lat,
									:lng
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

	function insertcragvisit($db,$query_params)
	{
		$query = "INSERT INTO cragvisit 
				(cragdetail_id, date, conditions, pub, rainedoff) 
				VALUES (
				:cragdetail_id,
				:date,
				:conditions,
				:pub,
				:rainedoff)";

		try{
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);
			return true;
		}
		catch(PDOException $ex){
			return false;
			die("Failed to run query: " . $ex->getMessage());
		}
	}

	function insertcragreport($db, $query_params)
	{
		$query="INSERT INTO cragreports
			(cragvisit_id, 
			 cragreport)
			VALUES (
			:cragvisit_id,
			:cragreport)";
		try{
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                        return true;
                }
                catch(PDOException $ex){
                        return false;
                        die("Failed to run query: " . $ex->getMessage());
                }
	}

	function getcragreport($db, $query_params)
	{
		$query="SELECT cv.date, cd.venue, cd.area, cr.cragvisit_id, cr.cragreport FROM cragreports as cr, cragdetail as cd, cragvisit as cv WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.cragvisit_id = cr.cragvisit_id";
		if(isset($query_params[':cragvisit_id'])){
                        $query .=" AND cv.cragvisit_id = :cragvisit_id";
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


	function insertattendeddata($db, $user_id, $value)
	{
		$insert=
			("INSERT INTO attended
			(user_id, cragvisit_id)
			VALUE ('$user_id', '$value')");

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
		$attendsql = "SELECT cragvisit_id 
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
						 AND cragvisit_id = :cragvisit_id
									 "; //query the db

		$results = $db->prepare($sql);
		$results->execute($query_params);

		return $results;
	}

	function getnextcrag($db)
	{
		$query ="SELECT cv.date, cd.venue, cd.area 
			 FROM cragdetail as cd, cragvisit as cv 
			 WHERE cd.cragdetail_id = cv.cragdetail_id 
			 AND cv.date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 10 DAY)";

		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}
	

	///////////////////////////////////////////////////////////////////////////////////////
	// Yearly stat functions
	//////////////////////////////////////////////////////////////////////////////////////
	function getuserattendence($db, $query_params)
	{
		$query ="SELECT a.user_id, 
				u.firstname, 
				u.surname, 
				count(a.cragvisit_id) as count 
			 FROM attended as a, 
			      cragvisit cv, 
			      users u 
			 WHERE a.cragvisit_id = cv.cragvisit_id 
			 AND u.user_id = a.user_id 
			 AND YEAR(cv.date)= 2014 
			 GROUP BY a.user_id";
		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}

	function gettopattendedcrag($db, $query_params)
	{
		$query ="SELECT cv.date, cd.venue, cd.area, count(cd.venue) as count, cv.conditions FROM cragdetail as cd, cragvisit as cv, attended as a WHERE a.cragvisit_id = cv.cragvisit_id AND cv.cragdetail_id = cd.cragdetail_id AND YEAR(cv.date)= 2014 GROUP BY cv.date ORDER BY count DESC Limit 3";

		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}

	function gettotalrainedoff($db, $query_params)
        {
                $query ="SELECT count(cv.rainedoff) as count FROM cragvisit as cv WHERE cv.rainedoff = 1";

                $results = $db->prepare($query);
                $results->execute();

                return $results;
        }



?>
