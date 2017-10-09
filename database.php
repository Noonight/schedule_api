<?php

include_once 'db.php';

class DatabaseLayer
{
    public function __constructor()
    {
    }

    function exec($sql)
    {
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
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    public function getUser($id_user)
    {
        $sql = "SELECT * FROM users WHERE id_user = $id_user limit 1";
        $this->exec($sql);
    }

    public function getUsers()
    {
        $sql = "SELECT * FROM users";
        $this->exec($sql);
    }

    public function getUserType($id_user_type)
    {
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

    public function getCourseForUser($user)
    {
        // id_course, title, description, startdate, enddate, id_user_lecturer
        //  , id_user, name, id_user_type, id_course
        /*$sql = "SELECT *
                FROM courses,
                  (SELECT users.id_user, users.name, users.id_user_type, listeners.id_courses
                  FROM users INNER JOIN listeners
                  ON users.id_user = listeners.id_user
                  WHERE users.id_user = $user
                  ORDER BY listeners.id_courses) AS tab
                WHERE tab.id_courses = courses.id_courses";*/
        $sql = "SELECT c.title, c.description, c.start_date, c.end_date, usv.name
                FROM courses AS c,
                  (SELECT users.id_user, users.name, users.id_user_type, listeners.id_courses
                  FROM users INNER JOIN listeners
                  ON users.id_user = listeners.id_user
                  WHERE users.id_user = $user
                  ORDER BY listeners.id_courses) AS tab,
                  (SELECT us.id_user, us.name
                  FROM users AS us) AS usv
                  WHERE tab.id_courses = c.id_courses
                  AND usv.id_user = c.id_user_lecturer";
        $this->exec($sql);
    }

    public function getLessonsForUser() {
        $sql = "SELECT l.id_lesson, l.id_courses, l.start_time, l.day, l.auditory, l.type
                FROM lessons as l,
                  (SELECT tab.id_courses
                  FROM courses,
                    (SELECT listeners.id_courses
                    FROM users INNER JOIN listeners
                    ON users.id_user = listeners.id_user
                    WHERE users.id_user = 1
                    ORDER BY listeners.id_courses) AS tab
                  WHERE tab.id_courses = courses.id_courses) AS tg
                WHERE l.id_courses = tg.id_courses";
        $this->exec($sql);
    }

    public function getScheduleCourse($id_course)
    {
        $sql = "
        SELECT c.id_courses, c.title, c.description, u.name, c.start_date, c.end_date
        FROM courses AS c, users AS u
        WHERE c.id_user_lecturer = u.id_user
        AND c.id_courses = $id_course";
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