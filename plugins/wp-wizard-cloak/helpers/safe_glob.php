<?php
if ( ! function_exists('safe_glob')):
/**#@+
 * Extra GLOB constant for safe_glob()
 */
define('GLOB_NODIR',256);
define('GLOB_PATH',512);
define('GLOB_NODOTS',1024);
define('GLOB_RECURSE',2048);
/**#@-*/

/**
 * A safe empowered glob().
 *
 * Function glob() is prohibited on some server (probably in safe mode)
 * (Message "Warning: glob() has been disabled for security reasons in
 * (script) on line (line)") for security reasons as stated on:
 * http://seclists.org/fulldisclosure/2005/Sep/0001.html
 *
 * safe_glob() intends to replace glob() using readdir() & fnmatch() instead.
 * Supported flags: GLOB_MARK, GLOB_NOSORT, GLOB_ONLYDIR
 * Additional flags: GLOB_NODIR, GLOB_PATH, GLOB_NODOTS, GLOB_RECURSE
 * (not original glob() flags)
 * @author BigueNique AT yahoo DOT ca
 * @updates
 * - 080324 Added support for additional flags: GLOB_NODIR, GLOB_PATH,
 *   GLOB_NODOTS, GLOB_RECURSE
 * - 100607 Recurse is_dir check fixed by Pavel Kulbakin <p.kulbakin@gmail.com>
 */
function safe_glob($pattern, $flags=0) {
    $split=explode('/',str_replace('\\','/',$pattern));
    $mask=array_pop($split);
    $path=implode('/',$split);
    if (($dir=opendir($path))!==false) {
        $glob=array();
        while(($file=readdir($dir))!==false) {
            // Recurse subdirectories (GLOB_RECURSE)
            if( ($flags&GLOB_RECURSE) && is_dir($path.'/'.$file) && (!in_array($file,array('.','..'))) ) {
                $glob = array_merge($glob, array_prepend(safe_glob($path.'/'.$file.'/'.$mask, $flags),
                    ($flags&GLOB_PATH?'':$file.'/')));
            }
            // Match file mask
            if (fnmatch($mask,$file)) {
                if ( ( (!($flags&GLOB_ONLYDIR)) || is_dir("$path/$file") )
                  && ( (!($flags&GLOB_NODIR)) || (!is_dir($path.'/'.$file)) )
                  && ( (!($flags&GLOB_NODOTS)) || (!in_array($file,array('.','..'))) ) )
                    $glob[] = ($flags&GLOB_PATH?$path.'/':'') . $file . ($flags&GLOB_MARK?'/':'');
            }
        }
        closedir($dir);
        if (!($flags&GLOB_NOSORT)) sort($glob);
        return $glob;
    } else {
        return false;
    }   
}
endif;

if ( ! function_exists('array_prepend')):
/**
 * Prepends $string to each element of $array
 * If $deep is true, will indeed also apply to sub-arrays
 * @author BigueNique AT yahoo DOT ca
 * @since 080324
 */
function array_prepend($array, $string, $deep=false) {
    if(empty($array)||empty($string)) return $array;
    foreach($array as $key => $element)
        if(is_array($element))
            if($deep)
                $array[$key] = array_prepend($element,$string,$deep);
            else
                trigger_error('array_prepend: array element',E_USER_WARNING);
        else
            $array[$key] = $string.$element;
    return $array;
   
}
endif;

if ( ! function_exists('fnmatch')):

define('FNM_PATHNAME', 1);
define('FNM_NOESCAPE', 2);
define('FNM_PERIOD', 4);
define('FNM_CASEFOLD', 16);

/**
 * Match filename against a pattern
 * 
 * @param string $pattern The shell wildcard pattern
 * @param string $string The tested string
 * @param int[optional] $flags
 * @return bool
 */
function fnmatch($pattern, $string, $flags = 0) {
	$modifiers = null;
	$transforms = array(
		'\*'    => '.*',
		'\?'    => '.',
		'\[\!'  => '[^',
		'\['    => '[',
		'\]'    => ']',
		'\.'    => '\.',
		'\\'    => '\\\\'
	);
             
	// Forward slash in string must be in pattern:
	if ($flags & FNM_PATHNAME) {
		$transforms['\*'] = '[^/]*';
	}
	 
	// Back slash should not be escaped:
	if ($flags & FNM_NOESCAPE) {
		unset($transforms['\\']);
	}
	 
	// Perform case insensitive match:
	if ($flags & FNM_CASEFOLD) {
		$modifiers .= 'i';
	}
	 
	// Period at start must be the same as pattern:
	if ($flags & FNM_PERIOD) {
		if (strpos($string, '.') === 0 && strpos($pattern, '.') !== 0) return false;
	}
	 
	$pattern = '#^'
		. strtr(preg_quote($pattern, '#'), $transforms)
		. '$#'
		. $modifiers;
	 
	return (boolean)preg_match($pattern, $string);
}
endif;