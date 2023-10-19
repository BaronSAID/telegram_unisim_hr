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

function get_massege_by_id($id){
    global $olink;
    $query = 'select * from vraport_hr_otrs o where o.id_mesage = '.$id;
    $stmt = oci_parse($olink, $query);
    oci_execute($stmt, OCI_DEFAULT);
    $data_xls = array();
    while ($row_xls = oci_fetch_row($stmt))
        $data_xls[] = $row_xls;
    return $data_xls;
};

function get_otrs_comment_by_user($id, $data){
    global $olink;
    $query = "select rownum rn
                   , a.*
                   , CASE WHEN a.NTICKET IS NOT NULL THEN 1 ELSE 0 END AS is_sum
  from (
  select t.NTICKET
        ,t.WCOMMENT
        , e.ievent_ru
        ,TO_CHAR(t.DATEST, 'DD-MM-YYYY HH24:MI:SS') DATEST
        ,TO_CHAR(t.DATEEND, 'DD-MM-YYYY HH24:MI:SS') DATEEND
        ,t.TIME_ELAPS
        ,TO_CHAR(t.DATEST_SYSTEM, 'DD-MM-YYYY HH24:MI:SS') DATEST_SYSTEM
        ,(select s.last_name||' '||s.first_name from system_user s where s.id=t.USERID) user_name
  from VXUSERWORK t, XUSERWORK_EVENTS e
  where t.userid=".$id."
    and t.IEVENTID = e.id
    and trunc(t.datest_system) = '".$data."'
    --and t.wcomment is not null
  order by t.id
) a";
    $stmt = oci_parse($olink, $query);
    oci_execute($stmt, OCI_DEFAULT);
    $data_xls = array();
    while ($row_xls = oci_fetch_row($stmt))
        $data_xls[] = $row_xls;
    return $data_xls;
};

function get_user_name($p_user_id){
    global $olink;
    $query = 'select v.long_name from vraport_hr_otrs v where v.OTRS_USER_ID = '.$p_user_id.' and rownum=1';
    $stmt = oci_parse($olink, $query);
    oci_execute($stmt, OCI_DEFAULT);
    $data_xls = array();
    while ($row_xls = oci_fetch_row($stmt))
        $data_xls[] = $row_xls;
    return $data_xls;
};