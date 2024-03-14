<?php

/**
 * header.php
 *
 * The header for the theme.
 */

?>
<!DOCTYPE html>
<!--[if IE 8]> <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if !IE]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

    <head>
        <!-- Google Analytics -->
        <script>
        window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
        ga('create', 'UA-85077124-3', 'auto');
        ga('set', 'anonymizeIp', true);
        ga('send', 'pageview');
        </script>
        
        <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NP6HF57');</script>
<!-- End Google Tag Manager -->
        
        <script async src='https://www.google-analytics.com/analytics.js'></script>
        <!-- End Google Analytics -->
        <!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/25b094ba71.js" crossorigin="anonymous"></script>


        <!-- Favicon and Apple Icons -->
        <?php
        fw_theme_get_the_favicon();
        fw_theme_get_the_apple_icon()
        ?>
        <script type="text/javascript">
            var xsUrl = '<?php echo get_template_directory_uri(); ?>';
            var sLocation = "<?php _e('Please select a location', 'fw') ?>";
            var adminAjax = "<?php echo admin_url("admin-ajax.php"); ?>";

            var selectedCars = "<?php _e('Selected Car', 'fw') ?>";
            var pickUpLocation = "<?php _e('Pickup Location', 'fw') ?>";
            var DropOffLocation = "<?php _e('Dropoff Location', 'fw') ?>";
            var Pickup = "<?php _e('Pickup', 'fw') ?>";
            var DropOff = "<?php _e('Dropoff', 'fw') ?>";
            var DateFormat = "<?php echo fw_get_db_settings_option('date_format'); ?>"


        </script>

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>  id="top" data-spy="scroll" data-target=".navbar" data-offset="260">
        <?php // fw_prerloader(); ?>


        <!-- Avianca Promo -->
        <div class="os-avianca-bar" onclick="location.href='/promocion-avianca';" style="cursor: pointer;"><i class="fa fa-plane" aria-hidden="true"></i>
Viaja con Avianca y ahorra con nuestra promoción - Haz click aquí</div>
        
        <!-- Header start -->
        <header data-spy="affix" data-offset-top="39" data-offset-bottom="0" class="nav-scroll large">

            <div class="row container box">
                <div class="col-md-2 col-lg-2">
                    <!-- Logo start -->
                    <div class="brand">
                        <?php
                        $menu_logo = '';
                        if (defined("FW")) {
                            $menu_logo = fw_get_db_settings_option('menu_logo');
                        }
                        if ($menu_logo != '') :
                            ?>
                            <h1>
                                <a class="scroll-to" href="<?php echo home_url('/'); ?>">
                                    <img class="img-responsive"  src="<?php echo esc_url($menu_logo['url']); ?>" alt="<?php bloginfo('name') ?>">
                                </a>
                            </h1>
                        <?php else : ?>
                            <a href = "<?php echo home_url('/'); ?>" class = "img-responsive">
                                <img src = "<?php echo XS_IMAGES . '/logo.gif' ?>" alt = "<?php bloginfo('name') ?>">
                            </a>
                        <?php endif;
                        ?>
                    </div>
                    <!-- Logo end -->
                </div>

                <div class="col-md-10 col-lg-10">
                    <div class="pull-right-os">
                        <div class="header-info">
<div class="os-flex">
    <div class="os-locations">
        <ul>
            <li>
                <span>Miami</span>
                <span>
                    <i class="fa fa-map-marker"></i>
                    <?php // @codingStandardsIgnoreStart ?>
                    <a href="http://maps.google.com/maps?q=3256+NW+24th+Street+Rd%2c+Miami%2c+FL%2c+33142%2c+United+States+(MIAMI)">3256 NW 24 th  St. Road, Miami, FL, 33142</a>
                    <?php // @codingStandardsIgnoreEnd ?>
                </span>
                <span><i class="fa fa-mobile"></i><a href="tel:+13054707556">+1 (305) 470 7556</a></span>
                <span><i class="fa fa-whatsapp"></i><a href="http://wa.me/+17868622219">+1 (786) 862 2219</a></span>
            </li>
        </ul>
    </div>
    <div class="os-locations">
        <ul>
            <li>
                <span>Orlando</span>
                <?php // @codingStandardsIgnoreStart ?>
                <span><i class="fa fa-map-marker"></i><a href="https://www.google.com/maps/place/3255+McCoy+Rd,+Belle+Isle,+FL+32812,+EE.+UU./@28.4515522,-81.3440854,17z/data=!3m1!4b1!4m5!3m4!1s0x88e77cd3117fd1d1:0x61bde943225bfa3c!8m2!3d28.4515522!4d-81.3418967">3255 McCoy Rd, Belle Isle, FL, 32812</a></span>
                <span><i class="fa fa-mobile"></i><a href="tel:+13218006002">+1 (321) 8006002</a></span>
                <span><i class="fa fa-whatsapp"></i><a href="http://wa.me/+13059886125"> +1 (305) 9886125</a></span>
                <?php // @codingStandardsIgnoreEnd ?>
            </li>
        </ul>
    </div>
    <div class="os-locations">
        <ul>
            <li>
                <span>Fort Lauderdale</span>
                <span><i class="fa fa-map-marker"></i><a href="https://goo.gl/maps/h6kBArr7XH93PqdM8">321 W State Road 84  Fort Lauderdale, FL, 33315</a></span>
                <span><i class="fa fa-mobile"></i><a href="tel:+19549004536">+1 (954) 9004536</a></span>
            </li>
        </ul>
    </div>
</div>
                        </div>
                    </div>

                    <span class="clearfix"></span>

                    <!-- start navigation -->
                    <nav class="navbar navbar-default" role="navigation" id="main-navbar">
                        <div class="container-fluid">
                            <!-- Toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <?php
                                $menu_logo = '';
                                if (defined("FW")) {
                                    $menu_logo = fw_get_db_settings_option('menu_logo');
                                }
                                if ($menu_logo != '') :
                                    ?>
                                    <a class="navbar-brand scroll-to" href="<?php echo esc_url(home_url('/')); ?>">
                                        <img class="img-responsive"  src="<?php echo esc_url($menu_logo['url']); ?>" alt="<?php bloginfo('name') ?>">
                                    </a>
                                <?php else : ?>
                                    <a class="navbar-brand scroll-to" href="<?php echo esc_url(home_url('/')); ?>">
                                        <img class="img-responsive"  src="<?php echo XS_IMAGES; ?>/logo.gif" alt="Car|Rental">
                                    </a>
                                <?php endif; ?>

                            </div>
                            <!-- Collect the nav links, for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <!-- Nav-Links start -->
                                <?php
                                wp_nav_menu(array(
                                    'menu' => 'primary',
                                    'theme_location' => 'primary',
                                    'depth' => 3,
                                    'container' => '',
                                    'container_class' => '',
                                    'container_id' => '',
                                    'menu_class' => 'nav navbar-nav navbar-right',
                                    'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                    'walker' => new wp_bootstrap_navwalker() ));
                                ?>
                            </div>
                        </div>
                    </nav>
                    <!-- end navigation -->
                </div>
            </div>

            <?php

            if (! wp_is_mobile()) {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function( $ ){
                        $(window).scroll(function() {
                          if ($(this).scrollTop() > 39){
                            $('#home').css('padding-top', '145px'); //Height of non-stick nav
                      $('.page-template-template-reservations > div').first().css('padding-top', '145px');
                           }
                          else{
                            $('#home').css('padding-top', '');
                      $('.page-template-template-reservations > div').first().css('padding-top', '');
                          }
                        });
                    });
                </script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function( $ ){
                        $(window).scroll(function() {
                          if ($(this).scrollTop() > 40){
                            $('#home').css('padding-top', '88px'); //Height of non-stick nav
                           }
                          else{
                            $('#home').css('padding-top', '');
                          }
                        });
                    });
                </script>
                <?php
            }
            ?>


        </header>
        <!-- Header end -->

       