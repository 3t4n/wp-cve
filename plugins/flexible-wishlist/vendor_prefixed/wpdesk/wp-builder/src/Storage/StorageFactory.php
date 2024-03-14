<?php

namespace FlexibleWishlistVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \FlexibleWishlistVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
