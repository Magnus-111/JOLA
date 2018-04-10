<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Informacja o kliencie</title>
        <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
        <link rel="stylesheet" type="text/css" href="styla.css" />
    </head>
    <body>
        <?php
        require '../polacz.php';
        echo '<h2>Konto klienta:</h2>';
        $kk = mysqli_query($pol, "SELECT * FROM lak WHERE id_uzyt=".$_GET['idk']);
        echo '<table class="tbl" border="1" cellspacing="3" cellpadding="8" width="100%"><thead><th colspan="2">Dane klienta: </th></thead><tbody>';
        while ($w = mysqli_fetch_object($kk))
        {
            $e = mysqli_query($pol,"SELECT * FROM lkk WHERE id_uzyt = ".$_GET['idk']." ");
            $em = mysqli_fetch_object($e);
            echo '<tr><td>E-mail: </td><td>'.$em->email.'</td></tr>';
            echo '<tr><td style="width: 25%;">Imię:</td><td>'.$w->imie.'</td></tr>';
            echo '<tr><td>Nazwisko:</td><td>'.$w->nazwisko.'</td></tr>';
            echo '<tr><td>Adres do wysyłki:</td><td style="white-space: pre-line;">'.$w->adres.'</td></tr>';
            if (!empty($w->adresf))
            {
                echo '<tr><td>Adres do faktury:</td><td style="white-space: pre-wrap;">'.$w->adresf.'</td></tr>';
            }
            else{
                echo '<tr><td>Adres do faktury:</td><td>Nie wskazano faktury.</td></tr>';
            }
            echo '<tr><td>Telefon:</td><td>'.$w->telefon.'</td></tr>';
        }
        echo '</tbody></table>';
        ?>        
    </body>
</html>
