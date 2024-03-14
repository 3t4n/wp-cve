<?php

class EOD_Fundamental_Data extends EOD_FD
{
    public function __construct( $preset_id ){
        $this->init( 'fundamental', $preset_id );
    }
}