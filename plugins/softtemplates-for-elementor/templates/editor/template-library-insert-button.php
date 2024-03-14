<# if ( window.SofttemplateThemeCoreData.license.activated ) { #>
<button class="elementor-template-library-template-action softtemplate-template-library-template-insert elementor-button elementor-button-success">
	<i class="eicon-file-download"></i><span class="elementor-button-title"><?php
		esc_html_e( 'Insert', 'soft-template-core' );
	?></span>
</button>
<# } else { #>
{{{ window.SofttemplateThemeCoreData.license.link }}}
<# } #>