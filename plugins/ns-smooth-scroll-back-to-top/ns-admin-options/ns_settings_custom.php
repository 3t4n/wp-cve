<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form method="post" action="options.php">
	<?php settings_fields('ns_btta_options_group'); ?>
	<div class="ns-container-options">
		<div class="ns-label-container-btta">
			<?php _e('Choose your label for back to top', 'ns-woocommerce-back-to-top-arrow')?><br/><?php _e('You can write what you want or add ', 'ns-woocommerce-back-to-top-arrow')?><a href="<?php echo esc_url('http://fortawesome.github.io/Font-Awesome/icons/'); ?>" target="_blank">FontAwesome icons</a>
		</div>
		<div class="ns-input-container-btta">
			<textarea id="ns_btta_font_awsome" name="ns_btta_font_awsome"><?php echo get_option('ns_btta_font_awsome', '<i class="fa fa-arrow-up"></i>'); ?></textarea>
		</div>
		<div class="ns-label-container-btta">
				<?php _e('Position', 'ns-woocommerce-back-to-top-arrow')?>
		</div>		
		<div class="ns-input-container-btta">
		<div class="ns-btta-container">
			<input type="hidden" name="ns_btta_position" id="ns_btta_position" value="<?php echo get_option('ns_btta_position', 4); ?>">
			<div id="ns_square_container_btta">
				<div class="ns_square" id="ns_square_1" data-square="1"></div>
				<div class="ns_square" id="ns_square_2" data-square="2"></div>
				<div class="ns_square" id="ns_square_3" data-square="3"></div>
				<div class="ns_square" id="ns_square_4" data-square="4"></div>
			</div>
		</div>			
		</div>		
		<div class="column-ns-btta" style="width: 300px; float: left;">
			<div class="ns-label-container-btta">
				<?php _e('Backgound color', 'ns-woocommerce-back-to-top-arrow')?>
			</div>
			<div class="ns-input-container-btta">
				<input type="text" name="ns_btta_background" id="ns_btta_background" value="<?php echo get_option('ns_btta_background'); ?>">
				<span class="description"></span>
			</div>
			<div class="ns-label-container-btta">
				<?php _e('Text color', 'ns-woocommerce-back-to-top-arrow')?>
			</div>
			<div class="ns-input-container-btta">
				<input type="text" id="ns_btta_text_color" value="<?php echo get_option('ns_btta_text_color', '#000000'); ?>" name="ns_btta_text_color" />
			</div>
			<div class="ns-label-container-btta">
				<?php _e('Border color', 'ns-woocommerce-back-to-top-arrow')?>
			</div>
			<div class="ns-input-container-btta">
				<input type="text" id="ns_btta_border_color" value="<?php echo get_option('ns_btta_border_color', '#000000'); ?>" name="ns_btta_border_color" />
			</div>
		</div>
		<div class="column-ns-btta" style="width: 300px; float: left;">
			<div class="ns-label-container-btta">
				<?php _e('Backgound color hover', 'ns-woocommerce-back-to-top-arrow')?>
			</div>
			<div class="ns-input-container-btta">
				<input type="text" id="ns_btta_background_hover" value="<?php echo get_option('ns_btta_background_hover', '#000000'); ?>" name="ns_btta_background_hover" />
			</div>
			<div class="ns-label-container-btta">
				<?php _e('Text color hover', 'ns-woocommerce-back-to-top-arrow')?>
			</div>
			<div class="ns-input-container-btta">
				<input type="text" id="ns_btta_text_color_hover" value="<?php echo get_option('ns_btta_text_color_hover', '#FFFFFF'); ?>" name="ns_btta_text_color_hover" />
			</div>
			<div class="ns-label-container-btta">
				<?php _e('Border color hover', 'ns-woocommerce-back-to-top-arrow')?>
			</div>
			<div class="ns-input-container-btta">
				<input type="text" id="ns_btta_border_color_hover" value="<?php echo get_option('ns_btta_border_color_hover', '#FFFFFF'); ?>" name="ns_btta_border_color_hover" />
			</div>
		</div>
		<div class="ns-label-container-btta row-btta-cont">
			<?php _e('Smoooth speed (This value sets the speed in millisecond to come back to top)', 'ns-woocommerce-back-to-top-arrow')?>
		</div>
		<div class="ns-input-container-btta">
			<input type="text" id="ns_btta_speed" value="<?php echo get_option('ns_btta_speed', '800'); ?>" name="ns_btta_speed" />
		</div>												
		<div class="ns-submit-container-btta"><input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Save Changes') ?>" /></div>	
	</div>
</div>

</form>
<?php /*
<!-- Begin MailChimp Signup Form -->

	<div id="mc_embed_signup">
	<form action="//nsthemes.us12.list-manage.com/subscribe/post?u=07ab11a197e784f0a8f6214a4&amp;id=d48f6e6eaa" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
	    <div id="mc_embed_signup_scroll">
		    <div class="ns-label-container-btta" style="margin-top: 50px; font-size: 24px;">
				<label for="mce-EMAIL"><?php _e('STAY TUNED!', 'ns-woocommerce-back-to-top-arrow')?><br/><span style="font-size: 14px;"><?php _e('Thanks to use BTTA plugin! Submit your email to keep in touch!', 'ns-woocommerce-back-to-top-arrow')?></span></label>
			</div>
			<div class="ns-input-container-btta">
				<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<?php _e('email address', 'ns-woocommerce-back-to-top-arrow')?>" required>
			</div>
	    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
	    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_07ab11a197e784f0a8f6214a4_d48f6e6eaa" tabindex="-1" value=""></div>
	    <div class="clear"><input type="submit" value="<?php _e('Subscribe', 'ns-woocommerce-back-to-top-arrow')?>" name="subscribe" id="mc-embedded-subscribe" class="button-primary"></div>
	    </div>
	</form>
	</div>

<!--End mc_embed_signup-->
*/

settings_fields('ns_btta_options_group'); ?>
