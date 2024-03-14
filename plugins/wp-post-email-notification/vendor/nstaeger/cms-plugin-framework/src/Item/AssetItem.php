<?php

namespace Nstaeger\CmsPluginFramework\Item;

use Nstaeger\CmsPluginFramework\Support\Str;

class AssetItem
{
    const TYPE_SCRIPT = 'script';
    const TYPE_STYLE = 'style';
    const TYPE_UNKNOWN = 'unknown';

    /**
     * The hook on which the asset should be available (admin only)
     *
     * @var string
     */
    private $hook;

    /**
     * (script, style, ...)
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $url;

    /**
     * @param string $url URL to the asset, relative from plugin url
     */
    public function __construct($url)
    {
        $this->url = $url;

        if (Str::endsWith($url, '.js')) {
            $this->type = self::TYPE_SCRIPT;
        } else if (Str::endsWith($url, '.css')) {
            $this->type = self::TYPE_STYLE;
        } else {
            $this->type = self::TYPE_UNKNOWN;
        }
    }

    /**
     * @return string may be null
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * @return string
     */
    public function getName()
    {
        // TODO generate real name
        return $this->url;
    }

    /**
     * Get the type of the asset
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $hook load asset only on the specified hook (admin only)
     * @return $this
     */
    public function onlyOn($hook)
    {
        $this->hook = $hook;

        return $this;
    }
}
