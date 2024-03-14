<?php

namespace UiCoreAnimate;

use Elementor\Core\DocumentTypes\Page;

defined('ABSPATH') || exit();

/**
 * PageTransition Handler
 */
class PageTransition
{

    private $animation;
    private $body_selector = '#uicore-page';
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Constructor function to initialize hooks
     *
     * @return void
     */

    public function __construct()
    {

        $this->animation = Settings::get_option('animations_page');

        //continue only if the animations are enabled
        if ($this->animation == 'none') {
            return;
        }
       
        if (!\class_exists('\UiCore\Core')) {
            $this->body_selector = 'body';
            add_action('wp_head', [$this, 'add_page_transition_style'], 90);
        } else {
            add_filter('uicore_css_global_code', [$this, 'add_css_to_framework'], 10, 2);
        }

        if ($this->animation != 'fade in') {
            add_action('wp_body_open', [$this, 'add_page_transition_script'], 90);
        }
        if ($this->animation === 'reveal' || $this->animation === 'fade and reveal' ) {
            if(!\class_exists('\UiCore\Core')) {
                add_action('wp_body_open', function () {
                    echo '<div class="uicore-animation-bg"></div>';
                });
            }
        }
    }


    /**
     * create the page transition js script
     *
     * @return string with javascript for animations
     * @author Andrei Voica <andrei@uicore.co>w
     * @since 1.1.0
     */
    function add_page_transition_script()
    {
        $js = null;

        //Page Transition js
        $animation = str_replace(' ', '-', $this->animation);
        $animation_reversed = null;

        if ($animation === 'fade') {
            $animation_reversed = 'document.querySelector("'. $this->body_selector.'").style.animationDirection = "reverse";';
        } else if ($animation === 'reveal') {
            $animation_reversed = 'document.querySelector(".uicore-animation-bg").style.animationName = "uiCoreAnimationsRevealInversed";';
        } else if ($animation === 'fade-and-reveal') {
            $animation_reversed = '
			document.querySelector(".uicore-animation-bg").style.animationName = "uiCoreAnimationsFadeT";
			document.querySelector(".uicore-animation-bg").style.animationTimingFunction = "ease-in";
			document.querySelector(".uicore-animation-bg").style.animationDuration = "0.3s";

			';
        }

        if ($animation != 'none' && $animation != 'fade') {
            $js .= '
            document.querySelector(".uicore-animation-bg").style.animationPlayState="running";
            document.querySelector(".uicore-animation-bg").style.animationName = "";
            ';
        }
        if ($animation != 'none') {
            $js .= '
            window.onbeforeunload = function(e) {
                ' . $animation_reversed . '
                document.body.classList.remove("ui-a-pt-' . $animation . '");
                void document.querySelector("'. $this->body_selector.'").offsetWidth;
                document.body.pointerEvents = "none";
                document.body.classList.add("ui-a-pt-' . $animation . '");
            }
            ';
        }

        echo '<script id="uicore-page-transition">';
        echo "window.onload=window.onpageshow= function() { ";
        echo $js;
        echo ' }; ';
        echo '</script>';
    }

    function add_page_transition_style(){
        $css = $this->generate_css(null);
        echo '<style id="uicore-page-transition">' . $css . '</style>';
    }
    function add_css_to_framework($css, \UiCore\CSS $class)
    {
        $css .= $this->generate_css($class);
        return $css;
    }

    function generate_css($class)
    {
        //chck if $class is an instance of CSS
        if (($class instanceof \UiCore\CSS)) {
            $background = $class->color(Settings::get_option('animations_page_color'));
        }else{
            $background = self::get_color(Settings::get_option('animations_page_color'));
        }
        $css = null;
 
        $css .= '
            .uicore-animation-bg{
                background-color:' . $background . ';
            }
            ';
        if (Settings::get_option('animations_page') === 'fade') {
            $css .= $this->body_selector . '{
                    opacity: 0;
                    animation-name: uicoreFadeIn;
                    animation-fill-mode: forwards;
                    animation-timing-function: ease-in;
                ';
            if (Settings::get_option('animations_page_duration') === 'fast') {
                $css .= 'animation-duration: 0.15s;';
            } elseif (Settings::get_option('animations_page_duration') === 'slow') {
                $css .= 'animation-duration: 0.8s;';
            } else {
                $css .= 'animation-duration: 0.35s;';
            }
            $css .= '}';
        }
        if (Settings::get_option('animations_page') === 'fade in') {
            $css .= $this->body_selector . '{
                opacity: 0;
                animation-name: uicoreFadeIn;
                animation-fill-mode: forwards;
                animation-timing-function: ease-in;
            ';
            if (Settings::get_option('animations_page_duration') === 'fast') {
                $css .= 'animation-duration: 0.1s;';
            } elseif (Settings::get_option('animations_page_duration') === 'slow') {
                $css .= 'animation-duration: 0.6s;';
            } else {
                $css .= 'animation-duration: 0.2s;';
            }
            $css .= '}';
        }
        if (Settings::get_option('animations_page') === 'reveal') {
            $css .= '
            @keyframes uiCoreAnimationsReveal {
                0% {
                    transform: scaleX(1);
                }
            
                30% {
                    transform: scaleX(1);
                }
            
                100% {
                    transform: scaleX(0);
                }
            }
            @keyframes uiCoreAnimationsRevealInversed {
                0% {
                    transform: scaleX(0);
                    transform-origin: left center;
                }
            
                70% {
                    transform: scaleX(1);
                    transform-origin: left center;
                }
            
                100% {
                    transform: scaleX(1);
                    transform-origin: left center;
                }
            }

            .uicore-animation-bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                display: block;
                pointer-events: none;
                transform: scaleX(0);
                animation-fill-mode: forwards;
                transform-origin: right center;
                animation-name: uiCoreAnimationsReveal;
                animation-play-state: paused;
                z-index: 99999999999999999999;
                animation-timing-function: cubic-bezier(0.87, 0, 0.13, 1);
            ';
            if (Settings::get_option('animations_page_duration') === 'fast') {
                $css .= 'animation-duration: 0.4s;';
            } elseif (Settings::get_option('animations_page_duration') === 'slow') {
                $css .= 'animation-duration: 1.2s;';
            } else {
                $css .= 'animation-duration: 0.65s;';
            }
            $css .= '}';
        }
        if (Settings::get_option('animations_page') === 'fade and reveal') {
            $css .= '

            @keyframes uiCoreAnimationsRevealBottom {
                0% {
                    transform: scaleY(1);
                    transform-origin: center top;
                }

                30% {
                    transform: scaleY(1);
                    transform-origin: center top;
                }

                100% {
                    transform: scaleY(0);
                    transform-origin: center top;
                }
            }
            @keyframes uiCoreAnimationsFadeT {
                0% {
                    transform: scaleX(1);
                    opacity: 0;
                }
            
                100% {
                    transform: scaleX(1);
                    opacity: 1;
                }
            }

            .uicore-animation-bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                display: block;
                pointer-events: none;
                transform: scaleX(0);
                animation-fill-mode: forwards;
                transform-origin: right center;
                animation-timing-function: cubic-bezier(0.87, 0, 0.13, 1);
                animation-name: uiCoreAnimationsRevealBottom;
                animation-play-state: paused;
                z-index: 99999999999999999999;
            ';
            if (Settings::get_option('animations_page_duration') === 'fast') {
                $css .= 'animation-duration: 0.75s;';
            } elseif (Settings::get_option('animations_page_duration') === 'slow') {
                $css .= 'animation-duration: 1.2s;';
            } else {
                $css .= 'animation-duration: 0.9s;';
            }
            $css .= '}';
        }
        
        return $css;
    }

    static function get_color($color)
    {
        if(!is_string($color) && (isset($color['type']) || isset($color['blur']))){
            $color = $color['color'];
        }
        //check if color is in array x
        if(\in_array($color, ['Primary', 'Secondary', 'Accent', 'Headline', 'Body', 'Dark Neutral', 'Light Neutral', 'White'])){
            return '#306BFF'; //fallback color
        }
        return $color;
    }
}

PageTransition::init();