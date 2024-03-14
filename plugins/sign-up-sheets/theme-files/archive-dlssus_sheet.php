<?php
/**
 * The template for displaying archive pages
 *
 * This template can be overridden by copying it to yourtheme/archive-dlssus_sheet.php.
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.1 (plugin version)
 * @version     1.0.1 (template file version)
 */

get_header();
fdsus_output_wrapper('start');
?>

    <header <?php fdsus_content_header_class(); ?>>
        <?php
        the_archive_title('<h1 ' . fdsus_h1_class('archive-title', false) . '>', '</h1>');
        the_archive_description('<div class="taxonomy-description">', '</div>');
        ?>
    </header><!-- .entry-header -->

    <?php fdsus_output_wrapper('content-start'); ?>

    <article <?php post_class('entry type-page') ?>>

        <div class="entry-content">

            <?php if (have_posts()) : ?>
                <?php echo do_shortcode('[sign_up_sheet]'); ?>
            <?php else: ?>
                <p><?php esc_attr_e('No sign-up sheets found.', 'fdsus'); ?></p>
            <?php endif; ?>

        </div><!-- .entry-content -->

    </article><!-- .entry -->

    <?php fdsus_output_wrapper('content-end'); ?>

<?php
fdsus_output_wrapper('end');
get_footer();
?>
