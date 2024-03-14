<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;

new HQRentalBakeryTurboReservationForm();

class HQRentalBakeryTurboReservationForm extends WPBakeryShortCode
{
    private $query;
    private $reservationURL;
    private $title;
    private $content;
    private $backgroundImage;

    public function __construct()
    {
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_turbo_reservation_form', array($this, 'content'));
        $this->query = new HQRentalsDBQueriesVehicleClasses();
        $this->queryLocations = new HQRentalsDBQueriesLocations();
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
    }

    public function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'search_form_title' => '',
            'content' => '',
            'action_url' => '/reservations/',
            'background_image_id' => ''
        ), $atts));

        $this->assets->loadAucapinaReservationFormAssets();
        $this->reservationURL = $atts['action_url'];
        $this->title = $atts['search_form_title'];
        $this->content = $atts['content'];
        $this->backgroundImage = wp_get_attachment_url($atts['background_image_id']);
        echo $this->renderShortcode();
    }

    public function setParams()
    {
        vc_map(
            array(
                "name" => __("HQ - Turbo - Vertical Search Form", "hq-wordpress"),
                "base" => "hq_turbo_reservation_form",
                "category" => __('Turbo Shortcode', 'hq-wordpress'),
                "icon" => HQRentalsAssetsHandler::getHQLogo(),
                "params" => array(
                    array(
                        "type" => "textfield",
                        "admin_label" => true,
                        "heading" => __("Search Form Title", "hq-wordpress"),
                        "param_name" => "search_form_title",
                    ),
                    array(
                        "type" => "textfield",
                        "admin_label" => true,
                        "heading" => __("Search Form Description", "hq-wordpress"),
                        "param_name" => "content",
                        "description" => __("Enter Search Form Description", "hq-wordpress")
                    ),
                    array(
                        "type" => "textfield",
                        "admin_label" => true,
                        "heading" => __("Reservation Page Screen", "hq-wordpress"),
                        "param_name" => "action_url",
                        "description" => __("Enter the URL from the Reservation Engine Page", "hq-wordpress")
                    ),
                    array(
                        "type" => "attach_image",
                        "admin_label" => true,
                        "heading" => __("Background Image", "hq-wordpress"),
                        "param_name" => "background_image_id",
                    )
                )
            )
        );
    }

    public function renderShortcode()
    {
        $locations = $this->queryLocations->allLocations();
        $locations_options = $this->helper->getLocationOptions($locations);
        $locale = get_locale();
        return HQRentalsAssetsHandler::getHQFontAwesome() . "
            <style>
                .hq-turbo-label{
                    display: block;
                    color: #2d3748;
                    font-size: 16px;
                    font-weight: 600;
                    text-transform: uppercase;
                    margin-bottom: 0;
                }
                .hq-turbo-input-group-wrapper{
                    padding-bottom: 20px;
                }
                #hq-turbo-reservation-form select,
                #hq-turbo-reservation-form input{
                    width: 100%;
                    max-width: 100% !important;
                }
                .hq-turbo-input-label-wrapper{
                    padding-bottom: 10px;
                }
                .hq-turbo-submit-button{
                    width: 100%;
                    max-width: 100%;
                    font-size: 14px;
                    font-weight: 700;
                    color: #fdfdfd;
                    display: inline-block;
                    background: none;
                    text-align: center;
                    background-color: #454545;
                    padding: 0 30px;
                    height: 42px;
                    line-height: 42px;
                    outline: 0;
                    border: 0;
                    cursor: pointer;
                    text-decoration: none;
                }
            </style>
            <script>
                var locale = '{$locale}';
            </script>
            <div id='hq-turbo-reservation-form' class='header turbo-vertical-search-wrapper index-two-header'>
                <div class='header-body' style='background: url({$this->backgroundImage}) top center no-repeat; background-size: 100% auto;'>
                    <div class='container'>
                        <div class='turbo-vertical-search-area'>
                            <div class='search-header'>
                                <h3>{$this->title}</h3>
                                <p>{$this->content}</p>
                            </div>
                            <form action='{$this->reservationURL}' method='get'>
                                <input type='hidden' id='hq_pick_up_date' name='pick_up_date' />
                                <input type='hidden' id='hq_return_date' name='return_date' />
                                <input type='hidden' name='target_step' value='2' />
                                <div class='turbo-obb-vertical-search-form'>
                                    <div class='turbo-horizontal-search-oob'>
                                        <div class='hq-turbo-input-group-wrapper'>
                                            <div class='hq-turbo-input-label-wrapper'>
                                                <label class='hq-turbo-label' for='hq_pick_up_location'>PICK UP LOCATION</label>
                                            </div>
                                            <div class='hq-turbo-input-wrapper'>
                                                <select name='pick_up_location' id='hq_pick_up_location'>
                                                    {$locations_options}
                                                </select>
                                            </div>
                                        </div>
                                        <div class='hq-turbo-input-group-wrapper'>
                                            <div class='hq-turbo-input-label-wrapper'>
                                                <label for='hq_return_location' class='hq-turbo-label'>DROP OFF LOCATION</label>
                                            </div>
                                            <div class='hq-turbo-input-wrapper'>
                                                <select name='return_location' id='hq_return_location' />
                                                    {$locations_options}
                                                </select>
                                            </div>
                                        </div>
                                        <div class='hq-turbo-input-group-wrapper'>
                                            <div class='hq-turbo-input-label-wrapper'>
                                                <label for='hq-daterange' class='hq-turbo-label'>CHOOSE DATE</label>
                                            </div>
                                            <div class='hq-turbo-input-wrapper'>
                                                <input type='text' id='hq-daterange' name='hq-daterange' required='required' />
                                            </div>
                                        </div>
                                        <div class='hq-turbo-input-group-wrapper'>
                                            <div class='hq-turbo-input-label-wrapper'>
                                               <button type='submit' class='hq-turbo-submit-button'>Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                jQuery(document).ready(function(){
                    
                });
            </script>
        ";
    }
}
