<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/subscription/myMailer.php';
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

try {
    $preparedSQL = ibase_prepare('UPDATE SUBSCRIPTIONS SET TOKEN = ? WHERE SUBJECT_ID = ? AND CONTACT_ID = ?');
} catch (ErrorException $e) {
    file_put_contents('error.log', $e->getMessage());
}


foreach ($_POST['subscriptions'] as $item) {
    $post = explode('|', $item);
    $token = md5(uniqid(rand(), true));
    try {
        ibase_execute($preparedSQL, $token, $post[0], $post[1]);
//        ibase_query('commit');
    } catch (ErrorException $e) {
        file_put_contents('error.log', $e->getMessage());
    }

    $email = new MyMailer();
//    $email->addAddress($post[2]);
    $email->Subject = $post[2];
    $email->addAddress('tagedo@yandex.ru');
    $email->href = 'http://file/subscription/accept.php?data=' . $token;
    $email->msgHTML('<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <h4>Здравствуйте, ' .$post[2] . '!</h4>
    <h5>Ваша ссылка для подтверждения подписки: ' .$email->href . '</h5>
</body>
</html>');
//    $email->Body = $email->href;
    if ($email->sendMail()) {
        echo 1;
    } else {
        echo 0;
        file_put_contents('error-mail.log', $email->ErrorInfo."\r\n", FILE_APPEND);
//        echo $email->ErrorInfo;
    }
}


