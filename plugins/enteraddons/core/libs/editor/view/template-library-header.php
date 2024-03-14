<div class="elementor-templates-modal__header">
	<div class="elementor-templates-modal__header__logo-area">
		<div class="elementor-templates-modal__header__logo">
			<div v-if="isPreview" id="enteraddons-template-library-header-preview-back">
				<i class="eicon-chevron-left" aria-hidden="true"></i>
				<span><?php esc_html_e( 'Back to Library', 'enteraddons' ); ?></span>
			</div>
			<div v-else class="modal-header-logo">
				<span class="elementor-templates-modal__header__logo__icon-wrapper">
					<img src="<?php echo ENTERADDONS_DIR_URL.'assets/icon.png'; ?>" />
				</span>
				<span class="elementor-templates-modal__header__logo__title"><?php esc_html_e( 'Enteraddons', 'enteraddons' ); ?></span>
			</div>
		</div>
	</div>
	<div class="elementor-templates-modal__header__menu-area">
		<!--- elementor-active   elementor-component-tab elementor-template-library-menu-item -->
		<div v-for="tab in tabs" :key="tab.slug" @click="getItemsByType(tab.slug)" class="elementor-component-tab elementor-template-library-menu-item" :class='{"elementor-active": (set_active_tab === tab.slug)}'>
			<span>{{tab.title}}</span>
		</div>
	</div>
	
	<div class="elementor-templates-modal__header__items-area">
		<div class="enteraddons-dialog-lightbox-header-inner">
			<div class="elementor-templates-modal__header__close enteraddons-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item" @click="closeModal()">
				<i class="eicon-close" aria-hidden="true" title="<?php esc_html_e( 'Close', 'enteraddons' ); ?>"></i>
				<span class="elementor-screen-only"><?php esc_html_e( 'Close', 'enteraddons' ); ?></span>
			</div>
		</div>
	</div>

</div>