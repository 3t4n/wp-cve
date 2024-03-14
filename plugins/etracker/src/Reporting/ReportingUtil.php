<?php
/**
 * Static utility functions used in reporting.
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting;

/**
 * ReportingUtils.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class ReportingUtil {
	/**
	 * Filter URL parts and return etracker useable URL string.
	 *
	 * @param string $url Input URL to be converted.
	 *
	 * @return string Cleaned URL $url ready to be used as filter.
	 */
	public static function url2etracker_url( string $url ): string {
		$parsed_url = parse_url( $url );
		$host       = isset( $parsed_url['host'] ) ? $parsed_url['host'] : '';
		$port       = isset( $parsed_url['port'] ) ? ':' . $parsed_url['port'] : '';
		$path       = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '';
		$query      = isset( $parsed_url['query'] ) ? '?' . $parsed_url['query'] : '';
		$fragment   = isset( $parsed_url['fragment'] ) ? '#' . $parsed_url['fragment'] : '';
		return "$host$port$path$query$fragment";
	}
}
