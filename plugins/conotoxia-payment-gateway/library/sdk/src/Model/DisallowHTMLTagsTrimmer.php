<?php

namespace CKPL\Pay\Model;

/**
 * Class DisallowHTMLTagsTrimmer
 * @package CKPL\Pay\Model
 */
class DisallowHTMLTagsTrimmer
{
    private static $COMMUNICATION_PROTOCOLS_REGEX = '#^[a-z]*?://|.*(www.)#';

    private static $HTML_SPECIAL_REGEX = '/[&#][a-zA-Z0-9]*?;.*?/';

    private static $HTML_BRACKET_REGEX = '/[<>]/';

    private static $HTML_MULTIPLE_SPACE_REGEX = '/\s+/';

    private static $TAB = '\t';

    private static $ENTITIES = null;

    /**
     * @param string $value
     *
     * @return string
     */
    public static function trim(string $value): string
    {
        self::init();
        $value = self::replaceCommunicationProtocolTags($value);
        $value = self::replaceDefaultHtmlTags($value);
        $value = self::resolveHtmlEntities($value);
        $value = self::replaceSpecialHtmlTags($value);
        $value = self::replaceBracketSign($value);
        $value = self::replaceTabSign($value);
        $value = self::replaceMultipleSpace($value);

        return $value;
    }

    /**
     * Initializes core functionalities
     */
    private static function init(): void
    {
        if (self::$ENTITIES === null) {
            self::$ENTITIES = [
                'O_ACUTE_SMALL' => new CustomHtmlEntity('&oacute;', '/&oacute;/', "\u{00F3}"),
                'O_ACUTE_BIG' => new CustomHtmlEntity('&Oacute;', '/&Oacute;/', "\u{00D3}"),
            ];
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private static function replaceCommunicationProtocolTags(string $value): string
    {
        return preg_replace(self::$COMMUNICATION_PROTOCOLS_REGEX, '', $value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private static function replaceDefaultHtmlTags(string $value): string
    {
        return htmlentities(strip_tags(html_entity_decode($value)));
    }

    /**
     * Replace <b>only</b> defined entities in DisallowHTMLTagsTrimmer::$ENTITIES
     * from $value, to it's UTF-8 corresponding character.
     *
     * @param string $value
     *
     * @return string
     */
    private static function resolveHtmlEntities(string $value): string
    {
        foreach (self::$ENTITIES as $entity) {
            /** @var CustomHtmlEntity $entity */
            if (strpos($value, $entity->getCharacterEntity()) !== false) {
                $value = preg_replace($entity->getRegex(), $entity->getUnicode(), $value);
            }
        }

        return $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private static function replaceSpecialHtmlTags(string $value): string
    {
        return preg_replace(self::$HTML_SPECIAL_REGEX, '', $value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private static function replaceBracketSign(string $value): string
    {
        return preg_replace(self::$HTML_BRACKET_REGEX, '', $value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private static function replaceTabSign(string $value): string
    {
        return str_replace(self::$TAB, '', $value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private static function replaceMultipleSpace(string $value): string
    {
        return preg_replace(self::$HTML_MULTIPLE_SPACE_REGEX, ' ', $value);
    }
}
