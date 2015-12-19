<html>
<body bgcolor="FFD700">
<div class="nav-wrapper">
	<div class="nav-bar">
		<h1 align="center">Jeffrey + Tyler's MOVIE AWARDS Database Website</h1>
		<h2 align="center">jsham2, tlee93</h2>
	</div>
</div>

<a href="edit_profile.html">Edit Profile</a>

<?php
	session_start();
	$role = $_SESSION["user_role"];
	if (strcmp($role, "Director") == 0) {
		echo "<a href=\"edit_film.php\">Edit Film</a>";
	}
	exit();
?>

</body>
</html>