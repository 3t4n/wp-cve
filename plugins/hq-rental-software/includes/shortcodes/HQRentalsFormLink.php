<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;

class HQRentalsFormLink
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
        add_shortcode('hq_rentals_form_link', array($this, 'packagesShortcode'));
    }

    public function packagesShortcode($atts = [])
    {
        $atts = shortcode_atts(
            array(
                'url' => '',
                'forced_locale' => 'en',
                'autoscroll' => 'true'
            ),
            $atts
        );
        $langParams = '?forced_locale=' . $atts['forced_locale'];
        $this->assets->getIframeResizerAssets();
        if ($atts['autoscroll'] == 'true') {
            $this->assets->loadScrollScript();
        }
        if (!empty($_POST['hq-integration'])) {
            $post_data = $_POST;
            $post_data = $this->helper->sanitizeTextInputs($post_data);
            ?>
            <form action="<?php echo esc_url($atts['url']); ?>" method="POST"
                  target="hq-rental-iframe" id="hq-form-init">
                <?php foreach ($post_data as $key => $value) : ?>
                    <input type="hidden" name="<?php echo esc_attr($key) ?>" value="<?php echo esc_attr($value); ?>"/>
                <?php endforeach; ?>
                <input type="submit" style="display: none;">
            </form>
            <?php
            $this->assets->getFirstStepShortcodeAssets();
        }
        return '<iframe id="hq-rental-iframe" src="' . esc_url($atts['url'] . $langParams) . '" scrolling="no"></iframe>';
    }
}
