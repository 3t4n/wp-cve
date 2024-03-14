<?php
/**
 * Timeline Block.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if( !class_exists( 'Wpmagazine_Modules_Lite_Timeline' ) ) :
    class Wpmagazine_Modules_Lite_Timeline extends Wpmagazine_Modules_Lite_Block_Base {
        /**
         * Name of block
         * 
         * @access protected
         * @since 1.0.0
         * 
         */
        protected $block_name = 'timeline';

        /**
         * Instance
         *
         * @access private
         * @static
         */
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @access public
         * @static
         *
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Attributes
         * 
         * @return array
         */
        public function get_attributes() {
            $attrs = array(
                'contentType'   => array(
                    'type'      => 'string',
                    'default'   => 'post'
                ),
                'postCategory'   => array(
                    'type'      => 'string',
                    'default'   => ''
                ),
                'postCount' => array(
                    'type'      => 'integer',
                    'default'   => 4
                ),
                'timelineRepeater'    => array(
                    'type'  => 'array',
                    'default'   => array(
                        array(
                            'timeline_image'   => '',
                            'timeline_date'   => esc_html__( '', 'wp-magazine-modules-lite' ),
                            'timeline_title'   => esc_html__( 'Highlight News', 'wp-magazine-modules-lite' ),
                            'timeline_desc'   => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 'wp-magazine-modules-lite' )
                        )
                    ),
                    'items' => array(
                        'type'  => 'object'
                    )
                ),
                'thumbOption'  => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'dateOption'  => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'titleOption'  => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'contentOption'  => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'dateTextAlign'  => array(
                    'type'      => 'string',
                    'default'   => 'left'
                ),
                'dateFontFamily' => array(
                    'type'      => 'string',
                    'default'   => 'Yanone Kaffeesatz'
                ),
                'dateFontWeight' => array(
                    'type'      => 'string',
                    'default'   => '700'
                ),
                'dateFontSize'   => array(
                    'type'      => 'number',
                    'default'   => 28
                ),
                'dateFontStyle'  => array(
                    'type'      => 'string',
                    'default'   => 'normal'
                ),
                'dateTextTransform'  => array(
                    'type'      => 'string',
                    'default'   => 'capitalize'
                ),
                'dateTextDecoration' => array(
                    'type'      => 'string',
                    'default'   => 'none'
                ),
                'dateBackgroundColor'    => array(
                    'type'      => 'string',
                    'default'   => '#333333'
                ),
                'dateFontColor'  => array(
                    'type'      => 'string',
                    'default'   => '#333333'
                ),
                'dateHoverColor' => array(
                    'type'      => 'string',
                    'default'   => '#f47e00'
                ),
                'datelineHeight' => array(
                    'type'      => 'number',
                    'default'   => 1.5
                ),
                'titleTextAlign'  => array(
                    'type'      => 'string',
                    'default'   => 'left'
                ),
                'titleFontFamily' => array(
                    'type'      => 'string',
                    'default'   => 'Yanone Kaffeesatz'
                ),
                'titleFontWeight' => array(
                    'type'      => 'string',
                    'default'   => '700'
                ),
                'titleFontSize'   => array(
                    'type'      => 'number',
                    'default'   => 28
                ),
                'titleFontStyle'  => array(
                    'type'      => 'string',
                    'default'   => 'normal'
                ),
                'titleTextTransform'  => array(
                    'type'      => 'string',
                    'default'   => 'capitalize'
                ),
                'titleTextDecoration' => array(
                    'type'      => 'string',
                    'default'   => 'none'
                ),
                'titleFontColor'  => array(
                    'type'      => 'string',
                    'default'   => '#333333'
                ),
                'titleHoverColor' => array(
                    'type'      => 'string',
                    'default'   => '#f47e00'
                ),
                'titlelineHeight' => array(
                    'type'      => 'number',
                    'default'   => 1.5
                ),
                'descTextAlign'  => array(
                    'type'      => 'string',
                    'default'   => 'left'
                ),
                'descFontFamily' => array(
                    'type'      => 'string',
                    'default'   => 'Yanone Kaffeesatz'
                ),
                'descFontWeight' => array(
                    'type'      => 'string',
                    'default'   => '700'
                ),
                'descFontSize'   => array(
                    'type'      => 'number',
                    'default'   => 28
                ),
                'descFontStyle'  => array(
                    'type'      => 'string',
                    'default'   => 'normal'
                ),
                'descTextTransform'  => array(
                    'type'      => 'string',
                    'default'   => 'capitalize'
                ),
                'descTextDecoration' => array(
                    'type'      => 'string',
                    'default'   => 'none'
                ),
                'descFontColor'  => array(
                    'type'      => 'string',
                    'default'   => '#333333'
                ),
                'descHoverColor' => array(
                    'type'      => 'string',
                    'default'   => '#f47e00'
                ),
                'desclineHeight' => array(
                    'type'      => 'number',
                    'default'   => 1.5
                )
            );
            return apply_filters( 'wpmagazine_modules_lite_'.$this->block_name.'_attributes', $attrs );
        }

        /**
         * Render callback
         * renders the content of block in database.
         */
        public function render_callback( $attributes ) {
            ob_start();
                extract( $attributes );
            ?>
                <div id="wpmagazine-modules-lite-timeline-block-<?php echo esc_attr( $blockID ); ?>" class="wpmagazine-modules-lite-timeline-block align<?php echo esc_html( $align ); ?> block-<?php echo esc_attr( $blockID ); ?> cvmm-block cvmm-block-timeline--<?php echo esc_html( $blockLayout ); ?>">
                    <?php
                        if( !empty( $blockTitle ) ) {
                            echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
                        }

                    include( plugin_dir_path( __FILE__ ) . esc_html( $blockLayout ).'/'.$blockLayout.'.php' );
                ?>
                </div><!-- #wpmagazine-modules-lite-timeline-block -->
        <?php
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }
    Wpmagazine_Modules_Lite_Timeline::instance();
endif;