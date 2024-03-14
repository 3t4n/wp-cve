<?php $user_exist = get_option('impact_existing_user'); ?>
<?php $impact_request_value = get_option('impact_request_value'); ?>

<div class="wrap">
	<?php settings_errors(); ?>

	<?php if ('false' === $user_exist) : ?>
		<div class="landing impact-user">
			<div class="logo pl-4 pr-4">
				<img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/impact-logo.png'; ?>" class="logo__image" alt="Impact Logo">
			</div>

			<section class="features pl-4 pr-4">
				<div class="row">
					<div class="col-sm">
						<div class="features__box">
							<h2>Launch your affiliate and influencer program in minutes</h2>
							<p class="mb-3">For as little as <span class="features--decorator">$30/month</span> you can:</p>
							<ul class="mb-4 list-with-bullets">
								<li>Integrate your WooCommerce store hassle-free</li>
								<li>Get matched with the right affiliate and influencers</li>
								<li>Accurately track affiliate and influencer performance</li>
								<li>Automate partner payments</li>
							</ul>
							<div class="features__buttons">
								<a class="btn btn--gradient btn-block" href="https://app.impact.com/signup/create-brand-flow.ihtml?edition=starter__woo_commerce?utm_source=woocommerce&utm_medium=ecommerce&utm_content=CTA&utm_campaign=woocommerce-app-lp" target="_blank">Get started now</a>
								<a class="btn btn-link btn-block" href="https://impact.com/woocommerce-plans/?utm_source=woocommerce&utm_medium=tech_partnerships&utm_campaign=applisting_learnmore&utm_content=learn_more_pricing" target="_blank">Learn more about pricing</a>
							</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="features__image-container">
							<img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/girl-working-on-computer.png'; ?>" class="features_image" alt="Girl Working on her Computer">
						</div>
					</div>
				</div>
			</section>

			<section class="partners pr-4 pl-4">
				<ul class="d-flex">
					<li><img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/brands.png'; ?>" class="partners__image" alt="Brands - Gray Scale"></li>
				</ul>
			</section>

			<section class="reviews ml-4 mr-4">
				<div class="d-flex flex-row justify-content-around align-items-center">
					<div class="reviews__box">
						<div class="reviews__rating d-flex flex-column">
							<div class="mb-3">
								<img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/top-50.png'; ?>" class="awards-image" alt="Best Software Awards - TOP 50 Marketing Products">
							</div>
							<div class="reviews__stars">
								<img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/rating-stars.png'; ?>" class="stars-image" alt="4.5 Stars of review">
							</div>
							<span>518 Reviews on G2.com</span>
						</div>
					</div>
					<div class="reviews__blockquote">
						<div class="reviews__item">
							<p>"We were able to layer on WITHIN's proprietary content strategies along with impact.com's technology solutions to create massive program growth with very strong efficiency and ROAS for Corkcicle. By using best in class technology and strategy, we have a program that is still scaling, and effectively and efficiently delivering on the client's OKRs."</p>
							<span>Kate Mueller, Director of Affiliate, WITHIN</span>
						</div>
						<div class="reviews__item">
							<p>"impact.com makes it easy for our brands to leverage the benefits of what a strong influencer and affiliate program can do for their business, opening and growing new channels for revenue. As a strategic partner, impact.com streamlines it for our clients, keeping the life cycle under one roof. impact.com says it best, "great partnerships grow your business."</p>
							<span>Theresa Reed, Senior Vice President of Growth, BVA</span>
						</div>
					</div>
				</div>
			</section>

			<section class="get-start">
				<h4>Acquire more customers through affiliate and influencer partnerships today with impact.com</h4>
				<div class="get-start__buttons">
					<a href="https://app.impact.com/signup/create-brand-flow.ihtml?edition=starter__woo_commerce?utm_source=woocommerce&utm_medium=ecommerce&utm_content=CTA&utm_campaign=woocommerce-app-lp" target="_blank" class="btn btn--black">Get started now</a>
					<a href="https://impact.com/woocommerce-plans/?utm_source=woocommerce&utm_medium=tech_partnerships&utm_campaign=applisting_learnmore&utm_content=learn_more_pricing" target="_blank" class="btn learn-more">Learn more</a>
				</div>
				<span class="circle-bottom"></span>
				<span class="circle-top"></span>
			</section>

			<section class="integration mt-4">
				<?php
					global $user;
					global $wpdb;

					$store_url    = home_url();
					$user         = wp_get_current_user();
					$endpoint     = '/wc-auth/v1/authorize';
					$params       = array(
						'app_name'     => 'Impact',
						'scope'        => 'read_write',
						'user_id'      => $user->user_login,
						'return_url'   => home_url() . '/wp-admin/admin.php?page=impact-settings',
						'callback_url' => home_url() . '/wp-json/impact/v1/callback',
					);
					$query_string = http_build_query( $params );
					$url          = $store_url . $endpoint . '?' . $query_string;
				?>
				<p>Already have an impact.com account? <a class="btn-link impact-exist-user" href="<?php echo $url ?>">Set up your integration</a></p>
			</section>
		</div>
	<?php elseif ('true' === $impact_request_value && !get_settings_errors()) : ?>
		<div class="col-md-5">
			<h5>The integration is now enabled. You can update your settings at any time.</h5>
		</div>
	<?php endif; ?>

	<div class="impact-form <?php echo ('true' === $user_exist) ? '' : 'impact-hidden'; ?>">
		<form method="post" action="options.php" class="new_integration_setting" id="new_integration_setting">
			<?php
			settings_fields('impact_settings_option_group');
			do_settings_sections('impact-settings-admin');
			submit_button();
			?>
		</form>
		<a href="<?php echo home_url() . '/wp-admin/admin.php?page=impact-settings-delete'; ?>">Delete integration >></a>
	</div>
</div>



<?php if ('true' === $impact_request_value && !get_settings_errors()) : ?>
	<div class="modal mt-5" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Impact Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>
						It seems you have already registered your account's Impact Settings.
					</p>
					<p>
						To update the configuration you'll need to re-enter the <strong>Account SID</strong> and <strong>Auth Token</strong> fields, so keep those at hand.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="button button-primary" data-dismiss="modal">Ok</button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>