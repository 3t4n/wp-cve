<?php
if (!class_exists('ppws_product_categories_settings')) {
    $ppws_product_categories_options = get_option('ppws_product_categories_settings');
    $ppws_whole_site_options = get_option('ppws_general_settings');
    class ppws_product_categories_settings
    {
        public function __construct()
        {
            add_action('admin_init', array($this, 'ppws_product_categories_register_settings_init'));
        }

        function ppws_product_categories_callback()
        {
            global $ppws_whole_site_options;
            global $ppws_product_categories_options; ?>
            <form action="options.php?tab=product-categories" method="post" class="ppws-procat-setting-form">
                <?php 
                settings_fields('ppws-settings-options');
                $custom_class =  isset($ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox']) == 'on' ? ' ' : 'ppws-hide-section' ;
                $custom_class2 = isset($ppws_product_categories_options['enable_user_role']) == 'on' ? ' ' : 'none-redio-click' ;
                echo '<p class="ppws-note ppws-note-info '.$custom_class.'">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18px" height="18px" x="0" y="0" viewBox="0 0 23.625 23.625" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                <path style="" d="M11.812,0C5.289,0,0,5.289,0,11.812s5.289,11.813,11.812,11.813s11.813-5.29,11.813-11.813   S18.335,0,11.812,0z M14.271,18.307c-0.608,0.24-1.092,0.422-1.455,0.548c-0.362,0.126-0.783,0.189-1.262,0.189   c-0.736,0-1.309-0.18-1.717-0.539s-0.611-0.814-0.611-1.367c0-0.215,0.015-0.435,0.045-0.659c0.031-0.224,0.08-0.476,0.147-0.759   l0.761-2.688c0.067-0.258,0.125-0.503,0.171-0.731c0.046-0.23,0.068-0.441,0.068-0.633c0-0.342-0.071-0.582-0.212-0.717   c-0.143-0.135-0.412-0.201-0.813-0.201c-0.196,0-0.398,0.029-0.605,0.09c-0.205,0.063-0.383,0.12-0.529,0.176l0.201-0.828   c0.498-0.203,0.975-0.377,1.43-0.521c0.455-0.146,0.885-0.218,1.29-0.218c0.731,0,1.295,0.178,1.692,0.53   c0.395,0.353,0.594,0.812,0.594,1.376c0,0.117-0.014,0.323-0.041,0.617c-0.027,0.295-0.078,0.564-0.152,0.811l-0.757,2.68   c-0.062,0.215-0.117,0.461-0.167,0.736c-0.049,0.275-0.073,0.485-0.073,0.626c0,0.356,0.079,0.599,0.239,0.728   c0.158,0.129,0.435,0.194,0.827,0.194c0.185,0,0.392-0.033,0.626-0.097c0.232-0.064,0.4-0.121,0.506-0.17L14.271,18.307z    M14.137,7.429c-0.353,0.328-0.778,0.492-1.275,0.492c-0.496,0-0.924-0.164-1.28-0.492c-0.354-0.328-0.533-0.727-0.533-1.193   c0-0.465,0.18-0.865,0.533-1.196c0.356-0.332,0.784-0.497,1.28-0.497c0.497,0,0.923,0.165,1.275,0.497   c0.353,0.331,0.53,0.731,0.53,1.196C14.667,6.703,14.49,7.101,14.137,7.429z" fill="#030104" data-original="#030104" class=""></path></svg> ';
                echo _e('The "Product Categories" password will be applied to products of selected categories. If both the "Page" and "General" passwords are enabled, then the "General" password will not be applicable to the products of selected category.','password-protected-store-for-woocommerce');
                echo '</p>'; 
                ?>
                <div class="ppws-section">
                    <?php do_settings_sections('ppws-product-categories-password-settings-section'); ?>
                </div>
                <div class="ppws-section ppws-section-user <?php echo $custom_class.' '.$custom_class2; ?>">
                    <?php do_settings_sections('ppws-product-categories-user-role-section'); ?>
                </div>
                <div class="ppws-submit-btn">

                    <?php submit_button('Save Setting'); ?>
                </div>
            </form>
            <?php
        }

        public function ppws_product_categories_register_settings_init()
        {
            register_setting(
                'ppws-settings-options',
                'ppws_product_categories_settings',
                array($this, 'sanitize_settings')
            );

            /* Password Settings Start */
            add_settings_section(
                'ppws_product_categories_password_settings_section',
                __('Password Settings', 'password-protected-store-for-woocommerce'),
                array(),
                'ppws-product-categories-password-settings-section'
            );

            add_settings_field(
                'ppws_product_categories_enable_password_checkbox',
                __('Enable Password', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_password_settings'),
                'ppws-product-categories-password-settings-section',
                'ppws_product_categories_password_settings_section',
                ['type' => 'checkbox', 'label_for' => 'ppws_product_categories_enable_password_field_checkbox', 'description' => 'Enable password for the category pages.', 'custom_class' => 'ppws_password_checkbox_validation']
            );

            global $ppws_product_categories_options;
            $ppws_product_categories_enable_password_value = isset($ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox']) ? $ppws_product_categories_options['ppws_product_categories_enable_password_field_checkbox'] : "";
            $ppws_product_categories_enable_password_class = "";
            if ($ppws_product_categories_enable_password_value != 'on') {
                $ppws_product_categories_enable_password_class = 'ppws-hide-section';
            }

            add_settings_field(
                'ppws_product_categories_enable_password_checkbox_for_admin',
                __('Disable For Administrator', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_password_settings'),
                'ppws-product-categories-password-settings-section',
                'ppws_product_categories_password_settings_section',
                ['type' => 'checkbox', 'label_for' => 'ppws_product_categories_enable_password_field_checkbox_for_admin', 'description' => '', 'class' => "$ppws_product_categories_enable_password_class ppws-product-categories-enable-password-section"]
            );

            add_settings_field(
                'ppws_product_categories_set_password_textbox',
                __('Set Password', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_password_settings'),
                'ppws-product-categories-password-settings-section',
                'ppws_product_categories_password_settings_section',
                ['type' => 'text', 'label_for' => 'ppws_product_categories_password', 'description' => 'Set password for the category pages.', 'placeholder' => 'Set password', 'class' => "$ppws_product_categories_enable_password_class ppws-product-categories-enable-password-section"]
            );

            add_settings_field(
                'ppws_product_categories_set_password_expiry_textbox',
                __('Set Password Expiry Days', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_password_settings'),
                'ppws-product-categories-password-settings-section',
                'ppws_product_categories_password_settings_section',
                ['type' => 'number', 'label_for' => 'ppws_product_categories_password_expiry_day', 'description' => 'Set expiry days of the password for the category pages. **Default: 400 days.**', 'placeholder' => 'Set Password Expiry Day', 'class' => "$ppws_product_categories_enable_password_class ppws-product-categories-enable-password-section"]
            );

            add_settings_field(
                'ppws_product_categories_all_categories_checkbox',
                __('Category List', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_list_settings'),
                'ppws-product-categories-password-settings-section',
                'ppws_product_categories_password_settings_section',
                ['type' => 'select', 'label_for' => 'ppws_product_categories_all_categories_field_checkbox', 'class' => "$ppws_product_categories_enable_password_class ppws-product-categories-enable-password-section ppws-product-categories-list-section", 'description' => 'Selected categories are protected with password.']
            );

            add_settings_field(
                'ppws_hide_products_checkbox',
                __('Hide Products from Loop', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_password_settings'),
                'ppws-product-categories-password-settings-section',
                'ppws_product_categories_password_settings_section',
                ['type' => 'checkbox', 'label_for' => 'ppws_hide_products_checkbox_field_checkbox', 'description' => '', 'class' => "$ppws_product_categories_enable_password_class ppws-product-categories-enable-password-section", 'description' => 'Hide products from shop/product loop listing.']
            );

            add_settings_field(
                'ppws_protect_archive_checkbox',
                __('Protect Archive Page', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_password_settings'),
                'ppws-product-categories-password-settings-section',
                'ppws_product_categories_password_settings_section',
                ['type' => 'checkbox', 'label_for' => 'ppws_protect_archive_checkbox_field_checkbox', 'description' => '', 'class' => "$ppws_product_categories_enable_password_class ppws-product-categories-enable-password-section", 'description' => 'Protect selected category archive pages.']
            );
            /* Password Settings End */

            /* User Role Start */
            add_settings_section(
                'ppws_product_categories_user_role_settings_section',
                __('User Role', 'password-protected-store-for-woocommerce'),
                array(),
                'ppws-product-categories-user-role-section'
            );

            add_settings_field(
                'enable_disable_userrole',
                __('Enable/Disable User Role', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_password_settings'),
                'ppws-product-categories-user-role-section',
                'ppws_product_categories_user_role_settings_section',
                ['type' => 'checkbox', 'label_for' => 'enable_user_role', 'description' => 'Enable to set user wise password for the category pages.<br> When user role is disable then password apply for both loggedin user and non logged in user.']
            );

            add_settings_field(
                'ppws_product_categories_select_role_radio',
                __('Select User Role', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_select_user_role_settings'),
                'ppws-product-categories-user-role-section',
                'ppws_product_categories_user_role_settings_section',
                ['type' => 'radio', 'label_for' => 'ppws_product_categories_select_user_role_field_radio']
            );

            add_settings_field(
                'ppws_product_categories_logged_in_user_checkbox',
                __('Select Logged In User', 'password-protected-store-for-woocommerce'),
                array($this, 'ppws_product_categories_logged_in_user_role_settings'),
                'ppws-product-categories-user-role-section',
                'ppws_product_categories_user_role_settings_section',
                ['type' => 'checkbox', 'label_for' => 'ppws_product_categories_logged_in_user_field_checkbox', 'class' => 'ppws-product-categories-select-logged-in-user-section ppws-page-logged-in-user-section', 'description' => 'Selected users get password form at front side.']
            );
            /* User Role End */
        }

        public function ppws_product_categories_password_settings($args)
        {
            global $ppws_product_categories_options;
            $value = isset($ppws_product_categories_options[$args['label_for']]) ? $ppws_product_categories_options[$args['label_for']] : '';

            if($args['label_for'] == 'ppws_protect_archive_checkbox_field_checkbox' && !isset($ppws_product_categories_options[$args['label_for']])) $value = "on";

            if ($args['type'] == 'checkbox') {
            ?>
                <label class="ppws-switch">
                    <input type="checkbox" class="ppws-checkbox <?php if (isset($args['custom_class'])) {  esc_attr_e($args['custom_class']); }  ?>" 
                    name="ppws_product_categories_settings[<?php esc_attr_e($args['label_for']) ?>]" 
                    id="<?php esc_attr_e($args['label_for']) ?>" 
                    value="on" 
                    <?php if ($value == "on") {   esc_attr_e('checked');  } ?>>
                    <span class="ppws-slider ppws-round"></span>
                </label>
                <p class="ppws-note">
                    <?php
                    $allowed_html = array('br'     => array(),);
                    echo wp_kses($args['description'], $allowed_html); ?>
                </p>
            <?php
            } elseif ($args['type'] == 'text') {
            ?>
                <input type="text" class="ppws-textbox ppws-pwd-input" name="ppws_product_categories_settings[<?php esc_attr_e($args['label_for']) ?>]" id="<?php esc_attr_e($args['label_for']) ?>" placeholder="<?php esc_attr_e($args['placeholder']) ?>" value="<?php esc_attr_e(ppws_decrypted_password($value)); ?>">
                <p class="ppws-note"><?php esc_attr_e($args['description']) ?></p>
            <?php
            } elseif ($args['type'] == 'number') {

            ?>
                <!-- Numberbox -->
                <input type="number" class="ppws-numberbox" max="400" name="ppws_product_categories_settings[<?php esc_attr_e($args['label_for']); ?>]" id="<?php esc_attr_e($args['label_for']) ?>" value="<?php esc_attr_e($value) ?>" placeholder="<?php esc_attr_e($args['placeholder']) ?>">
                <p class="ppws-note"><?php esc_attr_e($args['description']) ?></p>
            <?php
            }
        }

        public function ppws_product_categories_select_user_role_settings($args)
        {
            global $ppws_product_categories_options;

            if ($args['type'] == 'radio') {
            ?>
                <label class="ppws-label">
                    <input type="radio" class="<?php esc_attr_e('ppws_user_non_logged_in_user'); ?>" name="ppws_product_categories_settings[<?php esc_attr_e($args['label_for']); ?>]" value="non-logged-in-user" <?php checked('non-logged-in-user', $ppws_product_categories_options[$args['label_for']] ?? 'non-logged-in-user'); ?>> <?php _e('Non Logged In User', 'password-protected-store-for-woocommerce') ?>
                </label>
                <label class="ppws-label">
                    <input type="radio" class="<?php esc_attr_e('ppws_user_logged_in_user'); ?>" name="ppws_product_categories_settings[<?php esc_attr_e($args['label_for']); ?>]" value="logged-in-user" <?php checked('logged-in-user', $ppws_product_categories_options[$args['label_for']] ?? null); ?>> <?php _e('Logged In User', 'password-protected-store-for-woocommerce') ?>
                </label>
                <p class="ppws-note ppws-userrole-error ppws-hide-section">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18px" height="18px" x="0" y="0" viewBox="0 0 23.625 23.625" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <path style="" d="M11.812,0C5.289,0,0,5.289,0,11.812s5.289,11.813,11.812,11.813s11.813-5.29,11.813-11.813   S18.335,0,11.812,0z M14.271,18.307c-0.608,0.24-1.092,0.422-1.455,0.548c-0.362,0.126-0.783,0.189-1.262,0.189   c-0.736,0-1.309-0.18-1.717-0.539s-0.611-0.814-0.611-1.367c0-0.215,0.015-0.435,0.045-0.659c0.031-0.224,0.08-0.476,0.147-0.759   l0.761-2.688c0.067-0.258,0.125-0.503,0.171-0.731c0.046-0.23,0.068-0.441,0.068-0.633c0-0.342-0.071-0.582-0.212-0.717   c-0.143-0.135-0.412-0.201-0.813-0.201c-0.196,0-0.398,0.029-0.605,0.09c-0.205,0.063-0.383,0.12-0.529,0.176l0.201-0.828   c0.498-0.203,0.975-0.377,1.43-0.521c0.455-0.146,0.885-0.218,1.29-0.218c0.731,0,1.295,0.178,1.692,0.53   c0.395,0.353,0.594,0.812,0.594,1.376c0,0.117-0.014,0.323-0.041,0.617c-0.027,0.295-0.078,0.564-0.152,0.811l-0.757,2.68   c-0.062,0.215-0.117,0.461-0.167,0.736c-0.049,0.275-0.073,0.485-0.073,0.626c0,0.356,0.079,0.599,0.239,0.728   c0.158,0.129,0.435,0.194,0.827,0.194c0.185,0,0.392-0.033,0.626-0.097c0.232-0.064,0.4-0.121,0.506-0.17L14.271,18.307z    M14.137,7.429c-0.353,0.328-0.778,0.492-1.275,0.492c-0.496,0-0.924-0.164-1.28-0.492c-0.354-0.328-0.533-0.727-0.533-1.193   c0-0.465,0.18-0.865,0.533-1.196c0.356-0.332,0.784-0.497,1.28-0.497c0.497,0,0.923,0.165,1.275,0.497   c0.353,0.331,0.53,0.731,0.53,1.196C14.667,6.703,14.49,7.101,14.137,7.429z" fill="#030104" data-original="#030104" class=""></path>
                    </svg><?php _e('You must first enable user roles to select any specific user role.','password-protected-store-for-woocommerce'); ?>
                </p>
            <?php
            }
        }

        public function ppws_product_categories_logged_in_user_role_settings($args)
        {
            global $ppws_product_categories_options;
            $select_value = "";
            $blank_arr = array();
            $select_value = isset($ppws_product_categories_options[$args['label_for']]) ? $ppws_product_categories_options[$args['label_for']] : $blank_arr;
            if (isset($ppws_product_categories_options[$args['label_for']]) && !empty($ppws_product_categories_options[$args['label_for']]))            $select_value = explode(",", $ppws_product_categories_options[$args['label_for']]);

            if ($args['type'] == 'checkbox') {
                global $wp_roles;
                $all_roles = $wp_roles->roles;
                $editable_roles = apply_filters('editable_roles', $all_roles); ?>
                <div class="ppws_all_user_list"> 
                    <?php
                    echo '<div class="ppws_all_user_list_input"><label><input type="checkbox" class="ppws-checkbox editable_roles_all" >All</label></div>';
                    foreach ($editable_roles as $role) { 
                        if($role['name'] != 'Administrator'){
                        ?>
                        <div class="ppws_all_user_list_input">

                            <label>
                                <input type="checkbox" class="ppws-checkbox editable_roles_single" name="ppws_product_categories_settings[<?php esc_attr_e($args['label_for']) ?>][]" value="<?php esc_attr_e($role['name']); ?>" <?php if (in_array($role['name'], $select_value)) {
                                                                                                                                                                                                                    esc_attr_e('checked');
                                                                                                                                                                                                                } ?>><?php esc_attr_e($role['name']); ?>
                            </label>

                        </div>

                    <?php
                                                   } } ?>
                </div>
                <p class="ppws-note"><?php esc_attr_e($args['description']) ?></p>
            <?php
            }
        }

        public function ppws_product_categories_list_settings($args)
        {
            global $ppws_product_categories_options;
            $select_value = "";
            $blank_arr = array();
            $select_value = isset($ppws_product_categories_options[$args['label_for']]) ? $ppws_product_categories_options[$args['label_for']] : $blank_arr;
            if (isset($ppws_product_categories_options[$args['label_for']]) && !empty($ppws_product_categories_options[$args['label_for']]))            $select_value = explode(",", $ppws_product_categories_options[$args['label_for']]);

            if ($args['type'] == 'select') { ?>
                <select name="ppws_product_categories_settings[<?php esc_attr_e($args['label_for']) ?>][]" class="ppws_product_categories" id="ppws_product_categories"  multiple="multiple">
                    <?php if(!empty($select_value)) {
                            foreach($select_value as $category_id) {
                                $term = get_term( $category_id ); ?>
                                <option selected="selected" value="<?php esc_attr_e($category_id); ?>"><?php echo $term->name." (#".$category_id.")";; ?></option>
                            <?php }
                    } ?>
                </select>

                    
                <p class="ppws-note"><?php esc_attr_e($args['description']) ?></p>
                <?php
            }
        }

        public function sanitize_settings($input)
        {
            $new_input = array();

            if (isset($input['ppws_product_categories_enable_password_field_checkbox']) && !empty($input['ppws_product_categories_enable_password_field_checkbox'])) {
                $new_input['ppws_product_categories_enable_password_field_checkbox'] = sanitize_text_field($input['ppws_product_categories_enable_password_field_checkbox']);
            }
            if (isset($input['ppws_product_categories_enable_password_field_checkbox_for_admin']) && !empty($input['ppws_product_categories_enable_password_field_checkbox_for_admin'])) {
                $new_input['ppws_product_categories_enable_password_field_checkbox_for_admin'] = sanitize_text_field($input['ppws_product_categories_enable_password_field_checkbox_for_admin']);
            }

            if (isset($input['ppws_product_categories_password']) && !empty($input['ppws_product_categories_password'])) {
                $encrypt_new_password = ppws_encrypted_password($input['ppws_product_categories_password']);
                $new_input['ppws_product_categories_password'] = sanitize_text_field($encrypt_new_password);
            }

            if (isset($input['ppws_product_categories_password_expiry_day']) && !empty($input['ppws_product_categories_password_expiry_day'])) {
                $new_input['ppws_product_categories_password_expiry_day'] = sanitize_text_field($input['ppws_product_categories_password_expiry_day']);
            } else {
                $new_input['ppws_product_categories_password_expiry_day'] = "";
            }

            if (isset($input['ppws_product_categories_select_page_field_checkbox'])) {
                $new_input['ppws_product_categories_select_page_field_checkbox'] = sanitize_text_field($input['ppws_product_categories_select_page_field_checkbox']);
            }

            if (isset($input['enable_user_role'])) {
                $new_input['enable_user_role'] = sanitize_text_field($input['enable_user_role']);
				 
				if (isset($input['ppws_product_categories_select_user_role_field_radio'])) {
					$new_input['ppws_product_categories_select_user_role_field_radio'] = sanitize_text_field($input['ppws_product_categories_select_user_role_field_radio']);
				}
				
				if (isset($input['ppws_product_categories_logged_in_user_field_checkbox']) && !empty($input['ppws_product_categories_logged_in_user_field_checkbox'])) {
					$user_role_list = implode(",", $input['ppws_product_categories_logged_in_user_field_checkbox']);
					$new_input['ppws_product_categories_logged_in_user_field_checkbox'] = sanitize_text_field($user_role_list);
				}else{
                    if(isset($new_input['ppws_product_categories_select_user_role_field_radio']) && $new_input['ppws_product_categories_select_user_role_field_radio'] == 'logged-in-user') {
                        $new_input['ppws_product_categories_select_user_role_field_radio'] = 'non-logged-in-user';
                    }
                }
            }

            if (isset($input['ppws_product_categories_all_categories_field_checkbox'])) {
                $new_arra = $input['ppws_product_categories_all_categories_field_checkbox'];
                $cate_list = implode(",", $new_arra);
                $new_input['ppws_product_categories_all_categories_field_checkbox'] = $cate_list;
            }
            if (isset($input['ppws_hide_products_checkbox_field_checkbox']) && !empty($input['ppws_hide_products_checkbox_field_checkbox'])) {
                $new_input['ppws_hide_products_checkbox_field_checkbox'] = sanitize_text_field($input['ppws_hide_products_checkbox_field_checkbox']);
            }
            
            $new_input['ppws_protect_archive_checkbox_field_checkbox'] = sanitize_text_field($input['ppws_protect_archive_checkbox_field_checkbox']);
          
            return $new_input;
        }
    }
}
?>