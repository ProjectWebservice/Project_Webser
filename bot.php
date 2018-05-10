ๅๅๅ<?php
include_once('helper.php');
include_once('question.php');

letStart();

function letStart() {
	$content = file_get_contents('php://input');
	$events = json_decode($content, true);
    
    if (is_null($events['events'])) {
        $events = json_decode($_REQUEST['json'], true);
        $events['events'][0]['message']['text'] = $_REQUEST['testText'];
    }

	if (!is_null($events['events'])) {
		createUser($events['events'][0]['source']['userId']);

		foreach ($events['events'] as $event) {
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
				$text = $event['message']['text'];
				$replyToken = $event['replyToken'];
				$userId = $event['source']['userId'];

				$data = [
					'replyToken' => $replyToken,
					'messages' => checkQuestion($userId, $text)
				];

				$post = json_encode($data);

				sendMessage($post);
			} else if ($event['type'] == 'follow') {
				createUser($event['source']['userId']);
			} 
		} 
	}
}

function sendMessage($post){
	$access_token = 'ToM6Oc+3z1eG6zms7yWSGXVQ+WaqGh640QJeh1ys/rfbt3jyctfN1TIYF/3sl3n47QY1rBd21nDVGu0c8qr1RrJ+6RgOFleAOrA5EzsreuCpecxKSJI8y1JYl3NM2HBOr81fTlCq4ewblbmLtex+lQdB04t89/1O/w1cDnyilFU=';

	$url = 'https://api.line.me/v2/bot/message/reply';
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
}
?>
