<?php
namespace Mnet;
class MnetDbSchema
{
    public static function getSchema()
    {
        return array_map(function ($description) {
            $schema = "";
            $primaryKey = "";

            foreach ($description as $columnDescription) {
                $schemaColumn = [];

                $schemaColumn[] = $columnDescription['Field'];
                $schemaColumn[] = $columnDescription['Type'];

                if (strtolower($columnDescription['Key']) === "pri") {
                    $primaryKey = $columnDescription['Field'];
                }

                if (strtolower($columnDescription['Null']) === "no") {
                    $schemaColumn[] = "NOT NULL";
                }

                if (
                    strtolower($columnDescription['Default']) === "current_timestamp"
                ) {
                    $schemaColumn[] = "DEFAULT CURRENT_TIMESTAMP";
                } elseif ($columnDescription['Default'] === "''") {
                    $schemaColumn[] = "DEFAULT \"\"";
                } elseif ($columnDescription['Default'] === "0000-00-00 00:00:00") {
                    // ignored
                } elseif (!is_null($columnDescription['Default'])) {
                    $schemaColumn[] = "DEFAULT ${columnDescription['Default']}";
                }

                if (strtolower($columnDescription['Extra']) === "auto_increment") {
                    $schemaColumn[] = "AUTO_INCREMENT";
                } elseif (
                    strtolower($columnDescription['Extra']) === "on update current_timestamp"
                ) {
                    $schemaColumn[] = "ON UPDATE CURRENT_TIMESTAMP";
                } elseif ($columnDescription['Extra']) {
                    $schemaColumn[] = "${columnDescription['Extra']}";
                }

                $schema .= trim(implode(" ", $schemaColumn)) . ",\n";
            }

            if ($primaryKey) {
                $schema .= "primary key  (${primaryKey})" . "\n";
            }
            return $schema;
        }, static::$schemaDescription);
    }

    public static $schemaDescription = array(
        'ad_tags' => [
            [
                "Field" => "id",
                "Type" => "mediumint(9)",
                "Null" => "NO",
                "Key" => "PRI",
                "Default" => null,
                "Extra" => "auto_increment"
            ],
            [
                "Field" => "updated_at",
                "Type" => "timestamp",
                "Null" => "NO",
                "Key" => "",
                "Default" => "current_timestamp",
                "Extra" => "on update current_timestamp"
            ],
            [
                "Field" => "created_at",
                "Type" => "timestamp",
                "Null" => "NO",
                "Key" => "",
                "Default" => "0000-00-00 00:00:00",
                "Extra" => ""
            ],
            [
                "Field" => "type",
                "Type" => "varchar(28)",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "ad_tag_id",
                "Type" => "bigint(56)",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "name",
                "Type" => "varchar(128)",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "width",
                "Type" => "smallint(4)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "height",
                "Type" => "smallint(4)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "product_type_id",
                "Type" => "int(8)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "product_name",
                "Type" => "varchar(256)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "crid",
                "Type" => "bigint(56)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "version_id",
                "Type" => "mediumint(28)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "status",
                "Type" => "varchar(10)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "ad_code",
                "Type" => "text",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ]
        ],
        'ad_slots' => [
            [
                "Field" => "id",
                "Type" => "mediumint(9)",
                "Null" => "NO",
                "Key" => "PRI",
                "Default" => null,
                "Extra" => "auto_increment"
            ],
            [
                "Field" => "updated_at",
                "Type" => "timestamp",
                "Null" => "NO",
                "Key" => "",
                "Default" => "current_timestamp",
                "Extra" => "on update current_timestamp"
            ],
            [
                "Field" => "created_at",
                "Type" => "timestamp",
                "Null" => "NO",
                "Key" => "",
                "Default" => "0000-00-00 00:00:00",
                "Extra" => ""
            ],
            [
                "Field" => "ptype_id",
                "Type" => "int(8)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "page",
                "Type" => "varchar(128)",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "tag_id",
                "Type" => "mediumint(9)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "position",
                "Type" => "varchar(128)",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "ad_type",
                "Type" => "tinyint(1)",
                "Null" => "YES",
                "Key" => "",
                "Default" => "1",
                "Extra" => ""
            ],
            [
                "Field" => "debug",
                "Type" => "tinyint(1)",
                "Null" => "YES",
                "Key" => "",
                "Default" => "0",
                "Extra" => ""
            ],
            [
                "Field" => "options",
                "Type" => "longtext",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "custom_css",
                "Type" => "longtext",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ]
        ],
        'ad_paragraph_mapping' => [
            [
                "Field" => "id",
                "Type" => "mediumint(9)",
                "Null" => "NO",
                "Key" => "PRI",
                "Default" => null,
                "Extra" => "auto_increment"
            ],
            [
                "Field" => "ad_slot_id",
                "Type" => "mediumint(9)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "paragraph_no",
                "Type" => "int(8)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ]
        ],
        'ad_post_mapping' => [
            [
                "Field" => "id",
                "Type" => "mediumint(9)",
                "Null" => "NO",
                "Key" => "PRI",
                "Default" => null,
                "Extra" => "auto_increment"
            ],
            [
                "Field" => "ad_slot_id",
                "Type" => "mediumint(9)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "post_no",
                "Type" => "int(8)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ]
        ],
        'log_retry' => [
            [
                "Field" => "id",
                "Type" => "mediumint(9)",
                "Null" => "NO",
                "Key" => "PRI",
                "Default" => null,
                "Extra" => "auto_increment"
            ],
            [
                "Field" => "updated_at",
                "Type" => "timestamp",
                "Null" => "NO",
                "Key" => "",
                "Default" => "current_timestamp",
                "Extra" => "on update current_timestamp"
            ],
            [
                "Field" => "created_at",
                "Type" => "timestamp",
                "Null" => "NO",
                "Key" => "",
                "Default" => "0000-00-00 00:00:00",
                "Extra" => ""
            ],
            [
                "Field" => "content",
                "Type" => "text",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ]
        ],
        'blocked_urls' => [
            [
                "Field" => "id",
                "Type" => "mediumint(9)",
                "Null" => "NO",
                "Key" => "PRI",
                "Default" => null,
                "Extra" => "auto_increment"
            ],
            [
                "Field" => "url",
                "Type" => "varchar(256)",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ]
        ],
        'slot_blocked_urls' => [
            [
                "Field" => "id",
                "Type" => "mediumint(9)",
                "Null" => "NO",
                "Key" => "PRI",
                "Default" => null,
                "Extra" => "auto_increment"
            ],
            [
                "Field" => "slot_name",
                "Type" => "varchar(256)",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "url_list",
                "Type" => "text",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ]
        ],
        'external_ad' => [
            [
                "Field" => "id",
                "Type" => "mediumint(9)",
                "Null" => "NO",
                "Key" => "PRI",
                "Default" => null,
                "Extra" => "auto_increment"
            ],
            [
                "Field" => "slot_id",
                "Type" => "mediumint(9)",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "code",
                "Type" => "text",
                "Null" => "NO",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ],
            [
                "Field" => "meta",
                "Type" => "text",
                "Null" => "YES",
                "Key" => "",
                "Default" => null,
                "Extra" => ""
            ]
        ]
    );
}
