<?php

/**
 * HQ Location Map
 *
 */

if (!defined('FW')) {
    die('Forbidden');
}


function hq_location_map()
{

    ob_start();

    ?>

    <section id="locations">
        <div
                class="container location-select-container wow bounceInDown animated"
                data-wow-offset="200"
                style="visibility: visible; animation-name: bounceInDown;"
        >
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="location-select">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if (get_locale() == 'en_US') : ?>
                                    <h2>Car Rental Locations</h2>
                                <?php elseif (get_locale() == 'es_ES') : ?>
                                    <h2>Ubicaciones</h2>
                                <?php else : ?>
                                    <h2>Localizações</h2>
                                <?php endif; ?>  
                                
                            </div>
                            <div class="col-md-6">
                                <div class="styled-select-location">
                                    <select id="location-map-select">
                                        <option value="https://maps.google.com/maps?q=3256%20NW%2024th%20St%20Rd%2C%20Miami%20Fl%2033142&t=&z=13&ie=UTF8&iwloc=&output=embed">Miami</option>
                                        <option value="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d24103.846546517056!2d-80.15792500777896!3d26.097017910382522!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d900862ca97529%3A0xd9b77083012d233b!2s321%20W%20State%20Rd%2084%2C%20Fort%20Lauderdale%2C%20FL%2033315!5e0!3m2!1ses!2sus!4v1604017772732!5m2!1ses!2sus">Ft. Lauderdale</option>
                                        <option value="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3507.9291466381896!2d-81.34408538492146!3d28.45155218248955!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88e77cd3117fd1d1%3A0x61bde943225bfa3c!2s3255%20McCoy%20Rd%2C%20Belle%20Isle%2C%20FL%2032812%2C%20EE.%20UU.!5e0!3m2!1ses-419!2sve!4v1574890509166!5m2!1ses-419!2sve">Orlando</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="arrow-down-location">&nbsp;</div>
        </div>
        <div class="map wow bounceInUp animated" data-wow-offset="100" style="visibility: visible; animation-name: bounceInUp;">
            <div class="mapouter">
                <div class="gmap_canvas">
                    <iframe width="600" height="550" id="gmap_canvas" src="https://maps.google.com/maps?q=3256%20NW%2024th%20St%20Rd%2C%20Miami%20Fl%2033142&t=&z=11&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
                <style>
                    .mapouter{
                        position:relative;
                        text-align:right;
                        height:550px;
                        width:100%;
                    }.gmap_canvas {
                        overflow:hidden;
                        background:none!important;
                        height:550px;
                        width:100%;
                    }
                </style>
            </div>
        </div>
    </section>

    <script type="text/javascript">

        jQuery(document).ready(function( $ ){
            $('#location-map-select').on('change', function() {
              $('#gmap_canvas').attr('src', this.value);
              $('#gmap_canvas').reload();
            });

        });

    </script>

    <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
}

add_shortcode('hq_location_map', 'hq_location_map');
