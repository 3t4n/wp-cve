<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: AdminComments.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package   
 */ 
class IfwPsn_Util_Parser_AdminComments 
{
    /**
     * @var array
     */
    protected static $_allowedTags = array(
        '<a>',
        '<b>',
        '<br>',
        '<div>',
        '<em>',
        '<p>',
        '<span>',
        '<ul>',
        '<li>',
    );

    /**
     * @return array
     */
    public static function getAllowedTags()
    {
        return self::$_allowedTags;
    }

    public static function addAllowedTag($tag)
    {
        //array_push(self::$_allowedTags, $tag);
    }

    public static function sanitize($text)
    {
        if (!empty($text)) {
            $text = strip_tags(html_entity_decode($text), implode('', self::getAllowedTags()));
        }

        return $text;
    }

    /**
     * @param $text
     * @return mixed|string
     */
    public static function parse($text)
    {
        $result =  nl2br(strip_tags(html_entity_decode($text), implode('', self::getAllowedTags())));
        $result = IfwPsn_Util_Parser_Html::sanitize($result);
        return $result;
    }
}
