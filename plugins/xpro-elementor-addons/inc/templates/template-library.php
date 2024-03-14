<?php
/**
 * Template library templates
 */

defined( 'ABSPATH' ) || exit;

?>
<script type="text/template" id="tmpl-xproTemplateLibrary__header-logo">
	<span class="xproTemplateLibrary__logo-wrap">
		<i class="xi xi-xpro"></i>
	</span>
	<span class="xproTemplateLibrary__logo-title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php esc_html_e( 'Back to Library', 'xpro-elementor-addons' ); ?></span>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
	<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{
		args.title }}}
	</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__header-menu-responsive">
	<div class="elementor-component-tab xproTemplateLibrary__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'xpro-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'xpro-elementor-addons' ); ?></span>
	</div>
	<div class="elementor-component-tab xproTemplateLibrary__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'xpro-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'xpro-elementor-addons' ); ?></span>
	</div>
	<div class="elementor-component-tab xproTemplateLibrary__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'xpro-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'xpro-elementor-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__header-actions">
	<div id="xproTemplateLibrary__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'xpro-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'xpro-elementor-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__preview">
	<iframe></iframe>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ xpro.library.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__insert-button">
	<a class="elementor-template-library-template-action elementor-button xproTemplateLibrary__insert-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'xpro-elementor-addons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__pro-button">
	<a class="elementor-template-library-template-action elementor-button xproTemplateLibrary__pro-button" href="https://elementor.wpxpro.com/buy" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Get Pro', 'xpro-elementor-addons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'xpro-elementor-addons' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__templates">
	<div id="xproTemplateLibrary__toolbar">
		<div id="xproTemplateLibrary__toolbar-filter" class="xproTemplateLibrary__toolbar-filter">
			<# if (xpro.library.getTypeTags()) { var selectedTag = xpro.library.getFilter( 'tags' ); #>
			<# if ( selectedTag ) { #>
			<span class="xproTemplateLibrary__filter-btn">{{{ xpro.library.getTags()[selectedTag] }}} <i
						class="eicon-caret-right"></i></span>
			<# } else { #>
			<span class="xproTemplateLibrary__filter-btn"><?php esc_html_e( 'Filter', 'xpro-elementor-addons' ); ?> <i
						class="eicon-caret-right"></i></span>
			<# } #>
			<ul id="xproTemplateLibrary__filter-tags" class="xproTemplateLibrary__filter-tags">
				<li data-tag="">All</li>
				<# _.each(xpro.library.getTypeTags(), function(slug) {
				var selected = selectedTag === slug ? 'active' : '';
				#>
				<li data-tag="{{ slug }}" class="{{ selected }}">{{{ xpro.library.getTags()[slug] }}}</li>
				<# } ); #>
			</ul>
			<# } #>
		</div>
		<div id="xproTemplateLibrary__toolbar-counter"></div>
		<div id="xproTemplateLibrary__toolbar-search">
			<label for="xproTemplateLibrary__search" class="elementor-screen-only">
				<?php esc_html_e( 'Search Templates:', 'xpro-elementor-addons' ); ?>
			</label>
			<input id="xproTemplateLibrary__search" placeholder="<?php esc_attr_e( 'Search', 'xpro-elementor-addons' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>

	<div class="xproTemplateLibrary__templates-window">
		<div id="xproTemplateLibrary__templates-list"></div>
	</div>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__template">
	<div class="xproTemplateLibrary__template-body" id="xproTemplate-{{ template_id }}">
		<div class="xproTemplateLibrary__template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
		<img class="xproTemplateLibrary__template-thumbnail" src="{{ thumbnail }}" alt="thumbnail">
		<# if ( obj.isPro ) { #>
		<span class="xproTemplateLibrary__template-badge"><?php esc_html_e( 'Pro', 'xpro-elementor-addons' ); ?></span>
		<# } #>
	</div>
	<div class="xproTemplateLibrary__template-footer">
		{{{ xpro.library.getModal().getTemplateActionButton( obj ) }}}
		<a href="#" class="elementor-button xproTemplateLibrary__preview-button">
			<i class="eicon-device-desktop" aria-hidden="true"></i>
			<?php esc_html_e( 'Preview', 'xpro-elementor-addons' ); ?>
		</a>
	</div>
</script>

<script type="text/template" id="tmpl-xproTemplateLibrary__empty">
	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo esc_url( ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg' ); ?>" class="elementor-template-library-no-results" alt="no-result"/>
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		<?php esc_html_e( 'Want to learn more about the Xpro Library?', 'xpro-elementor-addons' ); ?>
		<a class="elementor-template-library-blank-footer-link" href="https://elementor.wpxpro.com/docs/" target="_blank"><?php esc_html_e( 'Click here', 'xpro-elementor-addons' ); ?></a>
	</div>
</script>
