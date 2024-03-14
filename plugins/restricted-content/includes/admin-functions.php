<?php

if ( ! function_exists( 'rsc_allowed_post_types' ) ) {
    function rsc_allowed_post_types() {
        $rsc_allowed_post_types = apply_filters( 'rsc_allowed_post_types',
            array(
                'post',
                'page',
                'tc_events',
                'tc_speakers',
                'surl'
            ) );
        return $rsc_allowed_post_types;
    }
}

if ( ! function_exists( 'rsc_skip_post_types' ) ) {
    function rsc_skip_post_types() {
        $rsc_skip_post_types = apply_filters( 'rsc_skip_post_types',
            array(
                'product',
                'product_variation',
                'shop_order',
                'shop_order_refund',
                'shop_coupon',
                'shop_subscription',
                'tc_tickets',
                'tc_api_keys',
                'tc_tickets_instances',
                'tc_orders',
                'tc_templates',
                'tc_forms',
                'tc_form_fields',
                'tc_seat_charts',
                'edd_discount',
                'edd_payment',
                'edd_log',
                'elementor_library',
                'download',
                'attachment',
                'scheduled-action',
                'revision',
                'custom_css',
                'nav_menu_item',
                'customize_changeset',
                'oembed_cache',
                'user_request',
                'wp_block',
            ) );
        return $rsc_skip_post_types;
    }
}

if ( ! function_exists( 'rsc_iw_is_wl' ) ) {

    function rsc_iw_is_wl() {
        global $rsc;
        return ( 'Restrict' == $rsc->title ) ? false : true;
    }
}

function rsc_get_documentation_link( $tab = 'welcome' ) {

    $base = 'https://restrict.io/';

    switch ( $tab ) {

        case 'general':
            $url = $base . 'restricted-content-documentation/general/';
            break;

        case 'tickera':
            $url = $base . 'restricted-content-documentation/tickera-integration/';
            break;

        case 'woocommerce':
            $url = $base . 'restricted-content-documentation/woocommerce-integration/';
            break;

        case 'edd':
            $url = $base . 'restricted-content-documentation/easy-digital-downloads-integration/';
            break;

        case 'shortcodes';
            $url = $base . 'restricted-content-documentation/shortcodes/';
            break;

        case 'login_form';
            $url = $base . 'restricted-content-documentation/login-form/';
            break;

        case 'post_types';
            $url = $base . 'restricted-content-documentation/post-types/';
            break;

        case 'simple_urls';
            $url = $base . 'restricted-content-documentation/simple-urls/';
            break;

        case 'bot_exclusion':
            $url = $base . 'restricted-content-documentation/seo-bots-and-web-crawlers-restriction-exclusion/';
            break;

        default:
            $url = $base . 'documentation/';
            break;
    }

    return $url;
}

function rsc_premium_vs_free_button_widget_welcome() {
    if ( restrict_fs()->can_use_premium_code() ) { ?>
        <div class="rsc-already-featured"></div>
    <?php } else { ?>
        <div class="rsc-premium-features-button">
            <a href="https://restrict.io/pricing/" target="_blank"><span><?php _e( 'Go Premium', 'rsc' ); ?></span>
                <div class="rsc-premium-icon"></div>
            </a>
        </div>
    <?php }
}

function rsc_welcome_widget_copy( $type = 'free' ) {

    $copy = array();

    $copy[ 'free' ] = array(
        'content' => array(
            'title' => __( 'Restrict the content of pages or posts', 'rsc' ),
            'description' => sprintf( __( 'When creating new or editing existing page or post, you will find a dropdown menu below the content area that will allow you to %sselect the criteria%s based on which the content should be restricted. ', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/restricting-the-content-of-page-or-post/">', '</a>' )
        ),
        'integrations' => array(
            'title' => __( 'Integrations', 'rsc' ),
            'description' => sprintf( __( 'Restrict has seamless integrations with %sTickera%s, %sWooCommerce%s, %sEasy Digital Downloads%s and %sSimple URLs%s allowing you to restrict the content of pages or posts based on criteria specific for these plugins (i.e. show content to users who purchased a specific ticket or WooCommerce product). ', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/tickera-integration/" target="_blank">', '</a>', '<a href="https://restrict.io/restricted-content-documentation/woocommerce-integration/" target="_blank">', '</a>', '<a href="https://restrict.io/restricted-content-documentation/easy-digital-downloads-integration/">', '</a>', '<a href="https://restrict.io/restricted-content-documentation/simple-urls/">', '</a>' )
        ),
        'shortcodes' => array(
            'title' => __( 'Restrict part or all the content', 'rsc' ),
            'description' => sprintf( __( 'Premium version of Restrict allows you to %srestrict the content partially%s. Also you can set default restriction rules for the available post types or apply the same rules for all the posts of the %sspecific post type%s.', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/shortcodes/">', '</a>', '<a href="https://restrict.io/restricted-content-documentation/post-types/">', '</a>' )
        ),
        'category' => array(
            'title' => __( 'Restricting a whole category', 'rsc' ),
            'description' => sprintf( __( 'There might be cases where you would want to %srestrict the whole post category%s. Premium version of Restrict takes care of that too, allowing you to easily set the criteria based on which a certain post category will be displayed or hidden. ', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/rectricting-access-to-post-categories/">', '</a>' ),
        ),
        'widgets' => array(
            'title' => __( 'Hide and show widgets conditionally', 'rsc' ),
            'description' => sprintf( __( 'Make widgets %sshow or disappear%s for different users! Similarly to the content restriction, you can also restrict which widgets will be shown to what user. ', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/restricting-visibility-of-wordpress-widgets/">', '</a>' ),
        ),
        'login' => array(
            'title' => __( 'Login form anywhere', 'rsc' ),
            'description' => sprintf( __( 'Regardless of whether you’re using Gutenberg or classic editor, we made it easy for you to place the %slogin form%s on any page or post you want or like', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/login-form/">', '</a>' ),
        ),
        'menus' => array(
            'title' => __( 'Custom menu', 'rsc' ),
            'description' => sprintf( __( 'Not every menu item should be accessed by everyone. Because of this, we have integrated simple yet powerful control over each individual menu item so that you can show or hide it based on various criteria.', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/restricting-menu-items/">', '</a>' ),
        ),
        'hide_show_menu' => array(
            'title' => __( 'Hide or show menu items', 'rsc' ),
            'description' => sprintf( __( 'Easily select which criteria is required to view which menu item. Each menu item in any of your menus may have %sdifferent criteria for visibility%s.', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/restricting-menu-items/">', '</a>' ),
        ),
        'site_shield' => array(
            'title' => __( 'Site Shield', 'rsc' ),
            'description' => sprintf( __( 'Lock your website to all non-logged in users and restrict It takes just one click!', 'rsc' ), '<a href="' . admin_url( 'nav-menus.php' ) . '">', '</a>' )
        ),
        'bots_crawlers' => array(
            'title' => __( 'Whitelist SEO bots and web crawlers', 'rsc' ),
            'description' => __( 'Make the content on your website restricted to humans but let SEO bots and web crawlers access it without restrictions to benefit from SEO juice.', 'rsc' ),
        )
    );

    $copy[ 'premium' ] = array(
        'content' => array(
            'title' => __( 'Restrict the content of pages or posts', 'rsc' ),
            'description' => sprintf( __( 'When creating new or editing existing page or post, you will find a dropdown menu below the content area that will allow you to select the criteria based on which the content should be restricted.', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/restricting-the-content-of-page-or-post/">', '</a>' )
        ),
        'integrations' => array(
            'title' => __( 'Integrations', 'rsc' ),
            'description' => sprintf( __( 'Restrict has seamless integrations with %sTickera%s, %sWooCommerce%s, %sEasy Digital Downloads%s and %sSimple URLs%s allowing you to restrict the content of pages or posts based on criteria specific for these plugins (i.e. show content to users who purchased a specific ticket or WooCommerce product).', 'rsc' ), '<a href="https://restrict.io/restricted-content-documentation/tickera-integration/" target="_blank">', '</a>', '<a href="https://restrict.io/restricted-content-documentation/woocommerce-integration/" target="_blank">', '</a>', '<a href="https://restrict.io/restricted-content-documentation/easy-digital-downloads-integration/">', '</a>', '<a href="https://restrict.io/restricted-content-documentation/simple-urls/">', '</a>' )
        ),
        'shortcodes' => array(
            'title' => __( 'Restrict part or all the content', 'rsc' ),
            'description' => sprintf( __( 'To restrict only part of the content. head to the %sShortcodes%s area and generate the code for partial content restriction. You can also navigate to %sPost Types%s area to set default content restriction for specific post types. ', 'rsc' ), '<a href="' . admin_url( 'admin.php?page=restricted_content_settings&tab=shortcodes' ) . '">', '</a>', '<a href="' . admin_url( 'admin.php?page=restricted_content_settings&tab=post_types' ) . '">', '</a>' )
        ),
        'category' => array(
            'title' => __( 'Restricting a whole category', 'rsc' ),
            'description' => sprintf( __( 'Within the %sCategories area%s you can now restrict which categories should be visible or hidden by setting the desired criteria while creating new or editing existing category.  ', 'rsc' ), '<a href="' . admin_url( 'edit-tags.php?taxonomy=category' ) . '">', '</a>' )
        ),
        'widgets' => array(
            'title' => __( 'Hide and show widgets conditionally', 'rsc' ),
            'description' => sprintf( __( 'In the %sWidgets area%s of your website, you will notice that every widget that you have placed in any of your widgets areas will now have a menu allowing you to display or hide each specific widget to certain users. ', 'rsc' ), '<a href="' . admin_url( 'widgets.php' ) . '">', '</a>' )
        ),
        'login' => array(
            'title' => __( 'Login form anywhere', 'rsc' ),
            'description' => sprintf( __( 'Navigate to the %sLogin Form%s area where you will find plenty of options to tailor your login form the way you want it. If you’re using Gutenberg editor, you can simply add a Login Form block for Gutenberg, with the exact same options. ', 'rsc' ), '<a href="' . admin_url( 'admin.php?page=restricted_content_settings&tab=login_form' ) . '">', '</a>' )
        ),
        'menus' => array(
            'title' => __( 'Custom menu', 'rsc' ),
            'description' => sprintf( __( 'Within the %sMenus area%s of your website each menu item now has the dropdown menu, allowing you to show or hide that item based on selected criteria.', 'rsc' ), '<a href="' . admin_url( 'nav-menus.php' ) . '">', '</a>' )
        ),
        'hide_show_menu' => array(
            'title' => __( 'Hide or show menu items', 'rsc' ),
            'description' => sprintf( __( 'Easily select which criteria is required to view which menu item. Each menu item in any of your %smenus%s may have different criteria for visibility.', 'rsc' ), '<a href="' . admin_url( 'nav-menus.php' ) . '">', '</a>' )
        ),
        'site_shield' => array(
            'title' => __( 'Site Shield', 'rsc' ),
            'description' => sprintf( __( 'Lock your website to all non-logged in users and restrict It takes just one click!', 'rsc' ), '<a href="' . admin_url( 'nav-menus.php' ) . '">', '</a>' )
        ),
        'bots_crawlers' => array(
            'title' => __( 'Whitelist SEO bots and web crawlers', 'rsc' ),
            'description' => __( 'Make the content on your website restricted to humans but let SEO bots and web crawlers access it without restrictions to benefit from SEO juice.', 'rsc' ),
        )
    );

    if ( class_exists( 'TC' ) && ! class_exists( 'WooCommerce' ) ) {
        $copy[ 'premium' ][ 'integrations' ][ 'description' ] = __( 'Content of all the posts and pages can be restricted by criteria related to Tickera plugin. For example, you can restrict the visibility of the content based on whether the visitor has purchased a ticket for the specific event or specific ticket type. ', 'rsc' );
    }

    if ( ! class_exists( 'TC' ) && class_exists( 'WooCommerce' ) ) {
        $copy[ 'premium' ][ 'integrations' ][ 'description' ] = __( 'Content of all the posts and pages can be restricted by criteria related specifically to WooCommerce. For example, you can restrict the visibility of the content based on whether the visitor has purchased any or some specific WooCommerce product.', 'rsc' );
    }

    return $copy[ $type ];
}

if ( ! function_exists( 'rsc_array_trim' ) ) {
    function rsc_array_trim( $array ) {
        return array_map( 'trim', $array );
    }
}

if ( ! function_exists( 'rsc_tooltip' ) ) {

    function rsc_tooltip( $content, $echo = true ) {
        if ( ! empty( $content ) ) {

            $tooltip = '<a title="' . htmlentities( $content ) . '" class="rsc_tooltip"><span class="dashicons dashicons-editor-help"></span></a>';

            if ( $echo ) {
                echo rsc_esc_html( $tooltip );

            } else {
                return rsc_esc_html( $tooltip );
            }
        }
    }
}

if ( ! function_exists( 'rsc_yes_no' ) ) {
    function rsc_yes_no( $field_name, $default_value = '' ) {
        global $rsc_settings;
        if ( isset( $rsc_settings[ $field_name ] ) ) {
            $checked = $rsc_settings[ $field_name ];
        } else {
            $checked = ( '' !== $default_value ) ? $default_value : 'no';
        } ?>
        <div class="rsc-yes-no-wrap">
            <div class="rsc-customized-yes-no rsc-no-active">
                <div class="rsc-yes"><?php _e( 'Yes', 'rsc' ); ?> </div>
                <div class="rsc-ball"></div>
                <div class="rsc-no"><?php _e( 'No', 'rsc' ); ?> </div>
            </div><!-- .tc-customized-yes-no -->
            <label>
                <input type="radio" class="<?php echo esc_attr( $field_name ); ?> rsc-radio-yes" name="rsc_settings[<?php echo esc_attr( $field_name ); ?>]" value="yes" <?php checked( $checked, 'yes', true ); ?> /><?php _e( 'Yes', 'rsc' ); ?>
            </label>
            <label>
                <input type="radio" class="<?php echo esc_attr( $field_name ); ?> rsc-radio-no" name="rsc_settings[<?php echo esc_attr( $field_name ); ?>]" value="no" <?php checked( $checked, 'no', true ); ?> /><?php _e( 'No', 'rsc' ); ?>
            </label>
        </div><!-- rsc-yes-no-wrap --><?php
    }
}

function rsc_get_default_admin_shortcode_message() {
    return __( 'Your content here', 'rsc' );
}

if ( ! function_exists( 'rsc_show_post_type_options' ) ) {
    function rsc_show_post_type_options( $field_name, $default_value = '' ) {
        global $rsc;
        $post_type = str_replace( 'post_type_', '', $field_name ); ?>
        <div class="rsc_outside_wrap">
            <div class="form-field term-slug-wrap rsc_metabox">
                <?php $rsc->show_metabox( false, 'post_type', 'post_type', $post_type ); ?>
            </div>
        </div><?php
        //rsc_yes_no('rsc_post_type_'.$post_type.'_restricted', 'no');
    }
}

if ( ! function_exists( 'rsc_generate_admin_box_content' ) ) {
    function rsc_generate_admin_box_content( $field_name, $default_value = '' ) {
        switch ( $field_name ) {
            case 'content_availability_logged_in': ?>
                <div class="rsc-code-generator">
                    <div class="rsc-input-wrap">
                        <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                    </div>
                    <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                </div><?php
                break;

            case 'content_availability_logged_out': ?>
                <div class="rsc-code-generator">
                    <div class="rsc-input-wrap">
                        <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                    </div>
                    <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                </div><?php
                break;

            case 'content_availability_specific_role': ?>
                <div class="rsc-chosen-multiple">
                    <div class="tc-custom-chosen-select-wrap">
                        <select data-placeholder="<?php echo __( 'Select User Roles', 'rsc' ); ?>" id="rsc_chosen_specific_role" multiple class="chosen-select">
                            <?php
                            $editable_roles = array_reverse( get_editable_roles() );
                            foreach ( $editable_roles as $role => $details ) {
                                $name = translate_user_role( $details[ 'name' ] ); ?>
                                <option value="<?php echo esc_attr( $role ); ?>"><?php echo rsc_esc_html( $name ); ?></option><?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="rsc-code-generator">
                    <div class="rsc-input-wrap">
                        <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                    </div>
                    <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                </div><?php
                break;

            case 'content_availability_tickera_users': ?>
                <div class="rsc-ticket-type-purchased">
                    <div class="rsc-input-text"><?php _e( 'Who Purchased', 'rsc' ); ?></div>
                    <div class="rsc-ticket-type-select-input">
                        <input type="radio" id="any_ticket_type" class="rsc_who_purchased_tickera_radio" name="who_purchased_tickera" value="any_ticket_type" checked>
                        <label for="any_ticket_type"><?php _e( 'Any ticket type', 'rsc' ); ?></label>
                    </div>

                    <?php if ( apply_filters( 'tc_is_woo', false ) == false ) {//Tickera isn't in the Bridge mode because this option doesn't work with WooCommerce
                        $rsc_events = get_posts( array(
                            'post_type' => 'tc_events',
                            'posts_per_page' => -1,
                        ) );

                        if ( count( $rsc_events ) > 0 ) { ?>
                            <div class="rsc-ticket-type-select-input">
                                <input type="radio" id="specific_event" class="rsc_who_purchased_tickera_radio"
                                       name="who_purchased_tickera" value="specific_event">
                                <label for="specific_event"><?php _e( 'Any ticket type for a specific event', 'rsc' ); ?></label>
                            </div>
                        <?php }
                    }

                    if ( apply_filters( 'tc_is_woo', false ) == false ) { // Tickera is in the Bridge mode
                        $rsc_ticket_types = get_posts( array(
                            'post_type' => 'tc_tickets',
                            'posts_per_page' => -1,
                        ) );
                    } else {
                        $rsc_ticket_types = get_posts( array(
                            'post_type' => 'product',
                            'posts_per_page' => -1,
                            'meta_key' => '_event_name'
                        ) );
                    }

                    if ( count( $rsc_ticket_types ) ) { ?>
                        <div class="rsc-ticket-type-select-input">
                            <input type="radio" id="specific_ticket_type" class="rsc_who_purchased_tickera_radio"
                                   name="who_purchased_tickera" value="specific_ticket_type">
                            <label for="specific_ticket_type"><?php _e( 'Specific ticket type', 'rsc' ); ?></label>
                        </div>
                    <?php } ?>
                </div><!-- .rsc-ticket-type-purchased -->
                <div class="rsc-chosen-multiple any_ticket_type_selected content_availability_tickera_users">
                    <div class="rsc-code-generator">
                        <div class="rsc-input-wrap">
                            <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                        </div>
                        <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                    </div>
                </div>
                <div class="rsc-chosen-multiple specific_event_selected content_availability_tickera_users" style="display: none;">
                    <div class="tc-custom-chosen-select-wrap">
                        <select data-placeholder="<?php _e( 'Select Events', 'rsc' ); ?>" multiple class="chosen-select" id="rsc_chosen_tickera_users_specific_event">
                            <?php
                            $default_value = '[rsc_restrict_content allowed_to="tickera" tickera_options="event" tickera_event="0"]' . rsc_get_default_admin_shortcode_message() . '[/rsc_restrict_content]';
                            foreach ( $rsc_events as $event ) { ?>
                                <option value="<?php echo (int) $event->ID; ?>"><?php echo rsc_esc_html( $event->post_title ); ?></option><?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="rsc-code-generator">
                        <div class="rsc-input-wrap">
                            <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                        </div>
                        <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                    </div>
                </div>
                <div class="rsc-chosen-multiple specific_ticket_type_selected content_availability_tickera_users" style="display: none;">
                    <?php $default_value = '[rsc_restrict_content allowed_to="tickera" tickera_options="ticket_type" tickera_ticket_type="0"]' . rsc_get_default_admin_shortcode_message() . '[/rsc_restrict_content]'; ?>
                    <div class="tc-custom-chosen-select-wrap">
                        <select data-placeholder="<?php _e( 'Select Ticket Types', 'rsc' ); ?>" multiple class="chosen-select" id="rsc_chosen_tickera_users_specific_ticket_type">
                            <?php
                            foreach ( $rsc_ticket_types as $ticket_type ) {
                                $event_id = get_post_meta( $ticket_type->ID, apply_filters( 'tc_event_name_field_name', 'event_name' ), true );
                                $event_title = get_the_title( $event_id );
                                if ( empty( $event_title ) ) {
                                    $event_title = sprintf( __( 'Event ID: %s', 'rsc' ), $event_id );
                                } ?>
                                <option value="<?php echo (int) $ticket_type->ID; ?>"><?php echo rsc_esc_html( $ticket_type->post_title . ' (' . $event_title . ')' ); ?></option><?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="rsc-code-generator">
                        <div class="rsc-input-wrap">
                            <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                        </div>
                        <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                    </div>
                </div><?php
                break;

            case 'content_availability_specific_users': ?>
                <div class="rsc-user-capability">
                    <div class="rsc-capability-label">
                        <?php _e( 'User Capability', 'rsc' ); ?>
                    </div>
                    <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php _e( 'manage_options', 'rsc' ); ?>"/>
                </div>
                <div class="rsc-code-generator">
                    <div class="rsc-input-wrap">
                        <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_output" value="<?php echo esc_attr( $default_value ); ?>"/>
                    </div>
                    <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                </div><?php
                break;

            case 'content_availability_woocommere': ?>
                <div class="rsc-ticket-type-purchased">
                    <div class="rsc-input-text"><?php _e( 'Who Purchased', 'rsc' ); ?></div>
                    <div class="rsc-ticket-type-select-input">
                        <input type="radio" id="any_product" value="any_product" class="rsc_who_purchased_woocommerce_radio" name="who_purchased_woocommerce" checked>
                        <label for="any_product"><?php _e( 'Any Product', 'rsc' ); ?></label>
                    </div>
                    <?php $woo_products = get_posts( array(
                        'post_type' => 'product',
                        'posts_per_page' => -1,
                    ) );
                    if ( count( $woo_products ) ) { ?>
                        <div class="rsc-ticket-type-select-input">
                            <input type="radio" id="specific_product" value="specific_product" class="rsc_who_purchased_woocommerce_radio" name="who_purchased_woocommerce">
                            <label for="specific_product"><?php _e( 'Specific Product', 'rsc' ); ?></label>
                        </div>
                    <?php } ?>
                </div><!-- .rsc-ticket-type-purchased -->
                <div class="rsc-chosen-multiple any_woocommerce_product_selected content_availability_woocommerce_users">
                    <div class="rsc-code-generator">
                        <div class="rsc-input-wrap">
                            <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                        </div>
                        <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                    </div>
                </div>
                <?php $default_value = esc_attr( '[rsc_restrict_content allowed_to="woo" woo_options="product" woo_product="0"]' . rsc_get_default_admin_shortcode_message() . '[/rsc_restrict_content]' ); ?>
                <div class="rsc-chosen-multiple specific_woocommerce_product_selected content_availability_woocommerce_users" style="display: none;">
                    <div class="tc-custom-chosen-select-wrap">
                        <select data-placeholder="<?php _e( 'Select Product', 'rsc' ); ?>" multiple class="chosen-select" id="rsc_chosen_woo_users_specific_product">
                            <?php foreach ( $woo_products as $product ) { ?>
                                <option value="<?php echo (int) $product->ID; ?>"><?php echo rsc_esc_html( $product->post_title ); ?></option>
                            <?php } ?>
                        </select>
                    </div><!-- .tc-custom-chosen-select-wrap -->
                    <div class="rsc-ticket-type-purchased">
                        <div class="rsc-input-text"><?php _e( 'Duration', 'rsc' ); ?></div>
                        <div class="rsc-ticket-type-select-input">
                            <input type="radio" id="indefinitely" value="indefinitely" class="rsc_indefinit_time rsc_time_offer_shortcode" name="rsc_time_offer" checked>
                            <label for="indefinitely"><?php _e( 'Indefinitely', 'rsc' ); ?></label>
                        </div>
                        <div class="rsc-ticket-type-select-input">
                            <input type="radio" id="limited_time" value="limited_time" class="rsc_limited_time_offer rsc_time_offer_shortcode" name="rsc_time_offer">
                            <label for="limited_time"><?php _e( 'Limited time after purchase', 'rsc' ); ?></label>
                        </div>
                    </div>
                    <div class="rsc_woo_times_shortcode rsc_times_shortcode">
                        <label>
                            <?php _e( 'Days:', 'tc' ); ?><br/>
                            <select id="rsc_woo_limited_time_days">
                                <?php for ( $day = apply_filters( 'rsc_woo_time_day_min', 0 ); $day <= apply_filters( 'rsc_woo_time_day_max', 365 ); $day++ ) { ?>
                                    <option value="<?php echo esc_attr( $day ); ?>"><?php echo rsc_esc_html( $day ); ?></option>
                                <?php } ?>
                            </select>
                        </label>
                        <label>
                            <?php _e( 'Hours:', 'tc' ); ?><br/>
                            <select id="rsc_woo_limited_time_hours">
                                <?php for ( $hour = apply_filters( 'rsc_woo_time_hour_min', 0 ); $hour <= apply_filters( 'rsc_woo_time_hour_max', 24 ); $hour++ ) { ?>
                                    <option value="<?php echo esc_attr( $hour ); ?>"><?php echo rsc_esc_html( $hour ); ?></option>
                                <?php } ?>
                            </select>
                        </label>
                        <label>
                            <?php _e( 'Minutes:', 'tc' ); ?><br/>
                            <select id="rsc_woo_limited_time_minutes">
                                <?php for ( $minute = apply_filters( 'rsc_woo_time_minute_min', 0 ); $minute <= apply_filters( 'rsc_woo_time_minute_', 60 ); $minute++ ) { ?>
                                    <option value="<?php echo esc_attr( $minute ); ?>"><?php echo rsc_esc_html( $minute ); ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    </div>
                    <div class="rsc-code-generator">
                        <div class="rsc-input-wrap">
                            <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                        </div>
                        <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                    </div>
                </div><?php
                break;

            case 'content_availability_edd': ?>
                <div class="rsc-ticket-type-purchased">
                    <div class="rsc-input-text"><?php _e( 'Who Purchased', 'rsc' ); ?></div>
                    <div class="rsc-ticket-type-select-input">
                        <input type="radio" id="edd_any_product" value="any_product" class="rsc_who_purchased_edd_radio" name="who_purchased_edd" checked>
                        <label for="any_product"><?php _e( 'Any Product', 'rsc' ); ?></label>
                    </div>
                    <?php $edd_products = get_posts( array(
                        'post_type' => 'download',
                        'posts_per_page' => -1,
                    ) );
                    if ( count( $edd_products ) ) { ?>
                        <div class="rsc-ticket-type-select-input">
                            <input type="radio" id="edd_specific_product" value="specific_product" class="rsc_who_purchased_edd_radio" name="who_purchased_edd">
                            <label for="specific_product"><?php _e( 'Specific Product', 'rsc' ); ?></label>
                        </div>
                    <?php } ?>
                </div><!-- .rsc-ticket-type-purchased -->
                <div class="rsc-chosen-multiple any_edd_product_selected content_availability_edd_users">
                    <div class="rsc-code-generator">
                        <div class="rsc-input-wrap">
                            <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                        </div>
                        <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                    </div>
                </div>
                <?php $default_value = esc_attr( '[rsc_restrict_content allowed_to="edd" edd_options="product" edd_product="0"]' . rsc_get_default_admin_shortcode_message() . '[/rsc_restrict_content]' ); ?>
                <div class="rsc-chosen-multiple specific_edd_product_selected content_availability_edd_users" style="display: none;">
                    <div class="tc-custom-chosen-select-wrap">
                        <select data-placeholder="<?php _e( 'Select Product', 'rsc' ); ?>" multiple class="chosen-select" id="rsc_chosen_edd_users_specific_product">
                            <?php foreach ( $edd_products as $product ) { ?>
                                <option value="<?php echo (int) $product->ID; ?>"><?php echo rsc_esc_html( $product->post_title ); ?></option>
                            <?php } ?>
                        </select>
                    </div><!-- .tc-custom-chosen-select-wrap -->
                    <div class="rsc-ticket-type-purchased">
                        <div class="rsc-input-text"><?php _e( 'Duration', 'rsc' ); ?></div>
                        <div class="rsc-ticket-type-select-input">
                            <input type="radio" id="edd_indefinitely" value="indefinitely" class="rsc_indefinit_time rsc_time_offer_shortcode_edd" name="rsc_time_offer" checked>
                            <label for="indefinitely"><?php _e( 'Indefinitely', 'rsc' ); ?></label>
                        </div>
                        <div class="rsc-ticket-type-select-input">
                            <input type="radio" id="edd_limited_time" value="limited_time" class="rsc_limited_time_offer rsc_time_offer_shortcode_edd" name="rsc_time_offer">
                            <label for="limited_time"><?php _e( 'Limited time after purchase', 'rsc' ); ?></label>
                        </div>
                    </div>
                    <div class="rsc_edd_times_shortcode rsc_times_shortcode">
                        <label>
                            <?php _e( 'Days:', 'tc' ); ?><br/>
                            <select id="rsc_edd_limited_time_days">
                                <?php for ( $day = apply_filters( 'rsc_edd_time_day_min', 0 ); $day <= apply_filters( 'rsc_edd_time_day_max', 365 ); $day++ ) { ?>
                                    <option value="<?php echo esc_attr( $day ); ?>"><?php echo rsc_esc_html( $day ); ?></option>
                                <?php } ?>
                            </select>
                        </label>
                        <label>
                            <?php _e( 'Hours:', 'tc' ); ?><br/>
                            <select id="rsc_edd_limited_time_hours">
                                <?php for ( $hour = apply_filters( 'rsc_edd_time_hour_min', 0 ); $hour <= apply_filters( 'rsc_edd_time_hour_max', 24 ); $hour++ ) { ?>
                                    <option value="<?php echo esc_attr( $hour ); ?>"><?php echo rsc_esc_html( $hour ); ?></option>
                                <?php } ?>
                            </select>
                        </label>
                        <label>
                            <?php _e( 'Minutes:', 'tc' ); ?><br/>
                            <select id="rsc_edd_limited_time_minutes">
                                <?php for ( $minute = apply_filters( 'rsc_edd_time_minute_min', 0 ); $minute <= apply_filters( 'rsc_edd_time_minute_', 60 ); $minute++ ) { ?>
                                    <option value="<?php echo esc_attr( $minute ); ?>"><?php echo rsc_esc_html( $minute ); ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    </div>
                    <div class="rsc-code-generator">
                        <div class="rsc-input-wrap">
                            <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                        </div>
                        <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                    </div>
                </div><?php
                break;

            default: ?>
                <div class="rsc-code-generator">
                    <div class="rsc-input-wrap">
                        <input type="text" class="rsc-shortcode-input <?php echo esc_attr( $field_name ); ?>_input" value="<?php echo esc_attr( $default_value ); ?>"/>
                    </div>
                    <button class="button-primary rsc_copy_button <?php echo esc_attr( $field_name ); ?>_button"><?php _e( 'Copy', 'rsc' ) ?></button>
                </div><?php
                break;
        }
    }
}

/**
 * Sanitize posted array
 * @param type $input
 * @return type
 */
if ( ! function_exists( 'rsc_sanitize_array' ) ) {
    function rsc_sanitize_array( $input ) {
        $new_input = array();

        foreach ( $input as $key => $val ) {
            if ( is_array( $val ) ) {
                // 2nd level
                $input_2 = rsc_sanitize_array( $val );
                foreach ( $input_2 as $key_2 => $val_2 ) {
                    if ( is_array( $val_2 ) ) {
                        // 3rd level
                        $input_3 = rsc_sanitize_array( $val_2 );
                        foreach ( $input_3 as $key_3 => $val_3 ) {
                            if ( is_array( $val_3 ) ) {
                                // 4th level
                                $input_4 = rsc_sanitize_array( $val_3 );
                                foreach ( $input_4 as $key_4 => $val_4 ) {
                                    if ( is_array( $val_4 ) ) {
                                        // 5th level
                                        $input_5 = rsc_sanitize_array( $val_4 );
                                        foreach ( $input_5 as $key_5 => $val_5 ) {
                                            $new_input[ $key ][ $key_2 ][ $key_3 ][ $key_4 ][ $key_5 ] = rsc_sanitize_string( $val_5 );
                                        }
                                    } else {
                                        $new_input[ $key ][ $key_2 ][ $key_3 ][ $key_4 ] = rsc_sanitize_string( $val_4 );
                                    }
                                }
                            } else {
                                $new_input[ $key ][ $key_2 ][ $key_3 ] = rsc_sanitize_string( $val_3 );
                            }
                        }
                    } else {
                        $new_input[ $key ][ $key_2 ] = rsc_sanitize_string( $val_2 );
                    }
                }
            } else {
                $new_input[ $key ] = rsc_sanitize_string( $val );
            }
        }

        return $new_input;
    }
}

/**
 * Sanitize posted string
 * @param type $string
 * @return type
 */

if ( ! function_exists( 'rsc_sanitize_string' ) ) {

    function rsc_sanitize_string( $string ) {

        if ( is_array( $string ) ) {
            return rsc_sanitize_array( $string );

        } elseif ( is_string( $string ) ) {

            if ( $string != strip_tags( $string ) || strpos( $string, "\n" ) !== FALSE ) { // String contain html tags

                $string = stripslashes( $string );

                $default_attribs = [
                    'id' => [],
                    'class' => [],
                    'title' => [],
                    'style' => [],
                    'data' => [],
                    'name' => [],
                    'data-mce-id' => [],
                    'data-mce-style' => [],
                    'data-mce-bogus' => [],
                    'data-condition-field_name' => [],
                    'data-condition-field_type' => [],
                    'data-condition-value' => [],
                    'data-condition-action' => [],
                    'data-placeholder' => []
                ];

                $allowed_tags = [
                    'a' => array_merge( $default_attribs, [
                        'href' => [],
                        'target' => [ '_blank', '_top' ],
                    ] ),
                    'abbr' => [
                        'title' => true,
                    ],
                    'acronym' => [
                        'title' => true,
                    ],
                    'cite' => [],
                    'table' => array_merge( $default_attribs, [
                        'width' => [],
                        'height' => [],
                        'cellspacing' => [],
                        'cellpadding' => [],
                        'border' => [],
                    ] ),
                    'td' => array_merge( $default_attribs, [
                        'width' => [],
                        'height' => []
                    ] ),
                    'tr' => array_merge( $default_attribs, [
                        'width' => [],
                        'height' => []
                    ] ),
                    'th' => array_merge( $default_attribs, [
                        'width' => [],
                        'height' => []
                    ] ),
                    'tbody' => [ $default_attribs ],
                    'div' => $default_attribs,
                    'del' => [
                        'datetime' => true,
                    ],
                    'em' => [],
                    'q' => [
                        'cite' => true,
                    ],
                    'strike' => [],
                    'strong' => $default_attribs,
                    'blockquote' => $default_attribs,
                    'del' => $default_attribs,
                    'strike' => $default_attribs,
                    'em' => $default_attribs,
                    'code' => $default_attribs,
                    'span' => $default_attribs,
                    'img' => [
                        'src' => [],
                        'width' => [],
                        'height' => []
                    ],
                    'ins' => [],
                    'p' => $default_attribs,
                    'u' => $default_attribs,
                    'i' => $default_attribs,
                    'b' => $default_attribs,
                    'ul' => $default_attribs,
                    'ol' => $default_attribs,
                    'li' => $default_attribs,
                    'br' => $default_attribs,
                    'hr' => $default_attribs,
                    'h1' => $default_attribs,
                    'h2' => $default_attribs,
                    'h3' => $default_attribs,
                    'h4' => $default_attribs,
                    'h5' => $default_attribs,
                    'h6' => $default_attribs,
                    'h7' => $default_attribs,
                    'h8' => $default_attribs,
                    'h9' => $default_attribs,
                    'h10' => $default_attribs,
                    'select' => array_merge( $default_attribs, [ 'multiple' => [] ] ),
                    'option' => array_merge( $default_attribs, [ 'value' => [], 'selected' => [] ] ),
                    'textarea' => array_merge( $default_attribs )
                ];

                return wp_kses( $string, $allowed_tags );

            } else {
                return sanitize_text_field( $string );
            }

        } else {
            return $string; // Returning original value for data type such as integer, float, double.
        }
    }
}

/**
 * Sanitize array values
 *
 * @param array $array
 * @param bool $allow_html
 * @return array
 */
if ( ! function_exists( 'rsc_sanitize_array2' ) ) {

    function rsc_sanitize_array2( $array, $allow_html = false ) {

        if ( ! is_array( $array ) ) {
            return $array;
        }

        $sanitized_array = [];

        foreach ( $array as $key => $value ) {

            // Sanitize Key without affecting case sensitivity
            $key = preg_replace( '/[^a-zA-Z0-9_\-]/', '', $key );

            if ( is_array( $value ) ) {
                $sanitized_array[ $key ] = rsc_sanitize_array2( $value, $allow_html );

            } else {

                if ( $allow_html ) {
                    $value = rsc_sanitize_string( $value );

                } elseif ( is_string( $value ) ) {
                    $value = sanitize_text_field( $value );
                }

                $sanitized_array[ $key ] = $value;
            }
        }

        return $sanitized_array;
    }
}

/**
 * Sanitize data URL
 *
 * @param $url
 * @param bool $output
 * @return string
 */
if ( ! function_exists( 'rsc_sanitize_url' ) ) {

    function rsc_sanitize_url( $url, $output = false ) {
        $url = stripslashes( strip_tags( $url ) );
        $url = ( $output ) ? esc_url( $url ) : esc_url_raw( $url );
        return esc_url_raw( $url );
    }
}

/**
 * Escape html
 *
 * @param $html
 * @return bool|string
 */
if ( ! function_exists( 'rsc_esc_html' ) ) {

    function rsc_esc_html( $html ) {

        if ( $html ) {

            if ( $html == strip_tags( $html ) ) {
                return esc_html( $html );

            } else {
                return rsc_sanitize_string( $html );
            }

        } else {
            return $html;
        }
    }
}
