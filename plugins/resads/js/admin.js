/**
 * Tabs
 */
jQuery(document).ready(function(){
    jQuery(".tabs-resads").tabs();
});
/**
 * Ajax Banner
 */
jQuery(document).ready(function(){
    jQuery("#banner-url").on( "focusout",function(){
        set_banner(jQuery(this).val());
    });
});
/**
 * WP Media Popup
 */
jQuery(document).ready(function($){
    var _custom_media = true,
    _orig_send_attachment = wp.media.editor.send.attachment;

    $('.upload-button').on( "click",function(e) {
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        var id = button.attr('id').replace('_button', '');
        _custom_media = true;
        wp.media.editor.send.attachment = function(props, attachment){
            if ( _custom_media ) {
                $("#"+id).val(attachment.url);
                set_banner(attachment.url, attachment.width, attachment.height);
                $(".ad_banner_width").val(attachment.width);
                $(".ad_banner_height").val(attachment.height);
            } else {
                return _orig_send_attachment.apply( this, [props, attachment] );
            };
        };
        wp.media.editor.open(button);
        return false;
    });
    
    $('.add_media').on('click', function(){
        _custom_media = false;
    });
});
/**
 * Checks and unchecked adspots
 */
jQuery(document).ready(function(){
    jQuery('.ad_spot_checkbox').on( "click",function(){
        var id = jQuery(this).attr('id');
        if(this.checked)
        {
            jQuery("." + id).each(function(){
                jQuery(this).attr('checked', true);
            });
        }
        else
        {
            jQuery("." + id).each(function(){
                jQuery(this).attr('checked', false);
            });
        }
    });
});
/**
 * Start chosen
 */
jQuery(document).ready(function(){
    jQuery("#adspot_banner").chosen();
});
/**
 * Check Banner if exist
 * @param {string} banner_url
 * @returns {undefined}
 */
function set_banner(banner_url, width, height)
{
    jQuery("#show-banner").html('');
    if(banner_url != '')
    {
        var target_url = jQuery('#target-url').val();
        if(target_url != '')
        {
            var show_banner = '<a href="' + target_url + '" target="_blank"><img src="' + banner_url + '" width="' + width + '" height="' + height + '" /></a>';
        }
        else
        {
            var show_banner = '<img src="' + banner_url + '" width="' + width + '" height="' + height + '" />';
        }
        jQuery("#show-banner").html(show_banner);
    }
}