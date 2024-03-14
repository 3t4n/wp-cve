<?php
$dashboard_url = fed_get_dashboard_url();
$login_page    = fed_get_login_url();
$login_page    = $login_page === false ? wp_login_url() : $login_page;
$current_link  = get_permalink();
if ( $dashboard_url === $current_link && ! is_user_logged_in() ) {
	wp_safe_redirect( $login_page );
}

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php wp_title( ' ' ); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="primary fed_dashboard" class="container-fluid">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</div>
<?php get_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>
