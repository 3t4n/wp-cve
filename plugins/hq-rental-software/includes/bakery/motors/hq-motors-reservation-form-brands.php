<?php

/*
 * HQ Rental Reservation Form - Support For Brands Automatically
 * Author: Miguel Faggioni
 */

vc_map(
    array(
        'name'                    => __('HQ Rental Reservation Form Multiple Brands', 'js_composer'),
        'base'                    => 'hq_motors_reservation_form_brands',
        'content_element'         => true,
        'show_settings_on_create' => true,
        'description'             => __('HQ Rental Reservation Form Integration', 'js_composer'),
        'icon'                    =>    HQ_MOTORS_VC_SHORTCODES_ICON,
        'params' => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => __("Alignment", "my-text-domain"),
                "param_name" => "alignment",
                "value" => array(
                    'left'  =>  'text-left',
                    'right' =>  'text-right'
                ),
                "description" => __("Form Alignment", "my-text-domain")
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Pick Up Location Label', 'motors'),
                'param_name' => 'pick_up_location_label',
                'value' => '',
                'description' => esc_html__('Enter the pick up Location Label')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Pick Up Location Placeholder', 'motors'),
                'param_name' => 'pick_up_location_placeholder',
                'value' => '',
                'description' => esc_html__('Enter the pick up Location Placeholder')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Return Location Label', 'motors'),
                'param_name' => 'return_location_label',
                'value' => '',
                'description' => esc_html__('Enter the return Location Label')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Return Location Placeholder', 'motors'),
                'param_name' => 'return_location_placeholder',
                'value' => '',
                'description' => esc_html__('Enter the return Location Placeholder')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Pick Up Date Label', 'motors'),
                'param_name' => 'pick_up_date_label',
                'value' => '',
                'description' => esc_html__('Enter the Pick Up Date Label')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Pick Up Date Placeholder', 'motors'),
                'param_name' => 'pick_up_date_placeholder',
                'value' => '',
                'description' => esc_html__('Enter the Pick Up Date Placeholder')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Return Date Label', 'motors'),
                'param_name' => 'return_date_label',
                'value' => '',
                'description' => esc_html__('Enter the Return Date Label')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Return Date Placeholder', 'motors'),
                'param_name' => 'return_date_placeholder',
                'value' => '',
                'description' => esc_html__('Enter the Return Date Placeholder')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Button Text', 'motors'),
                'param_name' => 'button_text',
                'value' => '',
                'description' => esc_html__('Enter the Button Text')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Brand Selector Label', 'motors'),
                'param_name' => 'pick_brand_label',
                'value' => '',
                'description' => esc_html__('Select Label for Brand Field')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Brand Selector Placeholder', 'motors'),
                'param_name' => 'pick_brand_placeholder',
                'value' => '',
                'description' => esc_html__('Select Placeholder for Brand Field')
            )
        )
    )
);

class WPBakeryShortCode_hq_motors_reservation_form_brands extends WPBakeryShortCode
{
    protected function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'pick_up_location_label'            =>  '',
            'pick_up_location_placeholder'      =>  '',
            'pick_up_date_label'                =>  '',
            'pick_up_date_placeholder'          =>  '',
            'return_date_label'                 =>  '',
            'return_date_placeholder'           =>  '',
            'button_text'                       =>  '',
            'alignment'                         =>  'text-left',
            'action_link'                       =>  '',
            'pickup_locations'                  =>  '',
            'return_locations'                  =>  '',
            'return_location_label'             =>  '',
            'return_location_placeholder'       =>  '',
            'delivery_location_label'           =>  '',
            'delivery_location_placeholder'     =>  '',
            'collection_location_label'         =>  '',
            'collection_location_placeholder'   =>  '',
            'multiple_brands'                   =>  false,
            'brands'                            =>  [],
            'pick_brand_label'                  =>  '',
            'pick_brand_placeholder'            =>  ''
        ), $atts));

        $brands = caag_hq_get_brands_for_display();
        $js_data = array();
        foreach ($brands as $brand) {
            $new_brand = new stdClass();
            $new_brand->id = $brand->id;
            $new_brand->name = $brand->name;
            $new_brand->page_link = $brand->page_link;
            $new_brand->locations = caag_hq_get_active_locations_by_brand_id_for_display($brand->id);
            $js_data[$brand->id] = $new_brand;
        }
        wp_enqueue_style('hq-motors-datetimepicker-css');
        wp_enqueue_script('hq-motors-datetimepicker-js');
        wp_enqueue_script('moment-js');
        wp_enqueue_script('hq-motors-reservations-brands-js');
        wp_localize_script('hq-motors-reservations-brands-js', 'hq_motors_brands_data', $js_data);
        ?>
        <div class="stm_rent_car_form_wrapper caag-book-form-style style_1 <?php echo $alignment; ?>">
            <div class="stm_rent_car_form">
                <form id="hq-brands-form" action="<?php echo $brands[0]->page_link; ?>" method="post">
                    <h4><?php echo $pick_brand_label; ?></h4>
                    <div class="stm_rent_form_fields" style="margin-bottom: 15px;">
                        <div class="stm_pickup_location">
                            <i class="stm-service-icon-pin"></i>
                            <select id="hq-brands-form-selection" name="pick_up_location"
                                    data-class="stm_rent_location" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                <?php foreach ($brands as $brand) : ?>
                                    <option value="<?php echo $brand->id; ?>"><?php echo $brand->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <h4><?php echo $pick_up_location_label; ?></h4>
                    <div class="stm_rent_form_fields">
                        <div class="stm_pickup_location">
                            <i class="stm-service-icon-pin"></i>
                            <select id="hq-brands-pick-up-location" name="pick_up_location"
                                    data-class="stm_rent_location" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                <option value=""><?php echo $pick_up_location_placeholder; ?></option>
                                <?php foreach (caag_hq_get_active_locations_by_brand_id_for_display($brands[0]->id) as $location) : ?>
                                    <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <h4 style="margin-top:18px;"><?php echo $return_location_label; ?></h4>
                    <div class="stm_rent_form_fields">
                        <div class="stm_pickup_location">
                            <i class="stm-service-icon-pin"></i>
                            <select id="hq-brands-return-location" name="return_location"
                                    data-class="stm_rent_location" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                <option value=""><?php echo $return_location_placeholder; ?></option>
                                <?php foreach (caag_hq_get_active_locations_by_brand_id_for_display($brands[0]->id) as $location) : ?>
                                    <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <h4 style="margin-top:18px;"><?php echo $pick_up_date_label; ?></h4>
                        <div class="stm_date_time_input">
                            <div class="stm_date_input">
                                <input type="text" id="hq-pickup-date" class=" active" name="pick_up_date"
                                       placeholder="<?php echo $pick_up_date_placeholder; ?>" required="" readonly="">
                                <i class="stm-icon-date"></i>
                            </div>
                        </div>
                    </div>
                    <h4><?php echo $return_date_label; ?></h4>
                    <div class="stm_rent_form_fields stm_rent_form_fields-drop">
                        <div class="stm_date_time_input">
                            <div class="stm_date_input">
                                <input type="text" id="hq-return-date" class=" active" name="return_date"
                                       placeholder="<?php echo $return_date_placeholder; ?>" required="" readonly="">
                                <i class="stm-icon-date"></i>
                            </div>
                        </div>
                    </div>
                    <button type="submit"><?php echo $button_text; ?><i class="fa fa-arrow-right"></i></button>
                </form>
            </div>
        </div>
        <style>
            .stm-template-car_rental .stm_rent_location .select2-dropdown{
                min-height: 0px;
            }
            #hq-delivery-location-wrapper, #hq-collection-location-wrapper {
                display: none;
            }
            .hq-text-fields{
                padding-left: 37px;
                height: 40px;
                line-height: 40px;
                background-color: #fff;
                border: 0;
            }
        </style>
        <script>
            (function($){
                $(document).ready(function(){
                    $("#hq-pick-brand").on('change',function(){

                    });
                });
            })(jQuery);
        </script>
        <?php
        wp_enqueue_script('moment-js');
        wp_enqueue_script('hq-motor-reservations-brands');
    }
}
