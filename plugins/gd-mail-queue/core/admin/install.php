<?php

if (!defined('ABSPATH')) { exit; }

function gdmaq_list_database_tables() {
    global $wpdb;

    return array(
        $wpdb->base_prefix.'gdmaq_queue' => 15,
        $wpdb->base_prefix.'gdmaq_emails' => 3,
        $wpdb->base_prefix.'gdmaq_log_email' => 4,
        $wpdb->base_prefix.'gdmaq_log' => 15
    );
}

function gdmaq_install_database() {
    global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)) {
        $charset_collate = "default CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)) {
        $charset_collate.= " COLLATE $wpdb->collate";
    }

    $tables = array(
        'queue' => $wpdb->base_prefix.'gdmaq_queue',
        'emails' => $wpdb->base_prefix.'gdmaq_emails',
        'log_email' => $wpdb->base_prefix.'gdmaq_log_email',
        'log' => $wpdb->base_prefix.'gdmaq_log'
    );

    $query = "CREATE TABLE ".$tables['queue']." (
 id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 blog_id bigint(20) unsigned NOT NULL DEFAULT '0',
 status varchar(64) NOT NULL DEFAULT 'queue' COMMENT 'queue,waiting,ok,fail',
 queued datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 sent datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 type varchar(128) NOT NULL DEFAULT 'mail',
 to_email varchar(191) NOT NULL DEFAULT '',
 to_name varchar(191) NOT NULL DEFAULT '',
 subject varchar(255) NOT NULL DEFAULT '',
 plain longtext,
 html longtext,
 headers longtext,
 attachments longtext,
 extras longtext,
 message varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY  (id),
 KEY status (status),
 KEY type (type),
 KEY blog_id (blog_id),
 KEY to_email (to_email)
) $charset_collate;
CREATE TABLE ".$tables['emails']." (
 id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 email varchar(191) NOT NULL DEFAULT '',
 name varchar(191) NOT NULL DEFAULT '',
 PRIMARY KEY  (id),
 UNIQUE KEY email (email),
 KEY name (name)
) $charset_collate;
CREATE TABLE ".$tables['log_email']." (
 id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 log_id bigint(20) unsigned NOT NULL DEFAULT '0',
 email_id bigint(20) unsigned NOT NULL DEFAULT '0',
 rel varchar(32) NOT NULL DEFAULT '' COMMENT 'to,from,cc,bcc',
 PRIMARY KEY  (id),
 KEY log_id (log_id),
 KEY email_id (email_id),
 KEY rel (rel),
 UNIQUE KEY log (log_id,email_id,rel)
) $charset_collate;
CREATE TABLE ".$tables['log']." (
 id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 blog_id bigint(20) unsigned NOT NULL DEFAULT '0',
 operation varchar(64) NOT NULL DEFAULT 'mail',
 engine varchar(64) NOT NULL DEFAULT 'phpmailer',
 status varchar(64) NOT NULL DEFAULT 'ok' COMMENT 'queue,ok,fail',
 type varchar(128) NOT NULL DEFAULT 'mail',
 logged datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 subject varchar(255) NOT NULL DEFAULT '',
 plain longtext,
 html longtext,
 headers longtext,
 attachments longtext,
 extras longtext,
 mailer longtext,
 message varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY  (id),
 KEY blog_id (blog_id),
 KEY operation (operation),
 KEY engine (engine),
 KEY status (status),
 KEY type (type)
) $charset_collate;";

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');

    return dbDelta($query);
}

function gdmaq_check_database() {
    global $wpdb;

    $result = array();
    $tables = gdmaq_list_database_tables();

    foreach ($tables as $table => $count) {
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $columns = $wpdb->get_results("SHOW COLUMNS FROM $table");

            if ($count != count($columns)) {
                $result[$table] = array("status" => "error", "msg" => __("Some columns are missing.", "gd-mail-queue"));
            } else {
                $result[$table] = array("status" => "ok");
            }
        } else {
            $result[$table] = array("status" => "error", "msg" => __("Table missing.", "gd-mail-queue"));
        }
    }

    return $result;
}

function gdmaq_truncate_database_tables() {
    global $wpdb;

    $tables = array_keys(gdmaq_list_database_tables());

    foreach ($tables as $table) {
        $wpdb->query("TRUNCATE TABLE ".$table);
    }
}

function gdmaq_drop_database_tables() {
    global $wpdb;

    $tables = array_keys(gdmaq_list_database_tables());

    foreach ($tables as $table) {
        $wpdb->query("DROP TABLE IF EXISTS ".$table);
    }
}
