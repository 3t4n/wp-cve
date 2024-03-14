<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\CustomField;

/** @internal */
interface RegisterFieldGroupInterface
{
    /**
     * Init process to add new group of custom fields
     *
     * @return void
     */
    public function register() : void;
}
