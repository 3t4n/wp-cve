<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.rosendo.pt
 * @since      1.0.0
 *
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/admin
 * @author     David Rosendo <david@rosendo.pt>
 */
class Smart_Variations_Images_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * The plugin options.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $reduxOptions    The current plugin options.
     */
    private  $options ;
    private  $videos ;
    private  $product ;
    private  $attributes ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version, $reduxOptions )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->options = $reduxOptions;
        $this->runSitePress = false;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        $screen = get_current_screen();
        if ( $screen->post_type == 'product' && $screen->base == 'post' || strpos( $screen->base, 'woosvi-options-settings' ) !== false ) {
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/smart-variations-images-admin' . SMART_SCRIPT_DEBUG . '.css',
                array(),
                $this->version,
                'all'
            );
        }
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        
        if ( $screen->post_type == 'product' && $screen->base == 'post' || strpos( $screen->base, 'woosvi-options-settings' ) !== false ) {
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/smart-variations-images-admin' . SMART_SCRIPT_DEBUG . '.js',
                array( 'jquery', 'jquery-ui-core' ),
                $this->version,
                false
            );
        }
    
    }
    
    /**
     * Returns an array of images present in the Product Gallery
     *
     *
     * @since 1.0.0
     * @return array
     */
    public function getProductGallery( $pid, $returnUrl = false )
    {
        $product_image_gallery = array();
        
        if ( metadata_exists( 'post', $pid, '_product_image_gallery' ) ) {
            $product_image_gallery = explode( ',', get_post_meta( $pid, '_product_image_gallery', true ) );
        } else {
            // Backwards compat
            $attachment_ids = get_posts( 'post_parent=' . $pid . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0' );
            $attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
            if ( $attachment_ids ) {
                $product_image_gallery = $attachment_ids;
            }
        }
        
        if ( count( $product_image_gallery ) < 1 ) {
            return false;
        }
        $product_image_gallery = array_filter( $product_image_gallery );
        if ( $returnUrl ) {
            foreach ( $product_image_gallery as $k => $v ) {
                $product_image_gallery[$k] = array(
                    'id'  => $v,
                    'url' => array_pop( explode( '/', wp_get_attachment_url( $v ) ) ),
                );
            }
        }
        return $product_image_gallery;
    }
    
    /**
     * Runs the fallback
     *
     *
     * @since 1.0.0
     */
    public function fallback( $pid )
    {
        $return = '';
        $product_image_gallery = $this->getProductGallery( $pid );
        if ( !$product_image_gallery ) {
            return;
        }
        $order = array();
        foreach ( $product_image_gallery as $key => $value ) {
            $woosvi_slug = get_post_meta( $value, 'woosvi_slug_' . $pid, true );
            
            if ( is_array( $woosvi_slug ) ) {
                $data = array();
                foreach ( $woosvi_slug as $k => $v ) {
                    
                    if ( count( $v ) > 1 ) {
                        $data[] = implode( '_svipro_', $v );
                    } else {
                        $data[] = $v;
                    }
                
                }
                $woosvi_slug = $data;
            }
            
            if ( !$woosvi_slug ) {
                $woosvi_slug = get_post_meta( $value, 'woosvi_slug', true );
            }
            if ( !$woosvi_slug ) {
                $woosvi_slug = 'nullsvi';
            }
            
            if ( is_array( $woosvi_slug ) ) {
                foreach ( $woosvi_slug as $k => $v ) {
                    
                    if ( is_array( $v ) ) {
                        $order[$v[0]][] = $value;
                    } else {
                        $order[$v][] = $value;
                    }
                
                }
            } else {
                $order[$woosvi_slug][] = $value;
            }
        
        }
        unset( $order['nullsvi'] );
        $ordered = array();
        foreach ( $order as $k => $v ) {
            $arr = array(
                'slugs' => explode( '_svipro_', $k ),
                'imgs'  => $v,
            );
            array_push( $ordered, $arr );
        }
        update_post_meta( $pid, 'woosvi_slug', $ordered );
    }
    
    /**
     * Returns an array with available attributes
     *
     *
     * @since 1.0.0
     * @return array
     */
    public function getAttributes( $attributes, $pid )
    {
        $data = array();
        if ( count( $attributes ) > 0 ) {
            foreach ( $attributes as $att => $attribute ) {
                
                if ( $attribute['is_taxonomy'] && $attribute['is_variation'] ) {
                    $terms = wp_get_post_terms( $pid, urldecode( $att ), 'all' );
                    if ( !empty($terms) ) {
                        foreach ( $terms as $tr => $term ) {
                            $data[strtolower( esc_attr( $term->slug ) )] = trim( esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) );
                        }
                    }
                } elseif ( !$attribute['is_taxonomy'] && $attribute['is_variation'] ) {
                    $terms = explode( '|', $attribute['value'] );
                    foreach ( $terms as $tr => $term ) {
                        $data[sanitize_title( $term )] = trim( esc_html( apply_filters( 'woocommerce_variation_option_name', $term ) ) );
                    }
                }
            
            }
        }
        return array_filter( $data );
    }
    
    /**
     * Returns an array with images that have Variations Assigned
     *
     *
     * @since 1.0.0
     * @return array
     */
    public function getImagesAssignedWithVariations( $pid, $woosvi_slug = array() )
    {
        $asigned_svi = array();
        $product_image_gallery = array();
        if ( !empty($woosvi_slug) && count( $woosvi_slug ) > 0 ) {
            foreach ( $woosvi_slug as $k => $v ) {
                $asigned_svi = array_unique( array_merge( $asigned_svi, $v['imgs'] ) );
            }
        }
        
        if ( metadata_exists( 'post', $pid, '_product_image_gallery' ) ) {
            $product_image_gallery = explode( ',', get_post_meta( $pid, '_product_image_gallery', true ) );
        } else {
            // Backwards compat
            $attachment_ids = get_posts( 'post_parent=' . $pid . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0' );
            $attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
            if ( $attachment_ids ) {
                $product_image_gallery = $attachment_ids;
            }
        }
        
        return array_diff( $product_image_gallery, $asigned_svi );
    }
    
    /**
     * Clean names to prevent breaks
     *
     * @return void
     */
    public function woosvi_esc_html()
    {
        header( "Content-type: application/json" );
        $slug = $_POST['data'];
        echo  json_encode( esc_html( implode( '_svipro_', $slug ) ) ) ;
        die;
    }
    
    /**
     * Insert a value or key/value pair after a specific key in an array.  If key doesn't exist, value is appended
     * to the end of the array.
     *
     * @param array $array
     * @param string $key
     * @param array $new
     *
     * @return array
     */
    function array_insert_after( array $array, $key, array $new )
    {
        $keys = array_keys( $array );
        $index = array_search( $key, $keys );
        $pos = ( false === $index ? count( $array ) : $index + 1 );
        return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
    }
    
    /**
     * Display button to help build SVI Gallery
     */
    public function variation_btn_builder( $loop, $variation_data, $variation )
    {
        echo  '<div class="svi-variation-gallery">' ;
        echo  '<a href="#" class="button button-primary svi-add-additional-images">' . __( 'Create additional images gallery', 'svi' ) . '</a>' ;
        echo  '</div>' ;
        echo  wc_help_tip( __( 'SVI Gallery will be created based on first attribute. SVI makes no use of the main variation image set for this variation. Use above variation image for other integrations.', 'svi' ) ) ;
    }
    
    /**
     * Add option to disable SVI from running in product
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function sviDisableProduct_advancedTab()
    {
        echo  '<div class="options_group">' ;
        // Checkbox
        woocommerce_wp_checkbox( array(
            'id'          => '_checkbox_svipro_enabled',
            'label'       => __( 'Disable SVI', 'wc_svi' ),
            'description' => __( 'Activating this option will make the product load the default theme image gallery and functions', 'wc_svi' ),
        ) );
        echo  '</div>' ;
    }
    
    /**
     * Add tab to WooCommerce Product
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function images_section( $tabs )
    {
        $svi_tab = array(
            'svi_variations' => array(
            'label'    => __( 'SVI Variations Gallery', 'svi' ),
            'target'   => 'svi-images_tab_data',
            'class'    => array( 'variations_tab', 'show_if_variable' ),
            'priority' => 61,
        ),
        );
        //echo '<li class="box_tab show_if_variable"><a href="#sviproimages_tab_data" id="svibulkbtn"><span>' . __('SVI <b>Variations Gallery</b>', 'svi') . '</span></a></li>';
        return $this->array_insert_after( $tabs, 'variations', $svi_tab );
    }
    
    /**
     * Prep data for storing
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function sviSaveData( $post_id )
    {
        // Checkbox
        $woocommerce_checkbox = ( isset( $_POST['_checkbox_svipro_enabled'] ) ? 'yes' : 'no' );
        update_post_meta( $post_id, '_checkbox_svipro_enabled', $woocommerce_checkbox );
        $this->save_woosvibulk_meta( $post_id );
    }
    
    /**
     * Saves the variation information on Save
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function save_woosvibulk_meta( $post_id )
    {
        $post_type = get_post_type( $post_id );
        if ( "product" != $post_type ) {
            return;
        }
        $attachment_ids = ( isset( $_POST['product_image_gallery'] ) ? array_unique( array_filter( explode( ',', wc_clean( $_POST['product_image_gallery'] ) ) ) ) : array() );
        if ( empty($attachment_ids) ) {
            delete_post_meta( $post_id, 'woosvi_slug' );
        }
        foreach ( $attachment_ids as $key => $value ) {
            delete_post_meta( $value, 'woosvi_slug' );
        }
        $bulk_video = false;
        $ordered = array();
        
        if ( $_POST['product-type'] == 'simple' ) {
            $arr = array(
                'imgs' => $attachment_ids,
            );
            array_push( $ordered, $arr );
        } else {
            if ( !isset( $_POST['sviproduct_image_gallery'] ) && !$bulk_video ) {
                return;
            }
            
            if ( isset( $_POST['sviproduct_image_gallery'] ) ) {
                $bulk = $_POST['sviproduct_image_gallery'];
                $keys = array();
                if ( array_key_exists( 'nullsvi', $bulk ) ) {
                    if ( $bulk['nullsvi'] ) {
                        foreach ( $bulk['nullsvi'] as $value ) {
                            $ids = explode( ',', wc_clean( $value ) );
                            foreach ( $ids as $id ) {
                                delete_post_meta( $id, 'woosvi_slug' );
                            }
                        }
                    }
                }
                if ( array_key_exists( 'nullsvi', $bulk ) ) {
                    unset( $bulk['nullsvi'] );
                }
                if ( array_key_exists( 'unsigned_svi', $bulk ) ) {
                    unset( $bulk['unsigned_svi'] );
                }
                //fs_ddd($bulk_video);
                foreach ( $bulk as $k => $v ) {
                    
                    if ( !empty($v) ) {
                        $arr = array(
                            'slugs' => explode( '_svipro_', $k ),
                            'imgs'  => explode( ',', $v ),
                        );
                        array_push( $ordered, $arr );
                    }
                
                }
            }
        
        }
        
        $woosvi_slug = $this->fixOrder( $ordered );
        $product = wc_get_product( $post_id );
        $product->update_meta_data( 'woosvi_slug', $woosvi_slug );
        $product->save();
    }
    
    /**
     * Build tab content on WooCommerce Product
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function images_settings()
    {
        echo  '<div id="svi-images_tab_data" class="panel woocommerce_options_panel wc-metaboxes-wrapper">' ;
        echo  '<div class="wc-metabox">' ;
        $this->build_sviTab();
        echo  '</div>' ;
        echo  '</div>' ;
    }
    
    public function load_variations( $pid )
    {
        $product_id = intval( $pid );
        $post = get_post( $product_id );
        // phpcs:ignore
        $product_object = wc_get_product_object( 'variable', $product_id );
        // Forces type to variable in case product is unsaved.
        $variation_object = wc_get_product_object( 'variation' );
        $variation_object->set_parent_id( $product_id );
        $variation_object->set_attributes( array_fill_keys( array_map( 'sanitize_title', array_keys( $product_object->get_variation_attributes() ) ), '' ) );
        $attribute_values = $variation_object->get_attributes( 'edit' );
        $selected_values = [];
        foreach ( $product_object->get_attributes( 'edit' ) as $attribute ) {
            if ( !$attribute->get_variation() ) {
                continue;
            }
            $attr_slug = ( isset( $attribute_values[sanitize_title( $attribute->get_name() )] ) ? sanitize_title( $attribute->get_name() ) : false );
            if ( $attr_slug && $attr_slug != '' ) {
                $selected_values[$attr_slug] = array(
                    'name'         => $attr_slug,
                    'position'     => 0,
                    'is_visible'   => ( $attribute->get_visible() ? 1 : 0 ),
                    'is_variation' => ( $attribute->get_variation() ? 1 : 0 ),
                    'is_taxonomy'  => ( $attribute->is_taxonomy() ? 1 : 0 ),
                    'value'        => ( $attribute->is_taxonomy() ? '' : wc_implode_text_attributes( $attribute->get_options() ) ),
                );
            }
        }
        return $selected_values;
    }
    
    /**
     * Returns a new Select Dropdown jus tin case there is new attributes
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function reloadSelect_json()
    {
        $pid = $_POST['data'];
        //$attributes = get_post_meta($pid, '_product_attributes', true);
        $attributes = $this->load_variations( $pid );
        $sviBody = HtmlTag::createElement( 'div' );
        /*if (count($attributes) < 1) {
        			//ALERT IF NO ATTRIBUTES EXIST
        			$sviBody = $this->build_sviTabNotice($sviBody);
        
        			echo $sviBody;
        			die();
        		}*/
        $this->build_sviAttributesSelector( $sviBody, $attributes, $pid );
        echo  $sviBody ;
        die;
    }
    
    /**
     * Output the SVI product tab content
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function build_sviTab()
    {
        global  $post ;
        //$attributes = get_post_meta($post->ID, '_product_attributes');
        $this->attributes = $this->load_variations( $post->ID );
        $sviBody = HtmlTag::createElement( 'div' )->set( 'class', 'svi-admin-body' );
        /*if (count($attributes) < 1) {
        			//ALERT IF NO ATTRIBUTES EXIST
        			$sviBody = $this->build_sviTabNotice($sviBody);
        
        			echo $sviBody;
        			return;
        		}*/
        $sviBody = $this->build_sviMetaBox( $sviBody );
        $this->build_sviAttributesForm( $sviBody, $this->attributes );
        $this->build_sviAttributesExplain( $sviBody );
        $this->build_sviGallery( $sviBody );
        $this->build_sviClone( $sviBody );
        echo  $sviBody ;
    }
    
    /**
     * Output notice of no Attributes
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function build_sviTabNotice( $sviBody )
    {
        return $sviBody->addElement( 'div' )->set( 'class', 'inline notice woocommerce-message' )->addElement( 'p' )->text( __( 'Before you can assign images to a variation you need to add some variation attributes on the <strong>Attributes</strong> tab and <b>save the product<b>.', 'svi' ) );
    }
    
    /**
     * Build the main DIV to hold the content for the TAB
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function build_sviMetaBox( $sviBody )
    {
        return $sviBody->addElement( 'div' )->set( 'class', 'wc-metabox-content' );
    }
    
    /**
     * Output the HTML that will allow the selections to build galleries
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function build_sviAttributesForm( $sviBody, $attributes, $select_only = false )
    {
        global  $post ;
        $sviBody = $sviBody->addElement( 'div' )->set( 'class', 'svi-table' );
        if ( !$select_only ) {
            $sviBody->addElement( 'div' )->set( 'class', 'svi-table-cell' )->addElement( 'label' )->set( 'for', 'sviprobulk' )->text( __( 'Assign Images to:', 'svi' ) );
        }
        $sviBody_BuildSelect = $sviBody->addElement( 'div' )->set( 'class', 'svi-table-cell' );
        $existing = $this->build_sviAttributesSelector(
            $sviBody_BuildSelect,
            $attributes,
            $post->id,
            $select_only
        );
        if ( !$select_only ) {
            $sviBody->addElement( 'div' )->set( 'class', 'svi-table-cell' )->addElement( 'button' )->set( 'id', 'addsviprovariation' )->set( 'class', 'button fr plus' )->text( __( 'Add', 'svi' ) );
        }
        return $existing;
    }
    
    /**
     * Output the SELECT with available atributes
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function build_sviAttributesSelector(
        $sviBody_BuildSelect,
        $attributes,
        $pid,
        $extras = false
    )
    {
        
        if ( !$extras ) {
            $sviBody_select = $sviBody_BuildSelect->addElement( 'div' )->id( 'sviselect_container' )->addElement( 'select' )->id( 'sviprobulk' );
        } else {
            $sviBody_select = $sviBody_BuildSelect->addElement( 'div' )->id( 'sviselect_container_' . $extras )->addElement( 'select' )->id( 'sviprobulk_' . $extras );
        }
        
        $existing = [];
        $options = $sviBody_select->set( 'multiple', 'multiple' );
        $options->addElement( 'option' )->set( 'value', 'svidefault' )->text( __( 'Default Gallery', 'svi' ) );
        if ( !$extras ) {
            $existing[] = 'svidefault';
        }
        $is_variation = false;
        foreach ( $attributes as $att => $attribute ) {
            $is_variation = $attribute['is_variation'];
            
            if ( $attribute['is_taxonomy'] && $is_variation ) {
                $terms = wp_get_post_terms( $pid, urldecode( $att ), 'all' );
                
                if ( !empty($terms) ) {
                    $tax = get_taxonomy( $att );
                    $optgroup = $options->addElement( 'optgroup' )->set( 'label', $tax->label )->set( 'data-svilabel', sanitize_title( $tax->label ) );
                    foreach ( $terms as $term ) {
                        $optgroup->addElement( 'option' )->set( 'value', esc_attr( $term->slug ) )->text( esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) );
                        $existing[] = esc_attr( $term->slug );
                    }
                }
            
            } elseif ( !$attribute['is_taxonomy'] && $is_variation ) {
                $terms = explode( '|', $attribute['value'] );
                $optgroup = $options->addElement( 'optgroup' )->set( 'label', $attribute['name'] )->set( 'data-svilabel', sanitize_title( $attribute['name'] ) );
                foreach ( $terms as $term ) {
                    $optgroup->addElement( 'option' )->set( 'value', sanitize_title( $term ) )->text( esc_html( apply_filters( 'woocommerce_variation_option_name', $term ) ) );
                    $existing[] = sanitize_title( $term );
                }
            }
        
        }
        return $existing;
    }
    
    /**
     * Output Badges with information
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function build_sviAttributesExplain( $sviBody )
    {
        $sviBody = $sviBody->addElement( 'div' )->set( 'class', 'svi-table' );
        $table_cell = $sviBody->addElement( 'div' )->set( 'class', 'svi-table-cell' );
        $this->build_sviBadge(
            $table_cell,
            'info',
            'Default Gallery',
            'Use this option to assign a default gallery to be displayed. All other images will be hidden until match occours.'
        );
        $table_cell->addElement( 'div' )->text( __( '<br><b>PRO VERSION OPTIONS:</b>', 'svi' ) );
        $this->build_sviBadge(
            $table_cell,
            'warning',
            'SVI Global',
            'Use this variation to assign global images to be showed in all variations.'
        );
        //return $sviBody;
    }
    
    /**
     * Output single badge
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function build_sviBadge(
        $table_cell,
        $type = '',
        $badge_text = '',
        $msg = ''
    )
    {
        $sviBadge = $table_cell->addElement( 'div' )->set( 'class', 'svibadge_wrapper' )->addElement( 'small' );
        $sviBadge->addElement( 'span' )->set( 'class', 'svibadge svibadge-' . $type )->text( __( $badge_text, 'svi' ) );
        $sviBadge->text( __( $msg, 'svi' ) );
        return $sviBadge;
    }
    
    /**
     * Output The current SVI galleries created
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function build_sviGallery( $sviBody )
    {
        global  $post ;
        $pid = $post->ID;
        $return = '';
        $errors = [];
        $title = '';
        $this->product = wc_get_product( $pid );
        $svigallery = $sviBody->addElement( 'div' )->id( 'svigallery' );
        $woosvi_slug = get_post_meta( $pid, 'woosvi_slug', true );
        // GET CURRENT SVI VARIATIONS GALLERY CREATED
        //$attributes = get_post_meta($pid, '_product_attributes', true);  // GET CURRENT PRODUCT ATTRIBUTES
        $attributes = $this->load_variations( $pid );
        $theslugs = $this->getAttributes( $attributes, $pid );
        
        if ( empty($woosvi_slug) ) {
            $this->fallback( $pid );
            $woosvi_slug = get_post_meta( $pid, 'woosvi_slug', true );
        }
        
        if ( $woosvi_slug == '' || $woosvi_slug == '""' ) {
            $woosvi_slug = array();
        }
        $unsigned_svi = $this->getImagesAssignedWithVariations( $pid, $woosvi_slug );
        //GET PRODUCT IMAGES IN GALLERY NOT ASSOCIATED TO ANY VARIATION
        
        if ( !empty($unsigned_svi) ) {
            $title = "Images without assigned variations";
            $data_send = array(
                'slugs' => 'unsigned_svi',
                'imgs'  => $unsigned_svi,
            );
            $this->outputOrder( $svigallery, $title, $data_send );
        }
        
        
        if ( !empty($woosvi_slug) ) {
            $woosvi_slug = $this->fixOrder( $woosvi_slug );
            foreach ( $woosvi_slug as $key => $data ) {
                $break = false;
                
                if ( array_key_exists( 'slugs', $data ) ) {
                    $slugs = $data['slugs'];
                    $slugsCount = count( $slugs );
                    
                    if ( $slugsCount > 0 ) {
                        $slugs_name = [];
                        foreach ( $slugs as $s ) {
                            switch ( $s ) {
                                case 'sviproglobal':
                                    $slugs_name[] = 'SVI Global';
                                    $key = 'sviproglobal';
                                    break;
                                case 'svidefault':
                                    $slugs_name[] = 'Default';
                                    $key = 'svidefault';
                                    break;
                                default:
                                    $sLower = strtolower( $s );
                                    
                                    if ( array_key_exists( $sLower, $theslugs ) ) {
                                        $slugs_name[] = $theslugs[$sLower];
                                    } else {
                                        $bigger = 0;
                                        $keep = '';
                                        foreach ( $theslugs as $extra => $check ) {
                                            $sim = similar_text( $extra, $s, $perc );
                                            
                                            if ( $perc > $bigger ) {
                                                $bigger = $perc;
                                                $keep = $extra;
                                            }
                                        
                                        }
                                        $slugs_name[] = HtmlTag::createElement( 'span' )->set( 'class', 'dashicons dashicons-hidden' ) . ' ' . HtmlTag::createElement( 'span' )->text( $s );
                                        $errors[$s] = HtmlTag::createElement( 'span' )->text( " The atrribute <b><u>" . $s . "</u></b> is no longer available in WooCommerce for the created SVI gallery. It seems that you have either deleted it or changed the attribute slug. This gallery will not work and matching will not occur as a consequence. Please consider either deleting this gallery or selecting the correct new attribute for it." );
                                    }
                                    
                                    break;
                            }
                        }
                        $title = implode( ' + ', $slugs_name ) . ' Gallery';
                    } else {
                        $firstSlug = $slugs[0];
                        switch ( $firstSlug ) {
                            case 'sviproglobal':
                                $title = 'Global Gallery';
                                $key = 'sviproglobal';
                                break;
                            case 'svidefault':
                                $title = 'Default Gallery';
                                $key = 'svidefault';
                                break;
                            default:
                                $sLower = strtolower( $firstSlug );
                                
                                if ( array_key_exists( $sLower, $theslugs ) ) {
                                    $slugs_name = $theslugs[$sLower];
                                } else {
                                    $bigger = 0;
                                    foreach ( $theslugs as $extra => $check ) {
                                        $sim = similar_text( $extra, $firstSlug, $perc );
                                        
                                        if ( $perc > $bigger ) {
                                            $slugs_name = $check;
                                            $bigger = $perc;
                                        }
                                    
                                    }
                                }
                                
                                $title = $slugs_name . ' Gallery';
                                break;
                        }
                    }
                
                }
                
                $data['video'] = false;
                if ( !$break ) {
                    $this->outputOrder(
                        $svigallery,
                        $title,
                        $data,
                        $key,
                        $errors
                    );
                }
            }
        }
    
    }
    
    /**
     * Builds the output order for the variations
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function outputOrder(
        $svigallery,
        $title,
        $data,
        $key = 'x',
        $errors = array()
    )
    {
        $slugs = ( array_key_exists( 'slugs', $data ) && $data['slugs'] ? $data['slugs'] : false );
        $attachments = ( array_key_exists( 'imgs', $data ) && $data['imgs'] ? array_unique( $data['imgs'] ) : array() );
        $videos = ( array_key_exists( 'video', $data ) && $data['video'] ? $data['video'] : false );
        $loop_hidden = ( array_key_exists( 'loop_hidden', $data ) && $data['loop_hidden'] == true ? 'checked' : '' );
        $slug = $slugs;
        if ( is_array( $slugs ) ) {
            $slug = strtolower( implode( '_svipro_', $slugs ) );
        }
        
        if ( $slugs ) {
            $class = '';
            switch ( $title ) {
                case 'Default Gallery':
                    $class = 'svibadge svibadge-light';
                    break;
                case 'Images without assigned variations':
                    $class = 'svibadge svibadge-warning';
                    break;
            }
            $removegal = HtmlTag::createElement( 'a' )->set( 'href', '#/' )->set( 'class', 'svi-pullright sviprobulk_remove' )->addElement( 'span' )->set( 'class', 'dashicons dashicons-trash' );
            $unsigned_svi = '';
            
            if ( $slug == 'unsigned_svi' ) {
                $unsigned_svi = $slug;
                $removegal = '';
            }
            
            $h2class = 'dark';
            $title_display = HtmlTag::createElement( 'span' )->set( 'class', 'svititle ' . $class )->text( $title );
            $missingSlugEl = false;
            if ( is_array( $slugs ) ) {
                foreach ( $slugs as $slu => $find ) {
                    
                    if ( array_key_exists( $find, $errors ) ) {
                        $missingSlugEl = HtmlTag::createElement( 'div' )->set( 'class', 'notice notice-error inline' )->addElement( 'p' )->addElement( 'span' )->set( 'class', 'dashicons dashicons-warning' )->getParent()->text( $errors[$find] );
                        $h2class = 'danger';
                    }
                
                }
            }
            $svipro_gal = $svigallery->addElement( 'div' )->id( 'svipro_' . $key )->set( 'class', 'postbox svi-woocommerce-product-images' )->set( 'data-title', esc_attr( $title ) )->set( 'data-svigal', esc_html( $slug ) )->set( 'data-svikey', $key );
            $svipro_gal->addElement( 'h2' )->set( 'class', $h2class )->text( $title_display . $removegal );
            if ( $missingSlugEl ) {
                $svipro_gal->text( $missingSlugEl );
            }
            $inside = $svipro_gal->addElement( 'div' )->set( 'class', 'inside' );
            $product_images_container = $inside->addElement( 'div' )->set( 'class', 'svipro-product_images_container' );
            $ul = $product_images_container->addElement( 'ul' )->set( 'class', 'product_images ui-sortable product_galsort ' . $unsigned_svi );
            $product_image_gallery_svi = '';
            
            if ( count( $attachments ) > 0 ) {
                $attachments_clean_id = [];
                foreach ( $attachments as $attachment_id ) {
                    $attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );
                    // if attachment is empty skip
                    
                    if ( empty($attachment) ) {
                        $update_meta = true;
                        continue;
                    }
                    
                    array_push( $attachments_clean_id, $attachment_id );
                    $ul->addElement( 'li' )->set( 'class', 'image' )->set( 'data-attachment_id', esc_attr( $attachment_id ) )->text( $attachment )->addElement( 'ul' )->set( 'class', 'actions' )->addElement( 'li' )->addElement( 'a' )->set( 'href', '#/' )->set( 'class', 'delete tips' )->set( 'data-tip', esc_attr__( 'Delete image', 'woocommerce' ) )->text( __( 'Delete', 'woocommerce' ) );
                }
                $product_image_gallery_svi = implode( ',', $attachments_clean_id );
            }
            
            if ( $slug != 'unsigned_svi' ) {
                $ul->addElement( 'li' )->set( 'class', 'add_product_images_svipro  hide-if-no-js ui-state-disabled' )->addElement( 'a' )->set( 'href', '#/' )->set( 'data-choose', 'Add Images to Product Gallery' )->set( 'data-update', 'Add to gallery' )->set( 'data-delete', 'Delete image' )->set( 'data-text', 'Delete' )->addElement( 'span' )->set( 'class', 'dashicons dashicons-plus' );
            }
            $product_images_container->addElement( 'span' )->set( 'class', 'sviHiddenLoop' )->text( 'Hide from <b>Product Loop</b>: (PRO VERSION FEATURE)' );
            $product_images_container->addElement( 'input' )->set( 'class', 'svipro-product_image_gallery' )->set( 'name', 'sviproduct_image_gallery[' . esc_html( $slug ) . ']' )->set( 'value', $product_image_gallery_svi )->set( 'type', 'hidden' );
            if ( sanitize_title( $slugs[0] ) == 'svidefault' ) {
                $inside->addElement( 'p' )->addElement( 'b' )->text( __( 'NOTICE: All other images/galleries will be hidden until matching occours.', 'svi' ) );
            }
        }
        
        return $svigallery;
    }
    
    public function build_sviClone( $sviBody )
    {
        $removegal = HtmlTag::createElement( 'a' )->set( 'href', '#/' )->set( 'class', 'svi-pullright sviprobulk_remove' )->addElement( 'span' )->set( 'class', 'dashicons dashicons-trash' );
        $title_display = HtmlTag::createElement( 'span' )->set( 'class', 'svititle' )->text( 'Product Gallery' );
        $svipro_gal = $sviBody->addElement( 'div' )->id( 'svipro_clone' )->set( 'class', 'postbox svi-woocommerce-product-images hidden' )->set( 'data-title', 'Product Gallery' )->set( 'data-svigal', '' );
        $svipro_gal->addElement( 'h2' )->text( $title_display . $removegal );
        $inside = $svipro_gal->addElement( 'div' )->set( 'class', 'inside' );
        $product_images_container = $inside->addElement( 'div' )->set( 'class', 'svipro-product_images_container' );
        $ul = $product_images_container->addElement( 'ul' )->set( 'class', 'product_images ui-sortable product_galsort' );
        $ul->addElement( 'li' )->set( 'class', 'add_product_images_svipro  hide-if-no-js ui-state-disabled' )->addElement( 'a' )->set( 'href', '#/' )->set( 'data-choose', 'Add Images to Product Gallery' )->set( 'data-update', 'Add to gallery' )->set( 'data-delete', 'Delete image' )->set( 'data-text', 'Delete' )->addElement( 'span' )->set( 'class', 'dashicons dashicons-plus' );
        $product_images_container->addElement( 'span' )->set( 'class', 'sviHiddenLoop' )->text( 'Hide from <b>Product Loop</b>: (PRO VERSION FEATURE)' );
        $product_images_container->addElement( 'input' )->set( 'class', 'svipro-product_image_gallery' )->set( 'name', '' )->set( 'value', '' )->set( 'type', 'hidden' );
        return $sviBody;
    }
    
    /**
     * Builds the output order for the variations
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function woo_handle_export(
        $value,
        $meta,
        $product,
        $row
    )
    {
        
        if ( $meta->key == 'woosvi_slug' ) {
            foreach ( $value as $k => $v ) {
                foreach ( $v['imgs'] as $k2 => $v2 ) {
                    $value[$k]['imgs'][$k2] = array(
                        'id'       => $v2,
                        'url'      => array_pop( explode( '/', wp_get_attachment_url( $v2 ) ) ),
                        'full_url' => wp_get_attachment_url( $v2 ),
                    );
                }
            }
            return json_encode( $value );
        }
        
        return $value;
    }
    
    public function fixOrder( $woosvi_slug )
    {
        foreach ( $woosvi_slug as $k => $v ) {
            $arr = [];
            if ( array_key_exists( 'slugs', $v ) ) {
                switch ( $v['slugs'][0] ) {
                    case 'svidefault':
                        $arr = $woosvi_slug[$k];
                        unset( $woosvi_slug[$k] );
                        array_unshift( $woosvi_slug, $arr );
                        break;
                }
            }
        }
        return $woosvi_slug;
    }
    
    /**
     * Ignore the meta if running on free version
     *
     * @param array $data Data.
     *
     * @return array
     */
    public function wc_ignore_svimeta_in_import( $data )
    {
        $ignore_with_key = array( 'woosvi_slug' );
        if ( isset( $data['meta_data'] ) ) {
            foreach ( $data['meta_data'] as $index => $meta ) {
                if ( in_array( $meta['key'], $ignore_with_key, true ) ) {
                    unset( $data['meta_data'][$index] );
                }
            }
        }
        return $data;
    }

}