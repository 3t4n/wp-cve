<?php

/**
 * Replace Member Type Field Value
 */
add_filter( 'bp_get_the_profile_field_value', 'youzify_replace_member_type_field_value', 0, 3 );

function youzify_replace_member_type_field_value( $values, $field_type, $user_id ) {

    if ( $field_type == 'member_types' ) {
        return youzify_get_user_member_type_singular( $values );
    }

    return $values;
}

/**
 * Get User Member Type By ID.
 */
function youzify_get_user_member_type_singular( $default_types = null ) {

    // Collect Member Types.
    $member_types = explode( ', ', $default_types );

    foreach ( $member_types as $member_type ) {

        // Get Member Type.
        $type = bp_get_member_type_object( $member_type );

        // Change Member Type Name.
        if ( isset( $type->labels['singular_name'] ) ) {
            $default_types = str_replace( $member_type, $type->labels['singular_name'], $default_types );
        }

    }

    return apply_filters( 'youzify_member_types_get_user_member_type_singular', $default_types  );
}

/**
 * Get Xprofile fields by field type.
 */
function youzify_get_xprofile_member_types_field_id() {

    $id = wp_cache_get( 'member_types_field_id', 'bp_xprofile' );

    if ( false === $id ) {

        global $wpdb;

        // Get Fields Table Name.
        $bp = buddypress();

        $table_name = isset( $bp->profile->table_name_fields ) ? $bp->profile->table_name_fields : $wpdb->prefix . 'bp_xprofile_fields';

        // Get Fields ID'S.
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table_name WHERE type = %s", 'member_types' ) );

        wp_cache_set( 'member_types_field_id', $id, 'bp_xprofile' );
    }

    return absint( $id );
}

/**
 * Convert Member Type Values from ID to Singular Name.
 */
add_filter( 'youzify_get_user_field_data', 'youzify_replace_member_types_with_singular_name', 10, 2 );

function youzify_replace_member_types_with_singular_name( $value, $field_id ) {

	if ( $field_id == youzify_get_xprofile_member_types_field_id() ) {

		return youzify_get_user_member_type_singular( $value );

	}

	return $value;
}

/**
 * Set Members Directory Member Type Custom Meta
 */
add_filter( 'youzify_author_box_header_meta', 'youzify_set_members_directory_custom_meta', 10, 5 );
add_filter( 'youzify_members_directory_user_meta', 'youzify_set_members_directory_custom_meta', 10, 5 );
function youzify_set_members_directory_custom_meta( $meta_html, $meta_icon, $meta_value, $field_id, $user_id ) {

    if ( $field_id == youzify_get_xprofile_member_types_field_id() ) {

        $meta_html = '';

        // Get User Types.
        $user_member_types = bp_get_member_type( $user_id, false );

        if ( $user_member_types ) {

            $types = bp_get_member_types( array( 'show_in_list' => true ),  'objects' );

            if ( ! empty( $types ) ) {
                foreach ( $types as $type ) {
                    if ( $type->show_in_list && in_array( $type->name, $user_member_types ) ) {
                        $meta_html .= '<span class="youzify-meta-item"><i class="' . get_term_meta( $type->db_id, 'youzify_type_icon', true ) . '"></i>' . bp_get_member_type_directory_link( $type->name ). '</span>';
                    }
                }

            }

        }
    }

    return $meta_html;
}