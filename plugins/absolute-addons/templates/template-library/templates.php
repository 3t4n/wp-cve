<?php
/**
 * Template library script templates
 *
 * @package AbsoluteAddons
 * @version 1.0.0
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
?>
<script type="text/template" id="tmpl-absp-templates-modal__header-logo">
	<span class="absp-templates-modal__header__logo__icon-wrapper" aria-hidden="true">
		<i class="absp-lib-logo eicon" aria-hidden="true"></i>
	</span>
	<span class="absp-templates-modal__header__logo__title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__header-back">
	<i class="eicon-chevron-left" aria-hidden="true"></i>
	<span><?php esc_html_e( 'Back to Library', 'absolute-addons' ); ?></span>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
		<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__header-menu-responsive">
	<div class="elementor-component-tab absp-templates-modal__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'absolute-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'absolute-addons' ); ?></span>
	</div>
	<div class="elementor-component-tab absp-templates-modal__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'absolute-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'absolute-addons' ); ?></span>
	</div>
	<div class="elementor-component-tab absp-templates-modal__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'absolute-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'absolute-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__header-actions">
	<div id="absp-templates-modal__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'absolute-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'absolute-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__preview">
	<iframe></iframe>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ AbspLibrary.getModal().getTemplateActionButton( obj, 'header' ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__insert-button">
	<a class="elementor-template-library-template-action elementor-button absp-templates-modal__insert-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'absolute-addons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__pro-button">
	<a class="elementor-template-library-template-action elementor-button absp-templates-modal__pro-button" href="{{ obj.proLink }}" target="_blank" rel="noopener">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Get Pro', 'absolute-addons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'absolute-addons' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__templates">
	<div id="absp-templates-modal__toolbar" class="absp-templates-modal__toolbar">
		<div id="absp-templates-modal__toolbar-filter" class="absp-templates-modal__toolbar-filter">
			<div id="absp-templates-modal__toolbar-my-favorites" class="absp-templates-modal__toolbar-my-favorites">
				<a href="javascript:void(0)" id="absp-template-library-filter-my-favorites">
					<i class="eicon-heart-o" aria-hidden="true"></i>
					<?php esc_html_e( 'My Favorites', 'absolute-addons' ); ?>
				</a>
			</div>
		</div>
		<div class="absp-templates-modal__toolbar-search">
			<label for="absp-templates-modal__search" class="elementor-screen-only"><?php esc_html_e( 'Search Templates:', 'absolute-addons' ); ?></label>
			<input type="text" id="absp-templates-modal__search" placeholder="<?php esc_attr_e( 'Search', 'absolute-addons' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>
	<div class="absp-templates-modal__templates-window">
		<div class="absp-templates-modal__templates-list"></div>
	</div>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__template">
	<div class="absp-templates-modal__template-body" id="template-{{ template_id }}">
		<div class="absp-templates-modal__template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
		<div class="absp-templates-modal__template-thumbnail">{{{ thumbnail }}}</div>
		<# if ( obj.isPro ) { #>
		<span class="absp-templates-modal__template-badge"><?php esc_html_e( 'Pro', 'absolute-addons' ); ?></span>
		<# } #>
		<div class="absp-templates-modal__template-badge favorite">
			<# if ( favorite ) { #>
			<input id="absp-template-library-template-{{ template_id }}-favorite-input" class="absp-template-library-template-favorite-input" type="checkbox" checked>
			<# } else { #>
			<input id="absp-template-library-template-{{ template_id }}-favorite-input" class="absp-template-library-template-favorite-input" type="checkbox">
			<# } #>
			<label for="absp-template-library-template-{{ template_id }}-favorite-input" class="absp-template-library-template-favorite-label">
				<i class="eicon-heart-o" aria-hidden="true"></i>
				<# if ( favorite ) { #>
				<span class="elementor-screen-only"><?php esc_html_e( 'Add to favorites', 'absolute-addons' ); ?></span>
				<# } else { #>
				<span class="elementor-screen-only"><?php esc_html_e( 'Remove from favorites', 'absolute-addons' ); ?></span>
				<# } #>
			</label>
		</div>
	</div>
	<div class="absp-templates-modal__template-footer">
		<div class="elementor-template-library-template-name">{{{ title }}}</div>
		{{{ AbspLibrary.getModal().getTemplateActionButton( obj, 'footer' ) }}}
		<a href="#" class="elementor-button absp-templates-modal__preview-button">
			<i class="eicon-device-desktop" aria-hidden="true"></i>
			<?php esc_html_e( 'Preview', 'absolute-addons' ); ?>
		</a>
	</div>
</script>

<script type="text/template" id="tmpl-absp-templates-modal__empty">
	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo esc_url( ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg' ); ?>" class="elementor-template-library-no-results" alt="<?php esc_attr_e( 'No Result Found', 'absolute-addons' ); ?>" />
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		<?php esc_html_e( 'Want to learn more about the Absolute Library?', 'absolute-addons' ); ?>
		<a class="elementor-template-library-blank-footer-link" href="https://go.absoluteplugins.com/to/docs/absolute-library/" target="_blank"><?php esc_html_e( 'Click here', 'absolute-addons' ); ?></a>
	</div>
</script>
