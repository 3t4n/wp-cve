<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsElementor\HQRentalsElementorAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesLocations;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsThemes\HQRentalsThemeCustomizer;

class HQWheelsberryLocationMapShortcode
{
    private $title;
    private $button_text;
    private $reservation_url;

    public function __construct()
    {
        $this->queryVehicles = new HQRentalsDBQueriesVehicleClasses();
        $this->settings = new HQRentalsSettings();
        $this->queryLocations = new HQRentalsDBQueriesLocations();
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
        add_shortcode('hq_wheelsberry_location_map', array($this, 'renderShortcode'));
    }
    public function renderShortcode()
    {
        global $post;
        $locationsOptions = '';
        $locationsQuery = new HQRentalsQueriesLocations();
        $locations = $locationsQuery->allLocations();
        foreach ($locations as $location) {
            $locationsOptions .= '<option value="' . $location->getId() . '" 
            data-address="' . $location->getCustomFieldForAddress() . '" 
            data-geo-lat="' . $location->getLatitude() . '" 
            data-geo-lng="' . $location->getLongitude() . '">' . $location->getName() . '</option>';
        }
        $googleKey = $this->settings->getGoogleAPIKey();
        if (empty($googleKey)) {
            $html = '<p style="text-align:center"><em>' .
                esc_html__('Google Maps API Key must specified in "HQ Rentals Settings"', 'hq-wordpress') .
                '</em></p>';
        } else {
            $html = HQRentalsAssetsHandler::getHQFontAwesome() . "
            <script>
                function hq_google_maps_api_loaded() {
                    initMapLocationGlobal();
                }
            </script>
            <div id='hq-map-location' class='vc_om-rental-locations'>
                <div class='om-rental-locations__header'>
                    <div class='om-container'>
                        <div class='om-rental-locations__header-inner om-container__inner'>
                            <h3 class='om-rental-locations__title'>Choose your Location</h3>
                            <div class='om-rental-locations__selector'>
                                <select class='om-rental-locations__select'>
                                    {$locationsOptions}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='om-rental-locations__map' 
                style='height: 370px; position: relative; overflow: hidden; width: 100%;' 
                data-map-zoom='12' data-map-marker='" . HQRentalsThemeCustomizer::getMapPinImage() . "'></div>
            </div>
            <script>
                function initMapLocationGlobal(){
                    jQuery(window).trigger('google_maps_api_loaded');
                    jQuery(window).data('google_maps_api_loaded',true);
                    jQuery('.vc_om-rental-locations').each(function(){
                        var obj = jQuery(this);
                        var error_container=jQuery('<div />');
                        var map=obj.find('.om-rental-locations__map');
                        var select= obj.find('.om-rental-locations__select');
                        var zoom= map.data('map-zoom');
                        if(!zoom) {
                            zoom=16;
                        }
                        var map_marker= map.data('map-marker');
                        
                        var locations={};
                        var mapInitialized=false;
                        var gMap;
        
                        var initMap = function() {
                            if(mapInitialized) {
                                return;
                            }
                            var ready=true;
                            jQuery.each([],function(id, location) {
                                if(!location.ready) {
                                    ready=false;
                                    return false;
                                }
                            });
                            if(!ready) {
                                return;
                            }
                            
                            mapInitialized=true;
                            
                            gMap = new google.maps.Map(map.get(0), {
                                center: {lat: 40, lng: -74},
                                zoom: 2,
                                scrollwheel: false,
                                draggable: !('ontouchstart' in window)
                            });
                            jQuery.each(locations,function(id, location) {
                                var latlng = new google.maps.LatLng(location.lat,location.lng);
                                var marker_agrs={
                                    position: latlng,
                                    title: location.name,
                                    map: gMap
                                };
                                if(map_marker) {
                                    marker_agrs.icon={
                                        url: map_marker
                                    };
                                }
                                var marker = new google.maps.Marker(marker_agrs);
                                var infowindow = new google.maps.InfoWindow({
                                    
                                    content: '<b>' + location.name + '</b><br/>' + location.address,
                                });
                                
                                marker.addListener('click', function() {
                                    infowindow.open(gMap, marker);
                                });
                            });
                            
                            select.change(function(){
                                var id=jQuery(this).val();
                                gMap.setCenter({lat: locations[id].lat, lng: locations[id].lng});
                                gMap.setZoom(zoom);
                            }).change();
                        };
                        
                        select.find('option').each(function(){
                            var id=jQuery(this).attr('value');
                            locations[id]={
                                address: jQuery(this).data('address'),
                                name: jQuery(this).text(),
                                ready: false
                            };
                            if(jQuery(this).data('geo-lat') && jQuery(this).data('geo-lng')) {
                                locations[id].lat=parseFloat(jQuery(this).data('geo-lat'));
                                locations[id].lng=parseFloat(jQuery(this).data('geo-lng'));
                                locations[id].ready=true;
                            } else {
                                var geocoder = new google.maps.Geocoder();
                                geocoder.geocode({'address':jQuery(this).data('address')}, function(results, status){
                                    if(status === google.maps.GeocoderStatus.OK) {
                                        locations[id].lat=results[0].geometry.location.lat();
                                        locations[id].lng=results[0].geometry.location.lng();
                                        locations[id].ready=true;
                                        initMap();
                                    } else if( status === google.maps.GeocoderStatus.REQUEST_DENIED) {
                                    }
                                });
                            }
                        });
                        initMap();    
                    });    
                }
                
            </script>
            <script 
                src='https://maps.googleapis.com/maps/api/js?key=" . esc_attr($googleKey) .
                    "&callback=initMapLocationGlobal'>
            </script>
            ";
        }
        return $html;
    }
}
