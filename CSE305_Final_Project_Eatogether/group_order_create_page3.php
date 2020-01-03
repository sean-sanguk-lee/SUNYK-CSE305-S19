<?php include 'server.php'; ?>
<!DOCTYPE HTML>

<!-- Header -->
<html>

<head>
    <title>Eatogether</title>
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
            <header class="select_location">
                <h1>Split order</h1>

            </header>

            <!-- Lists -->
            <section>

                <div class="row">
                    <div class="list_of_location">

                        <h5>3. Select the location</h5>
                        <ul class="alt">

                            <!--- load restaurant name from database  -->

                            <?php 
                                $q = 'SELECT * from location;';
                                $result = $db -> query($q);

                                $list_location = array();

                                while($row = $result -> fetch_assoc()){
                                    array_push($list_location, $row["location"]);
                                }   
            
                                ?>

                            <!--- Generate list of restaurants  -->
                            <form action="payment.php" method="post">
                                <select name="location">

                                    <?php
                                        $key = 0;
                                        foreach($list_location as $val) {
                                            echo '<option value="' .$val. '">';
                                            echo $val;
                                            echo "</option>\n";
                                            $key +=1;
                                        }

                                        ?>
                                </select>&nbsp;<input type="submit" name="button" value="Payment >> " />
                            </form>
                            <p>
                                <!--                                        Go back to order page<a href="split_order_create_page2.php">Order</a> <br>-->
                                Go back to my page? <a href="myPage.php">My Page</a>
                            </p>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </section>
</body>

</html>
