<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

require_once ABSPATH.'wp-admin/includes/upgrade.php';

class SystemRepository
{
    public const DB_PREFIX = 'mipconnector';
    private const WEB_HOOKS = 'wc_webhooks';

    /** @var \wpdb */
    protected $wpDb;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
    }

    /**
     * @param array $mipTables
     */
    public function createWcMipConnectorTables(array $mipTables): void
    {
        dbDelta($this->getWcMipConnectorSchemaTables($mipTables));
        dbDelta($this->getWcMipConnectorSchemaTablesMap($mipTables));
    }

    /**
     * @param array $mipTables
     * @return string
     */
    private function getWcMipConnectorSchemaTables(array $mipTables): string
    {
        $tables = [];

        if (!array_key_exists($this->wpDb->prefix.FileLogRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.FileLogRepository::TABLE_NAME] = "      
            CREATE TABLE ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME." (
                `file_id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NULL DEFAULT NULL,
                `version` VARCHAR(12) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_process` DATETIME NULL DEFAULT NULL,
                `date_processing_start` DATETIME NULL DEFAULT NULL,
                `in_process` TINYINT(1) NULL DEFAULT '0',
                PRIMARY KEY (`file_id`),
                INDEX `name` (`name`),
                INDEX `date_process` (`date_process`),
                INDEX `in_process` (`in_process`),
                INDEX `date_add` (`date_add`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.ImportProcessAttributeGroupRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImportProcessAttributeGroupRepository::TABLE_NAME] = "  
            CREATE TABLE ".$this->wpDb->prefix.ImportProcessAttributeGroupRepository::TABLE_NAME." (
                `attribute_group_id` INT(11) NOT NULL DEFAULT '0',
                `file_id` INT(11) NOT NULL,
                `response_api` TINYINT(1) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                CONSTRAINT `fk_file_log_attribute_group_id` FOREIGN KEY (`file_id`) REFERENCES ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME."(`file_id`) ON DELETE CASCADE,
                INDEX `attribute_group_id` (`attribute_group_id`),
                INDEX `file_id` (`file_id`),
                UNIQUE (`attribute_group_id`, `file_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.ImportProcessVariationRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImportProcessVariationRepository::TABLE_NAME] = "
             CREATE TABLE ".$this->wpDb->prefix.ImportProcessVariationRepository::TABLE_NAME." (
                `variation_id` INT(11) NOT NULL DEFAULT '0',
                `file_id` INT(11) NOT NULL,
                `response_api` TINYINT(1) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                CONSTRAINT `fk_file_log_variation_id` FOREIGN KEY (`file_id`) REFERENCES ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME."(`file_id`) ON DELETE CASCADE,
                INDEX `variation_id` (`variation_id`),
                INDEX `file_id` (`file_id`),
                UNIQUE (`variation_id`, `file_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.ImportProcessAttributeRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImportProcessAttributeRepository::TABLE_NAME] = "
            CREATE TABLE ".$this->wpDb->prefix.ImportProcessAttributeRepository::TABLE_NAME." (
                `attribute_id` INT(11) NOT NULL DEFAULT '0',
                `file_id` INT(11) NOT NULL,
                `response_api` TINYINT(1) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                CONSTRAINT `fk_file_log_attribute_id` FOREIGN KEY (`file_id`) REFERENCES ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME."(`file_id`) ON DELETE CASCADE,
                INDEX `attribute_id` (`attribute_id`),
                INDEX `file_id` (`file_id`),
                UNIQUE (`attribute_id`, `file_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.ImportProcessCategoryRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImportProcessCategoryRepository::TABLE_NAME] = "
            CREATE TABLE ".$this->wpDb->prefix.ImportProcessCategoryRepository::TABLE_NAME." (
                `category_id` INT(11) NOT NULL DEFAULT '0',
                `file_id` INT(11) NOT NULL,
                `response_api` TINYINT(1) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                CONSTRAINT `fk_file_log_category_id` FOREIGN KEY (`file_id`) REFERENCES ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME."(`file_id`) ON DELETE CASCADE,
                INDEX `category_id` (`category_id`),
                INDEX `file_id` (`file_id`),
                UNIQUE (`category_id`, `file_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.ImportProcessBrandRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImportProcessBrandRepository::TABLE_NAME] = "
            CREATE TABLE ".$this->wpDb->prefix.ImportProcessBrandRepository::TABLE_NAME." (
                `brand_id` INT(11) NOT NULL DEFAULT '0',
                `file_id` INT(11) NOT NULL,
                `response_api` TINYINT(1) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                CONSTRAINT `fk_file_log_brand_id` FOREIGN KEY (`file_id`) REFERENCES ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME."(`file_id`) ON DELETE CASCADE,
                INDEX `brand_id` (`brand_id`),
                INDEX `file_id` (`file_id`),
                UNIQUE (`brand_id`, `file_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.ImportProcessBrandPluginRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImportProcessBrandPluginRepository::TABLE_NAME] = "
            CREATE TABLE ".$this->wpDb->prefix.ImportProcessBrandPluginRepository::TABLE_NAME." (
                `brand_id` INT(11) NOT NULL DEFAULT '0',
                `file_id` INT(11) NOT NULL,
                `response_api` TINYINT(1) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                CONSTRAINT `fk_file_log_brand_plugin_id` FOREIGN KEY (`file_id`) REFERENCES ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME."(`file_id`) ON DELETE CASCADE,
                INDEX `brand_id` (`brand_id`),
                INDEX `file_id` (`file_id`),
                UNIQUE (`brand_id`, `file_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.ImportProcessTagRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImportProcessTagRepository::TABLE_NAME] = "
            CREATE TABLE ".$this->wpDb->prefix.ImportProcessTagRepository::TABLE_NAME." (
                `tag_id` INT(11) NOT NULL DEFAULT '0',
                `file_id` INT(11) NOT NULL,
                `response_api` TINYINT(1) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                CONSTRAINT `fk_file_log_tag_id` FOREIGN KEY (`file_id`) REFERENCES ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME."(`file_id`) ON DELETE CASCADE,
                INDEX `tag_id` (`tag_id`),
                INDEX `file_id` (`file_id`),
                UNIQUE (`tag_id`, `file_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.ImportProcessProductRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImportProcessProductRepository::TABLE_NAME] = "
            CREATE TABLE ".$this->wpDb->prefix.ImportProcessProductRepository::TABLE_NAME." (
                `product_id` INT(11) NOT NULL DEFAULT '0',
                `file_id` INT(11) NOT NULL,
                `response_api` TINYINT(1) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                CONSTRAINT `fk_file_log_product_id` FOREIGN KEY (`file_id`) REFERENCES ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME."(`file_id`) ON DELETE CASCADE,
                INDEX `product_id` (`product_id`),
                INDEX `file_id` (`file_id`),
                UNIQUE (`product_id`, `file_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.OrderLogRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.OrderLogRepository::TABLE_NAME] = "
                CREATE TABLE ".$this->wpDb->prefix.OrderLogRepository::TABLE_NAME." (
                    `order_id` INT(11) NOT NULL DEFAULT '0',
                    `date_add` DATETIME NULL DEFAULT NULL,
                    `date_update` DATETIME NULL DEFAULT NULL,
                    `date_process` DATETIME NULL DEFAULT NULL,
                    PRIMARY KEY (`order_id`),
                    INDEX `date_process` (`date_process`),
                    INDEX `date_update` (`date_update`)
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB;
            ";
        }

        if (!array_key_exists($this->wpDb->prefix.ProductUrlRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ProductUrlRepository::TABLE_NAME] = "
                CREATE TABLE ".$this->wpDb->prefix.ProductUrlRepository::TABLE_NAME." (
                    `product_shop_id` INT(11) UNSIGNED NOT NULL,
                    `variation_shop_id` INT(11) UNSIGNED NULL DEFAULT '0',
                    `iso_code` VARCHAR(10) NULL DEFAULT NULL,
                    `url` VARCHAR(2048) NULL DEFAULT NULL,
                    `date_add` DATETIME NULL DEFAULT NULL,
                    `date_update` DATETIME NULL DEFAULT NULL,
                    PRIMARY KEY (`product_shop_id`),
                    INDEX `date_add` (`date_add`),
                    INDEX `date_update` (`date_update`)
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB;
            ";
        }

        if (!array_key_exists($this->wpDb->prefix.ImagesUrlRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ImagesUrlRepository::TABLE_NAME] = "
                CREATE TABLE ".$this->wpDb->prefix.ImagesUrlRepository::TABLE_NAME." (
                    `id_image` INT(11) UNSIGNED NOT NULL,
                    `cover` INT(11) UNSIGNED NULL DEFAULT NULL,
                    `url` VARCHAR(2048) NULL DEFAULT NULL,
                    `date_add` DATETIME NULL DEFAULT NULL,
                    `date_update` DATETIME NULL DEFAULT NULL,
                    PRIMARY KEY (`id_image`),
                    INDEX `date_add` (`date_add`),
                    INDEX `date_update` (`date_update`)
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB;
            ";
        }

        if (!array_key_exists($this->wpDb->prefix.ProductImageUrlRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ProductImageUrlRepository::TABLE_NAME] = "
                CREATE TABLE ".$this->wpDb->prefix.ProductImageUrlRepository::TABLE_NAME." (
                    `product_shop_id` INT(11) UNSIGNED NOT NULL,
                    `id_image` INT(11) UNSIGNED NULL DEFAULT NULL,
                    `date_add` DATETIME NULL DEFAULT NULL,
                    `date_update` DATETIME NULL DEFAULT NULL,
                    INDEX `product_shop_id` (`product_shop_id`),
                    INDEX `id_image` (`id_image`)
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB;
            ";
        }

        if (!\array_key_exists($this->wpDb->prefix.CacheRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.CacheRepository::TABLE_NAME] = "
                CREATE TABLE ".$this->wpDb->prefix.CacheRepository::TABLE_NAME." (
                    `item_id` VARCHAR(255) NOT NULL,
                    `item_data` LONGTEXT NULL DEFAULT '',
                    `namespace` VARCHAR(128) NOT NULL,
                    `date_add` DATETIME NULL DEFAULT current_timestamp(),
                    `item_expiration_timestamp` INT(12) UNSIGNED NOT NULL,
                    PRIMARY KEY (`item_id`),
                    INDEX `date_add` (`date_add`),
                    INDEX `namespace` (`namespace`),
                    INDEX `item_expiration_timestamp` (`item_expiration_timestamp`)
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB;
            ";
        }

        if (!\array_key_exists($this->wpDb->prefix.ReferenceDataRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ReferenceDataRepository::TABLE_NAME] = "
                CREATE TABLE ".$this->wpDb->prefix.ReferenceDataRepository::TABLE_NAME." (
                    `reference` VARCHAR(25) NOT NULL,
                    `product_id` INT(11) NOT NULL,
                    `variation_id` INT(11) NULL DEFAULT NULL,
                    `ean` VARCHAR(15) NULL DEFAULT NULL,
                    PRIMARY KEY (`reference`),
                    INDEX `idx_product_id` (`product_id`)
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB;
            ";
        }

        return implode('', $tables);
    }

    /**
     * @param array $mipTables
     * @return string
     */
    private function getWcMipConnectorSchemaTablesMap(array $mipTables): string
    {
        $tables = [];

        if (!array_key_exists($this->wpDb->prefix.ProductMapRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ProductMapRepository::TABLE_NAME] = "          
            CREATE TABLE ".$this->wpDb->prefix.ProductMapRepository::TABLE_NAME." (
                `product_id` INT(11) NOT NULL DEFAULT '0',
                `product_shop_id` INT(11) NULL DEFAULT NULL,
                `version` VARCHAR(16000) NULL DEFAULT '{}',
                `image_version` INT(11) NULL DEFAULT NULL,
                `message_version` DATETIME NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`product_id`),
                INDEX `product_shop_id` (`product_shop_id`),
                UNIQUE (`product_id`),
                UNIQUE (`product_shop_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.BrandMapRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.BrandMapRepository::TABLE_NAME] = "                     
            CREATE TABLE ".$this->wpDb->prefix.BrandMapRepository::TABLE_NAME." (
                `brand_id` INT(11) NOT NULL DEFAULT '0',
                `brand_shop_id` INT(11) NULL DEFAULT NULL,
                `version` VARCHAR(4096) NULL DEFAULT '0',
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`brand_id`),
                INDEX `brand_shop_id` (`brand_shop_id`),
                UNIQUE (`brand_id`),
                UNIQUE (`brand_shop_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.BrandPluginMapRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.BrandPluginMapRepository::TABLE_NAME] = "                     
            CREATE TABLE ".$this->wpDb->prefix.BrandPluginMapRepository::TABLE_NAME." (
                `brand_id` INT(11) NOT NULL DEFAULT '0',
                `brand_shop_id` INT(11) NULL DEFAULT NULL,
                `version` VARCHAR(4096) NULL DEFAULT '0',
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`brand_id`),
                INDEX `brand_shop_id` (`brand_shop_id`),
                UNIQUE (`brand_id`),
                UNIQUE (`brand_shop_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.TagMapRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.TagMapRepository::TABLE_NAME] = "  
            CREATE TABLE ".$this->wpDb->prefix.TagMapRepository::TABLE_NAME." (
                `tag_id` INT(11) NOT NULL DEFAULT '0',
                `tag_shop_id` INT(11) NULL DEFAULT NULL,
                `version` VARCHAR(16000) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                `id_lang` INT(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`tag_id`, `id_lang`),
                INDEX `tag_shop_id` (`tag_shop_id`),
                INDEX `id_lang` (`id_lang`),
                INDEX `tag_id` (`tag_id`),
                INDEX `id_tag_id_lang` (`tag_id`, `id_lang`),
                UNIQUE (`tag_id`),
                UNIQUE (`tag_shop_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.AttributeGroupMapRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.AttributeGroupMapRepository::TABLE_NAME] = "  
            CREATE TABLE ".$this->wpDb->prefix.AttributeGroupMapRepository::TABLE_NAME." (
            `attribute_group_id` INT(11) NOT NULL DEFAULT '0',
            `attribute_group_shop_id` INT(11) NULL DEFAULT NULL,
            `version` VARCHAR(16000) NULL DEFAULT '{}',
            `date_add` DATETIME NULL DEFAULT NULL,
            `date_update` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`attribute_group_id`),
            INDEX `attribute_group_shop_id` (`attribute_group_shop_id`),
                UNIQUE (`attribute_group_id`),
                UNIQUE (`attribute_group_shop_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.VariationMapRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.VariationMapRepository::TABLE_NAME] = "  
            CREATE TABLE ".$this->wpDb->prefix.VariationMapRepository::TABLE_NAME." (
            `variation_id` INT(11) NOT NULL DEFAULT '0',
            `variation_shop_id` INT(11) NULL DEFAULT NULL,
            `date_add` DATETIME NULL DEFAULT NULL,
            `date_update` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`variation_id`),
            INDEX `variation_shop_id` (`variation_shop_id`),
            UNIQUE (`variation_id`),
            UNIQUE (`variation_shop_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.AttributeMapRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.AttributeMapRepository::TABLE_NAME] = "  
            CREATE TABLE ".$this->wpDb->prefix.AttributeMapRepository::TABLE_NAME." (
                `attribute_id` INT(11) NOT NULL DEFAULT '0',
                `attribute_shop_id` INT(11) NULL DEFAULT NULL,
                `version` VARCHAR(16000) NULL DEFAULT '{}',
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`attribute_id`),
                INDEX `attribute_shop_id` (`attribute_shop_id`),
                UNIQUE (`attribute_id`),
                UNIQUE (`attribute_shop_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;";
        }

        if (!array_key_exists($this->wpDb->prefix.CategoryMapRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.CategoryMapRepository::TABLE_NAME] = "  
            CREATE TABLE ".$this->wpDb->prefix.CategoryMapRepository::TABLE_NAME." (
                `category_id` INT(11) NOT NULL DEFAULT '0',
                `category_shop_id` INT(11) NULL DEFAULT NULL,
                `version` VARCHAR(16000) NULL DEFAULT '{}',
                `image_version` INT(11) NULL DEFAULT NULL,               
                `date_add` DATETIME NULL DEFAULT NULL,
                `date_update` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`category_id`),
                INDEX `category_shop_id` (`category_shop_id`),
                UNIQUE (`category_id`),
                UNIQUE (`category_shop_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;
            ";
        }

        if (!\array_key_exists($this->wpDb->prefix.ShippingServiceRepository::TABLE_NAME, $mipTables)) {
            $tables[$this->wpDb->prefix.ShippingServiceRepository::TABLE_NAME] = "  
            CREATE TABLE ".$this->wpDb->prefix.ShippingServiceRepository::TABLE_NAME." (
                `id` INT(11) NOT NULL DEFAULT '0',
                `name` VARCHAR(60) NULL DEFAULT NULL,
                `active` TINYINT(1) NULL DEFAULT '1',
                PRIMARY KEY (`id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;
            ";
        }

        return implode('', $tables);
    }

    public function deleteWcMipConnectorTables(): void
    {
        $this->wpDb->query($this->getDropTables());
    }

    /**
     * @return string
     */
    private function getDropTables(): string
    {
        return "DROP TABLE IF EXISTS
          ".$this->wpDb->prefix.AttributeGroupMapRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.AttributeMapRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.VariationMapRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.CategoryMapRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.BrandMapRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.BrandPluginMapRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ProductMapRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.OrderLogRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.TagMapRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ImportProcessAttributeGroupRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ImportProcessAttributeRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ImportProcessBrandRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ImportProcessVariationRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ImportProcessTagRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ImportProcessCategoryRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ImportProcessProductRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ProductUrlRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ImagesUrlRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.ProductImageUrlRepository::TABLE_NAME.",
          ".$this->wpDb->prefix.FileLogRepository::TABLE_NAME.";";
    }

    public function deleteWcMipConnectorOptions(): void
    {
        delete_option('WC_MIPCONNECTOR_VERSION');
        delete_option('WC_MIPCONNECTOR_ACCESS_TOKEN');
        delete_option('WC_MIPCONNECTOR_SECRET_KEY');
        delete_option('WC_MIPCONNECTOR_SEND_EMAIL');
        delete_option('WC_MIPCONNECTOR_TAG_ACTIVE');
        delete_option('WC_MIPCONNECTOR_TAG_NAME');
        delete_option('WC_MIPCONNECTOR_BRAND_ID');
        delete_option('WC_MIPCONNECTOR_BIGBUY_CARRIER_OPTION');
        delete_option('WC_BIGBUY_API_KEY');
    }

    /**
     * @param array $filter
     */
    public function deleteWebHooks(array $filter): void
    {
        $this->wpDb->delete($this->wpDb->prefix.self::WEB_HOOKS, $filter);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function findWebHookByName(string $name): ?array
    {
        $sql = 'SELECT * FROM '.$this->wpDb->prefix.self::WEB_HOOKS.' WHERE name = "'.$name.'"';

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @param array $data
     * @param array $filter
     * @return bool|null
     */
    public function resetFailureCount(array $data, array $filter): ?bool
    {
        return $this->wpDb->update($this->wpDb->prefix.self::WEB_HOOKS, $data, $filter);
    }

    /**
     * @param string $sql
     * @return bool
     */
    public function executeSql(string $sql): bool
    {
        return  $this->wpDb->query($sql);
    }

    /**
     * @return string
     */
    public function getWoocommerceDefaultCountryIsoCode(): string
    {
        return get_option('woocommerce_default_country');
    }

    /**
     * @return array
     */
    public function getMipTablesFromDatabase(): array
    {
        $mipTables = [];
        $mipTablesResponse = $this ->wpDb->get_results("SHOW TABLES LIKE '%".self::DB_PREFIX."%'");

        foreach ($mipTablesResponse as $tables) {
            foreach ($tables as $table) {
                $mipTables[$table] = $table;
            }
        }

        return $mipTables;
    }

}