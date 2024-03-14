<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;

new HQRentalBakeryRentitSliderShortcode();

class HQRentalBakeryRentitSliderShortcode extends WPBakeryShortCode
{
    private $query;
    private $reservationURL;
    private $title;
    private $sub_title;
    private $category;

    public function __construct()
    {
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_bakery_rentit_slider', array($this, 'content'));
        $this->query = new HQRentalsDBQueriesVehicleClasses();
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
    }

    public function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'title' => esc_html__("What a Kind of Car You Want", "hq-wordpress"),
            'sub_title' => esc_html__('Great Rental Offers for You', "hq-wordpress"),
            'id' => ''
        ), $atts));
        $this->reservationURL = $atts['reservation_page_url'];
        $this->title = $atts['title'];
        $this->sub_title = $atts['sub_title'];
        $this->id = $atts['id'];
        echo $this->renderShortcode();
    }

    public function setParams()
    {
        vc_map(
            array(
                'name' => __('HQRS Rentit Vehicle Class Slider', 'hq-wordpress'),
                'base' => 'hq_bakery_rentit_slider',
                'content_element' => true,
                "category" => __('HQ Rental Software - Rentit Theme'),
                'show_settings_on_create' => true,
                'description' => __('HQ Rentit Reservation Form', 'hq-wordpress'),
                'icon' => HQRentalsAssetsHandler::getHQLogo(),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Title', 'hq-wordpress'),
                        'param_name' => 'title',
                        'value' => '',
                        'description' => esc_html__('Enter the Silder Title', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Sub Title', 'motors'),
                        'param_name' => 'sub_title',
                        'value' => '',
                        'description' => esc_html__('Enter the Silder Sub Title', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Categories', 'motors'),
                        'param_name' => 'id',
                        'value' => '',
                        'description' => esc_html__('Enter the category Ids', 'hq-wordpress')
                    ),
                )
            )
        );
    }

    public function renderShortcode()
    {
        $this->assets->loadDatePickersReservationAssets();
        $vehicles = $this->query->allVehicleClasses();
        return HQRentalsAssetsHandler::getHQFontAwesome() . "
                <link rel='stylesheet' href='https://unpkg.com/swiper/swiper-bundle.min.css' />
                <section class='page-section'>
                    <div class='container'>
                        <h2 class='section-title wow fadeInUp' data-wow-offset='70' data-wow-delay='100ms'>
                            <small>{$this->title}</small>
                            <span>{$this->sub_title}</span>
                        </h2>
                        <div>
                            <div>
                                <div class='swiper swipe-container'>
                                    <div class='swiper-container swiper-container-slider-sc'>
                                        <div class='swiper-wrapper'>
                                            " . $this->resolveLoop($vehicles) . "
                                        </div>
                                    </div>
                                    <div class='swiper-button-next'><i class='fa fa-angle-right'></i></div>
                                    <div class='swiper-button-prev'><i class='fa fa-angle-left'></i></div>
                                </div>
                            </div>    
                        </div>
                    </div>
                </section>
                <style>
                    .hq-vehicle-front-image{
                        max-width: 100%;
                        max-height: 230px;
                        overflow-y: hidden;
                    }
                    .swiper-button-next,.swiper-button-prev{
                        width: 40px;
                        height: 40px;
                        line-height: 40px;
                        margin-top: -20px;
                        text-align: center;
                        background: 0 0;
                        border: solid 4px #14181c;
                        position: absolute;
                        top: 50%;
                    }
                   .swiper-button-next i,.swiper-button-prev i{
                        display: flex;
                        flex: 1;
                        justify-content: center;
                        align-items: center;
                        position: absolute;
                        color: #000;
                   }
                   .swiper .swiper-button-next{
                        color: transparent;
                   }
                   .features-table span{
                        font-family: Roboto;
                        font-weight: bold;
                   }
                   
                </style>
                <script src='https://unpkg.com/swiper/swiper-bundle.min.js'></script>
                <script>
                    const swiper = new Swiper('.swiper-container-slider-sc', {
                        // Optional parameters
                        direction: 'horizontal',
                        loop: true,
                        speed: 400,
                        autoHeight: true,
                        slidesPerView: 3,
                        spaceBetween: 30,
                        autoplay: 2000,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        // And if we need scrollbar
                        scrollbar: {
                            el: '.swiper-scrollbar',
                        },
                    });
                </script>
        ";
    }

    public function resolveLoop($vehicles)
    {
        $html = "";
        foreach ($vehicles as $vehicle) {
            $html .= $this->resolveItem($vehicle);
        }
        return $html;
    }

    public function resolveItem($vehicle)
    {
        return "
            <div class='swiper-slide'>
                <div class='thumbnail no-border no-padding thumbnail-car-card'>
                    <div class='media'>
                        <a class='media-link' data-gal='prettyPhoto' href='{$vehicle->getPublicImage()}' alt='{$vehicle->getLabelForWebsite()}'>
                            <img class='hq-vehicle-front-image' src='{$vehicle->getPublicImage()}' alt='{$vehicle->getLabelForWebsite()}'>
                            <span class='icon-view'><strong><i class='fa fa-eye'></i></strong></span>
                        </a>
                    </div>
                    <div class='caption text-center'>
                        <h4 class='caption-title'>
                            <a href=''>{$vehicle->getLabelForWebsite()}</a>
                        </h4>
                        <div class='caption-text'>{$vehicle->getActiveRate()->daily_rate->amount_for_display} /per day</div>
                        <div class='buttons'>
                            <a class='btn btn-theme ripple-effect'
                               href=''>Reserve</a>
                        </div>
                        " . $this->resolveFeatures($vehicle->features) . "
                    </div>
                </div>
            </div>
        ";
    }

    public function resolveFeatures($features)
    {

        $html = "";
        if (is_array($features) and count($features)) {
            $html .= "<table class='table features-table'>
                        <tr>";
            foreach ($features as $feature) {
                $html .= "
                        <td>
                            <i class='{$feature->icon}'</i>
                            <span>{$feature->label}</span>
                        </td>
                ";
            }
            $html .= "</tr>
                </table>";
        }
        return $html;
    }
}
