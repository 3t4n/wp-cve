function wpcShowAjaxIcon(){
    $saveIconWrapper = jQuery('#wpc-ajax-save');

    if ($saveIconWrapper.is(':hidden')) {
        $saveIcon = $saveIconWrapper.children();
        $saveIcon.removeClass();
        $saveIcon.addClass('fa fa-spin fa-spinner');
        $saveIconWrapper.fadeIn();
    }
}

function wpcHideAjaxIcon(){
    $saveIconWrapper = jQuery('#wpc-ajax-save');

    if ($saveIconWrapper.is(':visible')) {
        $saveIcon = $saveIconWrapper.children();
        $saveIcon.removeClass();
        $saveIcon.addClass('fa fa-check');
    }
    
    $saveIconWrapper.fadeOut();
}

function capFirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

jQuery(document).ready(function($){

    questionOpened = false;

    $('.wpc-question-btn').click(function(){
        $('.wpc-lightbox-title').html('Help');
        $('.wpc-lightbox-content').html($(this).attr('data-content'));
        $('.wpc-lightbox-wrapper').fadeIn();

        // hacky workaround to stop lightbox from closing immediately
        questionOpened = true;

        setTimeout(function(){
            questionOpened = false;
        }, 250);
    });

	$(document).on('click', '.wpc-lightbox-close', function(){
    	$('.wpc-lightbox-container, .wpc-award-lightbox-wrapper, .wpc-lightbox-wrapper').fadeOut();
        $('.wpc-lightbox-pagination').hide();
    });


    $('body').click(function(e) {
        if(questionOpened === false){
            if (!$(e.target).closest('.wpc-lightbox').length){
                $(".wpc-lightbox-wrapper").fadeOut();
                $('wpc-lightbox-pagination').hide();
            }
        }
        
    });

    $('#wpc-course-order-select').change(function(){
        $(this).parent('form').submit();
    });
});

/********** Front-End Editor **********/

jQuery(document).ready(function($){

    // Create accordions for 1) Size, 2) Colors and 3) Headings
    $( "#wpc-fe-options-accordion" ).accordion({
        heightStyle: "content",
        icons: {
            activeHeader: "wpc-arrow-up",
            header: "wpc-arrow-down"
        }
    });

    // Create color pickers
    // 2) Colors: Change site live
    $(".wpc-fe-color-field").spectrum({
      showAlpha: false,
      showInput: true,
      preferredFormat: "hex",
      change : function(color){

        let css = '';
       
        css += ':root { '

        $(".wpc-fe-color-field").each(function(){
            let varCSS = $(this).data('var');
            css += varCSS + ': ' + $(this).val() + ' !important;';
        });

        css += '}';

        $('#wpc-fe-styles').html(css);

      },
      move : function(color){

        let css = '';
       
            css += ':root { '

            $(".wpc-fe-color-field").each(function(){
                let varCSS = $(this).data('var');
                css += varCSS + ': ' + $(this).val() + '; !important;';
            });

            css += '}';

            $('#wpc-fe-styles').html(css);

          }
    });

    // 2) Colors: Save settings in db
    $('.wpc-fe-color-field').on("move.spectrum", function(e, color) {
        var style = $(this).data('style');
        var option = $(this).data('option');

        var data = {
            'type'      : 'POST',
            'action'    : 'save_fe_option',
            'style'     : style,
            'value'     : color.toHexString(),
            'option'    : option,
            'security'  : wpc_ajax.nonce,
            'post_id'   : window.wpc_feePostId
        };

        wpcShowAjaxIcon();

        jQuery.post(ajaxurl, data, function(response) {
            wpcHideAjaxIcon();
        });
    });

    // 1) Size and 3) Headings: Change site live & save settings in db
    var typingTimer;
    var doneTypingInterval = 250;

    $(document).on('input', '.wpc-feo-text', function(){
        clicked = jQuery(this);
        clearTimeout(typingTimer);
        if (clicked.val()) {
            typingTimer = setTimeout(wpcDoneOptionTyping, doneTypingInterval);
        }

        value = $(this).val();
        textInput = $(this).prev();

        // set unit of measurement
        if(value.indexOf('%') != -1) {
           $(this).attr('data-unit', '%');
           $(this).prev().attr('max', 100);
        } else if(value.indexOf('px') != -1){
            $(this).attr('data-unit', 'px');
        } else if(value.indexOf('em') != -1) {
            $(this).attr('data-unit', 'em');
        }

        unit = $(this).attr('data-unit');

        intValue = value.replace(/[^\d.-]/g, '');
        $(this).prev().val(intValue);

        if(intValue > 100){
            textInput.attr('max', intValue);
        } else {
            textInput.attr('max', 100);
        }

        textInput.val(intValue + unit);

        elem = $(this).data('class');
        style = $(this).data('style');
        option = $(this).data('option');
        elem = $('.' + elem);

        for (var i = 0; i < elem.length; i++) {
            elem[i].style.setProperty(style, intValue + unit, 'important'); // jQuery css() does not support important
        }

        WPC_Global_UI.containerQueries();
        UI_Controller.resizeIframe();
    });

    // Disply green check
    function wpcDoneOptionTyping(){
        var data = {
            'type'      : 'POST',
            'action'    : 'save_fe_option',
            'style'     : style,
            'value'     : intValue + unit,
            'option'    : option,
            'security'  : wpc_ajax.nonce,
            'post_id'   : window.wpc_feePostId
        };

        wpcShowAjaxIcon();

        jQuery.post(ajaxurl, data, function(response) {
            wpcHideAjaxIcon();
        });
    }

    // Open / close front end editor
    $('#wpc-fe-setting-icon').click(function(){
        var status = $(this).attr('data-open');

        $(this).animate({
            bottom: '30px',
            left: '30px',
        }, 150).animate({
            bottom: '20px',
            left: '20px',
        }, 150);

        if(status == 'false'){
            $('.wpc-fe-options-wrapper').animate({
                left: '0px',
            }, 750);
            $(this).attr('data-open', 'true');
            $(this).css('background-color', '#de3c62');
            $(this).children('i').removeClass('fa-cog');
            $(this).children('i').addClass('fa-times');
        } else {
            $('.wpc-fe-options-wrapper').animate({
                left: '-438px',
            }, 750);
            $(this).attr('data-open', 'false');
            $(this).css('background-color', 'rgb(18 199 149)');
            $(this).children('i').removeClass('fa-times');
            $(this).children('i').addClass('fa-cog');
        }
    });
});