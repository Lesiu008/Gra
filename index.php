<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyniki zawodów</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Wyniki zawodów - kalkulator punktów</h1>

    <form action="index.php" method="post">
        <table>
            <thead>
                <tr>
                    <th>Zadanie / Grupa</th>
                    <th>Grupa 1</th>
                    <th>Grupa 2</th>
                    <th>Grupa 3</th>
                    <th>Grupa 4</th>
                    <th>Grupa 5</th>
                    <th>Grupa 6</th>
                </tr>
            </thead>
            <tbody>
                <!-- Wiersze zadań -->
                <?php for ($zad = 1; $zad <= 10; $zad++): ?>
                <tr>
                    <td>Zadanie <?php echo $zad; ?></td>
                    <?php for ($gr = 1; $gr <= 6; $gr++): ?>
                    <td><input type="number" name="punkty[<?php echo $zad; ?>][<?php echo $gr; ?>]" min="0" max="100" value="0"></td>
                    <?php endfor; ?>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <button type="submit">Oblicz punkty i zapisz</button>
    </form>

    <form action="index.php" method="post" style="text-align:center; margin-top:10px;">
        <input type="hidden" name="reset" value="1">
        <button type="submit" style="background:#d9534f; color:#fff;">Resetuj wszystkie punkty</button>
    </form>


    <?php
    $plik = 'wyniki.csv';


    // Resetuj wyniki jeśli kliknięto reset
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
        if (file_exists($plik)) {
            unlink($plik);
        }
        echo '<p style="color:red;text-align:center;">Wszystkie punkty zostały zresetowane!</p>';
    }

    // Zapisz wyniki do pliku po wysłaniu formularza
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['punkty'])) {
        $fp = fopen($plik, 'a');
        for ($zad = 1; $zad <= 10; $zad++) {
            $wiersz = [$zad];
            for ($gr = 1; $gr <= 6; $gr++) {
                $wiersz[] = isset($_POST['punkty'][$zad][$gr]) ? (int)$_POST['punkty'][$zad][$gr] : 0;
            }
            fputcsv($fp, $wiersz);
        }
        fclose($fp);
        echo '<p style="color:green;text-align:center;">Wyniki zostały zapisane!</p>';
    }

    // Wczytaj wszystkie wyniki i podsumuj
    $suma = array_fill(1, 6, 0);
    if (file_exists($plik)) {
        $fp = fopen($plik, 'r');
        while (($wiersz = fgetcsv($fp)) !== false) {
            for ($gr = 1; $gr <= 6; $gr++) {
                if (isset($wiersz[$gr])) {
                    $suma[$gr] += (int)$wiersz[$gr];
                }
            }
        }
        fclose($fp);
    }
    ?>

    <h2>Podsumowanie punktów (wszystkie zapisane wyniki)</h2>
    <table>
        <thead>
            <tr>
                <th>Grupa</th>
                <th>Suma punktów</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($gr = 1; $gr <= 6; $gr++): ?>
            <tr>
                <td>Grupa <?php echo $gr; ?></td>
                <td><?php echo $suma[$gr]; ?></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</body>
</html>
