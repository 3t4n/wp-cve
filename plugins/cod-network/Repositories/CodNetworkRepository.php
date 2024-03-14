<?php

namespace CODNetwork\Repositories;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CodNetworkRepository')) {
    class CodNetworkRepository
    {
        private static $instance;
        const LOGS_STATUS_FIELD = "logs_status_field";

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Get Table name
         */
        public function get_table_name_setting(): string
        {
            global $wpdb;

            return sprintf('%scodnetwork_setting', $wpdb->prefix);
        }

        /**
         * Get Table name queue_job
         */
        public function get_table_name_queue_job(): string
        {
            global $wpdb;

            return sprintf('%scodnetwork_queue_jobs', $wpdb->prefix);
        }

        /**
         * Get Table name queue_failures
         */
        public function get_table_name_queue_failures(): string
        {
            global $wpdb;

            return sprintf('%scodnetwork_queue_failures', $wpdb->prefix);
        }

        /**
         * COD.network create table setting in database
         */
        public function create_table_setting(): bool
        {
            global $wpdb;
            $charsetCollate = $wpdb->get_charset_collate();
            $tableName = $this->get_table_name_setting();
            $queryCheckTableExist = sprintf('SHOW TABLES LIKE "%s";', $tableName);

            // this if statement makes sure that the table doe not exist already
            if ($wpdb->get_var($queryCheckTableExist) != $tableName) {
                $query = sprintf("CREATE TABLE %s (
               `id` int(10) NOT NULL AUTO_INCREMENT,
               `status` tinyint(4) DEFAULT '0',
               `key` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
               `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL, 
               `created_at` timestamp NULL DEFAULT NULL,
               `updated_at` timestamp NULL DEFAULT NULL,
               PRIMARY KEY (`id`)
            ) %s;", $tableName, $charsetCollate);

                require_once(ABSPATH . "wp-admin/includes/upgrade.php");
                dbDelta($query);

                return true;
            }

            return false;
        }

        /**
         * COD.network Delete table setting in database
         */
        public function delete_table_setting(): bool
        {
            global $wpdb;
            $tableName = $this->get_table_name_setting();
            $query = sprintf('DROP TABLE IF EXISTS %s', $tableName);
            $wpdb->query($query);

            return true;
        }

        /**
         * Store token in database
         */
        public function create_or_update_token(string $token): bool
        {
            global $wpdb;
            $date = date('Y-m-d H:i:s', current_time('timestamp', 0));

            if ($this->has_token()) {
                $query = sprintf("UPDATE %s SET `value`='%s', `updated_at`='%s' WHERE `key`='%s'", $this->get_table_name_setting(), $token, $date, "token");

                return $wpdb->query($query);
            }

            return $wpdb->insert($this->get_table_name_setting(), array(
                'status' => 1,
                'key' => 'token',
                'value' => $token,
                'created_at' => $date
            ));
        }

        /**
         * Delete token from setting
         */
        public function delete_token(): bool
        {
            global $wpdb;

            return $wpdb->delete($this->get_table_name_setting(), array(
                'status' => 1,
                'key' => 'token'
            ));
        }

        /**
         * Get token form Database
         */
        public function has_token(): bool
        {
            global $wpdb;
            $query = sprintf('SELECT COUNT(*) from %s where `key`="%s"', $this->get_table_name_setting(), "token");

            return $wpdb->get_var($query) > 0;
        }

        /**
         * Select token form database
         */
        public function select_token(): ?string
        {
            global $wpdb;
            $query = sprintf('SELECT `value` FROM %s where `key`="%s"', $this->get_table_name_setting(), "token");

            return $wpdb->get_var($query);
        }

        /**
         * Create table Queue jobs
         */
        public function create_queue_table(): bool
        {
            global $wpdb;
            $charsetCollate = $wpdb->get_charset_collate();
            $tableName = $this->get_table_name_queue_job();
            $queryCheckTableExist = sprintf('SHOW TABLES LIKE "%s";', $tableName);

            // this if statement makes sure that the table doe not exist already
            if ($wpdb->get_var($queryCheckTableExist) != $tableName) {
                $query = sprintf("CREATE TABLE %s (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `job` longtext NOT NULL,
                `attempts` tinyint(3) NOT NULL DEFAULT 0,
                `reserved_at` datetime DEFAULT NULL,
                `available_at` datetime NOT NULL,
                `created_at` datetime NOT NULL,
                PRIMARY KEY  (`id`)
            ) %s;", $tableName, $charsetCollate);

                require_once(ABSPATH . "wp-admin/includes/upgrade.php");
                dbDelta($query);

                return true;
            }

            return false;
        }

        /**
         * Create table Queue jobs
         */
        public function create_queue_failures_table(): bool
        {
            global $wpdb;
            $charsetCollate = $wpdb->get_charset_collate();
            $tableName = $this->get_table_name_queue_failures();
            $queryCheckTableExist = sprintf('SHOW TABLES LIKE "%s";', $tableName);

            // this if statement makes sure that the table doe not exist already
            if ($wpdb->get_var($queryCheckTableExist) != $tableName) {
                $query = sprintf("CREATE TABLE %s (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `job` longtext NOT NULL,
                `error` text DEFAULT NULL,
                `failed_at` datetime NOT NULL,
                PRIMARY KEY  (`id`)
                ) %s;", $tableName, $charsetCollate);

                require_once(ABSPATH . "wp-admin/includes/upgrade.php");
                dbDelta($query);

                return true;
            }

            return false;
        }

        /**
         * COD.network Delete table queue in database
         */
        public function delete_table_queue(): bool
        {
            global $wpdb;
            $tableName = $this->get_table_name_queue_job();
            $query = sprintf('DROP TABLE IF EXISTS %s', $tableName);
            $wpdb->query($query);

            return true;
        }

        /**
         * COD.network Delete table queue failures in database
         */
        public function delete_table_queue_failures(): bool
        {
            global $wpdb;
            $tableName = $this->get_table_name_queue_failures();
            $query = sprintf('DROP TABLE IF EXISTS %s', $tableName);
            $wpdb->query($query);

            return true;
        }

        /**
         * COD.network create status Log activity setting
         */
        public function create_status_logs_activity(): bool
        {
            global $wpdb;
            $date = date('Y-m-d H:i:s', current_time('timestamp', 0));

            return $wpdb->insert($this->get_table_name_setting(), array(
                'status' => 1,
                'key' => self::LOGS_STATUS_FIELD,
                'value' => true,
                'created_at' => $date
            ));
        }

        /**
         * COD.network update status Log activity setting
         */
        public function update_status_logs_activity(bool $value): bool
        {
            global $wpdb;
            $date = date('Y-m-d H:i:s', current_time('timestamp', 0));

            $query = sprintf("UPDATE %s SET `value`='%s', `updated_at`='%s' WHERE `key`='%s'", $this->get_table_name_setting(), $value, $date, self::LOGS_STATUS_FIELD);

            return $wpdb->query($query);
        }

        /**
         * Select value activity logs form database
         */
        public function select_logs_status(): ?bool
        {
            global $wpdb;
            $query = sprintf('SELECT `value` FROM %s where `key`="%s"', $this->get_table_name_setting(), self::LOGS_STATUS_FIELD);

            return $wpdb->get_var($query);
        }
    }
}

