jQuery(function($){

    $(document).ready(function(){

        function sirv_update_window_dimensions() {
            var footer = $('#footer').length > 0 ? $('#footer') : $('#wpfooter');
            var body_height = $('body').height() - $(footer).outerHeight(true);
            $('#wpcontent').css('margin-left', '156px');
            $('#wpbody').css('height', body_height).css('overflow', 'hidden');
            $('#wpbody-content .content').css('height', body_height);
        }

        //Initialization
        sirv_update_window_dimensions()
        $(window).on('resize', sirv_update_window_dimensions);

    });
});
