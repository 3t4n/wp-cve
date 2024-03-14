<?php

use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesLocations;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;

class HQRentalsElementorAucapinaReservationForm extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        $this->linkURL = '';
        $this->assets = new HQRentalsAssetsHandler();
    }

    public function get_name()
    {
        return 'Aucapina - Reservation Form';
    }

    public function get_title()
    {
        return __('Aucapina - Reservation Form', 'hq-wordpress');
    }

    public function get_icon()
    {
        return 'eicon-product-categories';
    }

    public function get_categories()
    {
        return ['hq-rental-software'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'hq-wordpress'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'reservation_url_aucapina_form',
            [
                'label' => __('Reservations URL', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $this->assets->loadAucapinaReservationFormAssets();
        $settings = $this->get_settings_for_display();
        $locationQuery = new HQRentalsQueriesLocations();
        $vehiclesQuery = new HQRentalsQueriesVehicleClasses();
        $vehicles = $vehiclesQuery->allVehicleClasses();
        $locations = $locationQuery->allLocations();
        ?>
        <style>
            .hq-field-wrapper{
                width: 50% !important;
                flex: 0 0 49% !important;
            }
            @media (max-width: 797px){
                .hq-field-wrapper{
                    width: 100% !important;
                    flex: 1 !important;
                }
            }
            .hq-field-wrapper select, .hq-field-wrapper span{
                width:100% !important;
            }
            .hq-field-wrapper select,
            .hq-field-wrapper input, .hq-field-wrapper span,
            .hq-button{
                height: 50px !important;
            }
        </style>
        <script>var locale = "<?php echo get_locale(); ?>"</script>
        <div class="elementor-widget-container hq-aucapina-form">
            <form method="GET"
                  action="<?php echo empty($settings['reservation_url_aucapina_form']) ? '/reservations/' :
                      $settings['reservation_url_aucapina_form']; ?>" class="js-filter-form ">
                <div class="l-section l-section--container c-filter c-filter--col-3 c-filter--layout-1"
                     style="color:#ffffff;background-color:#0b4453;">
                    <div class="c-filter__col-1">
                        <div class="c-filter__wrap">
                            <div class="c-filter__field hq-field-wrapper">
                                <div class="c-filter__title">Where?</div>
                                <div class="c-filter__element">
                                    <select name="pick_up_location" id="hq_pick_up_location"
                                            class="h-cb c-filter__select styled hasCustomSelect"
                                            style="color: rgba(255, 255, 255, 0.5) !important; background-color: rgb(28, 81, 95) !important;
                                            appearance: menulist-button; position: absolute; opacity: 0; height: 50.1875px;
                                            ont-size: 16px; width: 301px;">
                                        <option value="">Select location</option>
                                        <?php foreach ($locations as $location) : ?>
                                            <option value="<?php echo $location->getId(); ?>"><?php echo $location->getLabelForWebsite(); ?></option>
                                        <?php endforeach; ?>
                                    </select><span class="c-custom-select"
                                                   style="color: rgba(255, 255, 255, 0.5) !important;
                                                    background-color: rgb(28, 81, 95) !important; display: inline-block;">
                                        <span
                                                class="c-custom-selectInner"
                                                id="location-tag"
                                                style="width: 301px; display: inline-block;">Select location</span><i
                                                class="ip-select c-custom-select__angle"><!-- --></i></span>
                                </div>
                            </div>

                            <div class="c-filter__field hq-field-wrapper">
                                <div class="c-filter__title">When?</div>
                                <div class="c-filter__element">
                                    <input type="text" class="h-cb c-filter__date " value=""
                                           readonly=""
                                           id="hq-daterange"
                                           style="color:rgba(255, 255, 255, 0.5)!important;
                                           background-color:rgb(28, 81, 95)!important;">
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="c-filter__col-2">
                        <button type="submit" class="c-button c-button--fullwidth js-filter-button hq-button">Search</button>
                        <input type="hidden" name="target_step" value="2" />
                        <input type="hidden" name="pick_up_time" value="14:00" />
                        <input type="hidden" name="return_time" value="11:00" />
                        <input type="hidden" name="pick_up_date" id="hq_pick_up_date" value="" />
                        <input type="hidden" name="return_date" id="hq_return_date" value="" />
                        <input type="hidden" name="return_location" id="hq_return_location" value="<?php echo $locations[0]->getId(); ?>">
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
}

