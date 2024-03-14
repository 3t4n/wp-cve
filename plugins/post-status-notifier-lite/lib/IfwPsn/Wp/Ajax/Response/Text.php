<?php
/**
 * ifeelweb.de Wordpress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Text.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 */
class IfwPsn_Wp_Ajax_Response_Text extends IfwPsn_Wp_Ajax_Response_Abstract
{
    protected $_text;


    /**
     * @param $text
     */
    public function __construct($text = null)
    {
        if (!empty($text)) {
            $this->_text = $text;
        }
    }

    /**
     * Output response header
     */
    public function header()
    {
        header('Content-Type: text/plain; charset=utf-8');
    }

    /**
     * Outputs the response data
     */
    public function output()
    {
        echo $this->_text;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->_text = $text;
    }

}
