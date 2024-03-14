jQuery(document).ready(function () {

    jQuery('.atp-alert').on('click', '.closebtn', function () {
        jQuery(this).closest('.atp-alert').fadeOut(); //.css('display', 'none');
    });

    jQuery("#fs_connect button[type=submit]").on("click", function(e) {
        window.open('https://better-robots.com/subscribe.php?plugin=twitter-pixel','twitter-pixel','resizable,height=400,width=700');
    });

});