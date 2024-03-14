<?php
/**
 *	@package ThePaste\Core
 *	@version 1.0.0
 *	2018-09-22
 */

namespace ThePaste\Core;

interface CoreInterface {

	/**
	 *	@return string current Plugin version
	 */
	public function version();


	/**
	 *	Return locations where to look for assets and map them to URLs.
	 *
	 *	@return array [
	 * 		'absolute_path'	=> 'absolute_url',
	 * ]
	 */
	public function get_asset_roots();

}
