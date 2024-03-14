<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;

new HQRentalBakeryRentitReservationFormShortcode();


class HQRentalBakeryRentitReservationFormShortcode extends WPBakeryShortCode
{
    private $query;
    private $reservation_url;
    private $sub_title;
    private $title;
    private $form_title;
    private $background_image;

    public function __construct()
    {
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_bakery_rentit_reservation_form', array($this, 'content'));
        $this->query = new HQRentalsDBQueriesVehicleClasses();
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
    }

    public function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'title' => esc_html__('All Discounts Just For You', 'rentit'),
            'sub_title' => esc_html__('Find Best Rental Car', 'rentit'),
            'form_title' => esc_html__('Search for Cheap Rental Cars Wherever Your Are', 'rentit'),
            'reservation_url' => '',
            'background_image' => '',
            'action' => '',
        ), $atts));
        $this->reservation_url = $atts['reservation_url'];
        $this->sub_title = $atts['sub_title'];
        $this->title = $atts['title'];
        $this->form_title = $atts['form_title'];
        $this->background_image = wp_get_attachment_image_src($atts['background_image'], 'full')[0];
        echo $this->renderShortcode();
    }

    public function setParams()
    {
        vc_map(
            array(
                'name' => __('HQRS Rentit Reservation Form', 'hq-wordpress'),
                'base' => 'hq_bakery_rentit_reservation_form',
                'content_element' => true,
                "category" => __('HQ Rental Software - Rentit Theme'),
                'show_settings_on_create' => true,
                'description' => __('HQ Rentit Reservation Form', 'hq-wordpress'),
                'icon' => HQRentalsAssetsHandler::getHQLogo(),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Enter the Slider Title', 'hq-wordpress'),
                        'param_name' => 'title',
                        'value' => '',
                        'description' => esc_html__('Enter the Slider Title', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Enter the Slider Subtitle', 'hq-wordpress'),
                        'param_name' => 'sub_title',
                        'value' => '',
                        'description' => esc_html__('Enter the Slider Subtitle', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Form Title', 'hq-wordpress'),
                        'param_name' => 'form_title',
                        'value' => '',
                        'description' => esc_html__('Enter the Form Title', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => esc_html__('Backgroung Image', 'hq-wordpress'),
                        'param_name' => 'background_image',
                        'value' => '',
                        'description' => esc_html__('Backgroung Image', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Reservation URL', 'hq-wordpress'),
                        'param_name' => 'reservation_url',
                        'value' => '',
                        'description' => esc_html__('Enter Reservation Page Url', 'hq-wordpress')
                    ),
                )
            )
        );
    }

    public function renderShortcode()
    {
        $this->assets->loadDatePickersReservationAssets();
        $locations_options = $this->helper->getLocationOptions();
        return HQRentalsAssetsHandler::getHQFontAwesome() . "
                <!--Begin-->
                <div class='item slide1 ver1 hq-slider-image' style='background-image: url({$this->background_image});'>
                    <style>
                        .hq-slider-caption{
                            background:rgba(0, 0, 0, 0.5);
                        }
                    </style>
                    <div class='caption hq-slider-caption'>
                        <div class='container'>
                            <div class='div-table hq-form-outer-wrapper'>
                                <div class='div-cell'>
                                    <form id='hq-home-form' action='{$this->reservation_url}' method='get' class='caption-content'>
                                        <input type='hidden' name='target_step' value='2' />
                                        <h2 class='caption-title hq-home-form-title'>{$this->sub_title}</h2>
                                        <h3 class='caption-subtitle hq-home-form-subtitle'>{$this->title}</h3>
                                        <!-- Search form -->
                                        <div class='row'>
                                            <div class='col-sm-12 col-md-10 col-md-offset-1'>
                                                <div class='form-search dark'>
                                                    <div class='form-title'>
                                                        <h2>{$this->form_title}</h2>
                                                    </div>
                                                    <div class='row row-inputs'>
                                                        <div class='container-fluid'>
                                                            <div class='col-sm-6'>
                                                                <div class='form-group has-icon has-label'>
                                                                    <label>Pickup Location</label>
                                                                    <select name='pick_up_location' id='hq-pick-up-location' class='hq-locations-selects'>
                                                                        <option>Location</option>
                                                                        " . $locations_options . "
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class='col-sm-6'>
                                                                <div class='form-group has-icon has-label'>
                                                                    <label>Pick up Date</label>
                                                                    <input name='pick_up_date' type='text' autocomplete='off' class='form-control'
                                                                           id='hq_pick_up_date'
                                                                           placeholder='Date'>
                                                                    <span class='form-control-icon'><i class='fa fa-calendar'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row row-inputs'>
                                                        <div class='container-fluid'>
                                                            <div class='col-sm-6'>
                                                                <div class='form-group has-icon has-label'>
                                                                    <label>Return Location</label>
                                                                    <select name='return_location' id='hq-return-location' class='hq-locations-selects'>
                                                                        <option>Location</option>
                                                                        " . $locations_options . "
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class='col-sm-6'>
                                                                <div class='form-group has-icon has-label'>
                                                                    <label>Return Date</label>
                                                                    <input name='return_date' type='text' autocomplete='off' class='form-control'
                                                                           id='hq_return_date'
                                                                           placeholder='Date'>
                                                                    <span class='form-control-icon'><i class='fa fa-calendar'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row row-submit hq-row-submit'>
                                                        <div class='container-fluid'>
                                                            <div class='inner'>
                                                                <button type='submit' class='btn btn-submit btn-theme pull-right hq-submit-button'>
                                                                    Book
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Search form -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <style>
                    .hq-slider-image{
                        background-size: cover;
                    }
                    .div-table {
                        margin: auto;
                    }
                    .hq-form-outer-wrapper{
                        width: 100%;
                    }

                    .hq-locations-selects {
                        width: 100%;
                        background: rgba(255, 255, 255, 0.2);
                        border: 1px solid rgba(255, 255, 255, 0);
                        color: rgba(255, 255, 255, 0.6);
                        padding-right: 40px;
                        height: 40px;
                    }

                    .hq-locations-selects option {
                        color: #14181C;
                    }

                    .hq-home-form-title {
                        text-align: center;
                        font-family: roboto, sans-serif;
                        font-size: 24px;
                        font-weight: 100;
                        line-height: 1;
                        color: #fff;
                        clear: both;
                        text-transform: uppercase;
                        margin: 0 0 15px;
                    }

                    .hq-home-form-subtitle {
                        text-align: center;
                        font-family: raleway, sans-serif;
                        font-size: 72px;
                        font-weight: 900;
                        line-height: 1;
                        text-transform: uppercase;
                        color: #fff;
                        margin: 0 0 40px;
                    }

                    #hq-home-form {
                        margin-top: 70px;
                        margin-bottom: 70px;
                    }

                    .hq-text-inputs {
                        width: 100%;
                        background: rgba(255, 255, 255, 0.2);
                        border: 1px solid rgba(255, 255, 255, 0);
                        color: rgba(255, 255, 255, 0.6);
                        padding-right: 40px;
                        height: 40px;
                    }

                    .hq-row-submit {
                        padding-bottom: 30px;
                    }
                </style>
                <!-- /Slide 1 -->
        ";
    }
}
