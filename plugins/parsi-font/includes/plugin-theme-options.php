<form method="post" action="options.php">
  <?php
  settings_fields('site_font_settings');
  $options = get_option('site_font_settings');
  $mwfc_fonts = array('B Esfehan', 'B Helal', 'B Homa', 'B Jadid', 'B Koodak', 'B Bardiya', 'B Mahsa', 'B Mehr', 'B Mitra', 'B Nasim', 'B Nazanin', 'B Sina', 'B Titr', 'B Yekan', 'Dast Nevis', 'Droid Arabic Kufi', 'Droid Arabic Naskh', 'Gandom', 'IR Yekan', 'IRANSans', 'IRANYekan', 'IranNastaliq', 'Parastoo', 'Sahel', 'Samim', 'Shabnam', 'Shekasteh', 'Sultan Adan', 'Tahoma', 'Tanha', 'Vazir', 'XM Yekan');
  $mwfc_latinfonts = array('Arial', 'Comic Sans MS', 'Tahoma', 'Verdana');
  $mwfc_combine = [];
  foreach ($mwfc_fonts as $mwfc_font)
    foreach ($mwfc_latinfonts as $mwfc_latinfont)
      $mwfc_combine[] = $mwfc_font . ', ' . $mwfc_latinfont;
  ?>
    <table class="form-table">
        <p>
        <h3>
          <?php _e('Heading Font', 'mwfc'); ?>
        </h3>
        <h4>h1, h2, h3, h4, h5, h6</h4>
        </p>
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Family', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[hfontname]" id="site_font_settings[hfontname]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                    <optgroup label="<?php _e('Persian Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_fonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['hfontname'] == $mwfc_fonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>"><?= $mwfc_fonts[$i]; ?></option>
                      <?php } ?>
                    </optgroup>
                    <optgroup label="<?php _e('Latin Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_latinfonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['hfontname'] == $mwfc_latinfonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_latinfonts[$i]; ?>"><?= $mwfc_latinfonts[$i]; ?></option>
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
                                <?php echo($options && $options['hfontname'] == $mwfc_combine ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>, <?= $mwfc_latinfonts[$j]; ?>"><?= $mwfc_fonts[$i]; ?>
                                  , <?= $mwfc_latinfonts[$j]; ?></option>
                        <?php }
                      } ?>
                    </optgroup>
                </select>
                <p class="description">
                  <?php _e('Please select font family.', 'mwfc'); ?>
                </p>
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
    <hr/>
    <table class="form-table">
        <h3>
          <?php _e('Body Font', 'mwfc'); ?>
        </h3>
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Family', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[bodyfontname]" id="site_font_settings[bodyfontname]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                    <optgroup label="<?php _e('Persian Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_fonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['bodyfontname'] == $mwfc_fonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>"><?= $mwfc_fonts[$i]; ?></option>
                      <?php } ?>
                    </optgroup>
                    <optgroup label="<?php _e('Latin Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_latinfonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['bodyfontname'] == $mwfc_latinfonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_latinfonts[$i]; ?>"><?= $mwfc_latinfonts[$i]; ?></option>
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
                                <?php echo($options && $options['bodyfontname'] == $mwfc_combine ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>, <?= $mwfc_latinfonts[$j]; ?>"><?= $mwfc_fonts[$i]; ?>
                                  , <?= $mwfc_latinfonts[$j]; ?></option>
                        <?php }
                      } ?>
                    </optgroup>
                </select>
                <p class="description">
                  <?php _e('Please select font family.', 'mwfc'); ?>
                </p>
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
    <hr/>
    <table class="form-table">
        <h3>
          <?php _e('Adminbar Font', 'mwfc'); ?>
        </h3>
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Family', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[adminfontname]" id="site_font_settings[adminfontname]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                    <optgroup label="<?php _e('Persian Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_fonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['adminfontname'] == $mwfc_fonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>"><?= $mwfc_fonts[$i]; ?></option>
                      <?php } ?>
                    </optgroup>
                    <optgroup label="<?php _e('Latin Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_latinfonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['adminfontname'] == $mwfc_latinfonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_latinfonts[$i]; ?>"><?= $mwfc_latinfonts[$i]; ?></option>
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
                                <?php echo($options && $options['adminfontname'] == $mwfc_combine ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>, <?= $mwfc_latinfonts[$j]; ?>"><?= $mwfc_fonts[$i]; ?>
                                  , <?= $mwfc_latinfonts[$j]; ?></option>
                        <?php }
                      } ?>
                    </optgroup>
                </select>
                <p class="description">
                  <?php _e('Please select font family.', 'mwfc'); ?>
                </p>
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
    <hr/>
    <table class="form-table">
        <h3>
          <?php _e('Custom Elements 1', 'mwfc'); ?>
        </h3>
        <tr valign="top">
            <th scope="row">
              <?php _e('Enter theme classes and ids. You can separate theme with latin comma (,).', 'mwfc'); ?>
            </th>
            <td><textarea name="site_font_settings[c1listidclass]" style="direction:ltr;" cols="60" rows="5"
                          id="site_font_settings[c1listidclass]"
                          class="regular-text"><?php if ($options) esc_attr_e($options['c1listidclass']); ?></textarea>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Family', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[c1fontname]" id="site_font_settings[c1fontname]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                    <optgroup label="<?php _e('Persian Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_fonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['c1fontname'] == $mwfc_fonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>"><?= $mwfc_fonts[$i]; ?></option>
                      <?php } ?>
                    </optgroup>
                    <optgroup label="<?php _e('Latin Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_latinfonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['c1fontname'] == $mwfc_latinfonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_latinfonts[$i]; ?>"><?= $mwfc_latinfonts[$i]; ?></option>
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
                                <?php echo($options && $options['c1fontname'] == $mwfc_combine ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>, <?= $mwfc_latinfonts[$j]; ?>"><?= $mwfc_fonts[$i]; ?>
                                  , <?= $mwfc_latinfonts[$j]; ?></option>
                        <?php }
                      } ?>
                    </optgroup>
                </select>
                <p class="description">
                  <?php _e('Please select font family.', 'mwfc'); ?>
                </p>
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
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Size', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[c1fontsize]" id="site_font_settings[c1fontsize]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                  <?php for ($i = 5; $i < 61; $i++) { ?>
                      <option
                        <?php echo($options && $options['c1fontsize'] == $i ? "selected " : ""); ?>value="<?php echo $i; ?>"><?php echo $i; ?></option>
                  <?php } ?>
                </select>
                <p class="description">
                  <?php _e('Please select font size.', 'mwfc'); ?>
                </p>
            </td>
        </tr>
    </table>
    <hr/>

    <table class="form-table">
        <h3>
          <?php _e('Custom Elements 2', 'mwfc'); ?>
        </h3>
        <tr valign="top">
            <th scope="row">
              <?php _e('Enter theme classes and ids. You can separate theme with latin comma (,).', 'mwfc'); ?>
            </th>
            <td><textarea name="site_font_settings[c2listidclass]" cols="60" rows="5" style="direction:ltr;"
                          id="site_font_settings[c2listidclass]"
                          class="regular-text"><?php if ($options) esc_attr_e($options['c2listidclass']); ?></textarea>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Family', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[c2fontname]" id="site_font_settings[c2fontname]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                    <optgroup label="<?php _e('Persian Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_fonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['c2fontname'] == $mwfc_fonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>"><?= $mwfc_fonts[$i]; ?></option>
                      <?php } ?>
                    </optgroup>
                    <optgroup label="<?php _e('Latin Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_latinfonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['c2fontname'] == $mwfc_latinfonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_latinfonts[$i]; ?>"><?= $mwfc_latinfonts[$i]; ?></option>
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
                                <?php echo($options && $options['c2fontname'] == $mwfc_combine ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>, <?= $mwfc_latinfonts[$j]; ?>"><?= $mwfc_fonts[$i]; ?>
                                  , <?= $mwfc_latinfonts[$j]; ?></option>
                        <?php }
                      } ?>
                    </optgroup>
                </select>
                <p class="description">
                  <?php _e('Please select font family.', 'mwfc'); ?>
                </p>
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
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Size', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[c2fontsize]" id="site_font_settings[c2fontsize]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                  <?php for ($i = 5; $i < 61; $i++) { ?>
                      <option
                        <?php echo($options && $options['c2fontsize'] == $i ? "selected " : ""); ?>value="<?php echo $i; ?>"><?php echo $i; ?></option>
                  <?php } ?>
                </select>
                <p class="description">
                  <?php _e('Please select font size.', 'mwfc'); ?>
                </p>
            </td>
        </tr>
    </table>
    <hr/>
    <table class="form-table">
        <h3>
          <?php _e('Custom Elements 3', 'mwfc'); ?>
        </h3>
        <tr valign="top">
            <th scope="row">
              <?php _e('Enter theme classes and ids. You can separate theme with latin comma (,).', 'mwfc'); ?>
            </th>
            <td><textarea name="site_font_settings[c3listidclass]" cols="60" rows="5" style="direction:ltr;"
                          id="site_font_settings[c3listidclass]"
                          class="regular-text"><?php if ($options) esc_attr_e($options['c3listidclass']); ?></textarea>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Family', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[c3fontname]" id="site_font_settings[c3fontname]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                    <optgroup label="<?php _e('Persian Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_fonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['c3fontname'] == $mwfc_fonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>"><?= $mwfc_fonts[$i]; ?></option>
                      <?php } ?>
                    </optgroup>
                    <optgroup label="<?php _e('Latin Fonts', 'mwfc'); ?>">
                      <?php
                      for ($i = 0; $i < count($mwfc_latinfonts); $i++) {
                        ?>
                          <option
                            <?php echo($options && $options['c3fontname'] == $mwfc_latinfonts[$i] ? "selected " : ""); ?>value="<?= $mwfc_latinfonts[$i]; ?>"><?= $mwfc_latinfonts[$i]; ?></option>
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
                                <?php echo($options && $options['c3fontname'] == $mwfc_combine ? "selected " : ""); ?>value="<?= $mwfc_fonts[$i]; ?>, <?= $mwfc_latinfonts[$j]; ?>"><?= $mwfc_fonts[$i]; ?>
                                  , <?= $mwfc_latinfonts[$j]; ?></option>
                        <?php }
                      } ?>
                    </optgroup>
                </select>
                <p class="description">
                  <?php _e('Please select font family.', 'mwfc'); ?>
                </p>
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
        <tr valign="top">
            <th scope="row">
              <?php _e('Font Size', 'mwfc'); ?>
            </th>
            <td>
                <select name="site_font_settings[c3fontsize]" id="site_font_settings[c3fontsize]">
                    <option value=""><?php _e('None', 'mwfc'); ?></option>
                  <?php for ($i = 5; $i < 61; $i++) { ?>
                      <option
                        <?php echo($options && $options['c3fontsize'] == $i ? "selected " : ""); ?>value="<?php echo $i; ?>"><?php echo $i; ?></option>
                  <?php } ?>
                </select>
                <p class="description">
                  <?php _e('Please select font size.', 'mwfc'); ?>
                </p>
            </td>
        </tr>
    </table>
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'mwfc'); ?>"/>
    </p>
</form>
<br/>
<br/>