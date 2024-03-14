<?php

if ( ! class_exists( 'CF7_DATASOURCE_ADDONS' ) ) {
	class CF7_DATASOURCE_ADDONS {

		private static $addons;
		public static function load() {
			 self::$addons = array();
			$path          = dirname( __FILE__ ) . '/../add-ons';
			if ( file_exists( $path ) ) {
				$addons = dir( $path );
				while ( false !== ( $entry = $addons->read() ) ) {
					if ( strlen( $entry ) > 3 && strtolower( pathinfo( $entry, PATHINFO_EXTENSION ) ) == 'php' ) {
						require_once $addons->path . '/' . $entry;
					}
				}
			}
		} // End load

		public static function add( $addon_obj ) {
			self::$addons[ $addon_obj->get_slug() ] = $addon_obj;
		} // End add

		public static function get( $addon_slug ) {
			if ( self::exists( $addon_slug ) ) {
				return self::$addons[ $addon_slug ];
			}
			return false;
		} // End get

		public static function exists( $addon_slug ) {
			if ( ! empty( self::$addons ) && isset( self::$addons[ $addon_slug ] ) ) {
				return true;
			}
			return false;
		} // End exists

		public static function list() {
			 $free      = '<h2>' . __( 'Free add-ons', 'cf7-datasource' ) . '</h2><ul class="cf7-addons">';
			$commercial = '<h2>' . __( 'Commercial add-ons', 'cf7-datasource' ) . '</h2><ul class="cf7-addons">';

			foreach ( self::$addons as $addon_obj ) {
				$image_url = $addon_obj->get_image_url();
				if ( $addon_obj->is_free() ) {
					$v = &$free;
				} else {
					$v = &$commercial;
				}

				$v .= '
                    <li class="cf7-addon">
                        <div class="cf7-addon-details">
                            <div class="cf7-addon-description">' .
								(
									$addon_obj->is_free()
									? '<a href="admin.php?page=cf7-datasource-addons&cf7-data-source-addon=' . urlencode( $addon_obj->get_slug() ) . '"><h2>' . $addon_obj->get_title() . '</h2></a>'
									: '<a href="javascript:alert(\'' . esc_js( __( 'Only available with the Professional version of the plugin', 'cf7-datasource' ) ) . '\');"><h2>' . $addon_obj->get_title() . '</h2></a>'
								) .
								'<p>' . $addon_obj->get_description() . '</p>
                            </div>
                            <div class="cf7-addon-img-wrap">' . ( ! empty( $image_url ) ? '<img src="' . esc_attr( $image_url ) . '">' : '' ) . '</div>
                            <div style="border-top:1px solid #dcdcde;padding-top:10px; text-align:right;margin-left:-20px;margin-right:-20px;">' .
								(
									$addon_obj->is_free()
									? '<a href="admin.php?page=cf7-datasource-addons&cf7-data-source-addon=' . urlencode( $addon_obj->get_slug() ) . '" class="button-primary" style="margin-right:10px;">' . esc_html( __( "Let's go!", 'cf7-datasource' ) ) . '</a>'
									: '<a href="https://cf7-datasource.dwbooster.com/download" class="button-primary" style="margin-right:10px;" target="_blank">' . esc_html( __( 'Upgrade', 'cf7-datasource' ) ) . '</a>'
								) .
								( ( $add_on_help = $addon_obj->get_help_url() ) != '' ? '<a target="_blank" href="' . esc_attr( $add_on_help ) . '" class="button-secondary" style="margin-right:10px;">' . esc_html( __( 'View details', 'cf7-datasource' ) ) . '</a>' : '' ) // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
								. '
                            </div>
                        </div>
                    </li>
                ';
			}
			$free       .= '</ul>';
			$commercial .= '<li class="cf7-addon" style="text-align:center;font-size:1.5em;line-height:120px;">Upcoming add-ons...</li></ul>';

			print $free // phpcs:ignore WordPress.Security.EscapeOutput
				. '
                <div style="margin:20px 0;padding:20px; background-color:#fef8ee;border:1px solid #c3c4c7;border-left: 3px solid #f0b849;">
                    The add-ons listed below are available with the <a href="https://cf7-datasource.dwbooster.com/download" target="_blank"><b>Professional version of the plugin</b></a>. <a href="https://cf7-datasource.dwbooster.com/download" target="_blank"><b>CLICK HERE</b></a>.&nbsp;
                    To try the premium version, visit the links: <a href="https://demos.dwbooster.com/cf7-datasource/wp-login.php" target="_blank">WordPress area</a>, and <a href="https://demos.dwbooster.com/cf7-datasource/" target="_blank">public website</a>
                </div>
                ' . $commercial; // phpcs:ignore WordPress.Security.EscapeOutput
		} // End list

		public static function settings( $addon_slug ) {
			return self::$addons[ $addon_slug ]->settings_interface();
		} // End settings

	} // End CF7_DATASOURCE_ADDONS
}
