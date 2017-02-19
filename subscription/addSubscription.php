<?php

function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler("exception_error_handler");

//file_put_contents('post.log', print_r($_POST, true));

$data = http_build_query($_POST);

$urlPrefix = 'http://file/subscription/';
$url = $urlPrefix . 'ws-addSubscription.php';

$opts = array(
'http' => array(
'method' => "POST",
'header' => "Content-type: application/x-www-form-urlencoded\r\n"
//            . "Cookie: CRMSESSID=" . $_SESSION["SESS_AUTH"]['crmsession'] . "\r\n"
. "Content-Length: " . strlen($data) . "\r\n",
'content' => $data
)
);

$context = stream_context_create($opts);

$xml = new SimpleXMLElement(file_get_contents($url, false, $context), null, false);

header('Location: index.php');

//$result = array();
//
//foreach ($xml->name as $name) {
//$result[] = (string) $name;
//}

//var_dump($xml);