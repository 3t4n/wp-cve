<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/* Deactivation of Elementor Elements */
$basic_elements = array(
    'countdown',
    'image-box',
    'featured',
    'price',
    'clients',
    'team',
    'testimonial',
    'testimonial-carousel',
    'heading',
    'portfolio',
    'comparison',
    'menu-list',
    'blog',
    'slider',
    'fancy',
    'info-box',
    'about',
    'services',
    'nav',
    'contact',
    'map',
    'logo',
    'cart',
    'button',
    'image',
    'product',
    'counter',
    'breadcrumbs',
    'menu',
    'shadow',
    'showcase',
    'search-widget',
    'woo',
    'header-search',
    'image-box-slider',
    'bottom-shape',
    'top-shape',
    'insta',
    'video-box',
    'post-title',
    'post-author',
    'post-comments',
    'post-featured-image',
    'post-date',
    'animated-heading'
);
foreach ( $basic_elements as $element_name ) {
    $element_name__ = str_replace( '-', '_', $element_name );
    ${'deactivate_element_' . $element_name__} = bea_get_option( 'bea_deactivate_element_' . $element_name__, false );
}
?>

<div class="bea-settings">

    <div class="postbox">

        <!-------------------
        OPTIONS HOLDER START
        -------------------->
        <div class="bea-menu-options settings-options">

            <div class="bea-inner">

                <!-------------------  LI TABS -------------------->

                <ul class="bea-tabs-wrap">
                    <li class="bea-tab" data-target="general"><i
                                class="bea-icon dashicons dashicons-admin-generic"></i><?php 
echo esc_html( __( 'General', 'better-el-addons' ) );
?>
                    </li>
                    <li class="bea-tab  selected" data-target="elements"><i
                                class="bea-icon dashicons dashicons-admin-settings"></i><?php 
echo esc_html( __( 'Elements', 'better-el-addons' ) );
?>
                    </li>
                </ul>

                <!-------------------  GENERAL TAB -------------------->

                <div class="bea-tab-content general bea-tab-show">

                    <!---- Theme Colors -->
                    <div class="bea-box-side">
                        <h3><?php 
echo esc_html( __( 'Intro', 'better-el-addons' ) );
?></h3>
                    </div>
                    <div class="bea-inner bea-box-inner">
                        <div class="bea-row bea-field">
                            <label
                                    class="bea-label"><?php 
echo esc_html( __( 'Better Elementor Addons', 'better-el-addons' ) );
?></label>
                            <p class="bea-desc"><?php 
echo esc_html( __( 'Better Elementor Addons is an elementor add-on to showcase your Count down, Service Box, Team, Testimonial, our team, and Heading with card style/design. This is an simple and flexible way to add new elements/widgets to Elementor Page Builder.', 'better-el-addons' ) );
?></p>
                        </div>

                        <div class="bea-clearfix"></div>

                    </div>

                    <div class="bea-clearfix"></div>


                </div>

                <!-------------------  ELEMENTS TAB -------------------->

                <div class="bea-tab-content elements">

                    <!---- Auto activate Elementor Addons -->
                    <div class="bea-box-side">

                        <h3><?php 
echo esc_html( __( 'Optimize Plugin', 'better-el-addons' ) );
?></h3>

                    </div>

                    <div class="bea-inner bea-box-inner">


                        <div class="bea-row bea-field">
                            <label class="bea-label"><?php 
echo esc_html( __( 'Deactivate elements for better performance', 'better-el-addons' ) );
?></label>

                            <p class="bea-desc"><?php 
echo esc_html( __( 'You can deactivate those elements that you do not intend to use to avoid loading scripts and files related to those elements.', 'better-el-addons' ) );
?></p>
                        </div>

                        <div class="bea-elements-deactivate">
                            
                            <?php 
foreach ( $basic_elements as $element_name ) {
    $element_name__ = str_replace( '-', '_', $element_name );
    $element_name_ = str_replace( '_', ' ', $element_name__ );
    ?>
                                    <div class="bea-row bea-type-checkbox bea-field">
                                        <label class="bea-label"><?php 
    echo esc_html( __( $element_name_, 'better-el-addons' ) );
    ?></label>
                                        <div class="bea-toggle">
                                            <input type="checkbox" class="bea-checkbox" name="<?php 
    echo esc_attr( 'bea_deactivate_element_' . $element_name__ );
    ?>"
                                                    id="<?php 
    echo esc_attr( 'bea_deactivate_element_' . $element_name__ );
    ?>" data-default=""
                                                    value="<?php 
    echo esc_attr( ${'deactivate_element_' . $element_name__} );
    ?>" 
                                                    <?php 
    echo checked( !empty(${'deactivate_element_' . $element_name__}), 1, false );
    ?>>
                                            <label for="<?php 
    echo esc_attr( 'bea_deactivate_element_' . $element_name__ );
    ?>"></label>
                                        </div>
                                    </div>
                            <?php 
}
?>

                            <?php 
?>

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bea-spacer"></div>

<div class="bea-clearfix"></div>
