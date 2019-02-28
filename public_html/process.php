<!DOCTYPE html>
<html lang="en-GB">
	<head>
		<title>B-cards: business cards on demand</title>
		<link rel="stylesheet" type="text/css" href="styles/style.css">
	</head>
	<body>
		<div class="heading">
			<img src="images/logo.png" alt="B-cards logo" width="100">
			<h1>B-cards - business cards on demand</h1>
			
			<div id="menu">
				<a href="index.html">home</a> |
				<a href="order.html">order</a>
			</div>
		</div>
		<div class="content">

    <?php
        if($_GET['number']==NULL || $_GET['name'] == NULL){
            echo 'You have forgotten something...  <a href="order.html">retry</a>';
        }else {
            switch ($_GET['quality']) {
                case "basic" :
                    $base_price = 10;
                    break;
                case "medium" :
                    $base_price = 15;
                    break;
                case "high" :
                    $base_price = 20;
                    break;
                default :
                    echo 'The quality must be basic, medium or high' + '<a href="order.html">Retry</a>';
                    break;
            }
            echo '<div class="focus"><h3>Invoice</h3>';
            echo '<p>You have ordered <span class="uinput">' . $_GET['number'] . '</span> cards with business name <span class="uinput">' . $_GET['name'] . '</span></p>';
            echo '<p>The selected quality is <span class="uinput">' . $_GET['quality'] . '</span>';
            echo '<p>Total paid: £<span class="uinput">' . $base_price * $_GET['number'] . '</span>';
            echo '<p><a href="order.php">order more</a></p></div>';
        }
    ?>
		</div>
	</body>
</html>
			
			
			
			

