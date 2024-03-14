<?php

global $arflitesettingcontroller;

if ( $arf_addons == '' ) {

	echo "<div class='error_message' style='margin-top:100px; padding:20px;'>" . esc_html__( 'Add-On listing is currently unavailable. Please try again later.', 'arforms-form-builder' ) . '</div>';

} else {

	$arf_addons = maybe_unserialize( base64_decode( $arf_addons ) );

	$arflite_plugins = get_plugins();
	$installed_plugins = array();
	foreach ( $arflite_plugins as $key => $plugin_val ) {
		$is_active        = is_plugin_active( $key );
		$installed_plugin = array(
			'plugin'    => $key,
			'name'      => $plugin_val['Name'],
			'is_active' => $is_active,
		);

		$installed_plugin['activation_url']   = $is_active ? '' : wp_nonce_url( "plugins.php?action=activate&plugin={$key}", "activate-plugin_{$key}" );
		$installed_plugin['deactivation_url'] = ! $is_active ? '' : wp_nonce_url( "plugins.php?action=deactivate&plugin={$key}", "deactivate-plugin_{$key}" );

		$installed_plugins[] = $installed_plugin;
	}

	if ( is_array( $arf_addons ) && count( $arf_addons ) > 0 ) {

		foreach ( $arf_addons as $arf_addon ) {

			$is_active_addon = is_plugin_active( $arf_addon['plugin_installer'] );
			if ( isset( $arf_addon['allow_for_free'] ) && 1 != $arf_addon['allow_for_free'] ) {
				$addon_detail_url = 'https://1.envato.market/rdeQD';
			} else {
				$addon_detail_url = $arf_addon['detail_url'];
			}

			?>

			<div class="addon_container">

			<?php
			if ( $is_active_addon == 1 ) {
				echo "<div class='addon_container_activated'></div>";
			}
			?>

				<div class="addon_image">
					<a href="<?php echo esc_url( $addon_detail_url ); ?>" target="_blank"><img src="<?php echo esc_url( $arf_addon['image'] ); ?>" width="290" height="119" /></a>
				</div>

				<div class="addon_title">
					<a href="<?php echo esc_url( $addon_detail_url ); ?>" target="_blank"><?php echo esc_html( $arf_addon['full_name'] ); ?></a></div>

				<div class="addon_description"><?php echo esc_html( $arf_addon['description'] ); ?></div>

				<div class="add_more">
					<a href="<?php echo esc_url( $addon_detail_url ); ?>" class="addon_readmore" target="_blank"><?php echo esc_html__( 'Read More...', 'arforms-form-builder' ); ?></a>
				</div>

				<?php echo $arflitesettingcontroller->CheckpluginStatus( $installed_plugins, $arf_addon['plugin_installer'], 'plugin', $arf_addon['short_name'], $arf_addon['plugin_type'], $arf_addon['install_url'], $arf_addon['allow_for_free'] ); //phpcs:ignore ?>

			</div>

			<?php
		}
	}
}

$arf_addons_data = get_transient( 'arflite_addon_installation_page_data' );
if ( false == $arf_addons_data ) {
	set_transient( 'arflite_addon_installation_page_data', $arf_addons, DAY_IN_SECONDS );
}

?>

<div id="error_message" class="arf_error_message">
	<div class="message_descripiton">
		<div id="arf_plugin_install_error" style="float: left; margin-right: 15px;" id=""><?php echo esc_html__( 'File is not proper.', 'arforms-form-builder' ); ?></div>
		<div class="message_svg_icon">
			<svg style="height: 14px;width: 14px;"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg>
		</div>
	</div>
</div>
