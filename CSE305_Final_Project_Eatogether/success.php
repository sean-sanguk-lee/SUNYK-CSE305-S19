<?php include 'server.php'; 
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
//echo $uid;

// get location from db

if(isset($_SESSION['selected_coupon'])){
    $selected_coupon = $_SESSION['selected_coupon'];
    $q =  "delete from owns where owns.coupon_code = '$selected_coupon' and owns.uid = $uid;";
    $result = $db -> query($q);

};

$user_coin -=$_SESSION['total_price']; 

$q =  "update users set users.coin = $user_coin where users.uid = $uid;";
$result = $db -> query($q);


// get the restaurant rid 
$rid = $_SESSION['rest'];
//$rest_name = $_SESSION['rest_S'];
//echo $rest_name."<br>";
//$q = "select RESTAURANT.rid from RESTAURANT where RESTAURANT.rname = '$rest_name';";
//$result = $db -> query($q);
//if($result != null){
//    $row = $result->fetch_assoc();
//    $rid = $row['rid'];
//    echo $rid."<br>";
//}

// create waitlist
if($_SESSION['first_order_mode']){
    
    $group_mode = $_SESSION['group_mode'];
    $lid = $_SESSION['lid'];   
//    echo "lid : ".$lid."<br>"."group order : ".$group_mode."<br>";
    $q = "INSERT INTO WAITLIST (rid, lid, otype) VALUES ($rid, $lid, '$group_mode');";
    $result = $db -> query($q);
    $last_id = $db -> insert_id;
//    echo $last_id."<br>";
    $qid = $last_id;
}
else{
    $qid = $_SESSION['$selected_qid'];
}

//if($result != null){
//    $row = $result->fetch_assoc();
//    $qid = $row['qid'];
//    echo $qid;
//}
// create order row in db 
// qid = 0 / uid / mid 
foreach($_SESSION['selected_menu'] as $menu => $num){    
    if($num != 0){
        // B-1. get the mid
        $q  = "select menu.mid from menu where menu.menu = '$menu';";
     //   echo $menu."<br>";
        $result = $db->query($q);
        if($result != null){
            $row = $result->fetch_assoc();
            // B-2. get the num of menu
            $mid = $row['mid']; 
            while($num!=0){
                $q2 = "INSERT INTO ORDERS (uid, mid) VALUES ($uid, $mid);";
                $result2 = $db->query($q2);
                $last_oid = $db -> insert_id;
                $q3 = "INSERT INTO ORDER_QUEUE (qid, oid) VALUES ($qid, $last_oid);";
                $result3 = $db->query($q3);
                $num--;
//                echo "Insert!<br>";
            }
        }
    }
}


?>

<!DOCTYPE HTML>

<head>
    <title>Elements - Introspect by TEMPLATED</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
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
            <br><br>
            <h1> Order Successfully Submitted </h1>
            <div class="table-wrapper">
                <form name="itemForm" method="post">

                    <h3> <?php
    echo "Total price : ".$_SESSION['total_price']."<br>";
            echo "YOUR COIN : ".$user_coin."<br>";
            $_SESSION['total_price'] = 0;
                        ?></h3>
                </form>

                <p>

                    Go back to my page? <a href="myPage.php">My Page</a>
                </p>
            </div>
        </section>


    </div>
</section>

</body>

</html>
