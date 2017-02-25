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

$preparedSQL = ibase_prepare('INSERT INTO SUBSCRIPTIONS VALUES (?, ?, 0, NULL)');

foreach ($_POST['subjects'] as $subject){
    foreach ($_POST['contacts'] as $contact){
        ibase_execute($preparedSQL, $subject, $contact);
    }
}

$XML_txt = '<?xml version="1.0" encoding="utf-8"?' . '>';
$XML_txt .= '<data>';
$XML_txt .= '</data>';

if ($_GET['json'] == 1) { // вывод в формате JSON
    $xml = simplexml_load_string($XML_txt);
    header("Content-Type:application/json");
    echo json_encode($xml);
} else {
    header("Content-Type:application/xml");
    echo $XML_txt;    // вывод в XML  формате
}

