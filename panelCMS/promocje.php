<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (isset($_POST['Anuluj']))
{
    header("Location: admin.php?karta=5");
}

if (isset($_GET['usunpromo']))
{
    mysqli_query($pol,"DELETE FROM promocje WHERE id=".$_GET['usunpromo']);
    header("Location: admin.php?karta=5");
}

if (isset($_POST['wstaw']))
{
    if (isset($_FILES['baner']))
    {
        $fn = $_FILES['baner']['name'];
            try
            {
                move_uploaded_file($_FILES['baner']['tmp_name'], 'pimg/'.$fn);
            }
            catch (Exception $e)
            {
                echo $e;
            }
        }
    mysqli_query($pol, "INSERT INTO promocje VALUES(NULL,'".implode(';',$_POST['produkty'])."','".$fn."','".$_POST['tytul']."','".$_POST['opis']."')");
    header("Location: admin.php?karta=5");
}

if (!isset($_GET['dodajpromo']))
{
    echo '<form method="post" action="admin.php?karta=5&dodajpromo" accept-charset="UTF-8"><input type="submit" class="btn" value="Dodaj promocje" /></form>';
    echo '<table class="tbl" border="2" cellspacing="4" cellpadding="5"><thead><tr>
                                    <th> ID </th>
                                    <th> Tytuł </th>
                                    <th> Baner </th>
                                    <th> Opis </th>
                                    <th> Produkty </th>
                                    <th> Opcje </th>
                                </tr></thead><tbody>';
    /*if (mysqli_query($pol, "SELECT * FROM promocje ") != NULL) {

    }
    else
    {
        echo '<tr><td colspan="8">Brak promocji</td></tr>';
    }*/
    $q = mysqli_query($pol, "SELECT * FROM promocje ");
    while ($pq = mysqli_fetch_assoc($q)) {
        echo '<tr>';
        echo '<td>'.$pq['id'].'</a></td>';
        echo '<td>'.$pq['tytul'].'</td>';
        echo '<td>';
        echo '<div class = "option-heading" onclick = "ps(this, children);">Baner<div class = "arrow-up">&#9650;</div> <div class="arrow-down">&#9660;</div></div>';
        echo '<div class="option-content"><img src="pimg/'.$pq['baner'].'" height="108"></div>';
        echo '</td>';
        echo '<td>';
        echo '<div class = "option-heading" onclick = "ps(this, children);">Opis promocji<div class = "arrow-up">&#9650;</div> <div class="arrow-down">&#9660;</div></div>';
        echo '<div class="option-content">'.$pq['opis'].'</div>';
        echo '</td>';
        echo '<td>';
        echo '<div class = "option-heading" onclick = "ps(this, children);">Produkty<div class = "arrow-up">&#9650;</div> <div class="arrow-down">&#9660;</div></div>';
        echo '<div class="option-content">';
        //.$pq->id_prod.
        if (!empty($pq['id_prod']))
        {
            $prodikody = explode(';',$pq['id_prod']);
            echo '<table class="tbl" border="1" cellspacing="2" cellpadding="3"><thead><tr><th>Produkt</th><th>Sztuk</th><th>Cena</th></tr></thead><tbody>';
            for ($i = 0; $i < count($prodikody); $i++)
            {
                $q = mysqli_query($pol, "SELECT * FROM produkty WHERE id=".$prodikody[$i]);
                $oq = mysqli_fetch_object($q);
                echo '<tr><td>'.$oq->nazwa.'</td><td>'.$oq->dostep.'</td><td>'.$oq->cena.'</td></tr>';
            }
            echo '</thead></table>';
        }
        else
        {
            echo 'Brak produktów.';
        }
        echo '</div>';
        echo '</td>';
        echo '<td><a href="admin.php?karta=5&usunpromo='.$pq['id'].'" title="Usuń promocje"><img src="../ikon/usun.png" height="18"></a></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}
else
{
    echo '<form method="post" enctype="multipart/form-data">
           <label>Tytuł: </label><input type="text" class="tb" name="tytul"/><br/><br/>
           <label>Dodaj baner: </label><input type="file" name="baner" /><br/><br/>
           <label>Opis: </label><textarea class="tb" name="opis"></textarea><br/>
           <label>
           Produkty do promocji (Klawisz Ctrl - trzymanie zaznacza kolejne):
           <select name="produkty[]" multiple="multiple">';
                $q = mysqli_query($pol,'SELECT * FROM produkty');
        while ($p = mysqli_fetch_object($q))
        {
            echo '<option value="'.$p->id.'">'.$p->nazwa.'&nbsp;&nbsp;'.str_replace('.',',',$p->cena).' zł</option>';
        }
    echo '</select>
           </label>
           <br/><br/>
           <input type="submit" class="btn" value="Dodaj" name="wstaw" />
           <input type="submit" class="btn" value="Anuluj" name="Anuluj">
           </form>';
}

?>