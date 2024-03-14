<?php
if ( defined( 'ABSPATH' ) ) { ?>
<aside class="widget widget_meta">
	<h2 class="widget-title"><?php esc_html_e( $title ) ?></h2>
	<p><?php esc_html_e( $description ) ?></p>
	<form action="<?php echo get_site_url() . '/index.php?my-plugin=sarbacane' ?>"
		  method="POST"
		  id="sarbacane_desktop_widget_form_<?php echo $list_type . $rand ?>"
		  autocomplete="off"
		  onsubmit="return sarbacaneSubmitWidget( '<?php echo $list_type . $rand ?>' )">
		<?php foreach ( $fields as $field ) {
			if ( !isset( $field->placeholder ) ) {
				$field->placeholder = '';
			}
			if ( strtolower( $field->label ) == 'email' ) {
				$label = esc_html( __( 'Email', 'sarbacane-desktop' ) );
				$field_type = 'email';
				$name = 'email';
				$id = 'email' . '_' . $list_type . $rand;
			} else {
				$label = esc_html( $field->label );
				$field_type = 'text';
				$name = esc_attr( $field->label );
				$id = esc_attr( $field->label ) . '_' . $list_type . $rand;
			}
			?>
			<p>
				<label><?php echo $label ?><?php if ( $field->mandatory ) { ?> *<?php } ?></label>
				<br/>
				<input<?php if ( $field->mandatory ) { ?> required class="required"<?php } ?>
					   type="<?php echo $field_type ?>"
					   id="<?php echo $id ?>"
					   placeholder="<?php esc_attr_e( $field->placeholder ) ?>"
					   name="<?php echo $name ?>"/>
			</p>
		<?php } ?>
		<p>
			<?php esc_html_e( $registration_mandatory_fields ) ?>
			<?php if ($registration_legal_notices_mentions != '' && $registration_legal_notices_url != '') { ?>
			<br/>
			<a style="font-size: 0.9em" href="<?php esc_attr_e( $registration_legal_notices_url ) ?>" target="_blank">
				<?php esc_html_e( $registration_legal_notices_mentions ) ?>
			</a>
			<?php } ?>
		</p>
		<?php wp_nonce_field( 'newsletter_registration', 'sarbacane_form_token' ) ?>
		<input type="hidden" name="sarbacane_form_value" class="sarbacane_form_value" value=""/>
		<input type="submit" value="<?php esc_attr_e( $registration_button ) ?>"/>
	</form>
</aside>
<?php } ?>
