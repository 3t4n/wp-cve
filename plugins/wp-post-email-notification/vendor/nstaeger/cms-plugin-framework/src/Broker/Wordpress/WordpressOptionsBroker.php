<?php

namespace Nstaeger\CmsPluginFramework\Broker\Wordpress;

use Nstaeger\CmsPluginFramework\Broker\OptionBroker;
use Nstaeger\CmsPluginFramework\Configuration;
use Nstaeger\CmsPluginFramework\Support\ArgCheck;

class WordpressOptionsBroker implements OptionBroker
{
    /**
     * @var string
     */
    private $prefix;

    public function __construct(Configuration $configuration)
    {
        $this->prefix = $configuration->getOptionPrefix();
    }

    function delete($option)
    {
        ArgCheck::notNull($option);

        delete_option($this->prefix($option));
    }

    public function get($option, $default = false )
    {
        ArgCheck::notNull($option);

        return get_option($this->prefix($option), $default);
    }

    public function store($option, $value)
    {
        ArgCheck::notNull($option);

        update_option($this->prefix($option), $value);
    }

    private function prefix($option)
    {
        return $this->prefix . $option;
    }
}
