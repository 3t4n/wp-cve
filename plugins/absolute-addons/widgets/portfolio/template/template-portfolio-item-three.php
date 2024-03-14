<?php
/**
 * Template for Portfolio Style Three
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

use Elementor\Icons_Manager;

require ABSOLUTE_ADDONS_WIDGETS_PATH . 'portfolio/template/template-figure.php';
?>
<div class="portfolio-content-wrapper">
	<div class="portfolio-content">
		<div class="portfolio-content-meta">
			<?php if ( $figure_link ) { ?>
				<a href="<?php echo esc_url( $figure_link ); ?>" <?php echo $figure_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<h3><?php the_title() ?></h3>
				</a>
			<?php } else { ?>
				<h3><?php the_title() ?></h3>
			<?php } ?>
			<?php the_terms( get_the_ID(), 'portfolio_category', '<span>', '', '</span>' ); ?>
		</div>
		<?php if ( 'yes' === $settings['portfolio_social_icon'] && ! empty( $settings['social'] ) ) { ?>
			<ul class="social-link">
				<?php foreach ( $settings['social'] as $social ) { ?>
					<li>
						<a href="<?php echo esc_url( $social['social_link']['url'] ) ?>"><?php Icons_Manager::render_icon( $social['social_icon'], [ 'aria-hidden' => 'true' ] ); ?></a>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
</div>
