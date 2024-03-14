<?php
/**
 * UpStream_Model_File
 *
 * WordPress Coding Standart (WCS) note:
 * All camelCase methods and object properties on this file are not converted to snake_case,
 * because it being used (heavily) on another add-on plugins.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UpStream_Model_File
 */
class UpStream_Model_File extends UpStream_Model_Meta_Object {

	/**
	 * FileId
	 *
	 * @var int
	 */
	protected $fileId = 0; // phpcs:ignore

	/**
	 * UpfsFileId
	 *
	 * @var undefined
	 */
	protected $upfsFileId = null; // phpcs:ignore

	/**
	 * CreatedAt
	 *
	 * @var int
	 */
	protected $createdAt = 0; // phpcs:ignore

	/**
	 * Reminders
	 *
	 * @var array
	 */
	protected $reminders = array();

	/**
	 * MetadataKey
	 *
	 * @var string
	 */
	protected $metadataKey = '_upstream_project_files'; // phpcs:ignore

	/**
	 * Type
	 *
	 * @var undefined
	 */
	protected $type = UPSTREAM_ITEM_TYPE_FILE;

	/**
	 * UpStream_Model_File constructor.
	 *
	 * @param  mixed $parent parent.
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	public function __construct( $parent, $item_metadata ) {
		parent::__construct( $parent, $item_metadata );

		$this->type = UPSTREAM_ITEM_TYPE_FILE;
	}

	/**
	 * LoadFromArray
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	protected function loadFromArray( $item_metadata ) {
		parent::loadFromArray( $item_metadata );

		$this->createdAt = ! empty( $item_metadata['created_at'] ) ? $item_metadata['created_at'] : null; // phpcs:ignore

		if ( upstream_filesytem_enabled() && isset( $item_metadata['file'] ) && upstream_upfs_info( $item_metadata['file'] ) ) {
			$this->upfsFileId = $item_metadata['file']; // phpcs:ignore
		} elseif ( isset( $item_metadata['file'] ) && $item_metadata['file'] ) {
			$fid = @attachment_url_to_postid( $item_metadata['file'] );
			if ( $fid ) {
				$this->fileId = $fid; // phpcs:ignore
			}
		}

		if ( ! $this->fileId && ! $this->upfsFileId ) { // phpcs:ignore
			if ( ! empty( $item_metadata['file_id'] ) ) {
				$file = get_attached_file( $item_metadata['file_id'] );
				if ( false !== $file ) {
					$this->fileId = $item_metadata['file_id']; // phpcs:ignore
				}
			}
		}

		if ( ! empty( $item_metadata['reminders'] ) ) {
			foreach ( $item_metadata['reminders'] as $reminder_data ) {
				$exception = null;
				try {
					$d                 = json_decode( $reminder_data, true );
					$reminder          = new UpStream_Model_Reminder( $d );
					$this->reminders[] = $reminder;
				} catch ( \Exception $e ) {
					$exception = $e; // don't add anything else.
				}
			}
		}
	}

	/**
	 * StoreToArray
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	public function storeToArray( &$item_metadata ) {
		parent::storeToArray( $item_metadata );

		if ( $this->fileId > 0 ) { // phpcs:ignore
			$url = wp_get_attachment_url( $this->fileId ); // phpcs:ignore

			if ( false !== $url ) {
				$item_metadata['file']    = $url;
				$item_metadata['file_id'] = $this->fileId; // phpcs:ignore
			}
		} elseif ( $this->upfsFileId && upstream_filesytem_enabled() ) { // phpcs:ignore
			$item_metadata['file'] = $this->upfsFileId; // phpcs:ignore
		}

		if ( $this->createdAt >= 0 ) { // phpcs:ignore
			$item_metadata['created_at'] = $this->createdAt; // phpcs:ignore
		}
		$item_metadata['reminders'] = array();

		foreach ( $this->reminders as $reminder ) {
			$r = array();
			$reminder->storeToArray( $r );
			$item_metadata['reminders'][] = json_encode( $r );
		}
	}

	/**
	 * Get
	 *
	 * @param  mixed $property property.
	 */
	public function __get( $property ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'fileId': // phpcs:ignore
				if ( upstream_filesytem_enabled() ) {
					return $this->upfsFileId; // phpcs:ignore
				} else {
					return $this->fileId; // phpcs:ignore
				}

			case 'filename':
				if ( upstream_filesytem_enabled() ) {
					$file = upstream_upfs_info( $this->upfsFileId ); // phpcs:ignore
					if ( $file ) {
						return $file->orig_filename;
					}
				} else {
					$file_id = $this->fileId; // phpcs:ignore
					if ( $file_id > 0 ) {
						$file = get_attached_file( $file_id );
						return $file ? basename( $file ) : '';
					}
				}
				return '';

			case 'fileURL':
				if ( upstream_filesytem_enabled() ) {
					$file = upstream_upfs_info( $this->upfsFileId ); // phpcs:ignore
					if ( $file ) {
						return upstream_upfs_get_file_url( $this->upfsFileId ); // phpcs:ignore
					}
				} else {
					$file_id = $this->fileId; // phpcs:ignore
					if ( $file_id > 0 ) {
						$url = wp_get_attachment_url( $file_id );
						return $url || '';
					}
				}
				return '';

			case 'createdAt': // phpcs:ignore
				if ( $this->createdAt > 0 ) { // phpcs:ignore
					return self::timestampToYMD( $this->createdAt ); // phpcs:ignore
				} else {
					return '';
				}

			default:
				return parent::__get( $property );
		}
	}

	/**
	 * Set
	 *
	 * @param  mixed $property property.
	 * @param  mixed $value value.
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	public function __set( $property, $value ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'fileId':
				if ( upstream_filesytem_enabled() ) {
					throw new UpStream_Model_ArgumentException( __( 'Set not implemented for Upfs.', 'upstream' ) );
				} else {
					$file = get_attached_file( $value );
					if ( false === $file ) {
						throw new UpStream_Model_ArgumentException(
							sprintf(
								// translators: %s: file id.
								__( 'File ID %s is invalid.', 'upstream' ),
								$value
							)
						);
					}

					$this->fileId = $value; // phpcs:ignore
				}
				break;

			default:
				parent::__set( $property, $value );
				break;
		}
	}


	/**
	 * Fields
	 */
	public static function fields() {
		$fields = parent::fields();

		$fields['fileId']    = array(
			'type'    => 'file',
			'title'   => __( 'File' ),
			'search'  => false,
			'display' => true,
		);
		$fields['createdAt'] = array(
			'type'    => 'date',
			'title'   => __( 'Upload Date' ),
			'search'  => true,
			'display' => true,
		);

		$fields = self::customFields( $fields, UPSTREAM_ITEM_TYPE_FILE );

		return $fields;
	}

	/**
	 * Create
	 *
	 * @param  mixed $parent parent.
	 * @param  mixed $title title.
	 * @param  mixed $created_by created_by.
	 */
	public static function create( $parent, $title, $created_by ) {
		$item_metadata = array(
			'title'      => $title,
			'created_by' => $created_by,
		);

		return new self( $parent, $item_metadata );
	}

}
