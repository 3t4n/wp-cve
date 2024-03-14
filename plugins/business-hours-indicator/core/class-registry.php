<?php

namespace MABEL_BHI_LITE\Core
{
	class Registry
	{

		private static $loader;

		public static function get_loader()
		{
			if(self::$loader === null)
			{
				self::$loader = new Loader();
			}

			return self::$loader;
		}

	}
}