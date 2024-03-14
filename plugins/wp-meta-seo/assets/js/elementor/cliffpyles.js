var elementorTitle = '';
var elementorContent = '';
jQuery(document).ready(function ($) {
    var mcheck = 0;
    if (typeof wpmscliffpyles !== 'undefined' && typeof wpmscliffpyles.use_validate !== "undefined" && parseInt(wpmscliffpyles.use_validate) === 1) {
        wpms_validate_analysis();
    }

    function reload_analysis(first_load) {
        var mpageurl = '', current_editor = '';
        var meta_title = $('#metaseo_wpmseo_title').val();
        var meta_desc = $('#metaseo_wpmseo_desc').val();
        var seo_keywords = $("input#metaseo_wpmseo_specific_keywords").val();

        mpageurl = location.protocol + $('#wpmseosnippet').find('a').text();
        elementorTitle = document.title;
        if (typeof elementorTitle !== 'undefined' && elementorTitle.length) {
            elementorTitle = elementorTitle.replace("Elementor |", "");
            elementorTitle = elementorTitle.trim();
        }

        // get content html from elementor frontend
        elementorContent = elementor.$previewElementorEl.html();

        $('#wpms-elementor-spinner-gif').css({'display': 'flex'});
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
                    'title': elementorTitle,
                    'meta_title': meta_title,
                    'mpageurl': mpageurl,
                    'meta_desc': meta_desc,
                    'content': elementorContent,
                    'seo_keywords': seo_keywords
                },
                'wpms_nonce': wpmscliffpyles.wpms_nonce
            },
            success: function (res) {
                if (res) {
                    $('#wpms-elementor-spinner-gif').css({'display': 'none'});
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
    $(document).on('click', '#wpms-onelementor-tab', function (e) {
        reload_analysis(1);
    })
     .on('click', '#reload_analysis', function (e) {
         reload_analysis(0); // reload when click reload button
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
