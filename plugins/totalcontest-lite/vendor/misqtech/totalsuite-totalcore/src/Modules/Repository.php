<?php

namespace TotalContestVendors\TotalCore\Modules;

use TotalContestVendors\TotalCore\Contracts\Admin\Account as AccountContract;
use TotalContestVendors\TotalCore\Contracts\Admin\Activation as ActivationContract;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment as EnvironmentContract;
use TotalContestVendors\TotalCore\Contracts\Modules\Repository as RepositoryContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Strings;

/**
 * Class RepositoryService
 * @package TotalContestVendors\TotalCore\Modules
 */
class Repository implements RepositoryContract {
	/**
	 * @var EnvironmentContract $env
	 */
	protected $env;
	/**
	 * @var ActivationContract $activation
	 */
	protected $activation;
	/**
	 * @var AccountContract $account
	 */
	protected $account;

	/**
	 * Repository constructor.
	 *
	 * @param EnvironmentContract $env
	 * @param ActivationContract  $activation
	 * @param AccountContract     $account
	 */
	public function __construct( EnvironmentContract $env, ActivationContract $activation, AccountContract $account ) {
		$this->env        = $env;
		$this->activation = $activation;
		$this->account    = $account;
	}

	/**
	 * @param $criteria
	 *
	 * @return array|mixed
	 */
	public function getActiveWhere( $criteria ) {
		return array_filter( $this->getActive(), function ( $module ) use ( $criteria ) {
			foreach ( $criteria as $key => $value ):
				if ( ! isset( $module[ $key ] ) || $module[ $key ] != $value ):
					return false;
				endif;
			endforeach;

			return true;
		} );
	}

	/**
	 * @return array|mixed
	 */
	public function getActive() {
		$installedModules = $this->getAllInstalled();
		$activeModules    = [];
		foreach ( $installedModules as $moduleId => $module ):
			if ( ! empty( $module['activated'] ) ):
				$activeModules[ $moduleId ] = $module;
			endif;
		endforeach;

		return $activeModules;
	}

	/**
	 * @return array|mixed
	 */
	public function getAllInstalled() {
		$pluginPath        = $this->env['path'];
		$packagedTemplates = $this->fetchFromLocal( "{$pluginPath}/modules/templates/" );
		$uploadedTemplates = $this->fetchFromLocal( WP_CONTENT_DIR . "/uploads/{$this->env['slug']}/templates/" );

		$packagedExtensions = $this->fetchFromLocal( "{$pluginPath}/modules/extensions/" );
		$uploadedExtensions = $this->fetchFromLocal( WP_CONTENT_DIR . "/uploads/{$this->env['slug']}/extensions/" );

		return array_merge( $packagedTemplates, $packagedExtensions, $uploadedTemplates, $uploadedExtensions );
	}

	/**
	 * @param $path
	 *
	 * @return array
	 */
	protected function fetchFromLocal( $path ) {
		$activatedModules   = $this->getActivatedModules();
		$path               = wp_normalize_path( $path . '/*' );
		$modules            = [];
		$modulesDirectories = glob( $path, GLOB_ONLYDIR );
		$namespaces         = [
			'template'  => '\\{{namespace}}\\Modules\\Templates\\{{dirName}}\\Template',
			'extension' => '\\{{namespace}}\\Modules\\Extensions\\{{dirName}}\\Extension',
		];

		foreach ( $modulesDirectories as $moduleDirectory ):
			if ( ! file_exists( "{$moduleDirectory}/module.json" ) ):
				continue;
			endif;

			$moduleAttributes = json_decode( file_get_contents( "{$moduleDirectory}/module.json" ), true );

			if ( empty( $moduleAttributes['version'] ) ):
				$moduleAttributes['version'] = '1.0.0';
			endif;

			if ( empty( $moduleAttributes['id'] ) ):
				$moduleAttributes['id'] = md5( $moduleDirectory );
			endif;

			$moduleAttributes['lastVersion'] = $moduleAttributes['version'];

			if ( empty( $moduleAttributes['type'] ) ):
				$moduleAttributes['type'] = 'module';
			endif;

            if(strpos($path, 'uploads') !== false) {
                $basePath = str_replace(wp_upload_dir()['basedir'], '', $moduleDirectory);
                $moduleAttributes['url'] = trailingslashit(wp_upload_dir()['baseurl'] . $basePath);
            } else {
                $moduleAttributes['url'] = trailingslashit($this->env->get( 'url' ) . str_replace( $this->env->get('path'), '/', $moduleDirectory ));
            }

			$moduleAttributes['dirName']   = $moduleDirectory;
			$moduleAttributes['installed'] = true;
			$moduleAttributes['activated'] = ! empty( $moduleAttributes['built-in'] ) || ! empty( $activatedModules[ $moduleAttributes['id'] ] );
			$moduleAttributes['update']    = version_compare( $moduleAttributes['lastVersion'], $moduleAttributes['version'], '>' );

			if ( isset( $namespaces[ $moduleAttributes['type'] ] ) ) {
				$moduleAttributes['class'] = Strings::template(
					$namespaces[ $moduleAttributes['type'] ],
					[ 'namespace' => $this->env['namespace'], 'dirName' => basename( $moduleDirectory ) ]
				);
			}

			$modules[ $moduleAttributes['id'] ] = $moduleAttributes;
		endforeach;

		return $modules;
	}

	/**
	 * @return array
	 */
	protected function getActivatedModules() {
		return (array) get_option( "{$this->env['slug']}_modules", [] );
	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|mixed
	 */
	public function get( $moduleId ) {
		return $this->getInstalled( $moduleId ) ?: $this->getFromStore( $moduleId );
	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|mixed
	 */
	public function getInstalled( $moduleId ) {
		$installed = $this->getAllInstalled();

		return isset( $installed[ $moduleId ] ) ? $installed[ $moduleId ] : false;
	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|mixed
	 */
	public function getFromStore( $moduleId ) {
		$store = $this->getAllStore();

		return isset( $store[ $moduleId ] ) ? $store[ $moduleId ] : false;
	}

	/**
	 * @return array|mixed
	 */
	public function getAllStore() {
		if ( $cached = get_transient( "{$this->env['slug']}_modules_store_response" ) ):
			return $cached;
		endif;

		$modules     = [];
		$args        = [
			'license' => $this->activation->getLicenseKey() ?: 'none',
			'domain'  => $this->env['domain'],
			'version' => $this->env['version'],
		];
		$apiEndpoint = Strings::template( $this->env['api.store'], $args );
		$apiRequest  = add_query_arg( $args, $apiEndpoint );

		$headers = [];
		if ( $this->account->isLinked() ):
			$headers['Authorization'] = 'Bearer ' . $this->account->getAccessToken();
		endif;

		$apiResponse = json_decode( wp_remote_retrieve_body( wp_remote_get( $apiRequest, [ 'headers' => $headers ] ) ), true );

		if ( ! empty( $apiResponse['data'] ) ):
			$modules = $apiResponse['data'];
		endif;

		set_transient( "{$this->env['slug']}_modules_store_response", $modules, DAY_IN_SECONDS );

		return $modules;

	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|mixed
	 */
	public function setActive( $moduleId ) {
		$module = $this->getInstalled( $moduleId );
		if ( $module ):
			$modules              = $this->getActivatedModules();
			$modules[ $moduleId ] = true;
			$module['class']::onActivate();

			return $this->setActivatedModules( $modules );
		endif;

		return false;
	}

	/**
	 * @param $modules
	 *
	 * @return bool
	 */
	protected function setActivatedModules( $modules ) {
		return update_option( "{$this->env['slug']}_modules", $modules );
	}

	/**
	 * @param $moduleId
	 *
	 * @return bool|mixed
	 */
	public function setInactive( $moduleId ) {
		$module = $this->getInstalled( $moduleId );
		if ( $module ):
			$modules = $this->getActivatedModules();
			unset( $modules[ $moduleId ] );
			$module['class']::onDeactivate();

			return $this->setActivatedModules( $modules );
		endif;

		return false;
	}

	/**
	 * @param $criteria
	 *
	 * @return mixed
	 */
	public function getWhere( $criteria ) {
		return array_filter( $this->getAll(), function ( $module ) use ( $criteria ) {
			foreach ( $criteria as $key => $value ):
				if ( ! isset( $module[ $key ] ) || $module[ $key ] != $value ):
					return false;
				endif;
			endforeach;

			return true;
		} );
	}

	/**
	 * @return array|mixed
	 */
	public function getAll() {
		return $this->mergeModules( $this->getAllInstalled(), $this->getAllStore() );
	}

	/**
	 * @param $localModules
	 * @param $storeModules
	 *
	 * @return array
	 */
	protected function mergeModules( $localModules, $storeModules ) {
		foreach ( $localModules as $localModuleId => $localModule ):
			if ( isset( $storeModules[ $localModuleId ] ) ):
				$localModules[ $localModuleId ]                = Arrays::parse( $localModule, $storeModules[ $localModuleId ] );
				$localModules[ $localModuleId ]['lastVersion'] = $storeModules[ $localModuleId ]['version'];
				$localModules[ $localModuleId ]['update']      = version_compare( $storeModules[ $localModuleId ]['version'], $localModules[ $localModuleId ]['version'], '>' );
				$localModules[ $localModuleId ]['permalink']   = empty( $storeModules[ $localModuleId ]['permalink'] ) ? $localModule['permalink'] : esc_url( $storeModules[ $localModuleId ]['permalink'] );
				$localModules[ $localModuleId ]['description'] = empty( $storeModules[ $localModuleId ]['description'] ) ? $localModule['description'] : $storeModules[ $localModuleId ]['description'];
				$localModules[ $localModuleId ]['purchased']   = $storeModules[ $localModuleId ]['purchased'];
				$localModules[ $localModuleId ]['download']    = $storeModules[ $localModuleId ]['download'];
				$localModules[ $localModuleId ]['requires']    = $storeModules[ $localModuleId ]['requires'];
				unset( $storeModules[ $localModuleId ] );
			endif;
		endforeach;

		return array_merge( $localModules, $storeModules );
	}
}
