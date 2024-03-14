<?php
namespace CatFolder_Document_Gallery\Engine\Thumbnail;

use CatFolder_Document_Gallery\Utils\SingletonTrait;

class Thumbnail {
	use SingletonTrait;

	protected function __construct() {
		add_action( 'init', array( $this, 'run' ) );
	}

	public function run() {
		$verify_imagick = $this->verify_imagick();

		wp_localize_script(
			'catf-dg-datatables',
			'catf_dg',
			array(
				'verify_imagick' => $verify_imagick,
			)
		);
	}

	public function verify_imagick() {
		$imageMagick_ver       = $this->get_imageMagick_version();
		$imagick_extension_ver = $this->get_imagick_version();
		$ghostScript           = $this->get_ghostScript();

		$verify = array(
			'status'    => true,
			'installed' => array(
				'imageMagick' => true,
				'imagick'     => true,
				'ghostScript' => true,
			),
		);

		if ( ! $imageMagick_ver ) {
			$verify['installed']['imageMagick'] = false;
		}

		if ( ! $imagick_extension_ver ) {
			$verify['installed']['imagick'] = false;
		}

		if ( ! $ghostScript ) {
			$verify['installed']['ghostScript'] = false;
		}

		if ( $imageMagick_ver ) {
			if ( ! $ghostScript ) {
				if ( ! $imagick_extension_ver ) {
					$verify['status'] = false;
				}
			}
		} else {
			if ( ! $imagick_extension_ver ) {
				$verify['status'] = false;
			}
		}

		if ( ! $verify['status'] ) {
			$verify['message']      = 'To enable file thumbnail generation, please make sure your server is configured properly.';
			$verify['navigatePage'] = array(
				'content' => 'Learn more.',
				'url'     => 'https://wpmediafolders.com/docs/addons/document-gallery/',
			);
		}

		return $verify;
	}

	protected function get_imageMagick_version() {
		$version = 0;
		$pattern = '/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/';

		if ( function_exists( 'exec' ) ) {
			exec( 'convert -version', $output, $result_code );

			if ( ! empty( $output ) ) {
				preg_match( $pattern, $output[0], $matches );

			}

			if ( ! empty( $matches ) && count( $matches ) > 0 ) {
				$version = $matches[1];
			};
		}

		return $version;
	}

	protected function get_imagick_version() {
		$version = 0;
		$pattern = '/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/';

		try {
			if ( extension_loaded( 'imagick' ) ) {
				$imagick         = new \Imagick();
				$imagick_version = $imagick->getVersion();

				preg_match( $pattern, $imagick_version['versionString'], $matches );

				if ( ! empty( $matches ) && count( $matches ) > 0 ) {
					$version = $matches[1];
				};
			}
		} catch ( \Error $err ) {
			error_log( $err );
			return $version;
		} catch ( \Exception $exc ) {
			error_log( $exc );
			return $version;
		}

		return $version;
	}

	protected function get_ghostScript() {
		if ( function_exists( 'which gs' ) ) {
			exec( 'which gs', $output, $result_code );

			if ( 0 === $result_code ) {
				return true;
			}
		}

		return false;
	}

	public static function generate_thumbnail( $attachment_id, $file_type ) {
		$thumbnail = array(
			'generated'  => false,
			'type'       => 'jpg',
			'url'        => '',
			'path'       => '',
			'image_size' => array(),
			'file_type'  => $file_type,
		);

		if ( ! $attachment_id ) {
			return $thumbnail;
		}

		try {
			$attachment_path = get_attached_file( $attachment_id );
			$attachment_name = sanitize_file_name( basename( $attachment_path ) );

			$thumbnail_name = sanitize_file_name( str_replace( ".{$file_type}", "-{$file_type}-thumbnail.{$thumbnail['type']}", $attachment_name ) );
			$thumbnail_path = str_replace( $attachment_name, $thumbnail_name, $attachment_path );

			$imagick = new \Imagick(); // create an empty image object
			$imagick->setResolution( 90, 90 );

			if ( 'pdf' === $file_type ) {
				$attachment_path = $attachment_path . '[0]';
			}

			$imagick->readImage( $attachment_path );//read the file
			$imagick->setImageFormat( 'jpg' );

			// Read the background color of the image file
			$imageBackgroundColor = $imagick->getImagePixelColor( 0, 0 )->getColor();

			// Extract RGB color values
			$red   = $imageBackgroundColor['r'];
			$green = $imageBackgroundColor['g'];
			$blue  = $imageBackgroundColor['b'];
			$color = "rgb($red, $green, $blue)";

			// Set the background color for the new image
			$imagick->setImageBackgroundColor( $color );

			// Change the background color of the image
			$imagick->extentImage( $imagick->getImageWidth(), $imagick->getImageHeight(), 0, 0 );

			// Save the new image with specified filename
			$generated = $imagick->writeImage( $thumbnail_path );

			$imagick->stripImage();

			// Free memory
			$imagick->clear();
			$imagick->destroy();
		} catch ( \Error $err ) {
			error_log( $err );
			return $thumbnail;
		} catch ( \Exception $exc ) {
			error_log( $exc );
			return $thumbnail;
		}

		$upload_dir = wp_upload_dir();

		if ( $generated ) {
			$thumbnail['generated'] = true;
			$thumbnail['url']       = $upload_dir['baseurl'] . substr( stristr( $thumbnail_path, 'uploads' ), strlen( 'uploads' ) );
			$thumbnail['path']      = $thumbnail_path;

			$image_size = getimagesize( $thumbnail_path );

			if ( $image_size ) {
				$thumbnail['image_size']['width']  = $image_size[0];
				$thumbnail['image_size']['height'] = $image_size[1];
			}

			update_post_meta( $attachment_id, 'cf_generated_thumbnail', $thumbnail );
		}

		return $thumbnail;
	}

	public static function get_thumbnail( $attachment_id ) {
		$update_class         = 'cf-thumbnail-image ';
		$attachment_image_url = wp_get_attachment_image_url( $attachment_id, 'large' );
		$file_type            = self::get_attachment_type( $attachment_id );

		if ( $attachment_image_url ) {
			$thumbnail_orientation = self::retrieve_thumbnail_orientation( $attachment_id );

			$update_class .= $thumbnail_orientation;

			if ( 'pdf' === $file_type['ext'] ) {
				$update_class .= ' cf-pdf';
			}

			$thumbnail = "<img class='$update_class' src='" . esc_attr( $attachment_image_url ) . "' alt='thumbnail of " . esc_attr( get_the_title( $attachment_id ) ) . "'/>";
		} else {
			$generated_thumbnail = get_post_meta( $attachment_id, 'cf_generated_thumbnail', true );

			if ( ! $generated_thumbnail ) {
				$generated_thumbnail = Thumbnail::generate_thumbnail( $attachment_id, $file_type['ext'] );
			}

			if ( ! $generated_thumbnail['generated'] ) {
				$thumbnail = "<span class='cf-thumbnail-image icon-{$file_type['ext']}'></span>";
			} else {
				$thumbnail_url         = $generated_thumbnail['url'];
				$thumbnail_orientation = self::retrieve_thumbnail_orientation( $attachment_id );

				$update_class .= $thumbnail_orientation;

				if ( 'pdf' === $file_type['ext'] ) {
					$update_class .= ' cf-pdf';
				}

				$thumbnail = "<img class='$update_class' src='" . esc_attr( $thumbnail_url ) . "' alt='thumbnail of " . esc_attr( get_the_title( $attachment_id ) ) . "'/>";
			}
		}

		return $thumbnail;
	}

	private static function retrieve_thumbnail_orientation( $attachment_id ) {
		$attachment_image_src = wp_get_attachment_image_src( $attachment_id );
		$thumbnail_width      = $attachment_image_src['1'];
		$thumbnail_height     = $attachment_image_src['2'];

		$generated_thumbnail = get_post_meta( $attachment_id, 'cf_generated_thumbnail', true );

		if ( $generated_thumbnail ) {
			$thumbnail_width  = $generated_thumbnail['image_size']['width'];
			$thumbnail_height = $generated_thumbnail['image_size']['height'];
		}

		if ( $thumbnail_width > $thumbnail_height ) {
			return 'cf-landscape';
		}

		return 'cf-portrait';
	}

	private static function get_attachment_type( $attachment_id ) {
		$url  = wp_get_attachment_url( $attachment_id );
		$type = wp_check_filetype( strtok( $url, '?' ) );
		return $type;
	}
}
