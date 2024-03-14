<?php

use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;

function hq_reservation_form()
{
    $query = new HQRentalsQueriesVehicleClasses();
    $vehicles = $query->allVehicleClasses();
    ?>
    <section id="hq-reservation-form" class="elementor-section elementor-top-section elementor-element
    elementor-element-24d39dd elementor-section-boxed elementor-section-height-default elementor-section-height-default"
             data-element_type="section">
        <div class="elementor-container elementor-column-gap-no">
            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element
            elementor-element-c82dfe2" data-id="c82dfe2" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-af18c2b elementor-widget elementor-widget-templines-search"
                         data-id="af18c2b" data-element_type="widget" data-widget_type="templines-search.default">
                        <div class="elementor-widget-container">
                            <form method="GET" action="/reservations/" >
                                <div class="l-section l-section--container c-filter c-filter--col-2 c-filter--layout-1"
                                     style="color:#ffffff;background-color:#0b4453;">
                                    <div class="c-filter__col-1">
                                        <div class="c-filter__wrap">
                                            <div class="c-filter__field">
                                                <div class="c-filter__title">When?</div>
                                                <div class="c-filter__element">
                                                    <input type="hidden" class="js-filter-date-start " name="pick_up_date" value="14-04-2021">
                                                    <input type="hidden" class="js-filter-date-end " name="return_date" value="01-05-2021">
                                                    <input type="hidden" name="pick_up_time" value="10:00">
                                                    <input type="hidden"  name="return_time" value="10:00">
                                                    <input type="hidden"  name="pick_up_location" value="2">
                                                    <input type="hidden"  name="return_location" value="2">
                                                    <input type="hidden"  name="target_step" value="3">
                                                    <input type="text" class="h-cb c-filter__date js-filter-date-range" value="" readonly=""
                                                           style="color:rgba(255, 255, 255, 0.5)!important;
                                                           background-color:rgb(28, 81, 95)!important;">
                                                </div>
                                            </div>

                                            <div class="c-filter__field">
                                                <div class="c-filter__title">Type?</div>
                                                <div class="c-filter__element">
                                                    <select id="vehicle-class" name="vehicle_class_id"
                                                            class="h-cb c-filter__select styled js-filter-type hasCustomSelect"
                                                            style="color: rgba(255, 255, 255, 0.5) !important;
                                                            background-color: rgb(28, 81, 95) !important;
                                                            appearance: menulist-button; position: absolute; opacity: 0;
                                                            height: 50px; font-size: 16px; width: 439px;">
                                                        <option value="">Select type</option>
                                                        <?php foreach ($vehicles as $vehicle) : ?>
                                                            <option value="<?php echo $vehicle->id; ?>">
                                                                <?php echo $vehicle->getLabel(); ?></option>
                                                        <?php endforeach; ?>
                                                    </select><span class="c-custom-select"
                                                                   style="color: rgba(255, 255, 255, 0.5) !important;
                                                                   background-color: rgb(28, 81, 95) !important;
                                                                   display: inline-block;">
                                                        <span class="c-custom-selectInner"
                                                            style="width: 439px; display: inline-block;" id="vehicle-tag">Select type</span>
                                                        <i class="ip-select c-custom-select__angle"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="c-filter__col-2">
                                        <button type="submit" class="c-button c-button--fullwidth js-filter-button">Search</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        jQuery(document).ready(function(){
            jQuery("#vehicle-class").on("change",function(event){
                console.log('change');
                var label = jQuery("#vehicle-class :selected").text();
                console.log(label);
                jQuery("#vehicle-tag").html(label);
            })
        });

    </script>
    <?php
}
add_shortcode('hq_reservation_form', 'hq_reservation_form');
