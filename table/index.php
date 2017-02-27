<?php
$urlPrefix = 'http://file/table/';
$url = $urlPrefix . 'ws_get_table.php';

$context = stream_context_create();

$xml = new SimpleXMLElement(file_get_contents($url, false, $context), null, false);

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Тег INPUT, атрибут checked</title>
</head>
<body>


<div id="table-div">
    <table style="font-size: 12px; font-family: Verdana; border-collapse: collapse;">
        <tr>
            <th><a class="matid">MAT_ID</a></th>
            <th><a class="model">MODEL</a></th>
            <th><a class="name">NAME</a></th>
        </tr>
        <tbody>
        <? foreach ($xml->item as $item) : ?>
            <tr>
                <td><?= (integer)$item->MAT_ID; ?></td>
                <td><?= (string)$item->MODEL; ?></td>
                <td><?= (string)$item->NAME; ?></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
    <br>
    <button id="to-form-btn">To Form</button>
</div>

<div id="form-div" style="display: none;">
    <form>
        <p><b>Как по вашему мнению расшифровывается аббревиатура &quot;ОС&quot;?</b></p>
        <p><input type="radio" name="answer" value="a1">Офицерский состав<Br>
            <input type="radio" name="answer" value="a2">Операционная система<Br>
            <input type="radio" name="answer" value="a3">Большой полосатый мух</p>
        <input type="checkbox" name="items[]" value="A"><br>
        <input type="checkbox" name="items[]" value="B"><br>
        <input type="checkbox" name="items[]" value="C"><br>
        <input type="checkbox" name="items[]" value="D"><br><br>
        <input type="checkbox" class="check-all"><br>
        <input type="checkbox" class="check-one"><br>
        <input type="checkbox" class="check-one"><br>
        <input type="checkbox" class="check-one"><br>
        <input type="checkbox" class="check-one"><br>
        <label for="phone-input">Phone</label>
        <input type="text" name="phone" required><br><br>
        <label for="email-input" value="tagedo@yandex.ru">Email</label>
        <input type="email" name="email"><br><br>
        <button type="submit">Submit</button>
    </form>
    <br>
    <button id="to-table-btn">To Table</button>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script>

    var dataArray = [];

    jQuery(function () {

        var matid = '';
        var model = '';
        var name = '';
        jQuery('tr').each(function (ind, item) {
            if (ind > 0) {
                matid = jQuery(item).find('.matid').html();
                model = jQuery(item).find('.model').html();
                name = jQuery(item).find('.name').html();
                dataArray.push([matid, model, name]);
            }


        });
        console.log(dataArray);
    });

    function sMatid(i, ii) { // По имени (возрастание)
        if (i[0] > ii[0])
            return 1;
        else if (i[0] < ii[0])
            return -1;
        else
            return 0;
    }


    function sModel(i, ii) { // По возрасту (возрастание)
        if (i[1] > ii[1])
            return 1;
        else if (i[1] < ii[1])
            return -1;
        else
            return 0;
    }

    function sName(i, ii) { // По возрасту (возрастание)
        if (i[2] > ii[2])
            return 1;
        else if (i[2] < ii[2])
            return -1;
        else
            return 0;
    }


    jQuery('.matid').click(function () {

        console.log('matid');
        dataArray.sort(sMatid);
//        console.log(dataArray);
        jQuery('table').empty();
        jQuery('table').append(jQuery('<tr><th><a class="matid">MAT_ID</a></th><th><a class="model">MODEL</a></th><th><a class="name">NAME</a></th></tr>'));
        for (var i = 0; i < dataArray.length; i++) {
            console.log(dataArray[i][0]);
            console.log(dataArray[i][1]);
            console.log(dataArray[i][2]);
//            jQuery('table').append(jQuery('<tr><th><a id="matid">MAT_ID</a></th><th><a id="model">MODEL</a></th><th><a id="name">NAME</a></th></tr>'));
            jQuery('table')
                .append(jQuery('<tr>')
                    .append(jQuery('<td>').html(dataArray[i][0]))
                    .append(jQuery('<td>').html(dataArray[i][1]))
                    .append(jQuery('<td>').html(dataArray[i][2]))
                );
        }
    });

    jQuery('.model').click(function () {

        console.log('model');
        dataArray.sort(sModel);
//        console.log(dataArray);
        jQuery('table').empty();
        jQuery('table').append(jQuery('<tr><th><a class="matid">MAT_ID</a></th><th><a class="model">MODEL</a></th><th><a class="name">NAME</a></th></tr>'));
        for (var i = 0; i < dataArray.length; i++) {
            console.log(dataArray[i][0]);
            console.log(dataArray[i][1]);
            console.log(dataArray[i][2]);
//            jQuery('table').append(jQuery('<tr><th><a id="matid">MAT_ID</a></th><th><a id="model">MODEL</a></th><th><a id="name">NAME</a></th></tr>'));
            jQuery('table')
                .append(jQuery('<tr>')
                    .append(jQuery('<td>').html(dataArray[i][0]))
                    .append(jQuery('<td>').html(dataArray[i][1]))
                    .append(jQuery('<td>').html(dataArray[i][2]))
                );
        }
    });

    jQuery('.name').click(function () {

        console.log('name');
        dataArray.sort(sName);
//        console.log(dataArray);
        jQuery('table').empty();
        jQuery('table').append(jQuery('<tr><th><a class="matid">MAT_ID</a></th><th><a class="model">MODEL</a></th><th><a class="name">NAME</a></th></tr>'));
        for (var i = 0; i < dataArray.length; i++) {
            console.log(dataArray[i][0]);
            console.log(dataArray[i][1]);
            console.log(dataArray[i][2]);
//            jQuery('table').append(jQuery('<tr><th><a id="matid">MAT_ID</a></th><th><a id="model">MODEL</a></th><th><a id="name">NAME</a></th></tr>'));
            jQuery('table')
                .append(jQuery('<tr>')
                    .append(jQuery('<td>').html(dataArray[i][0]))
                    .append(jQuery('<td>').html(dataArray[i][1]))
                    .append(jQuery('<td>').html(dataArray[i][2]))
                );
        }
    });

    function clearForm(jqForm) {
        jqForm.find('input').each(function (ind, item) {
            switch (jQuery(item).attr('type')) {
                case 'checkbox':
                case 'radio':
                    jQuery(item).prop('checked', false);
                    break;
                default:
                    jQuery(item).val('');
                    break;

            }
        });
    }

    jQuery('.check-all').click(function () {
        jQuery(this).parentsUntil('div').find('.check-one').prop('checked', jQuery(this).prop('checked'));
    });

    jQuery('#to-form-btn').click(function () {
        clearForm(jQuery('form'));
        jQuery('#table-div').css({display: 'none'});
        jQuery('#form-div').css({display: 'block'});
    });

    jQuery('#to-table-btn').click(function () {
        clearForm(jQuery('table'));
        jQuery('#table-div').css({display: 'block'});
        jQuery('#form-div').css({display: 'none'});
    });

    jQuery('form').submit(function (event) {
        var postData = jQuery(this).serialize();
        event.preventDefault();
        jQuery.post('fromForm.php', postData, function (response) {
            console.log(response);
        }, 'json');
    });


</script>

</body>
</html>