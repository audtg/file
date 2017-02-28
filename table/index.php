<?php
$urlPrefix = 'http://file/table/';
$url = $urlPrefix . 'ws_table.php';

$context = stream_context_create();

$xml = new SimpleXMLElement(file_get_contents($url, false, $context), null, false);

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Тег INPUT, атрибут checked</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>


<div id="table-div">
    <table style="font-size: 12px; font-family: Verdana; border-collapse: collapse;">
        <thead>
        <tr>
            <th><a class="subject-th" style="text-decoration: none;">SUBJECT_NAME</a></th>
            <th><a class="contact-th" style="text-decoration: underline;">CONTACT_NAME</a></th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($xml->item as $item) : ?>
            <tr>
                <td class="subject"><?= (string)$item->SUBJECT_NAME; ?></td>
                <td class="contact"><?= (string)$item->CONTACT_NAME; ?></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
    <br>
    <button id="to-form-btn">To Form</button>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script>

    var dataArray = [];

    jQuery(function () {

        var subject = '';
        var contact = '';
        jQuery('tr').each(function (ind, item) {
            if (ind > 0) {
                subject = jQuery(item).find('.subject').html();
                contact = jQuery(item).find('.contact').html();
                dataArray.push([subject, contact]);
            }


        });
        console.log(dataArray);
    });

//    function sMatid(i, ii) { // По имени (возрастание)
//        return i[0] - ii[0];
//    }


    function sSubject(i, ii) { // По возрасту (возрастание)
        if (i[0] > ii[0])
            return 1;
        else if (i[0] < ii[0])
            return -1;
        else
            return 0;
    }

    function sContact(i, ii) { // По возрасту (возрастание)
        if (i[1] > ii[1])
            return 1;
        else if (i[1] < ii[1])
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
                );
        }
    }


    jQuery('.subject-th').click(function () {
        dataArray.sort(sSubject);
        recreateTbody();
        jQuery('.subject-th').css('text-decoration', 'none');
        jQuery('.contact-th').css('text-decoration', 'underline');
    });

    jQuery('.contact-th').click(function () {
        dataArray.sort(sContact);
        recreateTbody();
        jQuery('.subject-th').css('text-decoration', 'underline');
        jQuery('.contact-th').css('text-decoration', 'none');
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