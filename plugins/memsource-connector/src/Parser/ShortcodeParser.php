<?php

namespace Memsource\Parser;

use Memsource\Service\PlaceholderService;
use Memsource\Utils\AuthUtils;
use Memsource\Utils\StringUtils;

class ShortcodeParser
{
    private const SHORTCODE_MAX_NESTED_LEVEL = 100;
    private const SHORTCODE_WRAPPING_POST_CONTENT = 'memsource_body';

    /** @var AuthUtils */
    private $authUtils;

    /** @var PlaceholderService */
    private $placeholderService;

    public function __construct(AuthUtils $authUtils, PlaceholderService $placeholderService)
    {
        $this->authUtils = $authUtils;
        $this->placeholderService = $placeholderService;
    }

    /**
     * Encode WP Post to an HTML.
     *
     * @param string $content Post content.
     * @param array $availableShortcodes List of available shortcodes.
     *
     * @return ParserResult
     */
    public function encode(string $content, array $availableShortcodes): ParserResult
    {
        if (!StringUtils::containsText($content)) {
            return new ParserResult($content);
        }

        $result = "";
        $shortcodePostponeCount = [];
        $placeholders = [];

        // load WpBakery shortcodes so that function strip_shortcodes() works properly
        try {
            if (class_exists('\Vc_Frontend_Editor')) {
                (new \Vc_Frontend_Editor())->parseShortcodesString('');
            }
        } catch (\Exception $e) {
        }

        // post contains both shortcodes and plain text on the top level
        $contentWithoutShortcodes = strip_tags(strip_shortcodes($content));
        if (! empty(trim($contentWithoutShortcodes)) && $content !== $contentWithoutShortcodes) {
            $content = '[' . self::SHORTCODE_WRAPPING_POST_CONTENT . ']' . $content . '[/' . self::SHORTCODE_WRAPPING_POST_CONTENT . ']';
            $availableShortcodes[] = [
                'type' => "Memsource",
                'tag' => self::SHORTCODE_WRAPPING_POST_CONTENT,
                'ignore_body' => false,
                'editable' => false,
                'status' => 'Active',
                'delimiter' => '"',
                'attributes' => [],
            ];
        }

        while (!empty($availableShortcodes)) {
            foreach ($availableShortcodes as $shortcodeKey => $shortcodeObject) {
                $shortcode = $shortcodeObject['tag'];

                // 1) non-pair tags: [shortcode attribute="text"]
                if (sizeof($shortcodeObject['attributes']) > 0) {
                    $delimiter = $shortcodeObject['delimiter'];
                    foreach ($shortcodeObject['attributes'] as $attributeObject) {
                        $matchResult = 1;
                        $attribute = $attributeObject['name'];
                        while ($matchResult === 1) {
                            $matchResult = preg_match("|\[${shortcode}[^]]+?${attribute}=${delimiter}([^${delimiter}]+?)${delimiter}.*?\]|sm", $content, $matches);
                            if ($matchResult === 1) {
                                $extractedText = $matches[1];
                                $attributeWithId = $attribute . ':' . $this->authUtils->generateRandomToken();
                                $newContent = preg_replace("|(\[${shortcode}[^]]+?)${attribute}(=${delimiter}[^${delimiter}]+?${delimiter}.*?\])|sm", "$1${attributeWithId}$2", $content, 1);
                                if (strlen($newContent) === strlen($content)) {
                                    error_log("Infinite loop detected, aborting: ${content}");
                                    $matchResult = 0;
                                } else {
                                    $content = $newContent;
                                }
                                $result .= '<div id="' . $attributeWithId . '" class="memsource-attribute">' .
                                           $extractedText .
                                           '<div class="memsource-attribute-end" data-delimiter="' . $delimiter . '"></div>' .
                                           '</div>';
                            }
                        }
                    }
                }

                // 2) pair tags: [shortcode]text[/shortcode]
                if (! isset($shortcodeObject['ignore_body']) || ! $shortcodeObject['ignore_body']) {
                    $parsedShortcode = true;
                    while ($parsedShortcode) {
                        $parsedShortcode = $this->parseShortCode($content, $shortcode);
                        if ($parsedShortcode) {
                            $attributes = preg_replace("|]$|", "", $parsedShortcode[0]);
                            $extractedText = $parsedShortcode[1];
                            // shortcode contains another (nested) shortcode - process them first, postpone the current one
                            if ($extractedText !== strip_shortcodes($extractedText)) {
                                if (isset($shortcodePostponeCount[$shortcodeKey])) {
                                    $shortcodePostponeCount[$shortcodeKey] ++;
                                } else {
                                    $shortcodePostponeCount[$shortcodeKey] = 1;
                                }
                                if ($shortcodePostponeCount[$shortcodeKey] < self::SHORTCODE_MAX_NESTED_LEVEL) {
                                    continue 2;
                                } else {
                                    error_log("Too many nested available_shortcodes detected, skipping for [$shortcode]");
                                }
                            }
                            $shortcodeWithId = $shortcode . ':' . $this->authUtils->generateRandomToken();
                            $needle = "[${shortcode}${attributes}]";
                            $position = strpos($content, $needle);
                            // this is a fail-safe to avoid infinite loops if the string replacement fails
                            $newContent = substr_replace($content, "[${shortcodeWithId}${attributes}]", $position, strlen($needle));
                            // convert nested shortcodes to placeholders so that they won't appear in Memsource editor
                            $this->placeholderService->convertShortcodesToPlaceholders($extractedText, $placeholders);
                            if (strlen($newContent) === strlen($content)) {
                                error_log("Infinite loop detected, aborting: ${content}");
                                $parsedShortcode = false;
                            } else {
                                $content = $newContent;
                            }
                            // detect Base64 encoded content in fusion_code tag, decode and mark it in end:tag
                            $base64Detected = false;
                            if ($shortcode === 'fusion_code') {
                                $decodeResult = base64_decode($extractedText, true);
                                if ($decodeResult !== false) {
                                    $extractedText = html_entity_decode($decodeResult);
                                    $base64Detected = true;
                                }
                            }
                            $result .= '<div id="' . $shortcodeWithId . '" class="tag">';
                            $result .= $extractedText;
                            $result .= '<div class="memsource-attribute-end" data-base64="' . var_export($base64Detected, true) . '"></div>';
                            $result .= '</div>';
                        }
                    }
                }
                unset($availableShortcodes[$shortcodeKey]);
            }
        }

        if ($result !== '') {
            return new ParserResult($result, $placeholders, $content);
        }

        return new ParserResult($content);
    }

    /**
     * Parse attributes and content from short codes.
     *
     * Sample input:
     *   $content = '[tag id="1"]text[/tag]';
     *   $shortCodeName = 'tag';
     * Output:
     *   [' id="1"', 'text']
     *
     * @param string $content Post, page or any other content.
     * @param string $shortCodeName Name of shortcode.
     * @return array|bool Returns array of two parsed elements (attributes and body) or false in case of failure.
     */
    private function parseShortCode(string $content, string $shortCodeName)
    {
        $pattern = "#\[${shortCodeName}(\s+[^]]*]|])(.*?)\[/${shortCodeName}]#sm";

        if (!preg_match($pattern, $content, $matches)) {
            return false;
        }

        $wholeShortCode = $matches[0];

        $attributesPattern = '/(\[((?:[^\[\]]++|(?R))*)\])/m'; // Find matching bracket using PCRE recursive pattern

        if (!preg_match($attributesPattern, $wholeShortCode, $matches)) {
            return false;
        }

        $attributes = preg_replace("/^\s*$shortCodeName/", '', $matches[2], 1);

        if (!preg_match($pattern, str_replace($attributes, '', $wholeShortCode), $matches)) {
            return false;
        }

        return [$attributes, $matches[2]];
    }

    public function decode($content, $storedContent)
    {
        if (!$storedContent) {
            return $content;
        }

        // uncomment nested shortcodes
        $content = str_replace(' <!-- memsource-hidden-shortcode-start ', '', $content);
        $content = str_replace(' memsource-hidden-shortcode-end --> ', '', $content);
        $content = str_replace('<!-- memsource-hidden-shortcode-start ', '', $content);
        $content = str_replace(' memsource-hidden-shortcode-end -->', '', $content);

        // fix escaped quotes
        $content = preg_replace(
            '/(\\\\u[0-9a-zA-Z]{4})/sm',
            '\\\${0}',
            $content
        );

        // 1) Pair tags - iterate content for <div id="shortCodeWithId">Translated text</div>
        // find the transformed short code, strip the unique-id and replace its text with the translation
        // check end:tag if the content should be encoded to a Base64 string
        $patterns = [
            "|<div id=\"([^:]+?):([^\"]+?)\" class=\"tag\">(.*?)<!-- end:tag( base64:([^\s]+))? --></div>|sm", // <- BW compatibility
            "|<div id=\"([^:]+?):([^\"]+?)\" class=\"tag\">(.*?)<div class=\"memsource-attribute-end\"( data-base64=\"([^\s]+))?\"></div></div>|sm",
        ];
        foreach ($patterns as $pattern) {
            $matchResult = preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
            if ($matchResult > 0) {
                foreach (array_reverse($matches) as $match) {
                    $shortcode = $match[1];
                    $uniqueId = $match[2];
                    $extractedText = $match[3];
                    if (sizeof($match) >= 5 && $match[5] == 'true') {
                        $translatedText = base64_encode(htmlentities($extractedText));
                    } elseif ($shortcode !== self::SHORTCODE_WRAPPING_POST_CONTENT) {
                        $translatedText = html_entity_decode($extractedText);
                    } else {
                        $translatedText = $extractedText;
                    }
                    $translatedText = str_replace('$', '\$', $translatedText);
                    $shortcodeWithId = $shortcode . ":" . $uniqueId;
                    $storedContent = preg_replace(
                        "|\[${shortcodeWithId}([^]]*)\].*?\[/${shortcode}\]|sm",
                        "[${shortcode}$1]${translatedText}[/${shortcode}]",
                        $storedContent
                    );
                }
            }
        }

        // 2) Attributes from non-pair tags - iterate content for <span id="shortCodeWithId">Translated text</span>
        // find the transformed short code, strip the unique-id and replace its text with the translation
        $patterns = [
            "|<div id=\"([^:]+?):([^\"]+?)\" class=\"attribute\">(.+?)<!-- end:attribute( delimiter:([^\s]+))? --></div>|sm", // <- BW compatibility
            "|<div id=\"([^:]+?):([^\"]+?)\" class=\"memsource-attribute\">(.+?)<div class=\"memsource-attribute-end\"( data-delimiter=\"([^\s]+))?\"></div></div>|sm",
        ];
        foreach ($patterns as $pattern) {
            $matchResult = preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
            if ($matchResult > 0) {
                foreach ($matches as $match) {
                    $attribute = $match[1];
                    $uniqueId = $match[2];
                    $delimiter = '"';
                    if (sizeof($match) >= 5) {
                        $delimiter = $match[5];
                    }
                    $translatedText = html_entity_decode($match[3]);
                    $translatedText = str_replace('$', '\$', $translatedText);
                    $attributeWithId = $attribute . ":" . $uniqueId;
                    $storedContent = preg_replace(
                        "| ${attributeWithId}=[\"'].*?[\"']|sm",
                        " ${attribute}=${delimiter}${translatedText}${delimiter}",
                        $storedContent
                    );
                }
            }
        }

        $storedContent = str_replace('[' . self::SHORTCODE_WRAPPING_POST_CONTENT . ']', '', $storedContent);
        $storedContent = str_replace('[/' . self::SHORTCODE_WRAPPING_POST_CONTENT . ']', '', $storedContent);

        return $storedContent;
    }
}
