<?php

/**
 * Template Name: Reservations Page
 * The main template file for display page.
 *
 * @package WordPress
 */

/**
 *    Get Current page object
 **/
if (!is_null($post)) {
    $page_obj = get_page($post->ID);
}

$current_page_id = '';

/**
 *    Get current page id
 **/

if (!is_null($post) && isset($page_obj->ID)) {
    $current_page_id = $page_obj->ID;
}

$page_style = 'Right Sidebar';
$page_sidebar = get_post_meta($current_page_id, 'page_sidebar', true);

if (empty($page_sidebar)) {
    $page_sidebar = 'Page Sidebar';
}

get_header();
?>

<?php
//Include custom header feature
get_template_part("/templates/template-header-full-width");
?>

    <div>
        <!-- Begin main content -->
        <div class="inner_wrapper">
            <div>
                <div class="sidebar_content page_content">
                    <?php if (have_posts()) {
                        while (have_posts()) :
                            the_post(); ?>
                                                    <?php the_content(); ?>
                        <?php endwhile;
                    }; ?>

                    <?php
                    if (comments_open($post->ID)) {
                        ?>
                        <div class="fullwidth_comment_wrapper sidebar">
                            <?php comments_template('', true); ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- End main content -->
    </div>
    </div>
    <br class="clear"/><br/>
<?php get_footer(); ?>