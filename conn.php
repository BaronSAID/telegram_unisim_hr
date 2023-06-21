<?php

$olink = oci_connect(G_ORA_USER, G_ORA_PASS, G_ORA_SCHEMA);

function insert_plan($p_username, $p_chat_id, $p_message, $p_data_msg) {
    global $olink;
    $v_message = mb_convert_encoding($p_message, 'windows-1251', 'CodificareOriginala');
    $sql_text = "insert into TELEGRAM_HR_CHAT
  (username, chat_id, message)
values
  ('".$p_username."', '".$p_chat_id."', '".$v_message."')";

    echo $sql_text;

    $stmt = oci_parse($olink, $sql_text);
    $res = oci_execute($stmt);

    return $res;
}