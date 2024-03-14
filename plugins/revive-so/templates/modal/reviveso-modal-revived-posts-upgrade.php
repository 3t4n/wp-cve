<div class="reviveso-modal__overlay revived_posts">
	<div class="reviveso-modal__frame <?php echo esc_attr($settings['classes']); ?>" <?php if ( $settings['dismissible'] ) : ?>data-reviveso-modal-dismissible data-reviveso-modal-id="<?php echo esc_attr($id); ?>"<?php endif; ?>>
		<div class="reviveso-modal__header">
			<button class="reviveso-modal__dismiss">
				<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg>
			</button>
		</div>
		<div class="reviveso-modal__body">
			<div class="reviveso-upsells-carousel-wrapper-modal">
				<div class="reviveso-upsells-carousel-modal">
					<div class="reviveso-upsell-modal reviveso-upsell-item-modal">
						<h2><?php esc_html_e( 'Revive.so PRO', 'reviveso-best-grid-gallery' ); ?></h2>
						<h4 class="reviveso-upsell-description-modal"><?php esc_html_e( 'Revive.so PRO grants you even more control over your content allowing you to auto post your republished posts to social media and offer even more republishing customizations for your posts.', 'reviveso-best-grid-gallery' ); ?></h4>
						<ul class="reviveso-upsells-list-modal">
							<li><span class="dashicons dashicons-yes"></span><strong>Customized Republishing:</strong> Tailor republishing parameters for each post.</li>
							<li><span class="dashicons dashicons-yes"></span><strong>Automated Social Sharing:</strong> Share content on Facebook, Twitter, etc., automatically.</li>
							<li><span class="dashicons dashicons-yes"></span><strong>Manual Republishing:</strong> Repost content manually for strategic timing.</li>
							<li><span class="dashicons dashicons-yes"></span><strong>Email Notifications:</strong> Stay updated on republishing activities via email.</li>
							<li><span class="dashicons dashicons-yes"></span><strong>Short URL:</strong> Create concise, visually appealing links for sharing.</li>
							<li><span class="dashicons dashicons-yes"></span><strong>AI Rephrasing:</strong> Utilize AI to rephrase initial paragraphs for variety.</li>
						</ul>
						<p>
							<?php
 							$link = 'https://revive.so/pricing/?utm_source=reviveso-lite&utm_medium=rewriting-tab&utm_campaign=upsell';
							$buttons .= '<a target="_blank" href="https://revive.so/pricing/?utm_source=reviveso-lite&utm_medium=revived_posts_page&utm_campaign=admin_modal" style="margin-top:10px;" class="button-primary button">' . esc_html__( 'Get Premium!', 'reviveso-best-grid-gallery' ) . '</a>';

							echo apply_filters( 'reviveso_upsell_buttons', $buttons, 'revived_posts' );

							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

