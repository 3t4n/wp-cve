<?php
/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file:
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 *
 * @package Spacer Module
 * @since 1.1.3
 */
?>

<div class="tnit-content tnit-spacer">
	<?php if ( FLBuilderModel::is_builder_active() ) { ?>
		<p class="tnit-user-msg"><?php esc_html_e( 'Click here to edit Spacer module.', 'xpro-bb-addons' ); ?></p>
	<?php } ?>
</div>
