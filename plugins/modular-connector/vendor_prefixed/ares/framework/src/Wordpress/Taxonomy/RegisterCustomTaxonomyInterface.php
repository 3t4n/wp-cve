<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\Taxonomy;

/** @internal */
interface RegisterCustomTaxonomyInterface
{
    /**
     * Init process for WordPress
     */
    public function register() : void;
}
