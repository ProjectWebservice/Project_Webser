<?php
include_once('database.php');
include_once('helper.php');

function checkQuestion($userId, $text) {
    $userState = getState($userId);

    switch ($userState) {
        case 0:
            return askStudentName($userId);
        case 1: 
            return checkStudentName($userId, $text);
        case 2:
            return checkNationalId($userId, $text);
        case 3:
            return checkApiQuestion($userId, $text);
    }
}

function askStudentName($userId) {
    setState($userId, 1);

    $messages = array();
    $messages[] = createMessage("กรุณาระบุรหัสนิสิต");

    return $messages;
}

function checkStudentName($userId, $text) {
	$objConn = new Database();
	$objConn->connect();

	//$text = trim($text, ' ');
	//$doubleName = explode(' ', $text);
	
	/*if (count($doubleName) == 1) {
		$objConn->query("SELECT * FROM `chatbotStudent` WHERE `studentFirstName` = '" . $doubleName[0] . "' OR `studentLastName` = '" . $doubleName[0] . "'");
	} else {
		$objConn->query("SELECT * FROM `chatbotStudent` WHERE `studentFirstName` = '" . $doubleName[0] . "' AND `studentLastName` = '" . $doubleName[1] . "'");
    }*/
    
    $objConn->query("SELECT * FROM `chatbotStudent` WHERE `studentId` = '" . $text . "'");

	$resultArray = [];
	$rows = array();

	foreach ($objConn->results->fetchall() as $temp) {
		$rows[] = $temp;
	}

	if (count($rows) > 0) {
		$studentId = $rows[0]['studentId'];

		setStudentId($userId, $studentId);
		setState($userId, 3);
		
		//$response = array();
		//$response[] = createMessage("เลขบัตรประชาชน");

		//return $response;
        return showAllQuestion();
	} else {
		$response = array();
		$response[] = createMessage("ไม่พบนิสิตชื่อ " . $text);

		return $response;
	}
}

// function checkNationalId($userId, $text) {
//     $objConn = new Database();
//     $objConn->connect();

//     $studentId = getStudentId($userId);

//     $objConn->query("SELECT `studentId` FROM `chatbotStudent` WHERE `studentId` = '" . $studentId . "' AND `userNationId` = '" . $text . "'");
//     $rows = array();

//     foreach ($objConn->results->fetchall() as $temp) {
//         $rows[] = $temp['studentId'];
//     }

//     if (count($rows) > 0) {
//         setState($userId, 3);
        
//         return showAllQuestion();
//     } else {
//         setState($userId, 1);

//         $response = array();
//         $response[] = createMessage("ไม่พบเลขบัตรประชาชน");
//         $response[] = createMessage("คุณต้องการทราบข้อมูลนิสิตชื่ออะไร?");

//         return $response;
//     }
// }

function checkApiQuestion($userId, $text) {
    switch ($text) {
        case "เมนู":
            return showAllQuestion();
        case "เลือกนิสิตใหม่":
            return askStudentName($userId);
        case "NEXT":
            //return askStudentName($userId);
        case "PREVIOUS":
            //return askStudentName($userId);      
    }

    $objConn = new Database();
	$objConn->connect();

    $objConn->query("SELECT * FROM `chatbotQuestion` WHERE `questionId` = '" . $text . "'");

    foreach ($objConn->results->fetchall() as $temp) {
		$rows[] = $temp;
	}

    if (count($rows) > 0) {
        $endPoint = $rows[0]['questionAnswer'];

        return callApi($userId, $endPoint);
    } else {
        $messages = array();
        $messages[] = createMessage("ไม่พบคำถามที่ตรงกัน");
        $messages[] = createMessage("พิมพ์ 'เมนู' เพื่อดูคำถามทั้งหมด");

        return $messages;
    }
}

function showAllQuestion() {
    $objConn = new Database();
	$objConn->connect();

    $objConn->query("SELECT * FROM `chatbotQuestion`");

    $stringMessage = 'กรุณาเลือกวัน' . "\n";

	foreach ($objConn->results->fetchall() as $temp) {
		$stringMessage .= '- ' . $temp['questionAsk'] . "\n";
	}
    $stringMessage .= '- ' . "เลือกนิสิตใหม่";

    $messages = array();
    $messages[] = createMessage($stringMessage);

    return $messages;
}

function callApi($userId, $endPoint) {
	$ch = curl_init(Constant::$HOST . $endPoint);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=" . $userId);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);

	return $result;
}

?>