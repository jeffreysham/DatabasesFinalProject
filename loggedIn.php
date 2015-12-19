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
		<h3 align="center">Successfully Logged In!</h3>
	</div>
</div>

<div class="basic_search">
		<form action="basic_search.php" method="post" accept-charset="utf-8">
			<select name="search_select">
				<option value="person">Person</option>
				<option value="film">Film</option>
				<option value="award">Award</option>
				<option value="place">Place</option>
			</select>
			<label for="text">Search: </label>
			<input type="search" name="search" placeholder="Search..">
			<br>
			<input type="submit" value="Search">
		</form>
		<a href="set_up_adv_search.html">Advanced Search</a>
</div>

<a href="edit_info.php">Edit Information</a>
<form action="vote.php" method="post" accept-charset="utf-8">
			<label for="text">Select the year to vote for: </label>
			<select name="year_select">
				<option value="2011">2011</option>
				<option value="2010">2010</option>
				<option value="2009">2009</option>
				<option value="2008">2008</option>
				<option value="2007">2007</option>
				<option value="2006">2006</option>
				<option value="2005">2005</option>
				<option value="2004">2004</option>
				<option value="2003">2003</option>
				<option value="2002">2002</option>
				<option value="2001">2001</option>
				<option value="2000">2000</option>
			</select>
			<br>
			<input type="submit" value="Submit">
</form>

<h2 align="center">Sample Queries</h2>

<?php	
	$mysqli = new mysqli("dbase.cs.jhu.edu", "cs41515_jsham2", "PTIASBIT", "cs41515_jsham2_db");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s<br>", mysqli_connect_error());
		exit();
	}
	
	printf("<h3>List all the Oscar nominated actors that were born in Spain.</h3>");
	
	if ($mysqli->multi_query("SELECT P.Name, A.Name, A.Year, A.Win, Pl.City, Pl.State, Pl.Country
							FROM Person as P, Place as Pl, Award as A
							WHERE P.NominatedFor = A.ID AND
							P.BirthPlace = Pl.ID AND
							Pl.Country = 'Spain' AND
							A.Agency = 'AMPAS';")) {
		printf("<table><tr><th>Actor Name</th><th>Award Name</th><th>Year</th><th>Win</th><th>City</th><th>State</th><th>Country</th></tr>");
		do {
			if ($result = $mysqli->store_result()) {
				
				while ($row = $result->fetch_row()) {
					printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row[0], $row[1], $row[2],
					$row[3], $row[4], $row[5],$row[6]); 
				}
				$result->close();
				
			}
		} while ($mysqli->next_result());
		printf("</table>");
	} else {
		printf("No results");
	}
	
	printf("<h3>List the movies that were nominated for at least four Oscars.</h3>");
	
	if ($mysqli->multi_query("SELECT COUNT(F.NominatedFor) as NumNoms, F.Name, A.Year
					FROM Film as F, Award as A
					WHERE A.ID = F.NominatedFor AND
						A.Agency = 'AMPAS'
					GROUP BY F.ID
					HAVING NumNoms > 4
					ORDER BY NumNoms ASC;")) {
		printf("<table><tr><th>Number of Nominations</th><th>Film Name</th><th>Year</th></tr>");
		do {
			if ($result = $mysqli->store_result()) {
				
				while ($row = $result->fetch_row()) {
					printf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>", $row[0], $row[1], $row[2]); 
				}
				$result->close();
				
			}
		} while ($mysqli->next_result());
		printf("</table>");
	} else {
		printf("No results");
	}
	
	printf("<h3>List the actors who have been nominated for an Oscar at age 30 or younger and are from New York</h3>");
	
	if ($mysqli->multi_query("SELECT Y.Pname as NomineeName, A.Year-Y.Birthyear as Age, Y.Fname as FilmTitle, A.Win as Win
					FROM 	(SELECT DISTINCT EXTRACT(year from P.DOB) as Birthyear, P.Name as Pname, P.NominatedFor as NomFor, F.Name as Fname
							FROM Person as P, Film as F, Place as Pl
							WHERE P.DOB IS NOT NULL
							AND P.InFilm = F.ID
							AND Pl.ID = P.BirthPlace
							AND Pl.State = 'New York') as Y, Award as A
					WHERE A.Agency = 'AMPAS' AND
						A.ID = Y.NomFor
					HAVING Age < 31
					ORDER BY Age ASC;")) {
		printf("<table><tr><th>Name of Nominee</th><th>Age</th><th>Film Title</th><th>Win</th></tr>");
		do {
			if ($result = $mysqli->store_result()) {
				
				while ($row = $result->fetch_row()) {
					printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row[0], $row[1], $row[2], $row[3]); 
				}
				$result->close();
				
			}
		} while ($mysqli->next_result());
		printf("</table>");
	} else {
		printf("No results");
	}
	
	printf("<h3>List the actors that won the Golden Globe for best supporting actor but lost the Oscar for best supporting actor for the same performance.</h3>");
	
	if ($mysqli->multi_query("SELECT DISTINCT F1.Name as Title, F1.Year, P1.Name as Actor
					FROM Film F1, Film F2, Person P1, Person P2, Award A1, Award A2
					WHERE A1.Agency = 'HFPA'
					and A2.Agency = 'AMPAS'
					and A1.ID != A2.ID
					and A1.Name = 'Best Performance by an Actor In A Supporting Role'
					and A2.Name =  'Actor -- Supporting Role'
					and A1.Win = 'YES'
					and A2.Win = 'NO'
					and P1.NominatedFor = A1.ID
					and P1.InFilm = F1.ID
					and F1.ID = F2.ID
					and P1.Email = P2.Email
					and P2.NominatedFor = A2.ID
					and P2.InFilm = F2.ID
					GROUP BY F1.Name;")) {
		printf("<table><tr><th>Film Name</th><th>Year</th><th>Actor Name</th></tr>");
		do {
			if ($result = $mysqli->store_result()) {
				
				while ($row = $result->fetch_row()) {
					printf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>", $row[0], $row[1], $row[2]); 
				}
				$result->close();
				
			}
		} while ($mysqli->next_result());
		printf("</table>");
	} else {
		printf("No results");
	}
	
	printf("<h3>List the foreign born women who have been nominated for an Oscar for Best Supporting Actress in multiple years.</h3>");
	
	if ($mysqli->multi_query("SELECT DISTINCT F1.Name as Title, F1.Year, P1.Name as Actress, Pl.Country, A1.Win
				FROM Film as F1, Film as F2, Person as P1, Person as P2, Place as Pl, Award as A1, Award as A2
				WHERE A1.Agency = 'AMPAS' AND A2.Agency = 'AMPAS'
				AND A1.Name = 'Actress -- Supporting Role' AND A2.Name = 'Actress -- Supporting Role'
				AND A1.ID != A2.ID
				AND A1.ID = P1.NominatedFor AND A2.ID = P2.NominatedFor
				AND P1.Email = P2.Email AND P1.NominatedFor != P2.NominatedFor
				AND P1.InFilm = F1.ID AND P2.InFilm = F2.ID
				AND P1.BirthPlace = Pl.ID AND Pl.Country != 'USA'
				GROUP BY F1.Name
				ORDER BY P1.Name ASC;")) {
		printf("<table><tr><th>Film Name</th><th>Year</th><th>Actress Name</th><th>Country</th><th>Win</th></tr>");
		do {
			if ($result = $mysqli->store_result()) {
				
				while ($row = $result->fetch_row()) {
					printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row[0], $row[1], $row[2], $row[3], $row[4]); 
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