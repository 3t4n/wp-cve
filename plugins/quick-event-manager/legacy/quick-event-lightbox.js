var $j = jQuery.noConflict();

function xlightbox(insertContent, ajaxContentUrl) {

    // add lightbox/shadow <div/>'s if not previously added
    if ($j('#xlightbox').length == 0) {
        var theLightbox = $j('<div id="xlightbox"/>');
        var theShadow = $j('<div id="xlightbox-shadow"/>');
        $j(theShadow).click(function (e) {
            closeLightbox();
        });
        $j('body').append(theShadow);
        $j('body').append(theLightbox);
    }

    // remove any previously added content
    $j('#xlightbox').empty();

    // insert HTML content
    if (insertContent != null) {
        $j('#xlightbox').append(insertContent);
    }

    // insert AJAX content
    if (ajaxContentUrl != null) {
        // temporarily add a "Loading..." message in the lightbox
        $j('#xlightbox').append('<p class="loading">Loading...</p>');

        // request AJAX content
        $j.ajax({
            type: 'GET',
            url: ajaxContentUrl,
            success: function (data) {
                // remove "Loading..." message and append AJAX content
                $j('#xlightbox').empty();
                $j('#xlightbox').append(data);
            },
            error: function () {
                alert('AJAX Failure!');
            }
        });
    }

    // move the lightbox to the current window top + 100px
    $j('#xlightbox').css('top', $j(window).scrollTop() + 100 + 'px');

    // display the lightbox
    $j('#xlightbox').show();
    $j('#xlightbox-shadow').show();

}

// close the lightbox

function closeLightbox() {

    // hide lightbox and shadow <div/>'s
    $j('#xlightbox').hide();
    $j('#xlightbox-shadow').hide();

    // remove contents of lightbox in case a video or other content is actively playing
    $j('#xlightbox').empty();
}
