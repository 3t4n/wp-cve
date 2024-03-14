<?php

defined( 'ABSPATH' ) || exit;

$post_id = intval( $_GET['reading-mode'] );

query_posts( [
	'p'         => $post_id,
	'post_type' => get_post_type( $post_id ),

] );

add_filter( 'show_admin_bar', '__return_false' );

// Remove all WordPress actions
remove_all_actions( 'wp_head' );
remove_all_actions( 'wp_print_styles' );
remove_all_actions( 'wp_print_head_scripts' );
remove_all_actions( 'wp_footer' );

// Handle `wp_head`
add_action( 'wp_head', 'wp_enqueue_scripts', 1 );
add_action( 'wp_head', 'wp_print_styles', 8 );
add_action( 'wp_head', 'wp_print_head_scripts', 9 );
add_action( 'wp_head', 'wp_site_icon' );

// Handle `wp_footer`
add_action( 'wp_footer', 'wp_print_footer_scripts', 20 );


// Handle `wp_enqueue_scripts`
remove_all_actions( 'wp_enqueue_scripts' );

// Also remove all scripts hooked into after_wp_tiny_mce.
remove_all_actions( 'after_wp_tiny_mce' );

// Remove the_title and the_content filters
remove_all_filters( 'the_title' );
remove_all_filters( 'the_content' );

// Progress bar
add_action( 'wp_footer', function () {
	$readingProgressBar = dracula_get_settings( 'enableReadingProgress', true );
	if ( $readingProgressBar ) {
		dracula_render_progressbar();
	}
} );

// Enqueue Scripts
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'reading-mode', DRACULA_ASSETS . '/css/reading-mode.css', [ 'dashicons', 'wp-components' ] );

	$custom_css = dracula_get_settings( 'customCSS' );

	// Progressbar CSS Variable
	$progressbar_height = dracula_get_settings( 'progressbarHeight', '7' );
	$progressbar_color  = dracula_get_settings( 'progressbarColor', '#7C7EE5' );

	$progressbar_variable = '';
	$progressbar_variable .= sprintf( '--reading-mode-progress-height: %spx;', $progressbar_height );
	$progressbar_variable .= sprintf( '--reading-mode-progress-color: %s;', $progressbar_color );

	$custom_css .= sprintf( '.reading-mode-progress { %s }', $progressbar_variable );

	Dracula_Enqueue::instance()->frontend_scripts();
	wp_enqueue_script( 'reading-mode', DRACULA_ASSETS . '/js/reading-mode.js', [ 'dracula-frontend' ], DRACULA_VERSION, true );

} );

/*** Content ***/

$title = get_the_title( $post_id );

$content = get_post_field( 'post_content', $post_id );

$url       = get_the_permalink( $post_id );
$date      = get_the_date( '', $post_id );
$author    = reading_mode_get_author_name( $post_id );
$domain    = str_replace( [ 'http://', 'https://', 'www.' ], [ '', '', '' ], get_site_url() );
$site_icon = get_site_icon_url( 30, 'https://www.google.com/s2/favicons?domain=' . $domain );

$featured_image = get_the_post_thumbnail( $post_id );

if ( $featured_image ) {
	$content = $featured_image . $content;
}


?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_site_icon(); ?>

    <title><?php wp_title( '' ); ?></title>

	<?php do_action( 'wp_head' ); ?>

    <script>

        const theme = window.parent.draculaMode;

        if ('dark' === theme) {
            draculaDarkMode.enable();
        } else if ('system' === theme) {
            draculaDarkMode.auto();
        } else {
            draculaDarkMode.disable();
        }

    </script>

</head>
<body>

<div class="reading-mode">

    <!-- Left Sidebar -->
    <aside class="reading-mode-sidebar sidebar-left">
        <div id="reading-mode-toc" class="reading-mode-toc"></div>
        <div id="reading-mode-links" class="reading-mode-links"></div>

    </aside>

    <!-- Content Body -->
    <main class="reading-mode-body">

		<?php if ( dracula_get_settings( 'showSourceURL', true ) ) { ?>
            <div class="reading-mode-site">
                <img src="<?php echo esc_attr( $site_icon ); ?>" class="site-favicon">
                <a href="<?php echo esc_url( $url ); ?>" class="site-url"><?php echo esc_url( $url ); ?></a>
            </div>
		<?php } ?>

        <h1 class="reading-mode-title"><?php echo esc_html( $title ); ?></h1>

        <div class="reading-mode-byline">
			<?php


			if ( dracula_get_settings( 'showReadingTime', true ) ) {
				echo dracula_reading_mode_get_reading_time( $post_id, true );
			}

			?>

			<?php if ( dracula_get_settings( 'showDate', true ) ) { ?>
                <div class="reading-mode-date">
                    <i class="dashicons dashicons-calendar-alt"></i>
                    <span><?php echo esc_html( $date ); ?></span>
                </div>
			<?php } ?>

			<?php if ( dracula_get_settings( 'showAuthor', true ) ) { ?>
                <div class="reading-mode-author">
                    <i class="dashicons dashicons-admin-users"></i>
                    <span><?php echo esc_html( $author ); ?></span>
                </div>
			<?php } ?>
        </div>

        <div class="reading-mode-content">
			<?php echo wpautop( do_shortcode( $content ) ); ?>
        </div>

        <div class="reading-mode-share"></div>

    </main>

    <!-- Right Sidebar -->
    <aside class="reading-mode-sidebar sidebar-right">
        <div id="reading-mode-tools" class="reading-mode-tools-wrap"></div>
    </aside>

</div>

<?php do_action( 'wp_footer' ); ?>


</body>
</html>