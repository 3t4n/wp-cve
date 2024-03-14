<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsConfig{

	protected $config;

	protected $reader;

	public function __construct() {
		$this->reader = new apeGalleryFieldsConfigReader();

		$this->read();
	}

	protected function read(){

		$files = self::getConfigFiles(WPAPE_GALLERY_FIELDS_PATH_CONFIG);

		foreach ($files as $configName => $filePath) {
			preg_match('/\.([a-z0-9]+)$/', $filePath, $match);
			$extension = isset($match[1]) ? $match[1] : null;
			if (!$this->reader->isAllowExtension($extension)) {
				continue;
			}

			$configData = $this->reader->read($filePath);

			if (!is_array($configData)) {
				throw new \Exception(sprintf( 'Wrong configuration %s', $filePath));
			}
			$this->set($configName, $configData);
		}

		if (empty($this->config)) {
			throw new \Exception('Empty configuration');
		}
	}

	protected function getConfigFiles($dir){
		$files = array();

		foreach (scandir($dir) as $file) {
			if ('.' === $file || '..' === $file) {
				continue;
			}

			$path = $dir . $file;

			if (is_file($path)) {
				$configName = preg_replace('/\..*$/', '', $file);
				$files[$configName] = $path;

				continue;
			}

			if (is_dir($path)) {
				$subFiles = $this->getConfigFiles("{$path}/");
				foreach ($subFiles as $subConfigName => $subPath) {
					$files["{$file}/{$subConfigName}"] = $subPath;
				}
			}
		}

		return $files;
	}

	protected function set($path, $value){
		$pieces = explode('/', $path);
		$lastPiece = array_pop($pieces);
		$config = &$this->config;

		foreach ($pieces as $piece) {
			if (!isset($config[$piece]) || !is_array($config[$piece])) {
				$config[$piece] = array();
			}
			$config = &$config[$piece];
		}
		$config[$lastPiece] = $value;
	}

	public function get($path){
		$pieces = explode('/', $path);
		$config = &$this->config;

		foreach ($pieces as $piece) {
			if (!isset($config[$piece])) {
				return null;
			}
			$config = &$config[$piece];
		}

		return $config;
	}
}
