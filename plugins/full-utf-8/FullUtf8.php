<?php

class FullUtf8
{
	/**
	 * Callback used when escaping
	 * 
	 * @param string  $format   sprintf format string
	 * @param integer $unicode  unicode of the current utf8 char
	 * @param string  $utf8Char bytes of the current utf8 char
	 * 
	 * @return string
	 */
    public static function cb_write($format, $unicode, $utf8Char)
    {
        $result = $unicode < 0x10000 
            ? $utf8Char                   //pass through any utf8 char shorter than 4 bytes
            : sprintf($format, $unicode); //escape problems with the an alternative string
        return $result;
    }
    
    /**
     * Callback used when unescaping
     * 
     * @param string $all     total match
     * @param string $unicode matched unicode
     * 
     * @return string
     */
    public static function cb_read($all, $unicode)
    {
        return $unicode; //pattern matching does it all in this case
    }
    
    /**
     * Configuration of the Ando_Utf8 class
     * 
     * @var struct
     */
    protected static $ando_utf8_options = array(
        'extendedUseSurrogate' => false,
        'write'                => array(
            'callback'  => array('FullUtf8', 'cb_write'),
            'arguments' => array('(#%s#)'),
        ),
        'read'                 => array(
            'pattern'   => '/\(#(\d+)#\)/',
            'callback'  => array('FullUtf8', 'cb_read'),
            'arguments' => array(),
        ),
        'filters'              => array(
            'before-write' => array(
                'callback'  => 'preg_replace',
                'arguments' => array('/\(#(\d+#\))/', '(##\1'),
            ),
            'after-read'   => array(
                'callback'  => 'preg_replace',
                'arguments' => array('/\(##(\d+#\))/', '(#\1'),
            ),
        ),
    );
    
    /**
     * Simple wrapper around Ando_Utf8::escape, for simplifying configuration
     * 
     * @param string $content
     */
    public static function escape( &$content ) 
    {
        if (is_numeric($content) || ! is_string($content))
        {
            return;
        }
        try 
        {
            $content = Ando_Utf8::escape($content, self::$ando_utf8_options);
        }
        catch (Exception $e)
        {}
    }
    
    /**
     * Simple wrapper around Ando_Utf8::unescape, for simplifying configuration
     * 
     * @param string $content
     */
    public static function unescape( &$content ) 
    {
        if (is_numeric($content) || ! is_string($content))
        {
            return;
        }
        try 
        {
            $content = Ando_Utf8::unescape($content, self::$ando_utf8_options);
        }
        catch (Exception $e)
        {}
    }
    
    /**
     * Returns the source and destination filenames of the db dropin class
     * 
     * @return array
     */
    protected static function db_files()
    {
        $path = dirname(__FILE__);
        $dest = WP_CONTENT_DIR . '/db.php';
        $source = $path . '/db.php';
        return array($source, $dest);
    }
    
    /**
     * Callback used when the plugin is activted by the user
     * 
     * @return boolean
     */
    public static function on_activation()
    {
        if ('utf8' != strtolower(DB_CHARSET))
        {
            return FALSE;
        }
        list($source, $dest) = self::db_files();
        if (file_exists($dest))
        {
            rename($dest, $dest . date('---Y-m-d--H-i-s'));
        }
        copy($source, $dest);
        return TRUE;
    }
    
    /**
     * Callback used when the plugin is deactivted by the user
     * 
     * @return boolean
     */
    public static function on_deactivation()
    {
        list(, $dest) = self::db_files();
        rename($dest, $dest . date('---Y-m-d--H-i-s'));
        return TRUE;
    }
}
