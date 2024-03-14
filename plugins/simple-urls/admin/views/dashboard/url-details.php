<?php
/**
 * Dashboard
 *
 * @package Dashboard
 */
use LassoLite\Admin\Constant;
use LassoLite\Classes\Affiliate_Link;
use LassoLite\Classes\Amazon_Api;
use LassoLite\Classes\Config;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Group;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Page;
use LassoLite\Classes\Setting;

$post_id   = esc_html( $_GET['post_id'] ?? 0 );
$post_id   = intval( $post_id ) > 0 ? $post_id : 0;
$is_update = $post_id > 0 ? 1 : 0;

if ( $post_id > 0 && get_post_type( $post_id ) !== SIMPLE_URLS_SLUG ) {
	wp_redirect(  'edit.php?post_type=' . SIMPLE_URLS_SLUG . '&page=' . SIMPLE_URLS_SLUG . '-' . Enum::PAGE_DASHBOARD ); // phpcs:ignore
	exit;
}

$lasso_lite_url = Affiliate_Link::get_lasso_url( $post_id, true );

if ( '' === $lasso_lite_url->name ) {
	$url_details_h1 = 'Enter a URL Name';
} else {
	$url_details_h1 = $lasso_lite_url->name;
}

$shortcode_html = do_shortcode( '[lasso id="' . $post_id . '"]' );

$lasso_lite_settings         = Setting::get_settings();
$is_amazon_link              = Amazon_Api::is_amazon_url( $lasso_lite_url->target_url );
$is_amazon_link_attr         = $is_amazon_link ? '1' : '0';
$amazon_primary_tracking_id  = Amazon_Api::get_amazon_tracking_id_by_url( $lasso_lite_url->target_url );
$amazon_tracking_id          = $lasso_lite_settings['amazon_tracking_id'];
$disable_amazon_notification = $lasso_lite_settings['general_disable_amazon_notifications'] ? '1' : '0';
$permalink_display_class     = $is_amazon_link ? 'd-none' : '';
$amazon_setting_page         = Page::get_lite_page_url( Enum::PAGE_SETTINGS_AMAZON );
$amazon_product_id           = $is_amazon_link ? Amazon_Api::get_product_id_by_url( $lasso_lite_url->target_url ) : '';

$groups = Group::get_list();
// ? Categories
if ( isset( $lasso_lite_url->category ) ) {
	$category_options = '';
	foreach ( $groups as $group ) {
		$selected = '';
		if ( in_array( $group->get_id(), $lasso_lite_url->category, true ) ) {
			$selected = 'selected';
		}
		$category_options .= '<option name="affiliate_categories" value="' . $group->get_id() . '" ' . $selected . '>' . esc_html( $group->get_post_title() ) . '</option>';
	}
}

$price_disabled = $amazon_product_id ? 'disabled' : '';
?>

<?php Config::get_header(); ?>

<section class="py-5">
	<div class="lite-container container"
		data-is-amazon-link="<?php echo $is_amazon_link_attr; ?>"
		data-amazon-primary-tracking-id="<?php echo $amazon_primary_tracking_id; ?>"
		data-amazon-tracking-id="<?php echo $amazon_tracking_id; ?>"
		data-disable-amazon-notification="<?php echo $disable_amazon_notification; ?>"
	>
		<?php require Helper::get_path_views_folder() . 'dashboard/header.php'; ?>
		<form id="url-details" autocomplete="off">
			<!-- EDIT DETAILS -->
			<div class="row mb-5">
				<div class="col-lg-5 mb-lg-0 mb-5 h-100">
					<div class="white-bg rounded shadow p-4">
						<div class="d-none">
							<input id="is-update" class="" type="hidden" value="<?php echo $is_update; ?>">
							<input id="lasso-lite-id" class="" type="hidden" value="<?php echo intval( $lasso_lite_url->id ); ?>">
							<input name="uri" class="lasso-admin-input" type="text" value="<?php echo intval( $lasso_lite_url->id ); ?>">
							<input name="guid" class="lasso-admin-input hidden" type="text" value="<?php echo esc_html( $lasso_lite_url->guid ); ?>">
						</div>

						<!-- NAME -->
						<div class="form-group mb-4">
							<label data-tooltip="This title will only be shown in displays"><strong>Name</strong> <i class="far fa-info-circle light-purple"></i></label>
							<input id="affiliate_name" name="affiliate_name" type="text" class="form-control" value="<?php echo str_replace( '"', '&quot;', esc_html( $lasso_lite_url->name ) ); ?>" placeholder="URL Name Goes Here">
						</div>

						<!-- PERMALINK -->
						<div class="form-group mb-4 permalink-wrapper <?php echo $permalink_display_class ?>">
							<label data-tooltip="This slug will be used to cloak the the original target URL"><strong>Permalink</strong> <i class="far fa-info-circle light-purple"></i></label>
							<input id="permalink" name="permalink" type="text" class="form-control" value="<?php echo esc_html( $lasso_lite_url->slug ); ?>" placeholder="affiliate-name">
						</div>

						<!-- PRIMARY TARGET URL -->
						<div class="row">
							<div class="col-lg-8">
								<div class="form-group mb-4">
									<label data-tooltip="The actual URL you want people to go to when they click a link"><strong>Primary Destination URL</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input id="surl_redirect"
										class="form-control"
										name="surl_redirect"
										type="text"
										data-original-value="<?php echo esc_html( $lasso_lite_url->target_url ) ?>"
										value="<?php echo esc_html( $lasso_lite_url->target_url ); ?>"
										placeholder="https://www.example.com/affiliate-id">
								</div>
							</div>

							<div class="col-lg-4">
								<div class="form-group mb-4">
									<label data-tooltip="This text will appear in the primary button"><strong>Button Text</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text"
										class="form-control"
										id="buy_btn_text"
										value="<?php echo esc_html( $lasso_lite_url->display->primary_button_text ); ?>"
										placeholder="<?php echo esc_html( $lasso_lite_url->display->primary_button_text_default ); ?>">
								</div>
							</div>

							<div class="col-lg mb-4">
								<label class="toggle m-0 mr-1">
									<input id="url-open-link" name="open_new_tab" type="checkbox" <?php echo esc_html($lasso_lite_url->url_detail_checkbox->open_new_tab); ?>>
									<span class="slider"></span>
								</label>
								<label data-tooltip="When enabled, users who click this link will have it loaded in a new tab.">New Window / Tab <i class="far fa-info-circle light-purple"></i></label>
							</div>

							<div class="col-lg text-right">
								<label class="toggle m-0 mr-1">
									<input name="enable_nofollow" id="url-en-nofollow" type="checkbox" <?php echo esc_html( $lasso_lite_url->url_detail_checkbox->enable_nofollow ); ?>>
									<span class="slider"></span>
								</label>
								<label data-tooltip="When enabled, this link will be set to nofollow. This indicates to Google that it's an affiliate link.">NoFollow / NoIndex <i class="far fa-info-circle light-purple"></i></label>
							</div>
						</div>

						<!-- SECONDARY TARGET URL -->
						<div class="row">
							<div class="col-lg-8 lasso-lite-disabled">
								<div class="form-group mb-4">
									<label data-tooltip="A secondary URL you want people to go to when they click an optional second button in displays">
										<strong>Secondary Destination URL</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text" class="form-control" disabled value="" placeholder="https://www.example.com/affiliate-id2">
								</div>
							</div>

							<div class="col-lg-4 lasso-lite-disabled">
								<div class="form-group mb-4">
									<label data-tooltip="This text will appear in the optional secondary button"><strong>Button Text</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input type="text" class="form-control" disabled placeholder="Our Review">
								</div>
							</div>

							<div class="col-lg mb-4">
								<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
									<input name="open_new_tab2" disabled type="checkbox" checked="">
									<span class="slider"></span>
								</label>
								<label data-tooltip="When enabled, users who click this link will have it loaded in a new tab.">
									<span class="lasso-lite-disabled no-hint">New Window / Tab</span> <i class="far fa-info-circle light-purple"></i>
								</label>
							</div>

							<div class="col-lg text-right">
								<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
									<input name="enable_nofollow2" disabled type="checkbox" checked="">
									<span class="slider"></span>
								</label>
								<label data-tooltip="When enabled, this link will be set to nofollow. This indicates to Google that it's an affiliate link.">
									<span class="lasso-lite-disabled no-hint">NoFollow / NoIndex</span> <i class="far fa-info-circle light-purple"></i>
								</label>
							</div>
						</div>

						<!-- GROUPS -->
						<div class="form-group mb-4">
							<label data-tooltip="Group this URL for better organization and to use in group displays"><strong>Groups</strong> <i class="far fa-info-circle light-purple"></i></label>
							<select class="form-control select2-hidden-accessible" multiple name="categories[]" id="basic-categories" data-placeholder="Select a group" tabindex="-1" aria-hidden="true">
								<?php echo $category_options ?>
							</select>
						</div>

						<!-- CUSTOM FIELDS -->
						<div class="form-group mb-1 lasso-lite-disabled">
							<div class="add-custom-fields">
								<button class="btn" type="button">Add Custom Field</button>
								<button class="btn ml-3 learn-btn" type="button">
									<i class="far fa-info-circle"></i> Learn</button>
							</div>
						</div>
					</div>
				</div>

				<!-- EDIT MORE DETAILS -->
				<div class="col-lg-7">
					<div class="white-bg rounded shadow p-4">
						<div class="image_loading onboarding d-none"></div>
						<div id="demo_display_box">
							<?php
							if ( '' !== $shortcode_html ) : ?>
								<?php echo $shortcode_html; ?>
							<?php else: ?>
								<div class="lasso-container lasso-lite">
									<!-- LASSO DISPLAY BOX (https://getlasso.co) -->
									<div class="lasso-display lasso-cactus">
										<!-- LASSO TITLE AND IMAGE -->
										<div class="lasso-box-1">
											<a class="lasso-title" target="_blank" href="#" rel="noopener">
												Enter a URL Name
											</a>
											<div class="clear"></div>
										</div>

										<div class="lasso-box-2">
											<a href="#" id="lasso-thumbnail">
												<div class="image_wrapper d-block">
													<img id="render_thumbnail" src="<?php echo Constant::DEFAULT_THUMBNAIL ?>" loading="lazy" class="render_thumbnail img-fluid url_image">
													<div class="image_loading d-none"></div>
													<div class="image_hover">
														<div class="image_update"><i class="far fa-camera-alt"></i> Update Image</div>
													</div>
												</div>
											</a>
										</div>

										<!-- BUTTONS -->
										<div class="lasso-box-3">
											<a class="lasso-button-1" target="_blank" href="#" rel="">
												<?php echo esc_html( $lasso_lite_url->display->primary_button_text_default ); ?>
											</a>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<!-- IMAGE PREVIEW -->
						<div class="image_container position-relative d-none image_editor_wrapper" id="image_editor">
							<a href="#" id="lasso-thumbnail">
								<div class="image_wrapper d-block">
									<img id="render_thumbnail" src="<?php echo esc_html( $lasso_lite_url->image_src ); ?>" loading="lazy" class="render_thumbnail img-fluid url_image">
									<div class="image_loading d-none"></div>
									<div class="image_hover">
										<div class="image_update"><i class="far fa-camera-alt"></i> Update Image</div>
									</div>
								</div>
							</a>
						</div>

						<input type="hidden" id="thumbnail_id" name="thumbnail_id" value="<?php echo esc_html( $lasso_lite_url->thumbnail_id ); ?>"/>

						<!-- THEME & IMAGE REFRESH -->
						<div class="row lasso-lite-disabled">
							<div class="col-md">
								<div class="form-group mb-4">
									<label data-tooltip="Choose the default display theme for this link."><strong>Display Theme</strong> <i class="far fa-info-circle light-purple"></i></label>
									<select id="theme_name" name="theme_name" class="form-control lasso-lite-disabled no-hint" disabled>
										<option value="" selected="">Cactus</option>
									</select>
								</div>
							</div>
						</div>

						<!-- PRICE & BADGE TEXT -->
						<div class="row">
							<div class="col-lg">
								<div class="form-group mb-4">
									<label data-tooltip="This text will appear as a badge in displays for this URL."><strong>Badge Text</strong> <i class="far fa-info-circle light-purple"></i></label>
									<input id="badge_text"
										type="text"
										name="badge_text"
										class="form-control"
										placeholder="Our Pick"
										value="<?php echo esc_html( $lasso_lite_url->display->badge_text ); ?>">
								</div>
							</div>
							<div class="col-lg">
								<div class="form-group">
									<label data-tooltip="This price can be any text or amount that'll only be shown in displays. Prices automatically update every 24 hours with integrations like Amazon.">
										<strong>Price</strong> <i class="far fa-info-circle light-purple"></i>
									</label>
									<div class="float-right">
										<label data-tooltip="Turn this on to show the price in this display."><i class="far fa-info-circle light-purple"></i></label>
										<label class="toggle">
											<input id="show_pricing" type="checkbox" <?php echo esc_html( $lasso_lite_url->url_detail_checkbox->show_price ); ?>>
											<span class="slider"></span>
										</label>
									</div>
									<input name="price" type="text" id="price" class="form-control" value="<?php echo esc_html( $lasso_lite_url->price ); ?>" placeholder="$99.99" <?php echo esc_html( $price_disabled ); ?> >
									<?php if ( $amazon_product_id ): // ? Can't change the price of an Amazon Product ?>
										<em class="small"><span class="dark-gray">Amazon prices updated via </span> 
											<a target="_blank" href="<?php echo esc_html( $amazon_setting_page ) ?>" class="purple underline">Amazon API settings</a>
										</em>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<!-- DESCRIPTION -->
						<div class="form-group mb-4">
							<label data-tooltip="This description will only be shown in displays"><strong>Description</strong> <i class="far fa-info-circle light-purple"></i></label>
							<div class="form-control" id="description">
								<?php echo $lasso_lite_url->description ?>
                            </div>
						</div>

						<!-- DISCLOSURE -->
						<div class="form-group mb-4 lasso-lite-disabled">
							<label data-tooltip="This disclosure will only be shown in displays"><strong>Disclosure</strong> <i class="far fa-info-circle light-purple"></i></label>
							<textarea disabled class="form-control" rows="2" placeholder="We earn a commission if you make a purchase, at no additional cost to you."></textarea>
						</div>

						<!-- DISPLAY TOGGLES -->
						<div class="form-group mb-3">
							<div class="form-row">
								<div class="col-lg-6 lasso-lite-disabled">
									<label class="toggle m-0 mr-1">
										<input disabled name="is_opportunity" type="checkbox">
										<span class="slider"></span>
									</label>
									<label class="mb-1" data-tooltip="When disabled, Link Opportunities will not show for this Lasso Link.">
										<span>Detect Opportunities</span> <i class="far fa-info-circle light-purple"></i>
									</label>
								</div>
								<div class="col-lg-6">
									<label class="toggle m-0 mr-1">
										<input id="show_disclosure" type="checkbox" <?php echo esc_html( $lasso_lite_url->url_detail_checkbox->show_disclosure ); ?>>
										<span class="slider"></span>
									</label>
									<label data-tooltip="Turn this on to show the disclosure in Displays for this Lasso Link.">
										<span>Show Disclosure</span> <i class="far fa-info-circle light-purple"></i>
									</label>
								</div>
							</div>
						</div>

						<!-- LINK ACTION TOGGLES -->
						<div class="form-group mb-1">
							<div class="form-row">
								<div class="col-lg">
									<label class="toggle m-0 mr-1">
										<input id="enable_sponsored" name="enable_sponsored" type="checkbox" <?php echo esc_html( $lasso_lite_url->url_detail_checkbox->enable_sponsored ); ?>>
										<span class="slider"></span>
									</label>
									<label data-tooltip="When enabled, this link will be set to sponsored.">Sponsored <i class="far fa-info-circle light-purple"></i></label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!-- SAVE & DETELE -->
		<div class="row align-items-center">
			<!-- SAVE CHANGES -->
			<div class="col-lg order-lg-2 text-lg-right text-center mb-4">
				<a id="learn-more-link-details" href="https://support.getlasso.co/en/articles/5847370-how-to-use-the-link-details-page" target="_blank" class="btn black white-bg black-border mr-3">Learn About This Page</a>
				<button id="btn-save-url" class="btn">Save Changes</button>
			</div>

			<!-- DELETE URL -->
			<div class="col-lg text-lg-left text-center mb-4">
				<a href="#" id="btn-confirm-delete" class="red hover-red-text"><i class="far fa-trash-alt"></i> Delete This Link</a>
			</div>
		</div>
	</div>
</section>

<!-- DELETE CONFIRMATION -->
<div class="modal fade" id="url-delete" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content text-center shadow p-5 rounded">
			<h2>Delete This Link?</h2>
			<p>If deleted, you won't be able to get it back.</p>
			<div>
				<button type="button" class="btn black white-bg black-border mr-3" data-dismiss="modal">
					Cancel
				</button>
				<button id="lasso-delete-url" type="button" class="btn red-bg mx-1">
					Delete
				</button>
			</div>
		</div>
	</div>
</div>

<!-- URL SAVE PROGRESS -->
<div class="modal fade" id="url-save" data-backdrop="static" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content p-5 shadow text-center">
			<h2>Updating Your Site</h2>
			<div class="progress mt-3 mb-3">
				<div class="progress-bar progress-bar-striped progress-bar-animated green-bg" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
			</div>
			<p class="js-message">Just checking all the affected links and/or keywords on your site to make sure everything's updated.</p>
		</div>
	</div>
</div>


<?php echo Helper::wrapper_js_render( 'save-success-notification', Helper::get_path_views_folder() . '/notifications/save-success-jsrender.html' )?>
<?php echo Helper::wrapper_js_render( 'default-template-notification', Helper::get_path_views_folder() . '/notifications/default-template-jsrender.html' )?>
<?php echo Helper::wrapper_js_render( 'amazon-url-detected', Helper::get_path_views_folder() . '/notifications/amazon-url-detected-jsrender.html' )?>

<?php Config::get_footer(); ?>
