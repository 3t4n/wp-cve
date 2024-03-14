<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;

class HQRentalsCarRentalVehicleTabShortcode
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        add_shortcode('hq_rentals_vehicles_tabs', array($this, 'render'));
    }

    public function render($atts = [])
    {
        $this->assets->loadOwlCarouselAssets();
        $atts = shortcode_atts(
            array(
                'title' => 'Vehicle Models - Our rental fleet at a glance',
                'forced_locale' => 'en',
                'autoscroll' => 'true'
            ),
            $atts
        );
        $query = new HQRentalsDBQueriesVehicleClasses();
        $vehicles = $query->allVehicleClasses();
        return "
            <style>
            /* Vehicles
                --------------------------------------------*/
                
                #vehicles .title span {
                    font-size: 36px !important;
                    font-weight: 900 !important;
                    margin-bottom: 65px !important;
                }
                #vehicles .subtitle {
                    font-weight: normal;
                }
                #vehicles .vehicle-nav-row {
                    padding-right: 0;
                }
                #vehicles .vehicle-container {
                    height: 365px;
                    overflow: hidden;
                    position: relative;
                    margin-bottom: 1px;
                }
                #vehicles .hq-nav {
                    list-style: none;
                    margin: 0;
                    padding: 0;
                    top: 0px;
                    position: absolute;
                    width: 100%;
                }
                #vehicles .vehicle-scroll {
                    padding-right: 10px;
                }
                #vehicles .vehicle-scroll a {
                    width: 50%;
                    height: 40px;
                    display: block;
                    background-color: #716d6e;
                    text-align: center;
                    float: left;
                    color: #fff;
                    line-height: 40px;
                    font-size: 24px;
                    transition: all 0.25s ease-in-out;
                }
                #vehicles .vehicle-scroll a:first-of-type {
                    border-right: 1px solid #fff;
                }
                #vehicles .vehicle-scroll a:hover {
                    background-color: rgba(46,84,164,.4);
                    color: #716d6e;
                }
                #vehicles .hq-nav li {
                    background-color: #e3e2e2;
                    margin-bottom: 1px;
                    position: relative;
                    transition: .2s;
                    margin-right: 10px;
                }
                #vehicles .hq-nav li span {
                    width: 0px;
                    height: 0px;
                    border-left: 10px solid #fff;
                    border-top: 10px solid transparent;
                    border-bottom: 10px solid transparent;
                    display: block;
                    position: absolute;
                    right: -10px;
                    top: 20px;
                    transition: 0.2s;
                }
                #vehicles .hq-nav li:hover {
                    background-color: #d1cece;
                }
                #vehicles .hq-nav li.active {
                    background-color: rgba(46,84,164,.4);
                    font-weight: bold;
                }
                #vehicles .hq-nav li.active span {
                    border-left-color: rgba(46,84,164,.4);
                }
                #vehicles .hq-nav li a {
                    font-size: 18px;
                    color: #716d6e;
                    padding-left: 15px;
                    display: block;
                    height: 60px;
                    line-height: 60px;
                }
                #vehicles .styled-select-vehicle-data {
                    border: 2px solid #efe9e9;
                    height: 43px;
                    position: relative;
                    width: 100%;
                    display: none;
                }
                #vehicles .styled-select-vehicle-data:after {
                    content: '';
                    background: #ffffff url('../img/dropdown-icon.png') no-repeat 5px 18px;
                    right: 0px;
                    top: 0px;
                    width: 30px;
                    height: 39px;
                    position: absolute;
                    pointer-events: none;
                }
                #vehicles .styled-select-vehicle-data select {
                    border: none;
                    font-size: 16px;
                    width: 100%;
                    background-image: none;
                    background: #fff;
                    -webkit-appearance: none;
                    padding: 1px 10px;
                    height: 39px;
                }
                #vehicles .vehicle-img {
                    text-align: center;
                }
                #vehicles .vehicle-img img {
                    display: inline-block;
                }
                #vehicles .vehicle-price {
                    background-color: rgba(46,84,164,.4);
                    height: 53px;
                    line-height: 53px;
                    padding: 0 10px;
                    font-size: 24px;
                    font-weight: 900;
                }
                #vehicles .vehicle-price .info {
                    font-weight: normal;
                    font-size: 18px;
                }
                #vehicles .reserve-button {
                    color: #fff;
                    display: block;
                    height: 50px;
                    line-height: 51px;
                    font-size: 24px;
                    font-weight: 900;
                    padding: 0 15px;
                    box-shadow: 6px 6px 0 #efe9e9;
                    text-transform: uppercase;
                    transition: .2s;
                }
                #vehicles .reserve-button:hover {
                    background-color: #716d6e;
                }
                #vehicles .reserve-button span {
                    margin-right: 7px;
                }

                
                .hq-hq-nav a{
                    font-size: 16px;
                    color: #716d6e;
                    padding-left: 15px;
                    display: block;
                    height: 60px;
                    line-height: 60px;
                }
                #vehicles .hq-hq-nav li {
                    background-color: #e3e2e2;
                    margin-bottom: 1px;
                    position: relative;
                    transition: .2s;
                    margin-right: 10px;
                }
                #vehicles .hq-hq-nav li.active, #vehicles .vehicle-scroll a:hover {
                    background-color: rgba(46,84,164,.4) !important;
                }
                #vehicles .hq-hq-nav li.active {
                    background-color:rgba(46,84,164,.4) !important;
                    font-weight: 700;
                }
                #vehicles .hq-hq-nav li {
                    background-color: #e3e2e2;
                    margin-bottom: 1px;
                    position: relative;
                    transition: .2s;
                    margin-right: 10px;
                }
                #vehicles .hq-hq-nav {
                    list-style: none;
                }
                .hq-vehicle-tap-carousel-wrapper{
                    width: 75% !important;
                }
                
                @media only screen and (max-width: 991px) {
                    .hq-vehicle-tap-carousel-wrapper{
                        width: 100% !important;
                    }   
                    .vehicle-nav-row{
                        display: none;
                    }
                }
                
            </style>
            <div id='vehicles' class='container'>
	            <div class='row'>
		            <div class='col-md-12'>
			            <h2 class='title wow fadeInDown' 
			                data-wow-offset='200'><span class='subtitle'>{$atts['title']}</span></h2>
		            </div>
		            {$this->resolveNavBar($vehicles)}
		            {$this->resolveVehicles($vehicles)}
	            </div>
            </div>  
        ";
    }
    private function resolveNavBar($vehicles): string
    {
        return "
            <!-- Vehicle nav start -->
                <div class='col-md-3 vehicle-nav-row wow fadeInUp' data-wow-offset='100'>
                    <div id='hq-vehicle-nav-container'>
                        <ul class='hq-vehicles-inner-nav hq-nav '>
                            {$this->resolveNavBarVehicles($vehicles)}
                        </ul>
                    </div>
                </div>
            <!-- Vehicle nav end -->
        ";
    }
    private function resolveNavBarVehicles($vehicles)
    {
        $html = "";
        if (is_array($vehicles) and count($vehicles)) {
            $counter = 0;
            foreach ($vehicles as $vehicle) {
                $class = ($counter == 0) ? "active" : '';
                $html .= "
                    <li class='hq-tab {$class} hq-tap-pos-{$counter}' data-position='{$counter}'>
                        <a class='hq-tab-button' 
                            data-position='{$counter}' 
                            href='#{$vehicle->getId()}'>{$vehicle->getLabelForWebsite()}
                        </a>
                        <span class='active'>&nbsp;</span>
                    </li>
                ";
                $counter++;
            }
        }
        return $html;
    }
    private function resolveVehicles($vehicles): string
    {
        $html = "";
        if (is_array($vehicles) and count($vehicles)) {
            $html .= "<div class='owl-carousel owl-theme col-md-9 hq-vehicle-tap-carousel-wrapper'>";
            foreach ($vehicles as $vehicle) {
                $features = $vehicle->getVehicleFeatures();
                $html .= "
                <!-- Vehicle {$vehicle->getId()} data start -->
                    <div class='item vehicle-class-data-{$vehicle->getId()}' id='vehicle-class-{$vehicle->getId()}'>
                        <div class='col-md-7'>
                            <div class='vehicle-img'>
                                <img class='img-responsive' src='{$vehicle->getPublicImage()}' alt='Vehicle'>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='vehicle-price'>
                                {$vehicle->getActiveRate()->daily_rate->amount_for_display} <span class='info'> Rent per day</span>
                            </div>
                            <table class='table vehicle-features'>
                                {$this->resolveVehicleFeatures($features)}
                            </table>
                            <a href='#teaser' class='reserve-button scroll-to'><span class='glyphicon glyphicon-calendar'></span>Reserve Now</a>
                        </div>
                    </div>
                <!-- Vehicle {$vehicle->getId()} data end -->
            ";
            }
            $html .= '</div>';
        }
        return $html;
    }
    private function resolveVehicleFeatures($features)
    {
        $html = "";

        if (is_array($features) and count($features)) {
            foreach ($features as $feature) {
                $content = HQRentalsFrontHelper::getTranslatedContent($feature);
                $html .= "
                <tr>
                    <td>{$content}</td>
                </tr>
            ";
            }
        }
        return $html;
    }
}
