<?php

if (!defined('ABSPATH')) exit;

class gdmaq_core_plugin extends d4p_plugin_core {
	public $svg_icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NDAgNTEyIj48cGF0aCBmaWxsPSIjMDAwMDAwIiBkPSJNNTgwLjI1LDM3N0w1ODAuMjUsNDYzQzU4MC4yNSw0ODYuNzMyIDU2MC45ODIsNTA2IDUzNy4yNSw1MDZMMTg1LjI1LDUwNkMxNjEuNTE4LDUwNiAxNDIuMjUsNDg2LjczMiAxNDIuMjUsNDYzTDE0Mi4yNSwzNzdDMTQyLjI1LDM1My4yNjggMTYxLjUxOCwzMzQgMTg1LjI1LDMzNEw1MzcuMjUsMzM0QzU2MC45ODIsMzM0IDU4MC4yNSwzNTMuMjY4IDU4MC4yNSwzNzdaTTU1OC45MTcsMzc3QzU1OC45MTcsMzY1LjA0MiA1NDkuMjA4LDM1NS4zMzMgNTM3LjI1LDM1NS4zMzNMMTg1LjI1LDM1NS4zMzNDMTczLjI5MiwzNTUuMzMzIDE2My41ODMsMzY1LjA0MiAxNjMuNTgzLDM3N0wxNjMuNTgzLDQ2M0MxNjMuNTgzLDQ3NC45NTggMTczLjI5Miw0ODQuNjY3IDE4NS4yNSw0ODQuNjY3TDUzNy4yNSw0ODQuNjY3QzU0OS4yMDgsNDg0LjY2NyA1NTguOTE3LDQ3NC45NTggNTU4LjkxNyw0NjNMNTU4LjkxNywzNzdaTTMzNi4yNSw0MDBDMzQ3LjI4OCw0MDAgMzU2LjI1LDQwOC45NjIgMzU2LjI1LDQyMEMzNTYuMjUsNDMxLjAzOCAzNDcuMjg4LDQ0MCAzMzYuMjUsNDQwQzMyNS4yMTIsNDQwIDMxNi4yNSw0MzEuMDM4IDMxNi4yNSw0MjBDMzE2LjI1LDQwOC45NjIgMzI1LjIxMiw0MDAgMzM2LjI1LDQwMFpNNDc0LjI1LDQzMC42NjdMMzg1LjI1LDQzMC42NjdMMzg1LjI1LDQwOS4zMzNMNDc0LjI1LDQwOS4zMzNMNDc0LjI1LDM4OEw1MzguMjUsNDIwTDQ3NC4yNSw0NTJMNDc0LjI1LDQzMC42NjdaTTIwNi4yNSw0MDBDMjE3LjI4OCw0MDAgMjI2LjI1LDQwOC45NjIgMjI2LjI1LDQyMEMyMjYuMjUsNDMxLjAzOCAyMTcuMjg4LDQ0MCAyMDYuMjUsNDQwQzE5NS4yMTIsNDQwIDE4Ni4yNSw0MzEuMDM4IDE4Ni4yNSw0MjBDMTg2LjI1LDQwOC45NjIgMTk1LjIxMiw0MDAgMjA2LjI1LDQwMFpNMjcxLjI1LDQwMEMyODIuMjg4LDQwMCAyOTEuMjUsNDA4Ljk2MiAyOTEuMjUsNDIwQzI5MS4yNSw0MzEuMDM4IDI4Mi4yODgsNDQwIDI3MS4yNSw0NDBDMjYwLjIxMiw0NDAgMjUxLjI1LDQzMS4wMzggMjUxLjI1LDQyMEMyNTEuMjUsNDA4Ljk2MiAyNjAuMjEyLDQwMCAyNzEuMjUsNDAwWk0xMjMuMjU1LDQ2Mkw1OS43NSw0NjJMNTkuNzUsMTM3QzU5Ljc1LDEzNyAyMTQuMDgzLDYgMjkxLjI1LDZDMzY4LjQxNyw2IDUyMi43NSwxMzcgNTIyLjc1LDEzN0w1MjIuNzUsMzEyLjI3QzUyMS4yNzEsMzEyLjA5MSA1MTkuNzcsMzEyIDUxOC4yNSwzMTJMNTAxLjQxNywzMTJMNTAxLjQxNywxNDcuMDIxQzQ4MC45MjMsMTMwLjI1NyA0MjEuODY2LDgzLjQ3NCAzNjUuMjA1LDUzLjA5OEMzMzguMDExLDM4LjUyIDMxMS42OTYsMjcuMzMzIDI5MS4yNSwyNy4zMzNDMjcwLjgwNCwyNy4zMzMgMjQ0LjQ4OSwzOC41MiAyMTcuMjk1LDUzLjA5OEMxNjAuNjM0LDgzLjQ3NCAxMDEuNTc3LDEzMC4yNTcgODEuMDgzLDE0Ny4wMjFMODEuMDgzLDQ0MC42NjdMMTIzLjI1LDQ0MC42NjdMMTIzLjI1LDQ2MS4yNUMxMjMuMjUsNDYxLjUgMTIzLjI1Miw0NjEuNzUgMTIzLjI1NSw0NjJaTTEwOS4wOSwxOTIuNzA4TDEwMC4zODIsMTg2LjU0OEwxMTIuNzAyLDE2OS4xMzJMMTIxLjQxLDE3NS4yOTJDMTIxLjQxLDE3NS4yOTIgMTYzLjA0LDIwNC43NCAyMDguODM0LDIyOC4yOTlDMjM4LjEyNywyNDMuMzY5IDI2OS4wNTgsMjU2LjMzMyAyOTEuMjUsMjU2LjMzM0MzMTMuNDQyLDI1Ni4zMzMgMzQ0LjM3MywyNDMuMzY5IDM3My42NjYsMjI4LjI5OUM0MTkuNDYsMjA0Ljc0IDQ2MS4wOSwxNzUuMjkyIDQ2MS4wOSwxNzUuMjkyTDQ2OS43OTgsMTY5LjEzMkw0ODIuMTE4LDE4Ni41NDhMNDczLjQxLDE5Mi43MDhDNDczLjQxLDE5Mi43MDggNDE0Ljk4NSwyMzQuMDE3IDM1OS40ODksMjU4LjgxMUMzMzQuNTE1LDI2OS45NjkgMzEwLjAyOCwyNzcuNjY3IDI5MS4yNSwyNzcuNjY3QzI3Mi40NzIsMjc3LjY2NyAyNDcuOTg1LDI2OS45NjkgMjIzLjAxMSwyNTguODExQzE2Ny41MTUsMjM0LjAxNyAxMDkuMDksMTkyLjcwOCAxMDkuMDksMTkyLjcwOFoiLz48L3N2Zz4=';

    public $enqueue = true;
    public $cap = 'gd-mail-queue-standard';
    public $plugin = 'gd-mail-queue';

    private $engines = array();
    private $templates = array();

    /** @var d4p_datetime_core */
    public $datetime;

    public function __construct() {
        parent::__construct();

        if (!defined('GDMAQ_HTACCESS_FILE_NAME')) {
            define('GDMAQ_HTACCESS_FILE_NAME', '.htaccess');
        }

        $this->url = GDMAQ_URL;
        $this->datetime = new d4p_datetime_core();
    }

    public function plugins_loaded() {
        parent::plugins_loaded();

        define('GDMAQ_WPV', intval($this->wp_version));
        define('GDMAQ_WPV_MAJOR', substr($this->wp_version, 0, 3));
        define('GDMAQ_WP_VERSION', $this->wp_version_real);

        add_action('gdmaq_run_maintenance', array($this, 'maintenance'));

        do_action('gdmaq_load_settings');

	    if (version_compare( GDMAQ_WP_VERSION, gdmaq_settings()->info->wordpress, '<' )) {
		    if ( is_admin() ) {
			    add_action( 'admin_notices', array( $this, 'system_requirements_notices' ) );
		    } else {
			    $this->deactivate();
		    }
	    } else {
		    add_action('gdmaq_load_engine_phpmailer', array($this, 'engine_load_phpmailer'));
		    $this->register_engine('phpmailer', 'PHPMailer');

		    do_action('gdmaq_register_engines');

		    do_action('gdmaq_register_templates');

		    gdmaq_load_phpmailer();

		    gdmaq_external();
		    gdmaq_mailer();
		    gdmaq_queue();
		    gdmaq_htmlfy();
		    gdmaq_logger();

		    if (gdmaq_settings()->get('queue_pause', 'core')) {
			    add_filter('gdmaq_queue_paused', '__return_true');
		    }

		    if (gdmaq_settings()->get('email_pause', 'core')) {
			    add_filter('gdmaq_email_paused', '__return_true');
		    }

		    do_action('gdmaq_plugin_init');

		    if (!wp_next_scheduled('gdmaq_run_maintenance')) {
			    $cron_hour = apply_filters('gdmaq_cron_daily_maintenance_job_hour', 4);
			    $cron_time = mktime($cron_hour, 0, 0, date('m'), date('d') + 1, date('Y'));

			    wp_schedule_event($cron_time, 'daily', 'gdmaq_run_maintenance');
		    }
	    }
    }

	public function system_requirements_notices() {
		$render = '<div class="notice notice-error"><p>';
		$render .= esc_html__( "System requirements check for GD Mail Queue has failed. This plugin requires WordPress 5.5 or newer. The plugin will now be disabled.", "gd-mail-queue" );
		$render .= '</p></div>';

		echo $render;

		$this->deactivate();
	}

    public function maintenance() {
        if (gdmaq_settings()->get('queue_active', 'cleanup')) {
            $scope = gdmaq_settings()->get('queue_scope', 'cleanup') == 'sent' ? array('sent') : array('sent', 'failed');
            $days = absint(gdmaq_settings()->get('queue_days', 'cleanup'));
            $days = $days < 1 ? 30 : $days;

            gdmaq_db()->queue_cleanup(get_current_blog_id(), $scope, $days);
        }

        if (gdmaq_settings()->get('log_active', 'cleanup')) {
            $days = absint(gdmaq_settings()->get('log_days', 'cleanup'));
            $days = $days < 1 ? 365 : $days;

            gdmaq_db()->email_log_cleanup(get_current_blog_id(), $days);
        }
    }

    public function has_additional_templates() {
        return !empty($this->templates);
    }

    public function get_additional_templates_list() {
        return wp_list_pluck($this->templates, 'label');
    }

    public function get_additional_template_path($name) {
        return isset($this->templates[$name]) ? $this->templates[$name]['path'] : false;
    }

    public function register_engine($name, $label) {
        $this->engines[$name] = $label;
    }

    public function register_template($name, $label, $path) {
        if (file_exists($path)) {
            $this->templates[$name] = array(
                'name' => $name,
                'label' => $label,
                'path' => $path
            );
        }
    }

    public function engine_load_phpmailer() {
        if (!function_exists('gdmaq_engine_sender')) {
            require_once(GDMAQ_PATH.'core/mail/engine.phpmailer.php');
        }
    }

    public function get_list_of_engines() {
        return $this->engines;
    }

    public function get_engine_label($engine) {
        return isset($this->engines[$engine]) ? $this->engines[$engine] : __("Unknown", "gd-mail-queue").' ('.$engine.')';
    }

	public function deactivate() {
		deactivate_plugins('gd-mail-queue/gd-mail-queue.php', false, false);
	}
}
