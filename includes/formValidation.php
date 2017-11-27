<?php

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
		$fnameError = '<p class="error"> Please provide your first name.</p>';
		$okay = FALSE;
	} else {
		//Our name should contain letters, -, a space, and '
		$firstName = clean($firstName);
		if (!preg_match("/^([a-zA-Z]+[\'-]?[a-zA-Z]+[ ]?)+$/",$firstName)) {
			$fnameError = '<p class="error"> Only letters can be used.</p>';
			$okay = FALSE;
		}
	}
	if (empty($lastName)) {
		$lnameError = '<p class="error"> Please provide your last name.</p>';
		$okay = FALSE;
	} else {
		//Our name should contain letters, -, a space, and '
		$lastName = clean($lastName);
		if (!preg_match("/^([a-zA-Z]+[\'-]?[a-zA-Z]+[ ]?)+$/",$lastName)) {
			$lnameError = '<p class="error"> Only letters can be used.</p>';
			$okay = FALSE;
		}
	}
	// Validate the email address:
	if (empty($email)) {
		$emailError = '<p class="error"> Please enter your email address.</p>';
		$okay = FALSE;
	} else {
		$email = clean($email);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailError = '<p class="error"> Please enter a valid email address.</p>';
			$okay = FALSE;
		}
	}
	// Validate the password:
	if (empty($password)) {
		$passError = '<p class="error"> Please enter your password.</p>';
		$okay = FALSE;
	} else {
		$password = clean($password);
		// Check the two passwords:
		if ($password != $confirm) {
			$passError = '<p class="error">Your confirmed password does not match the original password.</p>';
			$okay = FALSE;
		}
	}
	// Validate the birth date:
		// Validate the month:
		if (is_numeric($_POST['birthMonth'])) {
			$month = clean($_POST['birthMonth']);
			$birthDate = $month . '-';
		} else {
			$dateError = '<p class="error"> Please select a month</p>';
			$okay = FALSE;
		}
		// Validate the day:
		if (is_numeric($_POST['birthDay'])) {
			$day = clean($_POST['birthDay']);
			$birthDate .= $day . '-';
		} else {
			$dateError = '<p class="error"> Please select a day</p>';
			$okay = FALSE;
		}
		// Validate the year:
		if (is_numeric($_POST['birthYear'])) {
			$year = clean($_POST['birthYear']);
			$birthDate .= $year;
		} else {
			$dateError = '<p class="error">Please select the year you were born as four digits.</p>';
			$okay = FALSE;
		}
	// If our form validates then send the values to our confirmation page
	if ($okay) {
		// We assign our sanitized variables to our session
		header('Location:confirmation.php');
		exit();
	}
}   

?>