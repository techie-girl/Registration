<?php

// Start a PHP Session since we'll be passing the form values to a DB
// and a confirmation page should the form validate
session_start();

// Custom function to sanitize our data before sending it to the DB Server
function clean($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// Import our form validation code
//require('includes/formValidation.php');
// $_POST[] Superglobal can be used since our sign up form uses the POST method
// We are pulling all the data inserted into our form and assigning them to variables
// These values will also be accessible through our Session Superglobal Array
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];
$birthDate = '';
$nameError = '';
$emailError = '';
$passError = '';
$dateError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {			
// Flag variable to track success:
	$okay = TRUE;

	//Validate first and last name:
	if (empty($firstName)) {
		$fnameError = '<p class="fnError">Please provide your first name.</p>';
		$okay = FALSE;
	} else {
		//Our name should contain letters, -, a space, and '
		$firstName = clean($firstName);
		if (!preg_match("/^([a-zA-Z]+[\'-]?[a-zA-Z]+[ ]?)+$/",$firstName)) {
			$fnameError = '<p class="fnError">Only letters can be used.</p>';
			$okay = FALSE;
		}
	}
	if (empty($lastName)) {
		$lnameError = '<p class="lnError">Please provide your last name.</p>';
		$okay = FALSE;
	} else {
		//Our name should contain letters, -, a space, and '
		$lastName = clean($lastName);
		if (!preg_match("/^([a-zA-Z]+[\'-]?[a-zA-Z]+[ ]?)+$/",$lastName)) {
			$lnameError = '<p class="lnError">Only letters can be used.</p>';
			$okay = FALSE;
		}
	}
	// Validate the email address:
	if (empty($email)) {
		$emailError = '<p class="emailError">Please enter your email address.</p>';
		$okay = FALSE;
	} else {
		$email = clean($email);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailError = '<p class="emailError">Please enter a valid email address.</p>';
			$okay = FALSE;
		}
	}
	// Validate the password:
	if (empty($password)) {
		$passError = '<p class="passError">Please enter your password.</p>';
		$okay = FALSE;
	} else {
		$password = clean($password);
		// Check the two passwords:
		if ($password != $confirm) {
			$passError = '<p class="passError">Your confirmed password does not match the original password.</p>';
			$okay = FALSE;
		}
	}
	// Validate the birth date:
		// Validate the month:
		if (is_numeric($_POST['birthMonth'])) {
			$month = clean($_POST['birthMonth']);
			$birthDate = $month . '-';
		} else {
			$dateError = '<p>Please select a month</p>';
			$okay = FALSE;
		}
		// Validate the day:
		if (is_numeric($_POST['birthDay'])) {
			$day = clean($_POST['birthDay']);
			$birthDate .= $day . '-';
		} else {
			$dateError = '<p>Please select a day</p>';
			$okay = FALSE;
		}
		// Validate the year:
		if (is_numeric($_POST['birthYear'])) {
			$year = clean($_POST['birthYear']);
			$birthDate .= $year;
		} else {
			$dateError = '<p class="yearError">Please enter the year you were born.</p>';
			$okay = FALSE;
		}
	// If our form validates then send the values to our confirmation page
	if ($okay) {
		$_SESSION['register']['firstName'] = $firstName;
		$_SESSION['register']['lastName'] = $lastName;
		$_SESSION['register']['email'] = $email;
		$_SESSION['register']['password'] = $password; 
		$_SESSION['register']['birthDate'] = $birthDate;
		// We assign our sanitized variables to our session
		header('Location:confirmation.php');
		exit();
	}
}   


?>
<!DOCTYPE html>
<html lang="en">
<head>
	  <meta charset="utf-8">
	  <title>Registration Form Example</title>
	  <meta name="description" content="Title of Site">
	  <meta name="author" content="Author Name">
	  <link rel="stylesheet" href="css/normalize.css">
	  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
	<div id="wrap">
		<h1>Sign Up</h1>
	    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" id="register">
			<div id="name">
				<label for="firstName">
					<input type="text" placeholder="First Name" name="firstName" id="firstName" value="<?php echo $firstName; ?>">
				</label>
				<label for="lastName">
					<input type="text" placeholder="Last Name" name="lastName" id="lastName" value="<?php echo $lastName; ?>">
				</label>
				<div class="error"><?php echo $fnameError ?></div>
				<div class="error"><?php echo $lnameError ?></div>
			</div>
				<div id="clear">
				 <label for="email">
					<input type="text" placeholder="Email Address" name="email" id="email" value="<?php echo $email; ?>">
					<div class="error"><?php echo $emailError ?></div>
				 </label>
				</div>
				<div class="clear">
				 <label for="password">
					<input type="password" placeholder="Password" name="password" id="password">
					<div class="error"><?php echo $passError ?></div>
				 </label>
				</div>
				<div class="clear">
				 <label for="confirm">
					<input type="password" placeholder="Confirm Password" name="confirm" id="confirm">
				 </label>
				</div>
				<div class="clear">
				 <div class="birthDate">
					<select name="birthMonth" id="birthMonth">
						<option value="">Birth Month</option>
						<option value="01">January</option>
						<option value="02">February</option>
						<option value="03">March</option>
						<option value="04">April</option>
						<option value="05">May</option>
						<option value="06">June</option>
						<option value="07">July</option>
						<option value="08">August</option>
						<option value="09">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
					<select name="birthDay" id="birthYear">
						<option value="">Day</option>
						<?php
							//Print out 31 days:
							for ($d = 1; $d <=31; $d++){
								print "<option value=\"$d\">$d</option>\n";
							}
						?>
					</select>
				 </div>
				 <label for="birthYear">
					<input type="text" placeholder="Year" name="birthYear" id="birthYear" size="4" value="<?php echo $year; ?>">
					<div class="error"><?php echo $dateError ?></div>
				 </label>
				</div>
				<div class="clear"></div>
				 <div id="submit">
					<input type="submit" value="Register">
				 </div>
				<div class="clear"></div>
		</form>
	</div>
</body>
</html>
