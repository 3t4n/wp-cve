<?php

use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesLocations;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;

vc_map(array(
    'name' => esc_html__('HQ Reservation Form', 'motors'),
    'base' => 'hq_reservation_form',
    'icon' => HQ_MOTORS_VC_SHORTCODES_ICON,
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Enter the Slider Title', 'motors'),
            'param_name' => 'title',
            'value' => '',
            'description' => esc_html__('Enter the Slider Title', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Enter the Slider Subtitle', 'motors'),
            'param_name' => 'subtitle',
            'value' => '',
            'description' => esc_html__('Enter the Slider Subtitle', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Form Title', 'motors'),
            'param_name' => 'form_title',
            'value' => '',
            'description' => esc_html__('Enter the Form Title', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Search for Cheap Rental Cars Wherever Your Are', 'motors'),
            'param_name' => 'st',
            'value' => '',
            'description' => esc_html__('Enter the Silder Title', 'motors')
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__('Backgroung Image', 'motors'),
            'param_name' => 'img_src',
            'value' => '',
            'description' => esc_html__('Backgroung Image', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Submit Button Text', 'motors'),
            'param_name' => 'button_text',
            'value' => '',
            'description' => esc_html__('Submit Button Text', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Form Link', 'motors'),
            'param_name' => 'action',
            'value' => '',
            'description' => esc_html__('Enter Reservation Page Url', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Youtube Video URl', 'motors'),
            'param_name' => 'video',
            'value' => '',
            'description' => esc_html__('Youtube Video URL', 'motors')
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__('Enable Email', 'motors'),
            'param_name' => 'enable_email',
            'value' => '',
            'description' => esc_html__('Enable Email Field on Form', 'motors')
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__('Enable Number of Passegers', 'motors'),
            'param_name' => 'enable_passenger',
            'value' => '',
            'description' => esc_html__('Enable Number of Passegers', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Pick up Location Label', 'motors'),
            'param_name' => 'pick_up_location_label',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Pick up Location Placeholder', 'motors'),
            'param_name' => 'pick_up_location_placeholder',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Return Location Label', 'motors'),
            'param_name' => 'return_location_label',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Return Location Placeholder', 'motors'),
            'param_name' => 'return_location_placeholder',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Pick up Date Label', 'motors'),
            'param_name' => 'pick_up_date_label',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Pick up Date Placeholder', 'motors'),
            'param_name' => 'pick_up_date_placeholder',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Return Date Label', 'motors'),
            'param_name' => 'return_date_label',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Return Date Placeholder', 'motors'),
            'param_name' => 'return_date_placeholder',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Pick up Time Label', 'motors'),
            'param_name' => 'pick_up_time_label',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Pick up Time Placeholder', 'motors'),
            'param_name' => 'pick_up_time_placeholder',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Return Time Label', 'motors'),
            'param_name' => 'return_time_label',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Return Time Placeholder', 'motors'),
            'param_name' => 'return_time_placeholder',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Passenger Label', 'motors'),
            'param_name' => 'passenger_label',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Passenger Placeholder', 'motors'),
            'param_name' => 'passenger_placeholder',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Email Label', 'motors'),
            'param_name' => 'email_label',
            'value' => ''
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Email Placeholder', 'motors'),
            'param_name' => 'email_placeholder',
            'value' => ''
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__('Hour Format (24h)', 'motors'),
            'param_name' => 'hour_format',
            'value' => '1'
        ),
    )
));
class WPBakeryShortCode_hq_reservation_form extends WPBakeryShortCode
{
    protected function content($atts, $content = null)
    {
        /*Helpers*/
        $front = new HQRentalsFrontHelper();
        $queryLocation = new HQRentalsQueriesLocations();
        $locations = $queryLocation->allLocations();
        $translations = hq_rentit_get_translations();

        extract(shortcode_atts(array(
            'title'                         => esc_html__('All Discounts Just For You', 'rentit'),
            'subtitle'                      => esc_html__('Find Best Rental Car', 'rentit'),
            'form_title'                    => esc_html__('Search for Cheap Rental Cars Wherever Your Are', 'rentit'),
            'img_src'                       => '',
            'button_text'                   => esc_html__(' Find Car', 'rentit'),
            'video'                         => '',
            'action'                        =>  '',
            'enable_email'                  =>  false,
            'pick_up_location_label'        =>  '',
            'pick_up_location_placeholder'  =>  '',
            'return_location_label'         =>  '',
            'return_location_placeholder'   =>  '',
            'pick_up_date_label'        =>  '',
            'pick_up_date_placeholder'  =>  '',
            'return_date_label'         =>  '',
            'return_date_placeholder'   =>  '',
            'pick_up_time_label'        =>  '',
            'pick_up_time_placeholder'  =>  '',
            'return_time_label'         =>  '',
            'return_time_placeholder'   =>  '',
            'passenger_label'           =>  '',
            'passenger_placeholder'     =>  '',
            'email_label'               =>  '',
            'email_placeholder'         =>  '',
            'enable_passenger'          =>  false,
            'hour_format'               =>  ''
        ), $atts));
        if (empty($img_src)) {
            $img_src = get_template_directory_uri() . '/img/preview/slider/slide-2.jpg';
        } else {
            $img = wp_get_attachment_image_src($img_src, 'full');
            $img = $img[0];
        }
        ob_start();
        ?>
        <!--Begin-->
        <div class="item slide1 ver1" style="background-image: url('<?php echo esc_url($img); ?>');">
            <style>
                .hq-slider-caption{
                    background:rgba(0, 0, 0, 0.5);
                }
            </style>
            <div class="caption hq-slider-caption">
                <div class="container">
                    <div class="div-table">
                        <div class="div-cell">
                            <form id="hq-home-form" action="<?php echo $translations['reservation_form_action']; ?>" method="post" class="caption-content">
                                <h2 class="caption-title hq-home-form-title"><?php echo $translations['reservation_form_title']; ?></h2>
                                <h3 class="caption-subtitle hq-home-form-subtitle"><?php echo $translations['reservation_form_subtitle']; ?></h3>
                                <!-- Search form -->
                                <div class="row">
                                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                                        <div class="form-search dark">
                                                <div class="form-title">
                                                    <i class="fa fa-globe"></i>
                                                    <h2><?php echo $translations['reservation_form_inner_title']; ?></h2>
                                                </div>
                                                <div class="row row-inputs">
                                                    <div class="container-fluid">
                                                        <div class="col-sm-4">
                                                            <div class="form-group has-icon has-label">
                                                                <label><?php echo $translations['pick_up_location_form_label']; ?></label>
                                                                <select name="pick_up_location" id="hq-pick-up-location" class="hq-locations-selects">
                                                                    <option><?php echo $translations['pick_up_location_placeholder']; ?></option>
                                                                    <?php foreach ($locations as $location) : ?>
                                                                        <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <span class="form-control-icon"><i class="fa fa-map-marker"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group has-icon has-label">
                                                                <label><?php echo $translations['pick_up_date_form_label']; ?></label>
                                                                <input name="pick_up_date" type="text" autocomplete="off"
                                                                       class="form-control" id="hq-pick-up-date"
                                                                       placeholder="<?php echo $translations['pick_up_date_placeholder']; ?>">
                                                                <span class="form-control-icon"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group has-icon has-label">
                                                                <label><?php echo $translations['pick_up_time_form_label']; ?></label>
                                                                <select name="pick_up_time" class="hq-locations-selects">
                                                                    <?php echo $front->getTimesForDropdowns('07:00', '20:00'); ?>
                                                                </select>
                                                                <span class="form-control-icon"><i class="fa fa-clock-o" style="margin-right:8px;"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row row-inputs">
                                                    <div class="container-fluid">
                                                        <div class="col-sm-4">
                                                            <div class="form-group has-icon has-label">
                                                                <label><?php echo $translations['return_location_form_label']; ?></label>
                                                                <select name="return_location" id="hq-return-location" class="hq-locations-selects">
                                                                    <option><?php echo $translations['return_location_placeholder']; ?></option>
                                                                    <?php foreach ($locations as $location) : ?>
                                                                        <option value="<?php echo $location->id; ?>"><?php echo $location->name; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <span class="form-control-icon"><i class="fa fa-map-marker"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group has-icon has-label">
                                                                <label><?php echo $translations['return_date_form_label']; ?></label>
                                                                <input name="return_date" type="text" autocomplete="off"
                                                                       class="form-control" id="hq-return-date"
                                                                       placeholder="<?php echo $translations['return_date_placeholder']; ?>">
                                                                <span class="form-control-icon"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group has-icon has-label">
                                                                <label><?php echo $translations['return_time_form_label']; ?></label>
                                                                <select name="return_time" class="hq-locations-selects">
                                                                    <?php echo $front->getTimesForDropdowns('07:00', '20:00'); ?>
                                                                </select>
                                                                <span class="form-control-icon"><i class="fa fa-clock-o" style="margin-right:8px;"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row row-inputs">
                                                    <div class="container-fluid">
                                                        <?php if (!empty($enable_passenger)) : ?>
                                                            <div class="col-sm-6">
                                                                <div class="form-group has-icon has-label">
                                                                    <label for="formSearchUpLocation">
                                                                        <?php esc_html_e($passenger_label, 'rentit'); ?>
                                                                    </label>
                                                                    <select name="passengers_number" class="hq-locations-selects">
                                                                        <option><?php echo $passenger_placeholder; ?></option>
                                                                        <option>2</option>
                                                                        <option>4</option>
                                                                        <option>6</option>
                                                                        <?php //for($i = 1; $i<=10; $i++): ?>
                                                                        <?php ///echo $i; ?>"><?php //echo $i; ?>
                                                                        <?php //endfor; ?>
                                                                    </select>
                                                                    <span class="form-control-icon"><i class="fa fa-users" style="margin-right:8px;"></i></span>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($enable_email)) : ?>
                                                            <div class="col-sm-6">
                                                                <div class="form-group has-icon has-label">
                                                                    <label for="formSearchUpLocation">
                                                                        <?php esc_html_e('Email', 'rentit'); ?>
                                                                    </label>
                                                                    <input type="text" name="email" autocomplete="off"
                                                                           placeholder="<?php esc_html_e('Email', 'rentit'); ?>"
                                                                           class="hq-text-inputs">
                                                                    <span class="form-control-icon">
                                                                        <i class="fa fa-envelope" style="margin-right:8px;"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="row row-submit hq-row-submit">
                                                    <div class="container-fluid">
                                                        <div class="inner">
                                                            <button type="submit" id="formSearchSubmit"
                                                                    class="btn btn-submit btn-theme pull-right">
                                                                <?php echo $button_text; ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Search form -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .div-table{
                margin: auto;
            }
            .hq-locations-selects{
                width: 100%;
                background: rgba(255, 255, 255, 0.2);
                border: 1px solid rgba(255, 255, 255, 0);
                color: rgba(255, 255, 255, 0.6);
                padding-right: 40px;
                height: 40px;
            }
            .hq-locations-selects option{
                color:#14181C;
            }
            .hq-home-form-title{
                text-align: center;
                font-family: roboto,sans-serif;
                font-size: 24px;
                font-weight: 100;
                line-height: 1;
                color: #fff;
                clear: both;
                text-transform: uppercase;
                margin: 0 0 15px;
            }
            .hq-home-form-subtitle{
                text-align: center;
                font-family: raleway,sans-serif;
                font-size: 72px;
                font-weight: 900;
                line-height: 1;
                text-transform: uppercase;
                color: #fff;
                margin: 0 0 40px;
            }
            #hq-home-form{
                margin-top: 70px;
                margin-bottom: 70px;
            }
            .hq-text-inputs {
                width: 100%;
                background: rgba(255, 255, 255, 0.2);
                border: 1px solid rgba(255, 255, 255, 0);
                color: rgba(255, 255, 255, 0.6);
                padding-right: 40px;
                height: 40px;
            }
            .hq-row-submit{
                padding-bottom: 30px;
            }
        </style>
        <!-- /Slide 1 -->
        <?php
        return ob_get_clean();
    }
}
