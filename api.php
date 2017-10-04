<?php
    $servername = "localhost"; // sql сервер
    $username = "root"; // пользователь
    $password = ""; // пароль
    $dbname = "schedulesecondstep"; // имя базы данных chat
	try{
		$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		//echo "Connected";
	}catch (PDOException $e) {
		//print "Error!: " . $e->getMessage() . "<br/>";
		echo "Not Connected";
		die();
	}

	if (isset($_GET["table"])) {
        $table = $_GET['table'];
        if ($table == 'users') {
            $query = $db->prepare("SELECT * FROM users");
            $query->execute();
        } else if($table == 'listeners') {
            $query = $db->prepare("SELECT * FROM listeners");
            $query->execute();
        } else if($table == 'users_type') {
            $query = $db->prepare("SELECT * FROM users_type");
            $query->execute();
        } else if($table == 'courses') {
            $query = $db->prepare("SELECT * FROM courses");
            $query->execute();
        } else if($table == 'lessons') {
            $query = $db->prepare("SELECT * FROM lessons");
            $query->execute();
        } else {
            
        }
    }
    if($query->rowCount() > 0 && $query != null){
        $data = $query->fetchAll(PDO::FETCH_ASSOC);         	
        
        echo json_encode($data);
	} else {
		$json['success'] = 0;
		$json['message'] = 'No Data found';		
		$json['myintro'] = '';

		echo json_encode($json);
	}
?>