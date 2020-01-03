<?php 
include 'server.php'; 
$_SESSION['first_order_mode'] = False; // order from waitlist
$selected_qid = 0;
$selected_items = array();
$menu_num = array();
?>

<?php 
if($_SESSION['$selected_qid']!=NULL){ 
    $selected_qid =$_SESSION['$selected_qid']; 

    $query = "select rid, qid, rname, location, min_price, lid from WAITLIST Natural Join RESTAURANT Natural Join LOCATION where waitlist.qid = $selected_qid;";
    $result = $db->query($query);
    if($result != null){
        $row = $result->fetch_assoc();
        $selected_items[$row['']] = array($row['qid'], $row['rname'], $row['location'] ,$row['min_price']);
        $_SESSION['lid'] = $row['lid'];
        $_SESSION['rest'] = $row['rid'];
    }
}

?>


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


<section>
    <br>
    <h1><br>Selected Waitlist Information<br></h1><br>
    <div class="table-wrapper">
        <form name="itemForm" method="post">
            <table>
                <thead>
                    <tr>
                        <th align='center'>Order number</th> <!-- waitlist.qid-->
                        <th align='center'>RESTAUANT</th> <!-- waitlist.location-->
                        <th align='center'>location</th>
                        <th align='center'>Min Price</th><!-- waitlist.deliver_ time-->

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php if($selected_items != NULL){  ?>
                        <td align='center'><?=$selected_items[0];?></td>
                        <td align='center'><?=$selected_items[1];?></td>
                        <td align='center'><?=$selected_items[2];?></td>
                        <td align='center'><?=$selected_items[3];?></td>
                        <?php }?>
                        <!--                        <td><form>-->
                        <!--                            <input type="hidden" name="selected_qid" value="<?php ?>">-->
                        <!--                            <input type=submit value="Join" style="width:100%">-->
                        <!--                            </form> </td>-->
                    </tr>

                </tbody>

            </table>
        </form>
    </div>


</section>
<br>
<section>


    <form method = 'POST' name = 'select_num_of_menu' action = '<?php $_SESSION['menu']  = $menu_num; ?>'>
        <table>
            <thead>
                <tr>
                    <th align='center'>Order Number</th> <!-- waitlist.qid-->
                    <th align='center'>Selected Menu</th> <!-- waitlist.location-->
                    <th align='center'>Price</th>
                    <th align='center'>Num</th>
                    <th align='center'>Select</th>

                </tr>
            </thead>
            <tbody>
            </tbody>

            <?php

            if($_SESSION['$selected_qid']!=NULL){
                $selected_qid =$_SESSION['$selected_qid']; 
                $q = "select DISTINCT menu, price from menu Natural Join Order_queue Natural Join Orders where qid = $selected_qid;";
                $result = $db -> query($q);

                $orders_info = array();

                while($row = $result -> fetch_assoc()){
                    $orders_info[$row['menu']]  = array($row['price'], 0);
                }
              




                foreach($orders_info as $oid => $val){ ?>
            <tr>
                <td><?=$oid;?></td>
                <td><?=$val[0];?></td>
                <td><?=$val[1];?></td>
                <td><?php if(isset($_POST['menu'.$oid])){$orders_info[$oid][2] += $_POST['menu'.$oid]; echo $_POST['menu'.$oid];} else {echo 0;}?></td>
                <td><input type="number" min="0" name = 'menu<?php echo $oid;?>' value = '<?php echo 0; ?>'></td>
            </tr>
            <?php } } 

            if(isset($_POST['button'])){
                foreach($orders_info as $oid => $subarr){
                    $_SESSION['menu'][$subarr[0]] = $subarr[2];
                    
                }
//                foreach($_SESSION['menu'] as $m => $v){
//                    echo $m."<br>".$v."<br>";
//                }
                
            }






            ?>


        </table>
        <br>
        <input type="submit" name="button" id = "button" value="select"/>
    </form>
    <form method = 'POST' name = 'select_num_of_menu' action = 'payment.php'>
        <input type="button" value="Payment" class="button" <?php if(!isset($_POST['button'])) {  echo 'disabled = "disabled"';}
               ?>
               onClick="Javascript:window.location.href = 'payment.php';" />
    </form>

</section>
</body>
</html>
