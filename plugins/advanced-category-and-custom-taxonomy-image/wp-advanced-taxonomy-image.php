<?php
/**
 * Plugin Name: Advanced Category and Custom Taxonomy Image
 * Plugin URI: https://wordpress.org/plugins/advanced-category-and-custom-taxonomy-image/
 * Description: Advanced Category and Taxonomy Image Plugin allow you to add image to your category / tag / custom taxonomy for different platforms (Mobile/ Desktop/ Tablet/ Mac/ Universal etc).
 * Version: 1.0.8
 * Author: Sajjad Hossain Sagor
 * Author URI: https://sajjadhsagor.com/
 * Text Domain: advanced-category-and-custom-taxonomy-image
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'AD_CAT_TAX_IMG_ROOT_DIR', dirname( __FILE__ ) ); // Plugin root dir

define( 'AD_CAT_TAX_IMG_ROOT_URL', plugin_dir_url( __FILE__ ) ); // Plugin root url

// add settings api wrapper
require AD_CAT_TAX_IMG_ROOT_DIR . '/includes/class.settings-api.php';

/**
 * Category & Taxonomy Settings Class
 *
 * @author Sajjad Hossain Sagor
 */
class AD_CAT_TAX_IMG_SETTINGS
{
	private $settings_api;

	function __construct()
	{    
		$this->settings_api = new WpNinjaCoder_Settings_API;

		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	public function admin_init()
	{
        //set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );

		$this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
		$this->settings_api->admin_init();
	}

	public function admin_menu()
	{
		add_options_page( __( 'Advanced Category & Taxonomy Image', 'advanced-category-and-custom-taxonomy-image' ), __( 'Advanced Category & Taxonomy Image', 'advanced-category-and-custom-taxonomy-image' ), 'manage_options', 'advanced-cat-tax-image', array( $this, 'render_advanced_taxonomy_image_settings' ) );
	}

	public function load_plugin_textdomain()
	{
		load_plugin_textdomain( 'advanced-category-and-custom-taxonomy-image', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function get_settings_sections()
	{
		$sections = array(
			array(
				'id'    => 'ad_cat_tax_img_basic_settings',
				'title' => __( 'General', 'advanced-category-and-custom-taxonomy-image' )
			),
			array(
				'id'    => 'ad_cat_tax_img_advanced_settings',
				'title' => __( 'Advanced ', 'advanced-category-and-custom-taxonomy-image' )
			)
		);

		return $sections;
	}

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    public function get_settings_fields()
    {
    	$settings_fields = array(
    		'ad_cat_tax_img_basic_settings' => array(
    			array(
    				'name'    => 'enabled_taxonomies',
    				'label'   => __( 'Select Taxonomies', 'advanced-category-and-custom-taxonomy-image' ),
    				'desc'    => __( 'Please Select Taxonomies You Want To Include Custom Image', 'advanced-category-and-custom-taxonomy-image' ),
    				'type'    => 'multicheck',
    				'options' => $this->get_all_taxonomies()
    			)    
    		),
    		'ad_cat_tax_img_advanced_settings' => array(
    			array(
    				'name'    => 'enabled_devices',
    				'label'   => __( 'Enable Device Filter', 'advanced-category-and-custom-taxonomy-image' ),
    				'desc'    => __( 'Please Select Device Type You Want To Use Enable For', 'advanced-category-and-custom-taxonomy-image' ),
    				'type'    => 'multicheck',
    				'options' => array(
    					'any'   	=> __( 'Any', 'advanced-category-and-custom-taxonomy-image' ),
    					'android'   => __( 'Android', 'advanced-category-and-custom-taxonomy-image' ),
    					'ios' 	    => __( 'iOS (Mac | iPhone | iPad | iPod)', 'advanced-category-and-custom-taxonomy-image' ),
    					'windowsph' => __( 'Windows Phone', 'advanced-category-and-custom-taxonomy-image' ),
    					'mobile'    => __( 'Mobile (Any)', 'advanced-category-and-custom-taxonomy-image' ),
    					'tablet'    => __( 'Tablet', 'advanced-category-and-custom-taxonomy-image' ),
    					'desktop'   => __( 'Desktop', 'advanced-category-and-custom-taxonomy-image' ),
    				),
    			)
    		)
    	);

    	return $settings_fields;
    }

    /**
     * Render settings fields
     *
     */
    public function render_advanced_taxonomy_image_settings()
    {
    	echo '<div class="wrap">';

	    	$this->settings_api->show_navigation();

	    	$this->settings_api->show_forms();

    	echo '</div>';
    }

    /**
     * Returns all the taxonomies
     *
     * @return array taxonomies
     */
    public function get_all_taxonomies()
    {
    	$args = array();

		$output = 'objects'; // objects
		
		$taxonomies = get_taxonomies( $args, $output );

		$name_value_pair = array();

		// exclude some wp & woocommerce private taxonomies 
		$disabled_taxonomies = array(
			'nav_menu',
			'link_category',
			'post_format',
			'product_visibility',
			'product_shipping_class',
			'action-group',
			'product_type',
			'wp_theme',
			'wp_template_part_area',
		);
		
		if ( $taxonomies )
		{
			foreach ( $taxonomies as $taxonomy )
			{
				if ( in_array( $taxonomy->name, $disabled_taxonomies ) ) continue;

				$name_value_pair[$taxonomy->name] = ucwords( $taxonomy->label );
			}
		}

		return $name_value_pair;
	}
}

/**
 * Returns option value
 *
 * @return string|array option value
 */
function ad_cat_tax_img_get_option( $option, $section, $default = '' )
{
	$options = get_option( $section );

	if ( isset( $options[$option] ) ){

		return $options[$option];
	}

	return $default;
}

$cat_tax_settings = new AD_CAT_TAX_IMG_SETTINGS();

// register custom image field for all enabled taxonomies
function ad_cat_tax_img_register_taxonomy_img_field()
{
	add_action( 'admin_enqueue_scripts', function()
	{
		wp_enqueue_media(); // load WP Media Uploader Modal scripts
		
		wp_enqueue_style( 'ad_cat_tax_img_custom_style', AD_CAT_TAX_IMG_ROOT_URL . 'assets/css/style.css', array(), false, 'all' );
		
		wp_enqueue_script( 'ad_cat_tax_img_custom_script', AD_CAT_TAX_IMG_ROOT_URL . 'assets/js/script.js', array( 'jquery' ), time(), true );
	} );

	/**
	 * Add Go To Settings Page in Plugin List Table
	 *
	 */
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links )
	{
		$plugin_actions = array();

		$plugin_actions[] = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=advanced-cat-tax-image' ), __( 'Settings', 'advanced-category-and-custom-taxonomy-image' ) );

		$plugin_actions[] = sprintf( '<a href="%s">%s</a>', 'https://wordpress.org/plugins/advanced-category-and-custom-taxonomy-image/#description-header', __( 'Documentation', 'advanced-category-and-custom-taxonomy-image' ) );
		
		return array_merge( $links, $plugin_actions );
	} );

	// get all image field enabled taxonomies
	$enabled_taxonomies = ad_cat_tax_img_get_option( 'enabled_taxonomies', 'ad_cat_tax_img_basic_settings' );

	//check if any taxonomy enabled
	if ( ! empty( $enabled_taxonomies ) )
	{
		//iterate all enabled taxonomies
		foreach ( $enabled_taxonomies as $enabled_taxonomy )
		{
			// Add shortcode column to taxonomy list
			add_filter( "manage_edit-{$enabled_taxonomy}_columns" , function( $columns )
			{
				// add carousel shortcode column
				$columns['taxonomy_image_template_tag'] = __( 'Taxonomy Image Template Tag', 'advanced-category-and-custom-taxonomy-image' );
				
				return $columns;
			} );

			// add shortcode column content
			add_filter( "manage_{$enabled_taxonomy}_custom_column", function( $content, $column_name, $term_id )
			{
				// check if column is our custom column 'carousel_shortcode' 
				if ( 'taxonomy_image_template_tag' == $column_name )
				{
					return ad_tax_image_available( $term_id ) ? '<code>echo get_taxonomy_image( ' . $term_id . ', true, "your-custom-class-list-seperated-by-space" );<br><br>echo do_shortcode( \'[ad_tax_image term_id="' . $term_id . '" return_img_tag="true" class="your-custom-class-list-seperated-by-space"]\' );</code>' : '';
				}

				return '';

			}, 10, 3 );

			// register all enabled taxonomy to add taxonomy field
			add_action( $enabled_taxonomy . '_add_form_fields', function()
			{
				$label = __( 'Choose File', 'advanced-category-and-custom-taxonomy-image' );

				// get all image field enabled devices
				$enabled_devices = ad_cat_tax_img_get_option( 'enabled_devices', 'advanced-category-and-custom-taxonomy-image' );

				//check if any device enabled
				if ( ! empty( $enabled_devices ) )
				{
					$html = '<div class="form-field"> <label for="tax_image_url_universal">' . __( 'Taxonomy Image For All Devices', 'advanced-category-and-custom-taxonomy-image' ) . '</label>';
					
						$html .= '<input type="text" class="tax_image_upload wpsa-url" id="tax_image_url_universal" name="tax_image_url[tax_image_url_universal]" value=""/>';

						$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';

						$html .= '<p class="description">' . __( 'Choose Image To Show For All Devices', 'advanced-category-and-custom-taxonomy-image' ) . '</p>';
					
					$html .= '</div>';

					echo $html;
					
					// registed custom image field for each enabled devices
					foreach ( $enabled_devices as $enabled_device )
					{
						$html  = '<div class="form-field"> <label for="tax_image_url_' . $enabled_device . '">' . __( 'Taxonomy Image For ', 'advanced-category-and-custom-taxonomy-image' ) . ucwords( $enabled_device ) . '</label>';
						
							$html .= '<input type="text" class="tax_image_upload wpsa-url" id="tax_image_url_' . $enabled_device . '" name="tax_image_url[tax_image_url_' . $enabled_device . ']" value=""/>';

							$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';

							$html .= '<p class="description">' . __( 'Choose Image To Show For ', 'advanced-category-and-custom-taxonomy-image' ) . ucwords( $enabled_device ) . '</p>';
						
						$html .= '</div>';

						echo $html;
					}
				}
				else
				{
					$html = '<div class="form-field"><label for="tax_image_url_universal">' . __( 'Taxonomy Image For All Devices', 'advanced-category-and-custom-taxonomy-image' ) . '</label>';
					
						$html .= '<input type="text" class="tax_image_upload wpsa-url" id="tax_image_url_universal" name="tax_image_url[tax_image_url_universal]" value=""/>';

						$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';

						$html .= '<p class="description">' . __( 'Choose Image To Show For All Devices', 'advanced-category-and-custom-taxonomy-image' ) . '</p>';
					
					$html .= '</div>';

					echo $html;
				}
			} );
			
			add_action( $enabled_taxonomy . '_edit_form_fields', function( $taxonomy )
			{
				$label = __( 'Choose File', 'advanced-category-and-custom-taxonomy-image' );

				// get all image field enabled devices
				$enabled_devices = ad_cat_tax_img_get_option( 'enabled_devices', 'ad_cat_tax_img_advanced_settings' );

				//check if any device enabled
				if ( ! empty( $enabled_devices ) )
				{
					$universal_image_url = esc_url( get_term_meta( $taxonomy->term_id, 'tax_image_url_universal', true ) );

					$image = $universal_image_url != '' ? '<img src="' . $universal_image_url . '" width="100"  height="100"/>' : '';
					
					$html  = '<tr class="form-field">';
					
					$html .= '<th scope="row" valign="top"><label for="tax_image_url_universal">' . __( 'Taxonomy Image For All Devices', 'advanced-category-and-custom-taxonomy-image' ) . '</label></th><td>';
					
					$html .= $image;
					
					$html .= '<br />';
					
					$html .= '<input type="text" class="tax_image_upload wpsa-url" id="tax_image_url_universal" name="tax_image_url[tax_image_url_universal]" value="' . $universal_image_url . '"/>';

					$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
					
					$html .= '<p class="description">' . __( 'Choose Image To Show For All Devices', 'advanced-category-and-custom-taxonomy-image' ) . '</p></td></tr>';

					echo $html;
					
					// registed custom image field for each enabled devices
					foreach ( $enabled_devices as $enabled_device )
					{
						$device_image_url = esc_url( get_term_meta( $taxonomy->term_id, 'tax_image_url_' . $enabled_device, true ) );

						$image = $device_image_url != '' ? '<img src="' . $device_image_url . '" width="100"  height="100"/>' : '';
						
						$html  = '<tr class="form-field">';
						
						$html .= '<th scope="row" valign="top"><label for="tax_image_url_' . $enabled_device . '">' . __( 'Taxonomy Image For ', 'advanced-category-and-custom-taxonomy-image' ) . ucwords( $enabled_device ) . '</label></th><td>';

						$html .= $image;
						
						$html .= '<br />';
						
						$html .= '<input type="text" class="tax_image_upload wpsa-url" id="tax_image_url_' . $enabled_device . '" name="tax_image_url[tax_image_url_' . $enabled_device . ']" value="' . $device_image_url . '"/>';

						$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';

						$html .= '<p class="description">' . __( 'Choose Image To Show For ', 'advanced-category-and-custom-taxonomy-image' ) . ucwords( $enabled_device ) . '</p>';

						echo $html;
					}
				}
				else
				{
					$universal_image_url = esc_url( get_term_meta( $taxonomy->term_id, 'tax_image_url_universal', true ) );

					$image = $universal_image_url != '' ? '<img src="' . $universal_image_url . '" width="100"  height="100"/>' : '';

					$html  = '<tr class="form-field">';
					
					$html .= '<th scope="row" valign="top"><label for="tax_image_url_universal">' . __( 'Taxonomy Image For All Devices', 'advanced-category-and-custom-taxonomy-image' ) . '</label></th><td>';
					
					$html .= $image;
					
					$html .= '<br />';
					
					$html .= '<input type="text" class="tax_image_upload wpsa-url" id="tax_image_url_universal" name="tax_image_url[tax_image_url_universal]" value="' . $universal_image_url . '"/>';

					$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
					
					$html .= '<p class="description">' . __( 'Choose Image To Show For All Devices', 'advanced-category-and-custom-taxonomy-image' ) . '</p></td></tr>';

					echo $html;
				}
			} );
		}
	}
}

ad_cat_tax_img_register_taxonomy_img_field();

//edit_$taxonomy
add_action( 'edit_term','ad_cat_tax_img_url_save' );

add_action( 'create_term','ad_cat_tax_img_url_save' );

// save taxonomy values
function ad_cat_tax_img_url_save( $term_id )
{
	if ( isset( $_POST['tax_image_url'] ) && ! empty( $_POST['tax_image_url'] ) )
	{
		if ( is_array( $_POST['tax_image_url'] ) )
		{
			foreach ( $_POST['tax_image_url'] as $name => $value )
			{
				update_term_meta( $term_id, $name, esc_url( $value ) );
			}
		}
	}
}

// register template tag function to show taxonomy image
function get_taxonomy_image( $term_id = '', $return_img_tag = false, $class = array() )
{
	require_once AD_CAT_TAX_IMG_ROOT_DIR . '/includes/Mobile_Detect.php';

	$detect = new Mobile_Detect;

	$term_id = $term_id == '' ? get_queried_object()->term_id : $term_id;

	// get all image field enabled taxonomies
	$enabled_taxonomies = ad_cat_tax_img_get_option( 'enabled_taxonomies', 'ad_cat_tax_img_basic_settings' );

	// get all image field enabled devices
	$enabled_devices = ad_cat_tax_img_get_option( 'enabled_devices', 'ad_cat_tax_img_advanced_settings' );

	//check if any taxonomy enabled
	if ( ! empty( $enabled_taxonomies ) )
	{
		$device_image_url = get_term_meta( $term_id, 'tax_image_url_universal', true );

		//check if any device enabled
		if ( ! empty( $enabled_devices ) )
		{
			// registed custom image field for each enabled devices
			foreach ( $enabled_devices as $enabled_device )
			{
				// available devices
                //'android'   => 'Android',
                //'ios' 	  => 'iOS (Mac, iPhone, iPad, iPod)',
                //'windowsph' => 'Windows Phone',
				//'mobile'    => 'Mobile',
                //'tablet'    => 'Tablet',
                //'desktop'   => 'Desktop'

				if( $enabled_device == 'all' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //android match found no need to check further
				}
				else if( $enabled_device == 'android' && $detect->isAndroidOS() )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //android match found no need to check further
				}
				else if( $enabled_device == 'iphone' && $detect->isiOS() )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //iOS match found no need to check further
				}
				else if( $enabled_device == 'windowsph' && $detect->version( 'Windows Phone' ) )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //Windows Phone match found no need to check further
				}
				else if( $enabled_device == 'mobile' && $detect->isMobile() )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //Any Mobile match found no need to check further
				}
				else if( $enabled_device == 'tablet' && $detect->isTablet() )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //Any Mobile match found no need to check further
				}
				else if( $enabled_device == 'desktop' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //match found no need to check further
				}
			}
		}
	}
	else
	{
		$device_image_url = __( 'Please Enable Taxonomies First!', 'advanced-category-and-custom-taxonomy-image' );
	}

	$classes = ! empty( $class ) ? implode( ' ', $class ) : '';

	$result = $return_img_tag ? "<img src='" . esc_url( $device_image_url ) . "' class='" . $classes . "'>" : esc_url( $device_image_url );
	
	return ! empty( $device_image_url ) ? $result : __( 'Please Upload Image First!', 'advanced-category-and-custom-taxonomy-image' );
}

// checks if taxonomy image is available for any device
function ad_tax_image_available( $term_id = '' )
{
	if ( empty( $term_id ) && ! $term_id ) return false;

	// get all image field enabled taxonomies
	$enabled_taxonomies = ad_cat_tax_img_get_option( 'enabled_taxonomies', 'ad_cat_tax_img_basic_settings' );

	// get all image field enabled devices
	$enabled_devices = ad_cat_tax_img_get_option( 'enabled_devices', 'ad_cat_tax_img_advanced_settings' );

	//check if any taxonomy enabled
	if ( ! empty( $enabled_taxonomies ))
	{
		$device_image_url = get_term_meta( $term_id, 'tax_image_url_universal', true );

		//check if any device enabled
		if ( ! empty( $enabled_devices ) )
		{
			// registed custom image field for each enabled devices
			foreach ( $enabled_devices as $enabled_device )
			{
				// available devices
                //'android'   => 'Android',
                //'ios' 	  => 'iOS (Mac, iPhone, iPad, iPod)',
                //'windowsph' => 'Windows Phone',
				//'mobile'    => 'Mobile',
                //'tablet'    => 'Tablet',
                //'desktop'   => 'Desktop'

				if( $enabled_device == 'all' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //android match found no need to check further
				}
				else if( $enabled_device == 'android' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //android match found no need to check further

				}
				else if( $enabled_device == 'iphone' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //iOS match found no need to check further
				}
				else if( $enabled_device == 'windowsph' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //Windows Phone match found no need to check further
				}
				else if( $enabled_device == 'mobile' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //Any Mobile match found no need to check further
				}
				else if( $enabled_device == 'tablet' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //Any Mobile match found no need to check further
				}
				else if( $enabled_device == 'desktop' )
				{
					$device_image_url = get_term_meta( $term_id, 'tax_image_url_' . $enabled_device, true );

					break; //match found no need to check further
				}
			}
		}
	}
	else
	{
		return false;
	}
	
	return ! empty( $device_image_url ) ? true : false;
}

add_shortcode( 'ad_tax_image', function( $atts )
{
	$atts = shortcode_atts( array(
		'term_id' => '',
		'class' => '',
		'return_img_tag' => false,
	), $atts );

	return get_taxonomy_image( $attr['term_id'], filter_var( $atts['return_img_tag'], FILTER_VALIDATE_BOOLEAN ), explode( ' ', $atts['class'] ) );
} );
