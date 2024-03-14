<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsDatesHelper;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsVendor\Carbon;

class HQRentalsReservationsShortcode
{
    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
        $this->dateHelper = new HQRentalsDatesHelper();
        $this->assets = new HQRentalsAssetsHandler();
        $this->frontHelper = new HQRentalsFrontHelper();
        add_shortcode('hq_rentals_reservations', array($this, 'reservationsShortcode'));
    }

    public function reservationsShortcode($atts = [])
    {
        $atts = shortcode_atts(
            array(
                'id' => '1',
                'forced_locale' => 'en',
                'new' => 'true',
                'autoscroll' => 'true'
            ),
            $atts,
            'hq_rentals_reservations'
        );
        ob_start();
        $post_data = $_POST;
        $post_data = $this->frontHelper->sanitizeTextInputs($post_data);
        $brand = new HQRentalsModelsBrand();
        $brand->findBySystemId($atts['id']);
        $this->assets->getIframeResizerAssets();
        if ($atts['autoscroll'] == 'true') {
            $this->assets->loadScrollScript();
        }
        try {
            if ($post_data['pick_up_date']) {
                if ($post_data['pick_up_time']) {
                    $pickup_date = Carbon::createFromFormat(
                        $this->settings->getFrontEndDatetimeFormat(),
                        $post_data['pick_up_date'] . ' ' . $post_data['pick_up_time']
                    );
                    $return_date = Carbon::createFromFormat(
                        $this->settings->getFrontEndDatetimeFormat(),
                        $post_data['return_date'] . ' ' . $post_data['return_time']
                    );
                } else {
                    $pickup_date = Carbon::createFromFormat(
                        $this->settings->getFrontEndDatetimeFormat(),
                        $post_data['pick_up_date']
                    );
                    $return_date = Carbon::createFromFormat(
                        $this->settings->getFrontEndDatetimeFormat(),
                        $post_data['return_date']
                    );
                }
                ?>
                <form action="
                    <?php echo esc_url($brand->publicReservationsFirstStepLink . '&' .
                    'forced_locale=' . $atts['forced_locale']); ?>"
                      method="POST"
                      target="hq-rental-iframe" id="hq-form-init">
                    <input type="hidden" name="pick_up_date"
                           value="<?php echo
                                        esc_attr($pickup_date->format(
                                            $this->dateHelper->getDateFormatFromCarbon(
                                                $this->settings->getHQDatetimeFormat()
                                            )
                                        )); ?>"/>
                    <input type="hidden" name="pick_up_time"
                        value="<?php
                                    echo esc_attr(
                                        $pickup_date->format(
                                            $this->dateHelper->getTimeFormatFromCarbon(
                                                $this->settings->getHQDatetimeFormat()
                                            )
                                        )
                                    ); ?>"/>
                    <input type="hidden" name="return_date"
                           value="<?php
                                    echo esc_attr(
                                        $return_date->format(
                                            $this->dateHelper->getDateFormatFromCarbon(
                                                $this->settings->getHQDatetimeFormat()
                                            )
                                        )
                                    ); ?>"/>
                    <input type="hidden" name="return_time"
                           value="<?php
                                    echo esc_attr(
                                        $return_date->format(
                                            $this->dateHelper->getTimeFormatFromCarbon(
                                                $this->settings->getHQDatetimeFormat()
                                            )
                                        )
                                    ); ?>"/>
                    <?php foreach ($post_data as $key => $value) : ?>
                        <?php if (
                                    $key !== 'pick_up_date' and
                                    $key !== 'pick_up_time' and
                                    $key !== 'return_date' and
                                    $key !== 'return_time'
) : ?>
                                    <input type="hidden" name="<?php echo esc_attr($key); ?>"
                                    value="<?php echo esc_attr($value); ?>"/>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <input type="submit" style="display: none;">
                </form>
                <iframe id="hq-rental-iframe" name="hq-rental-iframe"
                        src="<?php
                                echo esc_url(
                                    $brand->publicReservationsLinkFull .
                                        '&' . 'forced_locale=' . $atts['forced_locale']
                                ); ?>"
                        scrolling="no"></iframe>
                <?php
                $this->assets->getFirstStepShortcodeAssets();
            } else {
                if (!empty($_GET)) {
                    $getData = $this->frontHelper->sanitizeTextInputs($_GET);
                    $query = http_build_query($getData);
                    ?>
                    <iframe id="hq-rental-iframe" name="hq-rental-iframe"
                            src="<?php
                                    echo esc_url(
                                        $brand->publicReservationsLinkFull .
                                            '&' . $query . '&' . 'forced_locale=' .
                                            $atts['forced_locale']
                                    ); ?>"
                            scrolling="no"></iframe>
                    <?php
                } else {
                    ?>
                    <iframe id="hq-rental-iframe" name="hq-rental-iframe"
                            src="<?php
                                    echo esc_url(
                                        $brand->publicReservationsLinkFull .
                                            '&' . 'forced_locale=' . $atts['forced_locale']
                                    ); ?>"
                            scrolling="no"></iframe>
                    <?php
                }
            }
        } catch (\Throwable $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
