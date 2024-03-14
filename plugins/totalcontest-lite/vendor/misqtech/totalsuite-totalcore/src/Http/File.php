<?php

namespace TotalContestVendors\TotalCore\Http;

use TotalContestVendors\TotalCore\Contracts\Http\File as FileContract;

/**
 * Class File
 * @package TotalContestVendors\TotalCore\Http
 */
class File extends \SplFileInfo implements FileContract {
	/**
	 * @var string $filename
	 */
	protected $filename;
	/**
	 * @var string $clientFilename
	 */
	protected $clientFilename;

	/**
	 * File constructor.
	 *
	 * @param      $filename
	 * @param null $clientFilename
	 */
	public function __construct( $filename, $clientFilename = null ) {
		$this->filename       = $filename;
		$this->clientFilename = empty( $clientFilename ) ? $filename : $clientFilename;

		parent::__construct( $this->filename );
	}

	/**
	 * Move file.
	 *
	 * @param $target
	 *
	 * @return bool|File
	 */
	#[\ReturnTypeWillChange]
	public function move( $target ) {
		if ( ! @rename( $this->getPathname(), $target ) ) {
			return false;
		}
		@chmod( $target, 0666 & ~umask() );

		return new self( $target, $this->getClientFilename() );
	}

	/**
	 * Get client file name.
	 *
	 * @return string|null
	 */
	#[\ReturnTypeWillChange]
	public function getClientFilename() {
		return $this->clientFilename;
	}

	/**
	 * Count.
	 *
	 * @return int
	 */
	#[\ReturnTypeWillChange]
	public function count() {
		return 1;
	}

	/**
	 * Get JSON.
	 *
	 * @return array|mixed
	 */
    #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->toArray();
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	#[\ReturnTypeWillChange]
	public function toArray() {
		return [
			'extension'       => $this->getExtension(),
			'clientExtension' => $this->getClientExtension(),
			'filename'        => $this->getFilename(),
			'clientFilename'  => $this->getClientFilename(),
			'file'            => $this->getFileInfo(),
			'mimetype'        => $this->getMimeType(),
			'path'            => $this->getRealPath(),
		];
	}

	/**
	 * Get extension.
	 *
	 * @return string
	 */
	#[\ReturnTypeWillChange]
	public function getExtension() {
		$mimetype  = $this->getMimeType();
		$extension = isset( MimeTypes::$list[ $mimetype ] ) ? MimeTypes::$list[ $mimetype ] : '';

		return $extension;
	}

	/**
	 * Get mimetype.
	 *
	 * @return string
	 */
	#[\ReturnTypeWillChange]
	public function getMimeType() {
		$finfo = new \finfo( FILEINFO_MIME_TYPE );

		return $finfo->file( $this->filename );
	}

	/**
	 * Get client extension.
	 *
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
	public function getClientExtension() {
		return pathinfo( $this->clientFilename, PATHINFO_EXTENSION );
	}
}
