<?php
/*
	function clean_string($string)
	{
		$bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
	}
*/
	//////////////////////////////////////////////////////////
	// resetconfirm.php
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

	//////////////////////////////////////////////////////////
	// register.php
	function checkvirtualmemberdetails($db, $query_params)
	{
		$query = " SELECT user_id, firstname, surname, email, virtualuser 
			   FROM users 
			   WHERE firstname = :firstname 
			   AND surname = :surname 
			   AND virtualuser = 1";
		try{
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                        die("Failed to run query: " . $ex->getMessage());
                }
                return $stmt;
	}

	//////////////////////////////////////////////////////////
	// register.php
	function convertvirtualtofull($db, $query_params)
	{
		$query = "UPDATE users 
			  SET   firstname = :firstname,
				surname = :surname,
				username = :username, 
				password = :password, 
				salt = :salt,
				email = :email, 
				emailshow = :emailshow,
				virtualuser = :virtualuser 
			  WHERE user_id = :user_id";
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


	//////////////////////////////////////////////////////////
	// update member pasword
	// resetconfirm.php
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


	//////////////////////////////////////////////////////////
	// update user password reset activation code
	// reset.php
	function updatepasswordreset($db, $query_params)
	{
		$query = "UPDATE users SET activation_code = :code, expiry = :expiry WHERE email = :email";

		try{
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);
		return true;
		}
		catch(PDOException $ex){
			die("Failed to run query: " . $ex->getMessage());
		return false;
		}
	}


	//////////////////////////////////////////////////////////
	// Get all member accounts if approved or not
	// admin/approveaccount.php 
	// admin/logmemberattendence.php
	// dashboard/cragattendence.php
	// dashboard/memberlist.php 
	function getaccounts($db, $getapproved, $getvirtual, $flag)
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
				emailshow,
				virtualuser
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
		if($getvirtual == 1){
				if ($flag != 1){
				$query .="
					AND virtualuser = 1";
				}
				else{
				$query .="
					OR virtualuser = 1";
				}
		}
		elseif($getvirtual == 0){
			$query .="
				AND virtualuser = 0
			";
		}
			$query .= " ORDER BY surname, firstname";
		
		try{
			$stmt = $db->prepare($query);
			$stmt->execute();
		}
		catch(PDOException $ex){
			die("Failed to run query: " . $ex->getMessage());
		}
		return $stmt;
	}

	//////////////////////////////////////////////////////////
	//dashboard/cragattendence.php
	function getmembersattended($db, $query_params)
	{
		//$query = "SELECT distinct(u.user_id), u.firstname, surname FROM users as u INNER JOIN attended as a on u.user_id = a.user_id";

		//$query = "SELECT distinct(u.user_id), u.firstname, surname FROM users as u INNER JOIN attended as a on u.user_id = a.user_id INNER JOIN cragvisit as c ON a.cragvisit_id = c.cragvisit_id AND YEAR(c.date) = YEAR(now())";
		$query = "SELECT distinct(u.user_id), u.firstname, surname FROM users as u INNER JOIN attended as a on u.user_id = a.user_id INNER JOIN cragvisit as c ON a.cragvisit_id = c.cragvisit_id AND YEAR(c.date) = :year ORDER BY u.surname, u.firstname";


		try{
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                        die("In Function: getmembersattended: Failed to run query: " . $ex->getMessage());
                }
                return $stmt;
	}



	//////////////////////////////////////////////////////////
	// Get all member accounts
	// admin/approveaccount.php
	// dashboard/editaccount.php
	// login.php
	function getaccountsall($db, $query_params)
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

		if(isset($query_params[':username'])){
			$query .="
				WHERE username = :username
				OR email = :username
			";
		}

		if(isset($query_params[':user_id'])){
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

	//////////////////////////////////////////////////////////
	// Update member account set admin / approved status
	// admin/approveaccount.php
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

	//////////////////////////////////////////////////////////
	// register.php
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

	//////////////////////////////////////////////////////////
	// reset.php
	// register.php
	// admin/createvirtualuser.php
	// dashboard/editaccount.php
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

	//////////////////////////////////////////////////////////
	// register.php
	// admin/createvirtualuser.php
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
				emailshow,
				regdate,
				virtualuser
			) VALUES (
				:firstname,
				:surname,
				:username,
				:password,
				:salt,
				:email,
				:emailshow,
				now(),
				:virtualuser
			)";
		try{
			// Execute the query to create the user
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
			return true;
		}
		catch(PDOException $ex){
			return false;
			die("Failed to run query: " . $ex->getMessage());
		}
	}

	//////////////////////////////////////////////////////////
	// admin/updatevisit.php
	// admin/cragreportedit.php
	// admin/cragreportadd.php
	// admin/logmemberattendence.php
	// dashboard/craglist.php
	// dashboard/cragdetail.php
	// dashboard/cragattendence.php
	// dashboard/visitarchive.php
	function getcragdata($db, $query_params)
	{
		$query = "SELECT 
				cd.cragdetail_id, 
				cv.cragvisit_id, 
				cv.date, 
				cv.event, 
				cd.venue, 
				cd.area, 
				cd.web, 
				cd.rock, 
				cd.faces, 
				cd.altitude, 
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
		
		if(isset($query_params[':month'])){
			$query .=" AND MONTH(cv.date) = :month";
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
	
	// dashboard/visitarchive.php
	function getmonth($selectedmonth)
        {
		$selectmonth = '';
        	switch ($selectedmonth) {
        	case '1':
                	$selectmonth = 'January';
        	break;
        	case '2':
        	        $selectmonth = 'Feburay';
        	break;
        	case '3':
        	        $selectmonth = 'March';
        	break;
        	case '4':
        	        $selectmonth = 'April';
        	break;
        	case '5':
        	        $selectmonth = 'May';
        	break;
        	case '6':
        	        $selectmonth = 'June';
        	break;
        	case '7':
        	        $selectmonth = 'July';
        	break;
        	case '8':
        	        $selectmonth = 'August';
        	break;
        	case '9':
               		$selectmonth = 'September';
        	break;
        	case '10':
        	        $selectmonth = 'October';
        	break;
        	case '11':
        	        $selectmonth = 'November';
        	break;
        	case '12':
        	        $selectmonth = 'December';
        	break;
        	}
                return $selectmonth;
        }


	//////////////////////////////////////////////////////////
	// admin/cragupdate.php
	// admin/cragvisitcreate.php
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

	//////////////////////////////////////////////////////////
	// dashboard/cragattendence.php
	// dashboard/visitarchive.php
	function getattended($db, $query_params)
	{
		$attendsql = "SELECT a.user_id, u.firstname, u.surname, a.cragvisit_id, cv.date FROM attended a, users u, cragvisit cv WHERE u.user_id = a.user_id AND a.cragvisit_id = cv.cragvisit_id AND YEAR(cv.date) = :year ORDER BY u.user_id";

		$results = $db->prepare($attendsql);
		$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragdetail.php
	// admin/logmemberattendence.php
	function getattendendbycragid($db, $query_params)
	{
		$query = "SELECT u.user_id, u.firstname, u.surname, a.cragvisit_id 
			FROM users as u, attended as a 
			WHERE u.user_id = a.user_id 
			AND a.cragvisit_id = :cragvisit_id";

		$results = $db->prepare($query);
								$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// admin/cragupdate.php
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

	//////////////////////////////////////////////////////////
	// admin/updatevisit.php
	function updatecragvisit($db, $query_params)
	{
		$update=("
							UPDATE cragvisit SET
							date = :date,
							event = :event,
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

	//////////////////////////////////////////////////////////
	// admin/cragcreate.php
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


	//////////////////////////////////////////////////////////
	// admin/cragvisitcreate.php
	function insertcragvisit($db,$query_params)
	{
		$query = "INSERT INTO cragvisit 
				(cragdetail_id, date, event, conditions, pub, rainedoff) 
				VALUES (
				:cragdetail_id,
				:date,
				:event,
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

	//////////////////////////////////////////////////////////
	// admin/cragreportadd.php
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

	//////////////////////////////////////////////////////////
	// admin/cragreportedit.php
	function updatecragreport($db, $query_params)
	{
		$query="UPDATE cragreports
			SET cragreport = :cragreport
			WHERE cragvisit_id = :cragvisit_id";
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
	
	//////////////////////////////////////////////////////////
	// index.php
	function getlatestcragreport($db)
	{
		$query="SELECT cv.date, cd.venue, cd.area, cr.cragvisit_id, cr.cragreport FROM cragreports cr, cragvisit cv, cragdetail cd WHERE cv.cragvisit_id = cr.cragvisit_id AND cv.cragdetail_id = cd.cragdetail_id ORDER BY cv.date DESC LIMIT 1";
		try{
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                }
                catch(PDOException $ex){
                        die("Failed to run query: " . $ex->getMessage());
                }
		return $stmt;
	}

	//////////////////////////////////////////////////////////
	// admin/updatevisit.php
	// admin/cragreportedit.php
	// admin/cragreportadd.php
	// dashboard/cragdetail.php
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

	//////////////////////////////////////////////////////////
	// dashboard/craglist.php
	// admin/logmemberattendence.php
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


	//////////////////////////////////////////////////////////
	// dashboard/craglist.php
	function getuserattended($db, $query_params)
	{
		$attendsql = "SELECT cragvisit_id 
			      FROM attended 
			      WHERE user_id = :user_id "; //query the db

		$results = $db->prepare($attendsql);
		$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/craglist.php
	// admin/logmemberattendence.php
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

	//////////////////////////////////////////////////////////
	// index.php
	function getnextcrag($db)
	{
		$query ="SELECT cv.cragvisit_id, cv.date, cv.event, cd.venue, cd.area, cd.rock, cd.altitude, cd.faces FROM cragdetail as cd, cragvisit as cv WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.date BETWEEN CURDATE() AND DATE_ADD(NOW(), INTERVAL 10 DAY)";

		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}

	//////////////////////////////////////////////////////////
	// index.php
	// dashboard/cragdetail.php
	function getsunsettime($db, $query_params)
	{
		$query="SELECT sunsettime 
			FROM sunset 
			WHERE date = :date";
		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	// dashboard/visitarchive.php
	function getvisithistoryyear($db)
	{
		$query="SELECT distinct YEAR(date) as year from cragvisit WHERE YEAR(date) < 2014 ORDER BY date DESC";

		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}
	
	/*
	function getmembersbyyear($db, $query_params)
	{
		$query="SELECT distinct u.user_id, u.firstname, u.surname from users u, attended a, cragvisit cv WHERE u.user_id = a.user_id and a.cragvisit_id = cv.cragvisit_id AND YEAR(cv.date) = 2014";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}
	*/

	function getvisitmonths($db, $query_params)
        {
                //$query = "SELECT distinct MONTHNAME(date) as month FROM cragvisit WHERE YEAR(date) = :year";
                $query = "SELECT distinct MONTHNAME(date) as monthname, MONTH(date) as monthnum FROM cragvisit WHERE YEAR(date) = :year ORDER BY date";
                $results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
        }

	//////////////////////////////////////////////////////////
	// Yearly stat functions - dashboard/cragstats.php
	//////////////////////////////////////////////////////////
	function getuserattendence($db, $query_params)
	{
/*
		$query ="SELECT a.user_id, 
				u.firstname, 
				u.surname, 
				count(a.cragvisit_id) as count 
			 FROM attended as a, 
			      cragvisit cv, 
			      users u 
			 WHERE a.cragvisit_id = cv.cragvisit_id 
			 AND u.user_id = a.user_id 
			 AND YEAR(cv.date)= :year 
			 GROUP BY a.user_id
			 ORDER BY count DESC
			 LIMIT 10";
*/
		$query = "SELECT a.user_id,
                                u.firstname, 
                                u.surname, 
                                count(a.cragvisit_id) as count,
                                round(count(*) / t.total * 100,0) AS percent
                         FROM attended as a, 
                              cragvisit cv, 
                              users u, (SELECT COUNT(*) AS total FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE YEAR(cv.date) = :year AND cv.rainedoff = 0) AS t
                         WHERE a.cragvisit_id = cv.cragvisit_id
                         AND u.user_id = a.user_id
                         AND YEAR(cv.date)= :year
                         GROUP BY a.user_id
                         ORDER BY count DESC
                         LIMIT 10";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}

	function gettopattendedcrag($db, $query_params)
	{
		$query ="SELECT cv.cragvisit_id, cv.date, cd.venue, cd.area, count(cd.venue) as count, cv.conditions FROM cragdetail as cd, cragvisit as cv, attended as a WHERE a.cragvisit_id = cv.cragvisit_id AND cv.cragdetail_id = cd.cragdetail_id AND YEAR(cv.date)= :year GROUP BY cv.date ORDER BY count DESC Limit 3";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}

	function gettotalrainedoff($db, $query_params)
        {
                $query ="SELECT count(cv.rainedoff) as count FROM cragvisit as cv WHERE cv.rainedoff = 1 AND YEAR(cv.date)= :year";

                $results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
        }

	function getrocktotals($db, $query_params)
	{
		$query = "SELECT cd.rock, count(cd.rock) as split, round(count(*) / t.total * 100,0) AS percent FROM cragdetail cd, cragvisit cv, (SELECT COUNT(*) AS total FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE YEAR(cv.date) = :year AND cv.rainedoff = 0) AS t WHERE cd.cragdetail_id = cv.cragdetail_id AND YEAR(cv.date)= :year AND cv.rainedoff = 0 GROUP BY cd.rock ORDER BY split DESC";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}

	function weeksleftofsummer()
	{
		// How many weeks of summer are left this year
        	$dayDif    = date('z',strtotime(date('2014-10-26'))) - date('z',strtotime(date('Y-M-d')));
        	$numWeeks  = round($dayDif / 7);
		
		return $numWeeks;
	}

	function getcountytotals($db, $query_params)
	{
		$query = "SELECT cd.county,  count(*) AS split, round(count(*) / t.total * 100,0) AS percent FROM cragdetail cd, cragvisit cv, (SELECT COUNT(*) AS total FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE YEAR(cv.date) = :year AND cv.rainedoff = 0) AS t WHERE cd.cragdetail_id = cv.cragdetail_id AND YEAR(cv.date) = :year AND cv.rainedoff = 0 GROUP BY cd.county ORDER BY split DESC";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;

	}

	function getyearstats($db, $query_params)
	{
		$query = "SELECT count(*) as attempts, r.rainedoff, a.actual, round(actual / t.total * 100,0) AS percentvisited, round(r.rainedoff / t.total * 100,0) as percentraindedoff FROM cragvisit, (SELECT count(*) as actual FROM cragvisit WHERE rainedoff = 0 AND YEAR(date) = :year) as a, (SELECT count(*) as rainedoff FROM cragvisit WHERE rainedoff = 1 AND YEAR(date) = :year) as r, (SELECT COUNT(*) as total FROM cragvisit WHERE YEAR(date) = :year) as t WHERE YEAR(date) = :year";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}

	//////////////////////////////////////////////////////////
	// Personal Stat Functions - dashboard/mystats.php
	//////////////////////////////////////////////////////////
	function getcragvisitsbyuser($db, $query_params)
	{
		$query = "SELECT cd.venue as venue, cd.area as area, count(*) as count FROM cragdetail cd, cragvisit cv, attended a WHERE cv.cragvisit_id = a.cragvisit_id AND cd.cragdetail_id = cv.cragdetail_id AND a.user_id = :user_id group by venue, area ORDER BY count DESC";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}

	function gettotalvisitsbyuser($db, $query_params)
	{
		//$query = "SELECT YEAR(cv.date) as year, count(*) as visits FROM attended a, cragvisit cv WHERE a.cragvisit_id = cv.cragvisit_id AND a.user_id = :user_id GROUP BY YEAR(cv.date)";

		$query = "SELECT YEAR(cv.date) as year, count(*) as myvisits, t.total as attempts, round(count(*) / t.total * 100,0) as percent  FROM attended a, cragvisit cv, (SELECT YEAR(date) as year, count(*) as total from cragvisit WHERE rainedoff = 0 GROUP BY year) as t WHERE a.cragvisit_id = cv.cragvisit_id AND a.user_id = :user_id and t.year = YEAR(cv.date) GROUP BY YEAR(cv.date) ORDER BY YEAR(cv.date) DESC";

		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}

	function gettotalvisitsbymonthbyuser($db, $query_params)
	{
		$query = "SELECT MONTHNAME(cv.date) as monthname, count(*) as myvisits FROM cragvisit cv INNER JOIN attended a ON cv.cragvisit_id = a.cragvisit_id WHERE a.user_id = :user_id GROUP BY MONTH(cv.date) ORDER BY cv.date";
		$results = $db->prepare($query);
                $results->execute($query_params);

                return $results;
	}


	/////All Time Stats
	function getalltimesummary($db)
	{
		$query = "SELECT count(*) as attempts, r.rainedoff, a.actual, round(actual / t.total * 100,0) AS percentvisited, round(r.rainedoff / t.total * 100,0) as percentraindedoff FROM cragvisit, (SELECT count(*) as actual FROM cragvisit WHERE rainedoff = 0) as a, (SELECT count(*) as rainedoff FROM cragvisit WHERE rainedoff = 1) as r, (SELECT COUNT(*) as total FROM cragvisit) as t";

		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}



?>
