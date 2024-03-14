<?php
/**
 * Plugin Name: Category Featured Images - Extended
 * Plugin URI: http://ckmacleod.com/category-featured-images-extended
 * Description: Set category and tag images, especially for use as fallback thumbnails or featured images. 
 * Version: 1.52
 * Author: CK MacLeod
 * Author: URI: http://ckmacleod.com
 * License: GPL3
 * Text Domain: cks_cfix
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' ) ;

if ( ! defined( 'CKS_CFIX_VERSION' ) ) {
    
    define( 'CKS_CFIX_VERSION', '1.52' ) ;
    
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class category_featured_images_extended {
    
    function __construct() {
    // --- Actions ---------------------------------------------------------- //
        add_action( 'admin_print_scripts', 
                                        array( &$this, 'admin_print_scripts' ) 
                );
        add_action( 'admin_print_styles', 
                                        array( &$this, 'admin_print_styles' ) );
        add_action( 'category_edit_form', 
                                        array( &$this, 'category_edit_form' ) );
        add_action( 'edited_category',  array( &$this, 'edited_category' ) );
        add_action( 'series_edit_form', 
                                        array( &$this, 'category_edit_form' ) );
        add_action( 'edited_series',  array( &$this, 'edited_category' ) );
        add_action( 'post_tag_edit_form', 
                                        array( &$this, 'category_edit_form' ) );
        add_action( 'edited_post_tag',  array( &$this, 'edited_category' ) );
        add_action( 'admin_init',       array( &$this, 'admin_init' ) );
        add_action( 'admin_menu',       array( &$this, 'add_menu' ) );
        add_action( 'plugins_loaded',   array( &$this, 'check_version' ) ) ;
        add_action( 'init',             array( &$this, 'load_translation_file' ) 
                ) ;

    // --- Filters ---------------------------------------------------------- //
        add_filter( 'get_post_metadata', 
                array( &$this, 'get_post_metadata' ), 10, 4 );

    // --- Shortcodes ------------------------------------------------------- //
        add_shortcode( 'cfix_featured_image', 
                array( &$this, 'show_featured_image' ) );

    // --- Hooks ------------------------------------------------------------ //
        
        //cfix
        register_activation_hook( __FILE__, 
                array( 'category_featured_images_extended', 'install' ) );
    }

    //cfix
    public function admin_init() {
        // Set up the settings for this plugin
        $this->init_settings() ;
        
    } // END public static function activate

    /**
     * Initialize some custom settings
     */     
    public function init_settings()
    {
        
        // register the settings for this plugin
        register_setting( 'cks_cfix', 'cks_cfix_exclude_category', 
                'esc_html' ) ;
        register_setting( 'cks_cfix', 'cks_cfix_fallback_category', 
                'esc_html' ) ;
        register_setting( 'cks_cfix', 'cks_cfix_version' ) ;
        register_setting( 'cks_cfix', 'cks_cfix_use_yoast_primary', 'absint' ) ;
        
    } // END public function init_custom_settings()
    
    /**
     * add an Options Page
     */     
    public function add_menu() {
        
        add_options_page( 
                'Category Featured Images Extended', 
                'Category Featured Images Extended', 
                'manage_options', 
                'cks_cfix', 
                array(&$this, 'plugin_settings_page' 
                    ) );
        
    } // END public function add_menu()

    /**
     * Menu Callback
     */     
    public function plugin_settings_page() {
        
        if ( ! current_user_can( 'manage_options' ) ) {
                wp_die(__( 'You do not have sufficient permissions '
                        . 'to access this page.', 'cks_cfix' ) );
        }

        // Render the settings template
        include_once( dirname( __FILE__ ) . '/settings.php' ) ;
        } // END public function plugin_settings_page()
        
    //update current version if necessary
    public function check_version() {    
        
        if ( CKS_CFIX_VERSION !== get_option( 'cks_cfix_version') ) {
        
        update_option( 'cks_cfix_version', CKS_CFIX_VERSION ) ;
        
        }
        
    }   
    
    static function set_default_options() {
        
        update_option( 'cks_cfix_exclude_category', '' ) ;
        update_option( 'cks_cfix_fallback_category', '' ) ;
        update_option( 'cks_cfix_featured_images', array() ) ;
        update_option( 'cks_cfix_use_yoast_primary', 1 );
        update_option( 'cks_cfix_version', CKS_CFIX_VERSION ) ;
        
    }
    
   /**
    * CLEAN UP AFTER YOURSELF
    */
    static function uninstall() {
       
        delete_option( 'cks_cfix_exclude_category' ) ;
        delete_option( 'cks_cfix_fallback_category' ) ;
        delete_option( 'cks_cfix_featured_images') ;
        delete_option( 'cks_cfix_use_yoast_primary' ) ;
        
        delete_option( 'cks_cfix_version' ) ;
                
   }

   /**
    * UPGRADE FROM CFI FEATURED IMAGES, UPDATE OPTIONS FROM BETA VERSIONS
    */
    static function install() {

        if ( ! get_option( 'cks_cfix_featured_images' ) && 
                get_option( 'cfi_featured_images' ) ) {

            $cfi_options = get_option( 'cfi_featured_images' ) ; 

            update_option( 'cks_cfix_featured_images', $cfi_options ) ;

        }
        
        if ( get_option( 'cfix_featured_images' ) ) {
            
            $cfix_options = get_option( 'cfix_featured_images' ) ;
            
            update_option( 'cks_cfix_featured_images', $cfix_options ) ;
            
            delete_option( 'cfix_featured_images' ) ;
            
        }
        
        if ( ! get_option( 'cks_cfix_version ') ) {
            
            category_featured_images_extended::set_default_options() ;
            
        }
        
        register_uninstall_hook( __FILE__, 
                array( 'category_featured_images_extended', 'uninstall' ) );
        
   }

    function admin_print_scripts() {

        wp_enqueue_media();
        
        wp_register_script( 
                'cfix-media-upload', 
                plugins_url( 'js/cfix-media-upload.js', 
                        __FILE__ ), 
                array( 'jquery' ),
                CKS_CFIX_VERSION
                );
    
        // Localize the script with new data
        $media_trans_array = array(
            'title'  => __( 'Choose an Image', 'cks_cfix' ),
            'button' => __( 'Choose Image', 'cks_cfix' ),
        );
        
        wp_localize_script( 'cfix-media-upload', 'button_text', 
                $media_trans_array );
        
        $screen = get_current_screen();
    
        if ( $screen->base == 'term' )  {    
            
            wp_enqueue_script( 'cfix-media-upload' );
            
        }
        
        wp_register_script( 
                'cfix-select-cat-image', 
                plugins_url( 'js/cfix-select-cat-image.js',
                        __FILE__ ), 
                array( 'jquery' ),
                CKS_CFIX_VERSION
                );
        
        // Localize the script with new data
        $settings_trans_array = array(
            
            'save_changes'  => __( '"Save Changes" to make', 'cks_cfix' ),
            'new_fallback'  => __( 'your new Global Fallback.', 'cks_cfix' ),
            'or_cancel'     => __( 'or CANCEL', 'cks_cfix' ),
            
        );
        
        wp_localize_script( 'cfix-select-cat-image', 'select_cat_strings', 
                $settings_trans_array );
        
        if ( isset( $_GET['page'] ) && $_GET['page'] == 'cks_cfix' ) {    
            
            wp_enqueue_script( 'cfix-select-cat-image' );
            
        }

    }

    function admin_print_styles() {

        wp_register_style( 
                'cfix-styles', 
                plugins_url( 'cfix-styles.css'. '?v=' . 
                        CKS_CFIX_VERSION, __FILE__ ) 
                );
        
        $screen = get_current_screen();
        
        if ( (isset( $_GET['page'] ) ) && ( $_GET['page'] === "cks_cfix" ) || 
                $screen->base == 'term' )  {    
            
            wp_enqueue_style( 'cfix-styles' );
            
        }

   }

   /**
    * UPLOAD A FEATURED IMAGE FOR A CATEGORY
    */
   function category_edit_form() {
       
        $tag_ID = $_GET['tag_ID'] ;
        
        $tax = $_GET['taxonomy'] ;
        
        $tax_label = ( 'category' === $tax ) ? __( 'Category', 'cks_cfix') : 
            __( 'Tag', 'cks_cfix' ) ;
        
        if ( get_term( $tag_ID ) ) {
        
            $cat_name = get_term( $tag_ID )->name ;
            
        } 

        $images = get_option( 'cks_cfix_featured_images' );
        
        if ( $images === FALSE ) {
            
            $images = array() ;
            
        }
        
        $image = isset( $images[$tag_ID] ) ? $images[$tag_ID] : '' ;

        if ( $image ) {
            
            $change_button = __( 'Change Image', 'cks_cfix') ;
            
        } else {
            
            $change_button = __( 'Add Image', 'cks_cfix') ;
            
        }

        if ( $cat_name === get_option( 'cks_cfix_fallback_category') ) {

            $checked = 1; 
            $global_message = sprintf( __( 
                '"%s" %s Image set as '
                    . 'Global Fallback Featured/Thumbnail image.', 'cks_cfix' ), 
                    $cat_name, $tax_label ) ;
            $no_image_text = 
                    sprintf( __( '"%s" %s set as Global Fallback, but you '
                    . 'still have to add an Image for it.', 'cks_cfix' ), 
                            $cat_name, $tax_label ) ;
        
        } else {
            
            $checked = 0 ;
            $global_message = sprintf( __( 
                    'Check to set "%s"\'s image '
                    . 'as Global Featured/Thumbnail Fallback.', 'cks_cfix' ), 
                    $cat_name ) ;
            $no_image_text = __( 'No Image Selected' , 'cks_cfix' ) ;
            
        }
        
        $no_image = '<div id="cfix-thumbnail-no-image">'
                . '<span id="cfix-no-image">' . $no_image_text .
                '</span></div>' ;
        
                    
        ?>

        <table class="form-table">
             <tr class="form-field">
                 <th valign="top" scope="row">
                     <label><?php _e( $tax_label .
                             ' Image', 'cks_cfix' ) ; ?></label>
                 </th>
                 <td>
                     <div id="cfix-thumbnail" class="cfix-thumbnail">
                         <?php echo $image ? wp_get_attachment_image( $image ) : 
                             $no_image ; 
                         ?>
                     </div>
                     <div id="cfix-thumbnail-no-image" class="cfix-thumbnail" 
                          style="display: none;">
                         <span id="cfix-no-image" ><?php 
                         printf ( __( 'Click Update %s to Save Changes,%s'
                                 . 'or %s Cancel</a>' , 'cks_cfix' ), 
                                 '<br>', '<br>',
                                 '<a id="cancel-removal" href="#">', 
                                 '</a>' ) ; ?></span>
                     </div>
                     
                     <div id="cfix-cat-edit-buttons">
                     
                        <input id="cfix-featured-image" type="hidden" 
                               name="cks_cfix_featured_image" 
                               readonly="readonly" value="<?php 
                               echo $image; ?>" />
                        <input id="cfix-change-image" class="button" 
                               type="button" value="<?php 
                               echo $change_button ; ?>" />
                        <input id="cfix-remove-image" class="button" 
                               type="button" value="<?php 
                               _e( 'Remove Image', 'cks_cfix') ; ?>" />
                     
                     </div>
                     <p><?php 
                            _e( 
                             ' Click "Update" to save your selection, '
                             . 'along with any other changes.', 
                             'cks_cfix' ) ; 
                     ?></p>
                 </td>
             </tr>
             <tr class="form-field">
                 <th valign="top" scope="row">
                     <label><?php _e( 
                             'Set as Global Fallback', 'cks_cfix' ) ; ?></label>
                 </th>
                 <td>
                     <input type="checkbox" name="cks_cfix_use_cat" <?php 
                     checked( $checked, 1 ) ; 
                     ?> value=1 ><span class="description">
                       
                         <?php echo $global_message ; ?>
                         
                     </span>
    
                     
                 </td>
             </tr>
        </table>

        <?php
   }

   /**
    * PROCESS CATEGORY IMAGE
    * @param int $term_id
    */
   function edited_category( $term_id ) {
       
        if ( isset( $_POST['cks_cfix_featured_image'] ) ) {
       
            $images = get_option( 'cks_cfix_featured_images' );
            
            if ( $images === FALSE ) {
                
                $images = array() ;
                
            }

            $img_id = trim( $_POST['cks_cfix_featured_image'] );
            $images[$term_id] = $img_id ? $img_id : NULL;
            
            if ( $images[$term_id] == NULL ) {
                
                unset($images[$term_id]) ;
                
            }
            
            $cat_name = get_term( $term_id )->name  ;
            $cat_id = get_term( $term_id )->term_id ;
            $tax = get_term( $term_id )->taxonomy ;
            
            if ( $tax !== 'category' && 
                    get_term_by( 'name', $cat_name, 'category') && 
                    $images[$term_id] !== NULL ) {
                
                $message = '
                    
                    <div class="notice error bad-tax-notice is-dismissible" >
                    <p>' . __( 'You cannot add an image to a Tag '
                    . 'of the same name as a Category.', 'cks_cfix' ) . '</p>
            
                    <p style="float:right"> <a href="' 
                    . admin_url('edit-tags.php') 
                    . '">Return to Tags</a></p></div>' ;
            
                wp_die( $message ) ;
                
            }
            
            update_option( 'cks_cfix_featured_images', $images );
            
            $cat_name = get_term( $term_id )->name ;
        
            if (isset( $_POST['cks_cfix_use_cat'] ) ) {

                    update_option( 'cks_cfix_fallback_category', 
                    $cat_name ) ;
            
            } else { 
            
                //delete from fallback cats if set but no use cat
                if ( get_option( 
                        'cks_cfix_fallback_category') === 
                        $cat_name ) {

                    delete_option( 'cks_cfix_fallback_category' ) ;

                }
            
            }
        
        }
        
   }
    
    /**
     * FILTERS GET_POST_METADATA
     * pre-replace _thumbnail_id or provides fallback if set
     * @param string $meta_value
     * @param int $object_id
     * @param string $meta_key
     * @param boolean $single
     * @return image array
     */
    function get_post_metadata( $meta_value, $object_id, $meta_key, $single ) {

        if ( is_admin() || '_thumbnail_id' != $meta_key ) {
            
            return $meta_value;
            
        }

        $meta_type = 'post' ;
        $meta_cache = wp_cache_get( $object_id, $meta_type . '_meta' ) ;

        if ( ! $meta_cache ) {
            
            $meta_cache = update_meta_cache( $meta_type, array( $object_id ) ) ;
            $meta_cache = $meta_cache[$object_id];
       
        }

        if ( ! $meta_key ) {
            
            return $meta_cache;
            
        }

        if ( isset( $meta_cache[$meta_key]) ) {

            if ( $single ) {
                
                //defeats false thumbnail positives - removed for now (1.31)
                //to be incorporated as option in later version
              #  if ( get_children('post_parent=' . $object_id . 
              #     '&post_type=attachment&post_mime_type=image') ) {
                    
                return maybe_unserialize( $meta_cache[$meta_key][0] );
                    
              #  }
                
            } else {

                return array_map( 'maybe_unserialize', 
                        $meta_cache[$meta_key] ) ;

            }
                
        }
        
        if ( $single  ) {
            
            return $this->get_image( $object_id ) ;
            
        }
       
   }
   
   /**
    * THE MAIN FALLBACK FUNCTION
    * @param int $object_id - post ID used to find categories, images 
    */
   function get_image( $object_id ) {
       
       //initial variables
        $excl_cat           = 
                get_option( 'cks_cfix_exclude_category' ) ? 
                get_option( 'cks_cfix_exclude_category' ) : '' ;
        $fall_cat           =  
                get_option( 'cks_cfix_fallback_category' ) ? 
                get_option( 'cks_cfix_fallback_category' ) : '' ;
        $images             =  
                get_option( 'cks_cfix_featured_images')  ?
                get_option( 'cks_cfix_featured_images') : '' ;
        $use_yoast_cat = is_plugin_active('wordpress-seo/wp-seo.php') ? 
                get_option( 'cks_cfix_use_yoast_primary') : FALSE ;

        //added after support group question pointing out error warning
        $fall_cat_id = '' ;

        //convert exclude category string to array of IDs
        $excl_cat_arr = explode( ',', $excl_cat ) ;

        foreach ( $excl_cat_arr as $excl_cat_name ) {

            $excl_cat_ids[] = get_cat_ID( esc_html( $excl_cat_name ) ) ;

        }

        //get fallback category ID
        if ( get_cat_ID( $fall_cat) ) { 

            $fall_cat_id = get_cat_ID( $fall_cat ) ;

        } else {

            if (get_term_by( 'name', $fall_cat, 'post_tag' )) {

                $fall_cat_obj = get_term_by( 'name', $fall_cat, 'post_tag' ) ;
                $fall_cat_id = $fall_cat_obj->term_id ;

            }
        }

        //look for yoast primary image
        
        if ( $use_yoast_cat ) {
            
            $yoast_cat = get_post_meta( 
                        $object_id, '_yoast_wpseo_primary_category', true 
                        ) ? get_post_meta( 
                        $object_id, '_yoast_wpseo_primary_category', true 
                        ) : '' ;
            
            if ( $yoast_cat && ! in_array( $yoast_cat, $excl_cat_ids )       
                ) {   
                        
                $category = $yoast_cat ;
            
                if ( isset( $images[$category] ) )  {

                    return $images[$category];
            
                }
                
            }

        }
        
        // Look for a category featured image

        $terms = wp_get_post_terms( 
                $object_id, 
                array( 'category', 'post_tag'), 
                array( 'fields' => 'ids' )
                );

        // Sort in reverse chronological order
        natsort( $terms ) ;
        arsort( $terms ) ;
        $categories = apply_filters( 'cks_cfix_post_categories', $terms ) ;

        foreach( $categories as $category ) {   

            if ( in_array( $category, $excl_cat_ids ) ) {

                continue ;

            }

            if ( isset( $images[$category] ) ){

                return $images[$category];

            }            

        }

        //No category image, returned, so look for parent image,
        //ignoring excluded categories.
        //Will return existing parent image of most recently added category.

        foreach( $categories as $category ) {

        // Look for the parent category image
            if ( get_category( $category ) ) {

                $cat = get_category( $category ) ;

                if (

                    isset( $cat->parent ) && 
                    $cat->parent > 0 && 
                    isset( $images[$cat->parent] ) && 
                    ! in_array( $images[$cat->parent], $excl_cat_ids )

                    ) {

                    return $images[$cat->parent] ;

                } 

            }    

        }

       //if no category or parent image found, look for global fallback

        if ( isset( $images[$fall_cat_id] ) ) { 

             return $images[$fall_cat_id]; 

         } 
         
         return '' ;
   }
   
       /**
     * GET THE FEATURED IMAGE URL
     * ***********WILL NEED TO CHECK LOGIC/UPDATING******************
     * @param array $args
     * @return string
     */
    static function get_featured_image_url( $args ) {

        $images = get_option( 'cks_cfix_featured_images' ) ;
        $use_yoast_cat = is_plugin_active('wordpress-seo/wp-seo.php') ? 
                get_option( 'cks_cfix_use_yoast_primary') : FALSE ;
        
                            
        
        $size = isset( $args['size'] ) ? $args['size'] : 'full' ;
        
        if ( isset( $args['cat_id'] ) ) {
            
            $cat_id = intval( $args['cat_id'] ) ;

            if ( isset( $images[$cat_id] ) ) {

                $attachment = wp_get_attachment_image_src( 
                        $images[$cat_id], $size 
                        ) ;

                if ( $attachment !== FALSE ) { 
                    
                    return $attachment[0];
                    
                }

            }
                
        } else if ( is_single() ) {
                
            $id = get_post_thumbnail_id() ;
            
            if ( $id ) {
                
                $attachment = wp_get_attachment_image_src( $id, $size ) ;
                
                if ( $attachment !== FALSE ) {
                    
                    return $attachment[0];
                    
                }
            
            }
            
        } else if ( is_category() ) {
            
            $categories = get_the_category() ;
            
            if ( $categories ) {
                
                $cat = NULL;
                
                /* YOAST HERE */
                
                if ( $use_yoast_cat ) {
            
                    $yoast_cat = get_post_meta( 
                        $object_id, '_yoast_wpseo_primary_category', true 
                        ) ? get_post_meta( 
                        $object_id, '_yoast_wpseo_primary_category', true 
                        ) : '' ;
            
                    if ( $yoast_cat && 
                            in_array( $yoast_cat, $images ) &&
                            ! in_array( $yoast_cat, $excl_cat_ids )                
                    ) {   
                        
                        $attachment = wp_get_attachment_image_src( 
                                $images[$yoast_cat], $size 
                                ) ;
                        
                        if ( $attachment !== FALSE ) {
                            
                            return $attachment[0];
                            
                        }
            
                
                    }

                }
                
                foreach( $categories as $category ) {

                    if ( isset( $images[$category->term_id] ) ) {

                        $attachment = wp_get_attachment_image_src( 
                                $images[$category->term_id], $size 
                                ) ;
                        
                        if ( $attachment !== FALSE ) {
                            
                            return $attachment[0];
                            
                        }

                    }
                    
                    if ( $cat === NULL ) $cat = $category;
                    
                }
                    
                if ( $cat !== NULL ) {
                                
                    $parent = intval( $cat->parent ) ;
                    
                    if ( $parent > 0 && isset( $images[$parent] ) ) {
                                        
                        $attachment = wp_get_attachment_image_src( 
                                $images[$parent], $size 
                                ) ;
                        
                        if ( $attachment !== FALSE ) {
                            
                            return $attachment[0];
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }

        return '' ;

    }

    /**
     * SHOW THE FEATURED IMAGE
     * @param array $args
     * @return string
     */
    static function show_featured_image( $args ) {
        
        $images = get_option( 'cks_cfix_featured_images' ) ;
        $use_yoast_cat = is_plugin_active('wordpress-seo/wp-seo.php') ? 
                get_option( 'cks_cfix_use_yoast_primary') : FALSE ;
        
        if ( isset( $args['size'] ) ) {
            
            $size = $args['size'];
            unset( $args['size'] ) ;
            
        } else {
            
            $size = 'thumbnail' ;
            
        }
        
        if ( isset( $args['cat_id'] ) ) {
            
            $cat_id = intval( $args['cat_id'] ) ;
            
            if ( isset( $images[$cat_id] ) ) {
                
                $img = wp_get_attachment_image( $images[$cat_id], $size ) ;
                
                if ( $img ) {
                    
                    return '<span class="cfix-featured-image">' . 
                        wp_get_attachment_image( $images[$cat_id], $size ) . 
                        '</span>' ;
                    
                }
            }
        }
        
        else if ( is_single() ) {
            
            $image = get_the_post_thumbnail( null, $size, $args ) ;
            
            if (  $image ) return 
                '<span class="cfix-featured-image">' . $image . '</span>' ;
            
        }
        
        else if ( is_category() ) {
            
            $categories = get_the_category() ;
            
            if ( $categories ) {
                
                $cat = NULL;
                
                if ( $use_yoast_cat ) {
            
                    $yoast_cat = get_post_meta( 
                        $object_id, '_yoast_wpseo_primary_category', true 
                        ) ? get_post_meta( 
                        $object_id, '_yoast_wpseo_primary_category', true 
                        ) : '' ;
            
                    if ( $yoast_cat && 
                            in_array( $yoast_cat, $images ) &&
                            ! in_array( $yoast_cat, $excl_cat_ids )                
                    ) {   
                        
                        $attachment = wp_get_attachment_image_src( 
                                $images[$yoast_cat], $size 
                                ) ;
                        
                        if ( $attachment !== FALSE ) {
                            
                            return '<span class="cfix-featured-image">' . 
                            wp_get_attachment_image( 
                                $images[$yoast_cat], $size 
                                ) . '</span>' ;}
                            
                    }
            
                
                }
                
                foreach( $categories as $category ) {
                    
                    if ( isset( $images[$category->term_id] ) ) {
                        
                        return '<span class="cfix-featured-image">' . 
                            wp_get_attachment_image( 
                                $images[$category->term_id], $size 
                                ) . '</span>' ;}
                    
                    if ( $cat === NULL ) {
                        
                        $cat = $category;
                        
                    }
                    
                }
                
                if ( $cat !== NULL ) {
                
                    $parent = intval( $cat->parent ) ;
                    
                    if ( $parent > 0 && isset( $images[$parent] ) ) {
                        
                        return '<span class="cfix-featured-image">' . 
                           wp_get_attachment_image( $images[$parent], $size ) . 
                                '</span>' ;
                        
                    }
                
                }
                
            }
            
        }
        
        return '' ;
        
   }
   
   function load_translation_file() {
    
    // relative path to WP_PLUGIN_DIR where the translation files will sit:
    $plugin_path = plugin_basename( dirname( __FILE__ ) . '/languages' ) ;
    load_plugin_textdomain( 'cks_cfix', '', $plugin_path ) ;
    
    }
   
}

$wp_plugin_template = new category_featured_images_extended();

/**
 * ECHO FEATURED IMAGE
 * @param array $args
 */
function cfix_featured_image( $args ) {
    
    echo category_featured_images_extended::show_featured_image( $args );
        
}

/**
 * IMPLEMENT GET FEATURED IMAGE URL
 * @param array $args
 * @return string
 */
function cfix_featured_image_url( $args ) {
    
    return category_featured_images_extended::get_featured_image_url( $args );
    
}

// Add a link to the settings page onto the plugin page
if ( isset( $wp_plugin_template ) ) {
    
    // Add the settings link to the plugins page
    function cfix_plugin_settings_link( $links ) { 
        
        $settings_link = 
                '<a href="options-general.php?page=cks_cfix">Settings</a>'; 
        
        array_unshift( $links, $settings_link ); 
        
        return $links; 
        
    }

    $plugin = plugin_basename( __FILE__ ); 
    
    add_filter( "plugin_action_links_$plugin", 'cfix_plugin_settings_link' );
    
}