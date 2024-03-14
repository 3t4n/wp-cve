<?php
	global $woo_cs_message_position;
	
	function woo_cs_plugin_links($links) { 
	
		global $woo_cs_premium_link, $woo_cs_pro;
		
		if(woo_cs_check_plugin_active_status('woocommerce/woocommerce.php')){
			
			$settings_link = '<a href="admin.php?page=woo_cs_settings">'.__('Settings', 'woo-coming-soon').'</a>';
			
		}else{
			
			$settings_link = '<a href="admin.php?page=woo_cs_general">'.__('Settings', 'woo-coming-soon').'</a>';
			
		}
		
		$app_link = '<a target="_blank" href="https://play.google.com/store/apps/details?id=woo.coming.soon" title="'.__('Get it on GooglePlay', 'woo-coming-soon').'">'.__('GooglePlay', 'woo-coming-soon').'</a>';
		
		if($woo_cs_pro){
			array_unshift($links, $settings_link, $app_link); 
		}else{
			if($woo_cs_premium_link){
				$woo_cs_premium_link = '<a href="'.esc_url($woo_cs_premium_link).'" title="'.__('Go Premium', 'woo-coming-soon').'" target="_blank">'.__('Go Premium', 'woo-coming-soon').'</a>'; 
				array_unshift($links, $settings_link, $app_link, $woo_cs_premium_link); 
			}else{
				array_unshift($links, $settings_link, $app_link); 
			}
		
		}
		
		
		return $links; 
	}	
	
	
	add_filter('woocommerce_loop_add_to_cart_link', 'woo_csn_woocommerce_loop_add_to_cart_link', 10, 2);
	
	function woo_csn_woocommerce_loop_add_to_cart_link($html, $product){
		
		$product = (is_object($product) && method_exists($product, 'get_id')?$product:(object)array());
		$is_cs = false;
		
		if(!empty($product)){
			
			$_coming_soon = get_post_meta($product->get_id(), '_coming_soon', true);
			$_coming_soon = ($_coming_soon?$_coming_soon:'false').'';
				
			$is_cs = ($product->get_status()=='coming_soon' || $_coming_soon=='true');
			
			$woo_cs_text = woo_cs_btn_text();
		}
			
		if($is_cs)
		return '<div class="add-to-cart-button-outer"><div class="add-to-cart-button-inner"><div class="add-to-cart-button-inner2"><a href="'.get_permalink($product->get_id()).'" rel="nofollow" class="qbutton add-to-cart-button button add_to_cart_button ajax_add_to_cart">'.$woo_cs_text.'</a></div></div></div>';
		else
		return $html;
	}
	
	add_action( 'post_submitbox_misc_actions', 'woo_csn_custom_button' );
	
	function woo_csn_custom_button(){
		
			global $post, $woo_cs_android_settings, $woo_cs_url, $woo_cs_options;

			if(is_object($post) && isset($post->post_type) && $post->post_type != 'product'){
				return;
			}
			
			$_coming_soon = get_post_meta($post->ID, '_coming_soon', true);
			$_coming_soon_date = get_post_meta($post->ID, '_coming_soon_date', true);
			$_coming_soon_time = get_post_meta($post->ID, '_coming_soon_time', true);
			
			$_coming_soon = ($_coming_soon?$_coming_soon:'false').'';
			
			if(date('d')%2!=0){
				$woo_cs_android_settings->ab_io_display($woo_cs_url);
			}
			$woo_cs_title_date = __('Add date when to remove coming soon property from this product', 'woo-coming-soon');
			$woo_cs_label_date = __('Arrival date', 'woo-coming-soon');

			$woo_cs_title_time = __('Add time when to remove coming soon property from this product', 'woo-coming-soon');
			$woo_cs_label_time = __('Arrival time', 'woo-coming-soon');


			$html  = '<div class="coming-soon-section">';
			$html .= '<input type="button" value="'.woo_cs_btn_text().'" class="button button-secondary '.($_coming_soon=='true'?'active':'').'"><input type="hidden" class="" name="_coming_soon" value="'.$_coming_soon.'" />';

			if(array_key_exists('arrival_date', $woo_cs_options)) {

				$html .= "<div class='woo_cs_date_section'>";
				$html .= '<input type="text" placeholder="' . $woo_cs_label_date . '" name="_coming_soon_date" value="' . $_coming_soon_date . '" id="woo_cs_coming_soon_date" style="margin-top:5px;" /><input type="hidden" placeholder="' . $woo_cs_label_time . '" name="_coming_soon_time" value="' . $_coming_soon_time . '" id="woo_cs_coming_soon_time" style="margin-top:5px;" /> <span class="dashicons dashicons-calendar-alt" style="position: relative; top: 9px; left: -29px;"></span>';
				$html .= '</div>';

			}

			$html .= function_exists('woo_cs_stock_based_html') ? woo_cs_stock_based_html($post->ID) : '';

			

			$html .= '</div>';
			echo $html;
	}
	
	function woo_csn_disable_coming_soon_purchase( $purchasable, $product ) {
		
		$product = (is_object($product) && method_exists($product, 'get_id')?$product:(object)array());
		
		$is_cs = false;
		
		if(!empty($product)){
			
			if(function_exists('woo_cs_check_stock_status')){
				woo_cs_check_stock_status($product->get_id());
			}
	
			$_coming_soon = get_post_meta($product->get_id(), '_coming_soon', true);
			$_coming_soon = ($_coming_soon?$_coming_soon:'false').'';
				
			$is_cs = ($product->get_status()=='coming_soon' || $_coming_soon=='true');
			 
			//$product_id = $product->is_type( 'variation' ) ? $product->get_variation_id() : $product->get_id();
		}
	   
		return (!$is_cs);
	}
	add_filter( 'woocommerce_variation_is_purchasable', 'woo_csn_disable_coming_soon_purchase', 10, 2 );
	add_filter( 'woocommerce_is_purchasable', 'woo_csn_disable_coming_soon_purchase', 10, 2 );
	
	function woo_csn_custom_post_status(){
		

		
		
		register_post_status( 'coming_soon', array(
			'label'                     => _x( woo_cs_btn_text(), 'post' ),
			'public'                    => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( woo_cs_btn_text().' <span class="count">(%s)</span>', woo_cs_btn_text().' <span class="count">(%s)</span>' ),
		) );


		
		if(is_admin() && isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['post'])){
			$post = get_post($_GET['post']);

			if(is_object($post) && isset($post->post_type) && $post->post_type == 'product'){
				
				woo_cs_check_stock_status($post->ID);
			}

		}


		if(is_admin() && isset($_REQUEST['action']) && $_REQUEST['action'] == 'inline-save' && isset($_REQUEST['post_ID'])){
			$post = get_post($_REQUEST['post_ID']);

			if(is_object($post) && isset($post->post_type) && $post->post_type == 'product'){
				
				woo_cs_check_stock_status($post->ID);
			}

		}
	}
	add_action( 'init', 'woo_csn_custom_post_status' );
	
	add_action('admin_footer', 'woo_csn_append_post_status_list');
	function woo_csn_append_post_status_list(){
		 global $post;
		 $complete = '';
		 $label = '';
		 //pre($post);
		 if(is_object($post) && isset($post->post_type) && $post->post_type == 'product'){
			  if($post->post_status == 'coming_soon'){
				   $complete = ' selected="selected"';
				   $label = '<span id="post-status-display"> '.woo_cs_btn_text().'</span>';
			  }
	?>
			  <script>
			  jQuery(document).ready(function($){
				   $('select#post_status').append('<option value="coming_soon" <?php echo $complete; ?>><?php echo woo_cs_btn_text(); ?></option>');
				   $('.misc-pub-section label').append('<?php echo $label; ?>');

                  var obj = $('.coming-soon-section input[name="_coming_soon"]');
                  var obj_date = $('.coming-soon-section .woo_cs_date_section');
                  var remove_date = $('.coming-soon-section .woo_cs_date_section .dashicons');

                  if(obj.val()=='true'){

                      obj_date.show();

                  }else{

                      obj_date.hide();

                  }

                  $('.coming-soon-section input[type="button"]').click(function(){
					   $(this).toggleClass('active');
					   if(obj.val()=='true'){

					    obj.val('false');
                       obj_date.hide();

					   }else{

					    obj.val('true');
                        obj_date.show();

					   }

				   });


				   $('.ibulb_switch input[type="checkbox"]').on('change', function(){

						var toggle_data = $($(this).data('toggle'));
						var this_parent = $(this).parents('.ibulb_switch:first');
						var hidden_input = this_parent.find('input[type="hidden"]');

						if($(this).prop('checked')){
							toggle_data.show();
							hidden_input.val('yes');
						}else{
							toggle_data.hide();
							hidden_input.val('no');
						}

				   });
                  obj_date.find('input[type="text"]').datepicker(woo_coming_soon_obj.datepicker);
				  
				  var csdt = setInterval(function(){
					  if($('div.woocommerce_variable_attributes :input[name^="coming_soon_date"]').length>0){
						  $('div.woocommerce_variable_attributes :input[name^="coming_soon_date"]').datepicker(woo_coming_soon_obj.datepicker);
						  clearInterval(csdt);
					  }
				  }, 1000);
				  $('body.post-type-product').on('click', 'div.woocommerce_variable_attributes :input[name^="coming_soon_date"]', function(){
					  if(!$(this).hasClass('hasDatepicker')){
						  $('div.woocommerce_variable_attributes :input[name^="coming_soon_date"]').datepicker(woo_coming_soon_obj.datepicker);
					  }
				  });

                  remove_date.on('click', function(){

                      // obj_date.find('input[type="text"]').val('');

                  });



			  });
			  </script>
			  
              
	<?php  
		 }
	}
	function woo_csn_display_archive_state( $states ) {
		 global $post;
		 $arg = get_query_var( 'post_status' );
		 if($arg != 'coming_soon'){
			  if(is_object($post) && !empty($post) && $post->post_status == 'coming_soon'){
				   return array(woo_cs_btn_text());
			  }
		 }
		return $states;
	}
	add_filter( 'display_post_states', 'woo_csn_display_archive_state' );
	//pree($woo_cs_message_position);
	add_action( $woo_cs_message_position, 'woo_csn_wc_print_notices', 10 );

	function woo_cs_sanitize_input( $input ) {
		if(is_array($input)){		
			$new_input = array();	
			foreach ( $input as $key => $val ) {
				$new_input[ $key ] = (is_array($val)?woo_cs_sanitize_input($val):sanitize_text_field( $val ));
			}			
		}else{
			$new_input = sanitize_text_field($input);			
			if(stripos($new_input, '@') && is_email($new_input)){
				$new_input = sanitize_email($new_input);
			}
			if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
				$new_input = esc_url_raw($new_input);
			}			
		}	
		return $new_input;
	}	


	function woo_csn_wc_print_notices(){
		global $post, $woo_cs_options, $woo_cs_text_style;
		//pree($post);
		$_coming_soon = get_post_meta($post->ID, '_coming_soon', true);
		$_coming_soon = ($_coming_soon?$_coming_soon:'false').'';
			
		$is_cs = ((is_object($post) && isset($post->post_status) && $post->post_status=='coming_soon') || $_coming_soon=='true');
		//pree($is_cs);	
		if($is_cs){
			

			$product_page_style = ((array_key_exists('product_page_style', $woo_cs_options) && trim($woo_cs_options['product_page_style']))?$woo_cs_options['product_page_style']:$woo_cs_text_style);
			
	?>
	<style type="text/css">
	
	.single-product form.cart,
	.single-product #tab-reviews{
		display:none !important;
	}
	
	<?php echo ($product_page_style?$product_page_style:''); ?>
	</style>
	<script type="text/javascript" language="javascript">
		jQuery(document).ready(function($){
			setTimeout(function(){
				$('.single-product form.cart').remove();
			}, 3000);
		});
	</script>
	<div class="woo_csn_notices"><strong><?php echo $woo_cs_options['product_page_text']; ?></strong></div>
	<?php			
		}
				
	}
	
	function woo_csn_update_post( $post_id ) {
		if(is_admin()){
		    if(isset($_POST['_coming_soon'])){

			    update_post_meta($post_id, '_coming_soon', woo_cs_sanitize_input($_POST['_coming_soon']));
            }

            if(isset($_POST['_coming_soon_date'])){

                update_post_meta($post_id, '_coming_soon_date', woo_cs_sanitize_input($_POST['_coming_soon_date']));
				
				if(isset($_POST['_coming_soon_time'])){

					update_post_meta($post_id, '_coming_soon_time', woo_cs_sanitize_input($_POST['_coming_soon_time']));
				}
				
            }
			

			if(isset($_POST['_wcs_stock_threshold'])){

                update_post_meta($post_id, '_wcs_stock_threshold', woo_cs_sanitize_input($_POST['_wcs_stock_threshold']));
            }

			if(isset($_POST['_wcs_stock_based'])){

                update_post_meta($post_id, '_wcs_stock_based', woo_cs_sanitize_input($_POST['_wcs_stock_based']));
            }
		}
	}
	add_action( 'save_post', 'woo_csn_update_post' );
	
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

	function woo_cs_settings(){ 		
		global $wpdb, $woo_cs_dir; 
		//echo $woo_cs_dir;exit;
		include($woo_cs_dir.'inc/woo_cs_settings.php');	
	}
	
	function woo_cs_general(){ 		
		global $wpdb, $woo_cs_dir; 
		//echo $woo_cs_dir;exit;
		include($woo_cs_dir.'inc/woo_cs_general.php');	
	}	
	
	function woo_cs_admin_menu(){
		
		global $woo_cs_data;
		//pree($woo_cs_data);
		add_submenu_page('woocommerce', $woo_cs_data['Name'], $woo_cs_data['Name'], 'manage_woocommerce', 'woo_cs_settings', 'woo_cs_settings');
		
		$title = trim(str_replace(array('Woo', 'Pro'), '', $woo_cs_data['Name']));
		
		add_options_page($title, $title, 'activate_plugins', 'woo_cs_general', 'woo_cs_general');

	}
	function woo_cs_settings_update(){
		
		global $woo_cs_options;
	
		if(!empty($_POST) && array_key_exists('woo_cs_options', $_POST)){
				
			if ( ! isset( $_POST['woo_cs_nonce_field'] ) 
				|| ! wp_verify_nonce( $_POST['woo_cs_nonce_field'], 'woo_cs_nonce_action' ) 
			) {
			   _e('Sorry, your nonce did not verify.', 'woo-coming-soon');
			   exit;
			} else {
			   // process form data
			   $woo_cs_options = woo_cs_sanitize_input($_POST['woo_cs_options']);
			   update_option('woo_cs_options', $woo_cs_options);
			}
		}
		
		return $woo_cs_options;
	}
	
	function woo_cs_front_scripts(){
		
		global $woo_cs_url, $post, $woo_cs_options, $woo_cs_time;

		wp_enqueue_script(
			'woo_cs_scripts',
			plugins_url('/js/scripts.js', dirname(__FILE__)),
			array('jquery'),
			time()
		);
		wp_enqueue_style( 'woo-cs-styles', plugins_url('/css/front-styles.css', dirname(__FILE__)), '', time() );
		
		$translation_array = array(
					
			'is_product' => (is_product()),
			'product_url' => (is_product()?get_permalink():''),
			'this_url' => get_permalink(),
			'coming_soon' => array(),
			'variable_product' => false,
			'woo_csn_notice' => '<div class="woo_csn_notices"><strong>'.$woo_cs_options['product_page_text'].'</strong></div>',


		);
		
		if(is_product()){
			$coming_soon = array();
			if(is_object($post) && isset($post->post_type) && $post->post_type=='product'){
				$product = wc_get_product($post->ID);
				if($product->get_type()=='variable'){
					$translation_array['variable_product'] = true;
					$variations = $product->get_available_variations();
					if(!empty($variations)){
						foreach($variations as $variation){
							$variation_id = $variation['variation_id'];
							if($variation_id){
								$_coming_soon = get_post_meta($variation_id, '_coming_soon', true);
								$arrival_date = array_key_exists('arrival_date', $woo_cs_options);
								$coming_soon[$variation_id] = ($_coming_soon?'yes':'no');
								if($arrival_date && $coming_soon[$variation_id]=='yes'){
									$_coming_soon_date = trim(get_post_meta($variation_id, '_coming_soon_date', true));
									$_coming_soon_time = trim(get_post_meta($variation_id, '_coming_soon_time', true));
									
									$_coming_soon_dt = trim($_coming_soon_date.' '.$_coming_soon_time);
									
									$_coming_soon_time = $_coming_soon_date?strtotime($_coming_soon_dt):'';
									
									if(is_numeric($_coming_soon_time) && $woo_cs_time>$_coming_soon_time){
										$coming_soon[$variation_id] = 'no';
									}
								}
								
							}
						}
					}
				}
			}
			if(!empty($coming_soon)){
				$translation_array['coming_soon'] = $coming_soon;
			}
		}
		
		//pree($translation_array);exit;
		wp_localize_script( 'woo_cs_scripts', 'woo_cs_obj', $translation_array );				
	}
	
	add_action( 'wp_enqueue_scripts', 'woo_cs_front_scripts', 99 );	
	
	function woo_cs_admin_scripts(){
		global $woo_cs_url, $post_type;

		
		wp_enqueue_style( 'woo-coming-soon', $woo_cs_url.'css/admin-styles.css?t='.time(), array(), true );
		
		$allowed_pages = array(
            'woo_cs_settings',
			'woo_cs_general'
        );
		
		if(is_admin() && isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)){
			wp_enqueue_style('fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)));
	
			wp_enqueue_script(
				'bs-scripts',
				plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
				array('jquery')
			);
			
			wp_enqueue_style('bs-styles', plugins_url('css/bootstrap.min.css', dirname(__FILE__)));
			
			if($_GET['page'] == 'woo_cs_general'){
				wp_enqueue_media();
			}
		}
		

		if($post_type == 'product'){

            wp_enqueue_style( 'woo-coming-soon-ui', $woo_cs_url.'css/jquery-ui.css?t='.time(), array(), true );
            wp_enqueue_script('jquery-ui-datepicker' );
            wp_localize_script('jquery-ui-datepicker', "woo_coming_soon_obj", array(

            'datepicker' => array(
                'dateFormat' => "dd-mm-yy",
                'changeMonth' => true,
                'changeYear' => true,
                'minDate' => date('d-m-Y', strtotime('+ 1day')),
                ),

            ));

        }

    }		
		
		
	
	
	
		
	function woo_cs_custom_columns_values( $column, $post_id ) {
		
		if($column == 'woo_coming_soon'){
			$_coming_soon = get_post_meta($post_id, '_coming_soon', true);
			$_coming_soon = ($_coming_soon?$_coming_soon:'false').'';
				
			$is_cs = (get_post_type( $post_id )=='coming_soon' || $_coming_soon=='true');
					
			if ($is_cs){
				global $woo_cs_data;
				echo '<span style="font-size:10px; color:#0073aa;" title="'.$woo_cs_data['Name'].'">'.woo_cs_btn_text().'</span>';
			}
		}
	}
	add_action( 'manage_posts_custom_column' , 'woo_cs_custom_columns_values', 10, 2 );
	
	/* Add custom column to post list */
	function woo_cs_custom_columns_title( $column_array ) {		
	
		$column_array['woo_coming_soon'] = woo_cs_btn_text();		
		return $column_array;	
	}
		
	function woo_cs_btn_text() {		
		global $woo_cs_options;
		$btn_text = array_key_exists('product_page_edit', $woo_cs_options)?$woo_cs_options['product_page_edit']:'';
		$btn_text = trim($btn_text)?$btn_text:__('Coming Soon', 'woo-coming-soon');
		return $btn_text;		
    }

    add_action('init', 'woo_cs_remove_coming_soon_by_date');

    function woo_cs_remove_coming_soon_by_date(){

        global $wpdb, $woo_cs_time;
        $coming_soon_args = array(
            'numberposts' => -1,
            'post_type' => 'product',
            'post_status' => 'any',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => '_coming_soon',
                    'value' => 'true',
                    'compare' => '=',
                ),
                array(
                    'key' => '_coming_soon_date',
                    'compare' => 'EXIST',
                ),
                array(
                    'key' => '_coming_soon_date',
                    'value' => '',
                    'compare' => '!=',
                ),

            ),
        );



        $coming_soon_products = get_posts($coming_soon_args);

        if(empty($coming_soon_products)){return;}

        $coming_soon_products_str = '('.implode(', ', $coming_soon_products).')';
        $coming_soon_dates_query = "SELECT * FROM $wpdb->postmeta WHERE `post_id` IN $coming_soon_products_str AND `meta_key` = '_coming_soon_date'";
        $coming_soon_dates = $wpdb->get_results($coming_soon_dates_query);

        $finished_date_products = array();

        $current_time = $woo_cs_time;

        if(!empty($coming_soon_dates)){
            foreach($coming_soon_dates as $index => $coming_soon_date_obj){

                $coming_soon_date = $coming_soon_date_obj->meta_value;
                $coming_soon_date = strtotime($coming_soon_date);

                if($coming_soon_date <= $current_time){

                    $finished_date_products[] = $coming_soon_date_obj->post_id;
                }


            }
        }

        if(!empty($finished_date_products)){

            $finished_date_products_str = '('.implode(', ', $finished_date_products).')';
            $coming_soon_update_query = "UPDATE $wpdb->postmeta set `meta_value` = 'false' WHERE `post_id` IN $finished_date_products_str AND `meta_key` = '_coming_soon'";
            $coming_soon_date_update_query = "UPDATE $wpdb->postmeta set `meta_value` = '' WHERE `post_id` IN $finished_date_products_str AND `meta_key` = '_coming_soon_date'";


            $wpdb->query($coming_soon_update_query);
            $wpdb->query($coming_soon_date_update_query);


        }

    }

	if(!function_exists('woo_cs_stock_based_html')){
		function woo_cs_stock_based_html($product_id = 0, $bulk_edit = false){

			global $woo_cs_options;


			$html = '';

			if(array_key_exists('stock_based', $woo_cs_options)) {

				$wcs_stock_based = get_post_meta($product_id, '_wcs_stock_based', true);
				$wcs_stock_based = ($wcs_stock_based ? $wcs_stock_based : 'no');
				$_wcs_stock_threshold = get_post_meta($product_id, '_wcs_stock_threshold', true);
				$_wcs_stock_threshold = $_wcs_stock_threshold ? $_wcs_stock_threshold : 0;
				$woo_cs_label_stock = __('Stock Based Availability?', 'woo-coming-soon');
				$woo_cs_title_stock_threshold = __('Minimum stock to show coming soon message. Default: 0', 'woo-coming-soon');
				$woo_cs_title_stock_based = __('Change coming soon status on the basis of stock availability.', 'woo-coming-soon');
				$bulk_edit_class = ($bulk_edit ? 'wcs_bulk': 'wcs_edit');
				ob_start();


				?>
					<div class="wcs_stock_wrapper <?php echo $bulk_edit_class; ?>">

						<div class="wcs_switch" title="<?php echo $woo_cs_title_stock_based; ?>">
							
							<span class="switch_label">
								<label for="woo_cs_stock_based"><?php echo $woo_cs_label_stock;?></label>
							</span> 
							
						
							<label class="ibulb_switch">
								<input data-toggle=".woo_cs_stock_section" id="woo_cs_stock_based" type="checkbox" <?php checked($wcs_stock_based == 'yes'); ?>/>
								<span class="ibulb_slider"></span>
								<input type="hidden" name="_wcs_stock_based" value="<?php echo $wcs_stock_based; ?>" />
							</label>						
							
						</div>

						<div class="woo_cs_stock_section" <?php echo ($wcs_stock_based == 'yes' ? '' : 'style="display:none;"'); ?>>
							<input title="<?php echo $woo_cs_title_stock_threshold; ?>" type="number" name="_wcs_stock_threshold" value="<?php echo $_wcs_stock_threshold; ?>" /><br />
                            <small><?php echo $woo_cs_title_stock_based; ?></small>

						</div>

					</div>

				<?php

				$html .= ob_get_clean();

            }

			return $html;
		}
	}
	
	
	if(!function_exists('woo_cs_check_stock_status')){
		function woo_cs_check_stock_status($product_id){
			$product = wc_get_product($product_id);
			global $woo_cs_options;


			$product_stock_status = false;

			if($product){

				$stock_based_global = array_key_exists('stock_based', $woo_cs_options);
				$wcs_stock_based = get_post_meta($product_id, '_wcs_stock_based', true);
				$wcs_stock_based = ($wcs_stock_based ? $wcs_stock_based : 'no');
				$_wcs_stock_threshold = get_post_meta($product_id, '_wcs_stock_threshold', true);
				$_wcs_stock_threshold = $_wcs_stock_threshold ? $_wcs_stock_threshold : 0;
				$product_manage_stock = $product->get_manage_stock();
				$stock_status = $product->get_stock_status();
				$stock = $product->get_stock_quantity();
				$stock = ($stock ? $stock : 0);

				if($stock_based_global && $wcs_stock_based == 'yes'){

					if($product_manage_stock){

						$product_stock_status = $stock > $_wcs_stock_threshold;

					}else{

						$product_stock_status = $stock_status == 'instock';

					}

					update_post_meta($product_id, '_coming_soon', ($product_stock_status ? 'false' : 'true'));

				}

			}

			
		}
	}

	if(!function_exists('woo_cs_function_frontend')){

		function woo_cs_function_frontend(){
			// echo 'hello';
			global $wpc_dir, $wpc_url,  $woo_cs_all_plugins, $woo_cs_plugins_activated, $wpc_assets_loaded;

			$coming_soon_content = '';

			if(!array_key_exists('chameleon/index.php', $woo_cs_all_plugins)){

			}elseif(!in_array('chameleon/index.php', $woo_cs_plugins_activated)){
				
			}else{


				
				$short = 'cs';
				$wp_chameleon = get_option( 'wp_chameleon', array());
				$wp_chameleon_background = get_option( 'wp_chameleon_background', array());
				$cs_assets = array_key_exists($short, $wpc_assets_loaded) ? $wpc_assets_loaded[$short] : array();
				//pree($cs_assets);
				
				$wp_chameleon = (is_array($wp_chameleon) ? $wp_chameleon : array());
				$wp_chameleon_background = (is_array($wp_chameleon_background) ? $wp_chameleon_background : array());


				$cs_settings = (array_key_exists($short, $wp_chameleon) ? $wp_chameleon[$short] : array());
				$cs_background = (array_key_exists($short, $wp_chameleon_background) ? $wp_chameleon_background[$short] : array());

				$selected_style = '';

				if(!empty($cs_settings)){
					foreach ($cs_settings as $style_key => $style_value) {
						# code...

						if(is_array($style_value) && in_array('enabled', $style_value)){
							$selected_style = $style_key;

							break;
						}
					}
				}

				

				if($selected_style && file_exists($wpc_dir)){

					// pree($wpc_dir);

					


				
					$current_background = (array_key_exists($selected_style, $cs_background) ? $cs_background[$selected_style] : '');

					$current_bg_url = ($current_background ? wp_get_attachment_url($current_background) : '') ;

					$wpc_dir = str_replace('\\', '/', $wpc_dir);
					
					$current_cs_dir = $wpc_dir."assets/$short/$selected_style/";

					$wpc_asset_url = str_replace($wpc_dir, $wpc_url, $current_cs_dir);


					$current_cs_dir = str_replace('\\', '/', $current_cs_dir);
					$current_cs_index = $current_cs_dir."index.html";
					$selected_style_asset = array_key_exists($selected_style, $cs_assets) ? $cs_assets[$selected_style] : array();
					
					if(file_exists($current_cs_index)){
						$coming_soon_content = file_get_contents($current_cs_index);
						//pree($coming_soon_content);exit;
					}else{
						//pree('A');exit;
					}
					
					



					if(!empty($selected_style_asset)){
						foreach ($selected_style_asset as $asset_key => $asset_array) {
							# code...

							if(!empty($asset_array)){
								foreach ($asset_array as $single_asset_key => $single_asset) {
									# code...

									$single_asset = str_replace('\\', '/', $single_asset);
									$base_asset_url = str_replace($wpc_dir, $wpc_url, $single_asset);
									$base_asset = str_replace($current_cs_dir, '', $single_asset);


									$search_array = array(
										'href="'.$base_asset.'"',
										'src="'.$base_asset.'"',
										"url('$base_asset')",

									);
									//pree($search_array);

									$replace_array = array(
										'href="'.$base_asset_url.'"',
										'src="'.$base_asset_url.'"',
										"url('$base_asset_url')",
									);

									foreach ($search_array as $search_key => $search_value) {
										# code...
										$coming_soon_content = str_replace($search_value, $replace_array[$search_key], $coming_soon_content);								
									}


								}
								
							}
							
						}
						//pree($coming_soon_content);exit;
					}
					
					
				//pree($wpc_asset_url);
				$script = '
			
					<script type="text/javascript" language="javascript">
						
						
						jQuery(document).ready(function($){
							
							
	
							var all_images = $("img");
	
							$.each(all_images, function(){
	
								var src = $(this).attr("src");
								if(src.substr(0,4)!="http"){
									$(this).attr("src", "'.$wpc_asset_url.'"+src);
								}
	
							});
							
						});
	
					</script>
								
				';
	
				if($current_bg_url){
	
					$script .= '
					
						<style type="text/css">
	
							body{
								background: url("'.$current_bg_url.'");
								background-size: 100% 100%;
								background-repeat: no-repeat;
							}
	
							.main{
								background-image: url("'.$current_bg_url.'") !important;
								background-size: 100% 100%;
								background-repeat: no-repeat;
								
							}
	
						</style>
					
					';
	
				}
	
				$script .= "</html>";
	
				$coming_soon_content = str_replace('</html>', $script, $coming_soon_content);

					 
				}

			}		
	

			return $coming_soon_content;

		}
	}

	if(!function_exists('woo_cs_render_home_file')){
		function woo_cs_render_home_file(){
			global $woo_cs_dir;
			$cs_index_file = $woo_cs_dir.'inc/templates/index.php'; 
			$cs_actual_index_file = $woo_cs_dir.'inc/templates/actual_index.php'; 
	
			$home_dir = get_home_path();
	
			$home_file = $home_dir.'index.php';
			if(file_exists($home_file) && file_exists($cs_index_file)){
	
				$home_file_content = file_get_contents($home_file);
				$cs_file_content = file_get_contents($cs_index_file);
	
				$cs_reg = '/\$woo_cs_function_frontend/';
				$is_home_with_cs = preg_match($cs_reg, $home_file_content);
	
				if(!$is_home_with_cs){
	
					copy($home_file, $cs_actual_index_file);
					file_put_contents($home_file, $cs_file_content);
	
				}	
				
			}
		}
	}


	
	function woo_cs_demo_styles(){
		$remote_demo = 'http://androidbubble.com/html/';
		global $woo_cs_dir, $woo_cs_url;
		$demo_dir = $woo_cs_dir.'img/cs/';
		//pree($demo_dir);
		$ret = '<div class="alert alert-warning mt-3 d-inline-block float-left w-100" role="alert">'.__('Please install/activate WordPress Plugin Chameleon to use the following coming soon pages?', 'woo-coming-soon').'</div><ul class="woo_cs_demo_pages">';
		if ($handle = opendir($demo_dir)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$ret .= '<li><a href="'.$remote_demo.str_replace(array('.jpg', '.png', '.gif'), '', $entry).'" target="_blank"><img src="'.$woo_cs_url.'img/cs/'.$entry.'" /></a></li>';
				}
			}
			closedir($handle);
		}
		$ret .= '</ul>
  <div class="alert alert-secondary d-inline-block mt-3 w-100" role="alert">
 '.__('WordPress Plugin Chameleon is safe to use and it is compatbile with Coming Soon plugin.', 'woo-coming-soon').'
</div>
';
		
		return $ret;
	}
	
	