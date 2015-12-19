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
	session_start();
	$dob = $_POST["dob"];
	$email = $_SESSION["email_addr"];
	if ($mysqli->multi_query("CALL UpdatePersonDOB('".$email."', '".$dob."');")) {
		printf("<table><tr><th>Name</th><th>Date of Birth</th></tr>");
		do {
			if ($result = $mysqli->store_result()) {
				while ($row = $result->fetch_row()) {
					printf("<tr><td>%s</td><td>%s</td></tr>", $row[0], $row[1]); 
				}
				$result->close();
			}
		} while ($mysqli->next_result());
		printf("</table>");
		if ($mysqli->multi_query("SELECT * FROM Send_Person_Email ORDER BY ID DESC LIMIT 1;")) {
			if ($result = $mysqli->store_result()) {
				$row = $result->fetch_row();
				$to = $row[1];
				$subject = "Updated Profile Information";
				$info = "You updated your date of birth information on " . $row[2];
				$headers = "From: jsham2@jhu.edu" . "\r\n" . "CC: tlee93@jhu.edu";
				mail($to, $subject, $info, $headers);
			}
		}
	} else {
		printf("No results");
	}
	exit();
?>

</body>
</html>