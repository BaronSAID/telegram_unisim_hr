<?php
//ini_set('display_errors',1);

include('vendor/autoload.php');
include('env.php');
include('conn.php');

use Telegram\Bot\Api;

function getPlanFromMessage($p_text) {
    $v_variabile = G_LIST_KEY_PLAN;

    $p_text = strtolower($p_text);

    foreach ($v_variabile as $variabila) {
        $variabila = strtolower($variabila);

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
        $reply = $msg2;
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply]);
    /*}elseif (getPlanFromMessage($text)) {
            insert_plan($name, $chat_id, $text, $v_date_msg);
            $reply ='Planul zilei trimis anterior VA FI PARTAJAT pe E-Mail la Administratia Unisim in decurs de 5-10 minute';
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);*/
    }elseif ($text == "/tst"){
        $msg11 = 'Nicolai Gaidarji Lucrari efectuate astazi (08.08.2023):

*Client => IMENSITATE
Manager => Dragni Violeta*
*IMENSITATE) #202306291033284 ] Автоматическая отвязка товаров от магазинов (part.2)*
_- corectarea erorii cind la prelucrarea documentelor prin JOB autoprocess din document dispare user care a creat documentul
_
*Client => Gara Tiraspol
Manager => Tuhari Pavel*
*Gara Tiraspol) #202308081033742 ] Штраф за незаезд*
_-Caonsultare cu client, manager (Vadim Cerbari, Victoria N.)  -sysfid 50007 adaugare FX pentru calcularea amenzii dupa formula indicata de client.
_
*Client => CONINFO
Manager => Tuhari Pavel*
*CONINFO) #202307271033611 ] GARANTIE, crearea native-oracle-php pentru Dorimax*
_1. Symfony\ Component \ HttpKernel \ Exception \ MethodNotAllowedHttpException, RouteCollection.php (255) 2. Corectari erori din functional Shopcart
3. Corectare erori pagina admin, activare pe pagina /home categoriile produselor (carusel)
4. Testare in /admin functional pentru adaugare pagini noi (Blog, Noutati) (de concretizat la D-nul Pavel daca activam aceste pagini pe site)  5. eroare la salvarea termeni si conditii de pastrare:
_
';
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $msg11, 'parse_mode'=>'Markdown']);
    }
} else {
    echo 1;
}



