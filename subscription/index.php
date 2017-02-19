<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous">

    </script>

</head>
<body>


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

$preparedSQL = ibase_prepare('
select ss.CONTACT_ID,  c.CONTACT_NAME, ss.SUBJECT_ID, s.SUBJECT_NAME
from SUBSCRIPTIONS ss
INNER JOIN CONTACTS c ON c.CONTACT_ID = ss.CONTACT_ID
INNER JOIN SUBJECTS s ON s.SUBJECT_ID = ss.SUBJECT_ID
order by s.SUBJECT_NAME, c.CONTACT_NAME');
try {
$sth = ibase_execute($preparedSQL);
?>

<div id="list-div">
    <table>
        <thead>
        <th>Тип рассылки</th>
        <th>Получатель</th>
        <th></th>
        </thead>
        <tbody>
        <? while ($row = ibase_fetch_object($sth)) : ?>
            <tr>
                <td><?= $row->SUBJECT_NAME; ?></td>
                <td><?= $row->CONTACT_NAME; ?></td>
                <td><a class="trash" data-subject_id="<?= $row->SUBJECT_ID; ?>" data-contact_id="<?= $row->CONTACT_ID; ?>">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a></td>
            </tr>
        <? endwhile; ?>
        <?
        } catch
        (ErrorException $e) {
            @file_put_contents('logfile.log', $e->getMessage() . "\r\n", FILE_APPEND | LOCK_EX);
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td><a href="add.php"><i class="fa fa-plus-circle" aria-hidden="true"></i></a></td>
        </tr>

        </tbody>
    </table>
</div>


<script>

    $('.trash').click(function () {
        var that = this;
        var contact = $(that).attr('data-contact_id');
        var subject = $(that).attr('data-subject_id');
        $.get('dropSubscription.php', {SUBJECT_ID: subject, CONTACT_ID: contact}, function (response) {
            if (response) {
                $(that).parents('tr').remove();
            }
        });
    });


</script>


</body>