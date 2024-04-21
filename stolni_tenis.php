<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="cs-CZ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data stolního tenisu</title>
    <style>
        body{
            margin-left: 25px;
        }
        table, th, td, tr {
            border: black solid 1px;
            border-collapse: collapse;
            text-align: left;
        }
        h1 {
            color: #285097;

        }
        #data {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 30%;
        }

        #data td, #data th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #data tr:nth-child(even){background-color: #f2f2f2;}

        #data tr:hover {background-color: #ddd;}

        #data th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #285097;
            color: white;
        }
        #data a:link {
            color: black;
            text-decoration: none;
        }
        #data a:hover {
            color: black;
            font-weight: bold;

        }
        #buton{
            background-color: #285097;
            color: white;
        }
        #buton:hover {
            background-color: #ddd;
            color:black;

        }
    </style>
</head>
<body>
    <h1>Stolní tenis</h1>
    <form action="stolni_tenis.php" method="post">
        <label for="date">Datum a čas zápasu:</label>
        <input type="datetime-local" id="date" name="date" required><br><br>
        <label for="player1">Jméno prvního hráče:</label>
        <input type="text" id="player1" name="player1" required><br><br>
        <label for="player2">Jméno druhého hráče:</label>
        <input type="text" id="player2" name="player2" required><br><br>
        <label for="result">Výsledek:</label>
        <input type="text" id="result" name="result" pattern="(3:[0-2]|[0-2]:3)" title="Zadejte výsledek ve formátu 3:0, 3:1 nebo 3:2" required><br><br>
        <input type="submit" name="submit" value="Odeslat" id="buton">
    </form>
<!--    onclick="window.location.href = '/stolni_tenis/index.php';"-->

    <?php
        if(isset($_POST['submit'])){
            header('Location: /stolni_tenis/index.php');
        }

        $konektor = mysqli_connect('localhost','root','','stolni-tenis');
        
        // check if connected
        if(!$konektor)
        {
            echo 'Databáze nepřipojena';
            exit();
        }

        // vytvoříme tabulky
        $zapasy_vytvorit = 'CREATE TABLE IF NOT EXISTS zapasy (
            match_id INT AUTO_INCREMENT PRIMARY KEY,
            date DATETIME NOT NULL,
            player_win VARCHAR(255) NOT NULL,
            set_win INT UNSIGNED NOT NULL,
            player_lose VARCHAR(255) NOT NULL,
            set_lose INT UNSIGNED NOT NULL)';
        
        mysqli_query($konektor,$zapasy_vytvorit);

        
        // Zpracování formuláře po odeslání
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = explode(':', $_POST['result']);
            if($result[0] == '3') {
                $player1 = $_POST['player1'];
                $player2 = $_POST['player2'];
                $result1 = $result[0];
                $result2 = $result[1];
            }else {
                // Pokud je výsledek x:3, přehodíme sloupce, aby vítěz byl v prvním.
                $player1 = $_POST['player2'];
                $player2 = $_POST['player1'];
                $result1 = $result[1];
                $result2 = $result[0];
            }

            // Ošetření SQL injection
            $date = mysqli_real_escape_string($konektor, $_POST['date']);
            $player1 = mysqli_real_escape_string($konektor, $player1);
            $player2 = mysqli_real_escape_string($konektor, $player2);
            $result1 = mysqli_real_escape_string($konektor, $result1);
            $result2 = mysqli_real_escape_string($konektor, $result2);
            

            // Připravit SQL dotaz pro vložení dat
            $zapasy = "INSERT INTO zapasy (date, player_win, set_win, player_lose, set_lose) VALUES ('$date', '$player1', '$result1', '$player2', '$result2')";

            if (mysqli_query($konektor, $zapasy)) {
                echo "Výsledek zápasu byl úspěšně zaznamenán.";
            } else {
                echo "Chyba";
            }
        }
    ?>
</body>
</html>
