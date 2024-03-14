<span class="eod_ticker<?= $error ? ' error' : '' ?>">
    <span class="name"><?= $title === false ? $target : $title ?></span>
    <span class="close <?= $type ?> eod_t_<?= $key ?>"
          data-target="<?= $target ?>"
          <?= $ndap === '0' || $ndap ? "data-ndap='$ndap'" : '' ?>
          <?= $ndape === '0' || $ndape ? "data-ndape='$ndape'" : '' ?>>
    </span>
    <span class="evolution eod_t_<?= $key ?>_evol"></span>
</span>