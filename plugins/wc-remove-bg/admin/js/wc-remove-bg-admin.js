(function( $ ) {
    $(document).ready(function (){

        var ajaxQueue = $({});
        var processing = true;

        //Ajax queue requests. Used for synchronous transfer of pictures for processing.
        $.ajaxQueue = function(ajaxOpts) {
            // Hold the original complete function
            var oldComplete = ajaxOpts.complete;

            // Queue our ajax request
            ajaxQueue.queue(function (next) {
                // Create a complete callback to invoke the next event in the queue
                ajaxOpts.complete = function () {
                    // Invoke the original complete if it was there
                    if (oldComplete) {
                        oldComplete.apply(this, arguments);
                    }

                    // Run the next query in the queue
                    if(processing) {
                        next();
                    }else{
                        processing = true;
                    }
                };

                // Run the query
                if(processing) {
                    $.ajax(ajaxOpts);
                }
            });
        };

        //We add to processing a new picture with data to which post it belongs. The queue will be synchronous.
        function processQueue(post, image, thumb, gallery, currentProcessing, allProcessing, last, remove_bg_id){
            $.ajaxQueue({
                type: "post",
                dataType: "json",
                url: ajaxurl,
                data: {
                    action: "Remove_BG_processing",
                    process: 'processing_queue',
                    RemoveBG_NextPost: post,
                    RemoveBG_NextImage: image,
                    RemoveBG_NextImageThumb: thumb,
                    RemoveBG_NextImageGallery: gallery,
                    RemoveBG_CountProcessImage: currentProcessing,
                    RemoveBG_AllCountImage: allProcessing,
                    RemoveBG_LastImage: last,
                    RemoveBG_ApiKey: $('input[name="RemoveBG_ApiKey"]').val(),
                    RemoveBG_products: $('input[name="RemoveBG_products"]:checked').val(),
                    RemoveBG_products_IDs: $('input[name="RemoveBG_products_IDs"]').val(),
                    RemoveBG_thumbnail: $('input[name="RemoveBG_thumbnail"]:checked').val(),
                    RemoveBG_gallery: $('input[name="RemoveBG_gallery"]:checked').val(),
                    RemoveBG_Include_Processed: $('input[name="RemoveBG_Include_Processed"]:checked').val(),
                    RemoveBG_Background: $('input[name="RemoveBG_Background"]:checked').val(),
                    RemoveBG_Background_Color: $('input[name="RemoveBG_Background_Color"]').val(),
                    RemoveBG_Background_fit_fill: $('input[name="RemoveBG_Background_fit_fill"]:checked').val(),
                    RemoveBG_Preserve_Resize: $('input[name="RemoveBG_Preserve_Resize"]:checked').val(),
                    RemoveBG_ID: remove_bg_id,
                    schk: $('input#schk').val(),
                    _nonce: $('#_wpnonce').val()
                },
                success: function (data) {
                    if(data.hasErrors == true) {
                        $('.wc_remove_bg').hide();
                        $('.wc_remove_bg#status_restore_e').show();
                        $('.wc_remove_bg#status_restore_e p').html(data.error_msg+' ('+$('.wc_remove_bg-log').html()+')');
                        $("html, body").animate({ scrollTop: 0 }, "slow");

                        $('#loader').hide();
                        $('p.submit').show();

                        ajaxQueue = $({});
                        processing = false;
                    }else{
                        if(currentProcessing > 0){
                            $('.button-click').removeClass('d-none');
                            $('.block-count span').html(currentProcessing);
                        }
                        $('.RemoveBG_Background_img').attr('src', '').css('display', 'none');
                        if(data.success_msg != "") {
                            if(processing !== false) {
                                $('.wc_remove_bg-log-live').show().html(data.success_msg + ' (' + $('.wc_remove_bg-log').html() + ')');
                                $('.wc_remove_bg-process-stop').show().attr('data-id', remove_bg_id);
                                $('html,body').animate({
                                    scrollTop: $(".wc_remove_bg-log-live").offset().top
                                }, 'slow');
                            }
                        }
                        if(last){
                            $('.wc_remove_bg').hide();
                            $('.wc_remove_bg#status_s').show();
                            $('.wc_remove_bg#status_s p').html(data.success_msg+' ('+$('.wc_remove_bg-log').html()+')');
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                            $('.wc_remove_bg-log-live').hide();
                            $('.wc_remove_bg-process-stop').hide().attr('data-id', 0);
                            $('#loader').hide();
                            $('p.submit').show();
                            $('.block-count').show();
                        }
                    }
                    return true;
                }
            });
            return true;
        }

        $('input[name="RemoveBG_Background_Color"]').wpColorPicker();
        if($('input[name="RemoveBG_Background"]:checked').val() == 'color')
            $('.wp-picker-container').show();
        else
            $('.wp-picker-container').hide();
        if($('input[name="RemoveBG_Background"]:checked').val() == 'image')
            $('.fit_fill').show();
        else
            $('.fit_fill').hide();
        $('input[name="RemoveBG_Background"]').on('click', function () {
            if($('input[name="RemoveBG_Background"]:checked').val() == 'color')
                $('.wp-picker-container').show();
            else
                $('.wp-picker-container').hide();
            if($('input[name="RemoveBG_Background"]:checked').val() == 'image')
                $('.fit_fill').show();
            else
                $('.fit_fill').hide();
        })

        $('form#RemoveBG_Form input.button-primary').on('click', function (e) {
            e.preventDefault();
            $('#previewresult').hide();
            if(this.id == 'startpreview') return false;
            $('.wc_remove_bg').hide();
            var btn = $(this), process, start_p = false, start_nb = false, start_r = true;
            if(btn.hasClass('start')){
                process = 'start_queue';//'new';
                $('.wc_remove_bg#status_w').show();
                $('#process_status').val('w');
            }
            if(btn.hasClass('save')){
                process = 'save';
            }
            if($('input[name="RemoveBG_thumbnail"]:checked').length || $('input[name="RemoveBG_gallery"]:checked').length){
                start_p = true;
            }
            if($('input[name="RemoveBG_Background"]:checked').val() == 'color' && $('input[name="RemoveBG_Background_Color"]').val() != '')
                start_nb = true;
            if($('input[name="RemoveBG_Background"]:checked').val() == 'image' && $(".RemoveBG_Background_Image")[0].files[0] != '')
                start_nb = true;
            if($('input[name="RemoveBG_Background"]:checked').val() == 'transparent ')
                start_nb = true;

            if(start_r) {
                if (start_p && start_nb) {
                    $('#loader').show();
                    $('p.submit').hide();

                    var file_data = $(".RemoveBG_Background_Image")[0].files[0];
                    var form_data = new FormData();
                    form_data.append("RemoveBG_file", file_data);
                    form_data.append("action", 'Remove_BG_processing');
                    form_data.append("process", process);
                    form_data.append("action", 'Remove_BG_processing');
                    form_data.append("RemoveBG_CountProcessImage", 0);
                    form_data.append("RemoveBG_AllCountImage", 0);
                    form_data.append("RemoveBG_ApiKey", $('input[name="RemoveBG_ApiKey"]').val());
                    form_data.append("RemoveBG_products", $('input[name="RemoveBG_products"]:checked').val());
                    form_data.append("RemoveBG_products_IDs", $('input[name="RemoveBG_products_IDs"]').val());
                    form_data.append("RemoveBG_thumbnail", $('input[name="RemoveBG_thumbnail"]:checked').val());
                    form_data.append("RemoveBG_gallery", $('input[name="RemoveBG_gallery"]:checked').val());
                    form_data.append("RemoveBG_Include_Processed", $('input[name="RemoveBG_Include_Processed"]:checked').val());
                    form_data.append("RemoveBG_Background", $('input[name="RemoveBG_Background"]:checked').val());
                    form_data.append("RemoveBG_Background_Color", $('input[name="RemoveBG_Background_Color"]').val());
                    form_data.append("RemoveBG_Preserve_Resize", $('input[name="RemoveBG_Preserve_Resize"]:checked').val());
                    form_data.append("_nonce", $('#_wpnonce').val());
                    form_data.append("schk", $('input#schk').val());

                    $.ajax({
                        type: "post",
                        dataType: "json",
                        cache: false,
                        contentType: false,
                        processData: false,
                        url: ajaxurl,
                        data: form_data,
                        success: function (data) {
                            if(process == 'start_queue'){
                                if(data.background_image != ""){
                                    $('.RemoveBG_Background_img').attr('src', data.background_image).css('display', 'block');
                                }
                                var arrayPost = data.data;
                                if(arrayPost !== ""){
                                    var arrayPostJson = $.parseJSON(arrayPost);
                                    var countGenerateImage = $(arrayPostJson).length;
                                    if(countGenerateImage > 0) {
                                        $(arrayPostJson).each(function (item, res) {
                                            var iteration = item + 1;
                                            if (iteration == countGenerateImage) {
                                                processQueue(res.id, res.image, res.thumb, res.gallery, iteration, countGenerateImage, 1, data.remove_bg);
                                            } else {
                                                processQueue(res.id, res.image, res.thumb, res.gallery, iteration, countGenerateImage, 0, data.remove_bg);
                                            }
                                        });
                                    }else{
                                        ajaxQueue = $({});
                                        processing = false;
                                        $("html, body").animate({ scrollTop: 0 }, "slow");
                                        $('#status_restore_e').show();
                                        $('#status_restore_e p').text($('#alert-text-no-images').val());
                                        $('#loader').hide();
                                        $('p.submit').show();
                                    }
                                }
                                if(data.hasErrors == true){
                                    ajaxQueue = $({});
                                    processing = false;
                                    $("html, body").animate({ scrollTop: 0 }, "slow");
                                    $('#status_restore_e').show();
                                    $('#status_restore_e p').text(data.error_msg);
                                    $('#loader').hide();
                                    $('p.submit').show();
                                }
                            }
                            if (process == 'save') {
                                if($('input[name="RemoveBG_ApiKey"]').val()!='') $('#apiwarning').hide();
                                $("html, body").animate({ scrollTop: 0 }, "slow");
                                $('.wc_remove_bg#status_s').show();
                                $('#loader').hide();
                                $('p.submit').show();
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                            $('#status_restore_e').show();
                            $('#status_restore_e p').text(textStatus + ' '+ errorThrown);
                            $('#loader').hide();
                            $('p.submit').show();
                        }
                    });
                } else {
                    alert($('#alert-text').val());//alert-text
                }
            }
        });

        $('.button-click').on('click', function (e) {
            $('.wc_remove_bg.notice').hide();
        });

        $('.wc_remove_bg-process-stop').on('click', function (e) {
            e.preventDefault();
            ajaxQueue = $({});
            processing = false;
            $('#loader').hide();
            $('p.submit').show();
            $('.RemoveBG_Background_img').attr('src', '').css('display', 'block');
            $('.wc_remove_bg-log-live').hide();
            $('.wc_remove_bg-process-stop').hide();

            $.ajax({
                type: "post",
                dataType: "json",
                url: ajaxurl,
                data: {
                    action: "User_Aborted",
                    RemoveBG_ID: $('.wc_remove_bg-process-stop').attr('data-id'),
                    _nonce: $('#_wpnonce').val(),
                    schk: $('input#schk').val()
                },
                success: function (data) {
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    if (data.hasErrors) {
                        $('#status_restore_w').hide();
                        $('#status_restore_e').show();
                        $('#status_restore_e p').text(data.msg);
                    } else {
                        $('#status_restore_w').hide();
                        $('#status_restore_d').show();
                        $('#status_restore_d p').text(data.msg);
                    }
                    $('#loader').hide();
                    $('p.submit').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    $('#status_restore_e').show();
                    $('#status_restore_e p').text(textStatus + ' '+ errorThrown);
                    $('#loader').hide();
                    $('p.submit').show();
                }
            })
        });

        $('#restore_backup').on('click', function (e) {
            e.preventDefault();
            var confirm = window.confirm($('#restore_backup_confirm').val());
            if(confirm) {
                $('#loader').show();
                $('#previewresult').hide();
                $('p.submit').hide();
                $('.wc_remove_bg.notice').hide();
                $('#status_restore_w').show();
                $('#status_restore_e').hide();
                $('#status_restore_d').hide();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: ajaxurl,
                    data: {
                        action: "Remove_BG_Restore_Backup",
                        _nonce: $('#_wpnonce').val(),
                        schk: $('input#schk').val()
                    },
                    success: function (data) {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        if (data.hasErrors) {
                            $('#status_restore_w').hide();
                            $('#status_restore_e').show();
                            $('#status_restore_e p').text(data.msg);
                        } else {
                            $('#status_restore_w').hide();
                            $('.block-count').hide();
                            $('#status_restore_d').show();
                            $('.button-click').addClass('d-none');
                        }
                        $('#loader').hide();
                        $('p.submit').show();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        $('#status_restore_e').show();
                        $('#status_restore_e p').text(textStatus + ' '+ errorThrown);
                        $('#loader').hide();
                        $('p.submit').show();
                    }
                })
            }
        });

        $('#delete_backup').on('click', function (e) {
            e.preventDefault();
            var confirm = window.confirm($('#delete_backup_confirm').val());
            if(confirm) {
                $('#loader').show();
                $('p.submit').hide();
                $('#previewresult').hide();
                $('.wc_remove_bg.notice').hide();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: ajaxurl,
                    data: {
                        action: "Delete_backup",
                        RemoveBG_Background: $('input[name="RemoveBG_Background"]:checked').val(),
                        RemoveBG_Background_Color: $('input[name="RemoveBG_Background_Color"]').val(),
                        _nonce: $('#_wpnonce').val(),
                        schk: $('input#schk').val()
                    },
                    success: function (data) {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        if (data.hasErrors) {
                            $('#status_restore_w').hide();
                            $('#status_restore_e').show();
                            $('#status_restore_e p').text(data.msg);
                        } else {
                            $('.button-click').addClass('d-none');
                            $('.block-count').hide();
                            $('#status_restore_w').hide();
                            $('#status_d').show();
                            $('#status_d p').text(data.msg);
                        }
                        $('#loader').hide();
                        $('p.submit').show();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        $('#status_restore_e').show();
                        $('#status_restore_e p').text(textStatus + ' '+ errorThrown);
                        $('#loader').hide();
                        $('p.submit').show();
                    }
                })
            }
        });

        $('#startpreview').on('click', function (e) {
            e.preventDefault();
            $('#loader').show();
            $('p.submit').hide();
            $('.wc_remove_bg.notice').hide();

            var file_data = $(".RemoveBG_Background_Image")[0].files[0];
            var form_data = new FormData();
            form_data.append("RemoveBG_file", file_data);
            form_data.append("action", "Preview_BG_Images");
            form_data.append("RemoveBG_ApiKey", $('input[name="RemoveBG_ApiKey"]').val());
            form_data.append("RemoveBG_Background", $('input[name="RemoveBG_Background"]:checked').val());
            form_data.append("RemoveBG_Background_Color", $('input[name="RemoveBG_Background_Color"]').val());
            form_data.append("RemoveBG_Background_fit_fill", $('input[name="RemoveBG_Background_fit_fill"]:checked').val());
            form_data.append("post_id", $('input[name="RemoveBG_TestProduct"]').val());
            form_data.append("_nonce", $('#_wpnonce').val());

            $.ajax({
                type: "post",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxurl,
                data: form_data,
                success: function (data) {
                    if (data.hasErrors) {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        $('#status_restore_w').hide();
                        $('#status_restore_e').show();
                        $('#status_restore_e p').text(data.msg);
                    } else {
                        $('.img-after-remove-bg').attr('src', data.file_after);
                        $('.img-before-remove-bg').attr('src', data.file_before);
                        $('#previewresult').show();
                        $('input[name="RemoveBG_TestProduct"]').val('');
                    }

                    $('#loader').hide();
                    $('p.submit').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    $('#status_restore_e').show();
                    $('#status_restore_e p').text(textStatus + ' '+ errorThrown);
                    $('#loader').hide();
                    $('p.submit').show();
                }
            });
        });



		var products = document.getElementsByName('RemoveBG_products');
		var products_inp = document.getElementsByName('RemoveBG_products_IDs')[0];
		for (var i = 0; i < products.length; i++) {
			products[i].addEventListener('change', function() {
				if (this.value=='specified') products_inp.style.visibility = "visible";	
				else products_inp.style.visibility = "hidden";	
				
			});
		}
		
    });

})( jQuery );
