<?php

namespace WPPayVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \WPPayVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
