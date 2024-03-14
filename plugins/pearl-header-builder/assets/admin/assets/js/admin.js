(function($){
    $(document).ready(function(){
        $('.stm-theme-options-admin-wrapper .add-new-menu-action a').on('click', function(e){
            e.preventDefault();
            $('.stm_hb_add_new').slideToggle();
        });
    });
})(jQuery);