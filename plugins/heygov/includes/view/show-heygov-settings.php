<?php

// HeyGov ID
$heygov_id = get_option('heygov_id');

// HeyGov api key 
$heygov_api_key = get_option('heygov_api_key');

// widget info
$heygov_features = explode(',', get_option('heygov_features') ?: 'issues');
$heygov_btn_text = get_option('heygov_btn_text') ?: 'Submit a request';
$heygov_btn_position = get_option('heygov_btn_position') ?: 'bottom-right';
$heygov_location_required = get_option('heygov_location_required') ?: 0;

// apps banner info
$heygov_banner = get_option('heygov_banner') ?: 0;
$heygov_banner_bg_color = get_option('heygov_banner_bg_color') ?: '#EEF4FE';
$heygov_banner_img_big = get_option('heygov_banner_img_big') ?: HEYGOV_URL . 'assets/banner.jpg';
$heygov_banner_img_small = get_option('heygov_banner_img_small') ?: HEYGOV_URL . 'assets/banner-mobile.jpg';

// validate & save HeyGov ID
if (isset($_POST['heygov'])) {
	$id = heygov_validate_id(sanitize_text_field($_POST['heygov']['id']));
	$key = sanitize_key($_POST['heygov_api_key']);

	if (is_wp_error($id)) {
		echo wp_kses('<div class="notice notice-error"><p>' . $id->get_error_message() . '</p></div>', 'post');
	} else {
		$heygov_id = $id;
		$heygov_api_key = $key;
		update_option('heygov_id', $id);
		update_option('heygov_api_key', $key);
?>
		<div class="notice notice-success">
			<p>HeyGov settings are updated.</p>
		</div>
	<?php
	}
}

// save widget settings
if (isset($_POST['heygov_widget'])) {
	$heygov_features = array_map('sanitize_key', $_POST['heygov_widget']['features']);
	$heygov_btn_text = sanitize_text_field($_POST['heygov_widget']['text']);
	$heygov_btn_position = sanitize_text_field($_POST['heygov_widget']['position']);
	$heygov_location_required = sanitize_text_field($_POST['heygov_widget']['location'])  === 'on' ? 1 : 0;

	update_option('heygov_features', implode(',', $heygov_features));
	update_option('heygov_btn_text', $heygov_btn_text);
	update_option('heygov_btn_position', $heygov_btn_position);
	update_option('heygov_location_required', $heygov_location_required);
	?>
	<div class="notice notice-success">
		<p>HeyGov widget is updated.</p>
	</div>
<?php
}

// save banner settings
if (isset($_POST['heygov_banner'])) {
	$heygov_banner = isset($_POST['heygov_banner']['heygov_banner']) && $_POST['heygov_banner']['heygov_banner'] === 'on' ? 1 : 0;
	$heygov_banner_bg_color = sanitize_text_field($_POST['heygov_banner']['bg_color']);
	$heygov_banner_img_big = esc_url_raw($_POST['heygov_banner']['image_big']);
	$heygov_banner_img_small = esc_url_raw($_POST['heygov_banner']['image_small']);

	update_option('heygov_banner', $heygov_banner);
	update_option('heygov_banner_bg_color', $heygov_banner_bg_color);
	update_option('heygov_banner_img_big', $heygov_banner_img_big);
	update_option('heygov_banner_img_small', $heygov_banner_img_small);

?>
	<div class="notice notice-success">
		<p>HeyGov apps banner is updated.</p>
	</div>
<?php
}
?>

<div class="wrap">
	<h1 class="wp-heading-inline">HeyGov</h1>
	<hr class="wp-header-end">

	<form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">

		<table class="form-table">
			<tr>
				<th><label for="heygov_id">HeyGov ID</label></th>
				<td>
					<input type="text" name="heygov[id]" class="regular-text" id="heygov_id" value="<?php echo esc_attr($heygov_id ?: ''); ?>" />
					<p class="description">Usually the website domain without www, ex: <code>town.com</code></p>
				</td>
			</tr>
			<tr>
				<th><label for="heygov_api_key">HeyGov API Key</label></th>
				<td>
					<input type="text" name="heygov_api_key" pattern="sk_[A-Za-z0-9_]{26,29}" class="regular-text" id="heygov_api_key" value="<?php echo esc_attr($heygov_api_key ?: ''); ?>" />
					<p class="description">Ask HeyGov support to provide you HeyGov api key</p>
				</td>
			</tr>
			<tr>
				<th class="heygov-py-0"></th>
				<td class="heygov-py-0">
					<p class="heygov-py-0 submit">
						<input type="submit" name="heygov_submit" id="heygov_submit" class="button button-primary" value="Save HeyGov settings">
					</p>
				</td>
			</tr>
		</table>

	</form>

	<?php if ($heygov_id) : ?>
		<div class="heygov-feature">
			<h3>HeyGov Widget</h3>

			<form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">

				<table class="form-table">
					<tbody>
						<tr>
							<th><label for="heygov_widget_apps">Active apps</label></th>
							<td>
								<fieldset>
									<legend class="screen-reader-text"><span>HeyGov apps to enable </span></legend>
									<p>
										<label><input name="heygov_widget[features][]" type="checkbox" value="payments" <?php checked(in_array('payments', $heygov_features)) ?>> HeyGov QuickPay</label><br>
										<label><input name="heygov_widget[features][]" type="checkbox" value="forms" <?php checked(in_array('forms', $heygov_features)) ?>> HeyLicense</label><br>
										<label><input name="heygov_widget[features][]" type="checkbox" value="venues" <?php checked(in_array('venues', $heygov_features)) ?>> HeyReserve</label><br>
										<label><input name="heygov_widget[features][]" type="checkbox" value="issues" <?php checked(in_array('issues', $heygov_features)) ?>> HeyGov 311</label>
									</p>
									<p class="description">
										Which HeyGov apps should be displayed in the widget?
									</p>
								</fieldset>
							</td>
						</tr>

						<tr>
							<th><label for="heygov_btn_position">Button position</label></th>
							<td>
								<select class="regular-text" name="heygov_widget[position]" id="heygov_btn_position">
									<option value="none" <?php selected($heygov_btn_position, 'none'); ?>>None</option>
									<option value="bottom-right" <?php selected($heygov_btn_position, 'bottom-right'); ?>>Bottom right</option>
									<option value="middle-right" <?php selected($heygov_btn_position, 'middle-right'); ?>>Middle right</option>
									<option value="random" <?php selected($heygov_btn_position, 'random'); ?>>Random</option>
								</select>
								<p class="description">Select widget position, or turn off widget</p>
							</td>
						</tr>

						<tr>
							<th><label for="heygov_btn_text">Button text</label></th>
							<td>
								<input type="text" name="heygov_widget[text]" class="regular-text" id="heygov_btn_text" value="<?php echo esc_attr($heygov_btn_text); ?>" />
								<p class="description">Example: `Report a City Issue` or `Report a Town Issue`</p>
							</td>
						</tr>

						<tr>
							<th><label for="heygov_location_required">HeyGov 311 Location</label></th>
							<td>
								<p><label><input type="checkbox" name="heygov_widget[location]" id="heygov_location_required" <?php checked($heygov_location_required) ?> /> Set HeyGov 311 reported thread location to be required </label></p>
							</td>
						</tr>

						<tr>
							<th class="heygov-py-0"></th>
							<td class="heygov-py-0">
								<p class="heygov-py-0 submit"><input type="submit" name="heygov_submit" id="heygov_submit" class="button button-primary" value="Update widget"></p>
							</td>
						</tr>
					</tbody>
				</table>

			</form>
		</div>

		<div class="heygov-feature">
			<h3>HeyGov Apps banner</h3>

			<form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
				<p><label><input type="checkbox" name="heygov_banner[heygov_banner]" id="heygov_banner_toggle" <?php checked($heygov_banner) ?> /> Display a banner with HeyGov apps download links on the footer of your website</label></p>

				<div class="heygov-apps-banner-options <?php if (!$heygov_banner) echo esc_attr('hidden') ?>">
					<table class="form-table">
						<tbody>
							<tr>
								<th><label for="heygov_banner_bg">Banner background color</label></th>
								<td>
									<input type="color" name="heygov_banner[bg_color]" id="heygov_banner_bg" value="<?php echo esc_attr($heygov_banner_bg_color) ?>" />
								</td>
							</tr>

							<tr>
								<th><label for="heygov_banner_image_big">Banner desktop image</label></th>
								<td>
									<input type="file" id="heygov_banner_image_big" accept="image/*" />
									<input type="hidden" name="heygov_banner[image_big]" id="heygov_banner_image_big_inp" value="<?php echo esc_url($heygov_banner_img_big) ?>" />
								</td>
							</tr>

							<tr>
								<th><label for="heygov_banner_image_small">Banner mobile image</label></th>
								<td>
									<input type="file" name="heygov_banner[image_small]" id="heygov_banner_image_small" accept="image/*" />
									<input type="hidden" name="heygov_banner[image_small]" id="heygov_banner_image_small_inp" value="<?php echo esc_url($heygov_banner_img_small) ?>" />
								</td>
							</tr>
						</tbody>
					</table>

					<h5>Desktop preview</h5>
					<div class="heygov-banner-big-frame">
						<?php require HEYGOV_DIR . 'includes/view/apps-banner.php' ?>
					</div>

					<h5>Mobile preview</h5>
					<div class="heygov-banner-phone-frame">
						<?php require HEYGOV_DIR . 'includes/view/apps-banner.php' ?>
					</div>
				</div>

				<p class="heygov-py-0 submit">
					<input type="submit" name="heygov_submit" id="heygov_submit" class="button button-primary" value="Update banner">
				</p>
			</form>
		</div>

		<!-- Adding forms from heygov to muni website -->
		<div class="heygov-feature">
			<h3 class="heygov-h3">Embed HeyGov apps</h3>
			<p>Use these shortcodes to embed individual apps on the pages you want.</p>

			<table class="form-table">
				<tbody>

					<tr>
						<th scope="row"><label for="heygov_widget_shortcode">Widget</label></th>
						<td>
							<input type="text" class="large-text" id="heygov_widget_shortcode" value="[heygov-widget]" readonly />
							<p class="description">Embed the HeyGov widget directly on any page, ex: Contact page</p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="heygov_widget_forms">Permits and Licenses</label></th>
						<td>
							<input type="text" class="large-text" id="heygov_widget_forms" value='[heygov-forms maxcolumns="4" department="Parks and Rec"]' readonly />
							<p class="description">Display a section with permits and licenses. Use <code>department</code> attribute to filter forms by department ID or name.</p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="heygov_widget_venue">Venue calendar</label></th>
						<td>
							<input type="text" class="large-text" id="heygov_widget_venue" value='[heygov-venue venue="venue-unique-id"]' readonly />
							<p class="description">Display availability calendar for a specific venue. <code>venue</code> attribute is required.</p>
						</td>
					</tr>

				</tbody>
			</table>

		</div>

		<script type="text/javascript">
			const $banners = document.querySelectorAll('.heygov-apps-banner')

			jQuery('#heygov_banner_toggle').change(() => {
				jQuery('.heygov-apps-banner-options').toggleClass('hidden')
			})

			// change banner bg color
			const $bannerBgColor = document.getElementById('heygov_banner_bg')
			$bannerBgColor.addEventListener('change', event => {
				[...$banners].forEach(banner => {
					banner.style.backgroundColor = $bannerBgColor.value
				})
			})

			jQuery('#heygov_banner_image_small, #heygov_banner_image_big').change(event => {
				const lastImage = event.target.id.split('_').pop()

				heyGovUploadFile(event.target.files[0]).then(re => {
					jQuery(`#${event.target.id}_inp`).val(re.source_url)
					jQuery(`.heygov-apps-banner-image-${lastImage}`).attr('src', re.source_url)
				}, error => {
					alert(`Error uploading file ~ ${error}`)
				})
			})
		</script>
	<?php endif ?>

</div>