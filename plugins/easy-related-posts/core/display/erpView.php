<?php

/**
 * Easy Related Posts 
 *
 * @package   Easy_Related_Posts_Core_display
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * Renderer class.
 *
 * @package Easy_Related_Posts_Core_display
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpView {

    /**
     * Renders a template
     *
     * @param string $filePath
     *        	The path to markup file
     * @param string $viewData
     *        	Any data passed to markup file
     * @param bool $echo If set to true echoes the out. Default is to return it
     * @return string Rendered content
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function render($filePath, $viewData = null, $echo = FALSE) {
        ( $viewData ) ? extract($viewData) : null;

        ob_start();
        include ( $filePath );
        $template = ob_get_contents();
        ob_end_clean();
        if (!$echo) {
            return $template;
        }
        echo $template;
    }

}
