<!--Site Laoder-->
<ul class="xpro-portfolio-loader xpro-portfolio-loader-style-<?php echo esc_attr( $settings['popup_animation'] ); ?>">
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
</ul>

<!--Preview-->
<div class="xpro-preview xpro-preview-<?php echo esc_attr( $settings['popup_layout'] ); ?>">

	<?php if ( 'layout-8' !== $settings['popup_layout'] && ( 'layout-9' !== $settings['popup_layout'] && 'layout-10' !== $settings['popup_layout'] ) ) : ?>
		<div class="xpro-preview-header">

			<!--Preview Left-->
			<div class="xpro-preview-header-left">

				<?php if ( 'layout-1' === $settings['popup_layout'] ) { ?>
					<div class="xpro-preview-header-col xpro-preview-header-arrow">
						<span class="xpro-preview-arrow xpro-preview-prev-demo xpro-preview-inactive"></span>
					</div>

					<div class="xpro-preview-header-col xpro-preview-header-arrow">
						<span class="xpro-preview-arrow xpro-preview-next-demo"></span>
					</div>
				<?php } ?>

				<div class="xpro-preview-header-col xpro-preview-header-info">
					<span class="xpro-preview-demo-name">Original</span>
				</div>
			</div>

			<!--Preview Right-->
			<div class="xpro-preview-header-right">

				<div class="xpro-preview-header-col xpro-preview-header-arrow">
					<span class="xpro-preview-arrow xpro-preview-close"></span>
				</div>

			</div>

		</div>
	<?php endif; ?>

	<?php if ( 'layout-8' === $settings['popup_layout'] || ( 'layout-9' === $settings['popup_layout'] || 'layout-10' === $settings['popup_layout'] ) ) : ?>
		<!--Close Button-->
		<span class="xpro-top-preview-arrow xpro-preview-close"></span>
	<?php endif; ?>

	<?php if ( 'layout-2' === $settings['popup_layout'] ) : ?>
		<div class="xpro-preview-nav-<?php echo esc_attr( $settings['popup_layout'] ); ?>">
			<span class="xpro-preview-arrow xpro-preview-prev-demo xpro-preview-inactive"><i
						class="fas fa-long-arrow-alt-left"></i> Previous</span>
			<span class="xpro-preview-arrow xpro-preview-next-demo"> Next <i
						class="fas fa-long-arrow-alt-right"></i></span>
		</div>
	<?php endif; ?>

	<?php if ( 'layout-3' === $settings['popup_layout'] || ( 'layout-4' === $settings['popup_layout'] || 'layout-7' === $settings['popup_layout'] ) ) : ?>
		<div class="xpro-preview-nav-<?php echo esc_attr( $settings['popup_layout'] ); ?>">
			<span class="xpro-preview-arrow xpro-preview-prev-demo xpro-preview-inactive"></span>
			<span class="xpro-preview-arrow xpro-preview-next-demo"></span>
		</div>
	<?php endif; ?>

	<?php if ( 'layout-5' === $settings['popup_layout'] || 'layout-6' === $settings['popup_layout'] ) : ?>
		<div class="xpro-preview-nav-<?php echo esc_attr( $settings['popup_layout'] ); ?>">
			<div class="xpro-preview-arrow xpro-preview-prev-demo xpro-preview-inactive">
				<i class="fas fa-chevron-left"></i>
				<span class="xpro-preview-nav-img"></span>
			</div>
			<div class="xpro-preview-arrow xpro-preview-next-demo">
				<span class="xpro-preview-nav-img"></span>
				<i class="fas fa-chevron-right"></i>
			</div>
		</div>
	<?php endif; ?>

	<div class="xpro-preview-iframe-outer">
		<iframe loading="lazy" class="xpro-preview-iframe" src=""></iframe>
	</div>

	<?php if ( 'layout-8' === $settings['popup_layout'] || ( 'layout-9' === $settings['popup_layout'] || 'layout-10' === $settings['popup_layout'] ) ) : ?>
		<!--Footer Area-->
		<div class="xpro-preview-footer xpro-preview-footer-<?php echo esc_attr( $settings['popup_layout'] ); ?>">

			<div class="xpro-preview-footer-left">
				<div class="xpro-preview-arrow xpro-preview-prev-demo xpro-preview-inactive">
					<i class="fas fa-long-arrow-alt-left"></i>
					<?php if ( 'layout-8' === $settings['popup_layout'] || 'layout-9' === $settings['popup_layout'] ) : ?>
						<div class="xpro-preview-footer-text">
							<span>Previous</span>
							<span class="title"></span>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="xpro-preview-footer-center">
				<div class="xpro-preview-header-info">
					<span class="xpro-preview-demo-name"></span>
				</div>
				<!--Image Thumbnail-->
				<?php if ( 'layout-10' === $settings['popup_layout'] ) : ?>
					<div class="xpro-preview-thumbnails">
						<span class="xpro-preview-prev-thumb"></span>
						<span class="xpro-preview-current-thumb"></span>
						<span class="xpro-preview-next-thumb"></span>
					</div>
				<?php endif; ?>
			</div>

			<div class="xpro-preview-footer-right">
				<div class="xpro-preview-arrow xpro-preview-next-demo">
					<?php if ( 'layout-8' === $settings['popup_layout'] || 'layout-9' === $settings['popup_layout'] ) : ?>
						<div class="xpro-preview-footer-text">
							<span>Next</span>
							<span class="title"></span>
						</div>
					<?php endif; ?>
					<i class="fas fa-long-arrow-alt-right"></i>
				</div>
			</div>

		</div>
	<?php endif; ?>

</div>
