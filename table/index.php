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
        <thead>
        <tr>
            <th><a class="matid-th">MAT_ID</a></th>
            <th><a class="model-th">MODEL</a></th>
            <th><a class="name-th">NAME</a></th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($xml->item as $item) : ?>
            <tr>
                <td class="matid"><?= (integer)$item->MAT_ID; ?></td>
                <td class="model"><?= (string)$item->MODEL; ?></td>
                <td class="name"><?= (string)$item->NAME; ?></td>
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
        return i[0] - ii[0];
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


    function recreateTbody() {
        jQuery('tbody').empty();
        for (var i = 0; i < dataArray.length; i++) {
            jQuery('tbody')
                .append(jQuery('<tr>')
                    .append(jQuery('<td>').html(dataArray[i][0]))
                    .append(jQuery('<td>').html(dataArray[i][1]))
                    .append(jQuery('<td>').html(dataArray[i][2]))
                );
        }
    }


    jQuery('.matid-th').click(function () {
        dataArray.sort(sMatid);
        recreateTbody();
    });

    jQuery('.model-th').click(function () {
        dataArray.sort(sModel);
        recreateTbody();
    });

    jQuery('.name-th').click(function () {
        dataArray.sort(sName);
        recreateTbody();
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