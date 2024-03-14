(function ($) {
    $(document).ready(function () {


        if ($.fn.tabs) {
            $('#cmf_tabs').tabs({
                activate: function (event, ui) {
                    window.location.hash = ui.newPanel.attr('id').replace(/-/g, '_');
                },
                create: function (event, ui) {
                    var tab = location.hash.replace(/\_/g, '-');
                    var tabContainer = $(ui.panel.context).find('a[href="' + tab + '"]');
                    if (typeof tabContainer !== 'undefined' && tabContainer.length)
                    {
                        var index = tabContainer.parent().index();
                        $(ui.panel.context).tabs('option', 'active', index);
                    }
                }
            });
        }
        $('.cmf_field_help_container').each(function () {
            var newElement,
                    element = $(this);
            newElement = $('<div class="cmf_field_help"></div>');
            newElement.attr('title', element.html());
            if (element.siblings('th').length)
            {
                element.siblings('th').append(newElement);
            } else
            {
                element.siblings('*').append(newElement);
            }
            element.remove();
        });
        $('.cmf_field_help').tooltip({
            show: {
                effect: "slideDown",
                delay: 100
            },
            position: {
                my: "left top",
                at: "right top",
                using: function (position, feedback) {
                    $(this).css(position);
                    $(this).css('backgroundColor', '#FBF8E4');
                    $(this).css("borderColor", "#FBF8E4");
                    $(this).css('color', '#606060');
                    $(this).css("opacity", "1");

                    $("<div>")
                            // .addClass("arrow")
                            .addClass(feedback.vertical)
                            .addClass(feedback.horizontal)
                            .appendTo(this);
                }
            },
            content: function () {
                var element = $(this);
                return element.attr('title');
            },
            close: function (event, ui) {
                ui.tooltip.hover(
                        function () {
                            $(this).stop(true).fadeTo(400, 1);
                        },
                        function () {
                            $(this).fadeOut("400", function () {
                                $(this).remove();
                            });
                        });
            }
        });
        /**
         * Adding new definition row
         */

        // Dashicons array
        var dashicons = ['admin-site', 'admin-home', 'align-center', 'align-left', 'align-none', 'align-right', 'analytics', 'art', 'awards', 'backup',
            'book', 'book-alt', 'businessman', 'calendar', 'camera', 'cart', 'category', 'chart-area', 'chart-bar',
            'chart-line', 'chart-pie', 'clock', 'cloud', 'desktop', 'dismiss', 'download', 'edit', 'editor-customchar', 'editor-distractionfree', 'editor-help', 'editor-insertmore', 'editor-justify', 'editor-kitchensink', 'editor-ol', 'editor-paste-text',
            'editor-paste-word', 'editor-quote', 'editor-removeformatting', 'editor-rtl', 'editor-spellcheck',
            'editor-ul', 'editor-unlink', 'editor-video', 'email', 'email-alt', 'exerpt-view', 'facebook', 'facebook-alt', 'feedback', 'flag', 'format-aside',
            'format-audio', 'format-chat', 'format-gallery', 'format-image', 'format-quote', 'format-status',
            'format-video', 'forms', 'googleplus', 'groups', 'hammer', 'id', 'id-alt', 'image-crop',
            'image-flip-horizontal', 'image-flip-vertical', 'image-rotate-left', 'image-rotate-right', 'images-alt',
            'images-alt2', 'info', 'leftright', 'lightbulb', 'list-view', 'location', 'location-alt', 'lock', 'marker',
            'menu', 'migrate', 'minus', 'networking', 'no', 'no-alt', 'performance', 'plus', 'portfolio', 'post-status',
            'pressthis', 'products', 'redo', 'rss', 'screenoptions', 'search', 'share', 'share-alt',
            'share-alt2', 'share1', 'shield', 'shield-alt', 'slides', 'smartphone', 'smiley', 'sort', 'sos', 'star-empty',
            'star-filled', 'star-half', 'tablet', 'tag', 'testimonial', 'translation', 'twitter', 'undo',
            'update', 'upload', 'vault', 'video-alt', 'video-alt2', 'video-alt3', 'visibility', 'welcome-add-page',
            'welcome-comments', 'welcome-learn-more', 'welcome-view-site', 'welcome-widgets-menus', 'welcome-write-blog',
            'wordpress', 'wordpress-alt', 'yes'];

        $(document).on('click', 'a#cm_footnote_add_new_definition', function () {
            let x = $('.cm-foot-meta-values-block').length + 1;

            // Preparing new row block output
            let htmlOut = '<div class="cm-foot-settings-flex-block cm-foot-meta-values-block">';
            htmlOut += '<div class="cm_footnote_definitions_td_id">';
            htmlOut += '<input type="text" name="cm_footnote_definitions_row_id[]" class="cm_footnote_definitions_row_id" value="' + x + '">';
            htmlOut += '</div>';
            htmlOut += '<div class="cm_footnote_definitions_td_content">';
            htmlOut += '<textarea  name="cm_footnote_definitions_row_content[]" class="cm_footnote_definitions_row_content"></textarea>';
            htmlOut += '</div>';
            htmlOut += '<div>';
            htmlOut += '<a href="#" class="cm_footnote_definitions_row_remove"><img src="' + cmf_data.imagePath + 'cancel.png" /></a>';
            htmlOut += '</div>';

            htmlOut += '</div>';

            let lastRow = $('.cm-foot-meta-values-block').last();
            $(lastRow).after(htmlOut);

            return false;
        });


        // Metabox row remove
        $(document).on('click', 'a.cm_footnote_definitions_row_remove', function () {
            var $this = $(this), $parent;
            console.log($this);
            $parent = $this.parent().parent().remove();
            return false;
        });

        // added js for dashicons
        var curr = 0;
        //postype footnote one
        $(document).on('click', '.ws_select_iconft', function (e) {
            e.preventDefault();
            curr = $(this).attr('data-id');

            $('.custom-dash-show-ft').css('display', 'block');

        });
        //postype footnote one
        $(document).on('click', '.ws_icon_option_ft', function (e) {
            var iconval = $(this).data('icon-url');
            $('#setdashft').val(iconval);
            $('.ws_select_iconft .render-dash-ft').remove();
            $('.selct-icon-ft').css('display', 'none');
            $('.button.ws_select_iconft').append('<div class="icon16 dashicons render-dash-ft"></div>');
            $('.render-dash-ft').addClass(iconval);
            $('.custom-dash-show-ft').css('display', 'none');
        });

        // Add dashicon from popup window list
        $(document).on('click', '.ws_icon_option_post', function (e) {
            var iconval = $(this).data('icon-url');
            $('#setdash_' + curr).val(iconval);
            $('.render-dash-' + curr).remove();
            $('.selct-icon-' + curr).css('display', 'none');
            $('.dashicon-button_' + curr).append('<div class="icon16 dashicons render-dash-' + curr + ' ' + iconval + '"></div>');
            $('.render-dash-' + curr).addClass(iconval);
            $('.div-clss-' + curr).css('display', 'none');
        });

        // Close dashicons popup if clicked out of it
        $(document).mouseup(function (e) {
            let div = $(".popupdash");
            for (let i = 0; i < $(div).length; i++) {
                if ($(div[i]).css('display') === 'block') {
                    var targetDiv = div[i];
                }
            }
            if (e.target != targetDiv) {
                $(targetDiv).css('display', 'none');
            }
        });

        //custom add footnote in post/pages
        $(document).on('click', '.ws_select_icon', function (e) {
            e.preventDefault();
            curr = $(this).attr('data-id');
            $('.popupdash').css('display', 'none');
            $('.div-clss-' + curr).css('display', 'block');

        });
        //remove icon from footnote post type
        $(document).on('click', '.removeicon-ft', function (e) {
            e.preventDefault();
            $('#setdashft').val('');
            $('.render-dash-ft').remove();
            $('.selct-icon-ft').css('display', 'block');
        });
        //remove icon for footnotes in posts
        $(document).on('click', '.remve-icon', function (e) {
            e.preventDefault();
            var rmid = $(this).attr('data-id');
            console.log(rmid);
            $('#setdash_' + rmid).val('');
            $('.selct-icon-' + rmid).css('display', 'block');
            console.log(rmid);
            $('.render-dash-' + rmid).css('display', 'none');
        });


        //footer seperator setting section js
        //Customizable label
        if ($('input[name="cmf_footnotetitlesep"]').is(':checked') == true) {
            $('input[name="cmf_footnoteshowtitle"]').css('display', 'inline-block');
        }
        if ($('input[name="cmf_footnotetitlesep"]').is(':checked') == false) {
            $('input[name="cmf_footnoteshowtitle"]').css('display', 'none');
        }
        $('input[name="cmf_footnotetitlesep"]').click(function () {
            if ($('input[name="cmf_footnotetitlesep"]').is(':checked') == true) {
                $('input[name="cmf_footnoteshowtitle"]').css('display', 'inline-block');
            }
            if ($('input[name="cmf_footnotetitlesep"]').is(':checked') == false) {
                $('input[name="cmf_footnoteshowtitle"]').css('display', 'none');
            }
        });
        //Optional separator
        if ($('input[name="cmf_footnotedesgnsep"]').is(':checked') == true) {
            $('.seperater-setting').css('display', 'inline-block');
        }
        if ($('input[name="cmf_footnotedesgnsep"]').is(':checked') == false) {
            $('.seperater-setting').css('display', 'none');
        }
        $('input[name="cmf_footnotedesgnsep"]').click(function () {
            console.log($('input[name="cmf_footnotedesgnsep"]').is(':checked'));
            if ($('input[name="cmf_footnotedesgnsep"]').is(':checked') == true) {
                $('.seperater-setting').css('display', 'inline-block');
            }
            if ($('input[name="cmf_footnotedesgnsep"]').is(':checked') == false) {
                $('.seperater-setting').css('display', 'none');
            }
        });


    });

})(jQuery);