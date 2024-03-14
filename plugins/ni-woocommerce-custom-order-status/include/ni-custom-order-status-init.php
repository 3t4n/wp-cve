<?php
if (!defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_custom_order_status_init' ) ) {
	class ni_custom_order_status_init {
		
		var $vars =  array(); 
		public function __construct(){
			
			add_action( 'admin_menu',  array(&$this,'admin_menu' ));
			add_action( 'admin_init',  array( &$this, 'admin_init' ));
			
			/*Register Status*/
			add_filter( 'init',  array(&$this,'init' ));
			
			/*Add Status Order*/
			add_filter( 'wc_order_statuses',  array(&$this,'ni_wc_add_order_statuses') );
			
			
			/*3rd Save Post*/
			add_action( 'save_post', array( &$this, 'save_post'), 10, 2 );
			
			add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ));
			
			/*For Order Status Color and Circle*/
			add_action('admin_head', array(&$this,'admin_head'));
		
			/*Add Custom Columns into list page*/	
			add_filter( 'manage_edit-ni-order-status_columns',  array( &$this,'order_status_list_columns' ));
			
			/*Add Value to Columns*/
			add_action( 'manage_posts_custom_column', array( &$this,'order_status_list_columns_value' ), 10, 2 );
			
			/*Add Next Action*/
			add_action( 'woocommerce_admin_order_actions', array( &$this,'ni_next_order_actions' ), 10, 2 );
			
			
			add_action( 'wp_ajax_niwoocos_ajax',  array(&$this,'niwoocos_ajax' )); /*used in form field name="action" value="my_action"*/
			
		}
		function admin_enqueue_scripts(){
			global $typenow;
			if(  $typenow == 'ni-order-status' ) {
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'ajax-script-ni-custom-color', plugins_url( '../js/ni-custom-color-script.js', __FILE__ ), array('wp-color-picker' ) );
				
				wp_register_style('ni-custom-order-status-style', plugins_url( '../assets/css/ni-custom-order-status-style.css', __FILE__ ));
				wp_enqueue_style('ni-custom-order-status-style' );
			}
			
			 if (isset($_REQUEST["page"])){
		     	$page = sanitize_text_field($_REQUEST["page"]);
				if ($page == "ni-custom-order-status-report" || $page == "niwoocos-other-plugins" || $page ==  "niwoocos-order-status-report"){
					
					wp_register_style( 'ni-sales-report-summary-css', plugins_url( '../assets/css/ni-sales-report-summary.css', __FILE__ ));
		 			wp_enqueue_style( 'ni-sales-report-summary-css' );
					
					wp_register_style( 'ni-font-awesome-css', plugins_url( '../assets/css/font-awesome.css', __FILE__ ));
		 			wp_enqueue_style( 'ni-font-awesome-css' );
				
					
					wp_register_style('nicos-bootstrap-css', plugins_url('../assets/css/lib/bootstrap.min.css', __FILE__ ));
		 			wp_enqueue_style('nicos-bootstrap-css' );
				
					wp_enqueue_script('nicos-bootstrap-script', plugins_url( '../assets/js/lib/bootstrap.min.js', __FILE__ ));
					wp_enqueue_script('nicos-popper-script', plugins_url( '../assets/js/lib/popper.min.js', __FILE__ ));
					
					
					
					if ( $page ==  "niwoocos-order-status-report"){
						wp_register_script( 'niwoocos-order-status-report-script', plugins_url( '../js/ni-order-status-report.js', __FILE__ ) );
						wp_enqueue_script('niwoocos-order-status-report-script');
						
						wp_enqueue_script( 'niwoocos-script', plugins_url( '../js/script.js', __FILE__ ), array('jquery') );	
						wp_localize_script( 'niwoocos-script','niwoocos_ajax_object',array('niwoocos_ajaxurl'=>admin_url('admin-ajax.php')));
					}
					
				}
			 }
			
		}
		function niwoocos_ajax(){
			$sub_action = sanitize_text_field(isset($_REQUEST['sub_action'])?$_REQUEST['sub_action']:'');
			if ($sub_action  =='order_status_report'){
				
				include_once('ni-order-status-report.php');
				$obj = new Ni_Order_Status_Report();
				$obj->ajax_init();
			}
		}
		function admin_menu(){
			add_menu_page('Order Status', 'Order Status', 'manage_options', 'ni-custom-order-status',  array(&$this,'add_page'),  plugins_url( '../images/icon.png', __FILE__ ),14);
			
			add_submenu_page('ni-custom-order-status', 'Dashboard', 'Dashboard', 'manage_options', 'ni-custom-order-status-report' , array(&$this,'add_page'));
			
			add_submenu_page('ni-custom-order-status', 'Order Status Report', 'Order Status Report', 'manage_options', 'niwoocos-order-status-report' , array(&$this,'add_page'));
			
			//add_submenu_page('ni-custom-order-status', 'Setting', 'Setting', 'manage_options', 'niwoocos-setting' , array(&$this,'add_page'));
				
			add_submenu_page('ni-custom-order-status', 'Other Plugins', 'Other Plugins', 'manage_options', 'niwoocos-other-plugins' , array(&$this,'add_page'));
			
		}
		function admin_head(){
			global $typenow;
			
			if($typenow == 'ni-order-status' || $typenow == 'shop_order' ) {
				
				 /*Hide Permalink*/				 
				$output = "";
				$output .= "<style>#edit-slug-box {display:none;}</style>";	 
				$custom_order_status = $this->get_custom_post_type_order_status();
				
				foreach ($custom_order_status as $k => $v) { 
					$color =	$v["ni_order_status_color"];
					$slug =	$v["ni_order_status_slug"];
					$output .= '<style>
					mark.status-'.$slug.'{
							background-color:'. $color .';
							color:#FFFFFF;
					}
					</style>';
				}
				print($output);
			}
		}
		function add_page(){		
			if ( isset($_REQUEST["page"])){
				$page = sanitize_text_field($_REQUEST["page"]);
				if ($page =="ni-custom-order-status-report"){
					include_once("ni-custom-order-status-report.php");
					$obj = new ni_custom_order_status_report();
					$obj->page_init();
				}
				if ($page=="niwoocos-other-plugins")
				{
					include_once("ni-addons-order-custom.php");
					$obj =  new ni_addons_order_custom();
					$obj->page_init();
					
				}
				if ($page  =="niwoocos-order-status-report"){
					include_once("ni-order-status-report.php");
					$obj =  new Ni_Order_Status_Report();
					$obj->page_init();
				}
				if ($page  == "niwoocos-setting"){
					include_once("ni-custom-order-status-setting.php");
					$obj =  new ni_custom_order_status_setting();
					$obj->page_init();
				}
			}
			//echo "To Do..........!";	
			
		}
		function admin_init(){
			$this->add_meta_box();
		}
		function ni_wc_register_post_statuses(){
			
			$custom_order_status = $this->get_custom_post_type_order_status();
			
			/*Loop For Custom Order Status*/
			foreach ($custom_order_status as $k => $v) { 
			
					$id = preg_replace('#[ -]+#', '-', $v["ni_order_status_slug"]);
					$id = "wc-".strtolower($id);
					$label =  $v["ni_order_status_title"];
				
				register_post_status( trim($id), array(
					'label'                     => _x( $label, 'WooCommerce Order status', 'niwoocos' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					'label_count'               => _n_noop( $label.' <span class="count">(%s)</span>', $label.'<span class="count">(%s)</span>' )
				) );
			}
		}
		function ni_wc_add_order_statuses( $order_statuses ) {
			
			$custom_order_status = $this->get_custom_post_type_order_status();
			
			foreach ($custom_order_status as $k => $v) { 
				$id = preg_replace('#[ -]+#', '-', $v["ni_order_status_slug"]);
				$id = "wc-".strtolower($id);
				$label =  $v["ni_order_status_title"];
				
				$order_statuses[trim($id)] = _x( $label, 'WooCommerce Order status','niwoocos' );
			}
			
			return $order_statuses;
		}
		function init(){
			$this->ni_register_order_status_post_type();
			$this->ni_wc_register_post_statuses();
		}
		function ni_register_order_status_post_type(){
		
			register_post_type( 'ni-order-status', /*Name of Custome Post Type */
					array(
						'labels' => array(
							'name' 				 => esc_html__('Order Status','niwoocos'),
							'singular_name' 	 => esc_html__('Order Status','niwoocos'),
							'add_new' 			 => esc_html__('Add New','niwoocos'),
							'add_new_item' 		 => esc_html__('Add New Order Status','niwoocos'),
							'edit' 				 => esc_html__('Edit','niwoocos'),
							'edit_item' 		 => esc_html__('Edit Order Status','niwoocos'),
							'new_item' 			 => esc_html__('New Order Status','niwoocos'),
							'view' 				 => esc_html__('View','niwoocos'),
							'view_item' 		 => esc_html__('View Order Status','niwoocos'),
							'search_items' 		 => esc_html__('Search Order Status','niwoocos'),
							'not_found' 		 => esc_html__('No order status found','niwoocos'),
							'not_found_in_trash' => esc_html__('No Order Status found in Trash','niwoocos'),
							'parent' 			 => esc_html__('Parent Order Status','niwoocos'),
						),
						'public' => true,
						'show_in_menu' => 'ni-custom-order-status',
						'menu_position' => 15,
						'supports' => array( 'title'),
						'taxonomies' => array( '' ),
						'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
						'has_archive' => false,
						'publicly_queryable' => false,
						'rewrite' => NULL
						
					)
				);
		}
		/*display Meta Box*/
		function ni_display_custom_order_status_meta_box($order_status)
		{
			// Retrieve current slug and color base on the post id
			$order_status_slug = esc_html( get_post_meta( $order_status->ID, '_ni_order_status_slug', true ) );
			
			$order_status_color = esc_html( get_post_meta( $order_status->ID, '_ni_order_status_color', true ) );
			
			$order_status_description = esc_html( get_post_meta( $order_status->ID, '_ni_order_status_description', true ) );
			$ni_order_status_email_content = esc_html( get_post_meta( $order_status->ID, '_ni_order_status_email_content', true ) );
			$ni_order_status_email_enable = esc_html( get_post_meta( $order_status->ID, '_ni_order_status_email_enable', true ) );
			$ni_order_status_subject_line = esc_html( get_post_meta( $order_status->ID, '_ni_order_status_subject_line', true ) );
			
			?>
			<table class="status_fields" style="padding:10px;">
				<tr>
					<th style="padding-top:15px;"><label for="_ni_order_status_slug" class="ni-order-status-slug"><?php esc_html_e( 'Order Status Slug', 'niwoocos' )?></label></th>
					<td style="padding-top:15px;"><input type="text" size="30" name="_ni_order_status_slug" id="_ni_order_status_slug" value="<?php echo sanitize_key($order_status_slug); ?>" maxlength="18"  /></td>
				</tr>
				<tr>
					<th style="padding-top:15px;"><label for="_ni_order_status_color" class="prfx-row-title"><?php esc_html_e( 'Color Picker', 'niwoocos' )?></label></th>
					<td style="padding-top:15px;"><input name="_ni_order_status_color" type="text" value="<?php echo sanitize_hex_color(isset($order_status_color)?$order_status_color: "#ffffff"); ?>"  id="_ni_order_status_color" class="order_status_color" /></td>
				</tr>
                <tr>
					<th style="padding-top:15px;"><label for="_ni_order_status_description" class="ni-order-status-description"><?php esc_html_e( 'Description', 'niwoocos' )?></label></th>
					<td style="padding-top:15px;"><textarea name="_ni_order_status_description" id="_ni_order_status_description" rows="6" cols="15"><?php echo sanitize_textarea_field($order_status_description);?></textarea></td>
				</tr>
                
                <tr>
                	<td colspan="2"> <hr /></td>
                </tr>
                
                
                 <tr>
                	<th style="padding-top:15px;"><label for="_ni_order_status_email_enable" class="ni-order-status-email-enable"><?php esc_html_e( 'Enable Email Send', 'niwoocos' )?></label></th>
                    <td style="padding-top:15px;"> <input type="checkbox" id="_ni_order_status_email_enable" name="_ni_order_status_email_enable"  <?php echo ($ni_order_status_email_enable=="yes")?"checked":""; ?> />
                    <span><?php esc_html_e( 'If enabled, then email is sent to the customer when order status changes to this status.', 'niwoocos' )?></span>
                    </td>
                </tr>
                <tr>
					<th style="padding-top:15px;"><label for="_ni_order_status_subject_line" class="ni_order_status_subject_line"><?php esc_html_e( 'Subject line', 'niwoocos' )?></label></th>
					<td style="padding-top:15px;"><input name="_ni_order_status_subject_line" type="text" value="<?php echo  sanitize_text_field ($ni_order_status_subject_line); ?>"  id="_ni_order_status_subject_line"  style="width:600px"  />
                    <br />
                     <span><?php esc_html_e( 'Enter Email subject line', 'niwoocos' )?></span>
                     <?php do_action('ni_custom_order_status_email_subject_footer',$order_status,$order_status_slug);?>
                    </td>
				</tr>
                <tr>
                	<th style="padding-top:15px;"><label for="_ni_order_status_email_content" class="ni-order-status-email-content"><?php esc_html_e( 'Email Content', 'niwoocos' )?></label></th>
                    <td style="padding-top:15px;">
                    <textarea name="_ni_order_status_email_content" id="_ni_order_status_email_content" rows="6" cols="15"><?php echo sanitize_textarea_field($ni_order_status_email_content);?></textarea>
                     <span><?php esc_html_e( 'This email contet(text) is sent to customer when this order status change', 'niwoocos' )?></span>
                     <?php do_action('ni_custom_order_status_email_content_footer',$order_status,$order_status_slug);?>
                    </td>
                </tr>
                 
			</table>
			<?php
		}
		function get_custom_post_type_order_status(){
			
			global $wpdb;	
			$custom_order_status =  array();
			if(!isset($this->vars['custom_order_status'])){
				
				
				$query = "SELECT
						posts.post_title
						,slug.meta_value as ni_order_status_slug
						,color.meta_value as ni_order_status_color
						FROM {$wpdb->prefix}posts as posts		
					
						LEFT JOIN  {$wpdb->prefix}postmeta as slug ON slug.post_id=posts.ID 
						LEFT JOIN  {$wpdb->prefix}postmeta as color ON color.post_id=posts.ID 	
						
						WHERE 
								posts.post_type ='ni-order-status' 
								AND posts.post_status ='publish'
								AND slug.meta_key='_ni_order_status_slug'
								AND color.meta_key='_ni_order_status_color'
								
						";
					$results = $wpdb->get_results($query);	
					
					//echo mysql_error();
					
				//echo '<pre>',print_r($results,1),'</pre>';	
				foreach ($results as $k => $v) { 
					$custom_order_status[$k]["ni_order_status_title"] = $v->post_title;
					$custom_order_status[$k]["ni_order_status_slug"]  = $v->ni_order_status_slug;
					$custom_order_status[$k]["ni_order_status_color"] = isset($v->ni_order_status_color)?$v->ni_order_status_color :  "#ffffff";
					
					
				}
				$this->vars['custom_order_status'] = $custom_order_status;
			}else{
				$custom_order_status = $this->vars['custom_order_status'];
			}
			
			return $custom_order_status;
		}
		function add_meta_box(){
			add_meta_box( 'custom_order_status_meta_box', 
					esc_html__('Custom Order Status Meta Box','niwoocos'),
					array( &$this, 'ni_display_custom_order_status_meta_box'), /*Name of Call Back or Display Meta Box Function*/
					'ni-order-status', /*Custom Post Type Name*/
					'normal', 
					'high'
				);
		}
		
		function save_post($status_id, $status){
			
			if ( $status->post_type == 'ni-order-status' ) {
				// Store data in post meta table if present in post data
				if ( isset( $_POST['_ni_order_status_slug'] ) && $_POST['_ni_order_status_slug'] != '' ) {
					update_post_meta( $status_id, '_ni_order_status_slug', sanitize_key($_POST['_ni_order_status_slug']));
				}
				
				if ( isset( $_POST['_ni_order_status_color'] ) && $_POST['_ni_order_status_color'] != '' ) {
					update_post_meta( $status_id, '_ni_order_status_color', sanitize_hex_color ($_POST['_ni_order_status_color']));
				}else{
					update_post_meta( $status_id, '_ni_order_status_color', "#ffffff" );
				}
				
				if ( isset( $_POST['_ni_order_status_description'] ) && $_POST['_ni_order_status_description'] != '' ) {
					update_post_meta( $status_id, '_ni_order_status_description', sanitize_textarea_field($_POST['_ni_order_status_description']));
				}
				if ( isset( $_POST['_ni_order_status_email_content'] ) && $_POST['_ni_order_status_email_content'] != '' ) {
					update_post_meta( $status_id, '_ni_order_status_email_content', sanitize_textarea_field($_POST['_ni_order_status_email_content']));
				}
				//_ni_order_status_email_enable
				if ( isset( $_POST['_ni_order_status_email_enable'] )){
					update_post_meta( $status_id, '_ni_order_status_email_enable', 'yes');
				}else{
					update_post_meta( $status_id, '_ni_order_status_email_enable', 'no');
				}
				
				if ( isset( $_POST['_ni_order_status_subject_line'] ) && $_POST['_ni_order_status_subject_line'] != '' ) {
					update_post_meta( $status_id, '_ni_order_status_subject_line', sanitize_textarea_field($_POST['_ni_order_status_subject_line']));
				}
				
			}
		}
		/*Columns List*/
		function order_status_list_columns($columns){		
				unset( $columns['date'] );
				
				$columns['_ni_order_status_slug']			= esc_html__('Slug','niwoocos');
				$columns['_ni_order_status_color']			= esc_html__('Color','niwoocos');
				$columns['_ni_order_status_description']	= esc_html__('Description','niwoocos');
				$columns['date'] 							= esc_html__('Date','niwoocos');			   
				return $columns;
		}
		function order_status_list_columns_value($column,$post_id){
			 if ( '_ni_order_status_slug' == $column ) {
					$ni_order_status_slug = esc_html( get_post_meta( get_the_ID(), '_ni_order_status_slug', true ) );
					echo sanitize_text_field($ni_order_status_slug);
			 }
			 if ( '_ni_order_status_description' == $column ) {
					$ni_order_status_description = esc_html( get_post_meta( get_the_ID(), '_ni_order_status_description', true ) );
					echo sanitize_textarea_field($ni_order_status_description);
			 }
			 if ( '_ni_order_status_color' == $column ) {
					$ni_order_status_color = sanitize_hex_color( get_post_meta( get_the_ID(), '_ni_order_status_color', true ) );
					print("<div style=\"height:25px; width:25px; background-color:". $ni_order_status_color."\"></div>");
			 }
		}
		function ni_next_order_actions($actions , $the_order ){				
			global $post;
			
			$custom_order_status_string = "";
			$custom_order_status  = $this->get_custom_post_type_order_status();
			
			$custom_array = array(); 
			foreach($custom_order_status  as $k =>$v){			
				$custom_array[] =  $v["ni_order_status_slug"];
			}
			
			if ( $the_order->has_status( $custom_array) ) {
				$actions['complete'] = array(
					'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=completed&order_id=' . $post->ID ), 'woocommerce-mark-order-status' ),
					'name'      => __( 'Complete', 'niwoocos' ),
					'action'    => "complete"
				);
			}
			return $actions ;
		}
		
	}
}
?>