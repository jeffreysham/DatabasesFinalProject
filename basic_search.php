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
	
	$search = $_POST["search"];
	$option = $_POST["search_select"];
	
	if (strcmp($option,"person") == 0) {
		if ($mysqli->multi_query("CALL ViewPerson('".$search."');")) {
			printf("<table><tr><th>Name</th><th>Sex</th><th>DOB</th><th>Role</th><th>Nominated For</th><th>Film Name</th><th>Birth Place City</th><th>Birth Place State</th><th>Birth Place Country</th></tr>");
			do {
				if ($result = $mysqli->store_result()) {
					while ($row = $result->fetch_row()) {
						printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><tr>",
						$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
					}
					$result->close();
				}
			} while ($mysqli->next_result());
			printf("</table>");
		} else {
			printf("No results");
		}
	} else if (strcmp($option,"film") == 0) {
		if ($mysqli->multi_query("CALL ViewFilms('".$search."');")) {
			printf("<table><tr><th>Name</th><th>Year</th><th>Rating</th><th>Genre</th><th>Country</th><th>Language</th><th>Budget</th><th>Gross</th><th>Nominated For</th></tr>");
			do {
				if ($result = $mysqli->store_result()) {
					while ($row = $result->fetch_row()) {
						printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
						$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
					}
					$result->close();
				}
			} while ($mysqli->next_result());
			printf("</table>");
		} else {
			printf("No results");
		}
	} else if (strcmp($option,"award") == 0) {
		if ($mysqli->multi_query("CALL ViewAwards('".$search."');")) {
			printf("<table><tr><th>Agency</th><th>Name</th><th>Year</th><th>Win</th><th>Votes</th><th>Film/Person Name</th></tr>");
			do {
				if ($result = $mysqli->store_result()) {
					while ($row = $result->fetch_row()) {
						printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
						$row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
					}
					$result->close();
				}
			} while ($mysqli->next_result());
			printf("</table>");
		} else {
			printf("No results");
		}
	} else {
		//Place
		if ($mysqli->multi_query("CALL ViewPlaces('".$search."');")) {
			printf("<table><tr><th>City</th><th>State</th><th>Country</th><th>Person Name</th></tr>");
			do {
				if ($result = $mysqli->store_result()) {
					while ($row = $result->fetch_row()) {
						printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
						$row[0], $row[1], $row[2], $row[3]);
					}
					$result->close();
				}
			} while ($mysqli->next_result());
			printf("</table>");
		} else {
			printf("No results");
		}
	}
?>

</body>
</html>