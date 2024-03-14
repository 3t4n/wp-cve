<?php

global $wpdb;
$table_name_process = $wpdb->prefix . 'lpagery_process';
$table_name_process_post = $wpdb->prefix . 'lpagery_process_post';
$wpdb_collate = $wpdb->collate;

function lpagery_table_exists_migrate(string $table_name_process)
{
    global $wpdb;
    $prepare = $wpdb->prepare("SELECT EXISTS (
                SELECT
                    TABLE_NAME
                FROM
                    information_schema.TABLES
                WHERE
                        TABLE_NAME = %s
            ) as lpagery_table_exists;", $table_name_process);
    $process_table_exists = $wpdb->get_results($prepare)[0]->lpagery_table_exists;
    return  $process_table_exists;
}

$process_table_exists = lpagery_table_exists_migrate($table_name_process);

$process_post_table_exists = lpagery_table_exists_migrate($table_name_process_post);

$sql_process =
    "CREATE TABLE {$table_name_process} (
                id      bigint auto_increment     not null ,
			    post_id bigint   not null,
			    user_id bigint   not null,
			    purpose text, 
			    created timestamp not null default '0000-00-00 00:00:00',
			    data  longtext,
			    primary key  (id),
			     key  post_id(post_id) ,
			     key  user_id(user_id) 
            )
            COLLATE {$wpdb_collate}";

$sql_process_post =
    "CREATE TABLE  {$table_name_process_post} (
                id bigint  auto_increment not null,
			    lpagery_post_id bigint not null,
			    lpagery_process_id bigint not null,
			    post_id            bigint not null,
			    created            timestamp not null default '0000-00-00 00:00:00',
			    modified           timestamp not null default '0000-00-00 00:00:00',
			    data  longtext,
			    primary key  (id),
			     key  lpagery_process_id(lpagery_process_id) ,
			     key  post_id(post_id),
			    key lpagery_post_id(lpagery_post_id)
            )
            COLLATE {$wpdb_collate}";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
if (!$process_table_exists) {
    $wpdb->query($sql_process);
}
if (!$process_post_table_exists) {
    $wpdb->query($sql_process_post);
}

$db_version = intval(get_option("lpagery_database_version", 0));
if ($db_version < 2 || !$process_post_table_exists) {

    try {
        $wpdb->query("alter table $table_name_process_post add column  replaced_slug text");
    } catch (Throwable $e) {

    }


    $dataResults = $wpdb->get_results("select id, data from $table_name_process p");
    foreach ($dataResults as $result) {
        if (!$result->data) {
            continue;
        }
        $unserialized_data = maybe_unserialize($result->data);
        if (!$unserialized_data) {
            continue;
        }

        $slug = isset($unserialized_data["slug"]) ? ($unserialized_data["slug"]) : null;
        if (!$slug) {
            continue;
        }
        $slug = LPageryCustomSanitizer::lpagery_sanitize_title_with_dashes($slug);
        $process_id = $result->id;

        $process_post_results = $wpdb->get_results($wpdb->prepare("select id,data FROM $table_name_process_post where lpagery_process_id = %s ", $process_id));
        foreach ($process_post_results as $process_post_result) {
            $process_post_data = maybe_unserialize($process_post_result->data);
            if (!$process_post_result->data) {
                continue;
            }
            if (!$process_post_data) {
                continue;
            }
            $params = LPageryInputParamProvider::lpagery_get_input_params_without_images($process_post_data);
            $replaced_slug = sanitize_title(LPagerySubstitutionHandler::lpagery_substitute($params, $slug));
            $wpdb->query($wpdb->prepare("update $table_name_process_post set replaced_slug = %s where id = %s and replaced_slug is null", $replaced_slug, $process_post_result->id));
        }

    }

    try {
        $sql = "alter table $table_name_process_post drop key if exists lpagery_uq_lpagery_post_id_process_id;";
        $wpdb->query($sql);
    } catch (Throwable $e) {

    }
    try {
        $sql = "alter table $table_name_process_post drop column lpagery_post_id;";
        $wpdb->query($sql);
        $wpdb->query("alter table $table_name_process 
        add column  google_sheet_data longtext,
        add column  google_sheet_sync_status text,
        add column  google_sheet_sync_error longtext,
        add column  google_sheet_sync_enabled boolean,
        add column  last_google_sheet_sync timestamp,
        add column  config_changed boolean");
    } catch (Throwable $e) {

    }
    $table_exists = lpagery_table_exists_migrate($table_name_process);
    if($table_exists) {
        update_option("lpagery_database_version", 2);
    }

}
$db_version = intval(get_option("lpagery_database_version", 0));

if ($db_version < 3 && lpagery_table_exists_migrate($table_name_process)) {
    $wpdb->query("alter table $table_name_process_post add column config text");
    $wpdb->query("alter table $table_name_process_post add column lpagery_settings text");
    $wpdb->query("alter table $table_name_process drop column config_changed");
    update_option("lpagery_database_version", 3);
}