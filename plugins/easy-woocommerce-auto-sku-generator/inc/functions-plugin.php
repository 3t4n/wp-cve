<?php
/**
 * Get variation separator value.
 *
 * @return string The variation separator value.
 */
function get_variation_separator_value() {
    // Get the value of "Enable variant settings" option.
    $variant_settings_enabled = get_option('skuautoffxf_variation_settings');

    // Get the value of "Variation Separator" option.
    $variation_separator = get_option('skuautoffxf_variation_separator');

    // Check if both options have values.
    if (!empty($variant_settings_enabled) && !empty($variation_separator)) {
        // If both values exist, return the value of "Variation Separator".
        return $variation_separator;
    } else {
        // If at least one value is missing, return the default separator "-".
        return '-';
    }
}