<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Html.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package
 */
class IfwPsn_Util_Parser_Html extends IfwPsn_Util_Parser_Abstract
{
    /**
     * @param $html
     * @return mixed
     */
    public static function sanitize($html)
    {
        $html = self::stripNullByte($html);
        $html = self::stripScript($html);
        $html = self::removeComments($html);

        return $html;
    }

    public static function removeComments($html)
    {
        return preg_replace('/<!--(.|\s)*?-->/', '', $html);
    }

    /**
     * @param $html
     * @return mixed
     */
    public static function stripScript($html = null)
    {
        if (!empty($html)) {
            do {
                if (isset($result)) {
                    $html = $result;
                }
                $result = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
            } while ($result != $html);
        } else {
            $result = '';
        }

        return $result;
    }

    public static function prepareHtml($html, $options = array())
    {
        $html = preg_replace("/\r|\n/", "", $html);

        if (isset($options['charset'])) {
            $search = ($options['charset'] == 'UTF-8') ? "\xC2\xA0" : "\xA0";
            $html = str_replace($search, ' ', html_entity_decode($html, ENT_COMPAT, $options['charset']));
        }

        return $html;
    }

    /**
     * @param $selector
     * @param $html
     * @param array $options
     * @return null|string|string[]
     */
    public static function getBlock($selector, $html, $options = array())
    {
        set_time_limit(0);

        $result = '';

        $defaults = array(
            'block_tag' => 'div',
            'result_type' => 'block' // block / innerHtml
        );

        $isClassSelector = IfwPsn_Util_Parser_Css::isClassSelector($selector);
        $selector = substr($selector, 1);
        $selectorAttrName = $isClassSelector ? 'class' : 'id';

        $options = array_merge($defaults, $options);

        $html = self::prepareHtml($html, $options);

        /**
         * DOM attempt
         */
        $dom = new DomDocument();
        libxml_use_internal_errors(true);
        @$dom->loadHTML($html);

        $finder = new DomXPath($dom);
        if ($isClassSelector) {
            $nodes = $finder->query("//*[contains(@class, '$selector')]");
        } else {
            $nodes = $finder->query(sprintf('//%s[@id="%s"]', $options['block_tag'], $selector));
        }

        if ($nodes->length > 0) {
            /**
             * Xpath success
             */
            foreach ($nodes as $node) {
                if ($isClassSelector && strtolower($options['result_type']) === 'innerhtml') {
                    foreach ($node->childNodes as $child) {
                        $result .= $node->ownerDocument->saveHTML($child);
                    }
                } else {
                    $result .= $dom->saveXML($node);
                }
            }
        } else {
            if ($isClassSelector) {

            } else {
                $block = $dom->getElementById($selector);
                if (!empty($block)) {
                    if (strtolower($options['result_type']) === 'innerhtml') {
                        $result = $block->textContent;
                    } else {
                        $result = $dom->saveHTML($block);
                    }
                }
            }
        }

        if (empty($result)) {

            /**
             * Line parser attempt
             */
            $saveBuffer = false;

            foreach (preg_split("/$\R?^/m", $html) as $line) {

                if (strpos(trim($line), sprintf('<%s %s="%s"', $options['block_tag'], $selectorAttrName, $selector)) !== false) {
                    $saveBuffer = true;
                }
                if ($saveBuffer === true && trim($line) == sprintf('</%s>', $options['block_tag'])) {
                    $saveBuffer = false;
                }

                if ($saveBuffer === true) {
                    $result .= $line;
                }
            }
        }

        /**
         * Prepare result
         */
        if (!empty($result)) {
            $result = trim($result);
            $result = ifw_remove_whitespace_between_tags($result);
            $result = IfwPsn_Util_Parser_Js::remove($result);

            if (strtolower($options['result_type']) === 'innerhtml' && strpos($result, '<') === 0) {

                $dom = new DomDocument();
                libxml_use_internal_errors(true);
                @$dom->loadHTML($result);
                if ($isClassSelector) {
//                    $result = $dom->saveHTML();
                } else {
                    $block = $dom->getElementById($selector);
                    $result = '';
                    foreach ($block->childNodes as $child) {
                        $result .= $block->ownerDocument->saveHTML($child);
                    }
                }
            }
        }

        return $result;
    }
}
