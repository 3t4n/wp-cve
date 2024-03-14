<?php

/*
 * HQ Rental Reservation Form
 * Author: Miguel Faggioni
 */

vc_map(
    array(
        'name'                    => __('HQ Rental Form Supporting Multiple Brands', 'js_composer'),
        'base'                    => 'hq_reservation_form_brands',
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
                'heading' => esc_html__('Delivery Location Label', 'motors'),
                'param_name' => 'delivery_location_label',
                'value' => '',
                'description' => esc_html__('Enter the Delivery Location Label')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Delivery Location Placeholder', 'motors'),
                'param_name' => 'delivery_location_placeholder',
                'value' => '',
                'description' => esc_html__('Enter the Delivery Location Placeholder')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Custom Location Label', 'motors'),
                'param_name' => 'collection_location_label',
                'value' => '',
                'description' => esc_html__('Enter the Custom Location Label')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Custom Location Placeholder', 'motors'),
                'param_name' => 'collection_location_placeholder',
                'value' => '',
                'description' => esc_html__('Enter the Custom Location Placeholder')
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
                'heading' => esc_html__('Reservation Page URL', 'motors'),
                'param_name' => 'action_link',
                'value' => '',
                'description' => esc_html__('Reservation Page Url')
            ),
            array(
                'type'       => 'param_group',
                'heading'    => __('Pickup Locations', 'js_composer'),
                'param_name' => 'pickup_locations',
                'params'     => array(
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Location Label', 'js_composer'),
                        'param_name'  => 'label',
                        'description' => __('Location Label', 'js_composer'),
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Location Identification', 'js_composer'),
                        'param_name'  => 'id',
                        'description' => __('Integer Number Representing the Location on the System', 'js_composer'),
                        'value'       => ''
                    ),
                )
            ),
            array(
                'type'       => 'param_group',
                'heading'    => __('Return Locations', 'js_composer'),
                'param_name' => 'return_locations',
                'params'     => array(
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Location Label', 'js_composer'),
                        'param_name'  => 'label',
                        'description' => __('Location Label', 'js_composer'),
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Location Identification', 'js_composer'),
                        'param_name'  => 'id',
                        'description' => __('Integer Number Representing the Location on the System', 'js_composer'),
                        'value'       => ''
                    ),
                )
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_html__('Support Multiple Brands', 'motors'),
                'param_name' => 'multiple_brands',
                'value' => '',
                'description' => esc_html__('Reservation Page Url')
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
            ),
            array(
                'type'       => 'param_group',
                'heading'    => __('Brands Page', 'js_composer'),
                'param_name' => 'brands',
                'params'     => array(
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Brand Name', 'js_composer'),
                        'param_name'  => 'name',
                        'description' => __('Brand Name', 'js_composer'),
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Brand Page Link', 'js_composer'),
                        'param_name'  => 'link',
                        'description' => __('Add the Page Url from this Brand', 'js_composer'),
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Brand System Id', 'js_composer'),
                        'param_name'  => 'id',
                        'description' => __('Add HQ Rentals Brand Id', 'js_composer'),
                        'value'       => ''
                    )
                )
            ),
        )
    )
);

class WPBakeryShortCode_hq_reservation_form_brands extends WPBakeryShortCode
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
        $pickup_locations   = json_decode(urldecode($pickup_locations), true);
        $return_locations   = json_decode(urldecode($return_locations), true);
        $brands = json_decode(urldecode($brands), true);
        if (!empty($multiple_brands)) {
            $multiple_brands = true;
        }
        $form_data = array();
        foreach ($brands as $brand) {
            $new_brand = new stdClass();
            $new_brand->id = $brand['id'];
            $new_brand->link = $brand['link'];
            $new_brand->name = $brand['name'];
            $locations = caag_hq_get_active_locations_by_brand_id_for_display($brand['id']);
            foreach ($locations as $location) {
                $new_location = new stdClass();
                $new_location->id = $location->id;
                $new_location->name = $location->name;
                $new_location->is_airport = $location->is_airport;
                $new_location->is_office = $location->is_office;
                $new_brand->location = $new_location;
            }
            $form_data[] = $new_brand;
        }
        wp_localize_script('hq-motors-js', 'hq_rentals_brands', $form_data);
        ?>
        <div class="stm_rent_car_form_wrapper caag-book-form-style style_1 <?php echo $alignment; ?>">
            <div class="stm_rent_car_form">
                <?php if ($multiple_brands) : ?>
                <form id="caag-book-form" action="<?php echo $brands_links[0]['link']; ?>" method="post">
                <?php else : ?>
                    <form id="caag-book-form" action="<?php echo $action_link; ?>" method="post">
                <?php endif; ?>
                        <?php if ($multiple_brands) : ?>
                            <h4><?php echo $pick_brand_label; ?></h4>
                            <div class="stm_rent_form_fields" style="margin-bottom: 15px;">
                                <div class="stm_pickup_location">
                                    <i class="stm-service-icon-pin"></i>
                                    <select id="hq-pick-brand" name="pick_up_location" data-class="stm_rent_location"
                                            tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                        <option><?php echo $pick_brand_placeholder; ?></option>
                                        <?php foreach ($brands as $brand) : ?>
                                            <option value="<?php echo $brand['id']; ?>"><?php echo $brand['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        <h4><?php echo $pick_up_location_label; ?></h4>
                        <div class="stm_rent_form_fields">
                            <div class="stm_pickup_location">
                                <i class="stm-service-icon-pin"></i>
                                <select id="hq-pick-up-location" name="pick_up_location" data-class="stm_rent_location"
                                        tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                    <option value=""><?php echo $pick_up_location_placeholder; ?></option>
                                    <?php foreach ($pickup_locations as $location) : ?>
                                        <option value="<?php echo $location['id']; ?>"><?php echo $location['label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div id="hq-delivery-location-wrapper">
                            <h4 style="margin-top:18px;"><?php echo $delivery_location_label; ?></h4>
                            <div class="stm_date_time_input">
                                <div class="stm_date_input">
                                    <input type="text" value="" class="hq-text-fields" name="pick_up_location_custom"
                                           placeholder="<?php echo $delivery_location_placeholder; ?>" >
                                    <i class="stm-service-icon-pin"></i>
                                </div>
                            </div>
                        </div>
                        <h4 style="margin-top:18px;"><?php echo $return_location_label; ?></h4>
                        <div class="stm_rent_form_fields">
                            <div class="stm_pickup_location">
                                <i class="stm-service-icon-pin"></i>
                                <select id="hq-return-location" name="return_location" data-class="stm_rent_location"
                                        tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                    <option value=""><?php echo $return_location_placeholder; ?></option>
                                    <?php foreach ($return_locations as $location) : ?>
                                        <option value="<?php echo $location['id']; ?>"><?php echo $location['label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div id="hq-collection-location-wrapper">
                                <h4 style="margin-top:18px;"><?php echo $collection_location_label; ?></h4>
                                <div class="stm_date_time_input">
                                    <div class="stm_date_input">
                                        <input type="text" value="" class="hq-text-fields" name="return_location_custom"
                                               placeholder="<?php echo $collection_location_placeholder; ?>">
                                        <i class="stm-service-icon-pin"></i>
                                    </div>
                                </div>
                            </div>
                            <h4 style="margin-top:18px;"><?php echo $pick_up_date_label; ?></h4>
                            <div class="stm_date_time_input">
                                <div class="stm_date_input">
                                    <input type="text" id="caag-pick-up-date" class=" active" name="pick_up_date"
                                           placeholder="<?php echo $pick_up_date_placeholder; ?>" required="" readonly="">
                                    <i class="stm-icon-date"></i>
                                </div>
                            </div>
                        </div>
                        <h4><?php echo $return_date_label; ?></h4>
                        <div class="stm_rent_form_fields stm_rent_form_fields-drop">
                            <div class="stm_date_time_input">
                                <div class="stm_date_input">
                                    <input type="text" id="caag-return-date" class=" active" name="return_date"
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
