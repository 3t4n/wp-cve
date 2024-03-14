<?php
	if(!function_exists('pre')){
	function pre($data){
			if(isset($_GET['debug'])){
				pree($data);
			}
		}	 
	} 	
	if(!function_exists('pree')){
	function pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';	
		
		}	 
	} 
	
	if(!function_exists('sanitize_wpsy_data')){
		function sanitize_wpsy_data( $input ) {
			if(is_array($input)){		
				$new_input = array();	
				foreach ( $input as $key => $val ) {
					$new_input[ $key ] = (is_array($val)?sanitize_wpsy_data($val):sanitize_text_field( $val ));
				}			
			}else{
				$new_input = sanitize_text_field($input);			
				if(stripos($new_input, '@') && is_email($new_input)){
					$new_input = sanitize_email($new_input);
				}
				if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
					$new_input = sanitize_url($new_input);
				}			
			}	
			return $new_input;
		}	
	}
	
	add_action( 'admin_enqueue_scripts', 'register_wpshopify_scripts' );
	add_action( 'wp_enqueue_scripts', 'register_wpshopify_scripts' );
	function register_wpshopify_scripts(){

		if(array_key_exists('page', $_GET) && $_GET['page']=='wp_shopify'){
			
			wp_enqueue_script(
				'wp-shopify-scripts',
				plugins_url('js/scripts.js', dirname(__FILE__)),
				array('jquery'),
				time()
			);	
			
			
			
			
				
			if(is_admin()){			

				wp_enqueue_style('wp-shopify-styles-'.date('Y'), plugins_url('css/admin-styles.css', dirname(__FILE__)), array(), date('Ymdhi') );
				
				wp_enqueue_script(
					'fontawesome',
					plugins_url('js/fontawesome.min.js', dirname(__FILE__)),
					array('jquery')
				);	
				
				wp_enqueue_style('fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)), array(), date('Ymd') );

				wp_enqueue_script(
					'bootstrap',
					plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
					array('jquery')
				);	
				
				wp_enqueue_style('bootstrap', plugins_url('css/bootstrap.min.css', dirname(__FILE__)), array(), date('Ymd') );
				
				
				
				wp_localize_script('wp-shopify-scripts', 'wpsy_object', array(
					 'url' => admin_url('admin-ajax.php'),
					 'nonce' => wp_create_nonce('wpsy-nonce')
				 ));

			}else{
				
				wp_enqueue_style('wp-shopify-styles-'.date('Y'), plugins_url('css/front-styles.css', dirname(__FILE__)), array(), time() );
			}
			
		}else{
			
			if(!is_admin()){
				wp_enqueue_script(
					'wp-shopify-scripts',
					plugins_url('js/front.js', dirname(__FILE__)),
					array('jquery'),
					time()
				);	
			}
		}
			
	}	
	
	function wpsy_product_price($product_data){
		
		$variants = $product_data->priceRange;
		
		$currency = '';

		$prices = array();
		if(!empty($variants)){
		   foreach($variants as $variant){
			   $prices[] = $variant->amount;
			   $currency = $variant->currencyCode;
		   }
		}
		
		$ret = '';
		
		if(!empty($prices)){
			$ret = (min($prices)!=max($prices)?wpsy_shop_product_display_price(sanitize_text_field(min($prices)), $currency).' - '.wpsy_shop_product_display_price(sanitize_text_field(max($prices)), $currency):wpsy_shop_product_display_price(sanitize_text_field(min($prices)), $currency));		
		}
		
		return $ret;
	}
	
	function wpsy_shop_filter_bar(){
?>
<div class="wpsy-filter-bar-wrapper">
	<input type="text" name="wpsy-filter-bar" id="wpsy-filter-bar" placeholder="<?php _e('Search & Filter...', 'wp-shopify'); ?>" />
</div>
<?php		
	}
		
	function wpsy_shop_display_shortcode($atts = array(), $content = null, $tag = ''){
		
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
		
		$url_type = (isset($atts['url-type'])?$atts['url-type']:'default');
		$type = (isset($atts['type'])?$atts['type']:'');
		$wpsy_limit = (isset($atts['limit'])?$atts['limit']:'');
		$id = trim(isset($atts['id'])?$atts['id']:'');
		$searchfilter = (isset($atts['searchfilter']) && $atts['searchfilter']=='yes'?true:false);
		$thumb_size = (isset($atts['thumb-size'])?$atts['thumb-size']:'default');
		
		ob_start();
		
		if($searchfilter){
			wpsy_shop_filter_bar();
		}
		 
		switch($type){
			default:		
				$query_params = array('query'=>'products');
				$store_data = wpsy_graphql_central($query_params, true);
				$store_data = (!empty($store_data)?$store_data->products->edges:array());
			break;
			case 'collection':		
				if($id){					
					$query_params = array('query'=>'collection', 'id'=>$id, 'limit'=>$wpsy_limit);
					$store_data = wpsy_graphql_central($query_params, true);

					$store_collection = (is_object($store_data) && !empty($store_data)?$store_data->collection:array());
					
					if(!empty($store_collection)){
?>
<div class="wpsy-collection" id="<?php echo $store_collection->id; ?>">
	<h2><?php echo $store_collection->title; ?></h2>
    <?php if($store_collection->image->url): ?>
    <p style="margin:20px 0 0 0; padding:0"><img src="<?php echo $store_collection->image->url; ?>" style="height:200px; width:auto; float:left; margin:10px 20px 0 0;" /><?php echo $store_collection->descriptionHtml; ?></p>
    <?php endif; ?>
</div>
<?php					
					}
					
					$store_data = (is_object($store_collection) && !empty($store_collection)?$store_collection->products->edges:array());
					
					
				}
				
			break;
		}
		

	
	
	  
	   if(!empty($store_data)){
		   
		   
	?>
	<ul class="wp_shopify">
	<?php	   
			$wpsy_count = 0;
			
			foreach($store_data as $product_data){
				
				$product_data = $product_data->node;

				
				
				if (is_numeric($wpsy_limit) && $wpsy_limit>=0 && $wpsy_count >= $wpsy_limit){
					continue;	
				}
				$wpsy_count++;
			   	
				$product_data->price = wpsy_product_price($product_data);
				$attribs = array(				
									'title="'.ucwords($product_data->productType).'"',
							);
				
				switch($url_type){
					default:
						$product_url = home_url().'/product/?id='.basename($product_data->id);
					break;
					case 'shopify':
						$product_url = $product_data->onlineStoreUrl;
						$attribs[] = 'target="_blank" rel="nofollow" aria-label="'.$product_data->title.' '.__('(Opens in a new window)', 'wp-shopify');
					break;
				}

				$featuredImage = $product_data->featuredImage->url;
				
				switch($thumb_size){
					default:
						$extension_pos = strrpos($featuredImage, '.');
						$featuredImage = substr($featuredImage, 0, $extension_pos) . '_'.$thumb_size.'x' . substr($featuredImage, $extension_pos);
					break;
					case 'default':
					
					break;
				}
				
				$attribs[] = 'href="'.$product_url.'"';
				
				
				
	?>
	<li>
	<a <?php echo implode(' ', $attribs); ?>><img alt="<?php echo $product_data->title; ?>" src="<?php echo $featuredImage; ?>" /></a>
	<a class="ptitle" <?php echo implode(' ', $attribs); ?>><?php echo $product_data->title; ?></a>
	<strong><?php echo $product_data->price; ?></strong>
    <div style="display:none"><?php echo $product_data->description; ?></div>
	</li>
	<?php		   
		   }
	?>
	</ul>
	<?php	   


		$o = ob_get_contents();
		
		ob_end_clean();
		   
	   }
	   
	   return $o;
	}
	
	function wpsy_shop_product_display_shortcode($atts = array(), $content = null, $tag = ''){
		
		if(is_admin()){ return; }

		global $wpsy_pro;
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
		$wpsy_db_data = get_option('wpsy_db_data');
		
		$id = (isset($atts['id'])?$atts['id']:$_GET['id']);
		$template = (isset($atts['template'])?$atts['template']:'default');
		$button_type = (isset($atts['button_type'])?$atts['button_type']:'default');
		$shop_link = (isset($atts['shop_link'])?$atts['shop_link']:home_url('shop'));
		$description = (isset($atts['description'])?$atts['description']:'true');
		
		$store_data = wpsy_graphql_central(array('query'=>'product', 'id'=>$id), true);
		
		
		$images = $store_data->product->images;
	
		$data['name'] = ' ';
		$data['price'] = ' ';
		$data['special'] = ' ';
		$data['link'] = ' ';
		$data['image'] = ' ';
		$data['description'] = ' ';
		$data['variations'] = array();
		
		// Fill from response
		$data['name'] = sanitize_text_field($store_data->product->title);
		
		// show lowest price
		if(!empty($store_data->product->priceRange)){
			foreach($store_data->product->priceRange as $variant) { 
			  if (!isset($price)) {
				 $price = $variant->amount; 
			  } else {
				 if ($variant->amount < $price) $price = $variant->amount; 
			  }
			}
		}
		$data['price'] = wpsy_product_price($store_data->product);
		// field contains HTML markup
		switch($description){
			case 'yes':
			case '1':
			case 'true':
			case 'enable':
			case 'show':
			case 'display':
				$data['description'] = wp_kses_post($store_data->product->descriptionHtml);
			break;
		}
		$image_url = esc_url($store_data->product->featuredImage->url); 
		$data['image'] = '<img data-src="'.$image_url.'" src="' . sanitize_text_field($image_url) . '" />';
		$data['link'] = "https://" . $wpsy_db_data['wpsy_url'] . "/products/" . sanitize_text_field($store_data->product->handle);
		
		// start output
		$o = '';
		
		if($wpsy_pro && $template!='' && $template!='default'){
			
			if(!empty($store_data->product->variants)){
				foreach($store_data->product->variants->edges as $variant) { 
					$node = $variant->node;
					$variant_id = basename($node->id);
					$data['variations'][$variant_id] = $node;
				}
			}
			//pree($data);exit;
			
			$template_file = WPSY_PLUGIN_DIR.'/pro/templates/'.$template.'.php';
			

			$template_css_file = WPSY_PLUGIN_DIR.'/pro/templates/css/'.$template.'.css';
			$template_css_file = (file_exists($template_css_file)?$template_css_file:'');

			$template_js_file = WPSY_PLUGIN_DIR.'/pro/templates/js/'.$template.'.js';
			$template_js_file = (file_exists($template_js_file)?$template_js_file:'');
			
			if(file_exists($template_file)){
				ob_start();
				
				include_once($template_file);
				
				$o = ob_get_contents();
				
				
				ob_end_clean();
			}
		}else{
		
			// start box
			$o .= '<div class="wp_shopify_product">';
			
			$o .= '<div class="prod-left">' . '<a href="' . $data['link'] . '">' . $data['image'] . '</a>' . '';
	
			if(!empty($images)){
				$o .= '<div class="prod-gallery">' . '<ul>';
				foreach($images->edges as $nodes){
					foreach($nodes as $node){
						if($node->url!=$image_url){
							$o .= '<li><a href="' . $node->url . '"><img src="'.$node->url.'" /></a>' . '</li>';
						}
					}
				}
				$o .= '</ul></div>';
			}
			
			$o .= '</div>';
			
			$o .= '<div class="prod-upper">';
			$o .= '<div class="prod-right">' . '<a target="_blank" aria-label="' . $data['name'] . ' (Opens in a new window)" href="' . $data['link'] . '"><h3>' . $data['name'] . '</h3></a>' . '<br />';
			$o .= '<div class="prod-price">' .$data['price']. '</div>';
			$o .= '<div class="prod-buy"><a href="' . $data['link'] . '" target="_blank" aria-label="' . $data['name'] . ' (Opens in a new window)"><img src="'.plugins_url( 'images/btn.png', dirname(__FILE__) ).'" /></a></div>';
			$o .= '</div>';//prod-right
			$o .= '</div>';//prod-upper
			$o .= '<div class="prod-clear"></div>';
			$o .= '<div class="prod-desc">' . $data['description'] . '</div>';
			
			// enclosing tags
			if (!is_null($content)) {
			  // secure output by executing the_content filter hook on $content
			  $o .= apply_filters('the_content', $content);
			
			  // run shortcode parser recursively
			  $o .= do_shortcode($content);
			}
			
			// end box
			$o .= '</div>';
			
		}
		
		// return output
		return $o;
	}
	
	function wpsy_shop_product_display_price($price, $currency=''){	   
		if(class_exists('NumberFormatter')){
			$fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );
			return $fmt->formatCurrency($price, $currency);	   
		}else{
			return '$'.$price;
		}
	}
	
	function wpsy_shop_product_display_get_error($msg)
	{
	
	   $o = '<div class="shop_product_display-box">';
	   $o .= $msg;
	   $o .= '</div>';
	   return $o;
	}
	
	function wpsy_shop_product_display_shortcodes_init()
	{
	   wp_enqueue_style('wp-shopify-styles', plugins_url('css/front-styles.css', dirname(__FILE__)), array(), time(), 'all' );
	
	   add_shortcode('wp-shopify', 'wpsy_shop_display_shortcode');
	   add_shortcode('wp-shopify-product', 'wpsy_shop_product_display_shortcode');
	
	}
	
	add_action('init', 'wpsy_shop_product_display_shortcodes_init');
	
	add_action('admin_menu', 'wpsy_add_admin_menu');
	add_action('admin_init', 'wpsy_settings_init');
	
	
	function wpsy_add_admin_menu()
	{
		global $wpsy_data;
		add_options_page($wpsy_data['Name'], $wpsy_data['Name'], 'manage_options', 'wp_shopify', 'wp_shopify_settings');
	
	}

	
	function shoppd_settings_section_callback()
	{
	
	   echo '<div class="alert alert-warning" role="alert">'.__('Please fill the required fields.','wp-shopify').'</div>';
	
	}	
	
	function wpsy_settings_init()
	{
	
	   register_setting('wpsy_settings_page', 'wpsy_db_data');
	   
	   
	   add_settings_section(
		  'wpsy_settings_page_section',
		  '&nbsp;',
		  'shoppd_settings_section_callback',
		  'wpsy_settings_page'
	   );	

	   $args = array('size' => '80');
	   add_settings_field(
		  'wpsy_url',
		  __('Shopify Domain (no https://)','wp-shopify'),
		  'wpsy_url_render',
		  'wpsy_settings_page',
		  'wpsy_settings_page_section',
		  $args
	   );
	   add_settings_field(
		  'wpsy_api_key',
		  __('Shopify Private app API key','wp-shopify'),
		  'wpsy_api_key_render',
		  'wpsy_settings_page',
		  'wpsy_settings_page_section',
		  $args
	   );
	   add_settings_field(
		  'wpsy_password',
		  __('Shopify Private app API secret key', 'wp-shopify'),
		  'wpsy_password_render',
		  'wpsy_settings_page',
		  'wpsy_settings_page_section',
		  $args
	   );
	
	   add_settings_field(
		  'wpsy_storefront_token',
		  __('Storefront API access token', 'wp-shopify'),
		  'wpsy_storefront_token_render',
		  'wpsy_settings_page',
		  'wpsy_settings_page_section',
		  $args
	   );	
	
	}
	
	
	function wpsy_url_render($args)
	{
	
	   $options = get_option('wpsy_db_data');
	   ?>
		<input type='text' name='wpsy_db_data[wpsy_url]' value='<?php echo $options['wpsy_url']; ?>'
		   <?php
		   if (is_array($args) && sizeof($args) > 0) {
			  foreach ($args as $key => $value) {
				 echo $key . "=" . $value . " ";
			  }
		   }
		   ?>> <i style="color:#96BF48;" class="fab fa-shopify"></i>
	   <?php
	
	}
	
	function wpsy_api_key_render($args)
	{
	
	   $options = get_option('wpsy_db_data');
	   ?>
		<input type='text' name='wpsy_db_data[wpsy_api_key]' value='<?php echo $options['wpsy_api_key']; ?>'
		   <?php
		   if (is_array($args) && sizeof($args) > 0) {
			  foreach ($args as $key => $value) {
				 echo $key . "=" . $value . " ";
			  }
		   }
		   ?>> <i style="color:#06C;" class="fas fa-key"></i>
	   <?php
	
	}
	
	function wpsy_password_render($args)
	{
	
	   $options = get_option('wpsy_db_data');
	   ?>
		<input type='text' name='wpsy_db_data[wpsy_password]' value='<?php echo $options['wpsy_password']; ?>'
		   <?php
		   if (is_array($args) && sizeof($args) > 0) {
			  foreach ($args as $key => $value) {
				 echo $key . "=" . $value . " ";
			  }
		   }
		   ?>> <i style="color:#F90;" class="fas fa-fingerprint"></i>
	   <?php
	
	}
	
	function wpsy_storefront_token_render($args)
	{
	
	   $options = get_option('wpsy_db_data');
	   ?>
		<input type='text' name='wpsy_db_data[wpsy_storefront_token]' value='<?php echo $options['wpsy_storefront_token']; ?>'
		   <?php
		   if (is_array($args) && sizeof($args) > 0) {
			  foreach ($args as $key => $value) {
				 echo $key . "=" . $value . " ";
			  }
		   }
		   ?>> <i style="color:#333;" class="fas fa-user-lock"></i>
	   <?php
	
	}	
	
	
	function wp_shopify_settings()
	{
		include('wps_settings.php');

	
	}
	
	
	function wp_shopify_admin_links($links) { 

		global $wpsy_premium_copy, $wpsy_pro;


		$settings_link = '<a href="admin.php?page=wp_shopify">'.__('Settings', 'wp-shopify').'</a>';

		
		if($wpsy_pro){
			array_unshift($links, $settings_link); 
		}else{
			 
			$wpsy_premium_link = '<a href="'.esc_url($wpsy_premium_copy).'" title="'.__('Go Premium', 'wp-shopify').'" target="_blank">'.__('Go Premium', 'wp-shopify').'</a>'; 
			array_unshift($links, $settings_link, $wpsy_premium_link); 
		
		}
				
		
		return $links; 
	}