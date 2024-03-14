<?php
/**
 * Figure Template for portfolio items
 *
 * @package AbsoluteAddons
 */

/**
 * @var array $settings
 * @var string $portfolio_terms
 * @var string $style
 * @var string $figure_link
 * @var string $figure_attr
 */
?>
<figure>
	<?php if ( $figure_link && has_post_thumbnail() ) { ?>
		<a href="<?php echo esc_url( $figure_link ); ?>" <?php echo $figure_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php the_post_thumbnail(); ?></a>
		<?php
	} else {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail();
		}
	}
	?>
</figure>
