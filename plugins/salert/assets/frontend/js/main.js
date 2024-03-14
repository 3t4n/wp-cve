(function($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     */
    $.fn.extend({
        hasClasses: function(selectors) {
            var self = this;
            for (var i in selectors) {
                if ($(self).hasClass(selectors[i]))
                    return true;
            }
            return false;
        }
    });

    $(function() {
        /**
         *	Layout Position eg: Bottomm Left or buttom right
         */
        var temp = salert_settings.salert_popup_position;
        var $sel = $('.popup_position');

        if ($sel.hasClass(temp)) {
            return;
        }

        if ($sel.hasClasses(['bottomLeft', 'bottomRight'])) {
            $sel.removeClass('bottomLeft bottomRight ');
        }

        $sel.addClass(temp);

        /**
         *	Layout Transition eg: FadeIn  Left or FadeOut right 
         */
        var temp = salert_settings.salert_popup_transition;
        var $sel = $('.popup_template');
        if ($sel.hasClass(temp)) {
            return;
        }

        if ($sel.hasClasses(['fadeInLeft', 'fadeInUp', 'fadeInRight', 'bounceInRight', 'bounceInLeft', 'bounceInUp','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','jackInTheBox','rollIn','lightSpeedIn'])) {
            $sel.removeClass('fadeInLeft fadeInUp fadeInRight bounceInRight bounceInLeft bounceInUp zoomIn zoomInDown zoomInLeft zoomInRight zoomInUp jackInTheBox rollIn lightSpeedIn');
        }
        $sel.addClass(temp);
    });

    /**
     *    PopUp Template Load/ Unload function
     */
    //Trigger popup for the first time (after popup_start_time)
    setTimeout(function() {
        loadPopupBox();
    }, salert_settings.salert_popup_start_time * 1000);

    //Loads the popup
    function loadPopupBox() {
        var StayTime = salert_settings.salert_popup_stay * 1000;

        showRandomData();
        setTimeout(function() {
            unloadPopupBox();
        }, StayTime);

    }

    function unloadPopupBox() {
        var x = salert_settings.salert_popup_range_from * 1; //making sure that it is a number
        var y = salert_settings.salert_popup_range_to * 1;
        var PopRange = Math.floor((Math.random() * (y - x)) + x + 1) * 1000;

        hidePopUp();
        setTimeout(function() {
            $('#popup_template').html('');
        }, PopRange);
        setTimeout(function() {
            loadPopupBox();
        }, PopRange);
    }

    function showRandomData() {
        var pageId = $('#salertWrapper').data('id');
        var pageType = $('#salertWrapper').data('page');
        $.ajax({
            url: salert_settings.ajax_url,
            type: 'post',
            data: {
                'action': 'salert_get_content',
                'page_type': pageType,
                'page_id': pageId,
            },
            success: function(data) {
                $('#popup_template').html(data);
            },
            complete: function(data) {
                //console.log(data);
                if(data!='' ){
                showPopUp();
                }
            }
        });
    }



    var animateClass = salert_settings.salert_popup_transition;
    if (animateClass == 'fadeInLeft') {
        var hide = 'fadeOutLeft';
    } else if (animateClass == 'fadeInRight') {
        var hide = 'fadeOutRight';
    } else if (animateClass == 'fadeInUp') {
        var hide = 'fadeOutDown';
    } else if (animateClass == 'bounceInLeft') {
        var hide = 'bounceOutLeft';
    } else if (animateClass == 'bounceInRight') {
        var hide = 'bounceOutRight';
    } else if (animateClass == 'bounceInUp') {
        var hide = 'bounceOutDown';
    } else if (animateClass == 'zoomIn') {
        var hide = 'zoomOut';
    } else if (animateClass == 'zoomInUp') {
        var hide = 'zoomOutUp';
    } else if (animateClass == 'zoomInDown') { 
        var hide = 'zoomOutDown';
    } else if (animateClass == 'zoomInLeft') { 
        var hide = 'zoomOutLeft';
    } else if (animateClass == 'zoomInRight') { 
        var hide = 'zoomOutRight';
    } else if (animateClass == 'jackInTheBox') { 
        var hide = 'hinge';
    } else if (animateClass == 'rollIn') { 
        var hide = 'rollOut';
    } else if (animateClass == 'lightSpeedIn') { 
        var hide = 'lightSpeedOut';
    }

    function showPopUp() {
        $('#salertWrapper').show();
        $('#popup_template').show();
        $('#popup_template').addClass(animateClass);
        $('#popup_template').removeClass(hide);
    }

    function hidePopUp() {
        $('#popup_template').removeClass(animateClass);
        $('#popup_template').addClass(hide);
        setTimeout(function() {
            $('#salertWrapper').hide();
        }, 3000);
    }
    $(document).mouseover(function(){
        $('.popup-item .btn-close').on('click',function(){
            hidePopUp();
        });
    });

})(jQuery);