<?php

namespace Shop_Ready\extension\elewidgets\assets;

use Illuminate\Support\MessageBag as MangocubeMessageBag;

/*
 * Register Base js and css
 * @since 1.0 
 */

class Register extends Assets
{

    public $configs = null;

    public function register()
    {


        // // admin
        add_action('admin_enqueue_scripts', [$this, 'register_css']);
        add_action('admin_enqueue_scripts', [$this, 'register_js']);
        /*---------------------------------
          REGISTER FRONTEND SCRIPTS
      ----------------------------------*/
        if (function_exists('shop_ready_elewidget_assets_config')) {
            $this->configs = shop_ready_elewidget_assets_config();
        }

        add_action('elementor/frontend/after_register_scripts', [$this, 'register_public_js']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_public_css']);

    }
    /*
     * Register css and js
     */
    public function register_css()
    {



        $data = $this->configs;

        if (isset($data['css'])) {

            foreach ($data['css'] as $css) {

                if (file_exists($css['file']) && !$css['public']) {
                    $media = isset($css['media']) ? $css['media'] : 'all';

                    wp_register_style(str_replace(['_'], ['-'], $css['handle_name']), $css['src'], $css['deps'], filemtime($css['file']), $media, false);

                }

            }

        }

        unset($data);


    }
    /*
     * Register css and js
     * @since 1.0
     */
    public function register_public_css()
    {

        $data = $this->configs;

        if (isset($data['css'])) {

            foreach ($data['css'] as $css) {

                if (file_exists($css['file']) && $css['public']) {
                    $media = isset($css['media']) ? $css['media'] : 'all';

                    wp_register_style(str_replace(['_'], ['-'], $css['handle_name']), $css['src'], $css['deps'], filemtime($css['file']), $media, false);

                }

            }

        }

        unset($data);


    }

    /*
     * Register css and js
     */
    public function register_js()
    {

        $data = $this->configs;

        if (isset($data['js'])) {

            foreach ($data['js'] as $js) {

                if (file_exists($js['file']) && !$js['public']) {

                    wp_register_script(str_replace(['_'], ['-'], $js['handle_name']), $js['src'], $js['deps'], filemtime($js['file']), $js['in_footer']);

                }

            }

        }

        unset($data);

    }

    public function register_public_js()
    {

        $data = $this->configs;
        if (isset($data['js'])) {

            foreach ($data['js'] as $js) {

                if (file_exists($js['file']) && $js['public']) {

                    wp_register_script(str_replace(['_'], ['-'], $js['handle_name']), $js['src'], $js['deps'], filemtime($js['file']), $js['in_footer']);

                }

            }

        }

        unset($data);

    }

}