<?php
/**
 * Class ZipContainer.
 *
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model;

/**
 * Zip Container.
 */
class ZipContainer extends ImmutableZipContainer {

	/**
	 * Class constructor.
	 *
	 * @param ImmutableZipContainer|null $source_container Source container.
	 */
	public function __construct( $source_container = null ) {
		$entries         = array();
		$archive_comment = null;

		if ( null !== $source_container ) {
			foreach ( $source_container->get_entries() as $entry_name => $entry ) {
				$entries[ $entry_name ] = clone $entry;
			}
			$archive_comment = $source_container->get_archive_comment();
		}
		parent::__construct( $entries, $archive_comment );
	}

	/**
	 * Add entry.
	 *
	 * @param ZipEntry $entry The entry.
	 * @return void
	 */
	public function add_entry( ZipEntry $entry ) {
		$this->entries[ $entry->get_name() ] = $entry;
	}

	/**
	 * Delete entry.
	 *
	 * @param string|ZipEntry $entry The entry.
	 */
	public function delete_entry( $entry ): bool {
		$entry = $entry instanceof ZipEntry ? $entry->get_name() : (string) $entry;

		if ( isset( $this->entries[ $entry ] ) ) {
			unset( $this->entries[ $entry ] );

			return true;
		}

		return false;
	}
}
