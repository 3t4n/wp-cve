<span class="eod_ticker<?= $error ? ' error' : '' ?>">
    <span class="name"><?= $title === false ? $target : $title ?></span>
    <?php if($error){ ?>
        <span data-target="<?= $target ?>"><?= $error ?></span>
    <?php }else{ ?>
        <span class="eod_live eod_realtime eod_t_<?= $key ?>"
              data-target="<?= $target ?>"
              <?= $ndap === '0' || $ndap ? "data-ndap='$ndap'" : '' ?>>
        </span>
    <?php } ?>
</span>
