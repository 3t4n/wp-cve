<?php
if (!defined("CLUEVO_ACTIVE")) exit;
// update database after updates
add_action( 'plugins_loaded', 'cluevo_update_db_check' );

function cluevo_update_db_check() {
  $curDatabaseVersion = get_option(CLUEVO_DB_VERSION_OPT_KEY);

  if (version_compare($curDatabaseVersion, CLUEVO_PLUGIN_DB_VERSION) === -1) {
    cluevo_create_database();
  }
}
?>
