<?php
	error_reporting(E_ALL);
	function sendMessageBot($chatId,$userId,$message){
		global $token;
		$UserFile = "users.json"; //File utente
		$userJson = file_get_contents();
		// Leggo File Utente
		$userDecode = json_decode($userJson);
		if($userDecode[$userId] && $userDecode[$userId] > time()){
			return false;
		}
		$userDecode[$userId] = strtotime("+2 minutes");
		//Setto variabile Utente
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
		file_put_contents("result.json",$result);
		//Invia File
		file_put_contents(json_encode($userDecode),$UserFile);
	}
	$content = file_get_contents("test.json");
	file_put_contents("input.json",$content);
	$update = json_decode($content, true);
	$token = "553791725:AAEg_xmne9OYFNjqiak7ORJoW7mm4pqPcLo";
	if($update){
		$message = isset($update['message']) ? $update['message'] : "";
		$userId = isset($update["from"]["id"]) ? $update["from"]["id"] : "";
		$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
		if($userId && $chatId)sendMessageBot($chatId,$userId,"Prova123");
	}
?>