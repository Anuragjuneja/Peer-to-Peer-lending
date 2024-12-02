<?php
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];


	//If user login for the first time
	if (filesize('passwords.txt') === 0) {
	    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
	    $file = fopen("passwords.txt", "a");

	    if ($file) {
		fwrite($file, "Username: $username, Password: $hashed_password\n");
		fclose($file);
		header('Location: changePassword.html');
		echo "Data successfully saved!";
	    } else {
		echo "Unable to open file!";
	    }
	} 
	
	//If it is not the user's first login
	else {
	    //match the username and password
	     $file = fopen("passwords.txt", "r");
	    if ($file) {
			$savedUserName = null;
			$savedPassword = null;

			// Read the file line by line
			while (($line = fgets($file)) !== false) {
				    // Remove any extraneous whitespace or newline characters
				    $line = trim($line);

				    // Split the line by ","
				    $parts = explode(',', $line);

				    // Loop through each part to find the password
				    foreach ($parts as $part) {
					$part = trim($part); // Remove any extra whitespace
					if (strpos($part, 'Username:') === 0) {
					    // Extract the username
					    $savedUserName = trim(substr($part, strlen('Username:')));
					}
					else if (strpos($part, 'Password:') === 0) {
					    // Extract the password
					    $savedPassword = trim(substr($part, strlen('Password:')));
					}
				    }
			}

			// Close the file
			fclose($file);
			
			if (!password_verify($password, $savedPassword)) {
				echo("Incorrect password <br>");
			}
			else if($savedUserName !== $username){
				echo("Incorrect username <br>");
			}
			else{
				header('Location: editPublication.php');
			}
		}
	}
}
else {
    echo "Invalid request method.";
}
?>
