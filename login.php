<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
	session_start();
	$mysqli = new mysqli("dbase.cs.jhu.edu", "cs41515_jsham2", "PTIASBIT", "cs41515_jsham2_db");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s<br>", mysqli_connect_error());
		exit();
	}
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	if ($mysqli->multi_query("CALL Login('".$email."', '".$password."');")) {
		if ($result = $mysqli->store_result()) {
			if ($row = $result->fetch_row()) {
				printf("Login Successful. Redirecting...");
				$_SESSION["email_addr"] = $email;
				$_SESSION["user_role"] = $row[0];
				header("Location: loggedIn.php");
				session_write_close();
			} else {
				printf("Login Unsuccessful. Redirecting...");
				header("Location: index.php");
			}
		} else {
				printf("Login Unsuccessful. Redirecting...");
				header("Location: index.php");
		}
	} else {
		printf("Login Unsuccessful. Redirecting...");
		header("Location: index.php");
	}
?>
</body>
</html>