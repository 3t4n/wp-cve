<?php
/** MainWP Child Reports Exporter. */

namespace WP_MainWP_Stream;

/**
 * Class Exporter.
 * @package WP_MainWP_Stream
 */
abstract class Exporter {
	/**
	 * Exporter name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Exporter slug
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Output formatted data for download
	 *
	 * @param array $data Array of data to output.
	 * @param array $columns Column names included in data set.
	 * @return void
	 */
	abstract public function output_file( $data, $columns );

	/**
	 * Allow connectors to determine if their dependencies is satisfied or not
	 *
	 * @return bool
	 */
	public function is_dependency_satisfied() {
		return true;
	}
}
