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
  	<h2>Add Coupon</h2>
  </div>
	
  <form method="post" action="addCoupon.php">
  	<?php include('errors.php'); ?>
  	<div>
  	  <label>Select coupon value</label>
  	  <select name="amount" size="4" multiple>
  		<option value="1000">1000</option>
  		<option value="3000">3000</option>
  		<option value="4000">4000</option>
  		<option value="10000">10000</option>
		</select>
	  </div>
	<div>
  	  <label>Enter Username</label>
  	  <input type="text" name="username">
  	</div>
	  
	  <br>
  	<div>
  	  <button type="submit" class="btn" name="add_coupon">Add Coupon</button>
  	</div>
  	<p>
  		Go back to my page? <a href="myPage.php">My Page</a>
  	</p>
  </form>
			</div>
	</section>
</body>
</html>