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

<?php
	$mysqli = new mysqli("dbase.cs.jhu.edu", "cs41515_jsham2", "PTIASBIT", "cs41515_jsham2_db");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s<br>", mysqli_connect_error());
		exit();
	}
	
	$year = $_POST["year_select"];
	if ($mysqli->multi_query("CALL ViewAwardsWithYear('".$year."');")) {
		printf("<table><tr><th>Agency</th><th>Name</th><th>Year</th><th>Win</th><th>Votes</th><th>Nominee</th></tr>");
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
	
?>

<form action="submit_vote.php" method="post" accept-charset="utf-8">
			<label for="name">Nominee Name to Vote For: </label>
			<input type="text" name="nominee" placeholder="Nominee Name..">
			<br>
			<label for="name">Award Name to Vote For: (Copy it EXACTLY)</label>
			<input type="text" name="name" placeholder="Award Name..">
			<br>
			<input type="submit" value="Submit">
</form>
<h3>Disclaimer: Voting may take a bit of time in order to process</h3>


</body>
</html>