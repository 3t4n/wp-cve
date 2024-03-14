<?php
/**
 * Template Post Types Legend.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


global $old_method;
?>
<div id="eos-dp-priority-legend">
	<?php if ( $old_method ) { ?>
	<span class="eos-dp-priority-legend-wrp eos-dp-priority-active">
		<input class="eos-dp-priority-post-type" type="checkbox" />
	</span>
	<a class="eos-dp-no-decoration fdp-has-tooltip" href="#">
		<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
		<p class="fdp-tooltip" style="width:max-content">
		<?php
		esc_html_e( 'Overrides inactive rows in the Singles Settings', 'freesoul-deactivate-plugins' );
		?>
 </p>
	</a>
	<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<span class="eos-dp-priority-legend-wrp">
		<input style="pointer-events:none" class="eos-dp-priority-post-type" type="checkbox" />
	</span>
	<a class="eos-dp-no-decoration fdp-has-tooltip" href="#">
		<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
		<p class="fdp-tooltip" style="width:max-content">
		<?php
		esc_html_e( 'Singles Settings will override the Post Types Settings', 'freesoul-deactivate-plugins' );
		?>
 </p>
	</a>
	<div style="height:32px"></div>
	<?php } ?>
	<span class="eos-dp-default-legend-wrp eos-dp-default-active">
		<span class="eos-dp-default-active eos-dp-default-post-type-wrp">
			<span class="eos-dp-default-chk-wrp">
				<input style="pointer-events:none" checked title="<?php esc_html_e( 'Set as default on new posts.', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-default-post-type-checked eos-dp-default-post-type" type="checkbox" />
				<span></span>
			</span>
	</span>
	<a class="eos-dp-no-decoration fdp-has-tooltip" href="#">
		<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
		<p class="fdp-tooltip" style="width:max-content"><?php esc_html_e( 'Set as default on new posts.', 'freesoul-deactivate-plugins' ); ?></p>
	</a>
	<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<span class="eos-dp-default-legend-wrp">
			<span class="eos-dp-default-active eos-dp-default-post-type-wrp">
				<span class="eos-dp-default-chk-wrp">
					<input style="pointer-events:none" title="<?php esc_html_e( 'Do not set as default on new posts.', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-default-post-type" type="checkbox" />
					<span></span>
				</span>
			</span>
		</span>
		<a class="eos-dp-no-decoration fdp-has-tooltip" href="#">
			<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
			<p class="fdp-tooltip" style="width:max-content"><?php esc_html_e( 'Do not set as default on new posts.', 'freesoul-deactivate-plugins' ); ?></p>
		</a>
</div>
