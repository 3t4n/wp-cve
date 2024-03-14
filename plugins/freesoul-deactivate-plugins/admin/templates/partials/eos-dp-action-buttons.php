<?php
/**
 * Template Action Buttons.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( $this->fdp_is_single_post ) {
	$values_string = isset( $meta_values[ $post->ID ] ) ? $meta_values[ $post->ID ] : '';
	$values        = explode( ',', $values_string );
	$bin           = substr( implode( '', array_map( 'eos_dp_is_not_empty_string', $values ) ), 1 );
	$loc           = false;
	$flag          = '';

	if ( function_exists( 'pll_get_post_language' ) ) {
			$loc = pll_get_post_language( $post->ID );
		if ( $loc && '' !== $loc ) {
			$flag = defined( 'POLYLANG_FILE' ) && defined( 'POLYLANG_DIR' ) && file_exists( POLYLANG_DIR . '/flags/' . $loc . '.png' ) ? '<img src="' . esc_url( plugins_url( '/flags/' . $loc . '.png', POLYLANG_FILE ) ) . '" />' : esc_html( strtoupper( $loc ) );
		}
	} elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		$lang_info = apply_filters( 'wpml_post_language_details', null, $post->ID );
		if ( ! is_wp_error( $lang_info ) && is_array( $lang_info ) && isset( $lang_info['locale'] ) ) {
			$loc = $lang_info['language_code'];
			if ( $loc && '' !== $loc ) {
				$flag = defined( 'ICL_PLUGIN_URL' ) && defined( 'WPML_PLUGIN_PATH' ) && file_exists( WPML_PLUGIN_PATH . '/res/flags/' . $loc . '.png' ) ? '<img src="' . esc_url( ICL_PLUGIN_URL . '/res/flags/' . $loc . '.png' ) . '" />' : esc_html( strtoupper( $loc ) );
			}
		}
	}
	$needCustom = false;
	if ( isset( $default_language ) && $loc && strtolower( substr( $loc, 0, 2 ) ) !== strtolower( substr( $default_language, 0, 2 ) ) ) {
		$need_custom_url[ $post->ID ] = get_permalink( $post->ID );
		$needCustom                   = true;
	}
	$locked = '';
	if ( isset( $post_types_matrix_pt[3] ) && is_array( $post_types_matrix_pt[3] ) && ! empty( $post_types_matrix_pt[3] ) && in_array( $post->ID, $post_types_matrix_pt[3] ) ) {
		$locked = ' eos-post-locked';
	}
	if ( $needCustom && ! empty( $eos_dp_need_custom_url ) && ! in_array( $post->ID, array_keys( $eos_dp_need_custom_url ) ) ) {
		$locked = '';
	}
}
?>
<div class="eos-dp-actions" data-need-custom-url="<?php echo $needCustom ? 'true' : 'false'; ?>" data-post-id="<?php echo esc_attr( $post->ID ); ?>">
	<a class="eos-dp-edit fdp-has-tooltip fdp-right-tooltip" href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>" target="_blank"><span class="dashicons dashicons-edit"></span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Edit page', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<?php eos_dp_saved_preview_button( get_permalink( $post->ID ), $post->ID ); ?>
	<?php eos_dp_debug_button( get_permalink( $post->ID ) ); ?>
	<?php
	$themes_list = eos_dp_active_themes_list();
	if ( $themes_list ) {
		?>
	<a class="eos-dp-theme-sel fdp-has-tooltip fdp-right-tooltip" style="border:1px solid #fff !important">
		<?php echo $themes_list; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied on the output of eos_dp_active_themes_list(). ?>
		<div class="fdp-tooltip"><?php esc_html_e( 'Select a different Theme and then click on the lens icon to see the preview', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<?php } ?>
	<a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( $args, get_permalink( $post->ID ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank"><span class="dashicons dashicons-search"></span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins according the settings you see now on this row and the selected theme (shortcut: P)', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array_merge( $args, array( 'show_files' => 'true' ) ), get_permalink( $post->ID ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank">
		<span class="dashicons dashicons-search">
			<span class="dashicons dashicons-media-code"></span>
		</span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins according the settings you see now on this row and show the files that are called', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array_merge( $args, array( 'js' => 'off' ) ), get_permalink( $post->ID ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank">
		<span class="dashicons dashicons-search">
			<span class="eos-dp-no-js">JS</span>
		</span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Preview the page loading plugins and the theme according the settings you see now on this row and disable JavaScript esecution', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<?php
	$args['eos_dp_preview'] = 1000 * absint( time() / 1000 );
	$psi_url                = esc_url(
		add_query_arg(
			array(
				'url' => urlencode(
					add_query_arg(
						array_merge( array( 'display_usage' => 'false' ), $args ),
						get_permalink( $post->ID )
					)
				),
			),
			$this->gpsi_url
		)
	);
	?>
	<a data-page_speed_insights="true" data-encode_url="true" class="eos-dp-preview eos-dp-psi-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( $psi_url ); ?>" target="_blank" rel="noopener">
		<span class="dashicons dashicons-search">
			<img width="20" height="20" src="<?php echo esc_url( EOS_DP_PLUGIN_URL . '/admin/assets/img/pagespeed.png' ); ?>" />
		</span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Check the page with Google PageSpeed Insights loading plugins and the theme according the settings you see now on this row', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<?php
	if ( defined( 'FDP_PRO_ACTIVE' ) && FDP_PRO_ACTIVE && defined( 'EOS_DP_PRO_PLUGIN_URL' ) ) {
		?>
		<a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array_merge( $args, array( 'fdp-hooks' => 'actions' ) ), get_permalink( $post->ID ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank"><span class="fdp-hook-ico dashicons" style="margin:0 4px"></span>
			<div class="fdp-tooltip"><?php esc_html_e( 'Show the hooks loading plugins according the settings you see now on this row', 'eos-dp-pro' ); ?></div>
		</a>
		<a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="
		<?php
		echo esc_url(
			wp_nonce_url(
				add_query_arg(
					array_merge(
						$args,
						array(
							'fdp-assets'   => 'styles',
							'ao_noptimize' => '1',
						)
					),
					get_permalink( $post->ID )
				),
				'eos_dp_preview',
				'eos_dp_preview'
			)
		);
		?>
																														" target="_blank"><span class="dashicons"><span class="dashicons dashicons-editor-code" style="top:2px"><span class="eos-dp-after-icon">CSS</span></span>
			<div class="fdp-tooltip"><?php esc_html_e( 'Unload stylesheets of the remaining active plugins', 'eos-dp-pro' ); ?></div>
		</a>
		<a data-page_speed_insights="false" class="eos-dp-preview fdp-has-tooltip" oncontextmenu="return false;" href="
		<?php
		echo esc_url(
			wp_nonce_url(
				add_query_arg(
					array_merge(
						$args,
						array(
							'fdp-assets'   => 'scripts',
							'ao_noptimize' => '1',
						)
					),
					get_permalink( $post->ID )
				),
				'eos_dp_preview',
				'eos_dp_preview'
			)
		);
		?>
																														" target="_blank"><span class="dashicons"><span class="dashicons dashicons-editor-code" style="top:2px"><span class="eos-dp-after-icon">JS</span></span>
			<div class="fdp-tooltip"><?php esc_html_e( 'Unload scripts of the remaining active plugins', 'eos-dp-pro' ); ?></div>
		</a>
		<?php
	}
	if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-home-actions.php';
	}
	?>
	<a class="eos-dp-invert-selection fdp-has-tooltip" href="#"><span class="dashicons"><span style="display:inline-block"><span class="fdp-invert-up"></span><span class="fdp-invert-down"></span></span></span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Invert selection (shortcut: I)', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<a class="eos-dp-copy fdp-has-tooltip" href="#"><span class="dashicons dashicons-admin-page"></span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Copy this row settings (shortcut: C)', 'freesoul-deactivate-plugins' ); ?></div>
		<div class="fdp-action-msg fdp-msg-success" style="opacity:0;position:absolute;top:34px;background:#fff;padding:10px;transition:opacity 0.5s linear"><?php esc_html_e( 'Row settings copied', 'freesoul-deactivate-plugins' ); ?></div>
		<div class="fdp-action-msg fdp-msg-error" style="opacity:0;display:none;position:absolute;top:34px;background:#fff;padding:10px;transition:opacity 0.5s linear"><?php esc_html_e( 'It was not possible to copy the row settings', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<a class="eos-dp-paste fdp-has-tooltip" href="#"><span class="dashicons dashicons-category"></span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Paste last copied row settings (shortcut: V)', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<?php
	$GLOBALS['fdp_action_post_id'] = absint( $post->ID );
	do_action( 'eos_dp_action_buttons' );
	?>
	<a title="<?php esc_html_e( 'Close', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-close-actions" href="#"><span class="dashicons dashicons-no-alt" style="margin:0 4px"></span></a>
</div>
