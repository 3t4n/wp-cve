<?php

declare(strict_types=1);

/**
 * The public-facing functionality of the plugin.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Your Name <email@example.com>
 */
class Mailup_Public
{
    /**
     * The ID of this plugin.
     *
     * @since  1.2.6
     *
     * @var string the ID of this plugin
     */
    private $mailup;

    /**
     * The version of this plugin.
     *
     * @since  1.2.6
     *
     * @var string the current version of this plugin
     */
    private $version;

    private $tag_name = 'mailup_form';

    private $model;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since 1.2.6
     *
     * @param string $mailup  the name of the plugin
     * @param string $version the version of this plugin
     */
    public function __construct($mailup, $version)
    {
        $this->mailup = $mailup;
        $this->version = $version;
        $this->load_dependencies();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.2.6
     */
    public function enqueue_styles(): void
    {
        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mailup_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mailup_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->mailup, plugin_dir_url(__FILE__).'css/mailup-public.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.2.6
     */
    public function enqueue_scripts(): void
    {
        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mailup_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mailup_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (!is_admin()) {
            $lang = Mailup_i18n::getLanguage();
            wp_enqueue_script($this->mailup.'_validate', plugin_dir_url(__DIR__).'admin/js/jquery.validate.min.js', ['jquery'], '1.19.5', false);

            if ($lang) {
                wp_enqueue_script(sprintf('%s_validate_loc_%s', $this->mailup, $lang), sprintf('%sadmin/js/localization/messages_%s.js', plugin_dir_url(__DIR__), $lang), [$this->mailup.'_validate'], '1.19.5', false);
            }
            wp_enqueue_script($this->mailup, plugin_dir_url(__FILE__).'js/mailup-public.js', ['jquery', $this->mailup.'_validate'], $this->version, false);

            wp_localize_script(
                $this->mailup,
                'mailup_params',
                [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'ajaxNonce' => wp_create_nonce('ajax-nonce'),
                ]
            );
        }
    }

    public function register_shortcodes(): void
    {
        if (!is_admin()) {
            add_shortcode($this->tag_name, [$this, 'create_form']);
        }
    }

    public function register_inline($style): void
    {
        wp_register_style('mupwp-inline-style', false, [$this->mailup]);
        wp_enqueue_style('mupwp-inline-style');
        wp_add_inline_style('mupwp-inline-style', $style);
    }

    public function create_form()
    {
        try {
            ob_start();
            $this->model = new Mailup_Model($this->mailup);
            $form = $this->model->get_fe_form();
            $this->register_inline($form->custom_css);

            if ($this->model->has_tokens() && $form) {
                include __DIR__.'/partials/mailup-public-display.php';
            }

            return ob_get_clean();
        } catch (\Exception $ex) {
            return null;
        }
    }

    public function mupwp_save_contact(): void
    {
        if (is_array($_POST['parameters'])) {
            $this->model = new Mailup_Model($this->mailup);
            $parameters = array_filter($_POST['parameters']);
            $this->model->add_recipient($parameters);
        }

        exit;
    }

    private function load_dependencies(): void
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        include_once plugin_dir_path(__DIR__).'includes/class-mailup-model.php';
    }
}
