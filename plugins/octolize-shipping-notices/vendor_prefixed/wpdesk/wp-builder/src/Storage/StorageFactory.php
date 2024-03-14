<?php

namespace OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
