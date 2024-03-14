<?php
/**
 * ifeelweb.de Wordpress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Html.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 */
class IfwPsn_Wp_Ajax_Response_Html extends IfwPsn_Wp_Ajax_Response_Abstract
{
    protected $_html;


    /**
     * @param $html
     */
    public function __construct($html = null)
    {
        if (!empty($html)) {
            $this->_html = $html;
        }
    }

    /**
     * Output response header
     */
    public function header()
    {
        header('Content-Type: text/html; charset=utf-8');
    }

    /**
     * Outputs the response data
     */
    public function output()
    {
        echo $this->_html;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->_html;
    }

    /**
     * @param mixed $html
     */
    public function setHtml($html)
    {
        $this->_html = $html;
    }

}
