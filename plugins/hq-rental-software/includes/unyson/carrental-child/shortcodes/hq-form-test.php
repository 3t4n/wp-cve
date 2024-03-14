<?php

/**
 * HQ Booking Form
 *
 */

if (!defined('FW')) {
    die('Forbidden');
}

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleClass;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesLocations;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesBrands;

function hq_form_test()
{

    //$temp_query = $query;  // store it

    $args = array(
            'post_type'        => 'hqwp_veh_classes',
            'post_status'      => 'publish'
            );

    $query = new WP_QUERY($args);
    $queryLocations = new HQRentalsQueriesLocations();
    $queryBrands = new HQRentalsQueriesBrands();

    ob_start();

    ?>

    <!-- Teaser start -->
    <section id="<?php echo xs_sectionID(xs_main($post->ID, false)); ?>">
        <div id="teaser">
            <div class="container" >
                <div class="row">
                    <div class="col-md-7 col-xs-12 pull-right">
                    </div>
                    <div class="col-md-5 col-xs-12 pull-left">
                        <div class="reservation-form-shadow">

                            <form action="/reservations-miami/" method="post" id="hq-booking-form">                 

                                <!-- Pick-up location start -->  
                                <div class="location brands brand-">
                                    <div class="input-group pick-up">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span><?php _e('Pick-up', 'fw') ?> </span>
                                        <div class="styled-select-car pickuplocation">
                                            <select name="pick_up_location" id="pick_up_location" class="pickupselect">
                                            <?php
                                                // Locations
                                            foreach ($queryLocations->allLocations() as $location) {
                                                ?>
                                                        <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                                    <?php
                                            }
                                            ?>
                                            </select>   
                                        </div>
                                    </div>
                                </div>
                                <!-- Pick-up location end -->

                                <!-- Drop-off location start -->
                                <div class="location return brands brand-">
                                    <div class="input-group margin-20">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-map-marker"></span><?php _e('Drop-off', 'fw') ?>
                                        </span>
                                        <div class="styled-select-car pickuplocation">
                                            <select name="return_location" id="return_location" class="returnselect">
                                            <?php
                                                // Locations
                                            foreach ($queryLocations->allLocations() as $location) {
                                                ?>
                                                        <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                                    <?php
                                            }
                                            ?>
                                            </select>   
                                        </div>
                                    </div>
                                </div>
                                <!-- Drop-off location end -->             

                                    
                            

                        <!-- Pick-up date/time start -->
                        <div class="datetime pick-up">
                            <div class="date pull-left">
                                <div class="input-group">
                                    <span class="input-group-addon pixelfix"><span class="glyphicon glyphicon-calendar"></span><?php _e('Pick-up', 'fw') ?> </span>
                                    <input
                                            type="text"
                                            name="pick_up_date"
                                            id="pick-up-date"
                                            class="form-control datepicker"
                                            placeholder="<?php echo fw_get_db_settings_option('date_format'); ?>"
                                    >
                                </div>
                            </div>
                            <div class="time pull-right">
                                <div class="styled-select-time">
                                    <select name="pick_up_time" id="">
                                        <option value="12:00 AM">12:00AM</option>
                                        <option value="12:30 AM">12:30AM</option>       
                                        <option value="1:00AM">01:00 AM</option>
                                        <option value="1:30AM">01:30 AM</option>
                                        <option value="2:00AM">02:00 AM</option>
                                        <option value="2:30AM">02:30 AM</option>
                                        <option value="3:00AM">03:00 AM</option>
                                        <option value="3:30AM">03:30 AM</option>
                                        <option value="4:00AM">04:00 AM</option>
                                        <option value="4:30AM">04:30 AM</option>
                                        <option value="5:00AM">05:00 AM</option>
                                        <option value="5:30AM">05:30 AM</option>
                                        <option value="6:00AM">06:00 AM</option>
                                        <option value="6:30AM">06:30 AM</option>
                                        <option value="7:00AM">07:00 AM</option>
                                        <option value="7:30AM">07:30 AM</option>
                                        <option value="8:00AM">08:00 AM</option>
                                        <option value="8:30AM">08:30 AM</option>
                                        <option value="9:00AM">09:00 AM</option>
                                        <option value="9:30AM">09:30 AM</option>
                                        <option value="10:00AM">10:00 AM</option>
                                        <option value="10:30AM">10:30 AM</option>
                                        <option value="11:00AM">11:00 AM</option>
                                        <option value="11:30AM">11:30 AM</option>
                                        <option value="12:00PM">12:00 PM</option>
                                        <option value="12:30PM">12:30 PM</option>
                                        <option value="1:00PM">1:00 PM</option>
                                        <option value="1:30PM">1:30 PM</option>
                                        <option value="2:00PM">2:00 PM</option>
                                        <option value="2:30PM">2:30 PM</option>
                                        <option value="3:00PM">3:00 PM</option>
                                        <option value="3:30PM">3:30 PM</option>
                                        <option value="4:00PM">4:00 PM</option>
                                        <option value="4:30PM">4:30 PM</option>
                                        <option value="5:00PM">5:00 PM</option>
                                        <option value="5:30PM">5:30 PM</option>
                                        <option value="6:00PM">6:00 PM</option>
                                        <option value="6:30PM">6:30 PM</option>
                                        <option value="7:00PM">7:00 PM</option>
                                        <option value="7:30PM">7:30 PM</option>
                                        <option value="8:00PM">8:00 PM</option>
                                        <option value="8:30PM">8:30 PM</option>
                                        <option value="9:00PM">9:00 PM</option>
                                        <option value="9:30PM">9:30 PM</option>
                                        <option value="10:00PM">10:00PM</option>
                                        <option value="10:30PM">10:30PM</option>
                                        <option value="11:00PM">11:00PM</option>
                                        <option value="11:30PM">11:30PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- Pick-up date/time end -->

                        <!-- Drop-off date/time start -->
                        <div class="datetime drop-off">
                            <div class="date pull-left">
                                <div class="input-group">
                                    <span class="input-group-addon pixelfix">
                                        <span class="glyphicon glyphicon-calendar"></span><?php _e('Drop-off', 'fw') ?>
                                    </span>
                                    <input
                                        type="text"
                                        name="return_date"
                                        id="drop-off-date"
                                        class="form-control datepicker"
                                        placeholder="<?php echo fw_get_db_settings_option('date_format'); ?>"
                                    >
                                </div>
                            </div>
                            <div class="time pull-right">
                                <div class="styled-select-time">
                                    <select name="return_time" id="">
                                        <option value="12:00 AM">12:00 AM</option>
                                        <option value="12:30 AM">12:30 AM</option>      
                                        <option value="1:00AM">01:00 AM</option>
                                        <option value="1:30AM">01:30 AM</option>
                                        <option value="2:00AM">02:00 AM</option>
                                        <option value="2:30AM">02:30 AM</option>
                                        <option value="3:00AM">03:00 AM</option>
                                        <option value="3:30AM">03:30 AM</option>
                                        <option value="4:00AM">04:00 AM</option>
                                        <option value="4:30AM">04:30 AM</option>
                                        <option value="5:00AM">05:00 AM</option>
                                        <option value="5:30AM">05:30 AM</option>
                                        <option value="6:00AM">06:00 AM</option>
                                        <option value="6:30AM">06:30 AM</option>
                                        <option value="7:00AM">07:00 AM</option>
                                        <option value="7:30AM">07:30 AM</option>
                                        <option value="8:00AM">08:00 AM</option>
                                        <option value="8:30AM">08:30 AM</option>
                                        <option value="9:00AM">09:00 AM</option>
                                        <option value="9:30AM">09:30 AM</option>
                                        <option value="10:00AM">10:00 AM</option>
                                        <option value="10:30AM">10:30 AM</option>
                                        <option value="11:00AM">11:00 AM</option>
                                        <option value="11:30AM">11:30 AM</option>
                                        <option value="12:00PM">12:00 PM</option>
                                        <option value="12:30PM">12:30 PM</option>
                                        <option value="1:00PM">1:00 PM</option>
                                        <option value="1:30PM">1:30 PM</option>
                                        <option value="2:00PM">2:00 PM</option>
                                        <option value="2:30PM">2:30 PM</option>
                                        <option value="3:00PM">3:00 PM</option>
                                        <option value="3:30PM">3:30 PM</option>
                                        <option value="4:00PM">4:00 PM</option>
                                        <option value="4:30PM">4:30 PM</option>
                                        <option value="5:00PM">5:00 PM</option>
                                        <option value="5:30PM">5:30 PM</option>
                                        <option value="6:00PM">6:00 PM</option>
                                        <option value="6:30PM">6:30 PM</option>
                                        <option value="7:00PM">7:00 PM</option>
                                        <option value="7:30PM">7:30 PM</option>
                                        <option value="8:00PM">8:00 PM</option>
                                        <option value="8:30PM">8:30 PM</option>
                                        <option value="9:00PM">9:00 PM</option>
                                        <option value="9:30PM">9:30 PM</option>
                                        <option value="10:00PM">10:00 PM</option>
                                        <option value="10:30PM">10:30 PM</option>
                                        <option value="11:00PM">11:00 PM</option>
                                        <option value="11:30PM">11:30 PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- Drop-off date/time end -->
                        <input type="hidden" name="reservation_type" value="short">
                        

                        <button type="submit" class="submit" name="submit"><?php _e('continue car reservation', 'fw') ?></button>
                        </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    </section>
    <div class="arrow-down"></div>
    <!-- Teaser end -->

    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

add_shortcode('hq_form_test', 'hq_form_test');

