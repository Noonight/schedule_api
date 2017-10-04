<?php
include_once 'config.php';

class DbConnect
{
    private $db;
    public function __construct()
    {
        try {
            $this->db = new PDO("mysql:host =".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASSWORD);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getDb()
    {
        return $this->db;
    }
}
?>