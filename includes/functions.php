<?php

	//////////////////////////////////////////////////////////
	// login.php
	function test_input($data) {
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}
	
	//////////////////////////////////////////////////////////
	// login.php
	function insertlastlogin($db, $query_params)
	{
		$query = "INSERT INTO userlastlogin (user_id, lastlogin) VALUES (:user_id, NOW())";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// register.php
	function checkvirtualmemberdetails($db, $query_params)
	{
		$query = " SELECT u.user_id, u.firstname, u.surname, u.email, uc.usertype_id FROM users u INNER JOIN userconfig uc ON u.user_id = uc.user_id WHERE usertype_id = 2 AND u.firstname = :firstname AND u.surname = :surname";
		try{
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                }
                catch(PDOException $ex){
              //          die("Failed to run query: " . $ex->getMessage());
                }
                return $stmt;
	}

	//////////////////////////////////////////////////////////
	// register.php
	function convertvirtualtofull($db, $query_params, $query_params2)
	{
		$query = "UPDATE users SET   
				firstname = :firstname,
				surname = :surname,
				password = :password, 
				salt = :salt,
				email = :email
			  	WHERE user_id = :user_id";
				try{
                        // Execute the query
                        $stmt = $db->prepare($query);
                        $result = $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                     //  die("Failed to run query: " . $ex->getMessage());
                }

        $query2 = "UPDATE userconfig SET
        			admin = :admin,
        			approved = :approved,
        			emailshow = :emailshow,
        			usertype_id = :usertype_id
        			WHERE user_id = :user_id";

        			try{
                        // Execute the query
                        $stmt = $db->prepare($query2);
                        $result = $stmt->execute($query_params2);
                }
                catch(PDOException $ex){
                      // die("Failed to run query: " . $ex->getMessage());
                }

                return true;
	}

	//////////////////////////////////////////////////////////
	// register.php
	function checkusername($db,$query_params)
	{
		$query = "SELECT 1 FROM users WHERE username = :username";

		try{
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex){
			//die("Failed to run query: " . $ex->getMessage());
		}
		return $stmt;
	}


	//////////////////////////////////////////////////////////
	// resetconfirm.php
	function updatememberpassword($db, $query_params)
	{
		$query = "UPDATE users
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
			//die("Failed to run query: " . $ex->getMessage());
		}
		return true;
	}

	//////////////////////////////////////////////////////////
	// resetconfirm.php
	function checkpasswordcode($db, $query_params)
	{
		$query = "SELECT 1 FROM users WHERE email = :email AND activation_code = :code AND expiry >= CURDATE()";
		try{
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);
		}
		catch(PDOException $ex){
			// die("Failed to run query: " . $ex->getMessage());
		}
		return $stmt;
	}

	//////////////////////////////////////////////////////////
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
			//die("Failed to run query: " . $ex->getMessage());
		return false;
		}
	}

	//////////////////////////////////////////////////////////
	// index.php
	function getlatestcragreport($db)
	{
		$query="SELECT cv.date, cd.venue, cd.area, cd.crag, cr.cragvisit_id, cr.cragreport FROM cragreports cr, cragvisit cv, cragdetail cd WHERE cv.cragvisit_id = cr.cragvisit_id AND cv.cragdetail_id = cd.cragdetail_id ORDER BY cv.date DESC LIMIT 1";
		try{
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                }
                catch(PDOException $ex){
                  //      die("Failed to run query: " . $ex->getMessage());
                }
		return $stmt;
	}

	//////////////////////////////////////////////////////////
	// index.php
	function getnextcrag($db)
	{
		$query ="SELECT cv.cragvisit_id, 
						cv.date, cv.event, cd.venue, cd.area, cd.crag, cd.rock, cd.altitude, cd.faces, cd.lat, cd.lng
				FROM cragdetail as cd, cragvisit as cv 
				WHERE cd.cragdetail_id = cv.cragdetail_id 
				AND cv.date BETWEEN CURDATE() 
				AND DATE_ADD(NOW(), INTERVAL 150 DAY)";

		$results = $db->prepare($query);
        $results->execute();

        return $results;
	}

	//////////////////////////////////////////////////////////
	// index.php
	function getmoonphase($db, $query_params)
	{
		$query="SELECT phase, coverage
			FROM moonphase 
			WHERE date = :date";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
	}


	//////////////////////////////////////////////////////////
	// DASHBOARD                                            //
	//////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////
	// dashboard/visitarchive.php
	function mappingdetails($db, $query_params)
	{
		$query = "SELECT cv.cragvisit_id, cv.date, cd.venue, cd.area, cd.crag, cd.rock, cd.lat, cd.lng FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE year(cv.date) = :year AND cv.rainedoff = 0";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
	}
	
	//////////////////////////////////////////////////////////
	// dashboard/editaccount.php
	function updateuserdetails($db, $query_params)
	{
		$query = "UPDATE users SET 
				firstname = :firstname,
				surname = :surname,
				email = :email"; 
				
				if ($query_params[':password'] !== null){
					$query .= "
					, password = :password
					, salt = :salt";
				}
				
			  $query .= " WHERE user_id = :user_id";
		try{
                        // Execute the query
                        $stmt = $db->prepare($query);
                        $result = $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                 //   die("Failed to run query: " . $ex->getMessage());
                }
                return true;
	}

	function updatenickname($db, $query_params)
	{
		$query = "UPDATE nickname SET nickname = :nickname WHERE user_id = :user_id";

		try{
			// Execute the query
                        $stmt = $db->prepare($query);
                        $result = $stmt->execute($query_params);
                }
                catch(PDOException $ex){
                 //   die("Failed to run query: " . $ex->getMessage());
                }
                return true;
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragattendence.php
	// dashboard/visitarchive.php
	function getmembersattended($db, $query_params)
	{
		$query = "SELECT distinct(u.user_id), u.firstname, surname, n.nickname FROM users as u INNER JOIN attended as a on u.user_id = a.user_id INNER JOIN cragvisit as c ON a.cragvisit_id = c.cragvisit_id LEFT JOIN nickname n ON u.user_id = n.user_id WHERE YEAR(c.date) = :year ORDER BY u.surname, u.firstname";
			try{
                	$stmt = $db->prepare($query);
                    $stmt->execute($query_params);
                }
                catch(PDOException $ex){
              		// die("In Function: getmembersattended: Failed to run query: " . $ex->getMessage());
                }
                return $stmt;
	}

	//////////////////////////////////////////////////////////
	// dashboard/visitarchive.php
	// dashboard/cragattendence.php
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
	// dashboard/cragattendence.php
	// dashboard/visitarchive.php
	function getattended($db, $query_params)
	{
		$attendsql = "SELECT a.user_id, u.firstname, u.surname, n.nickname, a.cragvisit_id, cv.date FROM attended a INNER JOIN users u ON a.user_id = u.user_id INNER JOIN cragvisit cv ON a.cragvisit_id = cv.cragvisit_id LEFT JOIN nickname n ON u.user_id = n.user_id WHERE YEAR(cv.date) = :year ORDER BY u.user_id ";

		$results = $db->prepare($attendsql);
		$results->execute($query_params);

		return $results;
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
	// dashboard/cragattendence.php
	// dashboard/visitarchive.php
	function getvisitmonths($db, $query_params)
    {
        $query = "SELECT distinct MONTHNAME(date) as monthname, MONTH(date) as monthnum FROM cragvisit WHERE YEAR(date) = :year ORDER BY date";
                
        $results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
    }

	//////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	// dashboard/alltimestats.php
	function getuserattendence($db, $query_params)
	{
 		$query = "SELECT a.user_id,
                                u.firstname,
                                u.surname,
                                n.nickname,
                                count(a.cragvisit_id) as count,
                                round(count(*) / t.total * 100,0) AS percent
                        FROM attended as a 
                        INNER JOIN cragvisit cv ON a.cragvisit_id = cv.cragvisit_id
                        INNER JOIN users u ON u.user_id = a.user_id
                        LEFT JOIN nickname n ON u.user_id = n.user_id,
                             (SELECT COUNT(*) AS total
                              FROM cragdetail cd
                              INNER JOIN cragvisit cv
                              ON cd.cragdetail_id = cv.cragdetail_id
                              WHERE YEAR(cv.date) = :year AND date < NOW()
                              AND cv.rainedoff = 0) AS t
                         WHERE YEAR(cv.date)= :year AND date < NOW()
                         GROUP BY a.user_id
                         ORDER BY count DESC
                         LIMIT 10";

		$results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	function getcragbyyear($db,$query_params){
		$query = "SELECT cv.cragvisit_id, cv.date, cd.venue, cd.area, cd.crag, cv.event, cv.firstvisit FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE YEAR(cv.date) = :year AND rainedoff = 0 ORDER BY cv.date asc";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	function gettopattendedcrag($db, $query_params)
	{
		$query ="SELECT cv.cragvisit_id, cv.date, cd.venue, cd.area, cd.crag, count(cd.venue) as count, cv.conditions, cv.firstvisit FROM cragdetail as cd, cragvisit as cv, attended as a WHERE a.cragvisit_id = cv.cragvisit_id AND cv.cragdetail_id = cd.cragdetail_id AND YEAR(cv.date)= :year GROUP BY cv.date ORDER BY count DESC Limit 3";

		$results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	function gettotalrainedoff($db, $query_params)
	{
        $query ="SELECT count(cv.rainedoff) as count FROM cragvisit as cv WHERE cv.rainedoff = 1 AND YEAR(cv.date)= :year";

        $results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
    }

    //////////////////////////////////////////////////////////
	// dashboard/cragstats.php
    function getrainedoffdetail($db, $query_params)
    {
    	$query = "SELECT cv.date, cd.venue, cd.area, cd.crag FROM cragvisit as cv INNER JOIN cragdetail cd ON cv.cragdetail_id = cd.cragdetail_id WHERE cv.rainedoff = 1 AND YEAR(cv.date)= :year";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
    }

    //////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	function getrocktotals($db, $query_params)
	{
		$query = "SELECT cd.rock, count(cd.rock) as split, round(count(*) / t.total * 100,0) AS percent FROM cragdetail cd, cragvisit cv, (SELECT COUNT(*) AS total FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE cv.date < NOW() AND YEAR(cv.date) = :year AND cv.rainedoff = 0) AS t WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.date < NOW() AND YEAR(cv.date)= :year AND cv.rainedoff = 0 GROUP BY cd.rock ORDER BY split DESC";

		$results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	// dashboard/mystats.php
	function weeksleftofsummer($db)
	{
		// Get start and end dates for this years british summertime
		$query = "SELECT startdate, enddate FROM britishsummertime WHERE DATE_FORMAT(startdate, '%Y') = DATE_FORMAT(NOW(), '%Y')";

		$results = $db->prepare($query);
        $results->execute();
        
        $dates = $results->fetch();

        if (date('z',(strtotime(date('Y-m-d')))) <= date('z',(strtotime($dates['startdate']))))
        {
        	// How many days until summer starts
        	$query2 = "SELECT ROUND(SUM(DATEDIFF(bst.startdate,now())/7),0) AS weeks FROM britishsummertime bst WHERE DATE_FORMAT(bst.startdate, '%Y') = DATE_FORMAT(NOW(), '%Y')";

        	$results = $db->prepare($query2);
        	$results->execute();
        
        	$dates = $results->fetch();
        	$numweeks = $dates['weeks'];

        	$summertime = '0';
        }
        else
       	{
       		// How many weeks of summer are left this year
			$dayDif    = date('z',strtotime(date($dates['enddate']))) - date('z',strtotime(date($dates['startdate'])));

        	$numWeeks  = round($dayDif / 7);

        	$summertime = '1';
       	}
		
		return array($numweeks,$summertime);
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	function getcountytotals($db, $query_params)
	{
		$query = "SELECT cd.county,  count(*) AS split, round(count(*) / t.total * 100,0) AS percent FROM cragdetail cd, cragvisit cv, (SELECT COUNT(*) AS total FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE cv.date < NOW() AND YEAR(cv.date) = :year AND cv.rainedoff = 0) AS t WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.date < NOW() AND YEAR(cv.date) = :year AND cv.rainedoff = 0 GROUP BY cd.county ORDER BY split DESC";

		$results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/cragstats.php
	// dashboard/visitarchive.php
	function getyearstats($db, $query_params)
	{
		$query = "SELECT attempts, actual, rainedoff,
					round(actual/attempts*100,0) as percentvisited,
					round(rainedoff/attempts*100,0) as percentraindedoff
				FROM
				(
				SELECT count(*) as attempts,
					sum(if(rainedoff = 0, 1, 0)) as actual,
					sum(if(rainedoff = 1, 1, 0)) as rainedoff
				FROM cragvisit
				WHERE YEAR(date) = :year
				AND date < NOW()
				) as cv";

		$results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/mystats.php
	function getcragvisitsbyuser($db, $query_params)
	{
		$query = "SELECT cd.cragdetail_id, cd.venue as venue, cd.area as area, cd.crag as crag, count(*) as count FROM cragdetail cd, cragvisit cv, attended a WHERE cv.cragvisit_id = a.cragvisit_id AND cd.cragdetail_id = cv.cragdetail_id AND a.user_id = :user_id group by venue, area ORDER BY count DESC";

		$results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/mystats.php
	function gettotalvisitsbyuser($db, $query_params)
	{
		$query = "SELECT YEAR(cv.date) as year, count(*) as myvisits, t.total as attempts, round(count(*) / t.total * 100,0) as percent  FROM attended a, cragvisit cv, (SELECT YEAR(date) as year, count(*) as total from cragvisit WHERE rainedoff = 0 AND date < now() GROUP BY year) as t WHERE a.cragvisit_id = cv.cragvisit_id AND a.user_id = :user_id and t.year = YEAR(cv.date) GROUP BY YEAR(cv.date) ORDER BY YEAR(cv.date) DESC";

		$results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/mystats.php
	function gettotalvisitsbymonthbyuser($db, $query_params)
	{
		$query = "SELECT MONTHNAME(cv.date) as monthname, count(*) as myvisits FROM cragvisit cv INNER JOIN attended a ON cv.cragvisit_id = a.cragvisit_id WHERE a.user_id = :user_id GROUP BY MONTH(cv.date) ORDER BY cv.date";
		
		$results = $db->prepare($query);
        $results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/mystats.php
	function getcragdatevisits($db, $query_params)
	{
		$query = "SELECT cv.cragvisit_id, cv.date, cv.conditions, cd.venue 
				  FROM cragvisit cv 
				  INNER JOIN attended a ON cv.cragvisit_id = a.cragvisit_id 
				  INNER JOIN cragdetail cd ON cv.cragdetail_id = cd.cragdetail_id 
				  WHERE cv.cragdetail_id = :cragdetail_id 
				  AND a.user_id = :user_id";

		$results = $db->prepare($query);
		$results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/myvisits.php
	function getcragsbyyearbyuser($db, $query_params)
	{
		$query = "SELECT cv.cragvisit_id, cv.date, cd.venue, cd.area FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id INNER JOIN attended a ON cv.cragvisit_id = a.cragvisit_id WHERE a.user_id = :user_id AND YEAR(cv.date) = :year ORDER BY cv.date ASC";

		$results = $db->prepare($query);
		$results->execute($query_params);

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
	// dashboard/areabreakdown.php
	// dashboard/visitarchive.php
	function getalltimesummary($db)
	{
		$query = "SELECT attempts, rainedoff, actual,
			round(actual / attempts * 100,0) AS percentvisited,
			round(rainedoff / attempts * 100,0) as percentraindedoff
			FROM
			(
			SELECT count(*) as attempts,
			sum(if(rainedoff = 0, 1, 0)) as actual,
			sum(if(rainedoff = 1, 1, 0)) as rainedoff
			from cragvisit
			WHERE date < now()
			) as cv";

		$results = $db->prepare($query);
        $results->execute();

        return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
	function getcountyalltime($db)
	{
		$query = "SELECT cd.county,  count(*) AS split, round(count(*) / t.total * 100,0) AS percent FROM cragdetail cd, cragvisit cv, (SELECT COUNT(*) AS total FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE cv.date < NOW() AND cv.rainedoff = 0) AS t WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.date < NOW() AND cv.rainedoff = 0 GROUP BY cd.county ORDER BY split DESC";

		$results = $db->prepare($query);
        $results->execute();

        return $results;
	}	

	//////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
	function getrocktotalsalltime($db)
	{
		$query = "SELECT cd.rock, count(cd.rock) as split, round(count(*) / t.total * 100,0) AS percent FROM cragdetail cd, cragvisit cv, (SELECT COUNT(*) AS total FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE cv.date < NOW() AND cv.rainedoff = 0) AS t WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.date < NOW() AND cv.rainedoff = 0 GROUP BY cd.rock ORDER BY split DESC";

		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
    function getrainedoffdetailalltime($db)
    {
    	$query = "SELECT cd.venue, cd.area, count(*) as count FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE rainedoff = 1 GROUP BY cd.venue ORDER BY count DESC";

		$results = $db->prepare($query);
		$results->execute();

		return $results;
    }

    //////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
    function getuserattendencealltime($db)
	{
		$query = "SELECT a.user_id,
                                u.firstname, 
                                u.surname, 
                                count(a.cragvisit_id) as count,
                                round(count(*) / t.total * 100,0) AS percent
                         FROM attended as a, 
                              cragvisit cv, 
                              users u, (SELECT COUNT(*) AS total 
                              FROM cragdetail cd 
                              INNER JOIN cragvisit cv 
                              ON cd.cragdetail_id = cv.cragdetail_id 
                              WHERE date < NOW()
                              AND cv.rainedoff = 0) AS t
                         WHERE a.cragvisit_id = cv.cragvisit_id
                         AND u.user_id = a.user_id
                         AND date < NOW()
                         GROUP BY a.user_id
                         ORDER BY count DESC
                         LIMIT 10";

		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
	function gettopattendedcragalltime($db)
	{
		$query ="SELECT cv.cragvisit_id, cv.date, cd.venue, cd.area, cd.crag, cv.event, count(cd.venue) as count, cv.conditions FROM cragdetail as cd, cragvisit as cv, attended as a WHERE a.cragvisit_id = cv.cragvisit_id AND cv.cragdetail_id = cd.cragdetail_id GROUP BY cv.date ORDER BY count DESC Limit 3";

		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
	function gettotalcragsareavisited($db)
	{
		$query = "SELECT cd.venue, cd.area, count(*) AS count FROM cragdetail cd, cragvisit cv WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.rainedoff = 0 GROUP BY cd.venue, cd.area ORDER BY count desc";


 		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
	function gettotalcragvisited($db)
	{
		$query = "SELECT cd.venue, cd.area, cd.crag, count(*) AS count FROM cragdetail cd, cragvisit cv WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.rainedoff = 0 GROUP BY cd.venue, cd.area, cd.crag ORDER BY count desc";

 		$results = $db->prepare($query);
                $results->execute();

                return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/alltimestats.php
	function gettotalcragsvisited($db)
	{
		$query = "SELECT cd.venue, count(*) as count FROM cragdetail cd INNER JOIN cragvisit cv ON cd.cragdetail_id = cv.cragdetail_id WHERE date < NOW() AND cv.rainedoff = 0 GROUP BY cd.venue ORDER BY count desc";

		$results = $db->prepare($query);
		$results->execute();

		return $results;

	}

	//////////////////////////////////////////////////////////
	// dashboard/areabreakdown.php
	function getareabreakdown($db, $query_params)
	{
		$query = "SELECT cd.venue, cd.area, count(*) AS count FROM cragdetail cd, cragvisit cv WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.rainedoff = 0 AND cd.venue = :venue GROUP BY cd.venue, cd.area ORDER BY count desc";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/areabreakdown.php
	function getdatebyvenue($db, $query_params)
	{
		$query = "SELECT cv.cragvisit_id,cv.date, cd.venue, cv.event FROM cragdetail cd, cragvisit cv WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.rainedoff = 0 AND cd.venue = :venue ORDER BY cv.date DESC";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// dashboard/areabreakdown.php
	function getdatebyvenuearea($db, $query_params)
	{
		$query = "SELECT cv.cragvisit_id, cv.date, cd.venue, cd.area, cd.crag, cv.event FROM cragdetail cd, cragvisit cv WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.rainedoff = 0 AND cd.venue = :venue AND cd.area = :area ORDER BY cv.date DESC";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;

	}

	//////////////////////////////////////////////////////////
	// dashboard/areabreakdown.php
	function get3rdlevel($db, $query_params)
	{
		$query = "SELECT cv.date, cd.venue, cd.area, cd.crag, cv.event FROM cragdetail cd, cragvisit cv WHERE cd.cragdetail_id = cv.cragdetail_id AND cv.rainedoff = 0 AND cd.venue = :venue AND cd.area = :area";

		if (isset($query_params[':crag']))
		{
			$query .=" AND cd.crag = :crag";
		}
		else
		{ 
			$query .=" AND cd.crag IS NULL";
		} 
			$query .=" ORDER BY cv.date DESC";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// ADMIN                                                //
	//////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////
	// admin/createvirtualuser.php
	function insertusernickname($db, $query_params)
	{
		$query = "INSERT INTO nickname (user_id, nickname) VALUES (:user_id, :nickname)";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;
	}

	//////////////////////////////////////////////////////////
	// admin/createvirtualuser.php
	function getlastsignup($db)
	{
		$query = "SELECT MAX(user_id) as user_id FROM users";

		$results = $db->prepare($query);
		$results->execute();

		return $results;
	}

	//////////////////////////////////////////////////////////
	// admin/lastlogin.php
	function getlastlogin($db, $query_params)
	{
		$query = "SELECT u.firstname, u.surname, max(ll.lastlogin) as lastlogin
				FROM users u 
				INNER JOIN userlastlogin ll ON u.user_id = ll.user_id ";

		if(isset($query_params[':user_id'])){
			$query .=" WHERE u.user_id = :user_id";
		}

		$query .="GROUP BY u.firstname, u.surname ORDER BY u.user_id, ll.lastlogin";

		$results = $db->prepare($query);
		$results->execute($query_params);

		return $results;

	}

	//////////////////////////////////////////////////////////
	// admin/cragupdate.php
	// admin/cragvisitcreate.php
	function getcragdetail($db)
	{
		$query = "SELECT cd.cragdetail_id, 
			    	 cd.venue, 
				cd.area,
				cd.crag, 
				cd.rock, 
				cd.country, 
				cd.county, 
				cd.altitude, 
				cd.faces, 
				cd.web, 
				cd.lat, 
				cd.lng
			FROM cragdetail as cd 
			ORDER BY cd.venue, cd.area";

		try{
			$stmt = $db->prepare($query);
			$stmt->execute();
		}
		catch(PDOException $ex){
			//die("Failed to run query: " . $ex->getMessage());
		}

		return $stmt;
	}

	//////////////////////////////////////////////////////////
	// admin/cragupdate.php
	function updatecragdetails($db, $query_params)
	{
		$update=("UPDATE cragdetail SET
				venue = :venue,
				area = :area,
				crag = :crag,
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
			//die("Failed to run query: " . $ex->getMessage());
		}
	}

	//////////////////////////////////////////////////////////
	// admin/updatevisit.php
	function updatecragvisit($db, $query_params)
	{
		$update=("UPDATE cragvisit SET 
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
			//die("Failed to run query: " . $ex->getMessage());
		}
	}

	//////////////////////////////////////////////////////////
	// admin/cragcreate.php
	function insertcragdata($db, $query_params)
	{
		$query = "INSERT INTO cragdetail (
									venue,
									area,
									crag,
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
									:crag,
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
			//die("Failed to run query bugger: " . $ex->getMessage());
		}
	}

	//////////////////////////////////////////////////////////
	// admin/cragvisitcreate.php
	function insertcragvisit($db,$query_params)
	{
		$query = "INSERT INTO cragvisit 
				(cragdetail_id, date, event, conditions, pub, rainedoff, firstvisit) 
				VALUES (
				:cragdetail_id,
				:date,
				:event,
				:conditions,
				:pub,
				:rainedoff,
				:firstvisit)";

		try{
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);
			return true;
		}
		catch(PDOException $ex){
			return false;
			//die("Failed to run query: " . $ex->getMessage());
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
              //          die("Failed to run query: " . $ex->getMessage());
                }
	}

	//////////////////////////////////////////////////////////
	// admin/termreportadd.php
	function insertendoftermreport($db, $query_params)
	{
		$query="INSERT INTO endoftermreport
					(year,
					report)
				VALUES (
					:year,
					:report)";
		try{
				$stmt = $db->prepare($query);
				$stmt->execute($query_params);
				return true;
			}
			catch(PDOException $ex){
				return false;
				//die("Failed to run query: " . $ex->getMessage());
			}
	}

	//////////////////////////////////////////////////////////
	// admin/termreportadd.php
	function updatetermreport($db, $query_params)
	{
		$query = "UPDATE endoftermreport
					SET report = :report
					WHERE year = :year";

			try{
				$stmt = $db->prepare($query);
				$stmt->execute($query_params);
				return true;
			}
			catch(PDOException $ex){
				return false;
				//die("Failed to run query: " . $ex->getMessage());
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
                //	        die("Failed to run query: " . $ex->getMessage());
                	}
	}

	//////////////////////////////////////////////////////////
	// SHARED - admin / dashboard / root                    //
	//////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////
	// dashboard/memberlist.php
	// admin/logmemberattendence.php
	// admin/approveaccount.php
	function getuserbyoption($db, $getapproved, $getvirtual, $flag)
	{
		// Get Accounts that need approving List Data
		$query = "SELECT u.user_id, n.nickname, password, salt, firstname, surname, email, uc.admin, uc.approved, uc.emailshow, uc.usertype_id FROM users u INNER JOIN userconfig uc ON u.user_id = uc.user_id LEFT JOIN nickname n ON u.user_id = n.user_id";

		if($getapproved == 0){
			$query .="
				WHERE uc.approved = 0
			";
		}
		elseif($getapproved == 1){
			$query .="
				WHERE uc.approved = 1
			";
		}
		if($getvirtual == 1){
				if ($flag != 1){
				$query .="
					AND uc.usertype_id = 2";
				}
				else{
				$query .="
					OR uc.usertype_id = 2";
				}
		}
		elseif($getvirtual == 0){
			$query .="
				AND uc.usertype_id = 1
			";
		}
			$query .= " ORDER BY surname, firstname";
		
		try{
			$stmt = $db->prepare($query);
			$stmt->execute();
		}
		catch(PDOException $ex){
			//die("Failed to run query: " . $ex->getMessage());
		}
		return $stmt;
	}

	//////////////////////////////////////////////////////////
	// admin/approveaccount.php
	// dashboard/editaccount.php
	// login.php
	function getalluserdetails($db, $query_params)
	{
		$query = "SELECT u.user_id, n.nickname, password, salt, firstname, surname, email, uc.admin, uc.approved, uc.emailshow, uc.usertype_id FROM users u INNER JOIN userconfig uc ON u.user_id = uc.user_id LEFT JOIN nickname n ON u.user_id = n.user_id";

		if(isset($query_params[':email'])){
			$query .=" WHERE email = :email";
		}

		if(isset($query_params[':user_id'])){
			$query .=" WHERE u.user_id = :user_id";
		}
	
		try{
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);
		}
		catch(PDOException $ex){
			//die("Failed to run query: " . $ex->getMessage());
		}
		return $stmt;
	}

	//////////////////////////////////////////////////////////
	// admin/approveaccount.php
	// dashboard/editaccount.php
	function updateuserconfig($db, $query_params)
	{
		$query = "UPDATE userconfig SET";

		if (isset($query_params[':admin'])){
		$query .= " admin = :admin, approved = :approved";
		}

		if (isset($query_params[':emailshow'])){
			$query .=" emailshow = :emailshow";
		}
			$query .=" WHERE user_id = :user_id";

		try{
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);
		}
		catch(PDOException $ex){
			die("Failed to run query: " . $ex->getMessage());
		}
		//return true;
	}

	//////////////////////////////////////////////////////////
	// reset.php
	// register.php
	// admin/createvirtualuser.php
	// dashboard/editaccount.php
	function checkemail($db, $query_params)
	{
		$query = "SELECT 1 FROM users WHERE email = :email";
					
		try{
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex){
			//die("Failed to run query: " . $ex->getMessage());
		}
		return $stmt;
	}

	//////////////////////////////////////////////////////////
	// register.php
	// admin/createvirtualuser.php
	function insertuser($db, $query_params)
	{
		$query = "INSERT INTO users (
				firstname,
				surname,
				password,
				salt,
				email,
				regdate
			) VALUES (
				:firstname,
				:surname,
				:password,
				:salt,
				:email,
				now()
			)";

		try{
			// Execute the query to create the user
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);

			$user_id = $db->lastInsertId();
			return $user_id;
			
		}
		catch(PDOException $ex){
			return false;
			die();
			//die("Failed to run query: " . $ex->getMessage());
		}
	}

	//////////////////////////////////////////////////////////
	// register.php
	// admin/createvirtualuser.php
	function insertuserconfig($db, $query_params)
	{
		$query = "INSERT INTO userconfig (
					user_id,  
					emailshow, 
					usertype_id 
				) VALUES (
					:user_id,
					:emailshow,
					:usertype_id )";
		try{
			// Execute the query to create the user
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
			return true;
		}
		catch(PDOException $ex){
			return false;
			die();
			//die("Failed to run query: " . $ex->getMessage());
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
				cd.crag,
				cd.web, 
				cd.rock, 
				cd.faces, 
				cd.altitude, 
				cv.conditions, 
				cv.rainedoff, 
				cv.pub,
				cv.firstvisit
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
			//die("Failed to run query: " . $ex->getMessage());
		}

		return $stmt;
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
	// admin/termreportadd.php
	// dashboard/visitarchive.php
	function getendtermreport($db, $query_params)
	{
		$query = "SELECT year, report
				FROM endoftermreport
				WHERE year = :year";

				try{
				$stmt = $db->prepare($query);
				$stmt->execute($query_params);
				return $stmt;
			}
			catch(PDOException $ex){
				return false;
				//die("Failed to run query: " . $ex->getMessage());
			}

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
                    //    die("Failed to run query: " . $ex->getMessage());
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
			//die("Failed to run query: " . $ex->getMessage());
		}
	}

	//////////////////////////////////////////////////////////
	// dashboard/craglist.php
	// admin/logmemberattendence.php
	function removeattdence($db, $query_params)
	{
		$sql = " DELETE FROM attended 
			 WHERE user_id = :user_id 
			 AND cragvisit_id = :cragvisit_id"; 

		$results = $db->prepare($sql);
		$results->execute($query_params);

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
	// dashboard/mystats.php
	// admin/updatevisit.php
	// admin/termreportadd.php
	function getvisithistoryyear($db)
	{
		$query="SELECT distinct YEAR(date) as year from cragvisit WHERE YEAR(date) < DATE_FORMAT(NOW(), '%Y') ORDER BY date DESC";

		$results = $db->prepare($query);
        $results->execute();

        return $results;
	}
