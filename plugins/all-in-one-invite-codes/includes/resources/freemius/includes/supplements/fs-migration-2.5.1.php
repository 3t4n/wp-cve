<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( ! function_exists( 'fs_migrate_251' ) ) { function fs_migrate_251( Freemius $fs, $install_by_blog_id ) { $permission_manager = FS_Permission_Manager::instance( $fs ); foreach ( $install_by_blog_id as $blog_id => $install ) { if ( true === $install->is_disconnected ) { $permission_manager->update_site_tracking( false, ( 0 == $blog_id ) ? null : $blog_id, true ); } } } }