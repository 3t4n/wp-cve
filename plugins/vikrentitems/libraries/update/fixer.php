<?php
/** 
 * @package   	VikRentItems - Libraries
 * @subpackage 	update
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Implements the abstract methods to fix an update.
 *
 * Never use exit() and die() functions to stop the flow.
 * Return false instead to break process safely.
 */
class VikRentItemsUpdateFixer
{
	/**
	 * The current version.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Class constructor.
	 */
	public function __construct($version)
	{
		$this->version = $version;
	}

	/**
	 * This method is called before the SQL installation.
	 *
	 * @return 	boolean  True to proceed with the update, otherwise false to stop.
	 */
	public function beforeInstallation()
	{
		/**
		 * The photos uploaded before this version may not have a backup copy
		 * to be restored in all formats. The "big_" format may be missing.
		 * 
		 * @since 	1.1.4
		 */
		if (version_compare($this->version, '1.1.4', '<')) {
			// get the array information of the upload dir
			$upload_dir = wp_upload_dir();
			if (!is_array($upload_dir) || empty($upload_dir['basedir'])) {
				// just go ahead
				return true;
			}

			// this is where all photos should be
			$photo_mirroring_path = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'vikrentitems' . DIRECTORY_SEPARATOR . 'front';

			// get all thumbnails
			foreach (JFolder::files($photo_mirroring_path, '^thumb_') as $photo_thumb) {
				$no_thumb = preg_replace("/^thumb_/", '', $photo_thumb);
				$large_photo = "big_$no_thumb";
				if (is_file($photo_mirroring_path . DIRECTORY_SEPARATOR . $no_thumb) && !is_file($photo_mirroring_path . DIRECTORY_SEPARATOR . $large_photo)) {
					// move missing file to mirroring backup dir
					JFile::copy($photo_mirroring_path . DIRECTORY_SEPARATOR . $no_thumb, $photo_mirroring_path . DIRECTORY_SEPARATOR . $large_photo);
					// move file onto official directory
					$official_photo_path = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $large_photo;
					if (!is_file($official_photo_path)) {
						JFile::copy($photo_mirroring_path . DIRECTORY_SEPARATOR . $no_thumb, $official_photo_path);
					}
				}
			}
		}

		return true;
	}

	/**
	 * This method is called after the SQL installation.
	 *
	 * @return 	boolean  True to proceed with the update, otherwise false to stop.
	 */
	public function afterInstallation()
	{
		return true;
	}
}
