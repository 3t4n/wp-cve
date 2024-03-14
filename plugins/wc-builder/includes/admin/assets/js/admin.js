(function($){
"use strict";

    /* Tab Menu
    ======================================================= */
    function wpbforwpbakery_admin_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('wlactive').parent().siblings().children('a').removeClass('wlactive');
            $( $tabpane + $target ).addClass('wlactive').siblings().removeClass('wlactive');
        });
    }
    wpbforwpbakery_admin_tabs( $(".wpbforwpbakery-admin-tabs"), '.wpbforwpbakery-admin-tab-pane' );
    

    /* Conditional fields 
    ======================================================= */
    $.fn.enablerenamelabel_condition = function(){
        var checked = Number($(this).is(':checked'));

        if(checked){
            $('.htp_show_if_enablerenamelabel_1').css('display', 'table-row');
        } else {
            $('.htp_show_if_enablerenamelabel_1').hide();
        }
    }; 

    $(document).ready(function(){
        // for reload
        $('.enablerenamelabel .checkbox').enablerenamelabel_condition();

        // for instant chagne
        $('.enablerenamelabel .checkbox').on('change', $.fn.enablerenamelabel_condition);
    });

})(jQuery);