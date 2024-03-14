<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$template = get_template();

global $wp;

switch ( $template ) {
	case 'twentyten' :
		echo '<div id="container"><div id="content" role="main">';
		break;
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main" class="twentyeleven">';
		break;
	case 'twentytwelve' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="twentytwelve">';
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
		break;
	case 'twentyfourteen' :
		echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc">';
		break;
	case 'twentyfifteen' :
		echo '<div id="primary" role="main" class="content-area twentyfifteen"><div id="main" class="site-main t15wc">';
		break;
	case 'twentysixteen' :
		echo '<div id="primary" class="content-area twentysixteen"><main id="main" class="site-main" role="main">';
		break;
	default :
		echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
		break;
}
?>

<div class="main-sakolawp-wrap <?php echo esc_attr($wp->request); ?> skwp-clearfix">
	<div class="skwp-container skwp-clearfix">
	<?php if ( is_user_logged_in() ) {
		$userrole = wp_get_current_user();
		$usr_role = $userrole->roles; ?>
		<div class="skwp-menu-btn">
			<span class="top"></span>
			<span class="mid"></span>
			<span class="bot"></span>
		</div>
		<div class="sakolawp-navigation skwp-column-nav skwp-column">
			<div class="skwp-navigation-inner">
				<div class="skwp-nav-items">
					<?php require_once SAKOLAWP_PLUGIN_DIR. '/templates/'.$usr_role[0].'/navigation.php'; ?>
				</div>
			</div>
		</div>
		<div class="sakolawp-content-wrapper skwp-column-main skwp-column">
			<div class="sakolawp-inner-content">
	<?php } ?>