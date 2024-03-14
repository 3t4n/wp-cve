<?php

namespace CatFolders\Core;

use CatFolders\Models\FolderModel;
use CatFolders\Models\OptionModel;
class Base {
	public $settings     = array();
	public $userSettings = array();

	public const CAT_FOLDERS_TABLE       = 'catfolders';
	public const CAT_FOLDERS_TABLE_POSTS = 'catfolders_posts';

	public function __construct() {
	}

	public function initialize() {

		$this->settings = OptionModel::get_option();

		$userSettings       = get_user_meta(
			get_current_user_id(),
			'catf_user_settings',
			true
		);
		$this->userSettings = wp_parse_args(
			$userSettings,
			array(
				'sortFile'      => '',
				'sortFolder'    => '',
				'startupFolder' => '',
				'startupMode'   => '',
			)
		);

		$this->userSettings['startupMode']   = $this->userSettings['startupFolder'];
		$this->userSettings['startupFolder'] = $this->getStartupFolder( $this->userSettings['startupFolder'] );

		return true;
	}

	/**
	 * Get global settings
	 */
	public function getSettings() {
		if ( count( $this->settings ) == 0 ) {
			$this->initialize();
		}
		return $this->settings;
	}

	/**
	 * Update global settings
	 */
	public function updateSettings( $values = array() ) {
		if ( count( $this->settings ) == 0 ) {
			$this->initialize();
		}
		OptionModel::update_option( $values );
		$this->settings = OptionModel::get_option();
	}

	/**
	 * Get User settings
	 */
	public function getUserSettings() {
		if ( count( $this->userSettings ) == 0 ) {
			$this->initialize();
		}
		return $this->userSettings;
	}

	/**
	 * Update User settings
	 */
	public function updateUserSettings( $values = array() ) {
		if ( count( $this->userSettings ) == 0 ) {
			$this->initialize();
		}
		foreach ( $values as $key => $val ) {
			$this->userSettings[ $key ] = $val;
		}

		update_user_meta( get_current_user_id(), 'catf_user_settings', $this->userSettings );
	}

	private function getStartupFolder( $defaultFolder ) {
		if ( '' === $defaultFolder ) {
			return -1;
		}
		if ( 'prev' === $defaultFolder ) {
			if ( ! isset( $_COOKIE['catf_selected_keys'] ) ) {
				return -1;
			}

			$currentFolder = sanitize_key( $_COOKIE['catf_selected_keys'] );

			if ( '' !== $currentFolder ) {
				if ( intval( $currentFolder ) < 1 ) {
					return intval( $currentFolder );
				}
				if ( FolderModel::isFolderExist( $currentFolder ) ) {
					return intval( $currentFolder );
				};
				return -1;
			} else {
				return -1;
			}
		}

		if ( '' !== $defaultFolder ) {
			if ( intval( $defaultFolder ) < 1 ) {
				return intval( $defaultFolder );
			}
			if ( FolderModel::isFolderExist( $defaultFolder ) ) {
				return intval( $defaultFolder );
			}
		}

		return -1;
	}
}
