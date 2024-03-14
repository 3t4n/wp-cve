<?php
    
    defined('ABSPATH') or die();
    require_once
    WL_COMPANION_PLUGIN_DIR_PATH
    . 'admin/inc/helpers/wl-companion-helper.php' ;

    $class = wl_companion_helper::wl_check_for_options_data();
if ($class != 'disabled') { ?>
    <div class="enigma-import-export">
        <div class="jumbotron">
            <h3><?php esc_html_e('Import Your Previous Data From Older versions( below 4.0.1 ) to New Version.', WL_COMPANION_DOMAIN); ?></h3>
            <p class="import_caption">
                <?php esc_html_e('Just click the below button to import old data. If your old data detected then it will be imported to new version.', WL_COMPANION_DOMAIN); ?>
            </p>
            <p>
                <form action="" method="post" accept-charset="utf-8">
                    <input class="btn btn-import-enigma" type="submit" name="import_submit" value="Import Data">
                </form>
            </p>
        </div>
    </div><?php
} else { ?>
<div class="enigma-import-export danger">
    <div class="jumbotron">
          <h3><?php esc_html_e('Sorry.!!, On your wordpress didn\'t find any previous version theme data from "Weblizar".', WL_COMPANION_DOMAIN); ?></h3>
    </div>
</div>
<?php } ?>
<div class="enigma-import-export">
    <div class="jumbotron">
          <h3><?php esc_html_e('Export Your Free Theme Data and Import to your Pro Theme.', WL_COMPANION_DOMAIN); ?></h3>
          <p class="import_caption">
              <?php esc_html_e('Just click the below button to export old data. If your old data detected then it will be exported.', WL_COMPANION_DOMAIN); ?>
          </p>
          <p>
              <form action="" method="post" accept-charset="utf-8">
                <input class="btn btn-import-enigma" type="submit" name="export_submit" value="Export Data">
            </form>
        </p>
    </div>
</div>
<?php
if (isset($_REQUEST['import_submit'])) {
    foreach (wl_companion_helper::wl_get_option_name() as $key => $value) {
        set_theme_mod($key, $value);
    }
    echo wp_kses_post('<p class="import_successfully">'.esc_html__("Your Previous Version Theme Data is imported successfully.!", WL_COMPANION_DOMAIN).'</p>');
} elseif (isset($_REQUEST['export_submit'])) {
    $result = wl_companion_helper::wl_get_export_data();
    if (! empty($result)) {
        echo wp_kses_post(
            '<p class="import_successfully">'.esc_html__("Your data is exported successfully.! Now Install & Activate Pro Version then Go to Appearance->Import/Export->Click Import Button.<br> Before doing this remember to Install & Activate 'Weblizar Companion' Plugin.", WL_COMPANION_DOMAIN).'</p>
			<p>'.esc_html__("Copy the below code.!!", WL_COMPANION_DOMAIN).'</p>
			<p class="result_export_textarea"><textarea rows="5">'.$result.'</textarea></p>'
        );
    } else {
        echo wp_kses_post('<p class="import_failed">'.esc_html__("Your data is not generated.!! Something went wrong.!!", WL_COMPANION_DOMAIN).'</p>');
    }
}