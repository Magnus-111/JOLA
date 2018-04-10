<?php

/**
 * Description of towar
 *
 * @author Cezary
 */
class Towar {
    var $id;
    var $nazwa;
    var $cena;
    var $sztuk;
    var $waga;
    var $kodrabat;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNazwa()
    {
        return $this->nazwa;
    }

    /**
     * @return mixed
     */
    public function getCena()
    {
        return $this->cena;
    }

    /**
     * @return mixed
     */
    public function getSztuk()
    {
        return $this->sztuk;
    }

    /**
     * @return mixed
     */
    public function getWaga()
    {
        return $this->waga;
    }
}

?>