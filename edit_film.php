<!DOCTYPE html>
<html>
<body bgcolor="FFD700">
<style>
table, th, td {
     border: 1px solid black;
}
</style>
<div class="nav-wrapper">
	<div class="nav-bar">
		<h1 align="center">Jeffrey + Tyler's MOVIE AWARDS Database Website</h1>
		<h2 align="center">jsham2, tlee93</h2>
	</div>
</div>

<form action="edit_film_attr.php" method="post" accept-charset="utf-8">
			<label for="name">Film Name</label>
			<input type="text" name="name" placeholder="Film Name..">
			<br>
			<label for="name">Rating</label>
			<input type="text" name="rating" placeholder="Rating..">
			<br>
			<label for="name">Genre</label>
			<input type="text" name="genre" placeholder="Genre..">
			<br>
			<label for="name">Country</label>
			<input type="text" name="country" placeholder="Country..">
			<br>
			<label for="name">Language</label>
			<input type="text" name="language" placeholder="Language..">
			<br>
			<label for="name">Budget</label>
			<input type="text" name="budget" placeholder="Budget..">
			<br>
			<label for="name">Gross</label>
			<input type="text" name="gross" placeholder="Gross..">
			<br>
			<input type="submit" value="Submit">
</form>

<?php
	$mysqli = new mysqli("dbase.cs.jhu.edu", "cs41515_jsham2", "PTIASBIT", "cs41515_jsham2_db");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s<br>", mysqli_connect_error());
		exit();
	}
	session_start();
	$email = $_SESSION["email_addr"];
	if ($mysqli->multi_query("CALL FindFilmsGivenEmail('".$email."');")) {
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
			
	} else {
		printf("No results");
	}
	exit();
?>

</body>
</html>