<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    SCFW_Size_Chart_For_Woocommerce
 * @subpackage SCFW_Size_Chart_For_Woocommerce/public
 * @link       http://www.multidots.com/
 * @since      1.0.0
 */
/**
 * If this file is called directly, abort.
 */
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SCFW_Size_Chart_For_Woocommerce
 * @subpackage SCFW_Size_Chart_For_Woocommerce/public
 * @author     Multidots <inquiry@multidots.in>
 */
class SCFW_Size_Chart_For_Woocommerce_Public
{
    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string $version The current version of this plugin.
     */
    private  $post_type_name ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @param string $post_type_name The post type name of this plugin.
     *
     * @since 1.0.0
     */
    public function __construct( $plugin_name, $version, $post_type_name )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->post_type_name = $post_type_name;
    }
    
    /**
     * Get the plugin name.
     * @return string
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * Plugin dash name.
     * @return string
     */
    public function get_plugin_dash_name()
    {
        return sanitize_title_with_dashes( $this->get_plugin_name() );
    }
    
    /**
     * Get the plugin version.
     * @return string
     */
    public function get_plugin_version()
    {
        return $this->version;
    }
    
    /**
     * Register the Style and JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function scfw_enqueue_styles_scripts_callback()
    {
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in SCFW_Size_Chart_For_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The SCFW_Size_Chart_For_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        // Register styles.
        wp_register_style(
            $this->get_plugin_dash_name(),
            plugin_dir_url( __FILE__ ) . 'css/size-chart-for-woocommerce-public.css',
            array(),
            $this->version,
            'all'
        );
        // Enqueue styles.
        wp_enqueue_style( $this->get_plugin_dash_name() );
        $inline_style_varibale = $this->scfw_get_inline_style_for_size_chart();
        if ( false !== $inline_style_varibale ) {
            wp_add_inline_style( $this->get_plugin_dash_name(), $inline_style_varibale );
        }
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in SCFW_Size_Chart_For_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The SCFW_Size_Chart_For_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_register_script(
            $this->get_plugin_dash_name(),
            plugin_dir_url( __FILE__ ) . 'js/size-chart-for-woocommerce-public' . $suffix . '.js',
            array( 'jquery' ),
            $this->version,
            true
        );
        wp_enqueue_script( $this->get_plugin_dash_name() );
    }
    
    /**
     * Size chart product custom tab.
     *
     * @param array $tabs current tabs array.
     *
     * @return array return a array of tabs.
     * @since 1.0.0
     *
     */
    public function scfw_size_chart_custom_product_tab_callback( $tabs )
    {
        global  $post ;
        $prod_id = scfw_size_chart_get_product( $post->ID );
        $prod_id = ( is_array( $prod_id ) ? $prod_id : [ $prod_id ] );
        
        if ( isset( $prod_id ) && is_array( $prod_id ) && !empty($prod_id) ) {
            $i = 50;
            $i = apply_filters( 'scfw_tab_priority_setting', $i );
            foreach ( $prod_id as $prod_val ) {
                $chart_position = scfw_size_chart_get_position_by_chart_id( $prod_val );
                
                if ( 'publish' === get_post_status( $prod_val ) ) {
                    $size_chart_id = $prod_val;
                } else {
                    $size_chart_id = $this->scfw_size_chart_id_by_category( $post->ID );
                }
                
                // Check if product is belongs to tag
                
                if ( 0 === intval( $size_chart_id ) || !$size_chart_id ) {
                    $size_chart_id = $this->scfw_size_chart_id_by_tag( $post->ID );
                    // Check if product is belongs to attribute
                    if ( 0 === intval( $size_chart_id ) || !$size_chart_id ) {
                        $size_chart_id = $this->scfw_size_chart_id_by_attributes( $post->ID );
                    }
                }
                
                if ( !$size_chart_id ) {
                    return $tabs;
                }
                $link_show = true;
                // This will work in pro version
                
                if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
                    $current_country = $this->scfw_get_current_user_country__premium_only();
                    $chart_country = scfw_size_chart_country__premium_only( $size_chart_id );
                    $link_show = false;
                    if ( empty($chart_country) || in_array( $current_country, $chart_country, true ) ) {
                        $link_show = true;
                    }
                }
                
                if ( !$link_show ) {
                    return $tabs;
                }
                $chart_label = scfw_size_chart_get_label_by_chart_id( $size_chart_id );
                
                if ( 'tab' === $chart_position ) {
                    $size_chart_tab_label = scfw_size_chart_get_tab_label_by_chart_id( $size_chart_id );
                    
                    if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                        $tab_label = $size_chart_tab_label;
                    } else {
                        $size_chart_tab_label = scfw_size_chart_get_tab_label();
                        
                        if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                            $tab_label = scfw_size_chart_get_tab_label();
                        } else {
                            $tab_label = $chart_label;
                        }
                    
                    }
                    
                    
                    if ( !empty($size_chart_id) && is_array( $size_chart_id ) ) {
                        foreach ( $size_chart_id as $chart_id ) {
                            $tabs[$chart_id] = array(
                                'title'    => __( $tab_label, 'size-chart-for-woocommerce' ),
                                'priority' => $i++,
                                'callback' => array( $this, 'scfw_size_chart_custom_product_tab_content_callback' ),
                            );
                        }
                    } else {
                        $tabs[$size_chart_id] = array(
                            'title'    => __( $tab_label, 'size-chart-for-woocommerce' ),
                            'priority' => $i++,
                            'callback' => array( $this, 'scfw_size_chart_custom_product_tab_content_callback' ),
                        );
                    }
                
                }
            
            }
        }
        
        
        if ( isset( $post->ID ) && !empty($post->ID) ) {
            $j = 50;
            $j = apply_filters( 'scfw_tab_priority_setting', $j );
            $chart_ids = $this->scfw_size_chart_id_by_category( $post->ID );
            if ( !empty($chart_ids) ) {
                foreach ( $chart_ids as $chart_id ) {
                    $link_show = true;
                    // This will work in pro version
                    
                    if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
                        $current_country = $this->scfw_get_current_user_country__premium_only();
                        $chart_country = scfw_size_chart_country__premium_only( $chart_id );
                        $link_show = false;
                        if ( empty($chart_country) || in_array( $current_country, $chart_country, true ) ) {
                            $link_show = true;
                        }
                    }
                    
                    if ( !$chart_ids || !$link_show ) {
                        return $tabs;
                    }
                    $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
                    $chart_label = scfw_size_chart_get_label_by_chart_id( $chart_id );
                    
                    if ( 'tab' === $chart_position ) {
                        $size_chart_tab_label = scfw_size_chart_get_tab_label_by_chart_id( $chart_id );
                        
                        if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                            $tab_label = $size_chart_tab_label;
                        } else {
                            $size_chart_tab_label = scfw_size_chart_get_tab_label();
                            
                            if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                                $tab_label = scfw_size_chart_get_tab_label();
                            } else {
                                $tab_label = $chart_label;
                            }
                        
                        }
                        
                        $tabs[$chart_id] = array(
                            'title'    => __( $tab_label, 'size-chart-for-woocommerce' ),
                            'priority' => $j++,
                            'callback' => array( $this, 'scfw_size_chart_custom_product_tab_content_callback' ),
                        );
                    }
                
                }
            }
            $size_chart_id = $this->scfw_size_chart_id_by_tag( $post->ID );
            if ( !empty($size_chart_id) ) {
                foreach ( $size_chart_id as $chart_id ) {
                    $link_show = true;
                    // This will work in pro version
                    
                    if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
                        $current_country = $this->scfw_get_current_user_country__premium_only();
                        $chart_country = scfw_size_chart_country__premium_only( $chart_id );
                        $link_show = false;
                        if ( empty($chart_country) || in_array( $current_country, $chart_country, true ) ) {
                            $link_show = true;
                        }
                    }
                    
                    if ( !$size_chart_id || !$link_show ) {
                        return $tabs;
                    }
                    $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
                    $chart_label = scfw_size_chart_get_label_by_chart_id( $chart_id );
                    
                    if ( 'tab' === $chart_position ) {
                        $size_chart_tab_label = scfw_size_chart_get_tab_label_by_chart_id( $chart_id );
                        
                        if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                            $tab_label = $size_chart_tab_label;
                        } else {
                            $size_chart_tab_label = scfw_size_chart_get_tab_label();
                            
                            if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                                $tab_label = scfw_size_chart_get_tab_label();
                            } else {
                                $tab_label = $chart_label;
                            }
                        
                        }
                        
                        $tabs[$chart_id] = array(
                            'title'    => __( $tab_label, 'size-chart-for-woocommerce' ),
                            'priority' => $j++,
                            'callback' => array( $this, 'scfw_size_chart_custom_product_tab_content_callback' ),
                        );
                    }
                
                }
            }
            $chart_attr_id = $this->scfw_size_chart_id_by_attributes( $post->ID );
            if ( !empty($chart_attr_id) ) {
                foreach ( $chart_attr_id as $chart_id ) {
                    $link_show = true;
                    // This will work in pro version
                    
                    if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
                        $current_country = $this->scfw_get_current_user_country__premium_only();
                        $chart_country = scfw_size_chart_country__premium_only( $chart_id );
                        $link_show = false;
                        if ( empty($chart_country) || in_array( $current_country, $chart_country, true ) ) {
                            $link_show = true;
                        }
                    }
                    
                    if ( !$chart_attr_id || !$link_show ) {
                        return $tabs;
                    }
                    $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
                    $chart_label = scfw_size_chart_get_label_by_chart_id( $chart_id );
                    
                    if ( 'tab' === $chart_position ) {
                        $size_chart_tab_label = scfw_size_chart_get_tab_label_by_chart_id( $chart_id );
                        
                        if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                            $tab_label = $size_chart_tab_label;
                        } else {
                            $size_chart_tab_label = scfw_size_chart_get_tab_label();
                            
                            if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                                $tab_label = scfw_size_chart_get_tab_label();
                            } else {
                                $tab_label = $chart_label;
                            }
                        
                        }
                        
                        $tabs[$chart_id] = array(
                            'title'    => __( $tab_label, 'size-chart-for-woocommerce' ),
                            'priority' => $j++,
                            'callback' => array( $this, 'scfw_size_chart_custom_product_tab_content_callback' ),
                        );
                    }
                
                }
            }
        }
        
        return $tabs;
    }
    
    /**
     * Check popup button position.
     *
     * @since 1.0.0
     */
    public function scfw_size_chart_popup_button_position_callback()
    {
        $filter_hook = apply_filters( 'add_hook_custom_size_chart_position', 'woocommerce_single_product_summary' );
        add_action( $filter_hook, array( $this, 'scfw_size_chart_popup_button_callback' ), 11 );
    }
    
    /**
     * Size chart new tab content.
     *
     * @since 1.0.0
     */
    public function scfw_size_chart_custom_product_tab_content_callback( $size_chart_id )
    {
        global  $post ;
        $dup_id = array();
        $prod_id = scfw_size_chart_get_product( $post->ID );
        $prod_id = ( is_array( $prod_id ) ? $prod_id : [ $prod_id ] );
        if ( isset( $prod_id ) && is_array( $prod_id ) && !empty($prod_id) ) {
            foreach ( $prod_id as $prod_val ) {
                
                if ( '' !== get_post_status( $prod_val ) && 'publish' === get_post_status( $prod_val ) ) {
                    $chart_id = $prod_val;
                } else {
                    $chart_id = $this->scfw_size_chart_id_by_category( $post->ID );
                }
                
                // Check if product is belongs to tag
                
                if ( 0 === intval( $chart_id ) || !$chart_id ) {
                    $chart_id = $this->scfw_size_chart_id_by_tag( $post->ID );
                    // Check if product is belongs to attribute
                    if ( 0 === intval( $chart_id ) || !$chart_id ) {
                        $chart_id = $this->scfw_size_chart_id_by_attributes( $post->ID );
                    }
                }
                
                $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
                
                if ( 'tab' === $chart_position && $chart_id === $size_chart_id ) {
                    $file_dir_path = 'includes/common-files/size-chart-contents.php';
                    
                    if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                        include plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
                        $dup_id[] = $prod_val;
                    }
                
                }
            
            }
        }
        
        if ( isset( $post->ID ) && !empty($post->ID) ) {
            $chart_cat_ids = $this->scfw_size_chart_id_by_category( $post->ID );
            if ( !empty($chart_cat_ids) ) {
                foreach ( $chart_cat_ids as $chart_id ) {
                    
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
                        
                        if ( 'tab' === $chart_position && $chart_id === $size_chart_id ) {
                            $file_dir_path = 'includes/common-files/size-chart-contents.php';
                            
                            if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                                include plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
                                $dup_id[] = $chart_id;
                            }
                        
                        }
                    
                    }
                
                }
            }
            $chart_ids = $this->scfw_size_chart_id_by_tag( $post->ID );
            if ( !empty($chart_ids) ) {
                foreach ( $chart_ids as $chart_id ) {
                    
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
                        
                        if ( 'tab' === $chart_position && $chart_id === $size_chart_id ) {
                            $file_dir_path = 'includes/common-files/size-chart-contents.php';
                            
                            if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                                include plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
                                $dup_id[] = $chart_id;
                            }
                        
                        }
                    
                    }
                
                }
            }
            $chart_attr_ids = $this->scfw_size_chart_id_by_attributes( $post->ID );
            if ( !empty($chart_attr_ids) ) {
                foreach ( $chart_attr_ids as $chart_id ) {
                    
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
                        
                        if ( 'tab' === $chart_position && $chart_id === $size_chart_id ) {
                            $file_dir_path = 'includes/common-files/size-chart-contents.php';
                            if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                                include plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
                            }
                        }
                    
                    }
                
                }
            }
        }
    
    }
    
    /**
     * Hook to display chart button.
     *
     * @since 1.0.0
     */
    public function scfw_size_chart_popup_button_callback()
    {
        global  $post ;
        $dup_id = array();
        $prod_id = scfw_size_chart_get_product( $post->ID );
        $prod_id = ( is_array( $prod_id ) ? $prod_id : [ $prod_id ] );
        if ( isset( $prod_id ) && is_array( $prod_id ) && !empty($prod_id) ) {
            foreach ( $prod_id as $prod_val ) {
                
                if ( '' !== get_post_status( $prod_val ) && 'publish' === get_post_status( $prod_val ) ) {
                    $this->scfw_size_chart_popup_button_area( $prod_val );
                    $dup_id[] = $prod_val;
                }
            
            }
        }
        
        if ( isset( $post->ID ) && !empty($post->ID) ) {
            $chart_ids = $this->scfw_size_chart_id_by_category( $post->ID );
            if ( !empty($chart_ids) ) {
                foreach ( $chart_ids as $chart_id ) {
                    
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $this->scfw_size_chart_popup_button_area( $chart_id );
                        $dup_id[] = $chart_id;
                    }
                
                }
            }
            $chart_tag_id = $this->scfw_size_chart_id_by_tag( $post->ID );
            if ( !empty($chart_tag_id) ) {
                foreach ( $chart_tag_id as $chart_id ) {
                    
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $this->scfw_size_chart_popup_button_area( $chart_id );
                        $dup_id[] = $chart_id;
                    }
                
                }
            }
            $chart_attr_id = $this->scfw_size_chart_id_by_attributes( $post->ID );
            if ( !empty($chart_attr_id) ) {
                foreach ( $chart_attr_id as $chart_id ) {
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $this->scfw_size_chart_popup_button_area( $chart_id );
                    }
                }
            }
        }
    
    }
    
    /**
     * Product Details, Category, Tag or Attribute Specific Size Chart Generate
     */
    public function scfw_size_chart_popup_button_area( $chart_id )
    {
        $chart_label = scfw_size_chart_get_label_by_chart_id( $chart_id );
        $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
        $size_chart_style = scfw_size_chart_style_value_by_chart_id( $chart_id );
        $link_show = true;
        // This will work in pro version
        $popup_position = 'center';
        
        if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
            $current_country = $this->scfw_get_current_user_country__premium_only();
            $chart_country = scfw_size_chart_country__premium_only( $chart_id );
            $link_show = false;
            if ( empty($chart_country) || in_array( $current_country, $chart_country, true ) ) {
                $link_show = true;
            }
            $popup_position = scfw_get_popup_postition__premium_only( $chart_id );
        }
        
        
        if ( 0 !== $chart_id && 'popup' === $chart_position && $link_show ) {
            $chart_popup_label = scfw_size_chart_get_popup_label_by_chart_id( $chart_id );
            
            if ( isset( $chart_popup_label ) && !empty($chart_popup_label) ) {
                $popup_label = $chart_popup_label;
            } else {
                $size_chart_popup_label = scfw_size_chart_get_popup_label();
                
                if ( isset( $size_chart_popup_label ) && !empty($size_chart_popup_label) ) {
                    $popup_label = $size_chart_popup_label;
                } else {
                    $popup_label = $chart_label;
                }
            
            }
            
            $size_chart_get_button_class = '';
            $chart_popup_type = scfw_size_chart_get_popup_type_by_chart_id( $chart_id );
            
            if ( isset( $chart_popup_type ) && !empty($chart_popup_type) && 'global' !== $chart_popup_type ) {
                $popup_type = $chart_popup_type;
            } else {
                $size_chart_popup_type = scfw_size_chart_get_popup_type();
                
                if ( isset( $size_chart_popup_type ) && !empty($size_chart_popup_type) ) {
                    $popup_type = $size_chart_popup_type;
                } else {
                    $popup_type = 'text';
                }
            
            }
            
            $chart_popup_icon = scfw_size_chart_get_popup_icon_by_chart_id( $chart_id );
            if ( !empty($chart_popup_icon) ) {
                $popup_label = sprintf( __( '<span class="dashicons"><img src="%1$s" alt="%2$s" /></span>', 'size-chart-for-woocommerce' ), esc_url( SCFW_PLUGIN_URL . 'includes/chart-icons/' . $chart_popup_icon . '.svg' ), $chart_popup_icon ) . $popup_label;
            }
            ?>
            <div class="scfw-size-chart-main md-size-chart-modal-main">
	            <div class="button-wrapper">
	                <?php 
            
            if ( 'text' === $popup_type ) {
                ?>
	                    <a class="<?php 
                echo  esc_attr( $size_chart_get_button_class ) ;
                ?> md-size-chart-btn" chart-data-id="chart-<?php 
                echo  esc_attr( $chart_id ) ;
                ?>" href="javascript:void(0);" id="chart-button">
	                        <?php 
                echo  wp_kses_post( $popup_label ) ;
                ?>
	                    </a>
	                <?php 
            } else {
                ?>
	                    <button class="<?php 
                echo  esc_attr( $size_chart_get_button_class ) ;
                ?> button md-size-chart-btn" chart-data-id="chart-<?php 
                echo  esc_attr( $chart_id ) ;
                ?>"><?php 
                echo  wp_kses_post( $popup_label ) ;
                ?></button>
	                <?php 
            }
            
            ?>
	            </div>
	            <div id="md-size-chart-modal" class="md-size-chart-modal scfw-size-chart-modal <?php 
            echo  esc_attr( 'scfw-size-chart-popup-' . $popup_position ) ;
            ?>" chart-data-id="chart-<?php 
            echo  esc_attr( $chart_id ) ;
            ?>">
	                <div class="md-size-chart-modal-content">
            			<div class="md-size-chart-overlay"></div>
	                    <div class="md-size-chart-modal-body <?php 
            echo  esc_attr( scfw_size_chart_get_size() ) ;
            ?> <?php 
            echo  ( !empty($size_chart_style) && 'tab_style' === $size_chart_style ? esc_attr( 'scfw_tab_style' ) : '' ) ;
            ?>" id="md-poup">
	                        <?php 
            $file_dir_path = 'includes/common-files/size-chart-contents.php';
            if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                include plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
            }
            ?>
	                    </div>
	                </div>
	            </div>
	        </div>
            <?php 
        }
    
    }
    
    /**
     * Shortcode to display chart button.
     * 
     * @since 1.0.0
     */
    public static function scfw_size_chart_popup_shortcode_callback()
    {
        if ( is_admin() || is_customize_preview() || true === apply_filters( 'scfw_shortcode_callback_return', false ) ) {
            return;
        }
        global  $post ;
        $dup_id = array();
        $prod_id = scfw_size_chart_get_product( $post->ID );
        $prod_id = ( is_array( $prod_id ) ? $prod_id : [ $prod_id ] );
        $plugin_post_type_name = 'size-chart';
        $plugin_name = __( SCFW_PLUGIN_NAME, 'size-chart-for-woocommerce' );
        $plugin_version = __( SCFW_PLUGIN_VERSION, 'size-chart-for-woocommerce' );
        $cls = new SCFW_Size_Chart_For_Woocommerce_Public( $plugin_name, $plugin_version, $plugin_post_type_name );
        if ( isset( $prod_id ) && is_array( $prod_id ) && !empty($prod_id) ) {
            foreach ( $prod_id as $prod_val ) {
                
                if ( '' !== get_post_status( $prod_val ) && 'publish' === get_post_status( $prod_val ) ) {
                    $chart_id = $prod_val;
                } else {
                    $chart_id = $cls->scfw_size_chart_id_by_category( $post->ID );
                }
                
                // Check if product is belongs to tag
                
                if ( 0 === intval( $chart_id ) || !$chart_id ) {
                    $chart_id = $cls->scfw_size_chart_id_by_tag( $post->ID );
                    // Check if product is belongs to attribute
                    if ( 0 === intval( $chart_id ) || !$chart_id ) {
                        $chart_id = $cls->scfw_size_chart_id_by_attributes( $post->ID );
                    }
                }
                
                $chart_label = scfw_size_chart_get_label_by_chart_id( $chart_id );
                $chart_position = scfw_size_chart_get_position_by_chart_id( $chart_id );
                $link_show = true;
                // This will work in pro version
                
                if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
                    $current_country = $cls->scfw_get_current_user_country__premium_only();
                    $chart_country = scfw_size_chart_country__premium_only( $chart_id );
                    $link_show = false;
                    if ( empty($chart_country) || in_array( $current_country, $chart_country, true ) ) {
                        $link_show = true;
                    }
                }
                
                
                if ( 0 !== $chart_id && 'popup' === $chart_position && $link_show ) {
                    $chart_popup_label = scfw_size_chart_get_popup_label_by_chart_id( $chart_id );
                    
                    if ( isset( $chart_popup_label ) && !empty($chart_popup_label) ) {
                        $popup_label = $chart_popup_label;
                    } else {
                        $size_chart_popup_label = scfw_size_chart_get_popup_label();
                        
                        if ( isset( $size_chart_popup_label ) && !empty($size_chart_popup_label) ) {
                            $popup_label = $size_chart_popup_label;
                        } else {
                            $popup_label = $chart_label;
                        }
                    
                    }
                    
                    $size_chart_get_button_class = '';
                    $popup_position = 'center';
                    $chart_popup_type = scfw_size_chart_get_popup_type_by_chart_id( $chart_id );
                    
                    if ( isset( $chart_popup_type ) && !empty($chart_popup_type) && 'global' !== $chart_popup_type ) {
                        $popup_type = $chart_popup_type;
                    } else {
                        $size_chart_popup_type = scfw_size_chart_get_popup_type();
                        if ( isset( $size_chart_popup_type ) && !empty($size_chart_popup_type) ) {
                            $popup_type = $size_chart_popup_type;
                        }
                    }
                    
                    $chart_popup_icon = scfw_size_chart_get_popup_icon_by_chart_id( $chart_id );
                    if ( !empty($chart_popup_icon) ) {
                        $popup_label = sprintf( __( '<span class="dashicons"><img src="%1$s" alt="%2$s" /></span>', 'size-chart-for-woocommerce' ), esc_url( SCFW_PLUGIN_URL . 'includes/chart-icons/' . $chart_popup_icon . '.svg' ), $chart_popup_icon ) . $popup_label;
                    }
                    ?>
					<div class="scfw-size-chart-main md-size-chart-modal-main scfw-by-general-shortcode">
						<div class="button-wrapper">
                            <?php 
                    
                    if ( 'text' === $popup_type ) {
                        ?>
                                <a class="<?php 
                        echo  esc_attr( $size_chart_get_button_class ) ;
                        ?> md-size-chart-btn" chart-data-id="chart-<?php 
                        echo  esc_attr( $chart_id ) ;
                        ?>" href="javascript:void(0);">
                                    <?php 
                        echo  wp_kses_post( $popup_label ) ;
                        ?>
                                </a>
                            <?php 
                    } else {
                        ?>
                                <button class="<?php 
                        echo  esc_attr( $size_chart_get_button_class ) ;
                        ?> button md-size-chart-btn" chart-data-id="chart-<?php 
                        echo  esc_attr( $chart_id ) ;
                        ?>"><?php 
                        echo  wp_kses_post( $popup_label ) ;
                        ?></button>
                            <?php 
                    }
                    
                    ?>
                        </div>
						<div id="md-size-chart-modal" chart-data-id="chart-<?php 
                    echo  esc_attr( $chart_id ) ;
                    ?>" class="md-size-chart-modal scfw-size-chart-modal <?php 
                    echo  esc_attr( 'scfw-size-chart-popup-' . $popup_position ) ;
                    ?>">
							<div class="md-size-chart-modal-content">
								<div class="md-size-chart-overlay"></div>
								<div class="md-size-chart-modal-body <?php 
                    echo  esc_attr( scfw_size_chart_get_size() ) ;
                    ?>" id="md-poup">
									<?php 
                    $file_dir_path = 'includes/common-files/size-chart-contents.php';
                    if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                        include plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
                    }
                    ?>
								</div>
							</div>
						</div>
					</div>
					<?php 
                    $dup_id[] = $prod_val;
                }
            
            }
        }
        
        if ( isset( $post->ID ) && !empty($post->ID) ) {
            $chart_ids = $cls->scfw_size_chart_id_by_category( $post->ID );
            if ( !empty($chart_ids) ) {
                foreach ( $chart_ids as $chart_id ) {
                    
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $cls->scfw_size_chart_popup_button_area( $chart_id );
                        $dup_id[] = $chart_id;
                    }
                
                }
            }
            $chart_tag_id = $cls->scfw_size_chart_id_by_tag( $post->ID );
            if ( !empty($chart_tag_id) ) {
                foreach ( $chart_tag_id as $chart_id ) {
                    
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $cls->scfw_size_chart_popup_button_area( $chart_id );
                        $dup_id[] = $chart_id;
                    }
                
                }
            }
            $chart_attr_id = $cls->scfw_size_chart_id_by_attributes( $post->ID );
            if ( !empty($chart_attr_id) ) {
                foreach ( $chart_attr_id as $chart_id ) {
                    if ( !in_array( $chart_id, $dup_id, true ) ) {
                        $cls->scfw_size_chart_popup_button_area( $chart_id );
                    }
                }
            }
        }
    
    }
    
    /**
     * Check if product belongs to a category.
     *
     * @param int $product_id product id.
     *
     * @return bool|int|mixed return size chart id if size chart id found.
     * @since 1.0.0
     */
    public function scfw_size_chart_id_by_category( $product_id )
    {
        $size_chart_id = 0;
        $product_terms = wc_get_product_term_ids( $product_id, 'product_cat' );
        
        if ( isset( $product_terms ) && !empty($product_terms) && (is_array( $product_terms ) && array_filter( $product_terms )) ) {
            $cache_key = 'size_chart_categories_with_product_categories_' . implode( "_", $product_terms );
            $size_chart_id = wp_cache_get( $cache_key );
            
            if ( false === $size_chart_id ) {
                $size_chart_args = array(
                    'posts_per_page'         => 10,
                    'order'                  => 'DESC',
                    'post_type'              => 'size-chart',
                    'post_status'            => 'publish',
                    'no_found_rows'          => true,
                    'update_post_term_cache' => false,
                    'fields'                 => 'ids',
                );
                $size_chart_args['meta_query']['relation'] = 'OR';
                foreach ( $product_terms as $product_term ) {
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-categories',
                        'value'   => "[{$product_term},",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-categories',
                        'value'   => ",{$product_term},",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-categories',
                        'value'   => ",{$product_term}]",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-categories',
                        'value'   => "[{$product_term}]",
                        'compare' => 'LIKE',
                    );
                }
                $size_chart_category_query = new WP_Query( $size_chart_args );
                if ( isset( $size_chart_category_query ) && !empty($size_chart_category_query) && $size_chart_category_query->have_posts() ) {
                    foreach ( $size_chart_category_query->posts as $chart_array_id ) {
                        if ( !is_array( $size_chart_id ) ) {
                            $size_chart_id = [];
                        }
                        $size_chart_id[] = $chart_array_id;
                    }
                }
                wp_cache_set( $cache_key, $size_chart_id );
            }
        
        }
        
        return $size_chart_id;
    }
    
    /**
     * Check if product belongs to a tag.
     *
     * @param int $product_id product id.
     *
     * @return bool|int|mixed return size chart id if size chart id found.
     * @since 1.0.0
     */
    public function scfw_size_chart_id_by_tag( $product_id )
    {
        $size_chart_id = 0;
        $product_terms = wc_get_product_term_ids( $product_id, 'product_tag' );
        
        if ( isset( $product_terms ) && !empty($product_terms) && (is_array( $product_terms ) && array_filter( $product_terms )) ) {
            $cache_key = 'size_chart_tags_with_product_tags_' . implode( "_", $product_terms );
            $size_chart_id = wp_cache_get( $cache_key );
            
            if ( false === $size_chart_id ) {
                $size_chart_args = array(
                    'posts_per_page'         => 10,
                    'order'                  => 'DESC',
                    'post_type'              => 'size-chart',
                    'post_status'            => 'publish',
                    'no_found_rows'          => true,
                    'update_post_term_cache' => false,
                    'fields'                 => 'ids',
                );
                $size_chart_args['meta_query']['relation'] = 'OR';
                foreach ( $product_terms as $product_term ) {
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-tags',
                        'value'   => "[{$product_term},",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-tags',
                        'value'   => ",{$product_term},",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-tags',
                        'value'   => ",{$product_term}]",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-tags',
                        'value'   => "[{$product_term}]",
                        'compare' => 'LIKE',
                    );
                }
                $size_chart_tags_query = new WP_Query( $size_chart_args );
                if ( isset( $size_chart_tags_query ) && !empty($size_chart_tags_query) && $size_chart_tags_query->have_posts() ) {
                    foreach ( $size_chart_tags_query->posts as $chart_array_id ) {
                        if ( !is_array( $size_chart_id ) ) {
                            $size_chart_id = [];
                        }
                        $size_chart_id[] = $chart_array_id;
                    }
                }
                wp_cache_set( $cache_key, $size_chart_id );
            }
        
        }
        
        return $size_chart_id;
    }
    
    /**
     * Check if product belongs to a attributes.
     *
     * @param int $product_id product id.
     *
     * @return bool|int|mixed return size chart id if size chart id found.
     * @since 1.0.0
     */
    public function scfw_size_chart_id_by_attributes( $product_id )
    {
        $size_chart_id = 0;
        $product = wc_get_product( $product_id );
        if ( !is_a( $product, 'WC_Product' ) ) {
            return;
        }
        $product_attributes = $product->get_attributes();
        $product_terms = [];
        foreach ( $product_attributes as $attribute ) {
            if ( is_object( $attribute ) && !empty($attribute->get_options()) ) {
                $product_terms = array_merge( $product_terms, $attribute->get_options() );
            }
        }
        
        if ( isset( $product_terms ) && !empty($product_terms) && (is_array( $product_terms ) && array_filter( $product_terms )) ) {
            $cache_key = 'size_chart_attributes_with_product_attributes_' . implode( "_", $product_terms );
            $size_chart_id = wp_cache_get( $cache_key );
            
            if ( false === $size_chart_id ) {
                $size_chart_args = array(
                    'posts_per_page'         => 10,
                    'order'                  => 'DESC',
                    'post_type'              => 'size-chart',
                    'post_status'            => 'publish',
                    'no_found_rows'          => true,
                    'update_post_term_cache' => false,
                    'fields'                 => 'ids',
                );
                $size_chart_args['meta_query']['relation'] = 'OR';
                foreach ( $product_terms as $product_term ) {
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-attributes',
                        'value'   => "[{$product_term},",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-attributes',
                        'value'   => ",{$product_term},",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-attributes',
                        'value'   => ",{$product_term}]",
                        'compare' => 'LIKE',
                    );
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-attributes',
                        'value'   => "[{$product_term}]",
                        'compare' => 'LIKE',
                    );
                }
                $size_chart_attributes_query = new WP_Query( $size_chart_args );
                if ( isset( $size_chart_attributes_query ) && !empty($size_chart_attributes_query) && $size_chart_attributes_query->have_posts() ) {
                    foreach ( $size_chart_attributes_query->posts as $chart_array_id ) {
                        if ( !is_array( $size_chart_id ) ) {
                            $size_chart_id = [];
                        }
                        $size_chart_id[] = $chart_array_id;
                    }
                }
                wp_cache_set( $cache_key, $size_chart_id );
            }
        
        }
        
        return $size_chart_id;
    }
    
    /**
     * Create and get the inline style.
     *
     * @return bool|string Inline style string.
     */
    public function scfw_get_inline_style_for_size_chart()
    {
        global  $post ;
        
        if ( isset( $post ) && !empty($post) ) {
            $prod_id = scfw_size_chart_get_product( $post->ID );
            $prod_id = ( is_array( $prod_id ) ? $prod_id : [ $prod_id ] );
            $cs_style = '';
            if ( isset( $prod_id ) && is_array( $prod_id ) && !empty($prod_id) ) {
                foreach ( $prod_id as $prod_val ) {
                    $cs_style .= scfw_size_chart_get_inline_styles_by_post_id( $prod_val );
                }
            }
            
            if ( isset( $post->ID ) && !empty($post->ID) ) {
                $chart_ids = $this->scfw_size_chart_id_by_category( $post->ID );
                if ( !empty($chart_ids) ) {
                    foreach ( $chart_ids as $chart_id ) {
                        $cs_style .= scfw_size_chart_get_inline_styles_by_post_id( $chart_id );
                    }
                }
                $chart_tag_id = $this->scfw_size_chart_id_by_tag( $post->ID );
                if ( !empty($chart_tag_id) ) {
                    foreach ( $chart_tag_id as $chart_id ) {
                        $cs_style .= scfw_size_chart_get_inline_styles_by_post_id( $chart_id );
                    }
                }
                $chart_attr_id = $this->scfw_size_chart_id_by_attributes( $post->ID );
                if ( !empty($chart_attr_id) ) {
                    foreach ( $chart_attr_id as $chart_id ) {
                        $cs_style .= scfw_size_chart_get_inline_styles_by_post_id( $chart_id );
                    }
                }
                return $cs_style;
            }
        
        }
        
        return false;
    }

}
/** Added new shortcode for size chart link */
add_shortcode( 'scfw_product_size_chart', 'scfw_size_chart_link_shortcode' );
function scfw_size_chart_link_shortcode()
{
    ob_start();
    echo  esc_html( SCFW_Size_Chart_For_Woocommerce_Public::scfw_size_chart_popup_shortcode_callback() ) ;
    return ob_get_clean();
}
