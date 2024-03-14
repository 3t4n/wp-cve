<?php

namespace UpsFreeVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \UpsFreeVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
