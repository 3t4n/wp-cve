<?php
/**
 * Dashboard Page
 *
 * @package frontend-dashboard
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
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
	$user = get_user_by( 'slug', get_query_var( 'author_name' ) );
	fedt_show_user_profile_page( $user );
	?>
</div>
<?php get_footer(); ?>
</body>
</html>
