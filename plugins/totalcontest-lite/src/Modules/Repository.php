<?php

namespace TotalContest\Modules;

use TotalContest\Contracts\Modules\Repository as RepositoryContract;
use TotalContestVendors\TotalCore\Modules\Repository as RepositoryBase;

/**
 * Template.
 * @package TotalContest\Modules
 */
class Repository extends RepositoryBase implements RepositoryContract {

	/**
	 * Get defaults.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function getDefaults( $moduleId ) {
		$module = $this->getInstalled( $moduleId );

		if ( empty( $module['defaults'] ) ):
			return [];
		endif;

		if ( is_array( $module['defaults'] ) ):
			return $module['defaults'];
		endif;

		$defaults = [];
		$path     = "{$module['dirName']}/{$module['defaults']}";
		if ( file_exists( $path ) ):
			$defaults = ( include $path );

			if ( ! is_array( $defaults ) ):
				$defaults = [];
			endif;
		endif;

		return $defaults;
	}

	/**
	 * Get preview.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function getPreview( $moduleId ) {
		$module = $this->getInstalled( $moduleId );

		if ( empty( $module['preview'] ) ):
			return '';
		endif;

		$path = "{$module['dirName']}/{$module['preview']}";

		if ( file_exists( $path ) ):
			ob_start();
			include $path;

			return ob_get_clean();
		endif;

		return '';
	}

	/**
	 * Get settings.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function getSettings( $moduleId ) {
		$module = $this->getInstalled( $moduleId );

		if ( empty( $module['settings'] ) ):
			return '';
		endif;

		$path = "{$module['dirName']}/{$module['settings']}";

		if ( file_exists( $path ) ):
			ob_start();
			include $path;

			return ob_get_clean();
		endif;

		return '';
	}

	/**
	 * Get stylesheet.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function getStylesheet( $moduleId ) {
		$module = $this->getInstalled( $moduleId );

		if ( empty( $module['stylesheet'] ) ):
			return '';
		endif;

		$path = "{$module['dirName']}/{$module['stylesheet']}";

		if ( file_exists( $path ) ):
			ob_start();
			include $path;

			return ob_get_clean();
		endif;

		return '';
	}
}