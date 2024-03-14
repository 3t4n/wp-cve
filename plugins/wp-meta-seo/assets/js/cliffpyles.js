jQuery(document).ready(function ($) {
    var mcheck = 0;
    if (typeof wpmscliffpyles.use_validate !== "undefined" && parseInt(wpmscliffpyles.use_validate) === 1) {
        wpms_validate_analysis();
    }

    function reload_analysis(first_load) {
        var mpageurl = '', title = '', mcontent = '', current_editor = '';
        var meta_title = $('#metaseo_wpmseo_title').val();
        var meta_desc = $('#metaseo_wpmseo_desc').val();
        var seo_keywords = $("input#metaseo_wpmseo_specific_keywords").val();
        mpageurl = $('.editor-post-link .components-text-control__input').val();

        if (typeof mpageurl === 'undefined') {
            mpageurl = $('.edit-post-post-link__link').attr('href');
        }

        if (typeof wp.blocks !== "undefined") {
            if (typeof mpageurl === 'undefined') {
                mpageurl = $('#wp-admin-bar-view').find('a').attr('href');
            }
            current_editor = 'gutenberg';
            if (parseInt(first_load) === 1) {
                title = wpmscliffpyles.post_title;
                mcontent = wpmscliffpyles.post_content;
            } else {
                // Core editor
                if (typeof wp.data !== "undefined" && typeof wp.data.select('core/editor') !== "undefined" && wp.data.select('core/editor') !== null) {
                    title = $('.editor-post-title__input').text();
                    mcontent = wp.data.select('core/editor').getEditedPostContent();
                } else if(typeof tinyMCE !== 'undefined') {
                    // WooCommerce product
                    title = $('#title').val();
                    if (tinyMCE.get('content') !== null) {
                        mcontent = tinyMCE.editors.content.getContent();
                        if (mcontent === '') mcontent = $('#content').val();
                    } else {
                        mcontent = $('#content').val();
                    }
                }
            }
        } else {
            // undefined wp.blocks
            if (typeof wpmscliffpyles.post_type !== 'undefined' && wpmscliffpyles.post_type === 'attachment') {
                // On media attachment page
                if (typeof mpageurl === 'undefined') {
                    mpageurl = $('#sample-permalink').text().trim();
                }

                if (parseInt(first_load) === 1) {
                    title = wpmscliffpyles.post_title;
                    mcontent = wpmscliffpyles.post_content;
                } else if(typeof wp.data !== "undefined") {
                    title = $('#title').val();
                    mcontent = $('#attachment_content').val();
                }
            } else {
                // Something went wrong, use default
                if (typeof mpageurl === 'undefined') {
                    mpageurl = $('#editable-post-name-full').text();
                }
                title = $('#title').val();
                if (typeof tinyMCE !== 'undefined' && tinyMCE.get('content') !== null) {
                    mcontent = tinyMCE.editors.content.getContent();
                    if (mcontent === '') mcontent = $('#content').val();
                } else {
                    mcontent = $('#content').val();
                }
            }
        }
        // In bad case, set empty value to avoid errors
        if (typeof mcontent === 'undefined') mcontent = '';
        if (typeof title === 'undefined') title = '';
        if (typeof mpageurl === 'undefined') mpageurl = '';

        $('.wpmseotab .spinner').css({'visibility': ' inherit'}).show();
        $('.metaseo_right .panel-left, .metaseo_right .panel-right').html('');
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
                    $('.wpmseotab .spinner').hide();
                    $('.metaseo_right .panel-left').html(res.output);
                    $('.metaseo_right .panel-right').html(res.right_output);
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
    $('#reload_analysis').on('click', function () {
        reload_analysis(0);
    });

    function drawInactive(circliful) {
        $('.metaseo-progress-bar').circleProgress({
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
            var seo_keywords = $("input#metaseo_wpmseo_specific_keywords").val();
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
});