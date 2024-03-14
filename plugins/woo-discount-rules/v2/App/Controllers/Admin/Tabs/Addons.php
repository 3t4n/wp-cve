<?php

namespace Wdr\App\Controllers\Admin\Tabs;

use Wdr\App\Controllers\Admin\Settings;
use Wdr\App\Controllers\Configuration;
use Wdr\App\Helpers\Helper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Addons extends Base
{
    public $priority = 80;
    protected $tab = 'addons';

    /**
     * GeneralSettings constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->title = __('Add-Ons', 'woo-discount-rules');
    }

    /**
     * Render settings page
     * @param null $page
     * @return mixed|void
     */
    public function render($page = NULL)
    {
        $active_addons = $available_addons = [];
        foreach (Settings::getAvailableAddons() as $slug => $addon) {
            $messages = [];
            $is_activatable = true;
            if (!empty($addon['requires_core']) && defined('WDR_VERSION')) {
                if (version_compare(WDR_VERSION, $addon['requires_core'], '<')) {
                    $messages[] = sprintf(__("Requires at least v%s of Core plugin", 'woo-discount-rules'), $addon['requires_core']);
                    $is_activatable = false;
                }
            }
            if (!empty($addon['requires_pro'])) {
                if (defined('WDR_PRO_VERSION')) {
                    if (version_compare(WDR_PRO_VERSION, $addon['requires_pro'], '<')) {
                        $messages[] = sprintf(__("Requires at least v%s of PRO plugin", 'woo-discount-rules'), $addon['requires_pro']);
                        $is_activatable = false;
                    }
                } else {
                    $messages[] = __("Requires PRO plugin", 'woo-discount-rules');
                    $is_activatable = false;
                }
            }

            $addon['message'] = implode('<br>', $messages);
            $addon['is_activatable'] = $is_activatable;
            if ($addon['is_active']) {
                $active_addons[$slug] = $addon;
            } else {
                $available_addons[$slug] = $addon;
            }
        }

        $addon_activated = sanitize_text_field($this->input->get('addon_activated', ''));
        $addon_deactivated = sanitize_text_field($this->input->get('addon_deactivated', ''));

        $params=array(
            'woocommerce' => self::$woocommerce_helper,
            'configuration' => new Configuration(),
            'is_pro' => Helper::hasPro(),
            'base' => $this,
            'active_addons' => $active_addons,
            'available_addons' => $available_addons,
            'addon_activated' => $addon_activated,
            'addon_deactivated' => $addon_deactivated,
        );
        self::$template_helper->setPath(WDR_PLUGIN_PATH . 'App/Views/Admin/Tabs/Addons.php')->setData($params)->display();
    }
}