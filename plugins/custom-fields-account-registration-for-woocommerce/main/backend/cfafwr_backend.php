<?php
    function CFAFWR_submenu_page() {
        add_submenu_page('edit.php?post_type=wporg_custom_field',__( 'woocommerce Custom Fields Registration', __('Custom Fields Registration','custom-fields-account-for-woocommerce-registration') ),__('Settings','custom-fields-account-for-woocommerce-registration'),'manage_options','custom-fields-registration-settings','CFAFWR_callback');
    }

    function CFAFWR_callback(){
    	global $cfafwr_comman;
    	?>
    	<div class="wrap">
        	<h2><?php echo __('Custom Fields For Woocommerce Registration','custom-fields-account-for-woocommerce-registration');?></h2>	
        	<div class="card cfafw_notice">
	            <h2><?php echo __('Please help us spread the word & keep the plugin up-to-date', 'custom-fields-account-for-woocommerce-registration');?></h2>
	            <p>
	            	<a class="button-primary button" title="<?php echo __('Support Custom Fields Account Registration', 'custom-fields-account-for-woocommerce-registration');?>" target="_blank" href="https://www.plugin999.com/support/"><?php echo __('Support', 'custom-fields-account-for-woocommerce-registration'); ?></a>
	                <a class="button-primary button" title="<?php echo __('Rate Custom Fields Account Registration', 'custom-fields-account-for-woocommerce-registration');?>" target="_blank" href="https://wordpress.org/support/plugin/custom-fields-account-registration-for-woocommerce/reviews/?filter=5"><?php echo __('Rate the plugin ★★★★★', 'custom-fields-account-for-woocommerce-registration'); ?></a>
	            </p>
	        </div>
        	<?php 
                if(isset($_REQUEST['message'])){
                    if($_REQUEST['message'] == 'success'){ 
                        ?>
                        <div class="notice notice-success is-dismissible"> 
                            <p><strong><?php echo __( 'Your Settings Updated Successfully...!', 'custom-fields-account-for-woocommerce-registration' );?></strong></p>
                        </div>
                        <?php 
                    }elseif($_REQUEST['message'] == 'delete'){ 
                        ?>
                        <div class="notice notice-success is-dismissible"> 
                            <p><strong><?php echo __( 'Your Field Deleted Successfully...!', 'custom-fields-account-for-woocommerce-registration' );?></strong></p>
                        </div>
                        <?php 
                    }
                }
            ?>            		
        	<div class="cfafwr-container">
	            <form method="post">
	                <ul class="nav-tab-wrapper woo-nav-tab-wrapper">
	                    <li class="nav-tab nav-tab-active" data-tab="cfafwr-tab-general"><?php echo __('General Setting','custom-fields-account-for-woocommerce-registration');?></li>
                    	<li class="nav-tab" data-tab="cfafwr-tab-registration-fields"><?php echo __('Registration Fields','custom-fields-account-for-woocommerce-registration');?></li>
	                </ul>
	                <div id="cfafwr-tab-general" class="tab-content current">
	                	<div class="postbox">
	                		<div class="inside">
			                	<table class="data_table">
			                        <tbody>
			                            <tr>
			                                <th>
			                                    <label><?php echo __('Enable Authentication','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                    <input type="checkbox" name="cfafwr_comman[cfafwr_enable_plugin]" value="yes"<?php if($cfafwr_comman['cfafwr_enable_plugin'] == 'yes'){echo "checked";}?>>
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label><?php echo __('Enable User Registration Email','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                    <input type="checkbox" name="cfafwr_comman[cfafwr_user_email_sent]" class="enable_email_section" value="yes"<?php if($cfafwr_comman['cfafwr_user_email_sent'] == 'yes'){echo "checked";}?>>
			                                </td>
			                            </tr>
			                            <tr class="email_subject_and_body_message">
			                                <th>
			                                    <label><?php echo __('User Registration Email Subject Message','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_user_email_subject_msg]" value="Your account has been created succefully." disabled>
			                                	<label class="fcpfw_comman_link"><?php echo __('This Option Available in ','custom-fields-account-for-woocommerce-registration');?> <a href="https://www.plugin999.com/plugin/custom-fields-account-for-woocommerce-registration/" target="_blank"><?php echo __('Pro Version','custom-fields-account-for-woocommerce-registration');?></a></label>
			                                </td>
			                            </tr>
			                            <tr class="email_subject_and_body_message">
			                                <th>
			                                    <label><?php echo __('User Registration Email Body Message','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                    <textarea name="cfafwr_comman[cfafwr_user_email_body_msg]" class="regular-text" rows="5" disabled><?php echo __('Thanks for creating an account on {site_name}.','custom-fields-account-for-woocommerce-registration');?></textarea>
			                                    <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','custom-fields-account-for-woocommerce-registration');?> <a href="https://www.plugin999.com/plugin/custom-fields-account-for-woocommerce-registration/" target="_blank"><?php echo __('Pro Version','custom-fields-account-for-woocommerce-registration');?></a></label>
			                                    <p class="cfafwr_description"><strong>Note : </strong> <code>{site_name}</code> = <?php echo get_bloginfo( 'name' );?></p>
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label><?php echo __('Hide Field labels','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                   	<select name="cfafwr_comman[cfafwr_hide_field_labels]" class="regular-text">
			                                   		<option value="yes" <?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo "selected";}?>>Yes</option>
			                                   		<option value="no" <?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'no'){echo "selected";}?>>No</option>
			                                   	</select>
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label><?php echo __('Change Login/Register Title Text','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                   	<input type="checkbox" class="cfafwr_login_reg_change_text" name="cfafwr_comman[cfafwr_login_reg_change_text]" value="yes" <?php if($cfafwr_comman['cfafwr_login_reg_change_text'] == 'yes'){echo "checked";}?>>
			                                   	<label>Enable/Disable</label>
			                                </td>
			                            </tr>
			                            <tr class="cfafwr_log_reg">
			                                <th>
			                                    <label><?php echo __('Change Login Title Text','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                   	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_login_change_text]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_login_change_text']); ?>">
			                                </td>
			                            </tr>
			                            <tr class="cfafwr_log_reg">
			                                <th>
			                                    <label><?php echo __('Change Register Title Text','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_reg_change_text]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_reg_change_text']); ?>">
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label><?php echo __('Field Required Message Text','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_field_label_require_text]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_field_label_require_text']);?>">
			                                	<p class="cfafwr_description"><strong>Note : </strong> <code>{field_label}</code> = Register field labels..</p>
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label><?php echo __('Change My Account Custom Tab Title Text','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_myac_tab_title]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_myac_tab_title']);?>">
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label><?php echo __('Change My Account Custom Tab Form Heading Text','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_myac_tab_form_head]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_myac_tab_form_head']);?>">
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label><?php echo __('Show Custom Field','custom-fields-account-for-woocommerce-registration');?></label>
			                                </th>
			                                <td>
			                                   	<select name="cfafwr_comman[cfafwr_show_field_register]" class="regular-text">
			                                   		<option value="register_form_start" <?php if($cfafwr_comman['cfafwr_show_field_register'] == 'register_form_start'){echo "selected";}?>>Register Form Start</option>
			                                   		<option value="before_register_form" <?php if($cfafwr_comman['cfafwr_show_field_register'] == 'before_register_form'){echo "selected";}?>>Before Register Button</option>
			                                   	</select>
			                                </td>
			                            </tr>
			                        </tbody>
			                    </table>
			                </div>
		                </div>
	                </div>
	                <div id="cfafwr-tab-registration-fields" class="tab-content">
	                	<div class="postbox">
	    					<div class="cfafwr_add_new_fields">
	    						<div class="postbox-header">
									<span><h2><?php echo __('Registration Fields','custom-fields-account-for-woocommerce-registration');?></h2></span>
								</div>
								<?php 
								$myargs = array(
						           'post_type' => 'wporg_custom_field', 
						           'posts_per_page' => -1, 
						           'meta_key' => 'cfafwr_field_ajax_id', 
						           'orderby' => 'meta_value_num', 
						           'order' => 'ASC'
						        );
						        $posts = query_posts($myargs );
						        if (!empty($posts)) {
									?>
									<ul class="cfafwr_dl_data">
										<?php
								        foreach ($posts as $key => $post) {
											$custom_register_field_type = get_post_meta($post->ID,'custom_register_field_type',true);
											$cfafwr_field_ajax_id = get_post_meta($post->ID,'cfafwr_field_ajax_id',true);
											$custom_field_label = get_the_title($post->ID);
											$custom_field_checkbox = get_post_meta($post->ID,'custom_field_checkbox',true);
			                        	?>
			                        	<li>
			                        		<div class="cfafwr_add_new_fields_inner" value="<?php echo esc_attr($post->ID);?>" id="<?php echo 'custom_field_checkbox'. esc_attr($post->ID);?>">
			                        			<span class="cfafwr_label">
			                        				<?php echo __($custom_field_label,'custom-fields-account-for-woocommerce-registration');?>
			                        			</span>
			                        			<span class="cfafwr_checkbox">
			                        				<input type="checkbox" name="<?php echo 'custom_field_checkbox'.esc_attr($post->ID);?>" title="Enable This Field" value="yes"<?php if($custom_field_checkbox == 'yes'){echo "checked";}?>>
			                        				<?php
			                        					$link = admin_url() . "post.php?post=" . $post->ID . "&action=delete";
														$delLink = wp_nonce_url($link);
														$edit_link = get_edit_post_link($post->ID);
			                        				?>
			                        				<a href="<?php echo esc_attr($edit_link);?>" target="_blank" title="Edit Field"><img class="remove_field" data-id="<?php echo esc_attr($post->ID);?>" src="<?php echo CFAFWR_PLUGIN_DIR.'/assets/images/edit_icon.png';?>"></a>
			                        				<a href="<?php echo admin_url( '/admin.php?page=custom-fields-registration-settings' );?>&action=delete_post&post_id=<?php echo esc_attr($post->ID);?>" title="Delete Field"><img class="remove_field" data-id="<?php echo esc_attr($post->ID);?>" src="<?php echo CFAFWR_PLUGIN_DIR.'/assets/images/remove.png';?>"></a>
			                        			</span>
			                        		</div>
				                        </li>
			                        	<?php
										}
										?>
									</ul>
									<?php

						        }else{
						        	echo "<div class='register_empty_fields'>";
						        	echo "<p class='empty_register_fields'>Registration fields is not set....</p>";
						        	echo "<a href='".esc_url(admin_url())."post-new.php?post_type=wporg_custom_field' target='_blank' class='add_field_button button-primary'>".__('Add registration fields','custom-fields-account-for-woocommerce-registration')."</a>";
						        	echo "</div>";
						        }
						        ?>
						        <table class="data_table">
		                        	<tbody>
								        <?php
										$myargs = array(
								           'post_type' => 'wporg_custom_field', 
								           'posts_per_page' => -1, 
								           'meta_key' => 'cfafwr_field_ajax_id', 
								           'orderby' => 'meta_value_num', 
								           'order' => 'ASC'
								        );
								        $posts = query_posts($myargs );
							        	$confirm = false;
							        	if (!empty($posts)) {
									        foreach ($posts as $key => $post) {
									        	$custom_register_field_type = get_post_meta($post->ID,'custom_register_field_type',true);
												if ($custom_register_field_type == 'country') {
													$confirm = true;
												}
											}
										}
										?>
										<tr class="cfafwr_country_title">
			                            	<th><h2><?php echo __('Add a country field then you can see the State field','custom-fields-account-for-woocommerce-registration');?></h2>
			                            	<a class="button-primary add_country_button" target="_blank" href="<?php echo admin_url( '/post-new.php?post_type=wporg_custom_field&field_add=country');?>"><?php echo __('Add Country Field','custom-fields-account-for-woocommerce-registration');?></a></th>
			                            </tr>
									</tbody>
								</table>
							</div>
						</div>	
	                </div>
	                <div class="submit_button">
	                    <input type="hidden" name="cfafwr_form_submit" value="cfafwr_save_option">
	                    <input type="submit" value="Save changes" name="submit" class="button-primary" id="cfafwr-btn-space">
	                </div>
               	</form>
           	</div>
       	</div>
    	<?php
    }

    function CFAFWR_filed_sortable(){
    	
    	foreach ($_REQUEST['post_meta'] as $keypost_meta => $valuepost_meta) {
    		update_post_meta($valuepost_meta,'cfafwr_field_ajax_id',(int)($keypost_meta));
    	}
		exit();
	}

	function CFAFWR_global_notice_meta_box() {

	    add_meta_box(
	        'cusrom_regster_field_id',
	        __( 'Custom Register Fields', 'Custom_Register' ),
	        'cusrom_field_meta_box_callback',
	        'wporg_custom_field'
	    );
	}

	function cusrom_field_meta_box_callback($post){
		remove_meta_box( 'slugdiv', 'wporg_custom_field', 'normal' );
		?>
		<form method="post">
			<table class="meta_box_table">
				<tbody>
					<tr>
						<th>
							<label><?php echo __('Custom Registration Field Type','custom-fields-account-for-woocommerce-registration');?></label>
						</th>
						<td>
							<?php
							$custom_register_field_type = get_post_meta($post->ID,'custom_register_field_type',true);
							?>
							<select name="custom_register_field_type" class="regular-text custom_field_type">
								<optgroup label="Billing Address Fields">
									<?php
									$billaddress_fields = wc()->countries->get_address_fields(get_user_meta(get_current_user_id(), 'billing_country', true));
		                            foreach ($billaddress_fields as $key => $field) {
		                            	?>
								    	<option value="<?php echo esc_attr($key);?>" <?php if($custom_register_field_type == $key){echo "selected";}?>><?php echo esc_html($field['label']);?></option>
								    	<?php
		                            }
									?>
							  	</optgroup>
							  	<optgroup label="Shipping Address Fields">
								    <?php
									$countries = new WC_Countries();
		                            if ( ! isset( $country ) ) {
		                                $country = $countries->get_base_country();
		                            }
		                            $shipaddress_fields = WC()->countries->get_address_fields( $country, 'shipping_' );
		                            foreach ($shipaddress_fields as $key => $field) {
		                                ?>
								    	<option value="<?php echo esc_attr($key);?>" <?php if($custom_register_field_type == $key){echo "selected";}?>><?php echo esc_html($field['label']);?></option>
								    	<?php
		                            }
									?>
							  	</optgroup>
							  	<optgroup label="Other Fields">
									<option value="text"<?php if($custom_register_field_type == 'text'){echo "selected";}?>><?php echo __('Text','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="number"<?php if($custom_register_field_type == 'number'){echo "selected";}?>><?php echo __('Number','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="tel"<?php if($custom_register_field_type == 'tel'){echo "selected";}?>><?php echo __('Phone','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="email"<?php if($custom_register_field_type == 'email'){echo "selected";}?>><?php echo __('Email','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="url"<?php if($custom_register_field_type == 'url'){echo "selected";}?>><?php echo __('Url','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="checkbox"<?php if($custom_register_field_type == 'checkbox'){echo "selected";}?>><?php echo __('Checkbox','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="country" disabled><?php echo __('Country','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="image" disabled><?php echo __('File Upload','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="password" disabled><?php echo __('Password','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="textarea" disabled><?php echo __('Textarea','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="html" disabled><?php echo __('Custom Html','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="color" disabled><?php echo __('Color Picker','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="time" disabled><?php echo __('Time Picker','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="date" disabled><?php echo __('Date Picker','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="radio" disabled><?php echo __('Radio','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="multicheckbox" disabled><?php echo __('Multiple Checkbox','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="select" disabled><?php echo __('Select','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="multiselect" disabled><?php echo __('Multi Select','custom-fields-account-for-woocommerce-registration');?></option>
									<option value="hidden" disabled><?php echo __('Hidden','custom-fields-account-for-woocommerce-registration');?></option>
								</optgroup>
							</select>
							<label class="cfafw_common_link"><?php echo __('Some Types Only available in ','custom-fields-account-for-woocommerce-registration');?><a href="https://www.plugin999.com/plugin/custom-fields-account-for-woocommerce-registration/" target="_blank"><?php echo __('pro version','custom-fields-account-for-woocommerce-registration');?></a></label>
						</td>
					</tr>
					<tr class="custom_html">
						<th>
							<label><?php echo __('Field Label','custom-fields-account-for-woocommerce-registration');?></label>
						</th>
						<td>
							<?php
							$custom_field_label = get_post_meta($post->ID,'custom_field_label',true);
							?>
							<input type="text" class="regular-text" name="custom_field_label" value="<?php echo esc_attr($custom_field_label);?>">
							<p class="cfafwr_description"><strong>Note:</strong> You do compulsory add this field's value.</p>
						</td>
					</tr>
					<tr class="custom_html">
						<th>
							<label><?php echo __('Field Slug Name','custom-fields-account-for-woocommerce-registration');?></label>
						</th>
						<td>
							<?php
								if(!empty( get_post_meta($post->ID,'custom_field_slug_name',true))){
									$custom_field_slug_name = get_post_meta($post->ID,'custom_field_slug_name',true);
								}else{
									$custom_field_slug_name = get_post_meta($post->ID,'custom_field_label',true);
								}
							?>
							<input type="text" class="regular-text" name="custom_field_slug_name" value="<?php echo esc_attr($custom_field_slug_name);?>">
							<p class="cfafwr_description"><strong>Note:</strong> You do compulsory add this field's value. <code>Ex: Your Slug formate is <strong>slug_name</strong></code></p>
						</td>
					</tr>
					<tr class="custom_html cusrequired">
						<th>
							<label><?php echo __('Field Required?','custom-fields-account-for-woocommerce-registration');?></label>
						</th>
						<td>
							<?php
							$custom_field_required = get_post_meta($post->ID,'custom_field_required',true);
							?>
							<input type="checkbox" class="regular-text" name="custom_field_required" value="yes"<?php if($custom_field_required == 'yes'){echo "checked";}?>>
						</td>
					</tr>
					<tr class="custom_html">
						<th>
							<label><?php echo __('Field Size','custom-fields-account-for-woocommerce-registration');?></label>
						</th>
						<td>
							<?php
							$custom_field_size = get_post_meta($post->ID,'custom_field_size',true);
							?>
							<select name="custom_field_size" class="regular-text">
								<option value="full_width"<?php if($custom_field_size == 'full_width'){echo "selected";}?>><?php echo __('Full Width','custom-fields-account-for-woocommerce-registration');?></option>
								<option value="half_width"<?php if($custom_field_size == 'half_width'){echo "selected";}?>><?php echo __('Half Width','custom-fields-account-for-woocommerce-registration');?></option>
							</select>
						</td>
					</tr>
					<tr class="field_placeholder">
						<th>
							<label><?php echo __('Field Placeholder','custom-fields-account-for-woocommerce-registration');?></label>
						</th>
						<td>
							<?php
								$custom_field_placeholder = get_post_meta($post->ID,'custom_field_placeholder',true); ?>
							<input type="text" class="regular-text" name="custom_field_placeholder" value="<?php echo esc_attr($custom_field_placeholder);?>">
						</td>
					</tr>
					<tr class="cfafwr_custom_class">
						<th>
							<label><?php echo __('Add Custom Class','custom-fields-account-for-woocommerce-registration');?></label>
						</th>
						<td>
							<?php
							$cfafwr_add_custom_class = get_post_meta($post->ID,'cfafwr_add_custom_class',true);
							?>
							<input type="text" name="cfafwr_add_custom_class" class="regular-text" value="<?php echo esc_attr($cfafwr_add_custom_class);?>">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
	}

    function CFAFWR_custom_meta_box_field_save(){

	    if(isset($_REQUEST["custom_register_field_type"])){
	    	$custom_register_field_type = sanitize_text_field($_REQUEST["custom_register_field_type"]);
	        update_post_meta( get_the_ID(), 'custom_register_field_type', $custom_register_field_type );
	    }

	    if(isset($_REQUEST["custom_field_label"])){
	    	$custom_field_label = sanitize_text_field($_REQUEST["custom_field_label"]);
	        update_post_meta( get_the_ID(), 'custom_field_label', $custom_field_label );
	    }

	    if(isset($_REQUEST["custom_field_slug_name"])){
	    	$custom_field_slug_name = str_replace(' ','_',sanitize_text_field($_REQUEST["custom_field_slug_name"]));
	        update_post_meta( get_the_ID(), 'custom_field_slug_name', $custom_field_slug_name );
	    }

	    if(isset($_REQUEST["cfafwr_add_custom_class"])){
	    	$cfafwr_add_custom_class = str_replace(' ','_',sanitize_text_field($_REQUEST["cfafwr_add_custom_class"]));
	        update_post_meta( get_the_ID(), 'cfafwr_add_custom_class', $cfafwr_add_custom_class );
	    }

    	$custom_field_required = (!empty($_REQUEST['custom_field_required'])) ? sanitize_text_field($_REQUEST['custom_field_required']):'';
        update_post_meta( get_the_ID(), 'custom_field_required', $custom_field_required );

	    if(isset($_REQUEST["custom_field_size"])){
	    	$custom_field_size = sanitize_text_field($_REQUEST["custom_field_size"]);
	        update_post_meta( get_the_ID(), 'custom_field_size', $custom_field_size );
	    }

	    if(isset($_REQUEST["custom_field_placeholder"])){
	    	$custom_field_placeholder =sanitize_text_field($_REQUEST["custom_field_placeholder"]);
	        update_post_meta( get_the_ID(), 'custom_field_placeholder', $custom_field_placeholder );
	    }

     	$all_post_ids = get_posts(array(
		    'fields'          => 'ids',
		    'posts_per_page'  => -1,
		    'post_type' => 'wporg_custom_field'
		));
		$all_post = count($all_post_ids);
	    update_post_meta( get_the_ID() ,'cfafwr_field_ajax_id',$all_post);
	}


    function CFAFWR_save_option(){

    	$post_type = 'wporg_custom_field';
        $singular_name = 'Custom Register Field';
        $plural_name = 'Register Fields';
        $slug = 'wporg_custom_field';
        $labels = array(
            'name'               => _x( $plural_name, 'post type general name', 'custom-fields-account-for-woocommerce-registration' ),
            'singular_name'      => _x( $singular_name, 'post type singular name', 'custom-fields-account-for-woocommerce-registration' ),
            'menu_name'          => _x( $singular_name, 'admin menu name', 'custom-fields-account-for-woocommerce-registration' ),
            'name_admin_bar'     => _x( $singular_name, 'add new name on admin bar', 'custom-fields-account-for-woocommerce-registration' ),
            'add_new'            => __( 'Add New Field', 'custom-fields-account-for-woocommerce-registration' ),
            'add_new_item'       => __( 'Add New Field '.$singular_name, 'custom-fields-account-for-woocommerce-registration' ),
            'new_item'           => __( 'New '.$singular_name, 'custom-fields-account-for-woocommerce-registration' ),
            'edit_item'          => __( 'Edit '.$singular_name, 'custom-fields-account-for-woocommerce-registration' ),
            'view_item'          => __( 'View '.$singular_name, 'custom-fields-account-for-woocommerce-registration' ),
            'all_items'          => __( 'All '.$plural_name, 'custom-fields-account-for-woocommerce-registration' ),
            'search_items'       => __( 'Search '.$plural_name, 'custom-fields-account-for-woocommerce-registration' ),
            'parent_item_colon'  => __( 'Parent '.$plural_name.':', 'custom-fields-account-for-woocommerce-registration' ),
            'not_found'          => __( 'No Register Field found.', 'custom-fields-account-for-woocommerce-registration' ),
            'not_found_in_trash' => __( 'No Register Field found in Trash.', 'custom-fields-account-for-woocommerce-registration' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description', 'custom-fields-account-for-woocommerce-registration' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $slug ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title' ),
            'menu_icon'          => 'dashicons-media-text',
            'show_in_rest'       =>  false,
        );
        register_post_type( $post_type, $args );

    	if( current_user_can('administrator') ) {
    		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_post'){

                wp_delete_post($_REQUEST['post_id']);

                wp_redirect( admin_url( '/edit.php?post_type=wporg_custom_field&page=custom-fields-registration-settings&message=delete' ) );
                exit;     
            }

            if(isset($_REQUEST['cfafwr_form_submit']) && $_REQUEST['cfafwr_form_submit'] == 'cfafwr_save_option'){

                $isecheckbox = array(
                	'cfafwr_enable_plugin',
                	'cfafwr_user_email_sent',
                	'cfafwr_user_email_sub_body_enable',
                	'cfafwr_login_reg_change_text',
                );

                foreach ($isecheckbox as $key_isecheckbox => $value_isecheckbox) {
                    if(!isset($_REQUEST['cfafwr_comman'][$value_isecheckbox])){
                        $_REQUEST['cfafwr_comman'][$value_isecheckbox] ='no';
                    }
                }

    			$all_post_ids = get_posts(array(
				    'fields'          => 'ids',
				    'posts_per_page'  => -1,
				    'post_type' => 'wporg_custom_field'
				));

				foreach ($all_post_ids as $key => $valueee) {

	                   	$custom_field_slug_name = 'custom_field_checkbox'.$valueee;
						update_post_meta( $valueee, 'custom_field_checkbox', sanitize_text_field($_REQUEST[$custom_field_slug_name]) );
				}

                foreach ($_REQUEST['cfafwr_comman'] as $key_cfafwr_comman => $value_cfafwr_comman) {
                    update_option($key_cfafwr_comman, sanitize_text_field($value_cfafwr_comman), 'yes');
                }

                wp_redirect( admin_url( '/edit.php?post_type=wporg_custom_field&page=custom-fields-registration-settings&message=success' ) );
                exit;     
            }
        }

    }		

    function CFAFWR_custom_user_profile_fields( $user ){
    	global $cfafwr_comman;
        $user_id = $user->ID;
        echo '<h3 class="heading">'.__('Custom Fields','custom-fields-account-for-woocommerce-registration').'</h3>';
        $myargs = array(
           'post_type' => 'wporg_custom_field', 
           'posts_per_page' => -1, 
           'meta_key' => 'cfafwr_field_ajax_id', 
           'orderby' => 'meta_value_num', 
           'order' => 'ASC'
        );
        $posts = get_posts($myargs );
        ?>
        <table class="form-table">
        <?php
        if(!empty($posts)){
            foreach ($posts as $key => $post_id) {
                $custom_field_label = get_post_meta($post_id->ID,'custom_field_label',true);
                $custom_field_slug_name = get_post_meta($post_id->ID,'custom_field_slug_name',true);
                $custom_register_field_type = get_post_meta($post_id->ID,'custom_register_field_type',true);
                $custom_field_required = get_post_meta($post_id->ID,'custom_field_required',true);
                $custom_field_size = get_post_meta($post_id->ID,'custom_field_size',true);
                $cfafwr_add_custom_class = get_post_meta($post_id->ID,'cfafwr_add_custom_class',true);
                $custom_field_placeholder = get_post_meta($post_id->ID,'custom_field_placeholder',true);
                $bill_ship = explode('_', $custom_register_field_type);
                if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes' && $custom_register_field_type != 'checkbox' && $bill_ship[0] !== 'billing' && $bill_ship[0] !== 'shipping' ){
                	$value = get_user_meta( $user_id, $custom_field_slug_name, true );
                    ?>
                    <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                        <th><label for="reg_<?php echo esc_html($custom_field_slug_name);?>"><?php echo esc_html($custom_field_label); ?></label></th>
                        <td><input type="<?php echo esc_html($custom_register_field_type);?>" class="woocommerce-Input woocommerce-Input--<?php echo esc_html($custom_register_field_type);?> input-<?php echo esc_html($custom_register_field_type);?>" placeholder="<?php echo esc_html($custom_field_placeholder);?>" name="<?php echo esc_html($custom_field_slug_name);?>" id="reg_<?php echo esc_html($custom_field_slug_name);?>" value="<?php echo esc_attr($value); ?>" style="width: 25em;" /></td>
                    </tr>
                    </p>
                    <?php
                }elseif($custom_register_field_type == 'checkbox'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                            <th><label for="reg_<?php echo esc_html($custom_field_slug_name);?>"><?php echo esc_html($custom_field_label); ?></label></th>
                            <td><input type="<?php echo esc_html($custom_register_field_type);?>" class="woocommerce-Input woocommerce-Input--<?php echo esc_html($custom_register_field_type);?> input-<?php echo esc_html($custom_register_field_type);?>" placeholder="<?php echo esc_html($custom_field_placeholder);?>" name="<?php echo esc_html($custom_field_slug_name);?>" id="reg_<?php echo esc_html($custom_field_slug_name);?>" value="yes"<?php if (get_user_meta( $user_id, $custom_field_slug_name, true ) == 'yes' ) echo "checked"; ?> /></td>
                        </tr>
                        <?php
                    }
                }
            }
        }
        ?>
        </table>
        <?php
    }	

    function CFAFWR_save_custom_user_profile_fields( $user_id ) {
    	global $cfafwr_comman;
        $all_post_ids = get_posts(array(
            'fields'          => 'ids',
            'posts_per_page'  => -1,
            'post_type' => 'wporg_custom_field'
        ));
        
        if(!empty($all_post_ids)){
            foreach ($all_post_ids as $key => $post_id) {   
                $custom_register_field_type = get_post_meta($post_id,'custom_register_field_type',true);         
                $custom_field_slug_name = get_post_meta($post_id,'custom_field_slug_name',true);
                if ( isset( $_POST[$custom_field_slug_name] ) && get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' ) {
                    update_user_meta( $user_id, $custom_field_slug_name, sanitize_text_field( $_POST[$custom_field_slug_name] ) );
                }
            }
        }
    }

	function CFAFWR_filter_posts_columns( $wporg_custom_field_columns ) {
		$wporg_custom_field_columns['enable']   = '<strong>'.__('Enable','custom-fields-account-for-woocommerce-registration').'</strong>';
		return $wporg_custom_field_columns;
	}

	function CFAFWR_smashing_realestate_column( $column, $post_id ) {
	  	if ( $column == 'enable' ) {
	  		$custom_field_checkbox = get_post_meta($post_id,'custom_field_checkbox',true);
	  		if ($custom_field_checkbox == 'yes') {
	  			echo "<strong>".__('Yes','custom-fields-account-for-woocommerce-registration')."</strong>";
	  		}else{
	  			echo "<strong>".__('No','custom-fields-account-for-woocommerce-registration')."</strong>";
	  		}
	  	}	
	}

	function CFAFWR_save_custom_field_messages( $messages ) {
		if(isset($_REQUEST['post']) && !empty($_REQUEST['post'])){
	    	$messages['wporg_custom_field'] = array(
	    		1  => __( get_the_title( $_REQUEST['post'] ).' Field Saved Successfully...!', 'custom-fields-account-for-woocommerce-registration' ),
	    		6 => __( get_the_title( $_REQUEST['post'] ).' Field Added Successfully...!', 'custom-fields-account-for-woocommerce-registration' )
	    	);
		}
	    return $messages;
	}

	add_action( 'admin_menu','CFAFWR_submenu_page');
	add_action( 'init','CFAFWR_save_option');
    add_action( 'add_meta_boxes','CFAFWR_global_notice_meta_box');
    add_action( 'save_post','CFAFWR_custom_meta_box_field_save');
    add_action( 'wp_ajax_cfafwr_filed_sortable','CFAFWR_filed_sortable');
	add_action( 'wp_ajax_nopriv_cfafwr_filed_sortable','CFAFWR_filed_sortable');
    add_action( 'edit_user_profile','CFAFWR_custom_user_profile_fields',100 );
    add_action( 'show_user_profile','CFAFWR_custom_user_profile_fields',100 );
    add_action( 'personal_options_update','CFAFWR_save_custom_user_profile_fields');
    add_action( 'edit_user_profile_update','CFAFWR_save_custom_user_profile_fields');
    add_filter( 'post_updated_messages','CFAFWR_save_custom_field_messages');
    add_filter( 'manage_wporg_custom_field_posts_columns','CFAFWR_filter_posts_columns');
	add_action( 'manage_wporg_custom_field_posts_custom_column','CFAFWR_smashing_realestate_column', 10, 2);