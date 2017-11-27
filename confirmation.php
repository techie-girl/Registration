<?php

// We start a PHP Session since we need to use the values from our registration form
// we will display the values to the user as a 'confrimation page' and use them
// to create a new user in our database
session_start();

// We don't want users coming to the confirmation page without registering
if (!isset($_SESSION['register'])){
	header('Location:registration.php');
	exit();
}

// Import the db connection
require_once('db/dbconnection.php');

// We assign our session variables to our variables
$firstName = $_SESSION['register']['firstName'];
$lastName = $_SESSION['register']['lastName'];
$email = $_SESSION['register']['email'];
$birthDate = $_SESSION['register']['birthDate'];

$salt = '378570bdf03b25c8efa9bfdcfb64f99e';
$hash = hash_hmac('md5', $_SESSION['register']['password'], $salt);


try {
	$query1 = 'INSERT INTO users_info (firstName, lastName, email, birthDate) VALUES (?, ?, ?, ?)';
	$statement = $pdo->prepare($query1);
	$statement->execute(array("$firstName", "$lastName", "$email", "$birthDate"));
	
	$query2 = 'INSERT INTO users (username, password) VALUES (?, ?)';
	$statement = $pdo->prepare($query2);
	$statement->execute(array("$email", "$hash"));		
}
catch (PDOException $e){
	$output = 'Unable to insert data into the database ' . $e->getMessage();
	exit();
}

echo '<p class="successMSG">Successfully registered user</p>';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	  <meta charset="utf-8">
	  <title>Confirmation</title>
	  <meta name="description" content="Title of Site">
	  <meta name="author" content="Author Name">
	  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
	<div id="wrap">
		<h2>Thank You for Registering</h2>
		<p>The following User Account was created</p>
		<p><em>Name:</em> <span class="info"><?php echo $firstName . ' ' . $lastName; ?></span></p>
		<p><em>Username:</em> <span class="infoEmail"><?php echo $email; ?></span></p>
		<p><em>Birth Date:</em> <span class="info"><?php echo $birthDate; ?></span></p>
	</div>
</body>
</html>
