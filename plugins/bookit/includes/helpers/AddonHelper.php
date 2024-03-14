<?php

namespace Bookit\Helpers;

/**
 * Bookit Clean Helper
 */


class AddonHelper {

	public static $paymentAddon = 'bookit-pro';
	private static $renamedAddonPathes = array(
		'bookit-pro' => array( 'bookit-pro-premium', 'bookit-pro' ),
	);

	public static function getInstalledPluginBySlug( string $addonSlug ) {
		$installedPlugins = get_plugins();
		if ( array_key_exists( $addonSlug, $installedPlugins ) || in_array( $addonSlug, $installedPlugins, true ) ) {
			return $installedPlugins [ $addonSlug ];
		}
		return array();
	}

	public static function isProPaymentsInstalled() {
		return defined( 'BOOKIT_PRO_VERSION' ) ? 'true' : 'false';
	}

	public static function checkIsInstalledPlugin( string $addonSlug ) {
		$installedPlugins = get_plugins();
		return array_key_exists( $addonSlug, $installedPlugins ) || in_array( $addonSlug, $installedPlugins, true ) ? 'true' : 'false';
	}

	public static function getAddonDataByName( string $addon ) {
		$classNamespacePart = str_replace( '-', '', ucwords( $addon, '-' ) );
		$addonClass         = sprintf( '%s\Classes\Admin\Base', ucwords( $classNamespacePart ) );
		$addonPath          = $addon;
		if ( self::$paymentAddon == $addon ) {
			foreach ( self::$renamedAddonPathes[ $addon ] as $maybeAddonPath ) {
				if ( file_exists( WP_CONTENT_DIR . sprintf( '/plugins/%s/includes/classes/admin/Base.php', $maybeAddonPath ) ) ) {
					$addonPath = $maybeAddonPath;
				}
			}
		}

		if ( ! file_exists( WP_CONTENT_DIR . sprintf( '/plugins/%s/includes/classes/admin/Base.php', $addonPath ) ) ) {
			return array( 'isCanUse' => false );
		}

		if ( is_plugin_active( sprintf( '%s/%s.php', $addonPath, $addon ) ) && FreemiusHelper::get_license( FreemiusHelper::get_addon_id_by_name( $addon ) ) != null ) {
			require_once WP_CONTENT_DIR . sprintf( '/plugins/%s/includes/classes/admin/Base.php', $addonPath );
			if ( class_exists( $addonClass ) ) {
				return $addonClass::getAddonData();
			}
		}

		return array( 'isCanUse' => false );
	}
}
