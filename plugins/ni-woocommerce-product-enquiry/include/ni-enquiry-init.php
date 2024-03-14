<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}

if( !class_exists( 'ni_enquiry_init' ) ) :

require_once('ni-enquiry-function.php');
	
class ni_enquiry_init extends ni_enquiry_function{
	function __construct(){
		
		add_action('admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu',  array(&$this,'admin_menu' ));	
		add_action( 'wp_head', array(&$this,'wp_head'), 12.5 );
		add_action( 'wp_footer', array(&$this,'wp_footer'), 12.5 );
		add_action( 'ni_enquiry_form_data', array(&$this,'ni_enquiry_send_email'));
		add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ));
		
		
		
		add_action( 'wp_ajax_ni_enquiry_ajax_request',  array(&$this,'enquiry_ajax_request' ));
		add_action( 'wp_ajax_nopriv_ni_enquiry_ajax_request',  array(&$this,'enquiry_ajax_request' ));
		add_filter( 'plugin_row_meta',  array(&$this,'ni_enquiry_plugin_row_meta' ), 10, 2 );
		
		add_filter( 'admin_footer_text',  array(&$this,'admin_footer_text' ),101);
		
		$this->add_enquiry_setting_page();
		
		//add_filter( 'gettext', array($this, 'get_text'),20,3);
	}
	function get_text($translated_text, $text, $domain){
		if($domain == 'niwoope'){
			return '['.$translated_text.']';
		}		
		return $translated_text;
	}
	function admin_footer_text($text){
	
		$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : '';
		$admin_pages = $this->get_admin_pages();
		if (in_array($page,$admin_pages)){
				
				$text = sprintf( __( 'Thank you for using our plugins <a href="%s" target="_blank">naziinfotech</a>' ,'niwoope'), 
				__( 'http://naziinfotech.com/'  ,'niwoope') );
				$text = "<span id=\"footer-thankyou\">". $text ."</span>"	 ;
			
		 }
		return $text ; 
	}
	function get_admin_pages(){
		$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : '';
		$admin_pages = array();
		$admin_pages[] = 'ni-enquiry-dashboard';
		$admin_pages[] = 'ni-enquiry-addon';
		$admin_pages[] = 'ni-enquiry-setting';
		return $admin_pages;
	
	}
	function ni_enquiry_plugin_row_meta($links, $file){
		if ( $file == "ni-woocommerce-product-enquiry/ni-woocommerce-product-enquiry.php" ) {
				$row_meta = array(
				
				'ni_pro_version'=> '<a target="_blank" href="http://naziinfotech.com/product/ni-woocommerce-product-enquiry-pro">Buy Pro Version</a>',
				
				'ni_pro_review'=> '<a target="_blank" href="https://wordpress.org/plugins/ni-woocommerce-product-enquiry/#reviews">Write a Review</a>'	);
					
	
				return array_merge( $links, $row_meta );
			}
			return (array) $links;
	}
	function admin_enqueue_scripts(){
		$page = sanitize_text_field(isset($_REQUEST["page"])?$_REQUEST["page"]:'');
		
		if ($page =="ni-enquiry-dashboard" || $page =="ni-enquiry-addon" || $page == "ni-enquiry-setting"){
			
			
			/*Boostrap*/  
			wp_enqueue_script('niwoope-bootstrap-script', plugins_url( '../admin/js/bootstrap.js', __FILE__ ), array('jquery') );
			wp_enqueue_script('niwoope-popper-script', plugins_url( '../admin/js/popper.min.js', __FILE__ ), array('jquery') );
			wp_enqueue_style('niwoope-bootstrap-css',plugins_url( '../admin/css/bootstrap.min.css', __FILE__ ));	
			
			wp_enqueue_style('niwoope-style-css',plugins_url( '../admin/css/niwoope-style.css', __FILE__ ));	
			
			
		 }
		
	}
	function admin_init(){
	}
	function admin_menu(){
		add_menu_page(  __('Enquiry', 'niwoope')  ,  __( 'Enquiry', 'niwoope') ,'manage_options','ni-enquiry-dashboard',array(&$this,'add_page'),plugins_url( '../images/icon2.png', __FILE__ ),56.36);
    	add_submenu_page('ni-enquiry-dashboard', __( 'Dashboard', 'niwoope'), __(  'Dashboard', 'niwoope'), 'manage_options', 'ni-enquiry-dashboard' , array(&$this,'add_page'));
		add_submenu_page('ni-enquiry-dashboard', __(  'Add-ons', 'niwoope'),  __(  'Add-ons', 'niwoope'), 'manage_options', 'ni-enquiry-addon' , array(&$this,'add_page'));
	}
	function add_page(){
		if (isset($_REQUEST["page"])){
			$page = $_REQUEST["page"];
			if ($page=="ni-enquiry-dashboard"){
				include_once("ni-enquiry-dashboard.php");
				$obj =  new ni_enquiry_dashboard();
				$obj->init();
			}
			if ($page=="ni-enquiry-addon") {
				include_once("ni-addons.php");
				$obj =  new ni_enquiry_addons();
				$obj->page_init();
			}
		}
	}
	function wp_head(){
		//add_action('woocommerce_single_product_summary', array(&$this,'add_button'),40);
		if(is_product()) :
				
			global $post;
				
			$product = wc_get_product( $post->ID );
			  if ( $product->get_price() === '' ) {
            	add_action('woocommerce_product_meta_end', array(&$this,'add_button'));
			  } else {
					add_action('woocommerce_after_add_to_cart_form', array(&$this,'add_button'));	  
			  }
				 
			wp_enqueue_script('jquery-ui-dialog');
			//wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css'); 
			//wp_enqueue_script('jquery-ui-core');
			
			wp_enqueue_script( 'ni-enquiry', plugins_url( '../js/ni-enquiry.js', __FILE__ ) , array( 'jquery' ) );
			
			
			wp_enqueue_script( 'ni-enquiry-ajax-script', plugins_url( '../js/ni-enquiry-ajax-script.js', __FILE__ ) , array( 'jquery' ) );
			wp_localize_script( 'ni-enquiry-ajax-script', 'ni_enquiry_ajax_object', array( 'ni_enquiry_ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234, 'admin_url' => admin_url("admin.php"), 'admin_page'=>(isset($_REQUEST["page"]) ? $_REQUEST["page"] : '') ) );
			
			wp_register_style('ni-enquiry', plugins_url( '../css/ni-enquiry.css', __FILE__ ));
			wp_enqueue_style('ni-enquiry' );
			
			/*JQuery UI*/
			wp_register_style('in-jquery-ui', plugins_url( '../css/jquery-ui.css', __FILE__ ));
			wp_enqueue_style('in-jquery-ui' );
			
			/*Ni JQuery Popup*/
			wp_register_style('ni-popup-css', plugins_url( '../css/ni-popup.css', __FILE__ ));
			wp_enqueue_style('ni-popup-css' );
			
		endif;
	}
	function wp_footer(){
		if(is_product()) :
		
		global $product;
	   // $id = $product->id;
		?>
        <div class="popup ni-popup-content" data-popup="popup-1">
			<div class="popup-inner">
				<form name="frm_hd_ni_enquiry" id="frm_hd_ni_enquiry">
                <div class="ni_enquiry_message alert alert-danger"></div>
                <table class="ni_enquiry_table" cellpadding="0" cellspacing="0">
                	
                	<tr>
                    	<td class="ni_enquiry_text"><label for="ni_full_name"><?php _e(  'Full Name', 'niwoope') ?>:</label></td>
                        <td class="ni_enquiry_value"><input type="text" id="ni_full_name" name="ni_full_name" size="40"  /></td>
                    </tr>
                    <tr>
                    	<td class="ni_enquiry_text"><label for="ni_email_address"><?php _e(  'Email Address', 'niwoope') ?> :</label></td>
                        <td class="ni_enquiry_value"><input type="text" id="ni_email_address" name="ni_email_address" size="40" /></td>
                    </tr>
                     <tr>
                    	<td class="ni_enquiry_text"><label for="ni_contact_number"><?php _e( 'Contact Number', 'niwoope') ?> :</label> </td>
                        <td class="ni_enquiry_value"><input type="text" id="ni_contact_number"  name="ni_contact_number" size="40" /></td>
                    </tr>
                    <tr>
                    	<td class="ni_enquiry_text"><label for="ni_enquiry_description"><?php _e( 'Enquiry', 'niwoope') ?>:</label></td>
                        <td class="ni_enquiry_value"><textarea id="ni_enquiry_description" rows="5" cols="38" name="ni_enquiry_description"></textarea></td>
                    </tr>
                    <tr>
                    	<td><a data-popup-close="popup-1"  href="#"> <?php _e( 'Close', 'niwoope') ?>   </a></td>
                    	<td class="ni_normal_button"><button type="button" class="single_add_to_cart_button button alt btn_ni_send"><?php _e( 'Send', 'niwoope') ?> </button></td>
                    </tr>
                </table>
                 <input type="hidden" id="ni_ajax_url" name="ni_ajax_url" value="<?php echo admin_url( 'admin-ajax.php' ); ?>" />
          		 <input type="hidden" name="action" id="action" value="ni_enquiry_ajax_request" />
                 <input type="hidden" name="ni_product_id" id="ni_product_id" value="<?php echo $product->get_id() ?>" />
                 <input type="hidden" name="ni_variation_id" id="ni_variation_id" value="<?php //echo $product->get_id() ?>" />
              </form>
				<a class="popup-close" data-popup-close="popup-1" href="#">x</a>
			</div>
		</div>
        
        <?php 
		endif;
	}
	function add_button(){
		global $product;
		$product_id		= $product->get_id();
		$product_detail = wc_get_product( $product_id );
		
		$product_name 	= $product_detail->get_title();
			$product_url 	= $product_detail->get_permalink();
		
			
		$enquiry_option = get_option( 'ni_enquiry_option' );
		$enquiry_button_text =  isset($enquiry_option['ni_enquiry_button_text'])?$enquiry_option['ni_enquiry_button_text']:'';
		$whatsapp_button_text =  isset($enquiry_option['ni_whatsapp_button_text'])?$enquiry_option['ni_whatsapp_button_text']:'';
		
		$enable_whatsapp_enquiry =  isset($enquiry_option['enable_whatsapp_enquiry'])?$enquiry_option['enable_whatsapp_enquiry']:'';
		
		$ni_whatsapp_no =  isset($enquiry_option['ni_whatsapp_no'])?$enquiry_option['ni_whatsapp_no']:'';
		
		
		if (strlen($enquiry_button_text)==0){
			$enquiry_button_text = 'Enquiry';
		}
		if (strlen($whatsapp_button_text)==0){
			$whatsapp_button_text = 'Whatsapp Me!';
		}
		
		$whatsapp_message = '';
		$whatsapp_message .='';
		$whatsapp_message .= 'Product Name: '. urlencode($product_name);
		$whatsapp_message .= '%0D%0A';
		$whatsapp_message .= 'Product URL: '. urlencode($product_url);
		$whatsapp_message .= '%0D%0A';
		$whatsapp_link = 'https://wa.me/'.$ni_whatsapp_no.'?text='.$whatsapp_message.'';
		
		?>
        <div id="ni_enquiry" class="woocommerce">
        
            <input type="button"  data-popup-open="popup-1" class="single_add_to_cart_button button alt" id="btn_ni_enquiry"  value="<?php echo $enquiry_button_text ; ?>" />
            <?php if ($enable_whatsapp_enquiry  == 1): ?>
            <a href="<?php echo $whatsapp_link; ?>" target="_blank"><button type="button" class="single_add_to_cart_button button alt"><?php echo $whatsapp_button_text;  ?></button></a>
            <?php endif; ?>
        </div>
        <?php
	}
	function enquiry_ajax_request(){
		do_action("ni_enquiry_form_data",$_REQUEST);
		die;
	}
	function ni_enquiry_send_email($request){
		$data 			= array();
		$enquiry_option = get_option( 'ni_enquiry_option' );
		
		$to_email 			= $enquiry_option['ni_to_email'];
		$subject_line 		= $enquiry_option['ni_subject_line'];
		
		$subject_line  		= $subject_line ." | ".  date_i18n('Y-m-d h:i:s') ;
		
		$add_cc_email 		= $enquiry_option['add_cc_email'];
		
		$ni_from_email		= $enquiry_option['ni_from_email'];
		$enquiry_from_name		= $enquiry_option['enquiry_from_name'];
		
		$thank_you_message 	= $enquiry_option['ni_thank_you_message'];
		if ($thank_you_message ==""){
			$thank_you_message = "Thank you message for contact with us.";
		}
		$email_to_customer 	= isset($enquiry_option['enable_email_to_customer'])?'yes':'no';
		
		
		 $html  		= "";
		 $product_name  = "";
		 $category  	= "";
		 $price  		= "";
		 $sku  			= "";
		 
		 $product_id 	= $request["ni_product_id"];
		 //echo json_encode($request);
		// die;
		 $product_info  = $this->get_product_info( $product_id);
		 $category      = $this->get_product_category_by_id($product_id);
		 $product_name 	= get_the_title($product_id);
		 
		 $post_id =  ($request['ni_variation_id'] == 0) ? $request['ni_product_id'] :$request['ni_variation_id'];
		
		 $product_sku = get_post_meta($post_id, '_sku', true );
		 
		 if (empty($product_sku )){
		  	$product_sku = get_post_meta($request['ni_product_id'], '_sku', true );
		 }
		 
		 $html .= "<div style=\"overflow-x:auto;\">";
		 
		 $html .= "<table  style=\"width:75%; border:1px solid #00838f; border-collapse: collapse; margin: 0 auto;\" cellpadding=\"0\" cellspacing=\"0\" >";
		 $html .= 		apply_filters('ni_woocommerce_product_enquiry_email_top','',$request,$enquiry_option);
		 $html .= "		<tr>";
		 $html .= "			<td colspan=\"2\"  style=\"background:#0097A7;color:#FFFFFF; height:150; padding:15px;font-size:18;font-weight:bold\" >Customer Information</td>";
		 $html .= "		</tr>";
		 $html .= "		<tr>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f; width:200px\" >Full Name</td>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;\" >".$request["ni_full_name"]."</td>";
		 $html .= "		</tr>";
		 $html .= "		<tr>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;width:200px\" >Email Address</td>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;\" >".$request["ni_email_address"]."</td>";
		 $html .= "		</tr>";
		 
		 $html .= "		<tr>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;width:200px\" >Contact Number</td>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;\" >".$request["ni_contact_number"]."</td>";
		 $html .= "		</tr>";
		 
		 $html .= "		<tr>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;width:200px\" >Enquiry</td>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;\" >".$request["ni_enquiry_description"]."</td>";
		 $html .= "		</tr>";
		
		 $html .= "		<tr>";
		 $html .= "			<td colspan=\"2\" style=\"background:#0097A7;color:#FFFFFF; height:150; padding:15px;font-size:18;font-weight:bold\">Product Information</td>";
		 $html .= "		</tr>";
		
		 $html .= 		apply_filters('ni_woocommerce_product_enquiry_email_after_product_information','',$request,$enquiry_option); 
		 
		 $html .= "		<tr>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;width:200px\" >Product Name</td>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;\" >{$product_name}</td>";
		 $html .= "		</tr>";
		 $html .= "		<tr>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;width:200px\" >Category</td>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;\" >{$category}</td>";
		 $html .= "		</tr>";
		 $html .= "		<tr>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;width:200px\" >Price</td>";
		 $html .= "			<td style=\"padding:10px;border-bottom: 1px solid #00838f;\" >".  wc_price(isset ($product_info["price"])?$product_info["price"]:0) ."</td>";
		 $html .= "		</tr>";
		 $html .= "		<tr>";
		 $html .= "			<td style=\"padding:10px;width:200px\" >SKU</td>";
		 $html .= "			<td style=\"padding:10px;\" >".$product_sku."</td>";
		 $html .= "		</tr>";		 
		 $html .= 		apply_filters('ni_woocommerce_product_enquiry_email_bottom','',$request,$enquiry_option);
		 $html .= "</table>";
		 $html .= "</div>";
		 
		
		$headers =  array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		if ($enquiry_from_name ==""){
			$enquiry_from_name = "";
		}
		if ($ni_from_email)
		$headers[] = 'From: '.$enquiry_from_name.' <' .$ni_from_email. '>';
		if ($add_cc_email)
		$headers[] = 'Cc: <' .$add_cc_email. '>';
		//enquiry_from_name
		
		 $status = wp_mail($to_email, $subject_line, $html, $headers);
		if ($email_to_customer =="yes"){
			 $status2 = wp_mail($request["ni_email_address"], $subject_line, $html, $headers);
		}
		
		 if ($status){
			 $status = "SUCCESS";
		 }else{
		  	$status = "FAIL";
		 }
		 $data["status"] = $status ;
		 $data["message"] = $thank_you_message  ;
		
		 
		 /*Set Count*/
		 $this->set_enquiry_count();
		 echo  json_encode( $data);
		
	}
	function add_enquiry_setting_page()
	{
		include_once("ni-enquiry-setting.php");	
		$obj = new ni_enquiry_setting();
	}
}

endif;
?>