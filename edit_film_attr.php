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
<a href="loggedIn.php">Home</a>
<?php
	$mysqli = new mysqli("dbase.cs.jhu.edu", "cs41515_jsham2", "PTIASBIT", "cs41515_jsham2_db");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s<br>", mysqli_connect_error());
		exit();
	}
	
	$name = $_POST["name"];
	$rating = $_POST["rating"];
	$language = $_POST["language"];
	$country = $_POST["country"];
	$genre = $_POST["genre"];
	$budget = $_POST["budget"];
	$gross = $_POST["gross"];
	
	if ($mysqli->multi_query("CALL UpdateFilm('".$name."', ".$rating.", '".$genre."', '".$country."', '".$language."', ".$budget.", ".$gross.");")) {
		printf("<table><tr><th>Film Name</th><th>Year</th><th>Rating</th><th>Genre</th><th>Country</th><th>Language</th><th>Budget</th><th>Gross</th></tr>");
		do {
			if ($result = $mysqli->store_result()) {
				while ($row = $result->fetch_row()) {
					printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", 
					$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]); 
				}
				$result->close();
			}
		} while ($mysqli->next_result());
		printf("</table>");

		$to = $_SESSION["email"];
		$subject = "Updated Film Information";
		$info = "You updated your film, " . $name . ", recently.";
		$headers = "From: jsham2@jhu.edu" . "\r\n" . "CC: tlee93@jhu.edu";
		mail($to, $subject, $info, $headers);
			
	} else {
		printf("No results");
	}
	
?>

</body>
</html>