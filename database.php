<?php

include_once 'db.php';

class DatabaseLayer
{
    public function __constructor()
    {
    }

    function exec($sql) {
        try {
            $db = new DbConnect();
            $query = $db->getDb()->prepare($sql);
            $query->execute();
            $object = null;
            if ($query->rowCount() > 1) { // TODO определить вывод json 1 или больше объектов
                $object = $query->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $object = $query->fetch(PDO::FETCH_OBJ);
            }
            if ($object) {
                echo json_encode($object);
            } else {
                throw new PDOException('No records found.');
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    public function getUser($id_user) {
        $sql = "SELECT * FROM users WHERE id_user = $id_user limit 1";
        $this->exec($sql);
    }

    public function getUsers()
    {
        $sql = "SELECT * FROM users";
        $this->exec($sql);
    }

    public function getUserType($id_user_type) {
        $sql = "SELECT * FROM users_type WHERE id_user_type = $id_user_type limit 1";
        $this->exec($sql);
    }

    public function getUsersType()
    {
        $sql = "SELECT * FROM users_type";
        $this->exec($sql);
    }

    public function getCourses()
    {
        $sql = "SELECT * FROM courses";
        $this->exec($sql);
    }

    public function getLessons()
    {
        $sql = "SELECT * FROM lessons";
        $this->exec($sql);
    }

    public function getListeners()
    {
        $sql = "SELECT * FROM listeners";
        $this->exec($sql);
    }

    public function isUserExist($login)
    {
        $db = new DbConnect();
        $sql = "SELECT * FROM users WHERE name = $login limit 1";
        $query = $db->getDb()->prepare($sql);
        $query->execute();
        if (count($query) > 0) {
            return true;
        }
        return false;
    }

    public function addNewUser($email, $password)
    {
        $this->dbObject = new DbConnect();
        if (!$this->isUserExist($email, $password)) {
            $sql = "Insert into user (email, password) values ('$email', '$password')";
            $result = mysqli_query($this->dbObject->getDb(), $sql);
            if ($result) {
                return true;
            }
            return false;
        }
    }

}

?>