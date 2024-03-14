<p>
    Selecciona el formulario a mostrar:
    <label for="widget-optin-list"></label>
    <select name="<?php echo $this->get_field_name('optin_id') ?>" style="width: 100%;">
        <option value=""></option>
        <?php if ($optins->data):
            foreach ($optins->data as $optin):
                ?>
                <option value="<?php echo $optins->request->account ?>:<?php echo $optin->pubId ?>" <?php echo ($optins->request->account . ':' . $optin->pubId == $optin_id) ? 'selected="selected"' : '' ?> >
                    <?php echo $optin->name ?>
                </option>
            <?php endforeach;
        endif;
        ?>
    </select>

    <?php foreach ($optinModes as $k => $v): ?>
<div class="radio">
    <label>
        <input type="radio"
               name="<?php echo $this->get_field_name('optin_mode') ?>" <?= ($k == $optin_mode) ? 'checked="checked"' : '' ?>
               value="<?php echo $k ?>"><?php echo $v ?>
    </label>
</div>
<?php endforeach; ?>

</p>
