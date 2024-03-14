<?php

namespace SiteSEO\Helpers\Metas;

if ( ! defined('ABSPATH')) {
	exit;
}

abstract class RedirectionSettings {
	/**
	 * @since 5.0.0
	 *
	 * @param int|null $id
	 *
	 * @return array[]
	 *
	 *	key: string post meta
	 *	use_default: default value need to use
	 *	default: default value
	 *	label: string label
	 *	placeholder
	 */
	public static function getMetaKeys($id = null) {
		$defaultStatus = siteseo_get_service('RedirectionMeta')->getPostMetaStatus($id);
		if($defaultStatus === null || empty($defaultStatus)){
			$defaultStatus = 'both';
		}

		$defaultType = siteseo_get_service('RedirectionMeta')->getPostMetaType($id);
		if($defaultType === null || empty($defaultType)){
			$defaultType = 301;
		}

		$data = apply_filters('siteseo_api_meta_redirection_settings', [
			[
				'key'		 => '_siteseo_redirections_enabled',
				'type'		=> 'checkbox',
				'placeholder' => '',
				'use_default' => '',
				'default'	 => '',
				'label'	   => __('Enabled redirection?', 'siteseo'),
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_redirections_logged_status',
				'type'		=> 'select',
				'placeholder' => '',
				'use_default' => true,
				'default'	 => $defaultStatus,
				'label'	   => __('Select a login status:', 'siteseo'),
				'options'	 => [
					['value' => 'both', 'label' =>  __('All', 'siteseo')],
					['value' => 'only_logged_in', 'label' =>  __('Only Logged In', 'siteseo')],
					['value' => 'only_not_logged_in', 'label' =>  __('Only Not Logged In', 'siteseo')],
				],
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_redirections_type',
				'type'		=> 'select',
				'placeholder' => '',
				'use_default' => true,
				'default'	 => $defaultType,
				'label'	   => __('Select a redirection type:', 'siteseo'),
				'options'	 => [
					['value' => 301, 'label' =>  __('301 Moved Permanently', 'siteseo')],
					['value' => 302, 'label' =>  __('302 Found / Moved Temporarily', 'siteseo')],
					['value' => 307, 'label' =>  __('307 Moved Temporarily', 'siteseo')]
				],
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_redirections_value',
				'type'		=> 'input',
				'placeholder' => __('Enter your new URL in absolute (eg: https://www.example.com/)', 'siteseo'),
				'label'	   => __('URL redirection', 'siteseo'),
				'use_default' => '',
				'default'	 => '',
				'visible'	 => true,
			],
		], $id);

		return $data;
	}
}
