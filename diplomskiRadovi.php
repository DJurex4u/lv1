<?php

include 'iRadovi.php';
include 'mPDO.php';
include 'diplomskiRadoviDBHelper.php';

class diplomskiRadovi implements iRadovi
{
    private $naziv_rada;
    private $tekst_rada;
    private $link_rada;
    private $oib_tvrtke;

    private $pdo;

    public function __construct()
    {
        $this->pdo = diplomskiRadoviDBHelper::getInstance(mPDO::getInstance());
    }

    public function create($name, $text, $link, $oib)
    {
        $this->naziv_rada = $name;
        $this->tekst_rada = $text;
        $this->link_rada = $link;
        $this->oib_tvrtke = $oib;
    }    

    public function read()
    {
        return json_encode($this->pdo->getAll());
    }

    public function print()
    {
        echo <<< EOT
            <hr>
            <h2>PRINT RADA:</h2>
            <p>Naziv: {$this->naziv_rada}</p>
            <p>Tekst: {$this->tekst_rada}</p>
            <p>Oib: {$this->oib_tvrtke}</p>
            <hr>
            <br><br><br>
        EOT;
        return;
    }

    public function save()
    {
        $this->pdo->insert($this->naziv_rada, $this->tekst_rada, $this->link_rada, $this->oib_tvrtke);
    }  
    
}