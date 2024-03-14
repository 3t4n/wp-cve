<?php
#namespace WilokeTest;

#use WilokeOriginalNamespace\Illuminate\Message\MessageFactory;
#use WilokeOriginalNamespace\Illuminate\Prefix\AutoPrefix;

class ImageURLUpload extends AUpload {
	private array  $_aPathInfo      = [];
	private string $_originalUrl    = '';
	private string $_originalUrlKey = 'original_url';

	/**
	 * @param array $aFile ['content' => 'base64string', 'name' => '', 'mime' => 'jpeg']
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function _processUpload( array $aFile ): array {
		// Generate file name if the file is existed already
		if ( $this->_isFileExists() ) {
			$this->_fileName = $this->_generateFileName();
		}

		if ( ! function_exists( 'download_url' ) ) {
			require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
		}

		$tmpFile = download_url( $this->_originalUrl );

		if ( $tmpFile === false ) {
			return [
				'status' => 'error',
				'msg'    => esc_html__( 'Sorry, We could not download this file.', 'myshopkit' )
			];
		}

		$status = copy( $tmpFile, trailingslashit( $this->_uploadPath() ) . $this->_getFileNameWithExtension() );
		if ( ! $status ) {
			return [
				'status' => 'error',
				'msg'    => esc_html__( 'Sorry, We could not copy this file.', 'myshopkit' )
			];
		}
		@unlink( $tmpFile );

		return $this->_proceedUpdateOrInsertAttachment();
	}

	/**
	 * @param $url
	 *
	 * @return float|int
	 */
	private function _getFileSize() { //return memory size KB
		$ch = curl_init( $this->_originalUrl );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_exec( $ch );
		$size = curl_getinfo( $ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD );

		curl_close( $ch );
		$this->_fileSize = floor( $size / 1000 );
	}

	/**
	 * @return AUpload
	 * @throws \Exception
	 */
	private function _getFileInfo(): AUpload {
		$path                = parse_url( $this->aRawFile['content'] )['path'];
		$this->_aPathInfo    = pathinfo( $path );
		$this->_extension    = $this->_aPathInfo['extension'];
		$this->_fileName     = $this->_aPathInfo['filename'];
		$this->_fileName     = (string) preg_replace_callback( '/([\s%()]+)/', function ( $aMatch ) {
			return '-';
		}, $this->_fileName );
		$this->_fileMimeType = image_type_to_mime_type( exif_imagetype( $this->aRawFile['content'] ) );

		if ( ! $this->_fileMimeType ) {
			throw new \Exception( esc_html__( 'Invalid Image URL', 'myshopkit' ) );
		}

		return $this;
	}

	private function _updateOriginalImg( $attachmentId ) {
		return update_post_meta( $attachmentId, AutoPrefix::namePrefix( $this->_originalUrlKey ), $this->_originalUrl );
	}

	private function _cleanOriginalUrl( $url ): string {
		$imgFormats = implode( '|', $this->aExtensions );

		$this->_originalUrl = preg_replace_callback(
			'#(\.' . $imgFormats . ')(\?fit=.+)#',
			function ( $aMatches ) {
				return $aMatches[1];
			},
			$url
		);


		return $this->_originalUrl;
	}

	/**
	 * @return int
	 */
	private function _getAttachmentIdByOriginalImg(): int {
		if ( $this->_imgSource !== 'stock' ) {
			return 0;
		}

		$query = new \WP_Query(
			[
				'post_type'             => 'attachment',
				'meta_key'              => AutoPrefix::namePrefix( $this->_originalUrlKey ),
				'meta_value'            => $this->_originalUrl,
				'post_content_filtered' => $this->_imgSource,
				'posts_per_page'        => 1,
				'orderby'               => 'post_date',
				'order'                 => 'desc',
				'post_status'           => 'any'
			]
		);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$attachmentId = abs( $query->post->ID );
				$url          = wp_get_attachment_image_url( $attachmentId, 'full' );
				if ( $url ) {
					if ( $this->isRemoteFileExists( $url ) ) {
						return $attachmentId;
					}
				}
			}
		}
		wp_reset_postdata();

		return 0;
	}

	/**
	 * @return ImageURLUpload
	 * @throws \Exception
	 */
	private function _prepareImage(): ImageURLUpload {
		if ( ! filter_var( $this->aRawFile['content'], FILTER_VALIDATE_URL ) ) {
			throw new \Exception( esc_html__( 'Invalid Image URL', 'myshopkit' ) );
		}

		$this->_originalUrl = $this->_cleanOriginalUrl( $this->aRawFile['content'] );
		$this->_getFileSize();
		$this->_getFileInfo();

		return $this;
	}

	public function processUpload(): array {
		try {
			$this->_prepareImage();
			$this->_validateExtension( $this->_fileMimeType );
			$this->_validateFileSize( $this->_fileSize );
			$this->_parseTaxonomies();

			if ( $attachmentId = $this->_getAttachmentIdByOriginalImg() ) {
				return MessageFactory::factory( 'normal' )
				                     ->success(
					                     esc_html__( 'All files have been uploaded successfully.', 'myshopkit' ),
					                     [
						                     'item' => [
							                     'id'     => $attachmentId,
							                     'url'    => wp_get_attachment_image_url( $attachmentId, 'full' ),
							                     'msg'    => sprintf(
								                     esc_html__( 'The file %s has been uploaded already',
									                     'myshopkit' ),
								                     $this->_fileName
							                     ),
							                     'status' => 'success'
						                     ]
					                     ]
				                     );
			}

			$aResponse = $this->_processUpload( $this->aRawFile );
			if ( $aResponse['status'] == 'error' ) {
				return MessageFactory::factory( 'normal' )
				                     ->error( $aResponse['msg'], 400 );
			}
			$this->_updateOriginalImg( $aResponse['id'] );

			return MessageFactory::factory( 'normal' )
			                     ->success(
				                     esc_html__( 'All files have been uploaded successfully.', 'myshopkit' ),
				                     [
					                     'item' => $aResponse
				                     ]
			                     );
		}
		catch ( \Exception $oException ) {
			return MessageFactory::factory( 'normal' )->error( $oException->getMessage(), 415 );
		}
	}
}
