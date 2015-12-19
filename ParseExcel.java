import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Scanner;


public class ParseExcel {
	public static void main(String[] args) {
		Scanner input = new Scanner(System.in);
		System.out.println("Enter your file name: ");
		String fileName = input.next();
		input.close();
		HashMap<String, String> personHashMap = new HashMap<>();
		HashMap<String, String> movieHashMap = new HashMap<>();
		Scanner fileScanner = null;
		PrintWriter fileWriter = null;
		PrintWriter personWriter = null;
		PrintWriter movieWriter = null;
		try {
			fileScanner = new Scanner(new FileReader(fileName));
			fileWriter = new PrintWriter("output.txt");
			personWriter = new PrintWriter("personOut.txt");
			movieWriter = new PrintWriter("movieOut.txt");
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		String academy = "AMPAS";
		String awardQuery = "INSERT INTO Award values (";
		String personQuery = "INSERT INTO Person values (";
		String filmQuery = "INSERT INTO Film values (";
		int awardID = 100;
		int movieID = 100;
		int personID = 100;
		fileScanner.nextLine();
		
		while (fileScanner.hasNextLine()) {
			String line = fileScanner.nextLine();
			String year = line.substring(0,4);
			int yearNum = Integer.parseInt(year);
			if (yearNum < 2000) {
				fileWriter.close();
				fileScanner.close();
				Iterator<String> personList = personHashMap.keySet().iterator();
				while (personList.hasNext()) {
					String name = personList.next();
					if (name != null) {
						String email = personHashMap.get(name);
						personWriter.println(name + ", " + email);
					}
				}
				personWriter.close();
				
				Iterator<String> movieList = movieHashMap.keySet().iterator();
				while (movieList.hasNext()) {
					String name = movieList.next();
					if (name != null) {
						String id = movieHashMap.get(name);
						movieWriter.println(name + ", " + id);
					}
				}
				movieWriter.close();
				return;
			}
			
			line = line.substring(line.indexOf(",") + 1);
			String category = line.substring(0, line.indexOf(","));
			if (category.contains("Award") || category.contains("Medal")) {
				continue;
			}
			else if (category.contains("Act") && !category.contains("Action")){
				//Person
				line = line.substring(line.indexOf(",") + 1);
				String person = line.substring(0, line.indexOf(","));
				
				if (person.contains("'")) {
					person = person.substring(0, person.indexOf("'")) + "'" + person.substring(person.indexOf("'"));
				}
				
				Scanner personScanner = new Scanner(person);
				String fname = personScanner.next();
				personScanner.close();
				if (!personHashMap.containsKey(person)) {
					personHashMap.put(person, fname+personID+"@jhu.edu");
				}
				
				String sex = "";
				if (category.contains("Actor")) {
					sex = "M";
				} else {
					sex = "F";
				}
				
				line = line.substring(line.indexOf(",") + 1);
				String filmName = line.substring(0, line.indexOf("{") - 1);
				
				String firstChar = filmName.charAt(0) + "";
				if (firstChar.equals("\"")) {
					filmName = filmName.substring(1);
				}
				
				if (filmName.contains("'")) {
					int index = filmName.indexOf("'");
					filmName = filmName.substring(0, index) + "'" + filmName.substring(index);
				}
				
				if (!movieHashMap.containsKey(filmName)) {
					
					movieHashMap.put(filmName, movieID + "");
					movieID++;
				}
				
				String decision = line.substring(line.length() - 7);
				if (decision.contains("O")) {
					//NO
					String tempAwardQuery = awardQuery + "'" + awardID + "', '" + academy 
							+ "', '" + category + "', " + yearNum+ ", 'NO', 0 );";
					System.out.println(tempAwardQuery);
					fileWriter.println(tempAwardQuery);
					
				} else {
					//YES
					String tempAwardQuery = awardQuery + "'" + awardID + "', '" + academy 
							+ "', '" + category + "', " + yearNum+ ", 'YES', 0 );";
					System.out.println(tempAwardQuery);
					fileWriter.println(tempAwardQuery);
				}
				
				String tempPersonQuery = personQuery + "'" + personHashMap.get(person) 
						+ "', '" + person + "', '" + sex + "', NULL, 'Actor', '"
						+ awardID + "', '" + movieHashMap.get(filmName) + "', NULL, 'NO','" +
						personHashMap.get(person) + "' );";
				System.out.println(tempPersonQuery);
				fileWriter.println(tempPersonQuery);
				personID++;
				
				String tempMovieQuery = filmQuery + "'" + movieHashMap.get(filmName)
						+ "', '" + filmName + "', " + yearNum + ", 5, NULL, NULL, NULL, 0, 0, '" 
						+ awardID + "' );";
				System.out.println(tempMovieQuery);
				fileWriter.println(tempMovieQuery);
				movieID++;
			} else if (category.contains("Directing")) {
				//Director
				line = line.substring(line.indexOf(",") + 1);
				String filmName = line.substring(0, line.indexOf(","));
				line = line.substring(line.indexOf(",") + 1);
				String person = line.substring(0, line.indexOf(","));
				
				if (person.contains("'")) {
					person = person.substring(0, person.indexOf("'")) + "'" + person.substring(person.indexOf("'"));
				}
				
				Scanner personScanner = new Scanner(person);
				String fname = personScanner.next();
				personScanner.close();
				if (!personHashMap.containsKey(person)) {
					personHashMap.put(person, fname+personID+"@jhu.edu");
				}
				
				String sex = "M";
				
				line = line.substring(line.indexOf(",") + 1);
				
				String firstChar = filmName.charAt(0) + "";
				if (firstChar.equals("\"")) {
					filmName = filmName.substring(1);
				}
				
				if (filmName.contains("'")) {
					int index = filmName.indexOf("'");
					filmName = filmName.substring(0, index) + "'" + filmName.substring(index);
				}
				
				if (!movieHashMap.containsKey(filmName)) {
					
					movieHashMap.put(filmName, movieID + "");
					
					movieID++;
				}
				
				String decision = line.substring(line.length() - 7);
				if (decision.contains("O")) {
					//NO
					String tempAwardQuery = awardQuery + "'" + awardID + "', '" + academy 
							+ "', '" + category + "', " + yearNum+ ", 'NO', 0 );";
					System.out.println(tempAwardQuery);
					
				} else {
					//YES
					String tempAwardQuery = awardQuery + "'" + awardID + "', '" + academy 
							+ "', '" + category + "', " + yearNum+ ", 'YES', 0 );";
					System.out.println(tempAwardQuery);
				}
				
				String tempPersonQuery = personQuery + "'" + personHashMap.get(person) 
						+ "', '" + person + "', '" + sex + "', NULL, 'Director', '"
						+ awardID + "', '" + movieHashMap.get(filmName) + "', NULL, 'NO','" +
						personHashMap.get(person) + "' );";
				System.out.println(tempPersonQuery);
				fileWriter.println(tempPersonQuery);
				personID++;
				
				String tempMovieQuery = filmQuery + "'" + movieHashMap.get(filmName)
						+ "', '" + filmName + "', " + yearNum + ", 5, NULL, NULL, NULL, 0, 0, '" 
						+ awardID + "' );";
				System.out.println(tempMovieQuery);
				fileWriter.println(tempMovieQuery);
				movieID++;
			} else {
				//Movie
				line = line.substring(line.indexOf(",") + 1);
				String filmName = line.substring(0, line.indexOf(","));
				
				String firstChar = filmName.charAt(0) + "";
				if (firstChar.equals("\"")) {
					filmName = filmName.substring(1);
				}
				
				if (filmName.contains("'")) {
					int index = filmName.indexOf("'");
					filmName = filmName.substring(0, index) + "'" + filmName.substring(index);
				}
				
				if (!movieHashMap.containsKey(filmName)) {
					
					movieHashMap.put(filmName, movieID + "");
				}
				
				String decision = line.substring(line.length() - 7);
				if (decision.contains("O")) {
					//NO
					String tempAwardQuery = awardQuery + "'" + awardID + "', '" + academy 
							+ "', '" + category + "', " + yearNum+ ", 'NO', 0 );";
					System.out.println(tempAwardQuery);
					fileWriter.println(tempAwardQuery);
				} else {
					//YES
					String tempAwardQuery = awardQuery + "'" + awardID + "', '" + academy 
							+ "', '" + category + "', " + yearNum+ ", 'YES', 0 );";
					System.out.println(tempAwardQuery);
					fileWriter.println(tempAwardQuery);
				}
				
				String tempMovieQuery = filmQuery + "'" + movieHashMap.get(filmName)
						+ "', '" + filmName + "', " + yearNum + ", 5, NULL, NULL, NULL, 0, 0, '" 
						+ awardID + "' );";
				System.out.println(tempMovieQuery);
				fileWriter.println(tempMovieQuery);
				movieID++;
			}
			
			awardID++;
		}
	}
}
