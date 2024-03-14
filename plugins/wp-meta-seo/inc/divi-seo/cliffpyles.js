jQuery(document).ready(function ($) {
    console.log($(window.document).find('#wpseo-settings-bar-root'));
    if ($(window.document).find('#et-fb-app-frame').length < 1) {
        $parent_window = $(window.document);
        console.log($parent_window, $(window.document).find('#et-fb-app-frame'));
        var mcheck = 0;
        if (typeof wpmscliffpyles.use_validate !== "undefined" && parseInt(wpmscliffpyles.use_validate) === 1) {
            wpms_validate_analysis();
        }

        function reload_analysis(first_load) {
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
                    // Core editor
                    if (typeof wp.data !== "undefined" && typeof wp.data.select('core/editor') !== "undefined" && wp.data.select('core/editor') !== null) {
                        title = $parent_window.find('.editor-post-title__input').text();
                        mcontent = wp.data.select('core/editor').getEditedPostContent();
                    } else if (typeof tinyMCE !== 'undefined') {
                        // WooCommerce product
                        title = $parent_window.find('#title').val();
                        if (tinyMCE.get('content') !== null) {
                            mcontent = tinyMCE.editors.content.getContent();
                            if (mcontent === '') mcontent = $parent_window.find('#content').val();
                        } else {
                            mcontent = $parent_window.find('#content').val();
                        }
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

            // In bad case, set empty value to avoid errors
            if (typeof mcontent === 'undefined') mcontent = '';
            if (typeof title === 'undefined') title = '';
            if (typeof mpageurl === 'undefined') mpageurl = '';

            $parent_window.find('.wpmseotab .spinner').css({'visibility': ' inherit'}).show();
            $parent_window.find('.metaseo_right .panel-left, .metaseo_right .panel-right').html('');
            console.log(current_editor, meta_title, meta_desc, '123', seo_keywords, mpageurl, '123', mcontent, title);
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
                    console.log(res)
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

        // init load analysis
        reload_analysis(1);

        // reload analysis
        console.log($parent_window.find('#reload_analysis'));
        $parent_window.find('#reload_analysis').on('click', function () {
            reload_analysis(0);
        });

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
                        console.log(res);
                        if (res !== false) {
                            drawInactive(circliful);
                        }
                    }
                });

            });
        }
    }
});