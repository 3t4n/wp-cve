<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('WOOCOMMERCE', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check8" data-target="play8" type="checkbox" name="foxtool_settings[woo]" value="1" <?php if ( isset($foxtool_options['woo']) && 1 == $foxtool_options['woo'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play8" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-message-captions"></i> <?php _e('Advanced Ajax with WooCommerce', 'foxtool') ?></h3>
	<!-- ajax them vao gio -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[woo-aja1]" value="1" <?php if ( isset($foxtool_options['woo-aja1']) && 1 == $foxtool_options['woo-aja1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Ajax add to cart on single product page', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable Ajax for the add to cart button on the single product page', 'foxtool'); ?></p>
	
	<!-- ajax nut so luong -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[woo-aja2]" value="1" <?php if ( isset($foxtool_options['woo-aja2']) && 1 == $foxtool_options['woo-aja2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Ajax quantity button', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('The quantity buttons on the single product page and the cart page will be adjusted to work with Ajax', 'foxtool'); ?></p>
	
	<div class="ft-woo-load">
		<img src="<?php echo esc_url(FOXTOOL_URL . 'img/load1.gif'); ?>" />
		<img src="<?php echo esc_url(FOXTOOL_URL . 'img/load2.gif'); ?>" />
		<img src="<?php echo esc_url(FOXTOOL_URL . 'img/load3.gif'); ?>" />
		<img src="<?php echo esc_url(FOXTOOL_URL . 'img/load4.gif'); ?>" />
		<img src="<?php echo esc_url(FOXTOOL_URL . 'img/load5.gif'); ?>" />
	</div>
	<p>
	<?php $styles = array('None', 'Loading 1', 'Loading 2', 'Loading 3', 'Loading 4', 'Loading 5'); ?>
	<select name="foxtool_settings[woo-load1]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['woo-load1']) && $foxtool_options['woo-load1'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Select a loading animation for Ajax product on the website', 'foxtool'); ?></p>
	
  <h3><i class="fa-regular fa-text-size"></i> <?php _e('Modify content', 'foxtool') ?></h3>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Change the "Buy Now" text on the single product page', 'foxtool'); ?>" name="foxtool_settings[woo-text1]" type="text" value="<?php if(!empty($foxtool_options['woo-text1'])){echo sanitize_text_field($foxtool_options['woo-text1']);} ?>"/>
	</p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('The text to replace "Buy Now" on the product page', 'foxtool'); ?>" name="foxtool_settings[woo-text2]" type="text" value="<?php if(!empty($foxtool_options['woo-text2'])){echo sanitize_text_field($foxtool_options['woo-text2']);} ?>"/>
	</p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Display text if the price is 0', 'foxtool'); ?>" name="foxtool_settings[woo-text3]" type="text" value="<?php if(!empty($foxtool_options['woo-text3'])){echo sanitize_text_field($foxtool_options['woo-text3']);} ?>"/>
	</p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Display text if out of stock', 'foxtool'); ?>" name="foxtool_settings[woo-text4]" type="text" value="<?php if(!empty($foxtool_options['woo-text4'])){echo sanitize_text_field($foxtool_options['woo-text4']);} ?>"/>
	</p>
	
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[woo-ntext1]" value="1" <?php if (isset($foxtool_options['woo-ntext1']) && 1 == $foxtool_options['woo-ntext1']) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Convert "đ" to "VNĐ" (Vietnamese currency unit)', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Modify some information on Woocommerce that you want', 'foxtool'); ?></p>
	
  <h3><i class="fa-brands fa-telegram"></i> <?php _e('Configure order notifications to be sent to Telegram', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[woo-tele1]" value="1" <?php if (isset($foxtool_options['woo-tele1']) && 1 == $foxtool_options['woo-tele1']) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable notifications', 'foxtool'); ?></label>
	</p>
	<p><input class="ft-input-big"  placeholder="<?php _e('API Token', 'foxtool'); ?>" name="foxtool_settings[woo-tele11]" type="text" value="<?php if(!empty($foxtool_options['woo-tele11'])){echo sanitize_text_field($foxtool_options['woo-tele11']);} ?>"/></p>
	<p><input class="ft-input-big"  placeholder="<?php _e('Chat ID', 'foxtool'); ?>" name="foxtool_settings[woo-tele12]" type="text" value="<?php if(!empty($foxtool_options['woo-tele12'])){echo sanitize_text_field($foxtool_options['woo-tele12']);} ?>"/></p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('With this feature, you can notify your orders to your Telegram group, helping you manage orders conveniently', 'foxtool'); ?></p>
</div>	