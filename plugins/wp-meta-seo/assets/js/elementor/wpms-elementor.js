jQuery(function ($) {
    // This is short syntax for (function($) {} (jQuery);

    var tmpData = [];
    $(document).ready(function () {
        $(document).on('input', '#metaseo_wpmseo_desc', function () {
            this.style.width = this.value.length + "ch";
        });
        $('.metabox-snippet-title .container-snippet .input').on('input', function () {
            $('.metabox-snippet-title .container-snippet .text').text($(this).val());
        }).trigger('input');
    });

    var replacedVars = [];  // jshint ignore:line
    var wpmsdivtitle = '';

    // load js for element when Meta Seo tab active
    function initMetaboxEvent() {
        // title
        wpmsdivtitle = '.entry-title';

        $.fn.focusTextToEnd = function () {
            this.focus();
            var $thisVal = this.val();
            this.val('').val($thisVal);
            return this;
        }

        $('.snippet-preview').on('click', function (e) {
            e.preventDefault();
        });

        $('#metaseo_snippet_title').on('focus', function () {
            $(this).hide();
            $('#' + wpmseoMetaboxL10n.field_prefix + 'title').removeAttr('type').focusTextToEnd();
        });

        $('#' + wpmseoMetaboxL10n.field_prefix + 'title').on('focusout', function () {
            $(this).attr('type', 'hidden');
            $('#metaseo_snippet_title').show();
        });

        $('#' + wpmseoMetaboxL10n.field_prefix + 'title').keyup(function () {
            msUpdateTitle();
        });

        $('#' + wpmseoMetaboxL10n.field_prefix + 'keywords').keyup(function () {
            msUpdateKeywords();
        });

        $('body').on('keyup', wpmsdivtitle, function (event) {
            msUpdateTitle();
            msUpdateDesc();
        });

        // DON'T 'optimize' this to use descElm! descElm might not be defined and will cause js errors (Soliloquy issue)
        $('#' + wpmseoMetaboxL10n.field_prefix + 'desc').keyup(function () {
            msUpdateDesc();
        });

        // Set time out because of tinymce is initialized later then this is done
        setTimeout(
            function () {
                msUpdateSnippet();

                // Adding events to content and excerpt
                if (typeof tinyMCE !== 'undefined' && tinyMCE.get('content') !== null) {
                    tinyMCE.get('content').on('blur', msUpdateDesc);
                }

                if (typeof tinyMCE !== 'undefined' && tinyMCE.get('excerpt') !== null) {
                    tinyMCE.get('excerpt').on('blur', msUpdateDesc);
                }

                // set default size = 1
                $('.wpms-bootstrap-tagsinput input').attr('size', 1);
            },
            500
        );

        tippy('.metaseo_tool, .metaseo_help', {
            animation: 'scale',
            duration: 0,
            arrow: false,
            placement: 'top',
            theme: 'metaseo-tippy tippy-rounded',
            onShow(instance) {
                instance.popper.hidden = instance.reference.dataset.tippy ? false : true;
                instance.setContent(instance.reference.dataset.tippy);
            }
        });

        $('#metaseo_wpmseo_title, #metaseo_wpmseo_desc, #metaseo_snippet_title').on('mouseover', function () {
            $(this).addClass('wpms-mouseover-frame ');
        }).on('mouseout', function () {
            $(this).removeClass('wpms-mouseover-frame ');
        });
    }

    // Save Meta box data
    var onChangeWpms = false;
    var uploadImg = '#metaseo_wpmseo_twitter-image_button, #metaseo_wpmseo_opengraph-image_button';

    $(document).on("click", ".editor-post-permalink-editor__save", function () {
        var url;
        if ($('.editor-post-permalink-editor__edit').length) {
            var slug = $('.editor-post-permalink-editor__edit').val();
            url = wpmseoMetaboxL10n.wpmseo_permalink_template.replace('%postname%', slug).replace('http://', '');
        }

        $('#wpmseosnippet').find('.url').html(url);
    })
        .on("keypress", ".editor-post-permalink-editor__edit", function (e) {
            if (e.which === 13) {
                var slug = $(this).val();
                var url = wpmseoMetaboxL10n.wpmseo_permalink_template.replace('%postname%', slug).replace('http://', '');
                $('#wpmseosnippet').find('.url').html(url);
            }
        })

        // On change WPMS element on Elementor

        .on('keyup', '#metaseo_wpmseo_title, #metaseo_wpmseo_desc', function () {
            onChangeWpms = true;
            tmpData['title'] = $('#metaseo_wpmseo_title').val();
            tmpData['desc'] = $('#metaseo_wpmseo_desc').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('itemAdded itemRemoved', '#metaseo_wpmseo_specific_keywords', function () {
            onChangeWpms = true;
            tmpData['keyword'] = $('#metaseo_wpmseo_specific_keywords').tagsinput('items').join(', ');
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('keyup', '#metaseo_wpmseo_keywords', function () {
            onChangeWpms = true;
            tmpData['sEKeyword'] = $('#metaseo_wpmseo_keywords').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('keyup', '#metaseo_wpmseo_metaseo_canonical', function () {
            onChangeWpms = true;
            tmpData['canonicalUrl'] = $('#metaseo_wpmseo_metaseo_canonical').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('keyup', '#metaseo_wpmseo_opengraph-title', function () {
            onChangeWpms = true;
            tmpData['fbTitle'] = $('#metaseo_wpmseo_opengraph-title').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('keyup', '#metaseo_wpmseo_opengraph-desc', function () {
            onChangeWpms = true;
            tmpData['fbDesc'] = $('#metaseo_wpmseo_opengraph-desc').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('keyup', '#metaseo_wpmseo_opengraph-image', function () {
            onChangeWpms = true;
            tmpData['fbImg'] = $('#metaseo_wpmseo_opengraph-image').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('keyup', '#metaseo_wpmseo_twitter-title', function () {
            onChangeWpms = true;
            tmpData['twTitle'] = $('#metaseo_wpmseo_twitter-title').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('keyup', '#metaseo_wpmseo_twitter-desc', function () {
            onChangeWpms = true;
            tmpData['twDesc'] = $('#metaseo_wpmseo_twitter-desc').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('keyup', '#metaseo_wpmseo_twitter-image', function () {
            onChangeWpms = true;
            tmpData['fbImg'] = $('#metaseo_wpmseo_opengraph-image').val();
            tmpData['twImg'] = $('#metaseo_wpmseo_twitter-image').val();
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        })
        .on('click', uploadImg, function () {
            onChangeWpms = true;
            $('#elementor-panel-saver-button-publish').removeClass('elementor-disabled');
            $('#elementor-panel-saver-button-save-options').removeClass('elementor-disabled');
        });

    $(document).on('click', '#elementor-panel-saver-button-publish', function () {
        if (onChangeWpms) {
            // call ajax to save post
            const postID = parseInt($('#wpms-metabox-on-elementor .metaseo-progress-bar').data('post_id'));
            // get data from metabox on elementor to save
            let title, desc, keyword, sEKeyword, canonicalUrl, fTitle, fDesc, fImage, tTitle, tDesc,
                tImage, scoreProgress;
            title = $('#metaseo_wpmseo_title').val();
            desc = $('#metaseo_wpmseo_desc').val();
            keyword = $('#metaseo_wpmseo_specific_keywords').tagsinput('items');
            keyword = keyword.join(', ');
            sEKeyword = $('#metaseo_wpmseo_keywords').val();
            canonicalUrl = $('#metaseo_wpmseo_metaseo_canonical').val();
            fTitle = $('#metaseo_wpmseo_opengraph-title').val();
            fDesc = $('#metaseo_wpmseo_opengraph-desc').val();
            fImage = $('#metaseo_wpmseo_opengraph-image').val();
            tTitle = $('#metaseo_wpmseo_twitter-title').val();
            tDesc = $('#metaseo_wpmseo_twitter-desc').val();
            tImage = $('#metaseo_wpmseo_twitter-image').val();
            scoreProgress = $('#wpmetaseo_seo_keywords_result').val();
            var datas = {
                'title': title,
                'desc': desc,
                'specific_keywords': keyword,
                'keywords': sEKeyword,
                'metaseo_canonical': canonicalUrl,
                'opengraph-title': fTitle,
                'opengraph-desc': fDesc,
                'opengraph-image': fImage,
                'twitter-title': tTitle,
                'twitter-desc': tDesc,
                'twitter-image': tImage,
                'wp_metaseo_seoscore': scoreProgress
            };
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'wpms',
                    task: 'wpmsElementorSavePost',
                    elementorPostID: postID,
                    wpms_nonce: wpmseoMetaboxL10n.wpms_nonce,
                    datas: datas
                },
                success: function (res) {
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
    });

    // Active Meta SEO tab

    $(document).on('click', '#wpms-onelementor-tab', function (e) {
        $('#elementor-panel-elements-navigation .elementor-panel-navigation-tab').removeClass('elementor-active');
        $('#wpms-onelementor-tab').addClass('elementor-active');

        $('#elementor-panel-elements-search-area').hide();
        $('#elementor-panel-elements-wrapper').hide();
        $('#wpms-metabox-on-elementor').show();

        // Active SEO page optimation
        $('#elementor-wpms-general').addClass('active');
        $('#elementor-wpms-social').removeClass('active');
        $('#elementor-wpms-gsc').removeClass('active');
        $('#wpmseo_general').show();
        $('#wpmseo_social').hide();
        $('#wpmseo_gsc_keywords').hide();

        // Call js after meta seo tab ready
        if (typeof tmpData['title'] !== 'undefined') {
            $('#metaseo_wpmseo_title').val(tmpData['title']);
        }
        if (typeof tmpData['desc'] !== 'undefined') {
            $('#metaseo_wpmseo_desc').val(tmpData['desc']);
        }
        if (typeof tmpData['keyword'] !== 'undefined') {
            $('#metaseo_wpmseo_specific_keywords').val(tmpData['keyword']);
        }
        if (typeof tmpData['fbTitle'] !== 'undefined') {
            $('#metaseo_wpmseo_opengraph-title').val(tmpData['fbTitle']);
        }
        if (typeof tmpData['fbDesc'] !== 'undefined') {
            $('#metaseo_wpmseo_opengraph-desc').val(tmpData['fbDesc']);
        }
        if (typeof tmpData['fbImg'] !== 'undefined') {
            $('#metaseo_wpmseo_opengraph-image').val(tmpData['fbImg']);
        }
        if (typeof tmpData['twTitle'] !== 'undefined') {
            $('#metaseo_wpmseo_twitter-title').val(tmpData['twTitle']);
        }
        if (typeof tmpData['twDesc'] !== 'undefined') {
            $('#metaseo_wpmseo_twitter-desc').val(tmpData['twDesc']);
        }
        if (typeof tmpData['twImg'] !== 'undefined') {
            $('#metaseo_wpmseo_twitter-image').val(tmpData['twImg']);
        }
        if (typeof tmpData['sEKeyword'] !== 'undefined') {
            $('#metaseo_wpmseo_keywords').val(tmpData['sEKeyword']);
        }
        if (typeof tmpData['canonicalUrl'] !== 'undefined') {
            $('#metaseo_wpmseo_metaseo_canonical').val(tmpData['canonicalUrl']);
        }

        initMetaboxEvent();
    })
        .on('click', '#elementor-panel-elements-navigation .elementor-panel-navigation-tab:not(#wpms-onelementor-tab)', function (e) {
            $('#wpms-onelementor-tab').removeClass('elementor-active');
            $(this).addClass('elementor-active');

            $('#elementor-panel-elements-search-area').show();
            $('#elementor-panel-elements-wrapper').show();
            $('#wpms-metabox-on-elementor').hide();
        })

        // Active SEO page
        .on('click', '#elementor-wpms-general', (e) => {
            $('#elementor-wpms-social').removeClass('active');
            $('#elementor-wpms-general').addClass('active');
            $('#elementor-wpms-gsc').removeClass('active');
            $('#wpmseo_social').hide();
            $('#wpmseo_general').show();
            $('#wpmseo_gsc_keywords').hide();
        })
        .on('click', '#elementor-wpms-social', (e) => {
            $('#elementor-wpms-general').removeClass('active');
            $('#elementor-wpms-social').addClass('active');
            $('#elementor-wpms-gsc').removeClass('active');
            $('#wpmseo_general').hide();
            $('#wpmseo_social').show();
            $('#wpmseo_gsc_keywords').hide();
        })
        .on('click', '#elementor-wpms-gsc', (e) => {
            $('#elementor-wpms-gsc').addClass('active');
            $('#elementor-wpms-social').removeClass('active');
            $('#elementor-wpms-general').removeClass('active');
            $('#wpmseo_general').hide();
            $('#wpmseo_social').hide();
            $('#wpmseo_gsc_keywords').show();
        })

        // when change follow of post/page in metabox view
        .on('change', '.metaseo_metabox_follow', function () {
            const page_id = $(this).data('post_id');
            const follow = $(this).val();
            metaseo_update_pagefollow(page_id, follow);
        })
        .on('change', '.metaseo_metabox_index', function () {
            const page_id = $(this).data('post_id');
            const index = $(this).val();
            metaseo_update_pageindex(page_id, index);
        });

    function msClean(str) {
        if (str === '' || typeof (str) === 'undefined') {
            return '';
        }

        try {
            str = str.replace(/<\/?[^>]+>/gi, '');
            str = str.replace(/\[(.+?)](.+?\[\/\\1])?/g, '');
            str = $('<div/>').html(str).text();
        } catch (e) {
        }

        return str;
    }

    function msReplaceVariables(str, callback) {
        if (typeof str === 'undefined') {
            return;
        }
        let titleReplace = (typeof document.title !== 'undefined') ? document.title : '';
        titleReplace = titleReplace.replace("Elementor |", "");
        titleReplace = titleReplace.trim();
        str = str.replace(/%title%/g, titleReplace.replace(/(<([^>]+)>)/ig, ''));
        // These are added in the head for performance reasons.
        str = str.replace(/%id%/g, wpmseoMetaboxL10n.id);
        str = str.replace(/%date%/g, wpmseoMetaboxL10n.date);
        str = str.replace(/%sitedesc%/g, wpmseoMetaboxL10n.sitedesc);
        str = str.replace(/%sitename%/g, wpmseoMetaboxL10n.sitename);
        str = str.replace(/%sep%/g, wpmseoMetaboxL10n.sep);
        str = str.replace(/%page%/g, wpmseoMetaboxL10n.page);
        str = str.replace(/%currenttime%/g, wpmseoMetaboxL10n.currenttime);
        str = str.replace(/%currentdate%/g, wpmseoMetaboxL10n.currentdate);
        str = str.replace(/%currentday%/g, wpmseoMetaboxL10n.currentday);
        str = str.replace(/%currentmonth%/g, wpmseoMetaboxL10n.currentmonth);
        str = str.replace(/%pagetotal%/g, wpmseoMetaboxL10n.pagetotal);
        str = str.replace(/%pagenumber%/g, wpmseoMetaboxL10n.pagenumber);
        str = str.replace(/%currentyear%/g, wpmseoMetaboxL10n.currentyear);

        // excerpt
        var excerpt = '';
        if ($('#excerpt').length) {
            excerpt = msClean($('#excerpt').val().replace(/(<([^>]+)>)/ig, ''));
            str = str.replace(/%excerpt_only%/g, excerpt);
        }
        if ('' === excerpt && $('#content').length) {
            excerpt = $('#content').val().replace(/(<([^>]+)>)/ig, '').substring(0, wpmseoMetaboxL10n.wpmseo_meta_desc_length - 1);
        }
        str = str.replace(/%excerpt%/g, excerpt);

        // parent page
        if ($('#parent_id').length && $('#parent_id option:selected').text() !== wpmseoMetaboxL10n.no_parent_text) {
            str = str.replace(/%parent_title%/g, $('#parent_id option:selected').text());
        }

        // remove double separators
        var esc_sep = msEscapeFocusKw(wpmseoMetaboxL10n.sep);
        var pattern = new RegExp(esc_sep + ' ' + esc_sep, 'g');
        str = str.replace(pattern, wpmseoMetaboxL10n.sep);

        if (str.indexOf('%') !== -1 && str.match(/%[a-z0-9_-]+%/i) !== null) {
            var regex = /%[a-z0-9_-]+%/gi;
            var matches = str.match(regex);
            for (var i = 0; i < matches.length; i++) {
                if (typeof (replacedVars[matches[i]]) === 'undefined') {
                    //str = str.replace(matches[i], replacedVars[matches[i]]);
                } else {
                    var replaceableVar = matches[i];

                    // create the cache already, so we don't do the request twice.
                    replacedVars[replaceableVar] = '';
                    msAjaxReplaceVariables(replaceableVar, callback);
                }
            }
        }
        callback(str);
    }

    function msAjaxReplaceVariables(replaceableVar, callback) {
        $.post(ajaxurl, {
            action: 'wpmseo_replace_vars',
            string: replaceableVar,
            post_id: $('#post_ID').val(),
            _wpnonce: wpmseoMetaboxL10n.wpmseo_replace_vars_nonce
        }, function (data) {
            if (data) {
                replacedVars[replaceableVar] = data;
            }

            msReplaceVariables(replaceableVar, callback);
        });
    }

    /*
     * Change meta title in meta box
     */
    function msUpdateTitle(force) {
        var title = '';
        var titleElm = $('#' + wpmseoMetaboxL10n.field_prefix + 'title');
        if (!titleElm.length) {
            return;
        }
        var titleLengthError = $('#' + wpmseoMetaboxL10n.field_prefix + 'title-length-warning');
        var divHtml = $('<div />');

        if (titleElm.val()) {
            title = titleElm.val().replace(/(<([^>]+)>)/ig, '');
        } else if (wpmseoMetaboxL10n.metatitle_tab === '1') {
            title = divHtml.html(title).text();
        }

        if (title === '') {
            var len = wpmseoMetaboxL10n.wpmseo_meta_title_length - $('#metaseo_snippet_title').val().length;
            metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'title-length');
            titleLengthError.hide();
        }

        title = msClean(title);
        title = divHtml.text(title).html();
        if (force) {
            titleElm.val(title);
        }
        msReplaceVariables(title, function (title) {
            title = msSanitizeTitle(title);
            // do the placeholder
            var placeholder_title = divHtml.html(title).text();
            if (typeof placeholder_title !== 'undefined' && placeholder_title !== 'undefined') {
                $('#metaseo_snippet_title').val(placeholder_title);
            }

            var len = wpmseoMetaboxL10n.wpmseo_meta_title_length - $('#metaseo_snippet_title').val().length;
            metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'title-length');
        });
    }

    /*
     * Change meta keywords in meta box
     */
    function msUpdateKeywords() {
        var keywordsElm = $('#' + wpmseoMetaboxL10n.field_prefix + 'keywords');
        if (typeof keywordsElm.val() !== 'undefined' && keywordsElm.val() !== '') {
            var len = wpmseoMetaboxL10n.wpmseo_meta_keywords_length - keywordsElm.val().length;
            metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'keywords-length');
            $('#' + wpmseoMetaboxL10n.field_prefix + 'keywords-length').html(len);
        } else {
            $('#' + wpmseoMetaboxL10n.field_prefix + 'keywords-length').addClass('length-true').removeClass('length-wrong').html('<span class="good">' + wpmseoMetaboxL10n.wpmseo_meta_keywords_length + '</span>');
        }
    }

    /*
     * Clean title
     */
    function msSanitizeTitle(title) {
        title = msClean(title);
        return title;
    }

    /*
     * Change meta description in meta box
     */
    function msUpdateDesc() {
        var desc = (msClean($('#' + wpmseoMetaboxL10n.field_prefix + 'desc').val())).trim();
        var divHtml = $('<div />');
        var snippet = $('#wpmseosnippet');

        if (desc === '' && wpmseoMetaboxL10n.wpmseo_desc_template !== '') {
            desc = wpmseoMetaboxL10n.wpmseo_desc_template;
        }

        if (desc !== '') {
            msReplaceVariables(desc, function (desc) {
                desc = divHtml.text(desc).html();
                desc = msClean(desc);

                var len = wpmseoMetaboxL10n.wpmseo_meta_desc_length - desc.length;
                metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'desc-length');
                desc = msSanitizeDesc(desc);

                // Clear the autogen description.
                snippet.find('.desc span.autogen').html('');
                // Set our new one.
            });
        } else {
            var len = wpmseoMetaboxL10n.wpmseo_meta_desc_length;
            metaseo_status_length(len, '#' + wpmseoMetaboxL10n.field_prefix + 'desc-length');
        }
    }

    /*
     * Sanitize description
     */
    function msSanitizeDesc(desc) {
        desc = msTrimDesc(desc);
        return desc;
    }

    function msTrimDesc(desc) {
        if (desc.length > wpmseoMetaboxL10n.wpmseo_meta_desc_length) {
            var space;
            if (desc.length > wpmseoMetaboxL10n.wpmseo_meta_desc_length) {
                space = desc.lastIndexOf(' ', (wpmseoMetaboxL10n.wpmseo_meta_desc_length - 3));
            } else {
                space = wpmseoMetaboxL10n.wpmseo_meta_desc_length;
            }
            desc = desc.substring(0, space).concat(' ...');
        }
        return desc;
    }

    /*
     * Update Url
     */
    function msUpdateURL() {
        var url;
        if ($('#editable-post-name-full').length) {
            var name = $('#editable-post-name-full').text();
            url = wpmseoMetaboxL10n.wpmseo_permalink_template.replace('%postname%', name).replace('http://', '');
        }

        $('#wpmseosnippet').find('.url').html(url);
    }

    function msUpdateSnippet() {
        if (typeof wpmseoMetaboxL10n.show_keywords !== "undefined" && parseInt(wpmseoMetaboxL10n.show_keywords) === 1) {
            msUpdateKeywords();
        }
        msUpdateURL();
        msUpdateTitle();
        msUpdateDesc();
    }

    function msEscapeFocusKw(str) {
        return str.replace(/[\-\[\]\/\{}\(\)\*\+\?\.\\\^\$\|]/g, '\\$&');
    }

    function metaseo_status_length(len, id, number) {
        var num = 46;
        var check = 0;
        var mclass = '';
        if (id === '#metaseo_wpmseo_title-length') {
            num = 50;
            check = wpmseoMetaboxL10n.wpmseo_meta_title_length - len;
            mclass = 'word-74B6FC';
        } else if (id === '#metaseo_wpmseo_desc-length') {
            num = 120;
            check = wpmseoMetaboxL10n.wpmseo_meta_desc_length - len;
            mclass = 'word-74B6FC';
        } else if (id === '#metaseo_wpmseo_keywords-length') {
            num = 120;
            check = len;
        }

        if (len < 0) {
            $(id).addClass('length-wrong').removeClass('length-true length-warn ' + mclass);
            len = '<span class="wrong">' + len + '</span>';
        } else if (check >= 0 && check <= num) {
            $(id).addClass('length-warn ' + mclass).removeClass('length-true length-wrong');
            len = '<span class="length-warn ' + mclass + '">' + len + '</span>';
        } else {
            $(id).addClass('length-true').removeClass('length-wrong length-warn ' + mclass);
            len = '<span class="good">' + len + '</span>';
        }

        $(id).html(len);
    }

    function metaseo_update_pagefollow(page_id, follow) {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            dataType: 'json',
            data: {
                'action': 'wpms',
                'task': 'update_pagefollow',
                'page_id': page_id,
                'follow': follow,
                'wpms_nonce': wpmseoMetaboxL10n.wpms_nonce
            }
        });
    }

    function metaseo_update_pageindex(page_id, index) {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            dataType: 'json',
            data: {
                'action': 'wpms',
                'task': 'update_pageindex',
                'page_id': page_id,
                'index': index,
                'wpms_nonce': wpmseoMetaboxL10n.wpms_nonce
            }
        });
    }

    // Custom bootstrap tagsinput when paste value
    $(document).on('paste', '.wpms-bootstrap-tagsinput > input', function(e) {
        let pasteData = e.originalEvent.clipboardData.getData('text');
        $(this).attr('size', pasteData.length);
    })
        .on('focusout', '.wpms-bootstrap-tagsinput > input', function (e) {
            $(this).attr('size', 1);
        });
});

/* Google search console addon */
jQuery(function ($) {
    var tmpGSC = [];
    // When active Meta SEO tab, run the first load
    $(document).on('click', '#wpms-onelementor-tab', () => {
        // Check is google console connected
        if (typeof wpmseoMetaboxL10n.keyword_console_connected !== "undefined" && parseInt(wpmseoMetaboxL10n.keyword_console_connected) === 1) {

            // when change elementor tab, save tmp data
            if (typeof tmpGSC['typeName'] !== 'undefined') {
                $('.filter-type-name').val(tmpGSC['typeName']);
            }
            if (typeof tmpGSC['searchKey'] !== 'undefined') {
                $('#wpms-search-input-csl').val(tmpGSC['searchKey']);
                if (tmpGSC['searchKey'] !== '') {
                    $(".icons-key-csl-search").addClass('obtain');
                } else {
                    $(".icons-key-csl-search").remove('obtain');
                }
            } else {
                addLinkToSearchBox();
            }
            if (typeof tmpGSC['filterTime'] !== 'undefined') {
                $('.gsc_keywords_time_filter select').val(tmpGSC['filterTime']);
            }
            if (typeof tmpGSC['filter-row1'] !== 'undefined') {
                $('#filter-row1').attr('checked', 'checked');
                $('#gsc_keywords_table tr >th:nth-child(4),#gsc_keywords_table tr >td:nth-child(4)').show();
            }
            if (typeof tmpGSC['filter-row2'] !== 'undefined') {
                $('#filter-row2').attr('checked', 'checked');
                $('#gsc_keywords_table tr >th:nth-child(5),#gsc_keywords_table tr >td:nth-child(5)').show();
            }

            // first loading
            wpms_filter_search_keywords();
        }
    })
        // On change filter type name
        .on('change', '.filter-type-name', (e) => {
            const $this = $(e.target);
            var select = $this.val();
            tmpGSC['typeName'] = select;
            var placeholder = 'Add keywords like "Apple pie recipe..."';
            if (select === 'page') {
                placeholder = 'Paste URL to fetch the Google Search keywords';
            }

            $(".icons-key-csl-search").removeClass('obtain');
            $("#wpms-search-input-csl").val('').attr('placeholder', placeholder).focus();
        })

        .on('change', '.gsc_keywords_time_filter select', (e) => {
            const $this = $(e.target);
            tmpGSC['filterTime'] = $this.val();
            console.log(tmpGSC['filterTime']);
            wpms_filter_search_keywords();
        })

        .on('click', '#wpms-csl-search-submit', () => {
            var keycsl = $('.wpms-search-key-csl').val().trim().toLocaleString();

            if (keycsl === '') {
                $('#wpms-search-input-csl').focus();
            }

            wpms_filter_search_keywords();
        })

        .on('keyup', '#wpms-search-input-csl', () => {
            var keycsl = $('#wpms-search-input-csl').val();
            tmpGSC['searchKey'] = keycsl;
            if (keycsl === '') {
                $(".icons-key-csl-search").removeClass('obtain');
            } else {
                $("#wpms-search-input-csl").removeClass('obtain');
                $(".icons-key-csl-search").addClass('obtain');
            }
        })

        .on('click', '.icons-key-csl-search', () => {
            addLinkToSearchBox();
            $("#wpms-search-input-csl").focus();
            $('select[name="filter_type_name"]').val('page');
            return false;
        })

        // Show filter row
        .on('click', '.filter-row', () => {
            $('#filter-row-list').toggle('fade');
        });

    // Show filter row outside
    $(document).mouseup(function (e) {
        var container = $("#filter-row-list");
        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide('fade');
        }
    })
        // Set filter row
        .on('click', '.filter-row-checkbox', () => {
            var showitems = [];
            $('input[name="filter-row-name"]:checked').each(function () {
                tmpGSC[this.id] = this.value;
                showitems.push(this.value);
            });
            $('#gsc_keywords_table tr >th:nth-child(4),#gsc_keywords_table tr >th:nth-child(5),#gsc_keywords_table tr >td:nth-child(4), #gsc_keywords_table tr >td:nth-child(5)').hide();


            if (showitems.length > 0) {
                $.each(showitems, function (i, showitem) {
                    $('#gsc_keywords_table tr >th:nth-child('+showitem+'), #gsc_keywords_table tr >td:nth-child('+showitem+')').show();
                });
            }
        });

    // Enter action
    $(document).on("keypress","#wpms-search-input-csl", (e) => {
        if(e.which === 13){
            e.preventDefault();
            wpms_filter_search_keywords();
        }
    })
        // Sort table
        .on('click', '#gsc_keywords_table label.gsc-sort-by', (e) => {
            const $this = $(e.target);
            $('#gsc_keywords_table label.gsc-sort-by').not($this).removeClass('sorted down up');

            if ($this.hasClass('down')) {
                $this.removeClass('down').addClass('sorted up').attr('data-order', 'ascending');
            } else {
                $this.removeClass('up').addClass('sorted down').attr('data-order', 'descending');
            }

            var page = $('#gsc_keywords_table tfoot').data('page');
            if (typeof page === 'undefined') {
                page = 2;
            }
            wpms_filter_search_keywords_more(parseInt(page) - 1, 1);
        })

        .on('click', '#gsc_keywords_table tfoot', (e) => {
            const $this = $(e.target);
            const page = $this.data('page');
            wpms_filter_search_keywords_more(page, 2);
        });


    // Call ajax to search keywords
    function wpms_filter_search_keywords() {
        var time = $('select[name="gsc_keywords_time_filter_select"] option:selected').val();
        var keycsl = '';
        if (typeof $('input.wpms-search-key-csl').val() !== 'undefined') {
            keycsl = $('input.wpms-search-key-csl').val().trim().toLocaleString();
        }
        var searchType = $('select[name="filter_type_name"] option:selected').val();
        var postId = $('#post_ID').val();
        var showitems = [];
        $('input[name="filter-row-name"]:checked').each(function () {
            showitems.push(this.value);
        });

        var sorteditem = $("#gsc_keywords_table label.sorted");
        var dataSort = '';
        if (sorteditem.length > 0) {
            dataSort = JSON.stringify([sorteditem.attr('data-sort'), sorteditem.attr('data-order')]);
        }

        $('table#gsc_keywords_table tbody#the-list').html('<tr><td colspan="6" class="td-page-loader"><img class="page-loader-loadmore" src="'+wpmseoMetaboxL10n.image_loader+'"></td></tr>');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'wpms_filter_search_keywords',
                time : time,
                postId: postId,
                showitems : showitems,
                keycsl : keycsl,
                searchType : searchType,
                dataSort : dataSort,
                wpms_nonce: wpmseoMetaboxL10n.wpms_nonce
            },
            success: function (res) {
                $('.wpms_load_more_keyword td.td-page-loader').remove();
                if(res.status){
                    $('.wpms_load_more_keyword').data('page',2);
                    $('.wpms_list_gsc_keywords tr').remove();
                    $('.wpms_list_gsc_keywords').append(res.html);
                } else {
                    $('.wpms_list_gsc_keywords').html('<tr><td colspan="6">'+wpmseoMetaboxL10n.keyword_filter_return+'</td></tr>');
                }
            }
        });
    }

    function wpms_filter_search_keywords_more(page, type) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        var $this = $('#gsc_keywords_table tfoot');
        var time = $('select[name="gsc_keywords_time_filter_select"] option:selected').val();
        var keycsl = $('input.wpms-search-key-csl').val().trim().toLocaleString();
        var searchType = $('select[name="filter_type_name"] option:selected').val();
        var postId = $('#post_ID').val();
        var showitems = [];
        $('input[name="filter-row-name"]:checked').each(function () {
            showitems.push(this.value);
        });

        var sorteditem = $("#gsc_keywords_table label.sorted");
        var dataSort = '';
        if (sorteditem.length > 0) {
            dataSort = JSON.stringify([sorteditem.attr('data-sort'), sorteditem.attr('data-order')]);
        }
        var loadder = '<tr><td colspan="6" class="td-page-loader"><img class="page-loader-loadmore" src="'+wpmseoMetaboxL10n.image_loader+'"></td></tr>';
        if (type === 1) {
            // Sort loader
            $('table#gsc_keywords_table tbody#the-list').html(loadder);
        } else {
            $('table#gsc_keywords_table tbody#the-list').append(loadder);
        }
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'wpms_filter_search_keywords',
                time : time,
                postId: postId,
                page:page,
                showitems : showitems,
                keycsl : keycsl,
                searchType : searchType,
                dataSort : dataSort,
                wpms_nonce: wpmseoMetaboxL10n.wpms_nonce
            },
            success: function (res) {
                if(res.status){
                    $this.data('page',res.page);
                    $('.wpms_list_gsc_keywords tr').remove();
                    $('.wpms_list_gsc_keywords').append(res.html);
                } else {
                    $('.wpms_list_gsc_keywords').html('<tr><td colspan="6">'+wpmseoMetaboxL10n.keyword_filter_return+'</td></tr>');
                }
            }
        });
    }

    function addLinkToSearchBox() {
        const link = window.location.protocol + '//' + $('#wpmseosnippet').find('.url').text();
        if (typeof link !== 'undefined') {
            $("#wpms-search-input-csl").val(link);
        }

        $(".icons-key-csl-search").addClass('obtain');
    }
});