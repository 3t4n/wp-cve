<?php

/**
 * @link   https://www.jssor.com
 * @version 1.0
 * @author jssor
 */

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

class WP_Jssor_Push
{
    const NEWLINE = "\r\n";
    private $count = 0;

    public function write($str)
    {
        //$strlen = strlen($str);
        //$output = dechex($strlen).WP_Jssor_Push::NEWLINE.$str.WP_Jssor_Push::NEWLINE;

        $output = $str.WP_Jssor_Push::NEWLINE;

        ob_start();
        echo $output;
        ob_flush();
        flush();
        ob_end_flush();

        $this->count += strlen($output);
    }

    //browsers collect first 1024 bytes, and show page only if bytes collected
    //some server buffers the first 4096 bytes
    public function begin()
    {
        $restlength = 4096 - $this->count;

        if($restlength > 0) {
            $str = str_repeat(' ', $restlength);
            $this->write($str);
        }
    }

    public function push($method, $args)
    {
        $str = '<script type="text/javascript">';
        $str .= 'jssor_push(';

        $str .=  json_encode($method);

        if(!empty($args)){
            foreach ($args as $value)
            {
                $str .=  ','.json_encode($value);
            }
        }

        $str .=  ');';
        $str .=  '</script>';

        $this->write($str);
        $this->write('<br>');
    }

    public function end()
    {
        $this->push('end', null);
    }

    public function close()
    {
        //ob_start();
        //terminating part of encoding format
        //echo "0\r\n\r\n";
        //ob_flush();
    }
}
