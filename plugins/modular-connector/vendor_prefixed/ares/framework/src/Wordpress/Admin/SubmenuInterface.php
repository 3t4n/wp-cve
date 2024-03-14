<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\Admin;

/** @internal */
interface SubmenuInterface
{
    /**
     * The function to be called to output the content for this page.
     *
     * @return mixed
     */
    public function render() : void;
    /**
     * Set slug name for the parent menu
     *
     * @param $parent
     * @return $this
     */
    public function setParent($parent) : self;
}
