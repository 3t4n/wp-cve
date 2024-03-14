<div class="elementor-templates-modal__header">
	<div class="elementor-templates-modal__header__logo-area">
		<div class="elementor-templates-modal__header__logo">
			
			<div id="enteraddons-template-library-header-preview-back" @click="backToLibrary()">
				<i class="eicon-chevron-left" aria-hidden="true"></i>
				<span><?php esc_html_e( 'Back to Library', 'enteraddons' ); ?></span>
			</div>
		</div>
	</div>
		
	<div class="elementor-templates-modal__header__items-area">
		<div class="enteraddons-dialog-lightbox-header-inner enteraddons-dialog-lightbox-preview-header-inner">
			<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
				<!-- Insert button -->
				<a class="elementor-template-library-template-action elementor-template-library-template-insert enteraddons-template-insert elementor-button" @click="insertTemplate(tempid)">

					<i class="eicon-file-download" aria-hidden="true"></i>
					<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'enteraddons' ); ?></span>
				</a>
				<!-- Go pro button -->
				<a v-if="package_type == 'pro'" class="elementor-template-library-template-action elementor-button elementor-go-pro" :href="pro_url" target="_blank">
					<i class="eicon-external-link-square" aria-hidden="true"></i>
					<span class="elementor-button-title"><?php esc_html_e( 'Go Pro', 'enteraddons' ); ?></span>
				</a>
			</div>
			<div class="elementor-templates-modal__header__close enteraddons-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item" @click="closeModal()">
				<i class="eicon-close" aria-hidden="true" title="<?php esc_html_e( 'Close', 'enteraddons' ); ?>"></i>
				<span class="elementor-screen-only"><?php esc_html_e( 'Close', 'enteraddons' ); ?></span>
			</div>
		</div>
	</div>

</div>