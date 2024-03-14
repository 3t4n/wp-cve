<?php

namespace AOP\App\Options\Interfaces;

interface OptionsStoreInterface
{
    public function optionsSettingsInit();

    public function optionCallback($arg);

    public function displayCallback();
}
