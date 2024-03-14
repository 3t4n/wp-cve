<?php

namespace Memsource\Service;

use Memsource\Utils\DatabaseUtils;

class DatabaseService
{
    public function deleteTranslationsBySetId($id)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_TRANSLATION;
        return $wpdb->delete($tableName, ['set_id' => $id]);
    }

    public function deleteTranslationByTargetIdAndType($targetId, $type)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_TRANSLATION;
        return $wpdb->delete($tableName, ['target_id' => $targetId, 'type' => $type]);
    }

    public function updateTargetLanguageBySetIdAndType($language, $setId, $type)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_TRANSLATION;
        $data = ['target_language' => $language];
        $where = ['set_id' => $setId, 'type' => $type];

        return $wpdb->update($tableName, $data, $where);
    }

    /**
     * @param $code string original language code
     * @param $memsourceCode string memsource language code
     * @return int id of mapping
    */
    public function saveLanguageMapping($code, $memsourceCode)
    {
        global $wpdb;

        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_LANGUAGE_MAPPING;
        $mapping = $wpdb->get_var($wpdb->prepare('SELECT id FROM ' . $tableName . ' WHERE code = %s', $code));

        //save
        $data = [
            'code' => $code,
            'memsource_code' => $memsourceCode,
        ];
        return $mapping === null ? $wpdb->insert($tableName, $data) : $wpdb->update($tableName, $data, ['code' => $code]);
    }

    /**
     * Find all in language mapping table.
     * @return array
    */
    public function findAllLanguageMapping()
    {
        global $wpdb;

        $result = [];
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_LANGUAGE_MAPPING;
        $rows = $wpdb->get_results('SELECT * FROM ' . $tableName, ARRAY_A);
        foreach ($rows ?: [] as $row) {
            $result[$row['code']] = $row;
        }
        return $result;
    }

    /**
     * Find language mapping by code.
     * @param $code string original language code
     * @return array|null
    */
    public function findOneLanguageMappingByMemsourceCode($code)
    {
        global $wpdb;

        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_LANGUAGE_MAPPING;
        $sql = $wpdb->prepare('SELECT * FROM ' . $tableName . ' WHERE memsource_code = %s', $code);
        return $wpdb->get_row($sql, ARRAY_A) ?: null;
    }

    /**
     * Truncate language mapping table.
    */
    public function truncateLanguageMapping()
    {
        global $wpdb;

        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_LANGUAGE_MAPPING;
        $wpdb->query('TRUNCATE TABLE ' . $tableName);
    }
}
