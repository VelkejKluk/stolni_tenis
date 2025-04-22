<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

//ziskame jmeno
$hrac = $_GET['hrac'];


?>
<!doctype html>
<html lang=cs-CZ>
<head>
    <meta charset="UTF-8">
    <title>Detail hráče <?= htmlspecialchars($hrac) ?></title>
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
<?php
    $konektor = mysqli_connect('localhost', 'root', '', 'stolni-tenis');

    // zjistí připojení
    if (!$konektor) {
        echo 'Databáze nepřipojena';
        exit();
    }
    $hrac_sql = mysqli_real_escape_string($konektor, $hrac);
    //$sql = "SELECT * FROM `zapasy` WHERE `player_win`= '$hrac_sql'";

    //prohry
    $hrac_prohry = "SELECT COUNT(`player_lose`) AS `zapasy`, SUM(`set_win`) AS `prohra_sety`, SUM(`set_lose`) AS `vyhra_sety`
    FROM `zapasy` 
    WHERE `player_lose`='$hrac_sql'";

    //výhry
    $hrac_vyhry = "SELECT COUNT(`player_win`) AS `zapasy`, SUM(`set_win`) AS `vyhra_sety`, SUM(`set_lose`) AS `prohra_sety`
        FROM `zapasy` 
        WHERE `player_win`='$hrac_sql'";

    $hrac_prohry = mysqli_query($konektor, $hrac_prohry);

    $lose = mysqli_fetch_assoc($hrac_prohry);

    $hrac_vyhry = mysqli_query($konektor, $hrac_vyhry);

    $win = mysqli_fetch_assoc($hrac_vyhry);

    $lose_set = $lose['prohra_sety'] + $win['prohra_sety'] ?? 0;
    $lose_zapasy = $lose['zapasy'];

    $win_set = $win['vyhra_sety'] + $lose['vyhra_sety'] ?? 0;
    $win_zapasy = $win['zapasy'];

    //tabulka zápasů
?>
    <h1><?= htmlspecialchars($hrac) ?></h1>
        <table id="data">
            <tr>
                <th></th>
                <th>Výhry</th>
                <th>Prohry</th>
            </tr>
            <tr>
                <th>Na sety</th>
                <td><?= $win_set ?></td>
                <td><?= $lose_set?></td>
            </tr>
            <tr>
                <th>Na zápasy</th>
                <td><?= $win_zapasy ?></td>
                <td><?= $lose_zapasy ?></td>
            </tr>
        </table>
    <br>
<?php
    echo '<a href="/stolni_tenis/index.php">
        <input type="submit" value="Zpět na tabulku" id="buton">
    </a>';
?>
</body>
</html>