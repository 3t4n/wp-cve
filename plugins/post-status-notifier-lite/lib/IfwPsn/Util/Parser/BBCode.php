<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: BBCode.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package
 */
class IfwPsn_Util_Parser_BBCode extends IfwPsn_Util_Parser_Abstract
{
    protected static array $find = array(
        '~\[br\]~s',
        '~\[b\](.*?)\[/b\]~s',
        '~\[i\](.*?)\[/i\]~s',
        '~\[u\](.*?)\[/u\]~s',
        '~\[quote\](.*?)\[/quote\]~s',
        '~\[size=(.*?)\](.*?)\[/size\]~s',
        '~\[color=(.*?)\](.*?)\[/color\]~s',
        '~\[url=((?:ftp|https?)://.*?)\](.*?)\[/url\]~s',
        '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
    );

    /**
     * @param $text
     * @return mixed
     */
    public static function parse($text)
    {
        $replace = array(
            '<br>',
            '<b>$1</b>',
            '<i>$1</i>',
            '<span style="text-decoration:underline;">$1</span>',
            '<pre>$1</'.'pre>',
            '<span style="font-size:$1px;">$2</span>',
            '<span style="color:$1;">$2</span>',
            '<a href="$1" target="_blank">$2</a>',
            '<img src="$1" alt="" />'
        );

        $text = preg_replace(self::$find, $replace, $text);
        return self::stripNullByte($text);
    }

    /**
     * @param $text
     * @return mixed
     */
    public static function remove($text)
    {
        $find = [
            '~\[br\]~s',
            '~\[b\]~s',
            '~\[/b\]~s',
            '~\[i\]~s',
            '~\[/i\]~s',
            '~\[u\]~s',
            '~\[/u\]~s',
            '~\[url=((?:ftp|https?)://.*?)\]~s',
            '~\[/url\]~s'
        ];

        $replace = array_fill(0, count($find), '');
        $replace[0] = "\n";

        $text = preg_replace($find, $replace, $text);
        return self::stripNullByte($text);
    }
}
