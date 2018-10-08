<?php
	error_reporting(E_ALL);
	function sendMessageBot($chatId,$userId,$command = null,$message,$html = null){
		global $token;
		$UserFile = "users.json"; //File utente
		$userJson = file_get_contents($UserFile);
		// Leggo File Utente
		$userDecode = json_decode($userJson,true);
		if($command && $command !== "setmessage" && isset($userDecode[$userId]["command"][$command]) && $userDecode[$userId]["command"][$command] > time()){
			return false;
		}
		$userDecode[$userId]["command"][$command] = strtotime("+2 minutes");
		//Setto variabile Utente
		$data = array(
        'chat_id' => urlencode($chatId),
        'text' => $message
		);
		if($html)$data['parse_mode'] = 'html';
		
		$url ="https://api.telegram.org/bot{$token}/sendMessage";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		//Invia File
		file_put_contents($UserFile,json_encode($userDecode));
	}
	$botConfig = json_decode(file_get_contents("bot.json"),true);
	
	$content = file_get_contents("php://input");
	file_put_contents("inputs.json",$content);
	$update = json_decode($content, true);
	$token = "553791725:AAEg_xmne9OYFNjqiak7ORJoW7mm4pqPcLo";
	if($update){
		$message = isset($update['message']) ? $update['message'] : "";
		$userId = isset($message["from"]["id"]) ? $message["from"]["id"] : "";
		$messageConfig = isset($botConfig["setMessage"][$userId]) ? true : false;
		$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
		$text = isset($message["text"]) ? $message["text"] : "";
		$adminBot = [225541225,264445569];
		$command = strpos($text,'/') === 0 ? explode(" ",substr($text,1))[0] : false;
		file_put_contents("command.json",$command);
		if($userId && $chatId && $command && ($messageConfig === false || $command !== "setmessage")){
			switch($command){
				case "setmessage":
					if(in_array($userId,$adminBot)){
							if($messageConfig === false){
								sendMessageBot($chatId,$userId,$command,"Nel prossimo messaggio, scrivi il testo da far comparire ogni 8 ore, per annullare l'operazione clicca /setMessage");
								$botConfig["setMessage"][$userId] = 1;
							}
							else{
								sendMessageBot($chatId,$userId,$command,"Annullato");
								unset($botConfig["setMessage"][$userId]);
							}
							file_put_contents("bot.json",json_encode($botConfig));
						}
				break;
				case "message":
					sendMessageBot($chatId,$userId,null,file_get_contents("message.txt"),1);
				break;
				
				/*case "info":
					sendMessageBot($chatId,$userId,$command,"Info");
				break;
				case "test":
					sendMessageBot($chatId,$userId,$command,"Prova123");
				break;*/
			}
		}
		else if($userId && $chatId && $messageConfig && $text && ! $command){
			file_put_contents("message.txt",$text);
			sendMessageBot($chatId,$userId,null,"Anteprima messaggio:");
			sendMessageBot($chatId,$userId,null,$text,1);
			sendMessageBot($chatId,$userId,null,"Messaggio Impostato Correttamente");
			unset($botConfig["setMessage"][$userId]);
			file_put_contents("bot.json",json_encode($botConfig));
		}
		//;
	}
?>