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
	$nominee = $_POST["nominee"];
	$name = $_POST["name"];
	$email = $_SESSION["email_addr"];
	if ($mysqli->multi_query("CALL VoteForAward('".$email."', '".$name."', '".$nominee."');")) {
		printf("Vote submitted.");
		if ($mysqli->multi_query("SELECT * FROM Send_Person_Email ORDER BY ID DESC LIMIT 1;")) {
			if ($result = $mysqli->store_result()) {
				$row = $result->fetch_row();
				$to = $row[1];
				$subject = "Vote Confirmation";
				$info = "You voted for " . $nominee . " for " . $name . " on " . $row[2];
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