<?php
/**
** A base module for the following types of tags:
**      [password] and [password*]              # Single-line password
**/

// Activate Language Files for WordPress 3.7 or lator
load_plugin_textdomain('cf7-add-password-field');

function wpcf7_add_form_tag_k_password() {
	$features = array( 'name-attr' => true);
	$features = apply_filters( 'cf7-add-password-field-features',$features );
	wpcf7_add_form_tag( array('password','password*'),
		'wpcf7_k_password_form_tag_handler',$features );
}

function wpcf7_k_password_form_tag_handler( $tag ) {
	if ( empty( $tag->name ) ) {
		return '';
	}

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-text' );
	
	$class .= ' wpcf7-validates-as-password';
		
	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$atts = array();

	$atts['size'] = $tag->get_size_option( '40' );
	$atts['maxlength'] = $tag->get_maxlength_option();
	$atts['minlength'] = $tag->get_minlength_option();

	if ( $atts['maxlength'] && $atts['minlength']
	&& $atts['maxlength'] < $atts['minlength'] ) {
		unset( $atts['maxlength'], $atts['minlength'] );
	}

	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

	$atts['autocomplete'] = $tag->get_option( 'autocomplete',
		'[-0-9a-zA-Z]+', true );

	$atts['password_strength'] = (int)$tag->get_option( 'password_strength', 'signed_int', true);
	$atts['password_check'] = $tag->get_option( 'password_check', '', true);
	$atts['specific_password_check'] = $tag->get_option( 'specific_password_check', '', true);
	$atts['hideIcon'] = $tag->has_option( 'hideIcon' );	

	if ( $tag->is_required() ) {
		$atts['aria-required'] = 'true';
	}

	if ( $validation_error ) {
		$atts['aria-invalid'] = 'true';
		$atts['aria-describedby'] = wpcf7_get_validation_error_reference(
			$tag->name
		);
	} else {
		$atts['aria-invalid'] = 'false';
	}
	
	$value = (string) reset( $tag->values );
	
	// Support placeholder. Reference: modules/date.php in the contact form 7 plugin.
	if ( $tag->has_option( 'placeholder' )
	or $tag->has_option( 'watermark' ) ) {
		$atts['placeholder'] = $value;
		$value = '';
	}
	
	$value = $tag->get_default_option( $value );

	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts['value'] = $value;

	if ( wpcf7_support_html5() ) {
		$atts['type'] = $tag->basetype;
	} else {
		$atts['type'] = 'password';
	}
	$atts['name'] = $tag->name;

	$atts = wpcf7_format_atts( $atts );

	$tag_id = $tag->get_id_option();
	if( empty($tag_id) ) $tag_id = $tag->name; // for the version 5.8 of Contact form 7: Contact form 7 ignores the id attribute if the same ID is already used for another element.

	if( $tag_id === $tag->name && !$tag->has_option( 'hideIcon' ) ){
 		$html = sprintf(
			'<span class="wpcf7-form-control-wrap" data-name="%1$s"><input %2$s />%3$s<span style="position: relative; margin-left: -30px;"  id="buttonEye-'. $tag_id .'" class="fa fa-eye-slash" onclick="pushHideButton(\''. $tag_id .'\')"></span></span>',
			sanitize_html_class( $tag->name ), $atts, $validation_error );
	}else{
		$html = sprintf(
			'<span class="wpcf7-form-control-wrap" data-name="%1$s"><input %2$s />%3$s</span>',
			sanitize_html_class( $tag->name ), $atts, $validation_error );
	}
	return $html;
}

function wpcf7_k_password_validation_filter( $result, $tag ) {
	$name = $tag->name;

	$value = isset( $_POST[$name] )
		? trim( wp_unslash( strtr( (string) $_POST[$name], "\n", " " ) ) )
		: '';

	$specific_password_check = $tag->get_option( 'specific_password_check', '', true);
	if(!empty($specific_password_check)){
		$value_pass_array = explode("_", str_replace(" ", "", $specific_password_check));
		$flag = false;
		foreach($value_pass_array as $each_value_pass){
			if($value === $each_value_pass ){
				$flag = true;
				 break;
			}
		}
		if( $flag === false){
			$result->invalidate($tag, __("Passwords do not match defined!", 'cf7-add-password-field' ));		
		}
	}

	$password_check = $tag->get_option( 'password_check', '', true);
	if(!empty($password_check)){
		if(isset( $_POST[$password_check] )){
			$value_pass = isset( $_POST[$password_check] )
		? trim( wp_unslash( strtr( (string) $_POST[$password_check], "\n", " " ) ) )
		: '';
			if($value !== $value_pass ){
					$result->invalidate($tag, __("Passwords do not match!", 'cf7-add-password-field' ));		
			}
		}
	}

	$password_strength = (int)$tag->get_option( 'password_strength','signed_int', true);

	if ($password_strength < 0){
		$password_strength = 0;
	}

	$pattern = preg_quote ($tag->get_option( 'pattern' ));

	if ( $tag->is_required() and '' === $value ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	}elseif ( '' !== $value ){
		$maxlength = $tag->get_maxlength_option();
		$minlength = $tag->get_minlength_option();
		if ( $maxlength and $minlength and $maxlength < $minlength ) {
			$maxlength = $minlength = null;
		}
		$code_units = wpcf7_count_code_units( $value );
		if ( false !== $code_units ) {
			if ( $maxlength and $maxlength < $code_units ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_too_long' ) );
			} elseif ( $minlength and $code_units < $minlength ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_too_short' ) );
			}
		}

		if ($password_strength > 0) {
			if($password_strength === 1){
				if(!preg_match("/^[0-9]+$/", $value)){
					$result->invalidate($tag, __("Please use the numbers only", 'cf7-add-password-field' ));
				}
			}elseif($password_strength === 2){
				if(!preg_match("/([0-9].*[a-z,A-Z])|([a-z,A-Z].*[0-9])/", $value) ){
					$result->invalidate($tag, __("Please include one or more letters and numbers.", 'cf7-add-password-field' ));
				}
			}elseif($password_strength === 3){
				if(!preg_match("/[0-9]/", $value) or
				 !preg_match("/([a-z].*[A-Z])|([A-Z].*[a-z])/", $value)){
					$result->invalidate($tag, __("Please include one or more upper and lower case letters and numbers.", 'cf7-add-password-field' ));
				}
			}elseif($password_strength === 4){
				if(!preg_match("/[0-9]/", $value) or
				 !preg_match("/([a-z].*[A-Z])|([A-Z].*[a-z])/", $value) or 
				 !preg_match("/([!,%,&,@,#,$,^,*,?,_,~])/", $value)){
					$result->invalidate($tag, __("Please include one or more upper and lower case letters, numbers, and marks.", 'cf7-add-password-field' ));
				}
			}
		}
	}

	return apply_filters('wpcf7_k_password_validation_filter', $result, $tag);
}

// Add Tag.
if ( is_admin() ) {
	add_action( 'wpcf7_admin_init' , 'wpcf7_k_password_add_tag_generator' , 55 );
}

function wpcf7_k_password_add_tag_generator( $contact_form , $args = '' ){
	if(!class_exists('WPCF7_TagGenerator')) {
		return false;
	}
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'password', __( 'Password', 'cf7-add-password-field' ),
		'wpcf7_k_password_pane_confirm', array( 'nameless' => 1 ) );
}

function wpcf7_k_password_pane_confirm( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$description = __( "Generate a form-tag for a password button.", 'cf7-add-password-field' );

?>
<div class="control-box">
	<fieldset>
		<legend><?php echo  esc_html( $description ); ?></legend>

		<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
						<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><label
					for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label>
				</th>
				<td><input type="text" name="name" class="oneline"
				           id="<?php echo esc_attr( $args['content'] . '-values' ); ?>"/></td>
			</tr>

			<tr>
				<th scope="row"><label
					for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label>
				</th>
				<td><input type="text" name="id" class="idvalue oneline option"
				           id="<?php echo esc_attr( $args['content'] . '-id' ); ?>"/></td>
			</tr>

			<tr>
				<th scope="row"><label
					for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label>
				</th>
				<td><input type="text" name="class" class="classvalue oneline option"
				           id="<?php echo esc_attr( $args['content'] . '-class' ); ?>"/></td>
			</tr>
			
			<tr>
				<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?></label></th>
				<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
	<label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html( __( 'Use this text as the placeholder of the field', 'contact-form-7' ) ); ?></label></td>
			</tr>
			<tr>
				<th scope="row"><label
					for="<?php echo esc_attr( $args['content'] . '-minlength' ); ?>"><?php echo esc_html( __( 'Password Length', 'cf7-add-password-field' ) ); ?></label>
				</th>
				<td> Min <input type="text" name="minlength" class="classvalue oneline option"
				           id="<?php echo esc_attr( $args['content'] . '-minlength' ); ?>"/><br/>
				           <?php echo esc_html( __( 'Required more than the specified number of characters the input.', 'cf7-add-password-field' ) ); ?><br/>
				           Max <input type="text" name="maxlength" class="classvalue oneline option"
				           id="<?php echo esc_attr( $args['content'] . '-maxlength' ); ?>"/><br/>
				           <?php echo esc_html( __( 'Required less than the specified number of characters the input.', 'cf7-add-password-field' ) ); ?></td>
			</tr>
			<tr>
				<th scope="row"><label
					for="<?php echo esc_attr( $args['content'] . '-password_strength' ); ?>"><?php echo esc_html( __( 'Password Strength', 'cf7-add-password-field' ) ); ?></label>
				</th>
				<td><input type="text" name="password_strength" class="classvalue oneline option"
				           id="<?php echo esc_attr( $args['content'] . '-password_strength' ); ?>" /><br/>
				           1 = <?php echo esc_html( __( 'Numbers only', 'cf7-add-password-field' ) ); ?><br/>
				           2 = <?php echo esc_html( __( 'Include letters and numbers', 'cf7-add-password-field' ) ); ?><br/>
				           3 = <?php echo esc_html( __( 'Include upper and lower case letters and numbers', 'cf7-add-password-field' ) ); ?><br/>
				           4 = <?php echo esc_html( __( 'Include upper and lower case letters, numbers, and marks', 'cf7-add-password-field' ) ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><label
					for="<?php echo esc_attr( $args['content'] . '-password_check' ); ?>"><?php echo esc_html( __( 'Password Check', 'cf7-add-password-field' ) ); ?></label>
				</th>
				<td><input type="text" name="password_check" class="classvalue oneline option"
				           id="<?php echo esc_attr( $args['content'] . '-password_check' ); ?>" /><br/>
				           <?php echo esc_html( __( 'Enter the value of the “name” on the field if you wish to verify a value of a password field. In case of verifying the password value that you set [password password-100], set [password* password-101 password_check:password-100].', 'cf7-add-password-field' ) ); ?><br/>
				</td>
			</tr>
			<tr>
				<th scope="row"><label
					for="<?php echo esc_attr( $args['content'] . '-specific_password_check' ); ?>"><?php echo esc_html( __( 'Specific Password Check', 'cf7-add-password-field' ) ); ?></label>
				</th>
				<td><input type="text" name="specific_password_check" class="classvalue oneline option"
				           id="<?php echo esc_attr( $args['content'] . '-specific_password_check' ); ?>". placeholder="password1_password2"/><br/>
				           <?php echo esc_html( __( ' Enter your password separated by underline(Passwords cannot contain underline and marks escaped by preg_quote are not allowed.). Check if it matches the password entered here. If you have set a password strength, the password set here should also follow that rule.', 'cf7-add-password-field' ) ); ?><br/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php echo esc_html( __( 'Hide Icon', 'contact-form-7' ) ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><?php echo esc_html( __( 'Hide Icon', 'contact-form-7' ) ); ?></legend>
						<label><input type="checkbox" name="hideIcon"  class="option" /> <?php echo esc_html( __( 'Hide the icon that shows the password', 'contact-form-7' ) ); ?></label>
					</fieldset>
				</td>
			</tr>
		</tbody>
		</table>
	</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="password" class="tag code" readonly="readonly" onfocus="this.select()"/>

	<div class="submitbox">
		<input type="button" class="button button-primary insert-tag"
		       value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>"/>
	</div>
</div>
<?php
}