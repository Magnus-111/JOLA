<?php
$image = NULL;
$name = NULL;
$idp = NULL;
require '../polacz.php';
if (isset($_POST['wp'])) {
    $w = 'INSERT INTO produkty VALUES(NULL,"'. $_POST['nazwa'] .'",'. $_POST['sztuk'] .','. str_replace(",", ".", $_POST['cena']) .',NULL,'. str_replace(",", ".", $_POST['waga']) .',"'. $_POST['opis'] .'","' . $_POST['material'] . '",' . $_POST['typ'] . ')';
    mysqli_query($pol,$w);
    $pid = mysqli_query($pol, "SELECT * FROM `produkty` ORDER BY id DESC LIMIT 1");
    while ($odp = mysqli_fetch_object($pid)) {
        $idp = $odp->id;
    }
    if (isset($_FILES['images']))
    {
        foreach ($_FILES['images']['name'] as $key => $name)
        {
            $filename = $name;
            try
            {
                move_uploaded_file($_FILES['images']['tmp_name'][$key], 'pimg/'.$filename);
                mysqli_query($pol, 'INSERT INTO obr VALUES(NULL,"'.$filename.'",'.$idp.')');
            }
            catch (Exception $e)
            {
                echo $e;
            }
        }
    }
    /*$pid = mysqli_query($pol, "SELECT * FROM `produkty` ORDER BY id DESC LIMIT 1");
    while ($odp = mysqli_fetch_object($pid)) {
        $idp = $odp->id;
    }
    if (getimagesize($_FILES['image']['tmp_name']) == TRUE) {
        $image = addslashes($_FILES['image']['tmp_name']);
        $name = addslashes($_FILES['image']['name']);
        $image = file_get_contents($image, FALSE);
        $image = base64_encode($image);
        save($name, $image, $idp);
        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
            if (getimagesize($_FILES['images']['tmp_name'][$i]) == TRUE) {
                $image = addslashes($_FILES['images']['tmp_name'][$i]);
                $name = addslashes($_FILES['images']['name'][$i]);
                $image = file_get_contents($image, FALSE);
                $image = base64_encode($image);
                save($name, $image, $idp);
            }
            else
            {
                echo 'Nie wybrano zdjęcia.';
            }
        }
    } else {
        echo 'Nie wybrano miniaturki.';
    }*/
}
function save($name, $image,$id) {
    require '../polacz.php';
    $qry = "INSERT INTO zdj VALUES(NULL,'$name','$image','$id')";
    $r = mysqli_query($pol, $qry);
    $image = NULL;
    $name = NULL;
    if ($r) {
        $f = TRUE;
    } else {
        blad($r);
    }
}
function blad($t) {
    echo $t;
    return;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div class = "option-heading" onclick = "ps(this, children);">
            Dodaj produkt<div class = "arrow-up">&#9650;</div> <div class="arrow-down">&#9660;</div></div>
        <div id="" class="option-content">
            <br/>
            <form action="" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                <!--Miniaturka: <input type="file" name="image"><br/>-->
                Zdjęcia (Pierwsze zdjęcie jako główne): <input type="file" name="images[]" multiple ><br/>
                Nazwa artykułu: <input type="text" name="nazwa"><br/>
                Ilość sztuk: <input type="number" name="sztuk" min="0" step="1" value="0"><br/>
                Cena: <input type="number" name="cena" min="0.01" step="0.01" value="0.01"><br/>
                Waga: <input type="number" name="waga" min="0.01" step="0.01" value="0.01"><br/>
                Opis: <textarea name="opis"></textarea><br/>
                Materiał: <select name="material">
                    <option value=" ">Wybierz</option>
                    <option value="Naturalna skóra">Naturalna skóra</option>
                    <option value="Sztuczna skóra">Sztuczna skóra</option>
                    <option value="Tkanina">Tkanina</option>
                    <option value="Inny">Inny</option>
                </select><br/>
                Typ: <select name="typ">
                    <option value=" ">Wybierz</option>
                    <option value="1">Buty</option>
                    <option value="3">Portfele</option>
                    <option value="2">Torebki</option>
                    <option value="4">Dodatki</option>
                </select><br/>
                <br/>
                <input type="submit" name="wp" value="Wstaw">
            </form>
            <br/>
        </div>
    </body>
</html>
