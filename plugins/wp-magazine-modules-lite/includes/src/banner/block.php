<?php
/**
 * Banner Block.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if( !class_exists( 'Wpmagazine_Modules_Lite_Banner' ) ) :
    class Wpmagazine_Modules_Lite_Banner extends Wpmagazine_Modules_Lite_Block_Base {
        /**
         * Name of block
         * 
         * @access protected
         * @since 1.0.0
         * 
         */
        protected $block_name = 'banner';

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
                    'default'   => 'page'
                ),
                'bannerPage'    => array(
                    'type'      => 'string',
                    'default'   => ''
                ),
                'bannerImage'   => array(
                    'type'      => 'string',
                    'default'   => ''
                ),
                'titleOption'   => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'bannerTitle'   => array(
                    'type'      => 'string',
                    'default'   => esc_html__( 'WP Magazine Modules Lite', 'wp-magazine-modules-lite' )
                ),
                'bannerTitleLink'   => array(
                    'type'      => 'string',
                    'default'   => '#'
                ),
                'descOption'    => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'bannerDesc'    => array(
                    'type'      => 'string',
                    'default'   => esc_html__( 'Complete Magazine Plugin', 'wp-magazine-modules-lite' )
                ),
                'button1Option' => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'button1Label'  => array(
                    'type'      => 'string',
                    'default'   => esc_html__( 'Button One', 'wp-magazine-modules-lite' )
                ),
                'button1Link'   => array(
                    'type'      => 'string',
                    'default'   => '#'
                ),
                'button2Option' => array(
                    'type'      => 'boolean',
                    'default'   => true
                ),
                'button2Label'=> array(
                    'type'      => 'string',
                    'default'   => esc_html__( 'Button Two', 'wp-magazine-modules-lite' )
                ),
                'button2Link'   => array(
                    'type'      => 'string',
                    'default'   => '#'
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
                'button1TextAlign' => array(
                    'type' => 'string',
                    'default' => 'left'
                ),
                'button1FontFamily' => array(
                    'type' => 'string',
                    'default' => 'Roboto'
                ),
                'button1FontWeight' => array(
                    'type' => 'string',
                    'default' => '400'
                ),
                'button1FontSize' => array(
                    'type' => 'number',
                    'default' => 15
                ),
                'button1TextTransform' => array(
                    'type' => 'string',
                    'default' => 'none'
                ),
                'button1FontColor' => array(
                    'type' => 'string',
                    'default' => '#3b3b3b'
                ),
                'button1HoverColor' => array(
                    'type' => 'string',
                    'default' => '#3b3b3b'
                ),
                'button1BackgroundColor' => array(
                    'type' => 'string',
                    'default' => 'transparent'
                ),
                'button1BackgroundHoverColor' => array(
                    'type' => 'string',
                    'default' => '#f47e00'
                ),
                'button1PaddingTop' => array(
                    'type' => 'string',
                    'default' => '2'
                ),
                'button1PaddingRight' => array(
                    'type' => 'string',
                    'default' => '10'
                ),
                'button1PaddingBottom' => array(
                    'type' => 'string',
                    'default' => '2'
                ),
                'button1PaddingLeft' => array(
                    'type' => 'string',
                    'default' => '10'
                ),
                'button1BorderType' => array(
                    'type' => 'string',
                    'default' => 'solid'
                ),
                'button1BorderWeight' => array(
                    'type' => 'string',
                    'default' => '1'
                ),
                'button1BorderColor' => array(
                    'type' => 'string',
                    'default' => 'transparent'
                ),
                'button1BorderHoverColor' => array(
                    'type' => 'string',
                    'default' => '#f47e00'
                ),
                'button2TextAlign' => array(
                    'type' => 'string',
                    'default' => 'left'
                ),
                'button2FontFamily' => array(
                    'type' => 'string',
                    'default' => 'Roboto'
                ),
                'button2FontWeight' => array(
                    'type' => 'string',
                    'default' => '400'
                ),
                'button2FontSize' => array(
                    'type' => 'number',
                    'default' => 15
                ),
                'button2TextTransform' => array(
                    'type' => 'string',
                    'default' => 'none'
                ),
                'button2FontColor' => array(
                    'type' => 'string',
                    'default' => '#3b3b3b'
                ),
                'button2HoverColor' => array(
                    'type' => 'string',
                    'default' => '#3b3b3b'
                ),
                'button2BackgroundColor' => array(
                    'type' => 'string',
                    'default' => 'transparent'
                ),
                'button2BackgroundHoverColor' => array(
                    'type' => 'string',
                    'default' => '#f47e00'
                ),
                'button2PaddingTop' => array(
                    'type' => 'string',
                    'default' => '2'
                ),
                'button2PaddingRight' => array(
                    'type' => 'string',
                    'default' => '10'
                ),
                'button2PaddingBottom' => array(
                    'type' => 'string',
                    'default' => '2'
                ),
                'button2PaddingLeft' => array(
                    'type' => 'string',
                    'default' => '10'
                ),
                'button2BorderType' => array(
                    'type' => 'string',
                    'default' => 'solid'
                ),
                'button2BorderWeight' => array(
                    'type' => 'string',
                    'default' => '1'
                ),
                'button2BorderColor' => array(
                    'type' => 'string',
                    'default' => 'transparent'
                ),
                'button2BorderHoverColor' => array(
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
                <div id="wpmagazine-modules-lite-banner-block-<?php echo esc_attr( $blockID ); ?>" class="wpmagazine-modules-lite-banner-block align<?php echo esc_html( $align ); ?> block-<?php echo esc_attr( $blockID ); ?> cvmm-block cvmm-block-banner--<?php echo esc_html( $blockLayout ); ?>">
                    <?php
                        if( !empty( $blockTitle ) ) {
                            echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
                        }

                        switch( $contentType ) {
                            case 'page': $page_query = new WP_Query(
                                                            array(
                                                                'post_status'   => 'publish',
                                                                'post_type'     => 'page',
                                                                'name'          => esc_html( $bannerPage )
                                                            ));
                                            if( $page_query->have_posts() ) :
                                                while( $page_query->have_posts() ) : $page_query->the_post();
                                                    $title = get_the_title();
                                                    $bannerTitleLink = get_the_permalink();
                                                    $description = get_the_content();
                                                    $imageUrl = get_the_post_thumbnail_url();
                                                endwhile;
                                            endif;
                                        break;
                            default:    $title = esc_html( $bannerTitle );
                                        $bannerTitleLink = esc_url( $bannerTitleLink );
                                        $description = esc_html( $bannerDesc );
                                        $imageUrl = esc_url( $bannerImage );
                                    break;
                        }

                    include( plugin_dir_path( __FILE__ ) . esc_html( $blockLayout ).'/'.$blockLayout.'.php' );
                ?>
                </div><!-- #wpmagazine-modules-lite-banner-block -->
        <?php
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }
    Wpmagazine_Modules_Lite_Banner::instance();
endif;