jQuery(document).ready(function($) {
    var $ehuBar = $('div#ehu-bar');
    var $ehuBarLinks = $('div#ehu-bar a');
    var $ehuBarContent = $('div#ehu-bar *');
    var $hideShowBarSpeed = 'slow';
    var $dhuCloseButton = $('#ehu-close-button'); // close button
    var $dhuOpenButton = $('#ehu-open-button'); // Open button
    var $wpAdminBar = $('#wpadminbar').html();
    if ($wpAdminBar !== 'undefined') {
        var $dhuOpenButtonTop = '38px';
    } else {
        var $dhuOpenButtonTop = '10px';
    };
    var $ehuCookie = ehuReadCookie('ehuBarStatus');
    // Check the Cookie
    if ($ehuCookie === 'hidden') {
        $ehuBar.hide();
        $dhuOpenButton.css({
            'visibility': 'visible'
        });
    } else {
        $hideShowBarSpeed = 'slow';
    }
    // Check the bar is here
    if (typeof $ehuBar.html() !== 'undefined') {
        $ehuBar.remove();
        var $barTextColor = $ehuBar.attr('data-bar-text-color');
        var $linkColor    = $ehuBar.attr('data-bar-link-color');
        var $barLocation  = $ehuBar.attr('data-bar-location');

        if ($barLocation == 'top') {
            $ehuBar.prependTo('body');
        } else {
            $ehuBar.appendTo('body');
        };
        $ehuBarContent.css({
          'color': $barTextColor
        });
        $ehuBarLinks.css({
            'color': $linkColor
        });

        if (typeof $dhuCloseButton.html() !== 'undefined') {
            if ($barLocation == 'top') {
                $dhuOpenButton.css({
                    'top': $dhuOpenButtonTop
                });
            } else {
                $dhuOpenButton.css({
                    'bottom': '10px'
                })
            };

            // hide action
            $dhuCloseButton.click(function() {
                $(this).parent().slideUp($hideShowBarSpeed, function() {
                    $dhuOpenButton.css({
                        'visibility': 'visible'
                    });
                    // Set Cookie
                    ehuCreateCookie('ehuBarStatus', 'hidden', 7);
                });
            });

            // hide action
            $dhuOpenButton.click(function() {
                // Unset Cookie
                ehuEraseCookie('ehuBarStatus');
                $dhuOpenButton.css({
                    'visibility': 'hidden'
                });
                $ehuBar.slideDown($hideShowBarSpeed, function() {
                    if ($barLocation == 'top') {
                        window.scrollTo(0, 0);
                    } else {
                        window.scrollTo(0, $dhuOpenButtonTop);
                    };
                });
            });

        }; // end check for button
    }; // end Check for bar
});

// Cookie script care of these great guys http://www.quirksmode.org/js/cookies.html

function ehuCreateCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function ehuReadCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function ehuEraseCookie(name) {
    ehuCreateCookie(name, "", -1);
}

// EOF