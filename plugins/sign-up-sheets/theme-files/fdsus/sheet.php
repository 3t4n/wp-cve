<?php
/**
 * Template for display the sign-up sheet
 *
 * This template can be overridden by copying it to yourtheme/fdsus/sheet.php
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.2 (plugin version)
 * @version     1.0.0 (template file version)
 */

/** @var array $args */
?>

<?php the_title('<h2 class="entry-title">', '</h2>'); ?>

<?php if ($args['show_backlink']): ?>
    <p class="dls-sus-backlink">
        <a href="<?php echo esc_attr(remove_query_arg(array('sheet_id', 'task_id'), $_SERVER['REQUEST_URI'])); ?>">
            <?php esc_attr_e('&laquo; View all', 'fdsus'); ?>
        </a>
    </p>
<?php endif; ?>

<div class="dls-sus-sheet">

    <?php fdsus_the_signup_form_response(); ?>

    <?php if (!empty($_GET['task_id'])): ?>

        <?php echo do_shortcode('[sign_up_form task_ids=' . implode(',', (array)$_GET['task_id']) . ']'); ?>

    <?php else: ?>

        <div class="dls-sus-sheet-details"><?php the_content(); ?></div>

    <?php endif; ?>

</div><!-- .dls-sus-sheet -->
