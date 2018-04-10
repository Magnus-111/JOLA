<?php
    $f = ' ';
    $q = mysqli_query($pol, "SELECT * FROM lak ".$f);
    if (!isset($_GET['usunklienta']) && !isset($_GET['edytujklienta']))
    {
        echo '
        <div class = "option-heading" onclick = "ps(this, children);"> Filtruj: <div class = "arrow-up">&#9650;</div> <div class="arrow-down">&#9660;</div></div>
<div class="option-content">
    <form method="post" action="">
        <br/>
        <label>ID: </label><input type="text" class="tb" name="idk" /><br/><br/>
        <label>Imię: </label><input type="text" class="tb" name="imiek" /><br/><br/>
        <label>Nazwisko: </label><input type="text" class="tb" name="nazwiskok" /><br/><br/>
        <input type="submit" class="btn" value="Fitruj" name="filtrujk" />
    </form>
</div>
<br/><br/>
<table class="tbl" border="1" cellspacing="1" cellpadding="9"><thead>
    <tr>
        <th> ID </th>
        <th> Imię </th>
        <th> Nazwisko </th>
        <th> Adres </th>
        <th> Adres - faktura </th>
        <th> Telefon </th>
        <th> Data rejestracji </th>
        <th> E-mail </th>
        <th> Aktywowane </th>
        <th> Newsletter </th>
        <th> Opcje </th>
    </tr>
    </thead>
    <tbody>';
        while ($kq = mysqli_fetch_object($q))
        {
            echo '<tr>';
            echo '<td>'.$kq->id_uzyt.'</td>';
            echo '<td>'.$kq->imie.'</td>';
            echo '<td>'.$kq->nazwisko.'</td>';
            echo '<td><label>'.$kq->adres.'</label></td>';
            echo '<td><label>'.$kq->adresf.'</label></td>';
            echo '<td>'.$kq->telefon.'</td>';
            $lk = mysqli_fetch_object(mysqli_query($pol,"SELECT * FROM lkk WHERE id_uzyt=".$kq->id_uzyt));
            $data = date_create($lk->data);
            echo '<td><label>'.date_format($data,"d.m.Y").'</label></td>';
            echo '<td>'.$lk->email.'</td>';
            if ($lk->aktywne == 'fałsz')
            {
                echo '<td>Nie</td>';
            }
            else
            {
                echo '<td>Tak</td>';
            }
            if ($lk->newsletter == 'fałsz')
            {
                echo '<td>Nie</td>';
            }
            else
            {
                echo '<td>Tak</td>';
            }
            echo '<td><a class="btn" href="admin.php?karta=4&edytujklienta='.$kq->id_uzyt.'">Edytuj</a></td>';
            echo '</tr>';
        }
    echo '</tbody></table>';
    }
    else if (isset($_GET['edytujklienta']))
    {
        $q = mysqli_query($pol, "SELECT * FROM lak WHERE id_uzyt=".$_GET['edytujklienta']);
        echo '<form method="post" action="">';
        while ($ek = mysqli_fetch_object($q))
        {
            echo '<label> Numer klienta: '.$ek->id_uzyt.'</label><br/>';
            echo '<label> Imię i Nazwisko: '.$ek->imie.' '.$ek->nazwisko.'</label><br/>';
            echo '<label> Adres: </label><br/><textarea class="tb" name="adres">'.$ek->adres.'</textarea><br/>';
            if (!empty($ek->adresf))
            {
                echo '<label> Adres faktura: </label><textarea class="tb" name="adresf">'.$ek->adresf.'</textarea><br/>';
            }
            echo '<label>Telefon: </label><input type="tel" class="tb" name="telefon" value="'.$ek->telefon.'"/><br/>';
            $kt = mysqli_query($pol, "SELECT * FROM lkk WHERE id_uzyt=".$ek->id_uzyt);
            $ktn = mysqli_fetch_object($kt);
            echo '<label>E-mail: </label><input type="text" class="tb" name="email" value="'.$ktn->email.'"/><br/>';
            $c = '';
            if ($ktn->newsletter == 'prawda'){ $c = 'checked'; }
            echo '<label>Newsletter: </label><input type="checkbox" name="newsletter" '.$c.' /><br/>';
            //echo '<input type="submit" name="resethaslo" class="btn" value="Resetuj hasło" /><br/>';
            echo '<input type="submit" name="zapisz" class="btn" value="Zapisz" />';
        }
        echo '</form>';
    }

?>