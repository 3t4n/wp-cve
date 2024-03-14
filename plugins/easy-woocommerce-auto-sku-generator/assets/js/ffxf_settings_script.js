var inputs = document.querySelectorAll("input[name='skuautoffxf_letters_and_numbers']");

function FFxFparent() {
    var FFxFparent = inputs[3].parentElement.parentElement;
    var FFxF_el = document.createElement("div");
    FFxF_el.className = "FFxF_icon_setting";
    FFxF_el.innerHTML = '<div id="sku_description" class="updated inline sku_description" style=" max-width: 340px; "><p><strong>' + ffxf_settings_locale.ffxf_message + '</strong></p></div>';
    var element = document.getElementById("sku_description");
    if (!element) {
        FFxFparent.append(FFxF_el);
    }
}

if (inputs[3].checked) {
    FFxFparent();
}

for (var i = 0; i < inputs.length; ++i) {
    inputs[i].addEventListener('change', function () {
        if (this.value == 'ffxf_slug') {
            FFxFparent();
        } else {
            if (document.getElementById("sku_description")) {
                document.getElementById("sku_description").remove();
            }
        }

    });
}


function ffxf_message_preiv() {
    var chbox = document.getElementById('skuautoffxf_previous');
    if (chbox.checked) {

        var FFxFparent_preiv = document.querySelector('#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(5) > td > fieldset');
        var FFxF_el_preiv = document.createElement("div");
        FFxF_el_preiv.className = "FFxF_icon_setting_preiv";
        FFxF_el_preiv.innerHTML = '<div id="sku_description_preiv" class="updated inline sku_description" style=" max-width: 340px; z-index:999; top:0; left:0;"><p><strong>' + ffxf_settings_locale.ffxf_message_preiv + '</strong></p></div>';
        var element_preiv = document.getElementById("sku_description_preiv");

        if (!element_preiv) {
            FFxFparent_preiv.append(FFxF_el_preiv);
        }

        var FFxF_style = document.createElement('style');
        FFxF_style.setAttribute('id', 'FFxF_style_preiv');
        FFxF_style.typeof = 'text/css';
        FFxF_style.innerHTML = '.mass_generate { background: rgba(134, 134, 134, 0.3); cursor: no-drop; color: #9a9a9a; } #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(1), #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(2), #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(3),#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(4) {z-index:9;background: rgba(134, 134, 134, 0.3); cursor: no-drop;color: #9a9a9a;}#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(1) label, #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(2) label, #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(3) label, #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(4) label{cursor: no-drop;color: #9a9a9a;}#mainform > div > div:nth-child(3) > table > tbody > tr:nth-child(1) input, #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(2) input, #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(3) input, #mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(4) input{cursor: no-drop;color: #9a9a9a;}table.form-table { z-index: 99; }';
        document.getElementsByTagName('head')[0].appendChild(FFxF_style);
        document.getElementById('generate_mass_sku').setAttribute('disabled', 'disabled');
        document.getElementById('generate_mass_sku_category').setAttribute('disabled', 'disabled');


    } else {
        if (document.getElementById("sku_description_preiv")) {
            document.getElementById("sku_description_preiv").remove();
        }
        if (document.getElementById("FFxF_style_preiv")) {
            document.getElementById("FFxF_style_preiv").remove();
        }

        document.querySelector('#skuautoffxf_auto_prefix').removeAttribute("align");
        document.getElementById('generate_mass_sku').removeAttribute('disabled');
        document.getElementById('generate_mass_sku_category').removeAttribute('disabled');
    }
}


var FFxF_button_priev_post = document.querySelector('label[for="skuautoffxf_previous"]');
FFxF_button_priev_post.addEventListener("change", function () {
    ffxf_message_preiv();

});


window.onload = function () {
    ffxf_message_preiv();
};

(function ($) {
    var $modal_generate = $('.modal_generate');
    var $overlay = $('.ffxf-modal-overlay');
    var $modal_generate_category = $('.modal_generate_category');
    var select_cat_total = $('.modal_generate_category .ps').text();

    $modal_generate.bind('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e){
        if ($modal_generate.hasClass('state-leave')) {
            $modal_generate.removeClass('state-leave');
        }
    });

    $('.close').on('click', function(){
        $overlay.removeClass('state-show');
        $modal_generate.removeClass('state-appear').addClass('state-leave');
    });

    $('.open').on('click', function(){
        $overlay.addClass('state-show');
        $modal_generate.removeClass('state-leave').addClass('state-appear');
    });

    $modal_generate_category.bind('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e){
        if ($modal_generate_category.hasClass('state-leave')) {
            $modal_generate_category.removeClass('state-leave');
        }
    });

    $('.close_category').on('click', function(){
        $overlay.removeClass('state-show');
        $modal_generate_category.removeClass('state-appear').addClass('state-leave');
    });

    $('.open_category').on('click', function(){
        $overlay.addClass('state-show');
        $modal_generate_category.removeClass('state-leave').addClass('state-appear');
    });

    function ifselect(){
        var num_category_total = parseInt(select_cat_total.replace(/\D+/g,""));
        if ($('#product_cat > option:selected').val() === ''){
            $('.generate_button_category').attr('disabled','disabled').addClass('disabled');
            $('.modal_generate_category .ps').html(ffxf_settings_locale.ffxf_text_category_4 + ' ' + num_category_total + ' ' + ffxf_settings_locale.ffxf_text_category_5 + ' ' + ffxf_settings_locale.ffxf_text_category_6);
        } else {
            var select_cat = $('#product_cat option:selected').text();
            $('.generate_button_category').removeAttr('disabled','disabled').removeClass('disabled');
            var num_category = parseInt(select_cat.replace(/\D+/g,""));
            var text_category = select_cat.replace(/[^A-z]/g, '');
            $('.modal_generate_category .ps').html(ffxf_settings_locale.ffxf_text_category_1 + ' <b>' + text_category + '</b> ' + ffxf_settings_locale.ffxf_text_category_2 + ' <b><span id="num_category">' + num_category + '</span></b> ' + ffxf_settings_locale.ffxf_text_category_3);
        }
    }

    ifselect();

    $('#product_cat').on('change', function () {
        ifselect();
    });

    function toggleVariationSettings() {
        if ($("#skuautoffxf_variation_settings").is(":checked")) {
            $("#skuautoffxf_variation_separator").closest("tr").show();
            $("#skuautoffxf_auto_variant").closest("tr").show();
        } else {
            $("#skuautoffxf_variation_separator").closest("tr").hide();
            $("#skuautoffxf_auto_variant").closest("tr").hide();
        }
    }

    $("#skuautoffxf_variation_settings").on("change", function() {
        toggleVariationSettings();
    });

    toggleVariationSettings();

    function checkAndToggleParent() {
        var inputValue = $('#skuautoffxf_number_dop').val();
        var parentElement = $('#skuautoffxf_format_an').closest('tr');

        if (inputValue.startsWith('0') || inputValue.startsWith('00') || inputValue.startsWith('000')) {
            parentElement.show();
        } else {
            parentElement.hide();
        }
    }

    $('#skuautoffxf_number_dop').on('input', function() {
        checkAndToggleParent();
    });

    checkAndToggleParent();
})(jQuery);



