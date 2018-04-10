<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 29.04.2017
 * Time: 14:30
 */

require 'polacz.php';
require 'towar.php';

function ssnm($id)
{
    require 'polacz.php';
    $q = mysqli_query($pol, "SELECT * FROM produkty WHERE id=".$id);
    $p = mysqli_fetch_object($q);
    return $p->dostep;
}

if (!isset($_SESSION)) {
    session_start();
}
ob_start();

if (isset($_SESSION['koszyk'])) {
    $koszyk = unserialize(serialize($_SESSION['koszyk']));
    for ($i = 0; $i < count($koszyk); $i++) {
        if (isset($koszyk[$i])) {
            if (substr($koszyk[$i]->id, 0, 1) != "p" && substr($koszyk[$i]->id, 0, 1) != "d" && substr($koszyk[$i]->id, 0, 2) != "kr") {
                if (ssnm($koszyk[$i]->id) - $koszyk[$i]->sztuk < 0) {
                    $koszyk[$i]->sztuk = ssnm($koszyk[$i]->id);
                }
            }
        }
    }
}
else
{
    $nt = new Towar();
    $koszyk[] = $nt;
    unset($koszyk[0]);
    $_SESSION['koszyk'] = $koszyk;
}

function wartosckoszyka()
{
    $s = 0;
    if (isset($_SESSION['koszyk']))
    {
        $koszyk = unserialize(serialize($_SESSION['koszyk']));
        for ($i = 0; $i <count($koszyk); $i++)
        {
            if (isset($koszyk[$i])) $s += $koszyk[$i]->cena * $koszyk[$i]->sztuk;
        }
    }
    return $s;
}

if (isset($_GET['id'])) {
    $wyn = mysqli_query($pol, 'SELECT * FROM produkty WHERE id=' . $_GET['id']);
    $produkt = mysqli_fetch_object($wyn);
    $towar = new Towar();
    $towar->id = $produkt->id;
    $towar->nazwa = $produkt->nazwa;
    $towar->cena = $produkt->cena;
    $towar->sztuk = 1;
    $towar->waga = $produkt->waga;
    $index = -1;
    for ($i = 0; $i < count($koszyk); $i++) {
        if ($koszyk[$i]->id == $_GET['id']) {
            $index = $i;
            break;
        }
    }
    if ($index == -1) {
        $koszyk[] = $towar;
    } else {
        if (ssnm($koszyk[$index]->id) - $koszyk[$index]->sztuk > 0)
        {
            $koszyk[$index]->sztuk++;
        }
        else
        {
            unset($koszyk[$index]);
        }
    }
    for ($j = 0; $j < count($koszyk); $j++)
    {
        if (substr($koszyk[$j]->id,0,1) == "p")
        {
            unset($koszyk[$j]);
            unset($_SESSION['sp']);
            unset($_SESSION['sw']);
        }
    }
    unset($_GET['id']);
    $_SESSION['koszyk'] = $koszyk;
    $koszyk = "";
    header("Location: koszyk.php");
}

if (isset($_GET['ak']) && isset($_GET['idep']) && isset($_GET['szt']))
{
    unset($_SESSION['e_koszyk']);
    for ($i=0;$i<count($koszyk);$i++)
    {
        if (substr($koszyk[$i]->id,0,1) == "p")
        {
            unset($koszyk[$i]);
            unset($_SESSION['sp']);
            unset($_SESSION['sw']);
        }
        if (substr($koszyk[$i]->id,0,2) == "kr")
        {
            for ($j = 0; $j < count($_SESSION['akr']); $j++)
            {
                if ($_SESSION['akr'][$j] == $koszyk[$i]->kodrabat)
                {
                    unset($_SESSION['akr'][$j]);
                }
            }
            unset($koszyk[$i]);
        }
        if ($koszyk[$i]->id == $_GET['idep'])
        {
            if ($_GET['szt'] > 0)
            {
                if (ssnm($_GET['idep']) - $koszyk[$i]->sztuk > 0)
                {
                    $koszyk[$i]->sztuk++;
                }
                else
                {
                    $_SESSION['e_koszyk'] .= "Brak większej ilości produktu na magazynie.";
                }
            }
            elseif ($_GET['szt'] < 0) {
                if ($koszyk[$i]->sztuk > 1)
                {
                    $koszyk[$i]->sztuk--;
                }
            }
        }
    }
    unset($_GET['ak']);
    unset($_GET['idep']);
    unset($_GET['szt']);
    unset($_SESSION['akr']);
    $_SESSION['koszyk'] = $koszyk;
    $koszyk = "";
    header("Location: koszyk.php");
}

if (isset($_GET['iddod']))
{
    $dod = mysqli_query($pol, 'SELECT * FROM dodatkowe WHERE id=' . $_GET['iddod'].' ');
    $dodat = mysqli_fetch_object($dod);
    $toward = new Towar();
    $toward->id = "d".$dodat->id;
    $toward->nazwa = $dodat->nazwa;
    $toward->cena = $dodat->cena;
    $toward->sztuk = 1;
    $toward->waga = 0;
    $toward->kodrabat = " ";
    $index = -1;
    for ($i = 0; $i < count($koszyk); $i++) {
        if ($koszyk[$i]->nazwa == $dodat->nazwa) {
            $index = $i;
            break;
        }
    }
    if ($index == -1) {
        $koszyk[] = $toward;
    }
    unset($toward);
    unset($_GET['iddod']);
    $_SESSION['koszyk'] = $koszyk;
    header("Location: koszyk.php");
}

if (isset($_GET['w']) && isset($_GET['tw'])) {
    $wysylka = mysqli_query($pol, 'SELECT * FROM wysylka WHERE typ="' . $_GET['tw'] . '" AND wagamax >=' . $_GET['w']);
    $wwysylka = mysqli_fetch_object($wysylka);
    $towarw = new Towar();
    $towarw->id = "p".$wwysylka->id;
    $towarw->nazwa = $wwysylka->typ;
    $towarw->cena = $wwysylka->cena;
    $towarw->sztuk = 1;
    $towarw->waga = 0;
    $koszyk[] = $towarw;
    unset($_GET['w']);
    unset($_GET['tw']);
    $_SESSION['koszyk'] = $koszyk;
    header("Location: koszyk.php");
}

if (isset($_GET['idu'])) {
    if (substr($koszyk[$_GET['idu']],0,1) == "p") {
        unset($_SESSION['sp']);
        unset($_SESSION['sw']);
    }
    unset($koszyk[$_GET['idu']]);
    unset($_SESSION['akr']);
    $_SESSION['koszyk'] = $koszyk;
    unset($_GET['idu']);
    header("Location: koszyk.php");
}

if (isset($_GET['wyczysc']))
{
    unset($_SESSION['e_kod']);
    unset($_SESSION['e_koszyk']);
    unset($_SESSION['koszyk']);
    unset($_SESSION['akr']);
    unset($_SESSION['sw']);
    unset($_SESSION['sp']);
    header("Location: koszyk.php");
}

if (isset($_GET['kr'])) {
    $kr = mysqli_query($pol, 'SELECT * FROM kr WHERE kod = "'.$_GET['kr'].'" ');
    if (mysqli_num_rows($kr) > 0) {
        $rk = mysqli_fetch_object($kr);
        $kod = new Towar();
        $kod->id = "kr".$rk->id;
        $kod->nazwa = $rk->opis;
        $kod->kodrabat = $rk->kod;
        $up = 0;
        if (substr($rk->wartosc,0,2) == "d=") {
            if (substr($rk->wartosc,2,1) == '0') {
                for ($i = 0; $i < count($koszyk); $i++) {
                    if (substr($koszyk[$i]->id, 0, 1) == "p") {
                        $up = -1 * $koszyk[$i]->cena;
                    }
                }
            }
            else
            {
                $up = doubleval(substr($rk->wartosc,2,strlen($rk->wartosc) - 2));
            }
        }
        elseif (substr($rk->wartosc,0,2) == "r-") {
            $up = doubleval(substr($rk->wartosc,1));
        }
        elseif (substr($rk->wartosc,0,2) == "k-") {
            $up = round((wartosckoszyka() * doubleval(substr($rk->wartosc,1))),2,PHP_ROUND_HALF_UP);
        }
        $kod->cena = $up;
        $kod->sztuk = 1;
        $kod->waga = 0;
        $index = -1;
        if ($index == -1) {
            $koszyk[] = $kod;
        }
        $_SESSION['koszyk'] = $koszyk;
        $koszyk = "";
        header("Location: koszyk.php");
    }
    unset($_GET['kr']);
}
?>