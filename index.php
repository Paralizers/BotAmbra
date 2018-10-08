<?php
error_reporting(e_all);

	function sendMessage($chatId,$message){
		$data = array(
        'chat_id' => urlencode($chatId),
        'text' => urlencode($message)
		);
		$url ="https://api.telegram.org/bot{$token}/sendMessage";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
	}
	$content = file_get_contents("test2.json");
	//file_put_contents("test.json",$content);
	$update = json_decode($content, true);
	$token = "553791725:AAEg_xmne9OYFNjqiak7ORJoW7mm4pqPcLo";
	if($update){
		$message = isset($update['message']) ? $update['message'] : "";
		$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
		sendMessage($chatId,"Prova123");
	}
?>