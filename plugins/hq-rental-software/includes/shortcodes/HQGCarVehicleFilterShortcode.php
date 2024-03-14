<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQGCarVehicleFilterShortcode
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        $this->settings = new HQRentalsSettings();
        add_shortcode('hq_gcar_vehicles_filter', array($this, 'renderShortcode'));
    }

    public function renderShortcode($atts = [])
    {
        $this->assets->gCarVehicleFilterAssets();
        $atts = shortcode_atts(
            array(
                'baseURL' => get_site_url() . '/',
            ),
            $atts
        );
        $dataToJS = array(
            'baseURL' => get_site_url() . '/',
        );
        wp_localize_script('hq-gcar-vehicle-filter-js', 'HQGCarVehicleFilter', $dataToJS);
        ?>
        <style>
            .portfolio_filter_wrapper p {
                padding-bottom: 5.0em !important;
            }

            .spinner-wrapper img {
                padding-bottom: 5.0em !important;
            }

            .button:hover {
                background: #6699cc !important;
                border-color: #6699cc !important;
            }

            .four_cols.gallery .element {
                width: calc(33.33% - 22.5px) !important;
            }

            .four_cols.gallery .element:nth-child(3n) {
                float: right !important;
                margin-right: 0 !important;
            }

            .car_attribute_price_day.four_cols .single_car_price {
                font-size: 34px !important;
                line-height: 1.5;
            }

            .car_attribute_price_day.four_cols .car_unit_day {
                font-size: 11px !important;
                margin-top: -15px;
            }

            .car_link h5 {
                font-size: 20px !important;
                font-weight: 700;
                letter-spacing: 0px;
            }

            .car_attribute_price_day.four_cols .single_car_currency {
                top: -15px !important;
                font-size: 13px !important;
            }

            #page_content_wrapper .inner .sidebar_content {
                margin-top: 40px !important;
            }

            #page_caption {
                margin-bottom: 0px !important;
            }

            .hq-feature-wrapper {
                display: flex;
                flex: 1;
                align-items: center;
                justify-content: center;
                width: 20% !important;
            }

            .single_car_attribute_wrapper .car_attribute_content {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .hq-feature-wrapper .car_attribute_content {
                margin-left: 20px;
            }

            .feature-wrapper {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
            }

            .hq-inputs {
                width: 100%;
            }

            label {
                text-align: left;
            }

            .car_attribute_wrapper {
                width: 60% !important;
            }

            .car_attribute_price {
                width: 40% !important;
            }

            .single_car_attribute_wrapper .fa, .single_car_attribute_wrapper .fas {
                font-size: 30px !important;
            }

            .wrapper {
                max-width: 1425px;
                width: 100%;
                box-sizing: border-box;
                margin: auto;
                padding: 0 90px;
                height: 100%;
            }

            @media only screen and (max-width: 1099px) and (min-width: 960px) {
                .wrapper {
                    width: 100%;
                    margin-top: 0;
                    padding: 0 30px 0 30px;
                    box-sizing: border-box;
                }

                .car_attribute_price_day.four_cols .single_car_price {
                    font-size: 25px !important;
                }
            }

            .car_attribute_price_day.four_cols .single_car_price {
                font-size: 34px;
            }

            @media only screen and (max-width: 960px) and (min-width: 768px) {
                .portfolio_info_wrapper {
                    display: flex;
                    flex: 1;
                    justify-content: center;
                    align-items: center;
                    flex-direction: column;
                }

                .car_attribute_wrapper, .car_attribute_price {
                    width: 100% !important;
                }

                .wrapper {
                    width: 100%;
                    margin-top: 0;
                    padding: 0 30px 0 30px;
                    box-sizing: border-box;
                }

            }

            @media only screen and (max-width: 1099px) and (min-width: 960px) {
                .wrapper {
                    width: 100%;
                    margin-top: 0;
                    padding: 0 30px 0 30px;
                    box-sizing: border-box;
                }

                .car_attribute_price_day.three_cols .single_car_price {
                    font-size: 25px !important;
                }
            }

            @media only screen and (max-width: 1099px) and (min-width: 960px) {
                .car_attribute_price, .car_attribute_wrapper {
                    width: 50% !important;
                }
            }

            @media only screen and (max-width: 767px) {
                .wrapper {
                    width: 100%;
                    margin-top: 0;
                    padding: 0 30px 0 30px;
                    box-sizing: border-box;
                }


                .car_attribute_price, .car_attribute_wrapper {
                    width: 50% !important;
                }

                h4 {
                    font-size: 18px !important;
                }

                .single_car_attribute_wrapper .one_fourth, .single_car_attribute_wrapper .one_fourth.last {
                    width: 50% !important;
                    clear: none;
                    text-align: left;
                }
            }

            .hq-feature-wrapper {
                display: flex;
                flex: 1;
                align-items: center;
                justify-content: flex-start;
            }

            .single_car_attribute_wrapper .fa, .single_car_attribute_wrapper .fas {
                line-height: 1.5;
            }

            .inner {
                padding-bottom: 50px;
            }

            #portfolio_filter_wrapper .car_unit_day {
                margin-top: -15px !important;
                font-size: 11px !important;
            }

            #portfolio_filter_wrapper .single_car_currency {
                top: -15px !important;
            }

            .hq-class-title {
                font-size: 40px;
                font-weight: 700;
                line-height: 1.3;
            }

            .sidebar_content :not(.full_width) .car_attribute_wrapper_icon {
                width: 100% !important;
            }

            .car_attribute_wrapper_icon .one_fourth {
                width: 14% !important;
            }

            /*Features*/
            .car_attribute_wrapper_icon {
                flex: 1;
                display: flex;
                flex-direction: row;
                justify-content: flex-start;
                align-items: center;
                padding: 0px 20px 20px 20px;
                margin-top: 0px !important;
            }

            .car_attribute_wrapper_icon .feature-wrapper {
                margin-right: 15px;
            }

            .portfolio_info_wrapper {
                padding-bottom: 0px !important;
            }

            /*End Features*/
        </style>
        <link rel="stylesheet" href="https://caag.caagcrm.com/assets/font-awesome">
        <div id="hq-gcar-vehicle-filter"></div>
        <?php
    }
}
