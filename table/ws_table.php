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

$preparedSQL = ibase_prepare('
SELECT s.SUBJECT_ID, s.SUBJECT_NAME, c.CONTACT_ID, c.CONTACT_NAME
FROM SUBSCRIPTIONS ss
INNER JOIN CONTACTS c ON c.CONTACT_ID = ss.CONTACT_ID
INNER JOIN SUBJECTS s ON s.SUBJECT_ID = ss.SUBJECT_ID
ORDER BY s.SUBJECT_NAME, c.CONTACT_NAME');

$XML_txt = '<?xml version="1.0" encoding="utf-8"?' . '>';
$XML_txt .= '<data>';

try {
    $sth = ibase_execute($preparedSQL);
} catch (ErrorException $e) {
    @file_put_contents('logfile.log', $e->getMessage() . "\r\n", FILE_APPEND | LOCK_EX);
}


while ($row = ibase_fetch_object($sth)) {
    $XML_txt .= '<item>';
    $XML_txt .= '<SUBJECT_ID>' . $row->SUBJECT_ID . '</SUBJECT_ID>';
    $XML_txt .= '<SUBJECT_NAME>' . $row->SUBJECT_NAME . '</SUBJECT_NAME>';
    $XML_txt .= '<CONTACT_ID>' . $row->CONTACT_ID . '</CONTACT_ID>';
    $XML_txt .= '<CONTACT_NAME>' . $row->CONTACT_NAME . '</CONTACT_NAME>';
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