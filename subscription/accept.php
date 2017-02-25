<?php
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
file_put_contents('fromMail.log', print_r($_GET, true));
try {
    $selectSQL = ibase_prepare('SELECT * FROM SUBSCRIPTIONS WHERE TOKEN = ?');
    $updateSQL = ibase_prepare('UPDATE SUBSCRIPTIONS SET ACCEPT = 1, TOKEN = NULL WHERE TOKEN = ?');
    try{
        $selectSth = ibase_execute($selectSQL, $_GET['data']);
        if ($row = ibase_fetch_object($selectSth)) {
            ibase_execute($updateSQL, $_GET['data']);
            $body = 'Подписка подтверждена.';
        } else {
            $body = 'Не найдены данные для подтверждения подписки.';
        }
    }catch (ErrorException $e) {
        file_put_contents('error.log', $e->getMessage());
    }
} catch (ErrorException $e) {
    file_put_contents('error.log', $e->getMessage());
}


?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<p>
<?= $body; ?>
</p>
</body>
</html>