<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\FB\Actions;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class Sounds extends Actions
{
    public function __construct()
    {
        add_action('firebox/box/before_render', [$this, 'onFireBoxBeforeRender']);
    }

    /**
     * The BeforeRender event fires before the box's layout is ready.
     *
     * @param   object  $box  The box's settings object
     *
     * @return  void
     */
    public function onFireBoxBeforeRender($box)
    {
        if (!isset($box->params))
        {
            return;
        }

        if (!$opening_sound = $box->params->get('opening_sound'))
        {
            return;
        }

        if (!isset($opening_sound->source))
        {
            return;
        }

        $source = $opening_sound->source;

        if ($source == 'none')
        {
            return;
        }

        $this->actions[] = [
            'box' => $box->ID,
            'when' => 'open',
            'wrap_result' => false,
            'action' => $this->get_action_script($box)
        ];
    }

    /**
     * Returns the action script
     * 
     * @param   object  $box
     * 
     * @return  string
     */
    protected function get_action_script($box)
    {
        $file = $this->get_file($box);

        return 'let audio = new Audio(\'' . $file . '\'); audio.pause(); audio.currentTime = 0; me.on("open", function(evt) { audio.play(); });';
    }

    /**
     * Returns the sound file
     * 
     * @param   object  $box
     * 
     * @return  string
     */
    private function get_file($box)
    {
        $opening_sound = $box->params->get('opening_sound');
        $source = $opening_sound->source;

        // local audio file
        $file = FBOX_MEDIA_ADMIN_URL . 'sounds/' . $source . '.mp3';

        // audio comes from a custom file
        if ($source == 'custom_file' && isset($opening_sound->file) && !empty($opening_sound->file))
        {
            $file = $opening_sound->file;
        }
        // audio comes from a custom URL
        else if ($source == 'custom_url' && isset($opening_sound->url) && !empty($opening_sound->url))
        {
            $file = $opening_sound->url;
        }

        return $file;
    }

    /**
     * Get all available sounds.
     * 
     * @return  array
     */
    public static function get()
    {
        $files = \array_diff(\scandir(FBOX_PLUGIN_DIR . 'media/admin/sounds'), ['.', '..', 'index.php']);
        
        foreach ($files as $sound)
        {
            $sound_clean = pathinfo($sound, PATHINFO_FILENAME);
            $sound_label = ucwords(str_replace('-', ' ', $sound_clean));
            $sounds[$sound_clean] = $sound_label;
        }

        return $sounds;
    }
}