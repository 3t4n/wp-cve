<?php

namespace Memsource\Service;

use Memsource\Utils\AuthUtils;

class PlaceholderService
{
    /** @var AuthUtils */
    private $authUtils;

    public function __construct(AuthUtils $authUtils)
    {
        $this->authUtils = $authUtils;
    }

    /**
     * Convert HTML comments to placeholders.
     *
     * @param string $text
     * @param array $placeholders
     */
    public function convertHtmlCommentsToPlaceholders(&$text, &$placeholders)
    {
        $pattern = '/<!--(.*)-->/Uis';
        preg_match_all($pattern, $text, $matches);

        foreach ($matches[0] ?? [] as $match) {
            $pos = strpos($text, $match);

            if ($pos !== false) {
                $token = $this->authUtils->generateRandomToken();
                $text = substr_replace($text, $this->createPlaceholderTag($token), $pos, strlen($match));
                $placeholders[$token] = $match;
            }
        }
    }

    /**
     * Convert valid shortcodes to placeholders.
     *
     * @param string $text
     * @param array $placeholders
     */
    public function convertShortcodesToPlaceholders(&$text, &$placeholders)
    {
        $pattern = '|\[([\w\-_]+)([^]]*)?](?:(.+?)?\[/\1])?|sm';
        preg_match_all($pattern, $text, $matches);

        foreach ($matches[0] ?? [] as $match) {
            $pos = strpos($text, $match);

            if ($pos !== false) {
                preg_match_all('/' . get_shortcode_regex() . '/', $match, $res, PREG_SET_ORDER);

                if (isset($res[0][2]) && shortcode_exists($res[0][2])) {
                    $token = $this->authUtils->generateRandomToken();
                    $text = substr_replace($text, $this->createPlaceholderTag($token), $pos, strlen($match));
                    $placeholders[$token] = $match;
                }
            }
        }
    }

    /**
     * Create a HTML tag using given unique token.
     *
     * @param string $token
     *
     * @return string
     */
    public function createPlaceholderTag(string $token): string
    {
        return "<div id=\"memsource-placeholder_$token\"></div>";
    }

    /**
     * Restore strings converted to placeholders.
     *
     * @param string|null $encodedPlaceholders
     * @param string $text
     *
     * @return string
     */
    public function restorePlaceholders($encodedPlaceholders, $text, $depth = 0)
    {
        if ($encodedPlaceholders !== null) {
            $placeholders = json_decode($encodedPlaceholders, true);
            preg_match_all('|<div id="memsource-placeholder_([a-z0-9]+)"></div>|sm', $text, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                if (isset($placeholders[$match[1]])) {
                    $text = str_replace($match[0], $placeholders[$match[1]], $text);
                }
            }
        }

        if (strpos($text, '<div id="memsource-placeholder_') !== false && $depth < 10) {
            $text = $this->restorePlaceholders($encodedPlaceholders, $text, ++$depth);
        }

        return $text;
    }
}
