<?php
$title = $_POST['title'];
$perfTime = $_POST['perfTime'];
$perfDate = $_POST['perfDate'];
$email = $_POST['email'];
$seats = $_POST['seats'];
$total = $_POST['total'];

// server side validation to ensure data exists before passing to the database
    if ($email == NULL || $seats == NULL) {
        echo '
            <br><br><br><br><br><br><br>
                <div class = errorDialog>
                <h2 class="headers">Missing Information!</h2>
                <p class = errorInfo>
                    Not all information has been submitted to make this booking. <br>
                    Please return to Seat Selection to check your email and selected seats.
                </p>
                <div>
                    <a class = errorBookingButton href="javascript: history.go(-1)">Click here to go back and try again</a>
                </div>
            </div>';
    } else if (isset($email) && isset($seats)) { //could use isset here
        bookSeats();
    }

    // insert booking information into the database
    function bookSeats() {
        require("connect.php");
        $perfTime = $_POST['perfTime'];
        $perfDate = $_POST['perfDate'];
        $email = $_POST['email'];
        $seats = $_POST['seats'];
        $bookedSeats = explode(', ', $seats);

        foreach ($bookedSeats as $seat) {
            $conn = myconnect();
            $sql = "INSERT INTO Booking (Email, PerfDate, PerfTime, RowNumber) VALUES (:email, :perfDate, :perfTime, :seats);";
            $handle = $conn->prepare($sql);
            $handle->bindParam('email', $email);
            $handle->bindParam('perfTime', $perfTime);
            $handle->bindParam('perfDate', $perfDate);
            $handle->bindParam('perfTime', $perfTime);
            $handle->bindParam('seats', $seat);
            $handle->execute();
            $conn = null;
        }
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <title>Booking CO887</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
    </head>

    <body>
        <header id = header>
            <h1 class="headers">Theatre CO887</h1>

            <ul>
                <li><a class = navigation href="index.php">Home</a></li>
            </ul>
        </header>
        <div class = bookingComplete>
            <h2 class = headers>You're going to see <?=$title?>!</h2>
            <p class = information>Date: <span class="information"><?=$perfDate ?> at <?=$perfTime?></span>
            <p class = information>Booking e-mail: <span class="uInput"><?= $email?></span>
            <p class = information>Selected seats: <span class="uInput"><?= $seats?></span>
            <p class = information>Total price: £<span class="uInput"><?=$total?></span>
        </div>
        <div class = bookMoreShows>
            <a class = errorBookingButton href="index.php">Book more shows!</a>
        </div>
    </body>
</html>

