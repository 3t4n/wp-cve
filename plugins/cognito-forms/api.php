<?php
/**
 * Cognito Forms WordPress Plugin.
 *
 * The Cognito Forms WordPress Plugin is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * The Cognito Forms WordPress Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Cognito API access
if ( !class_exists('CognitoAPI') ) {
	class CognitoAPI {
		public static $formsBase;

		public static function __constructStatic() {
			self::$formsBase = ( get_option( 'cognito_dev_environment' ) !== false && !empty( get_option( 'cognito_dev_environment' ) ) ) 
				? get_option( 'cognito_dev_environment' ) 
				: 'https://www.cognitoforms.com';
		}

		// Convert MS GUID to Short GUID
		private static function guid_to_short_guid($guid) {
			$guid_byte_order = [ 3,2,1,0,5,4,7,6,8,9,10,11,12,13,14,15 ];
			$guid = preg_replace( "/[^a-zA-Z0-9]+/", "", $guid );
			$hex = "";
			for ( $i = 0; $i < 16; $i++ )
				$hex .= substr( $guid, 2 * $guid_byte_order[$i], 2 );
			$bin = hex2bin( $hex );
			$encoded = base64_encode( $bin );
			$encoded = str_replace( "/", "_", $encoded );
			$encoded = str_replace( "+", "-", $encoded );
			$encoded = substr ( $encoded, 0, 22 );
			return $encoded;
		}

		// Builds form embed script
		public static function get_form_embed_script( $public_key, $formId ) {
			$base = self::$formsBase;
			$public_short_guid = self::guid_to_short_guid( $public_key );

			return <<< EOF
				<script src="{$base}/f/seamless.js" data-key="{$public_short_guid}" data-form="{$formId}"></script>
	EOF;
		}
	}

	CognitoAPI::__constructStatic();
}

?>
