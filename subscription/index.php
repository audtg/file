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

    <style>
        .bloc {
            display: inline-block;
            vertical-align: top;
            overflow: hidden;
            border: solid grey 1px;

        }

        .bloc select {
            padding: 10px;
            margin: -5px -20px -5px -5px;
            width: 400px;
        }
    </style>

</head>
<body>


<?php

function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler("exception_error_handler");

$urlPrefix = 'http://file/subscription/';
$url = $urlPrefix . 'ws-getSubscriptionList.php';

$context = stream_context_create();

$xml = new SimpleXMLElement(file_get_contents($url, false, $context), null, false);

?>

<div id="list-div">
    <table>
        <thead>
        <th>Тип рассылки</th>
        <th>Получатель</th>
        <th></th>
        </thead>
        <tbody>
        <? foreach($xml->subscription as $subscription) : ?>
            <tr>
                <td><?= (string)$subscription->SUBJECT_NAME; ?></td>
                <td><?= (string)$subscription->CONTACT_NAME; ?></td>
                <td><a class="trash" data-subject_id="<?= $subscription->SUBJECT_ID; ?>" data-contact_id="<?= $subscription->CONTACT_ID; ?>">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a></td>
            </tr>
        <? endforeach; ?>

        <tr>
            <td></td>
            <td></td>
            <td><a id="add-a"><i class="fa fa-plus-circle" aria-hidden="true"></i></a></td>
        </tr>

        </tbody>
    </table>
</div>


<div id="add-div" style="display: none;">
    <form action="addSubscription.php" method="post">
        <div class="bloc">
            <select size="<?= (int)$xml->SUBJECT_COUNT + 1; ?>" multiple name="subjects[]">
                <option disabled>Выберите типы рассылок</option>
                <? foreach ($xml->subject as $subject) : ?>
                <option value="<?= (int)$subject->SUBJECT_ID; ?>"><?= (string)$subject->SUBJECT_NAME; ?></option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="bloc" style="margin-left: 30px;">
            <select size="<?= (int)$xml->CONTACT_COUNT + 1; ?>" multiple name="contacts[]">
                <option disabled>Выберите получателей</option>
                <? foreach ($xml->contact as $contact) : ?>
                    <option value="<?= (int)$contact->CONTACT_ID; ?>"><?= (string)$contact->CONTACT_NAME; ?></option>
                <? endforeach; ?>
            </select>
        </div>
        <p><input type="submit" value="Сохранить"></p>
    </form>
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


    $('#add-a').click(function () {
        $('#list-div').css({display: 'none'});
        $('#add-div').css({display: 'block'});
    });


</script>


</body>