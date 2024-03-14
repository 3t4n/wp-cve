<?php

namespace WPSocialReviews\App\Hooks\Handlers;

use WPSocialReviews\App\Services\Helper;
use WPSocialReviews\Framework\Foundation\App;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Services\Helper as GlobalHelper;
use WPSocialReviews\App\Services\Platforms\Chats\Helper as chatHelper;

class ChatHandler
{
    public function chatRegister()
    {
        //not show chat in the oxygen builder editor
        if(isset($_GET['ct_builder']) && $_GET['ct_builder']){
            return;
        }

        if(isset($_GET['bricks']) && $_GET['bricks'] === 'run'){
            return;
        }

        add_action('template_redirect', array($this, 'maybeHasChats'), 99);
    }

    public function maybeHasChats()
    {
        global $wpdb;
        $charset = $wpdb->get_col_charset( $wpdb->posts, 'post_content' );

        $args = array(
            'post_type'   => 'wpsr_social_chats',
            'post_status' => 'publish',
            'orderby'          => 'menu_order',
            'order'            => 'DESC',
            'numberposts' => -1
        );

        $chat_templates = get_posts($args);

        if (!$chat_templates) {
            return;
        }

        foreach ($chat_templates as $template) {
            $templateMeta = get_post_meta($template->ID, '_wpsr_template_config', true);
            $config = 'utf8mb3' === $charset ? json_decode($templateMeta, true) : maybe_unserialize($templateMeta);

            if (empty($config)) {
                continue;
            }

            if(is_array($config) && !Arr::has($config, 'chat_settings.settings.start_chat_time')){
                $config = $this->availableTimeUpdate($config);
                update_post_meta($template->ID, '_wpsr_template_config', $config);
            }

            // validate if the current template matched or not
            if (is_array($config) && !Arr::get($config, 'chat_settings.channels')) {
                return false;
            }

            $settings = Arr::get($config, 'chat_settings.settings', []);
            $isValid = GlobalHelper::isTemplateMatched($settings);

            //not show chat in the elementor builder editor
            if (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->preview->is_preview_mode()) {
                $isValid = false;
            }

            //not show chat in the beaver builder editor
            if(class_exists( 'FLBuilderModel' ) && \FLBuilderModel::is_builder_active()){
                $isValid = false;
            }

            if ($isValid) {
                add_action('wp_footer', function () use ($template, $config) {
                    $this->renderHTML($config['chat_settings'], $template->ID);
                });
                add_action('wp_enqueue_scripts', array($this, 'addAssets'));
                return;
            }
        }
    }

    public function renderHTML($config, $template_id)
    {
        $app = App::getInstance();
        $templateConfigs = Arr::get($config, 'template', 'template1');
        $templateConfigs = $config[$templateConfigs];

        $html = '';
        $html .= $app->view->make('public.chat-templates.template1', array(
            'settings'         => $config,
            'templateSettings' => $templateConfigs,
            'template_id'      => $template_id
        ));

        Helper::printInternalString($html);
    }


    /**
     *  Enqueue All Front-End Assets
     *
     * @param
     */
    public function addAssets()
    {
        wp_enqueue_style(
            'wpsocialreviews_chat',
            WPSOCIALREVIEWS_URL . 'assets/css/social-review-chat.css',
            array(),
            WPSOCIALREVIEWS_VERSION
        );
        wp_enqueue_script('wpsocialreviews_chat', WPSOCIALREVIEWS_URL . 'assets/js/chat.js', array('jquery'), WPSOCIALREVIEWS_VERSION, true);
    }

    public function availableTimeUpdate($config)
    {
        $startTime = Arr::get($config, 'chat_settings.settings.start_time', '');
        $endTime = Arr::get($config, 'chat_settings.settings.end_time', '');
        
        $config['chat_settings']['settings']['start_chat_time'] = chatHelper::formatedLocalTimeToUTCTime($startTime);
        $config['chat_settings']['settings']['end_chat_time'] = chatHelper::formatedLocalTimeToUTCTime($endTime);
            
        return $config;
    }
}