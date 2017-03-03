<?php
$urlPrefix = 'http://file/table/';
$url = $urlPrefix . 'ws_table.php';

$context = stream_context_create();

$xml = new SimpleXMLElement(file_get_contents($url, false, $context), null, false);

$currSubject = 0;
$currCorr = 0;

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Тег INPUT, атрибут checked</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>


<!--<form id="my-form">-->
<!--    <input type="email" name="email"><br>-->
<!--<!--    <input type="submit"><br>-->
<!---->
<!--    <a id="my-a" type="submit"><i class="fa fa-check-circle" aria-hidden="true"></i></a>-->
<!--    <span class="format-error-p" style="display: none;">Неверный формат.</span>-->
<!--</form>-->

<table style="border: solid 1px black;">
    <? foreach ($xml->subscription as $subscription) : ?>
        <tr>
            <td style="width: 30%;">
                <h5><?= (integer)$subscription->SUBJECT_ID; ?></h5>
                <p><?= (string)$subscription->SUBJECT_NAME; ?></p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut delectus illo ipsa qui tempora. Adipisci architecto aspernatur consequatur dignissimos, eaque est laboriosam, magnam possimus quae quas rem sequi soluta voluptates?
                </td>
            <td>
                <table style="border: solid 1px black;">
                    <? foreach ($xml->subscription->corr as $corr) : ?>
                        <tr>
<!--                            <td style="width: 30%;"></td>-->
                            <td style="width: 30%;">
                                <h6><?= (string)$corr->CORR_NAME; ?></h6>
                            </td>
                            <td style="width: 40%;">
                                <table>
                                    <? foreach ($xml->subscription->corr->contact as $contact) : ?>
                                        <tr>
                                            <td><?= (string)$contact->CONTACT_NAME; ?></td>
                                            <td><a class="cansel-a"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    <? endforeach; ?>
                                    <tr>
                                        <td colspan="2">
                                        <a class="add-a"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    <tr><td colspan="2"><a class="add-a"><i class="fa fa-plus-circle" aria-hidden="true"></i></a></td></tr>

                </table>
            </td>

        </tr>

    <? endforeach; ?>
    <tr><td colspan="2"><a class="add-a"><i class="fa fa-plus-circle" aria-hidden="true"></i></a></td></tr>

</table>


<!--<div id="table-div">-->
<!--    <table style="font-size: 12px; font-family: Verdana; border-collapse: collapse;">-->
<!--        <thead>-->
<!--        <tr>-->
<!--            <th><a class="subject-th" style="text-decoration: none;">SUBJECT_NAME</a></th>-->
<!--            <th><a class="contact-th" style="text-decoration: underline;">CONTACT_NAME</a></th>-->
<!--        </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        --><? // foreach ($xml->item as $item) : ?>
<!--            <tr>-->
<!--                <td class="subject">--><? //= (string)$item->SUBJECT_NAME; ?><!--</td>-->
<!--                <td><div class="contact">-->
<!--                        --><? //= (string)$item->CONTACT_NAME; ?><!--<br>-->
<!--                    </div>-->
<!--                    <a class="add-a"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>-->
<!--                    <form style="display: none;">-->
<!--                        <input type="email" name="email">-->
<!--                        <a class="submit-a" type="submit"><i class="fa fa-check" aria-hidden="true"></i></a>-->
<!--                        <a class="cansel-a"><i class="fa fa-times" aria-hidden="true"></i></a>-->
<!--                    </form>-->
<!---->
<!--                </td>-->
<!--            </tr>-->
<!--        --><? // endforeach; ?>
<!--        </tbody>-->
<!--    </table>-->
<!--    <br>-->
<!--    <button id="to-form-btn">To Form</button>-->
<!--</div>-->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script>

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    jQuery('input[name="email"]').keydown(function (event) {
        if (event.keyCode === 13) {
            jQuery(this).parents('form').find('.submit-a').click();
        }
        if (event.keyCode === 27) {
            jQuery(this).parents('form').find('.cansel-a').click();
        }
    });

    jQuery('.add-a').click(function () {
        jQuery(this).css({display: 'none'});
        jQuery(this).parents('td').find('form').css({display: 'block'});

    });

    jQuery('.submit-a').click(function () {
        jQuery(this).parents('form').submit();

    });

    jQuery('.cansel-a').click(function () {
        console.log('cansel');
        jQuery(this).parents('td').find('form').css({display: 'none'});
        jQuery(this).parents('td').find('.add-a').css({display: 'block'});

    });

    jQuery('input[name="email"]').mousedown(function (event) {
        jQuery(this).css({color: "black"});
    });

    jQuery('form').submit(function (event) {
        console.log('this is submit');
        var data = jQuery(this).serialize();
        var email = jQuery(this).find('input[name="email"]').val();
        console.log(data);
        console.log(email);
        console.log(validateEmail(email));
        if (validateEmail(email)) {
            jQuery(this).find('input[name="email"]').val('');
            var jCont = jQuery(this).parents('tr').find('.contact');
            var text = jCont.html() + email + '<br>';
            jCont.html(text);
            jQuery(this).css({display: 'none'});
            jQuery(this).parents('td').find('.add-a').css({display: 'block'});
        } else {
            jQuery(this).find('input[name="email"]').css({color: "red"});
        }
    });


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
//        console.log(dataArray);
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