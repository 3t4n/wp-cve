<?= $args['before_widget'] ?>

<?php if (!empty($title)){ ?>
    <?= $args['before_title'] . $title . $args['after_title']; ?>
<?php } ?>

    <div class="eod_widget_converter">
        <?php echo eod_shortcode_converter($props, null, 'eod_converter'); ?>
    </div>

<?= $args['after_widget']; ?>