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

$subscriptionSQL = ibase_prepare('
SELECT DISTINCT s.SUBJECT_ID, s.SUBJECT_NAME
FROM SUBSCRIPTIONS ss
  INNER JOIN CONTACTS c ON c.CONTACT_ID = ss.CONTACT_ID
  INNER JOIN CORR ON c.CORR_ID = corr.CORR_ID
  INNER JOIN SUBJECTS s ON s.SUBJECT_ID = ss.SUBJECT_ID
ORDER BY s.SUBJECT_NAME, corr.CORR_NAME, c.CONTACT_NAME');

$corrSQL = ibase_prepare('
SELECT DISTINCT co.CORR_ID, co.CORR_NAME
FROM SUBSCRIPTIONS ss
  INNER JOIN CONTACTS c ON c.CONTACT_ID = ss.CONTACT_ID
  INNER JOIN CORR co ON c.CORR_ID = co.CORR_ID
WHERE ss.SUBJECT_ID = ?
ORDER BY co.CORR_NAME
');

$contactSQL = ibase_prepare('
SELECT DISTINCT c.CONTACT_ID, c.CONTACT_NAME
FROM SUBSCRIPTIONS ss
  INNER JOIN CONTACTS c ON c.CONTACT_ID = ss.CONTACT_ID AND c.CORR_ID = ?
WHERE ss.SUBJECT_ID = ?
ORDER BY c.CONTACT_NAME
');

$notExistsSQL = ibase_prepare('
SELECT  sub.SUBJECT_ID, sub.SUBJECT_NAME FROM SUBJECTS as sub
where not exists(
    SELECT  s.SUBJECT_ID, s.SUBJECT_NAME
    FROM SUBSCRIPTIONS ss
      INNER JOIN CONTACTS c ON c.CONTACT_ID = ss.CONTACT_ID
      INNER JOIN CORR ON c.CORR_ID = corr.CORR_ID
      INNER JOIN SUBJECTS s ON s.SUBJECT_ID = ss.SUBJECT_ID
    WHERE ss.SUBJECT_ID = sub.SUBJECT_ID
)');

$allContactsSQL = ibase_prepare('
SELECT DISTINCT c.CONTACT_ID, c.CONTACT_NAME
FROM CONTACTS c
WHERE  c.CORR_ID = ?
ORDER BY c.CONTACT_NAME
');

$subjects = array();

$XML_txt = '<?xml version="1.0" encoding="utf-8"?' . '>';
$XML_txt .= '<data>';


$subscriptionSth = ibase_execute($subscriptionSQL);
while ($subscriptionRow = ibase_fetch_object($subscriptionSth)) {
    $subjects[] = $subscriptionRow->SUBJECT_ID;
    $XML_txt .= '<subscription>';
    $XML_txt .= '<SUBJECT_ID>' . $subscriptionRow->SUBJECT_ID . '</SUBJECT_ID>';
    $XML_txt .= '<SUBJECT_NAME>' . $subscriptionRow->SUBJECT_NAME . '</SUBJECT_NAME>';
    $corrSth = ibase_execute($corrSQL, $subscriptionRow->SUBJECT_ID);
    while ($corrRow = ibase_fetch_object($corrSth)) {
        $XML_txt .= '<corr>';
        $XML_txt .= '<CORR_ID>' . $corrRow->CORR_ID . '</CORR_ID>';
        $XML_txt .= '<CORR_NAME>' . $corrRow->CORR_NAME . '</CORR_NAME>';
        $contactSth = ibase_execute($contactSQL, $corrRow->CORR_ID, $subscriptionRow->SUBJECT_ID);
        $XML_txt .= '<contacts>';
        while ($contactRow = ibase_fetch_object($contactSth)) {
            $XML_txt .= '<contact>';
            $XML_txt .= '<CONTACT_ID>' . $contactRow->CONTACT_ID . '</CONTACT_ID>';
            $XML_txt .= '<CONTACT_NAME>' . $contactRow->CONTACT_NAME . '</CONTACT_NAME>';
            $XML_txt .= '</contact>';
        }
        $XML_txt .= '</contacts>';
        $allContactsSth = ibase_execute($allContactsSQL, $corrRow->CORR_ID);
        $XML_txt .= '<allcontacts>';
        while ($contactRow = ibase_fetch_object($allContactsSth)) {
            $XML_txt .= '<contact>';
            $XML_txt .= '<CONTACT_ID>' . $contactRow->CONTACT_ID . '</CONTACT_ID>';
            $XML_txt .= '<CONTACT_NAME>' . $contactRow->CONTACT_NAME . '</CONTACT_NAME>';
            $XML_txt .= '</contact>';
        }
        $XML_txt .= '</allcontacts>';
        $XML_txt .= '</corr>';
    }
    $XML_txt .= '</subscription>';
}

$subjectsCond = implode(', ', $subjects);

$subjectSQL =  ibase_prepare('
SELECT SUBJECT_ID, SUBJECT_NAME
  FROM SUBJECTS
WHERE SUBJECT_ID NOT IN ('.$subjectsCond.')
ORDER BY SUBJECT_NAME
');

$subjectSth = ibase_execute($subjectSQL);
while ($subjectRow = ibase_fetch_object($subjectSth)) {
    $XML_txt .= '<subject>';
    $XML_txt .= '<SUBJECT_ID>' . $subjectRow->SUBJECT_ID . '</SUBJECT_ID>';
    $XML_txt .= '<SUBJECT_NAME>' . $subjectRow->SUBJECT_NAME . '</SUBJECT_NAME>';
    $XML_txt .= '</subject>';
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