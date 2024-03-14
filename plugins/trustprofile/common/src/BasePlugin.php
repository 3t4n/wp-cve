<?php
namespace Valued\WordPress;

use ReflectionClass;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

abstract class BasePlugin {
    protected static $instances = [];

    public $admin;

    public $frontend;

    public $woocommerce;

    /** @return string */
    abstract public function getSlug();

    /** @return string */
    abstract public function getName();

    /** @return string */
    abstract public function getMainDomain();

    /** @return string */
    abstract public function getDashboardDomain();

    public static function getInstance() {
        if (!isset(self::$instances[static::class])) {
            self::$instances[static::class] = new static();
        }
        return self::$instances[static::class];
    }

    public function init() {
        register_activation_hook($this->getPluginFile(), [$this, 'activatePlugin']);
        add_action('plugins_loaded', [$this, 'loadTranslations']);
        add_action('admin_enqueue_scripts', [$this, 'addUpdateNoticeDismissScript']);
        add_action('wp_ajax_' . $this->getUpdateNoticeDismissedAjaxHook(), [$this, 'dismissUpdateNotice']);
        add_action('before_woocommerce_init', function() {
            if (class_exists(FeaturesUtil::class)) {
                FeaturesUtil::declare_compatibility('custom_order_tables', $this->getPluginFile());
            }
        });
        if ($this->shouldDisplayUpdateNotice()) {
            add_action('admin_notices', [$this, 'showUpdateNotice']);
        }
        if (is_admin()) {
            $this->admin = new Admin($this);
        } else {
            $this->frontend = new Frontend($this);
        }

        $this->woocommerce = new WooCommerce($this);
    }

    public function activatePlugin() {
        $this->dismissUpdateNotice();
        $this->createInvitesErrorTable();
    }

    public function createInvitesErrorTable() {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta('
            CREATE TABLE `' . $this->getInviteErrorsTable() . '` (
                `id` int NOT NULL AUTO_INCREMENT,
                `url` varchar(255) NOT NULL,
                `response` text NOT NULL,
                `time` bigint NOT NULL,
                `reported` boolean NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `time` (`time`),
                KEY `reported` (`reported`)
            )
        ');
    }

    public function loadTranslations() {
        load_plugin_textdomain(
            'webwinkelkeur',
            false,
            "{$this->getSlug()}/common/languages/"
        );
    }

    /**
     * @param string $name
     * @return string
     */
    public function getOptionName($name) {
        return "{$this->getSlug()}_{$name}";
    }

    /** @return string */
    public function getInviteErrorsTable() {
        return $GLOBALS['wpdb']->prefix . $this->getSlug() . '_invite_error';
    }

    /**
     * @param string $__template
     * @param array $__scope
     * @return string
     **/
    public function render($__template, array $__scope) {
        extract($__scope);
        ob_start();
        require $this->locateTemplate($__template);
        return ob_get_clean();
    }

    private function locateTemplate($template) {
        if (wp_using_themes() && $result = locate_template('webwinkelkeur/' . $template . '.php')) {
            return $result;
        }
        return __DIR__ . '/../templates/' . $template . '.php';
    }

    public function getPluginFile() {
        $reflect = new ReflectionClass($this);
        return dirname(dirname($reflect->getFilename())) . '/' . $this->getSlug() . '.php';
    }

    public function isWoocommerceActivated(): bool {
        return class_exists('woocommerce');
    }

    public function getActiveGtinPlugin() {
        $gtin_handler = new GtinHandler();
        return $gtin_handler->getActivePlugin();
    }

    public function showUpdateNotice() {
        $class = 'notice notice-info is-dismissible ' . $this->getUpdateNoticeClass();
        $message = $this->getUpdateMessage();
        if (!empty($message)) {
            printf('<div class="%s">%s</div>', esc_attr($class), $message);
        }
    }

    private function getUpdateMessage() {
        return $this->getUpdateNotices()[$this->getVersion()] ?? null;
    }

    protected function getUpdateNotices(): array {
        return [];
    }

    public function addUpdateNoticeDismissScript() {
        $js_file = plugin_dir_url(__FILE__) . 'admin/js/update-notice.js';
        $script_name = $this->getOptionName('notice_update');
        wp_register_script(
            $script_name,
            $js_file
        );
        wp_localize_script($script_name, 'notice_params', [
            'class' => $this->getUpdateNoticeClass(),
            'hook' => $this->getUpdateNoticeDismissedAjaxHook(),
        ]);
        wp_enqueue_script($script_name);
    }

    private function getUpdateNoticeClass(): string {
        return $this->getOptionName('custom_notice');
    }

    private function getUpdateNoticeDismissedAjaxHook(): string {
        return $this->getOptionName('notice_dismiss');
    }

    public function dismissUpdateNotice() {
        update_option(
            $this->getOptionName('last_notice_version'),
            $this->getVersion()
        );
    }

    private function shouldDisplayUpdateNotice(): bool {
        return version_compare(
            $this->getVersion(),
            $this->getOption($this->getOptionName('last_notice_version'), ''),
            '>'
        );
    }

    private function getVersion(): string {
        return '3.33';
    }

    private function getDefaultConfig(): array {
        return [
            'invite_delay' => 3,
            'javascript' => true,
            'order_statuses' => WooCommerce::DEFAULT_ORDER_STATUS,
            'product_reviews' => true,
            'invite' => WooCommerce::AFTER_EVERY_ORDER,
        ];
    }

    public function getOption($name, $default = null) {
        $value = get_option($this->getOptionName($name), null);
        if ($value !== null) {
            return $value;
        }
        $defaults = $this->getDefaultConfig();
        if (isset($defaults[$name])) {
            return $defaults[$name];
        }
        return $default;
    }
}
