<?php
if (!defined('ABSPATH')) exit;

$default_tab = null;
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

$options = wdgk_get_wc_donation_setting();

if (isset($_POST['wdgk_add_form'])) {
	$product_name		= "";
	$cart_product		= "";
	$checkout_product	= "";
	$checkout_note		= "";
	$btncolor			= "";
	$textcolor			= "";
	$btntext			= "";
	$btntext			= "";

	$form_title			= "";
	$amount_placeholder	= "";
	$note_placeholder	= "";

	if (isset($_POST['wdgk_product']))  	$product_name		=  sanitize_text_field($_POST['wdgk_product']);
	if (isset($_POST['wdgk_cart'])) 		$cart_product		=  sanitize_text_field($_POST['wdgk_cart']);
	if (isset($_POST['wdgk_checkout']))  $checkout_product	=  sanitize_text_field($_POST['wdgk_checkout']);
	if (isset($_POST['wdgk_note'])) 		$checkout_note		=  sanitize_text_field($_POST['wdgk_note']);
	if (isset($_POST['wdgk_btncolor'])) 	$btncolor			=  sanitize_text_field($_POST['wdgk_btncolor']);
	if (isset($_POST['wdgk_textcolor'])) $textcolor			=  sanitize_text_field($_POST['wdgk_textcolor']);
	if (isset($_POST['wdgk_btntext'])) 	$btntext			=  sanitize_text_field($_POST['wdgk_btntext']);

	if (isset($_POST['wdgk_title'])) 	$form_title			=  sanitize_text_field($_POST['wdgk_title']);
	if (isset($_POST['wdgk_amt_place'])) $amount_placeholder	=  sanitize_text_field($_POST['wdgk_amt_place']);
	if (isset($_POST['wdgk_note_place'])) $note_placeholder	=  sanitize_text_field($_POST['wdgk_note_place']);

	$options['Product']		= $product_name;
	$options['Cart']		= $cart_product;
	$options['Checkout']	= $checkout_product;
	$options['Note']		= $checkout_note;
	$options['Color']		= $btncolor;
	$options['Text']		= $btntext;
	$options['TextColor']	= $textcolor;
	$options['Formtitle']	= $form_title;
	$options['AmtPlaceholder']	= $amount_placeholder;
	$options['Noteplaceholder']	= $note_placeholder;

	$nonce	=  $_POST['wdgk_wpnonce'];

	if (wp_verify_nonce($nonce, 'wdgk_nonce')) {

		if (!empty($product_name)) {
			update_option('wdgk_donation_settings', $options);
			$successmsg = success_option_msg_wdgk('Settings Saved!');
		} else {
			$errormsg = failure_option_msg_wdgk('Please Select Donation Product from List.');
		}
	} else {
		$errormsg = failure_option_msg_wdgk('An error has occurred.');
	}
}


$product			=  "";
$cart				=  "";
$checkout			=  "";
$note				=  "";
$color				=  "";
$text				=  "";
$textcolor			=  "";
$form_title			=  "Donation";
$amount_placeholder	=  "Ex. 100";
$note_placeholder	=  "Note";

if (isset($options['Product'])) {
	$product = $options['Product'];
}
if (isset($options['Cart'])) {
	$cart = $options['Cart'];
}
if (isset($options['Checkout'])) {
	$checkout = $options['Checkout'];
}
if (isset($options['Note'])) {
	$note = $options['Note'];
}
if (isset($options['Color'])) {
	$color = $options['Color'];
}
if (isset($options['Text'])) {
	$text = $options['Text'];
}
if (isset($options['TextColor'])) {
	$textcolor = $options['TextColor'];
}
if (isset($options['Formtitle'])) {
	$form_title = $options['Formtitle'];
}
if (isset($options['AmtPlaceholder'])) {
	$amount_placeholder = $options['AmtPlaceholder'];
}
if (isset($options['Noteplaceholder'])) {
	$note_placeholder = $options['Noteplaceholder'];
}
?>


<div class="wdgk_wrap ">
	<div class="wdgk-header">
		<h1 class="wdgk-h1"> Woo Donation </h1>
	</div>
	<?php
	if (isset($successmsg)) {
		_e($successmsg, 'woo-donations');
	}

	if (isset($errormsg)) {
		_e($errormsg, 'woo-donations');
	}
	?>

	<nav class="nav-tab-wrapper">
		<a href="?page=wdgk-donation-page" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>"><?php _e('General Setting', 'woo-donations'); ?></a>
		<a href="?page=wdgk-donation-page&tab=donation-label" class="nav-tab <?php if ($tab === "donation-label") : ?>nav-tab-active<?php endif; ?>"><?php _e('Label', 'woo-donations'); ?></a>
		<a href="?page=wdgk-donation-page&tab=donation-pro-version" class="nav-tab <?php if ($tab === 'donation-pro-version') : ?>nav-tab-active<?php endif; ?>"><?php _e('Get Pro Version', 'woo-donations'); ?>
			<svg width="18" height="18" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="crown" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-crown fa-w-20 fa-2x">
				<path fill="#F5BC3E" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5.4 5.1.8 7.7.8 26.5 0 48-21.5 48-48s-21.5-48-48-48z" class=""></path>
			</svg>
		</a>
	</nav>

	<form method="post">

		<div class="wdgk_donation_setting wdgk_pro_details  <?php if ($tab != null) { _e('wdgk-hidden'); } ?>">

			<h2><?php _e('Donation Settings', 'woo-donations'); ?></h2>

			<div class='wdgk_inner'>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><?php _e('Select Donation Product', 'woo-donations'); ?></th>
							<td>
                                <div class="wdgk-select-box">
                                <?php 
                                $post_7 = get_post( $product );
                                $product_title = $post_7->post_title;                                
                                ?>

                                <select name="wdgk_product" class="wdgk_select_product">
                                    <?php 
                                    if(isset($product) && !empty($product)){
                                        ?>
                                        <option selected="selected" value="<?php echo $product; ?>"><?php echo $product_title . " ( #" . $product . " )"; ?></option>
                                        <?php
                                    }
                                    ?>
                 
                                </select>
                                </div>
							
								<span class="wdgk_note"><?php _e('Select woocommerce products for donation.', 'woo-donations'); ?></span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Add on Cart Page', 'woo-donations'); ?></th>
							<td>
								<label class="wdgk-switch wdgk-switch-wdgk_cart_status">
									<input type="checkbox" class="wdgk-cart" name="wdgk_cart" value="on" <?php if ($cart == 'on') { _e("checked");	} ?>>
									<span class="wdgk-slider wdgk-round"></span>
								</label>
								<span class="wdgk_note"><?php _e('Enable to display donation on cart page.', 'woo-donations'); ?></span>
								<span class="wdgk_note">
									<strong>Note: </strong><?php _e('when using woocommerce blocks in cart page use shortcode to display donation form.', 'woo-donations'); ?>
									For more details <a href="https://youtu.be/o_A25YbYFyU" target="_blank">watch the video</a>
								</span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Add on Checkout Page', 'woo-donations'); ?></th>
							<td>
								<label class="wdgk-switch wdgk-switch-wdgk_checkout_status">
									<input type="checkbox" class="wdgk-checkout" name="wdgk_checkout" value="on" <?php if ($checkout == 'on') { _e("checked");} ?>>
									<span class="wdgk-slider wdgk-round"></span>
								</label>
								<span class="wdgk_note"><?php _e('Enable to display donation on checkout page.', 'woo-donations'); ?></span>
								<span class="wdgk_note">
									<strong>Note: </strong><?php _e('when using woocommerce blocks in checkout page use shortcode to display donation form.', 'woo-donations'); ?>
									For more details <a href="https://youtu.be/o_A25YbYFyU" target="_blank">watch the video</a>
								</span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Add on Note'); ?></th>
							<td>
								<label class="wdgk-switch wdgk-switch-wdgk_note_status">
									<input type="checkbox" class="wdgk-note wdgk-checkout" name="wdgk_note" value="on" <?php if ($note == 'on') { _e("checked");} ?>>
									<span class="wdgk-slider wdgk-round"></span>
								</label>
								<span class="wdgk_note"><?php _e('Enable to display note on donation.', 'woo-donations'); ?></span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Button Color', 'woo-donations'); ?></th>
							<td>
								<input type="text" name="wdgk_btncolor" class="wdgk_colorpicker" value="<?php esc_attr_e($color); ?>">
								<span class="wdgk_note"><?php _e('Select donation button color.', 'woo-donations'); ?></span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Button Text', 'woo-donations'); ?></th>
							<td>
								<input type="text" name="wdgk_btntext" value="<?php esc_attr_e(wp_unslash($text)); ?>">
								<span class="wdgk_note"><?php _e('Add Donation button text.', 'woo-donations'); ?></span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Button Text Color', 'woo-donations'); ?></th>
							<td>
								<input type="text" name="wdgk_textcolor" class="wdgk_colorpicker" value="<?php esc_attr_e($textcolor); ?>">
								<span class="wdgk_note"><?php _e('Select donation button text color.', 'woo-donations'); ?></span>
							</td>
						</tr>

					</tbody>
				</table>

				<input type="hidden" name="wdgk_wpnonce" value="<?php $nonce = wp_create_nonce('wdgk_nonce'); ?>">
				<input class="button button-primary button-large wdgk_submit" type="submit" name="wdgk_add_form" id="wdgk_submit" value="<?php esc_attr_e('Save Changes','woo-donations'); ?>" />


			</div>
		</div>
		<div class="wdgk_donation_setting wdgk_pro_details  <?php if ($tab != "donation-label") { _e('wdgk-hidden'); } ?>">

			<h2><?php _e('Label Settings', 'woo-donations'); ?></h2>

			<div class='wdgk_inner'>

				<table class="form-table">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Donation Form Title', 'woo-donations'); ?></th>
							<td>
								<input type="text" class="wdgk_input" name="wdgk_title" value="<?php esc_attr_e(wp_unslash($form_title)); ?>">
								<span class="wdgk_note"><?php _e('Add Donation form title.', 'woo-donations'); ?></span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Amount Placeholder Text', 'woo-donations'); ?></th>
							<td>
								<input type="text" class="wdgk_input" name="wdgk_amt_place" value="<?php esc_attr_e(wp_unslash($amount_placeholder)); ?>">
								<span class="wdgk_note"><?php _e('Add Donation amount placeholder text.', 'woo-donations'); ?></span>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Note Placeholder Text', 'woo-donations'); ?></th>
							<td>

								<textarea name="wdgk_note_place" class="wdgk-note-placeholder wdgk_input" rows="5"><?php esc_attr_e(wp_unslash($note_placeholder)); ?></textarea>
								<span class="wdgk_note"><?php _e('Add Donation note placeholder text.', 'woo-donations'); ?></span>
							</td>
						</tr>


					</tbody>
				</table>
				<?php
				$nonce = wp_create_nonce('wdgk_nonce');
				?>
				<input type="hidden" name="wdgk_wpnonce" value="<?php _e($nonce, 'woo-donations'); ?>">
				<input class="button button-primary button-large wdgk_submit" type="submit" name="wdgk_add_form" id="wdgk_submit" value="Save Changes" />


			</div>
		</div>
		<div class="wdgk_pro_details <?php if ($tab != "donation-pro-version") { _e('wdgk-hidden'); } ?>">
			<h2><?php _e('Woocommerce Donation Pro Version', 'woo-donations'); ?></h2>
			<ul>
				<li><?php _e('Display predefined donation amount options.', 'woo-donations'); ?></li>
				<li><?php _e('Define minimum and maximum limits for donation payments..', 'woo-donations'); ?></li>
				<li><?php _e('Configurable screen position for donation form in cart page.', 'woo-donations'); ?></li>
				<li><?php _e('Configurable screen position for donation form in checkout page.', 'woo-donations'); ?>
				</li>
				<li><?php _e('Create Fundraising Campaigns', 'woo-donations'); ?></li>
				<li><?php _e('Add the donation widget on the website’s sidebar or footer.', 'woo-donations'); ?></li>
				<li><?php _e('Display donation request popup.', 'woo-donations'); ?></li>
				<li><?php _e('Show donation order listing.', 'woo-donations'); ?></li>
				<li><?php _e('Download CSV file in donation order table.', 'woo-donations'); ?></li>
				<li><?php _e('Auto create woo donation page.', 'woo-donations'); ?></li>
				<li><?php _e('Admin can set sticky donation button on the website’s.', 'woo-donations'); ?></li>
				<li><?php _e('Donation button shortcode for set in entire site.', 'woo-donations'); ?></li>
				<li><?php _e('Allow Email template for send mail to donor', 'woo-donations'); ?></li>
				<li><?php _e('Option to change any title, label, placeholder, button text etc.', 'woo-donations'); ?>
				</li>
				<li><?php _e('Timely <a href="https://geekcodelab.com/contact/" target="_blank">support</a> 24/7.', 'woo-donations'); ?>
				</li>
				<li><?php _e('Regular updates.', 'woo-donations'); ?></li>
				<li><?php _e('Well documented.', 'woo-donations'); ?></li>

			</ul>
			<a href="https://geekcodelab.com/wordpress-plugins/woo-donation-pro/" title="Upgrade to Premium" class="wdgk_premium_btn" target="_blank"><?php _e('Upgrade to Premium', 'woo-donations'); ?></a>
		</div>
	</form>
</div>

<script type='text/javascript'>
	(function($) {
		// Add Color Picker to all inputs that have 'color-field' class
		$(function() {
			$('.wdgk_colorpicker').wpColorPicker();
		});

	})(jQuery);
</script>