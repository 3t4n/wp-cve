
jQuery(document).ready(function() {

    jQuery('.upload_buttons').click(function() {
        formfieldID = jQuery(this).attr('id')+'_field';
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    window.send_to_editor = function(html) {
        attachmentID = jQuery('img',html).attr('src');
        jQuery('#'+formfieldID).val(attachmentID);
        tb_remove();
    }

});
    