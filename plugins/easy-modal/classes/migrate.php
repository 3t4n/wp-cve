<?php class EModal_Migrate_Pre_V1 {
	function __construct()
	{
		register_activation_hook(__FILE__, array(&$this, '_migrate'));

	}
	protected function _migrate_EM()
	{
		global $wp;
		$o_modal_list = emodal_get_option('EasyModal');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id)
		{
			$Modal = emodal_get_option('EasyModal_'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$Modal['name'] = $Modal['title'];
			$this->updateModalSettings('new',$Modal, false, true);
			//delete_option('EasyModal_'.$id);
		}
		//delete_option('eM_version');
		//delete_option('EasyModal');
	}
	protected function _migrate_EM_Lite()
	{
		global $wp;
		$o_modal_list = emodal_get_option('EasyModalLite_ModalList');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id => $title)
		{
			$Modal = emodal_get_option('EasyModalLite_Modal-'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$Modal['name'] = !empty($Modal['title']) ? $Modal['title'] : 'change_me';
			$this->updateModalSettings($id, $Modal, false, true);
			//delete_option('EasyModalLite_Modal-'.$id);
		}
		$Theme = emodal_get_option('EasyModalLite_Theme-1');
		if(!is_array($Theme))
		{
			$Theme = unserialize($Theme);
		}
		$this->updateThemeSettings(1,$Theme,false,true);
		//delete_option('EasyModalLite_Theme-1');
		$o_settings = emodal_get_option('EasyModalLite_Settings');
		if(!is_array($o_settings))
		{
			$o_settings = unserialize($o_settings);
			if(!is_array($o_settings))
			{
				$o_settings = array();
			}
		}
		unset($o_settings['license']);
		$this->updateSettings($o_settings,true);
		//delete_option('EasyModalLite_Settings');
		//delete_option('EasyModalLite_Version');
		//delete_option('EasyModalLite_ModalList');
		//delete_option('EasyModalLite_ThemeList');
	}
	protected function _migrate_EM_Pro()
	{
		global $wp;
		$o_theme_list = emodal_get_option('EasyModalPro_ModalList');
		if(!is_array($o_theme_list))
		{
			$o_theme_list = unserialize($o_theme_list);
		}
		foreach($o_theme_list as $id => $name)
		{
			$Theme = emodal_get_option('EasyModalPro_Theme-'.$id);
			if(!is_array($Theme))
			{
				$Theme = unserialize($Theme);
			}
			$theme = $this->updateThemeSettings('new',$Theme,false,true);
			//delete_option('EasyModalPro_Theme-'.$id);
			$themes[$id] = $theme['theme_id'];
		}
		//delete_option('EasyModalPro_ThemeList');
		$themes = $this->getThemeList();
		$o_modal_list = emodal_get_option('EasyModalPro_ModalList');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id => $title)
		{
			$Modal = emodal_get_option('EasyModalPro_Modal-'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$Modal['theme'] = isset($themes[$id]) ? $theme[$id] : 1;
			$Modal['name'] = !empty($Modal['title']) ? $Modal['title'] : 'change_me';
			$this->updateModalSettings($id, $Modal, false, true);
			//delete_option('EasyModalPro_Modal-'.$id);
		}
		//delete_option('EasyModalPro_ModalList');
		$o_settings = emodal_get_option('EasyModalPro_Settings');
		if(!is_array($o_settings))
		{
			$o_settings = unserialize($o_settings);
			if(!is_array($o_settings))
			{
				$o_settings = array();
			}
		}
		$license = emodal_get_option('EasyModalPro_License');
		$this->process_license($license);
		unset($o_settings['license']);
		$this->updateSettings($o_settings,true);
		//delete_option('EasyModalPro_License');
		//delete_option('EasyModalPro_Settings');
		//delete_option('EasyModalPro_Version');
	}
}
class EModal_Migrate_Pre_V2 {
	public function __construct()
	{
		if(emodal_get_option('EasyModal_Version'))
		{
			$themes = self::get_themes();
			$theme_check = array();
			foreach($themes as $theme)
			{
				$new_theme = new EModal_Model_Theme;
				$new_theme->set_fields(array(
					'id' => intval($theme['id']),
					'name' => $theme['name'],
					'is_system' => intval($theme['id']) == 1 ? 1 : 0,
					'meta' => array(
						'overlay' => array(
							'background' => array(
								'color' => $theme['overlayColor'],
								'opacity' => $theme['overlayOpacity'],
							)
						),
						'container' => array(
							'padding' => $theme['containerPadding'],
							'background' => array(
								'color' => $theme['containerBgColor'],
							),
							'border' => array(
								'style' => $theme['containerBorderStyle'],
								'color' => $theme['containerBorderColor'],
								'width' => $theme['containerBorderWidth'],
								'radius' => $theme['containerBorderRadius'],
							),
						),
						'title' => array(
							'font' => array(
								'color' => $theme['contentTitleFontColor'],
								'size' => $theme['contentTitleFontSize'],
								'family' => $theme['contentTitleFontFamily'],
							),
						),
						'content' => array(
							'font' => array(
								'color' => $theme['contentFontColor'],
							),
						),
						'close' => array(
							'padding' => ($theme['closeSize'] - $theme['closeFontSize']) / 2,
							'text' => $theme['closeText'],
							'location' => $theme['closePosition'],
							'background' => array(
								'color' => $theme['closeBgColor'],
							),
							'border' => array(
								'radius' => $theme['closeBorderRadius'],
							),
							'font' => array(
								'color' => $theme['closeFontColor'],
								'size' => $theme['closeFontSize'],
							),

						),
					),
				));
				$new_theme->save();
				$theme_check[] = $new_theme->id;
			}

			$modals = self::get_modals();
			foreach($modals as $modal)
			{
				$new_modal = new EModal_Model_Modal;
				$new_modal->set_fields(array(
					'id' => is_int(intval($modal['id'])) ? intval($modal['id']) : NULL,
					'theme_id' => in_array(intval($modal['theme']), $theme_check) ? intval($modal['theme']) : 1,
					'name' => $modal['name'],
					'title' => $modal['title'],
					'content' => $modal['content'],
					'is_sitewide' => $modal['sitewide'] ? 1 : 0,
					'content' => $modal['content'],
					'is_system' => in_array($modal['id'], array('Login','Register','Forgot')) ? 1 : 0,
					'meta' => array(
						'display' => array(
							'size' => $modal['size'] == '' ? 'normal' : $modal['size'],
							'custom_width' => $modal['userWidth'],
							'custom_width_unit' => $modal['userWidthUnit'],
							'custom_height' => $modal['userHeight'],
							'custom_height_unit' => $modal['userHeightUnit'],
							'animation' => array(
								'type' => $modal['animation'],
								'speed' => $modal['duration'],
								'origin' => $modal['direction'],
							),
						),
						'close' => array(
							'overlay_click' => $modal['overlayClose'] ? 1 : 0,
							'esc_press' => $modal['overlayEscClose'] ? 1 : 0,
							'disabled' => !empty($modal['closeDisabled']) && $modal['closeDisabled'] ? 1 : 0,
						)
					),
				));
				$new_modal->save();
				if($modal['id'] == 'Login')
				{
					$login_modal = $new_modal->id;
				}
				elseif($modal['id'] == 'Register')
				{
					$register_modal = $new_modal->id;
				}
				elseif($modal['id'] == 'Forgot')
				{
					$forgot_modal = $new_modal->id;
				}
			}

			$license = self::get_license();
			if(!empty($license))
			{
				$EModal_License = new EModal_License;
				$EModal_License->check_license($license, true);
				EModal_Admin::check_updates();
			}

			$settings = self::get_settings();
			if(array_key_exists('autoOpen_id', $settings))
			{
				$autoopen = array(
					'modal_id' => intval( $settings['autoOpen_id'] ),
					'type' => 'page-load',
					'pageload' => array(
						'delay' => intval($settings['autoOpen_delay'])
					),
					'cookie' => array(
						'time' => is_float( $settings['autoOpen_timer'] ) || is_int( $settings['autoOpen_timer'] ) ? $settings['autoOpen_timer'] .' days' : $settings['autoOpen_timer']
					)
				);
				emodal_update_option('easy-modal-pro_autoopen', $autoopen);

				$autoexit = array(
					'modal_id' => intval( $settings['autoExit_id'] ),
					'cookie' => array(
						'time' => is_float( $settings['autoExit_timer'] ) || is_int( $settings['autoExit_timer'] ) ? $settings['autoExit_timer'] .' days' : $settings['autoExit_timer']
					)
				);
				emodal_update_option('easy-modal-pro-exit-modals_autoexit', $autoexit);
			}

			if($settings['login_modal_enabled'])
			{					
				if(empty($login_modal))
				{
					$new_login_modal = new EModal_Model_Modal;
					$new_login_modal->set_fields(array(
						'name' => 'Login Modal',
						'meta' => array(
							'size' => 'small'
						)
					));
					$new_login_modal->save();
					$login_modal = $new_login_modal->id;
				}
				if(empty($register_modal))
				{
					$new_registration_modal = new EModal_Model_Modal;
					$new_registration_modal->set_fields(array(
						'name' => 'Registration Modal',
						'meta' => array(
							'size' => 'small'
						)
					));
					$new_registration_modal->save();
					$register_modal = $new_registration_modal->id;
				}
				if(empty($forgot_modal))
				{
					$new_forgot_modal = new EModal_Model_Modal;
					$new_forgot_modal->set_fields(array(
						'name' => 'Forgot Password Modal',
						'meta' => array(
							'size' => 'small'
						)
					));
					$new_forgot_modal->save();
					$forgot_modal = $new_forgot_modal->id;
				}


				$loginmodal = array(
					'enabled' => $settings['login_modal_enabled'] ? 1 : 0,
					'force_login' => $settings['force_user_login'] ? 1 : 0,
					'login' => array(
						'modal_id' => $login_modal,
					),
					'registration' => array(
						'modal_id' => $register_modal,
						'enable_password' => $settings['registration_modal']['enable_password'] ? 1 : 0,
						'autologin' => $settings['registration_modal']['autologin'] ? 1 : 0,
					),
					'forgot' => array(
						'modal_id' => $forgot_modal,
					),
				);
				emodal_update_option('easy-modal-pro-login-modals_loginmodal', $loginmodal);
			}

		}
		global $wpdb;
		$wpdb->query("UPDATE $wpdb->postmeta SET meta_key = 'easy-modal_post_modals' WHERE meta_key = 'easy_modal_post_modals'");
		$post_meta = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE 'easy_modal_post_%' ORDER BY post_id ASC");
		$new_post_meta = array();
		$delete_post_meta = array();
		foreach($post_meta as $meta)
		{
			if(empty($new_post_meta[$meta->post_id]))
			{
				$new_post_meta[$meta->post_id] = array(
					'autoopen' => array(
						'modal_id' => NULL,
						'type' => 'page-load',
						'pageload' => array(
							'delay' => NULL
						),
						'cookie' => array(
							'time' => NULL,
							'path' => ''
						),
						'scroll' => array(
							'distance' => NULL
						)
					),
					'autoexit' => array(
						'modal_id' => NULL,
						'cookie' => array(
							'time' => NULL,
							'path' => ''
						)
					),
					'loginmodal' => array(
						'force_login' => 0
					),
				);
			}
			switch($meta->meta_key)
			{
				case "easy_modal_post_autoExit_id": $new_post_meta[$meta->post_id]['autoexit']['modal_id'] = intval($meta->meta_value);
					break;
				case "easy_modal_post_autoExit_timer": $new_post_meta[$meta->post_id]['autoexit']['cookie']['time'] = is_float( $meta->meta_value ) || is_int( $meta->meta_value ) ? $meta->meta_value .' days' : $meta->meta_value;
					break;
				case "easy_modal_post_autoOpen_id": $new_post_meta[$meta->post_id]['autoopen']['modal_id'] = intval($meta->meta_value);
					break;
				case "easy_modal_post_autoOpen_timer": $new_post_meta[$meta->post_id]['autoopen']['cookie']['time'] = is_float( $meta->meta_value ) || is_int( $meta->meta_value ) ? $meta->meta_value .' days' : $meta->meta_value;
					break;
				case "easy_modal_post_autoOpen_delay": $new_post_meta[$meta->post_id]['autoopen']['pageload']['delay'] = intval($meta->meta_value);
					break;
				case "easy_modal_post_force_user_login": $new_post_meta[$meta->post_id]['autoopen']['modal_id'] = $meta->meta_value == 'true' ? 1 : 0;
					break;
			}
			if($meta->meta_key != 'easy-modal_post_modals')
			{
				$delete_post_meta[] = $meta->meta_id;
			}
		}
		foreach($new_post_meta as $post_id => $meta)
		{
			update_post_meta($post_id, 'easy-modal-pro_autoopen', $meta['autoopen']);
			update_post_meta($post_id, 'easy-modal-pro-exit-modals_autoexit', $meta['autoexit']);
			update_post_meta($post_id, 'easy-modal-pro-login-modals_loginmodal', $meta['loginmodal']);
		}
		emodal_update_option(EMCORE_SLUG.'_migration_approval', true);
	}


	public static function defaultSettings()
	{
		return array(
			'autoOpen_id' => NULL,
			'autoOpen_delay' => 500,
			'autoOpen_timer' => "",
			'autoExit_id' => NULL,
			'autoExit_timer' => "",
			'login_modal_enabled' => false,
			'force_user_login' => false,
			'registration_modal' => array(
				'enable_password' => false,
				'autologin' => false
			)
		);
	}
	public static function defaultThemeSettings()
	{
		return array(
			'name' => 'change_me',
			
			'overlayColor' => '#220E10',
			'overlayOpacity' => '85',
			
			'containerBgColor' => '#F7F5E7',
			'containerPadding' => '10',
			'containerBorderColor' => '#F0532B',
			'containerBorderStyle' => 'solid',
			'containerBorderWidth' => '1',
			'containerBorderRadius' => '8',
			'closeLocation' => 'inside',
			'closeBgColor' => '#000000',
			'closeFontColor' => '#F0532B',
			'closeFontSize' => '15',
			'closeBorderRadius' => '10',
			'closeSize' => '20',
			'closeText' => '&#215;',
			'closePosition' => 'topright',
			
			'contentTitleFontColor' => '#F0532B',
			'contentTitleFontSize' => '32',
			'contentTitleFontFamily' => 'Tahoma',
			'contentFontColor' => '#F0532B'
		);
	}
	public static function defaultModalSettings()
	{
		return array(
			'id' => '',
			'name'	=> 'change_me',
			'sitewide' => false,
			'title' => '',
			'content' => '',
			
			'theme' => 1,
			
			'size' => 'normal',
			'userHeight' => 0,
			'userHeightUnit' => 0,
			'userWidth' => 0,
			'userWidthUnit' => 0,
			
			'animation' => 'fade',
			'direction' => 'bottom',
			'duration' => 350,
			'overlayClose' => false,
			'overlayEscClose' => false,
			'closeDisabled' => false
		);
	}
	public static function get_themes()
	{
		$themes = array();
		$theme_list = emodal_get_option('EasyModal_ThemeList', array());
		foreach($theme_list as $theme_id => $name)
		{
			$theme = self::get_theme($theme_id);
			if($theme)
			{
				$themes[] = $theme;
			}
		}
		return $themes;
	}
	public static function get_theme($theme_id)
	{
		if($theme = emodal_get_option('EasyModal_Theme-'.$theme_id))
		{
			return self::merge_existing(self::defaultThemeSettings(), $theme);
		}
		return false;
	}



	public static function get_modals()
	{
		$modals = array();
		$modal_list = emodal_get_option('EasyModal_ModalList', array());
		foreach($modal_list as $modal_id => $name)
		{
			$modal = self::get_modal($modal_id);
			if($modal)
			{
				$modals[] = $modal;
			}
		}
		if(self::get_settings('login_modal_enabled'))
		{
			$login = self::get_modal('Login');
			$modals[] = $login;

			$register = self::get_modal('Register');
			$modals[] = $register;

			$forgot = self::get_modal('Forgot');
			$modals[] = $forgot;
		}
		return $modals;
	}
	public static function get_modal($modal_id)
	{
		if($modal = emodal_get_option('EasyModal_Modal-'.$modal_id))
		{
			return self::merge_existing(self::defaultModalSettings(), $modal);
		}
		return false;
	}
	public static function get_settings($key = NULL)
	{
		$settings = emodal_get_option('EasyModal_Settings');
		return $key ? !empty($settings[$key]) ? $settings[$key] : false : self::merge_existing(self::defaultSettings(), $settings);
	}
	public static function get_license()
	{
		return emodal_get_option('EasyModal_License');
	}

	public static function delete_all()
	{
		foreach(self::get_themes() as $theme)
		{
			delete_option('EasyModal_Theme-'.$theme['id']);
		}
		delete_option('EasyModal_ThemeList');

		foreach(self::get_modals() as $modal)
		{
			delete_option('EasyModal_Modal-'.$modal['id']);
		}
		delete_option('EasyModal_ModalList');

		delete_option('EasyModal_Settings');

		delete_option('EasyModal_License');
		delete_option('EasyModal_License_Status');
		delete_option('EasyModal_License_LastChecked');
		delete_option('EasyModal_Version');

		emodal_delete_option(EMCORE_SLUG.'_migration_approval');
		emodal_delete_option(EMCORE_SLUG.'_migration_approved');
	}

	public static function merge_existing($array1, $array2)
	{
		if(!is_array($array1) || !is_array($array2))
			return false;	
	
		foreach($array2 as $key => $val)
		{
			$array1[$key] = $val;
		}
		return $array1;
	}
}