<form class="xpro-elementor-search-wrapper xpro-elementor-search-layout-<?php use Elementor\Icons_Manager;

echo esc_attr( $settings['layout'] ); ?>" method="get" id="searchform" autocomplete="off" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">

	<div class="xpro-elementor-search-inner">
		<label class="sr-only" for="s"><?php esc_html_e( 'Search', 'xpro-elementor-addons' ); ?></label>
		<div class="xpro-elementor-search-input-group">
			<input class="field form-control" id="s" name="s" type="text" placeholder="<?php echo esc_html( $settings['placeholder'] ); ?>" value="<?php the_search_query(); ?>">
			<?php if ( '4' !== $settings['layout'] && '5' !== $settings['layout'] ) : ?>
				<button id="searchsubmit" class="xpro-elementor-search-button" type="submit">
					<?php
					if ( 'text' === $settings['button_type'] ) {
						echo esc_html( $settings['button_text'] );
					} else {
						Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );
					}
					?>
				</button>
			<?php endif; ?>
		</div>
		<?php if ( '4' === $settings['layout'] || '5' === $settings['layout'] ) : ?>
			<button class="xpro-elementor-search-button-close" type="button">
			</button>
		<?php endif; ?>
		<input type="hidden" name="post_type" value="<?php echo esc_html( $settings['post_type'] ); ?>">
	</div>

	<?php if ( '4' === $settings['layout'] || '5' === $settings['layout'] ) : ?>
		<button class="xpro-elementor-search-button" type="button">
			<?php
			if ( 'text' === $settings['button_type'] ) {
				echo esc_html( $settings['button_text'] );
			} else {
				Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );
			}
			?>
		</button>
	<?php endif; ?>
</form>
