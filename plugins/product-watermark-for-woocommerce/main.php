<?php
define( "BeRocket_image_watermark_domain", 'product-watermark-for-woocommerce'); 
define( "image_watermark_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('product-watermark-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'berocket/framework.php');
foreach (glob(__DIR__ . "/includes/*.php") as $filename)
{
    include_once($filename);
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class BeRocket_image_watermark extends BeRocket_Framework {
    public static $settings_name = 'br-image_watermark-options';
    protected static $instance;
    protected $disable_settings_for_admin = array();
    protected $check_init_array = array(
        array(
            'check' => 'woocommerce_version',
            'data' => array(
                'version' => '3.0',
                'operator' => '>=',
                'notice'   => 'Plugin Product Watermark for WooCommerce required WooCommerce version 3.0 or higher'
            )
        ),
        array(
            'check' => 'framework_version',
            'data' => array(
                'version' => '2.1',
                'operator' => '>=',
                'notice'   => 'Please update all BeRocket plugins to the most recent version. Product Watermark for WooCommerce is not working correctly with older versions.'
            )
        ),
        array(
            'check' => 'temp_php_extension',
            'data' => array(
                'extension' => 'gd',
                'notice'   => 'The required PHP module, GD, is not installed, or has been disabled. Product Watermark for WooCommerce required GD module to work with images.'
            )
        ),
    );
    function __construct () {
        $this->info = array(
            'id'          => 14,
            'lic_id'      => 77,
            'version'     => BeRocket_image_watermark_version,
            'plugin'      => '',
            'slug'        => '',
            'key'         => '',
            'name'        => '',
            'plugin_name' => 'image_watermark',
            'full_name'   => __('Product Watermark for WooCommerce', 'product-watermark-for-woocommerce'),
            'norm_name'   => __('Product Watermark', 'product-watermark-for-woocommerce'),
            'price'       => '',
            'domain'      => 'product-watermark-for-woocommerce',
            'templates'   => image_watermark_TEMPLATE_PATH,
            'plugin_file' => BeRocket_image_watermark_file,
            'plugin_dir'  => __DIR__,
        );
        $this->defaults = array(
            'enable_live'       => '0',
            'jpeg_quantity'     => '90',
            'max_img_width'     => '2500',
            'max_img_height'    => '2500',
            'php_memory_limit'  => (function_exists('ini_get') ? ini_get('memory_limit') : ''),
            'shop_thumbnail'    => array(
                'ratio'             => array(0 => '1', 1 => '1', 2 => '1', 3 => '1', 4 => '1'),
                'text'              => '',
                'text_alpha'        => '30',
                'text_angle'        => '0',
                'font_size'         => '20',
                'image_count'       => '0',
                'font_color'        => '#FFFFFF',
                'image'             => array(0 => '', 1 => '', 2 => '', 3 => '', 4 => ''),
                'top'               => array(0 => '25', 1 => '25', 2 => '25', 3 => '25', 4 => '25'),
                'left'              => array(0 => '25', 1 => '25', 2 => '25', 3 => '25', 4 => '25'),
                'width'             => array(0 => '50', 1 => '50', 2 => '50', 3 => '50', 4 => '50'),
                'height'            => array(0 => '50', 1 => '50', 2 => '50', 3 => '50', 4 => '50'),
            ),
            'shop_single'       => array(
                'ratio'             => array(0 => '1', 1 => '1', 2 => '1', 3 => '1', 4 => '1'),
                'text'              => '',
                'text_alpha'        => '30',
                'text_angle'        => '0',
                'font_size'         => '20',
                'image_count'       => '0',
                'font_color'        => '#FFFFFF',
                'image'             => array(0 => '', 1 => '', 2 => '', 3 => '', 4 => ''),
                'top'               => array(0 => '25', 1 => '25', 2 => '25', 3 => '25', 4 => '25'),
                'left'              => array(0 => '25', 1 => '25', 2 => '25', 3 => '25', 4 => '25'),
                'width'             => array(0 => '50', 1 => '50', 2 => '50', 3 => '50', 4 => '50'),
                'height'            => array(0 => '50', 1 => '50', 2 => '50', 3 => '50', 4 => '50'),
            ),
            'shop_catalog'      => array(
                'ratio'             => array(0 => '1', 1 => '1', 2 => '1', 3 => '1', 4 => '1'),
                'text'              => '',
                'text_alpha'        => '30',
                'text_angle'        => '0',
                'font_size'         => '20',
                'image_count'       => '0',
                'font_color'        => '#FFFFFF',
                'image'             => array(0 => '', 1 => '', 2 => '', 3 => '', 4 => ''),
                'top'               => array(0 => '25', 1 => '25', 2 => '25', 3 => '25', 4 => '25'),
                'left'              => array(0 => '25', 1 => '25', 2 => '25', 3 => '25', 4 => '25'),
                'width'             => array(0 => '50', 1 => '50', 2 => '50', 3 => '50', 4 => '50'),
                'height'            => array(0 => '50', 1 => '50', 2 => '50', 3 => '50', 4 => '50'),
            ),
            'addons'            => array(
                '/media_buttons/media_buttons.php',
            ),
            'image_lib' => ''
        );
        $this->values = array(
            'settings_name' => 'br-image_watermark-options',
            'option_page'   => 'br-image_watermark',
            'premium_slug'  => 'woocommerce-products-image-watermark',
            'free_slug'     => 'product-watermark-for-woocommerce',
            'hpos_comp'     => true
        );
        $this->active_libraries = array('addons');
        $this->feature_list = array();
        add_filter('BeRocket_framework_check_init_temp_php_extension', array($this, 'temp_php_extension_check'), 10, 2);
        parent::__construct( $this );
        if( $this->check_framework_version() ) {
            if ( $this->init_validation() ) {
                $options = $this->get_option();
                if( ! empty($options['image_lib']) && $options['image_lib'] == 'imagick' && class_exists('Imagick') ) {
                    include_once('includes/imagelib/imagick.php');
                } else {
                    include_once('includes/imagelib/gd.php');
                }
                //add meta key to image that must be watermarked
                add_action('added_post_meta', array($this, 'set_thumbnail_id'), 10, 4);
                add_action('updated_post_meta', array($this, 'set_thumbnail_id'), 10, 4);
                add_action('added_post_meta', array($this, 'set_product_image_gallery'), 10, 4);
                add_action('updated_post_meta', array($this, 'set_product_image_gallery'), 10, 4);
                
                add_filter('get_post_metadata', array($this, 'get_thumbnail_id'), 1, 4);
                add_filter('get_post_metadata', array($this, 'get_product_image_gallery'), 1, 4);
                add_filter('woocommerce_product_get_image_id', array($this, 'get_image_id_prop'), 500, 1);
                add_filter('berocket_apply_all_content_to_image', array($this, 'add_single_image_watermark'), 10, 3);
                add_action( 'wp_ajax_berocket_single_image', array ( $this, 'berocket_single_image' ) );
            }
        } else {
            add_filter( 'berocket_display_additional_notices', array(
                $this,
                'old_framework_notice'
            ) );
        }
    }
    function temp_php_extension_check($result_and, $check_and) {
        $result_and = extension_loaded( $check_and['data']['extension'] );
        if( ! $result_and ) {
            $this->check_lib->show_notice($result_and, $check_and['data']);
        }
        return $result_and;
    }
    function berocket_single_image() {
        if( ! empty($_GET['id']) && ! empty($_GET['generation']) ) {
            $id = intval($_GET['id']);
            if( current_user_can('edit_post', $id) ) {
                $this->add_watermark_to_images($id, $_GET['generation']);
                if( $_GET['generation'] == 'restore' ) {
                    update_post_meta($id, 'br_watermark', '1');
                } else {
                    update_post_meta($id, 'br_watermark', '2');
                }
            }
        }
        wp_die();
    }
    function init_validation() {
        return parent::init_validation() && $this->check_framework_version();
    }
    function check_framework_version() {
        return ( ! empty(BeRocket_Framework::$framework_version) && version_compare(BeRocket_Framework::$framework_version, 2.1, '>=') );
    }
    function old_framework_notice($notices) {
        $notices[] = array(
            'start'         => 0,
            'end'           => 0,
            'name'          => $this->info[ 'plugin_name' ].'_old_framework',
            'html'          => __('<strong>Please update all BeRocket plugins to the most recent version. Product Watermark for WooCommerce is not working correctly with older versions.</strong>', 'product-watermark-for-woocommerce'),
            'righthtml'     => '',
            'rightwidth'    => 0,
            'nothankswidth' => 0,
            'contentwidth'  => 1600,
            'subscribe'     => false,
            'priority'      => 10,
            'height'        => 50,
            'repeat'        => false,
            'repeatcount'   => 1,
            'image'         => array(
                'local'  => '',
                'width'  => 0,
                'height' => 0,
                'scale'  => 1,
            )
        );
        return $notices;
    }
    public function init () {
        parent::init();
        $options = $this->get_option();
        if( ! empty($options['enable_live']) ) {
            if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
                add_filter( 'image_downsize', array( $this, 'replace_image' ), 5, 3 );
            } else {
                add_filter( 'image_downsize', array( $this, 'replace_image' ), 200, 3 );
            }
        }
    }
    public function replace_image ($status, $post_id, $size) {
        $post_ready = get_option('br_watermarked');
        if( ! isset( $post_ready ) || ! is_array( $post_ready ) ) {
            $post_ready = array();
        }
        $br_watermark = get_post_meta($post_id, 'br_watermark', true);
        if( in_array( $post_id, $post_ready ) ) {
            $br_watermark = '2';
            update_post_meta($post_id, 'br_watermark', $br_watermark);
        }
        if( $br_watermark == '1' ) {
            if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
                remove_filter( 'image_downsize', array( $this, 'replace_image' ), 5, 3 );
            } else {
                remove_filter( 'image_downsize', array( $this, 'replace_image' ), 200, 3 );
            }
            $br_watermark = '2';
            $this->add_watermark_to_images($post_id, 'create');
            update_post_meta($post_id, 'br_watermark', $br_watermark);
            $options = $this->get_option();
            if( ! empty($options['enable_live']) ) {
                if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
                    add_filter( 'image_downsize', array( $this, 'replace_image' ), 5, 3 );
                } else {
                    add_filter( 'image_downsize', array( $this, 'replace_image' ), 200, 3 );
                }
            }
        }
        return $status;
    }
    public function convert_image_types($types = array()) {
        $types = array_merge(array(
            'shop_thumbnail'                => 'shop_single',
            'woocommerce_thumbnail'         => 'shop_single',
            'woocommerce_gallery_thumbnail' => 'shop_single',
            'thumbnail'                     => 'shop_single',
            'shop_catalog'                  => 'shop_single',
            'medium'                        => 'shop_single',
            'medium_large'                  => 'shop_single',
            'shop_catalog'                  => 'shop_single',
            'woocommerce_single'            => 'shop_single',
            'shop_single'                   => 'shop_single',
            'large'                         => 'shop_single',
            'full'                          => 'shop_single',
            'full_scaled'                   => 'shop_single'
        ), $types);
        
        return apply_filters('br_watermark_replace_types', $types);
    }
    public function add_watermark_to_images($post_id, $generation = 'create') {
        $options = $this->get_option();
        $types = $this->convert_image_types();
        $upload_dir = wp_upload_dir();
		$image_data_class = new BeRocket_attachment_data($post_id);
		$image_data = $image_data_class->get_attachment_info();
		if( $image_data != FALSE ) {
			$fullsize_filepath = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $image_data['relative_path'];
			$this->backup_image($fullsize_filepath, 'restore');
			$all_sizes = array(
				'full'	=> array(
					'fullpath' => $fullsize_filepath,
					'path' => $image_data['relative_path']
				)
			);
            if( ! empty($image_data['scaled']) ) {
                $all_sizes['full_scaled'] = array(
					'fullpath' => $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $image_data['scaled'],
					'path' => $image_data['scaled']
				);
            }
            foreach($image_data['registered_sizes'] as $size) {
                if( ! empty($size['filename']) ) {
                    $size_filepath = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $image_data['folder'] . DIRECTORY_SEPARATOR . $size['filename'];
                    $this->backup_image($size_filepath, 'restore');
                    if( $fullsize_filepath == $size_filepath && isset($types[$size['label']]) ) {
                        unset($types[$size['label']]);
                    }
                    $all_sizes[$size['label']] = array(
                        'fullpath' => $size_filepath,
                        'path' => $image_data['folder'] . DIRECTORY_SEPARATOR . $size['filename']
                    );
                }
            }
            if( $generation == 'restore' ) {
                $image_name = explode('.', $image_data['file_name']);
                $image_dimension = array_pop($image_name);
                $image_name = implode('.', $image_name);
                $image_name = $image_name . '*_br_backup.' . $image_dimension;
                $image_path = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $image_data['folder'] . DIRECTORY_SEPARATOR . $image_name;
                $files = glob($image_path);
                if( is_array($files) ) {
                    foreach($files as $backup_filepath) {
                        $filepath = str_replace('_br_backup.', '.', $backup_filepath);
                        if( file_exists($filepath) ) {
                            $this->backup_image($filepath, 'restore');
                        } else {
                            unlink($backup_filepath);
                        }
                    }
                }
            } else {
                try {
                    $types_applied = 0;
                    foreach( $types as $base_type => $type ) {
                        if( ! array_key_exists($base_type, $all_sizes) ) continue;
                        if( file_exists($all_sizes[$base_type]['fullpath']) ) {
                            $this->backup_image($all_sizes[$base_type]['fullpath'], $generation);
                            if( $generation == 'create' && 
                                isset( $options[$type] )
                            ) {
                                if( function_exists('set_time_limit')) {
                                    set_time_limit(60);
                                }
                                $watermark = $options[$type];
                                $watermark_applied = apply_filters('berocket_watermark_apply',
                                    false,
                                    $post_id,
                                    array(
                                        'all_sizes' => $all_sizes,
                                        'base_type' => $base_type,
                                        'watermark' => $watermark
                                    )
                                );
                                if( $watermark_applied ) {
                                    $types_applied++;
                                }
                            }
                        }
                    }
                    if( $types_applied == 0 ) {
                        echo '<span class="error">Attachment do not have sizes or watermark do not setuped('.$post_id.')</span>';
                        BeRocket_error_notices::add_plugin_error($this->info['id'], 'Attachment do not have sizes or watermark do not setuped', array(
                            'types'         => $types,
                            'image_data'    => $image_data,
                            'attachment_id' => $post_id
                        ));
                    }
                } catch (Exception $e) {
                    echo '<span class="error">', $e->getMessage(), '</span>';
                    BeRocket_error_notices::add_plugin_error($this->info['id'], 'Exception', array(
                        'error'         => $e->getMessage(),
                        'types'         => $types,
                        'image_data'    => $image_data,
                        'attachment_id' => $post_id
                    ));
                }
            }
            include_once( ABSPATH . 'wp-admin/includes/image.php' );
            $img_metadata = wp_get_attachment_metadata($post_id);
            if( $img_metadata != false ) {
                $uploads = wp_get_upload_dir();
                $attachment_metadata = wp_get_attachment_metadata($post_id);
                add_filter('intermediate_image_sizes_advanced', array($this, 'intermediate_image_sizes_advanced'));
                wp_generate_attachment_metadata($post_id, $uploads['basedir'].'/'.$img_metadata['file']);
                remove_filter('intermediate_image_sizes_advanced', array($this, 'intermediate_image_sizes_advanced'));
                wp_update_attachment_metadata($post_id, $attachment_metadata);
            }
		} else {
            echo '<span class="error">Image data error('.$post_id.')</span>';
            BeRocket_error_notices::add_plugin_error($this->info['id'], 'Image data error', array(
                'image_data'       => $image_data,
                'image_data_class' => $image_data_class,
                'attachment_id'    => $post_id
            ));
        }
    }
    public function add_single_image_watermark($image_content, $watermark, $image_data) {
        $image_content = apply_filters('berocket_apply_content_to_image_image', 
            $image_content, 
            array(
                'image'         => br_get_value_from_array($watermark, array('image',0)),
                'width'         => br_get_value_from_array($watermark, array('width',0), 50),
                'height'        => br_get_value_from_array($watermark, array('height',0), 50),
                'left'          => br_get_value_from_array($watermark, array('left',0), 25),
                'top'           => br_get_value_from_array($watermark, array('top',0), 25),
                'ratio'         => br_get_value_from_array($watermark, array('ratio',0)),
                'image_data'    => $image_data
            )
        );
        return $image_content;
    }
    public function backup_image($image_path, $generation = 'create') {
        $file_name = basename($image_path);
        $path = str_replace( $file_name, '', $image_path );
        $pattern = '/(\.\w+?$)/i';
        $replacement = '_br_backup$1';
        $new_file_name = preg_replace( $pattern, $replacement, $file_name);
        $new_path = $path.$new_file_name;   
        if ( $generation == 'restore' && is_file( $new_path ) ) {
            rename( $new_path, $image_path );
        } elseif ( $generation == 'create' && is_file( $new_path ) ) {
            copy( $new_path, $image_path );
        } elseif ( $generation == 'create' && is_file( $image_path ) ) {
            copy( $image_path, $new_path );
        }
    }

    public function set_thumbnail_id($return, $object_id, $meta_key, $meta_value) {
        if( $meta_key == '_thumbnail_id' ) {
            update_post_meta($object_id, '_thumbnail_id_watermarked', '1');
            $meta_value = array($meta_value);
            $this->set_image_array_for_watermark($object_id, $meta_key, $meta_value);
        }
    }

    public function set_product_image_gallery($return, $object_id, $meta_key, $meta_value) {
        if( $meta_key == '_product_image_gallery' ) {
            update_post_meta($object_id, '_product_image_gallery_watermarked', '1');
            $meta_value = explode(',', $meta_value);
            $this->set_image_array_for_watermark($object_id, $meta_key, $meta_value);
        }
    }

    public function get_thumbnail_id($return, $object_id, $meta_key, $single) {
        if( $meta_key == '_thumbnail_id' ) {
            $watermarked = get_post_meta($object_id, '_thumbnail_id_watermarked', true);
            if( empty($watermarked) ) {
                update_post_meta($object_id, '_thumbnail_id_watermarked', '1');
                remove_filter('get_post_metadata', array($this, 'get_thumbnail_id'), 1, 4);
                $return = get_metadata('post', $object_id, $meta_key, $single);
                $meta_value = array($return);
                $this->set_image_array_for_watermark($object_id, $meta_key, $meta_value);
                add_filter('get_post_metadata', array($this, 'get_thumbnail_id'), 1, 4);
            }
        }
        return $return;
    }

    public function get_product_image_gallery($return, $object_id, $meta_key, $single) {
        if( $meta_key == '_product_image_gallery' ) {
            $watermarked = get_post_meta($object_id, '_product_image_gallery_watermarked', true);
            if( empty($watermarked) ) {
                update_post_meta($object_id, '_product_image_gallery_watermarked', '1');
                remove_filter('get_post_metadata', array($this, 'get_product_image_gallery'), 1, 4);
                $return = get_metadata('post', $object_id, $meta_key, $single);
                if( is_array($return) ) {
                    $meta_value = array();
                    foreach($return as $return_val) {
                        $meta_value = array_merge($meta_value, explode($return_val));
                    }
                } else {
                    $meta_value = explode(',', $return);
                }
                $this->set_image_array_for_watermark($object_id, $meta_key, $meta_value);
                add_filter('get_post_metadata', array($this, 'get_product_image_gallery'), 1, 4);
            }
        }
        return $return;
    }

    public function get_image_id_prop($return) {
        global $post;
        if( ! empty($post) ) {
            $watermarked = get_post_meta($post->ID, '_thumbnail_id_watermarked', true);
            if( empty($watermarked) ) {
                update_post_meta($post->ID, '_thumbnail_id_watermarked', '1');
                remove_filter('woocommerce_product_get_image_id', array($this, 'get_image_id_prop'), 500, 1);
                $meta_value = array($return);
                $this->set_image_array_for_watermark($post->ID, '', $meta_value);
                add_filter('woocommerce_product_get_image_id', array($this, 'get_image_id_prop'), 500, 1);
            }
        }
        return $return;
    }

    public function set_image_array_for_watermark($object_id, $meta_key, $meta_value) {
        $post_type = get_post_type($object_id);
        if( $post_type == 'product' || $post_type == 'product_variation' ) {
            if(is_array($meta_value) && count($meta_value) ) {
                foreach($meta_value as $attachment_id) {
                    if( ! empty($attachment_id) ) {
                        $br_watermark = get_post_meta($attachment_id, 'br_watermark', true);
                        if( empty($br_watermark) ) {
                            update_post_meta($attachment_id, 'br_watermark', '1');
                        }
                    }
                }
            }
        }
    }
    public function intermediate_image_sizes_advanced($sizes) {
        $types = $this->convert_image_types();
        foreach( $types as $type => $base_type ) {
            if( isset($sizes[$type]) ) {
                unset($sizes[$type]);
            }
        }
        return $sizes;
    }
    public function set_styles () {}
    public function admin_init() {
        parent::admin_init();
        wp_enqueue_script( 'berocket_image_watermark_admin', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), BeRocket_image_watermark_version );
        wp_register_style( 'berocket_image_watermark_admin_style', plugins_url( 'css/admin.css', __FILE__ ), "", BeRocket_image_watermark_version );
        wp_enqueue_style( 'berocket_image_watermark_admin_style' );
        if( ! empty($_GET['page']) && $_GET['page'] == $this->values[ 'option_page' ] ) {
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-resizable' );
            wp_register_style( 'jquery-ui-smoothness', plugins_url( 'css/jquery-ui.min.css', __FILE__ ), "", BeRocket_image_watermark_version );
            wp_enqueue_style('jquery-ui-smoothness');
        }
    }
    public function admin_settings( $tabs_info = array(), $data = array() ) {
        $image_lib = array(
            array('value' => '',        'text' => __('GD Library', 'product-watermark-for-woocommerce')),
        );
        if( class_exists('Imagick') ) {
            $image_lib[] = array('value' => 'imagick', 'text' => __('Image Magick (Imagick)', 'product-watermark-for-woocommerce'));
        }
        parent::admin_settings(
            array(
                'General' => array(
                    'icon' => 'cog',
                    'name' => __( 'General', "BeRocket_AJAX_domain" )
                ),
                'Advanced' => array(
                    'icon' => 'cogs',
                    'name' => __( 'Advanced', "BeRocket_AJAX_domain" )
                ),
                'Addons' => array(
                    'icon' => 'plus',
                    'name' => __( 'Addons', "BeRocket_AJAX_domain" )
                ),
                'License' => array(
                    'icon' => 'unlock-alt',
                    'name' => __( 'License', "BeRocket_AJAX_domain" ),
                    'link' => admin_url( 'admin.php?page=berocket_account' )
                ),
            ),
            array(
            'General' => array(
                'jpeg_quantity' => array(
                    "label"     => __('JPEG image quality', 'product-watermark-for-woocommerce'),
                    "type"      => "number",
                    "name"      => "jpeg_quantity",
                    "extra"     => "min='1' max='100'",
                    "value"     => '',
                ),
                'max_img_height' => array(
                    "label"     => __('Maximum image height', 'product-watermark-for-woocommerce'),
                    "type"      => "number",
                    "name"      => "max_img_height",
                    "extra"     => "min='1'",
                    "value"     => '',
                ),
                'max_img_width' => array(
                    "label"     => __('Maximum image width', 'product-watermark-for-woocommerce'),
                    "type"      => "number",
                    "name"      => "max_img_width",
                    "extra"     => "min='1'",
                    "value"     => '',
                ),
                'php_memory_limit' => array(
                    "label"     => __('PHP memory limit on your server', 'product-watermark-for-woocommerce'),
                    "type"      => "text",
                    "name"      => "php_memory_limit",
                    "value"     => '',
                ),
                'watermarks_shop_single_0' => array(
                    "label"     => "",
                    "section"   => 'watermarks',
                    'name_start'=> 'shop_single',
                    'name_end'  => 0,
                    'tr_class'  => 'br_wm_img_shop_single berocket_image_count_0'
                ),
                'generate' => array(
                    "label"     => "",
                    "section"   => 'generate'
                ),
            ),
            'Addons' => array(
                'addons' => array(
                    "label"     => "",
                    "section"   => 'addons'
                ),
            ),
            'Advanced' => array(
                'fix_gd' => array(
                    "label"     => __('Transparency fix', 'product-watermark-for-woocommerce'),
                    "label_for" => __('Provide less quality of image, but fixes some glitches with transparency', 'product-watermark-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => "fix_gd",
                    "value"     => '1',
                ),
                'image_lib' => array(
                    "label"    => __( 'Image Processing', "product-watermark-for-woocommerce" ),
                    "label_for"=> __( 'Image processing library that will be used to add watermarks. Library will be diplayed only if installed in PHP', "product-watermark-for-woocommerce" )
                    .'<br>'.__( 'Plugin support: GD Library, Image Magick (Imagick)', "product-watermark-for-woocommerce" )
                    .(class_exists('Imagick') ? '' : '<br><strong style="color:red;">'.__('Server do not have Image Magick library', 'product-watermark-for-woocommerce').'</strong>'),
                    "name"     => "image_lib",
                    "type"     => "selectbox",
                    "options"  => $image_lib,
                    "value"    => '',
                ),
            ),
        ) );
    }
    public function section_watermarks($data, $options) {
        $defaults = $this->defaults;
        $water_name = $data['name_start'];
        $i = $data['name_end'];
        ob_start();
        $height = max(15, intval(br_get_value_from_array($options, array($water_name, 'height', $i), 50)));
        $height = intval($height/5)*5;
        $width = max(15, intval(br_get_value_from_array($options, array($water_name, 'width', $i), 50)));
        $width = intval($width/5)*5;
        $top = min(100-$height, max(0, intval(br_get_value_from_array($options, array($water_name, 'top', $i)))));
        $left = min(100-$width, max(0, intval(br_get_value_from_array($options, array($water_name, 'left', $i)))));
        echo '<td colspan="2">';
        ?>
        <?php echo br_upload_image('br-image_watermark-options['.$water_name.'][image]['.$i.']', br_get_value_from_array($options, array($water_name, 'image', $i))); ?>
        <p>
            <label><input type="checkbox" name="br-image_watermark-options[<?php echo $water_name; ?>][ratio][<?php echo $i; ?>]" value="1"<?php if( ! empty($options[$water_name]['ratio'][$i]) ) echo ' checked'; ?>><?php _e('Save aspect ratio', 'product-watermark-for-woocommerce');?></label>
        </p>
        <table>
            <tr>
                <td>
                    <div class="br_watermark_parent">
                        <div class="br_watermark" data-id="<?php echo $water_name.'_'.$i; ?>" style="<?php echo 'width:', ($width * 2), 'px;height:', ($height * 2), 'px;top:', ($top * 2), 'px;left:', ($left * 2), 'px;'; ?>">
                        </div>
                    </div>
                </td>
                <td>
                    <p>
                        <label>Top: <span class="<?php echo $water_name.'_'.$i; ?>_top"><?php echo $top; ?></span> %</label>
                        <input class="<?php echo $water_name.'_'.$i; ?>_top_input" type="hidden" name="br-image_watermark-options[<?php echo $water_name; ?>][top][<?php echo $i; ?>]" value="<?php echo $top; ?>">
                    </p>
                    <p>
                        <label>Left: <span class="<?php echo $water_name.'_'.$i; ?>_left"><?php echo $left; ?></span> %</label>
                        <input class="<?php echo $water_name.'_'.$i; ?>_left_input" type="hidden" name="br-image_watermark-options[<?php echo $water_name; ?>][left][<?php echo $i; ?>]" value="<?php echo $left; ?>">
                    </p>
                    <p>
                        <label>Height: <span class="<?php echo $water_name.'_'.$i; ?>_height"><?php echo $height; ?></span> %</label>
                        <input class="<?php echo $water_name.'_'.$i; ?>_height_input" type="hidden" name="br-image_watermark-options[<?php echo $water_name; ?>][height][<?php echo $i; ?>]" value="<?php echo $height; ?>">
                    </p>
                    <p>
                        <label>Width: <span class="<?php echo $water_name.'_'.$i; ?>_width"><?php echo $width; ?></span> %</label>
                        <input class="<?php echo $water_name.'_'.$i; ?>_width_input" type="hidden" name="br-image_watermark-options[<?php echo $water_name; ?>][width][<?php echo $i; ?>]" value="<?php echo $width; ?>">
                    </p>
                </td>
            </tr>
        </table>
        <?php
        echo '</td>';
        return ob_get_clean();
    }
    public function section_generate($data, $options) {
        ob_start();
        echo '<td colspan="2">';
        include('templates/generate.php');
        echo '</td>';
        return ob_get_clean();
    }
    public function sanitize_option( $input ) {
        if( empty($input['php_memory_limit']) ) {
            $input['php_memory_limit'] = (function_exists('ini_get') ? ini_get('memory_limit') : '');
        }
        $input = parent::sanitize_option( $input );
        if( !empty($input['addons']) && is_array($input['addons']) ) {
            foreach($input['addons'] as $i => $addon) {
                if( empty($addon) ) {
                    unset($input['addons'][$i]);
                }
            }
        }
        return $input;
    }
}

new BeRocket_image_watermark;
