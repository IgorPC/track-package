<?php


namespace IgorPC\TrackPackage\DB;

use \PDO;

class Connection
{
    private String $DB_NAME;
    private String $DB_HOST;
    private String $DB_USER;
    private String $DB_PASS;

    public function __construct(String $DB_NAME, String $DB_HOST, String $DB_USER, String $DB_PASS = '')
    {
        $this->DB_NAME = $DB_NAME;
        $this->DB_HOST = $DB_HOST;
        $this->DB_USER = $DB_USER;
        $this->DB_PASS = $DB_PASS;
    }

    public function connect()
    {
        $conn = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
        if($conn){
            $conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            return $conn;
        }else{
            return false;
        }
    }
}