<?php
	error_reporting(E_ALL);
	function sendMessageBot($chatId,$userId = null,$command = null,$message,$html = null){
		global $token;
		$UserFile = "users.json"; //File utente
		$userJson = file_get_contents($UserFile);
		// Leggo File Utente
		$userDecode = json_decode($userJson,true);
		if($userId && $command && $command !== "setmessage" && isset($userDecode[$userId]["command"][$command]) && $userDecode[$userId]["command"][$command] > time()){
			return false;
		}
		if($userId && $command !== "setmessage")$userDecode[$userId]["command"][$command] = strtotime("+5 minutes");
		//Setto variabile Utente
		$data = array(
        'chat_id' => $chatId,
        'text' => $message
		);
		if($html)$data['parse_mode'] = 'html';
		if($_GET["automatic"]){
			print_r($data);
		}
		$url ="https://api.telegram.org/bot{$token}/sendMessage";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		if($_GET["automatic"]){print_r($result);}
		curl_close($ch);
		//Invia File
		file_put_contents($UserFile,json_encode($userDecode));
	}
	$token = "553791725:AAEg_xmne9OYFNjqiak7ORJoW7mm4pqPcLo";
	$botConfig = json_decode(file_get_contents("bot.json"),true);
	if($_POST["automatic"] === "2bmamgk"){
		$text = file_get_contents("message.txt");
		if($text)sendMessageBot(-1001365101368,null,null,$text,1);
	}
	else{
		$content = file_get_contents("php://input");
		file_put_contents("inputs.json",$content);
		$update = json_decode($content, true);
		
		if($update){
			$message = isset($update['message']) ? $update['message'] : "";
			$userId = isset($message["from"]["id"]) ? $message["from"]["id"] : "";
			$messageConfig = isset($botConfig["setmessage"][$userId]) ? true : false;
			$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
			$text = isset($message["text"]) ? $message["text"] : "";
			$adminBot = [225541225,264445569];
			$command = strpos($text,'/') === 0 ? explode(" ",explode("@",substr($text,1))[0])[0] : false;
			file_put_contents("command.json",$command);
			if($userId && $chatId && $command && ($messageConfig === false || $command === "setmessage")){
				switch($command){
					case "setmessage":
						if(in_array($userId,$adminBot) && $message["chat"]["type"] === "private"){
								if($messageConfig === false){
									sendMessageBot($chatId,$userId,$command,"Nel prossimo messaggio, scrivi il testo da far comparire ogni 8 ore, per annullare l'operazione clicca /setmessage");
									$botConfig["setmessage"][$userId] = 1;
								}
								else{
									sendMessageBot($chatId,$userId,$command,"Annullato");
									unset($botConfig["setmessage"][$userId]);
								}
								file_put_contents("bot.json",json_encode($botConfig));
							}
					break;
					case "message":
						$text = file_get_contents("message.txt");
						if($text)sendMessageBot($chatId,$userId,null,$text,1);
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
				unset($botConfig["setmessage"][$userId]);
				file_put_contents("bot.json",json_encode($botConfig));
			}
		}
		//;
	}
?>