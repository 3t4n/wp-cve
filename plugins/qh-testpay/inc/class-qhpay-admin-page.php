<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Create the admin page under wp-admin -> WooCommerce -> 
 *
 * @author   qhpay Team
 * @since    
 *
 */
class QHPay_Admin_Page
{

	/**
	 * @var string The message to display after saving settings
	 */
	var $message = '';
	/**
	 *  constructor.
	 */
	public function __construct()
	{

		$this->get_list_banks =  QHPayPayment::get_list_banks();
		$this->get_list_bin =  QHPayPayment::get_list_bin();
		$this->get_status =  QHPayPayment::connect_status_banks();
		$this->settings = QHPayPayment::get_settings();
		$this->oauth_settings = QHPayPayment::oauth_get_settings();
		if (isset($_REQUEST['oauth2_status'])) {
			$this->disconnectOAuth2();
		}
		if (isset($_REQUEST['qhtp_nonce']) && isset($_REQUEST['action']) && 'qhtp_save_settings' == $_REQUEST['action']) {
			$this->save_settings();
		}
		add_action('admin_menu', array($this, 'register_submenu_page'));
	}
	public function reset_oauth() {
		delete_option('qhtp_oauth');
		$this->oauth_settings = array();//QHPayPayment::oauth_get_settings();
	}

	/**
	 * Save settings for the plugin
	 */
	public function save_settings()
	{

		if (wp_verify_nonce($_REQUEST['qhtp_nonce'], 'qhtp_save_settings')) {
			$settings = qhp_recursive_sanitize_text_field(isset($_POST['settings'])? $_POST['settings']: []);
			if(isset($this->settings['bank_transfer_accounts'])) $settings['bank_transfer_accounts'] = $this->settings['bank_transfer_accounts'];

			if (strlen($this->settings['bank_transfer']['secure_token']) <= 0) {
				$settings['bank_transfer']['secure_token'] = qhp_generate_random_string(16);
			} else {
				$settings['bank_transfer']['secure_token'] = $this->settings['bank_transfer']['secure_token'];
			}

			#$temp = $_REQUEST['settings']['bank_transfer']['authorization_code_force_delete'];
			#unset($_REQUEST['settings']['bank_transfer']['authorization_code_force_delete']);
			// Xoá kí tự đặc biệt và xóa bớt nếu dài quá, xóa khoảng trắng
			if(!empty($settings['bank_transfer']['transaction_prefix'])) {
				$settings['bank_transfer']['transaction_prefix'] = qhp_clean_prefix($settings['bank_transfer']['transaction_prefix']);	//$prefix =
			}
			$settings['bank_transfer']['extra_text'] = remove_accents($settings['bank_transfer']['extra_text']);
			//check if prefix changed
			/*if(!empty($this->settings['bank_transfer']['transaction_prefix']) && $prefix!= $this->settings['bank_transfer']['transaction_prefix']) {
				$this->reset_oauth();	//reset
			}*/
			if(!empty($settings) && is_array($settings)) {
				//qhp_valid_options($settings);
				QHPayPayment::update_settings( $settings);
			}
			$this->message = '<div class="success notice"><p><strong>'.__('Success').'</p></strong></div>';
			/* xử lí webhook!
			$this->message = $this->oauth_process_webhook($_POST['settings']);
			// Message for use
			$this->message .=
				'<div class="updated notice"><p><strong>' .
				__('Settings saved', '') .
				'</p></strong></div>';*/
		} else {

			$this->message =
				'<div class="error notice"><p><strong>' .
				__('Can not save settings! Please refresh this page.', 'qh-testpay') .
				'</p></strong></div>';
		}
	}
	

	/**
	 * Register the sub-menu under "WooCommerce"
	 */
	public function register_submenu_page()
	{
		add_submenu_page(
			'woocommerce',
			__('Thanh toán Quét Mã QR', 'qh-testpay'),//QHTestpay Settings
			'Thanh toán Quét Mã QR',
			'manage_options',
			'qhtp',
			array($this, 'admin_page_html')
		);
	}

	/**
	 * Generate the HTML code of the settings page
	 */
	public function admin_page_html()
	{
		
		// check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}
		/*if(!empty($_REQUEST['pc-reset'])) {
			$this->reset_oauth();			
		}*/
		$settings = QHPayPayment::get_settings();
		
?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form name="qhtp-setting-form" method="post">
				<p><?php echo wp_kses_post($this->message); ?></p>
				<input type="hidden" id="action" name="action" value="qhtp_save_settings">
				<input type="hidden" id="qhtp_nonce" name="qhtp_nonce" value="<?php echo wp_create_nonce('qhtp_save_settings') ?>">
				<input name="settings[bank_transfer][enabled]" type="hidden" value="yes">
				<input name="settings[bank_transfer][viet_qr]" type="hidden" value="yes">
				<input name="settings[bank_transfer][case_insensitive]" type="hidden" value="no">
				<p><?php echo __('Set up a link', 'qh-testpay'); ?></p>
				<table class="form-table">
					<tbody>
						<!--
						<tr>
							<th scope="row"><?php echo __('Enable/Disable', 'qh-testpay'); ?></th>
							<td>
								<input name="settings[bank_transfer][enabled]" type="hidden" value="yes">
							<input name="settings[bank_transfer][enabled]" type="checkbox" id="bank_transfer" value="yes" <?php if('yes' == $settings['bank_transfer']['enabled']) echo 'checked="checked"' ?>>
							<label for="bank_transfer" style="font-size: 13px; font-style: oblique;"><?php echo __('Turn on bank transfer', 'qh-testpay'); ?></label>
								<br />
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo __('VietQR', 'qh-testpay'); ?></th>
							<td>
								<input name="settings[bank_transfer][viet_qr]" type="hidden" value="yes">
								<input name="settings[bank_transfer][viet_qr]" type="checkbox" id="bank_transfer" value="yes" <?php if (!empty($settings['bank_transfer']['viet_qr']) && 'yes' == $settings['bank_transfer']['viet_qr'])echo 'checked="checked"' ?>>
								<label for="bank_transfer" style="font-size: 13px; font-style: oblique;"><?php echo __('Enable QR code display mode VietQR', 'qh-testpay'); ?></label>
								<br />
							</td>
						</tr>
					-->
						<tr style="<?php #if ($this->oauth_settings['login_type'] == 1) echo "display: none;" ?>">
							<th scope="row"><?php echo __('Download mobile app to connect your website', 'qh-testpay'); ?></th>

							<td id="connectqr">
								<?php #if(1||empty($this->oauth_settings['account_type'])){?>
								
								<div class="display:table">
								<span style="display: table-cell;padding-right: 10px;padding-bottom: 10px"><a href="https://play.google.com/store/apps/details?id=com.hoangweb.checkpay" target="_blank" class="qhtp-hidden" id="qhtpapp"><img src="<?php echo plugins_url('assets/playstore-btn.png',__DIR__)?>"/></a></span><span style="display: table-cell; vertical-align: middle;display: none"><?php #echo __('Download mobile app to connect your website','qh-testpay')?></span></div>
								<?php #} ?>
								<div id="qhtpqrcode" style="margin-bottom: 10px;"></div>

							</td>
						</tr>

						<tr style="display: none">
							<th scope="row"><?php echo __('Banks List', 'qh-testpay') ?></th>
							<td>
								
								<div id='banks_list_user' style="">
									<?php #if (empty($settings['bank_transfer_accounts'])) __('No banks found', 'qh-testpay'); 
									/*
									$banks_accepted = isset($settings['bank_transfer_accounts'])? $settings['bank_transfer_accounts']:[];
									$banks_added = get_option('woocommerce_bacs_accounts', array());
									
									?>
									<select multiple="" name="settings[bank_transfer_accounts][]" id="bank_transfer_accounts">
										<?php foreach($banks_added as $bank) {
											$id = $bank['bank_name'].'-'.$bank['account_number'];
											printf('<option %s value="%s">%s</option>', 
												in_array($id, $banks_accepted)? 'selected':'',
												$id,
												$bank['bank_name'].' - '.$bank['account_number'] );
										}?>
									</select>
									<?php */?>
								</div>
							</td>
							<td></td>
						</tr>

						
						<tr>
							<th scope="row"><?php echo __('Transaction prefix', 'qh-testpay') ?></th>
							<td>
								<input name="settings[bank_transfer][transaction_prefix]" type="text" value="<?php echo esc_attr($settings['bank_transfer']['transaction_prefix']); ?>" id="prefix">
								<label for="bank_transfer" style="font-size: 13px; font-style: oblique;">
									<ul>
										<li><?php echo __('This prefix goes with the order code.','qh-testpay')?></li>
										<li><?php echo __('Maximum 10 characters, no spaces and no special characters and no number.', 'qh-testpay') ?></li>
									</ul>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo  __('Extra Transaction Prefix (optional)', 'qh-testpay') ?></th>

							<td>
								<input type="text" name="settings[bank_transfer][extra_text]" id="transaction_extra_text" value="<?php echo !empty($settings['bank_transfer']['extra_text'])? esc_html($settings['bank_transfer']['extra_text']): ''; ?>"/><!-- <textarea style="width: 100%;min-height: 200px;"  -->
								<p><i><?php echo __("Ex: 'chuyen khoan ABC123' ('chuyen khoan' is the extra content). Maximum 20 characters, no spaces and no special characters and no number.",'qh-testpay');?></i></p>
							</td>
						</tr>
						<!--<tr>
							<th scope="row"><?php echo __('Turn on Case Sensitivity', 'qh-testpay') ?></th>
							<td>
								<input name="settings[bank_transfer][case_insensitive]" type="hidden" value="no">
								<input name="settings[bank_transfer][case_insensitive]" type="checkbox" id="bank_transfer" value="yes" <?php if ('yes' == $settings['bank_transfer']['case_insensitive']) echo 'checked="checked"';	?>>
								<label for="bank_transfer" style="font-size: 13px; font-style: oblique;"><?php echo __('Turn on Case Sensitivity', 'qh-testpay') ?></label>
								<br />
							</td>
						</tr>-->

						<tr>
							<th scope="row"><?php echo __('Acceptance difference', 'qh-testpay') ?></th>
							<td>
								<input name="settings[bank_transfer][acceptable_difference]" type="number" value="<?php echo  esc_attr($settings['bank_transfer']['acceptable_difference']); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo __('Status after full payment or balance', 'qh-testpay') ?></th>

							<td>
								<select name="settings[order_status][order_status_after_paid]" id="order_status_after_paid">
									<?php
									foreach ($this->get_order_statuses_after_paid() as $key => $value) {
										if ($key == $settings['order_status']['order_status_after_paid'])
											echo '<option value="' . esc_attr($key) . '" selected>' . esc_html($value) . '</option>';
										else echo '<option value="' . esc_attr($key) . '" >' . esc_html($value) . '</option>';
									}
									?>
								</select>
							</td>
						<tr>
						<tr>
							<th scope="row"><?php echo  __('Status if payment is missing', 'qh-testpay') ?></th>

							<td>
								<select name="settings[order_status][order_status_after_underpaid]" id="order_status_after_underpaid">
									<?php
									foreach ($this->get_order_statuses_after_underpaid() as $key => $value) {
										if ($key == $settings['order_status']['order_status_after_underpaid'])
											echo '<option value="' . esc_attr($key) . '" selected>' . esc_html($value) . '</option>';
										else echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<!--
						<tr>
							<th scope="row"><?php echo __('Webhook', 'qh-testpay') ?></th>
							<td>
								<input type="text" name="settings[webhook]" id="webhook" value="<?php echo isset($settings['webhook'])? $settings['webhook']:'' ?>"/>
							</td>
						</tr> -->
						<tr>
							<th scope="row"><?php echo __('Telegram Bot Token', 'qh-testpay') ?></th>
							<td>
								<input type="text" name="settings[telegram_token]" id="telegram_token" value="<?php echo isset($settings['telegram_token'])? $settings['telegram_token']:'' ?>"/>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo __('Telegram Group ID', 'qh-testpay') ?></th>
							<td>
								<input type="text" name="settings[telegram_chatid]" id="telegram_chatid" value="<?php echo isset($settings['telegram_chatid'])? $settings['telegram_chatid']:'' ?>"/>
							</td>
						</tr>
					
						<tr>
							<th scope="row"><?php echo __('Auto check user paid', 'qh-testpay') ?></th>
							<td>
								<input type="checkbox" name="settings[auto_check_status]" id="order_auto_check_status" <?php echo isset($settings['auto_check_status']) && (int)$settings['auto_check_status']? 'checked':'' ?>/>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save Changes','qh-testpay')?>">
				</p>

			</form>
			<div id="qhtp-admin-footer" style="border: 1px dotted; padding: 5px;display: none">
				<?php
				
				?>
			</div>
		</div>
		<script type="text/javascript">
			
			function generateQrCode() {
				<?php 
				if(!QHTP_TEST && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off')) {
					?>
					$('#connectqr').html("<p class='qhtp-error-tip'><?php echo __("Can't generate QR code because website doesn't have ssl",'qh-testpay')?></p>");
					<?php
				}
				else if(!empty($settings['bank_transfer']['secure_token']) && !empty($settings['bank_transfer']['transaction_prefix'])) {//'url_auth'=>admin_url('admin-ajax.php').'?action=auth_app_qhtp'?>
				var code = "<?php echo base64_encode(json_encode(['pf'=>$settings['bank_transfer']['transaction_prefix'],'tk'=>$settings['bank_transfer']['secure_token'], 'url'=>admin_url('admin-ajax.php').'?action=paid_order_qhtp',]))?>";
				var options = {
					text: code,
					width: 256,
				    height: 256,
				    colorDark : "#000000",
					colorLight : "#ffffff",
					logo: "<?php echo plugins_url('../assets/logo.png',__FILE__)?>",
				};
				new QRCode(jQuery('#qhtpqrcode')[0], options);
				$('#qhtpapp').removeClass('qhtp-hidden');
				<?php }else {?>
					$('#connectqr').html("<p class='qhtp-error-tip'><?php echo __('Please save settings, then qrcode image will appear','qh-testpay')?></p>");

				<?php }?>
			}

			jQuery(document).ready(function(_$){
				if(typeof $=='undefined') $=_$;
				generateQrCode();
				
			});

		</script>
		<!-- #wrap -->
        <?php
        do_action('qhtp_admin_page_footer');
	}
	
	public function get_order_statuses_after_paid()
	{
		$wooDefaultStatuses = array(
			"wc-pending",
			"wc-processing",
			"wc-on-hold",
			// "wc-completed",
			"wc-cancelled",
			"wc-refunded",
			"wc-failed",
			// "wc-paid",
			"wc-underpaid"
		);
		$statuses =  wc_get_order_statuses();
		$statuses['wc-default'] = __('Default', 'qh-testpay');
		for ($i = 0; $i < count($wooDefaultStatuses); $i++) {
			$statusName = $wooDefaultStatuses[$i];
			if (isset($statuses[$statusName])) {
				unset($statuses[$statusName]);
			}
		}
		return $statuses;
	}
	
	public function get_order_statuses_after_underpaid()
	{
		$wooDefaultStatuses = array(
			"wc-pending",
			// "wc-processing",
			"wc-on-hold",
			"wc-completed",
			"wc-cancelled",
			"wc-refunded",
			"wc-failed",
			"wc-paid",
			// "wc-underpaid"
		);
		$statuses =  wc_get_order_statuses();
		$statuses['wc-default'] =  __('Default', 'qh-testpay');
		for ($i = 0; $i < count($wooDefaultStatuses); $i++) {
			$statusName = $wooDefaultStatuses[$i];
			if (isset($statuses[$statusName])) {
				unset($statuses[$statusName]);
			}
		}
		return $statuses;
	}

	
}
