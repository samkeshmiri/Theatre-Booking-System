<!DOCTYPE html>
<html lang="en-GB">
	<head>
        <title>Theatre CO887</title>
		<link rel="stylesheet" type="text/css" href="styles/style.css">
	</head>
    
	<body>
        <header id = header>
            <h1 class="headers">Theatre CO887</h1>
            <ul>
                <li><a class = navigation href="index.php">Home</a></li>
            </ul>
        </header>
        <div class = "container">
            <h2 class="headers">Upcoming Shows</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Base price</th>
                        <th>Select Show</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        require("connect.php");
                        $conn=myconnect();
                        $sql="SELECT * from Production ORDER BY Title;";
                        $handle=$conn->prepare($sql);
                        $handle->execute();
                        $conn=null;
                        $res=$handle->fetchAll();

                        foreach ($res as $row) : ?>
                        <tr>
                            <td><?=$row['Title']?></td>
                            <td><?=$row['BasicTicketPrice']?></td>
                            <td>
                                <form method='GET' action='perf.php'>
                                    <input type='hidden' name='Title' value="<?=$row['Title']?>">
                                    <input type='submit' class='myButton' value="<?=$row['Title']?> Performances" >
                                </form>
                            </td>
                        </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
	</body>
</html>