<?php

if (!defined('ABSPATH')) exit;

class gdmaq_core_settings extends d4p_plugin_settings_corex {
    public $base = 'gdmaq';
    public $scope = 'blog';

    public $dk_list = array(
        'service_smtp' => array('username', 'password')
    );

    public $settings = array(
        'core' => array(
            'activated' => 0,
            'dashboard_errors' => '0000-00-00 00:00:00',
            'show_coupon_36' => true,
            'email_pause' => false,
            'queue_pause' => false
        ),
        'statistics' => array(
            'data' => array(),
            'types' => array(),
        ),
        'settings' => array(
            'intercept' => false,
            'htmlfy' => false,
            'plain_text_check' => 'tags', // tags, type //
            'fix_content_type' => false,
            'engine' => 'phpmailer',
            'q' => true,
            'queue' => 'ccbcc', // all, to, cc, bcc, ccbcc //
            'from' => false,
            'from_email' => '',
            'from_name' => '',
            'reply' => false,
            'reply_email' => '',
            'reply_name' => '',
            'cleanup_days' => 7
        ),
        'log' => array(
            'active' => false,
            'mail' => true,
            'mail_if_not_queue' => false,
            'queue' => true,
            'store_smtp_password' => false,
            'preview_html_disable_links' => false,
            'action_retry' => false
        ),
        'cleanup' => array(
            'queue_active' => true,
            'queue_scope' => 'sent',
            'queue_days' => 30,
            'log_active' => false,
            'log_days' => 365
        ),
        'queue' => array(
            'cron' => 5, // minutes
            'method' => 'classic', //classic, limited
            'timeout' => 20, // seconds
            'limit' => 50, // emails per job
            'limit_flex' => false,
            'sleep_batch' => 50000,
            'sleep_single' => 5000,
            'requeue' => true,
            'header' => true,
            'from' => false,
            'from_email' => '',
            'from_name' => '',
            'reply' => false,
            'reply_email' => '',
            'reply_name' => '',
            'sender' => false,
            'sender_email' => ''
        ),
        'htmlfy' => array(
	        'preprocess' => 'kses_post',
	        'replace' => 'template',
            'template' => 'clean-basic',
            'additional' => '',
            'preheader' => 'content', // subject, content, none
            'preheader_limit' => 65,
            'header' => '',
            'footer' => ''
        ),
        'external' => array(
            'buddypress_force_wp_mail' => false
        ),
        'engine_phpmailer' => array(
            'mode' => 'mail' // service_smtp
        ),
        'service_smtp' => array(
            'host' => '',
            'port' => '25',
            'encryption' => '',
            'auth' => true,
            'username' => '',
            'password' => ''
        )
    );

    protected function constructor() {
        $this->info = new gdmaq_core_info();

        add_action('gdmaq_load_settings', array($this, 'init'));
    }

    protected function _update() {
        if ($this->current['info']['build'] < 35) {
            $this->_upgrade_pre_35();
        }

        parent::_update();
    }

    protected function _db() {
        require_once(GDMAQ_PATH.'core/admin/install.php');

        gdmaq_install_database();
    }

    protected function _name($name) {
        return 'dev4press_'.$this->info->code.'_'.$name;
    }

    public function file_version() {
        return $this->info_version.'.'.$this->info_build;
    }

    public function plugin_version() {
        return 'v'.$this->info_version.'_b'.$this->info_build;
    }

    public function set($name, $value, $group = 'settings', $save = false) {
        if (isset($this->dk_list[$group]) && in_array($name, $this->dk_list[$group])) {
            $value = gdmaq_dk()->encrypt($value);
        }

        $this->current[$group][$name] = $value;

        if ($save) {
            $this->save($group);
        }
    }

    public function get($name, $group = 'settings') {
        $exit = null;

        if (isset($this->current[$group][$name])) {
            $exit = $this->current[$group][$name];
        } else if (isset($this->settings[$group][$name])) {
            $exit = $this->settings[$group][$name];
        }

        if (isset($this->dk_list[$group]) && in_array($name, $this->dk_list[$group])) {
            if (gdmaq_dk()->is_encrypted($exit)) {
                $exit = gdmaq_dk()->decrypt($exit);
            }
        }

        return apply_filters($this->base.'_'.$this->scope.'_settings_get', $exit, $name, $group);
    }

    public function export_to_json($list = array()) {
        if (empty($list)) {
            $list = $this->_groups();
        }

        $data = new stdClass();
        $data->info = $this->current['info'];

        foreach ($list as $name) {
            $data->$name = $this->current[$name];
        }

        return json_encode($data);
    }

    public function get_statistics($key, $default = 0) {
        if (is_object($this->current['statistics']['data'])) {
            $this->current['statistics']['data'] = (array)$this->current['statistics']['data'];
        }

        return isset($this->current['statistics']['data'][$key]) ? $this->current['statistics']['data'][$key] : $default;
    }

    public function update_statistics($key, $value, $reset = false, $save = false) {
        if (is_object($this->current['statistics']['data'])) {
            $this->current['statistics']['data'] = (array)$this->current['statistics']['data'];
        }

        if ($reset) {
            $this->current['statistics']['data'][$key] = $value;
        } else {
            if (!isset($this->current['statistics']['data'][$key])) {
                $this->current['statistics']['data'][$key] = 0;
            }

            $this->current['statistics']['data'][$key]+= $value;
        }

        if ($save) {
            $this->save('statistics');
        }
    }

    public function get_statistics_for_type($type, $key, $default = 0) {
        if (is_object($this->current['statistics']['types'])) {
            $this->current['statistics']['types'] = (array)$this->current['statistics']['data'];
        }

        return isset($this->current['statistics']['types'][$type][$key]) ? $this->current['statistics']['types'][$type][$key] : $default;
    }

    public function update_statistics_for_type($type, $key, $value, $reset = false, $save = false) {
        if (is_object($this->current['statistics']['types'])) {
            $this->current['statistics']['types'] = (array)$this->current['statistics']['data'];
        }

        if ($reset) {
            $this->current['statistics']['types'][$type][$key] = $value;
        } else {
            if (!isset($this->current['statistics']['types'][$type][$key])) {
                $this->current['statistics']['types'][$type][$key] = 0;
            }

            $this->current['statistics']['types'][$type][$key]+= $value;
        }

        if ($save) {
            $this->save('statistics');
        }
    }

    private function _upgrade_pre_35() {
        $phpmailer = $this->_settings_get('phpmailer');

        $smtp = $phpmailer['smtp'];
        $engine_phpmailer = $this->settings['engine_phpmailer'];

        if ($smtp != 'off') {
            $engine_phpmailer['mode'] = 'service_smtp';
        }

        update_option($this->_name('service_smtp'), $phpmailer);
        update_option($this->_name('engine_phpmailer'), $phpmailer);

        delete_option($this->_name('phpmailer'));
    }
}

class gdmaq_core_scope extends d4p_core_scope {
    public static function instance() {
        static $instance = null;

        if (null === $instance) {
            $instance = new gdmaq_core_scope();
        }

        return $instance;
    }
}

/** @return gdmaq_core_scope */
function gdmaq_scope() {
    return gdmaq_core_scope::instance();
}

class gdmaq_core_dk {
    protected $ck = 'gdmaq';
    protected $dk = 'def00000790f3a49caf36fa340c163c18a4469f242dbc948222537b14095e0dae629625dfd19dec033e1a328cd825c734b297e884aec78cf52df72d98b43c0169c124b37';

    public function __construct() {
        if (defined('GDMAQ_SETTINGS_DK')) {
            $this->dk = GDMAQ_SETTINGS_DK;
        }

        require_once(GDMAQ_PATH.'d4psec/autoload.php');
    }

    public static function instance() {
        static $instance = null;

        if (null === $instance) {
            $instance = new gdmaq_core_dk();
        }

        return $instance;
    }

    public function encrypt($string) {
        $key = Defuse\Crypto\Key::loadFromAsciiSafeString($this->dk);

        $crypt = $this->_key();
        $crypt.= Defuse\Crypto\Crypto::encrypt($string, $key);

        return $crypt;
    }

    public function decrypt($string) {
        if ($this->is_encrypted($string)) {
            $key = Defuse\Crypto\Key::loadFromAsciiSafeString($this->dk);

            try {
                $string = substr($string, strlen($this->_key()));
                $decrypt = Defuse\Crypto\Crypto::decrypt($string, $key);
            } catch (Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
                $decrypt = $string;
            }

            return $decrypt;
        } else {
            return $string;
        }
    }

    public function is_encrypted($string) {
        return strpos($string, $this->_key()) !== false;
    }

    protected function _key() {
        return '#'.$this->ck.'#DEF#';
    }
}

/** @return gdmaq_core_dk */
function gdmaq_dk() {
    return gdmaq_core_dk::instance();
}
