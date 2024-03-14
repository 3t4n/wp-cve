<?php

namespace Modular\SDK\Objects;

class BaseObjectFactory
{
    /**
     * @var array<string, string>
     */
    private static array $classMap = [
        'oauth_token' => OauthToken::class,
        'site_request' => SiteRequest::class,
    ];

    /**
     * @param string $name
     *
     * @return mixed|string|null
     */
    public static function getObjectClass(string $name): ?string
    {
        return array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }

    /**
     * TODO incomplete funciton to parse attributes from API response
     *
     * @param string $name
     * @return string|null
     */
    public static function parseAttributes(\stdClass $attrs)
    {
        if (!isset($attrs->attributes)) {
            return $attrs;
        }

        $attrs->attributes->id = $attrs->id;

        return $attrs->attributes;
    }

    /**
     * @param string $object
     *
     * @return false|int|string|null
     */
    protected function getObjectAlias(string $object)
    {
        return array_search($object, self::$classMap) ?? null;
    }
}
