/* 
 * EasyTransac oneclick.js
 */

jQuery(function($) {
    // return;
    // Double load failsafe.
    var session_id = 'easytransac-oneclick' + Date.now();

    // Creates workspace.
    $('div.payment_method_easytransac').before($('<div id="' + session_id + '" class="payment_box payment_method_easytransac">'));

    // Unified OneClick loader
    // Requires : listcards_url
    //            oneclick_url
    //
    var listcards_url = '?wc-api=easytransac&listcards=1';
    var oneclick_url = '?wc-api=easytransac&oneclick=1';

    
    let loaderText = $('<span id="etocloa001">OneClick loading ...</span>');
    if (typeof(loadingMsg) !==  'undefined') {
        // loaderText[0].innerHTML = loadingMsg;
    }
    $('#' + session_id).append(loaderText);

    // JSON Call
    $.getJSON(listcards_url, {}, buildFromJson);

    // Build  OneClick form from JSON.
    function buildFromJson(json) {

        $('#etocloa001').fadeOut().remove();

        if (!json.status || json.packet.length === 0) {

            // No cards available.
            $('#' + session_id).remove();
            return;
        }

        // Namespace
        $('#' + session_id).append($('<p>'))
        var _space = $('#' + session_id + ' > p');

        // Label
        var label = $('<span>');
        if (typeof(chooseCard) !==  'undefined') {
            label[0].innerHTML = chooseCard;
        }
        _space.append(label);

        // Dropdown
        _space.append($('<select id="etalcadd001" name="oneclick_alias" style="padding: 5px;">'));
        $('#etalcadd001')
            .append($('<option value="0"> - </option>'));
        $.each(json.packet, function(i, row) {
            $('#etalcadd001')
                .append($('<option value="' + row.Alias + '">' + row.CardNumber + ' ' + row.Month + '/' + row.Year + '</option>'));
        });

        let addButton = () => {
            // Button
            var button = $(' <input type="submit" id="etocbu001" class="button alt" style="margin-top: 15px; display: block;" value="OneClick Pay">');
            if (typeof(payNow) !== 'undefined') {
                button[0].value = payNow; 
            }
            _space.append(button);

            // Button click/*
            $('#etocbu001').click(function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                _space.append($('<input type="hidden" class="etocflag001" name="is_oneclick" value="1">'));
                $(this).submit();
                $('.etocflag001').remove();
            });
        };

        let removeBtn = () => {
            $('#etocbu001').remove();
        };

        $('#etalcadd001').on('change', function() {
            if(this.value == 0){
                console.log('DEBUG no credit card selected');
                $('#etocbu001').fadeOut();
                removeBtn();
            } else {
                addButton();
                $('#etocbu001').fadeIn();
            }
        });


    }
});