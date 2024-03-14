<?php
/**
 * check status of whole site protection
 */
function ppws_is_protected_whole_site() {
    $ppws_whole_site_options = get_option('ppws_general_settings');

    // Get the status of password protection for admin users.
    $dfa_general_settings_protection = (isset($ppws_whole_site_options['ppws_enable_password_field_checkbox_for_admin']) && $ppws_whole_site_options['ppws_enable_password_field_checkbox_for_admin'] == 'on') ? true : false;

    if(current_user_can( 'administrator' ) && $dfa_general_settings_protection) {
        return false;
    }    

    // Check if the site options exist and are not empty.
    if (isset($ppws_whole_site_options) && !empty($ppws_whole_site_options)) {

        // Check if password protection is enabled for the whole site.
        if (isset($ppws_whole_site_options['ppws_enable_password_field_checkbox']) && 'on' === $ppws_whole_site_options['ppws_enable_password_field_checkbox']) {

            // Check if user roles are enabled.
            $enable_user_role = (isset($ppws_whole_site_options['enable_user_role']) && 'on' === $ppws_whole_site_options['enable_user_role']) ? true : false;
            $general_setting_user_type = (isset($ppws_whole_site_options['ppws_select_user_role_field_radio']) && !empty($ppws_whole_site_options['ppws_select_user_role_field_radio'])) ? $ppws_whole_site_options['ppws_select_user_role_field_radio'] : false;

            if ($enable_user_role) {
                if($general_setting_user_type) {
                    // Check if the site is accessible for non-logged-in users.
                    if ("non-logged-in-user" === $general_setting_user_type && !is_user_logged_in()) {
                        return true;
                    } else {
                        // Check if the site is accessible for logged-in users based on their roles.
                        if ("logged-in-user" === $general_setting_user_type && is_user_logged_in()) {
                            $current_user = wp_get_current_user();
                            $current_user_role = $current_user->roles;
                            $final = ucfirst(str_replace("_", " ", array_shift($current_user_role)));

                            $selected_user_roles = (isset($ppws_whole_site_options['ppws_logged_in_user_field_checkbox']) && !empty($ppws_whole_site_options['ppws_logged_in_user_field_checkbox'])) ? $ppws_whole_site_options['ppws_logged_in_user_field_checkbox'] : false;
                            
                            // Check if the user roles is selected or not.
                            if($selected_user_roles){
                                $selected_user     = $selected_user_roles ? explode(",", $selected_user_roles) : array();
                                
                                
                                // Add "Administrator" role to the selected user roles if admin bypass is disabled.
                                if (!$dfa_general_settings_protection && current_user_can('administrator')) {
                                    array_push($selected_user, 'Administrator');
                                }
                                
                                // Check if the current user role is allowed to access the product or the user is not logged in.
                                if (in_array(ucfirst($final), $selected_user) || !is_user_logged_in()) {      
                                    // Product is accessible for logged-in users based on their roles.
                                    return true;
                                }
                            }
                        }
                    }
                }
            }else{
                return true;
            }
        }
    }

    return false;
}

/**
 * check status of product categories protection
 */
function ppws_is_protected_product_categories() {
    global $wp_query, $product;
    $ppws_product_categories_options = get_option('ppws_product_categories_settings');

    $dfa_product_categories_protect     = (isset($ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox_for_admin']) && $ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox_for_admin'] == 'on') ? true : false;

    if(current_user_can( 'administrator' ) && $dfa_product_categories_protect) {
        return false;
    }
    
    // Check if the product categories options exist and are not empty.
    if (isset($ppws_product_categories_options) && !empty($ppws_product_categories_options)) {

        // Check if password protection is enabled for product categories.
        if (isset($ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox']) && 'on' === $ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox']) {

            $all_selected_category = (isset($ppws_product_categories_options['ppws_product_categories_all_categories_field_checkbox']) && !empty($ppws_product_categories_options['ppws_product_categories_all_categories_field_checkbox'])) ? explode(",", $ppws_product_categories_options['ppws_product_categories_all_categories_field_checkbox']) : array();

            // Check if password protection is enabled for categories.
            if (isset($all_selected_category) && !empty($all_selected_category)) {                

                // Get the status of password protection for admin users.
                $ppws_password_status_for_admin = isset($ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox_for_admin']) ? $ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox_for_admin'] : 'off';

                
                $flag_single_product = 0;
                if (is_product()) {
                    // Get the product object
                    $product_obj = get_page_by_path($product, OBJECT, 'product');
                    $product_id = $product_obj->ID;
                    
                    $all_cat_id = array();
                    if (isset($all_selected_category) && !empty($all_selected_category)) {
                        foreach ($all_selected_category as $key => $cat_id) {
                            array_push($all_cat_id, $cat_id);
                        }
                    }
                    if (has_term($all_cat_id, 'product_cat', $product_id)) {
                        $flag_single_product = 1;
                    }
                }
                if (is_product_category()) {
                    $protect_genereal_archive_page = (isset($ppws_product_categories_options['ppws_protect_archive_checkbox_field_checkbox'])) ? $ppws_product_categories_options['ppws_protect_archive_checkbox_field_checkbox'] : 'on';
                    if($protect_genereal_archive_page == 'on') {
                        $woo_category = $wp_query->get_queried_object()->term_id;
                        if (in_array($woo_category, $all_selected_category)) {
                            $flag_single_product = 1;
                        }
                    }
                }

                if ($flag_single_product == 1) {
                    if (isset($ppws_product_categories_options['enable_user_role']) && !empty($ppws_product_categories_options['enable_user_role'])) {
                        if (isset($ppws_product_categories_options['ppws_product_categories_select_user_role_field_radio']) && "non-logged-in-user" === $ppws_product_categories_options['ppws_product_categories_select_user_role_field_radio'] 
                        && !is_user_logged_in()) {
                            return true;
                        } elseif (isset($ppws_product_categories_options['ppws_product_categories_select_user_role_field_radio']) && "logged-in-user" === $ppws_product_categories_options['ppws_product_categories_select_user_role_field_radio'] && is_user_logged_in()) {

                            if (isset($ppws_product_categories_options['ppws_product_categories_logged_in_user_field_checkbox'])) {
                                $current_user = wp_get_current_user();
                                $current_user_role = $current_user->roles;
                                $final = ucfirst(str_replace("_", " ", array_shift($current_user_role)));
    
                                if (isset($ppws_product_categories_options['ppws_product_categories_logged_in_user_field_checkbox']) && !empty($ppws_product_categories_options['ppws_product_categories_logged_in_user_field_checkbox'])) {
                                    $selected_user = explode(",", $ppws_product_categories_options['ppws_product_categories_logged_in_user_field_checkbox']);
                                  
                                    if (current_user_can('administrator') && $ppws_password_status_for_admin != 'on') {
                                        array_push($selected_user, 'Administrator');
                                    }
                                    if (in_array(ucfirst($final), $selected_user)) {
                                        return true;
                                    }
                                }
                            } else {
                                if (current_user_can('administrator') && $ppws_password_status_for_admin != 'on') {
                                    return true;
                                }
                            }

                        }
                    } else {
                        // Check if the current user is an administrator and admin bypass is disabled.
                        if (current_user_can('administrator')) {
                            if ('on' !== $ppws_password_status_for_admin) {
                                return true;
                            }
                        } else {
                            return true;
                        } 
                    }
                }
            }
        } 
    }

    return false;
}

/**
 * check status of protected page protection
 */
function ppws_is_protected_page() {
    // Get page options from settings
    $ppws_page_options = get_option('ppws_page_settings');

    $dfa_page_protect     = (isset($ppws_page_options['ppws_page_enable_password_field_checkbox_for_admin']) && $ppws_page_options['ppws_page_enable_password_field_checkbox_for_admin'] == 'on') ? true : false;

    if(current_user_can( 'administrator' ) && $dfa_page_protect) {
        return false;
    }
    
    // Check if the page options exist and password protection is enabled.
    if (isset($ppws_page_options['ppws_page_enable_password_field_checkbox']) && $ppws_page_options['ppws_page_enable_password_field_checkbox'] === 'on') {

        // Get selected protected pages.
        $selected_pages = (isset($ppws_page_options['ppws_page_list_of_page_field_checkbox']) && !empty($ppws_page_options['ppws_page_list_of_page_field_checkbox'])) ? explode(",", $ppws_page_options['ppws_page_list_of_page_field_checkbox']) : array();

        if(isset($selected_pages) && !empty($selected_pages)) {
        
            // Get the status of password protection for admin users.
            $ppws_page_status_for_admin = isset($ppws_page_options['ppws_page_enable_password_field_checkbox_for_admin']) ? $ppws_page_options['ppws_page_enable_password_field_checkbox_for_admin'] : 'off';
            
            // Get the current page ID.
            if (is_home() && !in_the_loop()) {
                $page_id = get_option('page_for_posts');
            } elseif (is_post_type_archive('product')) {
                $page_id = get_option('woocommerce_shop_page_id'); 
            } else { 
                $page_id = get_the_ID();
            }
            
            // Get the current page name.
            $page_name = get_the_title();
            
            // Check if the current page ID is in the selected protected pages.
            if (in_array($page_id, $selected_pages)) {
                if (isset($ppws_page_options['enable_user_role'])) {
                    if (isset($ppws_page_options['ppws_page_select_user_role_field_radio'])) {
                        // Check if the page is accessible for non-logged-in users.
                        if ("non-logged-in-user" === $ppws_page_options['ppws_page_select_user_role_field_radio'] && !is_user_logged_in()) {
                            return true;
                        } elseif ("logged-in-user" === $ppws_page_options['ppws_page_select_user_role_field_radio'] && is_user_logged_in()) {
                            // Check if the page is accessible for logged-in users based on their roles.
                            $current_user = wp_get_current_user();
                            $current_user_role = $current_user->roles;
                            $final = ucfirst(str_replace("_", " ", array_shift($current_user_role)));
        
                            $selected_user = isset($ppws_page_options['ppws_page_logged_in_user_field_checkbox']) ? explode(",", $ppws_page_options['ppws_page_logged_in_user_field_checkbox']) : array();
                                
                            if (current_user_can('administrator') && $ppws_page_status_for_admin != 'on') {
                                array_push($selected_user, 'Administrator');
                            }

                            if (in_array(ucfirst($final), $selected_user) || !is_user_logged_in()) {
                                return true;
                            }
                        }
                    } else {
                        // Check if the current user is an administrator and admin bypass is disabled.
                        if (current_user_can('administrator') && $ppws_page_status_for_admin != 'on') {
                            return true;
                        }
                    }

                } else {
                    // Check if the current user is an administrator and admin bypass is disabled.
                    if (current_user_can('administrator')) {
                        if ('on' !== $ppws_page_status_for_admin) {
                            return true;
                        }
                    } else {
                        return true;
                    } 
                }

            }
        }
    }

    // Return false if password protection is not enabled or no options are found.
    return false;
}

/**
 * Nocache headers
 */
function ppws_nocache_headers() {
    // Set headers to prevent caching
    nocache_headers();
       
    // Set constants to prevent caching in certain caching plugins
    if ( ! defined( 'DONOTCACHEPAGE' ) ) {
        define( 'DONOTCACHEPAGE', true );
    }
    if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
        define( 'DONOTCACHEOBJECT', true );
    }
    if ( ! defined( 'DONOTCACHEDB' ) ) {
        define( 'DONOTCACHEDB', true );
    }
}

/**
 * No index for Protected Page
 */
function ppws_prevent_indexing() {
    // noindex this page - we add X-Robots-Tag header and set meta robots
    if ( ! headers_sent() ) {
        header( 'X-Robots-Tag: noindex, nofollow' );
    }
}