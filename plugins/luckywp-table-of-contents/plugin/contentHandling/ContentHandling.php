<?php

namespace luckywp\tableOfContents\plugin\contentHandling;

use DOMXPath;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\plugin\dom\Dom;

class ContentHandling
{

    /**
     * @var int
     */
    protected static $headingIdCounter = 0;

    /**
     * @var string[]
     */
    protected static $headingIds = [];

    /**
     * @param ContentHandlingDto $dto
     * @return ContentHandlingResult
     */
    public static function go($dto)
    {
        $result = new ContentHandlingResult();
        $result->content = $dto->content;

        $skipRegex = Core::$plugin->skipHeadingTextToRegex($dto->skipText);

        static::reset();

        $dom = Dom::make($dto->content);
        if ($dom === false) {
            return $result;
        }

        $xpath = new DOMXPath($dom);
        $headingNodes = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');

        // Собираем все заголовки
        foreach ($headingNodes as $node) {
            /** @var $node \DOMElement */

            $label = static::makeLabel($node);
            if ($label == '') {
                continue;
            }

            $id = static::makeHeadingId($label, $dto);

            $index = (int)mb_substr($node->nodeName, 1, 1);

            $result->headings[] = [
                'id' => $id,
                'index' => $index,
                'label' => $label,
            ];

            if (!$dto->modify ||
                in_array('h' . $index, $dto->skipLevels) ||
                ($skipRegex && preg_match($skipRegex, $label))
            ) {
                continue;
            }

            $span = $dom->createElement('span');
            $span->setAttribute('id', $id);
            while ($node->childNodes->length) {
                $span->appendChild($node->firstChild);
            }
            $node->nodeValue = '';
            $node->appendChild($span);
        }

        $result->content = Dom::getBody($dom);

        return $result;
    }

    /**
     * @param \DOMElement $node
     * @return string
     */
    protected static function makeLabel($node)
    {
        $html = Dom::getInnerHtml($node);
        $html = apply_filters('lwptoc_heading_html', $html);

        $html = preg_replace('#<\s*script[^>]*>.*?</script\s*>#imsu', '', $html);
        $html = preg_replace('#<\s*style[^>]*>.*?</style\s*>#imsu', '', $html);

        $label = strip_tags($html);
        $label = html_entity_decode($label, ENT_HTML5, 'UTF-8');
        $label = apply_filters('lwptoc_heading_label', $label, $html);

        $label = preg_replace('/\s+/u', ' ', $label);
        $label = trim($label);
        return $label;
    }

    /**
     * @param string $label
     * @param ContentHandlingDto $dto
     * @return string
     */
    protected static function makeHeadingId($label, $dto)
    {
        switch ($dto->hashFormat) {
            case 'counter':
                $id = 'lwptoc' . ++static::$headingIdCounter;
                break;

            case 'asheading':
            case 'asheadingwotransliterate':
            default:
                $id = $label;
                if ($dto->hashFormat == 'asheadingwotransliterate') {
                    $id = htmlentities2($id);
                    $id = str_replace([
                        '&amp;',
                        '&nbsp;',
                        '#',
                        "\n\r",
                        "\r\n",
                        "\r",
                        "\n",
                        ':',
                    ], '_', $id);
                    $id = html_entity_decode($id, ENT_QUOTES, get_option('blog_charset'));
                } else {
                    $id = html_entity_decode($id, ENT_QUOTES, get_option('blog_charset'));
                    if (function_exists('transliterator_transliterate') && !apply_filters('lwptoc_force_wp_transliterate', false)) {
                        $id = transliterator_transliterate(apply_filters('lwptoc_transliterator', 'Any-Latin; Latin-ASCII;'), $id);
                    } else {
                        $id = remove_accents($id);
                    }
                    $id = preg_replace('/[^\d\-_A-Za-z ]+/', '', $id);
                }
                $id = preg_replace('/  +/', ' ', $id);
                $id = trim($id);
                $id = str_replace(' ', '_', $id);
                $id = preg_replace('/__+/', '_', $id);
                $id = trim($id, '-_');

                if ($dto->hashConvertToLowercase) {
                    $id = function_exists('mb_strtolower') ? mb_strtolower($id) : strtolower($id);
                }

                if ($dto->hashReplaceUnderlinesToDashes) {
                    $id = str_replace('_', '-', $id);
                    $id = preg_replace('/--+/', '-', $id);
                }

                if (!$id) {
                    $id = 'lwptoc';
                }
        }

        $id = (string)apply_filters('lwptoc_heading_id', $id, $label);

        $baseId = $id;
        $c = 0;
        while (in_array($id, static::$headingIds)) {
            $c++;
            $id = $baseId . $c;
        }
        static::$headingIds[] = $id;

        return $id;
    }

    /**
     * @return void
     */
    protected static function reset()
    {
        static::$headingIdCounter = 0;
        static::$headingIds = [];
    }
}
