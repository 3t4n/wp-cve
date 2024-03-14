<?php
namespace YTP\Base;
class EnqueueAssets {

    public function register(){
        add_action('enqueue_block_assets', [$this, 'enqueue_block_assets']);
    }

    function enqueue_block_assets(){
        wp_register_script('plyrio', YTP_PLUGIN_DIR.'public/js/plyr-v3.7.3.js', [], YTP_PLUGIN_VERSION);
        wp_register_style('plyrio', YTP_PLUGIN_DIR.'public/css/plyr-v3.7.3.css', [], YTP_PLUGIN_VERSION);
        
        wp_register_style('ytp-blocks', YTP_PLUGIN_DIR.'dist/blocks.css', ['plyrio'], YTP_PLUGIN_VERSION);

        wp_register_style('ytp-public', YTP_PLUGIN_DIR.'dist/public.css', ['plyrio'], YTP_PLUGIN_VERSION);
        wp_register_script('ytp-public', YTP_PLUGIN_DIR.'dist/public.js', ['plyrio', 'react', 'react-dom'], YTP_PLUGIN_VERSION);
    }

    public function wpFooter(){
        ?>
        <style>
            :root {
                --plyr-color-main: <?php echo esc_attr(Utils::getOptionDeep('ytp_option','brandColor', '#00affa')) ?>
            }
        </style>
        <?php
    }

    

    public function pluginsLoaded(){
        $imported_version = get_option('ytp_import_ver', 0);
        if($imported_version < '1.0.0'){
            Import::meta();
            update_option('ytp_import_ver', '1.0.0');
        }
    }

}