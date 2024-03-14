<?php
    if (! defined('KK_STAR_RATINGS')) {
        http_response_code(404);
        exit();
    }
?>

<?php $font_factor = (float) ($font_factor ?? 1.25); ?>

<div class="kksr-legend" style="font-size: <?php echo $size / $font_factor; ?>px;">
    <?php if ($count) { ?>
        <?php echo esc_html($legend); ?>
    <?php } else { ?>
        <span class="kksr-muted"><?php echo esc_html($greet); ?></span>
    <?php } ?>
</div>
