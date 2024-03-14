<?php

/**
 * Webtoffee Security Library
 *
 * Includes Data sanitization, Access checking
 * @author     WebToffee <info@webtoffee.com>
 */

if(!class_exists('EH_Helper_Class'))
{

	class EH_Helper_Class 
	{

		/**	
		* 	Data sanitization function.
		*	
		*	@param mixed $val value to sanitize
		*	@param string $key array key in the validation rule
		*	@param array $validation_rule array of validation rules. Eg: array('field_key' => array('type' => 'textarea'))
		*	@return mixed sanitized value
		*/
		public static function sanitize_data($val, $key, $validation_rule = array())
		{		
			if(isset($validation_rule[$key]) && is_array($validation_rule[$key])) /* rule declared/exists */
			{
				if(isset($validation_rule[$key]['type']))
				{
					$val = self::sanitize_item($val, $validation_rule[$key]['type']);
				}
			}else //if no rule is specified then it will be treated as text
			{
				$val = self::sanitize_item($val, 'text');
			}
			return $val;
		}


		/**
		* 	Sanitize individual data item
		*
		*	@param mixed $val value to sanitize
		*	@param string $type value type
		*	@return mixed sanitized value
		*/
		public static function sanitize_item($val, $type='')
		{
			switch ($type) 
			{
			    case 'text':
			        $val = sanitize_text_field($val);
			        break;
			    case 'text_arr':
			        $val = self::sanitize_arr($val);
			        break;
			    case 'url':
			        $val = esc_url_raw($val);
			        break;
			    case 'url_arr':
			        $val = self::sanitize_arr($val, 'url');
			        break;
			    case 'textarea':
			        $val=sanitize_textarea_field($val);
			        break;    
			    case 'int':
			        $val = intval($val);
			        break;
			    case 'int_arr':
			        $val = self::sanitize_arr($val, 'int');
			        break;
			    case 'float':
			        $val = floatval($val);
					break;
				case 'post_content':
			        $val = wp_kses_post($val);
					break;
				case 'hex':
			        $val = sanitize_hex_color($val);
			        break;
			    default:
			        $val = sanitize_text_field($val);
			} 

			return $val;
		}

		/**
		* 	Recursive array sanitization function
		*
		*	@param mixed $arr value to sanitize
		*	@param string $type value type
		*	@return mixed sanitized value
		*/
		public static function sanitize_arr($arr, $type = 'text')
		{
			if(is_array($arr))
			{
				$out = array();
				foreach($arr as $k=>$arrv)
				{
					if(is_array($arrv))
					{
						$out[$k] = self::sanitize_arr($arrv, $type);
					}else
					{
						$out[$k] = self::sanitize_item($arrv, $type);
					}
				}
				return $out;
			}else
			{
				return self::sanitize_item($arr, $type);
			}
		}

		/**
		* 	User accessibility. Function checks user logged in status, nonce and role access.
		*
		*	@param string $plugin_id unique plugin id. Note: This id is used as an identifier in filter name so please use characters allowed in filters 
		*	@param string $nonce_id Nonce id. If not specified then uses plugin id
		*	@return boolean if user allowed or not
		*/
		public static function check_write_access($plugin_id, $nonce_id = '')
		{
			$er = true;
			
	    	if(!is_user_logged_in()) //checks user is logged in
	    	{
	    		$er = false;
	    	}

	    	if($er === true) //no error then proceed
	    	{
	    		if(!(self::verify_nonce($plugin_id, $nonce_id))) //verifying nonce
		        {
		            $er = false;
		        }else
		        {
		        	if(!self::check_role_access($plugin_id)) //Check user role
		            {
		            	$er = false;
		            }
		        }
	    	}
	    	return $er;
		}

		/**
		* 	Verifying nonce
		*
		*	@param string $plugin_id unique plugin id. Note: This id is used as an identifier in filter name so please use characters allowed in filters 
		*	@param string $nonce_id Nonce id. If not specified then uses plugin id
		*	@return boolean if user allowed or not
		*/
		public static function verify_nonce($plugin_id, $nonce_id = '')
		{
			$nonce = (isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '');
    		$nonce = (is_array($nonce) ? $nonce[0] : $nonce); //in some cases multiple nonces are declared
    		$nonce_id = ($nonce_id == "" ? $plugin_id : $nonce_id); //if nonce id not provided then uses plugin id as nonce id
    		
    		if(!(wp_verify_nonce($nonce, $nonce_id))) //verifying nonce
		    {
		    	return false;
		    }else
		    {
		    	return true;
		    }
		}


		/**
		* 	Checks if user role has access
		*
		*	@param string $plugin_id unique plugin id. Note: This id is used as an identifier in filter name so please use characters allowed in filters 
		*	@return boolean if user allowed or not
		*/
		public static function check_role_access($plugin_id)
		{
			$roles = array('manage_options'); 
	    	$roles = apply_filters('wt_'.$plugin_id.'_alter_role_access', $roles); //dynamic filter based on plugin id to alter roles 
	    	$roles = (!is_array($roles) ? array() : $roles);
	    	$is_allowed = false;

	    	foreach($roles as $role) //loop through roles
	    	{
	    		if(current_user_can($role))  
	    		{
	    			$is_allowed = true;
	    			break;
	    		}
	    	}
	    	return $is_allowed;
		}

		/**
		* 	Checks if HPOS is enabled
		*
		*	@param string $method this indicate the db operation whether to get, insert or update order data
		*	@param string $meta_key this indicate the order/post meta key
		*	@param string $meta_value this indicate the order/post meta value
		*	@return db operation output
		*/

		static function wt_stripe_order_db_operations($order_id, $order, $method, $meta_key, $meta_value=null, $single=true)
		{
			$result = '';
			if(version_compare(WC_VERSION, '2.7.0', '>') && true === EH_Stripe_Payment::wt_stripe_is_HPOS_compatibile()){ 
				$order = wc_get_order($order_id);
				switch (strtolower($method)) {
					case 'get':
						$result = $order->get_meta( $meta_key, $single );
						
						if (!$single && is_array($result)) {
							$result = array_map(function ($meta) {
								return $meta instanceof WC_Meta_Data ? $meta->get_data()['value'] : $meta;
							}, $result);
						}
							if(isset($result[0]->value)){
								//$result = $result[0]->value;
							}
						 
						break;
					case 'add':
						$result = $order->add_meta_data( $meta_key, $meta_value );

						break;
					case 'update':
						$result = $order->update_meta_data( $meta_key, $meta_value );
						break;
					
					default:
						// code...
						break;
				}
				$order->save();
			}
			else{
				switch (strtolower($method)) {
					case 'get':
						$result = get_post_meta($order_id, $meta_key, $single);
						break;
					case 'add':
						$result = add_post_meta($order_id, $meta_key, $meta_value);
						break;
					case 'update':
						$result = update_post_meta($order_id, $meta_key, $meta_value);
						break;
					
					default:
						// code...
						break;
				}
			} 
			return $result;
		}		
		
	}
}