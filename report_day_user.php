<?php
ini_set('display_errors',1);
require 'vendor/autoload.php';
include('env.php');
include('conn.php');

$user_id = $_GET['user_id'];
$data = $_GET['data'];

$db_comments = get_otrs_comment_by_user($user_id, $data);

$count_tickets = 1;

function adunaTimpuri(array $timpuri) {
    $oreTotale = 0;
    $minuteTotale = 0;

    foreach ($timpuri as $timp) {
        list($ore, $minute) = explode(':', $timp);
        $oreTotale += (int) $ore;
        $minuteTotale += (int) $minute;
    }

    $oreTotale += floor($minuteTotale / 60);
    $minuteTotale %= 60;

    return sprintf('%02d:%02d', $oreTotale, $minuteTotale);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Bootstrap Example</title>
    <meta charset="UTF-8">
</head>
<style>
    @page{
        margin: 3px;
        height: calc(100% + 10px);
        /*height: 350px;*/
        width: 850px;
    }
    .otrs_head{
        background-color: #D6D6FF;
    }

    .otrs_body {
        /*background-color: #e1dbdb;*/
        background-color: #F2F2F2;
    }

    .otrs_body2 {
        background-color: #FFFFFF;
    }

    .otrs_tab_style {
        width: 100%;
        font-size: 9px;
    }

    .otrs_tab_style TD, TR, TH {
        border: 1px solid #9090Fa;
    }

    .col_nn {
        background-color: #D6D6FF;
        width: 2%;
    }

    .col_w {
        width: 5% !important;
        text-align: center;
    }

    .col_w2 {
        width: 12% !important;
    }

    .col_w3 {
        width: 50% !important;
    }
</style>
<body class="otrs_body" style="height: auto; margin: 0; padding: 1px;">

<div class="container otrs_body">
    <h2 style="margin-top: 1px">Raport <?=$db_comments[0][8]?>, <?=$data?></h2>
    <table border="0" align="center" cellspacing="0" cellpadding="1" class=" otrs_tab_style">
        <tr class="otrs_head">
            <th colspan="8" style="text-align: center"><?=$data?></th>
        </tr>
        <tr class="otrs_head">
            <th class="col_nn">NN</th>
            <th >&nbsp;&nbsp;Событие</th>
            <th class="col_w2">&nbsp;&nbsp;Номер заявки</th>
            <th class="col_w3">&nbsp;&nbsp;Комментарий</th>
            <th class="col_w">ВРН</th>
            <th class="col_w">ВРО</th>
            <th class="col_w">РВН</th>
            <th class="col_w">Время</th>
        </tr>
        <?$total_sum = []?>
        <?foreach ($db_comments as $comment) {?>
            <tr>
                <td class="col_nn"><?=$comment[0]?></td>
                <td class="otrs_body">&nbsp;&nbsp;<?=iconv('windows-1251','utf-8',$comment[3])?>&nbsp;&nbsp;</td>
                <td><a href="https://otrs.una.md/otrs/index.pl?&Action=AgentTicketZoom&TicketNumber=<?=$comment[1]?>">&nbsp;&nbsp;<?=$comment[1]?>&nbsp;&nbsp;</a></td>
                <td class="otrs_body2">&nbsp;&nbsp;<?=iconv('windows-1251','utf-8',$comment[2])?>&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;<?=substr($comment[4], 11, 5)?>&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;<?=substr($comment[5], 11, 5)?>&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;<?=substr($comment[7], 11, 5)?>&nbsp;&nbsp;</td>
                <td class="otrs_body2">&nbsp;&nbsp;<?=$comment[6]?>&nbsp;&nbsp;</td>
            </tr>
        <? if ($comment[9] == '1') { array_push($total_sum, $comment[6]);}
        }?>
            <tr>
                <td colspan="7">&nbsp;&nbsp;Итого:	</td>
                <td>&nbsp;&nbsp;<?=adunaTimpuri($total_sum)?></td>
            </tr>

    </table>
</div>

</body>
</html>