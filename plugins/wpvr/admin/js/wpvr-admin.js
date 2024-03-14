(function ($) {
    'use strict';
    var j = 1;
    var color = '#00b4ff';
    var scene_parent = '';
    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */


    $(document).ready(function () {
        $(".vrowl-carousel").owlCarousel({
            margin: 10,
            autoWidth: true,
            nav: true
        });

        var primary_markng = $('#scene-1').find('input.sceneid').val();
        $('.owl' + primary_markng).parents('.owl-item').addClass('marked');

        $('.meta-box-sortables').sortable({
            items: '#wpvr_item_builder_box',
            disabled: true
        });

        $('.postbox .hndle').css('cursor', 'pointer');
    });

    $(document).on("change", "input.vr-switcher-check", function (event) {
        if (this.checked) {
            $(this).val('on');
        } else {
            $(this).val('off');
        }
    });


    var specificeSceneID = ''
    var newScene = ''
    $(document).on("click", ".scene-nav ul li span", function (event) {
        $('.owl-item').removeClass('marked');
        var target = $(this).attr("data-index");
        if(target == undefined){
            var currentLi = $(this).parent('li');
            var previousLi = currentLi.prev();
            if (previousLi.length > 0) {
                newScene = previousLi.find('span').attr('data-index');
            }
        }
        var data = $('#scene-' + target).find('input.sceneid').val();
        if (data) {
            specificeSceneID = data;
            $('.panolenspreview').trigger('click')
            $('.owl' + data).parents('.owl-item').addClass('marked');
        }
    });

    jQuery(document).ready(function ($) {

        j = $('#scene-1').find('.hotspot-nav li').eq(-2).find('span').attr('data-index');
        var ajaxurl = wpvr_obj.ajaxurl;
        $('.panolenspreview').on('click', function (e) {
            e.preventDefault();
            $('.wpvr-loading').show();
            var videourl = $("input[name='video-attachment-url']").val();
            var vidautoplay = $("input[name='playvideo']:checked").val();
            var vidcontrol = $("input[name='playcontrol']:checked").val();

            var panovideo = $("input[name='panovideo']:checked").val();
            
            var postid = $("#post_ID").val();
            var autoload = $("input[name='autoload']").is(':checked') ? 'on' : 'off';
            var compass = $("input[name='compass']:checked").val();
            var control = $("input[name='controls']").is(':checked') ? 'on' : 'off';
            var rotation = $("input[name='autorotation']").is(':checked') ? 'on' : 'off';
            var defaultscene = $("input[name='default-scene-id']").val();
            var preview = $("input[name='preview-attachment-url']").val();
            var scenefadeduration = $("input[name='scene-fade-duration']").val();

            var autorotation = $("input[name='auto-rotation']").val();
            var autorotationinactivedelay = $("input[name='auto-rotation-inactive-delay']").val();
            var autorotationstopdelay = $("input[name='auto-rotation-stop-delay']").val();

            var panodata = $('.scene-setup').repeaterVal();
            var panolist = JSON.stringify(panodata);
            var previewtext = $('.previewtext').val();

            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "wpvr_preview",
                    nonce : wpvr_obj.ajax_nonce,
                    postid: postid,
                    compass: compass,
                    control: control,
                    autoload: autoload,
                    panodata: panolist,
                    defaultscene: defaultscene,
                    rotation: rotation,
                    autorotation: autorotation,
                    autorotationinactivedelay: autorotationinactivedelay,
                    autorotationstopdelay: autorotationstopdelay,
                    preview: preview,
                    scenefadeduration: scenefadeduration,
                    panovideo: panovideo,
                    videourl: videourl,
                    vidautoplay: vidautoplay,
                    vidcontrol: vidcontrol
                },

                success: function (response) {
                    $('.wpvr-loading').hide();
                    if (response.success == true) {
                        if(response.data[2] == 'off'){
                            $('#error_occured').hide();
                            $('#error_occuredpub').hide();
                            $('#' + response.data[0]["panoid"]).empty();
                            var scenes = response.data[1];
    
                            if (scenes) {
                                $.each(scenes.scenes, function (i) {
                                    $.each(scenes.scenes[i]['hotSpots'], function (key, val) {
                                        if (val["clickHandlerArgs"] != "") {
                                            val["clickHandlerFunc"] = wpvrhotspot;
                                        }
                                        if (val["createTooltipArgs"] != "") {
                                            val["createTooltipFunc"] = wpvrtooltip;
                                        }
                                    });
                                });
                            }
                            if (scenes) {
                                $('.scene-gallery').trigger('destroy.owl.carousel');
                                $('.scene-gallery').empty();
                                $.each(scenes.scenes, function (key, val) {
                                    $('.scene-gallery').append('<ul style="width:150px;"><li class="owlscene owl' + key + '">' + key + '</li><li title="Double click to view scene"><img loading="lazy"  class="scctrl" id="' + key + '_gallery" src="' + val.panorama + '"></li></ul>');
                                });
                                $(".vrowl-carousel").owlCarousel({
                                    margin: 10,
                                    autoWidth: true,
                                });
                                var active_owl_target = $('#wpvr_active_scenes').val();
                                var get_owl_target = $('#scene-' + active_owl_target).find('input.sceneid').val();
                                $('.owl' + get_owl_target).parents('.owl-item').addClass('marked');
                            }
                            var panoshow = pannellum.viewer(response.data[0]["panoid"], scenes);
                            if (scenes.autoRotate) {
                                panoshow.on('load', function () {
                                    setTimeout(function () {
                                        panoshow.startAutoRotate(scenes.autoRotate, 0);
                                    }, 3000);
                                });
                                panoshow.on('scenechange', function () {
                                    setTimeout(function () {
                                        panoshow.startAutoRotate(scenes.autoRotate, 0);
                                    }, 3000);
                                });
                            }
                            var touchtime = 0;
                            if (scenes) {
                                $.each(scenes.scenes, function (key, val) {
                                    document.getElementById('' + key + '_gallery').addEventListener('click', function (e) {
                                        if (touchtime == 0) {
                                            touchtime = new Date().getTime();
                                        } else {
                                            if (((new Date().getTime()) - touchtime) < 800) {
                                                panoshow.loadScene(key);
                                                touchtime = 0;
                                            } else {
                                                touchtime = new Date().getTime();
                                            }
                                        }
                                    });
                                });
                            }
                            if( newScene){
                                var data = $('#scene-' + newScene).find('input.sceneid').val();
                                if(data){
                                    panoshow.loadScene(data);
                                }
                                newScene = ''
                            }
                            if(specificeSceneID){
                                setTimeout(() => {
                                    panoshow.loadScene(specificeSceneID);
                                }, 500);
                            }
                            $('html, body').animate({
                                scrollTop: $("#wpvr_item_builder__box").offset().top
                            }, 500);
                            //set preview text
                            if ("" != previewtext) {
                                $('.pnlm-load-button p').text(previewtext);
                            }
                        }else{
                            $('#' + response.data["panoid"]).empty();
                            $('#' + response.data["panoid"]).html(response.data["panodata"]);
                            if (response.data['vidtype'] == 'selfhost') {
                                videojs(response.data["vidid"], {
                                    plugins: {
                                        pannellum: {}
                                    }
                                });
                            }
                            $('html, body').animate({
                                scrollTop: $("#wpvr_item_builder__box").offset().top
                            }, 500);
                        }
                        
                    } else {
                        $('#error_occured').show();
                        $('#error_occured .pano-error-message').html(response.data);
                        $('#error_occuredpub').show();
                        $('#error_occuredpub').html(response.data);
                        $('body').addClass('error-overlay');
                        $('html, body').animate({
                            scrollTop: $("#error_occured").offset().top
                        }, 500);
                    }

                }
            });
        });
    });

    //-- Set Cookie--//

    function setCookie(cName, cValue, expDays) {
        let date = new Date();
        date.setTime(date.getTime() + (expDays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = cName + "=" + cValue + "; " + expires + "; path=/";
    }
    // Get a cookie

    function getCookie(cName) {
        const name = cName + "=";
        const cDecoded = decodeURIComponent(document.cookie); //to be careful
        const cArr = cDecoded .split('; ');
        let res;
        cArr.forEach(val => {
            if (val.indexOf(name) === 0) res = val.substring(name.length);
        })
        return res;
    }

    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    function findValueByName(name,dataArray) {
        for (var i = 0; i < dataArray.length; i++) {
            if (dataArray[i].name === name) {
                return dataArray[i].value;
            }
        }
        // Return a default value or handle the case when the name is not found
        return '';
    }

    jQuery(document).ready(function ($) {
        var getRedirectUrl = getCookie("redirectUrl");
        var getCookiePostID = getCookie("postID");

        const urlParams = getParameterByName("active_tab");
        const action = wpvr_global_obj.url_info.param.action;
        let getPostID = wpvr_global_obj.url_info.param.post;

        if(getCookiePostID == getPostID){
            if(urlParams == '' || urlParams == undefined){
                if (action == 'edit' && getRedirectUrl != undefined){
                    window.location.replace(getRedirectUrl)
                    setCookie('postID', '', 1);
                    setCookie('redirectUrl', '', 1);
                }
            }
        }
        var flag_ok = false;
        $('#publish').on('click', function (e) {
            e.preventDefault();
            $('#publish').prop('disabled', true);

            $('.panolenspreview').trigger('click')
            var data = $('form#post').serializeArray();
            var post_status =  findValueByName('post_status',data)
            var hidden_post_status =  findValueByName('hidden_post_status',data)
            var hidden_post_password =  findValueByName('hidden_post_password',data)
            var hidden_post_visibility =  findValueByName('hidden_post_visibility',data)
            var visibility =  findValueByName('visibility',data)
            var post_password =  findValueByName('post_password',data)
            var post_title =  findValueByName('post_title',data)
            var x = $(this).val();
            // if (!flag_ok) {
                $('.wpvr-loading').show();
                var postid = $("#post_ID").val();
                var panovideo = $("input[name='panovideo']:checked").val();
                var videourl = $("input[name='video-attachment-url']").val();
                var autoload = $("input[name='autoload']").is(':checked') ? 'on' : 'off';
                var control = $("input[name='controls']").is(':checked') ? 'on' : 'off';
                var compass = $("input[name='compass']:checked").val();
                var defaultscene = $("input[name='default-scene-id']").val();
                var preview = $("input[name='preview-attachment-url']").val();
                var rotation = $("input[name='autorotation']").is(':checked') ? 'on' : 'off';  // Auto Rotation checkbox value
                var autorotation = $("input[name='auto-rotation']").val();
                var autorotationinactivedelay = $("input[name='auto-rotation-inactive-delay']").val();
                var autorotationstopdelay = $("input[name='auto-rotation-stop-delay']").val();

                var scenefadeduration = $("input[name='scene-fade-duration']").val();
                var previewtext = $('.previewtext').val();
                if ($('.scene-setup')[0]) {
                    var panodata = $('.scene-setup').repeaterVal();

                    var panolist = JSON.stringify(panodata);
                } else {
                    var panodata = '';
                    var panolist = '';
                }

                jQuery.ajax({

                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "wpvr_save",
                        nonce : wpvr_obj.ajax_nonce,
                        postid: postid,
                        panovideo: panovideo,
                        videourl: videourl,
                        control: control,
                        compass: compass,
                        autoload: autoload,
                        panodata: panolist,
                        defaultscene: defaultscene,
                        preview: preview,
                        rotation: rotation,             // Auto Rotation checkbox value
                        autorotation: autorotation,     // Rotatin speed
                        autorotationinactivedelay: autorotationinactivedelay,
                        autorotationstopdelay: autorotationstopdelay,
                        scenefadeduration: scenefadeduration,
                        previewtext: previewtext,

                        hidden_post_status: hidden_post_status,
                        post_status: post_status,
                        hidden_post_password: hidden_post_password,
                        hidden_post_visibility: hidden_post_visibility,
                        visibility: visibility,
                        post_password: post_password,
                        post_value: $('#publish').val(),
                        post_title: post_title,
                    },

                    success: function (response) {
                        $('.wpvr-loading').hide();
                        $('#publish').prop('disabled', false);
                        if (response.success == false) {
                            $('#error_occured').show();
                            $('#error_occured .pano-error-message').html(response.data);
                            $('#error_occuredpub').show();
                            $('#error_occuredpub').html(response.data);

                            $('body').addClass('error-overlay');
                            $('html, body').animate({
                                scrollTop: $("#error_occured").offset().top
                            }, 500);
                        } else {
                            flag_ok = true;
                            var url_info = wpvr_global_obj.url_info
                            var oldUrl = '';
                            let params = (new URL(document.location)).searchParams;
                            let active_tab = params.get('active_tab');
                            oldUrl = url_info.admin_url+"post.php?"+"post="+postid+"&action=edit"+"&active_tab="+active_tab;
                            if ( 'add' == url_info.screen ){
                                setCookie('redirectUrl', oldUrl, 1);
                                setCookie('postID', postid, 1);
                            }else{
                                setCookie('postID', postid, 1);

                                setCookie('redirectUrl', oldUrl, 1);
                            }
                            var publishedOptionSelected = $("#post_status option[value='publish']").length > 0;
                            if (!publishedOptionSelected) {
                                $("#post_status").append('<option selected="selected" value="publish">Published</option>')
                            }
                            var statusText = $("#post_status").val()
                            $("#post-status-display").text(statusText)
                            if(response.data.post_status == 'draft'){
                                $('#publish').val('Publish');
                            }else{
                                $('#publish').val('Update');
                            }
                            window.history.replaceState(null, '', url_info.admin_url+"post.php?"+"post="+postid+"&action=edit");
                        }
                    }
                });
            // }
        });
    });

    jQuery(document).ready(function ($) {
        $("body, .pano-error-close-btn").on("click", function (e) {
            $("#error_occured").hide();
            $('body').removeClass('error-overlay');
        });

        $(".panolenspreview, #error_occured").on("click", function (e) {
            e.stopPropagation();
        });
    });
    jQuery(document).ready(function ($) {
        var previewtext = $('.previewtext').val();
        if ("" != previewtext) {
            $('.pnlm-load-button p').text(previewtext);
        }
    });

    jQuery(document).ready(function ($) {

        var flag_ok = false;
        $('#save-post').on('click', function (e) {
            var x = $(this).val();
            if (!flag_ok) {
                e.preventDefault();
                $('.wpvr-loading').show();
                var postid = $("#post_ID").val();
                var panovideo = $("input[name='panovideo']:checked").val();
                var videourl = $("input[name='video-attachment-url']").val();
                var autoload = $("input[name='autoload']").val();
                var control = $("input[name='controls']").val();
                var compass = $("input[name='compass']:checked").val();
                var defaultscene = $("input[name='default-scene-id']").val();
                var preview = $("input[name='preview-attachment-url']").val();
                var rotation = $("input[name='autorotation']").val();
                var autorotation = $("input[name='auto-rotation']").val();
                var autorotationinactivedelay = $("input[name='auto-rotation-inactive-delay']").val();
                var autorotationstopdelay = $("input[name='auto-rotation-stop-delay']").val();

                var scenefadeduration = $("input[name='scene-fade-duration']").val();

                if ($('.scene-setup')[0]) {
                    var panodata = $('.scene-setup').repeaterVal();
                    var panolist = JSON.stringify(panodata);
                } else {
                    var panodata = '';
                    var panolist = '';
                }

                jQuery.ajax({

                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "wpvr_save",
                        nonce : wpvr_obj.ajax_nonce,
                        postid: postid,
                        panovideo: panovideo,
                        videourl: videourl,
                        control: control,
                        compass: compass,
                        autoload: autoload,
                        panodata: panolist,
                        defaultscene: defaultscene,
                        preview: preview,
                        rotation: rotation,
                        autorotation: autorotation,
                        autorotationinactivedelay: autorotationinactivedelay,
                        autorotationstopdelay: autorotationstopdelay,
                        scenefadeduration: scenefadeduration,
                    },

                    success: function (response) {
                        $('.wpvr-loading').hide();
                        if (response.success == false) {
                            $('#error_occured').show();
                            $('#error_occured').html(response.data);
                            $('#error_occuredpub').show();
                            $('#error_occuredpub').html(response.data);

                            $('body').addClass('error-overlay');
                            $('html, body').animate({
                                scrollTop: $("#error_occured").offset().top
                            }, 500);
                        } else {
                            flag_ok = true;
                            $('#save-post').trigger('click');
                        }
                    }
                });
            }
        });
    });
    // $("#custom-ifram").css({
    //     "height": "auto",
    //     "width": "auto",
    //     "max-width": "60%",
    //     "max-height": "80%",
    //     "text-align": "center",
    //     "padding": "8px",
    //     "overflow": "auto",
    // });






    function wpvrhotspot(hotSpotDiv, args) {
        var argst = args.replace(/\\/g, '');
        $("#custom-ifram").html(argst);
        $("#custom-ifram").fadeToggle();
        $(".iframe-wrapper").toggleClass("show-modal");

    }

    function wpvrtooltip(hotSpotDiv, args) {
        hotSpotDiv.classList.add('custom-tooltip');
        var span = document.createElement('span');
        args = args.replace(/\\/g, "");
        span.innerHTML = args;
        hotSpotDiv.appendChild(span);
        span.style.marginLeft = -(span.scrollWidth - hotSpotDiv.offsetWidth) / 2 + 'px';
        span.style.marginTop = -span.scrollHeight - 12 + 'px';
    }

    jQuery(document).ready(function ($) {
        $("#cross").on("click", function (e) {
            e.preventDefault();
            $("#custom-ifram").fadeOut();
            $(".iframe-wrapper").removeClass("show-modal");
            $('iframe').attr('src', $('iframe').attr('src'));
        });
    });

    jQuery(document).ready(function ($) {

        var i = $('.scene-nav li').eq(-2).find('span').attr('data-index');
        i = parseInt(i);

        $('.scene-setup').repeater({

            defaultValues: {
                'scene-type': 'equirectangular',
                'dscene': 'off',
                'ptyscene': 'off',
                'cvgscene': 'off',
                'chgscene': 'off',
                'czscene': 'off',
            },
            show: function () {
                $('textarea[name*=hotspot-content]').summernote({
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview', 'help']],
                    ],
                    callbacks: {
                        onKeyup: function(e) {
                            var getHotspotContent = $(this).val();
                            var getParent        =  $(this).parent();
                            var getMainParent    = getParent.parent()
                            var getClickContent  =  getMainParent.find('.hotspot-url')
                            if(getHotspotContent.length > 0){
                                getClickContent.find('input[name*=hotspot-url').val('')
                                getClickContent.find('input[name*=hotspot-url').attr('placeholder','You can set either a URL or an On-click content')
                                getClickContent.find('input[name*=hotspot-url]').attr("disabled",true)
                            }else{
                                getClickContent.find('input[name*=hotspot-url]').attr("disabled",false)
                            }
                        }
                    }
                });
                $('textarea[name*=hotspot-hover]').summernote({
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview', 'help']],
                    ],
                });
                if ($(this).parents(".scene-setup").attr("data-limit").length > 0) {

                    if ($(this).parents(".scene-setup").find("div[data-repeater-item]:not(.hotspot-setup div[data-repeater-item])").length <= $(this).parents(".scene-setup").attr("data-limit")) {

                        $(this).slideDown();
                        $(this).removeClass('active');

                        i = i + 1;
                        var scene = 'scene-' + i;

                        $(this).find(".title .scene-num").html(i);

                        $('<li><span data-index="' + i + '" data-href="#' + scene + '"><i class="fa fa-image"></i></span></li>').insertBefore($(this).parent().parent('.scene-setup').find('.scene-nav ul li:last-child'));

                        $(this).attr('id', scene);
                        changehotspotid(i);
                        $(this).siblings('.active').removeClass('active');
                        $(this).addClass('active');
                        $(this).find(".sceneid").val(generateRandomString(8));
                        setTimeout(changeicon, 1000);
                    } else {
                        $('.pano-alert .pano-error-message').html('<span class="pano-error-title">Limit Reached</span><p> You can add up to 5 scenes on each tour in the Free version. Upgrade to Pro to create tours with unlimited scenes.</p>');
                        $('body').addClass('error-overlay2');
                        $('.pano-alert').addClass('pano-default-warning').show();
                        $(this).remove();
                    }
                } else {
                    jQuery(this).slideDown();
                    $(this).removeClass('active');

                    i = i + 1;
                    var scene = 'scene-' + i;
                    $(this).find(".title .scene-num").html(i);
                    $('<li><span data-index="' + i + '" data-href="#' + scene + '"><i class="fa fa-image"></i></span></li>').insertBefore($(this).parent().parent('.scene-setup').find('.scene-nav ul li:last-child'));
                    $(this).attr('id', scene);
                    changehotspotid(i);
                }

                $(this).hide();

                //tab active setup
                $('#wpvr_active_scenes').val(i);
                $('#wpvr_active_hotspot').val(1);
            },
            hide: function (deleteElement) {
                var hide_id = $(this).attr("id");
                var _hide_id = hide_id.split('-').pop();
                hide_id = "#" + hide_id;

                var current = $(this).attr('id');
                var fchild = $('.single-scene:nth-child(2)').attr('id');
                // var fchild = $(this).parent().children(":first").attr('id');


                var elementcontains = $(this).attr("id");
                var str1 = 'scene';
                var str2 = 'hotspot';
                if (elementcontains.indexOf(str1) != -1 && elementcontains.indexOf(str2) == -1) {

                    var _this = $(this);
                    // $('.wpvr-delete-alert-wrapper').addClass('pano-error-color').css('display', 'flex');
                    // $('#confirm_text').html(' Are you sure you want to delete this Scene?');
                    // $('.wpvr-delete-confirm-btn .yes').click(function (e) {
                    //     e.preventDefault();
                    //     jQuery(_this).slideUp(deleteElement);
                    //     if (current == fchild) {
                    //         $(_this).next().addClass("active");
                    //         $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").next().addClass("active");
                    //         $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").next().children("span").trigger("click");
                    //     } else {
                    //         $(_this).prev().addClass("active");
                    //         $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").prev().addClass("active");
                    //         $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").prev().children("span").trigger("click");
                    //     }
                    //     $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").remove();
                    //     setTimeout(deleteinfodata, 1000);

                    //     //tab active setup
                    //     if (parseInt(_hide_id) - 1 > 0) {
                    //         $('#wpvr_active_scenes').val(parseInt(_hide_id) - 1);
                    //     } else {
                    //         $('#wpvr_active_scenes').val(1);
                    //     }
                    //     $('#wpvr_active_hotspot').val(1);
                    //     $('.wpvr-delete-alert-wrapper').removeClass('pano-error-color').css('display', 'none');
                    //     $('.wpvr-delete-alert-wrapper').hide();
                    // });

                    // $(document).on("click", ".wpvr-delete-confirm-btn .cancel, .wpvr-delete-alert-wrapper .cross", function (e) {
                    //     e.preventDefault();
                    //     $('.wpvr-delete-alert-wrapper').removeClass('pano-error-color').css('display', 'none');
                    //     $('.wpvr-delete-alert-wrapper').hide();
                    // });

                    if (confirm('Are you sure you want to delete?')) {
                        jQuery(_this).slideUp(deleteElement);
                        if (current == fchild) {
                            $(_this).next().addClass("active");
                            $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").next().addClass("active");
                            $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").next().children("span").trigger("click");
                        } else {
                            $(_this).prev().addClass("active");
                            $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").prev().addClass("active");
                            $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").prev().children("span").trigger("click");
                        }
                        $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").remove();
                        setTimeout(deleteinfodata, 1000);

                        //tab active setup
                        if (parseInt(_hide_id) - 1 > 0) {
                            $('#wpvr_active_scenes').val(parseInt(_hide_id) - 1);
                        } else {
                            $('#wpvr_active_scenes').val(1);
                        }
                        $('#wpvr_active_hotspot').val(1);
                    }

                }
            },
            repeaters: [{
                selector: '.hotspot-setup',
                defaultValues: {
                    'hotspot-type': 'info',
                    'hotspot-customclass-pro': 'none',
                },
                show: function () {
                    $('textarea[name*=hotspot-content]').summernote({
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['codeview', 'help']],
                        ],
                        callbacks: {
                            onKeyup: function(e) {
                                var getHotspotContent = $(this).val();
                                var getParent        =  $(this).parent();
                                var getMainParent    = getParent.parent()
                                var getClickContent  =  getMainParent.find('.hotspot-url')
                                if(getHotspotContent.length > 0){
                                    getClickContent.find('input[name*=hotspot-url').val('')
                                    getClickContent.find('input[name*=hotspot-url').attr('placeholder','You can set either a URL or an On-click content')
                                    getClickContent.find('input[name*=hotspot-url]').attr("disabled",true)
                                }else{
                                    getClickContent.find('input[name*=hotspot-url]').attr("disabled",false)
                                }
                            }
                        }
                    });
                    $('textarea[name*=hotspot-hover]').summernote({
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['codeview', 'help']],
                        ],
                    });
                    if ($(this).parents(".hotspot-setup").attr("data-limit").length > 0) {

                        if ($(this).parents(".hotspot-setup").find("div[data-repeater-item]").length <= $(this).parents(".hotspot-setup").attr("data-limit")) {

                            $(this).slideDown();
                            $(this).removeClass('active');
                            $(this).siblings('.active').removeClass('active');
                            $(this).addClass('active');
                            j = parseInt(j);
                            j = j + 1;
                            var parent_scene = $(this).parent().parent().parent('.single-scene.active').attr('id');
                            scene_parent = parent_scene;
                            var hotspot = parent_scene + '-hotspot-' + j;

                            var replace_string = parent_scene.replace("scene-", "");

                            $(this).find(".title .hotspot-num").html(j);
                            $(this).find(".title .scene-num").html(replace_string);

                            $('<li><span data-index="' + j + '" data-href="#' + hotspot + '"><i class="far fa-dot-circle"></i></span></li>').insertBefore($(this).parent().parent('.hotspot-setup').find('.hotspot-nav ul li:last-child'));

                            $(this).attr('id', hotspot);

                            setTimeout(changeicon, 1000);
                        } else {
                            $('.pano-alert .pano-error-message').html('<span class="pano-error-title">Limit Reached</span><p> You can add up to 5 hotspots on each scene in the Free version. <br> Upgrade to Pro to add an unlimited number of hotspots.</p>');
                            $('body').addClass('error-overlay2');
                            $('.pano-alert').addClass('pano-default-warning').show();
                            $(this).remove();
                        }
                    } else {
                        jQuery(this).slideDown();
                        $(this).removeClass('active');
                        j = parseInt(j);
                        j = j + 1;
                        var parent_scene = $(this).parent().parent().parent('.single-scene.active').attr('id');
                        var hotspot = parent_scene + '-hotspot-' + j;

                        var replace_string = parent_scene.replace("scene-", "");

                        $(this).find(".title .hotspot-num").html(j);
                        $(this).find(".title .scene-num").html(replace_string);

                        $('<li><span data-index="' + j + '" data-href="#' + hotspot + '"><i class="far fa-dot-circle"></i></span></li>').insertBefore($(this).parent().parent('.hotspot-setup').find('.hotspot-nav ul li:last-child'));

                        $(this).attr('id', hotspot);
                    }
                    $('#wpvr_active_hotspot').val(j);
                },
                hide: function (deleteElement) {
                    var hotspot_hide_id = $(this).attr("id");
                    var _hide_id = hotspot_hide_id.split('-').pop();
                    hotspot_hide_id = "#" + hotspot_hide_id;
                    var hotspot_current = $(this).attr('id');
                    var hotspot_fchild = $(this).parent().children(":first").attr('id');

                    var hpelementcontains = $(this).attr("id");
                    var hpstr1 = 'scene';
                    var hpstr2 = 'hotspot';
                    if (hpelementcontains.indexOf(hpstr1) != -1 && hpelementcontains.indexOf(hpstr2) != -1) {

                        var _this = $(this);

                        // $('#confirm_text').html(' Are you sure you want to delete this Hotspot?');
                        // $('.wpvr-delete-alert-wrapper').addClass('pano-error-color').css('display', 'flex');

                        // $('.wpvr-delete-confirm-btn .yes').click(function (e) {
                        //     e.preventDefault();
                        //     jQuery(_this).slideUp(deleteElement);
                        //     if (hotspot_current == hotspot_fchild) {
                        //         $(_this).next().addClass("active");
                        //         $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li span[data-href="' + hotspot_hide_id + '"]').parent("li").next().addClass("active");

                        //     } else {
                        //         $(_this).prev().addClass("active");
                        //         $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li span[data-href="' + hotspot_hide_id + '"]').parent("li").prev().addClass("active");
                        //     }

                        //     $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li:not(:last-child) span[data-href="' + hotspot_hide_id + '"]').parent("li").remove();

                        //     //tab active setup
                        //     if (parseInt(_hide_id) - 1 > 0) {
                        //         $('#wpvr_active_hotspot').val(parseInt(_hide_id) - 1);
                        //     } else {
                        //         $('#wpvr_active_hotspot').val(1);
                        //     }
                        //     $('.wpvr-delete-alert-wrapper').removeClass('pano-error-color').css('display', 'none');
                        //     $('.wpvr-delete-alert-wrapper').hide();
                        // });

                        // $(document).on("click", ".wpvr-delete-confirm-btn .cancel, .wpvr-delete-alert-wrapper .cross", function (e) {
                        //     e.preventDefault();
                        //     $('.wpvr-delete-alert-wrapper').removeClass('pano-error-color').css('display', 'none');
                        //     $('.wpvr-delete-alert-wrapper').hide();
                        // });

                        if (confirm('Are you sure you want to delete?')) {
                            jQuery(_this).slideUp(deleteElement);
                            if (hotspot_current == hotspot_fchild) {
                                $(_this).next().addClass("active");
                                $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li span[data-href="' + hotspot_hide_id + '"]').parent("li").next().addClass("active");

                            } else {
                                $(_this).prev().addClass("active");
                                $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li span[data-href="' + hotspot_hide_id + '"]').parent("li").prev().addClass("active");
                            }

                            $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li:not(:last-child) span[data-href="' + hotspot_hide_id + '"]').parent("li").remove();

                            //tab active setup
                            if (parseInt(_hide_id) - 1 > 0) {
                                $('#wpvr_active_hotspot').val(parseInt(_hide_id) - 1);
                            } else {
                                $('#wpvr_active_hotspot').val(1);
                            }
                        }
                    }
                },

            }]
        });
    });


    var file_frame;
    var parent;
    $(document).on("click", ".scene-upload", function (event) {
        event.preventDefault();
        parent = $(this).parent('.form-group');

        if (file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['image']
            },
            multiple: false
        });

        file_frame.on('select', function () {

            var attachment = file_frame.state().get('selection').first().toJSON();
            parent.find('.scene-attachment-url').val(attachment.url);
            parent.find('img').attr('src', attachment.url).show();
        });

        file_frame.open();
    });

    var file_frames;
    $(document).on("click", ".video-upload", function (event) {
        event.preventDefault();

        parent = $(this).parent('.form-group');

        if (file_frames) {
            file_frames.open();
            return;
        }

        file_frames = wp.media.frames.file_frames = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['video/mp4']
            },
            multiple: false
        });

        file_frames.on('select', function () {
            var attachment = file_frames.state().get('selection').first().toJSON();
            parent.find('.video-attachment-url').val(attachment.url);
        });

        file_frames.open();
    });

    var file_fram;

    $(document).on("click", ".preview-upload", function (event) {
        event.preventDefault();
        parent = $(this).parent('.form-group');

        if (file_fram) {
            file_fram.open();
            return;
        }

        file_fram = wp.media.frames.file_fram = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['image']
            },
            multiple: false
        });

        file_fram.on('select', function () {
            var attachment = file_fram.state().get('selection').first().toJSON();
            parent.find('.preview-attachment-url').val(attachment.url);
            parent.find('.img-upload-frame').css('background-image', 'url(' + attachment.url + ')');
            parent.find('.img-upload-frame').addClass('img-uploaded');
            parent.find('.remove-attachment').show();
        });

        file_fram.open();
    });

    //----remove tour preview image----
    $(document).on("click", ".remove-attachment", function (event) {
        $(this).hide();
        parent = $(this).parents('.form-group');

        parent.find('.preview-attachment-url').val('');
        parent.find('.img-upload-frame').css('background-image', '');
        parent.find('.img-upload-frame').removeClass('img-uploaded');
    });


    $(document).on("change", "select[name*=hotspot-type]", function (event) {

        var getparent = $(this).parent();
        var getvalue = $(this).val();
        if (getvalue == 'info') {
            getparent.find('.hotspot-scene').hide();
            getparent.find('.hotspot-scene input').val('');
            getparent.find('.hotspot-scene .hotspotscene').val('none');
            getparent.find('.hotspot-url').show();
            getparent.find('.s_tab').show();
            getparent.find('.hotspot-content').show();
            getparent.find('input[name*=hotspot-url]').attr("disabled",false)
            getparent.find('textarea[name*=hotspot-content]').next().find(".note-editable").attr("contenteditable", true);
        } else {
            getparent.find('.hotspot-scene').show();
            getparent.find('.hotspot-url').hide();
            getparent.find('.hotspot-url input').val('');
            getparent.find('.s_tab').hide();
            getparent.find('.s_tab input').val('off');
            getparent.find('.s_tab input').removeAttr('checked');
            getparent.find('.hotspot-content').hide();
            getparent.find('.hotspot-content textarea').val('');
            getparent.find('textarea[name*=hotspot-content]').next().find(".note-editable").html('');
        }
    });
    $(document).ready(function ($){
        $(document).on("keyup","input[name*=hotspot-url]",function (){
            var getHotspotUrl = $(this).val();
            var getParent        =  $(this).parent();
            var getMainParent    = getParent.parent()
            var getClickContent  =  getMainParent.find('.hotspot-content')
            if(getHotspotUrl.length > 0){
                getClickContent.find('textarea').val('')
                getClickContent.find('textarea').attr('placeholder','You can set either a URL or an On-click content')
                getClickContent.find('textarea').attr("disabled",true)
                getClickContent.find('textarea[name*=hotspot-content]').next().find(".note-editable").attr("contenteditable", false);
                getClickContent.find('textarea[name*=hotspot-content]').next().find(".note-editable").html('');

            }else{
                getClickContent.find('textarea').attr("disabled",false)
                getClickContent.find('textarea[name*=hotspot-content]').next().find(".note-editable").attr("contenteditable", true);
            }
        })

        $(document).on("keyup","textarea[name*=hotspot-content]",function (){
            var getHotspotContent = $(this).val();
            var getParent        =  $(this).parent();
            var getMainParent    = getParent.parent()
            var getClickContent  =  getMainParent.find('.hotspot-url')
            if(getHotspotContent.length > 0){
                getClickContent.find('input[name*=hotspot-url').val('')
                getClickContent.find('input[name*=hotspot-url').attr('placeholder','You can set either a URL or an On-click content')
                getClickContent.find('input[name*=hotspot-url]').attr("disabled",true)
            }else{
                getClickContent.find('input[name*=hotspot-url]').attr("disabled",false)
            }
        })
    })


    $(document).on("click", ".pano-error-close-btn", function (e) {
        e.preventDefault();
        $('.wpvr-delete-alert-wrapper').css('display', 'none');
        $('.wpvr-delete-alert-wrapper').hide();
    });

    $(document).on("change", "input[type=radio][name=panovideo]", function (event) {
        var getvalue = $(this).val();
        
        if (getvalue == 'on') {
            $('#confirm_text').html(`${window.wpvr_localize.VideoTourNotice}`);
            $('.wpvr-delete-alert-wrapper').css('display', 'flex');
        } else {
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            // $(".video-setting").hide();

            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.streetview").show();
            $(".video-setting").hide();
        }

        $(document).on("click", ".wpvr-delete-confirm-btn .cancel,.wpvr-delete-alert-wrapper .cross", function (e) {
            e.preventDefault();
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".video_on").prop('checked', false);
            $(".video_off").prop('checked', true);

            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.streetview").show();
            $(".video-setting").hide();

        });

        $(document).on("click", ".wpvr-delete-confirm-btn .yes", function (e) {
            e.preventDefault();
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".video_off").prop('checked', false);
            $(".video_on").prop('checked', true);
            $(".video-setting").show();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
            $("li.streetview").hide();

        });
    });

    jQuery(document).ready(function ($) {
        let detect_scene_1_data = $('#scene-1').find('.sceneid').val();
        if(detect_scene_1_data === "") {
            $('#scene-1').find('.sceneid').val(generateRandomString(8));
        }
    });

    function generateRandomString(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;

        for (var i = 0; i < length; i++) {
            var randomIndex = Math.floor(Math.random() * charactersLength);
            result += characters.charAt(randomIndex);
        }

        return result;
    }
    function prepare_hotspot_configuration_data() {
        var datacoords = $('#panodata').text().split(',');
        var pitchsplit = datacoords[0];
        var pitch = pitchsplit.split(':');
        var yawsplit = datacoords[1];
        var yaw = yawsplit.split(':');
        let detect_active_hotspot_id = $('div.single-scene.rex-pano-tab.active').children('div.hotspot-setup.rex-pano-sub-tabs').children('div.rex-pano-tab-content').children('div.single-hotspot.rex-pano-tab.active.clearfix').find("#hotspot-title").val();
        if(detect_active_hotspot_id === "" || detect_active_hotspot_id == undefined) {
            $('div.single-scene.rex-pano-tab.active').children('div.hotspot-setup.rex-pano-sub-tabs').children('div.rex-pano-tab-content').children('div.single-hotspot.rex-pano-tab.active.clearfix').find("#hotspot-title").val(generateRandomString(8));
        }
        $('div.single-scene.rex-pano-tab.active').children('div.hotspot-setup.rex-pano-sub-tabs').children('div.rex-pano-tab-content').children('div.single-hotspot.rex-pano-tab.active.clearfix').find('.hotspot-pitch').val(pitch[1]);
        $('div.single-scene.rex-pano-tab.active').children('div.hotspot-setup.rex-pano-sub-tabs').children('div.rex-pano-tab-content').children('div.single-hotspot.rex-pano-tab.active.clearfix').find('.hotspot-yaw').val(yaw[1]);
    }

    function register_hotspot() {
        $('.panolenspreview').trigger('click');
    }

    $(document).on("click", ".toppitch", function (event) {
        $.when(prepare_hotspot_configuration_data()).done(function() {
            register_hotspot();
        });
    });

    // $(document).on("change", "input[type=radio][name=panovideo]", function(event) {
    //     var getvalue = $(this).val();

    //     if (getvalue == 'on') {

    //         $(".video-setting").show();
    //         $("li.general").hide();
    //         $("li.scene").hide();
    //         $("li.hotspot").hide();
    //         $("li.streetview").hide();
    //     } else {
    //         $(".video-setting").hide();
    //         $("li.general").show();
    //         $("li.scene").show();
    //         $("li.hotspot").show();
    //         $("li.streetview").show();
    //     }
    // });

    jQuery(document).ready(function ($) {
        var viddata = $("input[name='panovideo']:checked").val();
        
        if (viddata == 'on') {

            $("li.scene").removeClass('active');
            $(".rex-pano-tab.wpvr_scene").removeClass('active');
            $("li.videos").addClass('active');
            $(".rex-pano-tab.video").addClass('active');
            $(".video-setting").show();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
        } else {
            // $(".video-setting").hide();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
        }
    });

    $(document).on("change", "select[name*=hotspot-customclass-pro]", function (event) {
        var getval = $(this).val();
        $(this).parent('.hotspot-setting').children('span.change-icon').html('<i class="' + getval + '"></i>');

    });

    $(document).on("change", ".hotspot-customclass-color", function (event) {
        var getcolor = $(this).val();
        color = getcolor;
        $('.hotspot-customclass-color-icon-value').val(getcolor);
        $('.hotspot-customclass-color').val(getcolor);
    });

    jQuery(document).ready(function ($) {
        if ($(".icon-found-value")[0]) {
            color = $('.hotspot-customclass-color-icon-value.icon-found-value').val();
        } else {
            color = '#00b4ff';
        }
    });

    function changeicon() {
        $('.hotspot-customclass-color-icon-value').val(color);
        $('.hotspot-customclass-color').val(color);
    }

    //------------panolens tab js------------------


    $(document).on("click", ".scene-nav ul li:not(:last-child) span", function () {

        var scene_id = $(this).data('index');
        scene_id = '#scene-' + scene_id;

        j = $(scene_id).find('.hotspot-nav li').eq(-2).find('span').attr('data-index');

        $([$(this).parent()[0], $($(this).data('href'))[0]]).addClass('active').siblings('.active').removeClass('active');
        $('#wpvr_active_scenes').val($(this).data('index'));
    });

    //add click
    $(document).on("click", ".scene-nav ul li:last-child span", function () {
        var scene_id = $(this).parent('li').prev().children("span").data('index');
        scene_id = '#scene-' + scene_id;
        $(scene_id).removeAttr("style");
        j = $(scene_id).find('.hotspot-nav li').eq(-2).find('span').attr('data-index');
        $('.scene-nav ul li.active').removeClass('active');
        $(this).parent('li').prev().addClass('active');
        var sceneinfo = $('.scene-setup').repeaterVal();
        var infodata = sceneinfo['scene-list'];
        $('.hotspotscene').find('option').remove();
        $('.hotspotscene').append("<option value='none'>None</option>");
        for (var i in infodata) {
            var optiondata = infodata[i]['scene-id'];
            if (optiondata != '') {
                $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
            }
        }
        $('.hotspot-customclass-pro-select').fontIconPicker();
        $('span.change-icon').hide();
    });

    //end add click
    $(document).on("click", ".hotspot-nav ul li:not(:last-child) span", function () {
        $('#wpvr_active_hotspot').val($(this).data('index'));
        $([$(this).parent()[0], $($(this).data('href'))[0]]).addClass('active').siblings('.active').removeClass('active');
    });

    $(document).on("click", ".hotspot-nav ul li:last-child span", function () {
        $(this).parent('li').siblings('.active').removeClass('active');
        $(this).parent('li').prev().addClass('active');
        var sceneinfo = $('.scene-setup').repeaterVal();
        var infodata = sceneinfo['scene-list'];
        $('.hotspotscene').find('option').remove();
        $('.hotspotscene').append("<option value='none'>None</option>");
        for (var i in infodata) {
            var optiondata = infodata[i]['scene-id'];
            if (optiondata != '') {
                $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
            }
        }
        $('.trtr').trigger('change');
        $('.hotspot-customclass-pro-select').fontIconPicker();
        $('span.change-icon').hide();

    });

    function changehotspotid(id) {
        var scene_id = '#scene-' + id;
        var hotspot_id = 'scene-' + id + '-hotspot-1';
        $(scene_id).find('.hotspot-nav li span').attr('data-href', '#' + hotspot_id + '');
        $(scene_id).find('.single-hotspot').attr('id', hotspot_id);

    }

    $(document).on("click", ".rex-pano-nav-menu.main-nav ul li span", function () {
        var screen = $(this).parent().attr('data-screen');

        var url_info = wpvr_global_obj.url_info
        if ( 'add' == url_info.screen ){
            var newUrl = url_info.admin_url+"post-new.php?"+"post_type="+url_info.param.post_type+"&active_tab="+screen;
        }else{
            var newUrl = url_info.admin_url+"post.php?"+"post="+url_info.param.post+"&action=edit"+"&active_tab="+screen;
        }


        if (window.history != 'undefined' && window.history.pushState != 'undefined') {
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        $('#wpvr_active_tab').val(screen);
        if ('hotspot' == screen) {
            $('.active_scene_id').show();
            var id = $('.single-scene.active').attr('id');
            $('.active_scene_id p').text(`${window.wpvr_localize.AddingHotspotsOnScene}`);
            var scenceID = $('#' + id + ' .sceneid').val();
            var span = '<span>(' + scenceID + ')</span>'
            $('.active_scene_id p').append(span);
            $('.active_scene_id').css({ "background-color": "#E0E1F7", "width": "100%", "text-align": "center", "padding": "10px 15px" });
            $('.active_scene_id p').css({ "color": "black", "font-size": "15px" });
            $('.active_scene_id p span').css({ "color": "#004efa", "font-size": "15px" });

        } else {
            $('.active_scene_id').hide();
        }
        $([$(this).parent()[0], $($(this).data('href'))[0]]).addClass('active').siblings('.active').removeClass('active');
    });

    //-Get Started reloaded -//
    $(document).on("click",".tabs.rex-tabs li a",function(){
        var tab = $(this).attr('href');
        var url = window.location;
        var origin = url.origin;
        var pathname = url.pathname
        var search_perameter = window.location.search.split('&');
        var makeUrl = origin + pathname + search_perameter[0]
        var newUrl = makeUrl + tab;

        if (window.history != 'undefined' && window.history.pushState != 'undefined') {
            window.history.pushState({ path: newUrl }, '', newUrl);
        }
    })
    //----------alert dismiss--------//
    $(document).on("click", "body", function () {
        $('body').removeClass('error-overlay2');
        $('.pano-alert').removeClass('pano-default-warning').hide();
    });
    $(document).on("click", ".pano-alert .pano-error-close-btn", function () {
        $('body').removeClass('error-overlay2');
        $('.pano-alert').removeClass('pano-default-warning').hide();
    });
    $(document).on("click", ".pano-alert, .rex-pano-sub-tabs .rex-pano-tab-nav li.add", function (e) {
        e.stopPropagation();
    });


    $(document).on("click", ".main-nav li.hotspot span", function () {
        $(".hotspot-setup.rex-pano-sub-tabs").show();
        $(".scene-setup > nav.scene-nav").hide();
        $(".scene-setup .single-scene > .scene-content").hide();
        $(".scene-setup .delete-scene").hide();
    });

    $(document).on("click", ".main-nav li.scene span", function () {
        $(".hotspot-setup.rex-pano-sub-tabs").hide();
        $(".scene-setup > nav.scene-nav").show();
        $(".scene-setup .single-scene > .scene-content").show();
        $(".scene-setup .delete-scene").show();
    });

    $(document).on("change", ".dscen", function () {
        var dscene = $(this).val();
        $(".dscen").not(this).each(function () {
            var oth_scene = $(this).val();
            if (dscene == 'on' && oth_scene == 'on') {
                $('#error_occured').show();
                $('#error_occured .pano-error-message').html('<span class="pano-error-title">Default scene has been updated</span>');
                $('body').addClass('error-overlay');
                $('html, body').animate({
                    scrollTop: $("#error_occured").offset().top
                }, 500);
                $(this).val('off');

            }
        });
    });

    $(document).on("change", ".sceneid", function () {
        var sceneinfo = $('.scene-setup').repeaterVal();
        var infodata = sceneinfo['scene-list'];
        $('.hotspotscene').find('option').remove();
        $('.hotspotscene').append("<option value='none'>None</option>");
        for (var i in infodata) {
            var optiondata = infodata[i]['scene-id'];
            if (optiondata != '') {
                $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
            }
        }
    });



    function deleteinfodata() {
        var sceneinfo = $('.scene-setup').repeaterVal();
        var infodata = sceneinfo['scene-list'];
        $('.hotspotscene').find('option').remove();
        $('.hotspotscene').append("<option value='none'>None</option>");
        for (var i in infodata) {
            var optiondata = infodata[i]['scene-id'];
            if (optiondata != '') {
                $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
            }
        }
    }

    $(document).on("change", ".hotspotscene", function () {

        var chanheghtptpval = $(this).val();
        if (chanheghtptpval != "none") {
            $(this).parent('.hotspot-scene').siblings('.hotspot-scene').children('.hotspotsceneinfodata').val(chanheghtptpval);
        } else {
            $(this).parent('.hotspot-scene').siblings('.hotspot-scene').children('.hotspotsceneinfodata').val('');
        }
    });

    $(document).on("click", ".hotpitch", function (event) {
        var datacoords = $('#panodata').text().split(',');
        var pitchsplit = datacoords[0];
        var pitch = pitchsplit.split(':');
        $(this).parent().parent('.hotspot-setting').children('.hotspot-pitch').val(pitch[1]);
    });

    $(document).on("click", ".hotyaw", function (event) {
        var datacoords = $('#panodata').text().split(',');
        var yawsplit = datacoords[1];
        var yaw = yawsplit.split(':');
        $(this).parent().parent('.hotspot-setting').children('.hotspot-yaw').val(yaw[1]);
    });

    jQuery(document).ready(function ($) {

        if ($(".scene-setup").length > 0) {
            var sceneinfo = $('.scene-setup').repeaterVal();
            var infodata = sceneinfo['scene-list'];
            $('.hotspotscene').find('option').remove();
            $('.hotspotscene').append("<option value='none'>None</option>");
            for (var i in infodata) {
                var optiondata = infodata[i]['scene-id'];
                if (optiondata != '') {
                    $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
                }
            }
        }
    });

    $(document).on("click", ".toppitch", function (event) {
        var datacoords = $('#panodata').text().split(',');
        var pitchsplit = datacoords[0];
        var pitch = pitchsplit.split(':');
        var yawsplit = datacoords[1];
        var yaw = yawsplit.split(':');

        $('div.single-scene.rex-pano-tab.active').children('div.hotspot-setup.rex-pano-sub-tabs').children('div.rex-pano-tab-content').children('div.single-hotspot.rex-pano-tab.active.clearfix').find('.hotspot-pitch').val(pitch[1]);
        $('div.single-scene.rex-pano-tab.active').children('div.hotspot-setup.rex-pano-sub-tabs').children('div.rex-pano-tab-content').children('div.single-hotspot.rex-pano-tab.active.clearfix').find('.hotspot-yaw').val(yaw[1]);
    });
    jQuery(document).ready(function ($) {
        $('.hotspot-customclass-pro-select').fontIconPicker();
    });
    jQuery(document).ready(function ($) {
        $('span.change-icon').hide();
    });

    jQuery(document).ready(function ($) {
        var autrotateset = $("input[name='autorotation']").is(':checked') ? 'on' : 'off';
        if (autrotateset == 'off') {
            $('.autorotationdata-wrapper').hide();
        } else {
            $('.autorotationdata-wrapper').show();
        }
    });

    $(document).on("change", "input[name='autorotation']", function (event) {
        var autrotateset = $(this).is(':checked') ? 'on' : 'off';

        if (autrotateset == 'on') {
            $('.autorotationdata-wrapper').show();
        } else {
            $('.autorotationdata-wrapper').hide();
        }
    });

    $(document).on("change", ".ptyscene", function (event) {
        var ptyscene = $(this).val();
        if (ptyscene == 'off') {
            $(this).parent('.single-settings').siblings('.ptyscenedata').hide();
        } else {
            $(this).parent('.single-settings').siblings('.ptyscenedata').show();
        }
    });

    $(document).on("change", ".cvgscene", function (event) {
        var cvgscene = $(this).val();
        if (cvgscene == 'off') {
            $(this).parent('.single-settings').siblings('.cvgscenedata').hide();
        } else {
            $(this).parent('.single-settings').siblings('.cvgscenedata').show();
        }
    });

    $(document).on("change", ".chgscene", function (event) {
        var chgscenedata = $(this).val();
        if (chgscenedata == 'off') {
            $(this).parent('.single-settings').siblings('.chgscenedata').hide();
        } else {
            $(this).parent('.single-settings').siblings('.chgscenedata').show();
        }
    });

    $(document).on("change", ".czscene", function (event) {
        var czscene = $(this).val();
        if (czscene == 'off') {
            $(this).parent('.single-settings').siblings('.czscenedata').hide();
        } else {
            $(this).parent('.single-settings').siblings('.czscenedata').show();
        }
    });

    /**
     * Nasim
     * enable script button on chnage action
     */
    $("#wpvr_script_control").change(function () {

        if ($('#wpvr_script_control').is(':checked')) {

            $(".wpvr_enqueue_script_list").show();

        } else {

            $(".wpvr_enqueue_script_list").hide();
        }

    });

    /**
     * Sakib
     * enable script button on chnage action
     */
    $("#wpvr_video_script_control").change(function () {

        if ($('#wpvr_video_script_control').is(':checked')) {

            $(".wpvr_enqueue_video_script_list").show();

        } else {

            $(".wpvr_enqueue_video_script_list").hide();
        }

    });


    /**
     * Nasim
     * Check enable script button is on or not
     * if on then script list field will be show and if off then script list field will be hide
     */
    if ($('#wpvr_script_control').is(':checked')) {

        $(".wpvr_enqueue_script_list").show();

    } else {

        $(".wpvr_enqueue_script_list").hide();
    }

    /**
     * Sakib
     * Check enable script button is on or not
     * if on then script list field will be show and if off then script list field will be hide
     */
    if ($('#wpvr_video_script_control').is(':checked')) {

        $(".wpvr_enqueue_video_script_list").show();

    } else {

        $(".wpvr_enqueue_video_script_list").hide();
    }

    $(document).on("click", "#wpvr_role_submit", function (e) {
        e.preventDefault();
        var ajaxurl = wpvr_obj.ajaxurl;
        $('#wpvr_role_progress').show();
        $('#wpvr_role_submit').attr('disabled', true);
        var editor = $('#wpvr_editor_active').is(':checked');
        var author = $('#wpvr_author_active').is(':checked');
        var fontawesome = $('#wpvr_fontawesome_disable').is(':checked');
        var mobile_media_resize = $('#mobile_media_resize').is(':checked');
        var wpvr_frontend_notice = $('#wpvr_frontend_notice').is(':checked');
        var wpvr_frontend_notice_area = $('#wpvr_frontend_notice_area').val();
        var wpvr_script_control = $('#wpvr_script_control').is(':checked');
        var wpvr_script_list = $('#wpvr_script_list').val();
        var wpvr_video_script_control = $('#wpvr_video_script_control').is(':checked');
        var wpvr_video_script_list = $('#wpvr_video_script_list').val();
        var high_res_image = $('#high_res_image').is(':checked');
        var dis_on_hover = $('#dis_on_hover').is(':checked');
            
        /**
         * check enable script button is on and script list field is not empty
         */
        if ( ($('#wpvr_video_script_control').is(':checked') && wpvr_video_script_list == '') || ($('#wpvr_script_control').is(':checked') && wpvr_script_list == '') ) {
            if(($('#wpvr_script_control').is(':checked') && wpvr_script_list == '')){
                if (confirm('The "List of Allowed Pages To Load WP VR Scripts " Field Is Empty. No Virtual Tours Will Show Up on Your Site.')) {
                    if($('#wpvr_video_script_control').is(':checked') && wpvr_video_script_list == ''){
                        if (confirm("The 'List of Allowed Pages To Load WPVR Video.js ' Field Is Empty. Any Self-hosted 360-degree videos won't function on your site.")) {
                            jQuery.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: {
                                    action: "wpvr_role_management",
                                    nonce : wpvr_obj.ajax_nonce,
                                    editor: editor,
                                    author: author,
                                    fontawesome: fontawesome,
                                    mobile_media_resize: mobile_media_resize,
                                    high_res_image: high_res_image,
                                    dis_on_hover: dis_on_hover,
                                    wpvr_frontend_notice: wpvr_frontend_notice,
                                    wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                                    wpvr_script_control: wpvr_script_control,
                                    wpvr_script_list: wpvr_script_list,
                                    wpvr_video_script_control: wpvr_video_script_control,
                                    wpvr_video_script_list: wpvr_video_script_list,
                                    // woocommerce: woocommerce,
                                },
                                success: function (response) {
                                    $('#wpvr_role_progress').hide();
                                    $('#wpvr_role_submit').attr('disabled', false);
            
                                    if (response.status == 'success') {
                                        Materialize.toast(response.message, 2000);
                                    }
            
                                }
                            });
                        } else {
                            $('#wpvr_role_progress').hide();
                            $('#wpvr_role_submit').attr('disabled', false);
                        }
                    }else{
                        jQuery.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: {
                                action: "wpvr_role_management",
                                nonce : wpvr_obj.ajax_nonce,
                                editor: editor,
                                author: author,
                                fontawesome: fontawesome,
                                mobile_media_resize: mobile_media_resize,
                                high_res_image: high_res_image,
                                wpvr_frontend_notice: wpvr_frontend_notice,
                                wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                                wpvr_script_control: wpvr_script_control,
                                wpvr_script_list: wpvr_script_list,
                                wpvr_video_script_control: wpvr_video_script_control,
                                wpvr_video_script_list: wpvr_video_script_list,
                                // woocommerce: woocommerce,
                            },
                            success: function (response) {
                                $('#wpvr_role_progress').hide();
                                $('#wpvr_role_submit').attr('disabled', false);
        
                                if (response.status == 'success') {
                                    Materialize.toast(response.message, 2000);
                                }
        
                            }
                        });
                    }
                } else {
                    $('#wpvr_role_progress').hide();
                    $('#wpvr_role_submit').attr('disabled', false);
                }
            }else if($('#wpvr_video_script_control').is(':checked') && wpvr_video_script_list == ''){
                
                if (confirm("The 'List of Allowed Pages To Load WPVR Video.js ' Field Is Empty. Any Self-hosted 360-degree videos won't function on your site.")) {
                    if(($('#wpvr_script_control').is(':checked') && wpvr_script_list == '')){
                        if (confirm('The "List of Allowed Pages To Load WP VR Scripts " Field Is Empty. No Virtual Tours Will Show Up on Your Site.')) {
                            jQuery.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: {
                                    action: "wpvr_role_management",
                                    nonce : wpvr_obj.ajax_nonce,
                                    editor: editor,
                                    author: author,
                                    fontawesome: fontawesome,
                                    mobile_media_resize: mobile_media_resize,
                                    high_res_image: high_res_image,
                                    dis_on_hover: dis_on_hover,
                                    wpvr_frontend_notice: wpvr_frontend_notice,
                                    wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                                    wpvr_script_control: wpvr_script_control,
                                    wpvr_script_list: wpvr_script_list,
                                    wpvr_video_script_control: wpvr_video_script_control,
                                    wpvr_video_script_list: wpvr_video_script_list,
                                    // woocommerce: woocommerce,
                                },
                                success: function (response) {
                                    $('#wpvr_role_progress').hide();
                                    $('#wpvr_role_submit').attr('disabled', false);
            
                                    if (response.status == 'success') {
                                        Materialize.toast(response.message, 2000);
                                    }
            
                                }
                            });
                        }else {
                            $('#wpvr_role_progress').hide();
                            $('#wpvr_role_submit').attr('disabled', false);
                        }
                    }else{
                        jQuery.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: {
                                action: "wpvr_role_management",
                                nonce : wpvr_obj.ajax_nonce,
                                editor: editor,
                                author: author,
                                fontawesome: fontawesome,
                                mobile_media_resize: mobile_media_resize,
                                high_res_image: high_res_image,
                                dis_on_hover: dis_on_hover,
                                wpvr_frontend_notice: wpvr_frontend_notice,
                                wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                                wpvr_script_control: wpvr_script_control,
                                wpvr_script_list: wpvr_script_list,
                                wpvr_video_script_control: wpvr_video_script_control,
                                wpvr_video_script_list: wpvr_video_script_list,
                                // woocommerce: woocommerce,
                            },
                            success: function (response) {
                                $('#wpvr_role_progress').hide();
                                $('#wpvr_role_submit').attr('disabled', false);
        
                                if (response.status == 'success') {
                                    Materialize.toast(response.message, 2000);
                                }
        
                            }
                        });
                    }
                } else {
                    $('#wpvr_role_progress').hide();
                    $('#wpvr_role_submit').attr('disabled', false);
                }
            }

        } else {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "wpvr_role_management",
                    nonce : wpvr_obj.ajax_nonce,
                    editor: editor,
                    author: author,
                    fontawesome: fontawesome,
                    mobile_media_resize: mobile_media_resize,
                    high_res_image: high_res_image,
                    dis_on_hover: dis_on_hover,
                    wpvr_frontend_notice: wpvr_frontend_notice,
                    wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                    wpvr_script_control: wpvr_script_control,
                    wpvr_script_list: wpvr_script_list,
                    wpvr_video_script_control: wpvr_video_script_control,
                    wpvr_video_script_list: wpvr_video_script_list,
                },
                success: function (response) {
                    $('#wpvr_role_progress').hide();
                    $('#wpvr_role_submit').attr('disabled', false);
                    if (response.status == 'success') {
                        Materialize.toast(response.message, 2000);
                    }
                }
            });
        }



    });


    //------general tab's inner tab-------
    jQuery(document).ready(function ($) {

        $('.general-inner-tab .inner-nav li span').on('click', function () {
            var this_id = $(this).attr('data-href');

            $(this).parent('li').addClass('active');
            $(this).parent('li').siblings().removeClass('active');
            
            $(this_id).show();
            $(this_id).siblings().hide();
        });
    });

    //------active tab scripts-------
    jQuery(document).ready(function ($) {

        function getUrlVars() {
            var vars = [],
                hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }
        var activeTab = 'general',
            vr_main_tab = $('#wpvr-main-nav'),
            var_main_tab_contents = $('#wpvr-main-tab-contents').find('.rex-pano-tab').not(".single-scene,.single-hotspot"),
            scene_content = $('.scene-content'),
            scene_nav = $('.scene-nav'),
            single_scene = $('.single-scene'),
            hotspot_content = $('.hotspot-setup'),
            single_hotspot = $('.single-hotspot'),
            delete_scene_btn = $('.delete-scene'),
            delete_hotspot_btn = $('.delete-hotspot');

        var _q_activeTab = getUrlVars()["active_tab"];
        var _sceneID = getUrlVars()["scene"];
        var _hotspotID = getUrlVars()["hotspot"];
        var default_tabs = ['general', 'scene', 'hotspot', 'video'];
        if (_q_activeTab) {
            if (default_tabs.includes(_q_activeTab)) {
                activeTab = _q_activeTab;
                if(activeTab == "video" ){
                    vr_main_tab.find('li:not(:first)').removeClass('active');
                    vr_main_tab.find(".videos").addClass('active');
                }else{
                    vr_main_tab.find('li:not(:first)').removeClass('active');
                    vr_main_tab.find('.' + activeTab).addClass('active');
                }

                // scene screens
                var_main_tab_contents.addClass('active');
                if (activeTab === 'scene' || activeTab === 'hotspot') {
                    var_main_tab_contents.not('#scenes').removeClass('active');
                } else {
                    var_main_tab_contents.not('#' + activeTab).removeClass('active');
                }
                if(activeTab === 'hotspot'){
                    $(".rex-pano-nav-menu.main-nav ul li.hotspot span").trigger('click')
                }

                // scene contents
                if (_sceneID) {
                    var scenesIds = [];
                    var sceneID = '#scene-' + _sceneID;
                    var scene_nav_items = scene_nav.find('li');
                    scene_nav.find('li').each(function () {
                        var index = $(this).find('span').attr('data-index');
                        if (index) {
                            scenesIds.push(index);
                        }
                    });
                    if (scenesIds.includes(_sceneID)) {
                        scene_nav_items.removeClass('active');
                        scene_nav.find('li').each(function () {
                            var index = $(this).find('span').attr('data-index');
                            if (_sceneID == index) {
                                $(this).addClass('active');
                            }
                        });
                        if (activeTab == 'scene' || _sceneID) {
                            single_scene.removeClass('active');
                            $(sceneID).addClass('active');
                        }
                    } else {
                        scene_nav.find('li:first').addClass('active');
                    }

                    if (activeTab === 'scene') {
                        if (scenesIds.includes(_sceneID)) {
                            $(single_scene).removeClass('active');
                            $(sceneID).addClass('active');
                        }
                    } else {
                        $(delete_scene_btn).hide();
                        $(scene_nav).hide();
                        $('.scene-content').hide();
                        $(sceneID).find('.hotspot-setup').show();
                    }

                    //hotspot contents
                    var hotspot_nav = $('.single-scene.active').find('.hotspot-nav');
                    var hotspotIds = [];
                    var hotspot_nav_items = $('.single-scene.active').find('.hotspot-nav').find('li');
                    var activeHotspotId = '#scene-' + _sceneID + 'hotspot-' + _hotspotID;
                    hotspot_nav_items.each(function () {
                        var index = $(this).find('span').attr('data-index');
                        if (index) {
                            hotspotIds.push(index);
                            activeHotspotId = $(this).find('span').attr('data-href');
                        }
                    });
                    if (hotspotIds.includes(_hotspotID)) {
                        hotspot_nav_items.removeClass('active');
                        hotspot_nav.find('li').each(function () {
                            var index = $(this).find('span').attr('data-index');
                            if (_hotspotID === index) {
                                $(this).addClass('active');
                            }
                        });
                        if (activeHotspotId) {
                            $(sceneID).find('.single-hotspot').removeClass('active');
                            $(activeHotspotId).addClass('active');
                        }
                    }
                }
            }
        }


    });

    $(document).on("click", ".wpvr_url_open", function (event) {
        if ($(this).val() == 'off') {
            $(this).val('on');
        }
        else {
            $(this).val('off');
        }
    });

    $(document).ready(function() {
        $('textarea[name*=hotspot-content]').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']],
            ],
            callbacks: {
                onKeyup: function(e) {
                    var getHotspotContent = $(this).val();
                    var getParent        =  $(this).parent();
                    var getMainParent    = getParent.parent()
                    var getClickContent  =  getMainParent.find('.hotspot-url')
                    if(getHotspotContent.length > 0){
                        getClickContent.find('input[name*=hotspot-url').val('')
                        getClickContent.find('input[name*=hotspot-url').attr('placeholder','You can set either a URL or an On-click content')
                        getClickContent.find('input[name*=hotspot-url]').attr("disabled",true)
                    }else{
                        getClickContent.find('input[name*=hotspot-url]').attr("disabled",false)
                    }
                }
            }
        });
        $('textarea[name*=hotspot-hover]').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']],
            ],
        });
    });

    $(document).on("submit", "#trigger-rollback", function (event) {
        event.preventDefault();
        if(confirm('Are you sure?')) {
            let version = $("#trigger-rollback").serialize();
            let redirectUrl = wpvr_global_obj.url_info.admin_url + 'admin.php?' + version;
            window.location.href = redirectUrl;
        } 
    });

})(jQuery);
