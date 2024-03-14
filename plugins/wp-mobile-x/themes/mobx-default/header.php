<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta name=viewport content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-transform">
    <title><?php wp_title(' | ', true, 'right');?></title>
    <?php wp_head();?>
</head>
<body <?php body_class();?>>
<header class="header">
    <div class="menu-btn">
        <button type="button" class="navbar-toggle">
            <span class="icon-bar icon-bar-1"></span>
            <span class="icon-bar icon-bar-2"></span>
            <span class="icon-bar icon-bar-3"></span>
        </button>
    </div>
    <div class="header-search">
        <i class="fa fa-search"></i>
        <div class="search-wrap">
            <form class="search-form" action="<?php echo get_bloginfo('url');?>" method="get" role="search">
                <input type="text" name="s" class="search-input" autocomplete="off" placeholder="<?php _e('Search here ...', 'wpcom');?>" value="<?php echo get_search_query(); ?>">
                <a class="search-close" href="javascript:;"><?php _e('Cancel', 'wpcom');?></a>
            </form>
        </div>
    </div>
    <?php $logo = isset($GLOBALS['mobx_options']['logo']) && $GLOBALS['mobx_options']['logo'] ? $GLOBALS['mobx_options']['logo'] : get_template_directory_uri() . '/images/logo.png'; ?>
    <a class="logo" href="<?php bloginfo('url');?>">
        <img src="<?php echo esc_url($logo);?>" alt="<?php echo esc_attr(get_bloginfo( 'name' ));?>">
    </a>

    <?php
    wp_nav_menu( array(
            'menu'              => 'mobile_x',
            'theme_location'    => 'mobile_x',
            'depth'             => 2,
            'container'         => 'nav',
            'container_class'   => 'navbar-nav',
            'menu_class'        => 'nav mobile-x-menu'
        )
    );
    ?>
</header>
<div id="wrap">