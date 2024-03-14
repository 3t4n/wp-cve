<?php
/**
 * Woo Email Customizer
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

use Pelago\Emogrifier\CssInliner;
use Pelago\Emogrifier\HtmlProcessor\CssToAttributeConverter;
use Pelago\Emogrifier\HtmlProcessor\HtmlPruner;

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_General_Template')):
class WECMF_General_Template{
	protected static $_instance = null;
	private $wecmf_builder = null;
	private $template_wrapper_styles = array();
	private $wecmf_ot_td = null;
	private $refunded_emails = array();
	private $wecm_order_item = '';
	private $temp_wrapper_styles;
	private $wecmf_ot_helper;

	public function __construct() {
		add_action('wp_ajax_thwecmf_template_actions', array($this,'save_template_content'));
		add_action('wp_ajax_thwecmf_send_test_mail', array($this,'send_test_mail'));
		add_action('wp_ajax_thwecmf_preview_template', array($this,'preview_template'));
		add_action('wp_ajax_thwecmf_reset_preview', array($this,'reset_preview'));
		$this->init_constants();
	}

	/**
     * Main WECMF_General_Template Instance.
     *
     * Ensures only one instance of WECMF_General_Template is loaded or can be loaded.
     *
     * @static
     * @return WECMF_General_Template Main instance
     */
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
     * Initialize variables
     *
     */
	public function init_constants(){
		$this->temp_wrapper_styles = array('bg' => '#f7f7f7', 'padding' => '70px 0');
		$this->wecmf_ot_td = '\[(\[?)(WECMF_ORDER_TD_CSS)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
		$this->wecmf_ot_helper = '\[(\[?)(WECMF_ORDER_T_HELPER)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
		$this->wecm_order_item = '\[(\[?)(WECM_ORDER_ITEM)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
		$this->refunded_emails = array( 'WC_Email_Customer_Partial_Refunded_Order', 'WC_Email_Customer_Refunded_Order' );
	}

	/**
     * Render the page content
     *
     */
	public function render_page(){
		$this->wecmf_builder = WECMF_Builder_Settings::instance();
		$this->render_content();
	}
	
	/**
     * Load the template builder
     *
     */
	private function render_content(){
		$this->reset_preview();
		$this->wecmf_builder->render_template_builder();
    }

    /**
     * Prepare the template preview
     *
	 * @return boolean whether template preview created or not
     */
    public function preview_template(){
		$response = false;
		check_ajax_referer( 'thwecmf_preview_order', 'security' );
		$task = isset( $_POST['task'] ) ? sanitize_text_field( $_POST['task'] ) : false;
		if( WECMF_Utils::wecm_valid() && WECMF_Utils::is_user_capable() ){
			if( $task === 'reset_preview' ){
				$this->reset_preview();
			}else if( $task === 'create_preview' ){
				$response = $this->prepare_template();
			}
		}
		wp_send_json( $response );
	}

	private function php8_comaptibiltiy_css( $styles ){
		$layout_css = " .thwecmf-block-one-column > tbody > tr > td{
				width: 100%;				
			}
			.thwecmf-block-two-column > tbody > tr > td{
				width: 50%;				
			}

			.thwecmf-block-three-column >tbody > tr > td{
                width: 33%;             
            }

            .thwecmf-block-four-column >tbody > tr > td{
                width: 25%;             
            }";
         return $layout_css.$styles;
	}

	private function prepare_template(){
		$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : false;
		$email = isset( $_POST['email_status'] ) ? sanitize_text_field( $_POST['email_status'] ) : false;
		$content = isset( $_POST['content_html'] ) ? wp_kses_post( stripslashes( $_POST['content_html'] ) ) : false;
		$css = isset( $_POST['content_css'] ) ? wp_kses_post( stripslashes( $_POST['content_css'] ) ) : false;
		$css = $this->php8_comaptibiltiy_css( $css );
		if( $content && $css ){
			$css = $this->prepare_images($css);
			$content = $this->prepare_email_content_wrapper($content);
			$content = $this->create_inline_styles( $content, $css );
			$content = $this->insert_dynamic_data($content, true);
			WECMF_Utils::create_preview();
			$template_name = WECMF_Utils::prepare_template_name( sanitize_text_field( $_POST['template_name'] ) );
			$template_name = $template_name == "customer_partial_refunded_order" ? "customer_partially_refunded_order" : $template_name;
			$path_template = WECMF_Utils::preview_path($template_name);
			return $this->save_template_file($content, $path_template);
		}
		return false;
	}

	/**
     * Get the image specific css to make it inline for outlook emails
     *
     * @param  string $css template styles
	 * @return string $css template styles
     */
	public function prepare_images($css){
		$dimensions = isset( $_POST['imgDimensions'] ) ?  $_POST['imgDimensions'] : false; 
		if(is_array($dimensions) && !empty($dimensions)){
			foreach ($dimensions as $id => $dimension) {
				$block = isset($dimension['blockName']) ? $dimension['blockName'] : false;
				$attr = $this->get_block_image_attribute($block);
				$id = str_replace('tb_', 'tpf_', $id);
				$css .= '#'.$id.$attr.'{';
				$width = isset($dimension['image']['width']) ? $dimension['image']['width'] : '';
				$height = isset($dimension['image']['height']) ? $dimension['image']['height'] : '';
				$css .= 'width:'.$width.'px;';
				$css .= 'height:'.$height.'px;';
				$css .= '}';
			}
		}
		return $css;
	}

	/**
     * Save the template settings to database
     *
     * @param  string $block name of the block
	 * @return string $attribute attribute for image element
     */
	private function get_block_image_attribute($block){
		$attribute = '';
		if( $block == "image" ){
			$attribute = '.thwecmf-block-image img';

		}else if( $block == "social" ){
			$attribute = '.thwecmf-block-social .thwecmf-social-icon img';

		}else if( $block == "header_details" ){
			$attribute = '.thwecmf-block-header .thwecmf-header-logo-ph img';

		}else if( $block == "gif" ){
			$attribute = '.thwecmf-block-gif td.thwecmf-gif-column img';

		}
		return $attribute;
	}

	/**
     * Reset the template preview
     *
     */
	public function reset_preview(){
		$ajaxAction = false;
		$deleted = false;
		if( isset( $_POST["action"] ) && sanitize_text_field( $_POST["action"] ) === "thwecmf_reset_preview" ){
			$ajaxAction = true;
			check_ajax_referer( 'thwecmf_reset_preview', 'security' );
		}
		if( WECMF_Utils::is_user_capable() ){
			$deleted = WECMF_Utils::delete_preview();
		}
		if( $ajaxAction ){
			wp_send_json( $deleted );
		}
	}

    /**
     * Ajax callback function to save the template
     *
	 * @return boolean action done or not
     */
	public function save_template_content(){
		$response = '';
		if( WECMF_Utils::is_valid_action() ){//Nonce, Capability
			$template_display_name = isset($_POST['template_name']) ? sanitize_text_field($_POST['template_name']) : "";
			if( WECMF_Utils::wecm_valid() ){
				$render_data = isset($_POST['contents']) ? wp_kses_post( trim( stripslashes( $_POST['contents'] ) ) ) : false;
				$render_css = isset($_POST['styles']) ? sanitize_textarea_field( stripslashes($_POST['styles'] ) ) : '';
				$render_css = $this->php8_comaptibiltiy_css( $render_css );
				$template_json = isset($_POST['template_tree']) ?  wp_kses_post( trim( stripslashes( $_POST['template_tree'] ) ) ) : ''; 
				if($render_data){
					$save_meta = false;						
					$template_name = WECMF_Utils::prepare_template_name($template_display_name);
					$template_name = $template_name == "customer_partial_refunded_order" ? "customer_partially_refunded_order" : $template_name;
					$save_files = $this->save_template_files($template_name, $render_data, $render_css);
					if($save_files){
						$save_meta = $this->save_settings($template_name, $template_display_name, $template_json);
					}
					wp_send_json($save_files);
				}
			}
			wp_send_json($response);
		}else{
			wp_die();
		}
	}

	/**
     * Save the template settings to database
     *
     * @param  string $template_name template name key
     * @param  string $display_name display name of the template
     * @param  string $template_json json settings of template
	 * @return boolean whether settings saved or not
     */
	public function save_settings($template_name, $display_name, $template_json){
		$settings = $this->prepare_settings($template_name, $display_name, $template_json);
		$result = WECMF_Utils::thwecmf_save_template_settings($settings);
		return $result;
	}

	/**
     * Prepare template data to be saved to a file
     *
     * @param  string $template_name template name key
     * @param  string $render_data template html content
     * @param  string $render_css template styles
	 * @return boolean file created or not
     */
	public function save_template_files($template_name, $render_data, $render_css){
		WECMF_Utils::create_directory();
		$path_template = THWECMF_CUSTOM_T_PATH.$template_name.'.php';
		$render_css = $this->prepare_images($render_css);
		$template_html_final = $this->prepare_email_content_wrapper($render_data);
		$content = $this->create_inline_styles( $template_html_final, $render_css );
		$template_html_final = $this->insert_dynamic_data($content);
		$save_render_file = $this->save_template_file($template_html_final, $path_template);
		return $save_render_file;
	}

	/**
	 * Prepare the test email
	 */	
	public function send_test_mail(){
		$response = "failure";
		$created = false;
		if( isset( $_POST['template'] ) && WECMF_Utils::wecm_valid() && WECMF_Utils::is_user_capable() ){
			$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : false;
			$email = isset( $_POST['email_status'] ) ? sanitize_text_field( $_POST['email_status'] ) : false;
			$content = isset( $_POST['template'] ) ? wp_kses_post( stripslashes( $_POST['template'] ) ) : false;
			$css = isset( $_POST['styles'] ) ? wp_kses_post( stripslashes( $_POST['styles'] ) ) : false;
			$css = $this->php8_comaptibiltiy_css( $css );
			$css = $this->prepare_images($css);
			$content = $this->prepare_email_content_wrapper($content);
			$content = $this->create_inline_styles( $content, $css );
			$content = $this->insert_dynamic_data($content, true);
			WECMF_Utils::create_preview();
			$template_name = WECMF_Utils::prepare_template_name( sanitize_text_field( $_POST['template_name'] ) );
			$template_name = $template_name == "customer_partial_refunded_order" ? "customer_partially_refunded_order" : $template_name;
			$path_template = WECMF_Utils::preview_path($template_name);
			
			$created = $this->save_template_file($content, $path_template);
			if( $created ){
				$content = $this->prepare_preview( $order_id, $email, $template_name, true );
				$send_mail = $this->send_mail( $content );
				$response = $send_mail ? 'success' : 'failure';
			}
		}
		wp_send_json($response);
	}

	/**
	 * Send the test email
	 *
	 * @param  string $message email content
	 * @return boolean $send_mail mail sent or not
	 */	
	public function send_mail( $message ){
		$to = $this->get_from_address();
		$subject = "[".get_bloginfo('name')."] Test Email";
		$headers = $this->setup_test_mail_variables( $to );
		
		add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
		
		$send_mail = wp_mail( $to, $subject, $message, $headers );

		remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
		return $send_mail;
	}

		/**
	 * Set the email from name
	 *
	 * @return string blogname
	 */	
	public function get_from_name() {
		return get_bloginfo('name');
	}

	/**
	 * Set the email from address
	 *
	 * @return string from email
	 */	
	public function get_from_address() {
		if( isset( $_POST['email_id'] ) && !empty( $_POST['email_id'] ) ){
			return sanitize_email( $_POST['email_id'] );
		}
	}

	/**
	 * Set the email content type
	 *
	 * @return string text or html email
	 */	
	public function get_content_type(){
		return 'text/html';
	}

	/**
     * Get the regex for shortcodes used
     */
    public function get_th_shortcode_atts_regex() {
		return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
	}

	/**
	 * Set the email header
	 *
	 * @param  string $from_email from email address
	 * @return array $headers email headers
	 */	
	public function setup_test_mail_variables( $from_email ){
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=".get_bloginfo('charset')."" . "\r\n";
		$headers .= "From: ".get_bloginfo()." <".$from_email.">" . "\r\n";
		return $headers;
	}

	/**
     * Wrap the template content in a standard email wrapper
     *
     * @param  string $content template content
	 * @return string wrapper content
     */
	public function prepare_email_content_wrapper($content){
		$wrap_css_arr = apply_filters('thwecmf_template_wrapper_style_override', $this->temp_wrapper_styles);
		$wrap_css = 'background-color:'.$wrap_css_arr['bg'].';'.'padding:'.$wrap_css_arr['padding'].';';
		$wrapper = '<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="thwecmf_template_wrapper">';
		$wrapper .= '<tr>';
		$wrapper .= '<td align="center" class="thwecmf-template-wrapper-column" valign="top" style="'.esc_attr( $wrap_css ).'">';
		$wrapper .= $content;
		$wrapper .= '</td>';
		$wrapper .= '</tr>';
		$wrapper .= '</tr></table>';									
		return $wrapper;
	}

	/**
     * Save the template content to a file
     *
     * @param  string $content template content
     * @param  string $path template path
	 * @return boolean $saved file created or not
     */
	public function save_template_file($content, $path){
		$saved = false;
		$myfile_template = fopen($path, "w") or die("Unable to open file!");
		if(false !== $myfile_template){
			fwrite($myfile_template, $content);
			fclose($myfile_template);
			$saved = true; 
		}
		return $saved;
	}

	/**
     * convert to inline style
     *
     * @param  string $content template content
     * @param  string $css template style
	 * @return string $content template content
     */
	public function create_inline_styles( $content, $css ) {
		if( WECMF_Utils::thwecmf_woo_version_check('6.5.0') ){
			$css_inliner_class = CssInliner::class;
			if ( class_exists( 'DOMDocument' ) && class_exists( $css_inliner_class ) ) {
				try {
					$css_inliner = CssInliner::fromHtml( $content )->inlineCss( $css );

					do_action( 'woocommerce_emogrifier', $css_inliner, $this );

					$dom_document = $css_inliner->getDomDocument();

					HtmlPruner::fromDomDocument( $dom_document )->removeElementsWithDisplayNone();
					$content = CssToAttributeConverter::fromDomDocument( $dom_document )
						->convertCssToVisualAttributes()
						->render();
					$content = htmlspecialchars_decode($content);
				} catch ( Exception $e ) {
					$logger = wc_get_logger();
					$logger->error( $e->getMessage(), array( 'source' => 'emogrifier' ) );
				}
			} else {
				$content = '<style type="text/css">' . $css . '</style>' . $content;
			}

		}else{
			$emogrifier_support = class_exists( 'DOMDocument' ) && version_compare( PHP_VERSION, '5.5', '>=' );
			if ( $content && $css && $emogrifier_support) {
				$emogrifier_class = '\\Pelago\\Emogrifier';
				$emogrifier_class = WECMF_Utils::thwecmf_emogrifier_version_check() ? '\\Pelago\\Emogrifier' : 'Emogrifier';
				if ( ! class_exists( $emogrifier_class ) ) {
					require_once(WP_PLUGIN_DIR.'/woocommerce/includes/libraries/class-emogrifier.php');
				}
				try {
					$emogrifier = new $emogrifier_class( $content, $css );
					$content    = $emogrifier->emogrify();
					$content    = htmlspecialchars_decode($content);
				} catch ( Exception $e ) {
				}
			}
		}
		return $content;
	}

	/**
     * Prepare template settings to be saved to database
     *
     * @param  string $template_name template name key
     * @param  string $display_name template display name
     * @param  string $template_json template json settings 
	 * @return string $settings template settings
     */
	public function prepare_settings($template_name, $display_name, $template_json){
		$settings = WECMF_Utils::thwecmf_get_template_settings();
		$data = $this->prepare_template_meta_data($template_name, $display_name, $template_json);
		$settings['templates'][$template_name] = $data;
		return $settings;
	}

	/**
     * Prepare template meta data 
     *
     * @param  string $template_name template name key
     * @param  string $display_name template display name
     * @param  string $template_json template json settings
	 * @return array $data template meta data
     */
	public function prepare_template_meta_data($template_name, $display_name, $template_json){
		$file_name = $template_name ? $template_name.'.php' : false;
		$data = array();
		$data['file_name'] = $file_name;
		$data['display_name'] = $display_name;
		$data['template_data'] = $template_json;
		$data['version'] = TH_WECMF_VERSION;
		$data['plan'] = "free";
		return $data;
	}

	/**
     * Insert dynamic data to the template
     *
	 * @param string $content template content
	 * @return string $modified_data template content with dynamic data
     */
	public function insert_dynamic_data($content, $preview=false){
		$modified_data = $content;
		$modified_data = $this->replace_thwecmf_placeholder_data($modified_data, $preview);
		$modified_data = str_replace('<span>{customer_address}</span>', $this->customer_data( $preview ), $modified_data);
		$modified_data = str_replace('<span>{billing_address}</span>', $this->billing_data(), $modified_data);
		$modified_data = str_replace('<span>{shipping_address}</span>', $this->shipping_data(), $modified_data);
		$modified_data = str_replace('<span>{thwecmf_before_shipping_table}</span>', $this->add_order_head( true ), $modified_data);
		$modified_data = str_replace('<span>{thwecmf_after_shipping_table}</span>', $this->add_order_foot( true ), $modified_data);
		$modified_data = str_replace('<span>{thwecmf_before_billing_table}</span>', $this->add_order_head(), $modified_data);
		$modified_data = str_replace('<span>{thwecmf_after_billing_table}</span>', $this->add_order_foot(), $modified_data);
		$modified_data = str_replace('<span>{thwecmf_before_customer_table}</span>', $this->add_order_head(), $modified_data);
		$modified_data = str_replace('<span>{thwecmf_after_customer_table}</span>', $this->add_order_foot(), $modified_data);


		$modified_data = str_replace('<span class="loop_start_before_order_table"></span>', $this->order_table_before_loop(), $modified_data); //woocommerce_email_before_order_table 
		$modified_data = str_replace('<span class="loop_end_after_order_table"></span>', $this->order_table_after_loop(), $modified_data); //woocommerce_email_before_order_table 
		$modified_data = str_replace('<span class="woocommerce_email_before_order_table"></span>', $this->order_table_before_hook(), $modified_data); //woocommerce_email_before_order_table 
		$modified_data = str_replace('{Order_Product}', $this->order_table_header_product(), $modified_data); //first row content
		$modified_data = str_replace('{Order_Quantity}', $this->order_table_header_qty(), $modified_data); //first row content
		$modified_data = str_replace('{Order_Price}', $this->order_table_header_price(), $modified_data);//first row content
		$modified_data = str_replace('<tr class="item-loop-start"></tr>', $this->order_table_item_loop_start(), $modified_data); // product display loop start
		$modified_data = str_replace('woocommerce_order_item_class-filter1', $this->order_table_class_filter(), $modified_data); // woocommerce filter as class for a <td>
		// $modified_data = str_replace('{order_items}', $this->order_table_items(), $modified_data); // Code to display  items without image
		// $modified_data = str_replace('{order_items_img}', $this->order_table_items(true), $modified_data); // Code to display  items along with image
		$modified_data = str_replace('{order_items_qty}', $this->order_table_items_qty(), $modified_data);// Code to display  item quantity
		$modified_data = str_replace('{order_items_price}', $this->order_table_items_price(), $modified_data); // Code to display  item price
		$modified_data = str_replace('<tr class="item-loop-end"></tr>',$this->order_table_item_loop_end(), $modified_data);  // product display loop end
		$modified_data = str_replace('<tr class="order-total-loop-start"></tr>', $this->order_table_total_loop_start(), $modified_data); //totals display loop start
		$modified_data = str_replace('{total_label}', $this->order_table_total_labels(), $modified_data); // Code to display <tfoot> total labels
		$modified_data = str_replace('{total_value}', $this->order_table_total_values(), $modified_data); // Code to display <tfoot> total values


		$modified_data = $this->replace_woocommerce_hooks_contents($modified_data);
		return $modified_data;
	}

	/**
     * Replace placeholders with corresponding data
     *
	 * @return string $modified_data template content
	 * @return string $modified_data template content
     */
	public function replace_thwecmf_placeholder_data($modified_data, $preview ){
		$modified_data = str_replace('{th_customer_name}', $this->wecmf_get_customer_name(), $modified_data);
		$modified_data = str_replace('{th_site_name}', $this->wecmf_get_site_name(), $modified_data);
		$modified_data = str_replace('{th_account_area_url}', $this->wecmf_get_account_area_url(), $modified_data);
		$modified_data = str_replace('{th_user_login}', $this->wecmf_get_user_login(), $modified_data);
		$modified_data = str_replace('{th_user_pass}', $this->wecmf_get_user_pass(), $modified_data);
		/**
		* Placeholders made compatible with the premuim version
 		* @version 3.7.0
 		*/
 		$modified_data = str_replace('{customer_name}', $this->wecmf_get_customer_name(), $modified_data);
		$modified_data = str_replace('{site_name}', $this->wecmf_get_site_name(), $modified_data);
		$modified_data = str_replace('{account_area_url}', $this->wecmf_get_account_area_url(), $modified_data);
		$modified_data = str_replace('{user_login}', $this->wecmf_get_user_login(), $modified_data);
		$modified_data = str_replace('{user_pass}', $this->wecmf_get_user_pass(), $modified_data);


		$modified_data = str_replace('{reset_password_url}', $this->wecmf_get_reset_password_url($preview), $modified_data);
		$modified_data = str_replace('{set_password_url}', $this->wecmf_set_password_url($preview), $modified_data, $preview);
		$modified_data = str_replace('{customer_note}', $this->wecmf_get_customer_note( $preview ), $modified_data);
		$modified_data = str_replace('{customer_full_name}', $this->wecmf_get_customer_full_name(), $modified_data);
		$modified_data = str_replace('{order_id}', $this->wecmf_get_order_id(), $modified_data);
		$modified_data = str_replace('{order_created_date}', $this->wecmf_get_order_created_date(), $modified_data);
		
		$modified_data = str_replace('{thwecmf_ot_header}', $this->get_order_table_head(), $modified_data);
		
		return $modified_data;
	}

	/**
     * Replace WooCommerce hook name with WooCommerec hooks
     *
	 * @return string $modified_data template content
	 * @return string $modified_data replaced template content
     */
	public function replace_woocommerce_hooks_contents($modified_data){
		$modified_data = str_replace('<p class="thwecmf-hook-code">{email_header_hook}</p>', $this->thwecmf_email_hooks('{email_header_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{email_order_details_hook}</p>', $this->thwecmf_email_hooks('{email_order_details_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{before_order_table_hook}</p>', $this->thwecmf_email_hooks('{before_order_table_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{after_order_table_hook}</p>', $this->thwecmf_email_hooks('{after_order_table_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{order_meta_hook}</p>', $this->thwecmf_email_hooks('{order_meta_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{customer_details_hook}</p>', $this->thwecmf_email_hooks('{customer_details_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{email_footer_hook}</p>', $this->thwecmf_email_hooks('{email_footer_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{downloadable_product_table}</p>',$this->downloadable_product_table(), $modified_data);
		$modified_data = preg_replace_callback("/$this->wecmf_ot_td/", array($this, "ot_shortcodes"),$modified_data);
		$modified_data = preg_replace_callback("/$this->wecmf_ot_helper/", array($this, "wecmf_order_table_helper"),$modified_data);
		$modified_data = preg_replace_callback("/$this->wecm_order_item/", array($this, "wecmf_order_item_functions"),$modified_data);
		return $modified_data;
	}

	public function get_shortcode_atts($occurances){
		$atts = array();
		if ( $occurances[1] == '[' && $occurances[6] == ']' ) {
			return substr($occurances[0], 1, -1);
		}
		$sec_pattern = $this->get_th_shortcode_atts_regex();
		$content = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $occurances[3]);
		if ( preg_match_all($sec_pattern, $content, $match, PREG_SET_ORDER) ) {
			foreach ($match as $m) {
				if (!empty($m[1]))
					$atts[strtolower($m[1])] = stripcslashes($m[2]);
				elseif (!empty($m[3]))
					$atts[strtolower($m[3])] = stripcslashes($m[4]);
				elseif (!empty($m[5]))
					$atts[strtolower($m[5])] = stripcslashes($m[6]);
				elseif (isset($m[7]) && strlen($m[7]))
					$atts[] = stripcslashes($m[7]);
				elseif (isset($m[8]) && strlen($m[8]))
					$atts[] = stripcslashes($m[8]);
				elseif (isset($m[9]))
					$atts[] = stripcslashes($m[9]);
			}
		}
		return $atts;
	}

	public function ot_shortcodes( $occurances ){
		$replace_html = '';
		$atts = $this->get_shortcode_atts($occurances);
		if($atts){
			$text = isset($atts['styles']) && !empty($atts['styles']) ? $atts['styles'] : false;
			$font_css = isset($atts['font_styles']) && !empty($atts['font_styles']) ? $atts['font_styles'] : false;
			$replace_html = $text ? $this->order_table_additional_td_css( $text, $font_css ) : "";
		}
		return $replace_html;
	}

	public function wecmf_order_table_helper($occurances){
		$replace_html = '';
		$atts = $this->get_shortcode_atts($occurances);
		if($atts){
			$id = isset($atts['id']) && !empty($atts['id']) ? $atts['id'] : false;
			$labels = isset($atts['labels']) && !empty($atts['labels']) ? $atts['labels'] : false;
			$labels = json_decode($labels, true );
			$product_column_label = isset( $labels['product_column_label'] ) ? $labels['product_column_label'] : '';
			$quantity_column_label = isset( $labels['quantity_column_label'] ) ? $labels['quantity_column_label'] : '';
			$price_column_label = isset( $labels['price_column_label'] ) ? $labels['price_column_label'] : '';
			$cart_subtotal = isset( $labels['subtotal_row_label'] ) ? $labels['subtotal_row_label'] : '';
			$shipping = isset( $labels['shipping_row_label'] ) ? $labels['shipping_row_label'] : '';
			$payment_method = isset( $labels['payment_row_label'] ) ? $labels['payment_row_label'] : '';
			$order_total = isset( $labels['total_row_label'] ) ? $labels['total_row_label'] : '';
			
			$replace_html = '<?php $wecmf_order_table_labels = array(
				"product_column_label" => "'.$product_column_label.'",
				"quantity_column_label" => "'.$quantity_column_label.'",
				"price_column_label" => "'.$price_column_label.'",
				"cart_subtotal" => "'.$cart_subtotal.'",
				"shipping" => "'.$shipping.'",
				"payment_method" => "'.$payment_method.'",
				"order_total" => "'.$order_total.'",
			); ?>';
		}
		return $replace_html;
	}

	/**
     * Downloadable product html string
     *
	 * @return string $downloadable_product downloadable product html string
     */
	public function downloadable_product_table(){
		$downloadable_product = '';
		$downloadable_product .= '<?php $show_downloads = isset( $order ) && $order->has_downloadable_item() && $order->is_download_permitted() && ! $sent_to_admin && ! is_a( $email, \'WC_Email_Customer_Refunded_Order\' ); ?>';
		$downloadable_product .= '<?php $text_align = is_rtl() ? \'right\' : \'left\'; 
		if( isset($show_downloads) && $show_downloads ){
		$downloads = $order->get_downloadable_items();
		$columns   = apply_filters(
					\'woocommerce_email_downloads_columns\', array(
					\'download-product\' => __( \'Product\', \'woocommerce\' ),
					\'download-expires\' => __( \'Expires\', \'woocommerce\' ),
					\'download-file\'    => __( \'Download\', \'woocommerce\' ),
					)
				); ?>';
		$downloadable_product .= '<?php if($downloads) {?>';
		$downloadable_product .=  '<h2 class="woocommerce-order-downloads__title"><?php esc_html_e( \'Downloads\', \'woocommerce\' ); ?></h2>';
		$downloadable_product .= '<table class="thwecmf_downloadable_table" class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 40px;" border="1">
		<thead>
			<tr>
				<?php foreach ( $columns as $column_id => $column_name ) : ?>
					<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo esc_html( $column_name ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<?php foreach ( $downloads as $download ) : ?>
			<tr>
				<?php foreach ( $columns as $column_id => $column_name ) : ?>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
						<?php
						if ( has_action( \'woocommerce_email_downloads_column_\' . $column_id ) ) {
							do_action( \'woocommerce_email_downloads_column_\' . $column_id, $download, $plain_text );
						} else {
							switch ( $column_id ) {
								case \'download-product\':
									?>
									<a href="<?php echo esc_url( get_permalink( $download[\'product_id\'] ) ); ?>"><?php echo wp_kses_post( $download[\'product_name\'] ); ?></a>
									<?php
									break;
								case \'download-file\':
									?>
									<a href="<?php echo esc_url( $download[\'download_url\'] ); ?>" class="woocommerce-MyAccount-downloads-file button alt"><?php echo esc_html( $download[\'download_name\'] ); ?></a>
									<?php
									break;
								case \'download-expires\':
									if ( ! empty( $download[\'access_expires\'] ) ) {
										?>
										<time datetime="<?php echo esc_attr( date( \'Y-m-d\', strtotime( $download[\'access_expires\'] ) ) ); ?>" title="<?php echo esc_attr( strtotime( $download[\'access_expires\'] ) ); ?>"><?php echo esc_html( date_i18n( get_option( \'date_format\' ), strtotime( $download[\'access_expires\'] ) ) ); ?></time>
										<?php
									} else {
										esc_html_e( \'Never\', \'woocommerce\' );
									}
									break;
							}
						}
						?>
					</td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?></table>';
		$downloadable_product .= '<?php } } ?>';
		return $downloadable_product;
	}

	/**
     * Get the customer name html string
     *
	 * @return string $customer_name customer name html string
     */
	public function wecmf_get_customer_name(){
		$customer_name = '<?php if(isset($order)) : ?>';
		$customer_name.= '<?php echo esc_html($order->get_billing_first_name()); ?>';
		$customer_name.= '<?php elseif(isset($user_login)): ?>';
		$customer_name.= '<?php echo esc_html($user_login); ?>'; 
		$customer_name.= '<?php endif; ?>';
		return $customer_name;
	}

	/**
     * Site name html string
     *
	 * @return string $site_name site name html string
     */
	public function wecmf_get_site_name(){
		// Email providers adding anchor tags to text with '.com' which can be avoided by wrapping the text around an anchor tag with nofollow and custom style
		$site_name = '<a class="thwec-placeholder-site-name" rel="nofollow" href="#" style="text-decoration:none;color:inherit;"><?php echo esc_html( get_bloginfo() );?></a>';
		return $site_name;
	}

	/**
     * Account area url html string
     *
	 * @return string $account_area_url account area url html string
     */
	public function wecmf_get_account_area_url(){
		$account_area_url = '<?php echo make_clickable( esc_url( wc_get_page_permalink( \'myaccount\' ) ) ); ?>';
		return $account_area_url;
	}

	/**
     * User login html string
     *
	 * @return string $user_login user login html string
     */
	public function wecmf_get_user_login(){
		$user_login = '<?php if(isset($user_login)) : ?>';
		$user_login .= '<?php echo \'<strong>\' . esc_html( $user_login ) . \'</strong>\' ?>';
		$user_login .= '<?php elseif( isset($order) ): ?>';
		$user_login .= '<?php $user = $order->get_user(); ?>';
		$user_login .= '<?php echo $user->user_login; ?>';
		$user_login .= '<?php endif; ?>';
		return $user_login;
	}

	/**
     * User password html string
     *
	 * @return string $user_pass user password html string
     */
	public function wecmf_get_user_pass(){
		$user_pass = '<?php if ( \'yes\' === get_option( \'woocommerce_registration_generate_password\' ) && isset($password_generated) ) : ?>';
		$user_pass.= '<?php echo \'<strong>\' . esc_html( $user_pass ) . \'</strong>\' ?>';
		$user_pass.= '<?php endif; ?>';
		return $user_pass;
	}

	/**
     * Password reset url html string
     *
	 * @return string password reset url html string
     */
	public function wecmf_get_reset_password_url($preview){
		if($preview){
			return '<a href="#"><?php _e( \'Click here to reset your password\', \'woocommerce\' ); ?></a>';
		}
		
		$reset_pass = '<?php if(isset($reset_key) && isset($user_id)): ?>';
		if( WECMF_Utils::thwecmf_woo_version_check('3.4.0') ){
			$reset_pass .= '<a class="link thwec-link" href="<?php echo esc_url( add_query_arg( array( \'key\' => $reset_key, \'id\' => $user_id ), wc_get_endpoint_url( \'lost-password\', \'\', wc_get_page_permalink( \'myaccount\' ) ) ) ); ?>">
			<?php _e( \'Click here to reset your password\', \'woocommerce\' ); ?></a>';
		}else{
			$reset_pass .= '<a class="link thwec-link" href="<?php echo esc_url( add_query_arg( array( \'key\' => $reset_key, \'login\' => $user_id ), wc_get_endpoint_url( \'lost-password\', \'\', wc_get_page_permalink( \'myaccount\' ) ) ) ); ?>">
				<?php _e( \'Click here to reset your password\', \'woocommerce\' ); ?></a>';
		}
		$reset_pass.= '<?php endif; ?>';
		return $reset_pass;
	}

	public function wecmf_set_password_url($preview){
		if($preview){
			return '<p><a href="#"><?php printf( esc_html__( \'Click here to set your new password.\', \'woocommerce\' ) ); ?></a></p>';
		}
		$set_pass = '';
		if( WECMF_Utils::thwecmf_woo_version_check('6.0.0') ){
			$set_pass .= '<?php if ( \'yes\' === get_option( \'woocommerce_registration_generate_password\' ) && isset( $password_generated ) && isset( $set_password_url ) ) : ?>';
			$set_pass .= '<p><a href="<?php echo esc_attr( $set_password_url ); ?>"><?php printf( esc_html__( \'Click here to set your new password.\', \'woocommerce\' ) ); ?></a></p>';
			$set_pass .= '<?php endif; ?>';
		}
		return $set_pass;
	}

	/**
     * Customer note html string
     *
	 * @return string $customer_note customer note html string
     */
	public function wecmf_get_customer_note( $preview ){
		$customer_note = '';
		if( $preview ){
			$customer_note.= '<?php if( isset( $order ) ) : ';
			$customer_note.= '$notes = $order->get_customer_order_notes();';
			$customer_note.= 'reset($notes);';
			$customer_note.= '$note_key = key($notes);';
			$customer_note.= '$customer_note = isset( $notes[$note_key]->comment_content ) ? $notes[$note_key]->comment_content : \'\';';
			$customer_note.= 'if( !empty( $customer_note ) ): ?>';
			$customer_note.= '<blockquote><?php echo wpautop( wptexturize( $customer_note ) ); ?></blockquote>';
			$customer_note.= '<?php endif; endif; ?>';

		}else{
			$customer_note = '<?php if(isset($customer_note)) : ?>';
			$customer_note.= '<blockquote><?php echo wpautop( wptexturize( $customer_note ) ); ?></blockquote>';
			$customer_note.= '<?php endif; ?>';
		}
		return $customer_note;
	}

	/**
     * Customer full name html string
     *
	 * @return string full name html string
     */
	public function wecmf_get_customer_full_name(){
		$customer_name = '<?php if(isset($order)) : ?>';
		$customer_name.= '<?php echo esc_html( $order->get_billing_first_name().\' \'.$order->get_billing_last_name() ); ?>';
		$customer_name.= '<?php elseif(isset($user_login)): ?>';
		$customer_name.= '<?php echo esc_html($user_login); ?>'; 
		$customer_name.= '<?php endif; ?>';
		return $customer_name;
	}

	/**
     * Get the order created date html string
     *
	 * @return string order created date html string
     */
	public function wecmf_get_order_created_date(){
		$order_date = '<?php if(isset($order)) : ?>';
		$order_date.= '<?php echo wc_format_datetime($order->get_date_created()); ?>';
		$order_date.= '<?php endif; ?>';
		return $order_date;
	}

	/**
     * Get the order ID html string
     *
	 * @return string order ID html string
     */
	public function wecmf_get_order_id(){
		$order_id = '<?php if(isset($order)) : ?>';
		$order_id.= '<?php echo $order->get_id();?>';
		$order_id.= '<?php endif; ?>';
		return $order_id;
	}

	/**
	 * Customer address content
	 */
	public function customer_data( $preview ){
		$address = '<?php echo esc_html( $order->get_formatted_billing_full_name() ); ?><br><?php echo wc_make_phone_clickable( $order->get_billing_phone() ); ?>';
		$address .= '<?php if ( $order->get_billing_email() ) : ?>
					<br><a class="thwecmf-link" href="mailto:<?php echo esc_html( $order->get_billing_email() ); ?>"><?php echo esc_html( $order->get_billing_email() ); ?></a>
				<?php endif; ?>';
		return $address;
	}

	/**
     * Get the billing address html string
     *
	 * @return string billing address html string
     */
	public function billing_data(){
		$address = '<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( "N/A", "woocommerce" ) ) ); ?>
				<?php if ( $order->get_billing_phone() ) : ?>
					<br/><?php echo wc_make_phone_clickable( $order->get_billing_phone() ); ?>
				<?php endif; ?>';
		$address .= '<?php if ( $order->get_billing_email() ) : ?>
					<br><a class="thwecmf-link" href="mailto:<?php echo esc_html( $order->get_billing_email() ); ?>"><?php echo esc_html( $order->get_billing_email() ); ?></a>
				<?php endif; ?>';
		return $address;
	}

	/**
     * Get the shipping address html string
     *
	 * @return string shipping address html string
     */
	public function shipping_data(){
		$address = '<?php echo isset( $shipping ) ? wp_kses_post( $shipping ) : wp_kses_post( $order->get_formatted_shipping_address() ); ?>';
		return $address;
	}

	/**
     * Get the content to be added before the order blocks
     *
	 * @param  boolean $shipping shipping block
	 * @return string content to be added before the order blocks
     */
	public function add_order_head( $shipping = false ){
		if( $shipping ){
			$data = '<?php if( isset( $order ) ) : ';
			$data .= '$shipping  = $order->get_formatted_shipping_address();';
			$data .= 'if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping ) : ?>';
			return $data;

		}
		return '<?php if( isset( $order ) ) : ?>';
	}
	
	/**
     * Get the content to be added after the order blocks
     *
	 * @param  boolean $shipping shipping block
	 * @return string content to be added after the order blocks
     */
	public function add_order_foot( $shipping = false ){
		if( $shipping ){
			return '<?php endif; endif; ?>';
		}

		return '<?php endif; ?>';
	}

	/**
     * Get default WooCommerce hooks
     *
	 * @param  string $hook name of the hook
	 * @return string $hook html content of the WooCommerce hook
     */
	public function thwecmf_email_hooks($hook){
		switch($hook){
			 case '{email_header_hook}':
                $hook ='<?php do_action( \'woocommerce_email_header\', $email_heading, $email ); ?>'; 
                break;
 			case '{email_order_details_hook}': 
 				$hook = '<div class="thwecmf-hook-order-details"><?php if(isset($order)){ 
 					do_action( \'woocommerce_email_order_details\', $order, $sent_to_admin, $plain_text, $email ); 
 				}?></div>';
 				break;
  			case '{before_order_table_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action(\'woocommerce_email_before_order_table\', $order, $sent_to_admin, $plain_text, $email); 
  				}?>';
 				break;
  			case '{after_order_table_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action(\'woocommerce_email_after_order_table\', $order, $sent_to_admin, $plain_text, $email); 
  				}?>';
 				break;
  			case '{order_meta_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action( \'woocommerce_email_order_meta\', $order, $sent_to_admin, $plain_text, $email ); 
  				}?>';
 				break;
  			case '{customer_details_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action( \'woocommerce_email_customer_details\', $order, $sent_to_admin, $plain_text, $email ); 
  				}?>';
 				break;
 			case '{email_footer_hook}':
                $hook = '<?php do_action( \'woocommerce_email_footer\', $email ); ?>';
                break;
            case '{email_footer_blogname}':
            $hook = '<?php echo wpautop( wp_kses_post( wptexturize( apply_filters( \'woocommerce_email_footer_text\', \'\' ) ) ) ); ?>';
            default:
                $hook = '';
		}
		return $hook;
	}

	/**
	 * Order condition opening
	 */
	public function order_table_before_loop(){
		$loop = '<?php if(isset($order)){ ?>';
		return $loop;
	}

	/**
	 * Order condition closing
	 */
	public function order_table_after_loop(){
		$loop = '<?php } ?>';
		return $loop;
	}

	/**
	 * Helper contents for order table
	 *
	 * @param  boolean $tag php tag necessary or not
	 * @return string $content button content
	 */
	public function order_table_before_hook(){
		$order_data = '<?php $text_align = is_rtl() ? "right" : "left"; ?>';
		return $order_data;
	}

	/**
	 * Order table header item
	 */
	public function order_table_header_product(){
		$order_data = '<?php echo esc_html__( apply_filters("thwecmf_rename_order_total_labels", "Product"), \'woocommerce\' ); ?>';
		return $order_data;
	}

	/**
	 * Order table header quantity
	 */
	public function order_table_header_qty(){
		$order_data = '<?php echo esc_html__( apply_filters("thwecmf_rename_order_total_labels", "Quantity"), \'woocommerce\' ); ?>';
		return $order_data;
	}

	/**
	 * Order table header price
	 */
	public function order_table_header_price(){
		$order_data = '<?php echo esc_html__( apply_filters("thwecmf_rename_order_total_labels", "Price"), \'woocommerce\' ); ?>';
		return $order_data;
	}

	/**
	 * Order item loop start
	 */
	public function order_table_item_loop_start(){
		$order_data = '<?php 
		$items = $order->get_items();
		foreach ( $items as $item_id => $item ) :
	$product = $item->get_product();
	if ( apply_filters( "woocommerce_order_item_visible", true, $item ) ) {
		?>';
		return $order_data;
	}

	/**
	 * Order item section loop end
	 */
	public function order_table_item_loop_end(){
		$order_data = '<?php
		}
		$show_purchase_note=true;
		if ( $show_purchase_note && is_object( $product ) && ( $purchase_note = $product->get_purchase_note() ) ) : ?>
			<tr>
				<td colspan="3" style="text-align:<?php echo $text_align; ?>;vertical-align:middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;"><?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?></td>
			</tr>
		<?php endif; ?>
		<?php endforeach; ?>';
		return $order_data;
	}

	/**
	 * Order item class filter
	 */
	public function order_table_class_filter(){
		$order_data = '<?php echo esc_attr( apply_filters( \'woocommerce_order_item_class\', \'order_item\', $item, $order ) ); ?>';
		return $order_data;
	}

	/**
	 * Order item qunatity
	 */
	public function order_table_items_qty(){
		$order_data = '<?php echo wp_kses_post( apply_filters( \'woocommerce_email_order_item_quantity\', $item->get_quantity(), $item ) ); ?>';
		return $order_data;
	}

	/**
	 * Order item price
	 */
	public function order_table_items_price(){
		$order_data = '<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>';
		return $order_data;
	}	

	/**
	 * Order item contents
	 *
	 * @param  boolean $img show image or not
	 */
	public function order_table_items($img=false){
		$order_data = '<?php '; 
		if($img){
			$order_data .= '$show_image = true;';
			$order_data .= '$image_size=array( 32, 32);';
		}else{
			$order_data .= '$show_image = false;';
		}
		$order_data .= '$show_sku = apply_filters( "thwec_show_order_table_sku", $sent_to_admin, $item_id, $item, $order, $plain_text );';
		$order_data .= '

				// Show title/image etc
				if ( $show_image ) {
					echo wp_kses_post( apply_filters( \'woocommerce_order_item_thumbnail\', \'<div style="margin-bottom: 5px"><img src="\' . ( $product->get_image_id() ? current( wp_get_attachment_image_src( $product->get_image_id(), \'thumbnail\' ) ) : wc_placeholder_img_src() ) . \'" alt="\' . esc_attr__( \'Product image\', \'woocommerce\' ) . \'" height="\' . esc_attr( $image_size[1] ) .\'" width="\' . esc_attr( $image_size[0] ) . \'" style="vertical-align:middle; margin-\' . ( is_rtl() ? \'left\' : \'right\' ) . \': 10px;" /></div>\', $item ) );
				}

				// Product name
				echo wp_kses_post( apply_filters( \'woocommerce_order_item_name\', $item->get_name(), $item, false ) );

				// SKU
				if ( $show_sku && is_object( $product ) && $product->get_sku() ) {
					echo wp_kses_post( \' (#\' . $product->get_sku() . \')\');
				}

				// allow other plugins to add additional product information here
				do_action( \'woocommerce_order_item_meta_start\', $item_id, $item, $order, $plain_text );

				wc_display_item_meta( $item );

				// allow other plugins to add additional product information here
				do_action( \'woocommerce_order_item_meta_end\', $item_id, $item, $order, $plain_text );

			?>';
		return $order_data;
	}

	/**
	 * Order table total loop start
	 */
	public function order_table_total_loop_start(){
		$order_data = '<?php
		if(isset($order)){
			$totals = $order->get_order_item_totals();
			if ( $totals ) {
				$i = 0;
				foreach ( $totals as $total_key => $total ) {
					$total[\'label\'] = isset( $wecmf_order_table_labels ) && isset( $wecmf_order_table_labels[$total_key] ) ? $wecmf_order_table_labels[$total_key] : $total[\'label\']; 
					$i++;
					?>';
		return $order_data;
	}

	/**
	 * Order total labels
	 */
	public function order_table_total_labels(){
		$order_data = '<?php echo wp_kses_post( apply_filters("thwec_rename_order_total_labels", $total[\'label\']) ); ?>';
		return $order_data;
	}

	/**
	 * Order table values
	 */
	public function order_table_total_values(){
		$order_data = '<?php echo wp_kses_post( $total[\'value\'] ); ?>';
		return $order_data;
	}

	/**
	 * Order table header
	 */
	private function get_order_table_head(){
		
		$order_data = '<?php if ( $sent_to_admin ) {
				$before = \'<a style="font-size:inherit;font-family:inherit;color:inherit;" class="link" href="\' . esc_url( $order->get_edit_order_url() ) . \'">\';
				$after  = \'</a>\';
			} else {
				$before = \'\';
				$after  = \'\';
			}
			echo wp_kses_post( $before . sprintf( __( \'[Order #%s]\', \'woocommerce\' ) . $after . \' (<time datetime=\"%s\">%s</time>)\', $order->get_order_number(), $order->get_date_created()->format( \'c\' ), wc_format_datetime( $order->get_date_created() ) ) );
			?>';
		return $order_data;
	}

	/**
	 * Order note in order table
	 *
	 * @param  string $styles css inline style
	 * @param  string $font_css coma seperated font string
	 */
	public function order_table_additional_td_css( $styles, $font_css ){
		$font_css = explode(',', $font_css);
		$fonts = WECMF_Utils::font_family_list();
		if( $font_css && is_array( $font_css ) ){
			foreach ($font_css as $index => $key) {
				if( isset( $fonts[$key] ) ){
					$styles .= "font-family:".$fonts[$key].";";
				}	
			}
		}
		$order_data = '<?php
				}
			}
			if ( isset($order) && $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="'.esc_attr( $styles ).'"><?php esc_html_e( \'Note:\', \'woocommerce\' ); ?></th>
					<td class="td" style="'.$styles.'"><?php echo wp_kses_post( wptexturize( $order->get_customer_note() ) ); ?></td>
				</tr>
				<?php
			}
		}
			?>';
		return $order_data;
	}

	/**
     * Get the preview of the template to be displayed
     *
	 * @param  int $order_id WooCommerce order id
	 * @param  string $email_index name of the email status
	 * @param  string $template name of the template
	 * @return string $result html content of the template to be previewed
     */
	public function prepare_preview( $order_id, $email_index, $template, $preview=false ){
		$content = '';
		$settings = WECMF_Utils::thwecmf_get_template_settings();
		if( WECMF_Utils::is_template( $template ) ){
			ob_start();
	        wc_get_template( 'emails/email-styles.php' );
	        $css = apply_filters( 'woocommerce_email_styles', ob_get_clean(), $this );
	        $css = $this->remove_harmful_styles($css);
    		$css = $css.WECMF_Utils::get_thwecmf_styles();
    		$account_emails = array( 'WC_Email_Customer_New_Account', 'WC_Email_Customer_Reset_Password' );
    		$template = WECMF_Utils::get_template( $template, $preview );

	  		if( $template ){ 			
	  			$emails = WC_Emails::instance();
	  			$email_class = $this->get_email_class( $emails, $email_index );
				$email_class_id = $email_index === "WC_Email_Customer_Partial_Refunded_Order" ? "customer_partially_refunded_order" : $email_class->id;
				add_filter( 'woocommerce_email_recipient_' . $email_class_id, array( $this, 'no_recipient' ) );
				if( in_array( $email_index, $account_emails ) ){
					//Account related email
					$customer = WECMF_Utils::get_logged_in_user();
					if( $customer ){
						$template_type = 'html';
						$customer_id = $customer->ID;
						$customer_login = $customer->user_login;
						$email_class->trigger( $customer_id, '', false );

						$email_args = array(
							'user_login'         => $customer_login,
							'user_pass'          => '',
							'blogname'           => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
							'password_generated' => false,
							'sent_to_admin'      => false,
						);
					}
				}else{
					//Order related email
					$email_type = $this->thwecmf_preview_email_type( $email_class );
					$order = wc_get_order( $order_id );
					if( !in_array( $email_index, $this->refunded_emails ) ){
						$email_class->object = $order;
					}

					if( !in_array( $email_index, $this->refunded_emails ) ){
						$email_class->object = $order;
					}				
					
					if( in_array( $email_index, $this->refunded_emails ) ){
						$is_partial = $email_index === "WC_Email_Customer_Partial_Refunded_Order" ? true : false;
						$email_class->trigger( $order_id, $is_partial );
					}else{
						$email_class->trigger( false, $order );
					}

					$email_args = array(
						'order'              => $order,
						'sent_to_admin'      => $email_type == 'admin' ? true : false,
					);
				}



				$args = array_merge( $email_args, array(
					'email_heading'      => $email_class->get_heading(),
					'additional_content' => $email_class->get_additional_content(),
					'plain_text'         => false,
					'email'              => $email_class,
				) );
				extract( $args );
	  			ob_start();
	  			
				include_once( $template );
				$content = ob_get_clean();
				$content = $this->create_inline_styles( $content, $css, true );
				remove_filter( 'woocommerce_email_recipient_' . $email_class_id, array( $this, 'no_recipient' ) );
			}

		}
		return $content;
	}

	/**
     * Get Email status class object
	 *
	 * @param string $emails Order ID to preview
	 * @param object email status class object
     */
	public function get_email_class( $emails, $index ){
		$index = $index === "WC_Email_Customer_Partially_Refunded_Order" ? "WC_Email_Customer_Refunded_Order" : $index;
		$emails = $emails->get_emails();
		return isset( $emails[$index] ) ? $emails[$index] : false;	 
	}

	/**
	 * Set empty email recipient
	 *
	 * @param $recipient email recipient
	 * @return empty string
	 */
	public function no_recipient( $recipient ){
		return '';
	}

	/**
     * Get the email type - admin/customer
     *
	 * @param  object $email_class WC_Emails class instance
	 * @return string $email_type whether admin or customer
     */
	public function thwecmf_preview_email_type( $email_class ){
		$email_type = 'customer';
		if( in_array( $email_class->id, WECMF_Utils::THWECMF_EMAIL_INDEX ) ){
			$email_type = in_array( $email_class->id, array( 'new_order', 'cancelled_order', 'failed_order') ) ? 'admin' : 'customer';
		}
		return $email_type;
	}

	/**
	 * Get order table item attributes and contents
	 *
	 * @param  array $occurances occurances
	 * @return string $replace_html content
	 */
	public function wecmf_order_item_functions( $occurances ){
		$atts = $this->get_shortcode_atts($occurances);
		$replace_html = '';
		if($atts){
			$image = isset($atts['image']) ? $atts['image'] === "on" : false;
			$sku = isset($atts['sku']) ? $atts['sku'] === "on" : false;
			$short_description = isset($atts['short_description']) ? $atts['short_description'] === "on" : false;
			$description_size = isset($atts['description_size']) ? $atts['description_size'] : '13px';
			$image_size = isset($atts['image_size']) ? explode('|', $atts['image_size']) : '';
			$image_width = isset( $image_size[0] ) ? $image_size[0] : '32';
			$image_height = isset( $image_size[1] ) ? $image_size[1] : '32';
			if($image){
				$replace_html .= '<?php $show_image = true;';
				$replace_html .= '$image_size = apply_filters("thwec_product_image_size", array('.$image_width.','. $image_height.')); ?>';
			}else{
				$replace_html .= '<?php $show_image = false; ?>';
			}
			if( $sku ){
				$replace_html .= '<?php $show_sku = apply_filters( "thwec_show_order_table_sku", '.$sku.', $item_id, $item, $order, $plain_text ); ?>';
			}else{
				$replace_html .= '<?php $show_sku = false; ?>';
			}
				$replace_html .= '
				<?php
				// Show title/image etc
				if ( $show_image ) {
					echo apply_filters( \'woocommerce_order_item_thumbnail\', \'<div class="thwecmf-order-item-img" style="margin-bottom: 5px"><img src="\' . ( $product->get_image_id() ? current( wp_get_attachment_image_src( $product->get_image_id(), \'thumbnail\' ) ) : wc_placeholder_img_src() ) . \'" alt="\' . esc_attr__( \'Product image\', \'woocommerce\' ) . \'" height="\' . esc_attr( $image_size[1] ) .\'" width="\' . esc_attr( $image_size[0] ) . \'" style="vertical-align:middle; margin-\' . ( is_rtl() ? \'left\' : \'right\' ) . \': 10px;" /></div>\', $item );
				}

				// Product name
				echo apply_filters( \'woocommerce_order_item_name\', $item->get_name(), $item, false );

				// SKU
				if ( $show_sku && is_object( $product ) && $product->get_sku() ) {
					echo \' (#\' . $product->get_sku() . \')\';
				} ?>';

				if( $short_description ){
 					$replace_html .= ' <?php if ( is_object( $product ) && $product->get_short_description() ) {
					echo \'<div class="thwec-short-description" style="font-size:'.$description_size.';">\'.$product->get_short_description().\'</div>\'; } ?>';
				}

				// allow other plugins to add additional product information here
				$replace_html .= '<?php do_action( \'woocommerce_order_item_meta_start\', $item_id, $item, $order, $plain_text );

				wc_display_item_meta( $item );

				// allow other plugins to add additional product information here
				do_action( \'woocommerce_order_item_meta_end\', $item_id, $item, $order, $plain_text );

			?>';
		}
		return $replace_html;
	}

	/**
	 * Remove unwanted styles from css style string
	 *
	 * @param  string $styles
	 * @return string $styles css style string
	 */
	public function remove_harmful_styles($styles){
		//Blind search and remove height auto style instead of overriding template
		$styles = str_replace('height: auto;', '', $styles);
		return $styles;
	}

}
endif;