<?php

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div id="section_lightbox" class= "postbox">
        <div class="inside">
            <div id="design_customization_settings" class="format-settings">
                <div class="format-setting-wrap">
		
	<h2><?php _e('General','gallery-lightbox-slider');?></h2><hr>
    	
	<form id="glg_lightbox_form" method="post" action="#">

		<table class="tbl_custom">
        
<div class="glg-info">If you use Gallery Lightbox Lite and found it useful then please consider rating it and leaving your positive feedback <a href="https://wordpress.org/support/plugin/gallery-lightbox-slider/reviews/?filter=5" target="_blank" style="color: #06F !important;">here</a></div> 
        
        <tr valign="top" class="glg_tr">
        <th><?php _e('Enable Lightbox in All Post/Page', 'gallery-lightbox-slider');?></th>
        <td>
        
        <?php
		
		if ( get_option('glg_gallery_active') ) {
			
			$isactive = get_option('glg_gallery_active');
			
			} else {
				
				$isactive = 'active';
				
				}
		?>
<input class="ios-checkbox" type="checkbox" id="glg_gallery_active" <?php checked('active', $isactive);?> value='active' />
   <input name="glg_gallery_active" type="hidden" class="glg_gallery_active" value='<?php print( $isactive ); ?>' />     
        <p class="description"><?php  _e('Use this option to Enable or Disable the Lightbox in all Post or Page', 'gallery-lightbox-slider'); ?></p>
        </td>
        </tr>

		<tr valign="top" class="glg_tr">
        <th scope="row"><?php _e('Slideshow Auto Play', 'gallery-lightbox-slider');?></th>
        <td><input class="ios-checkbox" type="checkbox" id="glg_gallery_autoplay" <?php checked( 'true', get_option('glg_gallery_autoplay', 'true'));?>/>
        <input name="glg_gallery_autoplay" type="hidden" class="glg_gallery_autoplay" value='<?php print(get_option('glg_gallery_autoplay', 'true')); ?>' />
        <p class="description"><?php  _e('Should the lightbox gallery autoplay on start or not', 'gallery-lightbox-slider'); ?></p>
        </td>
        </tr>
        
		<tr valign="top" class="glg_tr">
        <th scope="row"><?php _e('Slideshow Interval', 'gallery-lightbox-slider');?></th>
        <td><select name="ecf_slide_every" id="ecf_slide_every">
							<?php
							
							if ( get_option('ecf_slide_every') != '' ) {
								
								$slideinterval = get_option('ecf_slide_every');
								
								} else {
									
									$slideinterval = '3';
									
									}
				
								foreach ( range( 1, 60 ) as $i ) {
									
									$every[$i] = $i;
						
										}
							
							foreach ( $every as $key => $value ): ?>
							<option value="<?php esc_attr_e( $key ); ?>"<?php esc_attr_e( $key == $slideinterval ? ' selected="selected"' : '' ); ?>><?php esc_attr_e( $value ); ?></option>
							<?php endforeach;?>
						</select> <span> seconds</span>
        <p class="description"><?php  _e('The time in seconds when autoplaying a gallery. Default 3 seconds', 'gallery-lightbox-slider'); ?></p>
        </td>
        </tr>

		<tr valign="top" class="glg_tr">
        <th scope="row"><?php _e('Show Thumbnails on Bottom', 'gallery-lightbox-slider');?></th>
        <td>
        <input class="ios-checkbox" type="checkbox" id="glg_gallery_thumbnails" <?php checked( 'true', get_option('glg_gallery_thumbnails', 'true'));?>/>
        <input name="glg_gallery_thumbnails" type="hidden" class="glg_gallery_thumbnails" value='<?php print(get_option('glg_gallery_thumbnails', 'true')); ?>' />
        <p class="description"><?php  _e('Use this option to show / hide gallery thumbnails', 'gallery-lightbox-slider'); ?></p>
        </td>
        </tr>
        
		<tr valign="top" class="glg_tr">
        <th scope="row"><?php _e('Show Thumbnails Caption', 'gallery-lightbox-slider');?></th>
        <td>
        <input class="ios-checkbox" type="checkbox" id="glg_gallery_show_captions" <?php checked( 'true', get_option('glg_gallery_show_captions', 'true'));?>/>
        <input name="glg_gallery_show_captions" type="hidden" class="glg_gallery_show_captions" value='<?php print(get_option('glg_gallery_show_captions', 'true')); ?>' />
        <p class="description"><?php  _e('Use this option to show / hide gallery thumbnails caption', 'gallery-lightbox-slider'); ?></p>
        </td>
        </tr>
        
		<tr valign="top" class="glg_tr">
        <th scope="row"><?php _e('Fancy Lightbox Caption', 'gallery-lightbox-slider');?></th>
        <td>
            <input class="ios-checkbox" type="checkbox" id="glg_gallery_fancy_caption" <?php checked( 'true', get_option('glg_gallery_fancy_caption', 'true'));?>/>
        <input name="glg_gallery_fancy_caption" type="hidden" class="glg_gallery_fancy_caption" value='<?php print(get_option('glg_gallery_fancy_caption', 'true')); ?>' />
        <p class="description"><?php  _e('If Enabled the plugin will automatically convert uppercase the first character of each word and replace - with spaces in a title. For example : ferrari-f12-berlinetta will change to Ferrari F12 Berlinetta', 'gallery-lightbox-slider'); ?></p>
        </td>
        </tr>
        
		<tr valign="top" class="glg_tr">
        <th scope="row"><?php _e('Overlay Color', 'gallery-lightbox-slider');?></th>
        <td><input type="text" name="glg_gallery_overlay_color" id="glg_gallery_overlay_color" maxlength="255" size="25" value="<?php print(get_option('glg_gallery_overlay_color', '#000000')); ?>" data-default-color="#000000">
        <p class="description"><?php  _e('Set your gallery overlay color. Default: #000000 ', 'gallery-lightbox-slider'); ?></p>
        </td>
        </tr>
        
         <tr valign="top">
         <td>
         <a href="https://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-gallery-and-photo-albums-demo/" target="_blank"><img src="<?php echo plugins_url( 'images/banners/next_level_banner.png', dirname( __FILE__ ) ); ?>" width="877" height="158" /></a>
         </td>
         </tr>
        
		</table>
	<table class="tbl_custom tbl_custom_save">
		<tr valign="top">
        <td><input data-formname="glg_lightbox_form" data-nonce="<?php echo wp_create_nonce( "glg_form_settings" ); ?>" type="submit" value="<?php _e('Save Changes'); ?>" class="button button-primary glg_form_submit" /><span id="loader_glg_lightbox_form"></span><span style="display:none;position:relative;color:#148919; margin-left:11px; top:6px;" class="set_glg_lightbox_form glg_save_status">Settings Saved</span></td>
        </tr>
		
		</table>

	</form>	

				</div>
			</div>
		</div>
    </div>