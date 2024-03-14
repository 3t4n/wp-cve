<?php

namespace Mnet;

use Mnet\MnetDbSchema;
use Mnet\Admin\MnetAdTag;
use Mnet\Utils\MnetAdSlot;
use Mnet\Admin\MnetLogManager;
use Mnet\Admin\MnetPluginUtils;
use Mnet\Admin\MnetAdHandleAjaxCalls;
use Mnet\PublicViews\MnetInjectedAdTag;
use Mnet\Admin\MnetOptions;
use Mnet\Utils\MnetAdUtils;

class MnetDbManager
{
    public static $MNET_DB_VERSION = 2.6;
    public static $DB_VERSION_KEY = 'DB_VERSION';
    public static $MNET_TABLE_PREFIX = 'mnet_';

    public static $MNET_AD_TAGS = 'ad_tags';
    public static $MNET_AD_SLOTS = 'ad_slots';
    public static $MNET_AD_PARAGRAPH_MAPPING = 'ad_paragraph_mapping';
    public static $MNET_AD_POST_MAPPING = 'ad_post_mapping';
    public static $MNET_LOG_RETRY = 'log_retry';
    public static $MNET_BLOCKED_URLS = 'blocked_urls';
    public static $MNET_SLOT_BLOCKED_URLS = 'slot_blocked_urls';
    public static $MNET_EXTERNAL_ADS = 'external_ad';

    public static function createTablesOnActivate()
    {
        $stored_version = MnetOptions::getOption(self::$DB_VERSION_KEY);

        if (!$stored_version || $stored_version < self::$MNET_DB_VERSION) {
            self::createUpdateDatabase();
            MnetOptions::saveOption(self::$DB_VERSION_KEY, self::$MNET_DB_VERSION);
            MnetLogManager::logEvent('Activate');
        }
    }

    public static function createUpdateDatabase()
    {
        global $wpdb;
        $errors = array();
        $charset_collate = $wpdb->get_charset_collate();
        $custom_charset_collate = "DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $schema = MnetDbSchema::getSchema();
        foreach ($schema as $name => $columns) {
            $table_name = static::tableName($name);
            $createTableResponse = self::createTable($table_name, $columns, $charset_collate);
            $error = \Arr::get($createTableResponse, 'error');

            if (
                $error !== ""
                && preg_match("/unknown character set/i", $error)
                && $charset_collate !== $custom_charset_collate
            ) {
                $createTableResponse = self::createTable($table_name, $columns, $custom_charset_collate);
                $error = \Arr::get($createTableResponse, 'error');
            }

            if ($error !== "") {
                array_push($errors, array($table_name => $createTableResponse));
            }
        }
        if (count($errors) > 0) {
            MnetAdHandleAjaxCalls::logAdminPageError(array('createTableErrors' => $errors));
        }
    }

    public static function createTable($tableName, $columns, $charset)
    {
        global $wpdb;
        $query = "CREATE TABLE {$tableName} ({$columns}) $charset;";
        \dbDelta($query);
        return [
            'query' => $query,
            'error' => $wpdb->last_error,
        ];
    }

    public static function minimumVersionCheckFailed($requiredVersions = array())
    {
        if (isset($requiredVersions['plugin']) && version_compare(MNET_PLUGIN_VERSION, $requiredVersions['plugin']) === -1) {
            return true;
        }

        // check for php, db, wp versions
        $systemVersions = MnetPluginUtils::getServerInfo();
        foreach ($systemVersions as $sys => $version) {
            if (isset($requiredVersions[$sys]) && version_compare($version, $requiredVersions[$sys]) === -1) return true;
        }
        return false;
    }

    public static function checkDatabaseConfiguration()
    {
        $tablesExist = self::checkTablesExistAndDbVersion();
        if ($tablesExist !== true) { // if error in table creation
            return self::returnResponse($tablesExist);
        }
        $tablesSchema = self::checkTablesColumns();
        if ($tablesSchema !== true) { // if error in table schema
            return self::returnResponse($tablesSchema);
        }
        return self::returnResponse(array("hasError" => false));
    }

    private static function returnResponse($data)
    {
        \wp_send_json(array("status" => "OK", "data" => $data), 200);
    }

    private static function checkTablesExistAndDbVersion()
    {
        global $wpdb;
        $dbVersion = $wpdb->db_version();
        $missingTables = array();

        $wpdbPrefix = "error";
        try {
            $wpdbPrefix = $wpdb->prefix;
        } catch (\Exception $e) {
        }

        $searchString = '%' . self::$MNET_TABLE_PREFIX . '%';
        $mnetTables = $wpdb->get_results("SHOW TABLES LIKE '$searchString'");

        $tableList = [];

        foreach ($mnetTables as $table) {
            foreach ($table as $t) {
                array_push($tableList, $t);
            }
        }

        if (count($tableList) === 0) {
            $missingTables = 'No tables created';
        } else {
            foreach (MnetDbSchema::getSchema() as $tableName => $_) {
                // $tableName = MnetDbManager::tableName($tableName);
                if (empty(preg_grep('/' . $tableName . '/', $tableList))) {
                    array_push($missingTables, $tableName);
                }
            }
        }


        if (empty($missingTables)) {
            return true;
        } else if (self::minimumVersionCheckFailed()) {
            return array(
                "hasError" => true,
                "title" => "MySQL version ${dbVersion} not supported",
                "message" => "Please try upgrading your MySQL database version to version 5.5.5 or above.",
                "showMailOption" => false,
                "type" => "VersionError",
                "missingTables" => $missingTables,
                "mnetTables" => $tableList,
                "wpdbPrefix" => $wpdbPrefix,
            );
        } else {
            return array(
                "hasError" => true,
                "title" => "MySQL table creation failed",
                "message" => "Click <a href='plugins.php?s=media.net+ads+manager&plugin_status=all'>here</a> to reinstall the plugin.",
                "showMailOption" => true,
                "type" => "TableError",
                "missingTables" => $missingTables,
                "mnetTables" => $tableList,
                "wpdbPrefix" => $wpdbPrefix,
            );
        }
    }

    // https://stackoverflow.com/a/9546215
    private static function flatten($array, $prefix = '')
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + self::flatten($value, $prefix . $key . '.');
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }

    // https://stackoverflow.com/a/58827600
    private static function unflatten($data)
    {
        $output = [];
        foreach ($data as $key => $value) {
            $parts = explode('.', $key);
            $nested = &$output;
            while (count($parts) > 1) {
                $nested = &$nested[array_shift($parts)];
                if (!is_array($nested)) {
                    $nested = [];
                }
            }
            $nested[array_shift($parts)] = $value;
        }
        return $output;
    }

    private static function isEqual($s1, $s2)
    {
        // consider current_timestamp and current_timestamp() to be equal, and null and '' to be equal
        return $s1 === $s2 || ($s1 === null && $s2 === "''") || ($s2 === null && $s1 === "''") || (is_string($s1) &&
            is_string($s2) &&
            strtolower(preg_replace('/\(\)$/', '', $s1)) ===
            strtolower(preg_replace('/\(\)$/', '', $s2)));
    }

    public static function checkTablesSchema()
    {
        global $wpdb;

        $diff = [];

        foreach (MnetDbSchema::$schemaDescription as $tableName => $schema) {
            $missing = [];
            $extra = [];
            $different = [];

            $currentSchema = $wpdb->get_results('describe ' . MnetDbManager::tableName($tableName));
            $currentSchema = json_decode(json_encode($currentSchema), true);
            $keyedCurrentSchema = array_column($currentSchema, null, 'Field');

            $keyedSchema = array_column($schema, null, 'Field');

            $flatBase = self::flatten($keyedSchema);
            $flatData = self::flatten($keyedCurrentSchema);

            $baseKeys = array_keys($flatBase);
            $dataKeys = array_keys($flatData);

            $allKeys = array_values(array_unique(array_merge($baseKeys, $dataKeys)));

            foreach ($allKeys as $key) {
                if (!array_key_exists($key, $flatData)) {
                    $missing = array_merge($missing, [$key => $flatBase[$key]]);
                    continue;
                }
                if (!array_key_exists($key, $flatBase)) {
                    $extra = array_merge($extra, [$key => $flatData[$key]]);
                    continue;
                }
                if (!self::isEqual($flatData[$key], $flatBase[$key])) {
                    $different = array_merge($different, [$key => $flatData[$key]]);
                }
            }

            $missing = self::unflatten($missing);
            $extra = self::unflatten($extra);
            $different = self::unflatten($different);

            if (count($missing) > 0 || count($extra) > 0 || count($different) > 0) {
                $diff = array_merge(
                    $diff,
                    [
                        $tableName =>
                        [
                            'missing' => $missing,
                            'extra' => $extra,
                            'different' => $different,
                        ]
                    ]
                );
            }
        }

        if (count($diff) > 0) {
            $diffJson = json_encode($diff);
            MnetAdHandleAjaxCalls::logAdminPageError(array('tableSchemaDiff' => $diffJson));
        }
    }

    private static function checkTablesColumns()
    {
        global $wpdb;
        $incorrectSchemas = array();

        foreach (MnetDbSchema::$schemaDescription as $tableName => $schema) {
            $currentSchema = $wpdb->get_results('describe ' . MnetDbManager::tableName($tableName));
            $currentSchema = json_decode(json_encode($currentSchema), true);
            $keyedCurrentSchema = array_column($currentSchema, null, 'Field');

            $keyedSchema = array_column($schema, null, 'Field');

            $schemaKeys = array_keys($keyedSchema);
            $currentSchemaKeys = array_keys($keyedCurrentSchema);

            $missingColumns = [];
            foreach ($schemaKeys as $schemaKey) {
                if (!in_array($schemaKey, $currentSchemaKeys)) {
                    $missingColumns[] = $schemaKey;
                }
            }
            if (!empty($missingColumns)) {
                $incorrectSchemas = array_merge($incorrectSchemas, array($tableName => $missingColumns));
            }
        }

        if (count($incorrectSchemas) === 0) {
            return true;
        }
        return array(
            "hasError" => true,
            "title" => "MySQL table creation failed",
            "message" => "Click <a href='plugins.php?s=media.net+ads+manager&plugin_status=all'>here</a> to reinstall the plugin.",
            "showMailOption" => true,
            "type" => "SchemaError",
            "missingColumns" => $incorrectSchemas,
        );
    }

    public static function tableName($name)
    {
        global $wpdb;
        if (!isset(MnetDbSchema::getSchema()[$name])) {
            throw new \Exception("Unknown table {$name}");
        }
        return $wpdb->prefix . self::$MNET_TABLE_PREFIX . $name;
    }

    public static function getDbVersion()
    {
        global $wpdb;
        return $wpdb->db_version();
    }

    public static function all($table_name, $columns = "*", $order = false)
    {
        global $wpdb;
        $orderBy = $order ? " order by created_at desc " : "";
        $query = "select $columns from " . static::tableName($table_name) . $orderBy;
        return $wpdb->get_results($query, \ARRAY_A);
    }

    public static function getDataById($table_name, $id, $id_name = 'id', $columns = "*")
    {
        global $wpdb;
        $query = "select $columns from " . static::tableName($table_name) . " where " . $id_name . " = " . intval($id);
        return $wpdb->get_results($query, \ARRAY_A);
    }

    public static function getDataWithClauses($table_name, $clauses)
    {
        global $wpdb;
        $query = "Select * from " . static::tableName($table_name);
        $clause_str = [];
        foreach ($clauses as $key => $value) {
            $clause_str[] = "{$key}=" . (is_string($value) ? "'$value'" : $value);
        }
        if (!empty($clause_str)) {
            $query .= " where " . implode(" AND ", $clause_str);
        }
        return $wpdb->get_results($query, \ARRAY_A);
    }

    public static function getAdTagBySize($sizes)
    {
        global $wpdb;
        $size_list = implode("','", $sizes);
        $size_set = implode(",", $sizes);

        $columns = MnetAdTag::$requiredColumns;

        $query = "Select $columns, concat(width,'x',height) as ad_size from " . static::tableName(self::$MNET_AD_TAGS) . " where concat(width,'x',height) in ('" . $size_list . "') ORDER BY find_in_set(ad_size, '$size_set')";
        $preferredSizeAdtags = $wpdb->get_results($query, \ARRAY_A);
        $query = "Select $columns, concat(width,'x',height) as ad_size from " . static::tableName(self::$MNET_AD_TAGS) . " where concat(width,'x',height) not in ('" . $size_list . "')";
        $remainingSizeAdtags = $wpdb->get_results($query, \ARRAY_A);
        return array_merge($preferredSizeAdtags, $remainingSizeAdtags);
    }

    public static function getAdSlotsByTagId($tag_ids = array())
    {
        global $wpdb;
        $columns = MnetAdSlot::$requiredColumns;
        $query = 'SELECT ' . $columns . ' from ' . static::tableName(self::$MNET_AD_SLOTS) . ' WHERE tag_id in (' . implode(',', $tag_ids) . ')';
        return $wpdb->get_results($query, \ARRAY_A);
    }

    public static function getAdSlots($position = null, $page = null, $tag_types = array(), $exclude_inter_tags = false)
    {
        global $wpdb;
        $columns = MnetAdSlot::$requiredColumns;
        $query = "Select $columns from " . static::tableName(self::$MNET_AD_SLOTS);
        $positionClause = '';
        $pageClause = '';
        $tagTypeClause = '';
        if ($position) {
            if (!is_array($position)) {
                $position = array($position);
            }
            $positionClause = " where position IN ('" . implode("','", array_values(array_filter($position))) . "')";
        }
        if ($page) {
            $pageClause = (!empty($positionClause) ? " AND " : " where ") . "page='{$page}'";
        }
        if (!empty($tag_types)) {
            $tagTypeClause = (!empty($positionClause) || !empty($pageClause) ? " AND " : " where ");
            $tagTypeClause .= "ptype_id in (" . implode(',', $tag_types) . ")";
        }
        if ($exclude_inter_tags) {
            $interAds = array(
                MnetInjectedAdTag::$P_TYPE_INTERSTITIAL_ADS,
                MnetInjectedAdTag::$P_TYPE_MOBILE_ADS,
                MnetInjectedAdTag::$P_TYPE_INTERSTITIAL_MOBILE_ADS
            );
            $tagTypeClause .= " AND ptype_id not in (" . implode(',', $interAds) . ")";
        }
        $query .= $positionClause . $pageClause . $tagTypeClause;
        $slots = $wpdb->get_results($query, \ARRAY_A);
        return array_map(function ($slot) {
            $slot['external_code'] = '';
            if ($slot['ad_type'] == MNET_AD_TYPE_EXTERNAL) {
                $extCode = self::getExternalCode($slot['id']);
                if (!empty($extCode)) {
                    $external_code = $extCode[0];
                    $slot['external_code'] = html_entity_decode($external_code['code']);
                }
            }
            return $slot;
        }, $slots);
    }

    public static function getAdSlotsForPage($page = null, $tag_types = array())
    {
        if ($page === null) {
            $page = MnetAdUtils::getPageType();
        }
        return self::getAdSlots(null, $page, $tag_types);
    }

    public static function getAdSlot($slot_id)
    {
        return self::getDataById(self::$MNET_AD_SLOTS, $slot_id);
    }

    public static function getExternalCode($slot_id)
    {
        return self::getDataById(self::$MNET_EXTERNAL_ADS, $slot_id, 'slot_id');
    }

    public static function updateAdSlot($update_data, $slot_id)
    {
        self::updateData(self::$MNET_AD_SLOTS, $update_data, array('id' => $slot_id));
    }

    public static function insertData($table_name, $data, $has_create_timestamp = false)
    {
        global $wpdb;
        if ($has_create_timestamp) {
            $data['created_at'] = \current_time('mysql');
        }
        $wpdb->insert(static::tableName($table_name), $data);
        return $wpdb->insert_id;
    }

    public static function updateData($table_name, $update_data, $target_row)
    {
        global $wpdb;
        return $wpdb->update(static::tableName($table_name), $update_data, $target_row);
    }

    public static function deleteData($table_name, $target_row)
    {
        global $wpdb;
        return $wpdb->delete(static::tableName($table_name), $target_row);
    }

    public static function getRowCount($table_name, $clause = array())
    {
        global $wpdb;
        $query = "SELECT count(*) as count from " . static::tableName($table_name);
        if (!empty($clause)) {
            $clauseStr = '';
            foreach ($clause as $key => $value) {
                if (!empty($clauseStr)) {
                    $clauseStr .= ' AND ';
                }
                $clauseStr .= $key . '=' . (is_string($value) ? '\'' . $value . '\'' : $value);
            }
            $query .= ' WHERE ' . $clauseStr;
        }
        $count = \Arr::get($wpdb->get_results($query, \ARRAY_A), '0.count', null);
        return !empty($count) ? $count : 0;
    }

    public static function saveAdTags($ad_tags)
    {
        global $wpdb;
        $table_name = static::tableName(self::$MNET_AD_TAGS);
        return array_map(function ($ad_tag) use ($wpdb, $table_name) {
            return $wpdb->insert($table_name, $ad_tag);
        }, $ad_tags);
    }

    public static function saveAdTag($ad_tag)
    {
        global $wpdb;
        $result = self::getDataById(self::$MNET_AD_TAGS, $ad_tag['ad_tag_id'], 'ad_tag_id', 'id');
        if ($wpdb->num_rows) {
            $ad_tag_id = intval($ad_tag['ad_tag_id']);
            unset($ad_tag['ad_tag_id']);
            self::updateData(self::$MNET_AD_TAGS, $ad_tag, array('id' => intval($result[0]['id']), 'ad_tag_id' => $ad_tag_id));
            return $result[0]['id'];
        }
        return self::insertData(self::$MNET_AD_TAGS, $ad_tag, true);
    }

    public static function removeAdSlot($slot_id)
    {
        $slot = self::getAdSlot($slot_id);
        if (!empty($slot)) {
            try {
                self::deleteData(self::$MNET_AD_SLOTS, array('id' => $slot_id));
                self::deleteData(self::$MNET_AD_PARAGRAPH_MAPPING, array('ad_slot_id' => $slot_id));
                self::deleteData(self::$MNET_AD_POST_MAPPING, array('ad_slot_id' => $slot_id));
                self::deleteData(self::$MNET_SLOT_BLOCKED_URLS, array('slot_name' => $slot[0]['page'] . '_' . $slot[0]['position']));
                self::deleteData(self::$MNET_EXTERNAL_ADS, array('slot_id' => $slot_id));
                return true;
            } catch (\Exception $e) {
            }
        }
        return false;
    }

    public static function removeOtherSlotsWithTagId($page, $tagId, $position)
    {
        $adtag = self::getDataWithClauses(self::$MNET_AD_SLOTS, array('page' => $page, 'tag_id' => $tagId));
        $adtag = array_filter($adtag, function ($tag) use ($position) {
            return $tag['position'] != $position;
        });
        if (!empty($adtag)) {
            self::removeAdSlot($adtag[0]['id']);
        }
    }

    public static function getNumberForSlot($type, $slot_id)
    {
        global $wpdb;
        $results = $wpdb->get_results("Select " . $type . "_no from " . static::tableName('ad_' . $type . '_mapping') . " where ad_slot_id=" . $slot_id, \ARRAY_A);
        if ($wpdb->num_rows == 0) {
            return false;
        }
        return $results[0][$type . '_no'];
    }

    public static function clearExpiredAdtagsFromDb($new_ad_tag_ids)
    {
        global $wpdb;
        $clause = count($new_ad_tag_ids) ? ' where ad_tag_id not in (' . implode(',', $new_ad_tag_ids) . ')' : '';
        $query = 'Select id from ' . static::tableName(self::$MNET_AD_TAGS) . $clause;
        $expired_ad_tags = $wpdb->get_results($query, \ARRAY_A);
        foreach ($expired_ad_tags as $ad_tag) {
            $slot_ids = $wpdb->get_results('Select id from ' . static::tableName(self::$MNET_AD_SLOTS) . ' where tag_id =' . $ad_tag['id'], \ARRAY_A);
            foreach ($slot_ids as $slot_id) {
                self::removeAdSlot($slot_id['id']);
            }
            $wpdb->delete(static::tableName(self::$MNET_AD_TAGS), array('id' => $ad_tag['id']));
        }
    }

    public static function insertLog($log)
    {
        global $wpdb;
        $wpdb->insert(
            static::tableName(self::$MNET_LOG_RETRY),
            array(
                'content' => json_encode($log),
                'created_at' => \current_time('mysql')
            ),
            array(
                '%s',
            )
        );
        return $wpdb->insert_id;
    }

    public static function getLogs()
    {
        return self::all(self::$MNET_LOG_RETRY);
    }

    public static function deleteLog($log)
    {
        global $wpdb;
        return $wpdb->delete(static::tableName(self::$MNET_LOG_RETRY), array('id' => $log['id']));
    }

    public static function truncateIfExists($table_name)
    {
        global $wpdb;
        if ($wpdb->get_var("SHOW TABLES LIKE '" . static::tableName($table_name) . "'") == static::tableName($table_name)) {
            $wpdb->query("TRUNCATE TABLE " . static::tableName($table_name));
            return true;
        }
        return false;
    }

    public static function clearDatabase()
    {
        foreach (MnetDbSchema::getSchema() as $key => $value) {
            self::truncateIfExists($key);
        }
    }

    public static function clearSlots()
    {
        $cleared = true;
        $tables = array(
            self::$MNET_AD_SLOTS,
            self::$MNET_SLOT_BLOCKED_URLS,
            self::$MNET_AD_PARAGRAPH_MAPPING,
            self::$MNET_AD_POST_MAPPING,
            self::$MNET_EXTERNAL_ADS
        );
        foreach ($tables as $table) {
            try {
                self::truncateIfExists($table);
            } catch (\Exception $e) {
                $cleared = false;
            }
        }
        return $cleared;
    }

    public static function getAdHeadCodes($ptypes = array())
    {
        if (empty($ptypes)) return array();
        $headCodes = json_decode(MnetOptions::getOption(MnetAdTag::$AD_HEAD_CODE_KEY, "[]"), true);
        $codes = array();
        foreach ($ptypes as $ptype) {
            $codes[] = \Arr::get($headCodes, 'ptype_' . $ptype, '');
        }
        return $codes;
    }

    public static function getAdtagWithPagination($page = 1, $rows = 10, $search = '')
    {
        global $wpdb;
        $table = static::tableName(static::$MNET_AD_TAGS);
        $searchClause = " where name like '%$search%'";
        if (is_numeric($search)) {
            $searchClause .= " OR crid=" . intval($search);
        }

        $orderBy = " order by updated_at desc";

        $query = "Select * from " . $table . $searchClause . $orderBy . " LIMIT " . (($page - 1) * $rows) . "," . $rows;
        $adtags = $wpdb->get_results($query, \ARRAY_A);

        $query = "SELECT count(*) as count from " . $table . $searchClause;
        $count = \Arr::get($wpdb->get_results($query, \ARRAY_A), '0.count', 0);
        $result = [
            'adtags' => $adtags,
            'totalCount' => $count
        ];
        return $result;
    }
}
