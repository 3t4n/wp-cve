<?php
/**
 * The basic filters functions
 *
 * @package Canvas
 */

/**
 * Powerkit PinIt disable
 *
 * @param string $selectors List selectors.
 */
function cnvs_powerkit_pinit_disable( $selectors ) {
	$selectors[] = '.cnvs-block-row';
	$selectors[] = '.cnvs-block-section';
	$selectors[] = '.cnvs-block-posts .entry-thumbnail';
	$selectors[] = '.cnvs-post-thumbnail';

	return $selectors;
}
add_filter( 'powerkit_pinit_exclude_selectors', 'cnvs_powerkit_pinit_disable' );
