<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('SETTING', 'foxtool'); ?></h2>
  <h3><i class="fa-regular fa-bomb"></i> <?php _e('Advanced settings', 'foxtool') ?></h3>
	<!-- foxtool 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[foxtool1]" value="1" <?php if ( isset($foxtool_options['foxtool1']) && 1 == $foxtool_options['foxtool1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Admin account from profile page', 'foxtool'); ?></label>
	<p>
	<input class="ft-input-small" name="foxtool_settings[foxtool11]" type="number" value="<?php if(!empty($foxtool_options['foxtool11'])){echo sanitize_text_field($foxtool_options['foxtool11']);} ?>"/>
	<label class="ft-label-right"><?php _e('Enter Admin ID', 'foxtool'); ?></label>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you want to hide a specific Admin account, enter the ID', 'foxtool'); ?></p>
	<!-- foxtool 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[foxtool2]" value="1" <?php if ( isset($foxtool_options['foxtool2']) && 1 == $foxtool_options['foxtool2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Limit Foxtool display', 'foxtool'); ?></label>
	<p>
	<input class="ft-input-small" name="foxtool_settings[foxtool21]" type="number" value="<?php if(!empty($foxtool_options['foxtool21'])){echo sanitize_text_field($foxtool_options['foxtool21']);} ?>"/>
	<label class="ft-label-right"><?php _e('Enter Admin ID', 'foxtool'); ?></label>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature allows you to only display Foxtool to a specific Admin account', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-eye-slash"></i> <?php _e('Hide Foxtool', 'foxtool') ?></h3>
	<!-- tool hiden 1 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[foxtool3]" value="1" <?php if ( isset($foxtool_options['foxtool3']) && 1 == $foxtool_options['foxtool3'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Foxtool', 'foxtool'); ?></label>
	</p>
	<p class="ft-note ft-note-red"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('You can hide Foxtool from the WP menu, but you can still access it through the link. This will hide Foxtool for all accounts', 'foxtool'); ?><br>
	<b><?php echo admin_url('/admin.php?page=foxtool-options');?></b>
	</p>
	<!-- tool hiden 2 -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[foxtool4]" value="1" <?php if ( isset($foxtool_options['foxtool4']) && 1 == $foxtool_options['foxtool4'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide Foxtool in the plugin manager', 'foxtool'); ?></label>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature will hide Foxtool from the plugin management page', 'foxtool'); ?>
	</p>
  <h3><i class="fa-regular fa-palette"></i> <?php _e('Customize display', 'foxtool') ?></h3>
	<div class="ft-imgstyle">
		<img src="<?php echo esc_url(FOXTOOL_URL .'img/style/1.jpg'); ?>" data-value="Default" class="<?php if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'Default') echo 'selected'; ?>" />
		<img src="<?php echo esc_url(FOXTOOL_URL .'img/style/2.jpg'); ?>" data-value="WordPress" class="<?php if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'WordPress') echo 'selected'; ?>" />
		<img src="<?php echo esc_url(FOXTOOL_URL .'img/style/3.jpg'); ?>" data-value="Bright" class="<?php if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'Bright') echo 'selected'; ?>" />
		<img src="<?php echo esc_url(FOXTOOL_URL .'img/style/4.jpg'); ?>" data-value="Girly" class="<?php if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'Girly') echo 'selected'; ?>" />
		<img src="<?php echo esc_url(FOXTOOL_URL .'img/style/5.jpg'); ?>" data-value="Black" class="<?php if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'Black') echo 'selected'; ?>" />
	</div>
	<input type="hidden" name="foxtool_settings[foxtool5]" id="foxtool5" value="<?php if(!empty($foxtool_options['foxtool5'])){echo sanitize_text_field($foxtool_options['foxtool5']);} else {echo sanitize_text_field('Default');} ?>" />
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var imgStyles = document.querySelectorAll('.ft-imgstyle img');
			imgStyles.forEach(function(img) {
				img.addEventListener('click', function() {
					var selectedStyle = this.getAttribute('data-value');
					document.getElementById('foxtool5').value = selectedStyle;
					imgStyles.forEach(function(img) {
						img.classList.remove('selected');
					});
					this.classList.add('selected');
				});
			});
		});
		jQuery(document).ready(function($) {
			$('.ft-imgstyle img').click(function() { 
				var selectedStyle = $(this).attr('data-value');
				$('#foxtool5').val(selectedStyle);
				$('.ft-imgstyle img').removeClass('selected');
				$(this).addClass('selected');
				var currentForm = $(this).closest('form');
				$.ajax({
					type: 'POST',
					url: currentForm.attr('action'),
					data: currentForm.serialize(),
					success: function(response) {
						location.reload(); 
					},
				});
			});
		});
	</script>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Choose display interface according to your preference', 'foxtool'); ?>
	</p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Enter name', 'foxtool'); ?>" name="foxtool_settings[foxtool6]" type="text" value="<?php if(!empty($foxtool_options['foxtool6'])){echo sanitize_text_field($foxtool_options['foxtool6']);} ?>"/>
	</p>
	<p>
	<?php $styles = array('icon 1', 'icon 2', 'icon 3', 'icon 4', 'icon 5', 'icon 6'); ?>
	<select name="foxtool_settings[foxtool61]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['foxtool61']) && $foxtool_options['foxtool61'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Change display name and icon to your preference', 'foxtool'); ?>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[foxtool7]" value="1" <?php if ( isset($foxtool_options['foxtool7']) && 1 == $foxtool_options['foxtool7'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Hide author information', 'foxtool'); ?></label>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Hide author information in the info tab', 'foxtool'); ?>
	</p>