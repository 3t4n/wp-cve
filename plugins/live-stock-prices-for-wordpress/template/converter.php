<?php if($error){ ?>
    <div class="eod_error">Converter: <?= $error ?></div>
<?php }else{ ?>
    <div class="eod_converter <?= $changeable ? 'changeable' : '' ?>"<?= $whitelist ? " data-whitelist='$whitelist'" : '' ?>>
        <?php foreach ($targets_data as $i=>$item){ ?>
        <div class="<?= $i === 0 ? 'first main' : 'second' ?>">
            <label data-type="<?= $item['type'] ?>"><?= $item['code'] ?></label>
            <input type="number" value="<?= $i === 0 ? ($value ?: 1) : '' ?>">
        </div>
        <?php } ?>
    </div>
<?php } ?>
