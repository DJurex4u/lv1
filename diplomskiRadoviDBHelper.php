<?php

class diplomskiRadoviDBHelper {

    protected $db;
    private static $instance = null;

    private function __construct(mPDO $db) {
        $this->db = $db;
    }

    public static function getInstance(mPDO $db) {
        if (self::$instance == null) {
            self::$instance = new diplomskiRadoviDBHelper($db);
        }
        return self::$instance;
    }

    public function insert($name, $text, $link, $oib) {
        $stmt = $this->db->prepare("INSERT INTO diplomski_radovi (naziv_rada, tekst_rada, link_rada, oib_tvrtke) VALUES (?, ?, ?, ?)");
        $valid = $stmt->execute([$name, $text, $link, $oib]);
        return $valid;
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM diplomski_radovi")->fetchAll();
    }
}
