<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="cs-CZ">
<head>
    <meta charset="UTF-8">
    <title>Tabulka zápasů a hráčů</title>
    <style>
        table, th, td, tr {
            border: black solid 1px;
            border-collapse: collapse;

        }
        h1{
            color: #285097;
        }
        body{
            margin-left: 25px;
        }
        #data {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
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
            text-decoration: underline;

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

// http://josef-macbook-air.local/tabulka_stolnitenis.php?hrac=DDD

$sql = 'SELECT * FROM zapasy ORDER BY date DESC';
$hodnoty = mysqli_query($konektor, $sql);

if (mysqli_num_rows($hodnoty) > 0) {
    //tabulka zápasů
    ?>
    <h1>Tabulka hráčů</h1>
    <table id="data">
        <tr>
            <th>ID zápasu</th>
            <th>Datum</th>
            <th>Hráč1</th>
            <th>Hráč2</th>
            <th>Skóre</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($hodnoty)) {
            $score = $row['set_win'] . ' : ' . $row['set_lose'];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['match_id']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><a href="/stolni_tenis/hrac_detail.php?hrac=<?= htmlspecialchars(urlencode($row['player_win'])) ?>">
                        <?= htmlspecialchars($row['player_win']) ?>
                    </a></td>
                <td><a href="/stolni_tenis/hrac_detail.php?hrac=<?= htmlspecialchars(urlencode($row['player_lose'])) ?>">
                        <?= htmlspecialchars($row['player_lose']) ?>
                    </a></td>
                <td><?= htmlspecialchars($score) ?></td>
            </tr>
        <?php
        } ?>
    </table>
<?php
}

?>
    <br>
<?php
    echo '<a href="/stolni_tenis/stolni_tenis.php">
          <input type="submit" value="Přidat záznam" id="buton">
      </a>';
?>


</body>
</html>