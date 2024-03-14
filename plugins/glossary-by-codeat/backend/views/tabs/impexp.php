<?php
/**
 * Represents the view for the administration dashboard.
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 3.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 *
 * @phpcs:disable WordPress.Security.EscapeOutput
 */
?>
<div id="tabs-impexp" class="metabox-holder">
	<div class="postbox">
		<h3 class="hndle"><span><?php _e( 'Export Settings', GT_TEXTDOMAIN ); ?></span>
			<p class="cmb2-metabox-description"><?php _e( 'Here you can Import/Export Glossary\'s settings from/to other WordPress installations. For more details head over to our documentation', GT_TEXTDOMAIN ); ?></p>
		</h3>
		<div class="inside">
			<p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', GT_TEXTDOMAIN ); ?></p>
			<form method="post">
				<p><input type="hidden" name="g_action" value="export_settings" /></p>
				<p>
					<?php wp_nonce_field( 'g_export_nonce', 'g_export_nonce' ); ?>
					<?php submit_button( __( 'Export', GT_TEXTDOMAIN ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div>
	</div>

	<div class="postbox">
		<h3 class="hndle"><span><?php _e( 'Import Settings', GT_TEXTDOMAIN ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', GT_TEXTDOMAIN ); ?></p>
			<p><?php _e( 'If you are interested in obtaining the settings utilized in the <a href="https://demo.codeat.co/glossary" target="_blank">demonstration of our website</a>, you can acquire them through this <a href="https://docs.codeat.co/glossary/gt-settings-demo-export.json" target="_blank">link</a> and import them using this box.', GT_TEXTDOMAIN ); ?></p>
			<form method="post" enctype="multipart/form-data">
				<p>
					<input type="file" name="g_import_file"/>
				</p>
				<p>
					<input type="hidden" name="g_action" value="import_settings" />
					<?php wp_nonce_field( 'g_import_nonce', 'g_import_nonce' ); ?>
					<?php submit_button( __( 'Import', GT_TEXTDOMAIN ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div>
	</div>
</div>
