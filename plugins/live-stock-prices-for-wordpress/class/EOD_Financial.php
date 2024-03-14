<?php

class EOD_Financial extends EOD_FD
{
    public function __construct( $preset_id ){
        $this->init( 'financial', $preset_id );
    }
}