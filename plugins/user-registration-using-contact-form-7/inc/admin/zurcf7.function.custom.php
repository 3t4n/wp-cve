<?php
/**
 * ZURCF7.function.custom File
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage Plugin name
 * @since 1.0
 */
//call ajax fields
function zurcf7_ACF_filter_array_function()
{
	$response = array();
	if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
		
		$args =  array(     
			'order'    => 'DESC',
			'post_type' => 'acf-field-group',  
		);
		$temp_array = $resarr = $field_label = array();
		$data = get_posts( $args );
		
		foreach($data as $post){
		
			$group_id = $post->ID;		
			$field_group = acf_get_field_group($group_id);		
			if($field_group){
				$field_group_title = $field_group['title'];
				$field_group_location = $field_group['location'];
				foreach ($field_group_location as $rule_group) {
		
					if ($rule_group[0]['param'] === 'current_user' || $rule_group[0]['param'] === 'current_user_role' || $rule_group[0]['param'] === 'user_form' || $rule_group[0]['param'] === 'user_role') {
						
						$fields = acf_get_fields($group_id);   
							
						if($fields){           
							foreach($fields as $field){
								$field_type = $field['type'];
								
								$field_array=array(
									'text',
									'textarea',
									'checkbox',
									'radio',
									'select',
									'number',
									'date_picker',
									'email',
									'link',
									'date_time_picker',
									'password',
									
								);
								if(in_array($field_type,$field_array)){				
									if(!in_array($field['name'], $temp_array )){
										
										$temp_array[] = $field['name'];
										
										$resarr = array(
											'field_name' => $field['name'],
											'field_label' => $field['label'],
											'key' => $field['key'],
											'id' => $field['id'],
										);

										if($field_type == 'checkbox' || $field_type == 'radio' || $field_type == 'select'){
											$resarr['count'] = count($field['choices']);
											$resarr['field_choices'] = $field['choices'];
											$resarr['return_format'] = $field['return_format'];
											
											if(!empty($field['choices'])){
												
												$resarr['valuelabels'] = array_keys($field['choices']);
												$resarr['keylabels'] = array_values($field['choices']);
												
											}
											if($field_type == 'select'){
												
												$resarr['multiple'] = $field['multiple'];											
											}
											
										}									
										$response[] = $resarr;
									}
									
								}
							}
						}
					}
				}		  
				$message = 0;
			}else{
			$message = "Field group not found.";
			}
		
		}
	}else{
		$message = "Plugin Not Active";
	}
	if($field_label){
		array_unique($field_label);
	}
	$resp = array( 'message' => $message, 'response' => $response);
	return $resp;
}