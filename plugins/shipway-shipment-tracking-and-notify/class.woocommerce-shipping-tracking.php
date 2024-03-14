<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WooCommerce_Shipping_Tracking')) {
    
    
    class WooCommerce_Shipping_Tracking
    {
        
        /**
         * @var $_panel Panel Object
         */
        protected $_panel;
        
        private $options;
        
        public function __construct()
        {
            session_start();
            $this->initialize_settings();
            
            add_action('admin_menu', array(
                $this,
                'add_plugin_page'
            ));
            add_action('admin_init', array(
                $this,
                'page_init'
            ));
            add_action('add_meta_boxes', array(
                $this,
                'add_order_tracking_metabox'
            ));
            add_action('woocommerce_process_shop_order_meta', array(
                $this,
                'save_order_tracking_metabox'
            ), 10);
        }
        
        
        /**
         * Set values from plugin settings page
         */
        public function initialize_settings()
        {
            $this->default_carrier     = get_option('ship_carrier_default_name');
            $this->order_text_position = get_option('ship_order_tracking_text_position');
        }
        
        
        
        
        function add_order_tracking_metabox()
        {
            $userdata = get_option('my_option_name');
            if ($userdata) {
                add_meta_box('order-tracking-information', __('Order tracking', 'ship'), array(
                    $this,
                    'show_order_tracking_metabox'
                ), 'shop_order', 'side', 'high');
            }
        }
        
        
        
        function show_order_tracking_metabox($post)
        {
            
            $userdata = get_option('my_option_name');
            if ($userdata) {
                
                $data                = get_post_custom($post->ID);
                $order_tracking_code = isset($data['ship_tracking_code'][0]) ? $data['ship_tracking_code'][0] : '';
                $order_carrier_name  = isset($data['ship_courier_name'][0]) ? $data['ship_courier_name'][0] : '';
                
                
                if ($order_tracking_code != '' && $order_carrier_name != '') {
?>
			
				<div class="track-information">
				<p>
					<label for="ship_tracking_code"> <?php
                    _e('Tracking code:', 'ship');
                ?></label><?php
                    echo $order_tracking_code; ?>
					
				</p>
				
					<p>
				<label for="ship_courier_name"> <?php
                    _e('Courier:', 'ship'); ?></label>
					
					<?php
                    
                    global $wpdb;
                    
                    $table_name = $wpdb->prefix . "sw_couriers";
                    
                    $getretrieve_data = $wpdb->get_results("SELECT name  FROM $table_name where courier_id = '" . $order_carrier_name . "' ");
                    
                    
                    foreach ($getretrieve_data as $getretrieveeee_data) {
                        echo $getretrieveeee_data->name;
                    }
                    ?></p>
				
				</div>
	        <?php
                } else { ?>
			<div class="track-information">
				<p>
					<label for="ship_tracking_code"> <?php
                    _e('Tracking code:', 'ship'); ?></label>
					<br/>
					<input style="width: 100%" type="text" name="ship_tracking_code" id="ship_tracking_code"
					       placeholder="<?php
                    _e('Enter tracking code', 'ship'); ?>"
					       value="<?php
                    echo $order_tracking_code; ?>"/>
				</p>

				<p>
					<label for="ship_courier_name"> <?php
                    _e('Courier:', 'ship'); ?></label>
					<br/>
					<select id="ship_courier_name" name="ship_courier_name">
					<?php
                    
                    global $wpdb;
                    
                    $table_name = $wpdb->prefix . "sw_couriers";
                    
                    $retrieve_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY name ASC"); ?>
                    <option value="" >Select..</option>
					<?php
                    foreach ($retrieve_data as $retrieved_data) { ?>
						<option value="<?php
                        echo $retrieved_data->courier_id; ?>"><?php
                        echo $retrieved_data->name; ?> </option>
					<?php
                    } ?>
					
					</select>
					</p>

					</div>
		<?php
                }
            }
        }
        
        
        
        function save_order_tracking_metabox($post_id)
        {
            $post_id = $post_id;
            $key     = 'ship_tracking_code';
            $single  = TRUE;
            if (get_post_meta($post_id, $key, $single)) {
                
                $a = get_option('my_option_name');
                
                
            } else {
                
                $a = get_option('my_option_name');
                
                if (isset($_POST['ship_tracking_code']) && $_POST['ship_tracking_code'] != '') {
                    $data               = array();
					 $order_no = get_post_meta(get_the_ID(),'_order_number_formatted',true);
		   
					if (empty($order_no)) {
					$orderid = $_POST['ID'];
					} else {
					$orderid = $order_no;
					}
                    $company            = get_option('blogname');
                    $data['first_name'] = $_POST['_billing_first_name'];
                    $data['last_name']  = $_POST['_billing_last_name'];
                    $data['email']      = $_POST['_billing_email'];
                    $data['phone']      = $_POST['_billing_phone'];
                    
                    $data['company'] = $company;
                    
                    $data['carrier_id'] = $_POST['ship_courier_name'];
                    $data['order_id']   = $orderid;
                    $data['awb']        = $_POST['ship_tracking_code'];
                    $data['username']   = $a['id_number'];
                    $data['password']   = $a['title'];
                    
                    $order = new WC_Order($_POST['ID']);
                    $items = $order->get_items();
                    
                    $data['products'] = '';
                    
                    $products = array();
                    
                    foreach ($items as $key => $product) {
                        $products[$key]['product_id'] = $product['product_id'];
                        $products[$key]['name']       = $product['name'];
                        $products[$key]['price']      = $product['line_total'];
                        $products[$key]['quantity']   = $product['qty'];
                        $products[$key]['url']        = post_permalink($product['product_id']);
                        $data['products'] .= $product['name'] . " ";
                    }
					
					
                    
                    $order_details['order_id']   = $orderid;
                    $order_details['order_date'] = $_POST['order_date'];
                    $order_details['firstname']  = $_POST['_billing_first_name'];
                    $order_details['lastname']   = $_POST['_billing_last_name'];
                    $order_details['email']      = $_POST['_billing_email'];
                    $order_details['phone']      = $_POST['_billing_phone'];
                    $order_details['address']    = $_POST['_billing_address_1'];
                    $order_details['city']       = $_POST['_billing_city'];
                    $order_details['state']      = $_POST['_billing_state'];
                    $order_details['zipcode']    = $_POST['_billing_postcode'];
                    $order_details['country']    = WC()->countries->countries[$_POST['_billing_country']];
					
                     $order_details['country_code'] =   $_POST['_billing_country'];
                    $order_details['products'] = $products;
                    $payment_type              = 'P';
                    if (strtolower(trim($_POST['_payment_method'])) == 'cash on delivery') {
                        $payment_type = 'C';
                    }
                    $order_details['payment_type']       = $payment_type;
                    $order_details['collectable_amount'] = ($payment_type == 'C') ? $_POST['_order_total'] : 0;
                    $order_details['payment_method']     = $_POST['_payment_method'];
                    $order_details['amount']             = $_POST['_order_total'];
                    $order_details['return_address']     = '';
                     
                  
                    
                    $data['order'] = $order_details;
                    $data['country_code'] = $_POST['_billing_country'];
					$data['store_code'] = 'woocommerce';
					
						$data['country'] =	WC()->countries->countries[$_POST['_billing_country']];
			//	echo '<pre>'; print_r($data);die;
                    $url = "http://shipway.in/api/pushOrderData";
                    
                    $data_string = json_encode($data);
                    
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        'Content-Type:application/json'
                    ));
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    
                    $output = curl_exec($curl);
                    $output = json_decode($output);
                    curl_close($curl);
                    
                    
                }
				
				if (isset($_POST['ship_tracking_code'])) {
					delete_post_meta($post_id, 'ship_tracking_code');
					add_post_meta($post_id, 'ship_tracking_code', stripslashes($_POST['ship_tracking_code']));
				}

				if (isset($_POST['ship_courier_name'])) {
					//update_post_meta($post_id, 'ship_courier_name', stripslashes($_POST['ship_courier_name']));
					delete_post_meta($post_id, 'ship_courier_name');
					add_post_meta($post_id, 'ship_courier_name', stripslashes($_POST['ship_courier_name']));
				}
                
            }
            
            
        }
        
        public function add_plugin_page()
        {
            add_menu_page('Shipway', 'Shipway', 'manage_options', 'my-setting-admin', array(
                $this,
                'create_admin_page'
            ), '', 20);
            
        
        }
        
        /**
         * Options page callback
         */
        public function create_admin_page()
        {
            if (isset($_SESSION["errormsg"])) {
                $error = $_SESSION["errormsg"];
                echo $error;
                unset($_SESSION["errormsg"]);
            } else {
                $error = "";
            }
            
            if (isset($_SESSION["succesmsg"])) {
                $succesmsg = $_SESSION["succesmsg"];
                echo $succesmsg;
                unset($_SESSION["succesmsg"]);
            } else {
                $succesmsg = "";
            }
            
            if (isset($_SESSION["couriersuccesmsg"])) {
                $couriermessage = $_SESSION["couriersuccesmsg"];
                echo $couriermessage;
                unset($_SESSION["couriersuccesmsg"]);
            } else {
                $couriermessage = "";
            }
            
            // Set class property
            $this->options = get_option('my_option_name');
?>
        <div class="wrap">
            <h2>Shipway</h2>    
		
            <form method="post" id="form1"  action="options.php">

            <?php
            // This prints out all hidden setting fields
            settings_fields('my_option_group');
            do_settings_sections('my-setting-admin');
            submit_button();
?>
						
           <input type="submit" name="syncouriers" id="syncouriers" class="button button-primary" value="Sync Couriers">
            </form>
			
			    
        </div>
        <?php
        }
        
        /**
         * Register and add settings
         */
        public function page_init()
        {
            register_setting('my_option_group', // Option group
                'my_option_name', // Option name
                array(
                $this,
                'sanitize'
            ) // Sanitize
                );
            
             add_settings_section('setting_section_id', // ID
                'My Custom Settings', // Title
                array(
                $this,
                'print_section_info'
            ), // Callback
                'my-setting-admin' // Page
                );
            
            add_settings_field('id_number', // ID
                'Login Id', // Title 
                array(
                $this,
                'id_number_callback'
            ), // Callback
                'my-setting-admin', // Page
                'setting_section_id' // Section           
                );
            
            add_settings_field('title', 'Licence Key', array(
                $this,
                'title_callback'
            ), 'my-setting-admin', 'setting_section_id');
        }
        
        /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function sanitize($input)
        {
            
            $new_input = array();
            
            if ($_REQUEST['syncouriers']) {
                $a = get_option('my_option_name');
                
                $username = $a['id_number'];
                $password = $a['title'];
                
                if (isset($a['id_number']))
                    $new_input['id_number'] = $a['id_number'];
                
                if (isset($a['title']))
                    $new_input['title'] = $a['title'];
                
                global $wpdb;
                
                $countresults = $wpdb->get_results('SELECT count(courier_id) as total FROM ' . $wpdb->prefix . 'sw_couriers');
                foreach ($countresults as $courcount) {
                    
                }
                $oldresultcount = $courcount->total;
                $couriers       = $this->courrier_tracking();
                
                $newresultcount = count($couriers['couriers']);
                
                if ($newresultcount > $oldresultcount) {
                    
                    $wpdb->query('DELETE  FROM ' . $wpdb->prefix . 'sw_couriers');
                    
                    $couriers = $this->courrier_tracking();
                    
                    $table_name = $wpdb->prefix . 'sw_couriers';
                    
                    foreach ($couriers['couriers'] as $cour) {
                        
                        $wpdb->insert($table_name, array(
                            'courier_id' => $cour['id'],
                            'name' => $cour['courier_name']
                            
                        ));
                        
                    }
                    
                }
                $_SESSION["couriersuccesmsg"] = '<div style="color: #fff;background: #11a739;text-align: center;padding: 12px;font-size: 16px;margin-top: 10px;">Couriers Synced Successfully !</div>';
                
                
                return $new_input;
                
                
                
            } elseif ($_REQUEST['submit']) {
                
                $url         = "http://shipway.in/api/authenticateUser";
                $data_string = array(
                    "username" => $input['id_number'],
                    "password" => $input['title']
                );
                $data_string = json_encode($data_string);
                $curl        = curl_init();
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type:application/json'
                ));
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $output = curl_exec($curl);
                curl_close($curl);
                
                $output = json_decode($output);
                
                if (isset($output->status) && strtolower($output->status) == 'success') {
                    
                    if (isset($input['id_number']))
                        $new_input['id_number'] = $input['id_number'];
                    
                    if (isset($input['title']))
                        $new_input['title'] = $input['title'];
                    
                    
                    $couriers = $this->courrier_tracking();
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'sw_couriers';
                    
                    foreach ($couriers['couriers'] as $cour) {
                        $wpdb->insert($table_name, array(
                            'courier_id' => $cour['id'],
                            'name' => $cour['courier_name']
                            
                        ));
                        
                    }
                    
                    $_SESSION["succesmsg"] = '<div style="color: #fff;background: #11a739; text-align: center;  padding: 12px;font-size: 16px;margin-top: 10px;">Credentials Saved Successfully !</div>';
                    return $new_input;
                    
                } else {
                    
                    $a = get_option('my_option_name');
                    
                    $username = $a['id_number'];
                    $password = $a['title'];
                    
                    if (isset($a['id_number']))
                        $new_input['id_number'] = $a['id_number'];
                    
                    if (isset($a['title']))
                        $new_input['title'] = $a['title'];
                    
                    $_SESSION["errormsg"] = '<div style="color: #fff;background: #a72011;text-align: center; padding: 12px; font-size: 16px;margin-top: 10px;">Credentials Seems to be Wrong !</div>';
                    
                    
                    
                    return $new_input;
                    
                }
                
            }
            
        }
        
        /** 
         * Print the Section text
         */
        public function print_section_info()
        {
            print '<td colspan="2">
					<a href="http://shipway.in/admin/index.php/auth/register" target="_blank" style="background-color: #2eade0;color: #ffffff;text-decoration: none;padding: 4px;border: thin solid #ababab;">Register here</a> 
					<span style="font-size:14px;">for free courier tracking.</span> </td>';
        }
        
        
        
        
        /** 
         * Get the settings option array and print one of its values
         */
        public function id_number_callback()
        //echo '<pre>'; print_r($_POST);die;
        {
            printf('<input type="text" id="id_number" name="my_option_name[id_number]" value="%s"  required="required"/>', isset($this->options['id_number']) ? esc_attr($this->options['id_number']) : '');
            
            
            
        }
        
        /** 
         * Get the settings option array and print one of its values
         */
        public function title_callback()
        {
            printf('<input type="text" id="title" name="my_option_name[title]" value="%s"  required="required"/>', isset($this->options['title']) ? esc_attr($this->options['title']) : '');
        }
        public function courrier_tracking()
        {
            $a = get_option('my_option_name');
            
            $username    = $a['id_number'];
            $password    = $a['title'];
            $new_input   = array();
            $url         = "http://shipway.in/api/carriers";
            $data_string = array(
                "username" => $username,
                "password" => $password
            );
            $data_string = json_encode($data_string);
            $curl        = curl_init();
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json'
            ));
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $output = curl_exec($curl);
            curl_close($curl);
            
            $output = json_decode($output, true);
            
            if (isset($output->status) && strtolower($output->status) == 'success') {
                
                if (isset($input['id_number']))
                    $new_input['id_number'] = $input['id_number'];
                
                if (isset($input['title']))
                    $new_input['title'] = $input['title'];
                
            } else {
                
                
            }
            return $output;
        }
        
    }
}