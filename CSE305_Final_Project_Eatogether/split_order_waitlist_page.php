<?php include 'server.php';
    $_SESSION['group_mode'] = False;
    if(isset($_POST['list'])){
        $_SESSION['$selected_qid'] = $_POST['list']; 
    }
    $_SESSION['menu'] = NULL;
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
				
 
<!-- Waitlist Table -->
<section id = "main">
	<div class="inner">
    <br>
    <h1><br>Group Order Waitlist</h1>
    <br>
		<ul class="actions">
        <li><a href="split_order_create_page.php" class="button alt">Create New Group Order</a></li>
		<br>
		<li>Create a New Group Order if there is no desired menu in the waitlist.
			<br>
			Initiate the order and start gathering people to order together.
			</li>
    </ul>

    <div class="table-wrapper">
           
            <table>
                <thead>
                    <tr>
                        <th align='center'>Order number</th> <!-- waitlist.qid-->
                        <th align='center'>RESTAUANT</th> <!-- waitlist.location-->
                        <th align='center'>location</th>
                        <th align='center'>Minimum Price</th><!-- waitlist.deliver_ time-->
<!--                         -->
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    $waitlists = NULL;
                    $query  = "select qid, rname, location, min_price from WAITLIST Natural Join RESTAURANT Natural Join LOCATION where otype = False and matched = false;";
                    $result = $db->query($query);
                    if($result != null){
                        while($row = $result->fetch_assoc()){
                            $waitlists[$row['qid']] = array($row['rname'], $row['location'], $row['min_price']);
                        }
                    }
//                    <input type='checkbox' name='SelectedData[]' alt='Checkbox' value='$id&$name&$price'>
                     if($waitlists != NULL){   
                    foreach($waitlists as $waitlistId => $subarr): ?>
                    <tr>
<!--                        <form method="post" action = "<?php echo $_SERVER['PHP_SELF']; ?>" >-->

                        <td align='center'><?=$waitlistId;?></td>
                        <td align='center'><?=$subarr[0];?></td>
                        <td align='center'><?=$subarr[1];?></td>
                        <td align='center'><?=$subarr[2];?></td>
<!--
                        <td>
                           <input type='checkbox' name="selected_qid" value="<?php echo $waitlistId; ?>">
                        </td>
-->
                      
                      
                    </tr>
                    <?php endforeach;}?>
                </tbody>
                
                
              
<!--
                
                <form method="post" action = "split_order_waitlist_page2.php">
                
                <select> </select>
                </form>
-->


             
            </table>
        
        <br><br><br>
        <h3>Join Waitlist</h3>
		<p>Select Order number from waitlist to join</p>
		
         <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                            <!--                                        <input type="hidden" name="action" value="submit" />-->
                            <select name="list">
                                <?php foreach($waitlists as $key => $val) {
                                        echo '<option value="' .$key. '">';
                                        echo 'order num '.$key;
                                        echo "</option>\n";
                                }     
                                    ?>
                            </select>&nbsp;<input type="submit" name="button" value="SELECT"/>
                            
                            <input type="button" value="Go to next step" class="button" <?php if(!isset($_POST['list'])) { echo 'disabled = "disabled"';}
                                   ?>
                                    onClick="Javascript:window.location.href = 'split_order_waitlist_page2.php';" />
<!--                            <a href="split_order_create_page3.php" class="button alt">Select the location >></a>-->
                        </form>
<!--                <input type="submit" name="selected_qid" value ="Join"  onClick = "location.href ='split_order_waitlist_page2.php'" > -->
<!--        </form>-->
    </div>
		</div>
</section>


</body>
</html>
