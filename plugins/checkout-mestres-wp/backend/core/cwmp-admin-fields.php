<?php
if(esc_html(get_option('cwmp_activate_checkout'))=="S"){
	function cwmp_remove_checkout_fields( $fields ) {
		if(get_option('cwmp_view_active_address')=="S"){
			$fields['billing']['billing_postcode']['required'] = false;
			$fields['billing']['billing_address_1']['required'] = false;
			$fields['billing']['billing_number']['required'] = false;
			$fields['billing']['billing_address_2']['required'] = false;
			$fields['billing']['billing_neighborhood']['required'] = false;
			$fields['billing']['billing_city']['required'] = false;
			$fields['billing']['billing_state']['required'] = false;
			$fields['billing']['billing_country']['required'] = false;
		}
		$fields['billing']['billing_cpf']['required'] = false;
		$fields['billing']['billing_cnpj']['required'] = false;
		$fields['shipping']['shipping_last_name']['required'] = false;
		$fields['shipping']['shipping_postcode']['required'] = false;
		$fields['shipping']['shipping_address_1']['required'] = false;
		$fields['shipping']['shipping_number']['required'] = false;
		$fields['shipping']['shipping_address_2']['required'] = false;
		$fields['shipping']['shipping_neighborhood']['required'] = false;
		$fields['shipping']['shipping_city']['required'] = false;
		$fields['shipping']['shipping_state']['required'] = false;
		$fields['shipping']['shipping_country']['required'] = false;
		return $fields;
	}
	add_filter( 'woocommerce_checkout_fields', 'cwmp_remove_checkout_fields' );

	function cwmp_add_custom_fields($fields) {
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_fields");
		if ($results) {
			foreach ($results as $row) {
				if($row->required=="S"){
					$required = true;
				}else{
					$required = false;
				}
				$fields['billing']['billing_'.$row->name] = array(
					'label'       => __($row->label, 'woocommerce'),
					'placeholder' => _x($row->placeholder, 'placeholder', 'woocommerce'),
					'required'    => $required,
					'clear'       => true,
				);
			}
		}
		return $fields;
	}
	add_filter('woocommerce_checkout_fields', 'cwmp_add_custom_fields');
	
	function cwmp_add_field_name(){
		global $wpdb;
		$field_value = 'field_name';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}
	}
	add_action('cwmp_after_field_name', 'cwmp_add_field_name');



	function cwmp_add_field_phone(){
		global $wpdb;
		$field_value = 'field_phone';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}

			}
		}	
	}
	add_action('cwmp_after_field_phone', 'cwmp_add_field_phone');

	function cwmp_add_field_cellphone(){
		global $wpdb;
		$field_value = 'field_cellphone';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}	
	}
	add_action('cwmp_after_field_cellphone', 'cwmp_add_field_cellphone');

	function cwmp_add_field_email(){
		global $wpdb;
		$field_value = 'field_email';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}	
	}
	add_action('cwmp_after_field_email', 'cwmp_add_field_email');

	function cwmp_add_field_gender(){
		global $wpdb;
		$field_value = 'field_gender';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}	
	}
	add_action('cwmp_after_field_gender', 'cwmp_add_field_gender');

	function cwmp_add_field_birthdate(){
		global $wpdb;
		$field_value = 'field_birthdate';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}	
	}
	add_action('cwmp_after_field_birthdate', 'cwmp_add_field_birthdate');

	function cwmp_add_field_persontype(){
		global $wpdb;
		$field_value = 'field_persontype';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}	
	}
	add_action('cwmp_after_field_persontype', 'cwmp_add_field_persontype');

	function cwmp_add_field_cpf(){
		global $wpdb;
		$field_value = 'field_cpf';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}	
	}
	add_action('cwmp_after_field_cpf', 'cwmp_add_field_cpf');

	function cwmp_add_field_rg(){
		global $wpdb;
		$field_value = 'field_rg';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}
	}
	add_action('cwmp_after_field_rg', 'cwmp_add_field_rg');

	function cwmp_add_field_cnpj(){
		global $wpdb;
		$field_value = 'field_cnpj';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}
	}
	add_action('cwmp_after_field_cnpj', 'cwmp_add_field_cnpj');

	function cwmp_add_field_ie(){
		global $wpdb;
		$field_value = 'field_ie';
		$field_value = strval($field_value);
		$results = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "cwmp_fields WHERE `after` = %s",
			$field_value
		));
		if ($results) {
			foreach ($results as $row) {
				if($row->type=="text"){
				?>
				<p class="cwmp-form-row validate-required" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text" name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" placeholder="<?php echo $row->placeholder; ?>" value="<?php echo $row->default_value; ?>" />
					</span>
					<span class="error hide"></span>
				</p>
				<?php
				}
				if($row->type=="select"){

				?>
				<p class="cwmp-form-row is-active" id="billing_<?php echo $row->name; ?>_field">
					<label for="billing_<?php echo $row->name; ?>" class="screen-reader-text"><?php echo $row->label; ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
					<span class="woocommerce-input-wrapper">
						<select name="billing_<?php echo $row->name; ?>" id="billing_<?php echo $row->name; ?>" class="select" data-placeholder="<?php echo $row->placeholder; ?>" autocomplete="off">
							<?php
								$values = explode(",",$row->default_value);
								foreach($values as $value){
									echo '<option >'.$value.'</option>';
								}
							?>
						</select>
					</span>
				</p>
				<?php
				}
			}
		}
	}
	add_action('cwmp_after_field_ie', 'cwmp_add_field_ie');

	add_action('woocommerce_checkout_update_order_meta', 'cwmp_add_field_update');
	function cwmp_add_field_update($order_id) {
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_fields");
		if ($results) {
			foreach ($results as $row) {
				$value = $_POST['billing_'.$row->name];
				update_post_meta($order_id, $row->name, $value);
			}
		}
	}
	
	add_action('woocommerce_admin_order_data_after_billing_address', 'cwmp_custom_checkout_field_display_admin_order_meta', 10, 1);
	function cwmp_custom_checkout_field_display_admin_order_meta($order) {
		echo '<div class="order_data_column">';
		echo '<h4>' . __('Campos Adicionais', 'woocommerce') . '</h4>';
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_fields");
		if ($results) {
			foreach ($results as $row) {
				echo '<p><strong>' . $row->label . ':</strong> ' . get_post_meta($order->get_id(), $row->name, true) . '</p>';
			}
		}
		echo '</div>';
	}
}




