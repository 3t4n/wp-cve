<?php

/**
 * page-full-width-template.php
 *
 * Template Name: Full Width Avianca Page
 */

global $post;
?>

<?php get_header(); ?>
    <style>
        #custom-blog-header{
            background-color: #2e54a4;
            padding-top: 60px;
            padding-bottom: 60px;
        }
        #custom-blog-header h2{
            color: #fff;
            text-transform: uppercase;
            font-size: 24px;
            font-weight: 900;
            margin: 0;
        }
        .header-inner-wrapper{
            display: flex;
            flex: 1;
            justify-content: flex-start;
            align-items: center;
        }
        //teaser-blog
    </style>
    <section id="custom-blog-header">
        <div class="container">
            <div class="row header-inner-wrapper">
                <?php if (defined('FW')) : ?>
                    <div class="col-md-10 col-xs-12">
                        <h2 class="title"><?php
                            echo $post->post_title;
                        ?></h2>
                    </div>
                    <div class="col-md-2 text-right col-xs-12">
                        <div>
                            <img src="<?php echo get_field('header_image'); ?>" alt="avianca">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- Teaser end -->
    <div class="container blog-posts">
        <div class="row">
            <div class="blog-box col-md-12" role="main">
                <?php while (have_posts()) :
                    the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <!-- Article header -->
                        <header class="entry-header"> <?php
                        if (has_post_thumbnail() && !post_password_required()) :
                            ?>
                                <figure class="entry-thumbnail"><?php the_post_thumbnail(); ?></figure>
                        <?php endif; ?>

                        </header> <!-- end entry-header -->

                        <!-- Article content -->
                        <div class="entry-content">
                            <?php the_content(); ?>

                            <?php wp_link_pages(); ?>
                        </div> <!-- end entry-content -->

                        <!-- Article footer -->
                        <footer class="entry-footer">
                            <?php
                            if (is_user_logged_in()) {
                                echo '<p>';
                                edit_post_link(__('Edit', 'xs'), '<span class="meta-edit">', '</span>');
                                echo '</p>';
                            }
                            ?>
                        </footer> <!-- end entry-footer -->
                    </article>

                <?php endwhile; ?>
            </div> <!-- end main-content -->
        </div> <!-- end main-content -->
    </div> <!-- end main-content -->

<?php get_footer(); ?>