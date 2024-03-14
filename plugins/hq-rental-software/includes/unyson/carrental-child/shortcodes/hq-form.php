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

function hq_form()
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
                    <div class="col-md-6 col-xs-12 pull-right">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <!-- Wrapper for slides start -->
                            <div class="carousel-inner">
                                <?php   if (defined('FW')) :    ?>
                                    <?php if (get_locale() == 'en_US') : ?>
                                        <div class="item">
                                            <h1 class="title">AFFORDABLE RENTAL CARS</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/ford-mustang-black.png" class="img-responsive" alt="car1">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">NO HIDDEN FEES</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/jeep-black.png" class="img-responsive" alt="car2">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">CUSTOMER SUPPORT 24/7</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/toyota-corolla-red.png" class="img-responsive" alt="car3">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">BOOKING FOR MORE THAN 7 DAYS? ASK FOR A DISCOUNT!</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/hyundai-accent-blue.png" class="img-responsive" alt="car4">
                                            </div>
                                        </div>
                                    <?php elseif (get_locale() == 'es_ES') : ?>
                                        <div class="item">
                                            <h1 class="title">Alquiler de vehículos accesible</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/ford-mustang-black.png" class="img-responsive" alt="car1">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">SIN CARGOS OCULTOS</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/jeep-black.png" class="img-responsive" alt="car2">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">SOPORTE AL CLIENTE 24/7</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/toyota-corolla-red.png" class="img-responsive" alt="car3">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">¿RESERVAS DE MÁS DE 7 DÍAS? PREGUNTE POR UN DESCUENTO!</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/hyundai-accent-blue.png" class="img-responsive" alt="car4">
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="item">
                                            <h1 class="title">Aluguer de carros a preços acessíveis</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/ford-mustang-black.png" class="img-responsive" alt="car1">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">NÃO HÁ HIDDEN FEES</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/jeep-black.png" class="img-responsive" alt="car2">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">APOIO AO CLIENTE 24/7</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/toyota-corolla-red.png" class="img-responsive" alt="car3">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <h1 class="title">RESERVAS PARA MAIS DE 7 DIAS? Pedir um desconto!</h1>
                                            <div class="car-img">
                                                <img src="/wp-content/uploads/2019/06/hyundai-accent-blue.png" class="img-responsive" alt="car4">
                                            </div>
                                        </div>
                                    <?php endif; ?>                         
                                <?php	endif;    ?>
                            </div>
                            <!-- Wrapper for slides end -->

                            <!-- Slider Controls start -->
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                            <!-- Slider Controls end -->
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 pull-left">
                        <div class="reservation-form-shadow">
                            <div class="location-url-helper" style="display: none;">
                                <?php if (get_locale() == 'en_US') : ?>
                                        <span class="location-url-1"><?php echo '/reservations-miami' ?></span>
                                        <span class="location-url-2"><?php echo '/reservations-orlando' ?></span>
                                <?php elseif (get_locale() == 'es_ES') : ?>
                                        <span class="location-url-1"><?php echo '/es/reservaciones-miami' ?></span>
                                        <span class="location-url-2"><?php echo '/es/reservaciones-orlando' ?></span>
                                <?php else : ?>
                                        <span class="location-url-1"><?php echo '/pt/reserva-miami' ?></span>
                                        <span class="location-url-2"><?php echo '/pt/reserva-orlando' ?></span>
                                <?php endif; ?>
                            </div>

                            <form action="/reservations-miami/" method="post" id="hq-booking-form">

                                <div class="alert alert-danger hidden" id="car-select-form-msg">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong><?php _e('All fields required!', 'fw') ?></strong> 
                                </div>

                                <script type="text/javascript">
                                        
                                    jQuery(document).ready(function( $ ){

                                    // Branch locations

                                        //$('#branch-location').val(2).trigger('change');
                                        
                                    });

                                </script>
                                

                                <!-- Pick-up location start -->  
                                <div class="brands">
                                    <div class="input-group pick-up">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span>
                                        <?php if (get_locale() == 'en_US') :
                                                _e('Pick-up', 'fw');
                                        elseif (get_locale() == 'es_ES') :
                                                _e('Entrega', 'fw');
                                        else :
                                                _e('Retirada', 'fw');
                                        endif; ?>
                                         </span>
                                        <div class="styled-select-car pickuplocation">
                                            <select name="pick_up_location" id="pick_up_location" class="pickupselect">
                                                <?php if (get_locale() == 'en_US') : ?>
                                                    <option value="" disabled selected>Select Pick-up Location</option>
                                                <?php elseif (get_locale() == 'es_ES') : ?>
                                                    <option value="" disabled selected>Seleccionar Lugar de Entrega</option>
                                                <?php else : ?>
                                                    <option value="" disabled selected>Escolha Local de Retirada</option>   
                                                <?php endif; ?>
                                                
                                            <?php
                                                // Locations
                                            foreach ($queryLocations->allLocations() as $location) {
                                                if ($location->id == 2) { ?>
                                                        <option value="<?php echo $location->id; ?>" selected><?php echo $location->name; ?></option>
                                                <?php } else {  ?>
                                                        <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                                <?php }
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pick-up location end -->

                                <!-- Drop-off location start -->
                                <div class="location return brands">
                                    <div class="input-group" style="margin-top: 10px;">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span>
                                        <?php if (get_locale() == 'en_US') :
                                                _e('Drop-off', 'fw');
                                        elseif (get_locale() == 'es_ES') :
                                                _e('Retorno', 'fw');
                                        else :
                                                _e('Devolução', 'fw');
                                        endif; ?> 
                                        </span>
                                        <div class="styled-select-car pickuplocation">
                                            <select name="return_location"  id="return_location" class="returnselect">
                                                <?php if (get_locale() == 'en_US') : ?>
                                                    <option value="" disabled selected>Select Drop-off Location</option>
                                                <?php elseif (get_locale() == 'es_ES') : ?>
                                                    <option value="" disabled selected>Seleccionar Lugar de Retorno</option>
                                                <?php else : ?>
                                                    <option value="" disabled selected>Escolha Local de Devolução</option>  
                                                <?php endif; ?>
                                                
                                            <?php
                                                // Locations
                                            foreach ($queryLocations->allLocations() as $location) {
                                                if ($location->id == 2) { ?>
                                                        <option value="<?php echo $location->id; ?>" selected><?php echo $location->name; ?></option>
                                                <?php } else {  ?>
                                                        <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                                <?php }
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
                                    <span class="input-group-addon pixelfix"><span class="glyphicon glyphicon-calendar"></span>
                                    <?php if (get_locale() == 'en_US') :
                                                _e('Pick-up', 'fw');
                                    elseif (get_locale() == 'es_ES') :
                                                _e('Entrega', 'fw');
                                    else :
                                                _e('Retirada', 'fw');
                                    endif; ?>
                                    </span>
                                    <input
                                        type="text"
                                        name="pick_up_date"
                                        id="pick_up_date"
                                        class="form-control pick-up-date-main"
                                        placeholder="<?php echo fw_get_db_settings_option('date_format'); ?>"
                                        readonly="readonly"
                                    />
                                </div>
                            </div>
                            <div class="time pull-right">
                                <div class="styled-select-time">
                                    <select name="pick_up_time" id="pick-up-time">
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
                                        <option value="11:00AM" selected>11:00 AM</option>
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
                                    <span class="input-group-addon pixelfix"><span class="glyphicon glyphicon-calendar"></span>
                                    <?php if (get_locale() == 'en_US') :
                                                _e('Drop-off', 'fw');
                                    elseif (get_locale() == 'es_ES') :
                                                _e('Retorno', 'fw');
                                    else :
                                                _e('Devolução', 'fw');
                                    endif; ?> 
                                    </span>
                                    <input
                                        type="text"
                                        name="return_date"
                                        id="return_date"
                                        class="form-control datepicker return-date-main"
                                        placeholder="<?php echo fw_get_db_settings_option('date_format'); ?>"
                                        readonly="readonly"
                                    />
                                </div>
                            </div>
                            <div class="time pull-right">
                                <div class="styled-select-time">
                                    <select name="return_time" id="drop-off-time">
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
                                        <option value="11:00AM" selected>11:00 AM</option>
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
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- Drop-off date/time end -->
                        <input type="hidden" name="reservation_type" value="short">

                        <input type="hidden" name="terms_and_conditions" value="1" />

                        <?php if (get_locale() == 'en_US') : ?>
                            <button type="submit" class="submit" ><?php _e('continue reservation', 'fw') ?></button>
                        <?php elseif (get_locale() == 'es_ES') : ?>
                            <button type="submit" class="submit" ><?php _e('continuar reservación', 'fw') ?></button>
                        <?php else : ?>
                            <button type="submit" class="submit" ><?php _e('continuar reserva', 'fw') ?></button> 
                        <?php endif; ?>

                        
                        </div>
                        </form>

                    </div>
                </div>
                <style>
                    .xdsoft_current{
                        background: #2E54A4 !important;
                    }
                    .xdsoft_datetimepicker .xdsoft_calendar td:hover, .xdsoft_datetimepicker .xdsoft_timepicker .xdsoft_time_box>div>div:hover{
                        background: #2E54A4 !important;
                    }
                </style>
            </div>
        </div>
    </div>
    </section>
    <div class="arrow-down"></div>
    <!-- Teaser end -->

    <?php
    $html = ob_get_contents();
    ob_end_clean();
    hq_load_datepicker_assets();
    return $html;
}

add_shortcode('hq_form', 'hq_form');

