<?php
/**
 * Exif Details
 *
 * @package    Exif Details
 * @subpackage ExifDetails Main Functions
/*
	Copyright (c) 2020- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$exifdetails = new ExifDetails();

/** ==================================================
 * Main Functions
 */
class ExifDetails {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		/* Original hook */
		add_action( 'exif_details_update', array( $this, 'exif_details_update' ), 10, 1 );

		add_filter( 'manage_media_columns', array( $this, 'muc_column' ) );
		add_action( 'manage_media_custom_column', array( $this, 'muc_value' ), 10, 2 );
	}

	/** ==================================================
	 * Exif data update
	 *
	 * @param int $attach_id  attach_id.
	 * @since 1.00
	 */
	public function exif_details_update( $attach_id ) {

		$exifdatas = $this->exif_read( $attach_id );
		if ( ! empty( $exifdatas ) ) {
			/* Original hook */
			$exifdatas = apply_filters( 'exif_details_data', $exifdatas, $attach_id );
			update_post_meta( $attach_id, '_exif_details', $exifdatas );
		}
	}

	/** ==================================================
	 * Exif read
	 *
	 * @param int $attach_id  attach_id.
	 * @return array $exifdatas  $exifdatas.
	 * @since 1.00
	 */
	private function exif_read( $attach_id ) {

		if ( function_exists( 'wp_get_original_image_path' ) ) {
			$file_path = wp_get_original_image_path( $attach_id );
		} else {
			$file_path = get_attached_file( $attach_id );
		}

		$exif = @exif_read_data( $file_path, 0, true );
		if ( ! $exif ) {
			return;
		}

		$exifdatas = call_user_func_array( 'array_merge', array_values( $exif ) );

		foreach ( $exifdatas as $key => $value ) {
			if ( 'GPSLatitude' === $key ) {
				$exifdatas[ $key ] = wp_json_encode( $value );
				if ( ! empty( $value ) ) {
					$exifdatas['latitude_dms'] = $this->gps_text( 'dms', $exifdatas['GPSLatitudeRef'], $value );
					$exifdatas['latitude_dmm'] = $this->gps_text( 'dmm', $exifdatas['GPSLatitudeRef'], $value );
					$exifdatas['latitude_dd'] = $this->gps_text( 'dd', $exifdatas['GPSLatitudeRef'], $value );
				}
			} else if ( 'GPSLongitude' === $key ) {
				$exifdatas[ $key ] = wp_json_encode( $value );
				if ( ! empty( $value ) ) {
					$exifdatas['longtitude_dms'] = $this->gps_text( 'dms', $exifdatas['GPSLongitudeRef'], $value );
					$exifdatas['longtitude_dmm'] = $this->gps_text( 'dmm', $exifdatas['GPSLongitudeRef'], $value );
					$exifdatas['longtitude_dd'] = $this->gps_text( 'dd', $exifdatas['GPSLongitudeRef'], $value );
				}
			} else if ( 'GPSAltitude' === $key ) {
				if ( ! empty( $value ) ) {
					$parts = explode( '/', $value );
					$exifdatas[ strtolower( $key ) ] = floatval( $parts[0] / $parts[1] ) . 'm';
				}
			} else if ( 'WhiteBalance' === $key ) {
				if ( ! empty( $value ) ) {
					if ( 0 == $value ) {
						$exifdatas['white_balance'] = __( 'Auto' );
					} else {
						$exifdatas['white_balance'] = __( 'Manual' );
					}
				}
			} elseif ( is_array( $value ) ) {
					$exifdatas[ $key ] = wp_json_encode( $value );
			} elseif ( strpos( $value, '/' ) !== false ) {
					$parts = explode( '/', $value );
				if ( is_numeric( $parts[0] ) && is_numeric( $parts[1] ) ) {
					$exifdatas[ strtolower( $key ) ] = floatval( $parts[0] / $parts[1] );
				}
			}
		}

		return $exifdatas;
	}

	/** ==================================================
	 * GPS convert
	 *
	 * @param string $flag  flag.
	 * @param string $ref  ref.
	 * @param array  $gps  gps.
	 * @since 1.00
	 */
	private function gps_text( $flag, $ref, $gps ) {

		for ( $i = 0; $i < 3; $i++ ) {
			$gps_parts = explode( '/', $gps[ $i ] );
			switch ( $i ) {
				case 0:
					$gps_dms = floatval( $gps_parts[0] / $gps_parts[1] );
					break;
				case 1:
					$gps_dms += floatval( $gps_parts[0] / $gps_parts[1] ) / 60;
					break;
				case 2:
					$gps_dms += floatval( $gps_parts[0] / $gps_parts[1] ) / 3600;
					break;
			}
		}
		$d = intval( $gps_dms );
		$gps_m = ( $gps_dms - $d ) * 60;
		$m = intval( $gps_m );
		$s = round( ( $gps_m - $m ) * 60, 2 );

		$sign = null;
		if ( 'S' === $ref || 'W' === $ref ) {
			$sign = '-';
		}

		$gps_text = null;
		switch ( $flag ) {
			case 'dms':
				$gps_text = $d . '&#176;' . $m . '&#8242;' . $s . '&#8243;' . $ref;
				break;
			case 'dmm':
				$gps_text = $sign . $d . ' ' . round( $gps_m, 2 );
				break;
			case 'dd':
				$gps_text = $sign . round( $gps_dms, 4 );
				break;
		}

		return $gps_text;
	}

	/** ==================================================
	 * Media Library Column
	 *
	 * @param array $cols  cols.
	 * @return array $cols
	 * @since 1.00
	 */
	public function muc_column( $cols ) {

		global $pagenow;
		if ( 'upload.php' == $pagenow ) {
			$cols['exif_details'] = 'EXIF';
		}

		return $cols;
	}

	/** ==================================================
	 * Media Library Column
	 *
	 * @param string $column_name  column_name.
	 * @param int    $id  id.
	 * @since 1.00
	 */
	public function muc_value( $column_name, $id ) {

		if ( 'exif_details' == $column_name ) {
			$mime_type = get_post_mime_type( $id );
			if ( in_array( $mime_type, array( 'image/jpeg', 'image/tiff' ) ) ) {
				do_action( 'exif_details_update', $id );
				$exifdatas = get_post_meta( $id, '_exif_details', true );
				if ( ! empty( $exifdatas ) ) {
					?>
					<details>
					<summary><?php esc_html_e( 'Tags and Values', 'exif-details' ); ?></summary>
					<?php
					foreach ( $exifdatas as $key => $value ) {
						?>
						<div>
						<?php echo esc_html( $key ); ?>
						<?php echo esc_html( $value ); ?>
						</div>
						<?php
					}
					?>
					</details>
					<?php
				}
			}
		}
	}
}


