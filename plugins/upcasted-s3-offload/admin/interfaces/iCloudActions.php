<?php

/**
 * Interface iCloud
 */
interface iCloudActions
{
    /**
     * @param string $metaCompare
     * @return int
     */
    public function upcasted_get_number_of_files( string $metaCompare ) : int;
    
    /**
     * @param string $bucket
     * @return mixed
     */
    public function upcasted_init( string $bucket );

}