<?php
/**
 * @var EOD_Fundamental_Data $fd
 * @var string $target
 * @var string $key
 * @var string $years
 */
?>

<div class="eod_financials eod_t_<?= $key ?>"
     data-target="<?= $target ?>"
     data-cols="<?= str_replace('->','::', implode(';', $fd->list) ) ?>"
     data-group="<?= str_replace('->','::', $fd->group ) ?>"
     data-simplebar
     <?= isset($years) ? "data-years='$years'" : '' ?>>
    <div class="eod_table">
        <div class="eod_tbody"></div>
    </div>
</div>