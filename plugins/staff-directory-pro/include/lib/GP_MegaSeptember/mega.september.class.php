<?php
if ( !class_exists('GP_MegaSeptember') ):

	class GP_MegaSeptember
	{
		var $settings = array();
		var $defaults = array(
			'plugin_name' => 'Pro',
			'pitch' => '',
			'learn_more_url' => 'https://goldplugins.com/',
			'upgrade_url' => 'https://goldplugins.com/',
			'text_domain' => 'wordpress',
			'testimonial' => false,			
		);

		function __construct( $settings = array() )
		{
			if ( !empty($settings) ) {
				$this->settings = $settings;
			}
			add_action( 'admin_enqueue_scripts', array($this, 'register_scripts') );
			add_action( 'admin_enqueue_scripts', array($this, 'add_stylesheets') );
		}
				
		function get_settings()
		{
			return array_merge($this->defaults, $this->settings);
		}
		
		function register_scripts()
		{
			wp_register_script(
				'gp_mega_september',
				plugins_url('assets/js/gp_mega_september.js', __FILE__),
				array( 'jquery' ),
				false,
				true
			);
		}
		
		function add_stylesheets()
		{
			$css_url = plugins_url('assets/css/gp_mega_september.css', __FILE__);
			wp_register_style( 'gp_mega_september', $css_url);
			wp_enqueue_style( 'gp_mega_september' );
		}
		
		function form()
		{
			// enqueue JS
			wp_enqueue_script('gp_mega_september');
			
			// output the mailing list form
			$this->output_new_coupon_form();
		}
		
		function output_new_coupon_form()
		{
			$current_user = wp_get_current_user();
			extract( $this->get_settings() );
			?>
			<div class="gp_mega_september">
				<div id="signup_wrapper">
					<div class="topper purple">
						<h3><span>Upgrade To</span> <?php echo htmlentities($plugin_name); ?>!</h3>
						<p class="pitch"><?php echo htmlentities($pitch); ?></p>
						<a class="upgrade_link" href="<?php echo htmlentities($learn_more_url); ?>" title="Learn More">Click Here To Learn More &raquo;</a>
					</div>
					<div id="mc_embed_signup">
						<div class="save_now">
							<h3>Save 10% Now!</h3>
							<p class="pitch">Subscribe to our newsletter now, and we’ll send you a coupon for 10% off your upgrade to the Pro version.</p>
						</div>
						<form action="https://goldplugins.com/atm/atm.php?u=403e206455845b3b4bd0c08dc&amp;id=a70177def0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<div class="fields_wrapper">
								<label for="mce-NAME">Your Name (optional)</label>
								<input type="text" value="<?php echo (!empty($current_user->display_name) ?  $current_user->display_name : ''); ?>" name="NAME" class="name" id="mce-NAME" placeholder="Your Name">
								<label for="mce-EMAIL">Your Email</label>
								<input type="email" value="<?php echo (!empty($current_user->user_email) ?  $current_user->user_email : ''); ?>" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
								<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
								<div style="position: absolute; left: -5000px;"><input type="text" name="b_403e206455845b3b4bd0c08dc_6ad78db648" tabindex="-1" value=""></div>
							</div>
							<div class="clear"><input type="submit" value="<?php _e('Send My Coupon', $text_domain); ?>" name="subscribe" id="mc-embedded-subscribe" class="smallBlueButton"></div>
							<p class="secure"><img src="<?php echo plugins_url('assets/img/lock.png', __FILE__); ?>" alt="Lock" width="16px" height="16px" />We respect your privacy.</p>
							
							<input type="hidden" id="mc-upgrade-plugin-name" name="mc-upgrade-plugin-name" value="<?php echo htmlentities($plugin_name); ?>" />
							<input type="hidden" id="mc-upgrade-link-per" value="<?php echo htmlentities($upgrade_url_promo); ?>" />
							<input type="hidden" id="mc-upgrade-link-biz" value="<?php echo htmlentities($upgrade_url_promo); ?>" />
							<input type="hidden" id="mc-upgrade-link-dev" value="<?php echo htmlentities($upgrade_url_promo); ?>" />
							
							<?php if ( !empty($testimonial) ):?>
							<div class="customer_testimonial">
									<div class="stars">
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
									</div>
									<p class="customer_testimonial_title"><strong><?php echo wp_kses($testimonial['title'], 'post'); ?></strong></p>
									“<?php echo wp_kses($testimonial['body'], 'post'); ?>”
									<p class="author">&mdash; <?php echo wp_kses($testimonial['name'], 'strip'); ?></p>
							</div>
							<?php endif; ?>
						</form>
						<div class="gp_logo">
							<a href="https://goldplugins.com/?utm_source=<?php echo esc_url( sanitize_title($plugin_name) ); ?>_coupon_box&utm_campaign=gp_logo" target="_blank">
								<img src="<?php echo esc_url( plugins_url('assets/img/logo.png', __FILE__) );?>" alt="Gold Plugins" />
							</a>
						</div>
					</div>		
					<p class="u_to_p"><a href="<?php echo esc_url($upgrade_url); ?>">Upgrade to Pro</a> to remove banners like this one.</p>
					<?php //$this->output_hello_t_banner(); ?>
					<div style="clear:right;"></div>
				</div><!--/#signup_wrapper-->
			</div><!--/.gp_mega_september-->
			<?php			
		}		
		
	} // end class

endif; // class_exists check
