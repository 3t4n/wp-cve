var br_saved_timeout;
var br_savin_ajax = false;
(function ($){
    $(document).ready( function () {
        function destroy_br_saved() {
            $('.br_saved').addClass('br_saved_remove');
            var $get = $('.br_saved');
            setTimeout( function(){$get.remove();}, 200 );
        }
        jQuery(document).on('click', '.attachments .attachment', function() {
            if( !br_savin_ajax ) {
                var data = $('.attachments-browser .attachments .attachment.selected');
                $('.berocket_image_watermark_restore').data('length', data.length);
                BeRocket_set_watermark_restore_position(data.length);
            }
        });
        function berocket_watermarks_ajax_start() {
            br_savin_ajax = true;
            clearTimeout(br_saved_timeout);
            destroy_br_saved();
            $('body').append('<span class="br_saved"><i class="fa fa-refresh fa-spin"></i></span>');
            $('.berocket_image_watermark_restore .berocket_watermark_ready').hide();
            $('.berocket_image_watermark_restore .berocket_watermark_error').hide();
            $('.berocket_image_watermark_restore .berocket_watermark_spin').show();
        }
        function berocket_watermarks_ajax_stop_error() {
            $('.berocket_image_watermark_restore .berocket_watermark_spin').hide();
            $('.berocket_image_watermark_restore .berocket_watermark_error').show();
            if($('.br_saved').length > 0) {
                $('.br_saved').addClass('br_not_saved').find('.fa').removeClass('fa-spin').removeClass('fa-refresh').addClass('fa-times');
            } else {
                $('body').append('<span class="br_saved br_not_saved"><i class="fa fa-times"></i></span>');
            }
            br_saved_timeout = setTimeout( function(){destroy_br_saved();}, 5000 );
            $('.br_save_error').html(data.responseText);
            br_savin_ajax = false;
        }
        $(document).on( 'click', '.br_create_restore_image.global', function(event) {
            event.preventDefault();
            var url = ajaxurl;
            if( !br_savin_ajax ) {
                berocket_watermarks_ajax_start();
                var image_list = $(this).data('image_list');
                var generation = $(this).data('generation');
                var form_data = 'action=berocket_get_watermark_images&generation='+image_list;
                if( jQuery('.berocket_watermark_error_messages').length ) {
                    jQuery('.berocket_watermark_error_messages').html('');
                }
                $.get(url, form_data, function (data) {
                    $('.berocket_image_watermark_restore').data('length', data.length);
                    BeRocket_set_watermark_restore_position(data.length);
                    $('.berocket_watermark_load').show();
                    berocket_restore_images(data, generation);
                }, 'json').fail(function(data) {
                    berocket_watermarks_ajax_stop_error();
                });
            }
        });
        $(document).on( 'click', '.br_create_restore_image.media', function(event) {
            event.preventDefault();
            if( !br_savin_ajax ) {
                if( jQuery('.berocket_watermark_error_messages').length ) {
                    jQuery('.berocket_watermark_error_messages').html('');
                }
                berocket_watermarks_ajax_start();
                var generation = $(this).data('generation');
                var data = [];
                $('.attachments-browser .attachments .attachment.selected').each(function() {
                    data.push($(this).data('id'));
                });
                $('#posts-filter #the-list .check-column input[name="media[]"]:checked').each(function() {
                    data.push($(this).val());
                });
                $('.berocket_image_watermark_restore').data('length', data.length);
                BeRocket_set_watermark_restore_position(data.length);
                $('.berocket_watermark_load').show();
                berocket_restore_images(data, generation);
            }
        });
        var berocket_restore_create_stop = false;
        $(document).on( 'click', '.br_create_restore_image_stop', function(event) {
            berocket_restore_create_stop = true;
        });
        function berocket_restore_images(images, generation) {
            var image_id = images.pop();
            if( typeof(image_id) == 'undefined' || berocket_restore_create_stop ) {
                berocket_restore_create_stop = false;
                $('.berocket_image_watermark_restore .berocket_watermark_spin').hide();
                $('.berocket_image_watermark_restore .berocket_watermark_ready').show();
                if($('.br_saved').length > 0) {
                    $('.br_saved .fa').removeClass('fa-spin').removeClass('fa-refresh').addClass('fa-check');
                } else {
                    $('body').append('<span class="br_saved"><i class="fa fa-check"></i></span>');
                }
                br_saved_timeout = setTimeout( function(){destroy_br_saved();}, 5000 );
                br_savin_ajax = false;
            } else {
                form_data = 'action=berocket_single_image&id='+image_id+'&generation='+generation;
                $.get(ajaxurl, form_data, function (data) {
                    BeRocket_set_watermark_restore_position(images.length);
                    berocket_restore_images(images, generation);
                    if( jQuery('.berocket_watermark_error_messages').length && data ) {
                        jQuery('.berocket_watermark_error_messages').append('<div>'+data+'</div>');
                    }
                }).fail(function(data) {
                    BeRocket_set_watermark_restore_position(images.length);
                    berocket_restore_images(images, generation);
                });
            }
        }
        function BeRocket_set_watermark_restore_position(images_left) {
            var images_count = $('.berocket_image_watermark_restore').data('length');
            var images_ready = images_count - images_left;
            position = 100 / images_count * images_ready;
            $('.berocket_watermark_load .berocket_line').finish().css('width', position+'%');
            $('.berocket_watermark_load .berocket_watermark_action').text(images_ready+' / '+images_count);
        }
        $(document).on('change', '.br_wm_img_count', br_wm_img_count_hide);
        function br_wm_img_count_hide() {
            $('.br_wm_img_count').each(function() {
                var wmimg = $(this).data('wmimg');
                    console.log('.'+wmimg+'.berocket_image_count_'+i);
                for(var i = 0; i < 5; i++) {
                    if( i > $(this).val() ) {
                        $('.'+wmimg+'.berocket_image_count_'+i).hide();
                    } else {
                        $('.'+wmimg+'.berocket_image_count_'+i).show();
                    }
                }
            });
        }
        br_wm_img_count_hide();
    });
})(jQuery);

(function ($){
    function drop_for_10(value) {
        if( value % 10 >= 5 ) {
            value += 10 - value % 10;
        } else {
            value -= value % 10;
        }
        return value;
    }
    $(document).ready( function () {
        if( jQuery( ".br_watermark" ).length ) {
            jQuery( ".br_watermark" )
            .resizable({
                containment: "parent",
                grid: 10,
                aspectRatio: 1 / 1,
                minHeight: 30,
                minWidth: 30,
                stop: function( event, ui ) {
                    var height = ui.size.height;
                    var width = ui.size.width;
                    var id = $(ui.element).data('id');
                    var parent = $(ui.element).parent();
                    var parent_height = parent.height();
                    var parent_width = parent.width();
                    height = drop_for_10(height);
                    width = drop_for_10(width);
                    parent_height = drop_for_10(parent_height);
                    parent_width = drop_for_10(parent_width);
                    var width_p = parseInt(width / parent_width * 100);
                    var height_p = parseInt(height / parent_height * 100);
                    $('.'+id+'_width').text(width_p);
                    $('.'+id+'_height').text(height_p);
                    $('.'+id+'_width_input').val(width_p);
                    $('.'+id+'_height_input').val(height_p);
                }
            })
            .draggable({
                containment: "parent", 
                scroll: false,
                grid: [10, 10],
                stop: function( event, ui ) {
                    var top = ui.position.top;
                    var left = ui.position.left;
                    var id = $(this).data('id');
                    var parent = $(this).parent();
                    var parent_top = parent.height();
                    var parent_left = parent.width();
                    top = drop_for_10(top);
                    left = drop_for_10(left);
                    parent_top = drop_for_10(parent_top);
                    parent_left = drop_for_10(parent_left);
                    var top_p = parseInt(top / parent_top * 100);
                    console.log(parent_top);
                    var left_p = parseInt(left / parent_left * 100);
                    $('.'+id+'_top').text(top_p);
                    $('.'+id+'_left').text(left_p);
                    $('.'+id+'_top_input').val(top_p);
                    $('.'+id+'_left_input').val(left_p);
                }
            });
        }
    });
})(jQuery);
