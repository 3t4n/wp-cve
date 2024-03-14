<?php
/**
 * RDV Category Image: Admin page
 **/

if ( ! class_exists( 'RDV_CT_TAX_META' ) ) {
    
    class RDV_CT_TAX_META {
        
		public $rdv_image_placeholder;
		
		public $rdv_category_image_version = '1.0.8';
		
        public function __construct() {
            //This action helps to load all custom categories in function init()
            add_action('init', array($this, 'init_rdv_category_image'), 99);
            
            $this->rdv_image_placeholder = plugin_dir_url( __FILE__ ).'images/rdv-placeholder.png';
            
            add_action('admin_menu', array($this,'rdv_category_image_menu_actions'));
            add_action('admin_init', array($this,'register_rdv_category_image_plugin_settings'));
            
            // Settings page link in plugins list
            add_filter('plugin_action_links_rdv-category-image/rdv-category-image.php', array($this, 'rdv_category_image_plugin_page_link'));
            
            // Load admin css/scripts
            add_action('admin_enqueue_scripts', array($this, 'rdv_load_admin_styles_scripts') );
        }
        
        /*
        * Load admin css/scripts
        * @since 1.0.0
        */
        public function rdv_load_admin_styles_scripts() {
            wp_enqueue_style( 'rdv-category-image-admin-style', plugin_dir_url( __FILE__ ) . 'css/style.css', false, $this->rdv_category_image_version );
        }
		
        /*
        * Admin menu settings page
        * @since 1.0.0
        */
        // Add admin menu
        public function rdv_category_image_menu_actions(){
            add_menu_page( __( 'RDV Category Image'), __( 'RDV Category Image' ), 'administrator', 'rdv-category-image', array($this, 'rdv_category_image_menu_seetings'), 'dashicons-format-image' );
        }
        
        /*
        * Display settings link in plugins list page
        * @since 1.0.0
        */
        public function rdv_category_image_plugin_page_link($links) {
            $settings_link = '<a href="admin.php?page=rdv-category-image">Settings</a>';
            array_push($links, $settings_link);
            return $links;
        }
        
        /*
        * Save settings to displaty category image field on taxonomies
        * @since 1.0.0
        */
        public function rdv_category_image_menu_seetings() {
        ?>
            <div class="rdv-admin-section">
                <div class="rdv-admin-wrapper">
                    <h1>RDV Category Image</h1>
					<div class="rdv-admin-notice">
                        <?php settings_errors(); ?>
                    </div>
                    <div class="rdv-admin-content">
                        <h3>Select categories/taxonomies from the below list and set the category image.</h3>
                        <form method="post" action="options.php" id="rdv_category_image_options_form">
                            <div class="rdv-admin-fields-row">
                                <?php 
                                    settings_fields( 'rdv_plugin_settings_group' ); 
                                    do_settings_sections( 'rdv_plugin_settings_group' );
                                    $rdv_category_image_options = get_option('rdv_category_image_options');
									$remove_taxonomies = ['nav_menu','link_category','post_format','author','wp_theme','product_type','product_visibility','product_shipping_class','wp_template_part_area','wp_pattern_category','elementor_library_type','elementor_library_category','et_code_snippet_type','et_tb_item_type','layout_category','layout_tag'];
                                    foreach (get_taxonomies($args = array('public' => true, 'show_in_nav_menus' => true), $output = 'objects') as $tax) : if (in_array($tax->name, $remove_taxonomies)) continue; ?>
                                        <div class="rdv-admin-field">
                                            <input class="rdv-admin-checkbox" type="checkbox" id="slug_<?php echo esc_html( $tax->name ); ?>" name="rdv_category_image_options[rdv_cat_img_checked][<?php echo esc_html( $tax->name ); ?>]" value="<?php echo esc_html( $tax->name ); ?>" <?php checked(isset($rdv_category_image_options['rdv_cat_img_checked'][$tax->name])); ?> /> 
                                            <label class="rdv-admin-label" for="slug_<?php echo esc_html( $tax->name ); ?>"> <?php echo esc_html( $tax->label ). ' (' .esc_html( $tax->name ). ')'; ?></label>
                                        </div>
                                <?php 
                                	endforeach;
                                submit_button(); 
                            ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
        }
        
        /*
        * Register settings in optipns table
        * @since 1.0.0
        */        
        public function register_rdv_category_image_plugin_settings(){
            // Save option data 
            register_setting( 'rdv_plugin_settings_group', 'rdv_category_image_options' );
        }

        /*
        * Initialize the class and start calling our hooks and filters
        * @since 1.0.0
        */
        public function init_rdv_category_image() {
            add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
            // Enable category image for selected taxonomies
            $rdv_category_image_options = get_option('rdv_category_image_options');
            $rdv_categories_default	= get_taxonomies();
            if ($rdv_category_image_options != ''){
                $rdv_categories = $rdv_category_image_options['rdv_cat_img_checked'];
            }
            else {
                $rdv_categories = [];
            }
            foreach($rdv_categories as $category){
                if(!empty($rdv_category_image_options)) {
                    if(array_key_exists('rdv_cat_img_checked', $rdv_category_image_options)) {
                        $rdv_category_image_options_arr = $rdv_category_image_options['rdv_cat_img_checked'];
                        if(array_key_exists($category, $rdv_category_image_options_arr)){
                            add_action($category.'_add_form_fields', array($this, 'add_rdv_category_image' ), 10, 2);
                            add_action($category.'_edit_form_fields', array($this, 'update_rdv_category_image' ), 10, 2);
                            add_action('created_'.$category, array($this, 'save_rdv_category_image' ), 10, 2);
                            add_action('edited_'.$category, array($this, 'updated_rdv_category_image' ), 10, 2);
                            add_filter('manage_edit-'.$category.'_columns', array($this, 'rdv_categories_column_image'), 5);
                            add_filter('manage_'.$category.'_custom_column', array($this, 'rdv_categories_column_image_field'),5,3);
                        }
                    }
                }
            }
        }

        /*
        * Load WP media script for image selection 
        * @since 1.0.0
        */
        public function load_media() {
            wp_enqueue_media();
        }

        /*
        * Add a form field in the new category page
        * @since 1.0.0
        */
        public function add_rdv_category_image ( $taxonomy ) { ?>
            <div class="form-field term-group cat_rdv">
                <label class="rdv-label"><?php _e('Category Image', 'rdv-category-image'); ?></label>
                <input type="hidden" id="rdv_category_image_id" name="rdv_category_image_id" value="">
                <div id="rdv_category_thumbnail" class="rdv-category-thumbnail">
                    <img src="<?php echo esc_url( $this->rdv_image_placeholder ); ?>" width="100px" />
                </div>
                <div class="cat-buttons">
                    <input type="button" class="button button-secondary rdv_cat_tax_media_button" id="rdv_cat_tax_media_button" name="rdv_cat_tax_media_button" value="<?php _e( 'Add Image', 'rdv-category-image' ); ?>" />
                    <input type="button" class="button button-secondary rdv_cat_tax_media_remove" id="rdv_cat_tax_media_remove" name="rdv_cat_tax_media_remove" value="<?php _e( 'Remove Image', 'rdv-category-image' ); ?>" />
                </div>
               <script type="text/javascript">
                    // Only show the "remove image" button when needed
                    if ( ! jQuery( '#rdv_category_image_id' ).val() ) {
                        jQuery( '.rdv_cat_tax_media_remove' ).hide();
                    }

                    // Uploading files
                    var rdv_media_frame;

                    jQuery( document ).on( 'click', '.rdv_cat_tax_media_button', function( event ) {

                        event.preventDefault();

                        // If the media frame already exists, reopen it.
                        if ( rdv_media_frame ) {
                            rdv_media_frame.open();
                            return;
                        }

                        // Create the media frame.
                        rdv_media_frame = wp.media.frames.downloadable_file = wp.media({
                            title: '<?php esc_html_e( 'Choose an image', 'rdv-category-image' ); ?>',
                            button: {
                                text: '<?php esc_html_e( 'Set category image', 'rdv-category-image' ); ?>'
                            },
                            multiple: false
                        });

                        // When an image is selected, run a callback.
                        rdv_media_frame.on( 'select', function() {
                            var attachment           = rdv_media_frame.state().get( 'selection' ).first().toJSON();
                            var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                            jQuery( '#rdv_category_image_id' ).val( attachment.id );
                            jQuery( '#rdv_category_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
                            jQuery( '.rdv_cat_tax_media_remove' ).show();
                        });

                        // Finally, open the modal.
                        rdv_media_frame.open();
                    });

                    jQuery( document ).on( 'click', '.rdv_cat_tax_media_remove', function() {
                        jQuery( '#rdv_category_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_url( $this->rdv_image_placeholder ); ?>' );
                        jQuery( '#rdv_category_image_id' ).val( '' );
                        jQuery( '.rdv_cat_tax_media_remove' ).hide();
                        return false;
                    });

                    jQuery( document ).ajaxComplete( function( event, request, options ) {
                        if ( request && 4 === request.readyState && 200 === request.status
                            && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

                            var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
                            if ( ! res || res.errors ) {
                                return;
                            }
                            // Clear Thumbnail fields on submit
                            jQuery( '#rdv_category_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_url( $this->rdv_image_placeholder ); ?>' );
                            jQuery( '#rdv_category_image_id' ).val( '' );
                            jQuery( '.rdv_cat_tax_media_remove' ).hide();
                            return;
                        }
                    } );
                </script>
            </div>
        <?php
        }

        /*
        * Save the form field
        * @since 1.0.0
        */
        public function save_rdv_category_image ( $term_id, $tt_id ) {
            if( isset( $_POST['rdv_category_image_id'] ) && '' !== $_POST['rdv_category_image_id'] ){
                $image = $_POST['rdv_category_image_id'];
                $image_sanitized = sanitize_text_field( $image );
                add_term_meta( $term_id, 'rdv_category_image_id', $image_sanitized, true );
            }
        }

        /*
        * Edit the form field
        * @since 1.0.0
        */
        public function update_rdv_category_image ( $term, $taxonomy ) { ?>
            <tr class="form-field term-group-wrap cat_rdv">
                <th scope="row">
                    <label class="rdv-label"><?php _e( 'Edit Category Image', 'rdv-category-image' ); ?></label>
                </th>
                <td>
                    <?php 
                    $image_id = get_term_meta ( $term -> term_id, 'rdv_category_image_id', true ); 
                    $image_src = wp_get_attachment_image_src($image_id, $size = 'thumbnail', 'single-post-thumbnail');   
                    ?>
                    <input type="hidden" id="rdv_category_image_id" name="rdv_category_image_id" value="<?php echo esc_html( $image_id ); ?>">
                    <div id="rdv_category_thumbnail" class="rdv-category-thumbnail">
                        <img src="<?php  if ( $image_id ) { echo esc_url( $image_src[0] ); } else {echo esc_url( $this->rdv_image_placeholder );} ?>" width="100px" />
                    </div>

                    <div class="cat-buttons">
                        <input type="button" class="button button-secondary rdv_cat_tax_media_button" id="rdv_cat_tax_media_button" name="rdv_cat_tax_media_button" value="<?php _e( 'Add Image', 'rdv-category-image' ); ?>" />
                        <input type="button" class="button button-secondary rdv_cat_tax_media_remove" id="rdv_cat_tax_media_remove" name="rdv_cat_tax_media_remove" value="<?php _e( 'Remove Image', 'rdv-category-image' ); ?>" />
                    </div>

                    <script type="text/javascript">
                        // Only show the "remove image" button when needed
                        if ( '' === jQuery( '#rdv_category_image_id' ).val() ) {
                            jQuery( '.rdv_cat_tax_media_remove' ).hide();
                        }

                        // Uploading files
                        var rdv_media_frame;

                        jQuery( document ).on( 'click', '.rdv_cat_tax_media_button', function( event ) {

                            event.preventDefault();

                            // If the media frame already exists, reopen it.
                            if ( rdv_media_frame ) {
                                rdv_media_frame.open();
                                return;
                            }

                            // Create the media frame.
                            rdv_media_frame = wp.media.frames.downloadable_file = wp.media({
                                title: '<?php esc_html_e( 'Choose an image', 'rdv-category-image' ); ?>',
                                button: {
                                    text: '<?php esc_html_e( 'Set category image', 'rdv-category-image' ); ?>'
                                },
                                multiple: false
                            });

                            // When an image is selected, run a callback.
                            rdv_media_frame.on( 'select', function() {
                                var attachment           = rdv_media_frame.state().get( 'selection' ).first().toJSON();
                                var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                                jQuery( '#rdv_category_image_id' ).val( attachment.id );
                                jQuery( '#rdv_category_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
                                jQuery( '.rdv_cat_tax_media_remove' ).show();
                            });

                            // Finally, open the modal.
                            rdv_media_frame.open();
                        });

                        jQuery( document ).on( 'click', '.rdv_cat_tax_media_remove', function() {
                            jQuery( '#rdv_category_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_url( $this->rdv_image_placeholder ); ?>' );
                            jQuery( '#rdv_category_image_id' ).val( '' );
                            jQuery( '.rdv_cat_tax_media_remove' ).hide();
                            return false;
                        });
                    </script>
                </td>
            </tr>
         <?php
         }

        /*
        * Update the form field value
        * @since 1.0.0
        */
        public function updated_rdv_category_image ( $term_id, $tt_id ) {
            // Update meta from individual edit page
            if( isset( $_POST['rdv_category_image_id'] ) && '' !== $_POST['rdv_category_image_id'] ){
                $image = $_POST['rdv_category_image_id'];
                $image_sanitized = sanitize_text_field( $image );
                update_term_meta ( $term_id, 'rdv_category_image_id', $image_sanitized );
            }
            elseif(isset( $_POST['rdv_category_image_id'] ) && '' == $_POST['rdv_category_image_id']) {
                if ( metadata_exists( 'term', $term_id, 'rdv_category_image_id' ) ) {
                    update_term_meta ( $term_id, 'rdv_category_image_id', '' );
                }
            }
        }

        /*
        * Add column in category page
        * @since 1.0.0
        */
        function rdv_categories_column_image( $columns ) {
            $columns['rdv_category_image'] = 'Image';
            return $columns;
        }
        
        /*
        * Dispay images in category page
        * @since 1.0.0
        */
        function rdv_categories_column_image_field( $value, $column_name, $term_id ) {
            if($column_name == 'rdv_category_image') {
                ?>
                <?php 
                $image_id = get_term_meta ( $term_id, 'rdv_category_image_id', true ); 
                $image_src = wp_get_attachment_image_src($image_id, $size = 'thumbnail', 'single-post-thumbnail');
                $image_url = wp_get_attachment_image_url($image_id, $size = 'thumbnail', 'single-post-thumbnail');
                ?>
                <div class="rdv-category-image-wrapper">
                    <?php if ( $image_id ) { ?>
                        <img class="rdv-category-image-thumb" src="<?php echo esc_url( $image_url ); ?>" width="50" height="50" >
                    <?php } else { ?>
                        <img class="rdv-category-image-placeholder" src="<?php echo esc_url( $this->rdv_image_placeholder ); ?>" width="50" height="50" >
                </div>
                <?php
                    }
            }
        }
    } // RDV_CT_TAX_META end

    $RDV_CT_TAX_META = new RDV_CT_TAX_META();
}  // RDV_CT_TAX_META if exist end.