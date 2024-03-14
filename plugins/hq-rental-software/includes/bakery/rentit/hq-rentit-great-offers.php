<?php

/**
 * Created by PhpStorm.
 * User: Miguel Faggioni
 * Date: 12/07/2018
 */

use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;

vc_map(array(
    'name' => esc_html__('HQ Great Offers Slider', 'motors'),
    'base' => 'hq_great_offers_slider',
    'icon' => HQ_MOTORS_VC_SHORTCODES_ICON,
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Title', 'motors'),
            'param_name' => 'h',
            'value' => '',
            'description' => esc_html__('Enter the Silder Title', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Sub Title', 'motors'),
            'param_name' => 'h_s',
            'value' => '',
            'description' => esc_html__('Enter the Silder Sub Title', 'motors')
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__('Categories', 'motors'),
            'param_name' => 'id',
            'value' => '',
            'description' => esc_html__('Enter the category Ids', 'motors')
        ),
    )
));
class WPBakeryShortCode_hq_great_offers_slider extends WPBakeryShortCode
{
    protected function content($atts, $content = null)
    {
        $vehiclesQueries = new HQRentalsQueriesVehicleClasses();
        $vehicles = $vehiclesQueries->allVehicleClasses();
        extract(shortcode_atts(array(
            'h_s' => esc_html__("What a Kind of Car You Want", "rentit"),
            'h' => esc_html__('Great Rental Offers for You', "rentit"),
            'id' => ''
        ), $atts));
        ob_start();
        ?>
        <section class="page-section">
            <div class="container">
                <h2 class="section-title wow fadeInUp" data-wow-offset="70" data-wow-delay="100ms">
                    <small><?php echo wp_kses_post($atts['h_s']); ?></small>
                    <span><?php echo wp_kses_post($atts['h']); ?></span>
                </h2>
                <div class="tabs wow fadeInUp" data-wow-offset="70" data-wow-delay="300ms">
                    <ul id="tabs" class="nav"><!--
                        -->
                        <?php
                        $args = array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => 0,
                        );
                        if (isset($atts['id']{2})) {
                            $args['include'] = $atts['id'];
                        }
                        $cats = get_categories($args);
                        $i = 1;
                        foreach ($cats as $cat) {
                            if (!isset($cat->name)) {
                                continue;
                            }
                            $class = ($i == 1) ? 'active' : "";
                            ?>
                            <li class="<?php echo sanitize_html_class($class); ?>"><a
                                        href="#tab-<?php echo esc_attr($i); ?>"
                                        data-toggle="tab"><?php
                                        echo wp_kses_post($cat->name); ?></a></li>
                            <?php
                            $i++;
                        } ?>
                    </ul>
                </div>
                <div class="tab-content wow fadeInUp" data-wow-offset="70" data-wow-delay="500ms">
                    <?php $i = 1;
                    //show tabs
                    foreach ($cats as $cat) {
                        if (!isset($cat->name)) {
                            continue;
                        }
                        $class = ($i == 1) ? ' active in ' : "";
                        ?>
                        <!-- tab 1 -->
                        <div class="sladersss tab-pane fade  <?php echo esc_attr($class); ?>"
                             id="tab-<?php echo esc_attr((int)$i); ?>">
                            <div class="swiper swiper--<?php echo esc_attr(str_ireplace(' ', '-', $cat->name)); ?>">
                                <div class="swiper-container-GREAT-RENTAL swiper-container">

                                    <div class="swiper-wrapper">
                                        <!-- Slides -->
                                        <?php
                                        $rentit_new_arr = array(
                                            'paged'         => 1,
                                            'showposts'     => 10,
                                            'post_status'   => 'publish',
                                            'post_type'     => 'product',
                                            'orderby'       => 'meta_value_num',
                                            'meta_key'      =>  '_price',
                                            'order'         =>  'ASC'
                                        );
                                        ?>
                                            <?php foreach ($vehicles as $class) : ?>
                                                <div class="swiper-slide">
                                                    <div class="thumbnail no-border no-padding thumbnail-car-card">
                                                        <div class="media">
                                                            <a class="media-link" data-gal="prettyPhoto"
                                                               href="<?php echo $class->getImage()->publicLink; ?>"
                                                               alt="<?php echo $class->getImage()->label; ?>">
                                                                <img class="hq-vehicle-front-image"
                                                                     src="<?php echo $class->getImage()->publicLink . '?size=500'; ?>"
                                                                     alt="<?php echo $class->getImage()->label; ?>">
                                                                <span class="icon-view"><strong><i class="fa fa-eye"></i></strong></span>
                                                            </a>
                                                        </div>
                                                        <div class="caption text-center">
                                                            <h4 class="caption-title">
                                                                <a href="<?php echo esc_url(get_the_permalink($class->postId)); ?>">
                                                                    <?php echo $class->name; ?>
                                                                </a>
                                                            </h4>
                                                            <div class="caption-text">
                                                                <?php echo get_woocommerce_currency_symbol() ; ?> <?php
                                                                $rateT = ($class->rate()->getFormattedDailyRAte() * 1.19) ;
                                                                echo number_format($rateT, 2, ".", ",") . ' / per day';  ?>
                                                            </div>
                                                            <div class="buttons">
                                                                <a class="btn btn-theme ripple-effect"
                                                                   href="<?php echo esc_url(get_the_permalink($class->postId)) ?>">
                                                                    
                                                                     <?php if (get_locale() == 'nl_NL') {
                                                                            echo esc_html__('Reserveer nu', 'rentit');
                                                                     } else {
                                                                         echo esc_html__('Reservieren Sie jetzt', 'rentit');
                                                                     }

                                                                        ?>
                                                                </a>
                                                            </div>
                                                            <table class="table">
                                                                <tr>
                                                                    <?php foreach (array_slice($class->features(), 0, 3) as $feature) : ?>
                                                                        <td>
                                                                            <i class="<?php
                                                                            $string = $feature->icon;
                                                                            if (strpos($string, 'fal') !== false) {
                                                                                $string = str_replace("fal", "fa", $feature->icon);
                                                                            }
                                                                            if (strpos($string, 'far') !== false) {
                                                                                $string = str_replace("far", "fa", $feature->icon);
                                                                            }
                                                                            if (strpos($string, 'fas') !== false) {
                                                                                $string = str_replace("fas", "fa", $feature->icon);
                                                                            }
                                                                            echo  $string;
                                                                            ?>" ></i>
                                                                            <span><?php echo $feature->label; ?></span>
                                                                        </td>
                                                                    <?php endforeach; ?>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php wp_reset_postdata(); ?>
                                    </div>
                                </div>
                                <div class="swiper-button-next"><i class="fa fa-angle-right"></i></div>
                                <div class="swiper-button-prev"><i class="fa fa-angle-left"></i></div>
                            </div>
                        </div>
                        <?php
                        $i++;
                    } ?>
                </div>
            </div>
        </section>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" crossorigin="anonymous">
        <style>
            .hq-vehicle-front-image{
                max-width: 100%;
                max-height: 230px;
                overflow-y: hidden;
            }
        </style>
        <?php
        return ob_get_clean();
    }
}
