<?php 
add_action('wpcf7_admin_init','csfcf7_country_select_tag_generator');
function csfcf7_country_select_tag_generator($post){
    if (!class_exists('WPCF7_TagGenerator')) {
        return;
    }
    $tag_generator = WPCF7_TagGenerator::get_instance();
    $tag_generator->add( 'country_select', __( 'country_select', 'country-select-field-with-contact-form-7' ) , 'csfcf7_tag_generator_country' );
}


function csfcf7_tag_generator_country($contact_form, $args = '' ){


	$args = wp_parse_args( $args, array() );
	
	$wpcf7_contact_form = WPCF7_ContactForm::get_current();
	$contact_form_tags = $wpcf7_contact_form->scan_form_tags();
	$type = 'country_select';
	$description = __( "Generate a form-tag for a country select.", 'country-select-field-with-contact-form-7' );
	?>
	<div class="control-box">
		<fieldset>
			<legend><?php echo esc_attr($description); ?></legend>
			<table class="form-table">
				<tr>
					<th>
						<label for="<?php echo esc_attr( $args['content'] . '-filed_type' ); ?>"><?php echo esc_html( __( 'Field type', 'country-select-field-with-contact-form-7' ) ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="required" class=" required_files" required>
						<label><?php echo esc_html( __( 'Required Field', 'country-select-field-with-contact-form-7' ) ); ?></label>
					</td>
					</tr>
				<tr>
					<th><?php echo esc_html( __( 'Name', 'country-select-field-with-contact-form-7' ) ); ?></th>
					<td>
						<input type="text" name="name">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id Attribute', 'country-select-field-with-contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="id" class="country_id oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class Attribute', 'country-select-field-with-contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="class" class="country_value oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-default_country' ); ?>"><?php echo esc_html( __( 'Default country Attribute', 'country-select-field-with-contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="default_country" class="country_default_country oneline option" id="<?php echo esc_attr( $args['content'] . '-default_country' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-only_countries' ); ?>"><?php echo esc_html( __( 'Only Countries Attribute', 'country-select-field-with-contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="only_countries" class="country_only_countries oneline option" id="<?php echo esc_attr( $args['content'] . '-only_countries' ); ?>" disabled/></br><label for="<?php echo esc_attr( $args['content'] . '-only_countries' ); ?>"><p class="description"><?php echo esc_html( __( 'Display only these countries ', 'contact-form-7' ) ); ?></label></br><?php echo esc_html( __( '(e.g. us-ca)', 'contact-form-7' ) ); ?></p>
          				<label class="csfwcf7_comman_link"><?php echo __('This Option Available in ','country-select-field-with-contact-form-7');?> <a href="https://topsmodule.com/product/country-dropdown-for-contact-form-7/" target="_blank">Pro Version</a></label>
                    </td>
				</tr>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-preferred_countries' ); ?>"><?php echo esc_html( __( 'Preferred Countries Attribute', 'country-select-field-with-contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="preferred_countries" class="country_preferred_countries oneline option" id="<?php echo esc_attr( $args['content'] . '-preferred_countries' ); ?>" disabled/><br><label for="<?php echo esc_attr( $args['content'] . '-only_countries' ); ?>"><p class="description"><?php echo esc_html( __( 'The countries at the top of the list.', 'contact-form-7' ) ); ?></label></br><?php echo esc_html( __( '(e.g. us-gb-ch)', 'contact-form-7' ) ); ?></p>
          				<label class="csfwcf7_comman_link"><?php echo __('This Option Available in ','country-select-field-with-contact-form-7');?> <a href="https://topsmodule.com/product/country-dropdown-for-contact-form-7/" target="_blank">Pro Version</a></label>
                    </td>
				</tr>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-exclude_countries' ); ?>"><?php echo esc_html( __( 'Exclude Countries', 'country-select-field-with-contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="exclude_countries" class="country_select_countries oneline option" id="<?php echo esc_attr( $args['content'] . '-exclude_countries' ); ?>" disabled/></br><label for="<?php echo esc_attr( $args['content'] . '-only_countries' ); ?>"><p class="description"><?php echo esc_html( __( 'To hide these countries.', 'contact-form-7' ) ); ?></label></br><?php echo esc_html( __( '(e.g. br-cx-eg)', 'contact-form-7' ) ); ?></p>
          				<label class="csfwcf7_comman_link"><?php echo __('This Option Available in ','country-select-field-with-contact-form-7');?> <a href="https://topsmodule.com/product/country-dropdown-for-contact-form-7/" target="_blank">Pro Version</a></label>
                    </td>
				</tr> 

			</table>
		</fieldset>
	</div>
	<div class="insert-box"> 
		<input type="text" name="<?php echo esc_attr($type); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />
		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'signature-field-with-contact-form-7' ) ); ?>" />
		</div>
		<br class="clear" />
		<p class="description mail-tag">
			<label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'country-select-field-with-contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?>
				<input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" />
			</label>
		</p>
	</div>
	<?php
	}
?>