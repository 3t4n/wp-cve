<?php
/*
Plugin Name: Recurring PayPal Donations
Plugin URI: https://wp-ecommerce.net/wordpress-recurring-donation-plugin
Description: Plugin for accepting recurring PayPal donations via a simple shortcode
Author: wpecommerce
Version: 1.7
Author URI: https://wp-ecommerce.net/
License: GPLv2 or later
*/

//slug - dntplgn_

define('DNTPLGN_SITE_HOME_URL', home_url());

// Initialization of the plugin function
if ( ! function_exists ( 'dntplgn_plugin_init' ) ) {
	function dntplgn_plugin_init() {
		global $dntplgn_options;
		// Internationalization, first(!)
		load_plugin_textdomain( 'donateplugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		if ( ! is_admin() || ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'dntplgn_plugin' ) ) {
			dntplgn_register_settings();
		}
	}
}

// Adding admin plugin settings page function
if ( ! function_exists( 'add_dntplgn_admin_menu' ) ) {
	function add_dntplgn_admin_menu() {
		add_menu_page( __( 'Donate Plugin', 'donateplugin' ), __( 'Donate Plugin', 'donateplugin' ), 'manage_options', 'dntplgn_plugin', 'dntplgn_settings_page', 'dashicons-heart');
		//call register settings function
	}
}

// Initialization plugin settings function
if ( ! function_exists( 'dntplgn_register_settings' ) ) {
	function dntplgn_register_settings() {
		global $wpdb, $dntplgn_options;
		$dntplgn_option_defaults = array(
			'dntplgn_paypal_email'       => ''
		);
		// install the option defaults
		if ( is_multisite() ) {
			if ( ! get_site_option( 'dntplgn_options' ) ) {
				add_site_option( 'dntplgn_options', $dntplgn_option_defaults, '', 'yes' );
			}
		} else {
			if ( ! get_option( 'dntplgn_options' ) )
				add_option( 'dntplgn_options', $dntplgn_option_defaults, '', 'yes' );
		}
		// get options from the database
		if ( is_multisite() )
			$dntplgn_options = get_site_option( 'dntplgn_options' ); // get options from the database
		else
			$dntplgn_options = get_option( 'dntplgn_options' );// get options from the database
		// array merge incase this version has added new options
		$dntplgn_options = array_merge( $dntplgn_option_defaults, $dntplgn_options );
		update_option( 'dntplgn_options', $dntplgn_options );
	}
}
// Admin plugin settings page content function
if ( ! function_exists( 'dntplgn_settings_page' ) ) {
	function dntplgn_settings_page() {

		global $dntplgn_options;
		$message = '';
		if( isset( $_POST['dntplgn_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'dntplgn_nonce_name' ) ) {

                    //Save paypal email address
                    if ( isset( $_POST['dntplgn_paypal_account'] ) ) {
                            if ( is_email( $_POST['dntplgn_paypal_account'] ) ) {
                                    $dntplgn_options['dntplgn_paypal_email'] = $_POST['dntplgn_paypal_account'];
                            } else {
                                    $error_message = __( 'Email is incorrect', 'donateplugin' );
                            }
                    }

                    //Save currency code
                    $dntplgn_options['dntplgn_payment_currency'] = sanitize_text_field($_POST["dntplgn_payment_currency"]);
                    $dntplgn_options['dntplgn_currency_symbol'] = sanitize_text_field($_POST["dntplgn_currency_symbol"]);
                    $dntplgn_options['dntplgn_return_url'] = sanitize_text_field(trim($_POST["dntplgn_return_url"]));
                    $dntplgn_options['dntplgn_cancel_return'] = sanitize_text_field(trim($_POST["dntplgn_cancel_return"]));
                    $dntplgn_options['dntplgn_pm_label'] = sanitize_text_field($_POST["dntplgn_pm_label"]);

                    $message = __( 'Settings saved' , 'donateplugin' );
                    update_option( 'dntplgn_options', $dntplgn_options );
		}


                //$dntplgn_options = get_option('dntplgn_options');
                $defaultCurrency = isset($dntplgn_options['dntplgn_payment_currency']) ? $dntplgn_options['dntplgn_payment_currency'] : 'USD';
                $dntplgn_currency_symbol = isset($dntplgn_options['dntplgn_currency_symbol']) ? $dntplgn_options['dntplgn_currency_symbol'] : '$';
                $dntplgn_return_url = isset($dntplgn_options['dntplgn_return_url']) ? $dntplgn_options['dntplgn_return_url'] : '';
                $dntplgn_cancel_return = isset($dntplgn_options['dntplgn_cancel_return']) ? $dntplgn_options['dntplgn_cancel_return'] : '';
                $dntplgn_pm_label = isset($dntplgn_options['dntplgn_pm_label']) ? $dntplgn_options['dntplgn_pm_label'] : '(p/m)';

		?>
		<div class="wrap">
		    <h2><?php _e( 'Recurring Donation Plugin Settings', 'donateplugin' ); ?></h2>

		    <div id="poststuff"><div id="post-body">
			<?php if ( $message != '' && isset( $_POST['dntplgn_submit'] ) && is_email( $_POST['dntplgn_paypal_account'] ) ) { ?>
				<div class="updated fade">
					<p><strong><?php echo $message; ?></strong></p>
				</div>
			<?php } elseif ( '' != $error_message && ! is_email( $_POST['dntplgn_paypal_account'] ) ) { ?>
				<div class="error">
					<p><strong><?php echo $error_message; ?></strong></p>
				</div>
			<?php } ?>

                        <div style="background:#FFF6D5;border: 1px solid #D1B655;color: #3F2502;margin: 10px 0;padding: 5px 5px 5px 10px;text-shadow: 1px 1px #FFFFFF;">
                            <p>Read the usage instructions from the <a href="https://wp-ecommerce.net/wordpress-recurring-donation-plugin" target="_blank">Recurring PayPal Donation</a> plugin page.</p>
                        </div>

			<div class="postbox">
			<h3 class="hndle"><label for="title">Quick Usage Guide</label></h3>
			<div class="inside">
			    <div class="dntplgn_description_shortcode_block">
				    <p>
					<?php _e( 'You can use the [dntplgn] shortcode in a WordPress post, page or sidebar text widget to show the recurring donation form. It will show the recurring donation form with default amount values of 25,50 and 100.', 'donateplugin' ); ?>
				    </p>
				    <p>
					<?php _e( 'Alternatively, you can use the following shortcode with custom parameters to customize the donation form/widget.', 'donateplugin' ); ?> You can copy and paste it then customize the values: <br />
                                        <input type='text' class='' onfocus='this.select();' readonly='readonly' value='[dntplgn recurring_amt1="10.00" recurring_amt2="50.00" recurring_amt3="200.00" item_name="Donation for XX" paypal_email="paypalemail@example.com"]' size='170'>
				    </p>
				    <p>
					<?php _e( 'The following shortcode shows how you can customize the currency code and symbol using shortcode parameters.', 'donateplugin' ); ?> You can copy and paste it then customize the values: <br />
                                        <input type='text' class='' onfocus='this.select();' readonly='readonly' value='[dntplgn recurring_amt1="10.00" recurring_amt2="50.00" recurring_amt3="200.00" item_name="Donation for XX" paypal_email="paypalemail@example.com" currency_code="USD" currency_symbol="$"]' size='170'>
				    </p>
			    </div>
			</div>
			</div>

			<div class="postbox">
			<h3 class="hndle"><label for="title">Plugin Settings</label></h3>
			<div class="inside">
			<form id="dntplgn_settings_form" method='post' action=''>
				<table id='dnt_noscript' class="form-table">
					<tr>
						<th class='dnt_row dnt_account_row' scope="row">
							<?php _e( 'Your PayPal Account Email Address', 'donateplugin' ); ?>
						</th>
						<td class='dnt_account_row'>
							<input type='text' name='dntplgn_paypal_account' size='70' id='dntplgn_paypal_account' value="<?php if ( '' != $dntplgn_options['dntplgn_paypal_email'] ) echo $dntplgn_options['dntplgn_paypal_email']; ?>" />
                                                        <p class="description">The donation will go to this PayPal account.</p>
							<input type='hidden' id='dnt_tab_paypal' name='dnt_tab_paypal' value='1' />
						</td>
					</tr>

					<tr>
						<th class='dnt_row dnt_currency_row' scope="row">
                                                    <?php _e( 'Currency Code', 'donateplugin' ); ?>
						</th>
						<td class='dnt_account_row'>
                                                    <select id="dntplgn_payment_currency" name="dntplgn_payment_currency">
                                                        <option value="USD" <?php echo ($defaultCurrency == 'USD') ? 'selected="selected"' : ''; ?>>US Dollars (USD)</option>
                                                        <option value="EUR" <?php echo ($defaultCurrency == 'EUR') ? 'selected="selected"' : ''; ?>>Euros (EUR)</option>
                                                        <option value="GBP" <?php echo ($defaultCurrency == 'GBP') ? 'selected="selected"' : ''; ?>>Pounds Sterling (GBP)</option>
                                                        <option value="AUD" <?php echo ($defaultCurrency == 'AUD') ? 'selected="selected"' : ''; ?>>Australian Dollars (AUD)</option>
                                                        <option value="BRL" <?php echo ($defaultCurrency == 'BRL') ? 'selected="selected"' : ''; ?>>Brazilian Real (BRL)</option>
                                                        <option value="CAD" <?php echo ($defaultCurrency == 'CAD') ? 'selected="selected"' : ''; ?>>Canadian Dollars (CAD)</option>
                                                        <option value="CNY" <?php echo ($defaultCurrency == 'CNY') ? 'selected="selected"' : ''; ?>>Chinese Yuan (CNY)</option>
                                                        <option value="CZK" <?php echo ($defaultCurrency == 'CZK') ? 'selected="selected"' : ''; ?>>Czech Koruna (CZK)</option>
                                                        <option value="DKK" <?php echo ($defaultCurrency == 'DKK') ? 'selected="selected"' : ''; ?>>Danish Krone (DKK)</option>
                                                        <option value="HKD" <?php echo ($defaultCurrency == 'HKD') ? 'selected="selected"' : ''; ?>>Hong Kong Dollar (HKD)</option>
                                                        <option value="HUF" <?php echo ($defaultCurrency == 'HUF') ? 'selected="selected"' : ''; ?>>Hungarian Forint (HUF)</option>
                                                        <option value="INR" <?php echo ($defaultCurrency == 'INR') ? 'selected="selected"' : ''; ?>>Indian Rupee (INR)</option>
                                                        <option value="IDR" <?php echo ($defaultCurrency == 'IDR') ? 'selected="selected"' : ''; ?>>Indonesia Rupiah (IDR)</option>
                                                        <option value="ILS" <?php echo ($defaultCurrency == 'ILS') ? 'selected="selected"' : ''; ?>>Israeli Shekel (ILS)</option>
                                                        <option value="JPY" <?php echo ($defaultCurrency == 'JPY') ? 'selected="selected"' : ''; ?>>Japanese Yen (JPY)</option>
                                                        <option value="MYR" <?php echo ($defaultCurrency == 'MYR') ? 'selected="selected"' : ''; ?>>Malaysian Ringgits (MYR)</option>
                                                        <option value="MXN" <?php echo ($defaultCurrency == 'MXN') ? 'selected="selected"' : ''; ?>>Mexican Peso (MXN)</option>
                                                        <option value="NZD" <?php echo ($defaultCurrency == 'NZD') ? 'selected="selected"' : ''; ?>>New Zealand Dollar (NZD)</option>
                                                        <option value="NOK" <?php echo ($defaultCurrency == 'NOK') ? 'selected="selected"' : ''; ?>>Norwegian Krone (NOK)</option>
                                                        <option value="PHP" <?php echo ($defaultCurrency == 'PHP') ? 'selected="selected"' : ''; ?>>Philippine Pesos (PHP)</option>
                                                        <option value="PLN" <?php echo ($defaultCurrency == 'PLN') ? 'selected="selected"' : ''; ?>>Polish Zloty (PLN)</option>
                                                        <option value="SGD" <?php echo ($defaultCurrency == 'SGD') ? 'selected="selected"' : ''; ?>>Singapore Dollar (SGD)</option>
                                                        <option value="ZAR" <?php echo ($defaultCurrency == 'ZAR') ? 'selected="selected"' : ''; ?>>South African Rand (ZAR)</option>
                                                        <option value="KRW" <?php echo ($defaultCurrency == 'KRW') ? 'selected="selected"' : ''; ?>>South Korean Won (KRW)</option>
                                                        <option value="SEK" <?php echo ($defaultCurrency == 'SEK') ? 'selected="selected"' : ''; ?>>Swedish Krona (SEK)</option>
                                                        <option value="CHF" <?php echo ($defaultCurrency == 'CHF') ? 'selected="selected"' : ''; ?>>Swiss Franc (CHF)</option>
                                                        <option value="TWD" <?php echo ($defaultCurrency == 'TWD') ? 'selected="selected"' : ''; ?>>Taiwan New Dollars (TWD)</option>
                                                        <option value="THB" <?php echo ($defaultCurrency == 'THB') ? 'selected="selected"' : ''; ?>>Thai Baht (THB)</option>
                                                        <option value="TRY" <?php echo ($defaultCurrency == 'TRY') ? 'selected="selected"' : ''; ?>>Turkish Lira (TRY)</option>
                                                        <option value="VND" <?php echo ($defaultCurrency == 'VND') ? 'selected="selected"' : ''; ?>>Vietnamese Dong (VND)</option>
                                                        <option value="RUB" <?php echo ($defaultCurrency == 'RUB') ? 'selected="selected"' : ''; ?>>Russian Ruble (RUB)</option>
                                                    </select>
                                                    <p class="description">The donation will be paid in this currency.</p>
						</td>
					</tr>

					<tr>
						<th class='dnt_row dnt_currency_symbol_label_row' scope="row">
							<?php _e( 'Currency Symbol', 'donateplugin' ); ?>
						</th>
						<td class='dnt_account_row'>
							<input type='text' name='dntplgn_currency_symbol' size='10' id='dntplgn_currency_symbol' value="<?php echo $dntplgn_currency_symbol; ?>" />
                                                        <p class="description">This symbol is shown next to the recurring amount values. By default it will use the $ symbol if you don't specify a currency symbol.</p>
						</td>
					</tr>

					<tr>
						<th class='dnt_row dnt_return_url_row' scope="row">
							<?php _e( 'Return URL', 'donateplugin' ); ?>
						</th>
						<td class='dnt_account_row'>
							<input type='text' name='dntplgn_return_url' size='70' id='dntplgn_return_url' value="<?php echo $dntplgn_return_url; ?>" />
                                                        <p class="description">PayPal will send the user to this page after the payment.</p>
						</td>
					</tr>

					<tr>
						<th class='dnt_row dnt_cancel_return_row' scope="row">
							<?php _e( 'Cancel URL', 'donateplugin' ); ?>
						</th>
						<td class='dnt_account_row'>
							<input type='text' name='dntplgn_cancel_return' size='70' id='dntplgn_cancel_return' value="<?php echo $dntplgn_cancel_return; ?>" />
                                                        <p class="description">PayPal will send the user to this page if the user clicks on the cancel link on the PayPal checkout page.</p>
						</td>
					</tr>

					<tr>
						<th class='dnt_row dnt_per_month_label_row' scope="row">
							<?php _e( 'Label for Per Month Options', 'donateplugin' ); ?>
						</th>
						<td class='dnt_account_row'>
							<input type='text' name='dntplgn_pm_label' size='30' id='dntplgn_pm_label' value="<?php echo $dntplgn_pm_label; ?>" />
                                                        <p class="description">This label is used next to the recurring amount select options. Example: you can use a vlaue of p/m (short for per month). Leave this field empty to hide this label.</p>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type='submit' name='dntplgn_submit' value='<?php _e( "Save changes", "donateplugin" ); ?>' class='button-primary' />
					<?php wp_nonce_field( plugin_basename( __FILE__ ), 'dntplgn_nonce_name' ); ?>
				</p>
			</form>
			</div>
			</div>

		    </div></div><!-- End of poststuff and postbody -->

                <div style="background: none repeat scroll 0 0 #FFF6D5;border: 1px solid #D1B655;color: #3F2502;margin: 10px 0;padding: 5px 5px 5px 10px;text-shadow: 1px 1px #FFFFFF;">
                <p>If you need a robust method of accepting donations in WordPress, feel free to check out the <a href="https://www.tipsandtricks-hq.com/wordpress-estore-plugin-complete-solution-to-sell-digital-products-from-your-wordpress-blog-securely-1059?ap_id=wpecommerce" target="_blank">WP eStore Plugin</a></p>
                </div>

		</div><!-- end of wrap -->
	<?php
	}
}

// Enqueue plugins scripts and styles function
if ( ! function_exists( 'dntplgn_enqueue_scripts' ) ) {
	function dntplgn_enqueue_scripts() {
		wp_enqueue_script( 'dntplgn_script', plugins_url( 'js/script.js' , __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs' ) );
		wp_enqueue_style( 'dntplgn_style', plugins_url( 'css/style.css' , __FILE__ ) );
		wp_enqueue_style( 'jquery_ui_style', plugins_url( 'css/jquery-ui-styles.css' , __FILE__ ) );
	}
}

// Plugin form content function
if ( ! function_exists ( 'dntplgn_show_form' ) ) {
	function dntplgn_show_form( $atts ) {
		global $dntplgn_options;

		$dntplgn_atts = shortcode_atts( array(
			'recurring_amt1' => '25',
			'recurring_amt2' => '50',
			'recurring_amt3' => '100',
                        'item_name' => '',
                        'paypal_email' => '',
                        'currency_code' => '',
                        'currency_symbol' => '',
                        'return_url' => '',
                        'cancel_return' => '',
                        'per_month_label' => '',
		), $atts );

		if ( isset( $_POST['dntplgn_monthly_submit_button'] ) ) {
			if ( "other" != esc_html( $_POST["monthly_donate_buttons"] ) ) {
				$monthly_amount = esc_html( $_POST["monthly_donate_buttons"] );
			} elseif ( "other" == esc_html( $_POST["monthly_donate_buttons"] )  && isset( $_POST['dntplgn_monthly_other_sum'] ) ) {
				$monthly_amount = esc_html( $_POST['dntplgn_monthly_other_sum'] );
			}
		} elseif ( isset( $_POST['dntplgn_once_submit_button'] ) ) {
			if ( isset( $_POST['dntplgn_once_amount'] ) ) {
				$once_amount = esc_html( $_POST['dntplgn_once_amount'] );
			}
		}

                //Merchant paypal email
                $merchant_account_email = $dntplgn_options['dntplgn_paypal_email'];
                if(!empty($dntplgn_atts['paypal_email'])){
                    //Shortcode has a paypal email specfied for it.
                    $merchant_account_email = $dntplgn_atts['paypal_email'];//Override the paypal account to the one specified in the shortcode.
                }

                //Currency code
                $currency_code = isset($dntplgn_options['dntplgn_payment_currency']) ? $dntplgn_options['dntplgn_payment_currency'] : 'USD';
                if(!empty($dntplgn_atts['currency_code'])){
                    //Shortcode has a currency code so use that one.
                    $currency_code = $dntplgn_atts['currency_code'];
                }

                //Currency Symbol
                $currency_symbol = isset($dntplgn_options['dntplgn_currency_symbol']) ? $dntplgn_options['dntplgn_currency_symbol'] : '$';
                if(!empty($dntplgn_atts['currency_symbol'])){
                    //Shortcode has a currency code so use that one.
                    $currency_symbol = $dntplgn_atts['currency_symbol'];
                }

                //Return URL
                $return_url = isset($dntplgn_options['dntplgn_return_url']) ? $dntplgn_options['dntplgn_return_url'] : DNTPLGN_SITE_HOME_URL;
                if(!empty($dntplgn_atts['return_url'])){
                    //Shortcode has a return_url value so use that one.
                    $return_url = $dntplgn_atts['return_url'];
                }

                //Cancel Return URL
                $dntplgn_cancel_return = isset($dntplgn_options['dntplgn_cancel_return']) ? $dntplgn_options['dntplgn_cancel_return'] : DNTPLGN_SITE_HOME_URL;
                if(!empty($dntplgn_atts['cancel_return'])){
                    //Shortcode has a cancel_return value so use that one.
                    $dntplgn_cancel_return = $dntplgn_atts['cancel_return'];
                }

                //Per month amount label
                $per_month_label = isset($dntplgn_options['dntplgn_pm_label']) ? $dntplgn_options['dntplgn_pm_label'] : '';
                if(!empty($dntplgn_atts['per_month_label'])){
                    //Shortcode has a return_url value so use that one.
                    $per_month_label = $dntplgn_atts['per_month_label'];
                }

		ob_start(); ?>
		<div id="tabs" class="dntplgn_form_wrapper">
			<ul>
				<li><a href="#tabs-1"><?php _e( 'donate monthly', 'donateplugin' ); ?></a></li>
				<li><a href="#tabs-2"><?php _e( 'donate once only', 'donateplugin' ); ?></a></li>
			</ul>
			<div id="tabs-1">
				<!--Monthly donate form-->
				<form class="dntplgn_donate_monthly"  action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="dntplgn_form">
                                        <div class="dntplgn_monthly_donation_select_label">
                                            <?php _e( 'Select a Donation Option', 'donateplugin' ); ?>
                                            <span class="dntplgn_payment_currency"><?php echo ' ('.$currency_code.')'; ?></span>
                                        </div>

					<input type="hidden" name="cmd" value="_xclick-subscriptions">
					<input type="hidden" name="business" value="<?php echo $merchant_account_email; ?>" />
					<input type="hidden" name="lc" value="US">
					<input type="hidden" name="item_name" value="<?php echo $dntplgn_atts['item_name']; ?>">
					<input type="hidden" name="no_note" value="1">
					<input type="hidden" name="src" value="1">
					<!-- Donate Amount -->
					<input id="first_button" type="radio" name="a3" checked="checked" value="<?php echo $dntplgn_atts['recurring_amt1']; ?>" />
					<label for="first_button"> <?php echo $currency_symbol; ?><?php echo $dntplgn_atts['recurring_amt1']; ?> <span class="dntplgn_pm_label"><?php echo $per_month_label; ?></span></label>
					<input id="second_button" type="radio" name="a3" value="<?php echo $dntplgn_atts['recurring_amt2']; ?>" />
					<label for="second_button"> <?php echo $currency_symbol; ?><?php echo $dntplgn_atts['recurring_amt2']; ?> <span class="dntplgn_pm_label"><?php echo $per_month_label; ?></span></label>
					<input id="third_button" type="radio" name="a3" value="<?php echo $dntplgn_atts['recurring_amt3']; ?>" />
					<label for="third_button"> <?php echo $currency_symbol; ?><?php echo $dntplgn_atts['recurring_amt3']; ?> <span class="dntplgn_pm_label"><?php echo $per_month_label; ?></span></label>
					<input id="fourth_button" type="radio" name="a3" value="other" />
					<label for="fourth_button"> <?php _e( 'Other', 'donateplugin' ); ?> <span class="dntplgn_pm_label"><?php echo $per_month_label; ?></span></label></br>
                                        <input class="dntplgn_monthly_other_sum" type="text" name="dntplgn_monthly_other_sum" placeholder="<?php _e( 'Enter Amount', 'donateplugin' ); ?>" />
					<!-- End Donate Amount -->
					<input type="hidden" name="p3" value="1">
					<input type="hidden" name="t3" value="M">
					<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
                                        <input type="hidden" name="return" value="<?php echo $return_url; ?>">
                                        <input type="hidden" name="cancel_return" value="<?php echo $dntplgn_cancel_return; ?>" />
					<input type="hidden" name="bn" value="TipsandTricks_SP">
					<input type="hidden" name="on0" value="Donate" />
					<input type="hidden" name="os0" value="Monthly" />
					<input class="dntplgn_submit_button" type="submit" name="submit"  value="<?php _e( 'Donate', 'donateplugin' ); ?>" alt="PayPal - The safer, easier way to pay online!" />
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
				<!--End monthly donate form-->
				<div class="clear"></div>
			</div>
			<div id="tabs-2">
				<!--Donate once only form-->
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                        <div class="dntplgn_once_enter_donation_label">
                                            <?php _e( 'Enter Donation Amount', 'donateplugin' ); ?>
                                            <span class="dntplgn_payment_currency"><?php echo ' ('.$currency_code.')'; ?></span>
                                        </div>
                                        <input id="dntplgn_once_amount" type="text" name="amount" value="" />
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="<?php echo $merchant_account_email; ?>">
					<input type="hidden" name="lc" value="US">
                                        <input type="hidden" name="item_name" value="<?php echo $dntplgn_atts['item_name']; ?>">
					<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
                                        <input type="hidden" name="return" value="<?php echo $return_url; ?>">
                                        <input type="hidden" name="cancel_return" value="<?php echo $dntplgn_cancel_return; ?>" />
					<input type="hidden" name="no_note" value="0">
					<input type="hidden" name="bn" value="TipsandTricks_SP">
					<input class="dntplgn_submit_button" type="submit" name="submit"  value="<?php _e( 'Donate', 'donateplugin' ); ?>" alt="PayPal - The safer, easier way to pay online!" />
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
				<!--End donate once only form-->
				<div class="clear"></div>
			</div>
		</div>
		<?php $content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}

register_activation_hook( __FILE__, 'dntplgn_register_settings' );

add_action( 'init', 'dntplgn_plugin_init' );
add_action( 'admin_init', 'dntplgn_plugin_init' );
add_action( 'admin_menu', 'add_dntplgn_admin_menu' );
add_action( 'admin_enqueue_scripts', 'dntplgn_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'dntplgn_enqueue_scripts' );
add_shortcode( 'dntplgn', 'dntplgn_show_form' );
add_filter( 'widget_text', 'do_shortcode' );
