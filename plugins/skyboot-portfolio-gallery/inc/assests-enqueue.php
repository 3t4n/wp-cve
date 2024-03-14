<?php 

//Enqueue style

function skyboot_portfolio_gallery_enqueue() {

    // Enqueue Style

    wp_enqueue_style('skb-fontawesome', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/css/font-awesome.min.css', '', '4.5.0' );

    wp_enqueue_style('skb-framework-css', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/css/skb-framework.css', '', '1.0.0' );

    wp_enqueue_style('skb-venobox', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/css/venobox.css', '', SKYBOOT_PORTFOLIO_GALLERY_VERSION );

    wp_enqueue_style('skyboot-portfolio-style', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/css/skyboot-portfolio-style.css', '', SKYBOOT_PORTFOLIO_GALLERY_VERSION );

    wp_enqueue_style('skb-portfolio-responsive', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/css/responsive.css', '', SKYBOOT_PORTFOLIO_GALLERY_VERSION );

    // Enqueue Script

    wp_enqueue_script( 'skb-modernizr', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/js/modernizr-2.8.3.min.js', array ('jquery'), 1.1, false);

    wp_enqueue_script('imagesloaded');

    wp_enqueue_script( 'skb-isotope', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/js/isotope.pkgd.min.js', array ('jquery', 'imagesloaded'), SKYBOOT_PORTFOLIO_GALLERY_VERSION, true);

    wp_enqueue_script( 'skb-hoverdir', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/js/jquery.hoverdir.js', array ('jquery'), SKYBOOT_PORTFOLIO_GALLERY_VERSION, false);

    wp_enqueue_script( 'skb-venobox', SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL . '/assests/js/venobox.js', array ('jquery'), SKYBOOT_PORTFOLIO_GALLERY_VERSION, false);

}

add_action( 'wp_enqueue_scripts', 'skyboot_portfolio_gallery_enqueue' );

