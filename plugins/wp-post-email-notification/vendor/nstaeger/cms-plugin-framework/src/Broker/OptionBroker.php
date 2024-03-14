<?php

namespace Nstaeger\CmsPluginFramework\Broker;

interface OptionBroker
{
    /**
     * Delete an option.
     *
     * @param string $option unique name of the option
     */
    function delete($option);

    /**
     * Retrieve the value of an option.
     *
     * @param string $option unique name of the option
     * @param mixed $default Optional. Default value to return if the option does not exist.
     * @return mixed
     */
    function get($option, $default = false);

    /**
     * Store a value of an option.
     *
     * @param string $option unique name of the option
     * @param mixed $value data to store under this option
     */
    function store($option, $value);
}
