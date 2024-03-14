<?php
/**
 * Post List Block.
 * 
 * @package WP Magazine Modules
 * @since 1.0.0
 * 
 */
if( !class_exists( 'Wpmagazine_Modules_Lite_Post_List' ) ) :
    class Wpmagazine_Modules_Lite_Post_List extends Wpmagazine_Modules_Lite_Block_Base {
        /**
         * Name of block
         * 
         * @access protected
         * @since 1.0.0
         * 
         */
        protected $block_name = 'post-list';

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
                'posttype' => array(
                    'type' => 'string',
                    'default' => 'post'
                ),
                'postCategory' => array(
                    'type' => 'array',
                    'default' => [],
                    'items'   => [
                        'type' => 'integer',
                    ],
                ),
                'socialShareOption' => array(
                    'type'  => 'boolean',
                    'default' => true
                ),
                'socialShareLayout' => array(
                    'type'  => 'string',
                    'default' => 'default'
                ),
                'postCount' => array(
                    'type' => 'integer',
                    'default' => 6
                ),
                'contentOption' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'contentType' => array(
                    'type' => 'string',
                    'default' => 'excerpt'
                ),
                'wordCount' => array(
                    'type' => 'integer',
                    'default' => 15
                ),
                'buttonOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'buttonLabel' => array(
                    'type' => 'string',
                    'default' => esc_html__( 'Read more', 'wp-magazine-modules-lite' )
                ),
                'orderBy' => array(
                    'type' => 'string',
                    'default' => 'date'
                ),
                'order' => array(
                    'type' => 'string',
                    'default' => 'desc'
                ),
                'thumbOption' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'titleOption' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'dateOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'authorOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'categoryOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'categoriesCount' => array(
                    'type' => 'integer',
                    'default' => 2
                ),
                'tagsOption' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'tagsCount' => array(
                    'type' => 'integer',
                    'default' => 2
                ),
                'commentOption' =>  array(
                    'type' => 'boolean',
                    'default' => false
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
                    'default' => false
                ),
                'postButtonIcon' => array(
                    'type' => 'boolean',
                    'default' => false
                ),
                'blockColumn' => array(
                    'type' => 'string',
                    'default' => 'one'
                ),
                'postMargin' => array(
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
                <div id="wpmagazine-modules-lite-post-list-block-<?php echo esc_attr( $blockID ); ?>" class="wpmagazine-modules-lite-post-list-block align<?php echo esc_html( $align ); ?> block-<?php echo esc_attr( $blockID ); ?> cvmm-block cvmm-block-post-list--<?php echo esc_html( $blockLayout ); ?>">
                    <?php
                        if( !empty( $blockTitle ) ) {
                            echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
                        }
                    include( plugin_dir_path( __FILE__ ) . esc_html( $blockLayout ).'/'.$blockLayout.'.php' );
                ?>
                </div><!-- #wpmagazine-modules-post-list-block -->
        <?php
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }
    Wpmagazine_Modules_Lite_Post_List::instance();
endif;