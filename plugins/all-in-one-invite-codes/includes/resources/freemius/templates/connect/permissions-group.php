<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'ABSPATH' ) ) { exit; } $permission_group = $VARS; $fs = $permission_group[ 'fs' ]; $permission_manager = FS_Permission_Manager::instance( $fs ); $opt_out_text = $fs->get_text_x_inline( 'Opt Out', 'verb', 'opt-out' ); $opt_in_text = $fs->get_text_x_inline( 'Opt In', 'verb', 'opt-in' ); if ( empty( $permission_group[ 'prompt' ] ) ) { $is_enabled = false; foreach ( $permission_group[ 'permissions' ] as $permission ) { if ( true === $permission[ 'default' ] ) { $is_enabled = true; break; } } } else { $is_enabled = ( isset( $permission_group['is_enabled'] ) && true === $permission_group['is_enabled'] ); } ?>
<div class="fs-permissions-section fs-<?php echo esc_attr( $permission_group[ 'id' ] ) ?>-permissions">
    <div>
        <div class="fs-permissions-section--header">
            <a class="fs-group-opt-out-button"
                data-type="<?php echo esc_attr( $permission_group['type'] ) ?>"
                data-group-id="<?php echo esc_attr( $permission_group[ 'id' ] ) ?>"
                data-is-enabled="<?php echo $is_enabled ? 'true' : 'false' ?>"
                href="#"><?php echo esc_html( $is_enabled ? $opt_out_text : $opt_in_text ) ?></a>
            <span class="fs-permissions-section--header-title"><?php
 echo $permission_group[ 'title' ] ?></span>
        </div>
        <p class="fs-permissions-section--desc"><?php
 echo $permission_group['desc'] ?></p></div>
    <ul>
        <?php
 foreach ( $permission_group['permissions'] as $permission ) { $permission_manager->render_permission( $permission ); } ?>
    </ul>
</div>