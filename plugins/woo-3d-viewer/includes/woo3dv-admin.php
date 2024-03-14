<?php
/**
 *
 *
 * @author Sergey Burkov, http://www.wp3dprinting.com
 * @copyright 2017
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}



add_action( 'admin_menu', 'register_woo3dv_menu_page' );
function register_woo3dv_menu_page() {
	add_menu_page( 'Woo 3D Viewer', 'Woo 3D Viewer', 'manage_options', 'woo3dv', 'register_woo3dv_menu_page_callback' );
}

function register_woo3dv_menu_page_callback() {
	global $wpdb;

	if ( $_GET['page'] != 'woo3dv' ) return false;
	if ( !current_user_can('administrator') ) return false;

	$settings=woo3dv_get_option( 'woo3dv_settings' );

	if ( isset( $_POST['woo3dv_settings'] ) && !empty( $_POST['woo3dv_settings'] )) {
		if ( ! isset( $_POST['woo3dv_save_settings'] ) || ! wp_verify_nonce( $_POST['woo3dv_save_settings'], 'update' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		}
	        $settings_update = array_map('sanitize_text_field', $_POST['woo3dv_settings']);

		if (isset($_FILES['woo3dv_settings']['tmp_name']['ajax_loader']) && strlen($_FILES['woo3dv_settings']['tmp_name']['ajax_loader'])>0) {
			$uploaded_file = woo3dv_upload_file('woo3dv_settings', 'ajax_loader');
			$settings_update['ajax_loader']=str_replace('http:','',$uploaded_file['url']);
		}
		else {
			$settings_update['ajax_loader']=$settings['ajax_loader'];
		}
		if (isset($_FILES['woo3dv_settings']['tmp_name']['view3d_button_image']) && strlen($_FILES['woo3dv_settings']['tmp_name']['view3d_button_image'])>0) {
			$uploaded_file = woo3dv_upload_file('woo3dv_settings', 'view3d_button_image');
			$settings_update['view3d_button_image']=str_replace('http:','',$uploaded_file['url']);
		}
		else {
			$settings_update['view3d_button_image']=$settings['view3d_button_image'];
		}
		update_option( 'woo3dv_settings', $settings_update );
		wp_redirect( admin_url( 'admin.php?page=woo3dv' ) );
	}


	$settings=woo3dv_get_option( 'woo3dv_settings' );

	add_thickbox(); 
#	woo3dv_check_install();

?>

<script language="javascript">

jQuery(document).ready(function() {
	jQuery('.woo3dv-tooltip').tooltipster({ contentAsHTML: true, multiple: true });
});
</script>
<div class="wrap">
	<h2><?php esc_html_e( 'Woo3DViewer Dashboard', 'woo3dv' );?></h2>
	<div id="woo3dv_tabs">
		<ul>
			<li><a href="#woo3dv_tabs-0"><?php esc_html_e( 'General Settings', 'pizzatime' );?></a></li>
			<li><a href="#woo3dv_tabs-1"><?php esc_html_e( 'Shortcode Builder', 'pizzatime' );?></a></li>
		</ul>
		<div id="woo3dv_tabs-0">
			<form method="post" action="admin.php?page=woo3dv#woo3dv_tabs-0" enctype="multipart/form-data">
				<input type="hidden" value="stl,obj,zip" name="file_extensions">
			<?php    wp_nonce_field( 'update', 'woo3dv_save_settings' ); ?>
				<hr>
				<p><b><?php esc_html_e( 'Default Settings', 'woo3dv' );?></b></p>
				<table>
					<tr>
						<td><?php esc_html_e( 'Canvas Resolution', 'woo3dv' );?></td>
						<td><input size="3" type="text"  placeholder="<?php esc_attr_e( 'Width', 'woo3dv' );?>" name="woo3dv_settings[canvas_width]" value="<?php echo (int)$settings['canvas_width'];?>">px &times; <input size="3"  type="text" placeholder="<?php esc_attr_e( 'Height', 'woo3dv' );?>" name="woo3dv_settings[canvas_height]" value="<?php echo (int)$settings['canvas_height'];?>">px</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Canvas Border', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[canvas_border]" value="0"><input type="checkbox" name="woo3dv_settings[canvas_border]" <?php if ($settings['canvas_border']=='on') echo 'checked';?>></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Shading', 'woo3dv' );?></td>
						<td>
							<select name="woo3dv_settings[shading]">
								<option <?php if ( $settings['shading']=='flat' ) echo 'selected';?> value="flat"><?php esc_html_e( 'Flat', 'woo3dv' );?></option>
								<option <?php if ( $settings['shading']=='smooth' ) echo 'selected';?> value="smooth"><?php esc_html_e( 'Smooth', 'woo3dv' );?></option>
							</select> 
							<img class="woo3dv-tooltip" data-title="<img src='<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/shading.jpg' ));?>'>" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/question.png' )); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Model Color', 'woo3dv' );?></td>
						<td><input type="text" class="woo3dv-color-picker" name="woo3dv_settings[model_default_color]" value="<?php echo esc_attr($settings['model_default_color']);?>"></td>
					</tr>
					<tr>
						<td><?php esc_html_e('Model Shininess', 'woo3dv');?></td>
						<td>
							<select name="woo3dv_settings[model_default_shininess]">
								<option <?php if ( $settings['model_default_shininess']=='plastic' ) echo 'selected';?> value="plastic"><?php esc_html_e('Plastic', 'woo3dv');?></option>
								<option <?php if ( $settings['model_default_shininess']=='wood' ) echo 'selected';?> value="wood"><?php esc_html_e('Wood', 'woo3dv');?></option>
								<option <?php if ( $settings['model_default_shininess']=='metal' ) echo 'selected';?> value="metal"><?php esc_html_e('Metal', 'woo3dv');?></option>
							</select>
						</td>
			
					</tr>
					<tr>
					<td><?php esc_html_e('Model Transparency', 'woo3dv');?></td>
						<td>
							<select name="woo3dv_settings[model_default_transparency]">
								<option <?php if ( $settings['model_default_transparency']=='opaque' ) echo 'selected';?> value="opaque"><?php esc_html_e('Opaque', 'woo3dv');?></option>
								<option <?php if ( $settings['model_default_transparency']=='resin' ) echo 'selected';?> value="resin"><?php esc_html_e('Resin', 'woo3dv');?></option>
								<option <?php if ( $settings['model_default_transparency']=='glass' ) echo 'selected';?> value="glass"><?php esc_html_e('Glass', 'woo3dv');?></option>
							</select>
						</td>
					</tr>

					<tr>
						<td><?php esc_html_e( 'Background Color', 'woo3dv' );?></td>
						<td><input type="text" class="woo3dv-color-picker" name="woo3dv_settings[background1]" value="<?php echo esc_attr($settings['background1']);?>"></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Fog Color', 'woo3dv' );?></td>
						<td><input type="text" class="woo3dv-color-picker" name="woo3dv_settings[fog_color]" value="<?php echo esc_attr($settings['fog_color']);?>"></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Ground Color', 'woo3dv' );?></td>
						<td><input type="text" class="woo3dv-color-picker" name="woo3dv_settings[ground_color]" value="<?php echo esc_attr($settings['ground_color']);?>"></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Grid Color', 'woo3dv' );?></td>
						<td><input type="text" class="woo3dv-color-picker" name="woo3dv_settings[grid_color]" value="<?php echo esc_attr($settings['grid_color']);?>"></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Light Sources', 'woo3dv' );?></td>
						<td>
							<table>
								<tr>
									<td><input type="hidden" name="woo3dv_settings[show_light_source8]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source8]" <?php if ($settings['show_light_source8']=='on') echo 'checked';?>></td>
									<td><input type="hidden" name="woo3dv_settings[show_light_source1]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source1]" <?php if ($settings['show_light_source1']=='on') echo 'checked';?>></td>
									<td><input type="hidden" name="woo3dv_settings[show_light_source2]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source2]" <?php if ($settings['show_light_source2']=='on') echo 'checked';?>></td>
								</tr>
								<tr>
									<td><input type="hidden" name="woo3dv_settings[show_light_source7]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source7]" <?php if ($settings['show_light_source7']=='on') echo 'checked';?>></td>
									<td><input type="hidden" name="woo3dv_settings[show_light_source9]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source9]" <?php if ($settings['show_light_source9']=='on') echo 'checked';?>></td>
									<td><input type="hidden" name="woo3dv_settings[show_light_source3]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source3]" <?php if ($settings['show_light_source3']=='on') echo 'checked';?>></td>
								</tr>
								<tr>
									<td><input type="hidden" name="woo3dv_settings[show_light_source6]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source6]" <?php if ($settings['show_light_source6']=='on') echo 'checked';?>></td>
									<td><input type="hidden" name="woo3dv_settings[show_light_source5]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source5]" <?php if ($settings['show_light_source5']=='on') echo 'checked';?>></td>
									<td><input type="hidden" name="woo3dv_settings[show_light_source4]" value="0"><input type="checkbox" name="woo3dv_settings[show_light_source4]" <?php if ($settings['show_light_source4']=='on') echo 'checked';?>></td>
								</tr>

							</table>

						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Light Sources (bottom)', 'woo3dv' );?>:</td>
						<td>
							<table>
								<tr>
									<td><input type="checkbox" disabled></td>
									<td><input type="checkbox" disabled></td>
									<td><input type="checkbox" disabled></td>
								</tr>
								<tr>
									<td><input type="checkbox" disabled></td>
									<td><input type="checkbox" disabled></td>
									<td><input type="checkbox" disabled></td>
								</tr>
								<tr>
									<td><input type="checkbox" disabled></td>
									<td><input type="checkbox" disabled></td>
									<td><input type="checkbox" disabled></td>
							</tr>
							</table>

						</td>
						<td>Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a></td>
						<td></td>
					</tr>

					<tr>
						<td><?php esc_html_e( 'Loading Image', 'woo3dv' );?></td>
						<td>
							<img class="woo3dv-preview" src="<?php echo esc_url($settings['ajax_loader']);?>">
							<input type="file" name="woo3dv_settings[ajax_loader]" accept="image/*">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'View3D Image', 'woo3dv' );?></td>
						<td>
							<img class="woo3dv-preview" src="<?php echo esc_url($settings['view3d_button_image']);?>">
							<input type="file" name="woo3dv_settings[view3d_button_image]" accept="image/*">
						</td>
					</tr>


					<tr>
						<td><?php esc_html_e( 'Default Rotation', 'woo3dv' );?></td>
						<td>X<input size="3" type="text"  placeholder="<?php esc_attr_e( 'X', 'woo3dv' );?>" name="woo3dv_settings[default_rotation_x]" value="<?php echo esc_attr((float)$settings['default_rotation_x']);?>">&deg;  Y<input size="3"  type="text" placeholder="<?php esc_attr_e( 'Y', 'woo3dv' );?>" name="woo3dv_settings[default_rotation_y]" value="<?php echo esc_attr((float)$settings['default_rotation_y']);?>">&deg;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Auto Rotation', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[auto_rotation]" value="0"><input type="checkbox" name="woo3dv_settings[auto_rotation]" <?php if ($settings['auto_rotation']=='on') echo 'checked';?>></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Auto Rotation Speed', 'woo3dv' );?></td>
						<td>
							<input name="woo3dv_settings[auto_rotation_speed]" type="number" min="1" max="10" step="1" value="<?php echo (int)$settings['auto_rotation_speed'];?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Auto Rotation Direction', 'woo3dv' );?></td>
						<td>
							<select name="woo3dv_settings[auto_rotation_direction]">
								<option <?php if ( $settings['auto_rotation_direction']=='cw' ) echo "selected";?> value="cw"><?php esc_html_e('Clockwise', 'woo3dv');?></option>
								<option <?php if ( $settings['auto_rotation_direction']=='ccw' ) echo "selected";?> value="ccw"><?php esc_html_e('Counter-Clockwise', 'woo3dv');?></option>
							</select>
						</td>
					</tr>

					<tr>
						<td><?php esc_html_e( 'Show Toolbar', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[show_controls]" value="0"><input type="checkbox" name="woo3dv_settings[show_controls]" <?php if ($settings['show_controls']=='on') echo 'checked';?>>
							<img class="woo3dv-tooltip" data-title="<?php esc_attr_e( 'Enables frontend tools: fullscreen, zoom, auto rotation, screenshot', 'woo3dv' );?>" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/question.png' )); ?>">
						</td>
					</tr>
					<tr>
						<td><?php _e( 'Zoom Distance Min.', 'woo3dv' );?></td>
						<td>
							<input name="woo3dv_settings[zoom_distance_min]" type="number" min="0" step="1" value="<?php echo (int)$settings['zoom_distance_min'];?>" style="width:5em;">%&nbsp;
							<img class="woo3dv-tooltip" data-title="<?php htmlentities(_e( 'How far you can zoom in. 0 - no limit', 'woo3dv' ));?>" src="<?php echo plugins_url( 'woo-3d-viewer/images/question.png' ); ?>">
						</td>
					</tr>

					<tr>
						<td><?php _e( 'Zoom Distance Max.', 'woo3dv' );?></td>
						<td>
							<input name="woo3dv_settings[zoom_distance_max]" type="number" min="0" step="1" value="<?php echo (int)$settings['zoom_distance_max'];?>" style="width:5em;">%&nbsp;
							<img class="woo3dv-tooltip" data-title="<?php htmlentities(_e( 'How far you can zoom out. 0 - no limit', 'woo3dv' ));?>" src="<?php echo plugins_url( 'woo-3d-viewer/images/question.png' ); ?>">
						</td>
					</tr>

					<tr>
						<td><?php _e( 'Enable Zooming', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[enable_zoom]" value="0"><input type="checkbox" name="woo3dv_settings[enable_zoom]" <?php if ($settings['enable_zoom']=='on') echo 'checked';?>>
							<img class="woo3dv-tooltip" data-title="<?php htmlentities(_e( 'Enables zooming by mouse or swipe', 'woo3dv' ));?>" src="<?php echo plugins_url( 'woo-3d-viewer/images/question.png' ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php _e( 'Enable Panning', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[enable_pan]" value="0"><input type="checkbox" name="woo3dv_settings[enable_pan]" <?php if ($settings['enable_pan']=='on') echo 'checked';?>>
							<img class="woo3dv-tooltip" data-title="<?php htmlentities(_e( 'Enables panning by mouse or swipe', 'woo3dv' ));?>" src="<?php echo plugins_url( 'woo-3d-viewer/images/question.png' ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php _e( 'Enable Manual Rotation', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[enable_manual_rotation]" value="0"><input type="checkbox" name="woo3dv_settings[enable_manual_rotation]" <?php if ($settings['enable_manual_rotation']=='on') echo 'checked';?>>
							<img class="woo3dv-tooltip" data-title="<?php htmlentities(_e( 'Enables manual rotation by mouse or swipe', 'woo3dv' ));?>" src="<?php echo plugins_url( 'woo-3d-viewer/images/question.png' ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Show Fog', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[show_fog]" value="0"><input type="checkbox" name="woo3dv_settings[show_fog]" <?php if ($settings['show_fog']=='on') echo 'checked';?>></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Show Shadows', 'woo3dv' );?></td>
						<td>
							<input type="hidden" name="woo3dv_settings[show_shadow]" value="0"><input type="checkbox" name="woo3dv_settings[show_shadow]" <?php if ($settings['show_shadow']=='on') echo 'checked';?>>
							<?php _e('Shadow Softness', 'woo3dv'); ?><input type="number" width="2" name="woo3dv_settings[shadow_softness]" min="1" max="20" step="1" value="<?php echo (int)$settings['shadow_softness'];?>">
							<img class="woo3dv-tooltip" data-title="<?php htmlentities(_e( 'Set higher value for softer shadows', 'woo3dv' ));?>" src="<?php echo plugins_url( 'woo-3d-viewer/images/question.png' ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Show Ground', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[show_ground]" value="0"><input type="checkbox" name="woo3dv_settings[show_ground]" <?php if ($settings['show_ground']=='on') echo 'checked';?>></td>
					</tr>

					<tr>
						<td><?php esc_html_e( 'Ground Mirror', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[ground_mirror]" value="0"><input type="checkbox" name="woo3dv_settings[ground_mirror]" <?php if ($settings['ground_mirror']=='on') echo 'checked';?>></td>
					</tr>

					<tr>
						<td><?php esc_html_e( 'Show Grid', 'woo3dv' );?></td>
						<td><input type="hidden" name="woo3dv_settings[show_grid]" value="0"><input type="checkbox" name="woo3dv_settings[show_grid]" <?php if ($settings['show_grid']=='on') echo 'checked';?>></td>
					</tr>
				</table>
				<hr>

				<p><b><?php esc_html_e( 'Other', 'woo3dv' );?></b></p>
				<table>

					<tr>
						<td><?php esc_html_e( 'Load On', 'woo3dv' );?></td>
						<td>
							<select name="woo3dv_settings[load_everywhere]">
								<option <?php if ( $settings['load_everywhere']=='0' ) echo "selected";?> value="0"><?php esc_html_e('WooCommerce products with a 3D model', 'woo3dv');?></option>
								<option <?php if ( $settings['load_everywhere']=='shortcode' ) echo "selected";?> value="shortcode"><?php esc_html_e('Pages with the shortcode and WooCommerce products with a 3D model', 'woo3dv');?></option>
								<option <?php if ( $settings['load_everywhere']=='on' ) echo "selected";?> value="on"><?php esc_html_e('Everywhere', 'woo3dv');?></option>
							</select>
							<img class="woo3dv-tooltip" data-title="<?php esc_attr_e( 'Loads css and js files on certain pages of the site.', 'woo3dv' );?>" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/question.png' )); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'File Chunk Size', 'woo3dv' );?></td>
						<td><input type="text" size="1" name="woo3dv_settings[file_chunk_size]" value="<?php echo esc_attr($settings['file_chunk_size']);?>">&nbsp;<?php esc_html_e('mb', 'woo3dv');?>
							<img class="woo3dv-tooltip" data-title="<?php esc_attr_e( 'Used for uploading WEBM and GIF files rendered by the plugin. Keep it under upload_max_filesize and post_max_size PHP directives.', 'woo3dv' );?>" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/question.png' )); ?>">
						</td>

					</tr>

					<tr>
						<td><?php esc_html_e( 'Skip animation on mobile devices', 'woo3dv' );?></td>
						<td>
							<input type="hidden" name="woo3dv_settings[mobile_no_animation]" value="0">
							<input type="checkbox" name="woo3dv_settings[mobile_no_animation]" <?php if ($settings['mobile_no_animation']=='on') echo 'checked';?>>
							<img class="woo3dv-tooltip" data-title="<?php esc_attr_e( 'Disable rotation on mobile devices for better performance.', 'woo3dv' );?>" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/question.png' )); ?>">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Model compression', 'woo3dv' );?></td>
						<td>
							<input type="hidden" name="woo3dv_settings[model_compression]" value="0">
							<input type="checkbox" name="woo3dv_settings[model_compression]" disabled>
							<img class="woo3dv-tooltip" data-title="<?php esc_attr_e( 'Compress models (ZIP) for faster loading.', 'woo3dv' );?>" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/question.png' )); ?>">&nbsp;
							<?php esc_html_e('Compress models larger than', 'woo3dv');?><input type="text" size="2" name="woo3dv_settings[model_compression_threshold]" disabled>&nbsp;<?php esc_html_e('mb', 'woo3dv');?>
							<?php _e('Compression ratio threshold', 'woo3dv');?><input type="number" size="2" min="1" max="99" step="1" disabled>&nbsp;%
							<img class="woo3dv-tooltip" data-title="<?php htmlentities(_e( 'Do not compress models with low compression ratio', 'woo3dv' ));?>" src="<?php echo plugins_url( 'woo-3d-viewer/images/question.png' ); ?>">&nbsp;
							<?php 
							if (!class_exists('ZipArchive')) echo '<p><span style="color:red;">'.esc_html__('PHP zip extension is not enabled. Contact your hosting tech support to enable it.').'</span></p>';
							?>
							&nbsp;Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a><br><br>
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Use cross domain proxy', 'woo3dv' );?></td>
						<td>
							<input type="hidden" name="woo3dv_settings[proxy]" value="0">
							<input type="checkbox" name="woo3dv_settings[proxy]" disabled>
							<img class="woo3dv-tooltip" data-title="<?php esc_attr_e( 'Proxy makes possible loading models from 3rd party sites.', 'woo3dv' );?>" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/question.png' )); ?>">&nbsp;
							<?php esc_html_e('Trusted domains', 'woo3dv');?>:<input type="text" size="30" name="woo3dv_settings[proxy_domains]" disabled>&nbsp;
							<img class="woo3dv-tooltip" data-title="<?php esc_attr_e( 'Comma delimited list of domains you are loading models from. Please include both www and non-www domains, i.e.: example1.com, www.example1.com', 'woo3dv' );?>" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/question.png' )); ?>">&nbsp;
							&nbsp;Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a><br><br>
						</td>
					</tr>
					<tr>
						<td><?php _e( 'Override shopping cart thumbnail', 'woo3dv' );?></td>
						<td>
							<input type="hidden" name="woo3dv_settings[override_cart_thumbnail]" value="0">
							<input type="checkbox" name="woo3dv_settings[override_cart_thumbnail]" <?php if ($settings['override_cart_thumbnail']=='on') echo 'checked';?>>
						</td>
					</tr>





				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes', 'woo3dv' ) ?>" />
				</p>
			</form>
		</div>  
		<div id="woo3dv_tabs-1">
<?php
			include_once('woo3dv-admin-shortcode-builder.php');
?>
		</div>
	</div><!-- woo3dv_tabs -->
</div> <!-- wrap -->
<?php

}

add_action( 'add_meta_boxes', 'woo3dv_add_meta_boxes', 30 );
function woo3dv_add_meta_boxes() {
	add_meta_box( 'woo3dv-product-model', __( 'Product model', 'woo3dv' ), 'woo3dv_meta_box_output', 'product', 'side', 'low' );
}

function woo3dv_meta_box_output () {

	$settings = get_option( 'woo3dv_settings' );
	$upload_dir = wp_upload_dir();
#print_r($upload_dir);
//var_dump(woo3dv_is_woo3dv((int)$_GET['post'] ));
	$product_model=$display_mode=$display_mode_mobile=$product_color=$product_background1=$product_background_transparency=$product_default_color=$product_fog_color=$product_grid_color=$product_ground_color=$product_shininess=$product_transparency=$product_mtl=$product_attachment_id=$rotation_x=$rotation_y=$rotation_z=$product_image_png=$product_image_gif=$product_video_webm=$product_model_extracted_path=$product_show_grid=$product_show_fog=$product_show_ground=$product_ground_mirror=$product_show_shadow=$product_auto_rotation=$product_view3d_button=$upload_url='';
	if ( isset($_GET['post']) && woo3dv_is_woo3dv((int)$_GET['post'] )) {
		$product_model = get_post_meta( (int)$_GET['post'], '_product_model', true );

		$display_mode = get_post_meta( (int)$_GET['post'], '_display_mode', true );
		$display_mode_mobile = get_post_meta( (int)$_GET['post'], '_display_mode_mobile', true );
		$product_color = get_post_meta( (int)$_GET['post'], '_product_color', true );
		if (!$product_color) $product_color = $settings['model_default_color'];
		$product_shininess = get_post_meta( (int)$_GET['post'], '_product_shininess', true );
		$product_transparency = get_post_meta( (int)$_GET['post'], '_product_transparency', true );
		$product_mtl = get_post_meta( (int)$_GET['post'], '_product_mtl', true );
		$product_attachment_id = get_post_meta( (int)$_GET['post'], '_product_attachment_id', true );
		$product_remember_camera_position = get_post_meta( (int)$_GET['post'], '_product_remember_camera_position', true );
		$product_camera_position_x = get_post_meta( (int)$_GET['post'], '_product_camera_position_x', true );
		$product_camera_position_y = get_post_meta( (int)$_GET['post'], '_product_camera_position_y', true );
		$product_camera_position_z = get_post_meta( (int)$_GET['post'], '_product_camera_position_z', true );
		$product_camera_lookat_x = get_post_meta( (int)$_GET['post'], '_product_camera_lookat_x', true );
		$product_camera_lookat_y = get_post_meta( (int)$_GET['post'], '_product_camera_lookat_y', true );
		$product_camera_lookat_z = get_post_meta( (int)$_GET['post'], '_product_camera_lookat_z', true );
		$product_controls_target_x = get_post_meta( (int)$_GET['post'], '_product_controls_target_x', true );
		$product_controls_target_y = get_post_meta( (int)$_GET['post'], '_product_controls_target_y', true );
		$product_controls_target_z = get_post_meta( (int)$_GET['post'], '_product_controls_target_z', true );
		$product_offset_z = get_post_meta( (int)$_GET['post'], '_product_offset_z', true );
		$product_model_extracted_path = get_post_meta( (int)$_GET['post'], '_product_model_extracted_path', true );
#var_dump($product_offset_z);
		

#		$product_show_fog = get_post_meta( (int)$_GET['post'], '_product_show_fog', true );
		$product_show_grid = get_post_meta( (int)$_GET['post'], '_product_show_grid', true );
		$product_show_ground = get_post_meta( (int)$_GET['post'], '_product_show_ground', true );
		$product_show_shadow = get_post_meta( (int)$_GET['post'], '_product_show_shadow', true );
		$product_background1 = get_post_meta( (int)$_GET['post'], '_product_background1', true );
		$product_background_transparency = get_post_meta( (int)$_GET['post'], '_product_background_transparency', true );
		$product_ground_mirror = get_post_meta( (int)$_GET['post'], '_product_ground_mirror', true );
		$product_fog_color = get_post_meta( (int)$_GET['post'], '_product_fog_color', true );
		$product_grid_color = get_post_meta( (int)$_GET['post'], '_product_grid_color', true );
		$product_ground_color = get_post_meta( (int)$_GET['post'], '_product_ground_color', true );
		$product_auto_rotation = get_post_meta( (int)$_GET['post'], '_product_auto_rotation', true );
		$product_view3d_button = get_post_meta( (int)$_GET['post'], '_product_view3d_button', true );

#		if (!$product_show_fog) $product_show_fog = $settings['show_fog'];
		//$product_default_color = $settings['model_default_color'];

		if (!$product_shininess) $product_shininess = $settings['model_default_shininess'];
		if (!$product_transparency) $product_transparency = $settings['model_default_transparency'];


		if (!$product_show_grid) $product_show_grid = $settings['show_grid'];
		if (!$product_show_ground) $product_show_ground = $settings['show_ground'];
		if (!$product_show_shadow) $product_show_shadow = $settings['show_shadow'];
		if (!$product_background1) $product_background1 = $settings['background1'];
		if (!$product_background_transparency) $product_background_transparency = 'off';
		if (!$product_fog_color) $product_fog_color = $settings['fog_color'];
		if (!$product_grid_color) $product_grid_color = $settings['grid_color'];
		if (!$product_ground_color) $product_ground_color = $settings['ground_color'];
		if (!$product_ground_mirror) $product_ground_mirror = $settings['ground_mirror'];
		if (!$product_auto_rotation) $product_auto_rotation = $settings['auto_rotation'];

		$product_show_light_source1 = get_post_meta( (int)$_GET['post'], '_product_show_light_source1', true );
		$product_show_light_source2 = get_post_meta( (int)$_GET['post'], '_product_show_light_source2', true );
		$product_show_light_source3 = get_post_meta( (int)$_GET['post'], '_product_show_light_source3', true );
		$product_show_light_source4 = get_post_meta( (int)$_GET['post'], '_product_show_light_source4', true );
		$product_show_light_source5 = get_post_meta( (int)$_GET['post'], '_product_show_light_source5', true );
		$product_show_light_source6 = get_post_meta( (int)$_GET['post'], '_product_show_light_source6', true );
		$product_show_light_source7 = get_post_meta( (int)$_GET['post'], '_product_show_light_source7', true );
		$product_show_light_source8 = get_post_meta( (int)$_GET['post'], '_product_show_light_source8', true );
		$product_show_light_source9 = get_post_meta( (int)$_GET['post'], '_product_show_light_source9', true );


		if (!$product_show_light_source1) $product_show_light_source1 = $settings['show_light_source1'];
		if (!$product_show_light_source2) $product_show_light_source2 = $settings['show_light_source2'];
		if (!$product_show_light_source3) $product_show_light_source3 = $settings['show_light_source3'];
		if (!$product_show_light_source4) $product_show_light_source4 = $settings['show_light_source4'];
		if (!$product_show_light_source5) $product_show_light_source5 = $settings['show_light_source5'];
		if (!$product_show_light_source6) $product_show_light_source6 = $settings['show_light_source6'];
		if (!$product_show_light_source7) $product_show_light_source7 = $settings['show_light_source7'];
		if (!$product_show_light_source8) $product_show_light_source8 = $settings['show_light_source8'];
		if (!$product_show_light_source9) $product_show_light_source9 = $settings['show_light_source9'];

		$rotation_x = (int)get_post_meta( (int)$_GET['post'], '_rotation_x', true );
		$rotation_y = (int)get_post_meta( (int)$_GET['post'], '_rotation_y', true );
		$rotation_z = (int)get_post_meta( (int)$_GET['post'], '_rotation_z', true );

		$product_image_png = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( get_the_ID(), '_product_image_png', true );
		$product_image_gif = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( get_the_ID(), '_product_image_gif', true );
		$product_video_webm = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( get_the_ID(), '_product_video_webm', true );
		$upload_url = dirname($product_model).'/';
	}
#echo dirname($product_model);
#print_r(get_post_meta( (int)$_GET['post']));
//var_dump($product_color);

?>
	<div>
		
		<input type="hidden" id="product_model" name="product_model" value="<?php echo esc_attr( $product_model ); ?>" />
		<input type="hidden" id="product_attachment_id" name="product_attachment_id" value="<?php echo esc_attr( $product_attachment_id ); ?>" />
		<input type="hidden" id="product_image_png" name="product_image_png" value="<?php echo esc_attr( $product_image_png ); ?>" />
		<input type="hidden" id="product_image_gif" name="product_image_gif" value="<?php echo esc_attr( $product_image_gif ); ?>" />
		<input type="hidden" id="product_video_webm" name="product_video_webm" value="<?php echo esc_attr( $product_video_webm ); ?>" />
		<input type="hidden" id="product_model_extracted_path" name="product_model_extracted_path" value="<?php echo esc_attr( $product_model_extracted_path ); ?>" />

<!--		<input type="hidden" id="product_show_fog" name="product_show_fog" value="<?php echo esc_attr( $product_show_fog ); ?>" />-->
		<input type="hidden" id="product_show_grid" name="product_show_grid" value="<?php echo esc_attr( $product_show_grid ); ?>" />
		<input type="hidden" id="product_show_ground" name="product_show_ground" value="<?php echo esc_attr( $product_show_ground ); ?>" />
		<input type="hidden" id="product_show_shadow" name="product_show_shadow" value="<?php echo esc_attr( $product_show_shadow ); ?>" />
		<input type="hidden" id="product_background1" name="product_background1" value="<?php echo esc_attr( $product_background1 ); ?>" />
		<input type="hidden" id="product_background_transparency" name="product_background_transparency" value="<?php echo esc_attr( $product_background_transparency ); ?>" />
<!--		<input type="hidden" id="product_default_color" name="product_default_color" value="<?php echo esc_attr( $product_default_color ); ?>" />-->
<!--
		<input type="hidden" name="product_color" id="woo3dv_model_color" value="<?php echo esc_attr($product_color);?>">
-->
		<input type="hidden" id="product_ground_mirror" name="product_ground_mirror" value="<?php echo esc_attr( $product_ground_mirror ); ?>" />
		<input type="hidden" id="product_fog_color" name="product_fog_color" value="<?php echo esc_attr( $product_fog_color ); ?>" />
		<input type="hidden" id="product_grid_color" name="product_grid_color" value="<?php echo esc_attr( $product_grid_color ); ?>" />
		<input type="hidden" id="product_ground_color" name="product_ground_color" value="<?php echo esc_attr( $product_ground_color ); ?>" />
		<input type="hidden" id="product_auto_rotation" name="product_auto_rotation" value="<?php echo esc_attr( $product_auto_rotation ); ?>" />
		<input type="hidden" id="product_view3d_button" name="product_view3d_button" value="<?php echo esc_attr( $product_view3d_button ); ?>" />
		<input type="hidden" id="product_main_image_data" name="product_main_image_data" value="" />



		<?php

		if ($product_model!='') {
#var_dump($product_image_png);
		?>
		<div id="woo3dv_dialog" style="display:none;">
		<div id="woo3dv-viewer" style="<?php if ($product_model=='') echo 'display:none;';?>">
			<canvas id="woo3dv-cv" width="<?php echo esc_attr($settings['canvas_width']);?>" height="<?php echo esc_attr($settings['canvas_height']);?>" class="<?php if ($settings['canvas_border']=='on') echo 'woo3dv-canvas-border';?>" style="max-width:90%;min-width:640px;min-height:480px;"></canvas>
		</div>
		<p style="display:none;" id="woo3dv-convert1" class="woo3dv-status2">&#9888; For best performance <a href="https://modelconverter.com/convert.html">convert</a> the model to GLB format</p>
		<p style="display:none;" id="woo3dv-convert2" class="woo3dv-status2">&#9888; For best performance <a href="https://myminifactory.github.io/stl2gltf/">convert</a> the model to GLB format</p>

		<div class="woo3dv-info">
			<table width="100%">
				<tr>
					<td width="30%">
						<p class="woo3dv-info" id="woo3dv-file-stats">
							<?php esc_html_e('File Size', 'woo3dv');?>&nbsp;<span id="woo3dv-file-stats-size">0</span>&nbsp;<?php esc_html_e('mb', 'woo3dv');?>&nbsp;
							<?php esc_html_e('Polygon Count', 'woo3dv');?>&nbsp;<span id="woo3dv-file-stats-polygons">0</span>
						</p>
					</td>
					<td width="50%">
						<p>
							<input id="woo3dv-remember-camera-position" type="checkbox" <?php if ($product_remember_camera_position=='1') echo 'checked'; ?> value="1">
							<label for="woo3dv-remember-camera-position">
							<?php esc_html_e('Remember camera position', 'woo3dv');?>
							</label>
						</p>
					</td>
				</tr>
			</table>
		</div>

		<p class="woo3dv-info woo3dv-panel-right">
			<pre id="woo3dv-console"></pre>
		</p>

		<div class="woo3dv-info" id="canvas-stats">

		</div>
		<div class="woo3dv-info">
			<table width="100%">
			<tr>
				<td>
					<button onclick="alert('Unlock in PRO version')" type="button"><?php esc_html_e('Repair', 'woo3dv');?></button>&nbsp;
					<button onclick="alert('Unlock in PRO version')" type="button"><?php esc_html_e('Reduce', 'woo3dv');?></button>
					&nbsp;
					<button onclick="woo3dvFitCameraToObject(woo3dv.camera, woo3dv.object, 1.2, woo3dv.controls);" type="button"><?php esc_html_e('Fit camera');?></button>
					&nbsp;
					<button onclick="woo3dvSaveProductImage();" type="button"><?php esc_html_e('Set as main image', 'woo3dv');?></button>
				</td>
				<td>
					<span class="woo3dv-x-axis"><?php esc_html_e('X','woo3dv');?>:</span>
					<input type="number" autocomplete="off" class="woo3dv-dim-input" min="-360" max="360" step="Any" value="<?php echo esc_attr($rotation_x);?>" id="rotation_x" > <span class="woo3dv-separator">&deg; </span> 
					<span class="woo3dv-y-axis"><?php esc_html_e('Y','woo3dv');?>:</span>
					<input type="number" autocomplete="off" class="woo3dv-dim-input" min="-360" max="360" step="Any" value="<?php echo esc_attr($rotation_y);?>" id="rotation_y" > <span class="woo3dv-separator">&deg; </span>
					<span class="woo3dv-z-axis"><?php esc_html_e('Z','woo3dv');?>:</span>
					<input type="number" autocomplete="off" class="woo3dv-dim-input" min="-360" max="360" step="Any" value="<?php echo esc_attr($rotation_z);?>" id="rotation_z" > <span class="woo3dv-separator">&deg; </span>
					<span class="woo3dv-z-offset"><?php esc_html_e('Z Offset','woo3dv');?>:</span>
					<input type="number" autocomplete="off" step="Any" value="" id="z_offset"> 
				</td>
			
			</tr>
			</table>
		</div>
		<p class="hide-if-no-js" id="woo3dv-canvas-instructions" style="<?php if ($display_mode=='3d_model') echo 'display:none;'?>">
			<?php esc_html_e('Adjust angle with the left mouse button. Zoom with the scroll button. Adjust camera position with the right mouse button.', 'woo3dv');?>
		</p>

		<p class="hide-if-no-js" id="woo3dv-rotation-controls">

		</p>
		<table>
		<tr>
			<td><?php esc_html_e('Color');?>:</td>
			<td><input type="text" class="woo3dv-color-picker" onchange="woo3dvChangeModelColor(this.value);" value="<?php echo esc_attr($product_color);?>" /></td>
			<td><?php esc_html_e('Background Color');?>:</td>
			<td><input type="text" id="woo3dv-background-color" class="woo3dv-color-picker woo3dv-background" onchange="woo3dvChangeBackgroundColor(this.value);" value="<?php echo esc_attr($product_background1);?>"></td>
			<td><?php esc_html_e('Transparent', 'woo3dv');?>:</td>
			<td>
				<input type="checkbox" id="woo3dv-background-transparency" onclick="woo3dvToggleAlpha(this);" <?php if ($product_background_transparency=='on') echo 'checked'; ?>>
			</td> 
		</tr>
		<tr>
			<td><?php esc_html_e('Shininess', 'woo3dv');?>:</td>
			<td>
				<select onchange="woo3dvSetCurrentShininess(this.value);">
					<option <?php if ( $product_shininess=='plastic') echo "selected";?> value="plastic"><?php esc_html_e('Plastic', 'woo3dv');?></option>
					<option <?php if ( $product_shininess=='wood' ) echo "selected";?> value="wood"><?php esc_html_e('Wood', 'woo3dv');?></option>
					<option <?php if ( $product_shininess=='metal' ) echo "selected";?> value="metal"><?php esc_html_e('Metal', 'woo3dv');?></option>
				</select>
			</td>
			<td><?php esc_html_e( 'Grid Color', 'woo3dv' );?>:</td>
			<td><input type="text" id="woo3dv-grid-color" class="woo3dv-color-picker woo3dv-grid" onchange="woo3dvChangeGridColor(this.value);" value="<?php echo esc_attr($product_grid_color);?>"></td>
			<td><?php esc_html_e( 'Show Grid', 'woo3dv' );?></td>
			<td><input type="checkbox" id="woo3dv-show-grid" onclick="woo3dvToggleGrid();" <?php if ($product_background_transparency=='on') echo 'disabled'; ?> <?php if ($product_background_transparency!='on' && $product_show_grid=='on') echo 'checked';?>></td>
<!--			<td><?php esc_html_e( 'Fog Color', 'woo3dv' );?>:</td>
			<td><input type="text" id="woo3dv-fog-color" class="woo3dv-color-picker woo3dv-fog" onchange="woo3dvChangeFogColor(this.value);" value="<?php echo $product_fog_color;?>"></td>
			<td><?php esc_html_e( 'Show Fog', 'woo3dv' );?></td>
			<td><input type="checkbox" id="woo3dv-show-fog" onclick="woo3dvToggleFog();" <?php if ($product_show_fog=='on') echo 'checked';?>></td>
-->

		</tr>
		<tr>
		<td><?php esc_html_e('Transparency', 'woo3dv');?>:</td>
			<td>
				<select onchange="woo3dvSetCurrentTransparency(this.value);">
					<option <?php if ( $product_transparency=='opaque') echo "selected";?> value="opaque"><?php esc_html_e('Opaque', 'woo3dv');?></option>
					<option <?php if ( $product_transparency=='resin' ) echo "selected";?> value="resin"><?php esc_html_e('Resin', 'woo3dv');?></option>
					<option <?php if ( $product_transparency=='glass' ) echo "selected";?> value="glass"><?php esc_html_e('Glass', 'woo3dv');?></option>
				</select>
			</td>
			<td><?php esc_html_e( 'Ground Color', 'woo3dv' );?>:</td>
			<td><input type="text" id="woo3dv-ground-color" class="woo3dv-color-picker woo3dv-ground" onchange="woo3dvChangeGroundColor(this.value);" value="<?php echo esc_attr($product_ground_color);?>"></td>
			<td><?php esc_html_e( 'Show Ground', 'woo3dv' );?></td>
			<td><input type="checkbox" id="woo3dv-show-ground" onclick="woo3dvToggleGround();" <?php if ($product_background_transparency=='on') echo 'disabled'; ?> <?php if ($product_background_transparency!='on' &&  $product_show_ground=='on') echo 'checked';?>></td>




		</tr>
		<tr>
			<td>
				<?php esc_html_e('Display mode', 'woo3dv');?>:
			</td>
			<td>
				<select onchange="woo3dvChangeDisplayMode(this.value, false)">
					<option <?php if ( $display_mode=='3d_model') echo "selected";?> value="3d_model"><?php esc_html_e('3D model', 'woo3dv');?></option>
					<option disabled <?php if ( $display_mode=='png_image') echo "selected";?> value="png_image"><?php esc_html_e('PNG image', 'woo3dv');?></option>
					<option disabled <?php if ( $display_mode=='gif_image') echo "selected";?> value="gif_image"><?php esc_html_e('GIF image', 'woo3dv');?></option>
					<option disabled <?php if ( $display_mode=='webm_video') echo "selected";?> value="webm_video"><?php esc_html_e('WEBM video', 'woo3dv');?></option>
				</select>
				<br>Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a><br><br>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>


		</tr>
		<tr>
			<td>
				<?php esc_html_e('Mobile display mode', 'woo3dv');?>:
			</td>
			<td>
				<select onchange="woo3dvChangeDisplayMode(this.value, true)">
					<option <?php if ( $display_mode_mobile=='3d_model') echo "selected";?> value="3d_model"><?php esc_html_e('3D model', 'woo3dv');?></option>
					<option disabled <?php if ( $display_mode_mobile=='png_image') echo "selected";?> value="png_image"><?php esc_html_e('PNG image', 'woo3dv');?></option>
					<option disabled <?php if ( $display_mode_mobile=='gif_image') echo "selected";?> value="gif_image"><?php esc_html_e('GIF image', 'woo3dv');?></option>
					<option disabled <?php if ( $display_mode_mobile=='webm_video') echo "selected";?> value="webm_video"><?php esc_html_e('WEBM video', 'woo3dv');?></option>
				</select>
				<br>Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a><br><br>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>


		</tr>
		<tr>
			<td><?php esc_html_e( 'Light Sources', 'woo3dv' );?>:</td>
			<td>
				<table>
					<tr>
						<td><input onclick="woo3dvToggleLightSource(8);" type="checkbox" id="woo3dv-show-light-source8" <?php if ($product_show_light_source8=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(1);" type="checkbox" id="woo3dv-show-light-source1" <?php if ($product_show_light_source1=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(2);" type="checkbox" id="woo3dv-show-light-source2" <?php if ($product_show_light_source2=='on') echo 'checked';?>></td>
					</tr>
					<tr>
						<td><input onclick="woo3dvToggleLightSource(7);" type="checkbox" id="woo3dv-show-light-source7" <?php if ($product_show_light_source7=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(9);" type="checkbox" id="woo3dv-show-light-source9" <?php if ($product_show_light_source9=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(3);" type="checkbox" id="woo3dv-show-light-source3" <?php if ($product_show_light_source3=='on') echo 'checked';?>></td>
					</tr>
					<tr>
						<td><input onclick="woo3dvToggleLightSource(6);" type="checkbox" id="woo3dv-show-light-source6" <?php if ($product_show_light_source6=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(5);" type="checkbox" id="woo3dv-show-light-source5" <?php if ($product_show_light_source5=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(4);" type="checkbox" id="woo3dv-show-light-source4" <?php if ($product_show_light_source4=='on') echo 'checked';?>></td>
					</tr>
				</table>

			</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Show Shadow', 'woo3dv' );?>:</td>
			<td><input onclick="woo3dvToggleShadow(this);" type="checkbox" id="woo3dv-show-shadow" <?php if ($product_show_shadow=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Show Mirror', 'woo3dv' );?>:</td>
			<td><input onclick="woo3dvToggleMirror();" type="checkbox" id="woo3dv-show-mirror" <?php if ($product_background_transparency=='on') echo 'disabled'; ?> <?php if ($product_background_transparency!='on' && $product_ground_mirror=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Camera Auto Rotation', 'woo3dv' );?>:</td>
			<td><input onclick="woo3dvToggleRotation(this.checked);" type="checkbox" id="woo3dv-auto-rotation" <?php if ($product_auto_rotation=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Auto Rotation Speed', 'woo3dv' );?></td>
			<td>
				<input name="woo3dv_settings[auto_rotation_speed]" type="number" min="1" max="10" step="1" value="<?php echo $settings['auto_rotation_speed'];?>">
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Auto Rotation Direction', 'woo3dv' );?></td>
			<td>
				<select name="woo3dv_settings[auto_rotation_direction]">
					<option <?php if ( $settings['auto_rotation_direction']=='cw' ) echo "selected";?> value="cw"><?php esc_html_e('Clockwise', 'woo3dv');?></option>
					<option <?php if ( $settings['auto_rotation_direction']=='ccw' ) echo "selected";?> value="ccw"><?php esc_html_e('Counter-Clockwise', 'woo3dv');?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Show View 3D Button', 'woo3dv' );?>:</td>
			<td><input type="checkbox" id="woo3dv-view3d-button" <?php if ($product_view3d_button=='on') echo 'checked';?>></td>
		</tr>


		</table>


		</div>

		<input type="hidden" id="woo3dv_model_url" value="<?php echo esc_attr($product_model);?>">
		<input type="hidden" id="woo3dv_model_mtl" value="<?php echo esc_attr(basename($product_mtl));?>">
		<input type="hidden" id="woo3dv_upload_url" value="<?php echo esc_attr( $upload_url ); ?>" />
		<input type="hidden" name="product_color" id="woo3dv_model_color" value="<?php echo esc_attr($product_color);?>">
		<input type="hidden" name="product_shininess" id="woo3dv_model_shininess" value="<?php echo esc_attr($product_shininess);?>">
		<input type="hidden" name="product_transparency" id="woo3dv_model_transparency" value="<?php echo esc_attr($product_transparency);?>">
		<input type="hidden" name="rotation_x" id="woo3dv_rotation_x" value="<?php echo esc_attr($rotation_x);?>">
		<input type="hidden" name="rotation_y" id="woo3dv_rotation_y" value="<?php echo esc_attr($rotation_y);?>">
		<input type="hidden" name="rotation_z" id="woo3dv_rotation_z" value="<?php echo esc_attr($rotation_z);?>">
		<input type="hidden" name="display_mode" id="woo3dv_display_mode" value="<?php echo esc_attr($display_mode);?>">
		<input type="hidden" name="display_mode_mobile" id="woo3dv_display_mode_mobile" value="<?php echo esc_attr($display_mode_mobile);?>">
		<input type="hidden" name="use_png_as_thumnbail" id="woo3dv_use_png_as_thumnbail" value="">
		<input type="hidden" name="product_remember_camera_position" value="<?php echo esc_attr( $product_remember_camera_position ); ?>" />
		<input type="hidden" name="product_camera_position_x" id="product_camera_position_x" value="<?php echo esc_attr( $product_camera_position_x ); ?>" />
		<input type="hidden" name="product_camera_position_y" id="product_camera_position_y"  value="<?php echo esc_attr( $product_camera_position_y ); ?>" />
		<input type="hidden" name="product_camera_position_z" id="product_camera_position_z"  value="<?php echo esc_attr( $product_camera_position_z ); ?>" />
		<input type="hidden" name="product_camera_lookat_x" id="product_camera_lookat_x" value="<?php echo esc_attr( $product_camera_lookat_x ); ?>" />
		<input type="hidden" name="product_camera_lookat_y" id="product_camera_lookat_y"  value="<?php echo esc_attr( $product_camera_lookat_y ); ?>" />
		<input type="hidden" name="product_camera_lookat_z" id="product_camera_lookat_z"  value="<?php echo esc_attr( $product_camera_lookat_z ); ?>" />
		<input type="hidden" name="product_controls_target_x" id="product_controls_target_x" value="<?php echo esc_attr( $product_controls_target_x ); ?>" />
		<input type="hidden" name="product_controls_target_y" id="product_controls_target_y" value="<?php echo esc_attr( $product_controls_target_y ); ?>" />
		<input type="hidden" name="product_controls_target_z" id="product_controls_target_z" value="<?php echo esc_attr( $product_controls_target_z ); ?>" />
		<input type="hidden" name="product_offset_z" id="product_offset_z" value="<?php echo esc_attr( $product_offset_z ); ?>" />
		<input type="hidden" name="product_show_light_source1" value="<?php echo esc_attr( $product_show_light_source1 ); ?>" />
		<input type="hidden" name="product_show_light_source2" value="<?php echo esc_attr( $product_show_light_source2 ); ?>" />
		<input type="hidden" name="product_show_light_source3" value="<?php echo esc_attr( $product_show_light_source3 ); ?>" />
		<input type="hidden" name="product_show_light_source4" value="<?php echo esc_attr( $product_show_light_source4 ); ?>" />
		<input type="hidden" name="product_show_light_source5" value="<?php echo esc_attr( $product_show_light_source5 ); ?>" />
		<input type="hidden" name="product_show_light_source6" value="<?php echo esc_attr( $product_show_light_source6 ); ?>" />
		<input type="hidden" name="product_show_light_source7" value="<?php echo esc_attr( $product_show_light_source7 ); ?>" />
		<input type="hidden" name="product_show_light_source8" value="<?php echo esc_attr( $product_show_light_source8 ); ?>" />
		<input type="hidden" name="product_show_light_source9" value="<?php echo esc_attr( $product_show_light_source9 ); ?>" />

<!--		<input type="hidden" name="product_show_fog" value="<?php echo esc_attr( $product_show_fog ); ?>" />-->
		<input type="hidden" name="product_show_grid" value="<?php echo esc_attr( $product_show_grid ); ?>" />
		<input type="hidden" name="product_show_ground" value="<?php echo esc_attr( $product_show_ground ); ?>" />
		<input type="hidden" name="product_show_shadow" value="<?php echo esc_attr( $product_show_shadow ); ?>" />
		<input type="hidden" name="product_background1" value="<?php echo esc_attr( $product_background1 ); ?>" />
		<input type="hidden" name="product_background_transparency" value="<?php echo esc_attr( $product_background_transparency ); ?>" />
		<input type="hidden" name="product_show_mirror" value="<?php echo esc_attr( $product_ground_mirror ); ?>" />
		<input type="hidden" name="product_fog_color" value="<?php echo esc_attr( $product_fog_color ); ?>" />
		<input type="hidden" name="product_grid_color" value="<?php echo esc_attr( $product_grid_color ); ?>" />
		<input type="hidden" name="product_ground_color" value="<?php echo esc_attr( $product_ground_color ); ?>" />
		<input type="hidden" name="product_auto_rotation" value="<?php echo esc_attr( $product_auto_rotation ); ?>" />
		<input type="hidden" name="product_view3d_button" value="<?php echo esc_attr( $product_view3d_button ); ?>" />






		<input type="hidden" id="woo3dv_canvas_width" value="<?php echo esc_attr($settings['canvas_width']);?>">
		<input type="hidden" id="woo3dv_canvas_height" value="<?php echo esc_attr($settings['canvas_height']);?>">
		<input type="hidden" id="woo3dv_model_image_url" value="">

		<input type="hidden" name="product_image_data" id="product_image_data" value="">
		<input type="hidden" name="product_gif_data" id="product_gif_data" value="">
		<input type="hidden" name="product_webm_data" id="product_webm_data" value="">

		<p class="hide-if-no-js">
			<button onclick="woo3dvPreview()" type="button"><?php esc_html_e('Edit/Preview','woo3dv');?></button>
		</p>
		<p class="hide-if-no-js">
		<div id="product_model_name" style="<?php if ($product_model=='') echo 'display:none;';?>">
		<?php
			echo '<a href="'.esc_url($product_model).'">'.esc_html(basename($product_model)).'</a>&nbsp;';
			echo '<a title="'.esc_attr__('Remove', 'woo3dv').'" href="javascript:woo3dv_remove_model();">&#10006;</a>';

		?>
		</div>


			
		</p>
		<?php
		}
		else {
?>
		<p class="hide-if-no-js">
			<button disabled type="button"><?php _e('Edit/Preview','woo3dv');?></button>
		</p>
<?php
		}
		?>
		<p class="hide-if-no-js">

			<br>


		</p>

		<p id="woo3dv_save_block" class="hide-if-no-js" style="display:none;">
			<span style="color:green;"><?php esc_html_e('Uploaded! You can now save the product.', 'woo3dv'); ?></span>
		</p>



		<p class="hide-if-no-js">
			<a title="<?php esc_html_e( 'Set model', 'woo3dv' ); ?>" href="javascript:;" id="set-model"><?php esc_html_e( 'Set model', 'woo3dv' ); ?></a>
		</p>
	</div>
<?php
}
?>