function acwp_hasClass(ele,cls) {
    return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
if( acwp_hasClass(document.getElementsByTagName('body')[0], 'accessiblewp_page_acwp-images') ){
    jQuery(document).ready(function($){

        $('#accessiblewp-images .nav-tab').click(function (e) {
            e.preventDefault();
            var tab = $(this).attr('href');

            $('.acwp-tab').each(function () {
                $(this).removeClass('active');
            });

            $(tab).addClass('active');

            $('.nav-tab').each(function () {
                $(this).removeClass('nav-tab-active');
            });

            $(this).addClass('nav-tab-active');
        });

        // Activate wp color picker
        $('.color-field').each(function(){
            $(this).wpColorPicker();
        });
    });
}