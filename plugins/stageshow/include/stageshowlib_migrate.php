<?php
/*
Description: Code to trap passing NULL to deprecated functions

Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if (!class_exists('StageShowLibMigratePHPClass')) 
{
	class StageShowLibMigratePHPClass // Define class
	{
		static function Safe_htmlspecialchars($string, $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, $encoding = null, $double_encode = true)
		{
			if (is_null($string)) return '';				
			return htmlspecialchars($string, $flags, $encoding, $double_encode);
		}
		
		static function Safe_htmlspecialchars_decode($string, $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401)
		{
			if (is_null($string)) return '';				
			return htmlspecialchars_decode($string, $flags);
		}
		
		static function Safe_str_pad($string, $length, $pad_string = " ", $pad_type = STR_PAD_RIGHT)
		{
			if (is_null($string)) return '';				
			return str_pad($string, $length, $pad_string, $pad_type);
		}
		
		static function Safe_str_replace($search, $replace, $subject, &$count = null)
		{
			if (is_null($subject)) return '';				
			if (is_null($search)) return $subject;				
			if (is_null($replace)) $replace = '';				
			if (is_null($count))			
				return str_replace($search, $replace, $subject);
			else
				return str_replace($search, $replace, $subject, $count);
		}
	
		static function Safe_strcasecmp($string1, $string2)
		{
			if (is_null($string1)) $string1 = '';				
			if (is_null($string2)) $string2 = '';				
			return strcasecmp($string1, $string2);
		}

		static function Safe_stripos($haystack, $needle, $offset = 0)
		{
			if (is_null($haystack)) $haystack = '';
			if (is_null($needle)) $needle = '';
			return stripos($haystack, $needle, $offset);
		}

		static function Safe_stripslashes($string)
		{
			if (is_null($string)) return '';			
			return stripslashes($string);
		}

		static function Safe_strlen($string)
		{
			if (is_null($string)) return 0;				
			return strlen($string);
		}
		
		static function Safe_strncmp($string1, $string2, $length)
		{
			if (is_null($string1)) $string1 = '';				
			if (is_null($string2)) $string2 = '';				
			return strncmp($string1, $string2, $length);	
		}
		
		static function Safe_strpos($haystack, $needle, $offset = 0)
		{
			if (is_null($haystack)) $haystack = '';
			if (is_null($needle)) $needle = '';
			return strpos($haystack, $needle, $offset);
		}

		static function Safe_strripos($haystack, $needle, $offset = 0)
		{
			if (is_null($haystack)) $haystack = '';
			if (is_null($needle)) $needle = '';
			return strripos($haystack, $needle, $offset);
		}

		static function Safe_strrpos($haystack, $needle, $offset = 0)
		{
			if (is_null($haystack)) $haystack = '';
			if (is_null($needle)) $needle = '';
			return strrpos($haystack, $needle, $offset);
		}

		static function Safe_strstr($haystack, $needle, $before_needle = false)
		{
			if (is_null($haystack)) $haystack = '';
			if (is_null($needle)) $needle = '';
			return strstr($haystack, $needle, $before_needle);
		}

		static function Safe_strtolower($string)
		{
			if (is_null($string)) $string = '';		
			return strtolower($string);
		}

		static function Safe_strtotime($datetime, $baseTimestamp = null)
		{
			if (is_null($datetime)) return false;
			if (is_null($baseTimestamp)) $baseTimestamp = time();
			return strtotime($datetime, $baseTimestamp);
		}
		
		static function Safe_strtoupper($string)
		{
			if (is_null($string)) return '';			
			return strtoupper($string);
		}

		static function Safe_substr($string, $offset, $length = null)
		{
			if (is_null($string)) $string = '';
			// Bolt and braces code - Passing null length does not work on old versions of PHP
			if (is_null($length))
				return substr($string, $offset);
			else
				return substr($string, $offset, $length);
		}

		static function Safe_trim($string, $characters = " \n\r\t\v\x00")
		{
			if (is_null($string)) return '';			
			return trim($string, $characters);
		}

	
	}

}


