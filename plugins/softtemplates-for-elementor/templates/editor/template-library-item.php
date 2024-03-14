<?php
/**
 * Template item
 */
?>
<# var activeTab = window.SofttemplateThemeCoreData.tabs[ type ]; #>
<div class="elementor-template-library-template-body">
	<# if ( 'softtemplate-local' !== source ) { #>
	<div class="elementor-template-library-template-screenshot">
		<# if ( 'softtemplate-local' !== source ) { #>
		<div class="elementor-template-library-template-preview">
			<i class="fa fa-search-plus"></i>
		</div>
		<# } #>
		<img src="{{ thumbnail }}" alt="">
	</div>
	<# } #>
</div>
<div class="elementor-template-library-template-controls">
	<# if ( 'softtemplate-local' === source || window.SofttemplateThemeCoreData.license.activated ) { #>
	<button class="elementor-template-library-template-action softtemplate-template-library-template-insert elementor-button elementor-button-success">
		<i class="eicon-file-download"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'soft-template-core' ); ?></span>
	</button>
	<# } else if ( 'softtemplate-local' !== source ) { #>
		{{{ window.SofttemplateThemeCoreData.license.link }}}
	<# } #>
	<# if ( 'softtemplate-local' !== source && window.SofttemplateThemeCoreData.license.activated ) { #>
	<button class="softtemplate-clone-to-library">
		<i class="fa fa-files-o" aria-hidden="true"></i>
		<?php esc_html_e( 'Clone to Library', 'soft-template-core' ); ?>
	</button>
	<# } #>
</div>
<# if ( 'softtemplate-local' === source || true == activeTab.settings.show_title ) { #>
<div class="elementor-template-library-template-name">{{{ title }}}</div>
<# } else { #>
<div class="elementor-template-library-template-name-holder"></div>
<# } #>
<# if ( 'softtemplate-local' === source ) { #>
<div class="elementor-template-library-template-type">
	<?php esc_html_e( 'Type:', 'soft-template-core' ); ?> {{{ typeLabel }}}
</div>
<# } #>