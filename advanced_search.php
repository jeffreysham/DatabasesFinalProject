<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
     border: 1px solid black;
}
</style>
</head>
<body bgcolor="FFD700">
<?php
	$mysqli = new mysqli("dbase.cs.jhu.edu", "cs41515_jsham2", "PTIASBIT", "cs41515_jsham2_db");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s<br>", mysqli_connect_error());
		exit();
	}
	
	$person_name = $_POST["person_name"];
	$sex = $_POST["sex"];
	$dob = $_POST["dob"];
	$role = $_POST["role"];
	$person_nominated_for = $_POST["person_nominated_for"];
	$person_in_film = $_POST["person_in_film"];
	$person_birth_place = $_POST["person_birth_place"];
	
	$film_name = $_POST["film_name"];
	$film_year = $_POST["film_year"];
	$rating = $_POST["rating"];
	$genre = $_POST["genre"];
	$film_country = $_POST["film_country"];
	$language = $_POST["language"];
	$budget = $_POST["budget"];
	$gross = $_POST["gross"];
	$film_nominated_for = $_POST["film_nominated_for"];
	
	$agency = $_POST["agency"];
	$award_name = $_POST["award_name"];
	$award_year = $_POST["award_year"];
	$win = $_POST["win"];
	$votes = $_POST["votes"];
	
	$city = $_POST["city"];
	$state = $_POST["state"];
	$place_country = $_POST["place_country"];
	
	
	$queryArray = array();
	$fromArray = array();
	$whereArray = array();
	$theFrom = "";
	$addPerson = 0;
	$addFilm = 0;
	$addAward = 0;
	$addPlace = 0;
	$addPersonName = 0;
	$addFilmName = 0;
	$addAwardName = 0;
	
	
	
	
	if (strlen($person_name) > 0) {
		$addPerson = 1;
		$addPersonName = 1;
	}
	
	if (strlen($sex) > 0) {
		$queryArray[] = "P.Sex";
		$addPerson = 1;
		$addPersonName = 1;
		$whereArray[] = "P.Sex = '".$sex."'";
	} 
	
	if (strlen($dob) > 0) {
		$queryArray[] = "P.DOB";
		$addPerson = 1;
		$addPersonName = 1;
		$whereArray[] = "P.DOB = '".$dob."'";
	}
	
	if (strlen($role) > 0) {
		$queryArray[] = "P.Role";
		$addPerson = $addPerson + 1;
		$addPersonName = $addPersonName + 1;
		$whereArray[] = "P.Role = '".$role."'";
	} 
	
	if (strlen($person_nominated_for) > 0) {
		$addPerson = 1;
		$addAward = 1;
		$addAwardName = 1;
		$addPersonName = 1;
		$whereArray[] = "P.NominatedFor = A.ID and A.Name LIKE '%".$person_nominated_for."%'";
	}
	
	if (strlen($person_in_film) > 0) {
		$addFilmName = 1;
		$addFilm = 1;
		$addPerson = 1;
		$addPersonName = 1;
		$whereArray[] = "P.InFilm = F.ID and F.Name = '".$person_in_film."'";
	} 
	
	if (strlen($person_birth_place) > 0) {
		$addPlace = 1;
		$addPerson = 1;
		$addPersonName = 1;
		$whereArray[] = "P.BirthPlace = Pl.ID and (Pl.Country LIKE '%".$person_birth_place."%' OR Pl.City LIKE '%".$person_birth_place."%' OR Pl.State LIKE '%".$person_birth_place."%')";
	}
	
	if ($addPerson > 0) {
		$fromArray[] = "Person P";
	}
	
	
	
	
	if ($addPersonName > 0) {
		$temp = array();
		$temp[] = "P.Name";
		$queryArray = array_merge($temp, $queryArray);
		
		if (strlen($person_name) > 0) {
			$whereArray[] = "P.Name = '".$person_name."'";
		}
		
		
	}
	
	$filmArray = array();
	
	if (strlen($film_name) > 0) {
		$addFilmName = 1;
		$addFilmName = 1;
	}
	
	if (strlen($film_year) > 0) {
		$filmArray[] = "F.Year";
		$addFilmName = 1;
		$addFilm = 1;
		$whereArray[] = "F.Year = ".$film_year;
	}
	
	if (strlen($rating) > 0) {
		$filmArray[] = "F.Rating";
		$addFilmName = 1;
		$addFilm = 1;
		$whereArray[] = "F.Rating = ".$rating;
	}
	
	if (strlen($genre) > 0) {
		$filmArray[] = "F.Genre";
		$addFilmName = 1;
		$addFilm = 1;
		$whereArray[] = "F.Genre = '".$genre."'";
	}
	
	if (strlen($film_country) > 0) {
		$filmArray[] = "F.Country";
		$addFilmName = 1;
		$addFilm = 1;
		$whereArray[] = "F.Country = '".$film_country."'";
	}
	
	if (strlen($budget) > 0) {
		$filmArray[] = "F.Budget";
		$addFilmName = 1;
		$addFilm = 1;
		$whereArray[] = "F.Budget = ".$budget;
	}
	
	if (strlen($gross) > 0) {
		$filmArray[] = "F.Gross";
		$addFilmName = 1;
		$addFilm = 1;
		$whereArray[] = "F.Gross = ".$gross;
	}
	
	if (strlen($film_nominated_for) > 0) {
		$addFilmName = 1;
		$addFilm = 1;
		$addAward = 1;
		$addAwardName = 1;
		$whereArray[] = "F.NominatedFor = A.ID and A.Name LIKE '%".$film_nominated_for."%'";
	}
	
	if ($addFilm > 0) {
		$fromArray[] = "Film F";
	}
	
	if ($addFilmName > 0) {
		$queryArray[] = "F.Name";
		$queryArray = array_merge($queryArray, $filmArray);
		
		if (strlen($film_name) > 0) {
			$whereArray[] = "F.Name = '".$film_name."'";
		}
	}
	

	
	
	$awardArray = array();
	
	if (strlen($agency) > 0) {
		$awardArray[] = "A.Agency";
		$addAwardName = 1;
		$addAward = 1;
		$whereArray[] = "A.Agency = '".$agency."'";
	}
	
	if (strlen($award_name) > 0) {
		$addAwardName = 1;
		$addAward = 1;
	}
	
	if (strlen($award_year) > 0) {
		$awardArray[] = "A.Year";
		$addAwardName = 1;
		$addAward = 1;
		$whereArray[] = "A.Year = '".$award_year."'";
	}
	
	if (strlen($win) > 0) {
		$awardArray[] = "A.Win";
		$addAwardName = 1;
		$addAward = 1;
		$whereArray[] = "A.Win = '".$win."'";
	}
	
	if (strlen($votes) > 0) {
		$awardArray[] = "A.Votes";
		$addAwardName = 1;
		$addAward = 1;
		$whereArray[] = "A.Votes = ".$votes;
	}
	
	
	if ($addAwardName > 0) {
		$queryArray[] = "A.Name";
		$queryArray = array_merge($queryArray, $awardArray);
		
		if (strlen($whereArray) > 0) {
			$whereArray[] = "A.Name LIKE '%".$award_name."%'";
		}
		
	}
	
	if ($addAward > 0) {
		$fromArray[] = "Award A";
	}
	
	if (strlen($city) > 0 || strlen($state) > 0 || 
			strlen($place_country) > 0) {
		$queryArray[] = "Pl.City, Pl.State, Pl.Country";
		$whereArray[] = "(Pl.City LIKE '%".$city."%' OR Pl.State LIKE '%".$state."%' OR Pl.Country LIKE '%".$place_country."%')";
		$addPlace = 1;
	}
	
	if ($addPlace > 0) {
		$fromArray[] = "Place Pl";
	}
	
	
	
	$queryString = "SELECT distinct ";
	
	$i = count($queryArray) - 1;
	$j = 0;
	foreach ($queryArray as $val) {
		if ($j < $i) {
			$queryString .= $val . ", ";
		} else {
			$queryString .= $val . " ";
		}
		
		$j = $j + 1;
	}

	
	$k = count($fromArray) - 1;
	$l = 0;
	
	$fromString = "FROM ";
	
	foreach ($fromArray as $val) {
		if ($l < $k) {
			$fromString .= $val . ", ";
		} else {
			$fromString .= $val . " ";
		}
		
		$l = $l + 1;
	}
	
	$whereString = "WHERE ";
	$m = count($whereArray) - 1;
	$n = 0;
	
	foreach ($whereArray as $val) {
		if ($n < $m) {
			$whereString .= $val . " and ";
		} else {
			$whereString .= $val . ";";
		}
		
		$n = $n + 1;
	}
	
	$theQuery = $queryString . $fromString . $whereString;
	echo "The Query: ";
	echo $theQuery;
	
	if ($mysqli->multi_query($theQuery)) {
		printf("<table>");
		do {
			if ($result = $mysqli->store_result()) {
				while ($row = $result->fetch_row()) {
					echo "<tr>";
					foreach($row as $field) {
						printf("<td>" . htmlspecialchars($field) . "</td>");
					}
					echo "</tr>";
				}
				$result->close();
			}
		} while ($mysqli->next_result());
		printf("</table>");
	} else {
		printf("No results");
	}
?>
</body>
</html>