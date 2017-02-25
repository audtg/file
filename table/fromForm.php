<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/table/myMailer.php';
$host = 'localhost:C:\Program Files\Firebird\Firebird_2_5\data\test.fdb';
$username = 'sysdba';
$password = 'masterkey';

function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // Этот код ошибки не входит в error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

$dbh = ibase_connect($host, $username, $password, 'utf-8');
file_put_contents('fromForm.log', print_r($_POST, true));
$email = new MyMailer();
$email->addAddress($_POST['email']);
$email->username = 'dtg';
$token = 'qq';
$email->href = 'http://file/table/accept.php?user=' . $username . '&token=' . $token;
$email->Body = $email->href;
//$email->date = date('d.m.y H:i:s', $tmstamp);
if ($email->sendMail()) {
    echo json_encode('Вам направлено письмо');
} else {
    echo json_encode('Не удалось отправить письмо');
}