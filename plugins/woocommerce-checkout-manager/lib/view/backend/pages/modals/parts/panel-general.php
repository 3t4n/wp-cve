<div class="panel woocommerce_options_panel <# if (data.panel != 'general') { #>hidden<# } #>">
	<div class="options_group">
		<p class="form-field">
		<label><?php esc_html_e( 'Name', 'woocommerce-checkout-manager' ); ?></label>
			<# if ( _.contains(<?php echo json_encode( $defaults ); ?>, data.name)) { #>
				<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'You can\'t change the slug of default fields.', 'woocommerce-checkout-manager' ); ?>"></span>
				<input class="short" type="text" name="name" placeholder="<?php esc_html_e( 'myfield', 'woocommerce-checkout-manager' ); ?>" value="{{data.name}}" readonly="readonly">
				<# } else { #>
				<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Currently is not possible to change the name of the fields.', 'woocommerce-checkout-manager' ); ?>"></span>
				<input class="short" type="text" name="name" placeholder="<?php esc_html_e( 'myfield', 'woocommerce-checkout-manager' ); ?>" value="{{data.name}}" readonly="readonly" <?php /* if (empty($options['checkness']['abbreviation'])) { ?>readonly="readonly"<?php } */ ?>>
			<# } #>
		</p>
	</div>
	<div class="options_group">
		<p class="form-field">
			<label><?php esc_html_e( 'Type', 'woocommerce-checkout-manager' ); ?></label>
			<# if (data.type!='state' ) { #>
				<# if ( _.contains(<?php echo json_encode( $defaults ); ?>, data.name)) { #>
					<input class="short" type="text" name="type" value="{{data.type}}" readonly="readonly">
				<# } else { #>
				<select class="media-modal-render-tabs wooccm-enhanced-select" name="type">
					<?php if ( $types ) : ?>
						<?php foreach ( $types as $type => $name ) : ?>
						<option <# if ( data.type=='<?php echo esc_attr( $type ); ?>' ) { #>selected="selected"<# } #>
							<# if ( _.contains(<?php echo json_encode( $disabled ); ?>, '<?php echo esc_attr( $type ); ?>' )) { #>disabled="disabled"<# } #> value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $name ); ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
				<# } #>
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Type of the checkout field.', 'woocommerce-checkout-manager' ); ?>"></span>
			<# } else { #>
				<span span class="description"><?php esc_html_e( 'Is not possible to change state field type.', 'woocommerce-checkout-manager' ); ?></span>
			<# } #>
		</p>
		<# if (data.type=='colorpicker' ) { #>
		<p class="form-field">
			<label><?php esc_html_e( 'Picker Type', 'woocommerce-checkout-manager' ); ?></label>
			<select class="select short" name="pickertype">
				<option <# if ( data.pickertype=='farbtastic' ) { #>selected="selected"<# } #> value="farbtastic"><?php esc_html_e( 'Farbtastic', 'woocommerce-checkout-manager' ); ?></option>
				<option <# if ( data.pickertype=='iris' ) { #>selected="selected"<# } #> value="iris"><?php esc_html_e( 'Iris', 'woocommerce-checkout-manager' ); ?></option>
			</select>
		</p>
		<# } #>
	</div>
	<div class="options_group">
		<p class="form-field">
		<label><?php esc_html_e( 'Label', 'woocommerce-checkout-manager' ); ?></label>
		<input class="short " type="text" name="label" placeholder="<?php esc_html_e( 'My Field Name', 'woocommerce-checkout-manager' ); ?>" value="{{data.label}}">
		<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Label text of the checkout field.', 'woocommerce-checkout-manager' ); ?>"></span>
		</p>
		<# if ( !_.contains(<?php echo json_encode( $template ); ?>, data.type )) { #>
			<p class="form-field">
				<label><?php esc_html_e( 'Placeholder', 'woocommerce-checkout-manager' ); ?></label>
				<input class="short" type="text" name="placeholder" placeholder="<?php esc_html_e( 'This is a placeholder', 'woocommerce-checkout-manager' ); ?>" value="{{data.placeholder}}">
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Placeholder text of the checkout field.', 'woocommerce-checkout-manager' ); ?>"></span>
			</p>
		<# } #>
		<# if ( data.type=='file' ) { #>
			<p class="form-field">
				<label><?php esc_html_e( 'Button', 'woocommerce-checkout-manager' ); ?></label>
				<input class="short" type="text" name="placeholder" placeholder="<?php esc_html_e( 'Upload your files', 'woocommerce-checkout-manager' ); ?>" value="{{data.placeholder}}">
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Text for the button name.', 'woocommerce-checkout-manager' ); ?>"></span>
			</p>
			<p class="form-field wooccm-premium-field">
				<label><?php esc_html_e( 'File Limit', 'woocommerce-checkout-manager' ); ?></label>
				<input class="short" type="number" min="1" name="file_limit" placeholder="1" value="{{data.file_limit}}">
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Max number of files to upload.', 'woocommerce-checkout-manager' ); ?>"></span>
				<span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
			</p>
			<p class="form-field wooccm-premium-field">
				<label><?php esc_html_e( 'File Max Size (Kb)', 'woocommerce-checkout-manager' ); ?></label>
				<input class="short" type="number" min="1" max="<?php echo esc_attr( wp_max_upload_size() / 1024 ); ?>" name="file_max_size" value="{{data.file_max_size}}">
				<span span class="woocommerce-help-tip" data-tip="<?php printf( esc_html__( 'Max of each file to upload. It can\'t exceed the current WordPress max file size: %s', 'woocommerce-checkout-manager' ), esc_attr( wp_max_upload_size() / 1024 ) ); ?>"></span>
				<span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
			</p>
			<p class="form-field wooccm-premium-field">
				<label><?php esc_html_e( 'File Types', 'woocommerce-checkout-manager' ); ?></label>
				<select class="wooccm-enhanced-select" name="file_types[]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Choose the allowed types&hellip;', 'woocommerce-checkout-manager' ); ?>" data-allow_clear="true" >
					<?php foreach ( get_allowed_mime_types() as $type => $name ) : ?>
						<option <# if ( _.contains(data.file_types, '<?php echo esc_attr( $type ); ?>') ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $type ); ?></option>
					<?php endforeach; ?>
				</select>
				<span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
			</p>
		<# } #>
	</div>
	<# if ( !_.contains(<?php echo json_encode( $template ); ?>, data.type )) { #>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Description', 'woocommerce-checkout-manager' ); ?></label>
				<textarea class="short" type="text" name="description" placeholder="<?php esc_html_e( 'Description of the checkout field', 'woocommerce-checkout-manager' ); ?>">{{data.description}}</textarea>
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Placeholder text of the checkout field.', 'woocommerce-checkout-manager' ); ?>"></span>
			</p>
		</div>
	<# } #>
	<# if ( data.type=='message' ) { #>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Type', 'woocommerce-checkout-manager' ); ?></label>
				<select class="wooccm-enhanced-select" name="message_type">
				<option <# if ( 'info'==data.message_type ) { #>selected="selected"<# } #> value="info"><?php esc_html_e( 'Info', 'woocommerce-checkout-manager' ); ?></option>
				<option <# if ( 'message'==data.message_type ) { #>selected="selected"<# } #> value="message"><?php esc_html_e( 'Success', 'woocommerce-checkout-manager' ); ?></option>
				<option <# if ( 'error'==data.message_type ) { #>selected="selected"<# } #> value="error"><?php esc_html_e( 'Error', 'woocommerce-checkout-manager' ); ?></option>
				</select>
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Message alert type.', 'woocommerce-checkout-manager' ); ?>"></span>
			</p>
			<p class="form-field">
				<label><?php esc_html_e( 'Message', 'woocommerce-checkout-manager' ); ?></label>
				<textarea class="short" name="description" placeholder="<?php esc_html_e( 'Message content', 'woocommerce-checkout-manager' ); ?>">{{data.description}}</textarea>
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Message content.', 'woocommerce-checkout-manager' ); ?>"></span>
			</p>
		</div>
	<# } #>
	<# if ( data.type=='button' ) { #>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Type', 'woocommerce-checkout-manager' ); ?></label>
				<select class="wooccm-enhanced-select" name="button_type">
				<option <# if ( ''==data.button_type ) { #>selected="selected"<# } #> value=""><?php esc_html_e( 'Default', 'woocommerce-checkout-manager' ); ?></option>
				<option <# if ( 'alt'==data.button_type ) { #>selected="selected"<# } #> value="alt"><?php esc_html_e( 'Alt', 'woocommerce-checkout-manager' ); ?></option>
				</select>
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Message alert type.', 'woocommerce-checkout-manager' ); ?>"></span>
			</p>
			<p class="form-field">
				<label><?php esc_html_e( 'Link', 'woocommerce-checkout-manager' ); ?></label>
				<input class="short" type="text" name="button_link" placeholder="<?php esc_html_e( 'URL', 'woocommerce-checkout-manager' ); ?>" value="{{data.button_link}}" />
				<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Button URL.', 'woocommerce-checkout-manager' ); ?>"></span>
			</p>
		</div>
	<# } #>
	<# if ( !_.contains(<?php echo json_encode( array_merge( $template, $option ) ); ?>, data.type)) { #>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Default', 'woocommerce-checkout-manager' ); ?></label>
				<# if (data.type=='checkbox' ) { #>
				<select class="wooccm-enhanced-select" name="default">
					<option <# if ( 1==data.default ) { #>selected="selected"<# } #> value="1"><?php esc_html_e( 'Yes' ); ?></option>
					<option <# if ( 0==data.default ) { #>selected="selected"<# } #> value="0"><?php esc_html_e( 'No' ); ?></option>
				</select>
				<# } else if (data.type=='number' ) { #>
					<input class="short" type="number" name="default" placeholder="<?php esc_html_e( 'Enter a default value (optional)', 'woocommerce-checkout-manager' ); ?>" value="{{data.default}}">
					<# } else { #>
					<input class="short" type="text" name="default" placeholder="<?php esc_html_e( 'Enter a default value (optional)', 'woocommerce-checkout-manager' ); ?>" value="{{data.default}}">
					<# } #>
						<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Default value of the checkout field.', 'woocommerce-checkout-manager' ); ?>"></span>
			</p>
		</div>
	<# } #>
	<# if ( data.type=='number' ) { #>
		<p class="form-field dimensions_field">
		<label><?php esc_html_e( 'Number', 'woocommerce-checkout-manager' ); ?></label>
		<span class="wrap">
			<input style="width:48.1%" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" placeholder="<?php esc_attr_e( 'minimum', 'woocommerce-checkout-manager' ); ?>" class="short" name="min" value="{{data.min}}">
			<input style="width:48.1%;margin: 0;" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" type="number" placeholder="<?php esc_attr_e( 'maximum', 'woocommerce-checkout-manager' ); ?>" class="short" name="max" value="{{data.max}}">
		</span>
		</p>
	<# } #>
	<# if ( data.type=='text' || data.type=='textarea' ) { #>
		<p class="form-field dimensions_field">
			<label><?php esc_html_e( 'Maxlength', 'woocommerce-checkout-manager' ); ?></label>
			<input class="short" type="number" name="maxlength" placeholder="<?php esc_html_e( 'Enter a maxlength value (optional)', 'woocommerce-checkout-manager' ); ?>" value="{{data.maxlength}}">
		</p>
	<# } #>
	<# if ( data.type=='text' || data.type=='textarea' ) { #>
		<p class="form-field dimensions_field">
			<label><?php esc_html_e( 'Minlength', 'woocommerce-checkout-manager' ); ?></label>
			<input class="short" type="number" name="minlength" placeholder="<?php esc_html_e( 'Enter a minlength value (optional)', 'woocommerce-checkout-manager' ); ?>" value="{{data.minlength}}">
		</p>
	<# } #>
	<# if ( data.type=='text' ) { #>
		<p class="form-field dimensions_field wooccm-premium-field">
			<label><?php esc_html_e( 'Validate Regex', 'woocommerce-checkout-manager' ); ?></label>
			<input class="short" type="text" name="validate_regex" placeholder="<?php esc_html_e( 'Enter a regex to validate input data', 'woocommerce-checkout-manager' ); ?>" value="{{data.validate_regex}}">
			<span class="description"><a target="_blank" href="w3schools.com/php/php_regex.asp">PHP Regular Expressions</a>.</span>
			<span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
		</p>
	<# } #>
	<# if (data.type=='country' ) { #>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Default', 'woocommerce-checkout-manager' ); ?></label>
				<select class="wooccm-enhanced-select" name="default" data-placeholder="<?php esc_attr_e( 'Preserve default country&hellip;', 'woocommerce-checkout-manager' ); ?>" data-allow_clear="true">
				<option <# if (data.default=='' ) { #>selected="selected"<# } #> value=""></option>
				<?php foreach ( WC()->countries->get_countries() as $id => $name ) : ?>
					<option <# if (data.default=='<?php echo esc_attr( $id ); ?>' ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
				</select>
			</p>
		</div>
	<# } #>
	<# if (data.type=='state' ) { #>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Country', 'woocommerce-checkout-manager' ); ?></label>
				<select class="wooccm-enhanced-select" name="country" data-placeholder="<?php esc_attr_e( 'Select country&hellip;', 'woocommerce-checkout-manager' ); ?>" data-allow_clear="true">
				<?php foreach ( WC()->countries->get_countries() as $id => $name ) : ?>
					<option <# if (data.country=='<?php echo esc_attr( $id ); ?>' ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
				</select>
			</p>
		</div>
	<# } #>
	<div class="options_group">
		<p class="form-field">
		<label><?php esc_html_e( 'Extra class', 'woocommerce-checkout-manager' ); ?></label>
		<input class="short" type="text" name="extra_class" value="{{data.extra_class}}">
		</p>
	</div>
	<div class="options_group">
		<p class="form-field wooccm-premium-field">
			<label><?php esc_html_e( 'Autocomplete attribute', 'woocommerce-checkout-manager' ); ?></label>
			<select class="select short" name="autocomplete">
				<?php foreach ( QuadLayers\WOOCCM\Helpers::get_autocomplete_options() as $autocomplete_option ) : ?>
					<option <# if ( data.autocomplete=='<?php echo esc_attr( $autocomplete_option ); ?>' ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $autocomplete_option ); ?>"><?php echo sprintf( esc_html__( '%s', 'woocommerce-checkout-manager' ), esc_html( $autocomplete_option ) ); ?></option>
				<?php endforeach; ?>
			</select>
			<span class="description"><a target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/autocomplete">HTML autocomplete attribute</a>.</span>
			<span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
		</p>
	</div>
	<?php if ( $is_billing_shipping ) : ?>
	<div class="options_group">
		<p class="form-field wooccm-premium-field">
			<label><?php esc_html_e( 'Previous selection', 'woocommerce-checkout-manager' ); ?></label>
			<select class="select short" name="user_meta">
				<option <# if ( data.user_meta==true ) { #>selected="selected"<# } #> value="true"><?php esc_html_e( 'Yes', 'woocommerce-checkout-manager' ); ?></option>
				<option <# if ( data.user_meta==false ) { #>selected="selected"<# } #> value="false"><?php esc_html_e( 'No', 'woocommerce-checkout-manager' ); ?></option>
			</select>
			<span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Complete the field with previous user data on checkout load.', 'woocommerce-checkout-manager' ); ?>"></span>
			<span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
		</p>
	</div>
	<?php endif; ?>
</div>
