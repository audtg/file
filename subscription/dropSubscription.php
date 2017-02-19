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

$urlPrefix = 'http://file/subscription/';
$url = $urlPrefix . 'ws-dropSubscription.php?SUBJECT_ID='.$_GET['SUBJECT_ID'].'&CONTACT_ID='.$_GET['CONTACT_ID'];

$context = stream_context_create();

$xml = new SimpleXMLElement(file_get_contents($url, false, $context), null, false);

echo (boolean)$xml->result;
