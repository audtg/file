<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Тег INPUT, атрибут checked</title>
</head>
<body>

<div id="table-div">
    <table style="font-size: 12px; font-family: Verdana; border-collapse: collapse;">
        <caption>Таблица размеров обуви</caption>
        <tbody>
        <tr>
            <th>Россия</th>
            <th>Великобритания</th>
            <th>Европа</th>
            <th>Длина ступни, см</th>
            <th></th>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right;"><input class="check-all" type="checkbox"></td>
        </tr>
        <tr>
            <td>34,5</td>
            <td>3,5</td>
            <td>36</td>
            <td>23</td>
            <td><input class="check-one" type="checkbox"></td>
        </tr>
        <tr>
            <td>35,5</td>
            <td>4</td>
            <td>36⅔</td>
            <td>23–23,5</td>
            <td><input class="check-one" type="checkbox"></td>
        </tr>
        <tr>
            <td>36</td>
            <td>4,5</td>
            <td>37⅓</td>
            <td>23,5</td>
            <td><input class="check-one" type="checkbox"></td>
        </tr>
        <tr>
            <td>36,5</td>
            <td>5</td>
            <td>38</td>
            <td>24</td>
            <td><input class="check-one" type="checkbox"></td>
        </tr>
        <tr>
            <td>37</td>
            <td>5,5</td>
            <td>38⅔</td>
            <td>24,5</td>
            <td><input class="check-one" type="checkbox"></td>
        </tr>
        <tr>
            <td>38</td>
            <td>6</td>
            <td>39⅓</td>
            <td>25</td>
            <td><input class="check-one" type="checkbox"></td>
        </tr>
        <tr>
            <td>38,5</td>
            <td>6,5</td>
            <td>40</td>
            <td>25,5</td>
            <td><input class="check-one" type="checkbox"></td>
        </tr>
        <tr>
            <td>39</td>
            <td>7</td>
            <td>40⅔</td>
            <td>25,5–26</td>
            <td><input class="check-one" type="checkbox"></td>
        </tr>
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

    function clearForm(jqForm) {
        jqForm.find('input').each(function(ind, item) {
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

    jQuery('form').submit(function(event) {
        var postData =  jQuery(this).serialize();
        event.preventDefault();
        jQuery.post('fromForm.php', postData, function(response) {
            console.log(response);
        }, 'json');
    });


</script>

</body>
</html>