<?php
function ppws_protected_categories() {
    $protected_categories = [];
    $hide_protected_cat_products = false;

    $ppws_product_categories_options = get_option('ppws_product_categories_settings');    

    if(isset($ppws_product_categories_options) && !empty($ppws_product_categories_options)) {

        $dfa_product_categories_protect_enable = (isset($ppws_product_categories_options["ppws_product_categories_enable_password_field_checkbox_for_admin"]) && $ppws_product_categories_options["ppws_product_categories_enable_password_field_checkbox_for_admin"] == "on") ? true : false;

        $hide_general_product_from_loop = (isset($ppws_product_categories_options['ppws_hide_products_checkbox_field_checkbox'])) ? $ppws_product_categories_options['ppws_hide_products_checkbox_field_checkbox']: '';

        if($hide_general_product_from_loop == 'on') {
            $enable_categories_password = (isset($ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox']) && !empty($ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox'])) ? $ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox']: '';
            if($enable_categories_password == 'on') {

                if (isset($ppws_product_categories_options['enable_user_role']) && !empty($ppws_product_categories_options['enable_user_role'])) {
                    if (isset($ppws_product_categories_options['ppws_product_categories_select_user_role_field_radio']) && "non-logged-in-user" === $ppws_product_categories_options['ppws_product_categories_select_user_role_field_radio'] 
                    && !is_user_logged_in()) {
                        $hide_protected_cat_products = true;
                    } elseif (isset($ppws_product_categories_options['ppws_product_categories_select_user_role_field_radio']) && "logged-in-user" === $ppws_product_categories_options['ppws_product_categories_select_user_role_field_radio'] && is_user_logged_in()) {

                        if (isset($ppws_product_categories_options['ppws_product_categories_logged_in_user_field_checkbox'])) {
                            $current_user = wp_get_current_user();
                            $current_user_role = $current_user->roles;
                            $final = ucfirst(str_replace("_", " ", array_shift($current_user_role)));

                            if (isset($ppws_product_categories_options['ppws_product_categories_logged_in_user_field_checkbox']) && !empty($ppws_product_categories_options['ppws_product_categories_logged_in_user_field_checkbox'])) {
                                $selected_user = explode(",", $ppws_product_categories_options['ppws_product_categories_logged_in_user_field_checkbox']);
                            
                                if (current_user_can('administrator') && $dfa_product_categories_protect_enable) {
                                    array_push($selected_user, 'Administrator');
                                }
                                if (in_array(ucfirst($final), $selected_user)) {
                                    $hide_protected_cat_products = true;
                                }
                            }
                        } else {
                            if (current_user_can('administrator') && $dfa_product_categories_protect_enable) {
                                $hide_protected_cat_products = true;
                            }
                        }

                    }
                }else{
                    if (current_user_can('administrator')) {
                        if(!$dfa_product_categories_protect_enable) {
                            $hide_protected_cat_products = true;
                        }
                    }else{
                        $hide_protected_cat_products = true;
                    }
                }

            }
        }
    }

    if($hide_protected_cat_products) {
        // get categories_setting cookie
        $ppws_categories_cookie = ppws_get_cookie('ppws_categories_cookie');
        $ppws_categories_main_password = $ppws_product_categories_options['ppws_product_categories_password'];

        if(ppws_decrypted_password($ppws_categories_cookie) != ppws_decrypted_password($ppws_categories_main_password)) {
            $protected_categories = (isset($ppws_product_categories_options['ppws_product_categories_all_categories_field_checkbox']) && !empty($ppws_product_categories_options['ppws_product_categories_all_categories_field_checkbox'])) ? $ppws_product_categories_options['ppws_product_categories_all_categories_field_checkbox'] : array();
        }
    }

    return $protected_categories;
}