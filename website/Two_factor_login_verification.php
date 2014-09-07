<?php
	ob_start();
	
	// Step 1. Create a database connection
	$dbhost = "";
	$dbuser = "";
	$dbpass = "";
	$dbname = "";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	$message = "";
	
	if(mysqli_connect_errno()){
		die("Database connection failed: " . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")" );
	}
	else{
		//echo "Connection successful";
	}
	
	if(isset($_POST["submit"])){
		//	Form was submitted, now after assigning them, access the DB and see if they exist. If so, redirect to my site, else give an error
		$message = "Invalid username or password. Please try again";
		$username = $_POST["username"];
		$password = $_POST["password"];
	} 
	else{
		$message = "Please enter your login credentials.";
		$username = "";
		$password = "";
		}
	
	
?>

<?php
	//Step 2: Perform the database query
	$query = "SELECT *";
	$query .= "FROM login_credentials";
	
	$result = mysqli_query($connection, $query);
	
	//Test if there was a query error
	if(!$result){
		die("Database query failed");
	}
	
	
?>

<?php
//Check login credentials
	
	if(isset($_POST["submit"])){
	while($account = mysqli_fetch_assoc($result)) {
		$queriedUsername = $account['username'];
		$queriedPassword = $account['password'];
		$pnumber = $account['pnumber'];
		$verificationCode = rand(1000, 9999);
		
		if($username == $queriedUsername && $password == $queriedPassword){
			//Query into database and set the verifying-credentials field IN the DB to $verificationCode for the respective username
			$verificationQuery = "UPDATE login_credentials
					SET verification_code = $verificationCode
					WHERE username = '{$username}'";

			$verificationQueryExecution = mysqli_query($connection, $verificationQuery);

		require('twilio-php/Services/Twilio.php'); 
		 
		$account_sid = ''; 
		$auth_token = ''; 
		$client = new Services_Twilio($account_sid, $auth_token); 

		$textmessage = "Hello, Here is your verification code for user: {$username}

	Verification code: $verificationCode

	Have a great day!

	-Shafiq
		";


		$message = $client->account->messages->sendMessage(
		  '14703751625', // From a valid Twilio number
		  $pnumber, // Text this number
		  $textmessage
		);
			
			if(!$verificationQueryExecution){
				echo "Querying failed :(";
			}
			else{
			//Redirect to credentials page

			header("Location: ". "");
			}
		}
	}
		
	}
	ob_end_flush();
	?>

<html lang = "en">
	<head>
		<title>	Login Page </title>
		</head>
		<body bgcolor = wheat>
			<center>
			
			
			<form action = "index.php" method = "post">
			<h1><font color = "red"><u><b>Portal</u></b></h1><h3>To Enter my website, please enter the valid username and password: </font></h3><br>
			<b><?php echo $message . "<br>";?></b>
			Username: <input type = "text" name = "username" value = "<?php echo htmlspecialchars($username); ?>"/> <br>
			Password:<input type = "password" name = "password" value = ""/> <br>
			<input type = "submit" name = "submit" value = "Submit"/>
			<br><br>
			<font color = blue>Don't have an account yet? <a href = "accountCreation.php"><b>Click here to make one</b><br><br></a></font>
			<font color = blue>Forgot your password? <a href = "forgotPassword.php"><b>Click here to retrieve it</b></a></font>

			<br><br>
	
			</center>
		</body>
	</html>	
	
	<?php
		mysqli_close($connection);
	?>	