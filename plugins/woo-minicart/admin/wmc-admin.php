<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !current_user_can( 'activate_plugins' ) )  {
	wp_die( _e( 'You do not have sufficient permissions to access this page.','woo-minicart' ) );
}

if ( ! empty( $_POST ) && check_admin_referer( 'wmc-afs', 'wmc-admin-nonce' ) ){
	$data = array(
		'enable-minicart'   => (isset($_POST['enable-minicart'])?'1':'0'),
		'minicart-icon'     => sanitize_text_field($_POST['minicart-icon']),
		'minicart-position' => sanitize_text_field($_POST['minicart-position']),
		'wmc-offset'        => absint($_POST['wmc-offset'])
	);
	update_option( 'wmc_options', $data );
}

$current_options = get_option('wmc_options');
//print_r($current_options);

?>
<div class="wrap wmc-wrap">
	<h1 class="hidden-h1"></h1>
	<?php if ( isset( $_POST['wmc_option_submit'] ) ){ ?>
		<div class="notice notice-success"> 
			<p><strong>Settings saved.</strong></p>
		</div>
	<?php } ?>
	<div class="wmc-admin-page-title">
		<h1 class="wmc-admin-title"><?php echo esc_html(get_admin_page_title()); ?></h1>
		<span class="wmc-version"><?php echo esc_html($this->plugin_version); ?></span>
	</div>
	<form method="POST" class="options-form">

		<?php wp_nonce_field( 'wmc-afs', 'wmc-admin-nonce' ); ?>

		<div class="block">
			<fieldset>
				<legend class="screen-reader-text"><span>
					<?php _e( 'Enable Floating Minicart', 'woo-minicart' ); ?>
				</span></legend>
				<label for="enable-minicart">
					<input name="enable-minicart" type="checkbox" <?php if( $current_options['enable-minicart'] == 1 ) : ?> checked <?php endif; ?> />
					<span><?php _e( 'Enable Floating Minicart', 'woo-minicart' ); ?></span>
				</label>
			</fieldset>
		</div>

		<div class="block">
			<h3><?php _e( 'Floating Minicart Position', 'woo-minicart' ); ?></h3>

			<fieldset>
				<legend class="screen-reader-text"><span><?php _e( 'Minicart Position', 'woo-minicart' ) ?></span></legend>
				<label title='g:i a'>
					<input type="radio" name="minicart-position" value="wmc-top-left" <?php if( $current_options['minicart-position'] == 'wmc-top-left' ) : echo 'checked'; endif; ?> />
					<span>
						<?php _e( 'Top Left', 'woo-minicart' ) ?>
					</span>
				</label>
				<label title='g:i a'>
					<input type="radio" name="minicart-position" value="wmc-top-right" <?php if( $current_options['minicart-position'] == 'wmc-top-right' ) : echo 'checked'; endif; ?> />
					<span>
						<?php _e( 'Top Right', 'woo-minicart' ) ?>
					</span>
				</label>
				<label title='g:i a'>
					<input type="radio" name="minicart-position" value="wmc-bottom-left" <?php if( $current_options['minicart-position'] == 'wmc-bottom-left' ) : echo 'checked'; endif; ?> />
					<span>
						<?php _e( 'Bottom Left', 'woo-minicart' ) ?>
					</span>
				</label>
				<label title='g:i a'>
					<input type="radio" name="minicart-position" value="wmc-bottom-right" <?php if( $current_options['minicart-position'] == 'wmc-bottom-right' ) : echo 'checked'; endif; ?> />
					<span>
						<?php _e( 'Bottom Right', 'woo-minicart' ) ?>
					</span>
				</label>	
			</fieldset>
		</div>
		<div class="block">
			<h3><?php _e( 'Offset', 'woo-minicart' ); ?></h3>
			<p><?php _e( 'Position from top, only applicable if Minicart position is either Top Left or Top Right.', 'woo-minicart' ) ?></p>
			<input type="number" name="wmc-offset" value="<?php echo esc_html($current_options['wmc-offset']); ?>" /> px
		</div>

		<h3><?php _e( 'Minicart Icon', 'woo-minicart' ); ?></h3>

		<div class="block">
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e( 'Cart Icons', 'woo-minicart' ) ?></span></legend>
				<label title='g:i a'>
					<input type="radio" name="minicart-icon" value="wmc-icon-1" data-class="minicart-1" <?php if( $current_options['minicart-icon'] == 'wmc-icon-1' ) : echo 'checked'; endif; ?> />
					<span>
						<img class="minicart-1 <?php if( $current_options['minicart-icon'] == 'wmc-icon-1' ) : echo 'cart-active'; endif; ?>" src="<?php echo esc_url(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-1.png'); ?>" alt="Mini Cart" width="30" height="30" >
					</span>
				</label>
				<label title='g:i a'>
					<input type="radio" name="minicart-icon" value="wmc-icon-2" data-class="minicart-2" <?php if( $current_options['minicart-icon'] == 'wmc-icon-2' ) : echo 'checked'; endif; ?> />
					<img class="minicart-2 <?php if( $current_options['minicart-icon'] == 'wmc-icon-2' ) : echo 'cart-active'; endif; ?>" src="<?php echo esc_url(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-2.png'); ?>" alt="Mini Cart" width="30" height="30" >
				</label>
				<label title='g:i a'>
					<input type="radio" name="minicart-icon" value="wmc-icon-3" data-class="minicart-3" <?php if( $current_options['minicart-icon'] == 'wmc-icon-3' ) : echo 'checked'; endif; ?> />
					<img class="minicart-3 <?php if( $current_options['minicart-icon'] == 'wmc-icon-3' ) : echo 'cart-active'; endif; ?>" src="<?php echo esc_url(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-3.png'); ?>" alt="Mini Cart" width="30" height="30" >
				</label>
				<label title='g:i a'>
					<input type="radio" name="minicart-icon" value="wmc-icon-4" data-class="minicart-4" <?php if( $current_options['minicart-icon'] == 'wmc-icon-4' ) : echo 'checked'; endif; ?> />
					<img class="minicart-4 <?php if( $current_options['minicart-icon'] == 'wmc-icon-4' ) : echo 'cart-active'; endif; ?>" src="<?php echo esc_url(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-4.png'); ?>" alt="Mini Cart" width="30" height="30" >
				</label>
				<label title='g:i a'>
					<input type="radio" name="minicart-icon" value="wmc-icon-5" data-class="minicart-5" <?php if( $current_options['minicart-icon'] == 'wmc-icon-5' ) : echo 'checked'; endif; ?> />
					<img class="minicart-5 <?php if( $current_options['minicart-icon'] == 'wmc-icon-5' ) : echo 'cart-active'; endif; ?>" src="<?php echo esc_url(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-5.png'); ?>" alt="Mini Cart" width="30" height="30" >
				</label>
			</fieldset>
			<br>
			<fieldset class="pro-only">
				<label>
					<strong>
						<?php _e( 'Custom Cart Icon URL', 'woo-minicart' ); ?>
					</strong>
					(<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)
					<input type="url" readonly="readonly">
				</label>
			</fieldset>
		</div>
		<div class="block">
			<h3><?php _e( 'Shortcode', 'woo-minicart' ); ?></h3>
			<p><?php _e( '<strong style="font-size:18px;">[woo-minicart]</strong> Use this shortcode to display minicart anywhere.', 'woo-minicart' ) ?></p>
		</div>
		<div class="block wmc-styling">
			<h3><?php _e( 'Style', 'woo-minicart' ); ?></h3>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart Count Background color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart Count Text color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart Header Background Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart Header Text Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart View Cart Button Background Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart View Cart Button Text Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart View Cart Button Hover Background Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart View Cart Button Hover Text Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart Checkout Button Background Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart Checkout Button Text Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<label class="pro-only">
				<br>
				<span><strong><?php _e( 'Minicart Checkout Button Hover Background Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
			<br>
			<label class="pro-only">
				<span><strong><?php _e( 'Minicart Checkout Button Hover Text Color', 'woo-minicart' ); ?></strong> (<a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank" class="pro-only-link">Pro</a> only)</span>
				<input type="text" readonly="readonly">
			</label>
		</div>
		<input class="button-primary wmc-submit" type="submit" name="wmc_option_submit" value="<?php esc_attr_e( 'Save Changes', 'wmc-options-submit' ); ?>" />

	</form>
	<?php 
	$pro_notice = __( '<h3><a class="pro-only-link" href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank">Go Pro</a></h3><h4>What is included in Pro version?</h4><ol class="pro-details-list"><li>More Minicart Icons</li><li>Custom Minicart Icon</li><li>Custom Styling</li><li>Preferred Support</li><li>Lifetime Updates</li></ol><h2>Interested in Pro Version?</h2><h4><a href="https://ahmadshyk.com/item/woocommerce-minicart-pro/" target="_blank">Click here</a> to get pro version now.</h4>', 'woo-minicart' )
	?>
	<div class="pro-notice">
		<p>
			<strong>
				<?php
			_e( 'The plugin may need some css styling to adjust with your site on desktop as well as on mobile. I will do that for you without any additional cost with pro version. Just purchase pro version and send me email.', 'woo-minicart' )
			?>
			</strong>
		</p>
		<?php echo wp_kses_post($pro_notice); ?>
		<div class="review-request">
			<h3 style="margin-top: 50px;">
				<?php _e( 'Rate this Plugin' ) ?>
			</h3>
			<p>
				<?php _e( 'If you have a moment, I would very much appreciate if you could quickly rate the plugin on <a href="https://wordpress.org/support/plugin/woo-minicart/reviews/#new-post" target="_blank">wordpress.org</a>, just to help us spread the word.' ) ?>
			</p>
		</div>
		<h4 class="wmc-contact-info">
			In case of any problem, question, idea or any WordPress related work, reach me at <a href="mailto:a.hassan@ahmadshyk.com">a.hassan@ahmadshyk.com</a>
		</h4>
	</div>
</div>