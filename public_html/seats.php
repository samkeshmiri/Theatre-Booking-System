<?php
    require('connect.php');
    // retrieve basic price of the ticket to be multiplied by
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

    $title = $_GET['title'];
    $price = getProductionPrice($title);
    $perfTime = $_GET['perfTime'];
    $perfDate = $_GET['perfDate'];
    $conn = myconnect();
    $sql = "SELECT Seat.RowNumber, ROUND(Zone.PriceMultiplier * :price) AS Price, Zone.Name AS Area
            FROM Seat
            LEFT JOIN Zone ON Seat.Zone = Zone.Name
            WHERE Seat.RowNumber NOT IN 
            (SELECT Booking.RowNumber FROM Booking 
            WHERE Booking.PerfTime= :perfTime
            AND Booking.PerfDate= :perfDate) ORDER BY Price, Seat.RowNumber;";
    $handle = $conn->prepare($sql);
    $handle->bindParam('perfTime', $perfTime);
    $handle->bindParam('price', $price);
    $handle->bindParam('perfDate', $perfDate);
    $handle->execute();
    $conn = null;
    $res = $handle->fetchAll();
?>

<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <title>Available Seats CO887</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <script type="text/javascript">
            // get indexes of selected checkboxes into a new list
            function getSelectedIndexes() {
                var selectedIndexes = [];
                var ticketPrices = document.getElementsByName('seatSelection');
                for(var i=0;i<ticketPrices.length;i++){
                    if(ticketPrices[i].checked) {
                        selectedIndexes.push(i);
                    }
                }
                return selectedIndexes;
            }

            // gets the total price of tickets selected
            function getTotal() {
                var sum = 0;
                var selectedIndexes = getSelectedIndexes();
                var ticketPrices = document.getElementsByName('seatSelection');
                for (var sel=0;sel<selectedIndexes.length;sel++) {
                    sum += parseInt(ticketPrices[selectedIndexes[sel]].value);
                }
                return sum;
            }

            // gets the seats selected by the user
            function getSeats() {
                var seats = [];
                var selectedIndexes = getSelectedIndexes();
                var seatNames = document.getElementsByName('seatName');
                for (var j=0;j<selectedIndexes.length;j++) {
                    seats.push(seatNames[selectedIndexes[j]].value); //only checked indexes
                }
                return seats.join(', ');
            }

            // booking information passed to booking page
            function populateData() {
                var seats = getSeats();
                var total = getTotal();
                var seatInput = document.getElementById('seatsBooked');
                var totalInput = document.getElementById('totalCost');
                seatInput.setAttribute('value', seats);
                totalInput.setAttribute('value', total.toString());
                return true;
            }

            // validation alert with total, email and seats
            function alertData() {
                var sum = getTotal();
                var email = document.getElementById('email').value;
                var seats = getSeats();
                if (sum === 0 || email === "" || seats === "") {
                    alert('Please ensure you have selected at least one seat and entered an email to continue.');
                    return false;
                } else if (sum != 0 && email != "" && seats != "")
                alert('Email: ' + email + '\nSeats: ' + seats + '\nTotal £' + sum);
                return true;
            }
        </script>


    </head>
    <body>
        <header id = header>
            <h1 class = headers>Theatre CO887</h1>
            <ul>
                <li><a class=navigation href=index.php>Home</a></li>
                <li><a class=navigation href="javascript: history.go(-2)">Shows</a></li>
                <li><a class=navigation href="javascript: history.back()">Performances</a></li>
            </ul>
        </header>


        <div class = bookingHeaders>
            <h2 class = headers>Select your seats
                Show: <label class = "performanceLabels"> <?= $title ?> </label> <br>
                Date: <label class = "performanceLabels"> <?= $perfDate ?> </label><br>
                Time: <label class = "performanceLabels"> <?= $perfTime ?> </label>
            </h2>
        </div>

        <div class =container>
            <table>
            <tr>
                <th>Zone</th>
                <th>Seat</th>
                <th>Price</th>
                <th>Select Seat</th>
            </tr>
            <?php
                foreach ($res as $row) : ?>
                <tr>
                    <td><?=$row['Area']?></td>
                    <td><?=$row['RowNumber']?></td>
                    <td>£<?=$row['Price']?></td>
                    <td>
                        <input type='checkbox' name='seatSelection' value="<?=$row['Price']?>">
                        <input type='hidden' name='seatName' value = "<?=$row['RowNumber']?>">
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
        </div>

        <form class="bookingDialog" action="book.php" method="POST" name = "submit" onsubmit="return populateData();">
            <label class ="information" for="email">Enter Email Address </label>
            <!-- regex check to ensure email is in email format -->
            <input type="email" id = "email" class="emailText" name="email" required> <br><br>
            <input class="myBookingButton" type="submit" name="submit" value="Book now">
            <input class="myBookingButton" type="button" name="check" value="Check Booking" onclick="alertData()">
            <input type = 'hidden' name = 'title' value="<?=$title?>">
            <input type = 'hidden' name = 'perfDate' value="<?=$perfDate?>">
            <input type = 'hidden' name = 'perfTime' value="<?=$perfTime?>">
            <input type='hidden'  id='seatsBooked' name="seats" value=""> <!-- get seat inputs -->
            <input type='hidden' id='totalCost' name='total' value=""> <!-- get total cost -->
        </form>
    </body>
</html>