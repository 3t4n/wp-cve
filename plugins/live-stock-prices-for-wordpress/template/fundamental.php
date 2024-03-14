<?php
/**
 * @var EOD_Fundamental_Data $fd
 * @var string $target
 * @var string $key
 */
?>

<?php if( $fd->has_errors() ){ ?>
    <div class="eod_error">Fundamental data: <?= $fd->get_errors() ?></div>
<?php } else if (count($fd->list)){ ?>
    <ul class="eod_fd_list eod_t_<?= $key ?>" data-target="<?= $target ?>">
        <?php foreach($fd->list as $slug){ ?>
            <?php $fd_item = $fd->get_item( $slug ); ?>
            <li data-slug="<?= str_replace('->','::',$slug) ?>" <?= isset($fd_item['type']) ? "data-type='{$fd_item['type']}'" : '' ?>>
                <b><?= $fd->get_item_title( $slug ) ?>: </b>
            </li>
        <?php } ?>
    </ul>
<?php } ?>