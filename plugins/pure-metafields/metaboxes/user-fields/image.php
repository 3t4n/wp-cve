<?php
/**
 * Image field for uploading images
 */
if( !defined('ABSPATH') ) exit;

$user_avatar = get_user_meta($user_id, $id, true)?? $default;
if(empty($user_avatar)){
    $avatar_url = TPMETA_URL . 'metaboxes/images/avatar.png';
}else{
    $avatar_url = $user_avatar;
}
?>
<tr>
    <th><?php echo esc_html($label); ?></th>
    <td>
        <input type="hidden" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($user_avatar); ?>" class="regular-text"/>
        <div class="tp-image-container">
            <div class="tp-image-box">
                <img src="<?php echo esc_url($avatar_url); ?>" alt="" id="<?php echo esc_attr($id); ?>-display">
                <span id="<?php echo esc_attr($id); ?>-del" class="tp-del-img">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                    </svg>
                </span>
            </div>
            <button class="button button-primary" type="button" id="<?php echo esc_attr($id); ?>-btn"><?php _e('Upload Image', 'pure-metafields'); ?></button>
        </div>
        <span class="description"><?php _e('Upload or select an image for the user.', 'pure-metafields'); ?></span>
    </td>
</tr>

<script>
    jQuery(document).ready(function($) {
        $('#<?php echo esc_attr($id); ?>-btn').click(function() {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            wp.media.editor.send.attachment = function(props, attachment) {
                $('#<?php echo esc_attr($id); ?>').val(attachment.url);
                $('#<?php echo esc_attr($id); ?>-display').attr("src", attachment.url);
                wp.media.editor.send.attachment = send_attachment_bkp;
            };
            wp.media.editor.open();
            return false;
        });

        $('#<?php echo esc_attr($id); ?>-del').click(function(){
            $('#<?php echo esc_attr($id); ?>').val('<?php echo esc_url(TPMETA_URL . 'metaboxes/images/avatar.png'); ?>');
            $('#<?php echo esc_attr($id); ?>-display').attr('src', '<?php echo esc_url(TPMETA_URL . 'metaboxes/images/avatar.png'); ?>');
        })
    });
</script>