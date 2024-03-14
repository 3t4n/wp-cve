/*---------------------------------------------
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
----------------------------------------------*/

(function ($) {
    "use strict";

    // Tab
    var tabSelect = $('[data-tab-select]');
    var tab = $('[data-tab]');
    tabSelect.each(function(){
        var tabText = $(this).data('tab-select');
        $(this).on('click', function(){
            localStorage.setItem("eaSettingsTabActivation", tabText);
            $(this).addClass('active').siblings().removeClass('active');
            tab.each(function(){
                if(tabText === $(this).data('tab')){
                    $(this).fadeIn(500).siblings().hide(); // for click
                    $(this).addClass('active').siblings().removeClass('active');
                }
            });
        });
        if($(this).hasClass('active')){
            tab.each(function(){
                if(tabText === $(this).data('tab')){
                    $(this).addClass('active');
                }
                if($(this).hasClass('active')){
                    $(this).show().siblings().hide();
                }
            });
        }
    });

    // Check active tab
    let activateTab = localStorage.getItem("eaSettingsTabActivation");

    if( activateTab ) {
        $('[data-tab-select="'+activateTab+'"]').addClass('active').siblings().removeClass('active');
        $('[data-tab="'+activateTab+'"]').show().siblings().hide();
    }


    $(".activeable-element").on( 'click', function () {
        $(this).toggleClass('active');
        var $checks = $(this).find('input:checkbox[name]');
        $checks.prop("checked", !$checks.is(":checked"));

        //  Remove disabled attr from save changes button
        $('.enteraddons_save-btn').removeAttr('disabled')
        // Update selected widget count
        selected_widget_count();

    });

    // On key up input disabled attr from save changes button
    $('input').keyup( function() {
        $('.enteraddons_save-btn').removeAttr('disabled');
    } )

    /* -------------------------------------------------
      Selected widget count and show 
    ------------------------------------------------- */
    function selected_widget_count() {
        let $wc = $('[name="enteraddons_widgets[]"]:checked').length;
        $('.eddons-bottom-left').find('strong').text( $wc );
    }

    //Fire on dom ready
    $(function() {
        selected_widget_count();
    })

    /* -------------------------------------------------
      Element enable disable Button
    ------------------------------------------------- */
    var $itemSelector = $('.activeable-element'),
        $checkbox = $itemSelector.find('.onoffswitch-checkbox');

    $('[data-btn]').on( 'click', function(e) {
        e.preventDefault();

        $('.enteraddons_save-btn').removeAttr('disabled');

        var $this = $( this ).data('btn');
        if( $this == 'enable' ) {
          $checkbox.prop('checked', 'checked');  
          $itemSelector.addClass('active');
        } else {
          $checkbox.removeAttr('checked');
          $itemSelector.removeClass('active');
        }
        // Update selected widget count
        selected_widget_count();
    } )

    /* -------------------------------------------------
      Admin Search filter
    ------------------------------------------------- */

    $('[data-search]').on('keyup', function() {

        var searchVal = $(this).val();
        // Item filter callback
        items_filter( searchVal );

    });

    // Item filter callback function
    function items_filter( selectedVlue ) {

        var filterItems = $('[data-filter-item]');
        
        if ( selectedVlue != '' ) {
            filterItems.addClass('hidden');
            $('[data-filter-item][data-filter-name*="' + selectedVlue.toLowerCase() + '"]').removeClass('hidden');

        } else {
            filterItems.removeClass('hidden');
        }

    }

    /* -------------------------------------------------
      Admin Search filter
    ------------------------------------------------- */

    $('[data-type-btn]').on('click', function() {

        let $t = $(this),
            $type  = $t.data('type-btn'),
            $scope = $t.closest('.container'),
            $l = $scope.find('.lite-item'),
            $p = $scope.find('.pro-item');

        if( $type == 'free' ) {
            $l.fadeIn();
            $p.hide();
        } else {
            $l.hide();
            $p.fadeIn();
        }

    });

    // Select 2
    $('.ea-multiple-select').select2();

    $('.pro-item-demo').on( 'click', function(e) {
        e.preventDefault();
        $('.ea-modal-show').fadeIn();

    } )

    $('.ea-modal-close').on( 'click', function(e) {
        e.preventDefault();
        $('.ea-modal-show').fadeOut();

    } )


}(jQuery));