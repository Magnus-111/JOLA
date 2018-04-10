<?php
require '../polacz.php';

if (isset($_POST['anulujkod']))
{
    header("Location: admin.php?karta=2");
}

if (isset($_GET['usunkod']))
{
    mysqli_query($pol,"DELETE FROM kr WHERE id=".$_GET['usunkod']);
    header("Location: admin.php?karta=2");
}

if (isset($_SESSION['login_a']))
{
    $kr = mysqli_query($pol, "SELECT * FROM kr");
    if (!isset($_GET['dodajkod']))
    {
        echo '<form method="post" action="admin.php?karta=2&dodajkod">
<input type="submit" class="btn" value="Dodaj kod rabatowy" name="dodajkod" />
</form>';
        echo '<table class="tbl" border="2" cellspacing="3" cellpadding="5">
                            <thead>
                                <tr>
                                    <th> Kod </th>
                                    <th> Opis </th>
                                    <th> Wartość </th>
                                    <th> Status </th>
                                    <th> Opcje </th>
                                </tr>
                            </thead>
                            <tbody>';
        while ($kody = mysqli_fetch_object($kr)) {
            echo '<tr>';
            echo '<td>' . $kody->kod . '</td>';
            echo '<td>'.$kody->opis.'</td>';
            echo '<td>' . $kody->wartosc . '</td>';
            echo '<td>' . $kody->status . '</td>';
            if ($kody->status == "Aktywny") {
                echo '<td><form method="post" action="kody.php?idkod=' . $kody->id . '"><input type="submit" class="btn" value="Dezaktywuj" name="dez" /></form></td>';
            } else {
                echo '<td>
                    <form method="post" action="kody.php?idkod=' . $kody->id . '"><input type="submit" class="btn" value="Aktywuj" name="ak" /></form>
                    <a href="admin.php?karta=2&usunkod='.$kody->id.'"><img src="../ikon/usun.png" height="18"></a>
                 </td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    else
    {
        echo '<form name="ddkr" method="post">
<label>Kod: </label><input id="txtk" type="text" class="tb" name="txtkod">&nbsp;<input type="button" class="btn" value="Generuj kod" onclick="gen();" /><br/><br/>
<label>Typ kodu: </label><select name="typ">
<option value="k-">Wartość koszyk</option>
<option value="d=">Obniżona dostawa</option>
<option value="r-">Obniżka o wartość</option>
</select><br/><br/>
<label>Wartość kodu: </label><input type="text" class="tb" name="wartosc" /><br/><br/>
<label>Procentowe - 0,01 => 1%, Kwotowe - 20,01 => 20 zł 01 gr </label><br/><br/>
<label>Opis: </label><textarea class="tb" name="opis"></textarea><br/><br/>
<input type="submit" class="btn" value="Wstaw" name="wstkod" />
<input type="submit" class="btn" value="Anuluj" name="anulujkod" />
</form>';
    }

    if (isset($_POST['wstkod']))
    {
        mysqli_query($pol,"INSERT INTO kr VALUES(NULL,'".$_POST['txtkod']."','".$_POST['opis']."','".$_POST['typ'].str_replace(',','.',$_POST['wartosc'])."',DEFAULT)");
        header("Location: admin.php?karta=2");
    }

    if (isset($_POST['ak'])) {
        mysqli_query($pol, "UPDATE `kr` SET `status` = 'Aktywny' WHERE `kr`.`id` =" . $_GET['idkod']);
        header("Location: admin.php?karta=2");
    }

    if (isset($_POST['dez'])) {
        mysqli_query($pol, "UPDATE `kr` SET `status` = 'Dezaktywowany' WHERE `kr`.`id` =" . $_GET['idkod']);
        header("Location: admin.php?karta=2");
    }
}
else
{
    header("Location: index.php");
}
?>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        function randomPassword(length) {
            var chars = "abcdefghijklmnoprstqwvxuyz1234567890ABCDEFGHIJKLMNOPRSQTXWUVZ";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            return pass;
        }

        function gen() {
            ddkr.txtk.value = randomPassword(12);
        }
    </script>
</head>
<body>

</body>
</html>
