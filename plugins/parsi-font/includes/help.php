<h3>
    <?php _e('IRANSans & IRANYekan Fonts', 'mwfc'); ?>
</h3>
<p>
    <?php _e('As regards, these fonts are not free, we had not put fonts files in this plugin to support copyright law.', 'mwfc'); ?>
</p>
<p>
    <?php _e('For using these fonts you must buy them from <a href="https://fontiran.com/?ref=660">FontIran</a>. After buying both fonts (or one of them), please follow these steps:', 'mwfc'); ?></p>
<p>
    <ol class="mwfcsteps" type="1">
        <li>
            <?php _e('Open zip file you downloaded from your <a href="https://fontiran.com/?ref=660">FontIran</a> panel and extract it.', 'mwfc'); ?></li>
        <li>
            <?php _e('Go to "IRANSans » WebFonts » fonts".', 'mwfc'); ?>
        </li>
        <li>
            <?php _e('Copy all folders (eot, ttf, woff and woff2) to: <p style="direction:ltr;text-align:left;">"wp-content » plugins » parsi-font » assets » fonts » IRANSans"</p>', 'mwfc'); ?>
        </li>
        <li>:)</li>
        <p>*
            <?php _e('For IRANYekan font, repeat the above steps.', 'mwfc'); ?>
        </p>
    </ol>
</p>
<br>
<hr>
<br>
<h3>
    <?php _e('Custom Elements', 'mwfc'); ?>
</h3>
<p>
    <?php _e('For more description (in Persian) about Custom Elements follow this link:', 'mwfc'); ?> <br><code style="float:left;"><a style="color:black;" href="http://yon.ir/classid">http://yon.ir/classid</a></code></p><br><br>
<p>
    <?php _e('You can also follow this image:', 'mwfc'); ?>
</p>
<p>
    <?php
if ( is_rtl() ) {
  echo'<img class="mwfc-responsive" src="' . plugins_url( 'assets/images/classidfa.png', dirname(__FILE__) ) . '">';
} else {
  echo'<img class="mwfc-responsive" src="' . plugins_url( 'assets/images/classiden.png', dirname(__FILE__) ) . '">';
}
?>
</p>
<br>
<hr>
<br>
<h3>
    <?php _e('Problem in applying font', 'mwfc'); ?>
</h3>
<p>
    <?php _e('If you selected a font in the and the it was not applied to a particular section of theme like menu, you need to do one of the following steps:', 'mwfc'); ?>
    </h3>
</p>
<ol>
    <li>
        <?php _e('It may be you have selected a font in theme options. Please Go to Theme Options and deactivate that font.', 'mwfc'); ?>
    </li>
    <li>
        <?php _e('If in theme options a font was not selected, you (or theme designer) may have assigned a font to your desired part of theme in css files. You should search, find and remove that line.', 'mwfc'); ?>
    </li>
</ol>
<p>*
    <?php _e("If you couldn't solve problem, just tell us from 'Feedback' tab.", 'mwfc'); ?>
</p>
<br>
<hr>
<br>
<h3>
    <?php _e('Add Your Custom Fonts', 'mwfc'); ?>
</h3>
<p>
    <?php _e('To do this, you should have a concise understanding of CSS and PHP. Please follow these steps:', 'mwfc'); ?>
</p>
<ol class="mwfcsteps" type="1">
    <li>
        <?php _e('Put your font file(s) in this path:', 'mwfc'); ?>
    </li>
    <p><code style="float:left;">wp-content » plugins » parsi-font » assets » fonts</code></p>
    <br>
    <li>
        <?php _e('Create your font Font-Face and add it at the end of fonts.css file (in fonts directory beside fonts files).', 'mwfc'); ?>
    </li>
    <br>
    <li>
        <?php _e("If you don't know how you should create Font-Face, you can use <a href='https://transfonter.org'>Transfonter</a>. This site convert your font file to other types of font, too.", 'mwfc'); ?></li>
    <br>
    <li>
        <?php _e('Go to "parsi-font » includes" and open "plugin-dashboard-options.php" & "plugin-theme-options.php" files.', 'mwfc'); ?>
    </li>
    <br>
    <li>
        <?php _e('Go to line 5 (in both files).', 'mwfc'); ?>
    </li>
    <br>
    <li>
        <?php _e('Find <code>$mwfc_fonts</code> variable and add your font-family name that you used in font-face to its array.', 'mwfc'); ?></li>
    <br>
    <li>
        <?php _e('If your font is latin (non-rtl font) you should edit <code>$mwfc_latinfonts</code> variable.', 'mwfc'); ?></li>
    <br>
    <li>
        <?php _e('Save files and ... :)', 'mwfc'); ?>
    </li>
</ol>
<br>
<p>*
    <?php _e('In next updates your changes will be lost. To avoid this problem please send us font name (or font file) from "Feedback" tab. We will add it to plugin in next update.', 'mwfc'); ?>
</p>
<br>
<hr>
<br>
<h3>
    <?php _e('Problem in Persian or English Digits', 'mwfc'); ?>
</h3>
<p>
    <?php _e("This problem depends on the font file. If you have a Persian digits version of the font that we don't have used it in the plugin, please send us it from 'Feedback' tab.", 'mwfc'); ?>
</p>