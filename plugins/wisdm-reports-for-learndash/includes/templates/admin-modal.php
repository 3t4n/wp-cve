<div id="wrld-custom-modal" class="wrld-custom-popup-modal" wp_nonce=<?php echo esc_html( $wp_nonce ); ?>>
	<div class="wrld-modal-content">
		<div class="wrld-modal-content-container">
			<div class="wrld-modal-head">
				<span><p class="wrld-modal-head-text"><?php echo esc_html( $modal_head ); ?></p></span>
			</div>
			<div class="wrld-modal-text">
				<span><p class="wrld-modal-text"><?php echo $modal_description;// phpcs:ignore ?></p></span>
			</div>
			<div class="wrld-modal-actions">
				<div class="wrld-modal-action-item">
					<a href=<?php echo esc_attr( $info_url ); ?>>
						<button class="modal-button modal-button-reports wrld-modal-button <?php echo esc_attr( $action_close ); ?>"><?php echo esc_html( $modal_action_text ); ?><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
