<?php

/**
 * Template Name: Onepage Template
 * The template used for displaying page content in page.php
 *
 * @package _spx
 */

$frontpage_id = get_option('page_on_front');
$self_id = get_the_ID();
global $within_section;
$within_section = 'y';
get_header();
?>

<?php while (have_posts()) :
    the_post(); ?>

    <?php get_template_part('content', 'intro'); ?>

<?php endwhile; // end of the loop. ?>

<?php
$menu_locations = get_nav_menu_locations();

if (isset($menu_locations['primary'])) :
    $menu = wp_get_nav_menu_object($menu_locations['primary']);
    $menu_items = wp_get_nav_menu_items($menu->term_id);
    $menu_items_include = array();
    foreach ($menu_items as $item) {
        if (($item->object == 'page') && (xs_get_post_meta($item->object_id, 'xs_page_section') == "on") && ($item->object_id != $self_id)) {
            $menu_items_include[] = $item->object_id;
        }
    }

    $query = new WP_Query(
            array(
                'post_type' => 'page',
                'post__in' => $menu_items_include,
                'posts_per_page' => count($menu_items_include),
                'orderby' => 'post__in'
            )
    );

    if (!empty($menu_items_include) && $query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            ?>
            <?php //Section loop srarts ?>
            <!--BEGIN-<?php echo xs_sectionID(xs_main($post->ID, false)); ?>  -->
            <div id="<?php echo xs_sectionID(xs_main($post->ID, false)); ?>" class="section-wraper ">
                <?php echo carrental_edit_section(); ?>

                <?php the_content(); ?>
            </div>
            <!--END-SPEAKERS--> 
            <?php //Section loop ends   ?>

            <?php
        endwhile;
    endif;
    wp_reset_postdata();
endif;
?>

<?php get_footer(); ?>