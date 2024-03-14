<?php

/*
 * Template Name: Vehicle Class Page - Aucapina Theme
 */

use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;

$assets = new HQRentalsAssetsHandler();
$assets->loadAucapinaVehiclePageAssets();
require_once ABSPATH . 'wp-content/plugins/hq-rental-software/includes/elementor/widgets/HQRentalsElementorAucapinaReservationForm.php';
$query = new HQRentalsDBQueriesVehicleClasses();

if (isset($_GET['id'])) {
    $vehicle = $query->getVehicleClassById($_GET['id']);
} else {
    wp_redirect('/', 302);
    return exit;
}

get_header();
?>
<?php HQRentalsAssetsHandler::getHQFontAwesome(); ?>
<header
    class="l-section c-page-header">
    <div class="c-page-header__image header-image" style="background-image:url(<?php echo $vehicle->getPublicImage(); ?>)"></div>
    <div
        class="c-page-header__wrap">
        <h1 class="l-section__container c-page-header__title"><?php echo $vehicle->getLabelForWebsite(); ?></h1>
        <nav class="c-breadcrumbs">
            <ol class="c-breadcrumbs__list" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <li class="c-breadcrumbs__item  c-breadcrumbs__item--first  " itemprop="itemListElement"
                    itemscope="" itemtype="http://schema.org/ListItem">
                    <a itemprop="item" title="Home" href="<?php echo home_url(); ?>"><span itemprop="name">Home</span></a>
                    <i class="fas fa-arrow-right"></i>
                    <meta itemprop="position" content="1">
                </li>
                <li class="c-breadcrumbs__item  " itemprop="itemListElement" itemscope=""
                    itemtype="http://schema.org/ListItem">
                    <a itemprop="item" title="RV Rental Listings" href="<?php echo get_home_url() . '/vehicle-class?id=' . $vehicle->getId(); ?>">
                        <span itemprop="name"><?php echo $vehicle->getLabelForWebsite(); ?></span>
                    </a>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
        </nav>
    </div>
</header>
<style>
    .header-image {
        background-size: 100%;
        filter: opacity(0.5);
        font-size:12px;
    }
    .fas{
        margin:0 5px;
        color: #fff;
    }
    @media (min-width: 1170px) {
        .c-page-header {
            min-height: 550px !important;
        }
    }

    .c-vehicle-details__detail-list-wrap {
        display: block !important;
    }
    .header-image{
        background-size: 100%;
        filter: opacity(0.5);
    }
    .hq-calendar-wrapper{
        margin-top: 70px;
    }
</style>
<?php if (have_posts()) : ?>
    <?php while (have_posts()) :
        the_post(); ?>
        <div class="l-section l-section--container l-section--with-sidebar l-section--margin-120">
            <div class="l-section__content l-section__content--with-sidebar">
                <article id="vehicle-class-<?php echo $vehicle->getId(); ?>"
                         class="c-vehicle-details <?php templines_class(templines_mod('sticky_sidebar'), 'js-sticky-sidebar-nearby'); ?>">
                    <?php $images = $vehicle->images(); ?>
                    <?php if (is_array($images) and count($images)) : ?>
                        <div class="c-vehicle-details__images-block">
                            <div>
                                <div class="slider-pro" id="vehicle-images-slider">
                                    <div class="sp-slides">
                                        <?php foreach ($images as $image) : ?>
                                            <div class="sp-slide">
                                                <a data-fancybox="gallery" href="<?php echo $image->publicLink; ?>">
                                                    <img
                                                        class="sp-image" src="https://cdnjs.cloudflare.com/ajax/libs/slider-pro/1.5.0/css/images/blank.gif"
                                                        data-src="<?php echo $image->publicLink; ?>"
                                                    />
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="sp-thumbnails">
                                        <?php foreach ($images as $image) : ?>
                                            <div class="sp-thumbnail">
                                                <img class="sp-thumbnail-image" src="<?php echo $image->publicLink; ?>"/>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                    <h1 class="c-vehicle-details__title"><?php echo $vehicle->getLabelForWebsite(); ?></h1>
                    <h2 class="c-vehicle-details__subtitle">

                    </h2>

                    <h3 class="c-vehicle-details__subheader"><?php esc_html_e('Description', 'aucapina') ?></h3>
                    <div class="entry-content entry-content--sidebar">
                        <?php echo $vehicle->getShortDescriptionForWebsite(); ?>
                    </div>



                        <h3 class="c-vehicle-details__subheader"><?php esc_html_e('Details', 'aucapina') ?></h3>
                        <div class="c-vehicle-details__detail-list-wrap">
                            <?php echo $vehicle->getDescriptionForWebsite(); ?>
                        </div>
                    <div class="hq-calendar-wrapper">
                        <?php

                        echo do_shortcode(
                            '[hq_rentals_vehicle_calendar_snippet id="1" forced_locale="es" vehicle_class_id=' . $vehicle->getId() . ']'
                        );
                        ?>
                    </div>

                </article>
            </div>
            <div class="l-section__sidebar l-section__sidebar--right">
                <div id='hq-sidebar' class="c-vehicle-details__sidebar  js-sticky-sidebar">
                    <style>
                        #hq-sidebar .c-filter{
                            max-width: 100%;
                            flex-direction: column;
                            margin-top: 0 !important;
                        }
                        #hq-sidebar .c-filter__wrap{
                            flex-direction: column;
                        }
                        #hq-sidebar .c-filter__col-1,
                        #hq-sidebar input,
                        #hq-sidebar select,
                        #hq-sidebar .hq-field-wrapper{
                            width:100% !important;
                        }
                    </style>
                    <div>
                        <?php
                            $form = new HQRentalsElementorAucapinaReservationForm([
                                    'reservation_url_aucapina_form' => '/reservations/'
                            ], [
                                    'reservation_url_aucapina_form' => '/reservations/'
                            ]);
                            $form->render_content();
                        // @codingStandardsIgnoreStart
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="l-section">
            <div data-elementor-type="wp-post" class="elementor elementor-307">
                <div class="elementor-section-wrap">
                    <section
                            class="elementor-section elementor-top-section elementor-element elementor-element-274df8f elementor-section-full_width elementor-section-height-default elementor-section-height-default"
                            data-id="274df8f"
                            data-element_type="section"
                            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}"
                    >
                    </section>
                </div>
            </div>
        </div>
    <?php endwhile;
    // @codingStandardsIgnoreEnd
    ?>
<?php endif; ?>
<?php get_footer(); ?>