<?php

defined ( 'ABSPATH' ) || die ();

class WPBottomMenu_Customizer{

    public static function init(){
        
        add_action( 'customize_register', [ __CLASS__, 'register' ] );
        add_action( 'wp_footer', [ __CLASS__, 'wpbottommenu_customize_css' ] );

    }

    /**
	 * Register customizer options
	 *
     * @param WP_Customize_Manager $wp_customize
     * @since 1.0.0
     * 
	 */
    public static function register( $wp_customize ) {

        if( !class_exists('Customize_Control_Multiple_Select') ){
            require_once( WP_BOTTOM_MENU_DIR_PATH . 'inc/multiple-select/multiple-select.php');
        }
                
        // Add Theme Options Panel.
        $wp_customize->add_panel( 'wpbottommenu_panel',
            array(
                'title'      => esc_html__( 'WP Bottom Menu', 'wp-bottom-menu' ),
                'priority'   => 20
            )
        );
    
        //
        // Section: Settings
        //

        $wp_customize->add_section( 'wpbottommenu_section_settings', array(
            'title'      => esc_html__( 'Settings', 'wp-bottom-menu' ),
            'priority'   => 120,
            'panel'      => 'wpbottommenu_panel', 
        ));

                $wp_customize->add_setting( 'wpbottommenu_iconset', array(
                    'default' => 'fontawesome',
                    'type' => 'option',
                ) );
                
                $wp_customize->add_control( 'wpbottommenu_iconset', array(
                    'type' => 'select',
                    'section' => 'wpbottommenu_section_settings', 
                    'label' => __( 'Select Icon Type', 'wp-bottom-menu' ),
                    'description' => __( '<u>Custom SVG:</u> Paste SVG Icon code.<br><u>FontAwesome:</u> Enable FontAwesome Library.', 'wp-bottom-menu' ),
                    'choices' => array(
                        'svg' => __( 'Custom SVG' ),
                        'fontawesome' => __( 'FontAwesome (v4.7)' ),
                        'fontawesome2' => __( 'FontAwesome (v6.1.1)' ), 
                    ),
                ) );

                $wp_customize->add_setting( 'wpbottommenu_display_px' , array(
                    'default'     => '1024',
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_display_px', array(
                    'label'    => __( 'Active The Menu (px)', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_settings',
                    'settings' => 'wpbottommenu_display_px',
                    'type' => 'number',
                ));
                
                $wp_customize->add_setting( 'wpbottommenu_display_always' , array(
                    'default'     => false,
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_display_always', array(
                    'label'    => __( 'Active for any screen size?', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_settings',
                    'settings' => 'wpbottommenu_display_always',
                    'type' => 'checkbox',
                ));

                $wp_customize->add_setting( 'wpbottommenu_target' , array(
                    'default'     => false,
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_target', array(
                    'label'    => __( 'Open links in a new tab', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_settings',
                    'settings' => 'wpbottommenu_target',
                    'type' => 'checkbox',
                ));
                
                $wp_customize->add_setting( 'wpbottommenu_zindex' , array(
                    'default'     => '9999',
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_zindex', array(
                    'label'    => __( 'Menu Z-Index', 'wp-bottom-menu' ), 
                    'description' => esc_html__( 'Recommended value: 9999', 'wp-bottom-menu' ),
                    'section'  => 'wpbottommenu_section_settings',
                    'settings' => 'wpbottommenu_zindex',
                    'type' => 'number',
                ));
                
                $wp_customize->add_setting( 'wpbottommenu_search_cpt' , array(
                    'default'     => 'all',
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_search_cpt', array(
                    'label'    => __( 'Custom search post types', 'wp-bottom-menu' ), 
                    'description' => esc_html__( 'Add your post type with a comma. Example: post,product,my-post-type', 'wp-bottom-menu' ),
                    'section'  => 'wpbottommenu_section_settings',
                    'settings' => 'wpbottommenu_search_cpt',
                    'type' => 'text',
                    'input_attrs' => array(
                        'placeholder' => 'all',
                      ),
                ));

        //
        // Section: Conditions
        //

        $wp_customize->add_section( 'wpbottommenu_section_conditions', array(
            'title'      => esc_html__( 'Conditions', 'wp-bottom-menu' ),
            'priority'   => 120,
            'panel'      => 'wpbottommenu_panel', 
        ));

            $wp_customize->add_setting( 'wpbottommenu_condition_reverse' , array(
                'default' => false,
                'type' => 'option',
            ));
            
            $wp_customize->add_control('wpbottommenu_condition_reverse', array(
                'label' => __( 'Reverse the condition?', 'wp-bottom-menu' ), 
                'description' => __( 'Currently the WP Bottom Menu will appear under the conditions you selected. Check this if you want it to be hidden under the conditions you choose.', 'wp-bottom-menu' ), 
                'section' => 'wpbottommenu_section_conditions',
                'settings' => 'wpbottommenu_condition_reverse',
                'type' => 'checkbox',
            ));

            $wp_customize->add_setting( 'wpbottommenu_condition', array(
                'default' => 'entire',
                'type' => 'option',
            ) );

            $wp_customize->add_control( 'wpbottommenu_condition', array(
                'type' => 'select',
                'section' => 'wpbottommenu_section_conditions',
                'label' => __( 'Select the Archive', 'wp-bottom-menu' ),
                'description' => __( 'Select a condition' ),
                'choices' => array(
                    'entire' => __( 'Entire Site' ),
                    'archives' => __( 'Archives' ),
                    'singular' => __( 'Singular' ),
                    'woocommerce' => __( 'Woocommerce' ),
                ),
            ) );

            $wp_customize->add_setting( 'wpbottommenu_archives_condition', array(
                'default' => 'all',
                'type' => 'option',
            ) );

            $wp_customize->add_control( 'wpbottommenu_archives_condition', array(
                'type' => 'select',
                'section' => 'wpbottommenu_section_conditions',
                'label' => __( 'Archive Elements', 'wp-bottom-menu' ),
                'description' => __( 'Select a condition' ),
                'choices' => array(
                    'all' => __( 'All' ),
                    'author' => __( 'Author' ),
                    'cats' => __( 'Category' ),
                    'tags' => __( 'Tags' ),
                ),
            ) );

            $wp_customize->add_setting( 'wpbottommenu_woocommerce_condition', array(
                'default' => 'all',
                'type' => 'option',
            ) );

            $wp_customize->add_control( 'wpbottommenu_woocommerce_condition', array(
                'type' => 'select',
                'section' => 'wpbottommenu_section_conditions',
                'label' => __( 'WooCommerce Elements', 'wp-bottom-menu' ),
                'description' => __( 'Select a condition' ),
                'choices' => array(
                    'all' => __( 'All' ),
                    'archive' => __( 'Product Archives' ),
                    'shop' => __( 'Shop Page' ),
                    'cats' => __( 'Product Categories' ),
                    'tags' => __( 'Product Tags' ),
                    'products' => __( 'Single Products' ),
                    'product' => __( 'Single Product' ),
                ),
            ) );
   
            $wp_customize->add_setting( 'wpbottommenu_singular_condition', array(
                'default' => 'all',
                'type' => 'option',
            ) );

            $wp_customize->add_control( 'wpbottommenu_singular_condition', array(
                'type' => 'select',
                'section' => 'wpbottommenu_section_conditions',
                'label' => __( 'Singular Elements', 'wp-bottom-menu' ),
                'description' => __( 'Select a condition' ),
                'choices' => array(
                    'all' => __( 'All' ),
                    'front-page' => __( 'Front Page' ),
                    'post' => __( 'Post' ),
                    'pages' => __( 'Pages' ),
                    'search' => __( 'Seach Results' ),
                    'page-404' => __( '404 Page' ),
                ),
            ) );

            $wp_customize->add_setting( 'wpbottommenu_singular_page_condition', array(
                //'sanitize_callback' => 'wpbottommenu_sanitize_dropdown_pages',
                'type' => 'option',
            ) );

            $wp_customize->add_control( new Customize_Control_Multiple_Select( $wp_customize, 'wpbottommenu_singular_page_condition', array(
                'type' => 'multiple-select',
                'section' => 'wpbottommenu_section_conditions',
                'label' => __( 'Single Pages', 'wp-bottom-menu' ),
                'description' => __( 'Select a page' ),
                'choices' => self::get_available_custom_post( 'page' ),
            ) ) );

            $wp_customize->add_setting( 'wpbottommenu_singular_post_condition', array(
                'default' => 'all',
                'type' => 'option',
            ) );

            $wp_customize->add_control( new Customize_Control_Multiple_Select( $wp_customize, 'wpbottommenu_singular_post_condition', array(
                'type' => 'multiple-select',
                'section' => 'wpbottommenu_section_conditions',
                'label' => __( 'Single Post', 'wp-bottom-menu' ),
                'description' => __( 'Select a condition' ),
                'choices' => self::get_available_custom_post( 'post' ),
            ) ) );

            $wp_customize->add_setting( 'wpbottommenu_singular_product_condition', array(
                'default' => 'all',
                'type' => 'option',
            ) );

            $wp_customize->add_control( new Customize_Control_Multiple_Select( $wp_customize, 'wpbottommenu_singular_product_condition', array(
                'type' => 'multiple-select',
                'section' => 'wpbottommenu_section_conditions',
                'label' => __( 'Single Product', 'wp-bottom-menu' ),
                'description' => __( 'Select products' ),
                'choices' => self::get_available_custom_post( 'product' ),
            ) ) );

            // User role condition 
            $wp_customize->add_setting( 'wpbottommenu_user_role_condition', array(
                'default' => 'all',
                'type' => 'option',
            ) );

            $wp_customize->add_control( new Customize_Control_Multiple_Select( $wp_customize, 'wpbottommenu_user_role_condition', array(
                'type' => 'multiple-select',
                'section' => 'wpbottommenu_section_conditions',
                'label' => __( 'Select User Roles Condition', 'wp-bottom-menu' ),
                'description' => __( 'WP Bottom Menu will only appear in the user roles you select.' ),
                'choices' => wpbm_get_user_roles()
            ) ) );
                
        //
        // Section: Customize
        //

        $wp_customize->add_section('wpbottommenu_section_customize', array(
            'title' => __('Customize', 'wp-bottom-menu'),
            'priority' => 130,
            'panel'      => 'wpbottommenu_panel' 
        ));

                $wp_customize->add_setting( 'wpbottommenu_placeholder_text' , array(
                    'default'     => 'Search',
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control( 'wpbottommenu_placeholder_text', array(
                    'label'    => __( 'Search Input Placeholder Text', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_placeholder_text',
                    'type' => 'text',
                ));

                $wp_customize->add_setting( 'wpbottommenu_fontsize' , array(
                    'default'     => '12',
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control( 'wpbottommenu_fontsize', array(
                    'label'    => __( 'Menu Font Size (px)', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_fontsize',
                    'type' => 'number',
                ));

                $wp_customize->add_setting( 'wpbottommenu_iconsize' , array(
                    'default'     => '24',
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control( 'wpbottommenu_iconsize', array(
                    'label'    => __( 'Menu Icon Size (px)', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_iconsize',
                    'type' => 'number',
                ));

                $wp_customize->add_setting( 'wpbottommenu_wrapper_padding' , array(
                    'default'     => '10',
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control( 'wpbottommenu_wrapper_padding', array(
                    'label'    => __( 'Menu Padding (px)', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_wrapper_padding',
                    'type' => 'number',
                ));

                $wp_customize->add_setting( 'wpbottommenu_textcolor', array(
                    'default' => '#555555',
                    'section'  => 'wpbottommenu_section_customize',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'type' => 'option'
                ));

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpbottommenu_textcolor', array(
                    'label'    => __( 'Menu Text Color', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                )));
                
                $wp_customize->add_setting( 'wpbottommenu_htextcolor', array(
                    'default' => '#000000',
                    'section'  => 'wpbottommenu_section_customize',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'type' => 'option'
                ));

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpbottommenu_htextcolor', array(
                    'label'    => __( 'Menu Hover/Active Text Color', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                )));

                $wp_customize->add_setting( 'wpbottommenu_iconcolor', array(
                    'default' => '#555555',
                    'section'  => 'wpbottommenu_section_customize',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'type' => 'option'
                ));

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpbottommenu_iconcolor', array(
                    'label'    => __( 'Menu Icon Color', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                )));
               
                $wp_customize->add_setting( 'wpbottommenu_hiconcolor', array(
                    'default' => '#000000',
                    'section'  => 'wpbottommenu_section_customize',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'type' => 'option'
                ));

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpbottommenu_hiconcolor', array(
                    'label'    => __( 'Menu Hover/Active Icon Color', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                )));

                $wp_customize->add_setting( 'wpbottommenu_bgcolor', array(
                    'default' => '#ffffff',
                    'section'  => 'wpbottommenu_section_customize',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'type' => 'option'
                ));

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpbottommenu_bgcolor', array(
                    'label'    => __( 'Menu Background Color', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                )));

                $wp_customize->add_setting( 'wpbottommenu_disable_title' , array(
                    'default'     => false,
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_disable_title', array(
                    'label'    => __( 'Disable title?', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_disable_title',
                    'type' => 'checkbox',
                ));

                $wp_customize->add_setting( 'wpbottommenu_cart_separator' , array(
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_cart_separator', array(
                    'label'    => __( 'Customize Cart Item', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_cart_separator',
                    'type' => 'hidden',
                ));

                $wp_customize->add_setting( 'wpbottommenu_show_cart_count' , array(
                    'default'     => false,
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_show_cart_count', array(
                    'label'    => __( 'Show Cart Count', 'wp-bottom-menu' ), 
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_show_cart_count',
                    'type' => 'checkbox',
                ));
                
                $wp_customize->add_setting( 'wpbottommenu_cart_count_bgcolor', array(
                    'default' => '#ff0000',
                    'section'  => 'wpbottommenu_section_customize',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'type' => 'option'
                ));

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpbottommenu_cart_count_bgcolor', array( 
                    'description' => __('Cart Count Background Color', 'wp-bottom-menu'),
                    'section'  => 'wpbottommenu_section_customize',
                )));

                $wp_customize->add_setting( 'wpbottommenu_show_cart_total' , array(
                    'default'     => false,
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_show_cart_total', array(
                    'label'    => __( 'Show Cart Total', 'wp-bottom-menu' ), 
                    'description' => __( 'This option override cart menu title.', 'wp-bottom-menu' ),
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_show_cart_total',
                    'type' => 'checkbox',
                ));

                $wp_customize->add_setting( 'wpbottommenu_show_account_name' , array(
                    'default'     => false,
                    'type'        => 'option',
                ));
                
                $wp_customize->add_control('wpbottommenu_show_account_name', array(
                    'label'    => __( 'Show Account Name', 'wp-bottom-menu' ), 
                    'description' => __( 'This option override account menu title. First name is shown, if there is no first name, the username is shown.', 'wp-bottom-menu' ),
                    'section'  => 'wpbottommenu_section_customize',
                    'settings' => 'wpbottommenu_show_account_name',
                    'type' => 'checkbox',
                ));

        //
        // Section: Menu Items
        //
        
        $wp_customize->add_section('wpbottommenu_section_menuitems', array(
            'title' => __('Menu Items', 'wp-bottom-menu'),
            'priority' => 140,
            'panel'      => 'wpbottommenu_panel' 
        ));

                $wp_customize->add_setting( 'customizer_repeater_wpbm', array(
                    'sanitize_callback' => 'customizer_repeater_sanitize',
                    'type' => 'option',
                    'default' => json_encode( array(
                       array("choice" => "wpbm-homepage" ,"subtitle" => "fa-home", "title" => "Home", "id" => "customizer_repeater_1" ),
                       array("choice" => "wpbm-woo-account" ,"subtitle" => "fa-user", "title" => "Account", "id" => "customizer_repeater_2" ),
                       array("choice" => "wpbm-woo-cart" ,"subtitle" => "fa-shopping-cart", "title" => "Cart", "id" => "customizer_repeater_3" ),
                       array("choice" => "wpbm-woo-search" ,"subtitle" => "fa-search", "title" => "Search", "id" => "customizer_repeater_4" ),
                       ))
                ));

                $wp_customize->add_control( new Customizer_Repeater( $wp_customize, 'customizer_repeater_wpbm', array(
                    'label'   => esc_html__('Menu Item','customizer-repeater'),
                    'section' => 'wpbottommenu_section_menuitems',
                    'customizer_repeater_title_control' => true,
                    'customizer_repeater_link_control' => true,
                    'customizer_repeater_text_control' => true,
                    'customizer_repeater_subtitle_control' => true,
                )));

                $wp_customize->add_setting( 'wpbottommenu_howuseicon' , array(
                    'type'        => 'option',
                ));

                $wp_customize->add_control( 'wpbottommenu_howuseicon', array(
                    'label'    => __( 'How to use Icons?', 'wp-bottom-menu' ), 
                    'description' => sprintf(
                        __( '<u>For FontAwesome:</u> Add the names from (%1$s) to the "Icon" field.<br>Example:<code>fa-home</code><hr><u>For FontAwesome v6:</u> Add the names from (%3$s) to the "Icon" field.<br>Example:<code>fa-solid fa-house</code><hr><u>For SVG Icons:</u> simply paste your SVG code in the "Icon" field. SVG Icon Library: %2$s<br>Enable to use SVG <code>Settings > Select Icon Type > Custom SVG</code> ', 'wp-bottom-menu' ),
                        sprintf( '<a target="_blank" href="https://fontawesome.com/v4.7.0/icons/" rel="nofollow">%s</a>', esc_html__( 'FontAwesome', 'wp-bottom-menu' ) ),
                        sprintf( '<a target="_blank" href="https://remixicon.com" rel="nofollow">%s</a>', esc_html__( 'Remix Icon', 'wp-bottom-menu' ) ),
                        sprintf( '<a target="_blank" href="https://fontawesome.com/search?m=free&o=r rel="nofollow">%s</a>', esc_html__( 'FontAwesome v6', 'wp-bottom-menu' ) )
                    ),
                    'section'  => 'wpbottommenu_section_menuitems',
                    'settings' => 'wpbottommenu_howuseicon',
                    'type' => 'hidden',
                ));
                

    }

    /**
	 * Render styles
     * 
     * @since 1.0.0
     * 
	 */
    public static function wpbottommenu_customize_css(){

        // check condition
        $condition = new \WPBottomMenu_Condition();
        if ( !$condition->get_condition() ){
            return;
        }

        ?>
        <style type="text/css">
            <?php if (!get_option( 'wpbottommenu_display_always', false )): ?>
                @media (max-width: <?php echo get_option( 'wpbottommenu_display_px', '1024' ); ?>px){
                    .wp-bottom-menu{
                        display:flex;
                    }
                    .wp-bottom-menu-search-form-wrapper{
                        display: block;
                    }
                }
            <?php else: ?>
                    .wp-bottom-menu{
                        display:flex;
                    }
                    .wp-bottom-menu-search-form-wrapper{
                        display: block;
                    }
            <?php endif; ?>

            :root{
                --wpbottommenu-font-size: <?php echo get_option( 'wpbottommenu_fontsize', '12' );?>px;
                --wpbottommenu-icon-size: <?php echo get_option( 'wpbottommenu_iconsize', '24' );?>px;
                --wpbottommenu-text-color: <?php echo get_option( 'wpbottommenu_textcolor', '#555555' );?>;
                --wpbottommenu-h-text-color: <?php echo get_option( 'wpbottommenu_htextcolor', '#000000' );?>;
                --wpbottommenu-icon-color: <?php echo get_option( 'wpbottommenu_iconcolor', '#555555' );?>;
                --wpbottommenu-h-icon-color: <?php echo get_option( 'wpbottommenu_hiconcolor', '#000000' );?>;
                --wpbottommenu-bgcolor: <?php echo get_option( 'wpbottommenu_bgcolor', '#ffffff' );?>;
                --wpbottommenu-zindex: <?php echo get_option( 'wpbottommenu_zindex', '9999' ); ?>;
                --wpbottommenu-cart-count-bgcolor: <?php echo get_option( 'wpbottommenu_cart_count_bgcolor', '#ff0000' );?>;
                --wpbottommenu-wrapper-padding: <?php echo get_option( 'wpbottommenu_wrapper_padding', '10' );?>px 0;
            }

        </style>
        <?php
    }

    private static function get_available_custom_post( $type ) {
        $posts = get_posts( array(
            'post_type' => $type,
            'posts_per_page' => -1,
        ) );

        $options = [];

        foreach ( $posts as $post ) {
        $options[ $post->ID ] = $post->post_title;
        }

        return $options;
    }

}
WPBottomMenu_Customizer::init();