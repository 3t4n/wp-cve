<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( isset( $_POST['security'] ) ) {
	if ( wp_verify_nonce( $_POST['security'], 'feed_security_action' ) ) {
		if ( isset( $_REQUEST['ffp_page_identifier'] ) ) {
			$facebook_feed = serialize(
				array(
					'ffp_limit'           => sanitize_text_field( $_REQUEST['ffp_limit'] ),
					'ffp_timeline_layout' => sanitize_text_field( $_REQUEST['ffp_timeline_layout'] ),
					'feed_customs_css'    => sanitize_text_field( $_REQUEST['feed_customs_css'] ),
					'ffp_hover_color'     => sanitize_text_field( $_REQUEST['ffp_hover_color'] ),
					'ffp_header_check'    => sanitize_text_field( $_REQUEST['ffp_header_check'] ),
					'ffp_page_url'        => sanitize_text_field( $_REQUEST['ffp_page_identifier'] ),
					'ffp_page_id'         => sanitize_text_field( $_REQUEST['ffp_page_id'] ),
				)
			);
			update_option( 'weblizar_facebook_feed_option_settings', $facebook_feed );
		}
	}
}
$facebook_feed_fetch = unserialize( get_option( 'weblizar_facebook_feed_option_settings' ) );
if ( isset( $facebook_feed_fetch['ffp_limit'] ) ) {
	$ffp_limit = $facebook_feed_fetch['ffp_limit'];
} else {
	$ffp_limit = '5';
}
if ( isset( $facebook_feed_fetch['ffp_timeline_layout'] ) ) {
	$ffp_timeline_layout = $facebook_feed_fetch['ffp_timeline_layout'];
} else {
	$ffp_timeline_layout = 'full_width';
}
if ( isset( $facebook_feed_fetch['ffp_page_url'] ) ) {
	$ffp_page_identifier = $facebook_feed_fetch['ffp_page_url'];
} else {
	$ffp_page_identifier = '1614483668626851';
}
if ( isset( $facebook_feed_fetch['feed_customs_css'] ) ) {
	$feed_customs_css = $facebook_feed_fetch['feed_customs_css'];
} else {
	$feed_customs_css = '';
}
if ( isset( $facebook_feed_fetch['ffp_hover_color'] ) ) {
	$ffp_hover_color = $facebook_feed_fetch['ffp_hover_color'];
} else {
	$ffp_hover_color = '#2e2c2c';
}
if ( isset( $facebook_feed_fetch['ffp_header_check'] ) ) {
	$ffp_header_check = $facebook_feed_fetch['ffp_header_check'];
} else {
	$ffp_header_check = 'no';
}
if ( isset( $facebook_feed_fetch['ffp_page_id'] ) ) {
	$ffp_page_id = $facebook_feed_fetch['ffp_page_id'];
} else {
} ?>
<!-- facebook feed tab -->
<?php $feed_security_action_nonce = wp_create_nonce( 'feed_security_action' ); ?>
<div class="block ui-tabs-panel deactive" id="option-fbfeed">
	<div class="section">
		<form method="post" id="weblizar_feed_setting_option">
			<div class="feed_setting_page">
				<div class="feedheading_cls">
					<h3 class="feedheading"><?php esc_html_e( 'Feed Settings', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
					</h3>
				</div>
				<div class="row">
					<div class="col-md-6 fpp_border_left">
						<div class="col-md-12 no-pad form-group pb-1">
							<div class="col-md-12 no-pad pt-1">
								<div class="row">
									<div class="ffp_set_l col-md-6">
										<label class="ffp_font_bold"> <?php esc_html_e( 'Choose page, Group or Profile:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
									</div>
									<div class="col-md-6">
										<select name="feed_type" id="feed_type" style="width:100px;"
											onchange="feed_type_change_function()">
											<option value="page" selected="selected"> <?php esc_html_e( 'Page', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</option>
											<!-- <option value="group"> <?php esc_html_e( 'Group', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</option>
											<option value="profile"> <?php esc_html_e( 'Profile', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</option> -->
										</select>
									</div>
								</div>
							</div>
							<!-- facebook access token-->
							<div id="ffp_type_page" class="col-md-12 no-pad ffp_page_id" style="display: none;">
								<div class="row">
									<div class="ffp_set_l col-md-6">
										<label class="ffp_font_bold"><?php esc_html_e( 'Facebook Access Token:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
									</div>
									<div class="ffp_set_l col-md-6">
										<input type="text" id="ffp_page_id" name="ffp_page_id" value="
										<?php
										if ( isset( $ffp_page_id ) ) {
											echo esc_attr( $ffp_page_id );
										} else {
											echo esc_attr( 'EAAETHMFXDyMBAL5uQJfUQXO8uif3recXPjo8PPSbiLTtBsZBVPGxd6cshFjv7MVJwaODhScG6StiScG6go7jpDxaiwLKhcdTjqxrZAce6M5wXAidvAGnPirKLeGBNbCLixDr1oE2QO68uvDWy1227ZAWeRCVLQfK5Lb3HWuiQZDZD' );
										}
										?>
										" style="width:100%">
									</div>
									<div class="ffp_set_l col-md-6"></div>
									<div class="ffp_set_l col-md-6"><a
											href="https://weblizar.com/blog/generate-facebook-access-token/"
											style="width:100%"><?php esc_html_e( 'get your access token.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a>
									</div>
								</div>
							</div>
							<!-- fb type page -->
							<div id="ffp_type_page" class="col-md-12 no-pad ffp_page_url" style="display:none;">
								<div class="row">
									<div class="ffp_set_l col-md-6">
										<label class="ffp_font_bold"><?php esc_html_e( 'Page ID:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
									</div>
									<div class="ffp_set_l col-md-6">
										<input type="text" id="ffp_page_identifier" name="ffp_page_identifier" value="
										<?php
										if ( isset( $ffp_page_identifier ) ) {
											echo esc_attr( $ffp_page_identifier );
										} else {
											echo esc_attr( '1614483668626851' );
										}
										?>
										" style="width:100%">
									</div>
								</div>
							</div>
							<!-- fb type group -->
							<div id="ffp_type_group" class="col-md-12 no-pad" style="display:none; color:red;">
								<div class="row">
									<div class="col-md-6">
										<label for="ffp_group_url" class="ffp_font_bold"> <?php esc_html_e( 'Group id:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
										</label>
									</div>
									<div class="col-md-6"><?php esc_html_e( 'This Options Available in pro version', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
									</div>
								</div>
							</div>
							<div id="ffp_type_group_token" class="col-md-12 no-pad" style="display:none; color:red;">
								<div class="row">
									<div class="col-md-6">
										<label for="ffp_group_token" class="ffp_font_bold"><?php esc_html_e( 'Acess Token:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
									</div>
									<div class="col-md-6"><?php esc_html_e( 'This Option Available in pro version', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
									</div>
								</div>
							</div>
							<!-- fb type profile -->
							<!-- fb content type -->
							<div class="col-md-12 no-pad pt-1 pb-1">
								<div class="row">
									<div class="col-md-6">
										<label class="ffp_font_bold"><?php esc_html_e( 'Content type:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
										</label>
									</div>
									<div class="col-md-6">
										<input type="radio" class="inputbox" id="ffp_content_timeline"
											name="ffp_content_type" checked="checked" value="timeline"
											onclick="feed_timelineChanged();">
										<label> <?php esc_html_e( 'Timeline', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>&nbsp;
										<input type="radio" class="inputbox" id="ffp_content_specific"
											name="ffp_content_type" value="specific" onclick="feed_specificChanged();">
										<label> <?php esc_html_e( 'Specific', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
									</div>
								</div>
								<br>
								<div class="col-md-12 no-pad timeline_content" style="display:none">
									<div class="row">
										<div class="col-md-6">
											<label class="ffp_font_bold"> <?php esc_html_e( 'Show posts by:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</label>
										</div>
										<div class="col-md-6">
											<select name="ffp_timeline_type" disabled id="ffp_timeline_type">
												<option value="posts"><?php esc_html_e( 'Owner', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
												</option>
												<option value="feed"><?php esc_html_e( 'Owner and other', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
												</option>
											</select>
											<p class="description"><?php esc_html_e( 'Available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-12 no-pad timeline_content" style="display:none">
									<div class="row">
										<div class="col-md-6">
											<label class="ffp_font_bold"> <?php esc_html_e( 'Post type:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" class="video_light_box" id="ffp_timeline_statuses"
												name="ffp_timeline_statuses" value="statuses" disabled>
											<label> <?php esc_html_e( 'Statuses', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>&nbsp;
											<br>
											<input type="checkbox" class="video_light_box" id="ffp_timeline_photos"
												checked="checked" name="ffp_timeline_photos" value="photos">
											<label> <?php esc_html_e( 'Photos', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
											<br>
											<input type="checkbox" class="video_light_box" id="ffp_timeline_videos"
												name="ffp_timeline_videos" value="videos" disabled>
											<label> <?php esc_html_e( 'Videos', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
											<br>
											<input type="checkbox" class="video_light_box" id="ffp_timeline_links"
												name="ffp_timeline_links" value="links" disabled>
											<label> <?php esc_html_e( 'Links', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
											<br>
											<input type="hidden" class="video_light_box" id="ffp_timeline_events"
												name="ffp_timeline_events" value="events" disabled>
											<label style="display:none;"> <?php esc_html_e( 'Events', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="ffp_font_bold"> <?php esc_html_e( 'Hover Effect', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
									</div>
									<div class="col-md-6">
										<select name="ffp_gallery_effect" id="ffp_gallery_effect" class="form-control"
											disabled>
											<option value="gallery_effect_1"><?php esc_html_e( '60+ Image Effects', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</option>
										</select>
										<p class="description"><?php esc_html_e( 'Available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<div class="col-md-12 no-pad form-group pb-1">
								<div class="col-md-12 no-pad specific_contents">
									<h3></h3>
								</div>
								<div class="col-md-12 no-pad">
									<div class="row">
										<div class="col-md-6">
											<label class="ffp_font_bold"><?php esc_html_e( 'Hover color', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
										</div>
										<div class="col-md-6">
											<input type="text" class="weblizar-feed-color-picker" name="ffp_hover_color"
												id="ffp_hover_color" value="
												<?php
												if ( isset( $ffp_hover_color ) ) {
													echo esc_attr( $ffp_hover_color );
												} else {
													echo esc_attr( '#2e2c2c' );
												}
												?>
												" />
											<p class="description"><?php esc_html_e( 'Choose feed hover color.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-12 no-pad">
									<div class="row">
										<div class="col-md-6">
											<label class="ffp_font_bold"> <?php esc_html_e( 'Number of posts:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
										</div>
										<div class="col-md-6">
											<input type="number" min="1" max="50" id="ffp_limit" name="ffp_limit" value="
											<?php
											if ( isset( $ffp_limit ) ) {
												echo esc_attr( $ffp_limit );
											} else {
												echo esc_attr( '5' );
											}
											?>
											">
										</div>
									</div>
								</div>
								<div class="col-md-12 no-pad">
									<div class="row">
										<div class="col-md-6">
											<label class="ffp_font_bold"> <?php esc_html_e( 'Loading effect:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
										</div>
										<div class="col-md-6">
											<select id="ffp_loading_effect" disabled name="ffp_loading_effect">
												<option value="none"> <?php esc_html_e( 'None', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
												</option>
											</select>
											<p class="description"><?php esc_html_e( 'Available in pro version.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-12 no-pad">
									<div class="row">
										<div class="col-md-6 ">
											<label class="ffp_font_bold"><?php esc_html_e( 'Show Header:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" <?php if ( $ffp_header_check == 'yes' ) { ?>
											checked="checked" <?php } ?> id="ffp_header_check"
											name="ffp_header_check" value="yes" >
											<p class="description"><?php esc_html_e( 'Do you want to show cover image.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-12 no-pad timeline_content" style="display:none">
									<div class="row">
										<div class="col-md-6">
											<label class="ffp_font_bold"> <?php esc_html_e( 'Select layout:', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</label>
										</div>
										<?php
										if ( ( ! isset( $ffp_timeline_layout ) ) ) {
											$ffp_timeline_layout = 'full_width';
										}
										?>
										<div class="col-md-6">
											<select id="ffp_timeline_layout" name="ffp_timeline_layout">
												<optgroup label="Select layout">
													<option value="full_width" <?php selected( $ffp_timeline_layout, 'full_width' ); ?>>
														<?php esc_html_e( 'Full-width', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
													</option>
													<option value="half_width" <?php selected( $ffp_timeline_layout, 'half_width' ); ?>>
														<?php esc_html_e( 'Half-width', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
													</option>
													<option value="thumbnail" <?php selected( $ffp_timeline_layout, 'thumbnail' ); ?>>
														<?php esc_html_e( 'Thumbnail', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
													</option>
												</optgroup>
											</select>
											<p class="description"><?php esc_html_e( 'Select time-line(Posts) layout.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-12 no-pad">
									<div class="row">
										<div class="col-md-6">
											<label class="ffp_font_bold"><?php esc_html_e( 'Light Box Styles', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
										</div>
										<div class="col-md-6">
											<select name="ffp_light_Box" id="ffp_light_Box">
												<optgroup label="Select Light Box">
													<option value="custom_box"><?php esc_html_e( 'Custom Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
													</option>
													<option value="custom_box"><?php esc_html_e( ' 8+Light-Box Layouts available in Pro', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
													</option>
												</optgroup>
											</select>
											<p class="description"><?php esc_html_e( 'Select lightbox Styles.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>.
											</p>
											<input type="hidden" name="feed_security" id="feed_security"
												value="<?php echo esc_attr( $feed_security_action_nonce ); ?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 plugin_desc form-group">
					<div class="row">
						<div class="col-md-1">
							<label class="ffp_font_bold"><?php esc_html_e( 'Custom Css', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></label>
						</div>
						<div class="col-md-11">
							<textarea class="form-control" name="feed_customs_css" id="feed_customs_css"
								placeholder="Custom Css"
								rows="8"><?php echo esc_html( $feed_customs_css ); ?></textarea>
						</div>
					</div>
				</div>
				<div style="clear:both"></div>
			</div>
		</form>
		<div class="col-md-12">
			<button type="button" name="button" class="button-face-feed"
				onclick="save_feed_general('<?php echo esc_attr( $feed_security_action_nonce ); ?>')">
				<?php esc_html_e( 'Save', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></button>
			<div style="text-align:center;">
				<img id="loading-image"
					src="<?php echo esc_url( WEBLIZAR_FACEBOOK_PLUGIN_URL . 'images/loader.gif' ); ?>"
					alt="<?php esc_attr_e( 'Weblizar', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>"
					height="200" style="margin-top:-10px; margin-right:10px;" alt="Loading..."
					class="admin_loading_css" />
			</div>
			<div class="success-msg" style="display:none;">
				<div class="alert alert-success">
					<strong><?php esc_html_e( 'Success!', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></strong>
					<?php esc_html_e( 'Data Save Successfully.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12 form-group plugin_desc">
			<h1 class="feed_plugin_details"><?php esc_html_e( 'Plugin Shortcode', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
			</h1>
			<p style="font-size: 15px;line-height: 1.5;"><?php esc_html_e( 'copy this shortcode', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
				&nbsp; <strong><b>[facebook_feed]</b></strong> &nbsp;<?php esc_html_e( 'to any page, post or widget where you want to showcase your Facebook feed.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
			</p>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<script>
	jQuery(document).ready(function() {
		jQuery('input.weblizar-feed-color-picker').wpColorPicker();
	});
</script>
