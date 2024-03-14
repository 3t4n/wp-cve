<?php

/**
 * Template Name: Vehicle Class Page - Rentit
 *
 * @package WordPress

 */

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleClass;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesAdditionalCharges;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;

if (!defined('ABSPATH')) {
    exit;
// Exit if accessed directly
}
get_header('shop');
wp_enqueue_style('slider-pro-css');
wp_enqueue_script('slider-pro-js');
wp_enqueue_style('fancybox-css');
wp_enqueue_script('fancybox-js');
wp_enqueue_script('hq-vehicles-pages-script');
?>
    <div class="content-area">
        <!-- BREADCRUMBS -->
        <section class="page-section breadcrumbs text-right">
            <div class="container">
                <div class="page-header">
                    <h1><?php echo esc_html(get_bloginfo('name')); ?></h1>
                </div>
                <?php
                $args = array(
                    'delimiter' => ' ',
                    'wrap_before' => '<ul class="breadcrumb">',
                    'wrap_after' => '</ul>',
                    'before' => '<li>',
                    'after' => '</li>',
                    'home' => esc_html_x('Home', 'breadcrumb', "rentit")
                );
                woocommerce_breadcrumb($args);
                ?>
            </div>
        </section>
        <!-- /BREADCRUMBS -->
        <!-- PAGE WITH SIDEBAR -->
        <section class="page-section with-sidebar sub-page">
            <div class="container">
                <div class="row">
                    <?php
                    /*
                if (get_theme_mod('rentit_shop_sidebar_pos', 's2') == 's1') {
                    ?>
                    <!-- SIDEBAR -->
                    <aside class="col-md-3 sidebar" id="sidebar">
                        <?php dynamic_sidebar('rentit_sidebar_booking'); ?>
                    </aside>
                    <!-- /SIDEBAR -->
                    <?php
                }
                */
                    ?>
                    <div class="content-area">
                        <!-- PAGE WITH SIDEBAR -->
                        <section class="page-section with-sidebar sub-page">
                            <div class="container">
                                <div class="row">
                                    <?php
                                    if (get_theme_mod('rentit_shop_sidebar_pos', 's2') == 's1') {
                                        ?>
                                        <!-- SIDEBAR -->
                                        <aside class="col-md-3 sidebar" id="sidebar">
                                            <?php dynamic_sidebar('rentit_sidebar_booking'); ?>
                                        </aside>
                                        <!-- /SIDEBAR -->
                                        <?php
                                    }
                                    global $post;
                                    $vehicle = new HQRentalsModelsVehicleClass($post);
                                    $queryCharges = new HQRentalsQueriesAdditionalCharges();
                                    $charges = $queryCharges->allCharges();
                                    $frontHelper = new HQRentalsFrontHelper();
                                    $images = $vehicle->images();
                                    $minimum_rental = !empty($minimum_rental) ? (int)$minimum_rental : 0;
                                    $share_data = array(
                                        'hqMinimumPeriod'   =>  $minimum_rental
                                    );
                                    wp_localize_script('hq-rentit-app-js', 'hqHomeFormShareData', $share_data);
                                    ?>
                                    <?php if (get_locale() == 'nl_NL') :
                                        ?>
                                    <form id="hq-products-page-form" action="/reservation/" class="cart" method="POST">
                                        <?php
                                    elseif (get_locale() == 'de_DE') :
                                        ?>
                                        <form id="hq-products-page-form" action="/de/reservations/" class="cart" method="POST">
                                        <?php
                                    endif; ?>
                                            <div id="product-<?php the_ID(); ?>">
                                                <div class="car-big-card alt">
                                                    <div class="row">
                                                        <!--Gallery-->
                                                        <div class="col-md-8">
                                                            <div>
                                                                <div class="slider-pro" id="my-slider">
                                                                    <div class="sp-slides">
                                                                        <!-- Slide 1 -->
                                                                        <?php foreach ($images as $image) :
                                                                            ?>
                                                                            <div class="sp-slide">
                                                                                <a href="<?php echo $image->publicLink; ?>?size=500"
                                                                                   data-fancybox="gallery" data-caption="<?php echo $image->label; ?>">
                                                                                    <img class="sp-image"
                                                                                         src="<?php echo $image->publicLink; ?>?size=500"
                                                                                         alt="" />
                                                                                </a>

                                                                            </div>
                                                                            <?php
                                                                        endforeach; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--End Gallery-->
                                                        <div class="col-md-4">
                                                            <div class="car-details">
                                                                <div class="list">
                                                                    <ul>
                                                                        <!--Vehicle Name-->
                                                                        <li class="title">
                                                                            <h2>
                                                                                <span><?php echo $vehicle->name; ?></span>
                                                                            </h2>
                                                                            <?php
                                                                            $subtitle = get_post_meta(get_the_ID(), '_rentit_subtitle', true);
                                                                            if (!empty($subtitle)) {
                                                                                echo esc_html($subtitle);
                                                                            }

                                                                            ?>
                                                                        </li>
                                                                        <?php foreach ($vehicle->features() as $feature) :
                                                                            ?>
                                                                            <li><?php echo $feature->label; ?></li>
                                                                            <?php
                                                                        endforeach; ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="price">
                                                                    <div id="hq-price-product-page" class="price">
                                                                        €<strong><?php $rateT = ($vehicle->rate()->getFormattedDailyRAte() * 1.19) ;
                                                                        echo number_format($rateT, 2, ".", ","); ?></strong> / for 1 day(s)
                                                                        <i class="fa fa-info-circle"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row row-inputs">
                                                    <div class="col-sm-12">
                                                        <?php
                                                        $short = '[hq_rental_tabs vehicle_id=' . $vehicle->id . ']';
                                                        //echo do_shortcode( $short );
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row row-inputs">
                                                    <div class="col-sm-12">
                                                        <?php if (get_locale() == 'de_DE') :
                                                            ?>
                                                            <h3 class="block-title alt"><i class="fa fa-angle-down"></i>Description</h3>
                                                            <?php echo $vehicle->getDescription(); ?>
                                                            <?php
                                                        elseif (get_locale() == 'nl_NL') :
                                                            ?>
                                                            <h3 class="block-title alt"><i class="fa fa-angle-down"></i>Description</h3>
                                                            <?php echo $vehicle->getDescription(); ?>
                                                            <?php
                                                        endif; ?>

                                                    </div>
                                                </div>
                                                <div class="row row-inputs">
                                                    <div class="container-fluid hq-date-pickers-wrapper">
                                                        <h3 class="block-title alt"><i class="fa fa-angle-down"></i>Pickup / Return Dates</h3>
                                                        <div class="col-sm-3">
                                                            <div class="form-group has-icon has-label">
                                                                <label for="formSearchUpDate3"><?php esc_html_e('Picking Up Date', 'rentit') ?></label>
                                                                <input id="hq-pick-up-date" name="pick_up_date" type="text" class="form-control"
                                                                       placeholder="<?php esc_html_e('yyyy-mm-dd', 'rentit'); ?>"
                                                                       value="<?php
                                                                        if (function_exists('rentit_get_date_s')) {
                                                                            rentit_get_date_s('dropin_date');
                                                                        }
                                                                        ?>" autocomplete="off"
                                                                       required="required" readonly="true"
                                                                >
                                                                <span class="form-control-icon"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group has-icon has-label">
                                                                <label for="formSearchUpDate3"><?php esc_html_e('Picking Up Time', 'rentit') ?></label>
                                                                <select name="pick_up_time" class="hq-locations-selects" required="required">
                                                                    <?php echo $frontHelper->getTimesForDropdowns('15:00', '15:00'); ?>
                                                                </select>

                                                                <span class="form-control-icon"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <style>
                                                            .hq-locations-selects{
                                                                width: 100%;
                                                                border: 1px solid rgba(255, 255, 255, 0);
                                                                padding-right: 40px;
                                                                height: 50px;
                                                                border: 1px solid #e9e9e9;
                                                            }
                                                        </style>
                                                        <div class="col-sm-3">
                                                            <div class="form-group has-icon has-label">
                                                                <label for="formSearchOffDate3"><?php esc_html_e('Dropping Off Date', 'rentit') ?></label>
                                                                <input name="return_date" type="text" class="form-control"
                                                                       id="hq-return-date"
                                                                       placeholder="<?php esc_html_e('yyyy-mm-dd', 'rentit'); ?>"
                                                                       value="<?php
                                                                        if (function_exists('rentit_get_date_s')) {
                                                                            rentit_get_date_s('dropoff_date');
                                                                        }
                                                                        ?>" autocomplete="off"
                                                                       required="required" readonly="true"
                                                                >
                                                                <span class="form-control-icon"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group has-icon has-label">
                                                                <label for="formSearchUpDate3"><?php esc_html_e('Return Time', 'rentit') ?></label>
                                                                <select name="return_time" class="hq-locations-selects" required="required">
                                                                    <?php echo  $frontHelper->getTimesForDropdowns('10:00', '10:00'); ?>
                                                                </select>

                                                                <span class="form-control-icon"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group has-icon has-label">
                                                                <label for="formSearchUpDate3"><?php esc_html_e('Email', 'rentit') ?></label>
                                                                <input name="email" type="email" required autocomplete="false"
                                                                       class="form-control"
                                                                       placeholder="<?php esc_html_e('domain@example.com', 'rentit'); ?>">
                                                                <span class="form-control-icon"><i class="fa fa-envelope"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="images">
                                                </div>
                                                <hr class="page-divider half transparent"/>
                                                <h3 class="block-title alt"><i class="fa fa-angle-down"></i>
                                                    <?php esc_html_e('Additional Charges', 'rentit') ?>
                                                </h3>
                                                <div role="form" class="form-extras">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-6 car-big-card">
                                                                <div class="left car-details">
                                                                    <ul class="hq-additional-charges-list">
                                                                        <?php $column_size = (int)count($charges) / 2; ?>
                                                                        <?php foreach (array_slice($charges, 0, $column_size) as $charge) :
                                                                            ?>
                                                                            <li>
                                                                                <label for="hq-additional_charges-<?php echo $charge->id; ?>">
                                                                                    <input type="checkbox"
                                                                                           id="hq-additional_charges-<?php echo $charge->id; ?>"
                                                                                           name="additional_charges[<?php echo $charge->id; ?>]"
                                                                                           class="hq-checkboxes"  value="<?php echo $charges->id; ?>" />
                                                                                    <div class="hq-additional-charge-title">
                                                                                        <?php echo $charge->name; ?>
                                                                                    </div>
                                                                                </label>
                                                                            </li>
                                                                            <?php
                                                                        endforeach; ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 car-big-card">
                                                                <div class="right car-details">
                                                                    <ul class="hq-additional-charges-list">
                                                                        <?php foreach (array_slice($charges, $column_size, count($charges)) as $charge) :
                                                                            ?>
                                                                            <li>
                                                                                <label for="hq-additional_charges-<?php echo $charge->id; ?>">
                                                                                    <input type="checkbox" id="hq-additional_charges-<?php echo $charge->id; ?>"
                                                                                           name="additional_charges[<?php echo $charge->id; ?>]"
                                                                                           class="hq-checkboxes"
                                                                                           value="<?php echo $charges->id; ?>" />
                                                                                    <div class="hq-additional-charge-title">
                                                                                        <?php echo $charge->name; ?>
                                                                                    </div>
                                                                                </label>
                                                                            </li>
                                                                            <?php
                                                                        endforeach; ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php echo do_shortcode('[hq_rental_tabs]'); ?>
                                            <div class="overflowed reservation-now">
                                                <?php if (get_locale() == 'nl_NL') :
                                                    ?>
                                                    <h3 class="block-title alt"><i class="fa fa-angle-down"></i>Privacy Policy </h3>
                                                    <p style="text-align: justify">
                                                        Ik ben er mee eens dat mijn persoonlijke gegevens (naam, adres, telefoonnummer, e-mailadres,
                                                        geboortedatum, rijbewijs en ID gegevens) voor reclame en informatie met betrekking tot het
                                                        dienstenaanbod van het bedrijf wordt opgeslagen en wordt gebruikt voor het opnemen van contact.
                                                    </p>
                                                    <?php
                                                elseif (get_locale() == 'de_DE') :
                                                    ?>
                                                    <h3 class="block-title alt"><i class="fa fa-angle-down"></i>Privacy Policy </h3>
                                                    <p style="text-align: justify">
                                                        Ich stimme zu, dass meine persönlichen Daten (Name, Adresse, Telefonnummer, E-Mail-Adresse,
                                                        Geburtsdatum, Führerschein und Ausweisdetails) für Werbung und Informationen in Bezug auf die
                                                        Dienstleistungen des Unternehmens gespeichert und für den Kontakt verwendet werden.
                                                    </p>
                                                    <?php
                                                endif; ?>
                                            </div>

                                            <div class="overflowed reservation-now">
                                                <div class="checkbox pull-left">
                                                </div>
                                                <input type="hidden" name="vehicle_class_id" value="<?php echo $vehicle->id; ?>" >
                                                <input type="hidden" name="pick_up_location" value="2">
                                                <input type="hidden" name="new_reservation_to_step_4" value="1">
                                                <button id="reservation_car_btn" type="submit" class="btn btn-theme pull-right">Reserve Now</button>
                                            </div>
                                            <!-- #product-<?php the_ID(); ?> -->
                                        </form>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="block-title alt">
                                                    <i class="fa fa-angle-down"></i>
                                                    <?php esc_html_e('Camper Availability', 'rentit') ?>
                                                </h3>
                                                <?php
                                                $shortcode  = '[hq_rentals_vehicle_calendar id=1 ';
                                                $shortcode .= 'vehicle_class_id=' . $vehicle->id;
                                                $shortcode .= ' ]';
                                                ?>
                                                <?php echo do_shortcode($shortcode); ?>
                                            </div>
                                        </div>
                                        <style>
                                            .reservation-now{
                                                border-top: 0px !important;
                                                padding-bottom: 30px;
                                            }
                                            .hq-date-pickers-wrapper{
                                                padding-top: 25px ;
                                                padding-bottom: 25px ;
                                            }
                                            #hq-price-product-page{
                                                padding: 15px 20px;
                                            }
                                            .car-big-card .car-details .price{
                                                padding:15px 5px;
                                            }
                                            .hq-additional-charges-list li:before{
                                                display: none;
                                            }
                                            .hq-checkboxes{
                                                height: 15px;
                                                width: 15px;
                                            }
                                            input[type=checkbox]{
                                                margin-top: 4px;
                                            }
                                            .hq-additional-charge-title{
                                            }
                                            label{
                                                display: -webkit-inline-flex;
                                            }
                                            .sp-image-container, .sp-slide {
                                                min-height: 60vh;
                                                max-height: 60vh;
                                            }

                                            @media(max-width:768px){
                                                .sp-image-container, .sp-slide {
                                                    min-height: 40vh;
                                                    max-height: 40vh;
                                                }

                                            }
                                        </style>
                                </div>
                            </div>
                        </section>
                        <!-- /PAGE -->
                    </div>
                    <!-- /CONTENT AREA -->
                </div>
            </div>
        </section>
        <!-- /PAGE -->
    </div>
    <!-- /CONTENT AREA -->
<?php get_footer('shop'); ?>
