<?php

class Youzify_Profile_Info_Box_Widget {

    /**
     * Content.
     */
    function widget( $args ) {

        // Get Field  Id.
        $field_id = youzify_option( $args['option_id'] );

        if ( ! empty( $field_id ) && bp_is_active( 'xprofile' ) ) {

            // Get Hidden Fields.
            $hidden_fields = bp_xprofile_get_hidden_fields_for_user();

            if ( in_array( $field_id, $hidden_fields ) )  {
                return false;
            }

            global $field;

            // Get Field Data.
            $field = new BP_XProfile_Field( $field_id );

            // Get Field Value.
            $value = maybe_unserialize( BP_XProfile_ProfileData::get_value_byid( $field_id, bp_displayed_user_id() ) );

            // Get Field Title.
            $title = apply_filters( 'bp_get_the_profile_field_name', $field->name );

        } else {

            // Get Field Title.
            $title = $args['box_title'];

            // Get Field Value.
            $value = youzify_get_xprofile_field_value( $args['box_id'] );

        }

        // Hide Box if there's no content.
        if ( empty( $value ) ) {
            return false;
        }

        youzify_styling()->gradient_styling( array(
            'selector'      => '.youzify-box-' . $args['box_class'],
            'left_color'    => 'youzify_ibox_' . $args['box_class'] . '_bg_left',
            'right_color'   => 'youzify_ibox_' . $args['box_class'] . '_bg_right'
        ) );

		?>

		<div class="youzify-infobox-content <?php echo "youzify-box-" . $args['box_class']; ?>">
			<div class="youzify-box-head">
				<div class="youzify-box-icon">
					<i class="<?php echo $args['box_icon']; ?>"></i>
				</div>
				<h2 class="youzify-box-title"><?php echo $title; ?></h2>
			</div>
			<div class="youzify-box-content">
				<p><?php echo apply_filters( 'bp_get_profile_field_data', make_clickable( $value ) ); ?></p>
			</div>
		</div>

		<?php

	}

}