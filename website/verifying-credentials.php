<?php /*
@author: Shafiq Mohammed
Description: If the user comes to this page, it means they have successfully entered the proper username and password in the 
Two_factor_login_verification.php file. 
If that indeed was the case, then the user has been texted their 4-digit PIN code. In this page, if they enter it correctly,
then they will gain access to my website. Else the system won't log them in.

*/

?>
<?php
	ob_start();
	$dbhost = "";
	$dbuser = "";
	$dbpass = "";
	$dbname = "";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	$message = "";
	
	if(mysqli_connect_errno()){
		die("Database connectin failed: " . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")" );
	}


	//This part get's the verification code from my DB.
	$username = $_GET['username'];


	$query = "SELECT verification_code, pnumber
			  FROM login_credentials
			  WHERE username = '{$username}'";
	
	$userInfoInAssocArray = mysqli_fetch_assoc(mysqli_query($connection, $query));
	$verification_code = $userInfoInAssocArray['verification_code'];
	$pnumber = $userInfoInAssocArray['pnumber'];

//This part determines whether the code was indeed the code that was sent to the user.
	if(isset($_POST['submit'])){
		if($_POST['code'] == $verification_code){
			header("Location: ". "http://www.shafiqmohammed.com/homepage.php");
		}
		else{
			$errorMessage = "Incorrect code entered. Please try again.";
		}
	}
	
	ob_end_flush(); 
?>

<?php


<html>
	<title>Credential Verification</title>
	<center>
		<body bgcolor = 'wheat'>
		<font color = red>
			<h1><u>Please Verify Your Credentials</u></h1><br>
			<h3>A text was sent to your number containing your verification code. Please enter the code below. This feature is in place for security purposes.</h3><br>

			<form action = "verifying-credentials.php?username=<?php echo $username ?>" method = "post">
			<!--	<form action = "homepage.php" method = "post"> -->
				<input type = "text" name = "code" value = ""/><br>
				<input type = "submit" name = "submit" value = "Submit"/><br>
				<?php
					echo "<br>".$errorMessage."<br>";
				?>
			</form>
		</font> 
		</body>
	</center>
	</html>

