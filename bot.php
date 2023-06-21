<?php
//ini_set('display_errors',1);

include('vendor/autoload.php');
include('env.php');
include('conn.php');

use Telegram\Bot\Api;

function getPlanFromMessage($p_text) {
    $v_variabile = G_LIST_KEY_PLAN;

    foreach ($v_variabile as $variabila) {
        if (stripos($p_text, $variabila) !== false) {
            return true;
        }
    }
    return false;
}

$telegram = new Api(G_API_TOKEN); //Устанавливаем токен, полученный у BotFather

$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя
    
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$v_date_msg = date('d.m.Y H:i:s', $result["message"]["date"]); //Data mesaj
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя

$msg = "Bot telegram pentru partaker planurilor zilnice pe E-Mail
Mesajele pentru partajare se cauta dupa cuvintele cheie:
 - " . implode("\n - ", G_LIST_KEY_PLAN);

$msg2 = "Your id:       ".$chat_id."; \n".
        "Your username: ".$name."; \n\n";

if($text){
    if ($text == "/start") {
        $reply = "Bine ati venit";
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif ($text == "/help") {
        $reply = $msg;
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
	}elseif ($text == "/info") {
        $reply =$msg2;
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    }elseif (getPlanFromMessage($text)) {
        insert_plan($name, $chat_id, $text, $v_date_msg);
        $reply ='Planul zilei trimis anterior VA FI PARTAJAT pe E-Mail la Administratia Unisim in decurs de 5-10 minute';
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
    } else {
        	$reply = $msg2;
        	$telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
    }
} else {
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $msg ]);
}



