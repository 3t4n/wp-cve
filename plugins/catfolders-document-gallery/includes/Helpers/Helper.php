<?php
namespace CatFolder_Document_Gallery\Helpers;

use CatFolder_Document_Gallery\Engine\Thumbnail\Thumbnail;
use CatFolder_Document_Gallery\Utils\SingletonTrait;

class Helper {

	use SingletonTrait;

	protected function __construct() {}

	public static function get_available_type( $file_type ) {
		$file_type = strtolower( $file_type );

		$types = array( 'doc', 'jpg', 'mp4', 'other', 'pdf', 'ppt', 'wav', 'xls', 'zip' );
		if ( in_array( $file_type, $types, true ) ) {
			return $file_type;
		}

		$img_types = array( 'tif', 'tiff', 'jpeg', 'png', 'bmp', 'ithmb', 'gif', 'eps', 'raw', 'cr2', 'nef', 'orf', 'sr2', 'apng', 'avif', 'jfif', 'pjpeg', 'pjp', 'svg', 'ico', 'webp', 'psd', 'wbmp' );
		if ( in_array( $file_type, $img_types, true ) ) {
			return 'jpg';
		}

		$audio_types = array( 'mp3', 'aac', 'aif', 'aifc', 'aiff', 'au', 'flac', 'm4a', 'mid', 'm4b', 'm4p', 'm4r', 'oga', 'ogg', 'opus', 'ra', 'ram', 'spx', 'wm' );
		if ( in_array( $file_type, $audio_types, true ) ) {
			return 'wav';
		}

		$video_types = array( '3gp', '3gpp', '3gpp2', '3g2', 'asf', 'avi', 'dv', 'dvi', 'flv', 'm2t', 'm4v', 'mkv', 'mov', 'mpeg', 'mpg', 'mts', 'ogv', 'ogx', 'rm', 'rmvb', 'ts', 'vob', 'webm', 'wm' );
		if ( in_array( $file_type, $video_types, true ) ) {
			return 'mp4';
		}

		$ppts_types = array( 'pptx', 'ppthtml', 'pptm', 'pptxml', 'prn', 'ps', 'pps', 'ppsx', 'pwz', 'rtf', 'tab', 'template', 'tsv', 'vdx', 'vsd', 'vss', 'vst', 'vsx', 'vtx' );
		if ( in_array( $file_type, $ppts_types, true ) ) {
			return 'ppt';
		}

		$xls_types = array( 'xlsx', 'csv', 'wpd', 'wps', 'xdp', 'xdf', 'xlam', 'xll', 'xlr', 'xlsb', 'xlsm', 'xltm', 'xltx', 'xps', 'wbk', 'wpd', 'wi' );
		if ( in_array( $file_type, $xls_types, true ) ) {
			return 'xls';
		}

		$docs_types = array( 'docx', 'dochtml', 'docm', 'docxml', 'odt', 'dot', 'dothtml', 'dotm', 'dotx', 'eps', 'fdf', 'key', 'keynote', 'kth', 'mpp', 'mpt', 'mpx', 'mpd', 'txt' );
		if ( in_array( $file_type, $docs_types, true ) ) {
			return 'doc';
		}

		$zip_types = array( 'zip', 'rar', 'taz', 'gzip', 'tar.bz2', 'tar.gz' );
		if ( in_array( $file_type, $zip_types, true ) ) {
			return 'zip';
		}

		return 'other';
	}

	public static function get_attachments( $args ) {
		$selectedFolders = isset( $args['folders'] ) ? array_map( 'intval', $args['folders'] ) : array();
		$columns         = self::generate_columns( $args['displayColumns'] );

		if ( ! $selectedFolders ) {
			return array(
				'files'       => array(),
				'foundPosts'  => 0,
				'maxNumPages' => 0,
				'columns'     => $columns,
			);
		}
		global $wpdb;
		$search      = '';
		$search      = $wpdb->esc_like( $search );
		$limit       = apply_filters( 'catf_dg_posts_per_page', 1000 );
		$currentPage = isset( $args['currentPage'] ) ? intval( $args['currentPage'] ) : 1;
		remove_all_filters( 'pre_get_posts' );
		$ids          = $selectedFolders;
		$where_args[] = '`folder_id` IN (' . implode( ',', $ids ) . ')';
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$in_not_in = $wpdb->get_col( "SELECT `post_id` FROM {$wpdb->prefix}catfolders_posts" . ' WHERE ' . implode( ' AND ', $where_args ) );
		if ( ! $in_not_in ) {
			return array(
				'files'       => array(),
				'foundPosts'  => 0,
				'maxNumPages' => 0,
				'columns'     => $columns,
			);
		}

		$queryArgs = array(
			'post_type'      => 'attachment',
			'post__in'       => $in_not_in,
			'orderby'        => array(
				'ID' => 'DESC',
			),
			'post_status'    => 'inherit',
			'posts_per_page' => $limit,
			's'              => $search,
			'offset'         => ( $currentPage - 1 ) * $limit,
		);

		$sizeMeta = 'catf_filesize';

		$query = new \WP_Query( $queryArgs );

		$posts = $query->get_posts();

		$files = array();
		foreach ( $posts as $post ) {
			$size  = \get_post_meta( $post->ID, $sizeMeta, true );
			$url   = \wp_get_attachment_url( $post->ID );
			$type  = \wp_check_filetype( strtok( $url, '?' ) );
			$image = Thumbnail::get_thumbnail( $post->ID );
			$file  = array(
				'title'    => $post->post_title,
				'type'     => $type['ext'],
				'size'     => ! empty( $size ) ? \size_format( $size ) : '',
				'url'      => $url,
				'link'     => $url,
				'alt'      => $post->post_excerpt,
				'modified' => wp_date( 'M d, Y', strtotime( $post->post_modified ) ),
				'image'    => $image,
			);

			$files[] = $file;
		}

		return array(
			'files'       => $files,
			'foundPosts'  => $query->found_posts,
			'maxNumPages' => $query->max_num_pages,
			'columns'     => $columns,
		);
	}

	public static function generate_columns( $displayColumns ) {
		$columns = array(
			array(
				'label' => __( 'Image', 'catfolders-document-gallery' ),
				'key'   => 'image',
			),
			array(
				'label' => __( 'Title', 'catfolders-document-gallery' ),
				'key'   => 'title',
			),
			array(
				'label' => __( 'Type', 'catfolders-document-gallery' ),
				'key'   => 'type',
			),
			array(
				'label' => __( 'Size', 'catfolders-document-gallery' ),
				'key'   => 'size',
			),
			array(
				'label' => __( 'Updated', 'catfolders-document-gallery' ),
				'key'   => 'updated',
			),
			array(
				'label' => __( 'Link', 'catfolders-document-gallery' ),
				'key'   => 'link',
			),
		);

		$columns = array_filter(
			$columns,
			function( $column ) use ( $displayColumns ) {
				return $displayColumns[ $column['key'] ];
			}
		);

		return apply_filters( 'catf_dg_columns', $columns );
	}

	public static function get_file_link( $attributes, $link ) {
		switch ( $attributes['linkTo'] ) {
			case 'preview':
				return 'rel="noopener noreferrer" target="_blank" href="' . esc_url( $link ) . '"';
			case 'popup':
				return 'href="' . esc_url( $link ) . '" data-popup data-popupwidth="' . esc_attr( $attributes['popupWidth'] ) . '" data-popupheight="' . esc_attr( $attributes['popupHeight'] ) . '"';
			case 'download':
				return 'download rel="noopener noreferrer" target="_blank" href="' . esc_url( $link ) . '"';
			default:
				return '';
		}
	}

	public static function render_row( $columns, $file, $attributes ) {
		ob_start();

		foreach ( $columns as $column ) {

			$columns = apply_filters( 'catf_dg_columns_html', $column, $file, $attributes );
			if ( 'image' === $column['key'] ) {
				?>
					<td>
						<p class="cf-column-thumbnail"><?php echo $file['image']; ?></p>
					</td>
				<?php
			}

			if ( 'title' === $column['key'] ) {
				$file_type              = self::get_available_type( $file['type'] );
				$visible_document_icons = isset( $attributes['documentIcons']['display'] ) ? $attributes['documentIcons']['display'] : true;
				?>
					<td class="sorting_1 dtr-control">
						<div  class="flex">
							<a <?php echo self::get_file_link( $attributes, $file['link'] ); ?> class="cf-icon icon-<?php echo esc_attr( $file_type ); ?> <?php echo esc_attr( $visible_document_icons ? '' : 'cf-hidden' ); ?>"><?php echo esc_html( $file['title'] ); ?></a>
						</div>
					</td> 
				<?php
			}

			if ( 'type' === $column['key'] ) {
				?>
					<td>
						<div class="cf-column-type"><?php echo esc_html( $file['type'] ); ?></div>
					</td>
				<?php
			}

			if ( 'size' === $column['key'] ) {
				?>
					<td>
						<div class="cf-column-size"><?php echo esc_html( $file['size'] ); ?></div>
					</td>
				<?php
			}

			if ( 'updated' === $column['key'] ) {
				$updated_class = isset( $column['link'] ) ? 'cf-column-modified cf-column-link' : 'cf-column-modified';
				?>
					<td>
						<div class="<?php echo esc_attr( $updated_class ); ?>"><?php echo esc_html( $file['modified'] ); ?></div>
					</td>
				<?php
			}

			if ( 'link' === $column['key'] ) {
				?>
					<td>
						<div class="cf-column-last">
							<span class="cf-updated">
								<?php if ( isset( $column['updated'] ) ) : ?>
									<small> <?php esc_html_e( 'Updated', 'catfolders-document-gallery' ); ?></small>
									<?php echo esc_html( $file['modified'] ); ?>
								<?php endif; ?>
							</span>
							<a download rel="noopener noreferrer" href="<?php echo esc_url( $file['link'] ); ?>" class="btn-download">
								<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_1_337)"><path d="M5.30186e-07 8.99044C0.00168761 4.04699 4.03718 0.00393964 8.97357 2.87451e-06C13.9611 -0.00393389 18.0017 4.03631 17.9994 9.02587C17.9978 13.9868 13.9448 18.0073 8.95389 18C4.02312 17.9927 -0.00168655 13.9429 5.30186e-07 8.99044ZM8.0991 11.3176C7.98213 11.2108 7.91465 11.1534 7.85166 11.091C7.53899 10.7811 7.23644 10.46 6.91252 10.1625C6.54586 9.8256 6.01162 9.85034 5.67196 10.1962C5.3351 10.5387 5.30305 11.0662 5.64271 11.4138C6.55317 12.3446 7.47488 13.2646 8.40615 14.1752C8.743 14.5042 9.24856 14.4862 9.6051 14.1707C9.73838 14.0531 9.86041 13.9226 9.98638 13.7967C10.7551 13.0284 11.5289 12.2658 12.2887 11.4886C12.7672 10.9993 12.6199 10.257 12.0041 10.0084C11.6251 9.85541 11.2871 9.95045 11.0042 10.2395C10.6612 10.5905 10.321 10.9448 9.97963 11.2974C9.95376 11.2816 9.92789 11.2665 9.90203 11.2507C9.90203 11.1517 9.90203 11.0527 9.90203 10.9538C9.90203 8.84534 9.89978 6.73749 9.90427 4.62907C9.90484 4.34338 9.84635 4.0858 9.64165 3.8839C9.3616 3.60776 9.02699 3.54084 8.65809 3.68088C8.31842 3.80966 8.10191 4.14091 8.10135 4.54584C8.0991 6.68238 8.10023 8.81835 8.10023 10.9549C8.10023 11.0533 8.10023 11.1523 8.10023 11.3171L8.0991 11.3176Z" fill="currentColor"></path><path d="M8.09912 11.3176C8.09912 11.1528 8.09912 11.0539 8.09912 10.9554C8.09912 8.8189 8.09799 6.68292 8.10024 4.54638C8.10024 4.1409 8.31731 3.81021 8.65698 3.68142C9.02589 3.54138 9.36049 3.60831 9.64055 3.88445C9.84581 4.08634 9.90373 4.34448 9.90317 4.62962C9.89867 6.73804 9.90092 8.84589 9.90092 10.9543C9.90092 11.0533 9.90092 11.1523 9.90092 11.2513C9.92679 11.267 9.95266 11.2822 9.97852 11.2979C10.3199 10.9453 10.6595 10.591 11.0031 10.2401C11.286 9.951 11.624 9.85595 12.003 10.0089C12.6188 10.2575 12.7661 11.0004 12.2876 11.4891C11.5278 12.2658 10.7535 13.0284 9.98527 13.7972C9.8593 13.9232 9.73727 14.0531 9.60399 14.1712C9.24746 14.4867 8.7419 14.5053 8.40504 14.1757C7.47434 13.2652 6.55263 12.3451 5.64161 11.4143C5.30138 11.0668 5.334 10.5393 5.67085 10.1968C6.01108 9.85089 6.54476 9.82614 6.91142 10.163C7.23533 10.4605 7.53788 10.7817 7.85056 11.0915C7.91354 11.154 7.98159 11.2119 8.09799 11.3182L8.09912 11.3176Z" fill="white"></path></g><defs><clipPath id="clip0_1_337"><rect width="18" height="18" fill="white"></rect></clipPath></defs></svg>
								<?php esc_html_e( 'Download', 'catfolders-document-gallery' ); ?>
							</a>
						</div>
					</td>
				<?php
			}
		}

		echo ob_get_clean();
	}

	public static function get_shortcode_data( $args ) {
		$post_id = isset( $args['shortcodeId'] ) ? sanitize_text_field( $args['shortcodeId'] ) : '';

		$data = get_post_meta( $post_id, 'shortcode_settings', true );

		if ( ! $data ) {
			$data = array();
		}

		$thumbnail_instance = Thumbnail::get_instance();

		$verify_imagick = $thumbnail_instance->verify_imagick();

		$default_data = self::get_defaults_attribute();

		$attrs = shortcode_atts( $default_data, $data );

		$attrs['displayTitle']              = rest_sanitize_boolean( $attrs['displayTitle'] );
		$attrs['libraryIcon']['display']    = rest_sanitize_boolean( $attrs['libraryIcon']['display'] );
		$attrs['displayColumns']['title']   = rest_sanitize_boolean( $attrs['displayColumns']['title'] );
		$attrs['displayColumns']['type']    = rest_sanitize_boolean( $attrs['displayColumns']['type'] );
		$attrs['displayColumns']['size']    = rest_sanitize_boolean( $attrs['displayColumns']['size'] );
		$attrs['displayColumns']['updated'] = rest_sanitize_boolean( $attrs['displayColumns']['updated'] );
		$attrs['displayColumns']['link']    = rest_sanitize_boolean( $attrs['displayColumns']['link'] );
		$attrs['documentIcons']['display']  = rest_sanitize_boolean( $attrs['documentIcons']['display'] );

		$attrs['gridColumn']  = (int) $attrs['gridColumn'];
		$attrs['popupWidth']  = (int) $attrs['popupWidth'];
		$attrs['popupHeight'] = (int) $attrs['popupHeight'];
		$attrs['limit']       = (int) $attrs['limit'];

		if ( ! $verify_imagick['status'] ) {
			$attrs['displayColumns']['image'] = false;
		} else {
			$attrs['displayColumns']['image'] = rest_sanitize_boolean( $attrs['displayColumns']['image'] );
		}

		return $attrs;
	}

	public static function get_defaults_attribute() {
		$json = wp_json_file_decode( CATF_DG_DIR . '/build/block.json', array( 'associative' => true ) );

		$defaults = array();

		foreach ( $json['attributes'] as $key => $value ) {
			$defaults[ $key ] = $value['default'];
		}

		return $defaults;
	}
}
