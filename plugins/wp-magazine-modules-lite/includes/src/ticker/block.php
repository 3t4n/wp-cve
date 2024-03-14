<?php
/**
 * Ticker Block.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if( !class_exists( 'Wpmagazine_Modules_Lite_Ticker' ) ) :
    class Wpmagazine_Modules_Lite_Ticker extends Wpmagazine_Modules_Lite_Block_Base {
        /**
         * Name of block
         * 
         * @access protected
         * @since 1.0.0
         * 
         */
        protected $block_name = 'ticker';

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
                'tickerCaption' => array(
                    'type'  => 'string',
                    'default' => esc_html__( 'Highlights', 'wp-magazine-modules-lite' )
                ),
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
                    'default'   => 10
                ),
                'tickerRepeater'    => array(
                    'type'  => 'array',
                    'default'   => array(
                        array( 
                            'ticker_image'   => '',
                            'ticker_title'   => esc_html__( 'Highlight News', 'wp-magazine-modules-lite' )
                        )
                    ),
                    'items' => array(
                        'type'  => 'object'
                    )
                ),
                'marqueeDirection'  => array(
                    'type'  => 'string',
                    'default'   => 'left'
                ),
                'marqueeDuration'   => array(
                    'type'  => 'integer',
                    'default'   => 80000
                ),
                'marqueeStart'  => array(
                    'type'  => 'integer',
                    'default'   => 1000
                ),
                'captionTextAlign'  => array(
                    'type'      => 'string',
                    'default'   => 'left'
                ),
                'captionFontFamily' => array(
                    'type'      => 'string',
                    'default'   => 'Yanone Kaffeesatz'
                ),
                'captionFontWeight' => array(
                    'type'      => 'string',
                    'default'   => '700'
                ),
                'captionFontSize'   => array(
                    'type'      => 'number',
                    'default'   => 28
                ),
                'captionFontStyle'  => array(
                    'type'      => 'string',
                    'default'   => 'normal'
                ),
                'captionTextTransform'  => array(
                    'type'      => 'string',
                    'default'   => 'capitalize'
                ),
                'captionTextDecoration' => array(
                    'type'      => 'string',
                    'default'   => 'none'
                ),
                'captionBackgroundColor'    => array(
                    'type'      => 'string',
                    'default'   => '#333333'
                ),
                'captionFontColor'  => array(
                    'type'      => 'string',
                    'default'   => '#333333'
                ),
                'captionHoverColor' => array(
                    'type'      => 'string',
                    'default'   => '#f47e00'
                ),
                'captionlineHeight' => array(
                    'type'      => 'number',
                    'default'   => 1.5
                ),
                'contentTextAlign'  => array(
                    'type'      => 'string',
                    'default'   => 'left'
                ),
                'contentFontFamily' => array(
                    'type'      => 'string',
                    'default'   => 'Yanone Kaffeesatz'
                ),
                'contentFontWeight' => array(
                    'type'      => 'string',
                    'default'   => '700'
                ),
                'contentFontSize'   => array(
                    'type'      => 'number',
                    'default'   => 28
                ),
                'contentFontStyle'  => array(
                    'type'      => 'string',
                    'default'   => 'normal'
                ),
                'contentTextTransform'  => array(
                    'type'      => 'string',
                    'default'   => 'capitalize'
                ),
                'contentTextDecoration' => array(
                    'type'      => 'string',
                    'default'   => 'none'
                ),
                'contentFontColor'  => array(
                    'type'      => 'string',
                    'default'   => '#333333'
                ),
                'contentHoverColor' => array(
                    'type'      => 'string',
                    'default'   => '#f47e00'
                ),
                'contentlineHeight' => array(
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
                <div id="wpmagazine-modules-lite-ticker-block-<?php echo esc_attr( $blockID ); ?>" class="wpmagazine-modules-lite-ticker-block align<?php echo esc_html( $align ); ?> block-<?php echo esc_attr( $blockID ); ?> cvmm-block cvmm-block-ticker--<?php echo esc_html( $blockLayout ); ?>">
                    <?php
                        if( !empty( $blockTitle ) ) {
                            echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
                        }

                    include( plugin_dir_path( __FILE__ ) . esc_html( $blockLayout ).'/'.$blockLayout.'.php' );
                ?>
                </div><!-- #wpmagazine-modules-lite-ticker-block -->
        <?php
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }
    Wpmagazine_Modules_Lite_Ticker::instance();
endif;