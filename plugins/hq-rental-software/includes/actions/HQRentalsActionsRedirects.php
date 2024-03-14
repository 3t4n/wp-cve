<?php

namespace HQRentalsPlugin\HQRentalsActions;

use HQRentalsPlugin\HQRentalsHelpers\HQRentalsDatesHelper;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsVendor\Carbon;

class HQRentalsActionsRedirects
{
    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
        $this->dateHelper = new HQRentalsDatesHelper();
        add_action('template_redirect', array($this, 'safariRedirect'));
        add_action('template_redirect', array($this, 'pageRedirect'));
    }

    public function safariRedirect()
    {
        global $post;
        global $is_safari;
        if (!is_singular()) {
            return;
        }
        if (!empty($post->post_content) and !($this->settings->homeIntegration())) {
            $pattern = get_shortcode_regex();
            if (
                preg_match_all('/' . $pattern . '/s', $post->post_content, $matches)
                && array_key_exists(2, $matches)
                && in_array('hq_rentals_reservations', $matches[2])
                and ($is_safari)
            ) {
                // redirect to third party site
                $post_data = $_POST;
                $brandID = $this->getBrandIDFromRegex($post->post_content, 'hq_rentals_reservations');
                $brand = new HQRentalsModelsBrand();
                $brand->findBySystemId($brandID);
                if ($this->applyRedirect($brand->publicReservationsFirstStepLink)) {
                    $queryString = $brand->publicReservationsFirstStepLink;
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
                            $queryString .= '&pick_up_date=' . $pickup_date->format(
                                $this->dateHelper->getDateFormatFromCarbon(
                                    $this->settings->getHQDatetimeFormat()
                                )
                            ) . $pickup_date->format(
                                $this->dateHelper->getTimeFormatFromCarbon(
                                    $this->settings->getHQDatetimeFormat()
                                )
                            );
                            $queryString .= '&return_date =' . $return_date->format(
                                $this->dateHelper->getDateFormatFromCarbon(
                                    $this->settings->getHQDatetimeFormat()
                                )
                            ) . $return_date->format(
                                $this->dateHelper->getTimeFormatFromCarbon(
                                    $this->settings->getHQDatetimeFormat()
                                )
                            );
                            foreach ($post_data as $key => $value) {
                                if ($key !== 'pick_up_date' and $key !== 'pick_up_time' and $key !== 'return_date' and $key !== 'return_time') {
                                    $queryString .= '&' . $key . '=' . $value;
                                }
                            }
                            $queryString .= '&target_step=2';
                            wp_redirect($queryString);
                            exit;
                        } else {
                            wp_redirect($queryString);
                            exit;
                        }
                    } catch (\Throwable $e) {
                        wp_redirect($queryString);
                        exit;
                    }
                }
            }
        }
    }

    public function getBrandIDFromRegex($postContent, $shortcode)
    {
        /*
         * This can fail if the client has more than 10 brands
         * */
        preg_match_all('/\[' . $shortcode . ' id=(.*?)\]/', $postContent, $matches);
        $fullShortcode = $matches[0][0];
        $shortcodePieces = explode("id=", $fullShortcode);
        return $shortcodePieces[1][0];
    }

    public function noDNSRecordSetup($url)
    {
        return (strpos($url, 'caagcrm.com') !== false) or
            (strpos($url, 'hqrentals.eu') !== false) or
            (strpos($url, 'hqrentals.asia') !== false) or
            (strpos($url, 'hqrentals.app') !== false);
    }

    public function applyRedirect($url)
    {
        if ($this->settings->getDisableSafariValue()) {
            return false;
        }
        return $this->noDNSRecordSetup($url);
    }

    public function pageRedirect()
    {
        $get_data = $_GET;
        if (is_page('quotes')) {
            if (empty($get_data['quote_id'])) {
                wp_redirect('home');
                exit;
            }
        }
        if (is_page('payments')) {
            if (empty($get_data['payment_id'])) {
                wp_redirect('home');
                exit;
            }
        }
    }
}
