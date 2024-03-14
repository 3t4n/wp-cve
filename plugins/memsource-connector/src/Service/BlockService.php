<?php

namespace Memsource\Service;

use Memsource\Utils\ActionUtils;
use Memsource\Utils\DatabaseUtils;
use Memsource\Utils\SystemUtils;
use WP_Block_Type_Registry;

class BlockService
{
    private const BLOCKS_CONFIG_FILE = 'blocks.json';
    private const MAX_EXPANSIONS = 100;

    private $predefinedBlocks = [];
    private $installedBlocks = [];
    private $customBlocks = [];
    private $allBlocks = [];

    private $initialized = false;

    /**
     * Handle form registered in memsource.php
     */
    public function editBlocksFormSubmit()
    {
        $blocks = ActionUtils::getParameter('blocks');

        if (!empty($blocks)) {
            global $wpdb;
            $wpdb->query('START TRANSACTION');

            foreach (WP_Block_Type_Registry::get_instance()->get_all_registered() as $block) {
                $this->deleteBlocks($block->name);
            }

            foreach ($blocks as $block => $attributes) {
                $this->deleteBlocks($block);
                foreach (array_keys($attributes) as $attribute) {
                    if (!$this->isAttributePredefined($block, $attribute)) {
                        $this->insertBlock($block, $attribute);
                    }
                }
            }

            $wpdb->query('COMMIT');
        }

        wp_safe_redirect(wp_get_referer());
        exit;
    }

    /**
     * Handle form registered in memsource.php
     */
    public function storeBlockFormSubmit()
    {
        $block = ActionUtils::getParameter('block');
        $block = $this->unifyBlockName($block);

        $attributes = ActionUtils::getParameter('attributes');
        $attributes = explode(',', $attributes);
        $attributes = array_unique($attributes);
        $attributes = array_map('trim', $attributes);
        $attributes = array_filter($attributes);

        if (!empty($block) && !empty($attributes)) {
            global $wpdb;
            $wpdb->query('START TRANSACTION');

            $this->deleteBlocks($block);

            foreach ($attributes as $attribute) {
                $this->insertBlock($block, $attribute);
            }

            $wpdb->query('COMMIT');
        }

        wp_safe_redirect(wp_get_referer() . '#custom-blocks');
        exit;
    }

    /**
     * Handle form registered in memsource.php
     */
    public function deleteBlockFormSubmit()
    {
        $block = ActionUtils::getParameter('block');

        if (!empty($block)) {
            $this->deleteBlocks($block);
        }

        wp_safe_redirect(wp_get_referer() . '#custom-blocks');
        exit;
    }

    public function unifyBlockName(string $name): string
    {
        $name = trim($name);
        $prefix = 'wp:';

        if (strpos($name, $prefix) === 0) {
            $name = substr($name, strlen($prefix));
        }

        if (strpos($name, '/') === false) {
            $name = 'core/' . $name;
        }

        return $name;
    }

    public function isAttributeTranslatable(string $blockName, string $attribute): bool
    {
        $this->init();

        return ($this->allBlocks[$blockName][$attribute] ?? false) === true;
    }

    public function isAttributePredefined(string $blockName, string $attribute): bool
    {
        $this->init();

        return ($this->predefinedBlocks[$blockName][$attribute] ?? false) === true;
    }

    public function listInstalledBlocks(): array
    {
        $this->init();

        return $this->installedBlocks;
    }

    public function listCustomBlocks(): array
    {
        $this->init();

        return $this->customBlocks;
    }

    public function listUserDefinedBlocks(): array
    {
        $this->init();

        $customBlocks = [];

        foreach ($this->customBlocks as $name => $attributes) {
            foreach ($attributes as $attribute => $val) {
                if (!isset($this->installedBlocks[$name]->attributes[$attribute])) {
                    $customBlocks[$name][$attribute] = $val;
                }
            }
        }

        return $customBlocks;
    }

    public function getCustomBlocksDump(): array
    {
        $this->init();

        $result = [];

        foreach ($this->customBlocks as $name => $attrs) {
            $result[] = $name . ': ' . (empty($attrs) ? '-' : join(', ', array_keys($attrs)));
        }

        return $result;
    }

    private function init()
    {
        if (!$this->initialized) {
            $this->loadBlocksFromFile();
            $this->loadBlocksFromDb();
            ksort($this->predefinedBlocks);
            ksort($this->customBlocks);
            $this->allBlocks = array_merge_recursive($this->predefinedBlocks, $this->customBlocks);
            $this->expandWildcardAttributes();
            ksort($this->allBlocks);
            $this->initialized = true;
            $this->loadInstalledBlocks();
        }
    }

    private function expandWildcardAttributes()
    {
        foreach ($this->allBlocks as $block => $attributes) {
            foreach ($attributes as $attribute => $status) {
                if (strpos($attribute, '*') !== false) {
                    for ($i = 0; $i <= self::MAX_EXPANSIONS; $i++) {
                        $expandedAttribute = str_replace('*', "$i", $attribute);
                        $this->allBlocks[$block][$expandedAttribute] = $status;
                    }
                }
            }
        }
    }

    private function loadBlocksFromFile()
    {
        $file = file_get_contents(
            SystemUtils::getJsonConfigFile(self::BLOCKS_CONFIG_FILE)
        );

        if ($file !== false) {
            $this->predefinedBlocks = json_decode($file, true) ?? [];
        }
    }

    private function loadBlocksFromDb()
    {
        global $wpdb;
        $table = $wpdb->prefix . DatabaseUtils::TABLE_BLOCKS;
        $blocks = $wpdb->get_results(
            "select * from `${table}` order by `name`",
            ARRAY_A
        );

        foreach ($blocks as $block) {
            $this->customBlocks[$block['name']][$block['attribute']] = true;
        }
    }

    private function loadInstalledBlocks()
    {
        foreach (WP_Block_Type_Registry::get_instance()->get_all_registered() as $block) {
            $attributes = [];
            foreach ($block->attributes as $attributeName => $attribute) {
                if (is_array($attribute) && $attribute['type'] === 'string') {
                    $attributes[$attributeName] = (object) [
                        'name' => $attributeName,
                        'translatable' => $this->isAttributeTranslatable($block->name, $attributeName),
                        'uneditable' => $this->isAttributePredefined($block->name, $attributeName),
                    ];
                }
            }
            if (!empty($attributes)) {
                $filteredBlock = $block;
                $filteredBlock->attributes = $attributes;
                $this->installedBlocks[$block->name] = $filteredBlock;
            }
        }
    }

    private function deleteBlocks(string $name)
    {
        global $wpdb;
        $table = $wpdb->prefix . DatabaseUtils::TABLE_BLOCKS;
        $wpdb->delete($table, ['name' => $name]);
    }

    private function insertBlock(string $name, string $attribute)
    {
        global $wpdb;
        $table = $wpdb->prefix . DatabaseUtils::TABLE_BLOCKS;
        $wpdb->insert($table, [
            'name' => $name,
            'attribute' => $attribute,
        ]);
    }
}
