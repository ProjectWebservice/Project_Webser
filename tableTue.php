<?php
include_once('../database.php');
include_once('../helper.php');

$userId = $_REQUEST['userId'];
$day1 = 2;
$studentId = getStudentId($userId);

$objConn = new Database();
$objConn->connect();
$objConn->query("SELECT * FROM `chatbotTableClass` WHERE `studentId` = '" . $studentId . "' AND `tableClassDay` = '" . $day1 . "' ORDER BY `tableClassStartTime`");

$allTableClass = array();

foreach ($objConn->results->fetchall() as $temp) {
    $allTableClass[] = $temp;
}

$stringMessage .= "ตารางเรียน";

$stringMessage .= getTableClassMessage($allTableClass, 1, "วันจันทร์");
$stringMessage .= getTableClassMessage($allTableClass, 2, "วันอังคาร");
$stringMessage .= getTableClassMessage($allTableClass, 3, "วันพุธ");
$stringMessage .= getTableClassMessage($allTableClass, 4, "วันพฤหัสบดี");
$stringMessage .= getTableClassMessage($allTableClass, 5, "วันศุกร์");
$stringMessage .= getTableClassMessage($allTableClass, 6, "วันเสาร์");
$stringMessage .= getTableClassMessage($allTableClass, 7, "วันอาทิตย์");

if ($stringMessage == "ตารางเรียน") {
    $stringMessage = "ไม่พบตารางเรียน";
}

$messages = array();
$messages[] = createMessage($stringMessage);

echo json_encode($messages);

function getTableClass($studentId) {
    $objConn = new Database();
    $objConn->connect();
    $objConn->query("SELECT * FROM `chatbotTableClass` WHERE `studentId` = '" . $studentId . "' ORDER BY `tableClassStartTime`");
    $rows = array();
    foreach ($objConn->results->fetchall() as $temp) {
        $rows[] = $temp;
    }
    return $rows;
}

function getTableClassMessage($allTableClass, $day, $dayName){
    $result = '';
    foreach ($allTableClass as $tableClass) {
        if ($tableClass['tableClassDay'] == $day) {
            $result .= "\n\n" . $dayName;
            foreach ($allTableClass as $tableClass) {
                if ($tableClass['tableClassDay'] == $day) {
                    $result .= "\n -> " . substr($tableClass['tableClassStartTime'], 0 , 5) . ' - ' . substr($tableClass['tableClassEndTime'], 0 , 5) . ' : ' . $tableClass['tableClassSubjectName']. ' ห้อง : ' . $tableClass['Room'];
                }
            }
            break;
        }
    }
    return $result;
}
?>