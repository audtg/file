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

$subscriptionSQL = ibase_prepare('
select ss.CONTACT_ID,  c.CONTACT_NAME, ss.SUBJECT_ID, s.SUBJECT_NAME
from SUBSCRIPTIONS ss
INNER JOIN CONTACTS c ON c.CONTACT_ID = ss.CONTACT_ID
INNER JOIN SUBJECTS s ON s.SUBJECT_ID = ss.SUBJECT_ID
order by s.SUBJECT_NAME, c.CONTACT_NAME');

$subjectSQL = ibase_prepare('
select s.SUBJECT_ID, s.SUBJECT_NAME
from SUBJECTS s
order by s.SUBJECT_NAME');

$contactSQL = ibase_prepare('
select c.CONTACT_ID, c.CONTACT_NAME
from CONTACTS c
order by c.CONTACT_NAME');

$XML_txt = '<?xml version="1.0" encoding="utf-8"?' . '>';
$XML_txt .= '<data>';

try {
    $subscriptionSth = ibase_execute($subscriptionSQL);
    while ($subscriptionRow = ibase_fetch_object($subscriptionSth)) {
        $XML_txt .= '<subscription>';
        $XML_txt .= '<SUBJECT_ID>' . $subscriptionRow->SUBJECT_ID . '</SUBJECT_ID>';
        $XML_txt .= '<SUBJECT_NAME>' . $subscriptionRow->SUBJECT_NAME . '</SUBJECT_NAME>';
        $XML_txt .= '<CONTACT_ID>' . $subscriptionRow->CONTACT_ID . '</CONTACT_ID>';
        $XML_txt .= '<CONTACT_NAME>' . $subscriptionRow->CONTACT_NAME . '</CONTACT_NAME>';
        $XML_txt .= '</subscription>';
    }
    $subjectSth = ibase_execute($subjectSQL);
    $subjectCount = 0;
    while ($subjectRow = ibase_fetch_object($subjectSth)) {
        $XML_txt .= '<subject>';
        $XML_txt .= '<SUBJECT_ID>' . $subjectRow->SUBJECT_ID . '</SUBJECT_ID>';
        $XML_txt .= '<SUBJECT_NAME>' . $subjectRow->SUBJECT_NAME . '</SUBJECT_NAME>';
        $XML_txt .= '</subject>';
        $subjectCount++;
    }
    $XML_txt .= '<SUBJECT_COUNT>' . $subjectCount . '</SUBJECT_COUNT>';

    $contactSth = ibase_execute($contactSQL);
    $contactCount = 0;
    while ($contactRow = ibase_fetch_object($contactSth)) {
        $XML_txt .= '<contact>';
        $XML_txt .= '<CONTACT_ID>' . $contactRow->CONTACT_ID . '</CONTACT_ID>';
        $XML_txt .= '<CONTACT_NAME>' . $contactRow->CONTACT_NAME . '</CONTACT_NAME>';
        $XML_txt .= '</contact>';
        $contactCount++;
    }
    $XML_txt .= '<CONTACT_COUNT>' . $contactCount . '</CONTACT_COUNT>';

    $XML_txt .= '</data>';
} catch (ErrorException $e) {
    @file_put_contents('logfile.log', $e->getMessage() . "\r\n", FILE_APPEND);
}

if ($_GET['json'] == 1) { // вывод в формате JSON
    $xml = simplexml_load_string($XML_txt);
    header("Content-Type:application/json");
    echo json_encode($xml);
} else {
    header("Content-Type:application/xml");
    echo $XML_txt;    // вывод в XML  формате
}