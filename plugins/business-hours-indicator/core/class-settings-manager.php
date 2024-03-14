<?php

namespace MABEL_BHI_LITE\Core
{

	class Settings_Manager
	{

		private static $settings = [];

		private static $defaults;

		public static function init( array $defaults = [] )
		{
			self::$defaults = $defaults;
			self::$settings =  (array) get_option(MABEL_BHI_LITE_SETTINGS);
		}

		public static function get_setting($key)
		{
			if(!is_array(self::$settings)) return null;

			$setting = isset( self::$settings[$key] ) ? self::$settings[$key] : null;

			if($setting != null)
				return $setting;

			if(!is_array(self::$defaults))
				return null;

			if(isset(self::$defaults[$key]))
				return self::$defaults[$key];

			return null;
		}

		public static function get_translated_setting($key)
		{
			if(!is_array(self::$settings)) return null;

			$setting = isset( self::$settings[$key] ) ? self::$settings[$key] : null;
			if($setting != null)
				return $setting;

			if(!is_array(self::$defaults))
				return null;

			if(isset(self::$defaults[$key]))
				return __(self::$defaults[$key], 'business-hours-indicator');

			return null;
		}

	}

}