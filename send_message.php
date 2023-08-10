<?php
ini_set('display_errors',1);
require 'vendor/autoload.php';
include('env.php');
include('conn.php');

use Telegram\Bot\Api;

$telegramToken = G_API_TOKEN;

$db_id_message = $_GET['id'];
$db_message = get_massege_by_id($db_id_message);

$telegram = new Api($telegramToken);

$chatId = $db_message[0][4];

$v_message_body = $foo=iconv('windows-1251','utf-8',$db_message[0][2]);

$message = $db_message[0][3].chr(10).$v_message_body;

$response = $telegram->sendMessage([
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode'=>'Markdown'
]);

if ($response->getDate() <> null) {
    echo 'OK';
} else {
    echo 'Eroare la trimiterea mesajului: ' . $response->getDescription();
}
