<?php
    $conn = new mysqli(hostname: "localhost", username: "root", password: "", database: "rzeki");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styl.css">
    <title>Poziomy rzek</title>
</head>
<body>
    <div id="banery">
        <header id="baner-lewy">
            <img src="obraz1.png" alt="Mapa Polski">
        </header>
        <header id="baner-prawy">
            <h1>Rzeki w województwie dolnośląskim</h1>
        </header>
    </div>

    <nav id="menu">
        <form action="poziomRzek.php" method="post">
            <input type="radio" name="stan" id="wszystkie" value="wszystkie">
            <label for="wszystkie" class="tekst-opcji">Wszystkie</label>

            <input type="radio" name="stan" id="ostrzegawczy" value="ostrzegawczy">
            <label for="ostrzegawczy" class="tekst-opcji">Ponad stan ostrzegawczy</label>

            <input type="radio" name="stan" id="alarmowy" value="alarmowy">
            <label for="alarmowy" class="tekst-opcji">Ponad stan alarmowy</label>

            <button type="submit" name="pokaz">Pokaż</button>
        </form>
    </nav>

    <div id="blok-lewy">
        <h3>Stany na dzień 2022-05-05</h3>

        <table>
            <tr>
                <th>Wodomierz</th>
                <th>Rzeka</th>
                <th>Ostrzegawczy</th>
                <th>Alarmowy</th>
                <th>Aktualny</th>
            </tr>

            <?php

                if(isset($_POST['stan'])) {

                    $stan = $_POST['stan'];

                    if($stan == "wszystkie") {
                        $sql = "SELECT w.nazwa, w.rzeka, w.stanOstrzegawczy, w.stanAlarmowy, p.stanWody FROM wodowskazy w INNER JOIN pomiary p ON w.id = p.wodowskazy_id WHERE p.dataPomiaru = '2022-05-05';";
                    } else if($stan == 'ostrzegawczy') {
                        $sql = "SELECT w.nazwa, w.rzeka, w.stanOstrzegawczy, w.stanAlarmowy, p.stanWody FROM wodowskazy w INNER JOIN pomiary p ON w.id = p.wodowskazy_id WHERE p.dataPomiaru = '2022-05-05' AND p.stanWody > w.stanOstrzegawczy;";
                    } else if($stan == 'alarmowy') {
                        $sql = "SELECT w.nazwa, w.rzeka, w.stanOstrzegawczy, w.stanAlarmowy, p.stanWody FROM wodowskazy w INNER JOIN pomiary p ON w.id = p.wodowskazy_id WHERE p.dataPomiaru = '2022-05-05' AND p.stanWody > w.stanAlarmowy;";
                    }

                    $result = $conn -> query(query: $sql);

                    while($row = $result -> fetch_array()) {
                        echo "<tr>";
                            echo "<td>$row[0]</td>";
                            echo "<td>$row[1]</td>";
                            echo "<td>$row[2]</td>";
                            echo "<td>$row[3]</td>";
                            echo "<td>$row[4]</td>";
                        echo "</tr>";
                    }
                }

            ?>
        </table>
    </div>    

    <div id="blok-prawy">
        <h3>Informacje</h3>

        <ul>
            <li>Brak ostrzeżeń o burzach z gradem</li>
            <li>Smog w mieście Wrocław</li>
            <li>Silny wiatr w Karkonoszach</li>
        </ul>

        <h3>Średnie stany wód</h3>

        <?php
            $sql = "SELECT dataPomiaru, AVG(stanWody) AS 'Średnie stany wody' FROM pomiary GROUP BY dataPomiaru;";

            $result = $conn -> query(query: $sql);

            while($row = $result -> fetch_array()) {
                echo "<p>$row[0]: $row[1]</p>";
            }
        ?>

        <a href="https://komunikaty.pl">Dowiedz się więcej</a>

        <img src="obraz2.jpg" alt="rzeka" id="rzeka">
    </div>

    <footer id="stopka">
        <p>Stronę wykonał: <a href="https://github.com/ferl19" target="_blank" style="color: white;">ferl19</a></p>
    </footer>
    
</body>
</html>

<?php
    $conn -> close();
?>