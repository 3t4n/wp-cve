<?php
if (!is_admin()) {
    die();
}
?><div class="wrap">
<h2><?php _e('Flickr Slideshow','flickr_slideshow'); ?></h2>
<?php if (strlen(get_option('fshow_flickr_api_key')) == 0): ?>
<div class="notice">
<?php echo sprintf(__('You must obtain a <a href="%s" target="_blank">Flickr API Key</a> to use the new version of Flickr Slideshow correctly.','flickr_slideshow'),'https://www.flickr.com/services/apps/create/apply'); ?>
</div>
<?php endif; ?>
<form method="post" action="options.php">
<?php
echo settings_fields( 'flickr_slideshow' );
?>
<h3><?php _e('Main Settings','flickr_slideshow'); ?></h3>
<table class="form-table">
    <tr valign="top">
        <td colspan="2">
            <p><?php _e('Be sure to fill these out','flickr_slideshow'); ?></p>
        </td>
    </tr> 
	<tr valign="top">
            <th scope="row"><label for="id_fshow_flickr_api_key"><?php _e('Flickr API Key','flickr_slideshow'); ?>:</label></th>
	    <td><input type="text" id="id_fshow_flickr_api_key" name="fshow_flickr_api_key" value="<?php echo get_option('fshow_flickr_api_key'); ?>" />
        <?php echo '('.__('required','flickr_slideshow').')'; ?>
        </td>
	</tr>
	<tr valign="top">
            <th scope="row"><label for="id_fshow_default_photosetid"><?php _e('Default Flickr Photosetid','flickr_slideshow'); ?>:</label></th>
	    <td><input type="text" id="id_fshow_default_photosetid" name="fshow_default_photosetid" value="<?php echo get_option('fshow_default_photosetid'); ?>" />
        <?php echo '('.sprintf(__('displays when shortcode is blank, i.e. %s','flickr_slideshow'), '<code>[fshow]</code>').')'; ?>
        </td>
	</tr>
	<tr valign="top">
            <th scope="row"><label for="id_fshow_default_width"><?php _e('Default Maximum Slideshow Width','flickr_slideshow'); ?>:</label></th>
	    <td><input type="text" id="id_fshow_default_width" name="fshow_default_width" value="<?php echo get_option('fshow_default_width'); ?>" />px</td>
	</tr>
	<tr valign="top">
            <th scope="row"><label for="id_fshow_default_height"><?php _e('Default Slideshow Height','flickr_slideshow'); ?>:</label></th>
	    <td><input type="text" id="id_fshow_default_height" name="fshow_default_height" value="<?php echo get_option('fshow_default_height'); ?>" />px</td>
	</tr>
</table>
<h3 id="fshow_legacy_header"><span id="fshow_legacy_toggle">&#45;</span>
<?php _e('Legacy Settings','flickr_slideshow'); ?></h3>
<table class="form-table" id="fshow_legacy">
    <tr valign="top">
        <td colspan="2">
            <p><?php echo '('.__('Optional for users since version 2.0 and above','flickr_slideshow').')'; ?></p>
        </td>
    </tr>
	<tr valign="top">
            <th scope="row"><label for="id_fshow_default_username"><?php _e('Default Flickr Username','flickr_slideshow'); ?>:</label></th>
	    <td><input type="text" id="id_fshow_default_username" name="fshow_default_username" value="<?php echo get_option('fshow_default_username'); ?>" /></td>
	</tr>
	<tr valign="top">
            <th scope="row"><label for="id_fshow_default_thumburl"><?php _e('Default Flickr Thumbnail URL','flickr_slideshow'); ?>:</label></th>
	    <td><input type="text" id="id_fshow_default_thumburl" name="fshow_default_thumburl" value="<?php echo get_option('fshow_default_thumburl'); ?>" /></td>
	</tr>
</table>
<h3 id="fshow_advanced_header"><span id="fshow_advanced_toggle">&#45;</span><?php _e('Advanced Settings','flickr_slideshow'); ?></h3>
<table class="form-table" id="fshow_advanced">
    <tr valign="top">
        <td colspan="2">
            <p><?php echo '('.__('Most users are unlikely to need to change these','flickr_slideshow').')'; ?></p>
        </td>
    </tr>
    <tr valign="top">
            <th scope="row"><label for="id_fshow_gallery_short_url"><?php _e('Use Short URLs for Gallery Links','flickr_slideshow'); ?>:</label></th>
	    <td><input type="checkbox" id="id_fshow_gallery_short_url" name="fshow_gallery_short_url" value="1" <?php if ( get_option('fshow_gallery_short_url') == "1" ) { echo 'checked="checked"'; } ?> />
            <?php _e('(Can cause problems in some implementations of PHP)','flickr_slideshow'); ?> 
        </td>
    </tr>
    <tr valign="top">
            <th scope="row"><label for="id_fshow_performance_mode"><?php _e('Performance Mode','flickr_slideshow'); ?>:</label></th>
	    <td><input type="checkbox" id="id_fshow_performance_mode" name="fshow_performance_mode" value="1" <?php if ( get_option('fshow_performance_mode') == "1" ) { echo 'checked="checked"'; } ?> />
            <?php _e('Do not include Wordpress header/footer code in the slideshow iframe (recommended)','flickr_slideshow'); ?> 
        </td>
    </tr>
	<tr valign="top">
            <th scope="row"><label for="id_fshow_flickr_cache_time"><?php _e('Flickr API Cache Time (seconds)','flickr_slideshow'); ?>:</label></th>
	    <td><input type="text" id="id_fshow_flickr_cache_time" name="fshow_flickr_cache_time" value="<?php if (get_option('fshow_flickr_cache_time')) echo get_option('fshow_flickr_cache_time'); else echo '3600'; ?>" /></td>
	</tr>
</table>
    <?php submit_button(); ?>
</form>
<script>
jQuery(document).ready( function() {
    function fshow_toggle( obj, target ) {
        if (jQuery( target ).is(':visible')) {
            jQuery( obj ).html('&#43;');
        } else {
            jQuery( obj ).html('&#45;');
        }
    }
    jQuery('#fshow_legacy_header').on('click', function() {
        fshow_toggle( jQuery('#fshow_legacy_toggle'), jQuery('#fshow_legacy'));
        jQuery('#fshow_legacy').slideToggle();
    });
    jQuery('#fshow_advanced_header').on('click', function() {
        fshow_toggle( jQuery('#fshow_advanced_toggle'), jQuery('#fshow_advanced'));
        jQuery('#fshow_advanced').slideToggle();
    });
    jQuery('#fshow_legacy_header').trigger('click');
    jQuery('#fshow_advanced_header').trigger('click');
});
</script>
</div>
