<?php include 'server.php'; ?>

<html>

<!DOCTYPE HTML>

<head>
    <title>Elements - Introspect by TEMPLATED</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>
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
            <header class="select_menu">
                <h1>Group order</h1>

            </header>


            <section>
                <div class="list_of_menu">

                    <h5>2. select the menu</h5>
                    <ul class="alt">

                        <?php 
                            $rest = '';

                            if(isset($_POST['RESTAURANT'])){
                                $rest = $_POST['RESTAURANT'];
                                $_SESSION['rest'] = $rest;
                            }
                            else{
                                $rest = $_SESSION['rest'];
                            }


                            $_SESSION['menu'];
                            $list_restaurant = array();
                            $q = " select menu.menu from menu where menu.mid in (select offers.mid from offers where offers.rid = '$rest');";

                            $result = $db -> query($q);

                            while($row = $result -> fetch_assoc()){
                                array_push($list_restaurant, $row["menu"]);
                                if($_SESSION['mode'] == 0)
                                {
                                    $_SESSION['menu'][$row["menu"]] = 0; 
                                }

                            }

                            $_SESSION['mode'] = 1;

                            ?>

                        <?php
                            //                                                                              $q = "select menu.menu from menu where menu.menu =$selected;";
                            //                                        $result = $db -> query($q);
                            //                                        $row = $result -> fetch_assoc();

                            if(isset($_POST['Menu'])){
                                echo ($_POST['Menu']);
                                $selected = $_POST['Menu'];

                                $_SESSION['menu'][$selected] += 1;
                                echo $selected;
                                $num = $_SESSION['menu'][$selected];
                                echo $num;
                            }
                                                                   
                            ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                            <!--                                        <input type="hidden" name="action" value="submit" />-->
                            <select name="Menu">
                                <?php foreach($list_restaurant as $key=>$val) {
                                        echo '<option value="' .$val. '">';
                                        echo $val;
                                        echo "</option>\n";
                                    }
                                    ?>
                            </select>&nbsp;<input type="submit" name="button" value="ADD" />
                            <input type="button" value="Select the location" class="button" <?php if(!isset($_POST['Menu'])) { echo 'disabled = "disabled"';}
                                   ?> onClick="Javascript:window.location.href = 'group_order_create_page3.php';" />
                        </form>
                    </ul>

                </div>

            </section>

            <section>
                <br><br>

                <div class="table-wrapper">
                    <form name="itemForm" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th align='center'>Menu</th>
                                    <th>Num</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    unset($_SESSION['menu']['']);
                                    foreach($_SESSION['menu'] as $menu => $num): ?>
                                <tr>
                                    <td align='center'><?=$menu;?></td>
                                    <td><?=$num;?></td>
                                    <td> </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>


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
