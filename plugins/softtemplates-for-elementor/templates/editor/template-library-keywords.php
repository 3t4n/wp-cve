<?php
/**
 * Keywords template
 */
?>
<#
	if ( ! _.isEmpty( keywords ) ) {
#>
<select class="softtemplate-library-keywords">
	<option value=""><?php esc_html_e( 'Any Topic', 'soft-template-core' ); ?></option>
	<# _.each( keywords, function( title, slug ) { #>
	<option value="{{ slug }}">{{ title }}</option>
	<# } ); #>
</select>
<#
	}
#>