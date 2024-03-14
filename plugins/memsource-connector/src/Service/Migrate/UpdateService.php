<?php

namespace Memsource\Service\Migrate;

use Memsource\Utils\DatabaseUtils;
use Memsource\Utils\SystemUtils;

class UpdateService
{
    private static $versionHistory = ['2.0'];

    public function updateDatabase($lastVersion)
    {
        global $wpdb;
        foreach (self::$versionHistory as $version) {
            if (version_compare($lastVersion, $version, '<')) {
                $fileName = SystemUtils::getSqlUpdateFile($version);
                if (file_exists($fileName)) {
                    $handle = fopen($fileName, "r");
                    if ($handle) {
                        while (($line = fgets($handle)) !== false) {
                            $wpdb->query(str_replace("{wp_db_prefix}", $wpdb->prefix, $line));
                        }
                        fclose($handle);
                    }
                }
                $fileName = SystemUtils::getJsonUpdateFile($version);
                if (file_exists($fileName)) {
                    $jsonObject = json_decode(file_get_contents($fileName));
                    foreach ($jsonObject->shortCodes as $shortcodes) {
                        $codeType = $shortcodes->type;
                        foreach ($shortcodes->values as $shortcode) {
                            $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODES;
                            $sql = $wpdb->prepare("select * from {$tableName} where type = %s and tag = %s", [$codeType, $shortcode->tag]);
                            if (!$wpdb->get_row($sql)) {
                                $wpdb->insert($tableName, [
                                    'type' => $codeType,
                                    'tag' => $shortcode->tag,
                                    'ignore_body' => isset($shortcode->ignoreBody) && $shortcode->ignoreBody,
                                ]);
                                if (isset($shortcode->attributes)) {
                                    $shortcodeId = $wpdb->insert_id;
                                    $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODE_ATTRIBUTES;
                                    foreach ($shortcode->attributes as $attribute) {
                                        $wpdb->insert($tableName, [
                                            'short_code_id' => $shortcodeId,
                                            'name' => $attribute->name,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
