<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\Admin;

/** @internal */
interface MenuInterface
{
    /**
     * The function to be called to output the content for this page.
     *
     * @return mixed
     */
    public function render() : void;
}
