<?php
	$content = file_get_contents("php://input");
	file_put_contents("test.json",$content);
	$update = json_decode($content, true);
	$token = "553791725:AAEg_xmne9OYFNjqiak7ORJoW7mm4pqPcLo";
	if($update){
		$message = isset($update['message']) ? $update['message'] : "";
		$messageId = isset($message['message_id']) ? $message['message_id'] : "";
		$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
		$text = "Prova";
		$data = array(
        'chat_id' => urlencode($chatId),
        'text' => urlencode($text)
    );

	$url ="https://api.telegram.org/{$token}/sendMessage";

	//  open connection
	$ch = curl_init();
	//  set the url
	curl_setopt($ch, CURLOPT_URL, $url);
	//  number of POST vars
	curl_setopt($ch, CURLOPT_POST, count($fields));
	//  POST data
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	//  To display result of curl
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//  execute post
	$result = curl_exec($ch);
	//  close connection
	curl_close($ch);
	}
?>