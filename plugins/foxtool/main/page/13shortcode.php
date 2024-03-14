<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('SHORTCODE', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check13" data-target="play13" type="checkbox" name="foxtool_settings[shortcode]" value="1" <?php if ( isset($foxtool_options['shortcode']) && 1 == $foxtool_options['shortcode'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play13" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-arrow-right-to-bracket"></i> <?php _e('Shortcode content visible only to group of users', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[shortcode-s1]" value="1" <?php if ( isset($foxtool_options['shortcode-s1']) && 1 == $foxtool_options['shortcode-s1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable shortcode lock', 'foxtool'); ?></label>
	</p>
	<?php
	echo '<p><select name="foxtool_settings[shortcode-s11]">';
	echo '<option value="">Default</option>';
	foreach ($roles as $role_name => $role_info) {
		if ($role_name != 'administrator' && $role_name != 'editor') {
		if(isset($foxtool_options['shortcode-s11']) && $foxtool_options['shortcode-s11'] == $role_name) { $selected = 'selected="selected"'; } else { $selected = NULL; }
		echo '<option value="'. $role_name .'" '. $selected .'>'. $role_name .'</option>';
		}
	}
	echo '</select></p>';
	?>
	<p>
	<textarea style="height:100px;" class="ft-code-textarea" name="foxtool_settings[shortcode-s12]" placeholder="<?php _e('Enter note content', 'foxtool'); ?>"><?php if(!empty($foxtool_options['shortcode-s12'])){echo esc_textarea($foxtool_options['shortcode-s12']);} ?></textarea>
	</p>
	<input class="ft-input-big ft-view-in" type="text" value="[vip] <?php _e('Content to be hidden', 'foxtool'); ?> [/vip]"/>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This shortcode allows you to lock any content, and only the selected group of logged-in users can view it', 'foxtool'); ?></p>
	
  <h3><i class="fa-regular fa-signature"></i> <?php _e('Signature shortcode', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[shortcode-s2]" value="1" <?php if ( isset($foxtool_options['shortcode-s2']) && 1 == $foxtool_options['shortcode-s2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable signature shortcode', 'foxtool'); ?></label>
	</p>
	<div class="ft-classic">
	<?php
	$shortcode_s21 = !empty($foxtool_options['shortcode-s21']) ? $foxtool_options['shortcode-s21'] : '';
	ob_start();
	wp_editor(
		$shortcode_s21,
		'userpostcontent',
		array(
			'textarea_name' => 'foxtool_settings[shortcode-s21]',
			'media_buttons' => false,
		)
	);
	$editor_contents = ob_get_clean();
	echo $editor_contents;
	?>
	</div>
	<p>
	<input class="ft-input-big ft-view-in" type="text" value="[sign]"/>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you want to display your signature anywhere, you can create content above and then use the generated shortcode at your desired location', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-calendar-days"></i> <?php _e('Shortcode to display date', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[shortcode-s3]" value="1" <?php if ( isset($foxtool_options['shortcode-s3']) && 1 == $foxtool_options['shortcode-s3'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable date shortcode', 'foxtool'); ?></label>
	</p>
	<p>
	<?php $styles = array('VI', 'EN'); ?>
	<select name="foxtool_settings[shortcode-s31]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['shortcode-s31']) && $foxtool_options['shortcode-s31'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p><input class="ft-input-big ft-view-in" type="text" value="[titday]"/></p>
	<p><input class="ft-input-big ft-view-in" type="text" value="[titmonth]"/></p>
	<p><input class="ft-input-big ft-view-in" type="text" value="[tityear]"/></p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This shortcode is used to display the date in the post title. Please note that you need to enable the shortcode usage in the post title under the POST, PAGE section', 'foxtool'); ?></p>
 <h3><i class="fa-regular fa-download"></i> <?php _e('Download button GGET shortcode', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[shortcode-s4]" value="1" <?php if ( isset($foxtool_options['shortcode-s4']) && 1 == $foxtool_options['shortcode-s4'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable GGET shortcode', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[shortcode-s4a]" value="1" <?php if ( isset($foxtool_options['shortcode-s4a']) && 1 == $foxtool_options['shortcode-s4a'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Display link when seconds expire', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[shortcode-s4b]" value="1" <?php if ( isset($foxtool_options['shortcode-s4b']) && 1 == $foxtool_options['shortcode-s4b'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Center-align button on page', 'foxtool'); ?></label>
	</p>
	<p>
	<input class="ft-input-small" placeholder="10" name="foxtool_settings[shortcode-s41]" type="number" value="<?php if(!empty($foxtool_options['shortcode-s41'])){echo $foxtool_options['shortcode-s41'];} ?>"/>
	<label class="ft-label-right"><?php _e('Enter waiting time', 'foxtool'); ?></label>
	</p>
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[shortcode-s42]" type="text" data-coloris value="<?php if(!empty($foxtool_options['shortcode-s42'])){echo $foxtool_options['shortcode-s42'];} ?>"/>
	<label class="ft-right-text"><?php _e('Select button color', 'foxtool'); ?></label>
	</p>
	<p style="display:flex;align-items:center;">
	<input class="ft-input-color" name="foxtool_settings[shortcode-s43]" type="text" data-coloris value="<?php if(!empty($foxtool_options['shortcode-s43'])){echo $foxtool_options['shortcode-s43'];} ?>"/>
	<label class="ft-right-text"><?php _e('Select button border color', 'foxtool'); ?></label>
	</p>
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[shortcode-s44]" min="1" max="7" value="<?php if(!empty($foxtool_options['shortcode-s44'])){echo sanitize_text_field($foxtool_options['shortcode-s44']);} else { echo '2';} ?>" class="ftslide" data-index="7">
	<span><?php _e('Border size', 'foxtool'); ?> <span id="demo7"></span> PX</span>
	</p>
	<p class="ft-keo">
	<input type="range" name="foxtool_settings[shortcode-s45]" min="1" max="50" value="<?php if(!empty($foxtool_options['shortcode-s45'])){echo sanitize_text_field($foxtool_options['shortcode-s45']);} else { echo '10';} ?>" class="ftslide" data-index="8">
	<span><?php _e('Border radius', 'foxtool'); ?> <span id="demo8"></span> PX</span>
	</p>
	
	<p><input class='ft-input-big ft-view-in' type='text' value='[gget url="<?php _e('Download link', 'foxtool'); ?>"]'/></p>
	<p><input class='ft-input-big ft-view-in' type='text' value='[gget url="<?php _e('Download link', 'foxtool'); ?>"] <?php _e('Button name', 'foxtool'); ?> [/gget]' /></p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This shortcode is used to create a download button with a waiting time. If it a Google Drive download link that isnt virus scanned, the file will be downloaded directly', 'foxtool'); ?></p>
</div>		