<?php

namespace DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \DropshippingXmlFreeVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
