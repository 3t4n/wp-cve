/**
 * Validate that the value is a simple input
 *
 * @since    1.0.0
 * @param    str value_to_validate
 * @return   bool
 */
window.church_tithe_wp_validate_simple_input = function church_tithe_wp_validate_simple_input( value_to_validate ) {

    if ( ! value_to_validate ) {
        return true;
    }

    if (
        typeof value_to_validate === 'string' ||
        typeof value_to_validate === 'number'
    ) {
        return true;
    }

    return false;
}

/**
 * Validate that the value is an image file
 *
 * @since    1.0.0
 * @param    str value_to_validate
 * @return   bool
 */
window.church_tithe_wp_validate_image_upload = function church_tithe_wp_validate_image_upload( value_to_validate ) {

    if ( ! value_to_validate ) {
        return true;
    }

    // Validate the type of file
    if (
        'image/jpeg' == value_to_validate.type ||
        'image/png' == value_to_validate.type
    ) {
        return true;
    }

    return false;
}
