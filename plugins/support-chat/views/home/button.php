<?php

$detect = new \WpSaioMobileDetect();

$is_mobile = $detect->isMobile();

$from_db = get_option('njt_wp_saio', null);

$tooltip_type = get_option('wpsaio_tooltip', 'appname');

if ($is_mobile && $app === 'line') {
    $lineUrl = $from_db['line']['params']['url'];
?>
    <div class="nt-aio-item js__nt_aio_item" data-target="#nt-aio-popup-<?php echo $app; ?>" data-is-mobile='<?php echo $is_mobile ?>'>
        <a href="<?php echo $lineUrl; ?>" target="_blank" class='line-mobile-link'></a>
        <div class="nt-aio-item-icon nt-aio-<?php echo $app; ?>"></div>

        <!-- /.nt-aio-item-icon nt-aio- -->
        <div class="nt-aio-item-txt"><?php echo $tooltip_type === "appname" ? $title : $content; ?></div>
        <!-- /.nt-aio-item-txt -->
        </a>
    </div>
<?php
} else if ($app === 'custom-app') { ?>
      <div class="nt-aio-item js__nt_aio_item" data-target="#nt-aio-popup-<?php echo $app; ?>" data-is-mobile='<?php echo $is_mobile ?>'>
        <div class="nt-aio-item-icon nt-aio-<?php echo $app; ?>" data-appname='<?php echo $app ?>' data-coloricon='<?php echo $args['color-icon'] ?>' data-urlicon='<?php echo $args['url-icon'] ?>'></div>
        <!-- /.nt-aio-item-icon nt-aio- -->
        <div class="nt-aio-item-txt" data-title="<?php echo $args['custom-app-title'] !== '' ? $args['custom-app-title'] : $title ?>" data-content="<?php echo $content ?>">
            <?php echo $tooltip_type === "appname" ? ($args['custom-app-title'] !== '' ? $args['custom-app-title'] : $title) : $content; ?>
        </div>
        <!-- /.nt-aio-item-txt -->
    </div>
<?php } else { ?>

    <div class="nt-aio-item js__nt_aio_item" data-target="#nt-aio-popup-<?php echo $app; ?>" data-is-mobile='<?php echo $is_mobile ?>'>
        <div class="nt-aio-item-icon nt-aio-<?php echo $app; ?>" data-appname='<?php echo $app ?>' data-coloricon='<?php echo $args['color-icon'] ?>' data-urlicon='<?php echo $args['url-icon'] ?>'></div>
        <!-- /.nt-aio-item-icon nt-aio- -->
        <div class="nt-aio-item-txt" data-title="<?php echo $title ?>" data-content="<?php echo $content ?>">
            <?php echo $tooltip_type === "appname" ? $title : $content; ?>
        </div>
        <!-- /.nt-aio-item-txt -->
    </div>
<?php }
?>