<?php

/**
 * Plugin Name: Botão de Chat Grátis para Seu Site │ RD Station
 * Description: Transforme visitantes do seu site em contatos com o botão de chat para o app mais famoso do Brasil, criado pela RD Station. 
 * Version:           1.1.0
 * Requires at least: 1.0
 * Requires PHP:      5.2.4
 * Author:            RD Station 
 * Author URI:        https://rdstation.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('RD_BUTTON_CHAT_PLUGIN')) {
    class RD_BUTTON_CHAT_PLUGIN
    {
        function __construct()
        {
            $this->define_constants();

            require_once(RD_BUTTON_CHAT_PATH . 'class.rd-button-chat-settings.php');
            $rd_button_chat_Settings = new RD_BUTTON_CHAT_Settings();

            add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'), 999);
            add_action('admin_menu', array($this, 'add_menu'));
        }

        public function define_constants()
        {
            define('RD_BUTTON_CHAT_PATH', plugin_dir_path(__FILE__));
            define('RD_BUTTON_CHAT_URL', plugin_dir_url(__FILE__));
            define('RD_BUTTON_CHAT_VERSION', '1.0.0');
        }

        public static function activate()
        {
            update_option('rewrite_rules', '');
        }

        public static function deactivate()
        {
            flush_rewrite_rules();
            unregister_post_type('rd-button-chat');
        }

        public static function uninstall()
        {
            delete_option('rd_button_chat_options');
            delete_option('rd_button_chat_setup');
            delete_option('rd_button_chat_backup_options');
        }

        public function add_menu()
        {
            add_menu_page(
                esc_html__('Botão Chat │ RD', 'rd-button-chat'),
                'Botão Chat │ RD',
                'manage_options',
                'rd_button_chat_admin',
                array($this, 'rd_button_chat_settings_page'),
                'dashicons-whatsapp'
            );
        }

        public function rd_button_chat_settings_page()
        {
            if (!current_user_can('manage_options')) {
                return;
            }

            $options = get_option('rd_button_chat_options');
            $setup_options = get_option('rd_button_chat_setup_options');
            $setup_email = $setup_options['rd_button_chat_setup_email'];

            if (isset($options["rd_button_chat_show_button"])) {

                $backup_email = $options['rd_button_chat_email'];
                $backup_phone = $options['rd_button_chat_phone'];
                $backup_message = $options['rd_button_chat_message'];

                $backup_data = [
                    'rd_button_chat_email' => $backup_email,
                    'rd_button_chat_phone' => $backup_phone,
                    'rd_button_chat_message' => $backup_message,
                ];

                update_option('rd_button_chat_backup_options', $backup_data);
            }

            if (isset($_GET['settings-updated'])) {

                if (empty($setup_email)) {
                    add_settings_error('rd_button_chat_setup', 'rd_button_chat_toast', 'Insira um email de configuração válido :)', 'error');
                }

                if (!empty($setup_email)) {

                    $url = "https://mkt-tools-middleware.herokuapp.com/botao-whatsapp/$setup_email";

                    $response = wp_remote_get($url);
                    $response_data = json_decode($response['body']);

                    $data = [
                        'rd_button_chat_email' => $response_data->data[0]->email,
                        'rd_button_chat_phone' => $response_data->data[0]->phone,
                        'rd_button_chat_message' => $response_data->data[0]->message,
                    ];
                }

                add_settings_error('rd_button_chat_options', 'rd_button_chat_toast', 'Tudo certo! Suas alterações foram salvas :)', 'success');

                settings_errors('rd_button_chat_options');
                settings_errors('rd_button_chat_setup');

                if (!isset($options["rd_button_chat_show_button"])) {
                    update_option("rd_button_chat_options", $data);
                    echo "<script>location.reload();</script>";
                }
            }

            require(RD_BUTTON_CHAT_PATH . 'views/settings-page.php');
        }

        public function register_admin_scripts()
        {
            wp_enqueue_style('rd-button-chat-admin', RD_BUTTON_CHAT_URL . 'assets/css/style.css');
            wp_enqueue_script('rd-button-chat-admin-validation', RD_BUTTON_CHAT_URL . 'functions/validation.js', array(), false, true);
        }
    }
}

if (class_exists('RD_BUTTON_CHAT_PLUGIN')) {

    $rd_button_chat = new RD_BUTTON_CHAT_PLUGIN();
    register_activation_hook(__FILE__, array('RD_BUTTON_CHAT_PLUGIN', 'activate'));
    register_deactivation_hook(__FILE__, array('RD_BUTTON_CHAT_PLUGIN', 'deactivate'));
    register_uninstall_hook(__FILE__, array('RD_BUTTON_CHAT_PLUGIN', 'uninstall'));
}


function rd_button_chat_create_chat_button()
{
    $options = get_option('rd_button_chat_options');
    $email = isset($options['rd_button_chat_email']) ? $options['rd_button_chat_email'] : '';
    $phone = isset($options['rd_button_chat_phone']) ? $options['rd_button_chat_phone'] : '';
    $message = isset($options['rd_button_chat_message']) ? $options['rd_button_chat_message'] : '';

    $sanitize_phone = "55" . preg_replace('/[^0-9]/', '', $phone);

    echo "<script language='javascript' type='text/javascript'>        
        window.rwbp={
            email:'$email',
            phone:'$sanitize_phone',
            message:'$message',
            lang:'pt-BR'}</script>";

    if ($options['rd_button_chat_show_button'] == '1') {
        wp_enqueue_script('rd-button-chat-create-button', RD_BUTTON_CHAT_URL . 'vendor/whats.js', array(), false, true);
    }
}

function rd_button_chat_search_monitor_code()
{

    $has_code = 'false';
    echo "<script type='text/javascript'>
    
    jQuery(document).ready(function($) {

        const initialState = {$has_code}
        const head = document.head.outerHTML
        const checkCode = head.includes('d335luupugsy2') ? true : false

        if(checkCode != initialState){
            
            let data = {
                'check_code': checkCode 
            };
        
            jQuery.post('./wp-admin/admin-ajax.php?action=rd_button_chat_ajax', data, function(response) {
                
                const RDButtonChatPluginLink = document.getElementById('rd-button-chat-plugin')
                const RDButtonChatButton = Array.from(document.querySelectorAll('.svelte-19dp4zf'))[0]

                window.onload = () => {

                    if(RDButtonChatPluginLink && RDButtonChatButton) {
                        RDButtonChatPluginLink.remove()
                        RDButtonChatButton.remove()
                    }
                } 
            });
        }   
       
    });
    </script> ";
}


function rd_button_chat_ajax()
{
    wp_die();
}

function rd_button_chat_load_jquery()

{
    wp_enqueue_script('jquery');
}

add_action('wp_ajax_rd_button_chat_ajax', 'rd_button_chat_ajax');
add_action('wp_ajax_nopriv_rd_button_chat_ajax', 'rd_button_chat_ajax');
add_action('wp_body_open', 'rd_button_chat_create_chat_button', 10);
add_action('wp_body_open', 'rd_button_chat_search_monitor_code', 10);
add_action('wp_enqueue_scripts', 'rd_button_chat_load_jquery');