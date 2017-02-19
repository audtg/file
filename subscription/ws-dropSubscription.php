<?php

$host = 'localhost:C:\Program Files\Firebird\Firebird_2_5\data\test.fdb';
$username = 'sysdba';
$password = 'masterkey';

function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler("exception_error_handler");

$dbh = ibase_connect($host, $username, $password, 'utf-8');

//file_put_contents('ws-post.log', print_r($_POST, true));

$preparedSQL = ibase_prepare('
select ss.CONTACT_ID, ss.SUBJECT_ID
from SUBSCRIPTIONS ss
INNER JOIN CONTACTS c ON c.CONTACT_ID = ss.CONTACT_ID AND ss.CONTACT_ID = ?
INNER JOIN SUBJECTS s ON s.SUBJECT_ID = ss.SUBJECT_ID AND ss.SUBJECT_ID = ?
');
try {
    $sth = ibase_execute($preparedSQL, (integer)$_GET['CONTACT_ID'], (integer)$_GET['SUBJECT_ID']);

} catch
(ErrorException $e) {
    @file_put_contents('logfile.log', $e->getMessage() . "\r\n", FILE_APPEND | LOCK_EX);
    echo json_encode(0);
}

if ($row = ibase_fetch_object($sth)) {
    $preparedSQL = ibase_prepare('delete from SUBSCRIPTIONS ss WHERE ss.CONTACT_ID = ? AND ss.SUBJECT_ID = ?');
    try {
        $sthDel = ibase_execute($preparedSQL, (integer)$_GET['CONTACT_ID'], (integer)$_GET['SUBJECT_ID']);
        echo json_encode(1);
    } catch
    (ErrorException $e) {
        @file_put_contents('logfile.log', $e->getMessage() . "\r\n", FILE_APPEND | LOCK_EX);
        echo json_encode(0);
    }
}


$XML_txt = '<?xml version="1.0" encoding="utf-8"?' . '>';
$XML_txt .= '<data>';
$XML_txt .= '<result>' . 1 . '</result>';
$XML_txt .= '</data>';

if ($_GET['json'] == 1) { // вывод в формате JSON
    $xml = simplexml_load_string($XML_txt);
    header("Content-Type:application/json");
    echo json_encode($xml);
} else {
    header("Content-Type:application/xml");
    echo $XML_txt;    // вывод в XML  формате
}
