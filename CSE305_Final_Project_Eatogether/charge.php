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
  	<h2>Charge Coin</h2>
  </div>
	
  <form method="post" action="charge.php">
  	<?php include('errors.php'); ?>
  	<div>
  	  <label>Enter amount to charge</label>
  	  <input type="text" name="charge">
  	</div>
	  
	  <br>
  	<div>
  	  <button type="submit" class="btn" name="charge_coin">Charge</button>
  	</div>
  	<p>
  		Go back to my page? <a href="myPage.php">My Page</a>
  	</p>
  </form>
			</div>
	</section>
</body>
</html>