<?php
#namespace WilokeTest;

#use WilokeOriginalNamespace\Illuminate\Message\MessageFactory;

class Base64Upload extends AUpload {
	/**
	 * @param array $aFile ['content' => 'base64string', 'name' => '', 'mime' => 'jpeg']
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function _processUpload( array $aFile ): array {
		if ( $this->_isFileExists() ) {
			$this->_fileName = $this->_generateFileName();
		}

		// Save the image in the uploads directory.
		$uploadFile = file_put_contents(
			trailingslashit( $this->_uploadPath() ) . $this->_getFileNameWithExtension(),
			$aFile['content']
		);

		if ( ! $uploadFile ) {
			return [
				'msg'    => sprintf( esc_html__( 'Failed to upload %s', 'myshopkit' ), $this->_fileName ),
				'status' => 'error'
			];
		}

		return $this->_proceedUpdateOrInsertAttachment();
	}

	/**
	 * @param $base64Image
	 *
	 * @return float|int
	 */
	private function _getFileSize( $base64Image ): AUpload { //return memory size KB
		$this->_fileSize = (int) ( strlen( rtrim( $base64Image, '=' ) ) * 0.75 ) / 1024;

		return $this;
	}

	private function _getFileInfo() {
		$aImageData = $this->aRawFile['content'];

		$f = finfo_open();

		$this->_fileMimeType = finfo_buffer( $f, $aImageData, FILEINFO_MIME_TYPE );

		return $this->_fileMimeType;
	}

	private function _prepareImage(): Base64Upload {
		$this->_getFileSize( $this->aRawFile['content'] );

		$img = str_replace(
			[
				'data:image/jpg;base64,',
				'data:image/jpeg;base64,',
				'data:image/png;base64,'
			],
			[ '', '', '' ],
			$this->aRawFile['content']
		);

		$img        = str_replace( ' ', '+', $img );
		$imgDecoded = base64_decode( $img );

		if ( ! isset( $this->aRawFile['name'] ) || empty( $this->aRawFile['name'] ) ) {
			$this->_fileName = uniqid( 'image-' );
		} else {
			$this->_fileName = strtolower( $this->aRawFile['name'] );
			$this->_fileName = (string) preg_replace_callback( '/\\s+/', function () {
				return '-';
			}, $this->_fileName );
		}

		$this->aRawFile['content'] = $imgDecoded;
		$this->_getFileInfo();
		$this->_extension = explode( '/', $this->_fileMimeType )[1];

		return $this;
	}

	public function processUpload(): array {
		$this->_prepareImage();

		if ( ! $this->_fileMimeType ) {
			return MessageFactory::factory( 'normal' )->error( esc_html__( 'Invalid file type', 'myshopkit' ), 415 );
		}

		try {
			$this->_validateExtension( $this->_fileMimeType );
			$this->_validateFileSize( $this->_fileSize );
			$this->_parseTaxonomies();

			$aResponse = $this->_processUpload( $this->aRawFile );
			if ( $aResponse['status'] == 'error' ) {
				return MessageFactory::factory( 'normal' )
				                     ->error( $aResponse['msg'], 400 );
			}

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
