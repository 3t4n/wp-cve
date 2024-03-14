<h3 style="margin-top: 0;"><?php esc_html_e("Database Tables", "gd-mail-queue"); ?></h3>
<?php

require_once(GDMAQ_PATH.'core/admin/install.php');

$list_db = gdmaq_install_database();

if (!empty($list_db)) {
    echo '<h4>'.__("Upgrade Notices", "gd-mail-queue").'</h4>';
    echo join('<br/>', $list_db);
}

echo '<h4>'.__("Tables Check", "gd-mail-queue").'</h4>';
$check = gdmaq_check_database();

$msg = array();
foreach ($check as $table => $data) {
    if ($data['status'] == 'error') {
        $_proceed = false;
        $_error_db = true;

        $msg[] = '<span class="gdpc-error">['.__("ERROR", "gd-mail-queue").'] - <strong>'.$table.'</strong>: '.$data['msg'].'</span>';
    } else {
        $msg[] = '<span class="gdpc-ok">['.__("OK", "gd-mail-queue").'] - <strong>'.$table.'</strong></span>';
    }
}

echo join('<br/>', $msg);
