<?php
    if (! defined('KK_STAR_RATINGS')) {
        http_response_code(404);
        exit();
    }
?>

<?php
    $class = ['kk-star-ratings', $class ?? null];

    if ($reference = (($reference ?? null) ?: null)) {
        $class[] = "kksr-{$reference}";
    }

    if ($align = (($align ?? null) ?: null)) {
        $class[] = "kksr-align-{$align}";
    }

    if ($valign = (($valign ?? null) ?: null)) {
        $class[] = "kksr-valign-{$valign}";
    }

    if ($readonly = (($readonly ?? null) ?: null)) {
        $class[] = 'kksr-disabled';
    }
?>

<div class="<?php echo implode(' ', array_filter(array_map('esc_attr', $class))); ?>"
    data-payload='<?php echo esc_attr(json_encode(array_map('esc_attr', $__payload))); ?>'>
    <?php if (! $legendonly) { ?>
        <?php echo $__view('markup/stars.php'); ?>
    <?php } ?>
    <?php if (! $starsonly) { ?>
        <?php echo $__view('markup/legend.php'); ?>
    <?php } ?>
</div>
