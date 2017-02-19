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

?>

<div id="add-div">
    <form action="addSubscription.php" method="post">
        <div class="bloc">
            <select size="" multiple name="subjects[]">
                <option disabled>Выберите героя</option>
                <option value="Чебурашка">Чебурашка</option>
                <option value="Крокодил Гена">Крокодил Гена</option>
                <option value="Шапокляк">Шапокляк</option>
                <option value="Крыса Лариса">Крыса Лариса</option>
            </select>
        </div>
        <div class="bloc">
            <select size="30" multiple name="contacts[]">
                <option disabled>Выберите героя</option>
                <option value="Чебурашка">Чебурашка</option>
                <option value="Крокодил Гена">Крокодил Гена</option>
                <option value="Шапокляк">Шапокляк</option>
                <option value="Крыса Лариса">Крыса Лариса</option>
            </select>
        </div>
        <p><input type="submit" value="Отправить"></p>
    </form>
</div>


<script>



</script>


</body>