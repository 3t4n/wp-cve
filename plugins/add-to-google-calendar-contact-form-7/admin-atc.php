<?php

// ADD NEW EDITOR PANEL
add_filter( 'wpcf7_editor_panels', 'atccf7_editor' );

function atccf7_editor( $panels ) {
	$panels['editor-atc'] = array(
		'title'    => __( 'Add to Calendar', 'add-to-google-calendar-contact-form-7' ),
		'callback' => 'atccf7_editor_panel',
	);
	return $panels;
}



function atccf7_editor_panel( $form ) {
	if ( ! $form->id() ) {
		echo '<span class="advise">' . __( 'Please first create and save your form. ', 'add-to-google-clendar-contact-form-7') . '</span>';
	} else {
		atccf7_render_editor( $form );
	}
}

function atccf7_render_editor( $form ) {

	$postmeta_temp = get_post_meta( $form->id(), 'atccf7_options_form', true );
	$postmeta_atc = ( $postmeta_temp !== '' ? $postmeta_temp : false );

	if ( is_array( $postmeta_atc ) && count( $postmeta_atc ) < 1 ) {
		$atc_on = '';
		$date_1 = '';
		$date_2 = '';
		$event_name = '';
		$specific_date = '';
	} else {
		
		$atc_on = ( isset( $postmeta_atc['atc_on'] ) ? $postmeta_atc['atc_on'] : false );
		$date_1 = ( isset( $postmeta_atc['date_1'] ) ? $postmeta_atc['date_1'] : '' );
		$date_2 = ( isset( $postmeta_atc['date_2'] ) ? $postmeta_atc['date_2'] : '' );
		$event_name = ( isset( $postmeta_atc['event_name'] ) ? $postmeta_atc['event_name'] : '' );
		$specific_date = ( isset( $postmeta_atc['specific_date'] ) ? $postmeta_atc['specific_date'] : '' );
		$event_description = ( isset( $postmeta_atc['event_description'] ) ? $postmeta_atc['event_description'] : '' );
		$event_location = ( isset( $postmeta_atc['event_location'] ) ? $postmeta_atc['event_location'] : '' );
		$event_btn = ( isset( $postmeta_atc['event_btn'] ) ? $postmeta_atc['event_btn'] : '' );
		$color_btn = ( isset( $postmeta_atc['color_btn'] ) ? $postmeta_atc['color_btn'] : '' );
		$color_text = ( isset( $postmeta_atc['color_text'] ) ? $postmeta_atc['color_text'] : '' );
	}

	$actual_fields = $form->scan_form_tags();
	if ( count( $actual_fields ) > 0 ) {
		$options_fields = array();
		for ( $i = 0, $length_fields = count( $actual_fields ); $i < $length_fields; $i++ ) {

			$field = $actual_fields[$i];

			if ( $field['basetype'] != 'date' && $field['basetype'] != 'text' ) {
				continue;
			}
			array_push( $options_fields, $field['name'] );
		}
	}

	?>
	<div class="atc-container">
		<h3><?php echo esc_html__( 'Add to Calendar Options', 'add-to-google-calendar-contact-form-7' ); ?></h3>
		<input type="checkbox" id="atc_on" name="atc_on" value="1" <?php if ( $atc_on ) echo 'checked'; ?>>
		<label for="atc_on"><?php echo esc_html__( 'Activate Add to Calendar Button', 'add-to-google-calendar-contact-form-7' ); ?></label>
		<hr>
		<div class="choose-date">
			<p><?php echo esc_html__( 'Choose a specific Date', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<span class="advise"><?php echo esc_html__( '(You can specify the date instead use date fields. If you want to use date fields bellow, please leave this blank).', 'add-to-google-calendar-contact-form-7' ); ?></span>
			<br>
			<input type="date" value="<?php echo esc_attr( $specific_date ); ?>"  id="specific_date" name="specific_date">

		</div>
		<hr>	
		<div class="select-date-1">
			<p><?php echo esc_html__( 'Select Date field 1 (Start date)', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<?php
			atccf7_render_options_fields( $options_fields, 1, $date_1 );
			?>
		</div>
		<div class="select-date-2">
			<p><?php echo esc_html__( 'Select Date field 2 (End date)', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<span class="advise"><?php echo esc_html__( '(If you are using only one date field, please leave this blank).', 'add-to-google-calendar-contact-form-7' ); ?></span>
			<br>
			<?php
			atccf7_render_options_fields( $options_fields, 2, $date_2 );
			?>
		</div>
		<hr>
		<div class="event_name">
			<p><?php echo esc_html__( 'Event Name', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<input type="text" name="event_name" value="<?php echo esc_attr( $event_name ); ?>" placeholder="<?php echo esc_attr__( 'Event Name (on calendar)', 'add-to-google-calendar-contact-form-7' ); ?>"/>
		</div>
		<div class="event_description">
			<p><?php echo esc_html__( 'Event Description', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<input type="text" name="event_description" value="<?php echo esc_attr( $event_description ); ?>" placeholder="<?php echo esc_attr__( 'Event Description (on calendar)', 'add-to-google-calendar-contact-form-7' ); ?>"/>
		</div>
		<div class="event_location">
			<p><?php echo esc_html__( 'Event Location', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<input type="text" name="event_location" value="<?php echo esc_attr( $event_location ); ?>" placeholder="<?php echo esc_attr__( 'Event Location (on calendar)', 'add-to-google-calendar-contact-form-7' ); ?>"/>
		</div>
		<div class="btn_text">
			<p><?php echo esc_html__( 'Button Text', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<input type="text" name="event_btn" value="<?php echo esc_attr( $event_btn ); ?>" placeholder="<?php echo esc_attr__( 'Button Text', 'add-to-google-calendar-contact-form-7' ); ?>"/>
		</div>
		<div class="color_btn">
			<p><?php echo esc_html__( 'Button Color', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<input type="text" name="color_btn" value="<?php echo esc_attr( $color_btn ); ?>" placeholder="<?php echo esc_attr__( 'Button Color', 'add-to-google-calendar-contact-form-7' ); ?>"/>
		</div>
		<div class="color_text">
			<p><?php echo esc_html__( 'Text Color', 'add-to-google-calendar-contact-form-7' ); ?></p>
			<input type="text" name="color_text" value="<?php echo esc_attr( $color_text ); ?>" placeholder="<?php echo esc_attr__( 'Text Color', 'add-to-google-calendar-contact-form-7' ); ?>"/>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/tinyColorPicker/1.1.1/jqColorPicker.min.js"></script>
		<script>
    		jQuery('[name="color_btn"]').colorPicker();
    		jQuery('[name="color_text"]').colorPicker();
		</script>
	</div>
	<?php
}

function atccf7_render_options_fields( $options_fields, $x, $date_postmeta ) {

	// check if there are more than zero date field.
	if ( count( $options_fields ) < 1 ) {
		echo '<span class="advise">' . esc_html__( 'There are not date/text fields yet! Please create them first.', 'add-to-google-calendar-contact-form-7' ) . '<span>';
		return;
	}
	// check if there are more than one date field.
	if ( $x === 2 && count( $options_fields ) < 2 ) {
		echo '<span class="advise">' . esc_html__( 'There is only one date/text field.', 'add-to-google-calendar-contact-form-7' ) . '</span>';
		return;
	}
	$select = '<select name="date_' . $x . '">';
	$select .= '<option disabled selected>' . esc_html__( 'Select a date/text field.', 'add-to-google-calendar-contact-form-7' ) . '</option>';
	foreach ( $options_fields as $field ) {
		$selected = '';
		if ( $field == $date_postmeta )
			$selected = 'selected';
			$select .= '<option value="' . $field . '" ' . $selected . '>' . $field . '</option>';
	}
	$select .= '</select>';
	echo $select;
}

// when contact form is saved.
add_action( 'wpcf7_save_contact_form', 'atccf7_save_dataform' );

function atccf7_save_dataform( $form ) {

	$post_id = $form->id();
	if ( ! $post_id ) return;

	$atc_on = 1;

	if ( ! isset( $_POST['atc_on'] ) ) {
		$atc_on = 0;
	}

	$atccf7_options_form = array( 
		'atc_on'            => $atc_on, 
		'event_name'        => sanitize_text_field( $_POST['event_name'] ),
		'event_description' => sanitize_text_field( $_POST['event_description'] ),
		'event_location'    => sanitize_text_field( $_POST['event_location'] ),
		'event_btn'         => sanitize_text_field( $_POST['event_btn'] ),
		'color_btn'			=> sanitize_text_field( $_POST['color_btn'] ),
		'color_text'		=> sanitize_text_field( $_POST['color_text'] ),
	);

	if( isset ( $_POST['specific_date'] ) ) {
		$atccf7_options_form['specific_date'] = sanitize_text_field( $_POST['specific_date'] );
	}
	if ( isset ( $_POST['date_1'] ) ) {
		$atccf7_options_form['date_1'] = sanitize_text_field( $_POST['date_1'] );
	} 

	if ( isset( $_POST['date_2'] ) ) {
		$atccf7_options_form['date_2'] = sanitize_text_field( $_POST['date_2'] );
	} 

	update_post_meta( $post_id, 'atccf7_options_form', $atccf7_options_form );

	return;

};




