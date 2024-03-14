<?php

/**
 * Register the WP eBay Product Feeds Menu
 * @return void
 */
function ebay_feeds_for_wordpress_menus() {

	add_options_page( 'eBay Feeds Options', 'WP eBay Product Feeds', 'manage_options', 'ebayfeedforwordpressoptions', 'ebay_feeds_for_wordpress_options' );

}

/**
 * Create and add the WP eBay Product Feeds Options Page
 * @return void
 */
function ebay_feeds_for_wordpress_options() {

	$current_user = wp_get_current_user();
	?>
	<div class="pea_admin_wrap">
		<div class="pea_admin_top">
			<h1><?php echo EBFW_PLUGIN_NAME; ?> <small> - <?php echo EBFW_PLUGIN_TAGLINE; ?></small></h1>
		</div>

		<div class="pea_admin_main_wrap">
			<div class="pea_admin_main_left">
				<div class="pea_admin_signup">
					<?php _e( 'Want to know about updates to this plugin without having to log into your site every time? Want to know about other cool plugins we\'ve made? Add your email and we\'ll add you to our very rare mail outs.' ); ?>

					<!-- Begin MailChimp Signup Form -->
					<div id="mc_embed_signup">
						<form action="https://gospelrhys.us1.list-manage.com/subscribe/post?u=c656fe50ec16f06f152034ea9&id=d9645e38c2" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<div class="mc-field-group">
								<label for="mce-EMAIL"> <?php _e( 'Email Address', 'inline-tweet-sharer' ); ?>
								</label>
								<input type="hidden" value="eBay Feeds for WordPress" name="SIGNUP" class="" id="mce-SIGNUP">
								<input type="email" value="<?php echo $current_user->user_email; ?>" name="EMAIL" class="required email" id="mce-EMAIL" tabindex="10">
								<button type="submit" name="subscribe" id="mc-embedded-subscribe" class="pea_admin_green"  tabindex="20">Sign Up!</button><br/>
								<label for="mce-MMERGE4">I want to Receive The Winwar Media Newsletter: </label>
								<input type="checkbox" value="yes" name="MMERGE4" class="" id="mce-MMERGE4" tabindex="15">

							</div>
							<div id="mce-responses" class="clear">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>  <div class="clear"></div>
						</form>
					</div>

					<!--End mc_embed_signup-->
				</div>

				<form method="post" action="options.php" id="options">

					<?php wp_nonce_field( 'update-options' ); ?>

					<?php settings_fields( 'ebay-feeds-for-wordpress-group' ); ?>

					<table class="form-table">

						<tbody>

							<tr valign="top">

								<th scope="row" style="width:400px"><?php _e( 'Default eBay Feed: ', 'ebay-feeds-for-wordpress' ); ?></th>

								<td><input type="text" name="ebay-feeds-for-wordpress-default" class="regular-text code" value="<?php echo esc_attr( get_option( 'ebay-feeds-for-wordpress-default' ) ); ?>" />
								<?php
								if ( stripos( get_option( 'ebay-feeds-for-wordpress-default' ), 'rest.ebay.com' ) ) {
									$arurl = "https://www.winwar.co.uk/recommends/rss-ground-plugin/";
									?><span class="description"><?php printf( __( 'From first September 2020 links feeds with the old rest.ebay.com will no longer work. Please switch to an alternative service like <a href="%s">RSS Ground</a>.', 'ebay-feeds-for-wordpress' ), $arurl ); ?> </span><?php
								}
								?></td>

							</tr>

							<tr valign="top">

								<th scope="row" style="width:400px"><?php _e( 'Default Number of Items To Show:', 'ebay-feeds-for-wordpress' ); ?></th>

								<td><input type="text" name="ebay-feeds-for-wordpress-default-number" class="regular-text code" value="<?php echo esc_attr( get_option( 'ebay-feeds-for-wordpress-default-number' ) ); ?>" /></td>

							</tr>

							<tr valign="top">

								<th scope="row" style="width:400px"><label><?php _e( 'Open Links In New Window?', 'ebay-feeds-for-wordpress' ); ?></label></th>

								<td><input type="checkbox" name="ebay-feeds-for-wordpress-link-open-blank" value="1" <?php checked( 1, get_option( 'ebay-feeds-for-wordpress-link-open-blank' ) ); ?>></td>

							</tr>
								<tr valign="top">

									<th scope="row" style="width:400px"><label><?php _e( 'Nofollow Links?', 'ebay-feeds-for-wordpress' ); ?></label></th>

									<td><input type="checkbox" name="ebay-feeds-for-wordpress-nofollow-links" value="1" <?php checked( 1, get_option( 'ebay-feeds-for-wordpress-nofollow-links' ) ); ?>></td>

								</tr>


								<tr valign="top">

									<th scope="row" style="width:400px"><label><?php _e( 'Link to us (optional, but appreciated)', 'ebay-feeds-for-wordpress' ); ?></label></th>

									<td><input type="checkbox" name="ebay-feeds-for-wordpress-link" value="1" <?php checked( 1, get_option( 'ebay-feeds-for-wordpress-link' ) ); ?>></td>

								</tr>


								<tr valign="top">

									<th scope="row" style="width:400px"><label><?php _e( 'Disable Header', 'ebay-feeds-for-wordpress' ); ?></label></th>

									<td><input type="checkbox" name="ebay-feeds-for-wordpress-disable-header" id="ebay-feeds-for-wordpress-disable-header" value="1" <?php checked( 1, get_option( 'ebay-feeds-for-wordpress-disable-header' ) ); ?>>

									<?php

									$warningstring = '';

									if ( get_option( 'ebay-feeds-for-wordpress-disable-header' ) ) {
										$warningstring = ' style="display:block;"';
									}

									?>
									<div class="ebffwp-warningstring" <?php echo $warningstring; ?>><strong><em>Please Note - disabling the header string can lead to accounts being disabled. Please place something above the listings to indicate the listings are eBay listings.</em></strong></div>

									</td>

								</tr>

								<tr valign="top">

									<th scope="row" style="width:400px"><?php _e( 'Default Header: ', 'ebay-feeds-for-wordpress' ); ?></th>

									<td><input type="text" name="ebay-feeds-for-wordpress-default-header" class="regular-text" value="<?php echo esc_attr( get_option( 'ebay-feeds-for-wordpress-default-header' ) ); ?>" /><br/>
										<span class="description"><?php _e( 'Default header to use above feeds. Wrapped in <code>&lt;h3&gt;&lt;/h3&gt;</code> tags.', 'ebay-feeds-for-wordpress' ); ?> </span></td>

								</tr>

										<tr valign="top">

											<th scope="row" style="width:400px"><?php _e( 'Item Div Wrapper Class: ', 'ebay-feeds-for-wordpress' ); ?></th>

											<td><input type="text" name="ebay-feeds-for-wordpress-item-div-wrapper" class="regular-text code" value="<?php echo esc_attr( get_option( 'ebay-feeds-for-wordpress-item-div-wrapper' ) ); ?>" /><br/>
												<span class="description"><?php _e( 'Add a word here to add a class around each item. Leave blank to disable.', 'ebay-feeds-for-wordpress' ); ?> </span></td>

										</tr>

										<tr valign="top">

											<th scope="row" style="width:400px"><?php _e( 'Image Maximum Width: ', 'ebay-feeds-for-wordpress' ); ?></th>

											<td><input type="number" step="1" min="1" name="ebay-feeds-for-wordpress-imax-max-width" class="regular-text code" value="<?php echo esc_attr( get_option( 'ebay-feeds-for-wordpress-imax-max-width' ) ); ?>" /><br/>
												<span class="description"><?php _e( 'Set the maximum width of images in pixels.', 'ebay-feeds-for-wordpress' ); ?></span></td>

											</tr>

											<tr valign="top">

												<th scope="row" style="width:400px"><label><?php _e( 'Switch Debug Mode On?', 'ebay-feeds-for-wordpress' ); ?></label></th>

												<td><input type="checkbox" name="ebay-feeds-for-wordpress-debug" value="1" <?php checked( 1, get_option( 'ebay-feeds-for-wordpress-debug' ) ); ?>>

													<p><em><?php printf( __('Use this to identify problems with the feed or plugin. If switched on, logged in users will be able to see errors of the feed. If you <a href="%s">ask for support</a>, this will be the first thing we ask you to do!</em>', 'ebay-feeds-for-wordpress' ), 'http://winwar.co.uk/priority-support/?utm_source=settings-page&utm_medium=plugin&utm_campaign=ebayfeedsforwordpress' ); ?></p></td>

												</tr>

												<tr valign="top">

													<th scope="row" style="width:400px"><label><?php _e( 'Fallback Text', 'ebay-feeds-for-wordpress' ); ?></label></th>

													<td>
														<?php

														$fallback = get_option( 'ebay_feeds_for_wordpress_fallback' );

														wp_editor( $fallback, 'ebay_feeds_for_wordpress_fallback' );

														?>
														<em><?php _e( 'If for any reason, the feed doesn\'t work, this will be displayed instead. Use this to link to your eBay shop.', 'ebay-feeds-for-wordpress' ); ?></em>
													</td>

												</tr>

												<?php /* <tr valign="top">

													<th scope="row" style="width:400px"><label><?php _e( 'Disable Feed Caching?', 'ebay-feeds-for-wordpress' ); ?></label></th>

													<td><input type="checkbox" name="ebay-feed-for-wordpress-flush-cache" value="1" <?php checked( 1, get_option( 'ebay-feed-for-wordpress-flush-cache' ) ); ?>>

														<p><em><?php _e( 'If enabled, this flushes the cache. Use this if you have the odd problem with the feeds displaying incorrectly', 'ebay-feeds-for-wordpress' ); ?></p></td>

														</tr> */ ?>

														<tr valign="top">

															<th scope="row" style="width:400px"><label><?php _e( 'Enable Force Feed?', 'ebay-feeds-for-wordpress' ); ?></label></th>

															<td><input type="checkbox" name="ebay-feed-for-wordpress-force-feed" value="1" <?php checked( 1, get_option( 'ebay-feed-for-wordpress-force-feed' ) ); ?>>

																<p><em><?php _e( 'Check this box to force the feed', 'ebay-feeds-for-wordpress' ); ?></p></td>

																</tr>

																<tr valign="top">

																	<th scope="row" style="width:400px"><label><?php _e( 'Load Images over SSL', 'ebay-feeds-for-wordpress' ); ?></label></th>

																	<td><input type="checkbox" name="ebay-feed-for-wordpress-ssl" value="1" <?php checked( 1, get_option( 'ebay-feed-for-wordpress-ssl' ) ); ?>>

																		<p><em><?php _e( 'Check this box to load images over SSL', 'ebay-feeds-for-wordpress' ); ?></p></td>

																		</tr>
																<tr valign="top">

																	<th scope="row" style="width:400px"><label><?php _e( 'Hide results from known search engines', 'ebay-feeds-for-wordpress' ); ?></label></th>

																	<td>
																		<input type="checkbox" name="ebay-feeds-hide-results-from-search-engines" value="1" <?php checked( 1, get_option( 'ebay-feeds-hide-results-from-search-engines' ) ); ?>>
																		<p><em><?php _e( 'If you want to hide the results from appearing from search engines, tick this box.', 'ebay-feeds-for-wordpress' ); ?></p></td>
																	</td>

																</tr>

																	<?php do_action( 'ebay_feeds_for_wordpress_added_options' ); ?>

																	</tbody>

																</table>



																<input type="hidden" name="action" value="update" />

																<input type="hidden" name="page_options" value="ebay-feeds-for-wordpress-default" />

																<p class="submit">

																	<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />

																</p>

															</form>
														</div>

													</div>
													<div class="pea_admin_main_right">

														<?php if ( !defined( 'EBFFWP_HIDE_AD' ) ) { ?>

														<div class="pea_admin_box ebbfwpp_admin_box">
															<h2><?php _e( 'WP eBay Product Feeds Premium', 'ebay-feeds-for-wordpress' ); ?></h2>

															<p class="pea_admin_clear"><?php _e( 'Want more options? WP eBay Product Feeds Premium introduces a full templating system which can help increase clickthroughs and sales of your items on eBay.', 'ebay-feeds-for-wordpress' ); ?></p>

															<p class="pea_admin_clear"><?php _e( 'For more details, please visit the link below', 'ebay-feeds-for-wordpress' ); ?></p>

															<p class="pea_admin_clear"><?php _e( 'To get a £5 discount, use the discount code ', 'ebay-feeds-for-wordpress' ); ?><strong>5FREE</strong>.</p>

															<p class="pea_admin_clear text-center"><a href="https://www.winwar.co.uk/plugins/ebay-feeds-for-wordpress-premium/?utm_source=options-page&utm_medium=plugin&utm_campaign=ebayfeedsforwordpress"><button class="pea_admin_green"><?php _e( 'Go Premium Today', 'ebay-feeds-for-wordpress' ); ?></button></a></p>

														</div>

														<?php } ?>

														<div class="pea_admin_box">
															<h2><?php _e( 'Like this Plugin?', 'ebay-feeds-for-wordpress' ); ?></h2>
															<a href="<?php echo EBFW_EXTEND_URL; ?>" target="_blank"><button type="submit" class="pea_admin_green"><?php _e( 'Rate this plugin &#9733; &#9733; &#9733; &#9733; &#9733;', 'ebay-feeds-for-wordpress' ); ?></button></a><br><br>
															<div id="fb-root"></div>
															<script>(function(d, s, id) {
																var js, fjs = d.getElementsByTagName(s)[0];
																if (d.getElementById(id)) return;
																js = d.createElement(s); js.id = id;
																js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=181590835206577";
																fjs.parentNode.insertBefore(js, fjs);
															}(document, 'script', 'facebook-jssdk'));</script>
															<div class="fb-like" data-href="<?php echo EBFW_PLUGIN_URL; ?>" data-send="true" data-layout="button_count" data-width="250" data-show-faces="true"></div>
															<br>
															<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo EBFW_PLUGIN_URL; ?>" data-text="Just been using <?php echo EBFW_PLUGIN_NAME; ?> #WordPress plugin" data-via="<?php echo EBFW_AUTHOR_TWITTER; ?>" data-related="WPBrewers">Tweet</a>
															<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
															<br>
															<a href="http://bufferapp.com/add" class="buffer-add-button" data-text="Just been using <?php echo EBFW_PLUGIN_NAME; ?> #WordPress plugin" data-url="<?php echo EBFW_PLUGIN_URL; ?>" data-count="horizontal" data-via="<?php echo EBFW_AUTHOR_TWITTER; ?>">Buffer</a><script type="text/javascript" src="https://static.bufferapp.com/js/button.js"></script>

														</div>

														<div class="pea_admin_box">
															<h2><?php _e( 'About the Author', 'ebay-feeds-for-wordpress' ); ?></h2>

															<?php

															$size = 70;
															$rhys_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( "rhys@rhyswynne.co.uk" ) ) ) . "?s=" . $size;

															?>

															<p class="pea_admin_clear"><img class="pea_admin_fl" src="<?php echo $rhys_url; ?>" alt="Rhys Wynne" /> <h3>Rhys Wynne</h3><br><a href="https://twitter.com/rhyswynne" class="twitter-follow-button" data-show-count="false">Follow @rhyswynne</a>
																<div class="fb-subscribe" data-href="https://www.facebook.com/rhysywynne" data-layout="button_count" data-show-faces="false" data-width="220"></div>
															</p>
															<p class="pea_admin_clear"><?php _e( 'Rhys Wynne is a freelance WordPress developer at Dwinrhys.com, conference speaker and blogger. His plugins have had a total of 100,000 downloads, and his premium plugins have generated four figure sums in terms of sales. Rhys likes rubbish football (supporting Colwyn Bay FC) and Professional Wrestling.', 'ebay-feeds-for-wordpress' ); ?></p>
														</div>



													</div>
												</div>

												<?php

											}


/**
 * Process the WP eBay Product Feeds options page.
 * @return void
 */
function ebay_feeds_for_wordpress_options_process() { // whitelist options

	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-default' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-default-number' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-link' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-link-open-blank' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-nofollow-links' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay_feeds_for_wordpress_fallback' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-debug' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feed-for-wordpress-flush-cache' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-item-div-wrapper' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feed-for-wordpress-force-feed' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feed-for-wordpress-ssl' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-imax-max-width' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-disable-header' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-for-wordpress-default-header' );
	register_setting( 'ebay-feeds-for-wordpress-group', 'ebay-feeds-hide-results-from-search-engines' );

	do_action( 'ebay_feeds_for_wordpress_added_options_processing' );

}