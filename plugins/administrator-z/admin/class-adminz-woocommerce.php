<?php 
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_Flatsome;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Cart;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Checkout;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Navigation;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Ordering;
use Adminz\Inc\Widget\ADMINZ_Inc_Widget_Filter_Product_Taxonomy;
use Adminz\Helper\ADMINZ_Helper_Language;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Taxonomy; 
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Query; 
use Adminz\Helper\ADMINZ_Helper_Category;
use Adminz\Helper\ADMINZ_Helper_Product_Variation;
use Adminz\Helper\ADMINZ_Helper_ACF_THX;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Gallery;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Tooltip;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Message_Notice;


class ADMINZ_Woocommerce extends Adminz {	
	public $options_group = "adminz_woocommerce";
	public $title = "Woocommerce";
	static $slug = 'adminz_woocommerce';
	static $options;
	static $action_hooks = [];
	static $get_arr_meta_key= [];

	function __construct() {
		if(!class_exists( 'WooCommerce' ) ) return;
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action( 'adminz_tabs_html',[$this,'tab_html']);
		
		add_action(	'admin_init', [$this,'register_option_setting'] );
		add_action( 'init', array( $this, 'add_shortcodes') );		
		
		$this::$options = get_option('adminz_woocommerce', []);
		$this::$action_hooks = require_once(ADMINZ_DIR.'inc/file/woocommerce_hooks.php');

		/*For Form search shortcode*/
		if($this->is_flatsome()){
			// For price slider form
			add_action( 'wp_enqueue_scripts', function(){
				wp_enqueue_script( 'wc-price-slider' );
			});

			// For select 2 
			add_action( 'wp_enqueue_scripts', function(){
				wp_enqueue_style( 'adminz-fl-woo-form',plugin_dir_url(ADMINZ_BASENAME).'assets/css/shortcode/flatsome-woocommerce-form.css', array(), '1.0', 'all' );
				wp_enqueue_style( 'adminz-fl-woo-widget',plugin_dir_url(ADMINZ_BASENAME).'assets/css/shortcode/flatsome-woocommerce-widget.css', array(), '1.0', 'all' );     
			});
			
			// Nếu bật select 2 thì  mới enqueue select 2
			// var_dump($this->check_option('enable_select2',false,"on"));
			// die;
			if($this->check_option('enable_select2',false,"on")){
				add_action( 'wp_enqueue_scripts', function(){
					wp_enqueue_style('select2');
					wp_enqueue_script('select2');
				});
			}

			// luôn luôn enqueue scrippt của form, và sử dụng biến để check
			add_action( 'wp_enqueue_scripts', function(){				
				wp_enqueue_script( 
					'adminz_woo_form', 
					plugin_dir_url(ADMINZ_BASENAME) . 'assets/js/adminz_woo_form.js', 
					['jquery','selectWoo']
				);
				wp_localize_script( 
					'adminz_woo_form', 
					'adminz_woo_form_data',  
					[
						'is_select2'=>$this->check_option('enable_select2',false,"on"),
						'enable_select2_multiple_hide_child'=>$this->check_option('enable_select2_multiple_hide_child',false,"on"),
						'text_search'=> __("Search")
					]
				);
				if($this->check_option('enable_select2_multiple_hide_child',false,"on")){
					wp_enqueue_style( 'enable_select2_multiple_hide_child', plugin_dir_url(ADMINZ_BASENAME) . 'assets/css/shortcode/css-select2-multiple-hide-child.css' , [], false, 'all' );
				}
				if($this->check_option('enable_select2_css',false,"on")){
					wp_enqueue_style( 'adminz_css_select2', plugin_dir_url(ADMINZ_BASENAME) . 'assets/css/shortcode/css-select2.css' , [], false, 'all' );
				}
			});
		}
		
		$this->woocommerce_fire_action_hooks();
		$this->woocommerce_filter_hooks();
		$this->woocommerce_config();

		new ADMINZ_Inc_Widget_Filter_Product_Taxonomy;
		new ADMINZ_Helper_ACF_THX();
	}
	function register_tab($tabs) {
 		if(!$this->title) return; 		
 		$this->title = $this->get_icon_html('woocommerce').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            'type' => '1'
        );
        return $tabs;
    }
	function add_shortcodes(){
		$shortcodefiles = glob(ADMINZ_DIR.'shortcodes/woocommerce*.php');
		if(!empty($shortcodefiles)){
			foreach ($shortcodefiles as $file) {
				require_once $file;
			}
		}
	}
	function woocommerce_config(){

		new ADMINZ_Helper_Woocommerce_Query;
		new ADMINZ_Helper_Woocommerce_Cart($this);
		new ADMINZ_Helper_Woocommerce_Tooltip($this);
		new ADMINZ_Helper_Woocommerce_Message_Notice($this);
		
		
		if($this->check_option('adminz_woocommerce_simple_checkout_field',false,"on")){		
			new ADMINZ_Helper_Woocommerce_Checkout;
		}
		if($this->check_option('adminz_woocommerce_fix_gallery_image_size',false,"on")){	
			new ADMINZ_Helper_Woocommerce_Gallery;
		}
		$empty_price_html = ADMINZ_Helper_Language::get_pll_string('adminz_woocommerce[adminz_woocommerce_empty_price_html]');
		if($empty_price_html){
			add_filter('woocommerce_empty_price_html', function() use ($empty_price_html){
				return do_shortcode($empty_price_html);
			});
		}
		if($this->check_option('variable_product_price_custom',false,"on")){				
			new ADMINZ_Helper_Product_Variation;
		}
		
		if($this->check_option('enable_product_cat_tinymce',false,"on")){		
			add_filter('product_cat_edit_form_fields', function ($tag) {
			    ?>
			    
			        <tr class="form-field">
			            <th scope="row" valign="top"><label for="description"><?php _e('Description'); ?></label></th>
			            <td>
			                <?php  
			                $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' ); 
			                wp_editor(html_entity_decode($tag->description , ENT_QUOTES, 'UTF-8'), 'description1', $settings); ?>   
			                <br />
			                <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
			            </td>   
			        </tr>         
			    
			    <?php
			});	
			new ADMINZ_Helper_Category;
		}

		if($this->check_option('navigation_auto_fill',false,"on")){
			new ADMINZ_Helper_Woocommerce_Navigation;
		}
		if($this->check_option('ordering')){			
			new ADMINZ_Helper_Woocommerce_Ordering($this->get_option_value('ordering'));
		}

		if($this->get_option_value('adminz_woocommerce_from_currency_formatting')){
			$from_currency = explode(",",$this->get_option_value('adminz_woocommerce_from_currency_formatting'));
			$to_currency = explode(",",$this->get_option_value('adminz_woocommerce_to_currency_formatting'));
			add_filter('woocommerce_currency_symbol', function ( $currency_symbol, $currency ) use ($from_currency, $to_currency) {
				if(!empty($from_currency) and is_array($from_currency)){
					foreach ($from_currency as $k=> $from) {
						if($currency == $from) {
					 		$currency_symbol = " ".$to_currency[$k]." ";
					 	}
					}
				}			 	
			 	return $currency_symbol;
			}, 10, 2);
		}
		
		if($this->is_flatsome()){
			add_filter( 'flatsome_product_labels', function( $text, $post, $_product, $badge_style ) {
			    if($_product->is_featured()){
			        $bubble_text = $this->get_option_value('adminz_woocommerce_featured_text');
			        if($bubble_text) {
			        	$text .= '
				        <div class="badge callout badge-' . $badge_style . '">
				            <div class="badge-inner callout-new-bg is-small new-bubble">
				                ' . $bubble_text . '
				            </div>
				        </div>
				        ';
			        }			        
			    }   

			    return $text;
			}, 10, 4 );
		}
	}
	function woocommerce_fire_action_hooks(){
		$adminz_woocommerce_action_hook = $this->get_option_value('adminz_woocommerce_action_hook');
		if(!empty($adminz_woocommerce_action_hook) and is_array($adminz_woocommerce_action_hook)){			
			foreach ($adminz_woocommerce_action_hook as $action => $shortcode) {
				if($shortcode){
					add_action($action,function() use ($shortcode){
						echo do_shortcode(html_entity_decode($shortcode));
					});
				}				
			}
		}
		add_action('init',function(){
			if(
				$this->check_option('adminz_woocommerce_test_all_hook') or 
				(isset($_GET['testhook']) and $_GET['testhook'] =='woocommerce')
			){
				// if(!is_admin()){
					foreach (self::$action_hooks as $action) {
						add_action($action, function() use ($action){
							echo do_shortcode('[adminz_test content="'.$action.'"]');
						});
					}
				// }
			}
		});

		$woo_hook_data = json_decode($this->get_option_value('adminz_woocoommerce_custom_hook'));
		if(!empty($woo_hook_data) and is_array($woo_hook_data)){
			foreach ($woo_hook_data as $value) {
				$value[2] = $value[2]? $value[2] : 0;
				add_action($value[1],function() use ($value){					
					$condition = true;
					if(!empty($value[3])){
						$condition = call_user_func($value[3]);
					}
					if($condition){
						echo html_entity_decode(do_shortcode($value[0])); 
					}
				},$value[2]);
				
			}
		}
		
	}
	function woocommerce_filter_hooks(){		
		if($this->check_option('adminz_woocommerce_description_readmore',false,"on")){			
			add_action( 'woocommerce_before_single_product', function () {				
			    ob_start();
				?>				
				<style type="text/css">	
									
					#tab-description:not(.toggled){
						max-height: 70vh;    		
			    		overflow: hidden;
					}
					#tab-description{
						position: relative;
			    		padding-bottom: 50px;
					}
					#tab-description::after
					,#tab-description .adminz_readmore_description{
						content:  "";
						position: absolute;
			    		bottom: 0px;    		
			    		text-align: center;
			    		width: 100%;
			    		left: 0px;
			    		padding-top: 90px;
					}
					#tab-description .adminz_readmore_description{
						z-index: 1;
					}
					#tab-description:not(.toggled)::after					{
						background-image: -webkit-linear-gradient(bottom, white 40%, transparent 100%);    		
					}
					#main.dark #tab-description::after{
			    		background-image: -webkit-linear-gradient(bottom, #333 40%, transparent 100%);
			    	}
			    	<?php $content_bg = get_theme_mod('content_bg'); if($content_bg){?>
			    		#tab-description:not(.toggled)::after{
				    		background-image: -webkit-linear-gradient(bottom, <?php echo esc_attr($content_bg);?> 40%, transparent 100%) !important;
				    	}
					<?php }?>
			    	#tab-description .adminz_readmore_description .button{
			    		margin: 0;
			    	}
				</style>
				<script type="text/javascript">
					window.addEventListener('DOMContentLoaded', function() {
						(function($){
							var adminz_readmore_description = $('<div class="adminz_readmore_description"><div class="button white"><i class="icon-angle-down"></i>'+'<?php echo __("Read more...") ;?>'+'</div></div>');
							$("#tab-description").append(adminz_readmore_description);
							$(document).on('click','.adminz_readmore_description .button',function(){
								jQuery(this).find("i").toggleClass('icon-angle-down');
								jQuery(this).find("i").toggleClass('icon-angle-up');
								jQuery(this).closest('#tab-description').toggleClass('toggled');
							});
						})(jQuery);
					});
				</script>
				<?php
				echo apply_filters('adminz_output_debug',ob_get_clean());
			});

		}
	}
	function tab_html(){
		if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
		?>
		<form method="post" action="options.php">
			<?php 
			settings_fields($this->options_group);
	        do_settings_sections($this->options_group);
			?>
			<table class="form-table">				
				<tr valign="top">
					<th scope="row">
						<h3>Single Product</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Empty price html
					</th>
					<td>
						<label>
							<textarea cols="70" rows="1" type="text" name="adminz_woocommerce[adminz_woocommerce_empty_price_html]"><?php echo esc_attr($this->get_option_value('adminz_woocommerce_empty_price_html')); ?></textarea> Leave blank for not use
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Fix Gallery image size
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_fix_gallery_image_size]" <?php if($this->get_option_value('adminz_woocommerce_fix_gallery_image_size') =="on") echo "checked"; ?>> Enable
							<div>
							<small>| Note 1*: Only images that are larger than woocommerce's settings are effective</small></br>
							<small>| Note 2*: If this function is enabled <strong>after</strong> uploading the thumbnail: Require use <code>Regenerate Thumbnails</code> to reset thumbnails size after change </small></br> </div>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Collapse description and create Readmore button
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_description_readmore]" <?php if($this->get_option_value('adminz_woocommerce_description_readmore') =="on") echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<h3>Products List </h3>
					</th>
				</tr>
				<?php if($this->is_flatsome()){ ?>
				<tr valign="top">
					<th scope="row">
						Products Featured text
					</th>
					<td>
						<label>
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_featured_text]" value="<?php echo esc_attr($this->get_option_value('adminz_woocommerce_featured_text')); ?>">							
						</label>
					</td>
				</tr>
				<?php } ?>
				<tr valign="top">
					<th scope="row">
						List Ordering
					</th>
					<td>						
						<label>				
							<input type="checkbox" name="adminz_woocommerce[ordering][remove_default]" <?php if($this->get_option_value('ordering','remove_default') =="on") echo "checked"; ?>> 
							Remove <?php echo __('Default sorting','administrator-z'); ?>
							</br>
						</label>	
						<label>				
							<input type="checkbox" name="adminz_woocommerce[ordering][remove_popular]" <?php if($this->get_option_value('ordering','remove_popular') =="on") echo "checked"; ?>> 
							Remove <?php echo __('Sort by popularity','administrator-z'); ?>
							</br>
						</label>
						<label>
							<input type="checkbox" name="adminz_woocommerce[ordering][remove_rate]" <?php if($this->get_option_value('ordering','remove_rate') =="on") echo "checked"; ?>> 
							Remove <?php echo __('Sort by average rating','administrator-z'); ?>
							</br>
						</label>
						<label>
							<input type="checkbox" name="adminz_woocommerce[ordering][remove_date]" <?php if($this->get_option_value('ordering','remove_date') =="on") echo "checked"; ?>> 
							Remove <?php echo __('Sort by latest','administrator-z'); ?>
							</br>
						</label>
						<label>
							<input type="checkbox" name="adminz_woocommerce[ordering][remove_price]" <?php if($this->get_option_value('ordering','remove_price') =="on") echo "checked"; ?>> 
							Remove <?php echo __('Sort by price: low to high','administrator-z'); ?>
							</br>
						</label>
						<label>
							<input type="checkbox" name="adminz_woocommerce[ordering][remove_price_desc]" <?php if($this->get_option_value('ordering','remove_price_desc') =="on") echo "checked"; ?>> 
							Remove <?php echo __('Sort by price: high to low','administrator-z'); ?>
							</br>
						</label>
						<label>
							<input type="checkbox" name="adminz_woocommerce[ordering][percent_amount]" <?php if($this->get_option_value('ordering','percent_amount') =="on") echo "checked"; ?>> 
							Enable <?php echo __("Percentage discount",'administrator-z'); ?>	| *Note: Re-save all product for apply new value
							</br>	
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<h3>Products Category </h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Description tiny mce editor
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[enable_product_cat_tinymce]" <?php if($this->get_option_value('enable_product_cat_tinymce') =="on") echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<?php if($this->is_flatsome()){ ?>
				<tr valign="top">
					<th scope="row">
						Navigation Auto Fill
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[navigation_auto_fill]" <?php if($this->get_option_value('navigation_auto_fill') =="on") echo "checked"; ?>> Enable this function</br>
							<button class="button" onclick="jQuery('#adminz_navigtion_woo').toggle(); return false;">Show guid</button>
							<div id="adminz_navigtion_woo" style="display: none;">
								<p>* How to use: Type code into CSS classes input of Navigation Items class</p> 
								<p>Get products: <code>adminz_product</code> Fill Products as child of Navigation item</p>
								<p>Get categories: <code>adminz_product_category</code></p>
								<p>Get categories: <code>adminz_product_category_replace</code> Replace mode </p>
								<p>By parent: <code>parent_{term_id}</code> For get only children or your term_id. Ex: parent_57</p>
							</div>
						</label>
					</td>
				</tr>
				<?php } ?>
				<tr valign="top">
					<th scope="row">
						<h3>Price</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Hide max price in Variation product
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[variable_product_price_custom]" <?php if($this->get_option_value('variable_product_price_custom') =="on") echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<?php if($this->is_flatsome()){ ?>
				<tr valign="top">
					<th scope="row">
						Tooltip box on hover 
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_tooltip_products]" <?php if($this->get_option_value('adminz_tooltip_products') =="on") echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<?php } ?>

				<tr valign="top">
					<th scope="row">
						<h3>Add to cart</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Add to cart text
					</th>
					<td>
						<label>
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_text]" value="<?php echo esc_attr($this->get_option_value('adminz_woocommerce_ajax_add_to_cart_text')); ?>">							
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Remove add to cart button
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_remove_add_to_cart_button]" <?php if($this->get_option_value('adminz_woocommerce_remove_add_to_cart_button')) echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Redirect to checkout
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_redirect_checkout]" <?php if($this->get_option_value('adminz_woocommerce_ajax_add_to_cart_redirect_checkout') =="on") echo "checked"; ?>> Enable
							<?php 
							$current_setting = $this->get_option_value('woocommerce_enable_ajax_add_to_cart');
							if($current_setting == "yes"){
								echo "<mark> You need disable Ajax in Woocommerce setting for this option.</mark>";
							}
							?>							
						</label>
					</td>
				</tr>	
				<tr valign="top">
					<th scope="row">
						Ajax in single product
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_single_product]" <?php if($this->get_option_value('adminz_woocommerce_ajax_add_to_cart_single_product') =="on") echo "checked"; ?>> Enable
							<?php 
							$current_setting = $this->get_option_value('woocommerce_enable_ajax_add_to_cart');
							if($current_setting == "no"){
								echo "<mark> You need enable Ajax in Woocommerce setting for this option.</mark>";
							}
							?>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Buy now redirect checkout
					</th>
					<td>
						<label>							
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_add_buy_now_text]" value="<?php echo esc_attr($this->get_option_value('adminz_woocommerce_add_buy_now_text')); ?>">
						</label>
					</td>
				</tr>				
				<tr valign="top">
					<th scope="row">
						Buy now position
					</th>
					<td>
						<label>							
							<select name="adminz_woocommerce[adminz_woocommerce_add_buy_now_hook]">
								<?php 
								$hooklist = [
									'woocommerce_single_product_summary', 
									'woocommerce_before_add_to_cart_form', // 
									'woocommerce_before_variations_form', //
									'woocommerce_before_add_to_cart_button', 
									'woocommerce_before_single_variation', 
									'woocommerce_single_variation', 
									'woocommerce_before_add_to_cart_quantity', 
									'woocommerce_after_add_to_cart_quantity', 
									'woocommerce_after_single_variation', 
									'woocommerce_after_add_to_cart_button', 
									'woocommerce_after_variations_form', 
									'woocommerce_after_add_to_cart_form',
								 	'woocommerce_product_meta_start', 
									'woocommerce_product_meta_end', 
									'woocommerce_share'
								];
								$current = $this->get_option_value( 'adminz_woocommerce_add_buy_now_hook' );
								foreach ($hooklist as $value) {									
									if($value == $current) {$selected = "selected";} else{ $selected = "";}
									echo '<option '.esc_attr($selected).' value="'.esc_attr($value).'">'.esc_attr($value).'</option>';
								}
								 ?>
							</select>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Remove quanity field
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_remove_quanity]" <?php if($this->get_option_value('adminz_woocommerce_remove_quanity') =="on") echo "checked"; ?>> Enable
						</label>
					</td>					
				</tr>
			</table>
			<?php submit_button(); ?>
			<table class="form-table">		
				<tr valign="top">
					<th scope="row">
						<h3>Checkout</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Simple checkout field
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_simple_checkout_field]" <?php if($this->get_option_value('adminz_woocommerce_simple_checkout_field') == "on") echo 'checked'; ?>>
							<?php 
							if(empty(WC()->payment_gateways->get_available_payment_gateways())){
								echo '<mark>Next, you need to set at least 1 payment method for the ordering function to work.</mark></br>';
							};
							?>
							If have error shipping address. you need set free ship <a href="<?php echo admin_url(); ?>admin.php?page=wc-settings&tab=shipping&zone_id=0">here</a>
							
					 	</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Widget
					</th>
					<td>	
						<button class="button" onclick="jQuery('#adminz_widget_guid').toggle(); return false;">Show guid</button>
						<div class="hidden" id="adminz_widget_guid">
							<p>Widget: Find widget name as: <b> <?php echo __("Filter products",'administrator-z'). " - NEW"; ?></b></p>
							<p>Use widget in your shop page | Product search results page </p>
							<h4>* How to sync custom Attribute with global attribute</h4>
							<p>try with <a href="https://wordpress.org/support/topic/plugin-for-bulk-converting-custom-attributes-to-global/">this guid</a></p>
							<p>1. Change admin language to English</p>
							<p>2. Export product csv file and upload/ edit in google sheet</p>
							<p>3. Use gg sheet filter function to get all term of custom attribute</p>
							<p>4. Go do admin dashboard -> create global attribute/ term with the corresponding name</p>
							<p>5. Go to gg sheet, Search and replace 0 to 1 in collumn name like <code>Attribute global</code></p>
							<p>6. Export .csv from google sheet</p>
							<p>7. Import into woocommerce and done!</p>
							<p><a href="https://wordpress.org/plugins/updraftplus/">Backup plugin</a></p>
						</div>		
					</td>
				</tr>
				<!-- <tr valign="top">
					<th scope="row">
						Price filter Milestones
					</th>
					<td>
						<table>
							<tr>
								<td style="width: 50%;">
									<p>Values</p>
									<textarea cols=70 rows=4 name="adminz_woocommerce[filter_price_values]" placeholder="0- 5000&#10;5000- 8000&#10;8000- 10000&#10;10000"><?php echo esc_attr($this->get_option_value('filter_price_values')); ?></textarea> 									
								</td>
								<td style="width: 50%;">
									<p>Display</p>
									<textarea cols=70 rows=4 name="adminz_woocommerce[filter_price_display]" placeholder="<5 thousands&#10;5 thousands - 8 thousands&#10;8 thousands - 10 thousands&#10;> 10 thousands"><?php echo esc_attr($this->get_option_value('filter_price_display')); ?></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr> -->
				<tr valign="top">
					<th scope="row">
						<h3>Currency </h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Change currency formatting
					</th>
					<td>
						<p>
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_from_currency_formatting]" value="<?php echo esc_attr($this->get_option_value('adminz_woocommerce_from_currency_formatting')); ?>" placeholder="VND,USD">  Currencies key
						</p>
						<p>
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_to_currency_formatting]" value="<?php echo esc_attr($this->get_option_value('adminz_woocommerce_to_currency_formatting')); ?>" placeholder="Vnđ,Dollars"> Currencies formatings.
						</p>
						<p>
							<button class="button" onclick="jQuery('#adminz_change_currenct_formating').toggle(); return false;">Show all currency</button>
						</p>						
						<div class="hidden" id="adminz_change_currenct_formating"><pre><?php print_r(get_woocommerce_currencies()); ?></pre></div>

					</td>
				</tr>
				<tr valign="top">
	        		<th><h3>Tỉnh huyện xã</h3></th>
	        		<td></td>
	        	</tr>
	        	<?php if(get_locale() == 'vi' and function_exists('get_field')){ ?>
		        	<tr valign="top">	        		
		        		<th>
		        			Bật chức năng tỉnh huyện xã
		        		</th>
		        		<td>
		        				
		        			<input type="checkbox" <?php echo $this->check_option('enable_acf_thx',false,"on") ? 'checked' : ''; ?>  name="adminz_woocommerce[enable_acf_thx]"/>
        					<small>Bật: Tạo 1 metabox - ACF tỉnh huyện xã trong edit product, Form search và widget </small>
        					</br>
        					<input type="checkbox" <?php echo $this->check_option('enable_acf_thx_disable_data',false,"on") ? 'checked' : ''; ?>  name="adminz_woocommerce[enable_acf_thx_disable_data]"/>
        					<small>Bật: Sử dụng dữ liệu tuỳ chọn</small>
        					</br>
        					<input type="checkbox" <?php echo $this->check_option('enable_acf_thx_checkout_field',false,"on") ? 'checked' : ''; ?>  name="adminz_woocommerce[enable_acf_thx_checkout_field]"/>
        					<small>Bật: Field trong form checkout</small>
		        		</td>
		        	</tr>
	        	<?php } ?>
	        	<?php if($this->is_flatsome()){ ?>
	        		<tr valign="top">
		        		<th><h3>Form Search shortcode</h3></th>
		        		<td></td>
		        	</tr>
		        	<tr valign="top">	        		
		        		<th>
		        			Select tag
		        		</th>
		        		<td>
		        				
		        			<input type="checkbox" <?php echo $this->check_option('enable_select2',false,"on") ? 'checked' : ''; ?>  name="adminz_woocommerce[enable_select2]"/>
        					<small>Bật: Use select2.js </small>
        					</br>
        					<input type="checkbox" <?php echo $this->check_option('enable_select2_multiple',false,"on") ? 'checked' : ''; ?>  name="adminz_woocommerce[enable_select2_multiple]"/>
        					<small>Bật: Use select2 with multiple </small>
        					</br>
        					<input type="checkbox" <?php echo $this->check_option('enable_select2_multiple_hide_child',false,"on") ? 'checked' : ''; ?>  name="adminz_woocommerce[enable_select2_multiple_hide_child]"/>
        					<small>Bật: Use select2 with multiple hide children  </small>
        					</br>
        					<input type="checkbox" <?php echo $this->check_option('enable_select2_css',false,"on") ? 'checked' : ''; ?>  name="adminz_woocommerce[enable_select2_css]"/>
        					<small>Bật: Sử dụng style có sẵn cho select2  </small>
		        		</td>
		        	</tr>
	        	<?php } ?>
	        	<tr valign="top">
	        		<th><h3>Message notice</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">
					<th scope="row">
						Message Notice position
					</th>
					<td>
						<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_fix_notice_position]" <?php if($this->get_option_value('adminz_woocommerce_fix_notice_position') == "on") echo "checked"; ?>> Fix Message Notice position
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
			<table class="form-table">	
				<tr valign="top">
					<th scope="row">
						<h3>Template hook</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						List action hooks						
					</th>
					<td>
						<p>type <code>[adminz_test]</code> to test</p>
						<?php 
			        	$adminz_woocommerce_action_hook = $this->get_option_value('adminz_woocommerce_action_hook');			        	
			        	foreach (self::$action_hooks as $key => $value) {
			        		?>
			        		<div>
			        			<textarea cols="70" rows="1" name="adminz_woocommerce[adminz_woocommerce_action_hook][<?php echo esc_attr($value);?>]"><?php echo isset($adminz_woocommerce_action_hook[$value]) ? esc_attr($adminz_woocommerce_action_hook[$value]) : "";?></textarea><small><?php echo esc_attr($value); ?></small>
			        		</div>
			        		<?php
			        	}
		        	 	?>
					<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_test_all_hook]" <?php if($this->get_option_value('adminz_woocommerce_test_all_hook') == "on") echo "checked"; ?>><em>Test all hook</em>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Custom action hooks						
					</th>
					<td>
						<p>type <code>[adminz_test]</code> to test</p>		
						<?php $woo_hook_data = $this->get_option_value('adminz_woocoommerce_custom_hook'); ?>
						<textarea style="display: none;" cols="70" rows="10" name="adminz_woocommerce[adminz_woocoommerce_custom_hook]"><?php echo esc_attr($woo_hook_data); ?></textarea> </br>
						<div>
							<textarea cols="40" rows="1" disabled>Shortcode</textarea> 
							<textarea cols="40" rows="1" disabled>Action hook</textarea> 
							<textarea cols="20" rows="1" disabled>Priority</textarea>
							<textarea cols="20" rows="1" disabled>Conditional</textarea>
						</div>
						<div class="adminz_woocoommerce_custom_hook">
							<?php 
							$woo_hook_data = json_decode($woo_hook_data);							
							if(!empty($woo_hook_data) and is_array($woo_hook_data)){
								foreach ($woo_hook_data as $key => $value) {
									$value[0] = isset($value[0])? $value[0] : "";
									$value[1] = isset($value[1])? $value[1] : "";
									$value[2] = isset($value[2])? $value[2] : "";
									$value[3] = isset($value[3])? $value[3] : "";
									echo '<div class="item" style="margin-bottom: 5px;">
										<textarea cols="40" rows="1" name="" placeholder="[your shortcode]">'.esc_attr($value[0]).'</textarea>
										<textarea cols="40" rows="1" name="" placeholder="your action hook">'.esc_attr($value[1]).'</textarea>
										<textarea cols="20" rows="1" name="" placeholder="your priority">'.esc_attr($value[2]).'</textarea>
										<textarea cols="20" rows="1" name="" placeholder="your conditional">'.esc_attr($value[3]).'</textarea>
										<button class="button adminz_woocoommerce_custom_hook_remove" >Remove</button>
									</div>';
								}
							}
							?>							
						</div>
						<button class="button" id="adminz_woocoommerce_custom_hook_add">Add new</button>
						<script type="text/javascript">
							window.addEventListener('DOMContentLoaded', function() {
								(function($){
									var custom_woo_hooks_item = '<div class="item" style="margin-bottom: 5px;"> <textarea cols="40" rows="1" name="" placeholder="[your shortcode]"></textarea> <textarea cols="40" rows="1" name="" placeholder="your action hook"></textarea> <textarea cols="20" rows="1" name="" placeholder="your priority"></textarea> <textarea cols="20" rows="1" name="" placeholder="your conditional"></textarea><button class="button adminz_woocoommerce_custom_hook_remove" >Remove</button> </div>'; 
									$("body").on("click","#adminz_woocoommerce_custom_hook_add",function(){
										$(".adminz_woocoommerce_custom_hook").append(custom_woo_hooks_item);
										adminz_woocoommerce_custom_hook_update();
										return false;
									});
									$("body").on("click",".adminz_woocoommerce_custom_hook_remove",function(){
										$(this).closest(".item").remove();
										adminz_woocoommerce_custom_hook_update();
										return false;
									});
									$('body').on('keyup', '.adminz_woocoommerce_custom_hook .item textarea', function() {
					        			adminz_woocoommerce_custom_hook_update();					        			
					        		});
									function adminz_woocoommerce_custom_hook_update(){
										var data_js = $('textarea[name="adminz_woocommerce\[adminz_woocoommerce_custom_hook\]"]').val();

										var alldata = [];
										$('.adminz_woocoommerce_custom_hook .item').each(function(){
											var itemdata = [];
											var shortcode 	= $(this).find('textarea:nth-child(1)').val();
											var hook 		= $(this).find('textarea:nth-child(2)').val();
											var priority 	= $(this).find('textarea:nth-child(3)').val(); 
											var conditional 	= $(this).find('textarea:nth-child(4)').val(); 
											itemdata = [shortcode,hook,priority,conditional];	
											alldata.push(itemdata);																					
										});
										$('textarea[name="adminz_woocommerce\[adminz_woocoommerce_custom_hook\]"]').val(JSON.stringify(alldata));
									}
								})(jQuery);
							});
						</script>
					</td>
				</tr>
			</table>
			
			<?php submit_button(); ?>
		</form>
		<?php
	}
	static function get_arr_attributes(){
		$listattr = wc_get_attribute_taxonomies();		
	    $optionattr = [];
	    $optionattr2 = [];
	    if(!empty($listattr) and is_array($listattr)){
	        foreach ($listattr as $key => $value) {
	            $optionattr[$value->attribute_name] = $value->attribute_label;
	            $optionattr2["filter_".$value->attribute_name] = $value->attribute_label;
	        }
	    }
	    return $optionattr2;
	}
	static function get_arr_tax($hide_default = false){
		// hide default: exclude category, tag, to return 
	    $taxonomies = ADMINZ_Helper_Woocommerce_Taxonomy::lay_taxonomy_co_the_loc();
	    $tax_arr = [];
	    if(!$hide_default){
	    	$tax_arr = [""=>"--"];
	    }	    
	    if(!empty($taxonomies) and is_array($taxonomies)){
		    foreach ($taxonomies as $key => $value) {
		        $label = $value->labels->singular_name;

		        if(ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_taxonomy_label($value)){
		            $label = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_taxonomy_label($value);
		        }
		        $label = "[T] " . $label;
		        if($hide_default){
		        	if(!in_array($value->name,['product_cat','product_tag','title','price'])){
		        		$tax_arr[$value->name] =$label;
		        	}
		        }else{
		        	$tax_arr[$value->name] =$label;
		        }
		        
		    }
	    }
	    return $tax_arr;
	}
	static function adminz_get_all_meta_keys($post_type = 'post', $exclude_empty = true, $exclude_hidden = true){
		if(!is_user_logged_in() and get_transient( __FUNCTION__.$post_type )){
			return get_transient( __FUNCTION__.$post_type );
		}
	    global $wpdb;
	    $query = "
	        SELECT DISTINCT($wpdb->postmeta.meta_key) 
	        FROM $wpdb->posts 
	        LEFT JOIN $wpdb->postmeta 
	        ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
	        WHERE $wpdb->posts.post_type = '%s'
	    ";
	    if($exclude_empty) 
	        $query .= " AND $wpdb->postmeta.meta_key != ''";
	    if($exclude_hidden) 
	        $query .= " AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' ";        
	    $meta_keys = $wpdb->get_col($wpdb->prepare($query, $post_type));
	    set_transient(__FUNCTION__.$post_type,$meta_keys, DAY_IN_SECONDS );
	    return $meta_keys;
	}
	static function get_arr_meta_key($post_type = 'featured_item'){
		if(isset(self::$get_arr_meta_key[$post_type])){
			return self::$get_arr_meta_key[$post_type];
		}
		$meta_keys = self::adminz_get_all_meta_keys($post_type);
	    $key_arr = [];
	    $array_exclude = [
			'pv_commission_rate',
			'wc_productdata_options',
			'total_sales',
			'tm_meta_cpf',
			'tm_meta',
			'_'
		];
	    if(!empty($meta_keys) and is_array($meta_keys)){
	        foreach ($meta_keys as $value) {
	            if($value and !in_array($value,$array_exclude)){
	                $key_arr[$value] = "[M] ".$value;
	            }            
	        }
	    }
	    self::$get_arr_meta_key[$post_type] = $key_arr;
	    return $key_arr;
	}



	// function is_enable_select2_multiple(){
	// 	var_dump($this->check_option('enable_select2',false,"on"));
	// 	die;
	// }
	
	function register_option_setting() {		
		register_setting( $this->options_group, 'adminz_woocommerce');

		
		ADMINZ_Helper_Language::register_pll_string('adminz_woocommerce[adminz_woocommerce_empty_price_html]',self::$slug,false);
		ADMINZ_Helper_Language::register_pll_string('adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_text]',self::$slug,false);
		ADMINZ_Helper_Language::register_pll_string('adminz_woocommerce[adminz_woocommerce_add_buy_now_text]',self::$slug,false);
	}
}