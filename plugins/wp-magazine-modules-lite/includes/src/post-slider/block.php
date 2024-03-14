<?php
/**
 * Post Slider Block.
 * 
 * @package WP Magazine Modules
 * @since 1.0.0
 * 
 */
if( !class_exists( 'Wpmagazine_Modules_Lite_Post_Slider' ) ) :
    class Wpmagazine_Modules_Lite_Post_Slider extends Wpmagazine_Modules_Lite_Block_Base {
        /**
         * Name of block
         * 
         * @access protected
         * @since 1.0.0
         * 
         */
        protected $block_name = 'post-slider';

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
                'socialShareOption' => array(
                    'type'  => 'boolean',
                    'default' => true
                ),
                'socialShareLayout' => array(
                    'type'  => 'string',
                    'default' => 'default'
                ),
                'sliderposttype' => array(
                    'type' => 'string',
                    'default' => 'post'
                ),
                'sliderpostCategory' => array(
                    'type' => 'array',
                    'default' => [],
                    'items'   => [
                        'type' => 'integer',
                    ],
                ),
                'sliderpostCount' => array(
                    'type' => 'integer',
                    'default' => 10
                ),
                'slidercontentOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'slidercontentType' => array(
                    'type' => 'string',
                    'default' => 'excerpt'
                ),
                'sliderwordCount' => array(
                    'type' => 'integer',
                    'default' => 10
                ),
                'sliderbuttonOption' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'sliderbuttonLabel' => array(
                    'type' => 'string',
                    'default' => esc_html__( 'Read more', 'wp-magazine-modules-lite' )
                ),
                'sliderorderBy' => array(
                    'type' => 'string',
                    'default' => 'date'
                ),
                'sliderorder' => array(
                    'type' => 'string',
                    'default' => 'desc'
                ),
                'sliderdateOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'sliderauthorOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'slidercategoryOption' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'slidercategoriesCount' => array(
                    'type' => 'integer',
                    'default' => 2
                ),
                'slidertagsOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'slidertagsCount' => array(
                    'type' => 'integer',
                    'default' => 2
                ),
                'slidercommentOption' =>  array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'carouselType'  => array(
                    'type'      => 'boolean',
                    'default'   => false
                ),
                'carouselAuto'  => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'carouselDots'  => array(
                    'type'      => 'boolean',
                    'default'   => false
                ),
                'carouselControls'=> array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'carouselLoop'  => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'carouselSpeed'  => array(
                    'type'      => 'string',
                    'default'   => '2500'
                ),
                'carouselAutoplaySpeed'  => array(
                    'type'      => 'string',
                    'default'   => '4500'
                ),
                'fallbackImage'=> array(
                    'type' => 'string',
                ),
                'postFormatIcon' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'postMetaIcon' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'postButtonIcon' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'imageSize' => array(
                    'type'  => 'string',
                    'default'   => 'full'
                ),
                'titleTextAlign' => array(
                    'type' => 'string',
                    'default' => 'left'
                ),
                'titleFontFamily' => array(
                    'type' => 'string',
                    'default' => 'Yanone Kaffeesatz'
                ),
                'titleFontWeight' => array(
                    'type' => 'string',
                    'default' => '700'
                ),
                'titleFontSize' => array(
                    'type' => 'number',
                    'default' => '28'
                ),
                'titleFontStyle' => array(
                    'type' => 'string',
                    'default' => 'normal'
                ),
                'titleTextTransform' => array(
                    'type' => 'string',
                    'default' => 'capitalize'
                ),
                'titleTextDecoration' => array(
                    'type' => 'string',
                    'default' => 'none'
                ),
                'titleFontColor' => array(
                    'type' => 'string',
                    'default' => '#333333'
                ),
                'titleHoverColor' => array(
                    'type' => 'string',
                    'default' => '#f47e00'
                ),
                'titlelineHeight' => array(
                    'type' => 'number',
                    'default' => '1.5'
                ),
                'metaTextAlign' => array(
                    'type' => 'string',
                    'default' => 'left'
                ),
                'metaFontFamily' => array(
                    'type' => 'string',
                    'default' => 'Roboto'
                ),
                'metaFontWeight' => array(
                    'type' => 'string',
                    'default' => '400'
                ),
                'metaFontSize' => array(
                    'type' => 'number',
                    'default' => 14
                ),
                'metaFontStyle' => array(
                    'type' => 'string',
                    'default' => 'normal'
                ),
                'metaTextTransform' => array(
                    'type' => 'string',
                    'default' => 'capitalize'
                ),
                'metaTextDecoration' => array(
                    'type' => 'string',
                    'default' => 'none'
                ),
                'metaFontColor' => array(
                    'type' => 'string',
                    'default' => '#434343'
                ),
                'metaHoverColor' => array(
                    'type' => 'string',
                    'default' => '#f47e00'
                ),
                'metalineHeight' => array(
                    'type' => 'number',
                    'default' => 1.8
                ),
                'descTextAlign' => array(
                    'type' => 'string',
                    'default' => 'left'
                ),
                'descFontFamily' => array(
                    'type' => 'string',
                    'default' => 'Roboto'
                ),
                'descFontWeight' => array(
                    'type' => 'string',
                    'default' => '400'
                ),
                'descFontSize' => array(
                    'type' => 'number',
                    'default' => 15
                ),
                'descFontStyle' => array(
                    'type' => 'string',
                    'default' => 'normal'
                ),
                'descTextTransform' => array(
                    'type' => 'string',
                    'default' => 'none'
                ),
                'descTextDecoration' => array(
                    'type' => 'string',
                    'default' => 'none'
                ),
                'descFontColor' => array(
                    'type' => 'string',
                    'default' => '#3b3b3b'
                ),
                'desclineHeight' => array(
                    'type' => 'number',
                    'default' => 2
                ),
                'buttonTextAlign' => array(
                    'type' => 'string',
                    'default' => 'left'
                ),
                'buttonFontFamily' => array(
                    'type' => 'string',
                    'default' => 'Roboto'
                ),
                'buttonFontWeight' => array(
                    'type' => 'string',
                    'default' => '400'
                ),
                'buttonFontSize' => array(
                    'type' => 'number',
                    'default' => 15
                ),
                'buttonTextTransform' => array(
                    'type' => 'string',
                    'default' => 'none'
                ),
                'buttonFontColor' => array(
                    'type' => 'string',
                    'default' => '#3b3b3b'
                ),
                'buttonHoverColor' => array(
                    'type' => 'string',
                    'default' => '#3b3b3b'
                ),
                'buttonBackgroundColor' => array(
                    'type' => 'string',
                    'default' => 'transparent'
                ),
                'buttonBackgroundHoverColor' => array(
                    'type' => 'string',
                    'default' => '#f47e00'
                ),
                'buttonPaddingTop' => array(
                    'type' => 'string',
                    'default' => '2'
                ),
                'buttonPaddingRight' => array(
                    'type' => 'string',
                    'default' => '10'
                ),
                'buttonPaddingBottom' => array(
                    'type' => 'string',
                    'default' => '2'
                ),
                'buttonPaddingLeft' => array(
                    'type' => 'string',
                    'default' => '10'
                ),
                'buttonBorderType' => array(
                    'type' => 'string',
                    'default' => 'solid'
                ),
                'buttonBorderWeight' => array(
                    'type' => 'string',
                    'default' => '1'
                ),
                'buttonBorderColor' => array(
                    'type' => 'string',
                    'default' => 'transparent'
                ),
                'buttonBorderHoverColor' => array(
                    'type' => 'string',
                    'default' => '#f47e00'
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
                <div id="wpmagazine-modules-lite-post-slider-block-<?php echo esc_attr( $blockID ); ?>" class="wpmagazine-modules-lite-post-slider-block align<?php echo esc_html( $align ); ?> block-<?php echo esc_attr( $blockID ); ?> cvmm-block cvmm-block-post-slider--<?php echo esc_html( $blockLayout ); ?>">
                    <?php
                        if( !empty( $blockTitle ) ) {
                            echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
                        }

                    include( plugin_dir_path( __FILE__ ) . esc_html( $blockLayout ).'/'.$blockLayout.'.php' );
                ?>
                </div><!-- #wpmagazine-modules-banner-block -->
        <?php
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }
    Wpmagazine_Modules_Lite_Post_Slider::instance();
endif;