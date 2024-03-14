<?php
/*
  Plugin Name: Add External Media
  Plugin URI: http://wordpress.org/plugins/add-external-media/
  Description: Add external media to the media library
  Version: 1.0.5
  Author: leemon
  Text Domain: add-external-media
  License: GPLv2 or later

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class AddExternalMedia {  

    /**
     * Plugin instance.
     *
     * @since 1.0
     *
     */
    protected static $instance = null;


    /**
     * Access this plugin’s working instance
     *
     * @since 1.0
     *
     */
    public static function get_instance() {
        
        if ( !self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;

    }

    
    /**
     * Used for regular plugin work.
     *
     * @since 1.0
     *
     */
    public function plugin_setup() {

        $this->includes();

        add_action( 'init', array( $this, 'load_language' ) );
        add_action( 'wp_enqueue_media', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'wp_ajax_add-oembed', array( $this, 'add_oembed' ) );
        add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
        add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit' ), null, 2 );
        add_filter( 'attachment_fields_to_save', array( $this, 'attachment_fields_to_save' ), null, 2 );
        add_filter( 'media_view_strings', array( $this, 'media_view_strings' ), 10, 2 );
        add_filter( 'wp_prepare_attachment_for_js', array( $this, 'wp_prepare_attachment_for_js' ), 10, 3 );
    
    }

    
    /**
     * Constructor. Intentionally left empty and public.
     *
     * @since 1.0
     *
     */
    public function __construct() {}
  
    
    /**
     * Includes required core files used in admin and on the frontend.
     *
     * @since 1.0
     *
     */
    protected function includes() {
        if ( version_compare( $GLOBALS['wp_version'], '5.3-alpha', '<' ) ) {
            require_once ABSPATH . WPINC . '/class-oembed.php';
        } else {
            require_once ABSPATH . WPINC . '/class-wp-oembed.php';
        }
        
    }


    /**
     * Loads language
     *
     * @since 1.0
     *
     */
    function load_language() {
        load_plugin_textdomain( 'add-external-media' );
    }

    
    /**
     * Enqueues scripts in the backend.
     *
     * @since 1.0
     *
     */
    function admin_enqueue_scripts() {  
        wp_enqueue_script( 'add-external-media', plugins_url( '/add-external-media.js', __FILE__), array( 'media-models', 'media-views' ), false, true );
    }

    
    /**
     * Adds an oEmbed attachment to the database.
     *
     * @since 1.0
     *
     */
    function add_oembed() {
        $url = trim( $_POST['url'] );
        $width = absint( $_POST['width'] );
        $height = absint( $_POST['height'] );
        $post_ID = intval( $_POST['post_id'] );
        $nonce = $_POST['nonce'];
        
        if ( !wp_verify_nonce( $nonce, 'update-post_' . $post_ID ) ) {
            wp_send_json_error();
        }
    
        if ( !current_user_can( 'edit_post', $post_ID ) ) {
            wp_send_json_error();
        }

        $oembed = new WP_oEmbed();
        $provider = $oembed->get_provider( $url );
        if ( $provider === false && substr( $url, 0, 5 ) === 'https' ) {
            $url = str_replace( 'https', 'http', $url );
            $provider = $oembed->get_provider( $url );
        }
        if ( $provider === false ) {
            wp_send_json_error();
        }

        $response = $oembed->fetch( $provider, $url );
        if ( $response === false ) {
            wp_send_json_error();
        }

        $attachment = array(
            'post_parent'    => $post_ID,
            'post_title'     => $response->title,
            'post_content'   => '',
            'post_status'    => 'inherit',
            'post_author'    => get_current_user_id(),
            'post_type'      => 'attachment',
            'guid'           => $url,
            'post_mime_type' => 'oembed/' . strtolower( $response->provider_name )
        );
        $attachment_id = wp_insert_post( $attachment );
        if ( ! is_int( $attachment_id ) ) {
            wp_send_json_error();
        }

        $width = ( !empty( $width ) ? $width : 400 );
        $height = ( !empty( $height ) ? $height : 400 );
        
        update_post_meta( $attachment_id, '_oembed_width', $width );
        update_post_meta( $attachment_id, '_oembed_height', $height );

        if ( ! $attachment_js = wp_prepare_attachment_for_js( $attachment_id ) ) {
            wp_send_json_error();
        }

        wp_send_json_success( $attachment_js );
    
    }

    
    /**
     * Adds the width and height fields to the attachment details panel.
     *
     * @since 1.0
     *
     */
    function attachment_fields_to_edit( $form_fields, $post ) {
        $mime = get_post_mime_type( $post->ID );
        $type = strtok( $mime, '/' );
        if ( $type == 'oembed' ) {
            $form_fields['width'] = array(
                'label' => __( 'Width' ),
                'input' => 'text',
                'value' => get_post_meta( $post->ID, '_oembed_width', true )
            );
            $form_fields['height'] = array(
                'label' => __( 'Height' ),
                'input' => 'text',
                'value' => get_post_meta( $post->ID, '_oembed_height', true )
            );
        }
        return $form_fields;
    }

    
    /**
     * Save the width and height field values to the database.
     *
     * @since 1.0
     *
     */
    function attachment_fields_to_save( $post, $attachment ) {
        if ( isset( $attachment['width'] ) ) {
            update_post_meta( $post['ID'], '_oembed_width', $attachment['width'] );
        }
        if ( isset( $attachment['height'] ) ) {
            update_post_meta( $post['ID'], '_oembed_height', $attachment['height'] );
        }
        return $post;
    }
    

    /**
     * Prints the attachment details media view template.
     *
     * @since 1.0
     *
     */
    function print_media_templates() {
    ?>
        <script type="text/html" id="tmpl-add-external-settings">
            <div class="embed-container" style="display: none;">
                <div class="embed-preview"></div>
            </div>
            <label class="setting width">
                <span><?php _e( 'Width' ); ?></span>
                <input type="text" class="alignment" data-setting="width" />
            </label>
            <label class="setting height">
                <span><?php _e( 'Height' ); ?></span>
                <input type="text" class="alignment" data-setting="height" />
            </label>
        </script>
    <?php  
    }

    
    /**
     * Defines the menu item and button strings.
     *
     * @since 1.0
     *
     */
    function media_view_strings( $strings, $post ) {
        $strings['AddExternalMediaMenuTitle'] = _x( 'Add External Media', 'menu item', 'add-external-media' );
        $strings['AddExternalMediaButton'] = __( 'Add to library', 'add-external-media' );
        return $strings;
    }

    
    /**
     * Sets the filename of an oembed library item to its title.
     *
     * @since 1.0
     *
     */
    function wp_prepare_attachment_for_js( $response, $attachment, $meta ) {
        if ( $response['type'] == 'oembed' ) {
            if ( $response['title'] != '' ) {
                $response['filename'] = mb_strimwidth( $response['title'], 0, 25 );
            }
        }
        return $response;
    }
    
}

add_action( 'plugins_loaded', array ( AddExternalMedia::get_instance(), 'plugin_setup' ) );
