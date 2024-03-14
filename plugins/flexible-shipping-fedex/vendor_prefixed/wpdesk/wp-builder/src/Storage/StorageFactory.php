<?php

namespace FedExVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \FedExVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
