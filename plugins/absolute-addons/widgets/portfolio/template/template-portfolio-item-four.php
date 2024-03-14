<?php
/**
 * Template for Portfolio Style Four
 *
 * @package AbsoluteAddons
 */

/** @define "ABSOLUTE_ADDONS_WIDGETS_PATH" "./../../../widgets/" */

/**
 * @var array $settings
 * @var string $portfolio_terms
 * @var string $style
 * @var string $figure_link
 * @var string $figure_attr
 */

require ABSOLUTE_ADDONS_WIDGETS_PATH . 'portfolio/template/template-figure.php';
?>
<div class="portfolio-content-wrapper">
	<div class="portfolio-content">
		<?php the_terms( get_the_ID(), 'portfolio_category', '<span>', '', '</span>' ); ?>
		<?php if ( $figure_link ) { ?>
			<a href="<?php echo esc_url( $figure_link ); ?>">
				<h3><?php the_title() ?></h3>
			</a>
		<?php } else { ?>
			<h3><?php the_title() ?></h3>
		<?php } ?>
	</div>
</div>
