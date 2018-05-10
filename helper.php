<?php ใใใใ
include_once('database.php');

class Constant {
	public static $HOST = 'https://angsila.cs.buu.ac.th/~57160605/Linebot/';
}

class Success {
    public $success;
}

function createMessage($message) {
	$botMessages = [
		'type' => 'text',
		'text' => $message
	];
	return $botMessages;
}

function createImage($imgUrl) {
	$botMessages = [
		'type' => 'image',
		'originalContentUrl' => $imgUrl,
		'previewImageUrl' => $imgUrl
	];
	return $botMessages;
}

function setState($userId, $userState) {
	$objConn = new Database();
	$objConn->connect();

	$success = $objConn->query("UPDATE `chatbotUser` SET `userState` = '" . $userState . "' WHERE `userId` = '" . $userId . "'");
	$response = new Success();
	$response->success = $success;

	return $response;
}

function getState($userId)  {
	$objConn = new Database();
	$objConn->connect();

	$objConn->query("SELECT `userState` FROM `chatbotUser` WHERE `userId` = '" . $userId . "'");
	$response = array();

	foreach ($objConn->results->fetchall() as $temp) {
		$response[] = $temp['userState'];
	}

	return $response[0];
}

function setStudentId($userId, $studentId){
	$objConn = new Database();
	$objConn->connect();

	$success = $objConn->query("UPDATE `chatbotUser` SET `studentId` = '" . $studentId . "' WHERE `userId` = '" . $userId . "'");
	$response = new Success();
	$response->success = $success;

	return $response;
}

function getStudentId($userId) {
	$objConn = new Database();
	$objConn->connect();

	$objConn->query("SELECT `studentId` FROM `chatbotUser` WHERE `userId` = '" . $userId . "'");
	$response = array();

	foreach ($objConn->results->fetchall() as $temp) {
		$response[] = $temp['studentId'];
	}

	return $response[0];
}

function createUser($userId) {
	$objConn = new Database();
	$objConn->connect();
	
	$success = $objConn->query("INSERT INTO `chatbotUser` (`userId`) VALUES ('" . $userId . "')");
	$response = new Success();
	$response->success = $success;
	
	return $response;
}

?>
