<?php
/**
 * Template Library Header Template
 */
?>
<label class="softtemplate-template-library-filter-label">
	<input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="softtemplate-library-filter">
	<span>{{ title }}</span>
</label>