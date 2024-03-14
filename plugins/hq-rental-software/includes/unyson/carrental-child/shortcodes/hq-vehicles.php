<?php

/**
 * HQ Vehicles Shortcode
 *
 */

if (!defined('FW')) {
    die('Forbidden');
}

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleClass;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesLocations;

function hq_vehicles()
{

    $id = uniqid('tab-cont-');
    $vehicleClass = uniqid('vehicle-');
    $select = uniqid('select-');

    $args = array(
            'post_type'        => 'hqwp_veh_classes',
            'post_status'      => 'publish',
            'posts_per_page'   => -1
            );

    $query = new WP_QUERY($args);

    $locations_obj = new HQRentalsQueriesLocations();


    ob_start();
    ?>

    <div id="vehicles" class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (get_locale() == 'en_US') : ?>
                    <h2 class="title wow fadeInDown" data-wow-offset="200">Vehicle Models - Our rental fleet at a glance</h2>
                <?php elseif (get_locale() == 'es_ES') : ?>
                    <h2 class="title wow fadeInDown" data-wow-offset="200">Vehículos</h2>
                <?php else : ?>
                    <h2 class="title wow fadeInDown" data-wow-offset="200">Veículos</h2>
                <?php endif; ?>         
            </div>

            <!-- Vehicle nav start -->
            <div class="col-md-4 vehicle-nav-row wow fadeInUp" data-wow-offset="100">
                <div id="<?php echo $vehicleClass ?>-nav-container" class="vehicle-container <?php echo $select; ?>">
                    <ul class="<?php echo $vehicleClass ?>-nav vehicle-tab-nav">
                        <?php
                        $counter = 1;

                                    $vehicle_query = new HQRentalsQueriesVehicleClasses();
                                    $vehicle_classes = $vehicle_query->allVehicleClasses();

                                    // Order array of objects by order id
                                    $sort = array();
                        foreach ($vehicle_classes as $i => $obj) {
                            $sort[$i] = $obj->{'order'};
                        }

                                    array_multisort($sort, SORT_ASC, $vehicle_classes);

                        foreach ($vehicle_classes as $vehicle_obj) :
                            $vehicle_name = $vehicle_obj->name;
                            $vehicle_id = $vehicle_obj->id;
                            $vehicle_brandId = $vehicle_obj->brandId;
                            $price = $vehicle_obj->getCheapestPriceInterval()->formatPrice(2);

                            if ($vehicle_obj->brandId == 1 && $vehicle_obj->active == 1) {
                                ?>
                                                <li <?php echo ($counter == 1) ? 'class="active"' : ''; ?>>
                                                      <a href="#<?php echo $id . '-' . $counter; ?>"
                                                         data-vehicle-id="<?php echo $vehicle_id; ?>"
                                                         data-vehicle-price="<?php echo $price; ?>"
                                                         data-brand-id="<?php echo $vehicle_brandId; ?>"
                                                      >
                                                          <?php echo $vehicle_name;?>
                                                      </a>
                                                    <span class="active">&nbsp;</span>
                                                </li>
                                                <?php
                                                $counter++;
                            }

                            ?>
                        <?php	endforeach; ?>
                    </ul>
                </div>
                <div class="<?php echo $vehicleClass ?>-nav-control vehicle-scroll hidden-sm">
                    <a class="<?php echo $vehicleClass ?>-nav-scroll vehicle-scroll" data-direction="up" href="#"><i class="fa fa-chevron-up"></i></a>
                    <a class="<?php echo $vehicleClass ?>-nav-scroll vehicle-scroll" data-direction="down" href="#"><i class="fa fa-chevron-down"></i></a>
                </div>
            </div>
            <!-- Vehicle nav end -->

        <?php

        $cnt = 1;
        foreach ($vehicle_classes as $vehicle) :
            if ($vehicle->brandId == 1 && $vehicle->active == 1) {
                $image_src = $vehicle->publicImageLink; // vehicle image

                ?>
                        <!-- Vehicle 1 data start -->
                        <div class="<?php echo $vehicleClass ?>-data" id="<?php echo $id . '-' . $cnt; ?>">
                            <div class="col-md-5 ">
                                <div class="vehicle-img">
                                    <img class="img-responsive" src="<?php echo $image_src; ?>" alt="Vehicle">
                                </div>
                            </div>
                        </div>
                        <!-- Vehicle 1 data end -->

                        <?php
                        $cnt++;
            }
        endforeach;

        ?>
                <ul class="brand-1-locations" style="display: none;">
                    <?php foreach ($locations_obj->allLocations() as $location) { ?>    
                        <li value="<?php echo $location->id; ?>"><?php echo $location->name; ?></li>
                    <?php } ?>
                </ul>
                <ul class="brand-2-locations" style="display: none;">>
                    <?php foreach ($locations_obj->allLocations() as $location) { ?>    
                        <li value="<?php echo $location->id; ?>"><?php echo $location->name; ?></li>
                    <?php } ?>
                </ul>

                <div class="col-md-3">
                    <div class="vehicle-price"><span class="hq-vehicle-price"></span>
                        <?php if (get_locale() == 'en_US') : ?>
                            <span class="info"> per day</span>
                        <?php elseif (get_locale() == 'es_ES') : ?>
                            <span class="info"> por día</span>
                        <?php else : ?>
                            <span class="info"> por dia</span>
                        <?php endif; ?>     
                    </div>
                    <table class="table vehicle-features">
                        <tr>
                            <td><i class=""></i></td>
                            <td></td>
                        </tr>
                    </table>

                    <form action="/reservations-miami" method="post" id="hq-booking-form-tabs">
                        <div class="input-group pick-up">
                            <div class="styled-select-car pickuplocation">
                                <select name="pick_up_location" id="pick_up_location" class="tabs-locations select-pickup" style="width: 100%;">
                                    <?php if (get_locale() == 'en_US') : ?>
                                        <option>Select Pick-up Location</option>
                                    <?php elseif (get_locale() == 'es_ES') : ?>
                                        <option>Seleccionar Lugar de Entrega</option>
                                    <?php else : ?>
                                        <option>Escolha Local de Retirada</option>  
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="input-group pick-up">
                            
                            <div class="styled-select-car pickuplocation">
                                <select name="return_location" id="return_location" class="select-return" style="width: 100%;">
                                    <?php if (get_locale() == 'en_US') : ?>
                                        <option>Select Drop-off Location</option>
                                    <?php elseif (get_locale() == 'es_ES') : ?>
                                        <option>Seleccionar Lugar de Retorno</option>
                                    <?php else : ?>
                                        <option>Escolha Local de Devolução</option> 
                                    <?php endif; ?>
                                          <?php foreach ($locations_obj->allLocations() as $location) { ?>
                                                <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                          <?php }  ?>
                                </select>
                            </div>
                        </div>

                        <!-- Pick-up date/time start -->
                        <div class="datetime pick-up">
                            <div class="date pull-left">
                                <div class="input-group">
                                    <?php if (get_locale() == 'en_US') : ?>
                                        <input
                                            type="text"
                                            name="pick_up_date" id="pick_up_date"
                                            class="form-control datepicker pick-up-date"
                                            placeholder="Select Pick-up Date"
                                            readonly
                                        >
                                    <?php elseif (get_locale() == 'es_ES') : ?>
                                        <input
                                            type="text"
                                            name="pick_up_date"
                                            id="pick_up_date"
                                            class="form-control datepicker pick-up-date"
                                            placeholder="Fecha de Entrega"
                                            readonly
                                        >
                                    <?php else : ?>
                                        <input
                                            type="text"
                                            name="pick_up_date"
                                            id="pick_up_date"
                                            class="form-control datepicker pick-up-date"
                                            placeholder="Data de Retirada"
                                            readonly
                                        >
                                    <?php endif; ?>             
                                </div>
                            </div>
                            <div class="time pull-right">
                                <div class="styled-select-time">
                                    <select name="pick_up_time" id="pick-up-time">
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
                                    <?php if (get_locale() == 'en_US') : ?>
                                        <input
                                            type="text"
                                            name="return_date"
                                            id="return_date"
                                            class="form-control datepicker return-date"
                                            placeholder="Select Return Date"
                                            readonly
                                        >
                                    <?php elseif (get_locale() == 'es_ES') : ?>
                                        <input
                                            type="text"
                                            name="return_date"
                                            id="return_date"
                                            class="form-control datepicker return-date"
                                            placeholder="Fecha de Retorno"
                                            readonly
                                        >
                                    <?php else : ?>
                                        <input
                                            type="text"
                                            name="return_date"
                                            id="return_date"
                                            class="form-control datepicker return-date"
                                            placeholder="Data de Devolução"
                                            readonly>
                                    <?php endif; ?>         
                                </div>
                            </div>
                            <div class="time pull-right">
                                <div class="styled-select-time">
                                    <select name="return_time" id="drop-off-time">
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
                        

                        <input type="hidden" name="terms_and_conditions" value="1">
                        <input type="hidden" name="vehicle_class_id" value="<?php echo $vehicle->id; ?>">
                        <input type="hidden" name="reservation_type" value="short">
                        
                        <!-- Submit start -->
                        <?php if (get_locale() == 'en_US') : ?>
                            <button type="submit" class="submit reserve-button">
                                <span class="glyphicon glyphicon-calendar"></span><?php _e('Reserve now', 'fw') ?>
                            </button>
                        <?php elseif (get_locale() == 'es_ES') : ?>
                            <button type="submit" class="submit reserve-button">
                                <span class="glyphicon glyphicon-calendar"></span><?php _e('Reservar', 'fw') ?>
                            </button>
                        <?php else : ?>
                            <button type="submit" class="submit reserve-button">
                                <span class="glyphicon glyphicon-calendar"></span><?php _e('Reserva', 'fw') ?>
                            </button>
                        <?php endif; ?>  
                        
                        <!-- Submit end -->
                    </form>
                    
                </div>
            
            

        </div>
    </div>

    <?php if (wp_is_mobile()) { ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var waitForEl = function(selector, callback) {
                  if (jQuery(selector).length) {
                    callback();
                  } else {
                    setTimeout(function() {
                      waitForEl(selector, callback);
                    }, 100);
                  }
                };

                waitForEl('.vehicle-container select', function() {
                  $('.vehicle-container select').on('change', function() {
                    var vehicle_index = $(this).prop('selectedIndex') + 1;
                    $('.vehicle-tab-nav li:nth-child(' + vehicle_index + ') a').click();
                  });
                });
            });
        </script>

    <?php } ?>


    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            // Vehicles Items  
            //-------------------------------------------------------------

            var vehicleActiveitem = $('.vehicle-tab-nav li a');

              var location_url_vehicles_1 = $('.location-url-1').text();
              var location_url_vehicles_2 = $('.location-url-2').text();

            $(vehicleActiveitem).click(function () {
                $('#hq-booking-form-tabs input[name="vehicle_class_id"]').val($(this).attr("data-vehicle-id"));
                $('.select-pickup').find('option').not(':first').remove();
                //$('.select-return').find('option').not(':first').remove();
                if($(this).attr("data-brand-id") == 1) {
                    $('#hq-booking-form-tabs').attr('action', location_url_vehicles_1);
                    $('.brand-1-locations').children('li').each(function() {
                        $('.tabs-locations').append('<option value="' + $(this).val() + '">' + $(this).text() + '</option>');
                    });
                } else {

                    $('#hq-booking-form-tabs').attr('action', location_url_vehicles_2);
                    $('.brand-2-locations').children('li').each(function() {
                        $('.tabs-locations').append('<option value="' + $(this).val() + '">' + $(this).text() + '</option>');
                    });
                }

                $('.hq-vehicle-price').fadeOut();
                setTimeout(function () {
                    $('.hq-vehicle-price').text('$' + $(this).attr("data-vehicle-price"));
                }.bind(this), 600);
                $('.hq-vehicle-price').fadeIn(900);
            });

            $('.vehicle-tab-nav li.active a').click();


            // Vehicles Tabs functionality  
            //-------------------------------------------------------------
            $(".<?php echo $vehicleClass ?>-data").hide();
            var activeVehicleData = $(".<?php echo $vehicleClass ?>-nav .active a").attr("href");
            $(activeVehicleData).show();
            $('.<?php echo $vehicleClass ?>-nav-scroll').click(function () {
                var topPos = 0;
                var direction = $(this).data('direction');
                var scrollHeight = $('.<?php echo $vehicleClass ?>-nav li').height() + 1;
                var navHeight = $('#<?php echo $vehicleClass ?>-nav-container').height() + 1;
                var actTopPos = $(".<?php echo $vehicleClass ?>-nav").position().top;
                var navChildHeight = $('#<?php echo $vehicleClass ?>-nav-container').find('.<?php echo $vehicleClass ?>-nav').height();
                var x = -(navChildHeight - navHeight);
                var fullHeight = 0;
                $('.<?php echo $vehicleClass ?>-nav li').each(function () {
                    fullHeight += scrollHeight;
                });
                navHeight = fullHeight - navHeight + scrollHeight;
                // Scroll Down
                if ((direction == 'down') && (actTopPos > x) && (-navHeight <= (actTopPos - (scrollHeight * 2)))) {
                    topPos = actTopPos - scrollHeight;
                    $(".<?php echo $vehicleClass ?>-nav").css('top', topPos);
                }
                // Scroll Up
                if (direction == 'up' && 0 > actTopPos) {
                    topPos = actTopPos + scrollHeight;
                    $(".<?php echo $vehicleClass ?>-nav").css('top', topPos);
                }
                return false;
            });

            $(".<?php echo $vehicleClass ?>-nav li").on("click", function () {

                $(".<?php echo $vehicleClass ?>-nav .active").removeClass("active");
                $(this).addClass('active');

                $(activeVehicleData).fadeOut("slow", function () {
                    activeVehicleData = $(".<?php echo $vehicleClass ?>-nav .active a").attr("href");
                    $(activeVehicleData).fadeIn("slow", function () {
                    });
                });

                return false;
            });

            // Vehicles Responsive Nav  
            //-------------------------------------------------------------
            var windowWidth = $(window).width();
            if (windowWidth < 990) {
                $("<div />").appendTo(".<?php echo $select ?>").addClass("<?php echo $select ?>select-vehicle-data");
                $("<select />").appendTo(".<?php echo $select ?>").addClass("<?php echo $select ?>-data-select");
                $(".<?php echo $select ?> a").each(function () {
                    var el = $(this);
                    $("<option />", {
                        "value": el.attr("href"),
                        "text": el.text()
                    }).appendTo(".<?php echo $select ?> select");
                });

                $(".<?php echo $select ?>-data-select").change(function () {
                    $(activeVehicleData).fadeOut("slow", function () {
                        activeVehicleData = $(".<?php echo $select ?>-data-select").val();
                        $(activeVehicleData).fadeIn("slow", function () {
                        });
                    });

                    return false;
                });
            }

            // Initialize Datepicker
            //-------------------------------------------------------------------------------


                var nowTemp = new Date();
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                  var tomorrow = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), (nowTemp.getDate() + 1), 0, 0, 0, 0);
                  var oneweek = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), (nowTemp.getDate() + 8), 0, 0, 0, 0);
                  
                  // Main booking form
                var checkinmain = $('.pick-up-date-main').datepicker({
                    format: DateFormat,
                    weekStart: 1,
                    onRender: function (date) {
                        return date.valueOf() < now.valueOf() ? 'disabled' : '';
                    }
                }).on('changeDate', function (ev) {
                    if (ev.date.valueOf() > checkoutmain.date.valueOf()) {
                        var newDate = new Date(ev.date);
                        newDate.setDate(newDate.getDate() + 7);
                        checkoutmain.setValue(newDate);
                    }
                    checkinmain.hide();
                    $('.return-date-main')[0].focus();
                }).data('datepicker');
                var checkoutmain = $('.return-date-main').datepicker({
                    format: DateFormat,
                    weekStart: 1,
                    onRender: function (date) {
                        return date.valueOf() < checkinmain.date.valueOf() ? 'disabled' : '';
                    }
                }).on('changeDate', function (ev) {
                    checkoutmain.hide();
                }).data('datepicker');

                  // Other booking form
                  var checkin = $('.pick-up-date').datepicker({
                      format: DateFormat,
                      weekStart: 1,
                      onRender: function (date) {
                          return date.valueOf() < now.valueOf() ? 'disabled' : '';
                      }
                  }).on('changeDate', function (ev) {
                      if (ev.date.valueOf() > checkout.date.valueOf()) {
                          var newDate = new Date(ev.date);
                          newDate.setDate(newDate.getDate() + 7);
                          checkout.setValue(newDate);
                      }
                      checkin.hide();
                      $('.return-date')[0].focus();
                  }).data('datepicker');
                  var checkout = $('.return-date').datepicker({
                      format: DateFormat,
                      weekStart: 1,
                      onRender: function (date) {
                          return date.valueOf() < checkin.date.valueOf() ? 'disabled' : '';
                      }
                  }).on('changeDate', function (ev) {
                      checkout.hide();
                  }).data('datepicker');

                  // Main booking form
                  $('.pick-up-date-main').datepicker('setValue', tomorrow);
                  $('.return-date-main').datepicker('setValue', oneweek);

                  // Other booking form
                  $('.pick-up-date').datepicker('setValue', tomorrow);
                  $('.return-date').datepicker('setValue', oneweek);
        });


    </script>

    <style type="text/css">
        #hq-booking-form-tabs .datetime {
            display: flex;
        }
        #hq-booking-form-tabs .date {
            width: 60%;
        }
        #hq-booking-form-tabs .time {
            width: 40%;
        }
        #hq-booking-form-tabs .styled-select-car select {
            border: none;
            font-size: 15px;
            font-weight: normal;
            width: 100%;
            background-image: none;
            background: #fff;
            -webkit-appearance: none;
            padding: 9px 10px;
            height: 51px;
            outline: none;
            border: 2px solid #efe9e9;
            height: 55px;
            position: relative;
        }
        #hq-booking-form-tabs .reserve-button {
            width: 100%;
        }
        #hq-booking-form-tabs .datetime input, .styled-select-time select {
            border-radius: 0;
            color: #2e54a4;
            font-size: 15px;
            padding-left: 15px;
            box-shadow: none;
            background: none;
            -webkit-appearance: none;
            height: 35px;
            width: 100%;
            border: 2px solid #efe9e9;
        }
        #hq-booking-form-tabs .datetime input::placeholder {
            color: #2e54a4;
        }
        #hq-booking-form-tabs .input-group {
            margin-bottom: 10px;
            width: 100%;
        }
        #hq-booking-form-tabs .datetime .input-group {
            margin-right: 5px;
        }
    </style>

    <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
}


add_shortcode('hq_vehicles', 'hq_vehicles');