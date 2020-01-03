<?php 


include 'server.php';

$user = $_SESSION['username'];
// get user coin info from db
$query  = "SELECT * FROM Users WHERE Users.username = '$user';";
$result = $db->query($query);
$row = $result->fetch_assoc();
$user_coin = $row["coin"];

// get uid from db
$q =  "select users.uid from users where users.username = '$user';";
$result = $db -> query($q);
$row = $result -> fetch_assoc();
$uid = $row['uid'];
$prices = array();
//    echo $uid;
//foreach($_SESSION["menu"] as $k => $v){
//    echo $k." => ".$v;
//}
$_SESSION['total_price'] = 0;

?>

<?php


if(isset($_POST['location'])){
    $_SESSION['lid'] = $_POST['location'];
//    $q = "select lid from location where location = '$location_name';";
//    $result = $db -> query($q);
//    $row = $result -> fetch_assoc();
//    $_SESSION['lid'] = $row['lid'];
    //echo "lid".$_SESSION['lid'];
}
?>


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

<!-- Main -->
<section id="main">
    <div class="inner">
        <header class="select_restaurant">
            <h1>PAYMENT</h1>

        </header>

        <!-- Lists -->
        <section>


            <ul class="alt">
                <div class="table-wrapper">

                    <form name="itemForm" method="post">

                        <table>
                            <thead>
                                <tr>
                                    <th align='center'>Your Orders</th>
                                    <th>num</th>
                                    <th>total price </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

            $selected_menu =[];
            $total = 0;
            //    unset($_SESSION['menu']['']);
            foreach($_SESSION['menu'] as $menu => $num){
//                                                    echo $menu." ";
//                                                    echo $num."<br>";
                // A. get the total price
                if($num != 0){
//                   echo $num."<br>";
                    $q  = "select price from menu where menu.menu = '$menu';";
                    $result = $db->query($q);
                    $selected_menu[$menu] = $num;
//                    $row = $result->fetch_assoc();
                    if($result != null){
                        $row = $result->fetch_assoc();

//                        $selected_menu[$menu] = $num;
                        $prices[$menu] = $row['price'];
                        echo $row['price'];

                        $total = (int)$num * $row['price'];
                        //                                            echo "<br>".$num."<br>";
                        //                                            echo $row['price']."<br>";
//                        echo $total;
                        $_SESSION['total_price'] += $total;
                    }
                }

            }

            $meun = $_SESSION['menu'];
            $_SESSION['selected_menu'] = $selected_menu;

                ?>

                                <?php 

                                foreach($selected_menu as $menu => $num): ?>
                                <tr>
                                    <td align='center'><?=$menu;?></td>
                                    <td><?=$num;?></td>
                                    <td><?=$prices[$menu];?> </td>
                                </tr>
                                <?php endforeach;?>


                            </tbody>
                        </table>


                    </form>
                    <!-- create coupon list-->

                    <?php 

                    $q = "select coupon_code, price from coupon Natural Join Owns where Owns.uid = $uid;";

                    $result = $db -> query($q);
                    $coupons = array();
					if($result != NULL){
                    while($row = $result -> fetch_assoc()){
                        $coupons[$row['coupon_code']] = $row['price'];
                    }
					}
                    if(isset($_POST['coupon'])){

                        $selected_coupon = $_POST['coupon'];
                    //    echo "select ".$selected_coupon;
                        $_SESSION['selected_coupon'] = $selected_coupon;
                        $selected_coupon_price = $coupons[$selected_coupon];
                                           $total = $_SESSION['total_price'];
                        $total -= $selected_coupon_price;
                        $_SESSION['total_price'] = $total;
                        //                            echo $total;
                        //                            echo $_SESSION['total_price'];
                    }    
                    ?>

                    <!-- coupons drop down list -->

                    <form name="coupon_list" method="post">
                        <select name="coupon">
                            <?php

                            foreach($coupons as $couponCode => $p) {
                                if($total > $p){
                                    echo '<option value="' .$couponCode. '">';
                                    echo "Coupon Code : ".$couponCode." | Discount : ".$p;
                                    echo "</option>\n";
                                }
                            }
                            ?>
                        </select>&nbsp;<input type="submit" name="button" value="Select Coupon">
                    </form>

                    <?php 

                    echo "<h3><br> TOTAL PRICE <br> ".$_SESSION['total_price']."</h3>";                              
                    echo "<h3><br> YOUR COIN <br>".$user_coin."</h3>";

                    ?>

                    <form action="<?php 
                                  // order complete! update user coin
                                  if($total < $user_coin){ 
                                      echo "success.php";} else {echo "charge.php";} ?>" , method="post">
                        <input type="submit" name="complete_button" value="<?php if($total < $user_coin) {echo "PAY";} else {echo "Charge Coin";}?>">

                    </form>

                </div>
            </ul>

        </section>
    </div>
</section>

</body>

</html>
