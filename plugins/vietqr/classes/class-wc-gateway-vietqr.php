<?php

use Automattic\Jetpack\Sync\Functions;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * VietQR Transfer Payment Gateway.
 *
 * Provides a VietQR Payment Gateway. Based on code by Mike Pepper.
 *
 * @class       WC_Gateway_VietQR
 * @extends     WC_Payment_Gateway
 * @version     2.1.0
 * @package     WooCommerce\Classes\Payment
 */
class WC_Gateway_VietQR extends WC_Payment_Gateway
{

	/**
	 * Array of locales
	 *
	 * @var array
	 */
	public $locale;

	/**
	 * Constructor for the gateway.
	 */
	static private $URL_BANKS_LIST = "https://api.vietqr.io/v2/banks";
	static private $URL_QRCODE = "https://api.vietqr.io/v2/generate";
	public function __construct()
	{
		$this->id                 = 'vietqr';
		$this->icon               = apply_filters('woocommerce_vietqr_icon', '');
		$this->has_fields         = false;
		$this->icon               = apply_filters('woocommerce_icon_vietqr', plugins_url('../assets/img/vietqr.png', __FILE__));
		$this->method_title       = __('Direct bank transfer (Scan VietQR)', 'vietqr');
		$this->method_description = __('Take payments by scanning QR code with Vietnamese banking App. Supported by most major banks in Vietnam', 'vietqr');

		global $wp_session;

		//chỗ này cần cache lại, nếu ko sẽ bị gọi rất nhiều lần.
		$this->banks_list =  $this->get_option('vietqr_banks');
		$vietqr_banks_expired = $this->get_option("vietqr_banks_expired");
		
		//luu cache 1h.
		$CACHE_LIFE = 1 * 60 * 60;

		if (!$this->banks_list || !$vietqr_banks_expired || $vietqr_banks_expired < time() || $vietqr_banks_expired >  time() + $CACHE_LIFE) {
			//call api.
			$this->banks_list = $this->get_banks_list();
			$this->banks_list = json_decode($this->banks_list, true);
			//load banklist.
			$this->update_option("vietqr_banks", $this->banks_list);
			$this->update_option("vietqr_banks_expired", time() + $CACHE_LIFE);
		}

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title        = $this->get_option('title');
		$this->description  = $this->get_option('description');

		//xử lý cập nhật lại câu description payment 
		//TODO: xóa dòng dưới khi đã sang năm 2023
		if (strpos($this->description, "Hơn 14 ngân hàng Việt Nam") !== false){
			$this->description = str_replace( "Hơn 14 ngân hàng Việt Nam", "Hầu hết ngân hàng Việt Nam", $this->description);
			$this->update_option("description", $this->description);
		}


		$this->instructions = $this->get_option('instructions');
		$this->prefix = $this->get_option('prefix');
		$this->developer_id = $this->get_option('developer_id');
		// VietQR account fields shown on the thanks page and in emails.
		$this->account_details = get_option(
			'woocommerce_vietqr_accounts',
			array(
				array(
					'account_name'   => $this->get_option('account_name'),
					'account_number' => $this->get_option('account_number'),
					'bank_name'      => $this->get_option('bank_name'),
					'bank_id'      => $this->get_option('bank_id'),
				),
			)
		);

		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'save_account_details'));
		add_action('woocommerce_thankyou_vietqr', array($this, 'thankyou_page'));
		// Customer Emails.
		add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 3);
		add_action('woocommerce_api_vietqr/developer', array($this, 'vietqr_developer_payment_handler'));
		wp_enqueue_style('vietqr-custome-styles', plugins_url('../assets/css/custom.css', __FILE__));

	}
	public function vietqr_developer_payment_handler()
	{
		//serving to support customers and developers to install vietqr
		header('Content-type: application/json');
		preg_match('/(http(|s)):\/\/(.*?)\//si',  WC()->api_request_url(''), $output);
		$developer = array(
			'developer_id'   => $this->developer_id,
			'php_version' => phpversion(),
			'url' => $output[0],
			'prefix' => $this->get_option('prefix'),
			'account' => $this->account_details,
			'website_name' => get_bloginfo('name'),
			'wordpress_version' => get_bloginfo('version')
		);
		echo json_encode($developer);
		die();
	}
	/**
	 * Initialise Gateway Settings Form Fields. 
	 * 
	 */
	public function console_log($output, $with_script_tags = true)
	{
		$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
			');';
		if ($with_script_tags) {
			$js_code = '<script>' . $js_code . '</script>';
		}
		echo $js_code;
	}
	public function init_form_fields()
	{
		$default_prefix = "WCORDER";

		//Tự động sinh prefix đơn hàng cho website.
		//ví dụ website my.casso.vn thì prefix là CASSO.
		$server_domain = $_SERVER['SERVER_NAME'];
		$better_prefix = $this->guess_company_name_from_domain($server_domain);
		if (strlen($better_prefix) >= 4 && strlen($better_prefix) <= 8) {
			$default_prefix  = $better_prefix;
		}

		$this->form_fields = array(
			'enabled'         => array(
				'title'   => __('Enable/Disable', 'woocommerce'),
				'type'    => 'checkbox',
				'label'   => __('Enable bank transfer', 'woocommerce'),
				'default' => 'true',
			),
			'title'           => array(
				'title'       => __('Title', 'woocommerce'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default'     => __('Scan the bank transfer code', 'vietqr'),
				'desc_tip'    => true,
			),
			'description'     => array(
				'title'       => __('Description', 'woocommerce'),
				'type'        => 'textarea',
				'description' => __('Payment method description that the customer will see on your checkout.', 'woocommerce'),
				'default'     => __('Transfer payments using the banking App to scan the code. Supported by almost Vietnamese banking apps', 'vietqr'),
				'desc_tip'    => true,
			),
			'instructions'    => array(
				'title'       => __('Instructions', 'woocommerce'),
				'type'        => 'textarea',
				'description' => __('Instructions that will be added to the thank you page and emails.', 'woocommerce'),
				'default'     => '',
				'desc_tip'    => true,
			),
			'account_details' => array(
				'type' => 'account_details',
			),
			'prefix'           => array(
				'title'       => __('Prefix', 'vietqr'),
				'type'        => 'text',
				'description' => __('Prefix used to combine with order code to create money transfer content, Set rules: no spaces, no more than 15 characters and no special characters. Violations will be deleted', 'vietqr'),
				'default'     => $default_prefix,
				'desc_tip'    => true,
			),
		);
	}

	/**
	 * Generate account details html.
	 *
	 * @return string
	 */
	public function generate_account_details_html()
	{


		ob_start();

		// wp_enqueue_script('jQuery');
		wp_enqueue_script('vietqr-select2-script', plugins_url('../assets/js/select2.min.js', __FILE__));
		wp_enqueue_style('vietqr-select2-styles', plugins_url('../assets/css/select2.min.css', __FILE__));
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		$is_claim_show = false;
		if (isset($queries['claim'])) $is_claim_show = true;
?>
		<script>
			jQuery(document).ready(function() {
				jQuery("#vietqr_bank_selector2").select2({
					templateResult: formatOptions
				});
			});

			function formatOptions(state) {
				if (!state.id) {
					return state.text;
				}
				var $state = jQuery(
					'<span ><img style="vertical-align: middle;" src="' + state.title + '"  width="80px"/> ' + state.text + '</span>'
				);
				return $state;
			}
		</script>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php echo __('Bank name', 'vietqr') ?>:</th>
			<td class="forminp" id="vietqr_accounts">
				<select id='vietqr_bank_selector2' style='width: 100%' name="vietqr_bank_name[0]">
					<?php

					foreach ($this->banks_list['data'] as $bank) {
						$selected = "";
						if ($this->account_details[0]['bank_id'] == $bank['bin'])
							$selected  = "selected";
						$option_name  = "vietqr_bank_id_" . $bank['bin'];
						$option_value = $bank['bin'] . ":" . $bank['name'];
						$option_title = $bank['logo'];
						$option_text  = $bank['short_name'] . ' - ' . $bank['name'];

						echo '<option name="' . esc_html($option_name) . '" value="' . esc_html($option_value) . '" title="' . esc_html($option_title) . '" ' . $selected . '>' . esc_html($option_text) . '</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php echo __('Account name', 'vietqr') ?>:</th>
			<td class="forminp" id="vietqr_accounts">
				<?php
				echo '<input type="text" value="' . esc_html($this->account_details[0]['account_name']) . '" name="vietqr_account_name[0]" />';
				?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php echo __('Account number', 'vietqr') ?>:</th>
			<td class="forminp" id="vietqr_accounts">
				<?php
				echo '<input type="text" value="' . esc_html($this->account_details[0]['account_number']) . '" name="vietqr_account_number[0]" />';
				?>
			</td>
			</td>
		</tr>
		<tr valign="top" style="<?php if (!$is_claim_show) echo "display: none;"  ?>">
			<th scope="row" class="titledesc"><?php echo __('Developer ID', 'vietqr') ?>:</th>
			<td class="forminp" id="vietqr_accounts">
			
				<input type="text" value="<?php echo esc_html($this->developer_id) ?>" name="vietqr_developer_id[0]" style="max-width: 150px;"/>
				</br>
				<label for="bank_transfer" style="font-size: 11px; font-style: oblique;margin-top: 10px;"><?php echo __('Chúng tôi sẽ xét duyệt và gửi quà qua Momo bạn nếu đủ điều kiện', 'casso-wordpress-plugin') ?></label>
			</td>
		</tr>
<?php
		return ob_get_clean();
	}

	/**
	 * Save account details table.
	 */
	public function save_account_details()
	{
		$accounts = array();
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verification already handled in WC_Admin_Settings::save()
		if (
			isset($_POST['vietqr_account_name']) && isset($_POST['vietqr_account_number'])
		) {

			$account_names   = wc_clean(wp_unslash($_POST['vietqr_account_name']));
			$account_numbers = wc_clean(wp_unslash($_POST['vietqr_account_number']));
			$bank_names      = wc_clean(wp_unslash($_POST['vietqr_bank_name']));
			$bank_names   = explode(":", $bank_names[0]);
			$accounts[] = array(
				'account_name'   => $account_names[0],
				'account_number' => $account_numbers[0],
				'bank_name'      => $bank_names[1],
				'bank_id'      => $bank_names[0],
			);
		}
		//Update prefix nếu điền sai
		update_option('woocommerce_vietqr_accounts', $accounts);
		if (isset($_POST['vietqr_account_name'])) {
			$_POST['woocommerce_vietqr_prefix'] = $this->clean_prefix($_POST['woocommerce_vietqr_prefix']);
			$this->update_option('prefix', $_POST['woocommerce_vietqr_prefix']);
		}
		// Cập nhật developer_id
		if (isset($_POST['vietqr_developer_id'])) {
			$developer_id = wc_clean(wp_unslash($_POST['vietqr_developer_id']));
			$_POST['woocommerce_vietqr_developer_id'] = $developer_id[0];
			$this->update_option('developer_id', $_POST['woocommerce_vietqr_developer_id']);
			$this->developer_affiliate($_POST['woocommerce_vietqr_developer_id']);
		}
	}
	public function developer_affiliate($developer_id)
	{
		$url  = 'https://vietqr.devgioi.com/api/claim';
		preg_match('/(http(|s)):\/\/(.*?)\//si',  WC()->api_request_url(''), $output);
		$body = array(
			"developer_id" => $developer_id,
			"domain" => $output[0],
		);
		$args = array(
			'body'        => json_encode($body),
			'headers' => array(
				"content-type" => "application/json"
			),
			'timeout'     => 20,
		);
		$response = wp_remote_post($url, $args);
		if (is_wp_error($response)) {
			return null;
		}
		if ($response['response']['code'] == 200 || $response['response']['code'] == 201) {
			$body     = wp_remote_retrieve_body($response);
			return $body;
		}
		return null;
	}
	public function clean_prefix($string)
	{
		$string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
		if (strlen($string) > 15) {
			$string = substr($string, 0, 15);
		}
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
	/**
	 * Output for the order received page.
	 *
	 * @param int $order_id Order ID.
	 */
	public function thankyou_page($order_id)
	{
		if ($this->instructions) {
			echo wp_kses_post(wpautop(wptexturize(wp_kses_post($this->instructions))));
		}
		//	echo 'ok';
		$this->bank_details($order_id, false);
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool     $sent_to_admin Sent to admin.
	 * @param bool     $plain_text Email format: plain text or HTML.
	 */
	public function email_instructions($order, $sent_to_admin, $plain_text = false)
	{
		if (!$sent_to_admin && 'vietqr' === $order->get_payment_method() && $order->has_status('on-hold')) {
			if ($this->instructions) {
				echo wp_kses_post(wpautop(wptexturize($this->instructions)) . PHP_EOL);
			}
			$this->bank_details($order->get_id(), true);
		}
	}

	/**
	 * Get bank details and place into a list format.
	 *
	 * @param int $order_id Order ID.
	 */
	private function bank_details($order_id = '', $is_sent_email = false)
	{
		if (empty($this->account_details) && isset($this->account_details)) {
			return;
		}
		// Get order and store in $order.
		$order = wc_get_order($order_id);

		// Get the order country and country $locale.
		$country = '';
		$locale  = '';

		// Get sortcode label in the $locale array and use appropriate one.
		$sortcode = '';

		$vietqr_accounts = $this->account_details;

		$account_html = '';
		$has_details  = false;


		//debug.
		// $is_sent_email = true;

		foreach ($vietqr_accounts as $vietqr_account) {

			$vietqr_account = (object) $vietqr_account;

			if ($vietqr_account->account_name) {
				$account_html .= '<h3 class="wc-vietqr-bank-details-account-name">' . wp_kses_post(wp_unslash($vietqr_account->account_name)) . ':</h3>' . PHP_EOL;
			}

			$account_html .= '<ul class="wc-vietqr-bank-details order_details vietqr_details">' . PHP_EOL;

			// VietQR account fields shown on the thanks page and in emails.
			$account_fields = apply_filters(
				'woocommerce_vietqr_account_fields',
				array(
					'bank_name'      => array(
						'label' => __('Bank', 'woocommerce'),
						'value' => $vietqr_account->bank_name,
					),
					'bank_id'      => array(
						'label' => __('Bank Id', 'woocommerce'),
						'value' => $vietqr_account->bank_id,
					),
					'account_number' => array(
						'label' => __('Account number', 'woocommerce'),
						'value' => $vietqr_account->account_number,
					),
					'account_name' => array(
						'label' => __('Account name', 'woocommerce'),
						'value' => $vietqr_account->account_name,
					),
					'amount'            => array(
						'label' => __('Amount', 'vietqr'),
						'value' => $order->get_total(),
					),
					'memo'            => array(
						'label' => __('Memo', 'vietqr'),
						'value' =>  $this->prefix . $order_id,
					),

				),
				$order_id
			);

			$qrcode_url = "";
			$qrcode_page = "";
			// if (!$is_sent_email) {
			// 	$dataQR = $this->get_qrcode_vietqr($account_fields);
			// 	if (isset($dataQR)) {
			// 		$dataQR = json_decode($dataQR);
			// 		$qrcode_url = $dataQR->data->qrDataURL;
			// 	}
			// } else {
				$data = $this->get_qrcode_vietqr_img_url($account_fields);
				$qrcode_url  = $data['img_url'];
				$qrcode_page = $data['pay_url'];
			// }

			$has_details   = false;
			foreach ($account_fields as $field_key => $field) {
				if (!empty($field['value'])) {
					$account_html .= '<li class="' . esc_attr($field_key) . '">' . wp_kses_post($field['label']) . ': <strong>' . wp_kses_post(wptexturize($field['value'])) . '</strong></li>' . PHP_EOL;
					$has_details   = true;
				}
			}

			$account_html .= '</ul>';


			//hiển thị nút tải trên điện thoại và ko phải email
			$show_download  = wp_is_mobile();

			if ($has_details) {
				$showPayment =  '
			<section class="woocommerce-vietqr-qr-scan">

			<!-- QR TTILE-->';

				if ($qrcode_url) {
					$showPayment .=

						'
						<h2 class="wc-vietqr-bank-details-heading" style="text-align: center;">' . __('Bank transfer QR code', 'vietqr')  . '</h2>
						<!-- QR IMAGE HERE-->

			<div style="">
				<div id="qrcode" style="text-align: center;">
					<img src="' . esc_html($qrcode_url) . '"  alt="VietQR QR Image" width="400px" />
				</div>

				<!--buton download on email.-->
				<a style="max-width:200px; margin: 0 auto;background-color: limegreen; color:white; display:' . ($is_sent_email ? 'block' : 'none') . '" href="' . esc_html($qrcode_page) . '" target="_blank" >
					Tải QR Code             
				</a>
				
				<a id="downloadQR" download="vietqr_' . esc_html($account_fields['account_number']['value']) . '.jpg"  href="' . esc_html($qrcode_url) . '">
					<button id="btnDownloadQR">
					<div style="width: 100%;display: flex;align-items: center;justify-content: flex-start;">	
					
					<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3 3H11V1H1V11H3V3Z" fill="white"/>
					<path d="M11 11V5H5V11H11ZM7 7H9V9H7V7Z" fill="white"/>
					<path d="M20.5 3H29V11H31V1H20.5V3Z" fill="white"/>
					<path d="M27 11V5H21V11H27ZM23 7H25V9H23V7Z" fill="white"/>
					<path d="M11 29H3V21H1V31H11V29Z" fill="white"/>
					<path d="M11 21H5V27H11V21ZM9 25H7V23H9V25Z" fill="white"/>
					<path d="M29 29H20.5V31H31V21H29V29Z" fill="white"/>
					<path d="M17 19H25V23H27V17H17V19Z" fill="white"/>
					<path d="M27 27V25H15V17H5V19H13V27H27Z" fill="white"/>
					<path d="M13 5H15V11H13V5Z" fill="white"/>
					<path d="M5 15H19V5H17V13H5V15Z" fill="white"/>
					<path d="M21 13H27V15H21V13Z" fill="white"/>
					<path d="M21 21H23V23H21V21Z" fill="white"/>
					<path d="M17 21H19V23H17V21Z" fill="white"/>
					</svg>	

					<div style="margin-left: 16px;text-align: left;">
					<span style="display:block; font-size:16px; font-weight:bold;">LƯU ẢNH QR</span>
					<span style="font-size: 12px;"><i> 
					
					' . __('Then open the banking App and scan the transfer QR', 'vietqr')  . '
					</i></span>
					</div>
					</div>
					</button>
				</a>
			</div>	';
				}

				$showPayment .= '</section>

			<section class="woocommerce-vietqr-bank-details">

				<!-- BANK DETAIL TITLE-->
				<h2 class="wc-vietqr-bank-details-heading" style="text-align: center;">' . esc_html__('Our bank details', 'woocommerce') . '</h2>
				
				
				<!--REQUEST USER-->
				
				<h4 style="color: red;">' .  sprintf(__("Please transfer the correct content <b>%s</b> for we can confirm the payment", 'vietqr'), esc_html($this->prefix . $order_id)) . '</h4>
				
				<!-- BANK DETAIL INFO TABLE-->
				<table class="table table-bordered" style="font-size: 15px;max-width: 800px;margin-left: auto;margin-right: auto;">
				<tbody>
				<tr class="" >
						<td class="text-right width-column-25"  style="text-align: right;">
							<strong style="color: black;">' . __('Account name', 'woocommerce') . ':</strong>
							<br>
						</td>
						<td class="text-left payment-instruction width-column-25" style="text-align: left;">
							<div>
								<span style="color: black;">' . esc_html($account_fields['account_name']['value']) . '</span>
								<br>
							</div>
						</td>
					</tr>
					<tr class="" style="background-color:#FBFBFB;">
						<td class="text-right width-column-25"  style="text-align: right;">
							<strong style="color: black;">' . __('Account number', 'vietqr') . ':</strong>
						</td>
						<td class="text-left payment-instruction width-column-25" style="text-align: left;">
							<span style="color: black;">' . esc_html($account_fields['account_number']['value']) . '</span>
						</td>
					</tr>
					<tr class="" style="">
						<td class="text-right width-column-25" style="text-align: right;">
							<strong style="color: black;">' . __('Bank', 'vietqr') . ':</strong>
							<br>
						</td>
						<td class="text-left payment-instruction width-column-25" style="text-align: left;">
							<div>
								<span style="color: black;">' . esc_html($account_fields['bank_name']['value']) . '</span>
								<br>
							</div>
						</td>
					</tr>
					<tr class="" style="">
						<td class="text-right width-column-25"  style="text-align: right;">
							<strong style="color: black;">' . __('Amount', 'vietqr') . ':</strong>
							<br>
						</td>
						<td class="text-left payment-instruction width-column-25" style="text-align: left;">
							<div ng-switch-when="vcb" class="ng-scope">
								<span style="color: black;">' . esc_html($account_fields['amount']['value']) . ' <sup>vnđ</sup></span>
								<br>
							</div>
						</td>
					</tr>
					<tr class="" >
						<td class="text-right width-column-25" style="text-align: right;">
							<strong style="color: black;">' . __('Memo', 'vietqr') . '*:</strong>
						</td>
						<td class="text-left payment-instruction width-column-25" style="text-align: left;">
							<strong>
							' . esc_html($account_fields['memo']['value']) . '
							</strong>
						</td>
					</tr>
				</tbody>
				</table>
			</section>

			<!-- STYLE CSS-->
			<style>
				
				#downloadQR{
					z-index:333;
					position: fixed;
					left: 0;
					right: 0;
					bottom: 0;
					display:' . ($show_download ? 'block' : 'none') . '
				}

				#btnDownloadQR{
					width:100%;
					border-radius: 0;
					padding-left: 10px !important;
					padding-right: 10px !important;
					border-color: #0274be;
    				background-color: #0274be;
					color: #ffffff;
					line-height: 1;
				}

				.width-column-25 {
					width: 25% ;
				}
				#qrcode canvas {
					border: 2px solid #ccc;
					padding: 20px;
				
				}
				.woocommerce-vietqr-qr-scan{
					text-align: center;
					margin-top: 0px;
				}
				.woocommerce-vietqr-bank-details{
					text-align: center;
					margin-top: 10px;
				}
			</style>';

				echo $showPayment;
				if ($is_sent_email) {
					echo '
					<style>
						.table-bordered {
							border: 1px solid rgba(0,0,0,.1);
						}
					</style>
					';
				}
			}
		}
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment($order_id)
	{
		$order = wc_get_order($order_id);
		if ($order->get_total() > 0) {
			// Mark as on-hold (we're awaiting the payment).
			$order->update_status(apply_filters('woocommerce_vietqr_process_payment_order_status', 'on-hold', $order), __('Awaiting payment', 'woocommerce'));
		} else {
			$order->payment_complete();
		}

		// Remove cart.
		WC()->cart->empty_cart();

		// Return thankyou redirect.
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url($order),
		);
	}

	
	//generate 
	public function get_qrcode_vietqr_img_url($account_fields)
	{

		$accountNo = $account_fields['account_number']['value'];
		$accountName = $account_fields['account_name']['value'];
		$acqId = $account_fields['bank_id']['value'];
		$addInfo = $account_fields['memo']['value'];
		$amount = $account_fields['amount']['value'];
		$template = "compact";

		// $img_url = "https://img.vietqr.io/{$acqId}/{$accountNo}/{$amount}/{$addInfo}/{$format}.jpg";
		$img_url = "https://img.vietqr.io/image/{$acqId}-{$accountNo}-{$template}.jpg?amount={$amount}&addInfo={$addInfo}&accountName={$accountName}";
		$pay_url = "https://api.vietqr.io/{$acqId}/{$accountNo}/{$amount}/{$addInfo}";

		return array(
			"img_url" => $img_url,
			"pay_url" => $pay_url,
		);
	}

	// public function get_qrcode_vietqr($account_fields)
	// {
	// 	global $wp;
	// 	$url = self::$URL_QRCODE;
	// 	$body = array(
	// 		"accountNo" => $account_fields['account_number']['value'],
	// 		"accountName" => $account_fields['account_name']['value'],
	// 		"acqId" => $account_fields['bank_id']['value'],
	// 		"addInfo" => $account_fields['memo']['value'],
	// 		"amount" => intval($account_fields['amount']['value']),
	// 		"format" => "vietqr_net_2"
	// 	);
	// 	$args = array(
	// 		'body'        => json_encode($body),
	// 		'headers' => array(
	// 			'x-api-key' => 'we-l0v3-v1et-qr',
	// 			"x-client-id" => get_site_url(),
	// 			"content-type" => "application/json",
	// 			"referer" => home_url(add_query_arg(array($_GET), $wp->request))
	// 		)
	// 	);
	// 	$response = wp_remote_post($url, $args);
	// 	if (is_wp_error($response)) {
	// 		return null;
	// 	}
	// 	if ($response['response']['code'] == 200 || $response['response']['code'] == 201) {
	// 		$body     = wp_remote_retrieve_body($response);
	// 		return $body;
	// 	}
	// 	return null;
	// }
	public function get_banks_list()
	{
		global $wp;
		$url = self::$URL_BANKS_LIST;
		$response = wp_remote_get($url, array(
			'headers' => array(
				'x-api-key' => 'we-l0v3-v1et-qr',
				"x-client-id" => get_site_url(),
				"content-type" => "application/json",
				"referer" => home_url(add_query_arg(array($_GET), $wp->request))
			)
			// $current_url = home_url(add_query_arg(array($_GET), $wp->request));

		));
		$body     = wp_remote_retrieve_body($response);
		return $body;
	}

	public function get_description()
	{
		$des = apply_filters('woocommerce_gateway_description', $this->description, $this->id);

		if ($this->id == "vietqr") {
			wp_enqueue_style('vietqr-custome-styles', plugins_url('../assets/css/custom.css', __FILE__));
			$des .= "<div>";
			$des .= "<div class ='vietqr-row'>";
			$banks = $this->banks_list;
			foreach ($banks['data'] as $bank) {

				if (isset($bank['logo']) && $bank['transferSupported'] > 0) {
					$des .= "<div class=' list-bank'> ";
					$des .= "   <div class='list-bank-box'>";
					$des .= '		<img src="' . $bank['logo'] . '">';
					$des .= '	</div>';
					$des .= "</div>";
				}
			}
			$des .= "</div>";
			$des = "{$des}</div>";
		}
		return $des;
	}
	public function guess_company_name_from_domain($domain)
	{
		$domain = strtolower(trim($domain));

		//xóa https
		$domain = str_replace("http://", "", $domain);
		$domain = str_replace("https://", "", $domain);

		//xóa port
		$domain = preg_replace('/:[0-9]{2,5}/', '', $domain);


		//xóa kí tự đặc biệt
		$domain = preg_replace('/[^a-z0-9\.]/', '', $domain);

		//chia thành từng block.
		$blocks = explode(".", $domain);

		// trường hợp domain cấp 1 ***.casso.vn
		if (count($blocks) >= 2 && strlen($blocks[count($blocks) - 2]) >= 4)
			return strtoupper($blocks[count($blocks) - 2]);

		// trường họp top levep domain cấp 2: ***.casso.com.vn
		if (count($blocks) >= 3 && strlen($blocks[count($blocks) - 3]) >= 4)
			return strtoupper($blocks[count($blocks) - 3]);

		return '';
	}
}
