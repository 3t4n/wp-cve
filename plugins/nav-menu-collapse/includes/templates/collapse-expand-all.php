<?php
	/*!
	 * Collapse/expand all template.
	 * 
	 * @since 2.0.3 Added large class to button elements.
	 * @since 2.0.0
	 * 
	 * @package Nav Menu Collapse
	 */

	if (!defined('ABSPATH'))
	{
		exit;
	}
?>

<script id="tmpl-nmc-collapse-expand-all" type="text/html">

	<div id="nmc-collapse-expand-all">

		<button class="button button-large nmc-button" id="nmc-collapse-all" type="button"><?php _e('Collapse All', 'nav-menu-collapse'); ?></button>
		<button class="button button-large nmc-button" id="nmc-expand-all" type="button"><?php _e('Expand All', 'nav-menu-collapse'); ?></button>
		
		<?php wp_nonce_field(Nav_Menu_Collapse_Constants::HOOK_COLLAPSED, Nav_Menu_Collapse_Constants::HOOK_COLLAPSED); ?>

	</div>
	
</script>
