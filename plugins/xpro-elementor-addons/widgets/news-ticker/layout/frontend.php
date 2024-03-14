<!-- News Ticker -->
<div class="xpro-news-ticker-wrapper">

	<?php

	use Elementor\Group_Control_Image_Size;
	use Elementor\Icons_Manager;

	if ( $settings['title'] ) : ?>
		<div class="xpro-news-ticker-sticky-title xpro-news-ticker-separator-<?php echo esc_attr( $settings['news_ticker_heading_separator_style'] ); ?>">
			<?php if ( 'icon' === $settings['heading_media_type'] || 'image' === $settings['heading_media_type'] ) : ?>
				<span class="xpro-news-ticker-heading-box">
				<?php
				if ( 'icon' === $settings['heading_media_type'] && $settings['heading_icon'] ) {
					Icons_Manager::render_icon( $settings['heading_icon'], array( 'aria-hidden' => 'true' ) );
				}
				if ( 'image' === $settings['heading_media_type'] ) {
					echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'heading_media_thumbnail', 'heading_image' ) );
				}
				?>
			</span>
			<?php endif; ?>
			<span class="xpro-news-ticker-heading-text"><?php echo esc_html( $settings['title'] ); ?></span>
		</div>
	<?php endif; ?>

	<!-- Owl Carousel -->
	<div class="xpro-news-ticker xpro-owl-theme owl-carousel">
		<?php foreach ( $settings['item'] as $k => $item ) : ?>
			<div class="xpro-news-ticker-item">
				<?php if ( 'icon' === $item['content_media_type'] || 'image' === $item['content_media_type'] ) : ?>
					<span class="xpro-news-ticker-box">
					<?php
					/* Icon */
					if ( 'icon' === $item['content_media_type'] && $item['content_icon'] ) {
						Icons_Manager::render_icon( $item['content_icon'], array( 'aria-hidden' => 'true' ) );
					}
					/* Image */
					if ( 'image' === $item['content_media_type'] ) {
						echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'content_media_thumbnail', 'content_image' ) );
					}
					?>
				</span>
				<?php endif; ?>


				<?php if ( $item['description'] ) : ?>
					<!-- description -->
					<span class="xpro-news-ticker-description"><?php echo wp_kses_post( $item['description'] ); ?></span>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<!-- navigation -->
	<div class="xpro-news-ticker-navigation xpro-news-ticker-icon-<?php echo esc_attr( $settings['news_ticker_heading_nav_style'] ); ?>">

		<?php if ( 'yes' === $settings['news_ticker_separator_switch'] ) : ?>
			<!-- Separator -->
			<span class="xpro-news-ticker-separator"></span>
		<?php endif; ?>

		<?php

		$prev_btn = ( 'arrow' === $settings['news_ticker_heading_nav_style'] ) ? 'fa-angle-left' : ( ( 'long-angle' === $settings['news_ticker_heading_nav_style'] ) ? 'fa-long-arrow-alt-left' : 'fa-arrow-alt-circle-left' );
		$next_btn = ( 'arrow' === $settings['news_ticker_heading_nav_style'] ) ? 'fa-angle-right' : ( ( 'long-angle' === $settings['news_ticker_heading_nav_style'] ) ? 'fa-long-arrow-alt-right' : 'fa-arrow-alt-circle-right' );

		if ( 'yes' === $settings['custom_nav'] ) {
			?>
			<span class="xpro-news-ticker-prev">
				<i class="fas <?php echo esc_attr( $prev_btn ); ?>"></i>
			</span>
			<span class="xpro-news-ticker-next">
				<i class="fas <?php echo esc_attr( $next_btn ); ?>"></i>
			</span>
			<?php
		}


		if ( 'yes' === $settings['custom_close_nav'] ) {
			?>
			<span class="xpro-news-ticker-close">
				<i class="fas fa-times <?php echo esc_attr( $settings['custom_close_nav'] ); ?>"></i>
			</span>
			<?php
		}

		?>
	</div>
</div>
