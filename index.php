<?php
	error_reporting(E_ALL);
	function sendMessageBot($chatId,$message){
		$data = array(
        'chat_id' => urlencode($chatId),
        'text' => urlencode($message)
		);
		print_r($data);
		$url ="https://api.telegram.org/bot{$token}/sendMessage";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		file_put_contents("result.json",$result);
		curl_close($ch);
	}
	$content = file_get_contents("test2.json");
	$update = json_decode($content, true);
	$token = "553791725:AAEg_xmne9OYFNjqiak7ORJoW7mm4pqPcLo";
	print_r($update);
	if($update){
		$message = isset($update['message']) ? $update['message'] : "";
		$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
		print_r($update);
		sendMessageBot($chatId,"Prova123");
	}
?>