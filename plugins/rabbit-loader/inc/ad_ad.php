<?php

//Activate-Deactivate functions
class RabbitLoader_AD_AD{

    public static function on_plugins_loaded(){
        global $pagenow;
        if (strcmp($pagenow,'plugins.php')==0) {
            RabbitLoader_21_Core::getWpUserOption($user_options);
            add_action('admin_enqueue_scripts', function() {
                $kb_link = "https://rabbitloader.com/kb/new-errors-with-rabbit-loader/#turn_on_me_mode";
                wp_enqueue_script('rabbitloader-index', RABBITLOADER_PLUG_URL . 'admin/js/index.js', ['jquery'], RABBITLOADER_PLUG_VERSION);

                wp_localize_script('rabbitloader-index', 'rl_de_popup', [
                    'btn_de' => RL21UtilWP::__('Continue Deactivate'),
                    'btn_en' => RL21UtilWP::__('Enable Me mode'),
                    'pop_msg' => RL21UtilWP::__(sprintf('By turning on the <a href="%s" target="_blank">"Me" mode <i class="dashicons-before dashicons-external"></i></a>, only you can see the optimized version of the website. Do you still want to deactivate?', $kb_link)),
                    'private_mode_val'=> empty($user_options['private_mode_val']) ? "0" : "1"
                ]);
            });
        }
    }
}
