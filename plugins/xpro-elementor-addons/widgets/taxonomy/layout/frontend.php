<?php

use Elementor\Icons_Manager;

global $wp;
$hierarchical = isset( $item['hierarchical'] ) ? $item['hierarchical'] : true;

$term_data = get_terms(
	array(
		'taxonomy'   => $settings['taxonomy_type'],
		'orderby'    => $settings['orderby'],
		'order'      => $settings['order'],
		'hide_empty' => ( 'yes' === $settings['hide_empty'] ),
		'exclude'    => $settings['exclude'],
	)
);

$current_term_id = get_queried_object() && isset( get_queried_object()->term_id ) ? get_queried_object()->term_id : '';
$current_url     = home_url( $wp->request . '/' );

?>

<div class="xpro-taxonomy-wrapper xpro-taxonomy-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
	<?php
	if ( $term_data ) {
		?>
		<ul class="xpro-taxonomy-list">
			<?php if ( 'yes' === $settings['show_custom'] && $settings['custom_text'] ) { ?>
				<li class="xpro-taxonomy-list-item<?php echo esc_attr( isset( $settings['custom_link']['url'] ) && $current_url === $settings['custom_link']['url'] ) ? ' current-taxonomy' : ''; ?>">
					<a href="<?php echo esc_url( $settings['custom_link']['url'] ? $settings['custom_link']['url'] : '' ); ?>">
						<?php if ( $settings['icon']['value'] ) { ?>
							<span class="xpro-taxonomy-media">
								<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
						<?php } ?>
						<div class="xpro-taxonomy-list-content">
							<span class="xpro-taxonomy-list-title">
								<?php echo esc_html( $settings['custom_text'] ); ?>
							</span>
							<?php if ( 'yes' === $settings['show_count'] ) : ?>
								<span class="xpro-taxonomy-list-count">
								<?php echo esc_html( $settings['custom_count'] ); ?>
							</span>
							<?php endif; ?>
						</div>
					</a>
				</li>
			<?php } ?>
			<?php foreach ( $term_data as $taxonomy_data ) : ?>
				<li class="xpro-taxonomy-list-item<?php echo esc_attr( $current_term_id === $taxonomy_data->term_id ) ? ' current-taxonomy' : ''; ?>">
					<a href="<?php echo esc_attr( get_term_link( $taxonomy_data->term_id ) ); ?>">
						<?php if ( $settings['icon']['value'] ) { ?>
							<span class="xpro-taxonomy-media">
								<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
						<?php } ?>
						<div class="xpro-taxonomy-list-content">
							<span class="xpro-taxonomy-list-title">
								<?php echo esc_html( $taxonomy_data->name ); ?>
							</span>
							<?php if ( 'yes' === $settings['show_count'] ) : ?>
								<span class="xpro-taxonomy-list-count">
								<?php echo esc_html( $taxonomy_data->count ); ?>
							</span>
							<?php endif; ?>
						</div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	} else {
		?>
		<p class="xpro-alert xpro-alert-warning">
			<span class="xpro-alert-title"><?php esc_html_e( 'No Taxonomy Found!', 'xpro-elementor-addons' ); ?></span>
			<span class="xpro-alert-description"><?php esc_html_e( 'Sorry, but nothing matched your selection. Please try again with some different keywords.', 'xpro-elementor-addons' ); ?></span>
		</p>
		<?php
	}
	?>
</div>
