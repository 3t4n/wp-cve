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

trait ActionsTrait
{
    /**
     * Execute code block with a delay
     *
     * @param   string  $function
     *
     * @return  string
     */
    protected function delayFunction($delay = 0, $function = '')
    {
        if ($delay == 0)
        {
            return $function;
        }

        return 'setTimeout(function() { ' . $function . ' }, ' . esc_attr($delay) . ');';
	}

    /**
     * The script for the "Open a Box" action. It opens the specified the box.
     *
     * @return string
     */
    private function _OpenBox()
    {
        if (!isset($this->item['box']))
        {
            return;
        }
        
        return $this->delayFunction($this->item['delay'], 'FireBox.getInstance(' . $this->item['box'] . ').open();');
    }

    /**
     * The script for the "Close a Box" action. It closes the specified the box.
     *
     * @return string
     */
    private function _CloseBox()
    {
        if (!isset($this->item['box']))
        {
            return;
        }
        
        return $this->delayFunction($this->item['delay'], 'FireBox.getInstance(' . $this->item['box'] . ').close();');
    }

    /**
     * The script for the "Close all opened Boxes" action. It used the closeAll() static method to close all boxes.
     *
     * @return string
     */
    private function _CloseAll()
    {
        return 'FireBox.closeAll();';
    }

    /**
     * The script for the "Destroy Box" action. It destroys the box instance.
     *
     * @return string
     */
    private function _DestroyBox()
    {
        if (!isset($this->item['box']))
        {
            return;
        }
        
        return 'FireBox.getInstance(' . esc_attr($this->item['box']) . ').destroy();';
    }

    /**
     * The script for the "Redirect to a URL" action. It redirects the visitor to a URL.
     *
     * @return string
     */
    private function _GoToURL()
    {   
        $target = isset($this->item['newtab']) && $this->item['newtab'] ? '_blank' : '_self';
        return 'window.open("' . esc_url($this->item['url']) . '", "' . esc_attr($target) . '")';
    }

    /**
     * The script for the "Reload Page" action.
     *
     * @return string
     */
    private function _ReloadPage()
    {   
        return 'location.reload();';
    }

    /**
     * The script for the "Run Javascript" action. It executes the custom Javascript code specified by the administrator.
     *
     * @return string
     */
    private function _Custom()
    {
        return $this->item['customcode'];
    }

    /**
     * Protect code scope by wrapping it with an anonymous fuction
     *
     * @param  string $string   The code to anonymise
     *
     * @return string
     */
    protected function anonymise($string)
    {
        // Keep the new line character inside return for code presentation purposes.
        return '
        !(function() { ' . $string . ' })();';
    }
}