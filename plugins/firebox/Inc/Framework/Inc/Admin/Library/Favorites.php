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

namespace FPFramework\Admin\Library;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

trait Favorites
{
	public function favorites_init()
	{
		// Favorites AJAX
		add_action('wp_ajax_fpf_library_favorites_toggle', [$this, 'fpf_library_favorites_toggle']);
	}

	/**
	 * Handles AJAX Library favorite toggle
	 * 
	 * @return  string
	 */
	public function fpf_library_favorites_toggle()
	{
		if (!current_user_can('manage_options'))
		{
			return false;
		}
		
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
		
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
			return false;
		}
		
		$template_id = isset($_POST['template_id']) ? sanitize_text_field($_POST['template_id']) : '';

		if (empty($template_id))
		{
			return false;
		}
		
		$this->addOrRemoveFavorite($template_id);
		
		echo wp_json_encode($this->getFavorites());
		wp_die();
	}
	
    /**
     * Add or remove favorites
     * 
     * @param   int  $template_id
     * 
     * @return  void
     */
    public function addOrRemoveFavorite($template_id)
    {
        $favorites = $this->getFavorites();

        if (array_key_exists($template_id, $favorites))
        {  
            $this->removeFromFavorites($template_id);
            return;
        }
        
        $this->addToFavorites($template_id);
    }

    /**
     * Add to favorites
     * 
     * @param   int  $template_id
     * 
     * @return  void
     */
    public function addToFavorites($template_id)
    {
        $favorites = $this->getFavorites();

        if (array_key_exists($template_id, $favorites))
        {  
            return;
        }

        $favorites[$template_id] = true;

        $this->saveFavorites($favorites);
    }

    /**
     * Save favorites to file
     * 
     * @param   string  $content
     * 
     * @return  void
     */
    public function saveFavorites($content)
    {
        $file = $this->getTemplatesPath() . '/favorites.json';

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
        return file_put_contents($file, wp_json_encode($content));
    }

    /**
     * Remove from favorites
     * 
     * @param   int  $template_id
     * 
     * @return  void
     */
    public function removeFromFavorites($template_id)
    {
        $favorites = $this->getFavorites();
        unset($favorites[$template_id]);
        $this->saveFavorites($favorites);
    }

    /**
     * Get favorites
     *  
     * @return  array
     */
    public function getFavorites()
    {
        $file = $this->getTemplatesPath() . '/favorites.json';

        if (!file_exists($file))
        {
            return [];
        }
        
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        return (array) json_decode(file_get_contents($file), true);
    }
}