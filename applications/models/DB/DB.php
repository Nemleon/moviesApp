<?php

namespace applications\models\DB;

use PDO;

class DB
{
    private $DB;
    private $attributes = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    private $sqlType = 'mysql';
    private $host = '192.168.10.10';
    private $port = '3306';
    private $DBName = 'homestead';
    private $charSet = 'utf8';

    private $userName = 'homestead';
    private $pass = 'secret';

    private $dns;

    public function __construct()
    {
        $this->dns = "{$this->sqlType}:host={$this->host};port={$this->port};dbname={$this->DBName};charset={$this->charSet}";
        $this->DB = new PDO($this->dns, $this->userName, $this->pass, $this->attributes);
        $this->DB->beginTransaction();
    }

    protected function PDO()
    {
        return $this->DB;
    }

    public function __destruct()
    {
        if($this->DB->inTransaction()) {
            $this->DB->commit();
        }
        $this->DB = null;
    }
}