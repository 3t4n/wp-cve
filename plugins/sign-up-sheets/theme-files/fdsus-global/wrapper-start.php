<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/fdsus-global/wrapper-start.php.
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.1.4 (plugin version)
 * @version     1.0.0 (template file version)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$template = strtolower(get_option('template'));

switch ($template) {

    // WordPress Themes
    case 'twentyten':
        echo '<div id="container"><div id="content" role="main">';
        break;
    case 'twentyeleven':
        echo '<div id="primary"><div id="content" role="main" class="twentyeleven">';
        break;
    case 'twentytwelve':
        echo '<div id="primary" class="site-content"><div id="content" role="main" class="twentytwelve">';
        break;
    case 'twentythirteen':
        echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
        break;
    case 'twentyfourteen':
        echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc">';
        break;
    case 'twentyfifteen':
        echo '<div id="primary" role="main" class="content-area twentyfifteen"><div id="main" class="site-main t15wc">';
        break;
    case 'twentysixteen':
        echo '<div id="primary" class="content-area twentysixteen"><main id="main" class="site-main" role="main">';
        break;
    case 'twentyseventeen':
        echo '<div class="wrap"><div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
        break;
    case 'twentytwenty':
        echo '<main id="site-content" role="main">';
        break;

    // 3rd Party
    case 'divi':
        echo '<div id="main-content">';
        if (!function_exists('et_builder_is_product_tour_enabled') || !et_builder_is_product_tour_enabled()):
            echo '<div class="container"><div id="content-area" class="clearfix"><div id="left-area">';
        endif;
        break;
    case 'petal':
        $blog_single_layout = apply_filters('petal_filter_single_is_boxed', petal_get_option('blog-single-layout', 'default'));
        $blog_single_is_boxed = $blog_single_layout == 'boxed' || $blog_single_layout == 'boxed-fullwidth';
        $blog_single_is_fullwidth = $blog_single_layout == 'fullwidth' || $blog_single_layout == 'boxed-fullwidth' || !is_active_sidebar('wheels-sidebar-primary');
        $content_class = $blog_single_is_fullwidth ? 'content-fullwidth' : 'content';
        $boxed = $blog_single_is_boxed ? 'boxed' : null;
        $blog_sidebar_left = petal_get_option('single-post-sidebar-left', false);
        ?>
        <div class="<?php echo esc_attr(petal_class('main-wrapper')) ?>">
        <div class="<?php echo esc_attr(petal_class('container')) ?>">
            <?php if ($blog_sidebar_left && !$blog_single_is_fullwidth) : ?>
                <div class="<?php echo esc_attr(petal_class('sidebar')) ?>">
                    <?php get_sidebar(); ?>
                </div>
            <?php endif; ?>
        <div class="<?php echo esc_attr(petal_class($content_class)) ?>">
        <?php
        break;
    case 'enfold':
        ?>
        <div class='container_wrap container_wrap_first main_color <?php avia_layout_class('main'); ?>'>
            <div class='container template-blog template-single-blog '>
                <main class='content units <?php avia_layout_class('content'); ?> <?php echo avia_blog_class_string(); ?>' <?php avia_markup_helper(array('context' => 'content', 'post_type' => 'post')); ?>>
                    <div class="entry-content-wrapper clearfix">
        <?php
        break;
    case 'virtue':
        ?>
        <div id="content" class="container">
            <div class="row">
        <?php
        break;

    // Default
    default:
        echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
        break;

}
