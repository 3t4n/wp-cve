<?php
if (!current_user_can('edit_posts')) wp_die( __('You do not have sufficient permissions to access this page.') );
wp_enqueue_media();
?>
<table class="form-table">
	<input type="hidden" name="product_token" />

    <tr valign="top">
        <th scope="row"><strong><label for="isell_simple_download_checkbox"><?php echo __('Enable Simple Download:','sell-digital-downloads'); ?></label><strong></th>
        <td align="left">
        <input name="isell_simple_download_checkbox" type="checkbox"<?php if(get_post_meta($post_id,'isell_simple_download_checkbox',true)=="yes") echo ' checked="checked"'; ?> value="yes"/>
        <p class="description"><?php echo __('If you are having issues with downloading files on your server then you should check this option. It will use a simpler download method that should work.','sell-digital-downloads'); ?></p>
    </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="product_file_name"><?php echo __('File Name:','sell-digital-downloads'); ?></label><strong></th>
       <td>
       		<input type="text" value="<?php echo get_post_meta($post_id,'product_file_name',true); ?>" id="isell_product_file_name" name="product_file_name" required class="regular-text" />
       		<p class="description"><?php echo __('File will be downloaded to the customer\'s computer with this name. Example: My-ebook.pdf (This field is Required if "Simple Download Option" is not enabled)','sell-digital-downloads'); ?></p>
        </td>
    </tr>
    <input type="hidden" value='<?php echo wp_create_nonce("isell_file_upload"); ?>' name="isell_file_upload_nonce" id="isell_file_upload_nonce" />
    <input type="hidden" value='<?php echo $post_id; ?>' name="post_id" id="isell_product_id" />
    <tr valign="top">
        <th scope="row"><strong><label for="product_file_url"><?php echo __('File URL:','sell-digital-downloads'); ?></label><strong></th>
        <td>
            <input type="text" value="<?php echo get_post_meta($post_id,'product_file_url',true); ?>" id="product_file_url" name="product_file_url" size="90" />
	    <br /><br />
            <input class="button-primary" id="product_file_upload_button" type="button" value="<?php echo __('Select file','sell-digital-downloads'); ?>" />
            <p class="description"><?php echo __('Enter the URL of your downloadable file or select one from Media Library','sell-digital-downloads'); ?></p>
        </td>
    </tr>

</table>
<script>
    jQuery(document).ready(function ($) {
    var selectFileFrame;
    // Run media uploader for thumbnail upload
    $('#product_file_upload_button').click(function (e) {
        e.preventDefault();
        selectFileFrame = wp.media({
            title: '<?php echo esc_js(__("Select file",'sell-digital-downloads'));?>',
            button: {
                text: '<?php echo esc_js(__("Insert",'sell-digital-downloads'));?>',
            },
            multiple: false
        });
        selectFileFrame.open();
        selectFileFrame.on('select', function () {
            var attachment = selectFileFrame.state().get('selection').first().toJSON();

            $('#product_file_url').val(attachment.url);
        });
        return false;
    });
    });
</script>
