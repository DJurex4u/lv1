<?php

class mPDO {
    protected static $instance;
    public $pdo;

    private function __construct() {        
        $dsn = 'mysql:host=localhost;dbname=radovi;charset=utf8';
        $this->pdo = new PDO($dsn, 'root', '');
    }

    //simpleton
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __call($method, $args) {
        return call_user_func_array(array($this->pdo, $method), $args);
    }
}
