<?php include 'server.php';
$_SESSION['first_order_mode'] = True;
?>

<html>

<!DOCTYPE HTML>

<head>
    <title>Elements - Introspect by TEMPLATED</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />

</head>
<!-- Header -->
			<header id="header">
				<div class="inner">
					<?php  if (isset($_SESSION['username'])) : ?>
    				Welcome <strong><?php echo $_SESSION['username']; ?></strong>
    
					<a href="index.php" class="logo">Eatogether</a>
					<nav id="nav">
						<a href="index.php">Home</a>
						<a href="aboutUs.php">About Us</a>
                        <a href="order_page.php">Order</a>
                        <a href="myPage.php">My Page</a>
						<a href="admin.php">Admin</a>
						<a href="index.php?logout='1'">logout</a>
    					<?php endif ?>
					</nav>
				</div>
			</header>
			<a href="#menu" class="navPanelToggle"><span class="fa fa-bars"></span></a>
				
<body>
    
    <!-- Main -->
    <section id="main">
        <div class="inner">
            <header class="select_restaurant">
                <h1>Group order</h1>

            </header>

            <!-- Lists -->
            <section>

                <div class="list_of_restaurant">

                    <h5>1. select the restarurant</h5>
                    <ul class="alt">

                        <!--- load restaurant name from database  -->

                        <?php 
                            $_SESSION['mode'] = 0;
                            $_SESSION['menu'] = array();
                            $q = 'SELECT * From Restaurant;';
                            $list_restaurant = array('--select restaurant--');
                            $result = $db -> query($q);

                            while($row = $result -> fetch_assoc()){
                                            
                            $list_restaurant[$row["rid"]] = $row["rname"];
//                                            array_push($list_restaurant, $row["rname"]);
                                            
                           }
                        ?>

                        <!--- Generate list of restaurants  -->
                        <form action="group_order_create_page2.php" method="post">
                            <!--                                        <input type="hidden" name="selected_rest" value="rest">-->
                            <select name="RESTAURANT">
                                <?php 
                                    foreach($list_restaurant as $key=>$val) {
                                     echo '<option value="' .$key;
//                                                if(mysql_fetch_row ($result) == 0){echo " disabled ";};
                                     echo '">';
                                     echo $val;
                                     echo "</option>\n";
                                   }
                                            
                                ?>
                            </select>&nbsp;<input type="submit" name="button" value="Select the menu >>" />
                        </form>

                        <p>
                            Go back to my page? <a href="myPage.php">My Page</a>
                        </p>

                        <!--- generate list of menu  -->


                        <!--http://devinfo99.blogspot.com/2016/05/dynamic-select-box-using-php.html-->


                    </ul>

                </div>

            </section>

            <!-- Table -->
        </div>
    </section>

</body>

</html>
