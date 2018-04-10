<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 05.05.2017
 * Time: 23:09
 */

require '../polacz.php';
require '../towar.php';
$q = mysqli_query($pol, "SELECT * FROM lz WHERE id=" . $_GET['idz']);
$oq = mysqli_fetch_object($q);
$koszykz = unserialize($oq->koszyk);

function wkz($k)
{
    $s = 0.00;
    for ($i = 0; $i < count($k); $i++) {
        $s += ($k[$i]->cena * $k[$i]->sztuk);
    }
    return $s;
}

if (isset($_GET['idz']) && isset($_GET['usun'])) {
    $ep;
    for ($j = 0; $j < $_GET['usun']; $j++)
    {
        $ep[$j] = $koszykz[$j];
    }
    for ($i = $_GET['usun'] + 1; $i < count($koszykz); $i++)
    {
            $ep[$i - 1] = $koszykz[$i];
    }
    $koszykz = $ep;
}
if (isset($_GET['idz']) && isset($_GET['dp']) && isset($_GET['szt'])) {
    if (substr($_GET['dp'], 0, 1) == "d") {
        $wyn = mysqli_query($pol, 'SELECT * FROM dodatkowe WHERE id=' . substr($_GET['dp'], 1, 1));
    } else {
        $wyn = mysqli_query($pol, 'SELECT * FROM produkty WHERE id=' . $_GET['dp']);
    }
    $produkt = mysqli_fetch_object($wyn);
    $towar = new Towar();
    $towar->id = $produkt->id;
    $towar->nazwa = $produkt->nazwa;
    $towar->cena = $produkt->cena;
    $towar->sztuk = $_GET['szt'];
    $towar->waga = $produkt->waga;
    $index = -1;
    if ($index == -1) {
        $koszykz[] = $towar;
    }
}
if (isset($_GET['idz']) && isset($_GET['dkr'])) {
    $kr = mysqli_query($pol, "SELECT * FROM kr WHERE id=" . $_GET['dkr'] . "");
    $rk = mysqli_fetch_object($kr);
    $kod = new Towar();
    $kod->id = "kr" . $rk->id;
    $kod->nazwa = $rk->opis;
    $up = 0;
    if (substr($rk->wartosc, 0, 2) == "d=") {
        for ($i = 0; $i < count($koszykz); $i++) {
            if (substr($koszykz[$i]->id, 0, 1) == "p") {
                $up = -1 * $koszykz[$i]->cena;
            }
        }
    } elseif (substr($rk->wartosc, 0, 2) == "r-") {
        $up = doubleval(substr($rk->wartosc, 1));
    } elseif (substr($rk->wartosc, 0, 2) == "k-") {
        $up = (wkz($koszykz) * doubleval(substr($rk->wartosc, 1)));
    }
    $kod->cena = $up;
    $kod->sztuk = 1;
    $kod->waga = 0;
    $index = -1;
    if ($index == -1) {
        $koszykz[] = $kod;
    }

}
if (isset($_GET['idz']) && isset($_GET['ddw']))
{
    $wysylka = mysqli_query($pol, 'SELECT * FROM wysylka WHERE id=' . $_GET['ddw'] . ' ');
    $wwysylka = mysqli_fetch_object($wysylka);
    $towarw = new Towar();
    $towarw->id = "p".$wwysylka->id;
    $towarw->nazwa = $wwysylka->typ;
    $towarw->cena = $wwysylka->cena;
    $towarw->sztuk = 1;
    $towarw->waga = 0;
    $index = -1;
    if ($index == -1) {
        $koszykz[] = $towarw;
    }

}
if (isset($_GET['idz']) && isset($_GET['dodaj']))
{
    for ($i = 0; $i < count($koszykz); $i++) {
        if ($koszykz[$i]->id == $_GET['dodaj'])
        {
            $koszykz[$i]->sztuk++;
        }
    }
}

if (isset($_GET['idz']) && isset($_GET['odejmij']))
{
    for ($i = 0; $i < count($koszykz); $i++) {
        if ($koszykz[$i]->id == $_GET['odejmij'])
        {
            $koszykz[$i]->sztuk--;
        }
    }
}

mysqli_query($pol, "UPDATE lz SET koszyk='" . serialize($koszykz) . "' WHERE id=" . $_GET['idz']);
header("Location: admin.php?karta=0&idz=" . $_GET['idz']);
?>