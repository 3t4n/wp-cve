<?php
/**
 * Tabs block template
 *
 * @var     $attributes - block attributes
 * @var     $content - inner blocks
 * @var     $options - layout options
 *
 * @package Canvas
 */

$tabs_data = is_array( $attributes['tabsData'] ) ? $attributes['tabsData'] : array();

if ( $tabs_data && count( $tabs_data ) ) {
	$attributes['className'] .= ' cnvs-block-tabs-' . count( $tabs_data );
}

if ( 'vertical' === $attributes['tabsPosition'] ) {
	$attributes['className'] .= ' cnvs-block-tabs-' . $attributes['tabsPosition'];
}

?>

<div class="<?php echo esc_attr( $attributes['className'] ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>
	<div class="cnvs-block-tabs-buttons">
		<?php
		foreach ( $tabs_data as $i => $title ) {
			$tab_class = 'cnvs-block-tabs-button';

			if ( $i === $attributes['tabActive'] ) {
				$tab_class .= ' cnvs-block-tabs-button-active';
			}

			?>
			<div class="<?php echo esc_attr( $tab_class ); ?>">
				<a href="#"><?php echo (string) $title; // XSS. ?></a>
			</div>
			<?php
		}
		?>
	</div>
	<div class="cnvs-block-tabs-content">
		<?php echo (string) $content; // XSS. ?>
	</div>
</div>
