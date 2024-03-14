<?php
#namespace WilokeTest;


use finfo;
#use WilokeOriginalNamespace\Illuminate\Message\MessageFactory;
use RuntimeException;

class WPUpload extends AUpload {
	/**
	 * @param array $aFile ['content' => 'base64string', 'name' => '', 'mime' => 'jpeg']
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function _processUpload( array $aFile ): array {
		// Generate file name if the file is existed already
		if ( ! isset( $aFile['tmp_name'] ) || empty( $aFile['tmp_name'] ) ) {
			return [
				'status' => 'error',
				'msg'    => esc_html__( 'Sorry, We could not download this file.', 'myshopkit' )
			];
		}

		$status = copy( $aFile['tmp_name'],
			trailingslashit( $this->_uploadPath() ) . $this->_getFileNameWithExtension() );
		if ( ! $status ) {
			return [
				'status' => 'error',
				'msg'    => esc_html__( 'Sorry, We could not copy this file.', 'myshopkit' )
			];
		}
		@unlink( $aFile['tmp_name'] );

		return $this->_proceedUpdateOrInsertAttachment();
	}

	/**
	 * @return AUpload
	 * @throws \Exception
	 */
	private function _getFileInfo(): AUpload {
		$oFileInfo           = new finfo( FILEINFO_MIME_TYPE );
		$this->_fileMimeType = $oFileInfo->file( $this->singularFile['tmp_name'] );
		$this->_extension    = $this->getFileExtensionByFileMineType( $this->_fileMimeType );
		$this->_fileName     = $this->removeExtensionFromFileName( $this->singularFile['name'] );

		if ( $this->_isFileExists() ) {
			$this->_fileName = $this->_generateFileName();
		}

		return $this;
	}

	private function _getFileSize(): AUpload {
		$this->_fileSize = $this->singularFile['size'];

		return $this;
	}

	/**
	 * @return AUpload
	 * @throws \Exception
	 */
	private function _prepareImage(): AUpload {
		if (
			! isset( $this->singularFile['error'] ) ||
			is_array( $this->singularFile['error'] )
		) {
			throw new \RuntimeException( esc_html__( 'Invalid parameter', 'myshopkit' ) );
		}

		switch ( $this->singularFile['error'] ) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException( esc_html__( 'No file sent.', 'myshopkit' ) );
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException( esc_html__( 'Exceeded filesize limit.', 'myshopkit' ) );
			default:
				throw new RuntimeException( esc_html__( 'Unknown errors.', 'myshopkit' ) );
		}

		$this->_getFileSize();
		$this->_getFileInfo();
		return $this;
	}

	/**
	 * @return array
	 */
	public function processUpload(): array {

		if ( $this->isSingleUpload ) {
			try {
				$this->singularFile = $this->aRawFile['content'];
				$this->_prepareImage();
				$this->_validateExtension( $this->_fileMimeType );
				$this->_validateFileSize( $this->_fileSize );
				$this->_parseTaxonomies();
				$aResponse = $this->_processUpload( $this->singularFile );
				$msg       = $aResponse['msg'];
				unset( $aResponse['msg'] );

				if ( $aResponse['status'] == 'success' ) {
					return MessageFactory::factory( 'normal' )->success( $msg, [
						'item' => $aResponse
					] );
				} else {
					return MessageFactory::factory( 'normal' )->error( $msg, 422 );
				}
			}
			catch ( \Exception $oException ) {
				return MessageFactory::factory( 'normal' )->error( $oException->getMessage(), 415 );
			}
		} else {
			$aMessages            = [];
			$hasOneCorrectAtLeast = false;
			$hasOneErrorAtLeast   = false;

			foreach ( $this->aRawFile as $aFile ) {
				try {
					$this->singularFile = $aFile;
					$this->_prepareImage();
					$this->_validateExtension( $this->_fileMimeType );
					$this->_validateFileSize( $this->_fileSize );
					$this->_parseTaxonomies();
					$aResponse            = $this->_processUpload( $aFile );
					$aMessages['items'][] = $aResponse;

					if ( $aResponse['status'] == 'success' ) {
						$hasOneCorrectAtLeast = true;
					} else {
						$hasOneErrorAtLeast = true;
					}
				}
				catch ( \Exception $oException ) {
					$aMessages[] = [
						'status' => 'error',
						'msg'    => $oException->getMessage()
					];
				}
			}

			if ( ! $hasOneCorrectAtLeast ) {
				return MessageFactory::factory( 'normal' )
				                     ->error( esc_html__( 'Somethings went wrong, We could not upload your files',
					                     'myshopkit' ), 422 );
			}

			if ( $hasOneErrorAtLeast ) {
				return MessageFactory::factory( 'normal' )->success( esc_html__( 'We could not upload some files',
					'myshopkit' ),
					$aMessages );
			}

			return MessageFactory::factory( 'normal' )
			                     ->success( esc_html__( 'All files have been uploaded successfully.',
				                     'myshopkit' ),
				                     $aMessages );
		}
	}
}
