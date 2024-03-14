<?php

/**
 * template-blank.php
 *
 * Template Name: Blank
 * Removes title and container
 */

?>


<div>
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
