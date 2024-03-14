<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');


class styles
{
	protected static $style_classes;
	//protected static $styles;

	public static function registerStyle($classname, $name)
	{
		static::$style_classes[$name] =  $classname;
	}


	public static function getStyles()
	{

		return self::$style_classes;
	}

	public static function getStyle($name)
	{
	
		if (isset(self::$style_classes[$name]))
		{ return new self::$style_classes[$name]; }

/*
		foreach (self::$style_classes as $order => $styles)
		{
			foreach($styles as $style_class)
			{
				if ($style_class->name == $name)
					return $style_class;
			}
		} */
		return false;
	}

}
