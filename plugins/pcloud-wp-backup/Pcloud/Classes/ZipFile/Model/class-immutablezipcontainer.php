<?php
/**
 * Class ImmutableZipContainer.
 *
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model;

use Countable;

/**
 * Class immutablezipcontainer.
 */
class ImmutableZipContainer implements Countable {

	/**
	 * Zip entries.
	 *
	 * @var ZipEntry[] $entries
	 */
	protected $entries;

	/**
	 * Archive comment if any.
	 *
	 * @var string|null Archive comment.
	 */
	protected $archive_comment;

	/**
	 * Class constructor.
	 *
	 * @param ZipEntry[]  $entries The entries collection.
	 * @param string|null $archive_comment Archive comment.
	 */
	public function __construct( array $entries, ?string $archive_comment = null ) {
		$this->entries         = $entries;
		$this->archive_comment = $archive_comment;
	}

	/**
	 * Get entries.
	 *
	 * @return ZipEntry[]
	 */
	public function &get_entries(): array {
		return $this->entries;
	}

	/**
	 * Archive comment.
	 *
	 * @return string|null
	 */
	public function get_archive_comment(): ?string {
		return $this->archive_comment;
	}

	/**
	 * Count elements of an object.
	 *
	 * @return int The custom count as an integer. The return value is cast to an integer.
	 */
	public function count(): int {
		return count( $this->entries );
	}

	/**
	 * When an object is cloned, PHP 5 will perform a shallow copy of all the object's properties.
	 * Any properties that are references to other variables, will remain references.
	 * Once the cloning is complete, if a __clone() method is defined,
	 * then the newly created object's __clone() method will be called, to allow any necessary properties that need to
	 * be changed. NOT CALLABLE DIRECTLY.
	 */
	public function __clone() {
		foreach ( $this->entries as $key => $value ) {
			$this->entries[ $key ] = clone $value;
		}
	}
}
