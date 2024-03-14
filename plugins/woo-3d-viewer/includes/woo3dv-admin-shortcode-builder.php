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
$woo3dv_products = woo3dv_get_products();
if ($settings['load_everywhere']=='0') {
?>
	<p style="color:red;"><?php esc_html_e('Please set "Load On" option to "Shortcode" in General Settings!', 'woo3dv');?></p>
<?php
}
?>
<?php
if (isset($_GET['set_model']) && $_GET['set_model']=='1') {
?>
<script language="javascript">
jQuery(document).ready( function(){
	renderMediaUploader();
})

</script>
<?php
}
?>
	<input type="hidden" id="woo3dv_reload_url" value="<?php echo esc_url(admin_url( 'admin.php?page=woo3dv&set_model=1#woo3dv_tabs-1' )); ?>" />
	<input type="hidden" id="product_model" name="product_model" value="" />
	<input type="hidden" id="product_attachment_id" name="product_attachment_id" value="" />
	<input type="hidden" id="product_image_png" name="product_image_png" value="" />
	<input type="hidden" id="product_image_gif" name="product_image_gif" value="" />
	<input type="hidden" id="product_video_webm" name="product_video_webm" value="" />
	<input type="hidden" id="product_model_extracted_path" name="product_model_extracted_path" value="" />
	<input type="hidden" id="product_show_grid" name="product_show_grid" value="" />
	<input type="hidden" id="product_show_ground" name="product_show_ground" value="" />
	<input type="hidden" id="product_show_shadow" name="product_show_shadow" value="" />
	<input type="hidden" id="product_background1" name="product_background1" value="" />
	<input type="hidden" id="product_background_transparency" name="product_background_transparency" value="" />
	<input type="hidden" id="product_ground_mirror" name="product_ground_mirror" value="" />
	<input type="hidden" id="product_fog_color" name="product_fog_color" value="" />
	<input type="hidden" id="product_grid_color" name="product_grid_color" value="" />
	<input type="hidden" id="product_ground_color" name="product_ground_color" value="" />
	<input type="hidden" id="product_auto_rotation" name="product_auto_rotation" value="" />
	<input type="hidden" id="product_model" name="product_model" value="" />
	<input type="hidden" id="product_attachment_id" name="product_attachment_id" value="" />

	<input type="hidden" id="woo3dv_canvas_width" value="<?php echo (int)esc_attr($settings['canvas_width']);?>">
	<input type="hidden" id="woo3dv_canvas_height" value="<?php echo (int)esc_attr($settings['canvas_height']);?>">
	<input type="hidden" id="woo3dv_model_image_url" value="">

	<input type="hidden" name="product_image_data" id="product_image_data" value="">
	<input type="hidden" name="product_gif_data" id="product_gif_data" value="">
	<input type="hidden" name="product_webm_data" id="product_webm_data" value="">
	<div id="woo3dv_shortcode_popup" style="display:none;">
		<center>
		<p>
			<textarea id="woo3dv_init_from_shortcode" type="text" placeholder="<?php esc_attr_e('Paste the shortcode e.g.: [woo3dviewer model_url=...]', 'woo3dv');?>"></textarea>
		</p>
		<p>

				<button id="woo3dv-init-button" onclick="woo3dvInitFromShortcode();" class="button-secondary" type="button"><?php _e( 'Load', 'woo3dv' ); ?></button>
		</p>
		</center>

	</div>
	<div id="woo3dv_woocommerce_popup" style="display:none;">
<?php
	if ($settings['load_everywhere']=='0') {
	?>
		<p style="color:red;"><?php _e('Please set <a href="'.admin_url( "admin.php?page=woo3dv_settings#woo3dv_tabs-0" ).'">"Load On"</a> option to "Shortcode" or "Everywhere"!', 'woo3dv');?></p>
	<?php
	}
	if (count($woo3dv_products)==0) {
?>
		<p style="color:red;"><?php _e('No woo3dv products found! Please create one according to <a href="https://woo3dviewer.wp3dprinting.com/documentation/">WooCommerce usage section</a>.', 'woo3dv');?></p>
<?php
	}
?>
	<table>
		<tr>
			<td><?php _e('Product', 'woo3dv');?></td>
			<td>
<?php
			echo '<select id="woo3dv_bulk_product">';
			foreach ($woo3dv_products as $pid => $pname) {
				echo '<option value="'.$pid.'">'."#$pid ".esc_html($pname);
			}
			echo '</select>';

?>
			<a href="javascript:woo3dvEditProduct();"><?php _e('Edit', 'woo3dv');?></a>
			</td>
		</tr>

		<tr>
			<td><?php _e('Compatibility Mode', 'woo3dv');?></td>
			<td>
				<input type="checkbox" checked id="woo3dv_compatibility_mode">
				<img class="woo3dv-tooltip" data-title="<?php esc_attr_e( 'Fixes layout issues and theme incompatibilites.', 'woo3dv' );?>" src="<?php echo plugins_url( 'woo-3d-viewer/images/question.png' ); ?>">

			</td>
		</tr>

	</table>
	<p>
		<?php _e( 'Shortcode', 'woo3dv' ); ?>:<input id="woo3dv_shortcode_woocommerce" readonly size="50" onclick="this.select()" type="text">&nbsp;<button id="woo3dv-sg-button" onclick="woo3dvGenerateWooCommerceShortcode();" class="button-secondary" type="button"><?php _e( 'Generate', 'woo3dv' ); ?></button>
	</p>


	</div>



	<p>
	<table id="woo3dv-main-buttons">
	<tr>
		<td><button class="button-secondary" id="set-model" type="button"><?php _e( 'Set Model', 'woo3dv' ); ?></button></td><td> (.OBJ, .STL, .WRL, .GLTF, .GLB, .ZIP)</td>
	</tr>
	<tr>
		<td><button id="woo3dv-init-button" onclick="woo3dvShortcodePopup();" class="button-secondary" type="button"><?php _e( 'Load From Shortcode', 'woo3dv' ); ?></button></td><td><?php _e('Load the scene from the previously generated shortcode', 'woo3dv');?></td>
	</tr>
	<?php
	if ( class_exists( 'WooCommerce' ) ) {
	?>
	<tr>
		<td><button id="woo3dv-woocommerce-button" onclick="woo3dvWooCommercePopup();" class="button-secondary" type="button"><?php _e( 'Product Shortcode', 'woo3dv' ); ?></button></td><td><?php _e('Get the WooCommerce product shortcode', 'woo3dv');?></td>
	</tr>
	<?php
	}
	?>
	</table>

	</p>
	<table id="woo3dv-details">
	<tr>
		<td><?php esc_html_e( 'Shortcode', 'woo3dv' ); ?>:</td><td><input id="woo3dv_shortcode" readonly size="50" onclick="this.select()" type="text"></td><td><button id="woo3dv-sg-button" onclick="woo3dvGenerateShortcode();" class="button-secondary" type="button"><?php esc_html_e( 'Generate', 'woo3dv' ); ?></button></td>
	</tr>



	<tr>
		<td><?php esc_html_e( 'Thumbnail URL', 'woo3dv' ); ?>:</td><td><input id="woo3dv_thumbnail" size="50" onclick="this.select()" disabled type="text"></td><td>
			<button class="button-secondary" disabled id="set-thumbnail2" type="button"><?php esc_html_e( 'Set Thumbnail', 'woo3dv' ); ?></button> (.JPG, .PNG) 
			<button class="button-secondary" disabled id="generate-thumbnail" type="button"><?php esc_html_e( 'Generate Thumbnail', 'woo3dv' ); ?></button>
				&nbsp;Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a>
		</td>
	</tr>
	</table>


	<div id="woo3dv-viewer" style="display:none;">
		<p class="hide-if-no-js" id="woo3dv-canvas-instructions">
			<?php esc_html_e('Adjust angle with the left mouse button. Zoom with the scroll button. Adjust camera position with the right mouse button.', 'woo3dv');?>
		</p>

		<canvas id="woo3dv-cv" width="<?php echo esc_attr($settings['canvas_width']);?>" height="<?php echo esc_attr($settings['canvas_height']);?>" class="<?php if ($settings['canvas_border']=='on') echo 'woo3dv-canvas-border';?>" style="max-width:500px;max-height:500px;"></canvas>
		<div id="woo3dv-file-loading" style="display:none;">
			<img alt="Loading file" src="<?php echo esc_url($settings['ajax_loader']); ?>">
		</div>
		<p style="display:none;" id="woo3dv-convert1" class="woo3dv-status2">&#9888; For best performance <a href="https://modelconverter.com/convert.html">convert</a> the model to GLB format</p>
		<p style="display:none;" id="woo3dv-convert2" class="woo3dv-status2">&#9888; For best performance <a href="https://myminifactory.github.io/stl2gltf/">convert</a> the model to GLB format</p>

		<div class="woo3dv-info">
			<table cellpadding="5" class="woo3dv-option-container">
				<tr>
					<td>
						<p class="woo3dv-info" id="woo3dv-file-stats">
							<?php esc_html_e('File Size', 'woo3dv');?>&nbsp;<span id="woo3dv-file-stats-size">0</span>&nbsp;<?php esc_html_e('mb', 'woo3dv');?>&nbsp;
							<?php esc_html_e('Polygon Count', 'woo3dv');?>&nbsp;<span id="woo3dv-file-stats-polygons">0</span>
						</p>
					</td>
					<td>
						<p>
							<input id="woo3dv-remember-camera-position" type="checkbox" checked value="1">
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
			<p id="woo3dv-canvas-repair-status" style="display:none;">
				<br style="clear:both"> 
				<img id="woo3dv-canvas-repair-image" alt="Repairing" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/ajax-loader-small.gif')); ?>">
				<span id="woo3dv-canvas-repair-message"></span>
			</p>
			<p id="woo3dv-canvas-reduce-status" style="display:none;">
				<br style="clear:both">
				<img id="woo3dv-canvas-reduce-image" alt="Reducing" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/ajax-loader-small.gif')); ?>">
				<span id="woo3dv-canvas-reduce-message"></span>
			</p>
		</div>

		<div class="woo3dv-info">
			<table>
			<tr>
				<td>
					<button onclick="alert('Unlock in PRO version')" type="button"><?php esc_html_e('Repair', 'woo3dv');?></button>&nbsp;
					<button onclick="alert('Unlock in PRO version')" type="button"><?php esc_html_e('Reduce', 'woo3dv');?></button>&nbsp;
					<button onclick="woo3dvFitCameraToObject(woo3dv.camera, woo3dv.object, 1.2, woo3dv.controls);" type="button"><?php esc_html_e('Fit camera');?></button>
				</td>
				<td class="woo3dv-option-container">
					<span class="woo3dv-x-axis"><?php esc_html_e('X','woo3dv');?>:</span>
					<input type="number" autocomplete="off" class="woo3dv-dim-input" min="-360" max="360" step="90" value="0" id="rotation_x" > <span class="woo3dv-separator">&deg; </span> 
					<span class="woo3dv-y-axis"><?php esc_html_e('Y','woo3dv');?>:</span>
					<input type="number" autocomplete="off" class="woo3dv-dim-input" min="-360" max="360" step="90" value="0" id="rotation_y" > <span class="woo3dv-separator">&deg; </span>
					<span class="woo3dv-z-axis"><?php esc_html_e('Z','woo3dv');?>:</span>
					<input type="number" autocomplete="off" class="woo3dv-dim-input" min="-360" max="360" step="90" value="0" id="rotation_z" > <span class="woo3dv-separator">&deg; </span>
					<span class="woo3dv-z-offset"><?php esc_html_e('Z Offset','woo3dv');?>:</span>
					<input type="number" autocomplete="off" step="0.1" value="" id="z_offset"> 
				</td>
			</tr>
			</table>
		</div>

		<table class="woo3dv-option-container">
		<tr>
			<td><?php esc_html_e('Canvas Width', 'woo3dv');?>:</td>
			<td><input type="number" id="woo3dv-canvas-width" min="1" value="500" style="width:80px">px</td>
			<td><?php esc_html_e('Canvas Height', 'woo3dv');?>:</td>
			<td><input type="number" id="woo3dv-canvas-height" min="1" value="500" style="width:80px">px</td>
			<td><?php esc_html_e('Canvas Border', 'woo3dv');?>:</td>
			<td><input onclick="woo3dvToggleBorder();" type="checkbox" id="woo3dv-canvas-border" <?php if ($settings['canvas_border']=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td><?php esc_html_e('Color');?>:</td>
			<td><input type="text" id="woo3dv-model-color" class="woo3dv-color-picker" onchange="woo3dvChangeModelColor(this.value);" value="<?php echo esc_attr($settings['model_default_color']);?>" /></td>
			<td><?php esc_html_e('Background Color');?>:</td>
			<td><input type="text" id="woo3dv-background-color" class="woo3dv-color-picker woo3dv-background" onchange="woo3dvChangeBackgroundColor(this.value);" value="<?php echo esc_attr($settings['background1']);?>"></td>
			<td><?php _e('Transparent', 'woo3dv');?>:</td>
			<td><input type="checkbox" id="woo3dv-background-transparency" onclick="woo3dvToggleAlpha(this);"></td>
		</tr>
		<tr>
			<td><?php esc_html_e('Shininess', 'woo3dv');?>:</td>
			<td>
				<select id="woo3dv-model-shininess" onchange="woo3dvSetCurrentShininess(this.value);">
					<option <?php if ( $settings['model_default_shininess']=='plastic' ) echo 'selected';?> value="plastic"><?php esc_html_e('Plastic', 'woo3dv');?></option>
					<option <?php if ( $settings['model_default_shininess']=='wood' ) echo 'selected';?> value="wood"><?php esc_html_e('Wood', 'woo3dv');?></option>
					<option <?php if ( $settings['model_default_shininess']=='metal' ) echo 'selected';?> value="metal"><?php esc_html_e('Metal', 'woo3dv');?></option>

				</select>
			</td>
			<td><?php esc_html_e( 'Grid Color', 'woo3dv' );?>:</td>
			<td><input type="text" id="woo3dv-grid-color" class="woo3dv-color-picker woo3dv-grid" onchange="woo3dvChangeGridColor(this.value);" value="<?php echo esc_attr($settings['grid_color']);?>"></td>
			<td><?php esc_html_e( 'Show Grid', 'woo3dv' );?></td>
			<td><input type="checkbox" id="woo3dv-show-grid" onclick="woo3dvToggleGrid();" <?php if ($settings['show_grid']=='on') echo 'checked';?>></td>
<!--			<td><?php esc_html_e( 'Fog Color', 'woo3dv' );?>:</td>
			<td><input type="text" id="woo3dv-fog-color" class="woo3dv-color-picker woo3dv-fog" onchange="woo3dvChangeFogColor(this.value);" value="<?php echo $product_fog_color;?>"></td>
			<td><?php esc_html_e( 'Show Fog', 'woo3dv' );?></td>
			<td><input type="checkbox" id="woo3dv-show-fog" onclick="woo3dvToggleFog();" <?php if ($product_show_fog=='on') echo 'checked';?>></td>
-->

		</tr>
		<tr>
		<td><?php esc_html_e('Transparency', 'woo3dv');?>:</td>
			<td>
				<select id="woo3dv-model-transparency" onchange="woo3dvSetCurrentTransparency(this.value);">
					<option <?php if ( $settings['model_default_transparency']=='opaque' ) echo 'selected';?>  value="opaque"><?php esc_html_e('Opaque', 'woo3dv');?></option>
					<option <?php if ( $settings['model_default_transparency']=='resin' ) echo 'selected';?>  value="resin"><?php esc_html_e('Resin', 'woo3dv');?></option>
					<option <?php if ( $settings['model_default_transparency']=='glass' ) echo 'selected';?>  value="glass"><?php esc_html_e('Glass', 'woo3dv');?></option>
				</select>
			</td>
			<td><?php esc_html_e( 'Ground Color', 'woo3dv' );?>:</td>
			<td><input type="text" id="woo3dv-ground-color" class="woo3dv-color-picker woo3dv-ground" onchange="woo3dvChangeGroundColor(this.value);" value="<?php echo esc_attr($settings['ground_color']);?>"></td>
			<td><?php esc_html_e( 'Show Ground', 'woo3dv' );?></td>
			<td><input type="checkbox" id="woo3dv-show-ground" onclick="woo3dvToggleGround();" <?php if ($settings['show_ground']=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Display mode', 'woo3dv');?>:
			</td>
			<td>
				<select id="woo3dv-display-mode">
					<option selected value="3d_model"><?php esc_html_e('3D model', 'woo3dv');?></option>
<!--					<option value="png_image"><?php esc_html_e('PNG image', 'woo3dv');?></option>--> <!-- todo -->
					<option disabled value="gif_image"><?php esc_html_e('GIF image', 'woo3dv');?></option>
					<option disabled value="webm_video"><?php esc_html_e('WEBM video', 'woo3dv');?></option>
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
				<select id="woo3dv-display-mode-mobile">
					<option selected value="3d_model"><?php esc_html_e('3D model', 'woo3dv');?></option>
<!--					<option value="png_image"><?php esc_html_e('PNG image', 'woo3dv');?></option>--> <!-- todo -->
					<option disabled value="gif_image"><?php esc_html_e('GIF image', 'woo3dv');?></option>
					<option disabled value="webm_video"><?php esc_html_e('WEBM video', 'woo3dv');?></option>
				</select>
				<br>Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a><br><br>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>


		</tr>
		<tr>
			<td><?php esc_html_e( 'Light Sources (Top)', 'woo3dv' );?>:</td>
			<td>
				<table>
					<tr>
						<td><input onclick="woo3dvToggleLightSource(8);" type="checkbox" id="woo3dv-show-light-source8" <?php if ($settings['show_light_source8']=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(1);" type="checkbox" id="woo3dv-show-light-source1" <?php if ($settings['show_light_source1']=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(2);" type="checkbox" id="woo3dv-show-light-source2" <?php if ($settings['show_light_source2']=='on') echo 'checked';?>></td>
					</tr>
					<tr>
						<td><input onclick="woo3dvToggleLightSource(7);" type="checkbox" id="woo3dv-show-light-source7" <?php if ($settings['show_light_source7']=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(9);" type="checkbox" id="woo3dv-show-light-source9" <?php if ($settings['show_light_source9']=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(3);" type="checkbox" id="woo3dv-show-light-source3" <?php if ($settings['show_light_source3']=='on') echo 'checked';?>></td>
					</tr>
					<tr>
						<td><input onclick="woo3dvToggleLightSource(6);" type="checkbox" id="woo3dv-show-light-source6" <?php if ($settings['show_light_source6']=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(5);" type="checkbox" id="woo3dv-show-light-source5" <?php if ($settings['show_light_source5']=='on') echo 'checked';?>></td>
						<td><input onclick="woo3dvToggleLightSource(4);" type="checkbox" id="woo3dv-show-light-source4" <?php if ($settings['show_light_source4']=='on') echo 'checked';?>></td>
					</tr>
				</table>

			</td>
			<td></td>
			<td></td>
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
			<td><?php esc_html_e( 'Show Shadow', 'woo3dv' );?>:</td>
			<td><input onclick="woo3dvToggleShadow(this);" type="checkbox" id="woo3dv-show-shadow" <?php if ($settings['show_shadow']=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Show Mirror', 'woo3dv' );?>:</td>
			<td><input onclick="woo3dvToggleMirror();" type="checkbox" id="woo3dv-show-mirror" <?php if ($settings['ground_mirror']=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Auto Rotation', 'woo3dv' );?>:</td>
			<td><input onclick="woo3dvToggleRotation(this.checked);" type="checkbox" id="woo3dv-auto-rotation" <?php if ($settings['auto_rotation']=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Show Toolbar', 'woo3dv' );?>:</td>
			<td><input type="checkbox" id="woo3dv-show-controls" <?php if ($settings['show_controls']=='on') echo 'checked';?>></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Show View 3D Button', 'woo3dv' );?>:</td>
			<td><input type="checkbox" disabled id="woo3dv-view3d-button">&nbsp;Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a></td>
		</tr>
		</table>

			<div id="png_block" style="display:none">

			</div>
			<div id="gif_block" style="display:none;">

			</div>
			<div id="webm_block" style="display:none">

			</div>



	</div>