<?php
/**
 * @var EOD_Fundamental_Data $fd
 * @var string $target
 * @var array $args
 */
?>

<?= $args['before_widget'] ?>

<?php if (!empty($title)){ ?>
    <?= $args['before_title'] . $title . $args['after_title']; ?>
<?php } ?>

    <div class="eod_widget_fundamental">
        <?php
        echo eod_load_template("template/fundamental.php", array(
            'fd'      => $fd,
            'target'  => $target,
            'key'     => str_replace('.', '_', strtolower($target))
        ));
        ?>
    </div>

<?= $args['after_widget']; ?>