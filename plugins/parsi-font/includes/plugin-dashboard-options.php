<form method="post" action="options.php">
    <?php
    settings_fields('dash_font_settings');
    $options = get_option('dash_font_settings');
    $mwfc_fonts = array('B Esfehan', 'B Helal', 'B Homa', 'B Jadid', 'B Koodak', 'B Bardiya', 'B Mahsa', 'B Mehr', 'B Mitra', 'B Nasim', 'B Nazanin', 'B Sina', 'B Titr', 'B Yekan', 'Dast Nevis', 'Droid Arabic Kufi', 'Droid Arabic Naskh', 'Gandom', 'IR Yekan', 'IRANSans', 'IRANYekan', 'IranNastaliq', 'Parastoo', 'Sahel', 'Samim', 'Shabnam', 'Shekasteh', 'Sultan Adan', 'Tahoma', 'Tanha', 'Vazir', 'XM Yekan');
    $mwfc_latinfonts = array('Arial', 'Comic Sans MS', 'Tahoma', 'Verdana');
    $mwfc_combine = [];
    foreach ($mwfc_fonts as $mwfc_font)
      foreach ($mwfc_latinfonts as $mwfc_latinfont)
        $mwfc_combine[] = $mwfc_font . ', ' . $mwfc_latinfont;
    ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e('Font Family', 'mwfc'); ?>
            </th>
            <td>
                <select name="dash_font_settings[dashmwfcfont]" id="dash_font_settings[dashmwfcfont]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                    <optgroup label="<?php _e('Persian Fonts', 'mwfc'); ?>">
                        <?php
                        for ($i = 0; $i < count($mwfc_fonts); $i++) {
                            ?>
                            <option
                                <?php echo($options && $options['dashmwfcfont'] == $mwfc_fonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>"><?= $mwfc_fonts[$i]; ?></option>
                        <?php } ?>
                    </optgroup>
                    <optgroup label="<?php _e('Latin Fonts', 'mwfc'); ?>">
                        <?php
                        for ($i = 0; $i < count($mwfc_latinfonts); $i++) {
                            ?>
                            <option
                                <?php echo($options && $options['dashmwfcfont'] == $mwfc_latinfonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_latinfonts[$i]; ?>"><?= $mwfc_latinfonts[$i]; ?></option>
                        <?php } ?>
                    </optgroup>
                    <optgroup label="<?php _e('Fonts Combination', 'mwfc'); ?>">
                        <?php
                        for ($i = 0; $i < count($mwfc_fonts); $i++) {
                            ?>
                            <?php for ($j = 0; $j < count($mwfc_latinfonts); $j++) {
                                $mwfc_combine = $mwfc_fonts[$i] . ", " . $mwfc_latinfonts[$j];
                                ?>
                                <option
                                    <?php echo($options && $options['dashmwfcfont'] == $mwfc_combine ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>, <?= $mwfc_latinfonts[$j]; ?>"><?= $mwfc_fonts[$i]; ?>
                                    , <?= $mwfc_latinfonts[$j]; ?></option>
                            <?php }
                        } ?>
                    </optgroup>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e('Upload Custom Font', 'mwfc'); ?>
            </th>
            <td>
                <?php _e('This feature is not available in the free version. <a target="_blank" href="https://www.zhaket.com/web/mw-font-changer-pro/?affid=AF-61332c0051cb8">Get MW Font Changer Pro here.</a>', 'mwfc'); ?>
            </td>
        </tr>
    </table>
    <p class="submit2">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'mwfc'); ?>"/>
    </p>
</form>
<br/>
<br/>