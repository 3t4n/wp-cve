<?php

namespace WPPayForm\App\Services;

use WPPayForm\Framework\Support\Arr;
use WPPayForm\Framework\Foundation\App;

if (!defined('ABSPATH')) {
    exit;
}

class HoneyPot
{
    private $app;

    public function __construct($application)
    {
        $this->app = $application;
    }

    public function renderHoneyPot($form)
    {
        if (!$this->isEnabled($form->ID)) {
            return;
        }
        ?>
        <span style="display: none !important;"><input type="checkbox" name="<?php echo esc_attr($this->getFieldName($form->ID)); ?>" value="1"
                                                       style="display: none !important;" tabindex="-1"></span>
        <?php

    }

    public function verify($form_data, $formId)
    {
        if (!$this->isEnabled($formId)) {
            return;
        }

        // Now verify
        if (Arr::get($form_data, $this->getFieldName($formId))) {
            // It's a bot! Block him
            wp_send_json(
                array(
                    'errors' => 'Sorry! You can not submit this form at this moment!'
                ), 422);
        }

        return;
    }

    public function isEnabled($formId = false)
    {
        $option = get_option('wppayform_honeypot_status');
        if($option === 'no')
            return false;
        return true;;
    }

    private function getFieldName($formId)
    {
        return apply_filters('wppayform_honeypot_name', 'item__' . $formId . '__wppay_checkme_', $formId);
    }

}
