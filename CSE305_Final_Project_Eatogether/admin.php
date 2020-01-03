<?php

include 'template.php'; 

if (!($_SESSION['username'] == 'admin')) {
  	
	echo "<script>alert(\"You are not admin. Login with ID: admin\");window.location.href = 'index.php'</script>";
  }
?>

<!-- Table -->
					<section id="main">
		<div class="inner">
							<h3>You are Admin<br></h3>
					
							<?php
					$db = mysqli_connect('localhost', 'root', 'root', 'CSE305_Final');
							
						$query = "SELECT username, price, expires FROM COUPON C, OWNS O, USERS U WHERE C.coupon_code = O.coupon_code AND O.uid = U.uid";
						
							$result = $db->query($query);
							echo "<h2>Coupon list</h2><div class='table-wrapper'>";
								echo "<table>
							<thead>
										<tr><th>Username</th>
											<th>Coupon Price</th>
											<th>Expire Date</th>
										</tr>
									</thead>
									<tbody>";
							if ($result->num_rows > 0) {
              					while($row = $result->fetch_assoc()) {
									echo "<tr><td>" .$row["username"] ."</td><td>" .$row["price"] ."</td><td>" . $row["expires"]. " </td></tr>";
            				}
							}
							echo <<<_END
							</tbody>
								</table>
								</div>
							_END;
			
			$query2 = "SELECT username, coin FROM USERS";
						
				$result2 = $db->query($query2);
			
			echo "<h2>User list</h2><div class='table-wrapper'>";
								echo "<table>
							<thead>
										<tr><th>Username</th>
											<th>Coin</th>
										</tr>
									 </thead>
									<tbody>";
							if ($result2->num_rows > 0) {
              					while($row = $result2->fetch_assoc()) {
									echo "<tr><td>" .$row["username"] ."</td><td>" .$row["coin"] ."</td></tr>";
            				}
							}
							echo <<<_END
							</tbody>
								</table>
								</div>
							_END;
						?>
										
			<p><a href='addCoupon.php' class='button'>Add Coupon</a></p>	
						</div>
						</section>

	</body>
</html>