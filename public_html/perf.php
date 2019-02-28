<?php
    $title = $_GET['Title'];
    require("connect.php");
    $conn = myconnect();
    $sql = "SELECT * FROM Performance WHERE Performance.Title = :title ORDER BY PerfDate, PerfTime;";
    $handle = $conn->prepare($sql);
    $handle->bindParam('title', $title);
    $handle->execute();
    $conn = null;
    $res = $handle->fetchAll();

    function getProductionPrice($title){
        $conn = myconnect();
        $sql= "SELECT BasicTicketPrice FROM Performance JOIN Production ON Performance.Title = Production.Title WHERE Production.Title = :title;";
        $handle = $conn->prepare($sql);
        $handle->bindParam('title', $title);
        $handle->execute();
        $conn = null;
        $result = $handle->fetch();
        return $result["BasicTicketPrice"];
    }

    // display page to return to home if URL is not found on the server
    function show404(){
        echo "<html lang=\"en-GB\">
        <head>
            <title>Error 404</title>
        </head>
            <body>
                <h1>404 - Page not found</h1>
                <p>Some monkeys have had too much coffee and lost the page you were looking for... <br>
                We also lost the monkeys... <br>
                ...anyway, you should probably <a href ='index.php'> go back to the home page and try again</a>
                <p><img src=\"images/monkeyCoffee.png\" alt=\'404image\' align='center' width=\"180\"><br>You found him!</p>
            </body>
        </html>";
        die();
    }

    // check if URL returns data
    if(count($res)<1){
        show404();
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <title>Performances CO887</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
    </head>

    <body>
    <header id = header>
        <h1 class="headers">Theatre CO887</h1>
    </header>

    <ul>
        <li><a class = navigation href="index.php">Home</a></li>
        <li><a class = navigation href="index.php">Shows</a></li>
    </ul>
        <div class="container">
            <h2 class="headers">Timetable for <label class = "performanceLabels"> <?= $title ?> </label> tickets from<label class = "performanceLabels"> £<?= getProductionPrice($title) ?></label></h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Availability</th>
                </tr>
                <?php
                foreach ($res as $row) : ?>
                <tr>
                    <td><?=$row['Title']?></td>
                    <td><?=$row['PerfDate']?></td>
                    <td><?=$row['PerfTime']?></td>
                    <td>
                        <form method = 'GET' action = 'seats.php'>
                            <input type = 'hidden' class = 'myButton' name = 'title' value="<?=$row['Title']?>">
                            <input type = 'hidden' class = 'myButton' name = 'perfDate' value="<?=$row['PerfDate']?>">
                            <input type = 'hidden' class = 'myButton' name = 'perfTime' value="<?=$row['PerfTime']?>">
                            <input type = 'submit' class = 'myButton' value = "Show Availability" >
                        </form>
                    </td>
                <?php endforeach; ?>
            </table>
        </div>
    </body>
</html>