<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_Html')):

/**
 * Html Class
 * @since 1.0.0
 */
class RTCORE_Html extends RTCORE_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        add_action('init', array($this, 'start'));
    }

    public function start()
    {
        // WPL is not activated
        if(!function_exists('_wpl_import')) return;

        // Sesame Theme
        $sesame = wp_get_theme('sesame');
        if($sesame->exists()) $this->sesame();

        // Listy Theme
        $listy = wp_get_theme('listy');
        if($listy->exists()) $this->listy();

        // IDXed Theme
        $idxed = wp_get_theme('idxed');
        if($idxed->exists()) $this->idxed();
    }

    public function sesame()
    {
        $this->copy('sesame');
    }

    public function listy()
    {
        $this->copy('listy');
    }

    public function idxed()
    {
        $this->copy('idxed');
    }

    public function copy($theme)
    {
        // Destination Path
        $destination = get_theme_root().'/'.$theme.'/wplhtml';

        // Force Flag
        $force = false;

        // Create Destination
        if(!wpl_folder::exists($destination))
        {
            wpl_folder::create($destination);
            $force = true;
        }

        // Last Time
        $last_copy_time = get_option($theme.'_copytime', time());

        // Only Check if 240 hours passed
        if(!$force or ($last_copy_time + (240 * HOUR_IN_SECONDS) < time())) return;

        // Source Path
        $source = RTCORE_ABSPATH . '/html/'.$theme;

        $contents = $this->get_contents($source, '');
        foreach($contents as $content)
        {
            $dest = $destination.$content;
            if(!wpl_file::exists($dest))
            {
                $src = $source.$content;
                wpl_file::copy($src, $dest);
            }
        }

        // Update Last Copy Time
        update_option($theme.'_copytime', time());
    }

    public function get_contents($dir, $p, &$results = array())
    {
        $files = scandir($dir);
        foreach($files as $key => $file)
        {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $file);

            if(!is_dir($path)) $results[] = $p.'/'.$file;
            elseif($file != '.' && $file != '..')
            {
                $this->get_contents($path, $p.'/'.$file, $results);
            }
        }

        return $results;
    }

}

endif;