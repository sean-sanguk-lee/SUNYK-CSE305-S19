<?php include 'template.php'; 
$_SESSION['$selected_qid'] = NULL; // for selecting waitlist
?>

<!DOCTYPE HTML>

<head>
    <title>Eatogether</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>


<!-- One -->
<section id="one">
    <div class="inner">
        <header>
            <h2>Select Order Type</h2>
        </header>
        <table border="order_type_buttons">
            <tr>
                <td>
                    <ul class="actions">
                        <li><a href="split_order_waitlist_page.php" class="button alt">Group Order</a></li>
                    </ul>
					<ul>
						When minimum deliver cost is gathered, order happens.
					</ul>
                </td>
<!--
                <td>
                    <ul class="actions">
                        <li><a href="group_order_waitlist_page.php" class="button alt">Split Order</a></li>
                    </ul>
					<ul>
						Can order just the amount you want.
						<br>
						When deliver amount is gathered, order happens.
						</ul>
                </td>
-->
            </tr>
        </table>


        <p><b>Food delivery</b> service for 1 person </p>
     
    </div>
</section>


</body>

</html>
