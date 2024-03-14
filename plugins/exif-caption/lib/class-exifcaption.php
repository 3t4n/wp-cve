<?php
/**
 * Exif Caption
 *
 * @package    Exif Caption
 * @subpackage ExifCaption Main Functions
/*
	Copyright (c) 2015- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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

$exifcaption = new ExifCaption();

/** ==================================================
 * Main Functions
 */
class ExifCaption {

	/** ==================================================
	 * Construct
	 *
	 * @since 2.23
	 */
	public function __construct() {

		add_filter( 'exif_caption_getmeta', array( $this, 'getmeta' ), 10, 2 );

		add_action( 'excp_update', array( $this, 'caption_update' ), 10, 3 );
		add_action( 'excp_filter_form', array( $this, 'filter_form' ), 10, 1 );
		add_action( 'excp_per_page_set', array( $this, 'per_page_set' ), 10, 1 );
	}

	/** ==================================================
	 * Get meta data
	 *
	 * @param int    $attach_id  attach_id.
	 * @param string $exif_text_tag  exif_text_tag.
	 * @return string $exif_text
	 * @since 1.00
	 */
	public function getmeta( $attach_id, $exif_text_tag ) {

		$metadata = wp_get_attachment_metadata( $attach_id );
		if ( ! $metadata ) {
			return null;
		}

		$exifdatas = array();
		if ( $metadata['image_meta']['title'] ) {
			$exifdatas['title'] = $metadata['image_meta']['title'];
		}
		if ( $metadata['image_meta']['credit'] ) {
			$exifdatas['credit'] = $metadata['image_meta']['credit'];
		}
		if ( $metadata['image_meta']['camera'] ) {
			$exifdatas['camera'] = $metadata['image_meta']['camera'];
		}
		if ( $metadata['image_meta']['caption'] ) {
			$exifdatas['caption'] = $metadata['image_meta']['caption'];
		}
		$exif_ux_time = $metadata['image_meta']['created_timestamp'];
		if ( ! empty( $exif_ux_time ) ) {
			if ( function_exists( 'wp_date' ) ) {
				$exifdatas['created_timestamp'] = wp_date( 'Y-m-d H:i:s', $exif_ux_time, new DateTimeZone( 'UTC' ) );
			} else {
				$exifdatas['created_timestamp'] = date_i18n( 'Y-m-d H:i:s', $exif_ux_time, false );
			}
		} else {
			if ( function_exists( 'wp_get_original_image_path' ) ) {
				$file_path = wp_get_original_image_path( $attach_id );
			} else {
				$file_path = get_attached_file( $attach_id );
			}
			$mimetype = get_post_mime_type( $attach_id );
			if ( 'image/jpeg' === $mimetype || 'image/tiff' === $mimetype ) {
				$shooting_date_time = null;
				$exif = @exif_read_data( $file_path, 'FILE', true );
				if ( isset( $exif['EXIF']['DateTimeOriginal'] ) && ! empty( $exif['EXIF']['DateTimeOriginal'] ) ) {
					$shooting_date_time = $exif['EXIF']['DateTimeOriginal'];
				} else if ( isset( $exif['IFD0']['DateTime'] ) && ! empty( $exif['IFD0']['DateTime'] ) ) {
					$shooting_date_time = $exif['IFD0']['DateTime'];
				}
				if ( ! empty( $shooting_date_time ) ) {
					$shooting_date = str_replace( ':', '-', substr( $shooting_date_time, 0, 10 ) );
					$shooting_time = substr( $shooting_date_time, 10 );
					$exifdatas['created_timestamp'] = $shooting_date . $shooting_time;
				}
			}
		}
		if ( $metadata['image_meta']['copyright'] ) {
			$exifdatas['copyright'] = $metadata['image_meta']['copyright'];
		}
		if ( $metadata['image_meta']['aperture'] ) {
			$exifdatas['aperture'] = 'f/' . $metadata['image_meta']['aperture'];
		}
		if ( $metadata['image_meta']['shutter_speed'] ) {
			if ( $metadata['image_meta']['shutter_speed'] < 1 ) {
				$shutter = round( 1 / $metadata['image_meta']['shutter_speed'] );
				$exifdatas['shutter_speed'] = '1/' . $shutter . 'sec';
			} else {
				$exifdatas['shutter_speed'] = $metadata['image_meta']['shutter_speed'] . 'sec';
			}
		}
		if ( $metadata['image_meta']['iso'] ) {
			$exifdatas['iso'] = 'ISO-' . $metadata['image_meta']['iso'];
		}
		if ( $metadata['image_meta']['focal_length'] ) {
			$exifdatas['focal_length'] = $metadata['image_meta']['focal_length'] . 'mm';
		}
		if ( $metadata['image_meta']['orientation'] ) {
			$ort_no = $metadata['image_meta']['orientation'];
			$ort_text = null;
			switch ( $ort_no ) {
				case 1:
					$ort_text = __( 'Horizontal (normal)', 'media-metadata-list' );
					break;
				case 2:
					$ort_text = __( 'Mirror horizontal', 'media-metadata-list' );
					break;
				case 3:
					$ort_text = __( 'Rotate 180', 'media-metadata-list' );
					break;
				case 4:
					$ort_text = __( 'Mirror vertical', 'media-metadata-list' );
					break;
				case 5:
					$ort_text = __( 'Mirror horizontal and rotate 270 CW', 'media-metadata-list' );
					break;
				case 6:
					$ort_text = __( 'Rotate 90 CW', 'media-metadata-list' );
					break;
				case 7:
					$ort_text = __( 'Mirror horizontal and rotate 90 CW', 'media-metadata-list' );
					break;
				case 8:
					$ort_text = __( 'Rotate 270 CW', 'media-metadata-list' );
					break;
			}
			$exifdatas['orientation'] = $ort_text;
		}

		if ( ! empty( get_post_meta( $attach_id, '_exif_details', true ) ) ) {
			$exifdatas += get_post_meta( $attach_id, '_exif_details', true );
		}

		$exif_text = null;
		if ( $exifdatas ) {
			$exif_text = $exif_text_tag;
			foreach ( $exifdatas as $item => $exif ) {
				$exif_text = str_replace( '%' . $item . '%', $exif, $exif_text );
			}
			preg_match_all( '/%(.*?)%/', $exif_text, $exif_text_per_match );
			foreach ( $exif_text_per_match as $key1 ) {
				foreach ( $key1 as $key2 ) {
					$exif_text = str_replace( '%' . $key2 . '%', '', $exif_text );
				}
			}
		}

		return $exif_text;
	}

	/** ==================================================
	 * Caption update
	 *
	 * @param int    $pid  post id.
	 * @param string $caption  caption.
	 * @param bool   $and_alt  settings.
	 * @since 3.00
	 */
	public function caption_update( $pid, $caption, $and_alt ) {

		global $wpdb;
		/* Change DB Attachement post */
		$update_array = array( 'post_excerpt' => $caption );
		$id_array = array( 'ID' => $pid );
		$wpdb->update( $wpdb->prefix . 'posts', $update_array, $id_array, array( '%s' ), array( '%d' ) );

		/* Insert to alt */
		if ( $and_alt ) {
			update_post_meta( $pid, '_wp_attachment_image_alt', $caption );
		}
	}

	/** ==================================================
	 * Filter form
	 *
	 * @param int $uid  current user id.
	 * @since 3.00
	 */
	public function filter_form( $uid ) {

		$scriptname = admin_url( 'upload.php?page=exifcaption-settings' )
		?>
		<div style="margin: 0px 0px 0px 60px; padding: 5px;">
		<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
		<?php
		wp_nonce_field( 'excp_filter', 'exif_caption_filter' );

		if ( current_user_can( 'manage_options' ) ) {
			$users = get_users(
				array(
					'orderby' => 'nicename',
					'order' => 'ASC',
				)
			);
			$user_filter = get_user_option( 'exifcaption_filter_user', $uid );
			?>
			<select name="user_id">
			<?php
			$selected_user = false;
			foreach ( $users as $user ) {
				if ( user_can( $user->ID, 'upload_files' ) ) {
					if ( $user_filter == $user->ID ) {
						?>
						<option value="<?php echo esc_attr( $user->ID ); ?>" selected><?php echo esc_html( $user->display_name ); ?></option>
						<?php
						$selected_user = true;
					} else {
						?>
						<option value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_html( $user->display_name ); ?></option>
						<?php
					}
				}
			}
			if ( ! $selected_user ) {
				?>
				<option value="" selected><?php esc_html_e( 'All users', 'exif-caption' ); ?></option>
				<?php
			} else {
				?>
				<option value=""><?php esc_html_e( 'All users', 'exif-caption' ); ?></option>
				<?php
			}
			?>
			</select>
			<?php
		}

		$mime_types = array(
			'image/jpeg',
			'image/tiff',
		);
		$mime_types = apply_filters( 'exif_caption_mime_types', $mime_types );

		$args = array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'post_mime_type' => implode( ',', $mime_types ),
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		$all_posts = get_posts( $args );

		$exifcaption_settings = get_user_option( 'exifcaption', get_current_user_id() );
		$exif_text_tag = $exifcaption_settings['exif_text'];
		foreach ( $all_posts as $post ) {
			$exif_text = $this->getmeta( $post->ID, $exif_text_tag );
			if ( ! is_null( $exif_text ) ) {
				$year = get_the_time( 'Y', $post->ID );
				$month = get_the_time( 'F', $post->ID );
				/* translators: month year for media archive */
				$year_month = sprintf( __( '%1$s %2$s', 'exif-caption' ), $month, $year );
				$archive_list[ $year_month ][] = $post->ID;
			}
		}
		$monthly_filter = get_user_option( 'exifcaption_filter_monthly', get_current_user_id() );
		?>
		<select name="monthly">
		<?php
		$selected_monthly = false;
		if ( ! empty( $archive_list ) ) {
			foreach ( $archive_list as $key => $value ) {
				$pid_csv = implode( ',', $value );
				if ( $value == $monthly_filter ) {
					?>
					<option value="<?php echo esc_attr( $pid_csv ); ?>" selected><?php echo esc_html( $key ); ?></option>
					<?php
					$selected_monthly = true;
				} else {
					?>
					<option value="<?php echo esc_attr( $pid_csv ); ?>"><?php echo esc_html( $key ); ?></option>
					<?php
				}
			}
		}
		if ( ! $selected_monthly ) {
			?>
			<option value="" selected><?php esc_html_e( 'All dates' ); ?></option>
			<?php
		} else {
			?>
			<option value=""><?php esc_html_e( 'All dates' ); ?></option>
			<?php
		}
		?>
		</select>
		<?php

		$search_text = get_user_option( 'exifcaption_search_text', $uid );
		if ( ! $search_text ) {
			?>
			<input style="vertical-align: middle;" name="search_text" type="text" value="" placeholder="<?php echo esc_attr__( 'Search' ); ?>">
			<?php
		} else {
			?>
			<input style="vertical-align: middle;" name="search_text" type="text" value="<?php echo esc_attr( $search_text ); ?>">
			<?php
		}

		submit_button( __( 'Filter' ), 'large', 'exif-caption-filter', false );
		?>
		</form>
		</div>
		<?php
	}

	/** ==================================================
	 * Per page input form
	 *
	 * @param int $uid  user ID.
	 * @since 3.00
	 */
	public function per_page_set( $uid ) {

		?>
		<div style="margin: 0px; text-align: right;">
			<?php esc_html_e( 'Number of items per page:' ); ?><input type="number" step="1" min="1" max="9999" style="width: 80px;" name="per_page" value="<?php echo esc_attr( get_user_option( 'excp_per_page', $uid ) ); ?>" form="exifcaption_forms" />
			<?php submit_button( __( 'Change' ), 'large', 'per_page_change', false, array( 'form' => 'exifcaption_forms' ) ); ?>
		</div>
		<?php
	}
}


