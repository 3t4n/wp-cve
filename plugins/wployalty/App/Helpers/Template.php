<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;
defined('ABSPATH') or die;

class Template
{
    protected $path, $data = array();

    /**
     * Template constructor.
     * @since 1.0.0
     */
    function __construct()
    {
        return $this;
    }

    /**
     * set the path and data of the template
     * @param $path
     * @param $data
     * @return $this
     * @since 1.0.0
     */
    function setData($path, $data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }
        $this->path = $path;
        $this->data = $data;
        return $this;
    }

    /**
     * get the rendered template
     * @return false|string
     * @since 1.0.0
     */
    function render()
    {
        return $this->processTemplate();
    }

    /***
     * process the template nad return content
     * @return false|string|void
     * @since 1.0.0
     */
    function processTemplate()
    {
        if (file_exists($this->path)) {
            try {
                ob_start();
                if (!empty($this->data)) {
                    extract($this->data);
                }
                include $this->path;
                return ob_get_clean();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return __('Template not found', 'wp-loyalty-rules');
    }

    /**
     * print out the template
     * @since 1.0.0
     */
    function display()
    {
        echo $this->processTemplate();
    }
}