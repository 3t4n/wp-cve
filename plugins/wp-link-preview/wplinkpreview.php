<?php
/**
 * Plugin Name: WP Link Preview
 * Version: 1.4.1
 * Author: Kishan Gajera
 * Author URI: http://www.kgajera.com
 * Description: Turn a URL into a Facebook like link preview
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPLinkPreview {

    private $url = '';

    private $document;

    private $meta = array();

    function __construct() {
        if ( is_admin() ) {
            add_action( 'init', array( $this, 'init_tinymce' ) );
            add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ) );            
        }

        // Enqueue default styles for the link preview HTML
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Enable fetching link preview through shortcode
        add_shortcode( 'wplinkpreview', array( $this, 'shortcode' ) );        
    }

    /**
    * Make site url available in JavaScript
    */
    function admin_print_scripts() {
        echo "<script type='text/javascript'>\n";
        echo 'var siteurl = ' . wp_json_encode(  get_option( 'siteurl' ) ) . ';';
        echo "\n</script>";
    }

    /**
    * Register plugin front-end stylesheet
    */
    function enqueue_scripts() {
        wp_register_style( 'wplinkpreview-style', plugins_url( '/wplinkpreview.css', __FILE__ ), array(), '20120208', 'all' );
        wp_enqueue_style( 'wplinkpreview-style' );        
    }

    /**
    * AJAX action to fetch and output the link preview content
    */
    function fetch_wplinkpreview() {
        $url = $_GET['url'];
        $this->the_link_preview( $url );
        echo '<br />';
        wp_die();
    }

    /**
    * Initialization to add the TinyMCE plugin
    */
    function init_tinymce() {
        // Check if the logged in user can edit posts before registering the TinyMCE plugin
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }

        // Check if the logged in user has the Visual Editor enabled before registering the TinyMCE plugin
        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }

        // Register the TinyMCE plugin
        add_filter( 'mce_external_plugins', array( &$this, 'mce_external_plugins' ) );
        add_filter( 'mce_buttons', array( &$this, 'mce_buttons' ) );

        // Add action for the AJAX call that is made from the TinyMCE plugin to fetch link preview
        add_action( 'wp_ajax_fetch_wplinkpreview', array( &$this, 'fetch_wplinkpreview' ) );
    }

    /**
    * Return the description of the document. The following tags will be
    * used to find a description until one is found:
    *  1) open graph description meta tag
    *  2) description meta tag
    *
    * @return string A description for the document
    */
    function get_document_description() {
        $description = $this->meta['og:description'];
    
        if ( empty( $description ) ) {
            $description = $this->meta['description'];
        }

        return utf8_decode( $description );
    }

    /**
    * Return an image for the document. The following tags will be
    * used to find an image until one is found:
    *  1) open graph image meta tag
    *
    * @return string A image URL
    */
    function get_document_image() {
        return $this->meta['og:image'];
    }

    /**
    * Return the favicon for the document
    *
    * @return string The favicon image URL
    */
    function get_document_favicon() {
        $favicon = '';
        $links = $this->document->getElementsByTagName( 'link' );

        for( $i = 0; $i < $links->length; $i++ ) {
            $link = $links->item($i);

            if ( $link->getAttribute('rel') == 'icon' || $link->getAttribute('rel') == "Shortcut Icon" ||$link->getAttribute('rel') == "shortcut icon" ) {
                $href = $link->getAttribute('href');

                if ( filter_var( $href, FILTER_VALIDATE_URL ) !== false ) {
                    $favicon = $href;
                    break;
                }
            }
        }

        return $favicon;
    }

    /**
    * Return a title for the document. The following tags will be
    * used to find a title until one is found:
    *  1) open graph title meta tag
    *  2) the title tag
    *  3) the first h1 tag
    *
    * @return string A title
    */
    function get_document_title() {
        // 1) Use the open graph title
        $title = $this->meta['og:title'];

        // 2) Use the the document title tag
        if ( empty( $title ) ) {
            $nodes = $this->document->getElementsByTagName( 'title' );
            $title = $nodes->item(0)->nodeValue;
        }

        // 3) Use first h1 tag
        if ( empty( $title ) ) {
            $nodes = $this->document->getElementsByTagName( 'h1' );
            $title = $nodes->item(0)->nodeValue;
        }

        return utf8_decode( $title );
    }

    /**
    * Return the url of the document. The following tags will be
    * used to find the url until one is found:
    *  1) open graph url meta tag
    *  2) the url used to make the request
    *
    * @return string A url
    */
    function get_document_url() {
        // 1) Use the open graph url
        $og_url = $this->meta['og:url'];

        if ( ! empty( $og_url ) ) {
            return $og_url;
        }

        return $this->url;
    }

    /**
    * Adds a TinyMCE plugin compatible JS file to the TinyMCE / Visual Editor instance
    *
    * @param array $plugin_array Array of registered TinyMCE Plugins
    * @return array Modified array of registered TinyMCE Plugins
    */
    function mce_external_plugins( $plugin_array ) {
        $plugin_array['wplinkpreview_plugin'] = plugin_dir_url( __FILE__ ) . 'wplinkpreview.js';
        return $plugin_array;
    }

    /**
    * Adds a button to TinyMCE / Visual Editor which the user can click
    * to input a URL
    *
    * @param array $buttons Array of registered TinyMCE Buttons
    * @return array Modified array of registered TinyMCE Buttons
    */
    function mce_buttons( $buttons ) {
        array_push( $buttons, '|', 'wplinkpreview_plugin' );
        return $buttons;
    }

    /**
    * Parse all meta tags into a key value array
    *
    */
    function parse_document_meta() {
        $meta_tags = $this->document->getElementsByTagName( 'meta' );

        for ( $i = 0; $i < $meta_tags->length; $i++ ) {
            $meta = $meta_tags->item( $i );
            $name = $meta->getAttribute( 'name' );

            if ( empty( $name ) ) {
                $name = $meta->getAttribute( 'property' );
            }

            if ( ! empty( $name ) ) {
                $this->meta[$name] = $meta->getAttribute( 'content' );
            }
        }
    }

    /**
    * Make HTTP GET request for the inputted URL
    *
    */
    function setup_link_preview() {
        $this->document = null;
        $this->meta = array();

        if ( ! empty( $this->url ) ) {
            // Use WordPress HTTP API to make GET request for the given URL
            $response = wp_remote_get( $this->url, array(
                'timeout' => 120,
                'user-agent' => '' // Default value being blocked by Cloudflare
            ) );

            // Load and parse document
            if( is_array( $response ) ) {
                $this->document = new DOMDocument();
                @$this->document->loadHTML( wp_remote_retrieve_body( $response ) );
                $this->parse_document_meta();
            }
        }
    }

    function shortcode( $atts ) {
        extract(shortcode_atts(array(
            'url' => '',
         ), $atts));

        ob_start();
        $this->the_link_preview( $atts['url'] );
        return ob_get_clean();
    }

    function the_class( $suffix = '' ) {
        echo esc_attr( 'wplinkpreview' . ( empty( $suffix ) ? '' : '-' . $suffix ) );
    }

    function the_description() {
        $description = $this->get_document_description();

        if ( ! empty( $description ) ) {
            ?>
            <div class="<?php $this->the_class( 'description' ); ?>">
                <?php echo esc_html( $description ); ?>
            </div>
            <?php
        }
    }

    function the_image() {
        $image_url = $this->get_document_image();

        if ( ! empty( $image_url ) ) {
            ?>
            <div class="<?php $this->the_class( 'image' ); ?>">
                <a href="<?php echo esc_url ( $this->get_document_url() ); ?>" target="_blank">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $this->get_document_title() ); ?>" />
                </a>
            </div>
            <?php
        }
    }

    function the_link_preview( $url = "" ) {
        $url = esc_url( $url );

        if ( filter_var( $url, FILTER_VALIDATE_URL ) !== false ) {
            // Ensure we have a scheme
            $this->url = preg_replace( '/^(?!https?:\/\/)/', 'http://', $url );

            // Fetch and parse the document at the given URL
            $this->setup_link_preview();

            if ( ! empty( $this->document ) ) {
                ?>
                <div class="<?php $this->the_class(); ?>">
                    <?php 
                    $this->the_image();
                    $this->the_title();
                    $this->the_description();
                    $this->the_source();
                    ?>
                </div>
                <?php
            }
        }
    }

    function the_source() {
        $favicon = $this->get_document_favicon();
        $parsed_url = parse_url( $this->get_document_url() );
        ?>
        <div class="<?php $this->the_class( 'source' ); ?>">
            <?php if ( ! empty( $favicon ) ) { ?>
                <img src="<?php echo esc_url( $favicon ); ?>" width="16" height="16" />
            <?php } ?>
            <a href="<?php echo esc_url( $this->get_document_url() ); ?>" target="_blank">
                <?php echo esc_html( $parsed_url["host"] ); ?>
            </a>
        </div>
        <?php
    }

    function the_title() {
        $title = $this->get_document_title();

        if ( ! empty( $title ) ) {
            ?>
            <div class="<?php $this->the_class( 'title' ); ?>">
                <a href="<?php echo esc_url( $this->get_document_url() ); ?>" target="_blank">
                    <?php echo esc_html( $title ); ?>
                </a>
            </div>
            <?php
        }
    }

}

$wp_link_preview_class = new WPLinkPreview;
