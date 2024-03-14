sl = jQuery.noConflict();
sl(function ($) {
    var checkKey = setInterval(check, 60000);
    check();

    function check() {
        var data = {
            'action': 'sharelink-check-key'
        };

        jQuery.post(ajaxurl, data, function(response) {
            if(response == 'success' || response == 'failed') {
                clearInterval(checkKey);
            } 
        });
    }
});