<?php

/**
 * Admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://fresh-d.biz/wocommerce-remove-background.html
 * @since      1.0.0
 *
 * @package    wc-remove-bg
 * @subpackage wc-remove-bg/admin/partials
 */
$log_file = false;
$wp_upload_dir = wp_upload_dir();
$log_file_dir = $wp_upload_dir['basedir'].'/remove-bg-log/log.txt';
if (file_exists($log_file_dir)) {
    $log_file = true;
}
global $wpdb;
$sql = "SELECT count(id) as count FROM `".$wpdb->prefix."wc_remove_bg_backup`";
$count = $wpdb->get_var($sql);

if ( !is_admin() || !current_user_can('edit_pages')) {
    return false;
}

?>

<div class="wrap">
    <h2><?php _e('Wocommerce remove background', 'wc-remove-bg') ?></h2>

	<?php if (!get_option('RemoveBG_ApiKey')): ?>
		<div id="apiwarning">
			<p><span class="bold">WARNING:</span> You have not entered your <a href="https://www.remove.bg/?aid=qzfprflpwxrcxmbm" target="_blank">remove.bg</a> API key. This plugin doesn't work without it. To obtain the API key, please follow next steps:
			<ol>
				<li>Sign up to <a target="_blank" href="https://www.remove.bg/?aid=qzfprflpwxrcxmbm">remove.bg</a> site by going <a target="_blank" href="https://www.remove.bg/users/sign_up/?aid=qzfprflpwxrcxmbm">here</a>. Skip this step if you have already signed up;</li>
				<li>Sign in to your account at <a target="_blank" href="https://www.remove.bg/?aid=qzfprflpwxrcxmbm">remove.bg</a> by going <a target="_blank" href="https://www.remove.bg/users/sign_in/?aid=qzfprflpwxrcxmbm">here</a>;</li>
				<li>Navigate to API key tab at your <a target="_blank" href="https://www.remove.bg/?aid=qzfprflpwxrcxmbm">remove.bg</a> profile by going <a target="_blank" href="https://www.remove.bg/profile#api-key/?aid=qzfprflpwxrcxmbm">here</a>;</li>
				<li>Click the button SHOW and copy-paste the revealed API-key into appropriate field of this plugin.</li>
			</ol>
			
			</p>
		</div>
	<?php endif; ?>


    <form method="post" action="options.php" id="RemoveBG_Form">
        <?php echo wp_nonce_field('update-options')  ?>
        <input type="hidden" value="<?php _e('Not all options selected', 'wc-remove-bg') ?>" id="alert-text"/>
        <input type="hidden" value="<?php _e('No images to process', 'wc-remove-bg') ?>" id="alert-text-no-images"/>
        <input type="hidden" value="<?php echo get_current_user_id(); ?>" id="schk"/>

        <table class="form-table">

            <tr valign="top">
                <th scope="row"><p class="tooltip"><?php _e('RemoveBG Api key ?', 'wc-remove-bg') ?><span class="tooltiptext"><?php _e('Get the API key from remove.bg profile', 'wc-remove-bg') ?></span></p></th>
                <td><input type="text" style="width: 500px" name="RemoveBG_ApiKey" value="<?php echo esc_attr(get_option('RemoveBG_ApiKey')) ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><p class="tooltip"><?php _e('Choose target products ?', 'wc-remove-bg') ?><span class="tooltiptext"><?php _e('Whether to process all products or only products with provided IDs', 'wc-remove-bg') ?></span></p></th>
                <td>
                    <input type="radio" id="products_all" name="RemoveBG_products" value="all" <?php echo checked( ('all' == get_option('RemoveBG_products') || 'specified' != get_option('RemoveBG_products')), true, false ) ?>/><label for="products_all"><?php _e('Remove background from all products', 'wc-remove-bg') ?></label><br>
                    <input type="radio" id="products_spec" name="RemoveBG_products" value="specified" <?php echo checked( 'specified' == get_option('RemoveBG_products'), true, false ) ?>/><label for="products_spec"><?php _e('Remove background only from specified products ', 'wc-remove-bg') ?></label><span class="desc"><?php _e('(IDs of products to process: comma separated or ranges, i.e. 3,9,20-27,40-45)', 'wc-remove-bg') ?></span>
                    <input type="text" style="width: 100%; <?php if('specified' != get_option('RemoveBG_products')) echo ' visibility:hidden'; ?>" placeholder="4,156,271" name="RemoveBG_products_IDs" value="<?php echo esc_attr(get_option('RemoveBG_products_IDs')) ?>" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><p class="tooltip"><?php _e('Choose target images ?', 'wc-remove-bg') ?><span class="tooltiptext"><?php _e('"Main image" - processes only main image of a product, "Product gallery" - processes only product gallery images. Check both to process all images of a product', 'wc-remove-bg') ?></span></p></th>
                <td>
                    <input type="checkbox" id="target_main" name="RemoveBG_thumbnail" value="1"<?php echo checked( 1 == get_option('RemoveBG_thumbnail'), true, false ) ?>/><label for="target_main"><?php _e('Main image', 'wc-remove-bg') ?></label><br>
                    <input type="checkbox" id="target_gallery" name="RemoveBG_gallery" value="1"<?php echo checked( 1 == get_option('RemoveBG_gallery'), true, false ) ?>/><label for="target_gallery"><?php _e('Product gallery', 'wc-remove-bg') ?></label><br>
                </td>
            </tr>
			<tr valign="top">
                <th scope="row"><p class="tooltip"><?php _e('Include processed images ?', 'wc-remove-bg') ?><span class="tooltiptext"><?php _e('By default, plugin processes each image only once. If checked, plugin will not skip earlier processed images and will overwrite them', 'wc-remove-bg') ?></span></p></th>
                <td>
                    <input type="checkbox" name="RemoveBG_Include_Processed" value="1"<?php echo checked( 1 == get_option('RemoveBG_Include_Processed'), true, false ) ?>/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><p class="tooltip"><?php _e('Set desired output resolution ?', 'wc-remove-bg') ?><span class="tooltiptext"><?php _e('Maximum output image resolution: "Auto" = Use highest available resolution (based on image size and available credits), "Preview" = Resize image to 0.25 megapixels (e.g. 625×400 pixels), "Full" = Use original image resolution, up to 10 megapixels (e.g. 4000x2500). ', 'wc-remove-bg') ?></span></p></th>
                <td>
                    <input type="radio" id="out_auto" name="RemoveBG_Preserve_Resize" value="auto" <?php echo checked( 'auto' == get_option('RemoveBG_Preserve_Resize'), true, false ) ?>/><label for="out_auto"><?php _e('Auto', 'wc-remove-bg') ?></label><br>
                    <input type="radio" id="out_preview" name="RemoveBG_Preserve_Resize" value="preview" <?php echo checked( 'preview' == get_option('RemoveBG_Preserve_Resize'), true, false ) ?>/><label for="out_preview"><?php _e('Preview', 'wc-remove-bg') ?></label><br>
                    <input type="radio" id="out_full" name="RemoveBG_Preserve_Resize" value="full" <?php echo checked( 'full' == get_option('RemoveBG_Preserve_Resize'), true, false ) ?>/><label for="out_full"><?php _e('Full', 'wc-remove-bg') ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><p class="tooltip"><?php _e('Make new background ?', 'wc-remove-bg') ?><span class="tooltiptext"><?php _e('"Transparent" - processed images will have transarent background, "Color" - sets chosen color as new background of processed images, "Custom image" - sets your image as new background of processed images', 'wc-remove-bg') ?></span></p></th>
                <td>
                    <input type="radio" id="newbg_transp" name="RemoveBG_Background" value="transparent " <?php echo checked( ('transparent' == get_option('RemoveBG_Background')||'color' != get_option('RemoveBG_Background')||'image' != get_option('RemoveBG_Background')), true, false ) ?>/><label for="newbg_transp"><?php _e('Transparent', 'wc-remove-bg') ?></label><br>
                    <input type="radio" id="newbg_color" name="RemoveBG_Background" value="color" <?php echo checked( 'color' == get_option('RemoveBG_Background'), true, false ) ?>/><label for="newbg_color"><?php _e('Color', 'wc-remove-bg') ?></label><br>
                    <input type="text" name="RemoveBG_Background_Color" value="<?php echo esc_attr(get_option('RemoveBG_Background_Color')) ?>"/>
                    <input type="radio" id="newbg_image" name="RemoveBG_Background" value="image" <?php echo checked( 'image' == get_option('RemoveBG_Background'), true, false ) ?>/><label for="newbg_image"><?php _e('Custom image', 'wc-remove-bg') ?></label><br>
                    <div class="fit_fill">
                        <img src="" class="RemoveBG_Background_img">
                        <input type="file" name="RemoveBG_Background_Image" class="RemoveBG_Background_Image">
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><p class="tooltip"><?php _e('Preview a product ?', 'wc-remove-bg') ?><span class="tooltiptext"><?php _e('You can test and preview the background removal for any product before starting actual process. Enter ID of a product to see its main image after backround removal process with current settings. This will not affect actual image of the product at your site', 'wc-remove-bg') ?></span></p></th>
				<td>
				<p><?php _e('Enter a product id to preview result', 'wc-remove-bg') ?></p>
				<input type="text" style="width: 50px" name="RemoveBG_TestProduct" value="" /> <input type="button" class="button-primary button-click" id="startpreview" value="Preview">
				<div id="previewresult" style="display:none;"><img src="" class="img-before-remove-bg"/> -> <img src="" class="img-after-remove-bg"/>
				</td>	
            </tr>
        </table>
        <div id="loader">
            <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px" viewBox="0 0 128 16" xml:space="preserve"><path fill="#949494" fill-opacity="0.42" d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z"/><g><path fill="#000000" fill-opacity="1" d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z"/><animateTransform attributeName="transform" type="translate" values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0" calcMode="discrete" dur="1170ms" repeatCount="indefinite"/></g></svg>

        </div>
		<p class="submit">
            <input type="submit" class="button-primary button-click save" value="<?php _e('Save settings', 'wc-remove-bg') ?>" />
            <input type="submit" class="button-primary button-click start" value="<?php _e('Start background removal', 'wc-remove-bg') ?>" />

            <input type="submit" class="btn btn-warning button-click <?php if($count == 0) { ?>d-none<?php } ?> " id="restore_backup" value="<?php _e('Restore backup', 'wc-remove-bg') ?>" />
            <input type="submit" class="btn btn-danger button-click <?php if($count == 0) { ?>d-none<?php } ?> " id="delete_backup"  value="<?php _e('Delete backup', 'wc-remove-bg') ?>" />
            <input type="hidden"  id="restore_backup_confirm" value="<?php _e('This will restore your original images. Do you want to continue?', 'wc-remove-bg') ?>">
            <input type="hidden" id="delete_backup_confirm" value="<?php _e('This will permanently delete your original images. Do you want to continue?', 'wc-remove-bg')?>">
			<div class="block-count" <?php if($count == 0){ ?>style="display:none;" <?php } ?> > <?php _e('Images backed up - ', 'wc-remove-bg'); echo '<span>'.$count.'</span>'; ?></div>
        </p>
        <div class="wc_remove_bg-log-live">

        </div>
        <div class="wc_remove_bg-process-stop">
	        <?php _e('Abort process', 'wc-remove-bg') ?>
        </div>
        <div class="wc_remove_bg-log" <?php echo !$log_file?'style="display: none"':''; ?> >
            <a href="<?php echo $wp_upload_dir['baseurl']; ?>/remove-bg-log/log.txt" target="_blank" ><?php _e('View last log', 'wc-remove-bg') ?></a>
        </div>
    </form>
    <div class="bottomlinks">
        <a href="http://fresh-d.biz/wocommerce-remove-background.html" target="_blank">Description</a> | <a href="http://fresh-d.biz/wocommerce-remove-background.html#support" target="_blank">Support</a> | <a href="http://fresh-d.biz/about-us.html" target="_blank">Authors</a> | <a href="https://secure.wayforpay.com/payment/s7f497f68a340
" target="_blank">Donate</a> | <a href="https://fresh-d.biz/wocommerce-remove-background.html#feedback" target="_blank">Feedback</a>
    </div>

</div>
