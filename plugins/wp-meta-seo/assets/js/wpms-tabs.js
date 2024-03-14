(function ($) {
    $(document).ready(function(){
        $("ul.wpmstabs .tab a").on('click', function(e) {
            $(this).off('click').trigger('click');
        });
    });
}( jQuery ));
