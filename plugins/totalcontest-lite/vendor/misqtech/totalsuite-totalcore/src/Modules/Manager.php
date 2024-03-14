<?php

namespace TotalContestVendors\TotalCore\Modules;

use TotalContestVendors\TotalCore\Contracts\Filesystem\Base as FilesystemContract;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment as EnvironmentContract;
use TotalContestVendors\TotalCore\Contracts\Http\File as FileContract;
use TotalContestVendors\TotalCore\Contracts\Modules\Manager as ManagerContract;
use TotalContestVendors\TotalCore\Helpers\Tracking;
use TotalContestVendors\TotalCore\Http\File;

/**
 * Class Manager
 * @package TotalContestVendors\TotalCore\Modules
 */
class Manager implements ManagerContract {
	/**
	 * @var Repository
	 */
	protected $repository;
	/**
	 * @var FilesystemContract
	 */
	protected $filesystem;
	/**
	 * @var EnvironmentContract
	 */
	protected $env;

	/**
	 * Manager constructor.
	 *
	 * @param Repository          $repository
	 * @param FilesystemContract  $filesystem
	 * @param EnvironmentContract $env
	 */
	public function __construct( Repository $repository, FilesystemContract $filesystem, EnvironmentContract $env ) {
		$this->repository = $repository;
		$this->filesystem = $filesystem;
		$this->env        = $env;
	}

	/**
	 * @return array|mixed
	 */
	public function fetch() {
		return $this->repository->getAll();
	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|mixed|\WP_Error
	 */
	public function update( $moduleId ) {
		// Uninstall first
		$uninstall = $this->uninstall( $moduleId );
		if ( $uninstall instanceof \WP_Error ):
			return $uninstall;
		endif;

		// Then install it again
		$installUpdate = $this->installFromStore( $moduleId );
		if ( $uninstall instanceof \WP_Error ):
			return $installUpdate;
		endif;

		// Then activate it
		$activate = $this->activate( $moduleId );
		if ( $activate instanceof \WP_Error ):
			return $installUpdate;
		endif;

		return true;
	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|\WP_Error
	 */
	public function uninstall( $moduleId ) {
		$module = $this->repository->get( $moduleId );

		if ( $module && ! empty( $module['dirName'] ) ):
			$module['class']::onUninstall();
			$this->filesystem->delete( $module['dirName'], true, 'd' );
			$this->repository->setInactive( $moduleId );
            Tracking::trackEvents('uninstall-module', $moduleId);
			return true;
		endif;

		return new \WP_Error( 'uninstall_failure', __( 'Unable to uninstall this module. Refresh this page if the problem persists.', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) ) );

	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|mixed|\WP_Error
	 */
	public function installFromStore( $moduleId ) {
		$module = $this->repository->getFromStore( $moduleId );

		if ( $module && ! empty( $module['download'] ) ):
			return $this->install( $module['download'] );
		else:
			return new \WP_Error( 'lookup_failure', 'Unable to find this module in store.' );
		endif;
	}

	/**
	 * @param $moduleZip
	 *
	 * @return bool|mixed|\WP_Error
	 */
	public function install( $moduleZip ) {
		// Result
		$result = true;
		// Filesystem
		WP_Filesystem();
		// Download zip file when necessary
		if ( filter_var( $moduleZip, FILTER_VALIDATE_URL ) ):
			$downloaded = download_url( $moduleZip );
			if ( $downloaded instanceof \WP_Error ):
				$result = new \WP_Error( 'download_failure', 'Unable to download this module. Please try again.' );
			else:
				$moduleZip = new File( $downloaded, 'module.zip' );
			endif;
		endif;

		if ( $moduleZip instanceof FileContract ):
			// Generate a temporary directory
			$tempDir = get_temp_dir() . uniqid( time(), false );
			// Try to unzip the module zip file
			$result = unzip_file( $moduleZip->getPathname(), $tempDir );

			if ( $result === true ):
				// Search for directories
				$dirs = glob( "{$tempDir}/*", GLOB_ONLYDIR );

				if ( empty( $dirs ) ):
					// Nothing to install
					$result = new \WP_Error( 'empty_archive', __( 'Archive is empty or does not contain a directory.', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) ) );
				else:
					// Get first directory
					$moduleDir = $dirs[0];

					if ( ! file_exists( "{$moduleDir}/module.json" ) ):
						$result = new \WP_Error( 'no_module_json', __( 'module.json file is absent.', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) ) );
					else:
						// Parse attributes
						$moduleAttributes = json_decode( file_get_contents( "{$moduleDir}/module.json" ), true );

						// Get module directory name.
						$moduleDirName = basename( $moduleDir );

						// Default templates installation path
						$modulesPath = WP_CONTENT_DIR . "/uploads/{$this->env['slug']}/{$moduleAttributes['type']}s/{$moduleDirName}";

						// Fallback to plugin's directory
						if ( ! is_dir( $modulesPath ) && ! wp_mkdir_p( $modulesPath ) ) :
							$modulesPath = $this->env['path'] . "modules/{$moduleAttributes['type']}s/{$moduleDirName}";
						endif;

						Tracking::trackEvents('install-module', $moduleAttributes['id']);
						// Copy template's files
						$result = copy_dir( $moduleDir, $modulesPath );
					endif;

				endif;

			endif;

			$this->filesystem->delete( $tempDir, true, 'd' );
			$this->filesystem->delete( $moduleZip->getPathname(), false, 'f' );
		endif;

		return $result;
	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|\WP_Error
	 */
	public function activate( $moduleId ) {
		if ( $this->repository->setActive( $moduleId ) ):
            Tracking::trackEvents('activate-module', $moduleId);
			return true;
		endif;

		return new \WP_Error( 'activation_failure', __( 'Unable to activate this module. Refresh this page if the problem persists.', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) ) );
	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|\WP_Error
	 */
	public function deactivate( $moduleId ) {
		if ( $this->repository->setInactive( $moduleId ) ):
            Tracking::trackEvents('deactivate-module', $moduleId);
			return true;
		endif;

		return new \WP_Error( 'deactivation_failure', __( 'Unable to deactivate this module. Refresh this page if the problem persists.', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) ) );
	}


}