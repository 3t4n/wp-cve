<?php

#namespace WilokeTest;


use RuntimeException;

abstract class AUpload {
	protected ?string $_fileMimeType;
	protected ?float  $_fileSize;
	protected ?string $_fileName;
	protected ?string $_extension;
	protected string  $_postContentFiltered = 'default';
	protected ?int    $_existedAttachmentId;
	protected array   $_aValidSources       = [ 'default', 'stock', 'base64', 'self_hosted' ];
	protected ?string $type                 = 'photo';
	protected array   $aValidTypes          = [ 'photo', 'project' ];

	/**
	 * @var string "default"|"pixabay $_imgSource
	 */
	protected string $_imgSource = "default";

	/**
	 * @var string
	 */
	protected string $uploadPath = '';

	/**
	 * @var string
	 */
	protected string $uploadUrl = '';

	protected ?string $msg;
	/**
	 * @var array $aRawFile
	 */
	protected array $aRawFile    = [];
	protected       $singularFile;
	protected int   $userId      = 0;
	protected array $aExtensions = [ 'jpg', 'jpeg', 'png', 'image/jpeg', 'image/png', 'image/jpg' ];
	protected array $aFileMineTypesAndExtensions
	                             = [
			'image/jpeg' => 'jpg',
			'image/jpg'  => 'jpg',
			'image/png'  => 'png',
		];

	/**
	 * @var bool
	 */
	protected bool $isSingleUpload = true;

	/**
	 * @var ?int $_updateAttachmentId ;
	 */
	protected ?int $_updateAttachmentId = null;

	/**
	 * @var array
	 */
	private array $_aOldData       = [];
	private int   $_attachmentId;
	private array $_aCategories    = [];
	private array $_aRawCategories = [];
	/**
	 * @var mixed
	 */
	private array $_aSupportedTaxonomies = [];

	protected function isRemoteFileExists( $url ): bool {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		// don't download content
		curl_setopt( $ch, CURLOPT_NOBODY, 1 );
		curl_setopt( $ch, CURLOPT_FAILONERROR, 1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		return curl_exec( $ch ) !== false;
	}

	/**
	 * Where crawl/upload image.
	 *
	 * @param string $source
	 *
	 * @return AUpload
	 */
	public function setImageSource( string $source ): AUpload {
		if ( ! in_array( $source, $this->_aValidSources ) ) {
			throw new RuntimeException( esc_html__( 'Invalid source', 'myshopkit' ) );
		}

		$this->_imgSource = $source;

		return $this;
	}

	/**
	 * @param $fileName
	 *
	 * @return string|string[]
	 */
	public function removeExtensionFromFileName( $fileName ) {
		return str_replace( [ '.jpg', '.png', '.jpeg' ], [ '', '', '' ], $fileName );
	}

	public function getFileExtensionByFileMineType( $fileMineType ): ?string {
		return array_key_exists( $fileMineType, $this->aFileMineTypesAndExtensions ) ?
			$this->aFileMineTypesAndExtensions[ $fileMineType ] : null;
	}

	/**
	 * @param array{content: string, name: ?string, id: ?id, source: "pixabay"|"default" } $rawFile
	 *
	 * @return $this
	 */
	public function setFile( array $rawFile ): AUpload {
		$this->aRawFile = $rawFile;

		return $this;
	}

	public function setUserId( int $userId ): AUpload {
		$this->userId = $userId;

		return $this;
	}

	/**
	 * @param int $_updateAttachmentId
	 *
	 * @return $this
	 */
	public function setUpdateAttachmentId( int $_updateAttachmentId ): AUpload {
		if ( $this->_isAttachmentAuthor( $_updateAttachmentId ) ) {
			$this->_updateAttachmentId = $_updateAttachmentId;
		} else {
			throw new \Exception( esc_html__( 'You are not author of this attachment', 'myshopkit' ) );
		}

		return $this;
	}

	/**
	 * @param int $updateAttachmentId
	 *
	 * @return bool
	 */
	protected function _isAttachmentAuthor( int $updateAttachmentId ): bool {
		if ( current_user_can( 'administrator' ) ) {
			return true;
		}

		return get_current_user_id() == get_post_field( 'post_author', $updateAttachmentId );
	}

	/**
	 * @return int
	 */
	public function getUserId(): int {
		return empty( $this->userId ) ? get_current_user_id() : $this->userId;
	}

	/**
	 * @param bool $isSingle
	 *
	 * @return $this
	 */
	public function isSingleUpload( $isSingle = true ): AUpload {
		$this->isSingleUpload = $isSingle;

		return $this;
	}

	public function setAllowedExtensions( array $aExtensions ): AUpload {
		$this->aExtensions = $aExtensions;

		return $this;
	}

	public function setType( string $type ): AUpload {
		if ( in_array( $type, $this->aValidTypes ) ) {
			$this->type = $type;
		} else {
			throw new \RuntimeException( sprintf( esc_html__( 'Invalid image type %s', 'myshopkit' ), $type ) );
		}

		return $this;
	}

	public function getMessage(): string {
		return $this->msg;
	}

	protected abstract function _processUpload( array $aFile ): array;

	protected abstract function processUpload(): array;

	/**
	 * @param $fileMine
	 *
	 * @return bool
	 * @throws \Exception
	 */
	protected function _validateExtension( $fileMine ): bool {
		if ( ! in_array( $fileMine, $this->aExtensions ) ) {
			throw new \Exception( sprintf( esc_html__( 'The file type %s is not allowed', 'myshopkit' ), $fileMine ) );
		}

		return true;
	}

	protected function _validateFileSize( $fileSize ): bool {
		if ( $fileSize > wp_max_upload_size() ) {
			throw new \Exception( sprintf( esc_html__( 'The file is large than expected %sM', 'myshopkit' ),
				wp_max_upload_size() / 1024 ) );
		}

		return true;
	}

	/**
	 * @param $aFile
	 *
	 * @return bool
	 * @throws \Exception
	 */
	protected function _verifyFile( $aFile ): bool {
		if ( isset( $aFile['error'] ) && ! empty( $aFile['error'] ) ) {
			throw new \Exception( $aFile['error'] );
		}

		$maxFileSize = wp_max_upload_size();
		$this->_validateFileSize( $maxFileSize );

		$fileMine = mime_content_type( $aFile['tmp_name'] );
		$this->_validateExtension( $fileMine );

		return true;
	}

	/**
	 * @return $this
	 * @throws \Exception
	 */
	public function verify(): AUpload {
		if ( $this->isSingleUpload ) {
			$this->_verifyFile( $this->aRawFile );
		} else {
			foreach ( $this->aRawFile as $aFile ) {
				$this->_verifyFile( $aFile );
			}
		}

		return $this;
	}

	/**
	 * @return string
	 */
	protected function _uploadPath(): string {
		if ( ! empty( $this->uploadPath ) ) {
			return $this->uploadPath;
		}

		$aUploadDir       = wp_upload_dir();
		$this->uploadPath = $aUploadDir['path'];

		return $this->uploadPath;
	}

	/**
	 * @return string
	 */
	protected function _uploadUrl(): string {
		if ( ! empty( $this->uploadUrl ) ) {
			return $this->uploadUrl;
		}

		$aUploadDir      = wp_upload_dir();
		$this->uploadUrl = $aUploadDir['url'];

		return $this->uploadUrl;
	}

	protected function _getFileNameWithExtension(): string {
		return $this->_fileName . '.' . $this->_extension;
	}

	protected function _isFileExists(): bool {
		global $wpdb;

		$postId = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE post_title=%s and post_mime_type=%s",
				$this->_fileName, $this->_fileMimeType
			)
		);

		if ( wp_get_attachment_image_url( $postId ) ) {
			$this->_existedAttachmentId = $postId;
			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	protected function _generateFileName(): string {
		return uniqid( $this->_fileName . '_' );
	}

	/**
	 * @return AUpload
	 */
	private function _cacheOldAttachmentFiles(): AUpload {
		$aMeta        = wp_get_attachment_metadata( $this->_updateAttachmentId );
		$backup_sizes = get_post_meta( $this->_updateAttachmentId, '_wp_attachment_backup_sizes', true );
		$file         = get_attached_file( $this->_updateAttachmentId );

		$this->_aOldData = [
			'meta'        => $aMeta,
			'backupSizes' => $backup_sizes,
			'file'        => $file
		];

		return $this;
	}

	private function _deleteOldAttachmentFiles() {
		if ( is_multisite() ) {
			clean_dirsize_cache( $this->_aOldData['file'] );
		}

		wp_delete_attachment_files(
			$this->_updateAttachmentId, $this->_aOldData['meta'],
			$this->_aOldData['backupSizes'],
			$this->_aOldData['file']
		);
	}

	private function _setCategories() {
		foreach ( $this->_aCategories as $taxonomy => $aIds ) {
			wp_set_post_terms( $this->_attachmentId, $aIds, $taxonomy );
		}
	}

	private function _filterCategories( array $aRawCategories, $taxonomy ): array {
		return array_filter( $aRawCategories, function ( $id ) use ( $taxonomy ) {
			return term_exists( $id, $taxonomy );
		} );
	}

	private function _getSupportedTaxonomies(): array {
		if ( ! empty( $this->_aSupportedTaxonomies ) ) {
			return $this->_aSupportedTaxonomies;
		}

		foreach ( proomolandRepository()->setFile( 'taxonomies' )->get( 'taxonomies' ) as $aItem ) {
			if ( in_array( 'attachment', $aItem['post_types'] ) ) {
				$this->_aSupportedTaxonomies = $aItem['taxonomy'];
			}
		}

		return $this->_aSupportedTaxonomies;
	}

	private function _isSupportedTaxonomy( $taxonomy ): bool {
		if ( empty( $this->_getSupportedTaxonomies() ) ) {
			return false;
		}

		return in_array( $taxonomy, $this->_aSupportedTaxonomies );
	}

	protected function _parseTaxonomies(): AUpload {
		if ( isset( $this->aRawFile['taxonomies'] ) ) {
			foreach ( $this->aRawFile['taxonomies'] as $taxonomy => $aIds ) {
				if ( $this->_isSupportedTaxonomy( $taxonomy ) ) {
					$this->_aCategories[ $taxonomy ] = $this->_filterCategories( $aIds, $taxonomy );
				}
			}
		}

		return $this;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function _proceedUpdateOrInsertAttachment(): array {
		if ( empty( $this->_fileMimeType ) ) {
			throw new \Exception( esc_html__( 'The file mime type is required', 'myshopkit' ) );
		}

		if ( empty( $this->_fileName ) ) {
			throw new \Exception( esc_html__( 'The file name type is required', 'myshopkit' ) );
		}

		if ( empty( $this->_extension ) ) {
			throw new \Exception( esc_html__( 'The extension is required', 'myshopkit' ) );
		}

		if ( ! in_array( $this->_imgSource, $this->_aValidSources ) ) {
			throw new \Exception( esc_html__( 'The image source is not allowed', 'myshopkit' ) );
		}

		// Get the path to the upload directory.
		// Prepare an array of post data for the attachment.
		$aAttachment = [
			'post_mime_type'        => $this->_fileMimeType,
			'post_title'            => $this->_fileName,
			'post_content'          => '',
			'guid'                  => trailingslashit( $this->_uploadUrl() ) . $this->_getFileNameWithExtension(),
			'post_status'           => 'inherit',
			'post_author'           => get_current_user_id(),
			'post_content_filtered' => $this->_postContentFiltered
		];

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
			require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
			require_once( ABSPATH . "wp-admin" . '/includes/media.php' );
		}

		// Insert the attachment.
		if ( ! empty( $this->_updateAttachmentId ) ) {
			$this->_cacheOldAttachmentFiles();

			$status = update_attached_file(
				$this->_updateAttachmentId,
				trailingslashit( $this->_uploadPath() ) . $this->_getFileNameWithExtension()
			);

			if ( ! $status ) {
				throw new \Exception( esc_html__( 'We could not update the data', 'myshopkit' ) );
			}

			$this->_deleteOldAttachmentFiles();
			$attachmentId = $this->_updateAttachmentId;
		} else {
			$attachmentId = wp_insert_attachment(
				$aAttachment,
				trailingslashit( $this->_uploadPath() ) . $this->_getFileNameWithExtension()
			);
		}

		if ( is_wp_error( $attachmentId ) ) {
			throw new \Exception( $attachmentId->get_error_message() );
		}

		// wp_generate_attachment_metadata() won't work if you do not include this file
		// Generate and save the attachment metas into the database
		$aAttachData = wp_generate_attachment_metadata(
			$attachmentId,
			trailingslashit( $this->_uploadPath() ) . $this->_getFileNameWithExtension()
		);

		wp_update_attachment_metadata(
			$attachmentId,
			$aAttachData
		);

		if ( $this->type ) {
			update_post_meta( $attachmentId, 'pl_type', $this->type );
		}

		$this->_attachmentId = (int) $attachmentId;
		$this->_setCategories();

		// Show the uploaded file in browser
		return [
			'id'     => $attachmentId,
			'url'    => wp_get_attachment_image_url( $attachmentId, 'full' ),
			'msg'    => sprintf(
				esc_html__( 'The file %s has been uploaded successfully', 'myshopkit' ),
				$this->_fileName
			),
			'status' => 'success'
		];
	}
}
