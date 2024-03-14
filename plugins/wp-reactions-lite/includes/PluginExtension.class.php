<?php
namespace WP_Reactions\Lite;

class PluginExtension {
	private $front_assets = [];
	private $admin_assets = [];

	function addFrontAsset($type,$handle, $url, $deps = [], $locals = [], $footer = true) {
		$this->front_assets[] = [
			'type' => $type,
			'handle' => $handle,
			'url' => $url,
			'deps' => $deps,
			'footer' => $footer,
			'locals' => $locals
		];
	}

	function addAdminAsset($type ,$handle, $url, $hooks = [], $deps = [], $locals = [], $footer = true) {
		$this->admin_assets[] = [
			'type' => $type,
			'handle' => $handle,
			'url' => $url,
			'deps' => $deps,
			'footer' => $footer,
			'hooks' => $hooks,
			'locals' => $locals,
		];
	}

	function enqueueMedia($hooks = []){
		add_action('admin_enqueue_scripts', function($hook) use ($hooks){
			if (empty($hooks)) {
				wp_enqueue_media();
			} else if (in_array($hook, $hooks)) {
				wp_enqueue_media();
			}
		});
	}

	function flushAssets() {
		add_action('wp_enqueue_scripts', function() {
			if (!empty($this->front_assets)) {
				foreach ($this->front_assets as $asset) {
					if ($asset['type'] == 'style') {
						wp_enqueue_style($asset['handle'], $asset['url']);
					} elseif ($asset['type'] == 'script') {
						wp_enqueue_script($asset['handle'], $asset['url'], $asset['deps'], '', $asset['footer']);
					}

					if (!empty($asset['locals'])) {
						wp_localize_script( $asset['handle'], $asset['locals']['object'], $asset['locals']['vars'] );
					}
				}
			}
		});

		add_action('admin_enqueue_scripts', function($hook) {
			if (!empty($this->admin_assets)) {
				foreach ($this->admin_assets as $asset) {
					if ( isset($asset['hooks']) and (empty($asset['hooks']) or in_array($hook, $asset['hooks'])) ) {
						if ($asset['type'] == 'style') {
							wp_enqueue_style($asset['handle'], $asset['url']);
						} elseif ($asset['type'] == 'script') {
							wp_enqueue_script($asset['handle'], $asset['url'], $asset['deps'], '', $asset['footer']);
						}
					}
					if (!empty($asset['locals'])) {
						wp_localize_script( $asset['handle'], $asset['locals']['object'], $asset['locals']['vars'] );
					}
				}
			}
		});
	}
}
