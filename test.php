<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors',1);

include('env.php');
include('conn.php');

insert_plan('User', 'RRR', 'dfdfdf', '20.06.2023 14:14:00');
