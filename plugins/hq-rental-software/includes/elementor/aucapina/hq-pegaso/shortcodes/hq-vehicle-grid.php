<?php

use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;

function hq_vehicle_grid($atts = [])
{
    $args = shortcode_atts(
        array(
            'title'   => 'Pegaso Caravanas',
            'subtitle'   => 'Elige tu autocaravana',
        ),
        $atts
    );
    $query = new HQRentalsQueriesVehicleClasses();
    $vehicles = $query->allVehiclesByRate();
    ?>
    <?php HQRentalsAssetsHandler::getHQFontAwesome(); ?>
    <style>
        .hq-title{
            color: #222222;
            font-size: 40px;
            font-weight: bold;
            text-align: center;
        }
        .hq-title-wrapper,.elementor-widget-heading,.elementor-widget-icon{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .c-vehicle__thumb-wrap{
            min-height: 300px;
        }
        .c-vehicle__thumb-wrap a{
            //display: flex;
            //justify-content: center;
            // align-items: center;
        }
        .c-vehicle__thumb-wrap img{
            min-height: 270px;
            object-fit: cover;
        }
        .c-vehicle__content-wrap{
            margin-top: -30px;
        }
        @media only screen and (min-width : 1023px) {
            .hq-vehicles-wrapper{
                display:flex !important;
                flex-direction: row !important;
            }
            .hq-vehicles-wrapper .c-vehicle{
                flex:1;
            }
        }
        .hq-vehicles-wrapper{
            display:flex;
            flex-direction: column;
            width: 100% !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
          integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
          crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
          integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
          crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
            integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
            crossorigin="anonymous"></script>
    <div class="elementor-container elementor-column-gap-no">
        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-6a73a45"
             data-id="6a73a45" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-dc593f7 elementor-view-default
                elementor-widget elementor-widget-icon hq-title-wrapper"
                     data-id="dc593f7" data-element_type="widget" data-widget_type="icon.default">
                    <div class="elementor-widget-container">
                        <div class="elementor-icon-wrapper">
                            <div class="elementor-icon">
                                <i aria-hidden="true" class="fibd21- fi-bd21-mountain"></i></div>
                        </div>
                    </div>
                </div>
                <div class="elementor-element elementor-element-c78142e elementor-widget elementor-widget-heading"
                     data-id="c78142e" data-element_type="widget" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <div class="elementor-heading-title elementor-size-default"><?php echo $args['title']; ?></div>
                    </div>
                </div>
                <div class="elementor-element elementor-element-7c4f00c elementor-widget elementor-widget-heading"
                     data-id="7c4f00c" data-element_type="widget" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <div class="elementor-heading-title elementor-size-default hq-title"><?php echo $args['subtitle']; ?></div>
                    </div>
                </div>
                <div class="elementor-element elementor-element-e79d38b elementor-view-default
                elementor-widget elementor-widget-icon"
                     data-id="e79d38b" data-element_type="widget" data-widget_type="icon.default">
                    <div class="elementor-widget-container">
                        <div class="elementor-icon-wrapper">
                            <div class="elementor-icon">
                                <i aria-hidden="true" class="fibd21- fi-bd21-hr"></i></div>
                        </div>

                    </div>
                </div>
                <div class="elementor-element elementor-element-dfee2c8 elementor-widget elementor-widget-templines-product-carousel"
                     data-id="dfee2c8" data-element_type="widget" data-widget_type="templines-product-carousel.default">
                    <div class="elementor-widget-container">
                        <div class="c-product-carousel">
                            <div class="c-product-carousel__wrap">
                                <div class="c-product-carousel__list c-product-carousel__list--3 js-product-carousel
                                 h-carousel h-carousel--flex h-carousel--dark owl-carousel owl-loaded owl-drag">
                                    <div>
                                        <div class="hq-vehicles-wrapper"
                                             style="transform: translate3d(0px, 0px, 0px); transition: all 0.25s ease 0s; width: 1170px;">
                                            <?php foreach ($vehicles as $vehicle) : ?>
                                                <div id="vehicle-class-<?php echo $vehicle->id; ?>" class="c-vehicle">
                                                    <div class="c-vehicle__thumb-wrap">
                                                        <a href="<?php echo '/vehicle-class/?id=' . $vehicle->id; ?>">
                                                            <img width="360" height="300"
                                                                 src="<?php echo $vehicle->publicImageLink; ?>"
                                                                 class="c-vehicle__thumb wp-post-image" alt=""
                                                                 sizes="(max-width: 360px) 100vw, 360px"> </a>
                                                    </div>
                                                    <div class="c-vehicle__content-wrap ">
                                                        <div class="c-vehicle__price">
                                                            <span class="woocommerce-Price-amount amount">
                                                                <bdi>
                                                                <?php echo $vehicle->rate()->getDailyRateObject()->amount_for_display; ?>
                                                                    <span class="woocommerce-Price-currencySymbol"></span>
                                                                </bdi>
                                                            </span>
                                                        </div>
                                                        <div class="c-vehicle__title"><?php echo $vehicle->getLabel(); ?></div>
                                                        <?php $features = $vehicle->features(); ?>
                                                        <?php if (is_array($features) and count($features)) : ?>
                                                            <ul class="c-vehicle__detail-list">
                                                                <?php foreach ($features as $feature) : ?>
                                                                    <li class="c-vehicle__detail-item c-detail">
                                                                        <div class="c-vehicle__detail-icon-wrap"
                                                                             title="Fuel Consumption">
                                                                            <i class="<?php echo $feature->getIcon(); ?>"></i>
                                                                        </div>
                                                                        <span class="c-detail__text">
                                                                            <?php echo $feature->getLabelForWebsite(); ?>
                                                                        </span>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                        <div class="c-vehicle__space"></div>
                                                        <a href="<?php echo '/vehicle-class/?id=' . $vehicle->id; ?>"
                                                           class="c-button c-button--outline-gray c-vehicle__more">More
                                                            Details</a>
                                                    </div>

                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery('.hq-vehicles-wrapper').owlCarousel({
            loop:true,
            margin:10,
            items: <?php echo wp_is_mobile() ? 1 : 3; ?>,
            center: true,
            nav: true,
            dots: false
        })
    </script>
    <?php
}

add_shortcode('hq_vehicle_grid', 'hq_vehicle_grid');
