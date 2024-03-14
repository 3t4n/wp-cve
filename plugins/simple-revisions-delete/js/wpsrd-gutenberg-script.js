/**
 * Plugin Name: Simple Revisions Delete by bweb
 * Plugin URI: http://b-website.com/
 * Author: Brice CAPOBIANCO - b*web
 */

jQuery(document).ready(function ($) {
    $(window).load(function () {

        //Set revisions coutner to 0 and remove link to revisions control page
        function wpsrd_set_counter_null() {
            $('.edit-post-last-revision__panel .components-button').attr('href', '#');
            var revEl = $('.editor-post-last-revision__title');
            var svg = revEl.find('svg');
            revEl.html('0' + revEl.text().replace(/\d+/g, ''));
            revEl.prepend(svg)
        }

        // Insert revision purge link
        function wpsrd_purge_link() {
            setTimeout(function () {
                if ($('#wpsrd-gutenberg').length > 0 && $('.edit-post-last-revision__panel').length > 0) {
                    $('.edit-post-last-revision__panel').append($('#wpsrd-gutenberg').html());
                    $('#wpsrd-clear-revisions').css('display', 'inline-block');
                }
            }, 300);
        }

        //Add remove link under Gutenberg revisions control
        wpsrd_purge_link();
        $('.edit-post-header__settings, .edit-post-sidebar, .edit-post-layout__content').on('click, focusout', 'button.components-button, button.edit-post-sidebar__panel-tab, .editor-block-list__layout, .editor-post-save-draft', function () {
            if ($('#wpsrd-gutenberg').hasClass('purged')) {
                setTimeout(function () {
                    wpsrd_set_counter_null();
                }, 300);
            } else {
                wpsrd_purge_link();
            }
        });

        //Listen to post save state 
        //This is pretty much a hack, not a clean method
        function waitForEl(selector, callback, maxtries = 10, interval = 200) {
            const poller = setInterval(() => {
                const el = jQuery(selector)
                const retry = maxtries === false || maxtries-- > 0
                if (retry && el.length < 1) return // will try again
                clearInterval(poller)
                callback(el || null)
            }, interval)
        }
        $('.edit-post-header__settings').on('click', '.editor-post-publish-button, .editor-post-save-draft', function () {
            setTimeout(function () {
                var selector = $('.edit-post-last-revision__panel');
                waitForEl(selector, function () {
                    $('#wpsrd-gutenberg').removeClass('purged')
                    $('.edit-post-last-revision__panel #wpsrd-clear-revisions, .edit-post-last-revision__panel .misc-pub-section').remove();
                    wpsrd_purge_link();
                });
            }, 300);
        });

        //Ajax clear revisions 
        $('.edit-post-sidebar').on('click', '#wpsrd-clear-revisions a.once', function (event) {
            if ($('.edit-post-last-revision__panel').length > 0) {
                event.preventDefault();
                $(this).removeClass('once').html($(this).data('action')).blur();
                $('.edit-post-last-revision__panel #wpsrd-clear-revisions a.wpsrd-link').css({
                    'text-decoration': 'none'
                })
                $('.edit-post-last-revision__panel #wpsrd-clear-revisions .wpsrd-loading').css('display', 'inline-block');
                $.ajax({
                    url: ajaxurl,
                    data: {
                        'action': 'wpsrd_purge_revisions',
                        'wpsrd-nonce': $('#wpsrd-clear-revisions a').data('nonce'),
                        'wpsrd-post_ID': $('#post_ID[name="post_ID"]').val()
                    },
                    success: function (response) {
                        if (response.success) {
                            $('.edit-post-last-revision__panel #wpsrd-clear-revisions .wpsrd-loading, .misc-pub-revisions > a').remove();
                            wpsrd_set_counter_null();
                            $('.edit-post-last-revision__panel #wpsrd-clear-revisions a.wpsrd-link').addClass('sucess').html('<span class="dashicons dashicons-yes" style="color:#7ad03a;"></span> ' + response.data);
                        } else {
                            $('.edit-post-last-revision__panel #wpsrd-clear-revisions .wpsrd-loading').remove();
                            $('.edit-post-last-revision__panel #wpsrd-clear-revisions a.wpsrd-link').addClass('error').html(response.data);
                        }
                        $('#wpsrd-gutenberg').addClass('purged');
                        setTimeout(function () {
                            $('.edit-post-last-revision__panel #wpsrd-clear-revisions').fadeOut();
                        }, 3500);
                    },
                    error: function (response) {
                        $('.edit-post-last-revision__panel #wpsrd-clear-revisions .wpsrd-loading').remove();
                        $('.edit-post-last-revision__panel #wpsrd-clear-revisions a').html($('#wpsrd-clear-revisions a').data('error')).addClass('error');
                    }
                });
            }
        });

    });
});