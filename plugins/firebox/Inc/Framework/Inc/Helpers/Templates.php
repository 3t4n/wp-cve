<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Templates
{
	public static function getTemplates($plugin = null)
	{
		$path = self::getPath($plugin);

		// If it does not require an update, return its contents
		if (file_exists($path) && !self::requireUpdate($path))
		{
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			return json_decode(file_get_contents($path));
        }
		
		// Retrieve and return remote templates
		return self::getRemoteTemplatesAndStore($plugin);

	}

	public static function getPath($plugin = null)
	{
		return \FPFramework\Helpers\WPHelper::getPluginUploadsDirectory($plugin, 'templates') . '/templates.json';
	}
    
    /**
     * Gets the remote templates and stores them locally
	 * 
	 * @param   string  $plugin
     * 
     * @return  array
     */
    public static function getRemoteTemplatesAndStore($plugin = null)
    {
        $templates = self::getRemoteTemplates($plugin);

        if (!$templates)
        {
            return new \WP_Error('cannot_retrieve_templates', fpframework()->_('FPF_TEMPLATES_CANNOT_BE_RETRIEVED'));
        }

        // return errors
        if (isset($templates->errors) || isset($templates->code) || \is_wp_error($templates))
        {
            return $templates;
        }
        
		$path = self::getPath($plugin);

		// Create the uploads directory if it does not exist
		if (!is_dir(dirname($path)))
		{
			Directory::createDirs(dirname($path));
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
        file_put_contents($path, wp_json_encode($templates));

        return $templates;
    }

    /**
     * Returns the remote templates
	 * 
	 * @param   string  $plugin
     * 
     * @return  array
     */
	public static function getRemoteTemplates($plugin = null)
	{
        $license = get_option($plugin . '_license_key');
		$site_url = preg_replace('(^https?://)', '', get_site_url());
		$site_url = preg_replace('(^www.)', '', $site_url);
        $site_url = rtrim($site_url, '/') . '/';
        
        // get remote templates
		$templates_url = str_replace('{{PLUGIN}}', $plugin, FPF_TEMPLATES_GET_URL);
		$templates_url = str_replace('{{LICENSE_KEY}}', $license, $templates_url);
		// $templates_url = str_replace('{{PLUGIN_VERSION}}', $plugin_version, $templates_url);
		$templates_url = str_replace('{{SITE_URL}}', $site_url, $templates_url);

        $response = wp_remote_get($templates_url);

        if (!is_array($response) || \is_wp_error($response))
        {
			// If the request timed out, then let plugin show a more helpful error message
			if (isset($response->errors) && array_key_exists('http_request_failed', $response->errors))
			{
				return false;
			}
			
			// Otherwise show error message from server
            return $response;
        }
        else
        {
            $body = json_decode($response['body']);

            // an error has occurred
            if (isset($body->error))
            {
                return $body->error;
            }
            
            return $body;
        }
    }

	/**
	 * Checks whether the local templates list is older than 15 days.
	 * 
	 * @param   string  $path
	 * 
	 * @return  bool
	 */
	public static function requireUpdate($path = null)
	{
		if (!$path)
		{
			return;
		}

		if (!file_exists($path))
		{
			return;
		}

		$days_old = 15;

		/**
		 * If its older than 15 days, then request remote list
		 */
		// Get the modification time of the templates file
		$modTime = @filemtime($path);

		// Current time
		$now = time();

		// Minimum time difference
		$threshold = $days_old * 24 * 3600;

		// Do we need an update?
		return ($now - $modTime) >= $threshold;
	}
}