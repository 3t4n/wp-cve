<?php

$vafpress_settings = get_option( 'crp_option', array() );
CustomRelatedPosts::get()->helper( 'settings' )->bvs->update_settings( $vafpress_settings );