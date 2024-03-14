<?php
/**
 * Left sidebar check.
 *
 * @package opalportfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$left_sidebar  = apply_filters( "opalportfolio_left_sidebar"     , 'left-sidebar-portfolio' );
$right_sidebar = apply_filters( "opalportfolio_right_sidebar"    , 'right-sidebar-portfolio' ); 
$sidebar_pos   = apply_filters( "opalportfolio_sidebar_archive_position" , get_theme_mod( 'opalportfolio_sidebar_archive_position' ) ); 

?>
<?php if ( 'left' === $sidebar_pos ) {  ?>
	<div class="wp-col-md-4 widget-area column-sidebar" id="sidebar-left-portfolio" role="complementary">
		<?php dynamic_sidebar( $left_sidebar ); ?>
	</div>
<?php } elseif ( 'both' === $sidebar_pos ) { ?>

	<div class="wp-col-md-3 widget-area column-sidebar" id="sidebar-left-portfolio" role="complementary">
		<?php dynamic_sidebar( $left_sidebar ); ?>
	</div>

<?php } ?>

<?php
	$html = '';
	if ( 'right' === $sidebar_pos || 'left' === $sidebar_pos ) {
		$html = '<div class="';
		if ( ( is_active_sidebar( $right_sidebar ) && 'right' === $sidebar_pos ) || ( is_active_sidebar( $left_sidebar ) && 'left' === $sidebar_pos ) ) {
			$html .= 'wp-col-md-8 content-area" id="primary">';
		} else {
			$html .= 'wp-col-md-12 content-area" id="primary">';
		}

		echo trim( $html ); // WPCS: XSS OK.
	} elseif ( 'both' === $sidebar_pos ) {
		$html = '<div class="';
		if ( is_active_sidebar( $right_sidebar ) && is_active_sidebar( $left_sidebar ) ) {
			$html .= 'wp-col-md-6 content-area" id="primary">';
		} elseif ( is_active_sidebar( $right_sidebar ) || is_active_sidebar( $left_sidebar ) ) {
			$html .= 'wp-col-md-8 content-area" id="primary">';
		} else {
			$html .= 'wp-col-md-12 content-area" id="primary">';
		}

		echo trim( $html ); // WPCS: XSS OK.
 
	} else {
	    echo '<div class="wp-col-md-12 content-area" id="primary">';
	}
