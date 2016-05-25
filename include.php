<?php

function get_client_ip() {
    $ipaddress = '';
    if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function zfslog($message) {
    $client_ip = get_client_ip();
    file_put_contents( "zfs.log" , date('Ymd-H:m')." - ".$client_ip." - ".$message."\n", FILE_APPEND);
}
