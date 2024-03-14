<?php
class BeRocket_watermarks_media_generate_button {
    function __construct() {
        add_action( 'print_media_templates', array($this, 'print_media_templates') );
        add_action( 'attachment_submitbox_misc_actions', array($this, 'attachment_submitbox_metadata') );
    }
    function attachment_submitbox_metadata() {
        $post = get_post();
        $admin_ajax = admin_url('admin-ajax.php').'?action=berocket_single_image&id='.$post->ID.'&generation=';
        $meta = wp_get_attachment_metadata( $post->ID );
        $att_url = wp_get_attachment_url( $post->ID );
        list( $mime_type ) = explode( '/', $post->post_mime_type );
        if ( $mime_type === 'image' ) {
            echo '<div class="misc-pub-section" id="berocket_single_media_actions">
                <div class="berocket_single_image_actions" data-img="' . $att_url . '">' . __('Watermark options:', 'product-watermark-for-woocommerce')
                . '
                <a href="' . $admin_ajax.'restore' . '">' . __('Restore', 'product-watermark-for-woocommerce') . '</a>
                | <a href="' . $admin_ajax.'create' . '">' . __('Create', 'product-watermark-for-woocommerce') . '</a>
                </div>
                <div class="berocket_watermark_error_messages_single"></div>
                </div>';
            $this->print_media_templates();
        }
    }
    function print_media_templates() {
        /*$screen = get_current_screen();
        if( $screen->id != 'upload' ) return;*/
        $admin_ajax = admin_url('admin-ajax.php').'?action=berocket_single_image&id={{data.id}}&generation=';
        ?>
        <script>
            var BRreplace_to = '<# if ( \'image\' === data.type ) { #>';
            BRreplace_to += '<div class="berocket_single_image_actions" data-img="{{ data.url }}"><?php _e('Watermark options:', 'product-watermark-for-woocommerce'); ?> ';
            BRreplace_to += '<a href="<?php echo $admin_ajax.'restore'; ?>">';
            BRreplace_to += '<?php _e('Restore', 'product-watermark-for-woocommerce'); ?>';
            BRreplace_to += '</a>';
            BRreplace_to += ' | <a href="<?php echo $admin_ajax.'create'; ?>">';
            BRreplace_to += '<?php _e('Create', 'product-watermark-for-woocommerce'); ?>';
            BRreplace_to += '</a>';
            BRreplace_to += '<div class="berocket_watermark_error_messages_single"></div>';
            BRreplace_to += '</div>';
            BRreplace_to += '<# } #>';
            //Two column replace
            if( jQuery("#tmpl-attachment-details-two-column").length ) {
                var BRattachment_text = jQuery("#tmpl-attachment-details-two-column").html();
                var BRreplace_from = '<div class="actions">';
                BRattachment_text = BRattachment_text.replace(BRreplace_from, BRreplace_from+BRreplace_to);
                jQuery("#tmpl-attachment-details-two-column").html(BRattachment_text);
            }
            //Attachment details replace
            if( jQuery("#tmpl-attachment-details").length ) {
                var BRattachment_text = jQuery("#tmpl-attachment-details").html();
                var BRreplace_from = '<div class="compat-meta">';
                BRattachment_text = BRattachment_text.replace(BRreplace_from, BRreplace_from+BRreplace_to);
                jQuery("#tmpl-attachment-details").html(BRattachment_text);
            }

            var BRactions_ajax_loading = false;
            jQuery(document).on('click', '.berocket_single_image_actions a', function(event) {
                event.preventDefault();
                if( ! BRactions_ajax_loading ) {
                    if( jQuery('.berocket_watermark_error_messages_single').length ) {
                        jQuery('.berocket_watermark_error_messages_single').html('');
                    }
                    BRactions_ajax_loading = true;
                    var $this = jQuery(this);
                    var old_text = $this.html();
                    $this.html('<?php _e('Wait...', 'product-watermark-for-woocommerce'); ?>');
                    jQuery.get($this.attr('href'), function(data) {
                        $this.html(old_text);
                        var url = $this.parents('.berocket_single_image_actions').data('img');
                        url = url + '?time=' + new Date().getTime();
                        $this.parents('.attachment-details').first().find('.details-image').attr('src', url);
                        $this.parents('#poststuff').first().find('.wp_attachment_image .thumbnail').attr('src', url);
                        BRactions_ajax_loading = false;
                        if( jQuery('.berocket_watermark_error_messages_single').length && data ) {
                            jQuery('.berocket_watermark_error_messages_single').append('<div>'+data+'</div>');
                        }
                    }).fail(function(){
                        $this.html('<strong style="color:red;"><?php _e('Error', 'product-watermark-for-woocommerce'); ?></strong>');
                    });
                }
            });
            
            var html = '<div class="berocket_image_watermark_restore berocket_media_bulk">';
            html +=    '    <span><?php _e('Watermark Options:', 'product-watermark-for-woocommerce') ?></span>';
            html +=    '    <a class="button br_create_restore_image media" data-generation="create" data-image_list="create"><?php _e('Create', 'product-watermark-for-woocommerce') ?></a>';
            html +=    '    <a class="button br_create_restore_image media" data-generation="restore" data-image_list="restore"><?php _e('Restore', 'product-watermark-for-woocommerce') ?></a>';
            html +=    '    <span class="berocket_watermark_spin" style="display:none;">';
            html +=    '        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>';
            html +=    '        <a class="button br_create_restore_image_stop" style="color:red;" data-generation="restore"><?php _e('Stop', 'product-watermark-for-woocommerce') ?></a>';
            html +=    '    </span>';
            html +=    '    <span class="berocket_watermark_ready" style="display:none;"><i class="fa fa-check fa-3x fa-fw"></i></span>';
            html +=    '    <span class="berocket_watermark_error" style="display:none;"><i class="fa fa-times fa-3x fa-fw"></i></span>';
            html +=    '    <div class="berocket_watermark_load" style="display:none;"><div class="berocket_line"></div><div class="berocket_watermark_action"></div></div>';
            html +=    '<div class="berocket_watermark_error_messages"></div>';
            html +=    '</div>';
            jQuery(document).on('click', '.select-mode-toggle-button', function() {
                if( typeof(wp) != 'undefined'
                    && typeof(wp.media) != 'undefined'
                    && (
                        ( typeof(wp.media.frames) != 'undefined' 
                          && typeof(wp.media.frames.browse) != 'undefined' 
                          && typeof(wp.media.frames.browse.isModeActive) == 'function' 
                          && wp.media.frames.browse.isModeActive('select') 
                        )
                        ||
                        ( jQuery(this).parents('.media-frame').length
                          && jQuery(this).parents('.media-frame').first().is('.mode-select')
                        )
                    )
                ) {
                    jQuery('.media-toolbar-secondary').append(jQuery(html));
                } else {
                    jQuery('.berocket_image_watermark_restore.berocket_media_bulk').remove();
                }
            });
            if( jQuery('.upload-php #bulk-action-selector-top').length ) {
                jQuery('.upload-php #bulk-action-selector-top').parents('#posts-filter').find('.bulkactions').append(jQuery(html));
            }
            </script>
        <?php
    }
}
new BeRocket_watermarks_media_generate_button();
