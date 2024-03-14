<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Class WP_Flexslider_Editor
 */
if( !class_exists( 'WP_Flexslider_Editor' ) ):
    /**
     * Gallery Settings for Media Uploader
     */
    class WP_Flexslider_Editor {
        /**
         * Gallery types
         */
        public $gallery_types = array();
        /**
         * Constructor
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function __construct() {

            add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

        }
        /**
         * init
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function after_setup_theme(){

            if( !function_exists( 'is_plugin_active' ) ){
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            // Jetpack compat
            if ( class_exists( 'Jetpack') && Jetpack::is_module_active( 'tiled-gallery' ) ) {

                add_filter( 'jetpack_gallery_types', array( $this, 'compat_gallery_types' ), 15, 1  );

            // Long Live Gallery compat
            }elseif( is_plugin_active( 'wp-long-live-gallery/wp-long-live-gallery.php' ) ){
                
                add_filter( 'wp_long_live_gallery_media_gallery_setting_types', array( $this, 'compat_gallery_types' ), 15, 1  );                

            }else{
                add_action( 'admin_init', array( $this, 'admin_init' ) );

                // add_filter( 'jetpack_default_gallery_type', array( $this, 'set_default_type'), 20 );
                add_filter( 'wp_flexslider_default_gallery_type', array( $this, 'set_default_type'), 20 );
            }
        }
        /**
         * Jetpack Compatibility
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function compat_gallery_types( $types ){
            $types['flexslider'] = esc_html__( 'Flexslider', 'wp-flexslider' );
            return apply_filters( 'wp_flexslider_jetpack_gallery_types', $types );
        }

        /**
         * Admin init
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function admin_init() {
            
            $this->gallery_types = apply_filters( 'wp_flexslider_media_gallery_setting_types', 
                array( 
                    'default' => esc_html__( 'Default', 'wp-flexslider' ),
                    'flexslider' => esc_html__( 'Flexslider', 'wp-flexslider' )
                ) 
            );

            // Enqueue the media UI only if needed.
            if ( count( $this->gallery_types ) > 1 ) {
                //add_action( 'wp_enqueue_media', array( $this, 'wp_enqueue_media' ) );
                add_action( 'admin_print_footer_scripts', array( $this, 'footer_scripts'));
                add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
            }
        }
        /**
         * Registers/enqueues the gallery settings admin js.
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function footer_scripts() {
            if( !is_admin() )
                return;

            if ( ! wp_script_is( 'jetpack-gallery-settings', 'enqueued' ) ){
                //media-views
                ?>
                <script type="text/javascript">
                (function($) {
                    if( wp.media === undefined || wp.media === null ){
                        
                    }else{
                        var media = wp.media;

                        // Wrap the render() function to append controls.
                        media.view.Settings.Gallery = media.view.Settings.Gallery.extend({
                            render: function() {
                                var $el = this.$el;

                                media.view.Settings.prototype.render.apply( this, arguments );

                                // Append the type template and update the settings.
                                $el.append( media.template( 'wp-flexslider-gallery-settings' ) );
                                media.gallery.defaults.type = 'default'; // lil hack that lets media know there's a type attribute.

                                this.update.apply( this, ['type'] );

                                return this;
                            }
                        });
                    }
                })(jQuery);
                </script>
                <?php
            }
        }
        /**
         * Outputs a view template which can be used with wp.media.template
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function print_media_templates() {
            if( !is_admin() )
                return;

            $default_gallery_type = apply_filters( 'wp_flexslider_default_gallery_type', 'default' );
?><script type="text/html" id="tmpl-wp-flexslider-gallery-settings">
    <label class="setting">
        <span><?php _e( 'Type', 'wp-flexslider' ); ?></span>
        <select class="type" name="type" data-setting="type">
            <?php foreach ( $this->gallery_types as $value => $caption ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $default_gallery_type ); ?>><?php echo esc_html( $caption ); ?></option>
            <?php endforeach; ?>
        </select>
    </label>
</script><?php
        }
        /**
         * Set default value when creating gallery
         *
         * @return    void
         *
         * @access    public
         * @since     1.0.4
         */
        public function set_default_type( $type ){
            
            if( wp_flexslider_get_option('set_default') ){
                $type = 'flexslider';
            }
            return $type;
        }
    }
endif;

// Kickstart it
$GLOBALS['wp_flexslider_editor'] = new WP_Flexslider_Editor;