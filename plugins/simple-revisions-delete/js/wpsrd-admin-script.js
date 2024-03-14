/**
 * Plugin Name: Simple Revisions Delete by bweb
 * Plugin URI: http://b-website.com/
 * Author: Brice CAPOBIANCO - b*web
 */

jQuery(document).ready(function ($) {

    //Ajax clear revisions
    if ($('.misc-pub-revisions b').length > 0) {
        $('#wpsrd-clear-revisions').appendTo('.misc-pub-revisions').show();
        $('#wpsrd-clear-revisions a.once').on("click", function (event) {
            event.preventDefault();
            $(this).removeClass('once').html($(this).data('action')).blur();
            $('#wpsrd-clear-revisions a.wpsrd-link').css({
                'text-decoration': 'none'
            })
            $('#wpsrd-clear-revisions .wpsrd-loading').css('display', 'inline-block');
            $.ajax({
                url: ajaxurl,
                data: {
                    'action': 'wpsrd_purge_revisions',
                    'wpsrd-nonce': $('#wpsrd-clear-revisions a').data('nonce'),
                    'wpsrd-post_ID': $('#post_ID[name="post_ID"]').val()
                },
                success: function (response) {
                    if (response.success) {
                        $('#revisionsdiv').slideUp();
                        $('#wpsrd-clear-revisions .wpsrd-loading, .misc-pub-revisions > a').remove();
                        $('.misc-pub-revisions b').text('0');
                        $('#wpsrd-clear-revisions a.wpsrd-link').addClass('sucess').html('<span class="dashicons dashicons-yes" style="color:#7ad03a;"></span> ' + response.data);
                    } else {
                        $('#wpsrd-clear-revisions .wpsrd-loading').remove();
                        $('#wpsrd-clear-revisions a.wpsrd-link').addClass('error').html(response.data);
                    }
                    setTimeout(function () {
                        $('#wpsrd-clear-revisions a.wpsrd-link').fadeOut();
                    }, 3500);
                },
                error: function (response) {
                    $('#wpsrd-clear-revisions .wpsrd-loading').remove();
                    $('#wpsrd-clear-revisions a').html($('#wpsrd-clear-revisions a').data('error')).addClass('error');
                }
            });
        });
    }

    // Ajax single revision delete
    if ($('.post-php #revisionsdiv').length > 0 && $('#wpsrd-btn-container').length) {

        $('#wpsrd-btn-container .wpsrd-btn').clone().appendTo('#revisionsdiv .post-revisions li');
        $.each($('#revisionsdiv .wpsrd-btn'), function () {
            var url = $(this).parent('li').find('a').attr('href');
            var revID = url.split('revision=').pop();
            $(this).attr('data-revid', revID);
        });

        $('#revisionsdiv .wpsrd-btn.once').on("click", function (event) {
            event.preventDefault();
            var elem = $(this);
            elem.removeClass('once');
            $('<span class="wpsrd-loading" style="display:inline-block"></span>').insertAfter(elem);

            $.ajax({
                url: ajaxurl,
                data: {
                    'action': 'wpsrd_single_revision_delete',
                    'revID': elem.data('revid'),
                    'wpsrd-post_ID': $('#post #post_ID').val(),
                    'wpsrd-nonce': elem.data('nonce')
                },
                success: function (response) {
                    elem.hide();
                    var count = $('.misc-pub-revisions b').text();
                    elem.parent('li').find('.wpsrd-loading').hide();
                    if (response.success) {
                        count = count - 1;
                        $('.misc-pub-revisions b').text(count);
                        elem.parent('li').addClass('sucess').append('<span class="dashicons dashicons-yes" style="color:#7ad03a;"></span> <b>' + response.data + '</b>');
                    } else {
                        elem.parent('li').addClass('error').append('<b> ' + response.data + '</b>');
                    }
                    setTimeout(function () {
                        elem.parent('li').fadeOut();
                        elem.remove();
                        if (count == '0') {
                            $('#revisionsdiv').slideUp();
                            $('#wpsrd-clear-revisions, .misc-pub-revisions > a').remove();
                        }
                    }, 3500);
                },
                error: function (response) {
                    elem.parent('li').find('.wpsrd-loading').hide();
                    elem.parent('li').addClass('error').append('<b> ' + $('#wpsrd-clear-revisions a').data('error') + '</b>');
                    elem.remove();
                }
            });
        });
    }

});