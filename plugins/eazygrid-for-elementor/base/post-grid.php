<?php

namespace EazyGrid\Elementor\Base;

abstract class Post_Grid extends EazyGrid_Base {

	protected function render_badge( $show_badge ) {

		if ( 'yes' !== $show_badge || ! ezg_ele_the_first_category( get_the_ID(), [ 'class' => 'ezg-ele-post-grid-badge-text' ], false ) ) {
			return;
		}
		?>
			<div class="ezg-ele-post-grid-badge">
				<?php ezg_ele_the_first_category( get_the_ID(), [ 'class' => 'ezg-ele-post-grid-badge-text' ] ); ?>
			</div>
		<?php
	}

	protected function render_title( $show_title, $title_tag ) {

		if ( 'yes' === $show_title && get_the_title() ) {
			printf( '<%1$s %2$s><a href="%3$s">%4$s</a></%1$s>',
				tag_escape( $title_tag ),
				'class="ezg-ele-post-grid-title"',
				esc_url( get_the_permalink( get_the_ID() ) ),
				esc_html( get_the_title() )
			);
		}
	}

	protected function render_excerpt( $excerpt_length = false ) {
		if ( empty( $excerpt_length ) ) {
			return;
		}
		?>
			<div class="ezg-ele-post-grid-excerpt">
				<p><?php echo esc_html( ezg_ele_get_excerpt( '', $excerpt_length ) ); ?></p>
			</div>
		<?php
	}

	protected function render_read_more( $read_more_text = false, $new_tab = false ) {
		if ( $read_more_text ) {
			printf(
				'<div class="%1$s"><a href="%2$s" target="%3$s">%4$s</a></div>',
				'ezg-ele-post-grid-readmore',
				esc_url( get_the_permalink( get_the_ID() ) ),
				'yes' === $new_tab ? '_blank' : '_self',
				esc_html( $read_more_text )
			);
		}
	}

	protected function render_meta( $active_meta, $has_icon ) {
		if ( empty( $active_meta ) ) {
			return;
		}
		?>
			<div class="ezg-ele-post-grid-meta-wrap">
				<ul>
					<?php if ( in_array( 'author', $active_meta ) ) : ?>
						<li class="ezg-ele-post-grid-author">
						<?php $this->render_author( $has_icon ); ?>
						</li>
					<?php endif; ?>
					<?php if ( in_array( 'date', $active_meta ) ) : ?>
						<li class="ezg-ele-post-grid-date">
							<?php $this->render_date( $has_icon ); ?>
						</li>
					<?php endif; ?>
					<?php if ( in_array( 'comments', $active_meta ) ) : ?>
						<li class="ezg-ele-post-grid-comment">
							<?php $this->render_comments( $has_icon ); ?>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		<?php
	}

	protected function render_author( $has_icon = true ) {
		?>
		<span class="ezg-ele-post-grid__author-text">
			<?php if ( $has_icon ) : ?>
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M30 26.4V29c0 1.7-1.3 3-3 3H5c-1.7 0-3-1.3-3-3v-2.6c0-4.6 3.8-8.4 8.4-8.4h1c1.4 0.6 2.9 1 4.6 1s3.2-0.4 4.6-1h1C26.2 18 30 21.8 30 26.4zM8 8c0-4.4 3.6-8 8-8s8 3.6 8 8 -3.6 8-8 8S8 12.4 8 8z"/></svg>
			<?php endif; ?>
			<?php the_author(); ?>
		</span>
		<?php
	}

	protected function render_date( $has_icon = true ) {
		?>
		<span class="ezg-ele-post-grid__date-text">
			<?php if ( $has_icon ) : ?>
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M32 16c0 8.8-7.2 16-16 16S0 24.8 0 16 7.2 0 16 0 32 7.2 32 16zM22.2 19.5c0-0.3-0.2-0.6-0.4-0.8L18.1 16V6.7c0-0.6-0.5-1-1-1H15c-0.6 0-1 0.5-1 1v10 0c0 0.7 0.4 1.6 1 2l4.3 3.2c0.2 0.1 0.4 0.2 0.6 0.2 0.3 0 0.6-0.2 0.8-0.4l1.3-1.6C22.1 20 22.2 19.7 22.2 19.5z"/></svg>
			<?php endif; ?>
			<?php the_time( get_option( 'date_format' ) ); ?>
		</span>
		<?php
	}

	protected function render_comments( $has_icon = true ) {
	}

	/**
	 * @return mixed
	 */
	public function get_cat_list() {
		$all           = [];
		$categories    = get_categories( [
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
		] );
		$all['recent'] = esc_html__( 'Recent Posts', 'eazygrid-elementor' );
		foreach ( $categories as $category ) {
			$all[ $category->term_id ] = $category->name;
		}

		return $all;
	}
}
