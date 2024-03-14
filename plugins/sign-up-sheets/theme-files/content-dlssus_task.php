<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * This template can be overridden by copying it to yourtheme/content-dlssus_task.php.
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.1 (plugin version)
 * @version     1.1.0 (template file version)
 */
?>

<?php echo do_shortcode('[sign_up_form task_ids=' . implode(',', (array)$_GET['task_id']) . ']'); ?>
