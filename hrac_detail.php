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
        table, th, td, tr {
            border: black solid 1px;
            border-collapse: collapse;
            text-align: left;
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
    $sql = "SELECT * FROM `zapasy` WHERE `player_win`= '$hrac_sql'";

    //prohry
    $hrac_prohry = "SELECT COUNT(`set_lose`) AS `zapasy`, SUM(`set_lose`) AS `sety`
    FROM `zapasy` 
    WHERE `player_lose`='$hrac_sql'";

    $hrac_prohry = mysqli_query($konektor, $hrac_prohry);

    $lose = mysqli_fetch_assoc($hrac_prohry);

    $lose_set = $lose['sety'] ?? 0;
    $lose_zapasy = $lose['zapasy'];

    //výhry
    $hrac_vyhry = "SELECT COUNT(`set_win`) AS `zapasy`, SUM(`set_win`) AS `sety`
    FROM `zapasy` 
    WHERE `player_win`='$hrac_sql'";

    $hrac_vyhry = mysqli_query($konektor, $hrac_vyhry);

    $win = mysqli_fetch_assoc($hrac_vyhry);

    $win_set = $win['sety'] ?? 0;
    $win_zapasy = $win['zapasy'];

    //tabulka zápasů
?>
    <h1><?= htmlspecialchars($hrac) ?></h1>
        <table>
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

</body>
</html>