<?php
$host = 'localhost:C:\Program Files\Firebird\Firebird_2_5\data\test.fdb';
$username = 'sysdba';
$password = 'masterkey';

function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        // Этот код ошибки не входит в error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler("exception_error_handler");

$dbh = ibase_connect($host, $username, $password, 'utf-8');

$preparedSQL = ibase_prepare('SELECT MAT_ID, MODEL, NAME FROM TEST ORDER BY MAT_ID');

$XML_txt = '<?xml version="1.0" encoding="utf-8"?' . '>';
$XML_txt .= '<data>';

try {
    $sth = ibase_execute($preparedSQL);
} catch (ErrorException $e) {
    @file_put_contents('logfile.log', $e->getMessage() . "\r\n", FILE_APPEND | LOCK_EX);
}


while ($row = ibase_fetch_object($sth)) {
    $XML_txt .= '<item>';
    $XML_txt .= '<MAT_ID>' . $row->MAT_ID . '</MAT_ID>';
    $XML_txt .= '<MODEL>' . $row->MODEL . '</MODEL>';
    $XML_txt .= '<NAME>' . $row->NAME . '</NAME>';
    $XML_txt .= '</item>';
}


$XML_txt .= '</data>';

if ($_GET['json'] == 1) { // вывод в формате JSON
    $xml = simplexml_load_string($XML_txt);
    header("Content-Type:application/json");
    echo json_encode($xml);
} else {
    header("Content-Type:application/xml");
    echo $XML_txt;    // вывод в XML  формате
}