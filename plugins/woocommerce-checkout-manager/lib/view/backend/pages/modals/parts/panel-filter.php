<div class="panel woocommerce_options_panel <# if (data.panel != 'filter') { #>hidden<# } #>">
  <div class="options_group wooccm-premium-field wooccm-enhanced-between-days">
	<p class="form-field dimensions_field">
	  <label><?php esc_html_e( 'Cart subtotal', 'woocommerce-checkout-manager' ); ?></label>
	  <span class="wrap">
		<input style="width:48.1%" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" placeholder="<?php esc_attr_e( 'minimum', 'woocommerce-checkout-manager' ); ?>" min="0" class="short " name="show_cart_minimum" value="{{data.show_cart_minimum}}">
		<input style="width:48.1%;margin: 0;" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" type="number" placeholder="<?php esc_attr_e( 'maximum', 'woocommerce-checkout-manager' ); ?>" min="0" class="short" name="show_cart_maximun" value="{{data.show_cart_maximun}}">
	  </span>
	  <span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
	</p>
  </div>
  <div class="options_group">
	<p class="form-field">
	  <label><?php esc_html_e( 'Show for roles', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-enhanced-select" name="show_role[]" data-placeholder="<?php esc_attr_e( 'Filter by roles', 'woocommerce-checkout-manager' ); ?>" data-allow_clear="true" multiple="multiple">
		<?php foreach ( $wp_roles->roles as $key => $value ) : ?>
		  <option <# if ( _.contains(data.show_role, '<?php echo esc_attr( $key ); ?>' ) ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value['name'] ); ?></option>
		<?php endforeach; ?>
	  </select>
	</p>
	<p class="form-field">
	  <label><?php esc_html_e( 'Hide for roles', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-enhanced-select" name="hide_role[]" data-placeholder="<?php esc_attr_e( 'Filter by roles', 'woocommerce-checkout-manager' ); ?>" data-allow_clear="true" multiple="multiple">
		<?php foreach ( $wp_roles->roles as $key => $value ) : ?>
		  <option <# if ( _.contains(data.hide_role, '<?php echo esc_attr( $key ); ?>' ) ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value['name'] ); ?></option>
		<?php endforeach; ?>
	  </select>
	  <span span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'If user is not logged in will appear as customer.', 'woocommerce-checkout-manager' ); ?>"></span>
	</p>
  </div>

  <div class="options_group">
	<p class="form-field">
	  <label><?php esc_html_e( 'More', 'woocommerce-checkout-manager' ); ?></label>
	  <input <# if (data.apply_conditions_if_more_than_one_product) { #>checked="checked"<# } #> type="checkbox" name="apply_conditions_if_more_than_one_product" value="1">
		<span class="description"><?php esc_html_e( 'Apply conditions even if there is more than one product', 'woocommerce-checkout-manager' ); ?></span>
	</p>
  </div>

  <div class="options_group">
	<p class="form-field">
	  <label><?php esc_html_e( 'Show for products', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-product-search" name="show_product[]" data-placeholder="<?php esc_attr_e( 'Filter by product', 'woocommerce-checkout-manager' ); ?>" data-selected="{{data.show_product}}" data-allow_clear="true" multiple="multiple">
		<# _.each(data.show_product_selected, function(title, id){ #>
		  <option value="{{id}}" selected="selected">{{title}}</option>
		  <# }); #>
	  </select>
	</p>
	<p class="form-field">
	  <label><?php esc_html_e( 'Hide for products', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-product-search" name="hide_product[]" data-placeholder="<?php esc_attr_e( 'Filter by product', 'woocommerce-checkout-manager' ); ?>" data-selected="{{data.hide_product}}" data-allow_clear="true" multiple="multiple">
		<# _.each(data.hide_product_selected, function(title, id){ #>
		  <option value="{{id}}" selected="selected">{{title}}</option>
		  <# }); #>
	  </select>
	</p>
  </div>

  <div class="options_group">
	<p class="form-field">
	  <label><?php esc_html_e( 'Show for category', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-enhanced-select" name="show_product_cat[]" data-placeholder="<?php esc_attr_e( 'Filter by categories', 'woocommerce-checkout-manager' ); ?>" data-selected="{{data.show_product_cat}}" data-allow_clear="true" multiple="multiple">
		<?php if ( $product_categories ) : ?>
			<?php foreach ( $product_categories as $category ) : ?>
			<option <# if ( _.contains(data.show_product_cat, '<?php echo esc_attr( $category->term_id ); ?>' ) ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
	</p>
	<p class="form-field">
	  <label><?php esc_html_e( 'Hide for category', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-enhanced-select" name="hide_product_cat[]" data-placeholder="<?php esc_attr_e( 'Filter by categories', 'woocommerce-checkout-manager' ); ?>" data-selected="{{data.hide_product_cat}}" data-allow_clear="true" multiple="multiple">
		<?php if ( $product_categories ) : ?>
			<?php foreach ( $product_categories as $category ) : ?>
			<option <# if ( _.contains(data.hide_product_cat, '<?php echo esc_attr( $category->term_id ); ?>' ) ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
	</p>
  </div>

  <div class="options_group">
	<p class="form-field">
	  <label><?php esc_html_e( 'Show for product type', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-enhanced-select" name="show_product_type[]" data-placeholder="<?php esc_attr_e( 'Filter by product type', 'woocommerce-checkout-manager' ); ?>" data-selected="{{data.show_product_type}}" data-allow_clear="true" multiple="multiple">
		<?php if ( $product_types ) : ?>
			<?php foreach ( $product_types as $key => $label ) : ?>
			<option <# if ( _.contains(data.show_product_type, '<?php echo esc_attr( $key ); ?>' ) ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
	</p>
	<p class="form-field">
	  <label><?php esc_html_e( 'Hide for product type', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-enhanced-select" name="hide_product_type[]" data-placeholder="<?php esc_attr_e( 'Filter by product type', 'woocommerce-checkout-manager' ); ?>" data-selected="{{data.hide_product_type}}" data-allow_clear="true" multiple="multiple">
		<?php if ( $product_types ) : ?>
			<?php foreach ( $product_types as $key => $label ) : ?>
			<option <# if ( _.contains(data.hide_product_type, '<?php echo esc_attr( $key ); ?>' ) ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
	</p>
  </div>

  <div class="options_group wooccm-premium-field">
	<p class="form-field">
	  <label><?php esc_html_e( 'Show for subtype/s', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-enhanced-select" name="show_product_subtype" data-placeholder="<?php esc_attr_e( 'Filter by product subtype', 'woocommerce-checkout-manager' ); ?>" data-selected="{{data.show_product_subtype}}" data-allow_clear="true">
		<?php if ( $product_subtypes_options ) : ?>
			<option></option>
			<?php foreach ( $product_subtypes_options as $key => $label ) : ?>
			<option <# if ( data.show_product_subtype == '<?php echo esc_attr( $key ); ?>' ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
		<span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
	</p>

	<p class="form-field">
	  <label><?php esc_html_e( 'Hide for subtype/s', 'woocommerce-checkout-manager' ); ?></label>
	  <select class="wooccm-enhanced-select" name="hide_product_subtype" data-placeholder="<?php esc_attr_e( 'Filter by product subtype', 'woocommerce-checkout-manager' ); ?>" data-selected="{{data.hide_product_subtype}}" data-allow_clear="true">
		<?php if ( $product_subtypes_options ) : ?>
			<option></option>
			<?php foreach ( $product_subtypes_options as $key => $label ) : ?>
			<option <# if ( data.hide_product_subtype == '<?php echo esc_attr( $key ); ?>' ) { #>selected="selected"<# } #> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
		<span class="description premium">(<?php esc_html_e( 'This is a premium feature', 'woocommerce-checkout-manager' ); ?>)</span>
	</p>
  </div>
</div>
