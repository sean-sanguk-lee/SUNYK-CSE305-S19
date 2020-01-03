<?php include('server.php') ?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Eatogether</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
<body>
	<section id="main">
		<div class="inner">
  <div class="header">
  	<h2>Register</h2>
  </div>
	
  <form method="post" action="register.php">
  	<?php include('errors.php'); ?>
  	<div>
  	  <label>Username</label>
  	  <input type="text" name="username" value="<?php echo $username; ?>">
  	</div>
  	<div>
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div>
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>
	  <br>
  	<div>
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
  	<p>
  		Already a member? <a href="login.php">Sign in</a>
  	</p>
  </form>
		</div>
	</section>
</body>
</html>