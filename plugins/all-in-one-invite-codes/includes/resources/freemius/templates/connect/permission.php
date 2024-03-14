<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'ABSPATH' ) ) { exit; } $permission = $VARS; $is_permission_on = ( ! isset( $permission['default'] ) || true === $permission['default'] ); ?>
<li id="fs_permission_<?php echo esc_attr( $permission['id'] ) ?>" data-permission-id="<?php echo esc_attr( $permission['id'] ) ?>"
    class="fs-permission fs-<?php echo esc_attr( $permission['id'] ); ?><?php echo ( ! $is_permission_on ) ? ' fs-disabled' : ''; ?>">
    <i class="<?php echo esc_attr( $permission['icon-class'] ); ?>"></i>
    <?php if ( isset( $permission['optional'] ) && true === $permission['optional'] ) : ?>
        <div class="fs-switch fs-small fs-round fs-<?php echo $is_permission_on ? 'on' : 'off' ?>">
            <div class="fs-toggle"></div>
        </div>
    <?php endif ?>

    <div class="fs-permission-description">
        <span<?php if ( ! empty( $permission['tooltip'] ) ) : ?> class="fs-tooltip-trigger"<?php endif ?>><?php echo esc_html( $permission['label'] ); ?><?php if ( ! empty( $permission['tooltip'] ) ) : ?><i class="dashicons dashicons-editor-help"><span class="fs-tooltip" style="width: 200px"><?php echo esc_html( $permission['tooltip'] ) ?></span></i><?php endif ?></span>

        <p><?php echo esc_html( $permission['desc'] ); ?></p>
    </div>
</li>