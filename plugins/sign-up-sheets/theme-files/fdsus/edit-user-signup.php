<?php
/**
 * The default template for displaying the signup edit form.
 *
 * This template can be overridden by copying it to yourtheme/fdsus/edit-user-signup.php
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.1.11 (plugin version)
 * @version     1.0.0 (template file version)
 */

get_header();
fdsus_output_wrapper('start');
fdsus_output_wrapper('content-start');
/** @var array $args */
/** @var \FDSUS\Model\Signup $signup */
extract($args);
?>

<article <?php post_class('entry') ?>>

    <div class="entry-content">
        <?php echo do_shortcode('[sign_up_form task_ids=' . (int)$signup->post_parent . ']'); ?>
    </div><!-- .entry-content -->

</article><!-- .entry -->

<?php
fdsus_output_wrapper('content-end');
fdsus_output_wrapper('end');
get_footer();
