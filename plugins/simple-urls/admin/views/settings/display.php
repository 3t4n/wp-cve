<?php
/**
 * Lasso Lite - Displays
 *
 * @package Lasso Lite
 */

use LassoLite\Classes\Config;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Setting;

$lasso_lite_setting     = Setting::get_settings();
$disclosure_text        = $lasso_lite_setting['disclosure_text'];
$main_color             = $lasso_lite_setting['display_color_main'];
$title_color            = $lasso_lite_setting['display_color_title'];
$bg_color               = $lasso_lite_setting['display_color_background'];
$button_color           = $lasso_lite_setting['display_color_button'];
$secondary_button_color = $lasso_lite_setting['display_color_secondary_button'];
$button_text_color      = $lasso_lite_setting['display_color_button_text'];
$pros_color             = $lasso_lite_setting['display_color_pros'];
$cons_color             = $lasso_lite_setting['display_color_cons'];
$primary_button_text    = $lasso_lite_setting['primary_button_text'];
$secondary_button_text  = $lasso_lite_setting['secondary_button_text'];
$lasso_affiliate_url    = $lasso_lite_setting['lasso_affiliate_URL'];
$show_price_attr        = $lasso_lite_setting['show_price'] ? 'checked' : '';
$show_disclosure_attr   = $lasso_lite_setting['show_disclosure'] ? 'checked' : '';
$enable_brag_mode_attr  = $lasso_lite_setting['enable_brag_mode'] ? 'checked' : '';
?>

<?php Config::get_header(); ?>

<!-- DISPLAY SETTINGS -->
<section class="px-3 py-5">
	<div class="container lite-container">
		<!-- HEADER -->
		<?php require_once 'header.php'; ?>

		<form class="lasso-lite-admin-settings-form" autocomplete="off" onsubmit="return false;">
			<div class="white-bg rounded shadow p-4 mb-4">
				<div class="onboarding_display_container pb-3">
					<div id="demo_display_box">
						<div class="lasso-single" style="max-width: 750px; margin: 0 auto;">
							<div class="single-view-wrap">
								<div class="lasso-container lasso-lite">
									<div class="lasso-display lasso-cactus">
										<!-- BADGE -->
										<div class="lasso-badge">Our Pick</div>

										<!-- LASSO TITLE, PRICE, DESC, AND IMAGE -->
										<div class="lasso-box-1">
											<a class="lasso-title">Essentialism: The Disciplined Pursuit of Less</a>

											<div class="lasso-stars" style="--rating: 4.5">
												<span class="lasso-stars-value"> 4.5 </span>
											</div>

											<div class="lasso-price">
												<div class="lasso-price-value"><span class="discount-price"><strike>$18.99</strike></span><span class="latest-price">$15.99</span></div>
												<i class="lasso-amazon-prime"></i>
											</div>
											<div class="clear"></div>
											<!-- DESCRIPTION -->
											<div class="lasso-description">
												The Way of the Essentialist isn't about getting more done in less time. It's not about getting less done. It's about getting only the right things done. It's about the pursuit of the right thing, in the right way, at the right time.
											</div>

											<div class="lasso-fields">
												<div class="lasso-fields-pros lasso-fields-2">
													<strong>Pros:</strong>
													<ul>
														<li><span class="lasso-check"><span class="lasso-check-content"></span></span>Will make you more effective.</li>
														<li><span class="lasso-check"><span class="lasso-check-content"></span></span>Quick read and highly actionable.</li>
													</ul>
												</div>
												<div class="lasso-fields-cons lasso-fields-3">
													<strong>Cons:</strong>
													<ul>
														<li><span class="lasso-x"><span class="lasso-x-1"></span><span class="lasso-x-2"></span></span>You have to be open minded.</li>
													</ul>
												</div>
											</div>
										</div>

										<div class="lasso-box-2">
											<a class="lasso-image">
												<img src="<?php echo SIMPLE_URLS_URL . '/admin/assets/images/displays/essentialism.jpg'; ?>" height="500" width="500">
											</a>
										</div>

										<!-- BUTTONS -->
										<div class="lasso-box-3">
											<a class="lasso-button-1">Buy Now</a>
										</div>

										<div class="lasso-box-4">
											<a class="lasso-button-2">Our Review</a>
										</div>

										<!-- DISCLOSURE & DATE -->
										<div class="lasso-box-5">
											<div class="lasso-disclosure">We earn a commission if you make a purchase, at no additional cost to you.</div>
										</div>

										<div class="lasso-box-6">
											<div class="lasso-date">June 30, 2022 5:41 am UTC <i class="lasso-amazon-info" data-tooltip="Price and availability are accurate as of the date and time indicated and are subject to change."></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6 mb-lg-0 mb-5 h-100">
						<div class="form-group">
							<div class="form-row mb-4">
								<div class="col-lg">
									<label data-tooltip="This is the color of your badge background."><strong>Badge</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text" name="display_color_main" value="<?php echo esc_html( $main_color ); ?>" class="form-control color-picker" placeholder="#5E36CA" />
								</div>

								<div class="col-lg">
									<label data-tooltip="This is the color of the title text of your display."><strong>Title</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text" name="display_color_title" value="<?php echo esc_html( $title_color ); ?>" class="form-control color-picker" placeholder="#FFFFFF" />
								</div>
							</div>

							<div class="form-row mb-4">
								<div class="col-lg">
									<label data-tooltip="This is the color of the inside of your display."><strong>Background</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text" name="display_color_background" value="<?php echo esc_html( $bg_color ); ?>" class="form-control color-picker" placeholder="#FFFFFF" />
								</div>

								<div class="col-lg">
									<label data-tooltip="This is text color for your badges and buttons."><strong>Button + Badge Text</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text" name="display_color_button_text" value="<?php echo esc_html( $button_text_color ); ?>" class="form-control color-picker" placeholder="#FFFFFF" />
								</div>
							</div>

							<div class="form-row mb-4">
								<div class="col-lg">
									<label data-tooltip="This is the main color of the Pros Field."><strong>Pros</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text" name="display_color_pros" value="<?php echo esc_html( $pros_color ); ?>" class="form-control color-picker" placeholder="#FFFFFF" />
								</div>

								<div class="col-lg">
									<label data-tooltip="This is the main color of the Cons Field."><strong>Cons</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text" name="display_color_cons" value="<?php echo esc_html( $cons_color ); ?>" class="form-control color-picker" placeholder="#FFFFFF" />
								</div>
							</div>

							<div class="form-row">
								<div class="col-lg-6">
									<label class="toggle m-0 mr-1">
										<input type="checkbox" id="show_price" name="show_price" <?php echo esc_html( $show_price_attr ); ?> >
										<span class="slider"></span>
									</label>
									<label data-tooltip="Turn this on to show the price in Displays by default. You can override this per display.">Show Price <i class="far fa-info-circle light-purple"></i></label>
								</div>

								<div class="col-lg-6">
									<label class="toggle m-0 mr-1">
										<input type="checkbox" id="show_disclosure" name="show_disclosure" <?php echo esc_html( $show_disclosure_attr ); ?> >
										<span class="slider"></span>
									</label>
									<label data-tooltip="Turn this on to show the disclosure in Displays by default. You can override this per display.">Show Disclosure <i class="far fa-info-circle light-purple"></i></label>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6 mb-lg-0 mb-5 h-100">
						<div class="form-row mb-4">
							<div class="col-lg">
								<label data-tooltip="If you leave your display button text blank, this is what it will default to."><strong>Primary Button</strong> <i class="far fa-info-circle light-purple"></i></label>
								<input type="text" name="primary_button_text" value="<?php echo esc_html( $primary_button_text ); ?>" class="form-control" placeholder="Buy Now" />
							</div>

							<div class="col-lg">
								<label data-tooltip="This is the color of your display's main CTA button.">&nbsp;</label>
								<input type="text" name="display_color_button" value="<?php echo esc_html( $button_color ); ?>" class="form-control color-picker" placeholder="#22BAA0" />
							</div>
						</div>

						<div class="form-row mb-4">
							<div class="col-lg">
								<label data-tooltip="If you set a secondary button for your display and leave it blank, this is what it will default to."><strong>Secondary Button</strong> <i class="far fa-info-circle light-purple"></i></label>
								<input type="text" name="secondary_button_text" value="<?php echo esc_html( $secondary_button_text ); ?>" class="form-control" placeholder="Learn More" />
							</div>

							<div class="col-lg">
								<label data-tooltip="This is the color of your display's secondary CTA button.">&nbsp;</label>
								<input type="text" name="display_color_secondary_button" value="<?php echo esc_html( $secondary_button_color ); ?>" class="form-control color-picker" placeholder="#22BAA0" />
							</div>
						</div>

						<div class="form-row mb-1">
							<div class="col-lg lasso-lite-disabled">
								<label data-tooltip="This is the default disclosure text used with your displays.">
									<span>Disclosure</span> <i class="far fa-info-circle light-purple"></i></label>
								<textarea class="form-control" rows="4" disabled><?php echo $disclosure_text; ?></textarea>
							</div>
							<div class="col-lg">
								<div class="lasso-lite-disabled">
									<label data-tooltip="Earn money sharing Lasso with our affiliate program."><span>Brag Mode</span> <i class="far fa-info-circle light-purple"></i></label>
									<input disabled type="text" class="form-control mb-4" placeholder="Your Lasso Affiliate URL" value="<?php echo $lasso_affiliate_url; ?>">
								</div>
								<div class="no-hint">
									<label class="toggle m-0 mr-1">
										<input name="enable_brag_mode" type="checkbox" <?php echo esc_html( $enable_brag_mode_attr ); ?>>
										<span class="slider"></span>
									</label>
									<label class="m-0">Enable Brag Mode</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<!-- SAVE CHANGES -->
		<div class="row align-items-center">
			<div class="col-lg text-lg-left text-center">
				<a class="btn black white-bg black-border" id="need-more-customization">Need More Customization?</a>
			</div>
			<div class="col-lg text-lg-right text-center">
				<button id="btn-save-settings-display" type="button" class="btn">Save Changes</button>
			</div>
		</div>
	</div>
</section>

<?php echo Helper::wrapper_js_render( 'default-template-notification', Helper::get_path_views_folder() . '/notifications/default-template-jsrender.html' )?>
<?php Config::get_footer(); ?>
