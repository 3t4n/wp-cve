<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(!class_exists('NEXForms_Database_Actions'))
	{
	class NEXForms_Database_Actions{

/* INSERT */
		public $client_info;
		public $license_info;
		
		public function __construct(){
			
			add_action('wp_ajax_deactivate_license', array($this,'deactivate_license'));
			add_action('wp_ajax_nf_insert_record', array($this,'insert_record'));
			add_action('wp_ajax_nf_update_record', array($this,'update_record'));
			add_action('wp_ajax_nf_delete_record', array($this,'delete_record'));
			add_action('wp_ajax_nf_duplicate_record', array($this,'duplicate_record'));
			add_action('wp_ajax_nf_delete_file', array($this,'delete_file'));
			
			
			add_action('wp_ajax_preview_nex_form', array($this,'preview_nex_form'));
			add_action('wp_ajax_nf_get_forms', array($this,'get_forms'));
			add_action('wp_ajax_nf_load_nex_form', array($this,'load_nex_form'));
			add_action('wp_ajax_nf_get_email_setup', array($this,'get_email_setup'));
			add_action('wp_ajax_nf_get_pdf_setup', array($this,'get_pdf_setup'));
			add_action('wp_ajax_nf_get_options_setup', array($this,'get_options_setup'));
			add_action('wp_ajax_nf_hidden_fields', array($this,'get_form_hidden_fields'));
			add_action('wp_ajax_nf_load_form_entries', array($this,'load_form_entries'));
			add_action('wp_ajax_nf_populate_form_entry', array($this,'populate_form_entry'));
			add_action('wp_ajax_nf_load_pagination', array($this,'load_pagination'));
			
			add_action('wp_ajax_nf_populate_form_entry_dashboard', array($this,'populate_form_entry'));
			
			
			add_action( 'wp_ajax_save_email_config', array($this,'save_email_config'));
			add_action( 'wp_ajax_save_script_config', array($this,'save_script_config'));
			add_action( 'wp_ajax_save_style_config', array($this,'save_style_config'));
			add_action( 'wp_ajax_save_other_config', array($this,'save_other_config'));
			add_action( 'wp_ajax_save_mc_key', array($this,'save_mc_key'));
			add_action( 'wp_ajax_save_gr_key', array($this,'save_gr_key'));
			
			add_action( 'wp_ajax_do_form_import', array($this,'do_form_import'));
			add_action( 'wp_ajax_load_template', array($this,'load_template'));
			
			
			add_action('wp_ajax_nf_load_conditional_logic', array($this,'load_conditional_logic'));
			add_action('wp_ajax_nf_send_test_email', array($this,'nf_send_test_email'));
			
			add_action('wp_ajax_update_paypal', array($this,'update_paypal'));
			add_action('wp_ajax_get_data', array($this,'NEXForms_get_data'));
			add_action('wp_ajax_get_c_logic_ui', array($this,'get_c_logic_ui'));
						
		
		}
		
		public function get_c_logic_ui($form_id=0){
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global $wpdb;
			
			$conditional_logic_array =  (isset($_POST['conditional_logic_array'])) ? $_POST['conditional_logic_array'] : '';
			
				$c_logic = json_encode($conditional_logic_array);
			
			$rules = json_decode($c_logic);
			
			/*echo '<pre>';
			print_r($rules);
			echo '</pre>';*/
			$u_arrows = array();
			$arrows = array();
			$adv_rules = array();
			$output = '';
				$i = 0;
				$a = 0;
			if(is_array($rules))
				{
				foreach($rules as $rule)
					{
					if(is_array($rule->conditions))
						{
						if(count($rule->conditions)==1 && count($rule->actions)==1)
							{
							$rule_operator = $rule->operator;
							$reverse_action = $rule->reverse_actions;
							
							//echo 'COUNT CONS: '. count(get_object_vars($rule->conditions));
							
							
							foreach($rule->conditions as $condition)
								{
								$get_the_condition 	=  $condition->condition;
								$get_the_value 		=  $condition->condition_value;
								$selection_value 	=  $condition->selected_value;
								}
							$targets = array();	
							
							//echo '    COUNT ACTIONS: '. sizeof($rule->actions).'<br /><br />';
								
							foreach($rule->actions as $action)
								{
								$get_action_to_take = $action->do_action;
								$selection_value = $action->selected_value;
								$arrows[$i][$condition->field_Id] = 
										array(
											  'target_id'=>$action->target_field_Id,
											  'action'=>$action->do_action,
											  'condition'=>$condition->condition,
											  'condition_value'=>$condition->condition_value
											 );
								}
							$i++;
							}
						else
							{
							
							$rule_operator = $rule->operator;
							$reverse_action = $rule->reverse_actions;
							
							//echo 'COUNT CONS: '. count(get_object_vars($rule->conditions));
							
							$adv_arrows = array();
							foreach($rule->conditions as $condition)
								{
								$get_the_condition 	=  $condition->condition;
								$get_the_value 		=  $condition->condition_value;
								$selection_value 	=  $condition->selected_value;
								
								//$arrows[$a][$condition->field_Id] = $action->target_field_Id;
								array_push($adv_arrows,
								
								array(
											'arrow_id'=>$condition->field_Id,
											'condition'=>$condition->condition,
											'condition_value'=>$condition->condition_value));
								}
							$adv_targets = array();
							foreach($rule->actions as $action)
								{
								$get_action_to_take = $action->do_action;
								$selection_value = $action->selected_value;
								array_push($adv_targets,
								array(
										'target_id'=>$action->target_field_Id,
										'action'=>$action->do_action)
								);
								}
							$a++;
							$adv_rules[$a]['operator'] = $rule->operator;
							$adv_rules[$a]['arrows'] = $adv_arrows;
							$adv_rules[$a]['targets'] = $adv_targets; 	
							
							}
						}
					}
				}
			
			if(is_array($arrows))
				{
				foreach($arrows as $arrows1)
					{
					foreach($arrows1 as $key=>$arrow)
						{
						$u_arrows[$key] = array();
						}
					}
				}
			if(is_array($u_arrows))
				{
				foreach($u_arrows as $key=>$val)
					{
					foreach($arrows as $arrows4)
						{
						foreach($arrows4 as $key2=>$arrow)
							{
							if($key == $key2)
								array_push($u_arrows[$key2],$arrow);
							}
						}
					}
				}
				$output .= '<div class="con_logic_rules">';
				foreach($u_arrows as $arrow=>$targets)
					{
					$output .= '<div class="the_rule" data-cl-arrow="'.$arrow.'" data-cl-targets=\''.json_encode($targets).'\'></div>';
					}
				foreach($adv_rules as $key => $adv_rule)
					{
					$output .= '<div class="the_adv_rule" data-adv-id="'.$key.'" data-cl-targets=\''.json_encode($adv_rule).'\'></div>';
					}
				$output .= '</div>';
		if($form_id)
			return $output;	
		else
			{	
			echo $output;
			wp_die();
			}
		}
		
		
		
		
		public function checkout()
			{
			if(strstr(get_option('siteurl'),'http://localhost/') && strstr(get_option('siteurl'),'wp6.2.2'))
				{
				$this->client_info = array(
						'Id' => '2526991',
						'item_code' => '7103891',
						'purchase_code' => '*****************************',
						'license_type' => 'Regular License',
						'envato_user_name' => 'Basix',
						'for_site'=>get_option('siteurl')
					)	;
				return true;
				}
				
			if ( function_exists( 'activator_admin_notice_plugin_activate' ) ) {
				 return false;
			 }
			$theme = wp_get_theme();
			if($theme->Name=='NEX-Forms Demo')
				return true;
			$api_params = array( 'check_key' => 1,'ins_data'=>get_option('7103891'));
			$response = wp_remote_post( 'https://basixonline.net/activate-license-new-api-v3', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );

			if(isset($response->errors))
				{
				return false;		
				}
			else
				{
				$get_response = json_decode($response['body'],1);
				
				$this->client_info = $get_response['client_info'];
				$this->license_info = $get_response['license_info'];
				if($get_response['ver']!='true')
					{
					$this->deactivate_license();	
					}
				return ($get_response['ver']=='true') ? true : false;
				}
			}
		
		public function insert_record(){
			
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_new_form_actions' ) ) {
				wp_die();
			}
			if ( function_exists( 'activator_inject_plugins_filter' ) ) {
				 return false;
			 }
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global $wpdb;
			
			$db_table = sanitize_title($_POST['table']);
			
			if(!strstr($db_table, 'nex_forms'))
				wp_die();
			
			$fields 	= $wpdb->get_results('SHOW FIELDS FROM ' . $wpdb->prefix .filter_var($db_table,FILTER_SANITIZE_STRING));
			$field_array = array();
			foreach($fields as $field)
				{
				if(isset($_POST[$field->Field]))
					{
					if(is_array($_POST[$field->Field]))
						{
						$set_field_array = NEXForms_sanitize_array($_POST[$field->Field]);
						$field_array[$field->Field] = json_encode($set_field_array,JSON_FORCE_OBJECT);
						}
					else
						{
						$_POST[$field->Field] = NEXForms_rgba2Hex($_POST[$field->Field]);
						$field_array[$field->Field] = $_POST[$field->Field];
						}
					}	
				}
			$insert = $wpdb->insert ( $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING), $field_array );
			$insert_id = $wpdb->insert_id;
			echo $insert_id;
			
			$theme = wp_get_theme();
			if($theme->Name=='NEX-Forms Demo')
				{
				$post_id = wp_insert_post(
					array(
						'comment_status'	=>	'closed',
						'ping_status'		=>	'closed',
						'post_author'		=>	1,
						'post_name'			=>	'user-test-form-'.$insert_id,
						'post_title'		=>	'User Test Form '.$insert_id,
						'post_status'		=>	'publish',
						'post_type'			=>	'page',
						'post_content'		=>	'[NEXForms id="'.$insert_id.'"]',
						'post_parent'		=>  '11',
					)
				);
				}
			die();
		}
		
/* UPDATE */
		
		
		public function update_record(){
			if ( function_exists( 'activator_admin_notice_plugin_install' ) ) {
				 return false;
			 }
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_update_record' ) ) {
				wp_die();
			}
			if(!is_admin())
				wp_die();
				
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
				
			
			
			global $wpdb;
	
			$db_table = sanitize_title($_POST['table']);
			
			if(!strstr($db_table, 'nex_forms'))
				wp_die();
			
			$edit_id = sanitize_title($_POST['edit_Id']);
			
			$fields 	= $wpdb->get_results('SHOW FIELDS FROM `'. $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING).'`');
			$field_array = array();
			foreach($fields as $field)
				{
				if(isset($_POST[$field->Field]))
					{
					if(is_array($_POST[$field->Field]))
						{
						$set_field_array = NEXForms_sanitize_array($_POST[$field->Field]);
						$field_array[$field->Field] = json_encode($set_field_array,JSON_FORCE_OBJECT);
						}
					else
						{
						$_POST[$field->Field] = NEXForms_rgba2Hex($_POST[$field->Field]);
						$field_array[$field->Field] = $_POST[$field->Field];
						}
					}	
				}
				
			$update = $wpdb->update ( $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING), $field_array, array(	'Id' => filter_var($edit_id,FILTER_SANITIZE_NUMBER_INT)) );
			echo filter_var($edit_id,FILTER_SANITIZE_NUMBER_INT);
			wp_die();
		}

	public function update_paypal(){
		
		if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		global $wpdb;
		
		
		
		$db_table = sanitize_title($_POST['table']);
		
		if(!strstr($db_table, 'nex_forms'))
			wp_die();
		
		$nex_forms_Id = sanitize_title($_POST['nex_forms_Id']);
		
		$fields 	= $wpdb->get_results('SHOW FIELDS FROM `' . $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING).'`');
		$field_array = array();
		foreach($fields as $field)
				{
				if(isset($_POST[$field->Field]))
					{
					if(is_array($_POST[$field->Field]))
						{
						$set_field_array = NEXForms_sanitize_array($_POST[$field->Field]);
						$field_array[$field->Field] = json_encode($set_field_array,JSON_FORCE_OBJECT);
						}
					else
						{
						$_POST[$field->Field] = NEXForms_rgba2Hex($_POST[$field->Field]);
						$field_array[$field->Field] = wp_kses( $_POST[$field->Field], NEXForms_allowed_tags() );
						}
					}	
				}
		
		$row_prep = $wpdb->prepare('SELECT nex_forms_Id FROM `'. $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING).'` WHERE nex_forms_Id=%d',filter_var($nex_forms_Id,FILTER_SANITIZE_NUMBER_INT));
		$get_row = $wpdb->get_var($row_prep);
		
		if(!$get_row)
			$insert = $wpdb->insert ( $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING), $field_array );
		else
			$update = $wpdb->update ( $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING), $field_array, array(	'nex_forms_Id' => filter_var($nex_forms_Id,FILTER_SANITIZE_NUMBER_INT)) );
			
			
		echo filter_var($nex_forms_Id,FILTER_SANITIZE_NUMBER_INT);
		die();
		}
		
		
		
	public function build_paypal_products($form_Id){
	
		global $wpdb;
		
		
		$form = $wpdb->get_row('SELECT * FROM '. $wpdb->prefix .'wap_nex_forms WHERE Id = '.filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT));
		
		$set_products = '';
		$products = explode('[end_product]',$form->products);
		$i=1;
		foreach($products as $product)
			{
			$item_name =  explode('[item_name]',$product);
			
			$item_name_1   = (isset($item_name[1])) ? $item_name[1] : '';
			$item_name2 =  explode('[end_item_name]',$item_name_1);
			
			$item_qty =  explode('[item_qty]',$product);
			
			$item_qty_1   = (isset($item_qty[1])) ? $item_qty[1] : '';
			$item_qty2 =  explode('[end_item_qty]',$item_qty_1);
			
			$map_item_qty =  explode('[map_item_qty]',$product);
			
			$map_item_qty_1   = (isset($map_item_qty[1])) ? $map_item_qty[1] : '';
			$map_item_qty2 =  explode('[end_map_item_qty]',$map_item_qty_1);
			
			$set_quantity =  explode('[set_quantity]',$product);
			
			$set_quantity_1   = (isset($set_quantity[1])) ? $set_quantity[1] : '';
			$set_quantity2 =  explode('[end_set_quantity]',$set_quantity_1);
			
			$item_amount =  explode('[item_amount]',$product);
			
			$item_amount_1   = (isset($item_amount[1])) ? $item_amount[1] : '';
			$item_amount2 =  explode('[end_item_amount]',$item_amount_1);
			
			$map_item_amount =  explode('[map_item_amount]',$product);
			
			$map_item_amount_1   = (isset($map_item_amount[1])) ? $map_item_amount[1] : '';
			$map_item_amount2 =  explode('[end_map_item_amount]',$map_item_amount_1);
			
			$set_amount =  explode('[set_amount]',$product);
			
			$set_amount_1   = (isset($set_amount[1])) ? $set_amount[1] : '';
			$set_amount2 =  explode('[end_set_amount]',$set_amount_1);
			
			$item_name2_0   = (isset($item_name2[0])) ? $item_name2[0] : '';
			
			if($item_name2_0)
				{
				$set_products .= '<div class="paypal_product">';
					$set_products .= '<span class="product_number badge">'.$i.'</span><div class="button remove_paypal_product"><span class="fa fa-close"></span></div>';
					
						$set_products .= '<div class="input-group input-group-sm item_name" role="group">
						<span class="input-group-addon is_label" style="" title="Bold">Item Name</span>
						<input placeholder="Enter item name" name="item_name" class="form-control" value="'.$item_name2_0.'">
						</div>
						';
						
						$set_products .= '<div class="input-group input-group-sm pp_product_amount" role="group">
											<span class="input-group-addon is_label" style="border-right:1px solid #ccc; border-radius:0;" title="">Amount</span>
											<span class="input-group-addon field_value '.(($set_amount2[0]!='static') ? 'active' : '').'" style="border-right:1px solid #ccc" title="">Map Field</span>	
											<span class="input-group-addon static_value '.(($set_amount2[0]=='static') ? 'active' : '').'" style="border-right:1px solid #ccc" title="">Static value</span>
											<input type="hidden" name="set_amount" value="'.$set_amount2[0].'">
											<input type="hidden" name="selected_amount_field" value="'.$map_item_amount2[0].'">	
											<input  value="'.$item_amount2[0].'" type="text" placeholder="Set static amount" name="item_amount" class="form-control '.(($set_amount2[0]=='map') ? 'hidden' : '').'">
											<select name="map_item_amount" class="form-control paypal_select '.(($set_amount2[0]=='static') ? 'hidden' : '').'" data-selected="'.$map_item_amount2[0].'"><option value="'.$map_item_amount2[0].'">'.$map_item_amount2[0].'</option></select>
										  </div>';
								
						$set_products .= '<div class="input-group input-group-sm pp_product_quantity" role="group">
											<span class="input-group-addon is_label" style="border-right:1px solid #ccc; border-radius:0;" title="">Quantity</span>
											<span class="input-group-addon field_value '.(($set_quantity2[0]!='static') ? 'active' : '').'" style="border-right:1px solid #ccc" title="">Map Field</span>
											<span class="input-group-addon static_value '.(($set_quantity2[0]=='static') ? 'active' : '').'" style="border-right:1px solid #ccc" title="">Static value</span>
											<input type="hidden" name="set_quantity" value="'.$set_quantity2[0].'">
											<input type="hidden" name="selected_qty_field" value="'.$map_item_qty2[0].'">	
											<input value="'.$item_qty2[0].'"  type="text" placeholder="Set static quantity" name="item_quantity" class="form-control '.(($set_quantity2[0]!='static') ? 'hidden' : '').'">
											<select name="map_item_quantity" class="form-control paypal_select paypal_select '.(($set_quantity2[0]=='static') ? 'hidden' : '').'" data-selected="'.$map_item_qty2[0].'"><option value="'.$map_item_qty2[0].'">'.$map_item_qty2[0].'</option></select>
										  </div>';
				$set_products .= '</div>';
				
				$i++;	
				}
			}	
		$output = '';
		$output .= '<div class="paypal_items_list" >';
								
								
							
								$output .= '<div class="paypal_product_clone hidden">';
									$output .= '<span class="product_number badge"></span><div class="button remove_paypal_product"><span class="fa fa-close"></span></div>';
					
											$output .= '<div class="input-group input-group-sm item_name" role="group">
											<span class="input-group-addon is_label" style="" title="Bold">Item Name</span>
											<input placeholder="Enter item name" name="item_name" class="form-control" value="">
											</div>';
											
											$output .= '<div class="input-group input-group-sm pp_product_amount" role="group">
																<span class="input-group-addon is_label" style="border-right:1px solid #ccc; border-radius:0;" title="Bold">Amount</span>																
																<span class="input-group-addon field_value active" style="border-right:1px solid #ccc" title="Bold">Map Field</span>
																<span class="input-group-addon static_value " style="border-right:1px solid #ccc" title="Bold">Static value</span>
																<input type="hidden" name="set_amount" value="map">
																<input  value="" type="text" placeholder="Set static amount" name="item_amount" class="form-control hidden">
																<select name="map_item_amount" class="form-control paypal_select" data-selected=""><option value="0">--- Map field for this item\'s amount ---</option></select>
															  </div>';
													
											$output .= '<div class="input-group input-group-sm pp_product_quantity" role="group">
																<span class="input-group-addon is_label" style="border-right:1px solid #ccc; border-radius:0;" title="Bold">Quantity</span>
																<span class="input-group-addon field_value active" style="border-right:1px solid #ccc" title="Bold">Map Field</span>
																<span class="input-group-addon static_value " style="border-right:1px solid #ccc" title="Bold">Static value</span>
																<input type="hidden" name="set_quantity" value="map">	
																<input value=""  type="text" placeholder="Set static quantity" name="item_quantity" class="form-control hidden">
																<select name="map_item_quantity" class="form-control paypal_select" data-selected=""><option value="0">--- Map field for this item\'s quantity ---</option></select>
															  </div>';
										$output .= '</div>';
										
								$output .= '<div class="paypal_products">'.((!empty($products)) ? $set_products : '').'</div>';
								
								
								
							$output .= '</div>';
							
							
							
		if ( !function_exists('nf_get_paypal_payment') && !function_exists('nf_not_found_notice_pp') ) {
				$output .= '<div class="alert alert-success">You need the "<strong><em>PayPal for NEX-forms</em></strong>" Add-on to use PayPal integration and receive online payments! <br>&nbsp;<a class="btn btn-success btn-large form-control" target="_blank" href="https://codecanyon.net/item/paypal-pro-for-nexforms/22449576?ref=Basix">Buy Now</a></div>';
		}
		return $output;
		
	}
	
	public function print_paypal_setup($form_Id){
		
		global $wpdb;
		
		
		$form = $wpdb->get_row('SELECT * FROM '. $wpdb->prefix .'wap_nex_forms WHERE Id = '.filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT));
		
		$output = '';
		
		$output .= '<div class="paypal_setup" >';
								
								
									
									$output .=  '<div class="row">';
										
										$output .= '<div class="integration_form_label">';
											$output .= 'Integrate PayPal';
										$output .= '</div>';
									
										$output .=  '<div class="integration_form_field no_input tour_paypal_setup_1" style="margin-left:0 !important;">';
											$output .=  '<div class="col-sm-4 zero_padding"><input class="with-gap" name="go_to_paypal" '.((!$form->is_paypal || $form->is_paypal=='no') ? 'checked="checked"' : '' ).' id="go_to_paypal_no" value="no" type="radio">
													<label for="go_to_paypal_no">No</label>
													</div>
													<div class="col-sm-8"><input class="with-gap" name="go_to_paypal" '.(($form->is_paypal=='yes') ? 'checked="checked"' : '' ).' id="go_to_paypal_yes" value="yes" type="radio">
													<label for="go_to_paypal_yes">Yes</label></div>';
										$output .=  '</div>';
									$output .=  '</div>';	
									
									
										
									$output .=  '<div class="row">';
										
										$output .= '<div class="integration_form_label">';
											$output .= 'PayPal Environment';
										$output .= '</div>';
									
										$output .=  '<div class="integration_form_field no_input tour_paypal_setup_2" style="margin-left:0 !important;">';
											$output .=  '<div class="col-sm-4 zero_padding"><input class="with-gap" name="paypal_environment" '.((!$form->environment || $form->environment=='sandbox') ? 'checked="checked"' : '' ).' id="paypal_environment_sb" value="sandbox" type="radio">
													<label for="paypal_environment_sb">Sandbox</label>
													</div>
													<div class="col-sm-8"><input class="with-gap" name="paypal_environment" '.(($form->environment=='live') ? 'checked="checked"' : '' ).' id="paypal_environment_live" value="live" type="radio">
													<label for="paypal_environment_live">Live</label></div>';
										$output .=  '</div>';
									$output .=  '</div>';		
										
										
								
								if(function_exists('nf_get_paypal_payment'))
									{
									$output .=  '<div class="row">';
									$output .= '<div class="integration_form_label ">Client ID</div><div class="integration_form_field zero_padding tour_paypal_setup_3"><input type="text" placeholder="Enter your PayPal Client ID" value="'.$form->paypal_client_Id.'" name="paypal_client_Id" class="form-control"></div>';
									$output .= '</div>';
									$output .=  '<div class="row">';
										$output .= '<div class="integration_form_label ">Client Secret</div><div class="integration_form_field zero_padding tour_paypal_setup_4"><input type="text" placeholder="Enter your PayPal Client Secret" value="'.$form->paypal_client_secret.'" name="paypal_client_secret" class="form-control"></div>';
									$output .= '</div>';	
									}
								else
									{
									$output .=  '<div class="row">';
										$output .= '<div class="integration_form_label">Business</div><div class="integration_form_field zero_padding"><input type="text" placeholder="Paypal Email address/ Paypal user ID" value="'.$form->business.'" name="business" class="form-control"></div>';
									$output .= '</div>';
									$output .=  '<div class="row">';
										$output .= '<div class="integration_form_label">Return URL</div><div class="integration_form_field zero_padding"><input type="text" placeholder="Leave blank to return back to the original form" value="'.$form->return_url.'" name="return" class="form-control"></div>';
									$output .= '</div>';
									$output .=  '<div class="row">';
										$output .= '<div class="integration_form_label">Cancel URL</div><div class="integration_form_field zero_padding"><input type="text" placeholder="Cancel URL" value="'.$form->cancel_url.'" name="cancel_url" class="form-control"></div>';
									$output .= '</div>';
									}
								
								$output .=  '<div class="row">';
									$output .= '<div class="integration_form_label ">Currency</div><div class="integration_form_field zero_padding tour_paypal_setup_5"><select name="currency_code" class="set_currency_code form-control" data-selected="'.$form->currency_code.'">
												  <option selected="" value="USD">--- Select ---</option>
												  <option value="AUD">Australian Dollar</option>
												  <option value="BRL">Brazilian Real</option>
												  <option value="CAD">Canadian Dollar</option>
												  <option value="CZK">Czech Koruna</option>
												  <option value="DKK">Danish Krone</option>
												  <option value="EUR">Euro</option>
												  <option value="HKD">Hong Kong Dollar</option>
												  <option value="HUF">Hungarian Forint </option>
												  <option value="INR">Indian Rupee </option>
												  <option value="ILS">Israeli New Sheqel</option>
												  <option value="JPY">Japanese Yen</option>
												  <option value="MYR">Malaysian Ringgit</option>
												  <option value="MXN">Mexican Peso</option>
												  <option value="NOK">Norwegian Krone</option>
												  <option value="NZD">New Zealand Dollar</option>
												  <option value="PHP">Philippine Peso</option>
												  <option value="PLN">Polish Zloty</option>
												  <option value="GBP">Pound Sterling</option>
												  <option value="SGD">Singapore Dollar</option>
												  <option value="SEK">Swedish Krona</option>
												  <option value="CHF">Swiss Franc</option>
												  <option value="TWD">Taiwan New Dollar</option>
												  <option value="THB">Thai Baht</option>
												  <option value="TRY">Turkish Lira</option>
												  <option value="USD">U.S. Dollar</option>
												</select></div>';
								$output .= '</div>';
								
								if(function_exists('nf_get_paypal_payment'))
									{
									$output .=  '<div class="row">';
										$output .= '<div class="integration_form_label label_textarea ">Success Message</div><div class="tour_paypal_setup_6 integration_form_field zero_padding"><textarea type="text" placeholder="Enter Message for a successful payment" value="Payment Successful" name="payment_success_msg" class="form-control">'.(($form->payment_success_msg) ? $form->payment_success_msg : 'Payment Successful').'</textarea></div>';
									$output .= '</div>';
									$output .=  '<div class="row">';
										$output .= '<div class="integration_form_label label_textarea ">Failure Message</div><div class="tour_paypal_setup_7 integration_form_field zero_padding"><textarea type="text" placeholder="Enter Message for a failed payment" value="Payment Unsuccessful" name="payment_failed_msg" class="form-control">'.(($form->payment_failed_msg) ? $form->payment_failed_msg : 'Payment Unsuccessful').'</textarea></div>';
									$output .= '</div>';	
									$email_on_payment_success = explode(',',$form->email_on_payment_success);
									
									$check_array = $email_on_payment_success[0];
									
									
									
									$output .= '<div class="row last">';
									$output .= '<div class="integration_form_label">Email Alerts</div>';
									$output .= '<div class="integration_form_field no_input tour_paypal_setup_8" style="padding: 10px 10px 20px 10px;">';
										$output .= '<input '.((in_array('payments',$email_on_payment_success) || !$check_array || empty($email_on_payment_success)) ? 'checked="checked"': '').' name="email_on_payments" value="1" id="email1" type="checkbox"><label for="email1"> Send emails on successful payments<em></em></label><br />';
										$output .= '<input '.(in_array('failures',$email_on_payment_success) ? 'checked="checked"': '').' name="email_on_failures" value="1" id="email2" type="checkbox"><label for="email2"> Send emails on failed payments<em></em></label><br />';
										$output .= '<input '.(in_array('before_payments',$email_on_payment_success) ? 'checked="checked"': '').' name="email_before_payments" value="1" id="email3" type="checkbox"><label for="email3"> Send emails before payments<em></em></label>';
									$output .= '</div>';
									$output .= '</div>';
									}
								else
									{
									$output .=  '<div class="row">';
										$output .= '<div class="integration_form_field">Language</div><div class="col-sm-8"><select name="paypal_language_selection"  class="form-control paypal_select"  data-selected="'.$form->lc.'">
													<option selected="" value="US"> --- Select ---</option>
													<option value="AU">Australia</option>
													<option value="AT">Austria</option>
													<option value="BE">Belgium</option>
													<option value="BR">Brazil</option>
													<option value="CA">Canada</option>
													<option value="CH">Switzerland</option>
													<option value="CN">China</option>
													<option value="DE">Germany</option>
													<option value="ES">Spain</option>
													<option value="GB">United Kingdom</option>
													<option value="FR">France</option>
													<option value="IT">Italy</option>
													<option value="NL">Netherlands</option>
													<option value="PL">Poland</option>
													<option value="PT">Portugal</option>
													<option value="RU">Russia</option>
													<option value="US">United States</option>
													<option value="da_DK">Danish(for Denmark only)</option>
													<option value="he_IL">Hebrew (all)</option>
													<option value="id_ID">Indonesian (for Indonesia only)</option>
													<option value="ja_JP">Japanese (for Japan only)</option>
													<option value="no_NO">Norwegian (for Norway only)</option>
													<option value="pt_BR">Brazilian Portuguese (for Portugaland Brazil only)</option>
													<option value="ru_RU">Russian (for Lithuania, Latvia,and Ukraine only)</option>
													<option value="sv_SE">Swedish (for Sweden only)</option>
													<option value="th_TH">Thai (for Thailand only)</option>
													<option value="tr_TR">Turkish (for Turkey only)</option>
													<option value="zh_CN">Simplified Chinese (for China only)</option>
													<option value="zh_HK">Traditional Chinese (for Hong Kongonly)</option>
													<option value="zh_TW">Traditional Chinese (for Taiwanonly)</option>
												</select></div>';
									$output .= '</div>';
									}
								
								
							$output .= '</div>';	
				return $output;
	}
	
	
/* DUPLICATE */
		public function duplicate_record(){
			
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global $wpdb;
			
			$db_table = sanitize_title($_POST['table']);
			
			if(!strstr($db_table, 'nex_forms'))
				wp_die();
			
			$record_id = sanitize_title($_POST['Id']);
	
			$get_record = $wpdb->prepare('SELECT * FROM ' .$wpdb->prefix. filter_var($db_table,FILTER_SANITIZE_STRING). ' WHERE Id = %d ',filter_var($record_id,FILTER_SANITIZE_NUMBER_INT));
			$record = $wpdb->get_row($get_record);
			
			$get_fields 	= $wpdb->prepare("SHOW FIELDS FROM " . $wpdb->prefix .filter_var($db_table,FILTER_SANITIZE_STRING),'');
			$fields 	= $wpdb->get_results($get_fields);
			$field_array = array();
			$draft_array = array();
			foreach($fields as $field)
				{
				$column = $field->Field;
				$field_array[$field->Field] = $record->$column;
				}
			//remove values not to be copied
			unset($field_array['entry_count']);
			unset($field_array['Id']);
			$draft_array = $field_array;	
			$insert = $wpdb->prepare ( $wpdb->insert ( $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING), $field_array ),'');
			$wpdb->query($insert);
			
			$insert_id = $wpdb->insert_id;
	
			
			echo $insert_id;
			
			$theme = wp_get_theme();
			if($theme->Name=='NEX-Forms Demo')
				{
				$post_id = wp_insert_post(
					array(
						'comment_status'	=>	'closed',
						'ping_status'		=>	'closed',
						'post_author'		=>	1,
						'post_name'			=>	'user-test-form-'.$insert_id,
						'post_title'		=>	'User Test Form '.$insert_id,
						'post_status'		=>	'publish',
						'post_type'			=>	'page',
						'post_content'		=>	'[NEXForms id="'.$insert_id.'"]',
						'post_parent'		=>  '11',
					)
				);
				}
			
			die();
		}
			
/* DELETE */
		public function delete_record(){
			
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
				
			global $wpdb;

			$db_table = sanitize_title($_POST['table']);
			
			if(!strstr($db_table, 'nex_forms'))
				wp_die();
			
			$record_id = sanitize_title($_POST['Id']);
			
			$delete = $wpdb->delete($wpdb->prefix. filter_var($db_table,FILTER_SANITIZE_STRING),array('Id'=>filter_var($record_id,FILTER_SANITIZE_NUMBER_INT)));	
			$delete_draft = $wpdb->delete($wpdb->prefix. filter_var($db_table,FILTER_SANITIZE_STRING),array('draft_Id'=>filter_var($record_id,FILTER_SANITIZE_NUMBER_INT)));	
			wp_die();
		}	
		public function delete_file(){
			
			if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			
			$db_table = sanitize_title($_POST['table']);
			
			if(!strstr($db_table, 'nex_forms'))
				wp_die();
			
			$record_id = sanitize_title($_POST['Id']);
			$get_file = $wpdb->prepare('SELECT location FROM ' .$wpdb->prefix. filter_var($db_table,FILTER_SANITIZE_STRING). ' WHERE Id = %d',filter_var($record_id,FILTER_SANITIZE_NUMBER_INT));
			$file = $wpdb->get_var($get_file);
			
			unlink($file);
			
			die();
		}

		public function NEXForms_get_data(){
				
			
				$api_params = array( 
					'verify-2' 		=> 1, //'',
					'license' 		=> filter_var($_POST['pc'],FILTER_SANITIZE_STRING), 
					'user_name' 	=> filter_var($_POST['eu'],FILTER_SANITIZE_STRING), 
					'item_code' 	=> '7103891',
					'email_address' => get_option('admin_email'),
					'for_site' 		=> get_option('siteurl'),
					'unique_key'	=> get_option('7103891'),
					're_register'	=> (($_POST['rereg']=='false') ? false : true)
				);
				
				// Call the custom API.
				$response = wp_remote_post( 'https://basixonline.net/activate-license-new-api-v3', array(
					'timeout'   => 30,
					'sslverify' => false,
					'body'      => $api_params
				) );
				// make sure the response came back okay
				
				if ( is_wp_error( $response ) )
					echo '<div class="alert alert-danger"><strong>Could not connect</div><br /><br />Please try again later.';

				// decode the license data
				$license_data = json_decode($response['body'],true);
				if($license_data['error']<=0)
					{
					update_option( '1983017'.$license_data['key'] , array( $license_data['pc']));
					}
				
				echo $license_data['message'];
				die();
		}
/* ALTER TABLE */
		public function alter_plugin_table($table='', $col = '', $type='text'){
			
			//if(!current_user_can( NF_USER_LEVEL ))	
			//	wp_die();
			
			global $wpdb;
			
			
			
			if(!strstr($table, 'nex_forms'))
				return;
			
			$fields 	= $wpdb->get_results('SHOW FIELDS FROM `'.$wpdb->prefix.$table.'`');
			$field_array = array();
			foreach($fields as $field)
				{
				$field_array[$field->Field] = $field->Field;
				}
			if(!in_array(filter_var($col,FILTER_SANITIZE_STRING),$field_array))
				$result = $wpdb->query("ALTER TABLE `".$wpdb->prefix.$table."` ADD `".$col."` ".strtoupper($type));
			
		}
		public function recollate_plugin_table(){
			
			//if(!current_user_can( NF_USER_LEVEL ))	
			//	wp_die();
			
			global $wpdb;
			$check_field = $wpdb->query("SHOW COLUMNS FROM `".$wpdb->prefix ."wap_nex_forms` LIKE 'last_update'");
			
			if($check_field)
				$wpdb->query("ALTER TABLE `".$wpdb->prefix ."wap_nex_forms` DROP `last_update`");
					
			$wpdb->query("ALTER TABLE `".$wpdb->prefix ."wap_nex_forms` ENGINE = MYISAM;");
			$wpdb->query("ALTER TABLE `".$wpdb->prefix ."wap_nex_forms` DEFAULT CHARSET=utf8mb4");
			$wpdb->query("ALTER TABLE `".$wpdb->prefix ."wap_nex_forms` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
			
			//$wpdb->query("ALTER TABLE `".$wpdb->prefix ."wap_nex_forms_entries` ENGINE = MYISAM;");
			//$wpdb->query("ALTER TABLE `".$wpdb->prefix ."wap_nex_forms_entries` DEFAULT CHARSET=utf8mb4");
			//$wpdb->query("ALTER TABLE `".$wpdb->prefix ."wap_nex_forms_entries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
			}
/* PREVIEW FORM */
		public function preview_nex_form(){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			
			$db_table = sanitize_title($_POST['table']);
			
			if(!strstr($db_table, 'nex_forms'))
				wp_die();

			
			$form = $wpdb->get_var('SELECT Id FROM '. $wpdb->prefix .'wap_nex_forms WHERE is_form = "preview"');
			$fields 	= $wpdb->get_results("SHOW FIELDS FROM " . $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING));
			$field_array = array();
			
			if($form)
				{
				$edit_id = $form;
			
				
				foreach($fields as $field)
					{
					if(isset($_POST[$field->Field]))
						{
						if(is_array($_POST[$field->Field]))
							$field_array[$field->Field] = json_encode($_POST[$field->Field],JSON_FORCE_OBJECT);
						else
							$field_array[$field->Field] = $_POST[$field->Field];
						}	
					}
					
				$update = $wpdb->update ( $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING), $field_array, array(	'Id' => filter_var($edit_id,FILTER_SANITIZE_NUMBER_INT)) );
				echo filter_var($edit_id,FILTER_SANITIZE_NUMBER_INT);
			
				}
			else
				{
			
			
				$wpdb->delete($wpdb->prefix.'wap_nex_forms',array('is_form'=>'preview'));
				foreach($fields as $field)
					{
					if(isset($_POST[$field->Field]))
						{
						$field_array[$field->Field] = $_POST[$field->Field];
						}	
					}
				
				$field_array['multistep_settings'] = json_encode($field_array['multistep_settings']);
				$field_array['md_theme'] = json_encode($field_array['md_theme']);
				$field_array['form_hidden_fields'] = json_encode($field_array['form_hidden_fields']);
				$field_array['hidden_fields'] = json_encode($field_array['hidden_fields']);
				$field_array['conditional_logic_array'] = json_encode($field_array['conditional_logic_array']);
				$insert = $wpdb->insert ( $wpdb->prefix . filter_var($db_table,FILTER_SANITIZE_STRING), $field_array );
	
				echo $wpdb->insert_id;
				}
			
			wp_die();
		}
		
		
	
		
	   public function get_forms(){
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		global $wpdb;
		
		
		$db_actions = new NEXForms_Database_Actions();
		
		$output = '';
		if($_POST['get_templates']=='1')
			{
			$get_forms = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE is_template=1 AND is_form<>"preview" AND is_form<>"draft" ORDER BY Id DESC','');
			$is_template = 'is_template';
			}
		else
			{
			$get_forms = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE is_template<>1 AND is_form<>"preview" AND is_form<>"draft" ORDER BY Id DESC','');
			$is_template = '';
			}

		
		$forms = $wpdb->get_results($get_forms);
		if($forms)
			{
			$output .= '<table class="table table-striped" style="width:100%; margin-bottom:0px;">';
				$output .= '<tr>';
					if($_POST['get_templates']!='1')
						{
						$output .= '<th style="width:30px;">';
							$output .= '#';
						$output .= '</th>';	
						}
					$output .= '<th style="width:168px;">';
						$output .= 'Title';
					$output .= '</th>';	
					if($_POST['get_templates']!='1')
						{
						/*$output .= '<th style="width:30px;">';
							$output .= 'Type';
						$output .= '</th>';*/
					
						$output .= '<th style="width:56px;">';
							$output .= 'Entries';
						$output .= '</th>';
						}
					
					
					$output .= '<th style="width:100px;">';
						$output .= '&nbsp;';
					$output .= '</th>';	
				$output .= '</tr>';	
			foreach($forms as $form)
				{
				$output .= '<tr id="'.$form->Id.'" class="'.$is_template.'">';
					if($_POST['get_templates']!='1')
						{
						$output .= '<td class="open_form" style="cursor:pointer;">';
							$output .= $form->Id;
						$output .= '</td>';	
						}
					$output .= '<td class="open_form the_form_title" style="cursor:pointer;">';
						$output .= $form->title;
					$output .= '</td>';	
					if($_POST['get_templates']!='1')
						{
						/*$output .= '<td class="open_form form_type" style="cursor:pointer;">';
							$output .= $form->form_type;
						$output .= '</td>';*/	
					
						$output .= '<td class="open_form" style="cursor:pointer">';
							$output .= $db_actions->get_total_records('wap_nex_forms_entries','',$form->Id);
						$output .= '</td>';	
						}
					
					
					
					
					$output .= '<td align="right">';
						$output .= '<a class="nf-button export_form" data-toggle="tooltip" data-placement="left" title="" data-original-title="Export"  href="'.get_option('siteurl').'/wp-admin/admin.php?page=nex-forms-main&nex_forms_Id='.$form->Id.'&export_form=true"><span class="fa fa-cloud-download bs-tooltip"  data-toggle="tooltip" data-placement="left" title="" data-original-title="Export"></span></a>';
					
						$output .= '<a class="duplicate_record nf-button" data-toggle="tooltip" data-placement="top" title="Duplicate" id="'.$form->Id.'">&nbsp;<span class="fa fa-files-o"></span>&nbsp;</button>';
					
						$output .= '<a id="'.$form->Id.'" class="do_delete nf-button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">&nbsp;<span class="fa fa-trash"></span>&nbsp;</button>';
					$output .= '</td>';	
				$output .= '</tr>';	
				
			}
			$output .= '</table>';
			$output .= '<div class="scroll_spacer"></div>';
			}
		else
			{
			if($_POST['get_templates']!='1')
				$output .= '<div class="loading">No forms have been saved yet.<br /><br /><button class="btn btn-default btn-sm trigger_create_new_form">Create a new form</button></div>';	
			}
			//$output .= '<li id="'.$calendar->Id.'" class="nex_event_calendar"><a href="#"><span class="the_form_title">'.$calendar->title.'</span></a>&nbsp;&nbsp;<i class="fa fa-trash-o delete_the_calendar" data-toggle="modal" data-target="#deleteCalendar" id="'.$calendar->Id.'"></i></li>';	
		echo $output;
		die();
		}
	
		
		
	public function get_title($Id='',$table=''){
			global $wpdb;
			$nf_functions = new NEXForms_Functions();
			if(is_array($Id))
				{
				$params = $Id;
				$Id = $params[0];
				$table = $params[1];
				}
				
			$get_the_title = $wpdb->prepare("SELECT title FROM " . $wpdb->prefix .$table." WHERE Id = %d ",$Id);
			$the_title = $wpdb->get_var($get_the_title);
			
			$the_title= wp_unslash($the_title);
			$the_title= str_replace('\"','',$the_title);
			$the_title= str_replace('/','',$the_title);
			$the_title = sanitize_text_field( $the_title );
			
			/*if(!$the_title)
				{
				$the_title = 'Unidentified (Form#'.$Id.')';				
				}*/
			if(!$the_title)
				{
				$the_title = 'Form Preview';				
				}
			return $nf_functions->view_excerpt(str_replace('\\','',sanitize_text_field($the_title)),20);
		}
	public function get_title2($Id='',$table=''){
			global $wpdb;
			$functions = new NEXForms_Functions();
			if(is_array($Id))
				{
				$params = $Id;
				$Id = $params[0];
				$table = $params[1];
				}
			$get_the_title = $wpdb->prepare("SELECT title FROM " . $wpdb->prefix .$table." WHERE Id = %d",filter_var($Id,FILTER_SANITIZE_NUMBER_INT));
			$the_title = $wpdb->get_var($get_the_title);
			
			$the_title= wp_unslash($the_title);
			$the_title= str_replace('\"','',$the_title);
			$the_title= str_replace('/','',$the_title);
			$the_title = sanitize_text_field( $the_title );
			
			if(!$the_title)
				{
				$the_title = 'Form Preview';				
				}
			return str_replace('\\','',sanitize_text_field($the_title));
		}
	
	public function get_user_firstname($Id){
			global $wpdb;
			$get_username = $wpdb->prepare("SELECT meta_value FROM " . $wpdb->prefix . "usermeta WHERE user_id = %d AND meta_key='first_name'",filter_var($Id,FILTER_SANITIZE_NUMBER_INT));
			$username = $wpdb->get_var($get_username);
			return $username;
			
		}	
	public function get_user_lastname($Id){
			global $wpdb;
			$get_username = $wpdb->prepare("SELECT meta_value FROM " . $wpdb->prefix . "usermeta WHERE user_id = %d AND meta_key='last_name'",filter_var($Id,FILTER_SANITIZE_NUMBER_INT));
			$username = $wpdb->get_var($get_username);
			return $username;
		}	
	
	public function get_username($Id){
			global $wpdb;
			$get_username = $wpdb->prepare("SELECT display_name FROM " . $wpdb->prefix . "users WHERE ID = %d",filter_var($Id,FILTER_SANITIZE_NUMBER_INT));
			$username = $wpdb->get_var($get_username);
			return $username;
		}
	public function get_useremail($Id){
			global $wpdb;
			$get_useremail = $wpdb->prepare("SELECT user_email FROM " . $wpdb->prefix . "users WHERE ID = %d",filter_var($Id,FILTER_SANITIZE_NUMBER_INT));
			$useremail = $wpdb->get_var($get_useremail);
			return $useremail;
		}
	public function get_userurl($Id){
			global $wpdb;
			$get_userurl = $wpdb->prepare("SELECT user_url FROM " . $wpdb->prefix . "users WHERE ID = %d",filter_var($Id,FILTER_SANITIZE_NUMBER_INT));
			$userurl = $wpdb->get_var($get_userurl);
			return $userurl;
		}
	
	public function load_nex_form(){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			$get_id = 'Id';
			if($_POST['status']=='draft')
				$get_id = 'draft_Id';
				
			$form_Id = sanitize_title($_POST['form_Id']);
				
			$get_form = $wpdb->get_row($wpdb->prepare('SELECT form_fields FROM '.$wpdb->prefix.'wap_nex_forms WHERE '.$get_id.'= %d',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT)));
			$form = $wpdb->get_row($get_form);
			echo str_replace('\\','',$form->form_fields);
			die();	
		}
		
	public function load_conditional_logic($form_Id=''){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global $wpdb;
						
			$get_logic = $wpdb->prepare('SELECT conditional_logic FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id=%d ',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT));
			$conditional_logic = $wpdb->get_var($get_logic);
			
			//echo '<pre>';
		$rules = explode('[start_rule]',$conditional_logic);
		$i=1;
		//print_r( $rules);		
		
		$nf_function = new NEXForms_Functions();
		
		$output = '';
		
		
		$output .= '<select name="cl_current_fields_container" class="cl_current_fields_container hidden" style=""></select>';
		$output .= '<select name="cl_current_action_fields_container" class="cl_current_action_fields_container hidden" style=""></select>';
		
		$output .= '<div class="conditional_logic_clonables hidden">';
							
							
							$output .= '
							<div class="panel new_rule row">
  <div class="panel-heading">
    <button aria-hidden="true" data-dismiss="modal" class="close delete_rule" type="button"><span class="fa fa-close "></span></button>
  </div>
  <div class="panel-body">
    <div class="col-xs-7">
      <h3 class="advanced_options do_conditions_head"><strong>
        <div class="badge rule_number">1</div>
        IF</strong>
        <select id="operator" style="width:15%; float:none !important; display: inline" class="form-control" name="selector">
          <option value="any" selected="selected"> any </option>
          <option value="all"> all </option>
        </select>
        of these conditions are true</h3>
      <div class="set_rule_conditions">
        <div class="the_rule_conditions">
          <div class="rule_number">1</div>
          <div class="input-group"><span class="input-group-addon">IF</span>
            <select name="reloaded_fields" class="cl_current_fields_container form-control cl_field" style="" data-selected="">
              <option value="0">-- Fields --</option>
              
            </select>
            <span class="input-group-addon">IS</span>
            <select name="field_condition" class="form-control" style="">
              <option value="0">-- Condition --</option>
              <option selected="selected" value="equal_to">Equal To</option>
              <option value="not_equal_to">Not Equal To</option>
              <option value="less_than">Less Than</option>
              <option value="greater_than">Greater Than</option>
              <option value="less_equal">Less or Equal</option>
              <option value="greater_equal">Greater or Equal</option>
			  <option value="contains">Contains</option>
			  <option value="not_contains">Not Contain</option>
            </select>
            <span class="input-group-addon"><span class="fa fa-caret-right"></span></span>
            <input type="text" name="conditional_value" class="form-control" style="" placeholder="Enter Value" value="">
            <button class=" button delete_condition advanced_options" style=""><span class="fa fa-close"></span></button>
            <div style="clear:both;"></div>
          </div>
        </div>
      </div>
      <button class=" button add_condition advanced_options" style=""><span class="fa fa-plus"></span> Add Condition</button>
    </div>
    <div class="col-xs-5">
      <h3 class="advanced_options do_actions_head" style="">Do Actions</h3>
      <div class="set_rule_actions">
        <div class="the_rule_actions">
          <div class="input-group">
		  <span class="input-group-addon">THEN</span>
            <select name="the_action" class="form-control" style="">
              <option value="0">-- Action --</option>
              <option selected="selected" value="show">Show</option>
              <option value="hide">Hide</option>
              <option value="disable">Disable</option>
              <option value="enable">Enable</option>
			  <option value="change_value">Change Value</option>
			  <option value="skip_to">Skip To Step</option>
            </select>
            <span class="input-group-addon changeable">
				<span class="fa fa-caret-right"></span>
				<span class="cv_txt">OF</span>
			</span>
            <select name="cla_field" class="cl_current_action_fields_container form-control" style="" data-selected="">
              <option value="0">-- Fields --</option>
              
            </select>
			
			<!--<select name="cla_steps" class="cl_current_action_fields_steps form-control hidden" style="" data-selected="">
              <option value="0">-- Steps --</option>
              
            </select>-->
			
            <button class=" button delete_action advanced_options" style=""><span class="fa fa-close"></span></button>
          </div>
		  <div class="input-group show_change_value_to hidden">
			  <span class="input-group-addon">
			  	<span class="">TO</span>
			  </span>
			  <input type="text" name="action_value_to" class="form-control" style="" placeholder="enter new value" value="">
		  </div>
		  
        </div>
      </div>
      <button class=" button add_action advanced_options" style="width:100%;"><span class="fa fa-plus"></span> Add Action</button>
    </div>
    <div class="con_col col-xs-2 hidden">
      <h3 class="advanced_options" style="">ELSE</h3>
      <span class="statment_head">ELSE</span>
      <select name="reverse_actions" class="form-control">
        <option selected="selected" value="true">Reverse Actions</option>
        <option value="false">Do Nothing</option>
      </select>
    </div>
    <button class="button delete_simple_rule" style="width:15%;"><span class="fa fa-close"></span></button>
    <button class="button duplicate_simple_rule" style="width:15%;"><span class="fa fa-copy"></span></button>
  </div>
</div>
';
							
						
						
						
						$output .= '</div>';
		
		
		$output .= '<div class="set_rules">';
		foreach($rules as $rule)
			{
			
			
			
			if($rule)
				{
				$output .= '<div class="panel new_rule row">';
					$output .= '<div class="panel-heading"><button aria-hidden="true" data-dismiss="modal" class="close delete_rule" type="button"><span class="fa fa-close "></span></button></div>';
					$output .= '<div class="panel-body">';
				
						$operator =  explode('[operator]',$rule);
						
						$operator_1   = (isset($operator[1])) ? $operator[1] : '';
						$operator2 =  explode('[end_operator]',$operator_1);
						
						$operator2_0   = (isset($operator2[0])) ? $operator2[0] : '';
						$get_operator = trim($operator2_0);
						
						$get_operator2 = explode('##',$get_operator);
						
						$get_operator2_0   = (isset($get_operator2[0])) ? $get_operator2[0] : '';
						$rule_operator = $get_operator2_0;
						
						$get_operator2_1   = (isset($get_operator2[1])) ? $get_operator2[1] : '';
						$reverse_action = $get_operator2_1;
				
				
				$conditions =  explode('[conditions]',$rule);
				
				$conditions_1   = (isset($conditions[1])) ? $conditions[1] : '';
				$conditions2 =  explode('[end_conditions]',$conditions_1);
				
				$conditions2_0   = (isset($conditions2[0])) ? $conditions2[0] : '';
				$rule_conditions = trim($conditions2_0);
	
				$get_conditions =  explode('[new_condition]',$rule_conditions);
				
				$get_conditions_1   = (isset($get_conditions[1])) ? $get_conditions[1] : '';
				$get_conditions2 =  explode('[end_new_condition]',$get_conditions_1);
				
				$get_conditions2_0   = (isset($get_conditions2[0])) ? $get_conditions2[0] : '';
				$get_rule_conditions = trim($get_conditions2_0);
				
				$output .= '<div class="col-xs-7">';
					$output .= '<h3 class="advanced_options do_conditions_head"><strong><div class="badge rule_number">1</div>IF</strong> ';
						$output .= '<select id="operator" style="width:15%; float:none !important; display: inline" class="form-control" name="selector">';
							$output .= '<option value="any" '.(($rule_operator=='any' || !$rule_operator) ? 'selected="selected"' : '').'> any </option>';
							$output .= '<option value="all" '.(($rule_operator=='all' || !$rule_operator) ? 'selected="selected"' : '').'> all </option>';
						$output .= '</select> ';
					$output .= 'of these conditions are true</h3>';
				
					$output .= '<div class="get_rule_conditions">';
				
				foreach($get_conditions as $set_condition)
					{
					
					$the_condition 		=  explode('[field_condition]',$set_condition);
					
					$the_condition_1   = (isset($the_condition[1])) ? $the_condition[1] : '';
					$the_condition2 	=  explode('[end_field_condition]',$the_condition_1);
					
					$the_condition2_0   = (isset($the_condition2[0])) ? $the_condition2[0] : '';
					$get_the_condition 	=  trim($the_condition2_0);
					
					$the_value 		=  explode('[value]',$set_condition);
					
					
					$the_value_1   = (isset($the_value[1])) ? $the_value[1] : '';
					$the_value2 	=  explode('[end_value]',$the_value_1);
					
					$the_value_2_0   = (isset($the_value2[0])) ? $the_value2[0] : '';
					$get_the_value 	=  trim($the_value_2_0);
						
					
					$con_field =  explode('[field]',$set_condition);
					
					$con_field_1 = (isset($con_field[1])) ? $con_field[1] : '';
					$con_field2 =  explode('[end_field]',$con_field_1);
					
					$con_field2_0 = (isset($con_field2[0])) ? $con_field2[0] : '';
					$get_con_field = explode('##',$con_field2_0);;
					
					
					$get_con_field_0 = (isset($get_con_field[0])) ? $get_con_field[0] : '';
					$con_field_type = $get_con_field[0];
					
					$get_con_field_attr = explode('**',$get_con_field_0);
					
					$get_con_field_attr_0 = (isset($get_con_field_attr[0])) ? $get_con_field_attr[0] : '';
					$con_field_id	 = $get_con_field_attr_0;
					
					$get_con_field_attr_1 = (isset($get_con_field_attr[1])) ? $get_con_field_attr[1] : '';
					$con_field_type	 = $get_con_field_attr_1;
					
					$get_con_field_1 = (isset($get_con_field[1])) ? $get_con_field[1] : '';
					$con_field_name	 = $get_con_field_1;
					
					if($con_field_type)
						{
						
						$output .= '<div class="the_rule_conditions">';
								$output .= '<div class="rule_number">1</div>';
								$output .= '<div class="input-group">';
								
									$output .= '<span class="input-group-addon">IF</span><select name="fields_for_conditions" class="form-control cl_field" style="" data-selected="'.$con_field2[0].'">';
										$output .= '<option data-field-id="'.$con_field_id.'" data-field-name="'.$con_field_name.'" data-field-type="'.$con_field_type.'" selected="selected" value="'.$con_field2[0].'">'.$nf_function->unformat_name($con_field_name).'</option>';
									$output .= '</select>';
									$output .= '<span class="input-group-addon">IS</span><select name="field_condition" class="form-control" style="">';
										$output .= '<option '.((!$get_the_condition) ? 'selected="selected"' : '').' value="0" >-- Condition --</option>';
										$output .= '<option '.(($get_the_condition=='equal_to') ? 'selected="selected"' : '').' 	value="equal_to">Equal To</option>';
										$output .= '<option '.(($get_the_condition=='not_equal_to') ? 'selected="selected"' : '').' value="not_equal_to">Not Equal To</option>';
										$output .= '<option '.(($get_the_condition=='less_than') ? 'selected="selected"' : '').' 	value="less_than">Less Than</option>';
										$output .= '<option '.(($get_the_condition=='greater_than') ? 'selected="selected"' : '').' value="greater_than">Greater Than</option>';
										$output .= '<option '.(($get_the_condition=='less_equal') ? 'selected="selected"' : '').' value="less_equal">Less or Equal</option>';
										$output .= '<option '.(($get_the_condition=='greater_equal') ? 'selected="selected"' : '').' value="greater_equal">Greater or Equal</option>';
										$output .= '<option '.(($get_the_condition=='contains') ? 'selected="selected"' : '').' value="contains">Contains</option>';
										$output .= '<option '.(($get_the_condition=='not_contains') ? 'selected="selected"' : '').' value="not_contains">Not Contain</option>';
										
										
									$output .= '</select>';
									$output .= '<span class="input-group-addon"><span class="fa fa-caret-right"></span></span><input type="text" name="conditional_value" class="form-control" style="" placeholder="enter value" value="'.$get_the_value.'">';
									$output .= '<button class=" button delete_condition advanced_options" style=""><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
								$output .= '</div>';
							$output .= '</div>';
						
						
						}
						
					}		
					$output .= '</div>';
					
					$output .= '<button class=" button add_condition advanced_options" style="width:100%;"><span class="fa fa-plus"></span> Add Condition</button>';
				$output .= '</div>';
									
				//THEN
				$output .= '<div class="col-xs-5">';
					$output .= '<h3 class="advanced_options do_actions_head" style="">Do Actions</h3>';
					$output .= '<div class="get_rule_actions">';
				
				
				$actions =  explode('[actions]',$rule);
				
				$actions_1 = (isset($actions[1])) ? $actions[1] : '';
				$actions2 =  explode('[end_actions]',$actions_1);
				
				$actions2_0 = (isset($actions2[0])) ? $actions2[0] : '';
				$rule_actions = trim($actions2_0);
				
				$get_actions =  explode('[new_action]',$rule_actions);
				
				$get_actions_1 = (isset($get_actions[1])) ? $get_actions[1] : '';
				$get_actions2 =  explode('[end_new_action]',$get_actions_1);
				
				$get_actions2_0 = (isset($get_actions2[0])) ? $get_actions2[0] : '';
				$get_rule_actions = trim($get_actions2_0);
				
					//print_r($get_actions);
				foreach($get_actions as $set_action)
					{
					
					$action_to_take =  explode('[the_action]',$set_action);
					
					$action_to_take_1 = (isset($action_to_take[1])) ? $action_to_take[1] : '';
					$action_to_take2 =  explode('[end_the_action]',$action_to_take_1);

					
					$action_to_take2_0 = (isset($action_to_take2[0])) ? $action_to_take2[0] : '';
					$get_action_to_take = trim($action_to_take2_0);
					
					$action_field =  explode('[field_to_action]',$set_action);
					
					$action_field_1 = (isset($action_field[1])) ? $action_field[1] : '';
					$action_field2 =  explode('[end_field_to_action]',$action_field_1);
					
					$action_field2_0 = (isset($action_field2[0])) ? $action_field2[0] : '';
					$get_action_field = explode('##',$action_field2_0);
					
					$action_to =  explode('[to_value]',$set_action);
					
					$action_to_1 = (isset($action_to[1])) ? $action_to[1] : '';
					$get_action_to =  explode('[end_to_value]',$action_to_1);
					
					
					$get_action_field_0 = (isset($get_action_field[0])) ? $get_action_field[0] : '';
					$action_field_type = $get_action_field_0;
					
					$get_action_field_attr = explode('**',$get_action_field_0);
					
					$get_action_field_attr_0 = (isset($get_action_field_attr[0])) ? $get_action_field_attr[0] : '';
					$action_field_id	 = $get_action_field_attr_0;
					
					$get_action_field_attr_1 = (isset($get_action_field_attr[1])) ? $get_action_field_attr[1] : '';
					$action_field_type	 = $get_action_field_attr_1;
					
					$get_action_field_1 = (isset($get_action_field[1])) ? $get_action_field[1] : '';
					$action_field_name	 = $get_action_field_1;
					
						
					if($action_field_type)
						{
						
						
						
						$output .= '<div class="the_rule_actions">';
								
								$output .= '<div class="input-group '.(($get_action_to_take=='skip_to') ? 'steps_only' : '').'">';
								
									$output .= '<span class="input-group-addon">THEN</span><select name="the_action" class="form-control" style="">';
										$output .= '<option '.((!$get_action_to_take) ? 'selected="selected"' : '').' value="0">-- Action --</option>';
										$output .= '<option '.(($get_action_to_take=='show') ? 'selected="selected"' : '').' value="show">Show</option>';
										$output .= '<option '.(($get_action_to_take=='hide') ? 'selected="selected"' : '').' value="hide">Hide</option>';
										$output .= '<option '.(($get_action_to_take=='disable') ? 'selected="selected"' : '').' value="disable">Disable</option>';
										$output .= '<option '.(($get_action_to_take=='enable') ? 'selected="selected"' : '').' value="enable">Enable</option>';
										$output .= '<option '.(($get_action_to_take=='change_value') ? 'selected="selected"' : '').' value="change_value">Change Value</option>';
										$output .= '<option '.(($get_action_to_take=='skip_to') ? 'selected="selected"' : '').' value="skip_to">Skip To Step</option>';

									$output .= '</select>';
									$output .= '<span class="input-group-addon changeable  '.(($get_action_to_take=='change_value') ? 'show_change_value' : '').'"><span class="fa fa-caret-right"></span> <span class="cv_txt">OF</span></span>
									<select name="cla_field" class="cla_field form-control " style="" data-selected="'.$action_field2[0].'">';
										$output .= '<option data-field-id="'.$action_field_id.'" data-field-name="'.$action_field_name.'" data-field-type="'.$action_field_type.'" selected="selected" value="'.$action_field2[0].'">'.$nf_function->unformat_name($action_field_name).'</option>';
									$output .= '</select>';
									
									//$output .= '<select name="cla_steps" class="cl_current_action_fields_steps form-control '.(($get_action_to_take=='skip_to') ? '' : 'hidden').'" style="" data-selected="'.$get_action_skip_to_step[0].'">';
              						//	$output .= '<option value="0">-- Steps --</option>';
            						//$output .= '</select>';
									$output .= '<button class=" button delete_action advanced_options" style=""><span class="fa fa-close"></span></button>';
							
							
								$output .= '</div>';
								$output .= '<div class="input-group show_change_value_to '.(($get_action_to_take=='change_value') ? '' : 'hidden').'">';
								
									$output .= '<span class="input-group-addon"><span class="">TO</span></span><input type="text" name="action_value_to" class="form-control" style="" placeholder="enter new value" value="'.$get_action_to[0].'">';
							
								$output .= '</div>';
								
							$output .= '</div>';
						
						
						}
						//$output .= '</div>';
						
					}
						$output .= '</div>';
						$output .= '<button class=" button add_action advanced_options" style="width:100%;"><span class="fa fa-plus"></span> Add Action</button>';
						$output .= '</div>';
					
					
					$output .= '<div class="con_col col-xs-2 hidden" >';
						$output .= '<h3 class="advanced_options" style="">ELSE</h3>';
						$output .= '<span class="statment_head">ELSE</span> <select name="reverse_actions" class="form-control">';
							$output .= '<option '.((!$reverse_action || $reverse_action=='true') ? 'selected="selected"' : '').' value="true">Reverse Actions</option>';
							$output .= '<option '.((!$reverse_action || $reverse_action=='false') ? 'selected="selected"' : '').' value="false">Do Nothing</option>';
						$output .= '</select>';
						$output .= '
						
						';
					$output .= '</div> <button class="button delete_simple_rule" style="width:15%;"><span class="fa fa-close"></span></button>
					<button class="button duplicate_simple_rule" style="width:15%;"><span class="fa fa-copy"></span></button>
					';
					
					
				$output .= '</div>';
			$output .= '</div>';	
				}
				
				
			}
			$output .= '</div>';
			
			//$output .= '<button class="button btn btn-default add_new_rule"><span class="fa fa-plus"></span>&nbsp;<span class="btn-tx">Add Rule</span></button>';
			$output .= '<div style="clear:both"></div>';

		return $output;	
				
		}
		
		
		
		public function load_conditional_logic_array($form_Id=''){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
						
			$get_logic = $wpdb->prepare('SELECT conditional_logic_array FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id=%d ',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT));
			$conditional_logic = $wpdb->get_var($get_logic);
			
			/*echo '<pre>';
				print_r(json_decode($conditional_logic));
			echo '</pre>';*/
			
			
		$rules = json_decode($conditional_logic);
		$i=1;
		//print_r( $rules);		
		
		
		
		$output = '';
		
		
		$output .= '<div class="conditional_logic_clonables hidden">';
							
							
							$output .= '<div class="panel new_rule">';
								$output .= '<div class="panel-heading advanced_options"><button aria-hidden="true" class="close delete_rule" type="button"><span class="fa fa-close "></span></button></div>';
								$output .= '<div class="panel-body">';
									//IF
									$output .= '<div class="col-xs-7 con_col">';
										$output .= '<h3 class="advanced_options"><strong><div class="badge rule_number">1</div>IF</strong> ';
											$output .= '<select id="operator" style="width:15%; float:none !important; display: inline" class="form-control" name="selector">';
												$output .= '<option value="any" selected="selected"> any </option>';
												$output .= '<option value="all"> all </option>';
											$output .= '</select> ';
										$output .= 'of these conditions are true</h3>';
										$output .= '<div class="get_rule_conditions">';
											$output .= '<div class="the_rule_conditions">';
											$output .= '<span class="statment_head"><div class="badge rule_number">1</div>IF</span> <select name="fields_for_conditions" class="form-control cl_field" style="width:33%;">';
													$output .= '<option selected="selected" value="0">-- Field --</option>';
												$output .= '</select>';
												$output .= '<select name="field_condition" class="form-control" style="width:28%;">';
													$output .= '<option selected="selected" value="0">-- Condition --</option>';
													$output .= '<option value="equal_to">Equal To</option>';
													$output .= '<option value="not_equal_to">Not Equal To</option>';
													$output .= '<option value="less_than">Less Than</option>';
													$output .= '<option value="greater_than">Greater Than</option>';
													$output .= '<option value="less_equal">Less or Equal</option>';
													$output .= '<option  value="greater_equal">Greater or Equal</option>';
													$output .= '<option value="contains">Contains</option>';
			  										$output .= '<option value="not_contains">Not Contain</option>';
										
										
										
													
										
													
													/*$output .= '<option value="contains">Contains</option>';
													$output .= '<option value="not_contians">Does not Contain</option>';
													$output .= '<option value="is_empty">Is Empty</option>';*/
												$output .= '</select>';
												$output .= '<input type="text" name="conditional_value" class="form-control" style="width:28%;" placeholder="enter value">';
												$output .= '<button class=" button delete_condition advanced_options" style="width:11%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
										$output .= '</div>';
									$output .= '</div>';
										
										$output .= '<button class=" button add_condition advanced_options" style="width:100%;"><span class="fa fa-plus"></span> Add Condition</button>';
									$output .= '</div>';
									
									//THEN
									$output .= '<div class="col-xs-5 con_col">';
										$output .= '<h3 class="advanced_options" style="">THEN</h3>';
										$output .= '<div class="get_rule_actions">';
											$output .= '<div class="the_rule_actions">';
											$output .= '<span class="statment_head">THEN</span> <select name="the_action" class="form-control" style="width:40%;">';
												$output .= '<option selected="selected" value="0">-- Action --</option>';
												$output .= '<option value="show">Show</option>';
												$output .= '<option value="hide">Hide</option>';
												$output .= '<option value="disable">Disable</option>';
												$output .= '<option value="enable">Enable</option>';
											$output .= '</select>';
											$output .= '<select name="cla_field" class="cla_field form-control" style="width:42%;">';
											$output .= '</select>';
											$output .= '<button class=" button delete_action advanced_options" style="width:15%;"><span class="fa fa-close"></span></button>';
											
														
											$output .= '</div>';
										$output .= '</div>';
										$output .= '<button class=" button add_action advanced_options" style="width:100%;"><span class="fa fa-plus"></span> Add Action</button>';
										
									$output .= '</div>';
									
									//ELSE
										$output .= '<div class="con_col col-xs-2" >';
											$output .= '<h3 class="advanced_options" style="">ELSE</h3>';
											$output .= '<span class="statment_head">ELSE</span> <select name="reverse_actions" class="form-control">';
												$output .= '<option selected="selected" value="true">Reverse Actions</option>';
												$output .= '<option value="false">Do Nothing</option>';
											$output .= '</select>';
											$output .= '
											
											';
										$output .= '</div><button class="button delete_simple_rule" style="width:15%;"><span class="fa fa-close"></span></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							
							
							
							
							$output .= '<div class="set_rule_conditions">';
								$output .= '<select name="fields_for_conditions" class="form-control cl_field" style="width:33%;">';
									$output .= '<option selected="selected" value="0">-- Field --</option>';
								$output .= '</select>';
								$output .= '<select name="field_condition" class="form-control" style="width:28%;">';
									$output .= '<option selected="selected" value="0">-- Condition --</option>';
									$output .= '<option value="equal_to">Equal To</option>';
									$output .= '<option value="not_equal_to">Not Equal To</option>';
									$output .= '<option value="less_than">Less Than</option>';
									$output .= '<option value="greater_than">Greater Than</option>';
									$output .= '<option value="less_equal">Less or Equal</option>';
									$output .= '<option value="greater_equal">Greater or Equal</option>';
									$output .= '<option value="contains">Contains</option>';
			  						$output .= '<option value="not_contains">Not Contain</option>';
											
									/*$output .= '<option value="contains">Contains</option>';
									$output .= '<option value="not_contians">Does not Contain</option>';
									$output .= '<option value="is_empty">Is Empty</option>';*/
								$output .= '</select>';
								$output .= '<input type="text" name="conditional_value" class="form-control" style="width:28%;" placeholder="enter value">';
								$output .= '<button class=" button delete_condition" style="width:11%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
							$output .= '</div>';
							
							
							$output .= '<div class="set_rule_actions">';
								
								$output .= '<select name="the_action" class="form-control" style="width:40%;">';
									$output .= '<option selected="selected" value="0">-- Action --</option>';
									$output .= '<option value="show">Show</option>';
									$output .= '<option value="hide">Hide</option>';
									$output .= '<option value="disable">Disable</option>';
									$output .= '<option value="enable">Enable</option>';
								$output .= '</select>';
								$output .= '<select name="cla_field" class="cla_field form-control" style="width:42%;">';
								$output .= '</select>';
								$output .= '<button class=" button delete_action" style="width:15%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
							$output .= '</div>';
							
						
						
						
						$output .= '</div>';
		
		
		$output .= '<div class="set_rules">';
				
			if(!empty($rules))
				{	
				foreach($rules as $rule)
					{
					if($rule)
						{
						$output .= '<div class="panel new_rule">';
							$output .= '<div class="panel-heading advanced_options"><button aria-hidden="true" data-dismiss="modal" class="close delete_rule" type="button"><span class="fa fa-close "></span></button></div>';
							$output .= '<div class="panel-body">';
						
								
							$rule_operator = $rule->operator;
							$reverse_action = $rule->reverse_actions;
						
							$output .= '<div class="col-xs-7 con_col">';
								$output .= '<h3 class="advanced_options"><strong><div class="badge rule_number">1</div>IF</strong> ';
									$output .= '<select id="operator" style="width:15%; float:none !important; display: inline" class="form-control" name="selector">';
										$output .= '<option value="any" '.(($rule_operator=='any' || !$rule_operator) ? 'selected="selected"' : '').'> any </option>';
										$output .= '<option value="all" '.(($rule_operator=='all' || !$rule_operator) ? 'selected="selected"' : '').'> all </option>';
									$output .= '</select> ';
								$output .= 'of these conditions are true</h3>';
							
								$output .= '<div class="get_rule_conditions">';
						
						foreach($rule->conditions as $condition)
							{
							$get_the_condition 	=  $condition->condition;
							$get_the_value 		=  $condition->condition_value;
							$selection_value 	=  $condition->selected_value;
							$selection_name 	=  $condition->field_name;
							
							if($get_the_condition)
								{
								
								$output .= '<div class="the_rule_conditions">';
										$output .= '<span class="statment_head"><div class="badge rule_number">1</div>IF</span>';
										
										$output .= '<div class="input-group">';
										
											$output .= '<select name="fields_for_conditions" class="form-control cl_field" style="width:33%;" data-selected="'.$selection_value.'">';
												$output .= '<option selected="selected" value="'.$selection_value.'">'.$selection_name.'</option>';
												
											$output .= '</select>';
											$output .= '<select name="field_condition" class="form-control" style="width:28%;">';
												$output .= '<option '.((!$get_the_condition) ? 'selected="selected"' : '').' value="0" >-- Condition --</option>';
												$output .= '<option '.(($get_the_condition=='equal_to') ? 'selected="selected"' : '').' 	value="equal_to">Equal To</option>';
												$output .= '<option '.(($get_the_condition=='not_equal_to') ? 'selected="selected"' : '').' value="not_equal_to">Not Equal To</option>';
												$output .= '<option '.(($get_the_condition=='less_than') ? 'selected="selected"' : '').' 	value="less_than">Less Than</option>';
												$output .= '<option '.(($get_the_condition=='greater_than') ? 'selected="selected"' : '').' value="greater_than">Greater Than</option>';
												$output .= '<option '.(($get_the_condition=='less_equal') ? 'selected="selected"' : '').' value="less_equal">Less or Equal</option>';
												$output .= '<option '.(($get_the_condition=='greater_equal') ? 'selected="selected"' : '').' value="greater_equal">Greater or Equal</option>';
												$output .= '<option '.(($get_the_condition=='contains') ? 'selected="selected"' : '').' value="contains">Contains</option>';
												$output .= '<option '.(($get_the_condition=='not_contains') ? 'selected="selected"' : '').' value="not_contains">Not Contain</option>';
												
											$output .= '</select>';
											$output .= '<input type="text" name="conditional_value" class="form-control" style="width:28%;" placeholder="enter value" value="'.$get_the_value.'">';
											$output .= '<button class=" button delete_condition advanced_options" style="width:11%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
										$output .= '</div>';
									
									
									$output .= '</div>';
								
								}
								
							}		
							$output .= '</div>';
							
							$output .= '<button class=" button add_condition advanced_options" style="width:100%;"><span class="fa fa-plus"></span> Add Condition</button>';
						$output .= '</div>';
											
						//THEN
						$output .= '<div class="col-xs-5 con_col">';
							$output .= '<h3 class="advanced_options" style="">THEN</h3>';
							$output .= '<div class="get_rule_actions">';
							
						foreach($rule->actions as $action)
							{
							
							$get_action_to_take = $action->do_action;
							$selection_value = $action->selected_value;
							$selection_name = $action->target_field_name;
							
							if($get_action_to_take)
								{						
								$output .= '<div class="the_rule_actions">';
									
										$output .= '<span class="statment_head">THEN</span><select name="the_action" class="form-control" style="width:40%;">';
											$output .= '<option '.((!$get_action_to_take) ? 'selected="selected"' : '').' value="0">-- Action --</option>';
											$output .= '<option '.(($get_action_to_take=='show') ? 'selected="selected"' : '').' value="show">Show</option>';
											$output .= '<option '.(($get_action_to_take=='hide') ? 'selected="selected"' : '').' value="hide">Hide</option>';
											$output .= '<option '.(($get_action_to_take=='disable') ? 'selected="selected"' : '').' value="disable">Disable</option>';
											$output .= '<option '.(($get_action_to_take=='enable') ? 'selected="selected"' : '').' value="enable">Enable</option>';
										$output .= '</select>';
										$output .= '<select name="cla_field" class="cla_field form-control" style="width:42%;" data-selected="'.$selection_value.'">';
											$output .= '<option selected="selected" value="'.$selection_value.'">rdhsfdhfhs</option>';
										$output .= '</select>';
										$output .= '<button class=" button delete_action advanced_options" style="width:15%;"><span class="fa fa-close"></span></button>';
									$output .= '</div>';
								}
								
							}
								$output .= '</div>';
								$output .= '<button class=" button add_action advanced_options" style="width:100%;"><span class="fa fa-plus"></span> Add Action</button>';
								$output .= '</div>';
							
							
							$output .= '<div class="con_col col-xs-2 hidden" >';
								$output .= '<h3 class="advanced_options" style="">ELSE</h3>';
								$output .= '<span class="statment_head">ELSE</span> <select name="reverse_actions" class="form-control">';
									$output .= '<option '.((!$reverse_action || $reverse_action=='true') ? 'selected="selected"' : '').' value="true">Reverse Actions</option>';
									$output .= '<option '.((!$reverse_action || $reverse_action=='false') ? 'selected="selected"' : '').' value="false">Do Nothing</option>';
								$output .= '</select>';
								$output .= '
								
								';
							$output .= '</div><button class="button delete_simple_rule" style="width:15%;"><span class="fa fa-close"></span></button>';
							
							
						$output .= '</div>';
					$output .= '</div>';	
						}
						
						
					}
				}
			$output .= '</div>';
			
			//$output .= '<button class="button btn btn-default add_new_rule"><span class="fa fa-plus"></span>&nbsp;<span class="btn-tx">Add Rule</span></button>';
			$output .= '<div style="clear:both"></div>';
	//echo '</pre>';
		return $output;	
				
		}
		
		
		
		
		public function get_email_setup(){
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global $wpdb;
			if($_POST['form_Id'])
				{
				$get_id = 'Id';
				if($_POST['status']=='draft')
					$get_id = 'draft_Id';
					
				$form_Id = sanitize_title($_POST['form_Id']);
			
				$form = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE '.$get_id.'=%d',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT)));
				}
	//ADMIN EMAIL SETUP
					$preferences = get_option('nex-forms-preferences');
							$output .= '<div role="tabpanel" class="tab-pane active" id="admin-email">';
							
							
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">From Address</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" name="nex_autoresponder_from_address" id="nex_autoresponder_from_address"  placeholder="Enter From Address" value="'.(($form->from_address) ? str_replace('\\','',$form->from_address) : $preferences['email_preferences']['pref_email_from_address']).'">';
									$output .= '</div>';
								$output .= '</div>';
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">From Name</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" name="nex_autoresponder_from_name" id="nex_autoresponder_from_name"  placeholder="Enter From Name"  value="'.(($form->from_name) ? str_replace('\\','',$form->from_name) : $preferences['email_preferences']['pref_email_from_name']).'">';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">Recipients</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" name="nex_autoresponder_recipients" id="nex_autoresponder_recipients"  placeholder="Example: email@domian.com, email2@domian.com" value="'.(($form->mail_to) ? str_replace('\\','',$form->mail_to) : $preferences['email_preferences']['pref_email_recipients']).'">';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">BCC</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" name="nex_admin_bcc_recipients" id="nex_admin_bcc_recipients"  placeholder="Example: email@domian.com, email2@domian.com" value="'.(($form->bcc) ? str_replace('\\','',$form->bcc) : '').'" >';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">Subject</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" name="nex_autoresponder_confirmation_mail_subject" id="nex_autoresponder_confirmation_mail_subject"  placeholder="Enter Email Subject" value="'.(($form->confirmation_mail_subject) ? str_replace('\\','',$form->confirmation_mail_subject) : $preferences['email_preferences']['pref_email_subject']).'">';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="row">';
									$output .= '<div class="col-xs-3">';
										$output .= '<small>Placeholders/Tags</small>';
										$output .= '<select name="email_field_tags" multiple="multiple"></select>';
									$output .= '</div>';
									$output .= '<div class="col-xs-9">';
										$output .= '<small>Admin Mail Body</small>';
										$output .= '<textarea style="width:100% !important;" placeholder="Enter Email Body. Use text or HTML" class="form-control" name="nex_autoresponder_admin_mail_body" id="nex_autoresponder_admin_mail_body">'.(($form->admin_email_body) ? str_replace('\\','',$form->admin_email_body) : $preferences['email_preferences']['pref_email_body']).'</textarea>';  //wp_editor( 'test', 'nex_autoresponder_admin_mail_body', $settings = array() );//
									$output .= '</div>';
								$output .= '</div>';
								
							
							$output .= '</div>';
							
					//USER EMAIL SETUP			
							$output .= '<div role="tabpanel" class="tab-pane" id="user-email">';
									
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">Recipients (map email field)</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<select class="form-control" data-selected="'.$form->user_email_field.'" id="nex_autoresponder_user_email_field" name="posible_email_fields"><option value="">Dont send confirmation mail to user</option></select>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">BCC</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" name="nex_autoresponder_bcc_recipients" id="nex_autoresponder_bcc_recipients"  placeholder="Example: email@domian.com, email2@domian.com" value="'.(($form->bcc_user_mail) ? str_replace('\\','',$form->bcc_user_mail) : '').'" >';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">Subject</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" name="nex_autoresponder_user_confirmation_mail_subject" id="nex_autoresponder_user_confirmation_mail_subject"  placeholder="Enter Email Subject" value="'.(($form->user_confirmation_mail_subject) ? str_replace('\\','',$form->user_confirmation_mail_subject) :  $preferences['email_preferences']['pref_user_email_subject']).'">';
									$output .= '</div>';
								$output .= '</div>';
																	
								$output .= '<div class="row">';
									$output .= '<div class="col-xs-3">';
										$output .= '<small>Placeholders/Tags</small>';
										$output .= '<select name="user_email_field_tags" multiple="multiple"></select>';
									$output .= '</div>';
									$output .= '<div class="col-xs-9">';
										$output .= '<small>Autoresponder Mail Body</small>';
										$output .= '<textarea style="width:100% !important;" placeholder="Enter Email Body. Use text or HTML" class="form-control" name="nex_autoresponder_confirmation_mail_body" id="nex_autoresponder_confirmation_mail_body">'.(($form->confirmation_mail_body) ? str_replace('\\','',$form->confirmation_mail_body) :  $preferences['email_preferences']['pref_user_email_body']).'</textarea>';  //wp_editor( 'test', 'nex_autoresponder_admin_mail_body', $settings = array() );//
									$output .= '</div>';
								$output .= '</div>';
								
							$output .= '</div>';
							
							
					
						
			
			echo $output;
			die();	
		}
		
		
		public function get_pdf_setup(){
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global $wpdb;
			if($_POST['form_Id'])
				{
				$get_id = 'Id';
				if($_POST['status']=='draft')
					$get_id = 'draft_Id';
					
				$form_Id = sanitize_title($_POST['form_Id']);
			
				$form = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE '.$get_id.'=%d',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT)));
				}
			//PDF SETUP
					$preferences = get_option('nex-forms-preferences');
							
								
								
			
			if ( function_exists('NEXForms_export_to_PDF') ) {
					
					$pdf_attach = explode(',',$form->attach_pdf_to_email);
					$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">PDF Email Attachements</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<label for="pdf_admin_attach"><input '.(in_array('admin',$pdf_attach) ? 'checked="checked"': '').' name="pdf_admin_attach" value="1" id="pdf_admin_attach" type="checkbox"> Attach this PDF to Admin Notifications Emails<em></em></label>';
										$output .= '<label for="pdf_user_attach"><input '.(in_array('user',$pdf_attach) ? 'checked="checked"': '').' name="pdf_user_attach" value="1" id="pdf_user_attach" type="checkbox"> Attach this PDF to Autoresponder User Emails<em></em></label>';
									$output .= '</div>';
								$output .= '</div>';
					$output .= '<div class="row">';
									$output .= '<div class="col-xs-3">';
										$output .= '<small>Placeholders/Tags</small>';
										$output .= '<select name="pdf_field_tags" multiple="multiple"></select>';
									$output .= '</div>';
									$output .= '<div class="col-xs-9">';
										$output .= '<small>PDF Layout</small>';
										$output .= '<textarea style="width:100% !important;" placeholder="Enter your PDF body content" class="form-control" name="nex_pdf_html" id="nex_pdf_html">'.(($form->pdf_html) ? str_replace('\\','',$form->pdf_html) : $preferences['email_preferences']['pdf_html']).'</textarea>';  //wp_editor( 'test', 'nex_autoresponder_admin_mail_body', $settings = array() );//
									$output .= '</div>';
								$output .= '</div>';
					
			}
			else
				{
				$output .= '<div class="alert alert-success">You need the "<strong><em>PDF Creator for NEX-forms</em></strong>" Add-on to create your own PDF\'s from form data and also have the ability to send these PDF\'s via your admin and usert emails! <br>&nbsp;<a class="btn btn-success btn-large form-control" target="_blank" href="https://codecanyon.net/item/export-to-pdf-for-nexforms/11220942?ref=Basix">Buy Now</a></div>';
				}
			
			echo $output;
			die();	
		}
		
		
		public function get_hidden_fields($form_Id=''){
			global $wpdb;
				
				if(!current_user_can( NF_USER_LEVEL ))	
					wp_die();
				
				if($form_Id)
					{
					$get_form = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id= %d ',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT));
					$form = $wpdb->get_row($get_form);
					}
				//HIDDEN FIELDS SETUP	
					
					$output = '<div class="paypal-items-column"><div class="material_box">
									<div class="material_box_head"><button id="add_hidden_field"  class="add_hidden_field tour_hidden_fields_setup_1 button btn btn-default"><span class="fa fa-plus"></span> Add Hidden Field</button></div>
							
					';
						
					$output .= 	'<div class="material_box_content hidden_fields_setup">';
							$output .= '
							
								<div class="hidden_field_clone hidden">
									<div class="input-group input-group-sm">
										<div class="input-group-addon name_label">Field Name</div><input class="form-control field_name hidden_field_name" type="text" placeholder="Enter field name" value="">
										<div class="input-group-addon the_hidden_field_value">
											<select name="set_hidden_field_value" class="">
																	<optgroup label="Dynamic Variables">
																		<option value="0" selected="selected">Field Value</option>
																		<option value="{{FORM_TITLE}}">Form Title</option>
																		<option value="{{PAGE_TITLE}}">Page Title</option>
																		<option value="{{PAGE_ID}}">Page ID</option>
																		<option value="{{PAGE_URL}}">Page URL</option>
																		<option value="{{DATE_TIME}}">Date and Time</option>
																		<option value="{{DATE}}">Date only</option>
																		<option value="{{TIME}}">Time only</option>
																		<option value="{{DATE_DAY}}">Date - Day</option>
																		<option value="{{DATE_MONTH}}">Date - Month</option>
																		<option value="{{DATE_YEAR}}">Date - Year</option>
																		<option value="{{WP_USER}}">Current User Name</option>
																		<option value="{{WP_USER_FIRST_NAME}}">Current First Name</option>
																		<option value="{{WP_USER_LAST_NAME}}">Current Last Name</option>
																		<option value="{{WP_USER_EMAIL}}">Current User Email</option>
																		<option value="{{WP_USER_URL}}">Current User URL</option>
																		<option value="{{WP_USER_IP}}">Current User IP</option>
																		<option value="{{WP_USER_ID}}">Current User ID</option>
																	</optgroup>
																	
																	<optgroup label="Server Variables">
																		<option value="{{DOCUMENT_ROOT}}">DOCUMENT_ROOT</option>
																		<option value="{{HTTP_REFERER}}">HTTP_REFERER</option>
																		<option value="{{REMOTE_ADDR}}">REMOTE_ADDR</option>
																		<option value="{{REQUEST_URI}}">REQUEST_URI</option>
																		<option value="{{HTTP_USER_AGENT}}">HTTP_USER_AGENT</option>											
																	</optgroup>
																</select>
										</div><input class="form-control field_value hidden_field_value" type="text" placeholder="Enter field value" value="">
										<div class="input-group-addon remove_hidden_field">
											<span class="fa fa-close"></span>
										</div>
									</div>
								</div>
							
							<div class="hidden_fields">
							';
						
						$hidden_options = '';
						
						if($form_Id)
							{
							
							if($form->hidden_fields)
								{
								$hidden_fields_raw = explode('[end]',$form->hidden_fields);
			
								foreach($hidden_fields_raw as $hidden_field)
									{
									$hidden_field = explode('[split]',$hidden_field);
									if($hidden_field[0])
										{
										$output .= '<div class="hidden_field"><div class="input-group input-group-sm">';
												$output .= '<div class="input-group-addon name_label">Field Name</div><input type="text" class="form-control field_name hidden_field_name" value="'.$hidden_field[0].'" placeholder="Enter field name">';
												$output .= '<div class="input-group-addon the_hidden_field_value">
																<select name="set_hidden_field_value" class="">
																	<optgroup label="Dynamic Variables">
																		<option value="0" selected="selected">Field Value</option>
																		<option value="{{FORM_TITLE}}">Form Title</option>
																		<option value="{{PAGE_TITLE}}">Page Title</option>
																		<option value="{{PAGE_ID}}">Page ID</option>
																		<option value="{{PAGE_URL}}">Page URL</option>
																		<option value="{{DATE_TIME}}">Date and Time</option>
																		<option value="{{DATE}}">Date only</option>
																		<option value="{{TIME}}">Time only</option>
																		<option value="{{DATE_DAY}}">Date - Day</option>
																		<option value="{{DATE_MONTH}}">Date - Month</option>
																		<option value="{{DATE_YEAR}}">Date - Year</option>																		
																		<option value="{{WP_USER}}">Current User Name</option>
																		<option value="{{WP_USER_EMAIL}}">Current User Email</option>
																		<option value="{{WP_USER_URL}}">Current User URL</option>
																		<option value="{{WP_USER_IP}}">Current User IP</option>
																		<option value="{{WP_USER_ID}}">Current User ID</option>
																	</optgroup>
																	
																	<optgroup label="Server Variables">
																		<option value="{{DOCUMENT_ROOT}}">DOCUMENT_ROOT</option>
																		<option value="{{HTTP_REFERER}}">HTTP_REFERER</option>
																		<option value="{{REMOTE_ADDR}}">REMOTE_ADDR</option>
																		<option value="{{REQUEST_URI}}">REQUEST_URI</option>
																		<option value="{{HTTP_USER_AGENT}}">HTTP_USER_AGENT</option>											
																	</optgroup>
																</select>
												</div><input type="text" class="form-control field_value hidden_field_value" value="'.$hidden_field[1].'" placeholder="Enter field value">';
												$output .= '<div class="input-group-addon remove_hidden_field"><span class="fa fa-close"></span></div>';
												
												$hidden_options .= '<option value="'.trim($hidden_field[0]).'">'.$hidden_field[0].'</option>';
												
										$output .= '</div></div>';
										}
									}
								}
							}
							$output .= '<div class="hidden_form_fields hidden">'.$hidden_options.'</div>
							';
							
						$output .= '</div>
							';					
								
				//$output .= '<button class="button btn btn-default add_hidden_field"><span class="fa fa-plus"></span>&nbsp;<span class="btn-tx">Add hidden Field</span></div></button>';
			$output .= '<div style="clear:both"></div></div></div>';
			return $output;
			
			
		}
		
		
		
		
		public function get_form_hidden_fields($form_Id=''){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			
				if($form_Id)
					{
					$get_form = $wpdb->prepare('SELECT form_hidden_fields FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id= %d ',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT));
					$hidden_fields = $wpdb->get_var($get_form);
					}
				//HIDDEN FIELDS SETUP	
					
					$output = '<div class="paypal-items-column"><div class="material_box">
									<div class="material_box_head"><button id="add_hidden_field"  class="add_hidden_field tour_hidden_fields_setup_1 button btn btn-default"><span class="fa fa-plus"></span> Add Hidden Field</button></div>
							
					';
						
					$output .= 	'<div class="material_box_content  hidden_fields_setup">';
							$output .= '
							
								<div class="hidden_field_clone hidden">
									<div class="input-group input-group-sm">
										<div class="input-group-addon name_label">Field Name</div><input class="form-control field_name hidden_field_name" type="text" placeholder="Enter field name" value="">
										<div class="input-group-addon the_hidden_field_value">
											<select name="set_hidden_field_value" class="">
																	<optgroup label="Dynamic Variables">
																		<option value="0" selected="selected">Field Value</option>
																		<option value="{{FORM_TITLE}}">Form Title</option>
																		<option value="{{PAGE_TITLE}}">Page Title</option>
																		<option value="{{PAGE_ID}}">Page ID</option>
																		<option value="{{PAGE_URL}}">Page URL</option>
																		<option value="{{DATE_TIME}}">Date and Time</option>
																		<option value="{{DATE}}">Date only</option>
																		<option value="{{TIME}}">Time only</option>
																		<option value="{{DATE_DAY}}">Date - Day</option>
																		<option value="{{DATE_MONTH}}">Date - Month</option>
																		<option value="{{DATE_YEAR}}">Date - Year</option>
																		<option value="{{WP_USER}}">Current User Name</option>
																		<option value="{{WP_USER_FIRST_NAME}}">Current First Name</option>
																		<option value="{{WP_USER_LAST_NAME}}">Current Last Name</option>
																		<option value="{{WP_USER_EMAIL}}">Current User Email</option>
																		<option value="{{WP_USER_URL}}">Current User URL</option>
																		<option value="{{WP_USER_IP}}">Current User IP</option>
																		<option value="{{WP_USER_ID}}">Current User ID</option>
																	</optgroup>
																	
																	<optgroup label="Server Variables">
																		<option value="{{DOCUMENT_ROOT}}">DOCUMENT_ROOT</option>
																		<option value="{{HTTP_REFERER}}">HTTP_REFERER</option>
																		<option value="{{REMOTE_ADDR}}">REMOTE_ADDR</option>
																		<option value="{{REQUEST_URI}}">REQUEST_URI</option>
																		<option value="{{HTTP_USER_AGENT}}">HTTP_USER_AGENT</option>											
																	</optgroup>
																</select>
										</div><input class="form-control field_value hidden_field_value" type="text" placeholder="Enter field value" value="">
										<div class="input-group-addon remove_hidden_field">
											<span class="fa fa-close"></span>
										</div>
									</div>
								</div>';
							
							$output .= '<div class="hidden_fields">
							';
						
						$hidden_options = '';
						
						if($form_Id)
							{
							
							if($hidden_fields)
								{
								$hidden_fields_array = json_decode($hidden_fields);
			
								foreach($hidden_fields_array as $hidden_field)
									{
									
									//if($hidden_field[0])
										//{
										$output .= '<div class="hidden_field"><div class="input-group input-group-sm">';
												$output .= '<div class="input-group-addon name_label">Field Name</div><input type="text" class="form-control field_name hidden_field_name" value="'.$hidden_field->field_name.'" placeholder="Enter field name">';
												$output .= '<div class="input-group-addon the_hidden_field_value">
																<select name="set_hidden_field_value" class="">
																	<optgroup label="Dynamic Variables">
																		<option value="0" selected="selected">Field Value</option>
																		<option value="{{FORM_TITLE}}">Form Title</option>
																		<option value="{{PAGE_TITLE}}">Page Title</option>
																		<option value="{{PAGE_ID}}">Page ID</option>
																		<option value="{{PAGE_URL}}">Page URL</option>
																		<option value="{{DATE_TIME}}">Date and Time</option>
																		<option value="{{DATE}}">Date only</option>
																		<option value="{{TIME}}">Time only</option>
																		<option value="{{DATE_DAY}}">Date - Day</option>
																		<option value="{{DATE_MONTH}}">Date - Month</option>
																		<option value="{{DATE_YEAR}}">Date - Year</option>																		
																		<option value="{{WP_USER}}">Current User Name</option>
																		<option value="{{WP_USER_EMAIL}}">Current User Email</option>
																		<option value="{{WP_USER_URL}}">Current User URL</option>
																		<option value="{{WP_USER_IP}}">Current User IP</option>
																		<option value="{{WP_USER_ID}}">Current User ID</option>
																	</optgroup>
																	
																	<optgroup label="Server Variables">
																		<option value="{{DOCUMENT_ROOT}}">DOCUMENT_ROOT</option>
																		<option value="{{HTTP_REFERER}}">HTTP_REFERER</option>
																		<option value="{{REMOTE_ADDR}}">REMOTE_ADDR</option>
																		<option value="{{REQUEST_URI}}">REQUEST_URI</option>
																		<option value="{{HTTP_USER_AGENT}}">HTTP_USER_AGENT</option>											
																	</optgroup>
																</select>
												</div><input type="text" class="form-control field_value hidden_field_value" value="'.$hidden_field->field_value.'" placeholder="Enter field value">';
												$output .= '<div class="input-group-addon remove_hidden_field"><span class="fa fa-close"></span></div>';
												
												$hidden_options .= '<option value="'.trim($hidden_field->field_name).'">'.$hidden_field->field_name.'</option>';
												
										$output .= '</div></div>';
										//}
									}
								}
							}
							$output .= '<div class="hidden_form_fields hidden">'.$hidden_options.'</div>
							';
							
						$output .= '</div></div>
							';					
								
				//$output .= '<button class="button btn btn-default add_hidden_field"><span class="fa fa-plus"></span>&nbsp;<span class="btn-tx">Add hidden Field</span></div></button>';
				$output .= '<div style="clear:both"></div></div>';
			return $output;
			
			
		}
		
		
		
		public function get_options_setup(){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			if($_POST['form_Id'])
				{
				$get_id = 'Id';
				if($_POST['status']=='draft')
					$get_id = 'draft_Id';
					
				$form_Id = sanitize_title($_POST['form_Id']);	
				
				$form = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE '.$get_id.'=%d',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT)));
				}
			$preferences = get_option('nex-forms-preferences');	
	//FORM ATTR
		
		$form_type = $form->form_type;
		
		if($_POST['form_type'])
			$form_type = sanitize_text_field($_POST['form_type']);
		
		$output .= '<div class="form_attr hidden">';
			$output .= '<div class="form_type">';
				$output .= ($form_type) ? $form_type : 'normal';
			$output .= '</div>';
			$output .= '<div class="form_title">';
				$output .= $form->title;
			$output .= '</div>';			
		$output .= '</div>';
	//ON SUBMIT SETUP
	
							$output .= 	'<div class="on_submit_setup">';
								$output .= '<div role="toolbar" class="btn-toolbar">';
	/*** From Address ***/	
									$output .= '<div role="group" class="btn-group post_action">';
										$output .= '<small>Post Action</small>';
										$output .= '<button class="btn btn-default ajax '.((!$form->post_action || $form->post_action=='ajax') ? 'active' : '' ).'" type="button" title="Use AJAX with no page refreshing" data-value="ajax"><span class="btn-tx">AJAX</span></button>';
										$output .= '<button class="btn btn-default custom '.(($form->post_action=='custom') ? 'active' : '' ).'" type="button" title="Post Form to custom URL" data-value="custom"><span class="btn-tx">Custom</span></button>';
									$output .= '</div>';
									
									$output .= '<div role="group" class="btn-group on_form_submission '.(($form->post_action=='custom') ? 'hidden' : '' ).'">';
										$output .= '<small>After Submit</small>';
										$output .= '<button class="btn btn-default message '.((!$form->on_form_submission || $form->on_form_submission=='message') ? 'active' : '' ).'" type="button" title="Show on-screen message" data-value="message"><span class="btn-tx">Show Message</span></button>';
										$output .= '<button class="btn btn-default redirect '.(($form->on_form_submission=='redirect') ? 'active' : '' ).'" type="button" title="Redirect to a URL after submit" data-value="redirect"><span class="btn-tx">Redirect</span></button>';
									$output .= '</div>';
									
									$output .= '<div role="group" class="btn-group post_method '.(($form->post_action=='custom') ? '' : 'hidden' ).'">';
										$output .= '<small>Submmision method</small>';
										$output .= '<button class="btn btn-default post '.((!$form->post_type || $form->post_type=='POST') ? 'active' : '' ).'" type="button" title="Use POST" data-value="POST"><span class="btn-tx">POST</span></button>';
										$output .= '<button class="btn btn-default get '.(($form->post_type=='GET') ? 'active' : '' ).'" type="button" title="USE GET" data-value="GET"><span class="btn-tx">GET</span></button>';
									$output .= '</div>';
									
								$output .= '</div>';
								
								
								
								//On screen confirmation message
								$output .= '<div class="ajax_settings '.(($form->post_action=='custom') ? 'hidden' : '' ).'"><div class="on_screen_message_settings '.(($form->on_form_submission=='message' || !$form->on_form_submission) ? '' : 'hidden' ).'"><small>On-screen confirmation message</small><textarea class="form-control" name="on_screen_confirmation_message" id="nex_autoresponder_on_screen_confirmation_message">'.(($form->on_screen_confirmation_message) ? str_replace('\\','',$form->on_screen_confirmation_message) : $preferences['other_preferences']['pref_other_on_screen_message'] ).'</textarea></div>';
								
								$output .= '<div class="row redirect_settings '.(($form->on_form_submission=='redirect') ? '' : 'hidden' ).'">';
									$output .= '<div class="col-sm-4">Redirect to</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" value="'.$form->confirmation_page.'" placeholder="Enter URL" name="confirmation_page" id="nex_autoresponder_confirmation_page" data-tag-class="label-info">';
									$output .= '</div>';
								$output .= '</div>';
								
								
								
								
							$output .= '</div>';
							$output .= '<div class="row custom_url_settings '.(($form->post_action=='custom') ? '' : 'hidden' ).'">';
									$output .= '<div class="col-sm-4">Submit form to</div>';
									$output .= '<div class="col-sm-8">';
										$output .= '<input type="text" class="form-control" value="'.$form->custom_url.'" name="custum_url" placeholder="Enter Custom URL" id="on_form_submission_custum_url" data-tag-class="label-info">';
									$output .= '</div>';
								$output .= '</div>';
	
			echo $output;
			die();	
		}
		public function load_form_entries(){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			
			$args 		= str_replace('\\','',$_POST['args']);
			$headings 	= array('Form Name'=>'nex_forms_Id','Page'=>'page','IP Address'=>'ip','User'=>'user_Id','Date Submitted'=>'date_time');
			
			$form_Id = sanitize_title($_POST['form_Id']);
			$post_additional_params = sanitize_text_field($_POST['additional_params']);
			$plugin_alias = sanitize_text_field($_POST['plugin_alias']);
			$orderby = sanitize_text_field($_POST['orderby']);
			$current_page = sanitize_text_field($_POST['current_page']);
			
			$nf_functions 		= new NEXForms_Functions();	
			$db_actions 		= new NEXForms_Database_Actions();
			
			$additional_params = json_decode(str_replace('\\','',$post_additional_params),true);
			
			if(is_array($additional_params))
				{
				foreach($additional_params as $column=>$val)
					$where_str .= ' AND '.$column.'="'.$val.'"';
				}
			
			if($form_Id)
				$where_str .= ' AND nex_forms_Id='.filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT);
			
			
			$results 	= $wpdb->get_results($wpdb->prepare('SELECT * FROM '. $wpdb->prefix . 'wap_nex_forms_entries WHERE Id <> "" 
											'.(($tree) ? ' AND parent_Id="0"' : '').' 
											'.(($plugin_alias) ? ' AND (plugin="'.filter_var($plugin_alias,FILTER_SANITIZE_STRING).'" || plugin="shared")' : '').' 
											'.$where_str.'   
											ORDER BY 
											'.((isset($orderby) && !empty($orderby)) ? filter_var($orderby,FILTER_SANITIZE_STRING).' 
											'.filter_var($orderby,FILTER_SANITIZE_STRING) : 'Id DESC').' 
											LIMIT '.((isset($current_page)) ? filter_var($current_page,FILTER_SANITIZE_NUMBER_INT)*10 : '0'  ).',10 ',''));
			
			
			$output .= '<table class="table table-striped">';
			
			$output .= '<tr><th class="entry_Id">ID</th>';
			
			$order = sanitize_text_field($_POST['order']);
			
			foreach($headings as $heading=>$val)	
						{
						$output .= '<th class="manage-column sortable column-'.$val.'"><a class="'.(($order) ? $order : 'asc').'"><span data-col-order="'.(($order) ? $order : 'asc').'" data-col-name="'.$val.'" class="sortable-column">'.$heading.'</span></a></th>';
						}
			$output .= '<th>&nbsp;</th></tr>';
			if($results)
				{			
				foreach($results as $data)
					{	
					$output .= '<tr>';
					$output .= '<td class="manage-column column-">'.$data->Id.'</td>';
					$k=1;
					foreach($headings as $heading)	
						{
						
						$heading = $nf_functions->format_name($heading);
						$heading = str_replace('_id','_Id',$heading);
						
						if($heading=='user_Id')
							{
							$val = $db_actions->get_username($data->$heading);	
							}
						else
							{
							$val = (strstr($heading,'Id')) ? $db_actions->get_title($data->$heading,'wap_'.str_replace('_Id','',$heading)) : $data->$heading;
							
							
							$val = str_replace('\\', '', $db_actions->view_excerpt($val,25));
							}
						
						$output .= '<td class="manage-column column-'.$heading.'">'.(($k==1) ? '<strong>'.$val.'</strong>' : $val).'';
						$k++;
						}
					
					$output .= '<td width="16%" align="right" class="view_export_del">';
					
					if ( function_exists('NEXForms_export_to_PDF') )
						$output .= '<a target="_blank" title="PDF [new window]" href="'.WP_PLUGIN_URL . '/nex-forms-export-to-pdf/examples/main.php?entry_ID='.$data->Id.'" class="nf-button"><span class="fa fa-file-pdf-o"></span> PDF</div></a>&nbsp;';
					else
						$output .= '<a target="_blank" title="Get export to PDF add-on" href="http://codecanyon.net/item/export-to-pdf-for-nexforms/11220942?ref=Basix" class="nf-button buy">PDF</a>&nbsp;';
					
					$output .= '<a class="nf-button view_form_entry" data-target="#viewFormEntry" data-toggle="modal"  data-id="'.$data->Id.'">View</a>
					<a data-original-title="Delete" title="" data-placement="top" data-toggle="tooltip" class="do_delete_entry nf-button" id="'.$data->Id.'">&nbsp;
					<span class="fa fa-trash"></span>&nbsp;</a>
					
					</td>';
					$output .= '</tr>';	
					
					}
				}
			else
				{
				$output .= '<tr>';	
				$output .= '<td></td><td class="manage-column" colspan="'.(count($headings)).'">No items found</td>';
				$output .= '</tr>';
				}
			
			$output .= '</table>';
				
			echo $output;
			die();

		
		}
		
		public function populate_form_entry(){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			
			$edit_entry = 0;
			
			$db_actions = new NEXForms_Database_Actions();
			$nf_functions 		= new NEXForms_Functions();
			
			if($_POST['edit_entry'])
				$edit_entry = sanitize_text_field($_POST['edit_entry']);
			
			
			
			
			$form_entry_Id = sanitize_title($_POST['form_entry_Id']);
			 if(isset($_POST['batch']) && $_POST['batch']=='false')
				 	{
					$form_entry = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE Id= %d',filter_var($form_entry_Id,FILTER_SANITIZE_NUMBER_INT)));
					
					
					$form_data = json_decode($form_entry->form_data);
					}
				
			
			$checkout = (isset($_POST['load_entry']) ? sanitize_text_field($_POST['load_entry']) : false);
			$output = '';
			
			if($checkout)
				{					
				 
				 $update = $wpdb->update($wpdb->prefix.'wap_nex_forms_entries',array('viewed'=>'viewed'), array('Id'=>filter_var($form_entry_Id,FILTER_SANITIZE_NUMBER_INT)));
				 
				 if(isset($_POST['batch']) && $_POST['batch']=='false')
				 	{
					 $output .= '<div class="entry_wrapper">';
						 $output .= '<div class="additional_entry_details hidden">';
							$output .= '<div class="entry_id">#<strong>'.$form_entry->Id.'</strong></div>';
							$output .= '<div class="the_form">Form: <strong>'.$db_actions->get_title($form_entry->nex_forms_Id,'wap_nex_forms').'</strong></div>';
							$output .= '<div class="page">Page: <strong>'.$form_entry->page.'</strong></div>';
							$output .= '<div class="date_time">Date: <strong>'.$form_entry->date_time.'</strong></div>';
							$output .= '<div class="user_ip">User IP: <strong>'.$form_entry->ip.'</strong></div>';
							if($form_entry->user_Id)
								$output .= '<div class="user_id">Username: <strong>'.$db_actions->get_username($form_entry->user_Id).'</strong></div>';
							
						 $output .= '</div>';
						 $output .= '<input type="hidden" name="action" value="do_form_entry_save">';
						 
						 $nonce_url = wp_create_nonce( 'nf_admin_do_form_entry_save' );
		 				 $output .= '<input name="_wpnonce" type="hidden" value="'.$nonce_url.'">';
						 
						 $output .= '<input type="hidden" name="form_entry_id" value="'.$form_entry_Id.'">';
						 $output .= '<table class="highlight" id="form_entry_table">';
							$output .= '<thead>';
								$output .= '<tr>';
									$output .= '<th>'.__('Field Name','nex-forms').'</th>';
									$output .= '<th>'.__('Field Value','nex-forms').'</th>';
								$output .= '</tr>';
							$output .= '</thead>';
							$output .= '<tbody class="form_entry_data_records">';
							
							$img_ext_array = array('jpg','jpeg','png','tiff','gif','psd');
							$file_ext_array = array('doc','docx','mpg','mpeg','mp3','mp4','odt','odp','ods','pdf','ppt','pptx','txt','xls','xlsx');
				
							foreach($form_data as $data)
								{
								
								$field_value = $data->field_value;
								if(is_string($field_value))
									{
									if(strstr($field_value,'||'))
										{
										
										$get_val = explode('||',$field_value);
										
										foreach($get_val as $setkey=>$setval)
											{
											$set_val .= ''.trim($setval).',';	
											}
										
										$field_value = $set_val;
										
										}
									}
									
								if($data->field_name!='' && $data->field_name!='math_result' && $data->field_name!='paypal_invoice'){
									$output .= '<tr>';
										$output .= '<td valign="top" class="result_head" style="vertical-align:top !important; width:200px;"><strong>';
											$output .= $nf_functions->unformat_records_name($data->field_name);
										$output .= '</strong></td>';
										$output .= '<td valign="top" style="vertical-align:top !important;">';
										
											if((is_array($field_value) || is_object($field_value)) )
												{
												
												if($data->field_name!='')
													{
													
													$i = 1;
														$output .= '<table width="100%" class="highlight" cellpadding="10" cellspacing="0" style="border-bottom:1px solid #ddd; border-left:1px solid #ddd; border-top:1px solid #ddd;">';
															
														foreach($field_value as $key=>$val)
															{
															
															if(is_array($val) || is_object($val))
																{
																
																if($i==1)
																	{
																	$output .= '<tr>';
																	foreach($val as $innerkey=>$innervalue)
																		{
																		if(!strstr($innerkey,'real_val__'))	
																			$output .= '<td style="border-bottom:1px solid #ddd;border-right:1px solid #ddd;"><strong>'.$nf_functions->unformat_records_name($innerkey).'</strong></td>';
																		}
																	$output .= '</tr>';
																	}
																$output .= '<tr>';
																foreach($val as $innerkey=>$innervalue)
																	{
																	if(array_key_exists('real_val__'.$innerkey.'',$val))
																			{
																			$realval = 'real_val__'.$innerkey;
																			$innervalue = $val->$realval;	
																			
																			}
																	if(!strstr($innerkey,'real_val__'))
																		{
																	
																		if($edit_entry)
																			{
																			$output .= '<td style="border-right:1px solid #ddd;border-bottom:1px solid #eee;">';
																			$output .= '<input name="'.$data->field_name.'['.$i.']['.$innerkey.']" id="'.$innerkey.'" type="text" value="'.rtrim($innervalue,', ').'">';
																			$output .= '</td>';
																			}
																		else
																			{
																			if(in_array($nf_functions->get_ext($innervalue),$img_ext_array))
																				$output .= '<td style="border-right:1px solid #ddd;border-bottom:1px solid #eee;"><img class="materialboxed" src="'.rtrim($innervalue,', ').'" /></td>';
																			else
																				$output .= '<td style="border-right:1px solid #ddd;border-bottom:1px solid #eee;">'.rtrim($innervalue,', ').'</td>';
																			}
																		}
																	}
																$output .= '</tr>';
																}
															else
																{
																if($edit_entry)
																	{
																	$output .= '<input name="'.$data->field_name.'[]" type="text" id="'.$val.'" value="'.rtrim($val,', ').'" />';
																	}
																else
																	{	
																	$output .= rtrim($val,', ').'<br />';
																	}
																}
															$i++;	
															}
													$output .= '</table>';	
													}
												
												}
											else
												{	
												if(strstr($field_value,',') && !strstr($field_value,'data:image'))
													{
													$is_array = explode(',',$field_value);
													foreach($is_array as $item)
														{
														if(in_array($nf_functions->get_ext($item),$img_ext_array))
															$output .= '<div class="col-xs-12" style="margin-bottom:15px;"><img class="materialboxed" width="100%" src="'.$item.'"></div>
	';													else if(in_array($nf_functions->get_ext(trim($item)),$file_ext_array))
															$output .= '<div class="col-xs-12" style="margin-bottom:15px;"><a class="file_ext_data" href="'.$item.'" target="_blank">'.$item.'</a></div>';
														else
															$output .= $item;
														}
													$output .= '<input type="hidden" name="'.$data->field_name.'" value="'.$field_value.'">';
													}
												else if(strstr($field_value,'data:image'))
													$output .= '<img src="'.$field_value.'"><input type="hidden" name="'.$data->field_name.'" value="'.$field_value.'">';
												else if(in_array($nf_functions->get_ext(trim($field_value)),$img_ext_array))
													$output .= '<div class="col-xs-6"><img class="materialboxed" width="100%" src="'.$field_value.'" style="margin-bottom:15px;"></div><input type="hidden" name="'.$data->field_name.'" value="'.$field_value.'">';
												else
													{
													if($edit_entry)
														{
														if(strlen($field_value)>50)
															{
															$output .= '<div class="input-field">
																		  <textarea class="materialize-textarea" name="'.$data->field_name.'" id="'.$data->field_name.'">'.$field_value.'</textarea>
																		</div>';
															}
														else
															{
															$output .= '<div class="input-field">
																		  <input name="'.$data->field_name.'" id="'.$data->field_name.'" type="text" value="'.$field_value.'">
																		</div>';
															}
														}
													else
														{
														if(in_array($nf_functions->get_ext(trim($field_value)),$file_ext_array))	
															$output .= '<a class="file_ext_data" href="'.$field_value.'" target="_blank"><span class="fa fa-file"></span> '.$field_value.'</a>'; 
														//else if(in_array($nf_functions->get_ext(trim($field_value)),$img_ext_array))	
														//	$output .= '<a class="file_ext_data" href="'.$field_value.'" target="_blank"><img class="materialboxed" width="100%" src="'.$field_value.'"></a>'; 
														else
															$output .= str_replace('\\','',$field_value);
														}
													
													}
												}
										$output .= '</td>';
										
									$output .= '</tr>';
									
									
									
									}
								}
								if($form_entry->paypal_payment_id)
									{
									$output .= '<thead style="border-top:1px solid #ddd;"><tr><th colspan="2">Payment Details</th></tr></thead>';
									$output .= '<tr>';									
										$output .= '<td valign="top" style="vertical-align:top !important; width:200px;"><strong>';
											$output .= 'Status';
										$output .= '</strong></td>';
										
										$output .= '<td valign="top" style="vertical-align:top !important;">';
											if($form_entry->payment_status=='payed')

												$output .= 'Payed';
											elseif($form_entry->payment_status=='pending')
												$output .= 'Pending';
											else
												$output .= 'Not Payed';
										$output .= '</td>';
									$output .= '</tr>';
									$output .= '<tr>';	
										$output .= '<td valign="top" style="vertical-align:top !important; width:200px;"><strong>';
											$output .= ($form_entry->payment_status=='payed') ? 'Recieved' : 'Unpaid';
										$output .= '</strong></td>';
										
										$output .= '<td valign="top" style="vertical-align:top !important;">';
											$output .= $form_entry->payment_ammount.' '.$form_entry->payment_currency;
										$output .= '</td>';
									$output .= '</tr>';	
									$output .= '<tr>';
										$output .= '<td valign="top" style="vertical-align:top !important; width:200px;"><strong>';
											$output .= 'ID';
										$output .= '</strong></td>';
									
										$output .= '<td valign="top" style="vertical-align:top !important;">';
											$output .= $form_entry->paypal_payment_id;
										$output .= '</td>';
									$output .= '</tr>';	
									$output .= '<tr>';
										$output .= '<td valign="top" style="vertical-align:top !important; width:200px;"><strong>';
											$output .= 'Token';
										$output .= '</strong></td>';
										
										$output .= '<td valign="top" style="vertical-align:top !important;">';
											$output .= $form_entry->paypal_payment_token;
										$output .= '</td>';									
									$output .= '</tr>';
									}
							$output .= '</tbody>';
						$output .= '</table>';
					$output .= '</div>';
				
					}
				else
					{
					foreach($_POST['selection'] as $key=>$val)
						{
						$get_form_entry = $wpdb->prepare('SELECT form_data,title FROM '.$wpdb->prefix.'wap_nex_forms_entries, '.$wpdb->prefix.'wap_nex_forms WHERE '.$wpdb->prefix.'wap_nex_forms_entries.nex_forms_Id = '.$wpdb->prefix.'wap_nex_forms.Id AND '.$wpdb->prefix.'wap_nex_forms_entries.Id=%d', filter_var($val,FILTER_SANITIZE_NUMBER_INT));
						$set_form_entry = $wpdb->get_row($get_form_entry);		
							if($set_form_entry)
								{
								$output .= '<div class="row batch_entry" id="'.$val.'">';
									$output .= '<div class="col-sm-12">';
										$output .= '<div class="form_title">';
											$output .= ''.$set_form_entry->title.'';
										$output .= '</div>';
										
										$output .= '<div class="form_data">';
											
											$form_data = json_decode($set_form_entry->form_data, 1);
											$set_data = '';
											foreach($form_data as $data)
												{
												$set_values = '';
												if(is_array($data['field_value']))
													{
													$set_data .= '<span class="entry_data_name">'.$nf_functions->unformat_records_name($data['field_name']).'</span> : ';
													
													foreach($data['field_value'] as $key=>$val)
														$set_values .= $val.',';
													
													$set_data .= rtrim($set_values,',');
													
													$set_data .= '<span class="entry_data_value"></span> | ';
													}
												else
													{
													if(!strstr($data['field_value'],'data:image'))
														$set_data .= '<span class="entry_data_name">'.$nf_functions->unformat_records_name($data['field_name']).'</span> : <span class="entry_data_value">'.$data['field_value'].'</span> | ';
													else
														$set_data .= '<span class="entry_data_name">'.$nf_functions->unformat_records_name($data['field_name']).'</span> : <span class="entry_data_value"><img src="'.$data['field_value'].'" width="50"/></span> | ';
													}
												}
											$set_data = rtrim($set_data,' | ');
											$output .= $set_data;
											
										$output .= '</div>';
										
									$output .= '</div>';
								$output .= '</div>';
								}
							
							//print_r( $set_form_entry); //->Id;
						}
					
						
					
					}
					
				}
			else
				{
				$output .= '<div class="alert alert-danger" style="margin:20px;">Please register this plugin to view entries. Go to global settings above and follow registration procedure.</div>';	
				}
			echo $output;
			
			die();	
		}
	
	public function load_pagination($table='',$form_Id='',$echo=false,$additional_params=array(), $search_params=array(), $search_term=''){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			$db_actions = new NEXForms_Database_Actions();
			
			if($_POST['form_Id'])
				$form_Id = sanitize_title($_POST['form_Id']);
			
			if($_POST['table'])
				$table = sanitize_title($_POST['table']);
			
			if($_POST['echo'])
				$echo = sanitize_text_field($_POST['echo']);
			
			if($_POST['search_params'])
				$search_params = sanitize_text_field($_POST['search_params']);
			
			if($_POST['additional_params'])
				$additional_params = sanitize_text_field($_POST['additional_params']);
			
			if($_POST['search_term'])
				$search_term = sanitize_text_field($_POST['search_term']);
			
			$total_records = $db_actions->get_total_records($table,$additional_params,$form_Id,$search_params, $search_term, $echo);
			
			$total_pages = ((is_float($total_records/10)) ? (floor($total_records/10))+1 : $total_records/10);
			
			$output .= '<span class="displaying-num"><span class="entry-count">'.$total_records.'</span> item'.(($total_records==1) ? '' : 's').'</span>';
			if($total_pages>1)
				{				
				$output .= '<span class="pagination-links">';
				$output .= '<a class="first-page iz-first-page btn waves-effect waves-light"><span class="fa fa-angle-double-left"></span></a>';
				$output .= '<a title="Go to the next page" class="iz-prev-page btn waves-effect waves-light prev-page"><span class="fa fa-angle-left"></span></a>&nbsp;';
				$output .= '<span class="paging-input"> ';
				$output .= '<span class="current-page">'.($_POST['page']+1).'</span> of <span class="total-pages">'.$total_pages.'</span>&nbsp;</span>';
				$output .= '<a title="Go to the next page" class="iz-next-page btn waves-effect waves-light next-page"><span class="fa fa-angle-right"></span></a>';
				$output .= '<a title="Go to the last page" class="iz-last-page btn waves-effect waves-light last-page"><span class="fa fa-angle-double-right"></span></a></span>';
				}
			if($echo)
				{
				echo $output;
				die();
				}
			else
				return $output;
		}
	
	public function get_total_records($table,$additional_params=array(),$nex_forms_id='', $search_params=array(),$search_term='',$echo=true){
			global $wpdb;
			
			$where_str = '';
			
			if(is_array($additional_params))
				{
				foreach($additional_params as $clause)
					{
					$like = '';
					$quote = '"';
					if($clause['operator'] == 'LIKE' || $clause['operator'] == 'NOT LIKE')
						$like = '%';
					
					if($clause['operator'] == 'IS' || $clause['operator'] == 'IS NOT')
						$quote = '';
					
					$where_str .= ' AND `'.$clause['column'].'` '.(($clause['operator']) ? $clause['operator'] : '=').'  '.$quote.''.$like.$clause['value'].$like.''.$quote.'';
					}
				}
			
			$count_search_params = count($search_params);
			if(is_array($search_params) && $search_term)
				{
				if($count_search_params>1)
					{
					$where_str .= ' AND (';
					$loop_count = 1;
					foreach($search_params as $column)
						{
						if($loop_count==1)
							$where_str .= '`'.$column.'` LIKE "%'.$search_term.'%" ';
						else
							$where_str .= ' OR `'.$column.'` LIKE "%'.$search_term.'%" ';
							
						$loop_count++;
						}
					$where_str .= ') ';
					}
				else
					{
					foreach($search_params as $column)
						{
						$where_str .= ' AND `'.$column.'` LIKE "%'.$search_term.'%" ';
						}
					}
				}
				
			if($nex_forms_id)
				$where_str .= ' AND nex_forms_Id='.$nex_forms_id;
			
			$set_alias = isset($_POST['plugin_alias']) ? sanitize_text_field($_POST['plugin_alias']) : '';
			$tree = '';
			$sql = 'SELECT count(*) FROM '.$wpdb->prefix . filter_var($table,FILTER_SANITIZE_STRING).' WHERE Id<>"" '. (($tree) ? ' AND parent_Id=0' : '').' '. ((filter_var($set_alias,FILTER_SANITIZE_STRING)) ? ' AND plugin="'.$set_alias.'"' : '').' '.$where_str;
			
			//echo $sql;
			
			$wpdb->show_errors(); 
			//$wpdb->print_error(); 
			return $wpdb->get_var($sql);
			
			if($echo)
				{
				echo $wpdb->get_var($sql);
				die();
				}
			else
				return $wpdb->get_var($sql);
			
		}
	
	
	public function save_mc_key() {
		if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
		
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		$api_key = sanitize_text_field($_POST['mc_api']);
		update_option('nex_forms_mailchimp_api_key',filter_var($api_key,FILTER_SANITIZE_STRING));
		
		die();
	}
	public function save_gr_key() {
		if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		$api_key = sanitize_text_field($_POST['gr_api']);
		update_option('nex_forms_get_response_api_key',filter_var($api_key,FILTER_SANITIZE_STRING));
		
		die();
	}
	
	public function save_email_config() {
		if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		
		$email_method = sanitize_text_field($_POST['email_method']);
		$smtp_host = sanitize_text_field($_POST['smtp_host']);
		$mail_port = sanitize_text_field($_POST['mail_port']);
		$email_smtp_secure = sanitize_text_field($_POST['email_smtp_secure']);
		$smtp_auth = sanitize_text_field($_POST['smtp_auth']);
		$set_smtp_user = sanitize_text_field($_POST['set_smtp_user']);
		$set_smtp_pass = sanitize_text_field($_POST['set_smtp_pass']);
		$email_content = sanitize_text_field($_POST['email_content']);
		
		update_option('nex-forms-email-config',array
			(
			'email_method'			=> filter_var($email_method,FILTER_SANITIZE_STRING),
			'smtp_host' 			=> filter_var($smtp_host,FILTER_SANITIZE_STRING),
			'mail_port' 			=> filter_var($mail_port,FILTER_SANITIZE_NUMBER_INT),
			'email_smtp_secure' 	=> filter_var($email_smtp_secure,FILTER_SANITIZE_STRING),
			'smtp_auth' 			=> filter_var($smtp_auth,FILTER_SANITIZE_NUMBER_INT),
			'set_smtp_user' 		=> filter_var($set_smtp_user,FILTER_SANITIZE_STRING),
			'set_smtp_pass' 		=> filter_var($set_smtp_pass,FILTER_SANITIZE_STRING),
			'email_content' 		=> filter_var($email_content,FILTER_SANITIZE_STRING)
			)
		
		);
		die();
	}
	
	public function save_script_config() {
		if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
		
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		if(!array_key_exists('inc-jquery',$_POST))
			$_POST['inc-jquery'] = '2';
		if(!array_key_exists('inc-jquery-ui-core',$_POST))
			$_POST['inc-jquery-ui-core'] = '2';
		if(!array_key_exists('inc-bootstrap',$_POST))
			$_POST['inc-bootstrap'] = '2';
		if(!array_key_exists('inc-jquery-ui-autocomplete',$_POST))
			$_POST['inc-jquery-ui-autocomplete'] = '2';
		if(!array_key_exists('inc-jquery-ui-slider',$_POST))
			$_POST['inc-jquery-ui-slider'] = '2';
		if(!array_key_exists('inc-jquery-form',$_POST))
			$_POST['inc-jquery-form'] = '2';
		if(!array_key_exists('inc-onload',$_POST))
			$_POST['inc-onload'] = '2';
		if(!array_key_exists('enable-print-scripts',$_POST))
			$_POST['enable-print-scripts'] = '2';
		if(!array_key_exists('inc-moment',$_POST))
			$_POST['inc-moment'] = '2';
		if(!array_key_exists('inc-locals',$_POST))
			$_POST['inc-locals'] = '2';
		if(!array_key_exists('inc-datetime',$_POST))
			$_POST['inc-datetime'] = '2';
		if(!array_key_exists('inc-math',$_POST))
			$_POST['inc-math'] = '2';
		if(!array_key_exists('inc-colorpick',$_POST))
			$_POST['inc-colorpick'] = '2';
		if(!array_key_exists('inc-wow',$_POST))
			$_POST['inc-wow'] = '2';
		if(!array_key_exists('inc-raty',$_POST))
			$_POST['inc-raty'] = '2';
		if(!array_key_exists('inc-sig',$_POST))
			$_POST['inc-sig'] = '2';
		
		
		
		$inc_jquery = sanitize_text_field($_POST['inc-jquery']);
		$inc_jquery_ui_core = sanitize_text_field($_POST['inc-jquery-ui-core']);
		$inc_jquery_ui_autocomplete = sanitize_text_field($_POST['inc-jquery-ui-autocomplete']);
		$inc_jquery_ui_slider = sanitize_text_field($_POST['inc-jquery-ui-slider']);
		$inc_bootstrap = sanitize_text_field($_POST['inc-bootstrap']);
		$inc_jquery_form = sanitize_text_field($_POST['inc-jquery-form']);
		$inc_onload = sanitize_text_field($_POST['inc-onload']);
		$enable_print_scripts = sanitize_text_field($_POST['enable-print-scripts']);
		
		$inc_moment = sanitize_text_field($_POST['inc-moment']);
		$inc_locals = sanitize_text_field($_POST['inc-locals']);
		$inc_datetime = sanitize_text_field($_POST['inc-datetime']);
		$inc_math = sanitize_text_field($_POST['inc-math']);
		$inc_colorpick = sanitize_text_field($_POST['inc-colorpick']);
		$inc_wow = sanitize_text_field($_POST['inc-wow']);
		$inc_raty = sanitize_text_field($_POST['inc-raty']);
		$inc_sig = sanitize_text_field($_POST['inc-sig']);
		
		
		update_option('nex-forms-script-config',array
			(
			'inc-jquery' 					=> filter_var($inc_jquery,FILTER_SANITIZE_NUMBER_INT),
			'inc-jquery-ui-core' 			=> filter_var($inc_jquery_ui_core,FILTER_SANITIZE_NUMBER_INT),
			'inc-jquery-ui-autocomplete' 	=> filter_var($inc_jquery_ui_autocomplete,FILTER_SANITIZE_NUMBER_INT),
			'inc-jquery-ui-slider' 			=> filter_var($inc_jquery_ui_slider,FILTER_SANITIZE_NUMBER_INT),
			'inc-jquery-form' 				=> filter_var($inc_jquery_form,FILTER_SANITIZE_NUMBER_INT),
			'inc-bootstrap' 				=> filter_var($inc_bootstrap,FILTER_SANITIZE_NUMBER_INT),
			'inc-onload' 					=> filter_var($inc_onload,FILTER_SANITIZE_NUMBER_INT),
			'enable-print-scripts' 			=> filter_var($enable_print_scripts,FILTER_SANITIZE_NUMBER_INT),
			'inc-moment' 					=> filter_var($inc_moment,FILTER_SANITIZE_NUMBER_INT),
			'inc-locals' 					=> filter_var($inc_locals,FILTER_SANITIZE_NUMBER_INT),
			'inc-datetime' 					=> filter_var($inc_datetime,FILTER_SANITIZE_NUMBER_INT),
			'inc-math' 						=> filter_var($inc_math,FILTER_SANITIZE_NUMBER_INT),
			'inc-colorpick' 				=> filter_var($inc_colorpick,FILTER_SANITIZE_NUMBER_INT),
			'inc-wow' 						=> filter_var($inc_wow,FILTER_SANITIZE_NUMBER_INT),
			'inc-raty' 						=> filter_var($inc_raty,FILTER_SANITIZE_NUMBER_INT),
			'inc-sig' 						=> filter_var($inc_sig,FILTER_SANITIZE_NUMBER_INT)
			)
		);
		die();
	}
	
	
	
	public function save_style_config() {
		
		if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
		
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		if(!array_key_exists('incstyle-jquery',$_POST))
			$_POST['incstyle-jquery'] = '0';
		if(!array_key_exists('incstyle-font-awesome',$_POST))
			$_POST['incstyle-font-awesome'] = '0';
		if(!array_key_exists('incstyle-font-awesome-v4-shims',$_POST))
			$_POST['incstyle-font-awesome-v4-shims'] = '0';
		if(!array_key_exists('incstyle-bootstrap',$_POST))
			$_POST['incstyle-bootstrap'] = '0';
		if(!array_key_exists('incstyle-jquery',$_POST))
			$_POST['incstyle-custom'] = '0';
		if(!array_key_exists('incstyle-animations',$_POST))
			$_POST['incstyle-animations'] = '0';
		if(!array_key_exists('enable-print-styles',$_POST))
			$_POST['enable-print-styles'] = '0';
		
		
		$incstyle_jquery = sanitize_text_field($_POST['incstyle-jquery']);
		$incstyle_font_awesome = sanitize_text_field($_POST['incstyle-font-awesome']);
		$incstyle_font_awesome_v4 = sanitize_text_field($_POST['incstyle-font-awesome-v4-shims']);
		$incstyle_bootstrap = sanitize_text_field($_POST['incstyle-bootstrap']);
		$incstyle_custom = sanitize_text_field($_POST['incstyle-custom']);
		$enable_print_styles = sanitize_text_field($_POST['enable-print-styles']);
		$incstyle_animations = sanitize_text_field($_POST['incstyle-animations']);
		
		update_option('nex-forms-style-config',array
			(
			'incstyle-jquery' 					=> filter_var($incstyle_jquery,FILTER_SANITIZE_NUMBER_INT),
			'incstyle-font-awesome' 			=> filter_var($incstyle_font_awesome,FILTER_SANITIZE_NUMBER_INT),
			'incstyle-font-awesome-v4-shims' 	=> filter_var($incstyle_font_awesome_v4,FILTER_SANITIZE_NUMBER_INT),
			'incstyle-bootstrap' 				=> filter_var($incstyle_bootstrap,FILTER_SANITIZE_NUMBER_INT),
			'incstyle-custom' 					=> filter_var($incstyle_custom,FILTER_SANITIZE_NUMBER_INT),
			'incstyle-animations' 				=> filter_var($incstyle_animations,FILTER_SANITIZE_NUMBER_INT),
			'enable-print-styles' 				=> filter_var($enable_print_styles,FILTER_SANITIZE_NUMBER_INT)
			)
		);
		die();
	}
	public function save_other_config() {
		
		if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
		
		if(!current_user_can( NF_USER_LEVEL ))	
			wp_die();
				
		if(!get_option('nex-forms-other-config'))
		{
		update_option('nex-forms-other-config',array(
				'enable-tinymce'=>'1',
				'enable-widget'=>'1',
				'set-wp-user-level'=>'administrator',	
			));
		}
		
		if(!get_user_option('nex-forms-user-config'))
		{
		update_user_option(get_current_user_id(),'nex-forms-user-config',array(
				'enable-color-adapt'=>'1',
			));
		}
		
		if(!array_key_exists('enable-tinymce',$_POST))
			$_POST['enable-tinymce'] = '0';
		if(!array_key_exists('enable-widget',$_POST))
			$_POST['enable-widget'] = '0';
		if(!array_key_exists('enable-color-adapt',$_POST))
			$_POST['enable-color-adapt'] = '0';
		if(!array_key_exists('set-wp-user-level',$_POST))
			$_POST['set-wp-user-level'] = 'administrator';
		
		
		$enable_tinymce = sanitize_text_field($_POST['enable-tinymce']);
		$enable_widget = sanitize_text_field($_POST['enable-widget']);
		$enable_color_adapt = sanitize_text_field($_POST['enable-color-adapt']);
		$set_wp_user_level = sanitize_text_field($_POST['set-wp-user-level']);
		
		update_option('nex-forms-other-config',array
			(
			'enable-tinymce' 			=> filter_var($enable_tinymce,FILTER_SANITIZE_NUMBER_INT),
			'enable-widget' 			=> filter_var($enable_widget,FILTER_SANITIZE_NUMBER_INT),
			'enable-color-adapt' 		=> filter_var($enable_color_adapt,FILTER_SANITIZE_NUMBER_INT),
			'set-wp-user-level' 		=> filter_var($set_wp_user_level,FILTER_SANITIZE_STRING)
			)
		);
		
		update_user_option(get_current_user_id(),'nex-forms-user-config',array(
				'enable-color-adapt' 		=> filter_var($enable_color_adapt,FILTER_SANITIZE_NUMBER_INT),
			));
		
		die();
	}
	
	function deactivate_license(){
		
		
		$theme = wp_get_theme();
		if($theme->Name!='NEX-Forms Demo')
			{
			global $wpdb;
			
			$delete = $wpdb->query('DELETE FROM '.$wpdb->prefix.'options WHERE option_name LIKE "1983017%"');
			
			$api_params = array( 'client_deactivate_license' => 1,'key'=>get_option('7103891'));
			delete_option('7103891');
		
			$response = wp_remote_post( 'https://basixonline.net/activate-license-new-api-v3', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );
			}
			
	}

	
	public function load_template() {
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		global $wpdb;
		

		$nf_functions = new NEXForms_Functions();
		$template  = $_POST['template'];
		$template_dir  = $_POST['template_dir'];
		
		$template = str_replace(' ','%20',$template);
		$template_dir = str_replace(' ','%20',$template_dir);
		
		$url = plugins_url( '/includes/templates/'.$template_dir.'/'.$template.'.txt',dirname(dirname(__FILE__)));

		$response = wp_remote_get( $url );
		$file_content     = wp_remote_retrieve_body( $response );
		
		$get_form_data = json_decode($file_content,true);
		
		$import_record = $wpdb->insert($wpdb->prefix.'wap_nex_forms',$get_form_data);

		$insert_id = $wpdb->insert_id;	
		echo $insert_id;
		
		$theme = wp_get_theme();
			if($theme->Name=='NEX-Forms Demo')
				{
				$post_id = wp_insert_post(
					array(
						'comment_status'	=>	'closed',
						'ping_status'		=>	'closed',
						'post_author'		=>	1,
						'post_name'			=>	'user-test-form-'.$insert_id,
						'post_title'		=>	'User Test Form '.$insert_id,
						'post_status'		=>	'publish',
						'post_type'			=>	'page',
						'post_content'		=>	'[NEXForms id="'.$insert_id.'"]',
						'post_parent'		=>  '11',
					)
				);
			}
						
		die();
	}
	
	public function do_form_import() {
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		global $wpdb;
		
		$nf_functions = new NEXForms_Functions();

		if($_POST['import_type']=='manual')
			{
			$get_form_data = str_replace('\\"','"',$_POST['form_content']);
			$get_form_data = str_replace('\\\\','\\',$get_form_data);
			
			$get_form_data = str_replace('\n','',$get_form_data);
			$get_form_data = str_replace('\t','',$get_form_data);
				
			$pattern = '/font-family:\s*("([^"]+)");/'; // Match font-family: "something";
			$replacement = 'font-family: $2;'; // Replace with font-family: something;

			$outputString = preg_replace($pattern, $replacement, $get_form_data);
			
			$get_data= json_decode($outputString,true);
			
			$import_record = $wpdb->insert($wpdb->prefix.'wap_nex_forms',$get_data);
			$insert_id = $wpdb->insert_id;	
			echo $insert_id;	
			}
		else
			{
			if($_FILES['form_html']['type'] == 'text/plain')
				{	
				$import_form = file_get_contents($_FILES['form_html']['tmp_name']);
				
				$import_form = str_replace('\n','',$import_form);
				$import_form = str_replace('\t','',$import_form);
				
				$pattern = '/font-family:\s*("([^"]+)");/'; // Match font-family: "something";
				$replacement = 'font-family: $2;'; // Replace with font-family: something;
				$outputString = preg_replace($pattern, $replacement, $import_form);
				
				$get_form_data = json_decode($outputString, true);
				
				$import_record = $wpdb->insert($wpdb->prefix.'wap_nex_forms',$get_form_data);
				$insert_id = $wpdb->insert_id;	
				echo $insert_id;
				}		
			}
		wp_die();
	}
	
	
	
	
		
	
	public function nf_send_test_email(){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			$email_config = get_option('nex-forms-email-config');
			
			$email_address = sanitize_email($_POST['email_address']);
			
			$preferences = get_option('nex-forms-preferences');
			$get_pref_email = $preferences['email_preferences']['pref_email_from_address'];
			
			$from_address 	= ($get_pref_email) ? $get_pref_email : get_option('admin_email');
			$from_name 		= 'You';
			$subject 		= 'NEX-Forms Test Mail';
			$plain_body		= 'This is a test message in PLAIN TEXT. If you received this your email settings are working correctly :)
			
You are using '.$email_config['email_method'].' as your emailing method';
			$html_body		= 'This is a test message in <strong>HTML</strong>. If you received this your email settings are working correctly :)<br /><br />You are using <strong>'.$email_config['email_method'].'</strong> as your emailing method';
			
			if($email_config['email_method']=='api')
				{
					$api_params = array( 
						'from_address' => $from_address,
						'from_name' => $from_name,
						'subject' => $subject,
						'mail_to' => $from_address,
						'admin_message' => ($email_config['email_content']=='pt') ? $plain_body : $html_body,
						'user_email' => 0,
						'is_html'=> ($email_config['email_content']=='pt') ? 0 : 1
					);
					
					//$request = new WP_Http();
					//$response = $request->post( 'https://basixonline.net/activate-license-new-api-v3', array( 'timeout'   => 30, 'body' => $api_params ) );
					$response = wp_remote_post( 'https://basixonline.net/mail-api/', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );
					//echo $response['body'];
				}
			else if($email_config['email_method']=='smtp' || $email_config['email_method']=='php_mailer')
				{
				date_default_timezone_set('Etc/UTC');
				include_once(ABSPATH . WPINC . '/class-phpmailer.php'); 
				//Create a new PHPMailer instance
				$mail = new PHPMailer;
			
				$mail->CharSet = "UTF-8";
				
				if($email_config['email_content']=='pt')
					$mail->IsHTML(false);
				 
				//Tell PHPMailer to use SMTP
				if($email_config['email_method']=='smtp')
					{
					$mail->isSMTP();
					
					if($email_config['email_smtp_secure']!='0')
						$mail->SMTPSecure  = $email_config['email_smtp_secure']; //Secure conection
					
					if($email_config['smtp_auth']=='1')
						{
						$mail->SMTPAuth = true;
						//Username to use for SMTP authentication
						$mail->Username = $email_config['set_smtp_user'];
						//Password to use for SMTP authentication
						$mail->Password = $email_config['set_smtp_pass'];
						}
					else
						{
						$mail->SMTPAuth = false;
						}
					
					
					
					
					//encoding
					
					//Whether to use SMTP authentication
					
					}
				//}
				//Set who the message is to be sent from
				//Set an alternative reply-to address
			//Set the hostname of the mail server
					$mail->Host = $email_config['smtp_host'];
					//Set the SMTP port number - likely to be 25, 465 or 587
					$mail->Port = ($email_config['email_port']) ? $email_config['email_port'] : 587;
					
				$mail->setFrom($from_address, $from_name);
				$mail->addCC($from_address, $from_name);
				//Set the subject line
				$mail->Subject = $subject;
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				if($email_config['email_content']=='html')	
					$mail->msgHTML($html_body);
				else
					$mail->Body = $plain_body;
				if (!$mail->send()) {
				    echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
				   echo "Message sent!";
					//echo print_r($mail);
				}
			}
		
/**************************************************/
/** NORMAL PHP ************************************/
/**************************************************/
	else if($email_config['email_method']=='php')
		{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-Type: '.(($email_config['email_content']=='html') ? 'text/html' : 'text/plain').'; charset=UTF-8\n\n'. "\r\n";
		$headers .= 'From: '.$from_name.' <'.$from_address.'>' . "\r\n";
		
		if($email_config['email_content']=='html')	
			$set_body = $html_body;
		else
			$set_body = $plain_body;
		
		$email_address = sanitize_email($_POST['email_address']);
		
		mail(filter_var($email_address,FILTER_SANITIZE_EMAIL),$subject,$set_body,$headers);
		}

/**************************************************/
/** WORDPRESS MAIL ********************************/
/**************************************************/	
	else if($email_config['email_method']=='wp_mailer')
		{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-Type: '.(($email_config['email_content']=='html') ? 'text/html' : 'text/plain').'; charset=UTF-8\n\n'. "\r\n";
		$headers .= 'From: '.$from_name.' <'.$from_address.'>' . "\r\n";
		
		if($email_config['email_content']=='html')	
			$set_body = $html_body;
		else
			$set_body = $plain_body;
		$email_address = sanitize_email($_POST['email_address']);
		wp_mail(filter_var($email_address,FILTER_SANITIZE_EMAIL),$subject,$set_body,$headers);				
		}
					
	die();
	}
	
	}
}



class NEXForms_widget extends WP_Widget{
	public $name = 'NEX-Forms';
	public $widget_desc = 'Add NEX-Forms to your sidebars.';
	
	public $control_options = array('title' => '','form_id' => '', 'make_sticky'=>'no', 'paddel_text'=>'Contact Us', 'paddel_color'=>'btn-primary', 'position'=>'right', 'open_trigger'=>'normal','type'=>'button' , 'text'=>'Open Form', 'button_color'=>'btn-primary');
	function __construct(){
		$widget_options = array('classname' => __CLASS__,'description' => $this->widget_desc);
		parent::__construct( __CLASS__, $this->name,$widget_options , $this->control_options);
	}
	function widget($args, $instance){
		echo '<div class="widget">';
		NEXForms_ui_output(
			array(
				'id'=>$instance['form_id'],
				'make_sticky'=>$instance['make_sticky'],
				'paddel_text'=>$instance['paddel_text'],
				'paddel_color'=>$instance['paddel_color'],
				'position'=>$instance['position'],
				'open_trigger'=>$instance['open_trigger'],
				'type'=>$instance['type'],
				'text'=>$instance['text'],
				'button_color'=>$instance['button_color']
				
				),true,'');
		echo '</div>';
	}
	public function form( $instance ){
		
		$db_action = new NEXForms_Database_Actions();
		
		$placeholders = array();
		foreach ( $this->control_options as $key => $val )
			{
			$placeholders[ $key .'.id' ] = $this->get_field_id( $key);
			$placeholders[ $key .'.name' ] = $this->get_field_name($key );
			if ( isset($instance[ $key ] ) )
				$placeholders[ $key .'.value' ] = esc_attr( $instance[$key] );
			else
				$placeholders[ $key .'.value' ] = $this->control_options[ $key ];
			}
		global $wpdb;
		$get_forms = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE is_form=1 ORDER BY Id DESC');
		$current_form = NEXForms_widget_controls::parse('[+form_id.value+]', $placeholders);
		
		$tpl  = '<input id="[+title.id+]" name="[+title.name+]" value="'.$db_action->get_title(NEXForms_widget_controls::parse('[+form_id.value+]', $placeholders),'wap_nex_forms').'" class="widefat" style="width:96%;display:none;" />';
		
		if($get_forms)
			{
			$tpl  .= '<h3>Select Form</h3>';
			$tpl .= '<select id="[+form_id.id+]" name="[+form_id.name+] " style="width:100%;">';
				$tpl .= '<option value="0">-- Select form --</option>';
				foreach($get_forms as $form)
					$tpl .= '<option value="'.$form->Id.'" '.(($form->Id==$current_form) ? 'selected="selected"' : '' ).'>'.$form->title.'</option>';
			$tpl .= '</select></p>';
			}
		else
			$tpl .=  '<p>No forms have been created yet.<br /><br /><a href="'.get_option('siteurl').'/wp-admin/admin.php?page=WA-x_forms-main">Click here</a> or click on "X Forms" on the left-hand menu where you will be able to create a form that would be avialable here to select as a widget.</p>';
		
		
		$tpl  .= '<hr />';
		$tpl  .= '<h3>Sticky Mode Options</h3>';
		$tpl  .= '<p><label for="[+make_sticky.id+]"><strong>Make Sticky?</strong></label><br /><small><em>Choose <strong>no</strong> to display in sidebar.<br /> Choose <strong>yes</strong> to display form in sticky mode and select prefered settings.</em></small><br /><input id="1[+make_sticky.id+]" name="[+make_sticky.name+]" value="no" '.((NEXForms_widget_controls::parse('[+make_sticky.value+]', $placeholders))=='no' ? 'checked="checked"' : '').' type="radio" class="widefat"  /> <label for="1[+make_sticky.id+]">No</label><br /><input id="2[+make_sticky.id+]" name="[+make_sticky.name+]" value="yes" '.((NEXForms_widget_controls::parse('[+make_sticky.value+]', $placeholders))=='yes' ? 'checked="checked"' : '').' type="radio" class="widefat"  /> <label for="2[+make_sticky.id+]">Yes</label></p>';
		
		$tpl  .= '<p><label for="[+paddel_text.id+]"><strong>Paddel Text </strong></label><input type="text" id="[+paddel_text.id+]" name="[+paddel_text.name+]" value="'.NEXForms_widget_controls::parse('[+paddel_text.value+]', $placeholders).'" class="widefat" /><p>';
		
		$tpl  .= '<p><label for="[+paddel_color.id+]"><strong>Paddel Color</strong></label><br />';
		/*
		.btn-red { background: #f44336; }
.btn-pink { background: #e91e63; }
.btn-purple { background: #9c27b0; }
.btn-deep-purple { background: #673ab7; }
.btn-indigo { background: #3f51b5; }
.btn-blue { background: #2979FF; }
.btn-light-blue { background: #40C4FF; }
.btn-cyan { background: #00bcd4; }
.btn-teal { background: #009688; }
.btn-green { background: #4caf50; }
.btn-light-green { background: #8bc34a; }
.btn-lime { background: #cddc39; }
.btn-yellow { background: #ffeb3b; }
.btn-amber { background: #ffc107; }
.btn-orange { background: #ff9800; }
.btn-brown { background: #795548; }
.btn-gray { background: #9e9e9e; }
.btn-blue-gray { background: #607d8b; }
		*/
		$tpl  .= '<p style="clear:both;">Material Colors</p><label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #f44336; border-radius:0px; border:1px solid #f44336; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-red' ? 'checked="checked"' : '').' value="btn-red"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #e91e63; border-radius:0px; border:1px solid #e91e63; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-pink' ? 'checked="checked"' : '').' value="btn-pink"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #9c27b0; border-radius:0px; border:1px solid #9c27b0; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-purple' ? 'checked="checked"' : '').' value="btn-purple"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #673ab7; border-radius:0px; border:1px solid #673ab7; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-deep-purple' ? 'checked="checked"' : '').' value="btn-deep-purple"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #3f51b5; border-radius:0px; border:1px solid #3f51b5; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-indigo' ? 'checked="checked"' : '').' value="btn-indigo"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #2979FF; border-radius:0px; border:1px solid #2979FF; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-blue' ? 'checked="checked"' : '').' value="btn-blue"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #40C4FF; border-radius:0px; border:1px solid #40C4FF; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-light-blue' ? 'checked="checked"' : '').' value="btn-light-blue"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #00bcd4; border-radius:0px; border:1px solid #00bcd4; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-cyan' ? 'checked="checked"' : '').' value="btn-cyan"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #009688; border-radius:0px; border:1px solid #009688; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-teal' ? 'checked="checked"' : '').' value="btn-teal"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #4caf50; border-radius:0px; border:1px solid #4caf50; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-green' ? 'checked="checked"' : '').' value="btn-green"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #8bc34a; border-radius:0px; border:1px solid #8bc34a; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-light-green' ? 'checked="checked"' : '').' value="btn-light-green"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #cddc39; border-radius:0px; border:1px solid #cddc39; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-lime' ? 'checked="checked"' : '').' value="btn-lime"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #ffeb3b; border-radius:0px; border:1px solid #ffeb3b; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-yellow' ? 'checked="checked"' : '').' value="btn-yellow"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #ffc107; border-radius:0px; border:1px solid #ffc107; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-amber' ? 'checked="checked"' : '').' value="btn-amber"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #ff9800; border-radius:0px; border:1px solid #ff9800; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-orange' ? 'checked="checked"' : '').' value="btn-orange"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #795548; border-radius:0px; border:1px solid #795548; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-brown' ? 'checked="checked"' : '').' value="btn-brown"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #9e9e9e; border-radius:0px; border:1px solid #9e9e9e; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-gray' ? 'checked="checked"' : '').' value="btn-gray"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #607d8b; border-radius:0px; border:1px solid #607d8b; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-blue-gray' ? 'checked="checked"' : '').' value="btn-blue-gray"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<p style="clear:both;">Bootstrap Colors</p>';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #428bca; border-radius:0px; border:1px solid #357ebd; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-primary' ? 'checked="checked"' : '').' value="btn-primary"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #5bc0de; border-radius:0px; border:1px solid #46b8da; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-info' ? 'checked="checked"' : '').' value="btn-info"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #5cb85c; border-radius:0px; border:1px solid #4cae4c; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-success' ? 'checked="checked"' : '').' value="btn-success"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #f0ad4e; border-radius:0px; border:1px solid #eea236; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-warning' ? 'checked="checked"' : '').' value="btn-warning"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #d9534f; border-radius:0px; border:1px solid #d43f3a; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-danger' ? 'checked="checked"' : '').' value="btn-danger"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #ffffff; border-radius:0px; border:1px solid #cccccc; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+paddel_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-default' ? 'checked="checked"' : '').' value="btn-default"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> </p><br /><br />&nbsp;';

		$tpl  .= '<p style="clear:both;"><label for="[+position.id+]"><strong>Position</strong></label><br />';
		$tpl  .= '<input id="1[+position.id+]" name="[+position.name+]" '.((NEXForms_widget_controls::parse('[+position.value+]', $placeholders))=='top' ? 'checked="checked"' : '').' value="top"  type="radio" class="widefat"  /> <label for="1[+position.id+]">Top</label><br />';
		$tpl  .= '<input id="2[+position.id+]" name="[+position.name+]" '.((NEXForms_widget_controls::parse('[+position.value+]', $placeholders))=='right' ? 'checked="checked"' : '').' value="right"  type="radio" class="widefat"  /> <label for="2[+position.id+]">Right</label><br />';
		$tpl  .= '<input id="3[+position.id+]" name="[+position.name+]" '.((NEXForms_widget_controls::parse('[+position.value+]', $placeholders))=='bottom' ? 'checked="checked"' : '').' value="bottom"  type="radio" class="widefat"  /> <label for="3[+position.id+]">Bottom</label><br />';
		$tpl  .= '<input id="4[+position.id+]" name="[+position.name+]" '.((NEXForms_widget_controls::parse('[+position.value+]', $placeholders))=='left' ? 'checked="checked"' : '').' value="left"  type="radio" class="widefat"  /> <label for="4[+position.id+]">Left</label></p>';
		
		
		
		$tpl  .= '<hr />';
		$tpl  .= '<h3>Popup Form Options</h3>';
		$tpl  .= '<p><label for="[+open_trigger.id+]"><strong>Popup Form?</strong></label><br /><input id="1[+open_trigger.id+]" name="[+open_trigger.name+]" value="normal" '.((NEXForms_widget_controls::parse('[+open_trigger.value+]', $placeholders))=='normal' ? 'checked="checked"' : '').' type="radio" class="widefat"  /> <label for="1[+open_trigger.id+]">No</label><br /><input id="2[+open_trigger.id+]" name="[+open_trigger.name+]" value="popup" '.((NEXForms_widget_controls::parse('[+open_trigger.value+]', $placeholders))=='popup' ? 'checked="checked"' : '').' type="radio" class="widefat"  /> <label for="2[+open_trigger.id+]">Yes</label></p>';
		
		$tpl  .= '<p><label for="[+type.id+]"><strong>Popover Trigge</strong>r</label><br /><input id="1[+type.id+]" name="[+type.name+]" value="button" '.((NEXForms_widget_controls::parse('[+type.value+]', $placeholders))=='button' ? 'checked="checked"' : '').' type="radio" class="widefat"  /> <label for="1[+type.id+]">Button</label><br /><input id="2[+type.id+]" name="[+type.name+]" value="link" '.((NEXForms_widget_controls::parse('[+type.value+]', $placeholders))=='link' ? 'checked="checked"' : '').' type="radio" class="widefat"  /> <label for="2[+type.id+]">Link</label></p>';
		
		$tpl  .= '<p><label for="[+button_color.id+]">Button Color</label><br />';
		$tpl  .= '<p style="clear:both;">Materail Colors</p><label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #f44336; border-radius:0px; border:1px solid #f44336; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-red' ? 'checked="checked"' : '').' value="btn-red"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #e91e63; border-radius:0px; border:1px solid #e91e63; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-pink' ? 'checked="checked"' : '').' value="btn-pink"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #9c27b0; border-radius:0px; border:1px solid #9c27b0; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-purple' ? 'checked="checked"' : '').' value="btn-purple"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #673ab7; border-radius:0px; border:1px solid #673ab7; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-deep-purple' ? 'checked="checked"' : '').' value="btn-deep-purple"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #3f51b5; border-radius:0px; border:1px solid #3f51b5; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-indigo' ? 'checked="checked"' : '').' value="btn-indigo"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #2979FF; border-radius:0px; border:1px solid #2979FF; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-blue' ? 'checked="checked"' : '').' value="btn-blue"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #40C4FF; border-radius:0px; border:1px solid #40C4FF; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-light-blue' ? 'checked="checked"' : '').' value="btn-light-blue"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #00bcd4; border-radius:0px; border:1px solid #00bcd4; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-cyan' ? 'checked="checked"' : '').' value="btn-cyan"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #009688; border-radius:0px; border:1px solid #009688; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-teal' ? 'checked="checked"' : '').' value="btn-teal"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #4caf50; border-radius:0px; border:1px solid #4caf50; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-green' ? 'checked="checked"' : '').' value="btn-green"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #8bc34a; border-radius:0px; border:1px solid #8bc34a; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-light-green' ? 'checked="checked"' : '').' value="btn-light-green"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #cddc39; border-radius:0px; border:1px solid #cddc39; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-lime' ? 'checked="checked"' : '').' value="btn-lime"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #ffeb3b; border-radius:0px; border:1px solid #ffeb3b; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-yellow' ? 'checked="checked"' : '').' value="btn-yellow"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #ffc107; border-radius:0px; border:1px solid #ffc107; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-amber' ? 'checked="checked"' : '').' value="btn-amber"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #ff9800; border-radius:0px; border:1px solid #ff9800; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-orange' ? 'checked="checked"' : '').' value="btn-orange"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #795548; border-radius:0px; border:1px solid #795548; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-brown' ? 'checked="checked"' : '').' value="btn-brown"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #9e9e9e; border-radius:0px; border:1px solid #9e9e9e; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-gray' ? 'checked="checked"' : '').' value="btn-gray"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #607d8b; border-radius:0px; border:1px solid #607d8b; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+paddel_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+paddel_color.value+]', $placeholders))=='btn-blue-gray' ? 'checked="checked"' : '').' value="btn-blue-gray"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<p style="clear:both;">Bootstrap Colors</p>';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #428bca; border-radius:0px; border:1px solid #357ebd; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+button_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+button_color.value+]', $placeholders))=='btn-primary' ? 'checked="checked"' : '').' value="btn-primary"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #5bc0de; border-radius:0px; border:1px solid #46b8da; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+button_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+button_color.value+]', $placeholders))=='btn-info' ? 'checked="checked"' : '').' value="btn-info"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #5cb85c; border-radius:0px; border:1px solid #4cae4c; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+button_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+button_color.value+]', $placeholders))=='btn-success' ? 'checked="checked"' : '').' value="btn-success"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #f0ad4e; border-radius:0px; border:1px solid #eea236; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+button_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+button_color.value+]', $placeholders))=='btn-warning' ? 'checked="checked"' : '').' value="btn-warning"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #d9534f; border-radius:0px; border:1px solid #d43f3a; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+button_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+button_color.value+]', $placeholders))=='btn-danger' ? 'checked="checked"' : '').' value="btn-danger"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> ';
		$tpl  .= '<label style="margin-right: 5px; margin-bottom: 5px;background: none repeat scroll 0 0 #ffffff; border-radius:0px; border:1px solid #cccccc; display: block;float: left; height: 23px; width: 30px;">&nbsp;&nbsp;<input id="[+button_color.id+]" name="[+button_color.name+]" '.((NEXForms_widget_controls::parse('[+button_color.value+]', $placeholders))=='btn-default' ? 'checked="checked"' : '').' value="btn-default"  type="radio" class="widefat"  />&nbsp;&nbsp;</label> </p>&nbsp;';
		
		$tpl  .= '<p style="clear:both;"><label for="[+text.id+]"><strong>Button/link Text </strong></label><input type="text" id="[+text.id+]" name="[+text.name+]" value="'.NEXForms_widget_controls::parse('[+text.value+]', $placeholders).'" class="widefat" /><p>';

		print NEXForms_widget_controls::parse($tpl, $placeholders);
	}
	static function register_this_widget(){
		register_widget(__CLASS__);
	}
}
   
class NEXForms_widget_controls {
	static function parse($tpl, $hash){
   	   foreach ($hash as $key => $value)
			$tpl = str_replace('[+'.$key.'+]', $value, $tpl);
	   return $tpl;
	}
}
$get_nf_db = new NEXForms_Database_Actions();

function NEXForms_entry_status($form_Id){
			global  $wpdb;
			
			$set_form_id = $form_Id;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$entry = $wpdb->get_var($wpdb->prepare('SELECT viewed FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE Id= %d',$set_form_id));
			return ($entry=='viewed') ? '<span data-form-id="'.$set_form_id.'" class="set_viewed is_viewed fas fa-eye"></span>' : '<span data-form-id="'.$set_form_id.'" class="set_viewed not_viewed fas fa-eye-slash"></span>';
		}


function NEXForms_starred($form_Id){
			global  $wpdb;
			
			$set_form_id = $form_Id;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$entry = $wpdb->get_var($wpdb->prepare('SELECT starred FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE Id= %d',$set_form_id));
			return ($entry=='1') ? '<span data-form-id="'.$set_form_id.'" class="set_starred is_starred fas fa-star"></span>' : '<span data-form-id="'.$set_form_id.'" class="set_starred not_starred far fa-star"></span>';
		}

function NEXForms_get_attachment($form_Id){
			global  $wpdb;
			
			$set_form_id = $form_Id;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$entry = $wpdb->get_var($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_files WHERE entry_Id= %d',$set_form_id));
			return ($entry) ? '<span data-form-id="'.$set_form_id.'" class="set_attachement has_attachment fas fa-paperclip"></span>' : '';
		}

function NEXForms_get_entry_data_preview($Id='',$table=''){
	global $wpdb;
	$nf_functions = new NEXForms_Functions();
	
	if(is_array($Id))
		{
		$params = $Id;
		$Id = $params[0];
		$table = $params[1];
		}
	
	$get_the_data = $wpdb->prepare("SELECT form_data FROM " . $wpdb->prefix ."wap_nex_forms_entries WHERE Id = %d ",$Id);
	$set_form_data = $wpdb->get_var($get_the_data);
	
	$form_data = json_decode($set_form_data,1);
	$set_data = '';
	foreach($form_data as $data)
		{
		$field_name = $data['field_name'];
		$field_value = $data['field_value'];
		if(!is_array($field_value)){

			if(!strstr($field_value,'data:image'))
				$set_data .= '<span class="entry_data_name">'.$nf_functions->unformat_records_name($field_name).'</span> : <span class="entry_data_value">'.$field_value.'</span> | ';
			else
				$set_data .= '<span class="entry_data_name">'.$nf_functions->unformat_records_name($field_name).'</span> : <span class="entry_data_value"><img src="'.$field_value.'" width="50"/></span> | ';
			}
		}
		
		$set_data = rtrim($set_data,' | ');
	return '<div class="entry_data_summary" data-title="View Entry" data-toggle="tooltip_bs" data-placement="bottom"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-submissions&entry_id='.$Id.'" >'.str_replace('\\','',$set_data).'</a></div>';
}
function NEXForms_get_title($Id='',$table=''){
			global $wpdb;
			$nf_functions = new NEXForms_Functions();
			if(is_array($Id))
				{
				$params = $Id;
				$Id = $params[0];
				$table = $params[1];
				}
				
			$get_the_title = $wpdb->prepare("SELECT title FROM " . $wpdb->prefix .$table." WHERE Id = %d ",$Id);
			$the_title = sanitize_text_field($wpdb->get_var($get_the_title));
			
			$the_title= wp_unslash($the_title);
			$the_title= str_replace('\"','',$the_title);
			$the_title= str_replace('/','',$the_title);
			$the_title = sanitize_text_field( $the_title );
		
			if(!$the_title)
				{
				$the_title = 'Form Preview';				
				}
			return $nf_functions->view_excerpt(str_replace('\\','',sanitize_text_field($the_title)),20);
		}
function NEXForms_get_title2($Id='',$table=''){
			global $wpdb;
			$functions = new NEXForms_Functions();
			if(is_array($Id))
				{
				$params = $Id;
				$Id = $params[0];
				$table = $params[1];
				}
			$get_the_title = $wpdb->prepare("SELECT title FROM " . $wpdb->prefix .$table." WHERE Id = %d",filter_var($Id,FILTER_SANITIZE_NUMBER_INT));
			$the_title = sanitize_text_field($wpdb->get_var($get_the_title));
			
			$the_title= wp_unslash($the_title);
			$the_title= str_replace('\"','',$the_title);
			$the_title= str_replace('/','',$the_title);
			$the_title = sanitize_text_field( $the_title );
			
			if(!$the_title)
				{
				$the_title = 'Form Preview';				
				}
			return str_replace('\\','', sanitize_text_field($the_title));
		}
function NEXForms_get_title3($Id='',$table=''){
			global $wpdb;
			$functions = new NEXForms_Functions();
			if(is_array($Id))
				{
				$params = $Id;
				$Id = $params[0];
				$table = $params[1];
				}
			$get_the_title = $wpdb->prepare("SELECT title FROM " . $wpdb->prefix .$table." WHERE Id = %d",filter_var($Id,FILTER_SANITIZE_NUMBER_INT));
			$the_title = sanitize_text_field($wpdb->get_var($get_the_title));
			
			
			$the_title= wp_unslash($the_title);
			$the_title= str_replace('\"','',$the_title);
			$the_title= str_replace('/','',$the_title);
			$the_title = sanitize_text_field( $the_title );
			
			if(!$the_title)
				{
				$the_title = 'Form Preview';				
				}
			return '<span class="the_form_title" data-form-id="'.$Id.'">'.str_replace('\\','',sanitize_text_field($the_title)).'</span>';
		}
function NEXForms_download_file($url,$table=''){
			global $wpdb;
			
			$functions = new NEXForms_Functions();
			if(is_array($url))
				{
				$url = $url[0];
				}
			
			return '<a href="'.$url.'" class="export_form" download="'.$url.'"><i class="fa fa-cloud-download" data-title="Download" data-toggle="tooltip_bs" data-placement="bottom" data-original-title="" title=""></i></a>';
		}		
function NEXForms_sanitize_array( $array=array() ) {
	foreach ( $array as $key => $val ) {
		if(is_array($val))
			{
			$safe_array[ $key ] = NEXForms_sanitize_array($val);
			}
		else
			{
			$safe_array[ $key ] =  sanitize_text_field( $val );
			}
	}
	return $safe_array;
}				
		
?>