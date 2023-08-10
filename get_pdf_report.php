<?php
ini_set('display_errors',1);
// Includerea autoloader-ului pentru a încărca biblioteca Dompdf
require 'dompdf/autoload.inc.php';
require 'vendor/autoload.php';
include('env.php');
include('conn.php');

use Dompdf\Dompdf;
use Telegram\Bot\Api;

$telegramToken = G_API_TOKEN;

$telegram = new Api($telegramToken);

$user_id = $_GET['user_id'];
$data = $_GET['data'];
$chat_id = $_GET['chat_id'];

$db_comments = get_otrs_comment_by_user($user_id, $data);
$comment_count = count($db_comments);
$height_page = 140 + ($comment_count*17);

// Crearea unui obiect Dompdf
$dompdf = new Dompdf([
    'defaultFont' => 'DejaVu Serif'
]);

// URL-ul de la care se va lua conținutul paginii HTML
$url = 'https://una.md/iRuta-devel/telegram_unisim_hr/report_day_user.php?user_id='.$user_id.'&data='.$data;

$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

// Obținerea conținutului paginii HTML de la URL
$html = file_get_contents($url, false, $context);

$encoding = mb_detect_encoding($html, 'auto');
$html = mb_convert_encoding($html, 'UTF-8', $encoding);

// Setarea codificării de caractere pentru conținutul paginii HTML
$html = '<meta charset="UTF-8">' . $html;

// Încărcarea HTML-ului în obiectul Dompdf
$dompdf->loadHtml($html);

// Opțiuni pentru generarea PDF-ului (opțional)
$dompdf->setPaper(array(0,0,$height_page , 840), 'landscape');
$dompdf->set_option('isHtml5ParserEnabled', true); // Permiterea parsării HTML5

// Setarea codificării de caractere pentru PDF
$dompdf->set_option('defaultFont', 'DejaVu Sans'); // Fontul care suportă caracterele specifice rusești

// Generarea PDF-ului
$dompdf->render();

// Salvarea PDF-ului pe disk sau afișarea în browser
//$dompdf->stream('exemplu.pdf', ['Attachment' => 0]);

$output = $dompdf->output();
file_put_contents('user_'.$user_id.'.pdf', $output);

$response = $telegram->sendDocument([
    'caption'  => 'Raport zilnic, '.$data,
    'chat_id'  => $chat_id,
    'document' => fopen('user_'.$user_id.'.pdf', 'rb'),
]);

?>
