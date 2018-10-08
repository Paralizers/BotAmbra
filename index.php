<?php
	error_reporting(E_ALL);
	function sendMessageBot($chatId,$userId,$command = null,$message){
		global $token;
		$UserFile = "users.json"; //File utente
		$userJson = file_get_contents($UserFile);
		// Leggo File Utente
		$userDecode = json_decode($userJson,true);
		if(isset($userDecode[$userId]["command"][$command]) && $userDecode[$userId]["command"][$command] > time()){
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
		file_put_contents($UserFile,json_encode($userDecode));
	}
	$content = file_get_contents("test.js");
	file_put_contents("input.json",$content);
	$update = json_decode($content, true);
	$token = "553791725:AAEg_xmne9OYFNjqiak7ORJoW7mm4pqPcLo";
	if($update){
		$message = isset($update['message']) ? $update['message'] : "";
		$userId = isset($update["message"]["from"]["id"]) ? $update["message"]["from"]["id"] : "";
		$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
		$text = isset($message["text"]) ? $message["text"] : "";
		print_r($text);
		$adminBot = [225541225];
		$command = strpos("/",$message) === 0 ?  substr(explode(" ",$message)[0],1) : "base";
		if($userId && $chatId && $text){
			switch($command){
				case "info":
					sendMessageBot($chatId,$userId,$command,"Info");
				break;
				case "test":
					sendMessageBot($chatId,$userId,$command,"Prova123");
				break;
			}
		}
		//;
	}
?>