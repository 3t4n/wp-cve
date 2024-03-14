<?php
namespace UiCoreAnimate;


/**
 * Frontend Pages Handler
 */
class Frontend {

    private $style = '';

    /**
     * Constructor function to initialize hooks
     *
     * @return void
     */

    public function __construct() {
        //Handle animation style in UiCore Framework Global if is active
        if(!\class_exists('\UiCore\Helper')){
            $style = Settings::get_option( 'uianim_style' );
            if(is_array($style)){
                $this->style = $style['value'];
            }else{
                $this->style = null;
            }
            
            if( $this->style ){
                add_action('elementor/frontend/after_enqueue_scripts',function() {
                    wp_deregister_style('e-animations' );
                    wp_dequeue_style( 'e-animations' );
                }, 20 );

                add_action( 'wp_enqueue_scripts', [ $this, 'animation_style' ], 60 );
            }

            //scroll
            if( Settings::get_option('uianim_scroll')  == 'true' ){
                add_action( 'wp_enqueue_scripts', [ $this, 'scroll' ], 60 );
            }
        }else{
            //add the resources to global files in UiCore Framework
            add_filter('uicore_css_global_files', [$this, 'add_css_to_framework'], 10, 2);
            add_filter('uicore_js_global_files', [$this, 'add_js_to_framework'], 10, 2);
        }
        
    }

    /**
     * Enqueue animation style
     *
     */
    public function animation_style() {
        wp_dequeue_style( 'elementor-animations' );
        wp_enqueue_style( 'uianim-style', UICORE_ANIMATE_ASSETS . '/css/'.$this->style.'.css' );
    }

    /**
     * Enqueue scroll
     *
     */
    public function scroll() {
        wp_enqueue_script( 'uianim-scroll', UICORE_ANIMATE_ASSETS . '/js/scroll.js',  UICORE_ANIMATE_VERSION, true );
    }



    public function add_css_to_framework($files, $settings)
    {
        if($settings['performance_animations'] === 'true'){
            $style = $settings['uianim_style'];
            $style = isset($style['value']) ? $style['value'] : 'style1';
            $files[] = UICORE_ANIMATE_PATH . '/assets/css/'.$style.'.css';

            if($settings['performance_ugly_animations'] === 'false'){
                $files[] =  UICORE_ANIMATE_PATH . '/assets/css/global.css';
            }
        }

        return $files;
    }

    public function add_js_to_framework($files, $settings)
    {
        if($settings['performance_animations'] === 'true' && $settings['uianim_scroll'] == 'true'){
            $files[] =  UICORE_ANIMATE_PATH . '/assets/js/scroll.js';
        }

        return $files;
    }

}
