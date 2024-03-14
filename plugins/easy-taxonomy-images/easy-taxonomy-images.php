<?php
/*
 * Plugin Name: Easy Taxonomy Featured and Cover Images
 * Plugin URL: www.wpdevstudio.com
 * Description: Have featured and cover images for tags, categories and custom taxonomies
 * Version: 1.0.1
 * Author: wpdevstudio
 * Author URI: https://profiles.wordpress.org/wpdevstudio
 */
  
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
if ( ! class_exists( 'EASY_TAX_IMAGES' ) ) :
	/*
	 * EASY_TAX_IMAGES
	 *
	 * @since 1.0.0
	 */
	final class EASY_TAX_IMAGES {
		
		/*
		 * contains instance of the class
		 *
		 * @since 1.0.0
		 *
		 */
		private static $instance;
		
		
		public $prefix 		= 'eti_';
		
		public $text_domain	= 'ETI';
	
		private $version = '1.0.0';

		
		/*
		 * ensures only one instance of this class is running
		 *
		 * @since 1.0.0
		 *
		 * @return EASY_TAX_IMAGES instance
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EASY_TAX_IMAGES ) ) {
				self::$instance = new EASY_TAX_IMAGES;
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->hooks();

			}
			return self::$instance;
		}
		
		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function hooks() {


			// activation
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// hooks browser menu
			add_action('admin_menu', array($this,'easy_tax_images_menu'),99);

			// admin styles and scripts
			add_action('admin_enqueue_scripts', array($this,'admin_enqueue_scripts'),99);
			
			// save taxonomy images
			add_action('edit_term', array($this,'save_taxonomy_image') );
			add_action('create_term',array($this,'save_taxonomy_image') );
			
			// allow featured image change in quick edit box as well
			add_action('quick_edit_custom_box', array($this,'quick_edit_custom_box'), 10, 3);
			
		}
		
		/**
		 * Registers hooks browser submenu
		 * @since 1.0
		 * @access public
		 *
		 * @return void
		 */
		public function easy_tax_images_menu() {
			 add_menu_page(  
			  	__( 'Easy Taxonomy Images', $this->text_domain ),
			  	__( 'Easy Taxonomy Images', $this->text_domain ), 
			  	'manage_options',
			  	$this->prefix.'easy_tax_images',
			  	array($this,'eti_callback')
		  	); 
		}

		/**
		 * Activation function fires when the plugin is activated.
		 * @since 1.0
		 * @access public
		 *
		 * @return void
		 */
		public function admin_init() {
		
			if( isset($_POST['option_page']) && $_POST['option_page'] == 'easy-tax-images-settings'  ) {
			
				if(!empty($_POST['eti_options'])) {
				
					update_option('eti_options',$_POST['eti_options']);
				}

			}

			$taxes = get_taxonomies();
			
			if (is_array($taxes)) {
			
				$eti_options = get_option('eti_options');
				$excluded_taxonomies_featured   = !empty($eti_options['excluded_taxonomies_featured'])? $eti_options['excluded_taxonomies_featured'] : array();
                $excluded_taxonomies_cover      = !empty($eti_options['excluded_taxonomies_cover'])? $eti_options['excluded_taxonomies_cover'] : array();
				foreach ($taxes as $tax) {

					if (!in_array($tax, $excluded_taxonomies_featured)) {

						add_action($tax.'_add_form_fields', array($this,'add_taxonomy_field_featured') );
						add_action($tax.'_edit_form_fields', array($this,'edit_taxonomy_field_featured') );
						add_filter( 'manage_edit-' . $tax . '_columns',  array($this,'taxonomy_columns_featured') );
						add_filter( 'manage_' . $tax . '_custom_column',  array($this,'taxonomy_column_featured'), 10, 3 );
					}

                    if (!in_array($tax, $excluded_taxonomies_cover)) {

                        add_action($tax.'_add_form_fields', array($this,'add_taxonomy_field_cover') );
                        add_action($tax.'_edit_form_fields', array($this,'edit_taxonomy_field_cover') );
                        add_filter( 'manage_edit-' . $tax . '_columns',  array($this,'taxonomy_columns_cover') );
                        add_filter( 'manage_' . $tax . '_custom_column',  array($this,'taxonomy_column_cover'), 10, 3 );
                    }
				}
			}

		}

		/** enqueue media for media library **/
		function enqueue_media() {

			if (get_bloginfo('version') >= 3.5)
				wp_enqueue_media();
			else {
				wp_enqueue_style('thickbox');
				wp_enqueue_script('thickbox');
			}

		}

		/** add cover & featured image fields on add tax screen**/
		function add_taxonomy_field_featured() {

			echo '
			<div class="form-field eti-image-wrap">
				<label for="taxonomy_featured_image">' . __('Featured', $this->text_domain) . '</label>
				<img class="eti_image" src=""/><br/>
				<input type="text" class="eti_image_url" name="taxonomy_featured_image" id="taxonomy_featured_image" value="" />
				<br/>
				<button class="eti_upload_image button">' . __('Upload/Add image', $this->text_domain) . '</button>
			</div>';

		}

        /** add cover & featured image fields on add tax screen**/
        function add_taxonomy_field_cover() {

            echo '
			<div class="form-field eti-image-wrap">
				<label for="taxonomy_cover_image">' . __('Cover', $this->text_domain) . '</label>
				<img class="eti_image" src=""/><br/>
				<input type="text" class="eti_image_url" name="taxonomy_cover_image" id="taxonomy_cover_image" value="" />
				<br/>
				<button class="eti_upload_image button">' . __('Upload/Add image', $this->text_domain) . '</button>
			</div>';
        }

		/** add featured image fields on edit tax screen**/
		function edit_taxonomy_field_featured($taxonomy) {
		
			if ($this->taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == $this->placeholder('taxonomy_featured_image') ) 
				$image_url = "";
			else
				$image_url = $this->taxonomy_image_url( $taxonomy->term_id, NULL, TRUE );
			echo '
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="taxonomy_featured_image">' . __('Featured Image', $this->text_domain) . '</label>
				</th>
				<td class="eti-image-wrap">
					<img class="eti_image" src="' . $this->taxonomy_image_url( $taxonomy->term_id, 'medium', TRUE ) . '"/><br/>
					<input type="text" class="eti_image_url" name="taxonomy_featured_image" id="taxonomy_featured_image" value="'.$image_url.'" /><br />
					<button class="eti_upload_image button button">' . __('Upload/Add image', $this->text_domain) . '</button>
					<button class="eti_remove_image_button button">' . __('Remove image', $this->text_domain) . '</button>
				</td>
			</tr>';

		}

        /** add cover featured image fields on edit tax screen**/
        function edit_taxonomy_field_cover($taxonomy) {

            if ($this->taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == $this->placeholder('taxonomy_cover_image') )
                $image_url = "";
            else
                $image_url = $this->taxonomy_image_url( $taxonomy->term_id, NULL, TRUE );
            echo '
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="taxonomy_image">' . __('Cover Image', $this->text_domain) . '</label>
				</th>
				<td class="eti-image-wrap">
					<img class="eti_image eti_image" src="' . $this->taxonomy_image_url( $taxonomy->term_id, 'medium', TRUE ) . '"/><br/>
					<input type="text" class="eti_image_url" name="taxonomy_cover_image" id="taxonomy_cover_image" value="'.$image_url.'" /><br />
					<button class="eti_upload_image button button">' . __('Upload/Add image', $this->text_domain) . '</button>
					<button class="eti_remove_image_button button">' . __('Remove image', $this->text_domain) . '</button>
				</td>
			</tr>';
        }
		/** placeholder images for featured image & cover image **/
		function placeholder($type='taxonomy_featured_image') {

            $options = get_option('eti_options');
            $featured = isset($options['default_cover_image']) ? $options['default_cover_image'] : '';
            $cover = isset($options['default_cover_image']) ? $options['default_cover_image'] : '';
			if($type == 'taxonomy_featured_image') {
				return apply_filters('eti_default_taxonomy_featured_image',$featured);
			} elseif($type == 'taxonomy_cover_image') {
                return apply_filters('eti_default_taxonomy_featured_image',$cover);
			}
		}
		
		function change_insert_button_text($safe_text, $text) {
			return str_replace("Insert into Post", "Use this image", $text);
		}
		
		// get attachment ID by image url
		function get_attachment_id_by_url($image_src) {
			global $wpdb;
			$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_src);
			$id = $wpdb->get_var($query);
			return (!empty($id)) ? $id : NULL;
		}

		// get taxonomy image url for the given term_id (Place holder image by default)
		function taxonomy_image_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE,$img_key='taxonomy_featured_image') {
		
			if (!$term_id) {
				if (is_category())
					$term_id = get_query_var('cat');
				elseif (is_tax()) {
					$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
					$term_id = $current_term->term_id;
				}
			}
	
			$taxonomy_image_url = get_option($img_key.'_'.$term_id);
			if(!empty($taxonomy_image_url)) {
				$attachment_id = $this->get_attachment_id_by_url($taxonomy_image_url);
				if(!empty($attachment_id)) {
					$taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
					$taxonomy_image_url = $taxonomy_image_url[0];
				}
			}

			if ($return_placeholder)
				return ($taxonomy_image_url != '') ? $taxonomy_image_url : $this->placeholder($img_key);
			else
				return $taxonomy_image_url;
		}
		
		/** save taxonomy images **/
		function save_taxonomy_image($term_id) {
			if(isset($_POST['taxonomy_featured_image']))
				update_option('taxonomy_featured_image_'.$term_id, $_POST['taxonomy_featured_image'], NULL);
				
			if(isset($_POST['taxonomy_cover_image']))
				update_option('taxonomy_cover_image_'.$term_id, $_POST['taxonomy_cover_image'], NULL);
		}
		
		/** add/update images from quick edit screen **/
		function quick_edit_custom_box($column_name, $screen, $name) {
			if ($column_name == 'thumb') 
				echo '
				<fieldset>
					<div class="thumb inline-edit-col eti-image-wrap">
						<label>
							<span class="title"><img src="" alt="Thumbnail"/></span>
							<span class="input-text-wrap">
								<input type="text" name="taxonomy_featured_image" value="" class="tax_list" />
							</span>
							<span class="input-text-wrap">
								<button class="eti_upload_image button">' . __('Add image', $this->text_domain) . '</button>
								<button class="eti_remove_image_button button">' . __('Remove image', $this->text_domain) . '</button>
							</span>
						</label>
					</div>
				</fieldset>';
				
			if ($column_name == 'cover_thumb') 
			echo '
			<fieldset>
				<div class="cover_thumb inline-edit-col eti-image-wrap">
					<label>
						<span class="title"><img src="" alt="Cover Image"/></span>
						<span class="input-text-wrap">
							<input type="text" name="taxonomy_cover_image" value="" class="tax_list" />
						</span>
						<span class="input-text-wrap">
							<button class="eti_upload_image button">' . __('Add image', $this->text_domain) . '</button>
							<button class="eti_remove_image_button button">' . __('Remove image', $this->text_domain) . '</button>
						</span>
					</label>
				</div>
			</fieldset>';
		}
		/*
		 * Setup plugin constants
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function setup_constants() {		
			
			// Plugin File
			if ( ! defined( 'ETI_PLUGIN_FILE' ) ) {
				define( 'ETI_PLUGIN_FILE', __FILE__ );
			}
			
			// Plugin Folder URL
			if ( ! defined( 'ETI_PLUGIN_URL' ) ) {
				define( 'ETI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
			
			// Plugin Folder Path
			if ( ! defined( 'ETI_PLUGIN_PATH' ) ) {
				define( 'ETI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}
			
			// Plugin dir path
			if ( ! defined( 'ETI_PLUGIN_DIR' ) ) {
				define( 'ETI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

		}

		/*
		 * Include required files
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		 
		private function includes() {
		
			// Common files required everywhere
			$this->common_includes();
		
		    // Admin Specific files
			if ( is_admin() ) {
				$this->admin_includes();
			}
			
			// Ajax Specific files
			if ( defined( 'DOING_AJAX' ) ) {
				$this->ajax_includes();
			}

			// frontend specific files
			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
				$this->frontend_includes();
			}
			
		}

		/*
		 * Include common required files
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		function common_includes() {
			do_action('eti_common_includes');
		}
		
		/*
		 * Include admin specific required files
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		function admin_includes() {
			do_action('eti_admin_includes');
		}
		
		/*
		 * Include ajax required required files
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		function ajax_includes() {
			do_action('eti_ajax_includes');
		}
		
		/*
		 * Include frontend specific required files
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		function frontend_includes() {
			do_action('eti_frontend_includes');
		}
		
		/*
		 * Admin menu callback
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		function eti_callback() {
			include_once(ETI_PLUGIN_DIR.'lib/admin-page.php');
		}

		/*
		 * Admin scripts and styles 
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		function admin_enqueue_scripts() {
		
			$js_vars = array(
				'wp_version' 			=>	get_bloginfo('version'),
				'featured_placeholder'	=>	$this->placeholder('taxonomy_featured_image'),
				'cover_placeholder'		=>	$this->placeholder('taxonomy_cover_image'),
			);
			
			$this->enqueue_media();
			
			wp_enqueue_style( 'eti-admin-stylesheet', ETI_PLUGIN_URL . 'assets/css/eti-admin.css', array(), $this->version);
			wp_register_script( 'eti-admin-script', ETI_PLUGIN_URL . 'assets/js/eti-admin.js', array('jquery'), $this->version);
			wp_localize_script( 'eti-admin-script', 'eti', $js_vars );
			wp_enqueue_script( 'eti-admin-script');
		}
		
		/**
		 * Add featured thumbnail column.
		 */
		function taxonomy_columns_featured( $columns ) {
			$new_columns 					= array();
			$new_columns['cb'] 				= $columns['cb'];
			$new_columns['thumb'] 			= __('Featured', $this->text_domain);
			unset( $columns['cb'] );

			return array_merge( $new_columns, $columns );
		}

        /**
         * Add cover thumbnail column.
         */
        function taxonomy_columns_cover( $columns ) {
            $new_columns 					= array();
            $new_columns['cb'] 				= $columns['cb'];
            $new_columns['cover_thumb'] 	= __('Cover', $this->text_domain);
            unset( $columns['cb'] );

            return array_merge( $new_columns, $columns );
        }

		/**
		 * Render html for featured column.
		 */
		function taxonomy_column_featured( $columns, $column, $id ) {
			if ( $column == 'thumb' )
				$columns = '<span>
								<img 
									src="' .$this->taxonomy_image_url($id, 'thumbnail', TRUE,'taxonomy_featured_image') . '" 
									alt="' . __('Thumbnail', $this->text_domain) . '" 
									class="wp-post-image" 
								/>
							</span>';

			return $columns;
		}

        /**
         * Render html for featured column.
         */
        function taxonomy_column_cover( $columns, $column, $id ) {
            if ( $column == 'cover_thumb' )
                $columns = '<span>
								<img
									src="' .$this->taxonomy_image_url($id, 'thumbnail', TRUE,'taxonomy_cover_image') . '"
									alt="' . __('Thumbnail', $this->text_domain) . '"
									class="wp-post-image"
								/>
							</span>';

            return $columns;
        }


		function display_images($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE, $key = 'taxonomy_featured_image') {
		
			if (!$term_id) {
				if (is_category())
					$term_id = get_query_var('cat');
				elseif (is_tax()) {
					$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
					$term_id = $current_term->term_id;
				}
			}
	
			$taxonomy_image_url = get_option($key.'_'.$term_id);
			if(!empty($taxonomy_image_url)) {
				$attachment_id = $this->get_attachment_id_by_url($taxonomy_image_url);
				if(!empty($attachment_id))
					$taxonomy_image = wp_get_attachment_image($attachment_id, $size, FALSE, $attr);
				else {
					$image_attr = '';
					if(is_array($attr)) {
						if(!empty($attr['class']))
							$image_attr .= ' class="'.$attr['class'].'" ';
						if(!empty($attr['alt']))
							$image_attr .= ' alt="'.$attr['alt'].'" ';
						if(!empty($attr['width']))
							$image_attr .= ' width="'.$attr['width'].'" ';
						if(!empty($attr['height']))
							$image_attr .= ' height="'.$attr['height'].'" ';
						if(!empty($attr['title']))
							$image_attr .= ' title="'.$attr['title'].'" ';
					}
					$taxonomy_image = '<img src="'.$taxonomy_image_url.'" '.$image_attr.'/>';
				}
			}

			if ($echo)
				echo $taxonomy_image;
			else
				return $taxonomy_image;

		}


		
	}
endif; // End if class_exists check

/*
 * @since 1.0.0
 * @return object The one true EASY_TAX_IMAGES Instance
 */
function WPETI() {
	return EASY_TAX_IMAGES::instance();
}
// Get EASY_TAX_IMAGES Running
WPETI();

// global template functions


// display taxonomy featured image for the given term_id
function taxonomy_featured_image($term_id = NULL, $size = 'medium', $attr = NULL, $echo = TRUE,$key = 'taxonomy_featured_image') {
    WPETI()->display_images($term_id,$size,$attr,$echo,$key);
}

// display taxonomy cover image for the given term_id
function taxonomy_cover_image($term_id = NULL, $size = 'large', $attr = NULL, $echo = TRUE,$key = 'taxonomy_cover_image') {
    WPETI()->display_images($term_id,$size,$attr,$echo,$key);
}
