<?php

namespace Baqend\SDK\Value;

/**
 * A media type is a two-part identifier for file formats and format contents transmitted on the Internet.
 *
 * Class MediaType created on 13.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Value
 */
final class MediaType
{

    const APPLICATION = 'application';
    const AUDIO = 'audio';
    const EXAMPLE = 'example';
    const IMAGE = 'image';
    const MESSAGE = 'message';
    const MODEL = 'model';
    const MULTIPART = 'multipart';
    const TEXT = 'text';
    const VIDEO = 'video';

    private static $types = [
        self::APPLICATION,
        self::AUDIO,
        self::EXAMPLE,
        self::IMAGE,
        self::MESSAGE,
        self::MODEL,
        self::MULTIPART,
        self::TEXT,
        self::VIDEO,
    ];

    private static $trees = [
        'vnd',
        'prs',
        'x',
    ];

    private static $suffices = [
        'xml',
        'json',
        'ber',
        'der',
        'fastinfoset',
        'wbxml',
        'zip',
        'gzip',
        'cbor',
    ];

    /** @var MediaType[] */
    private static $instances = [];

    /** @var string */
    private $string;

    /** @var string */
    private $type;

    /** @var string|null */
    private $tree;

    /** @var string */
    private $subtype;

    /** @var string|null */
    private $suffix;

    /** @var string|null */
    private $parameter;

    /**
     * MediaType constructor.
     *
     * @param string $string
     * @param string $type
     * @param string|null $tree
     * @param string $subtype
     * @param string|null $suffix
     * @param string|null $parameter
     */
    private function __construct($string, $type, $tree, $subtype, $suffix, $parameter) {
        $this->string = $string;
        $this->type = $type;
        $this->tree = $tree;
        $this->subtype = $subtype;
        $this->suffix = $suffix;
        $this->parameter = $parameter;
    }

    /**
     * Parses a media type string to a media type.
     *
     * @param mixed $string The string to parse.
     * @return MediaType The media type instance found.
     * @throws \InvalidArgumentException If the $string is not a valid media type.
     */
    public static function parse($string) {
        if (!is_string($string)) {
            $message = 'Media type to parse must be of type string, '.gettype($string).' given.';
            throw new \InvalidArgumentException($message);
        }

        if (preg_match('#^([^/]+)/([^;]+)(;.*|)$#', $string, $matches) !== 1) {
            throw new \InvalidArgumentException('"'.$string.'" is not a valid media type.');
        }

        list(, $type, $subtype, $parameterString) = $matches;

        if (!empty($parameterString)) {
            $parameter = trim(substr($parameterString, 1));
        } else {
            $parameter = null;
        }

        return self::fromValues($type, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "application" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function application($subtype, $parameter = null) {
        return self::fromValues(self::APPLICATION, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "audio" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function audio($subtype, $parameter = null) {
        return self::fromValues(self::AUDIO, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "example" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function example($subtype, $parameter = null) {
        return self::fromValues(self::EXAMPLE, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "image" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function image($subtype, $parameter = null) {
        return self::fromValues(self::IMAGE, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "message" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function message($subtype, $parameter = null) {
        return self::fromValues(self::MESSAGE, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "model" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function model($subtype, $parameter = null) {
        return self::fromValues(self::MODEL, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "multipart" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function multipart($subtype, $parameter = null) {
        return self::fromValues(self::MULTIPART, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "text" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function text($subtype, $parameter = null) {
        return self::fromValues(self::TEXT, $subtype, $parameter);
    }

    /**
     * Returns an instance of a "video" media type.
     *
     * @param string $subtype The required subtype for the given type.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType Returns the instance.
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function video($subtype, $parameter = null) {
        return self::fromValues(self::VIDEO, $subtype, $parameter);
    }

    /**
     * Creates a media type from type, subtype, and parameter.
     *
     * @param string $type The main type.
     * @param string $subtype The subtype.
     * @param string|null $parameter An optional media type parameter.
     * @return MediaType
     * @throws \InvalidArgumentException If the $subtype is invalid.
     */
    public static function fromValues($type, $subtype, $parameter = null) {
        $type = strtolower(trim($type));
        self::inArray('type', $type, self::$types);

        $subtype = strtolower(trim($subtype));

        // Extract the subtype's tree
        if (preg_match('#^([^\\.]+)\\.(.*)$#', $subtype, $matches) === 1) {
            list(, $tree, $subtype) = $matches;
            self::inArray('tree', $tree, self::$trees);
        } else {
            $tree = null;
        }

        // Extract the subtype's suffix
        if (preg_match('#^([^\\+]+)\\+([^\\+]+)$#', $subtype, $matches) === 1) {
            list(, $subtype, $suffix) = $matches;
            self::inArray('suffix', $suffix, self::$suffices);
        } else {
            $suffix = null;
        }

        return self::create($type, $tree, $subtype, $suffix, $parameter);
    }

    /**
     * @param string $type
     * @param string|null $tree
     * @param string $subtype
     * @param string|null $suffix
     * @param string|null $parameter
     * @return MediaType
     */
    private static function create($type, $tree, $subtype, $suffix, $parameter) {
        $string = self::toString($type, $tree, $subtype, $suffix, $parameter);
        if (isset(self::$instances[$string])) {
            return self::$instances[$string];
        }

        return self::$instances[$string] = new MediaType($string, $type, $tree, $subtype, $suffix, $parameter);
    }

    /**
     * Ensures the given element is in the given array.
     *
     * @param string $name
     * @param string $needle
     * @param string[] $haystack
     */
    private static function inArray($name, $needle, array $haystack) {
        if (!in_array($needle, $haystack, true)) {
            $message = 'The '.$name.' must be one of ['.implode(',', $haystack).'], but "'.$needle.'" was given.';
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @param string $type
     * @param string|null $tree
     * @param string $subtype
     * @param string|null $suffix
     * @param string|null $parameter
     * @return string
     */
    private static function toString($type, $tree, $subtype, $suffix, $parameter) {
        $result = $type.'/';
        if ($tree !== null) {
            $result .= $tree.'.';
        }
        $result .= $subtype;
        if ($suffix !== null) {
            $result .= '+'.$suffix;
        }
        if ($parameter !== null) {
            $result .= '; '.$parameter;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return null|string
     */
    public function getTree() {
        return $this->tree;
    }

    /**
     * @return string
     */
    public function getSubtype() {
        return $this->subtype;
    }

    /**
     * @return null|string
     */
    public function getSuffix() {
        return $this->suffix;
    }

    /**
     * @return null|string
     */
    public function getParameter() {
        return $this->parameter;
    }

    /**
     * @return bool
     */
    public function isStandard() {
        return $this->tree === null;
    }

    /**
     * @return bool
     */
    public function isVendor() {
        return $this->tree === 'vnd';
    }

    /**
     * Returns a version of this media type without parameter.
     *
     * @return MediaType The cloned media type without parameter.
     */
    public function withoutParameter() {
        return self::create($this->type, $this->tree, $this->subtype, $this->suffix, null);
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->string;
    }
}
