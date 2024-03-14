<?php

/**
 * Gutenberg editor
 *
 * Class Wpil_Editor_Gutenberg
 */
class Wpil_Editor_Kadence
{
    public static $keyword_links_count;

    /**
     * Get all Kadence block positions
     *
     * @param $content
     * @return array
     */
    public static function getBlocks($content)
    {
        $blocks = [];
        $end = 0;
        $i = 0;
        while ($end < strlen($content) && strpos($content, '<!-- wp:kadence', $end) !== false) {
            $begin = strpos($content, '<!-- wp:kadence', $end);
            $type = self::getBlockType($content, $begin);
            $ending = '<!-- /wp:kadence/' . $type . ' -->';
            $end = strpos($content, $ending, $begin) + strlen($ending);
            $blocks[] = [$begin, $end, $type];

            //protection from infinite loop
            $i++;
            if ($i > 50) {
                break;
            }
        }

        $end = 0;
        $i = 0;
        while ($end < strlen($content) && strpos($content, '<!-- wp:rank-math', $end) !== false) {
            $begin = strpos($content, '<!-- wp:rank-math', $end);
            $type = self::getBlockType($content, $begin);
            $ending = '<!-- /wp:rank-math/' . $type . ' -->';
            $end = strpos($content, $ending, $begin) + strlen($ending);
            $blocks[] = [$begin, $end, $type];

            //protection from infinite loop
            $i++;
            if ($i > 50) {
                break;
            }
        }

        return $blocks;
    }

    /**
     * Get block type
     *
     * @param $content
     * @param $begin
     * @return string
     */
    public static function getBlockType($content, $begin)
    {
        $end = strpos($content, ' ', $begin + 5);
        $type = substr($content, $begin, $end - $begin);
        $type = explode('/', $type);
        return $type[1];
    }

    /**
     * Get Kadence block by sentence
     *
     * @param $sentence
     * @param $content
     * @return array | bool
     */
    public static function getBlock($sentence, $content)
    {
        $types = [
            'iconlist',
            'testimonials',
            'faq-block',
            'howto-block'
        ];

        $pos = strpos($content, $sentence);
        if (!empty($pos)) {
            foreach (self::getBlocks($content) as $block) {
                if (in_array($block[2], $types) && $block[0] < $pos && $block[1] > $pos) {
                    $text = substr($content, $block[0], $block[1] - $block[0]);
                    $begin = strpos($text, '{');
                    $end = strpos($text, '} -->', $begin);
                    $json = substr($text, $begin, $end - $begin + 1);

                    return [
                        'type' => $block[2],
                        'text' => $text,
                        'json' => $json
                    ];
                }
            }
        }

        return false;
    }

    /**
     * Delete link
     *
     * @param $content
     * @param $url
     * @param $anchor
     */
    public static function deleteLink(&$content, $url, $anchor)
    {
        self::findBlock($content, [
            'action' => 'delete',
            'url' => $url,
            'anchor' => $anchor,
            'search' => $url
        ]);
    }

    /**
     * Remove links insert by Auto-Linking
     *
     * @param $content
     * @param $keyword
     * @param bool $left_one
     */
    public static function removeKeywordLinks(&$content, $keyword, $left_one = false)
    {
        self::$keyword_links_count = 0;
        self::findBlock($content, [
            'action' => 'remove_keyword',
            'keyword' => $keyword,
            'left_one' => $left_one,
            'search' => $keyword->link
        ]);
    }

    /**
     * Replace URLs
     *
     * @param $post
     * @param $url
     */
    public static function replaceURLs(&$content, $url)
    {
        self::findBlock($content, [
            'action' => 'replace_urls',
            'url' => $url,
            'search' => $url->old
        ]);
    }

    /**
     * Revert URLs
     *
     * @param $post
     * @param $url
     */
    public static function revertURLs(&$content, $url)
    {
        self::findBlock($content, [
            'action' => 'revert_urls',
            'url' => $url,
            'search' => $url->new
        ]);
    }

    /**
     * Find block with sentence or link
     *
     * @param $content
     * @param $params
     */
    public static function findBlock(&$content, $params)
    {
        if ($block = self::getBlock($params['search'], $content)) {
            $slashes = false;
            $json = json_decode($block['json']);
            if (empty($json)) {
                $json = json_decode(stripslashes($block['json']));
                $slashes = true;
            }
            switch ($block['type']) {
                case 'iconlist':
                    foreach ($json->items as $key => $item) {
                        self::manageElement($json->items[$key]->text, $params);
                    }
                    break;
                case 'testimonials':
                    foreach ($json->testimonials as $key => $item) {
                        self::manageElement($json->testimonials[$key]->content, $params);
                    }
                    break;
                case 'faq-block':
                    foreach ($json->questions as $key => $item) {
                        self::manageElement($json->questions[$key]->content, $params);
                    }
                    break;
                case 'howto-block':
                    foreach ($json->steps as $key => $item) {
                        self::manageElement($json->steps[$key]->content, $params);
                    }
                    break;
            }

            $json = json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
            if ($slashes) {
                $json = addslashes($json);
            }
            $text = str_replace($block['json'], $json, $block['text']);
            $content = str_replace($block['text'], $text, $content);
        }
    }

    /**
     * Route current action
     *
     * @param $block
     * @param $params
     */
    public static function manageElement(&$element, $params)
    {
        if ($params['action'] == 'insert') {
            self::insertLinkToElement($element, $params['sentence'], $params['replacement']);
        } elseif ($params['action'] == 'delete') {
            self::deleteLinkFromElement($element, $params['url'], $params['anchor']);
        }
    }

    /**
     * Insert link to the Element
     *
     * @param $element
     * @param $sentence
     * @param $replacement
     */
    public static function insertLinkToElement(&$element, $sentence, $replacement)
    {
        $sentence = stripslashes($sentence);
        $replacement = stripslashes($replacement);
        if (strpos($element, $sentence) !== false) {
            $element = preg_replace('`' . preg_quote($sentence, '`') . '`i', $replacement, $element, 1);
        }
    }

    /**
     * Delete link from the element
     *
     * @param $post_id
     * @param $url
     * @param $anchor
     */
    public static function deleteLinkFromElement(&$element, $url, $anchor)
    {
        $anchor = stripslashes($anchor);
        preg_match('`<a .+?' . preg_quote($url, '`') . '.+?>' . preg_quote($anchor, '`') . '</a>`i', $element,  $matches);
        if (!empty($matches[0])) {
            $element = preg_replace('|<a [^>]+' . preg_quote($url, '`') . '[^>]+>' . preg_quote($anchor, '`') . '</a>|i', $anchor,  $element, 1);
        }
    }
}
