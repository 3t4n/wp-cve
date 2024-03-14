<?php
$item = cluevo_get_the_lms_page();

$module = cluevo_get_module((int)$item->module_id);
if (is_dir(cluevo_get_conf_const('CLUEVO_MODULE_DIR') . $module->module_name)) {
  $src = cluevo_get_conf_const('CLUEVO_MODULE_DIR') . $module->module_name;
  $it = new RecursiveDirectoryIterator($src);
  $haystack = [ "index_lms_html5.html", "index.html" ];
  foreach(new RecursiveIteratorIterator($it) as $file) {
    $name = basename($file);
    $dir = $it->getBasename();
    if (in_array($name, $haystack))
      $src = plugin_dir_url($file) . $name;
  }
  $curUser = wp_get_current_user();
  $userId = get_current_user_id();
  if (empty($userId) && $item->login_required) { ?>
    <p class="cluevo cluevo-error-message"><?php esc_html_e("You have to be logged in to access learning content.", "cluevo"); ?></p>
  <?php } else { ?>
  <?php if ($item->access == true) { ?>
    <script type="text/javascript">
    var scormParms = {};
  <?php
    $curUser = wp_get_current_user();
    echo "var scormModule = $item->module_id;\n"; // TODO: this should be encrypted or hashed in some way so the user can't just change this id to cheat variables to an unrelated module
    echo "var itemId = $item->item_id;\n";
    $displayName = (!empty($curUser->display_name)) ? $curUser->display_name : __("Guest", "cluevo");
    echo "scormParms['cmi.learner_name'] = '" . $displayName . "';\n";
    echo "scormParms['cmi.core.student_name'] = '" . $displayName . "';\n";
    if ($userId !== 0) {
      $state = cluevo_get_module_progress($userId, $item->module_id);
      $parameters = [];
      if (!empty($state))
        $parameters = cluevo_get_module_parameters($item->module_id, $userId);
    }
  ?>
    </script>
  <?php
    wp_enqueue_script( 'cluevo-scorm-wrapper' );
    wp_enqueue_script( 'cluevo-scorm-parms' );
    wp_enqueue_script( 'cluevo-scorm' );
  ?>
    <?php if (!empty($item->completed) && $item->completed === true) { ?>
    <p><?php esc_html_e("You have already completed this content.", "cluevo"); ?></p>
  <?php } ?>
  <iframe id="cluevo-module-iframe" class="cluevo-module-iframe" src="<?php echo esc_url($src, ['http', 'https']); ?>"></iframe>
  <?php } else {
      cluevo_display_template('scorm-access-error');
    }
  }
} else {
  echo "<p>" . esc_html_("This module does not seem to exist...", "cluevo") . "</p>";
}
?>
