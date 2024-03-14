<?php
/**
 * The template for displaying the videos in the video archive for post type video.
 *
 * This template can be overridden by copying it to your-theme/vimeotheque/archive-video.php
 *
 * @version 1.0
 */
if( !defined( 'ABSPATH' ) ){
	exit; // exit if accessed directly
}

get_header(); ?>

	<?php
	/**
	 * Before content hooks.
	 *
	 * Runs before the content is displayed into the page.
	 */
	do_action( 'vimeotheque_before_main_content' );
	?>

    <?php
        /*
         * Include the post format-specific template for the content.
         */
        vimeotheque_get_template_part( 'archive/content', 'archive' );
    ;?>

	<?php
	/**
	 * After content hooks.
	 *
	 * Runs after the content is displayed into the page.
	 */
	do_action( 'vimeotheque_after_main_content' );
	?>

	<?php
	/**
	 * Action for sidebar.
	 *
	 * Action that runs for the sidebar display.
	 */
	do_action('vimeotheque_sidebar');
	?>

<?php
get_footer();

/* Omit the closing PHP tag to avoid "headers already sent" issues */
