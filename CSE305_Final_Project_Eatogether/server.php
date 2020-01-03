<?php
session_start();

// initializing variables
$username = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', 'root', 'CSE305_Final');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM USERS WHERE username='$username' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO USERS (username, pw, coin) 
  			  VALUES('$username', '$password', '0')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM USERS WHERE username='$username' AND pw='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

// CHARGE COIN
if (isset($_POST['charge_coin'])){
	$charge = mysqli_real_escape_string($db, $_POST['charge']);
	$user = $_SESSION['username'];
	$query = "UPDATE USERS SET coin = coin + $charge WHERE username = '$user'";
	$db->query($query);
}

// ADD COUPON
if (isset($_POST['add_coupon'])){
	$amount = mysqli_real_escape_string($db, $_POST['amount']);
	$username = mysqli_real_escape_string($db, $_POST['username']);
	
	if (empty($amount)) {
  	array_push($errors, "Amount is required");
  }
  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
	
	$query1 = "INSERT INTO COUPON (price) VALUES('$amount')";
	mysqli_query($db, $query1);

	$query2 = "Select Max(coupon_code) FROM COUPON";
	$result2 = mysqli_query($db, $query2);
	$coupon_code = "";
	if (mysqli_num_rows($result2) == 1) {
  	   $coupon= mysqli_fetch_assoc($result2);
		$coupon_code = $coupon["Max(coupon_code)"];
  	}
	
	$query3 = "SELECT uid FROM USERS WHERE username = '$username'";
	$result3 = mysqli_query($db, $query3);
	$uid = "";
	if (mysqli_num_rows($result3) == 1) {
  	   $uidfetch = mysqli_fetch_assoc($result3);
		$uid = $uidfetch["uid"];
  	}
	
	$query4 = "INSERT INTO owns (uid, coupon_code) VALUES('$uid', '$coupon_code')";
	mysqli_query($db, $query4);
}

?>