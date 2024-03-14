<?php
/**
 * Section block template
 *
 * @var     $attributes - block attributes
 * @var     $content - inner blocks
 * @var     $options - layout options
 *
 * @package Canvas
 */

if ( 'full' === $attributes['layout'] ) {
	$attributes['className'] .= ' cnvs-block-section-fullwidth';
}
if ( 'full' === $attributes['layout'] && 'full' === $attributes['layoutAlign'] ) {
	$attributes['className'] .= ' cnvs-block-section-layout-align-full';
}
if ( 'with-sidebar' === $attributes['layout'] && $attributes['sidebarSticky'] ) {
	$attributes['className'] .= ' cnvs-block-section-sidebar-sticky-' . $attributes['sidebarStickyMethod'];
}
if ( 'with-sidebar' === $attributes['layout'] && $attributes['sidebarPosition'] ) {
	$attributes['className'] .= ' cnvs-block-section-sidebar-position-' . $attributes['sidebarPosition'];
}
if ( $attributes['textColor'] ) {
	$attributes['className'] .= ' cnvs-block-section-with-text-color';
}
if ( $attributes['backgroundColor'] ) {
	$attributes['className'] .= ' cnvs-block-section-with-background-color';
}

$section_style = null;

// Set section style.
if ( ! get_theme_support( 'canvas-disable-section-responsive' ) && ( ( 'full' === $attributes['layout'] && ! $attributes['layoutAlign'] ) || ( 'full' !== $attributes['layout'] ) ) ) {
	if ( isset( $attributes['contentWidth'] ) && $attributes['contentWidth'] ) {
		$section_style = sprintf( 'max-width: %spx;', $attributes['contentWidth'] );
	} else {
		$section_style = sprintf( 'max-width: %spx;', apply_filters( 'canvas_section_responsive_max_width', 1200 ) );
	}
}

// Check page template.
$object_id = get_queried_object_id();

$page_template = apply_filters( 'canvas_block_section_page_template', get_page_template_slug( $object_id ) );

if ( 'template-canvas-fullwidth.php' === $page_template ) {
	?>
	<div class="<?php echo esc_attr( $attributes['className'] ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>
		<div class="cnvs-block-section-outer" style="<?php echo esc_attr( $section_style ); ?>">
			<div class="cnvs-block-section-inner">
				<?php echo (string) $content; // XSS. ?>
			</div>
		</div>
	</div>

	<?php
} else {
	cnvs_alert_warning( esc_html__( 'To use this block, please select the page template - "Canvas Full Width".', 'canvas' ) );
}
