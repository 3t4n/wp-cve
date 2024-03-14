<?php

namespace Memsource\Service;

use Memsource\Utils\ActionUtils;
use Memsource\Utils\DatabaseUtils;
use Memsource\Utils\SystemUtils;

class ShortcodeService
{
    private $shortcodeCache = [];
    private $shortcodeTypes = [];

    public function init()
    {
        // load short codes to the cache
        $this->loadFromJson('2.0');  // if the UpdateService insert failed, reload from JSON
        $this->loadFromJson('2.4');  // if the UpdateService insert failed, reload from JSON
        $this->loadFromJson('2.4.3');  // if the UpdateService insert failed, reload from JSON
        global $wpdb;
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODES;
        $sql = "select * from {$tableName} order by type";
        $shortcodes = $wpdb->get_results($sql, ARRAY_A);
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODE_ATTRIBUTES;
        $sql = "select * from {$tableName}";
        $shortcodeAttributes = $wpdb->get_results($sql, ARRAY_A);
        foreach ($shortcodes as $shortcode) {
            if (!array_key_exists('delimiter', $shortcode)) {
                $shortcode['delimiter'] = "\"";
            }
            if (!$this->hasShortcode($shortcode['tag'], $shortcode['editable'])) {  // avoid duplicities
                $id = $shortcode['id'];
                $type = $shortcode['type'];
                if (!in_array($type, $this->shortcodeTypes, true)) {
                    $this->shortcodeTypes[] = $type;
                }
                $attrs = [];
                foreach ($shortcodeAttributes as $shortcodeAttribute) {
                    if ($shortcodeAttribute['short_code_id'] === $id) {
                        $attrs[] = $shortcodeAttribute;
                    }
                }
                $shortcode['attributes'] = $attrs;
                $shortcode = $this->removeDuplicateAttributes($shortcode);
                $this->shortcodeCache[] = $shortcode;
            }
        }
    }

    private function loadFromJson($version)
    {

        $fileName = SystemUtils::getJsonUpdateFile($version);
        if (file_exists($fileName)) {
            $jsonObject = json_decode(file_get_contents($fileName));
            foreach ($jsonObject->shortCodes as $shortcodes) {
                $codeType = $shortcodes->type;
                if (!in_array($codeType, $this->shortcodeTypes, true)) {
                    $this->shortcodeTypes[] = $codeType;
                }
                $delimiter = $shortcodes->delimiter ?? "\"";
                foreach ($shortcodes->values as $code) {
                    $shortcode = [
                      'type' => $codeType,
                      'tag' => $code->tag,
                      'ignore_body' => isset($code->ignoreBody) && $code->ignoreBody,
                      'editable' => false,
                      'status' => 'Active',
                      'delimiter' => $delimiter,
                    ];
                    $attrs = [];
                    if (isset($code->attributes)) {
                        foreach ($code->attributes as $attribute) {
                            $attrs[] = [
                              'name' => $attribute->name,
                              'type' => null,
                              'encoding' => null,
                              'editable' => false,
                              'status' => 'Active',
                            ];
                        }
                    }
                    $shortcode['attributes'] = $attrs;
                    $shortcode = $this->removeDuplicateAttributes($shortcode);
                    $this->shortcodeCache[] = $shortcode;
                }
            }
        }
    }

    private function removeDuplicateAttributes($shortcode)
    {

        $key = array_search($shortcode['tag'], array_column($this->shortcodeCache, 'tag'), true);
        if ($key !== false) {
            foreach ($shortcode['attributes'] as $attributeKey => $attribute) {
                $found = array_search($attribute['name'], array_column($this->shortcodeCache[$key]['attributes'], 'name'), true);
                if ($found !== false) {
                    unset($shortcode['attributes'][$attributeKey]);
                }
            }
        }
        return $shortcode;
    }

    public function getShortcodes(): array
    {
        return $this->shortcodeCache;
    }

    public function getShortcodeData()
    {

        return ["types" => $this->shortcodeTypes, "codes" => $this->shortcodeCache];
    }

    private function findShortcode($shortcode)
    {

        foreach ($this->shortcodeCache as $shortcodeObject) {
            if ($shortcodeObject['editable'] && strcasecmp($shortcodeObject['tag'], $shortcode) === 0) {
                return $shortcodeObject;
            }
        }
        return null;
    }

    private function hasShortcode($shortcode, $editable = false)
    {

        foreach ($this->shortcodeCache as $shortcodeObject) {
            if (strcasecmp($shortcodeObject['tag'], $shortcode) === 0) {
                if (($editable && $shortcodeObject['editable']) || !$editable) {
                    return true;
                }
            }
        }
        return false;
    }

    public function addOrUpdateShortcodeEndpoint()
    {

        $shortcode = ActionUtils::getParameter("shortCode", '');
        $attributes = ActionUtils::getParameter("attributes", '');
        if (strlen($shortcode) > 0) {
            $attributeList = $attributes ? explode(",", $attributes) : [];
            if ($this->hasShortcode($shortcode, true)) {
                $this->updateShortcode($shortcode, $attributeList);
            } else {
                $this->addShortcode($shortcode, $attributeList);
            }
        }
        wp_safe_redirect(wp_get_referer());
        exit();
    }

    public function deleteShortcodeEndpoint()
    {

        $shortcode = ActionUtils::getParameter("shortCode");
        if ($shortcode) {
            $this->deleteShortcode($shortcode);
        }
        wp_safe_redirect(wp_get_referer());
        exit();
    }

    private function addShortcode($shortcode, $attributeList)
    {

        global $wpdb;
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODES;
        $wpdb->insert($tableName, [
            'type' => 'Custom',
            'tag' => $shortcode,
            'editable' => true,
        ]);
        if (sizeof($attributeList) > 0) {
            $shortcodeId = $wpdb->insert_id;
            $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODE_ATTRIBUTES;
            foreach ($attributeList as $attribute) {
                $attribute = trim($attribute);
                if (strlen($attribute) > 0) {
                    $wpdb->insert($tableName, [
                        'short_code_id' => $shortcodeId,
                        'name' => $attribute,
                        'editable' => true,
                    ]);
                }
            }
        }
    }

    private function updateShortcode($shortcode, $attributeList)
    {

        $this->deleteShortcode($shortcode);
        $this->addShortcode($shortcode, $attributeList);
    }

    private function deleteShortcode($shortcode)
    {

        $shortcodeObject = $this->findShortcode($shortcode);
        if ($shortcodeObject) {
            global $wpdb;
            $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODE_ATTRIBUTES;
            $wpdb->delete($tableName, ['short_code_id' => $shortcodeObject['id'], 'editable' => true]);
            $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODES;
            $wpdb->delete($tableName, ['id' => $shortcodeObject['id'], 'editable' => true]);
        }
    }

    public function getCustomShortcodesDump(): array
    {
        $result = [];

        foreach ($this->shortcodeCache as $shortcode) {
            if ($shortcode['type'] === 'Custom') {
                $attributes = [];

                foreach ($shortcode['attributes'] as $attribute) {
                    $attributes[] = $attribute['name'];
                }

                $result[] = $shortcode['tag'] . ': ' . (empty($attributes) ? '-' : join(', ', $attributes));
            }
        }

        return $result;
    }
}
