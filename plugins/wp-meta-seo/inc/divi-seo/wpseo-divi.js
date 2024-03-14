var wpmseo_target_id, load_frame = true;
jQuery(document).ready(function ($) {
    if (load_frame) {
        load_frame = false;
        var $parent_window = $(window.parent.document);
        var $reload_analysis = 0;

        function get_content_and_title(first_load) {
            var mpageurl = '', title = '', mcontent = '', current_editor = '';

            var meta_title = $parent_window.find('#metaseo_wpmseo_title').val();
            var meta_desc = $parent_window.find('#metaseo_wpmseo_desc').val();
            var seo_keywords = $parent_window.find("input#metaseo_wpmseo_specific_keywords").val();
            mpageurl = location.protocol + $parent_window.find('#wpmseosnippet').find('a').text();

            if (typeof wp.blocks !== "undefined") {
                if (typeof mpageurl === 'undefined') {
                    mpageurl = $parent_window.find('#wp-admin-bar-view').find('a').attr('href');
                }
                current_editor = 'gutenberg';
                if (parseInt(first_load) === 1) {
                    title = wpmscliffpyles.post_title;
                    mcontent = wpmscliffpyles.post_content;
                } else {
                    title = wpmscliffpyles.post_title;
                    // Core editor
                    if (typeof wp.data !== "undefined" && typeof wp.data.select('core/editor') !== "undefined" && wp.data.select('core/editor') !== null) {
                        mcontent = wp.data.select('core/editor').getEditedPostContent();
                    } else if (typeof tinyMCE !== 'undefined') {
                        // WooCommerce product
                        if (tinyMCE.get('content') !== null) {
                            mcontent = tinyMCE.editors.content.getContent();
                            if (mcontent === '') mcontent = $parent_window.find('#content').val();
                        } else {
                            var $content_window = document.getElementById("et-fb-app-frame");
                            mcontent = $($content_window.contentWindow.document.getElementById("main-content")).prop('outerHTML');
                        }
                        console.log(mcontent);
                    }
                }
            } else {
                // undefined wp.blocks
                if (typeof wpmscliffpyles.post_type !== 'undefined' && wpmscliffpyles.post_type === 'attachment') {
                    // On media attachment page
                    if (typeof mpageurl === 'undefined') {
                        mpageurl = $parent_window.find('#sample-permalink').text().trim();
                    }

                    if (parseInt(first_load) === 1) {
                        title = wpmscliffpyles.post_title;
                        mcontent = wpmscliffpyles.post_content;
                    } else if (typeof wp.data !== "undefined") {
                        title = $parent_window.find('#title').val();
                        mcontent = $parent_window.find('#attachment_content').val();
                    }
                } else {
                    // Something went wrong, use default
                    if (typeof mpageurl === 'undefined') {
                        mpageurl = $parent_window.find('#editable-post-name-full').text();
                    }
                    title = $parent_window.find('#title').val();
                    if (typeof tinyMCE !== 'undefined' && tinyMCE.get('content') !== null) {
                        mcontent = tinyMCE.editors.content.getContent();
                        if (mcontent === '') mcontent = $parent_window.find('#content').val();
                    } else {
                        mcontent = $parent_window.find('#content').val();
                    }
                }
            }

            return [mcontent, title, mpageurl, seo_keywords, meta_desc, meta_title, current_editor];
        }

        function wpseoEditor($seo_et_ignore_iframe, $settings_bar_root, $settings_bar) {
            var wpmseo_uploader;

            function innit() {
                $settings_bar_root.after($seo_et_ignore_iframe);
                $settings_bar.append($settings_bar_root);
                $settings_bar_root.append('<div class="wp-menu-image dashicons-before dashicons-chart-area" aria-hidden="true"><br></div>');
                $settings_bar.find('.et-fb-page-settings-bar__column--main').before($settings_bar_root);
            }
            innit();

            function wpms_validate_analysis() {
                jQuery(document).on('click', '.metaseo-dashicons.icons-mboxwarning', function () {
                    var seo_keywords = $parent_window.find("input#metaseo_wpmseo_specific_keywords").val();
                    var $this = $(this);
                    $this.html('done').removeClass('icons-mboxwarning').addClass('icons-mboxdone');
                    if (mcheck === 0) {
                        mcheck = jQuery('#metaseo_alanysis_ok').val();
                        mcheck++;
                    } else {
                        mcheck++;
                    }

                    var total = 7;
                    if (seo_keywords !== '') {
                        total++;
                    }

                    var circliful = Math.ceil((mcheck * 100) / total);
                    jQuery.ajax({
                        dataType: 'json',
                        method: 'POST',
                        url: ajaxurl,
                        data: {
                            'action': 'wpms',
                            'task': 'validate_analysis',
                            'post_id': jQuery('.metaseo-progress-bar').data('post_id'),
                            'field': $this.parent('.metaseo_analysis').data('title'),
                            'wpms_nonce': wpms_localize.wpms_nonce
                        },
                        success: function (res) {
                            if (res !== false) {
                                drawInactive(circliful);
                            }
                        }
                    });

                });
            }

            var mcheck = 0;
            if (typeof wpmscliffpyles.use_validate !== "undefined" && parseInt(wpmscliffpyles.use_validate) === 1) {
                wpms_validate_analysis();
            }

            function reload_analysis(first_load) {
                $reload_analysis = 1;
                var mpageurl = '', title = '', mcontent = '', current_editor = '';
                var meta_title, meta_desc, seo_keywords;

                [mcontent, title, mpageurl, seo_keywords, meta_desc, meta_title, current_editor] = get_content_and_title(first_load);

                // In bad case, set empty value to avoid errors
                if (typeof mcontent === 'undefined') mcontent = '';
                if (typeof title === 'undefined') title = '';
                if (typeof mpageurl === 'undefined') mpageurl = '';

                $parent_window.find('.wpmseotab .spinner').css({'visibility': ' inherit'}).show();
                $parent_window.find('.metaseo_right .panel-left, .metaseo_right .panel-right').html('');
                // console.log(current_editor, meta_title, meta_desc, '123', seo_keywords, mpageurl, '123', mcontent, title);

                $.ajax({
                    dataType: 'json',
                    method: 'POST',
                    url: ajaxurl,
                    data: {
                        'action': 'wpms',
                        'task': 'reload_analysis',
                        'datas': {
                            'editor': current_editor,
                            'first_load': first_load,
                            'post_id': jQuery('.metaseo-progress-bar').data('post_id'),
                            'title': title,
                            'meta_title': meta_title,
                            'mpageurl': mpageurl,
                            'meta_desc': meta_desc,
                            'content': mcontent,
                            'seo_keywords': seo_keywords
                        },
                        'wpms_nonce': wpms_localize.wpms_nonce
                    },
                    success: function (res) {
                        if (res) {
                            $parent_window.find('.wpmseotab .spinner').hide();
                            $parent_window.find('.metaseo_right .panel-left').html(res.output);
                            $parent_window.find('.metaseo_right .panel-right').html(res.right_output);
                            mcheck = parseInt(res.check);
                            tippy('.metaseo_tool', {
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

                            drawInactive(res.circliful);
                        }
                    }
                });
            }

            function drawInactive(circliful) {
                $parent_window.find('.metaseo-progress-bar').circleProgress({
                    value: circliful / 100,
                    size: 250,
                    thickness: 8,
                    fill: {
                        gradient: ["#34e0ff", "#5dadff"]
                    }
                }).on('circle-animation-progress', function (event, progress) {
                    $(this).find('strong').html(Math.round(circliful) + '<i>%</i>');
                });
            }

            function addClassTo(that, classname) {
                if (that.hasClass(classname)) {
                    that.removeClass(classname);

                    // init load analysis
                    if ($reload_analysis === 0) {
                        reload_analysis(1);
                    }

                    $parent_window.find('#reload_analysis').on('click', function () {
                        reload_analysis(0);
                    });

                    $('#wpmseo_social').hide();

                    $('#elementor-wpms-general').addClass('active');
                    $('#elementor-wpms-social').removeClass('active');

                    $('#elementor-wpms-social').on('click', function (e) {
                        $('#elementor-wpms-general').removeClass('active');
                        $('#elementor-wpms-social').addClass('active');
                        $('#wpmseo_general').hide();
                        $('#wpmseo_social').show();
                        $('#wpmseo_gsc_keywords').hide();
                    });

                    $('#elementor-wpms-general').on('click', function (e) {
                        $('#elementor-wpms-general').addClass('active');
                        $('#elementor-wpms-social').removeClass('active');
                        $('#wpmseo_general').show();
                        $('#wpmseo_social').hide();
                        $('#wpmseo_gsc_keywords').show();
                    });

                    $('.wpmseo_image_upload_button').unbind('click').on('click', function () {
                        wpmseo_target_id = $(this).attr('id').replace(/_button$/, '');
                        if (wpmseo_uploader) {
                            wpmseo_uploader.open();
                            return;
                        }
                        wpmseo_uploader = wp.media.frames.file_frame = wp.media({
                            title: wpmseoMediaL10n.choose_image,
                            button: {text: wpmseoMediaL10n.choose_image},
                            multiple: false
                        });


                        wpmseo_uploader.on('select', function () {
                            var attachment = wpmseo_uploader.state().get('selection').first().toJSON();
                            console.log(wpmseo_target_id);
                            jQuery('#' + wpmseo_target_id).val(attachment.url);
                            wpmseo_uploader.close();
                        });

                        wpmseo_uploader.open();
                    });

                    $(document).on('click', '.et-fb-button--publish, .et-fb-button--save-draft', function () {
                        const postID = parseInt(jQuery('.metaseo-progress-bar').data('post_id'));
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
                    });

                } else {
                    that.addClass(classname);
                }
            }

            $settings_bar_root.on('click', function () {
                addClassTo($seo_et_ignore_iframe, 'hidden');
            });

            $(document).on('click', '.et-fb-page-settings-bar__toggle-button', function () {
                if ($(this).hasClass('et-fb-button--active')) {
                    $settings_bar_root.appendTo($(this).siblings('.et-fb-page-settings-bar__column--main'));
                } else {
                    $(this).siblings('.et-fb-page-settings-bar__column--main').after($settings_bar_root);
                }
            })
        }
        window.addEventListener("message", (function (t) {
            if ("et_builder_api_ready_wpms" === t.data.etBuilderEvent) {
                var $seo_et_ignore_iframe = $(window.document).find('#et-fb-app-frame').contents().find('#seo_et_ignore_iframe'),
                    $settings_bar_root = $('#wpseo-settings-bar-root'),
                    $settings_bar = $('.et-fb-page-settings-bar');

                wpseoEditor($seo_et_ignore_iframe, $settings_bar_root, $settings_bar);
            }
        }));

        class wpmsSpecificKeywords {
            constructor() {
                this.title = null;
                this.content = null;
                this.tagsElement = $('#metaseo_wpmseo_specific_keywords');
                this.metaTitleElement = $('#metaseo_wpmseo_title');
                this.metaDescElement = $('#metaseo_wpmseo_desc');
                this.listTags = [];
                this.resultAnalytics = null;
            }

            init = () => {
                var mpageurl = '', title = '', mcontent = '', current_editor = '';
                var meta_title, meta_desc, seo_keywords;
                [this.content, this.title, mpageurl, seo_keywords, meta_desc, meta_title, current_editor] = get_content_and_title(1);
                // Get editor title
                // if (typeof elmTitle !== 'undefined') {
                //     this.title = elmTitle;
                // }
                // Get editor content
                // if (typeof elmContent !== 'undefined') {
                //     this.content = elmContent;
                // }

                this.bindEvents();
                this.tagsElement.on('itemAdded itemRemoved', this.bindEvents);
                this.metaTitleElement.on('keyup', this.bindEvents);
                this.metaDescElement.on('keyup', this.bindEvents);
            }

            bindEvents = () => {
                if (this.tagsElement.siblings('.wpms-bootstrap-tagsinput').length > 1) {
                    this.tagsElement.siblings('.wpms-bootstrap-tagsinput').remove();
                }
                this.listTags = this.tagsElement.tagsinput('items');
                if (typeof this.listTags !== 'undefined' && typeof this.listTags.itemsArray !== 'undefined' && this.listTags.itemsArray !== '') {
                    this.listTags = this.listTags.itemsArray;
                }

                this.analytics();
            }

            analytics = () => {
                this.resultAnalytics = {
                    keyInTitle: this.collectKeywordsInTitle(),
                    keyInContent: this.collectKeywordsInContent(),
                    keyInContentHeading: this.collectKeywordsInHeading(),
                    keyInMetaTitle: this.collectKeywordsInMetaTitle(),
                    keyInMetaDescription: this.collectKeywordsInMetaDesc()
                }

                if (this.resultAnalytics !== null) {
                    let discovered = false;
                    Object.entries(this.resultAnalytics).forEach(entry => {
                        const [key, value] = entry;
                        if (value) discovered = true;
                        this.changeAnalyticsInfo(key, value);
                    });

                    this.editAnalyticsInfo(discovered);
                }
            }

            editAnalyticsInfo = (discovered) => {
                const seo_keywords = $("input#metaseo_wpmseo_specific_keywords").val();
                if (seo_keywords === '') {
                    $('div.metaseo_analysis[data-title="seokeyword"]').hide();
                    $('.seokeyword-information').hide();
                    $('input.wpms_analysis_hidden[name="wpms[seokeyword]"]').val(0);
                    this.reDrawInactive(7);
                    return false;
                } else {
                    $('div.metaseo_analysis[data-title="seokeyword"]').show();
                    $('.seokeyword-information').show();
                }

                if (!discovered) {
                    $('div.metaseo_analysis[data-title="seokeyword"]').find('i').removeClass('icons-mboxdone').addClass('icons-mboxwarning').html('error_outline');
                    $('input.wpms_analysis_hidden[name="wpms[seokeyword]"]').val(0);
                } else {
                    $('div.metaseo_analysis[data-title="seokeyword"]').find('i').removeClass('icons-mboxwarning').addClass('icons-mboxdone').html('done');
                    $('input.wpms_analysis_hidden[name="wpms[seokeyword]"]').val(1);
                }

                this.reDrawInactive(8);
            }

            reDrawInactive = (totalItems) => {
                const analyticItems = $('.panel-left .wpms_analysis_hidden');
                let mcheck = 0;

                for (let i = 0; i < analyticItems.length; i++) {
                    if ($(analyticItems[i]).val() == 1) {
                        mcheck++;
                    }
                }

                const circliful = Math.ceil((mcheck * 100) / totalItems);

                $('#wpmetaseo_seo_keywords_result').val(circliful);
                $('.metaseo-progress-bar').circleProgress('value', circliful / 100).on('circle-animation-progress', function (event, progress) {
                    $(this).find('strong').html(circliful + '<i>%</i>');
                });
            }

            // change material icon
            changeAnalyticsInfo = (key, value) => {
                if (value) {
                    $('div.metaseo_analysis[data-title="' + key.toLowerCase() + '"]').find('i').removeClass('icons-mboxwarning').addClass('icons-mboxdone').html('done');
                    $('input.wpms_analysis_hidden[name="wpms[' + key.toLowerCase() + ']"]').val(1);
                } else {
                    $('div.metaseo_analysis[data-title="' + key.toLowerCase() + '"]').find('i').removeClass('icons-mboxdone').addClass('icons-mboxwarning').html('error_outline');
                    $('input.wpms_analysis_hidden[name="wpms[' + key.toLowerCase() + ']"]').val(0);
                }
            }

            collectKeywordsInTitle = () => {
                let title = {text: this.title,};
                let isContain = false;
                //alert(this.listTags);
                if (this.listTags.length && title.text.length) {
                    this.listTags.forEach(function (item) {
                        if (title.text.toLowerCase().includes(item.toLowerCase().trim())) {
                            isContain = true;
                            return isContain;
                        }
                    });
                }

                return isContain;
            }

            collectKeywordsInContent = () => {
                let content = {text: this.content};
                let isContain = false;
                if (this.listTags.length && content.text.length) {
                    this.listTags.forEach(function (item, index) {
                        if (content.text.toLowerCase().includes(item.toLowerCase().trim())) {
                            isContain = true;
                            return isContain;
                        }
                    });
                }

                return isContain;
            }

            collectKeywordsInHeading = () => {
                let content = {text: this.content};
                let isContain = false;
                if (this.listTags.length && content.text.length) {
                    this.listTags.forEach(function (item, index) {
                        const regex = RegExp("<h[2-6][^>]*>.*" + item.toLowerCase().trim() + ".*</h[2-6]>", "gi");
                        if (content.text.toLowerCase().match(regex) != null) {
                            isContain = true;
                            return isContain;
                        }
                    });
                }

                return isContain;
            }

            collectKeywordsInMetaTitle = () => {
                let metaTitle = {text: this.replaceVariables(this.metaTitleElement.val())};
                let isContain = false;
                if (this.listTags.length && metaTitle.text.length) {
                    this.listTags.forEach(function (item, index) {
                        if (metaTitle.text.toLowerCase().includes(item.toLowerCase().trim())) {
                            isContain = true;
                            return isContain;
                        }
                    });
                }

                return isContain;
            }

            collectKeywordsInMetaDesc = () => {
                let metaDesc = {text: this.replaceVariables(this.metaDescElement.val())};
                let isContain = false;
                if (this.listTags.length && metaDesc.text.length) {
                    this.listTags.forEach(function (item, index) {
                        if (metaDesc.text.toLowerCase().includes(item.toLowerCase().trim())) {
                            isContain = true;
                            return isContain;
                        }
                    });
                }

                return isContain;
            }

            replaceVariables = (str) => {
                if (typeof str === 'undefined') {
                    return;
                }

                if (this.title) {
                    str = str.replace(/%title%/g, this.title.replace(/(<([^>]+)>)/ig, ''));
                }

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
                let excerpt = '';
                if (jQuery('#excerpt').length) {
                    excerpt = msClean(jQuery('#excerpt').val().replace(/(<([^>]+)>)/ig, ''));
                    str = str.replace(/%excerpt_only%/g, excerpt);
                }

                if ('' === excerpt && jQuery('#content').length) {
                    excerpt = jQuery('#content').val().replace(/(<([^>]+)>)/ig, '').substring(0, wpmseoMetaboxL10n.wpmseo_meta_desc_length - 1);
                }
                str = str.replace(/%excerpt%/g, excerpt);

                // parent page
                if (jQuery('#parent_id').length && jQuery('#parent_id option:selected').text() !== wpmseoMetaboxL10n.no_parent_text) {
                    str = str.replace(/%parent_title%/g, jQuery('#parent_id option:selected').text());
                }

                // remove double separators
                const esc_sep = wpmseoMetaboxL10n.sep.replace(/[\-\[\]\/\{}\(\)\*\+\?\.\\\^\$\|]/g, '\\$&');
                const pattern = new RegExp(esc_sep + ' ' + esc_sep, 'g');
                str = str.replace(pattern, wpmseoMetaboxL10n.sep);

                return str;
            }
        }

        $(document).ajaxComplete(function (event, request, settings) {
            if (typeof settings.data !== "undefined" && settings.data.includes('action=wpms&task=reload_analysis')) {
                new wpmsSpecificKeywords().init();
            }
        });
    }
    $(window).on('et_builder_api_ready', function (event) {
        window.parent.postMessage({etBuilderEvent: "et_builder_api_ready_wpms"}, "*");
    });
})