<div id="qodef-dashboard-widget">
	<div  class="qodef-dw-header">
		<div class="qodef-dw-logo">
			<a href="https://qodeinteractive.com/?utm_source=dash&utm_medium=wp&utm_campaign=widget">
				<img src="<?php echo QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/dashboard-widgets/assets/img/qode-logo.png'; ?>" alt="<?php esc_attr_e( 'Qode Interactive', 'qi-addons-for-elementor' ); ?>" width="32" />
				<?php esc_html_e( 'Qode Interactive', 'qi-addons-for-elementor' ); ?>
			</a>
		</div>
		<div class="qodef-dw-market-link">
			<a href="https://qodeinteractive.com/?utm_source=dash&utm_medium=wp&utm_campaign=widget">
				<span aria-hidden="true" class="dashicons dashicons-external"></span>
				<?php esc_html_e( 'Shop themes', 'qi-addons-for-elementor' ); ?>
			</a>
		</div>
	</div>
	<div class="qodef-dw-sticky-item qodef-dw-box">
		<div class="qodef-dw-sticky-item-image">
			<a href="<?php echo esc_url( $special_post['link'] ); ?>?utm_source=dash&utm_medium=wp&utm_campaign=widget" target="_blank">
				<img src="<?php echo esc_url( $special_post['img_url'] ); ?>" alt="<?php esc_attr( $special_post['title'] ); ?>" />
			</a>
		</div>
		<div class="qodef-dw-sticky-item-text">
			<h3><a href="<?php echo esc_url( $special_post['link'] ); ?>?utm_source=dash&utm_medium=wp&utm_campaign=widget" target="_blank"><?php echo esc_html( $special_post['title'] ); ?></a></h3>
		</div>
	</div>
	<div class="qodef-dw-news-list qodef-dw-box">
		<h2><?php esc_html_e( 'Latest Magazine Articles', 'qi-addons-for-elementor' ); ?></h2>
		<?php foreach ( $magazine_posts as $magazine_post ) : ?>
			<div class="qodef-dw-news-list-item">
				<div class="qodef-dw-news-list-item-image">
					<a href="<?php echo esc_url( $magazine_post['link'] ); ?>?utm_source=dash&utm_medium=wp&utm_campaign=widget" target="_blank">
						<img src="<?php echo esc_url( $magazine_post['img_url'] ); ?>" alt="<?php esc_attr( $magazine_post['title'] ); ?>" />
					</a>
				</div>
				<div class="qodef-dw-news-list-item-text">
					<h3><a href="<?php echo esc_url( $magazine_post['link'] ); ?>?utm_source=dash&utm_medium=wp&utm_campaign=widget" target="_blank"><?php echo esc_html( $magazine_post['title'] ); ?></a></h3>
					<p><?php echo wp_kses_post( substr( $magazine_post['excerpt'], 0, 65 ) ); ?><?php echo strlen( $magazine_post['excerpt'] ) > 110 ? '...' : ''; ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="qodef-dw-magazine qodef-dw-box">
		<h2><?php esc_html_e( 'Read all our articles', 'qi-addons-for-elementor' ); ?></h2>
		<a href="https://qodeinteractive.com/magazine/?utm_source=dash&utm_medium=wp&utm_campaign=widget" target="_blank">
			<span aria-hidden="true" class="dashicons dashicons-external"></span>
			<?php esc_html_e( 'Qode Magazine', 'qi-addons-for-elementor' ); ?>
		</a>
	</div>
	<div class="qodef-dw-support qodef-dw-box">
		<h2><?php esc_html_e( 'Support', 'qi-addons-for-elementor' ); ?></h2>
		<p><?php esc_html_e( 'Our support team is always there to help you out with any questions or issues you may come across.', 'qi-addons-for-elementor' ); ?></p>
		<div class="qodef-dw-support-links">
			<div class="qodef-dw-support-row">
				<div class="qodef-dw-support-cell">
					<a href="https://qodeinteractive.com/qi-addons-for-elementor/documentation/?utm_source=dash&utm_medium=wp&utm_campaign=widget" target="_blank">
						<span aria-hidden="true" class="dashicons dashicons-external"></span>
						<?php esc_html_e( 'Documentation', 'qi-addons-for-elementor' ); ?>
					</a>
				</div>
				<div class="qodef-dw-support-cell">
					<a href="https://helpcenter.qodeinteractive.com/?utm_source=dash&utm_medium=wp&utm_campaign=widget" target="_blank">
						<span aria-hidden="true" class="dashicons dashicons-external"></span>
						<?php esc_html_e( 'Knowledge Base', 'qi-addons-for-elementor' ); ?>
					</a>
				</div>
			</div>
			<div class="qodef-dw-support-row">
				<div class="qodef-dw-support-cell">
					<a href="https://www.youtube.com/c/QodeInteractiveVideos/" target="_blank">
						<span aria-hidden="true" class="dashicons dashicons-external"></span>
						<?php esc_html_e( 'Video Tutorials', 'qi-addons-for-elementor' ); ?>
					</a>
				</div>
				<div class="qodef-dw-support-cell">
					<a href="https://qodeinteractive.com/submit-a-request/?utm_source=dash&utm_medium=wp&utm_campaign=widget" target="_blank">
						<span aria-hidden="true" class="dashicons dashicons-external"></span>
						<?php esc_html_e( 'Submit Ticket', 'qi-addons-for-elementor' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="qodef-dw-social qodef-dw-box">
		<div class="qodef-dw-social-inner">
			<a href="https://www.facebook.com/QodeInteractive/" target="_blank">
				<span class="dashicons dashicons-facebook-alt"></span>
				<?php esc_html_e( 'Facebook', 'qi-addons-for-elementor' ); ?>
			</a>
			<a href="https://www.instagram.com/qodeinteractive/" target="_blank">
				<span class="dashicons dashicons-instagram"></span>
				<?php esc_html_e( 'Instagram', 'qi-addons-for-elementor' ); ?>
			</a>
			<a href="https://twitter.com/QodeInteractive/" target="_blank">
				<span class="dashicons dashicons-twitter"></span>
				<?php esc_html_e( 'Twitter', 'qi-addons-for-elementor' ); ?>
			</a>
		</div>
	</div>
</div>
