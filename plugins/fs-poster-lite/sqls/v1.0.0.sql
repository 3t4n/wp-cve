DROP TABLE IF EXISTS `{tableprefix}accounts`;
DROP TABLE IF EXISTS `{tableprefix}account_nodes`;
DROP TABLE IF EXISTS `{tableprefix}account_node_status`;
DROP TABLE IF EXISTS `{tableprefix}account_status`;
DROP TABLE IF EXISTS `{tableprefix}feeds`;
DROP TABLE IF EXISTS `{tableprefix}grouped_accounts`;

CREATE TABLE `{tableprefix}accounts` (
                                                `id` int(11) NOT NULL,
                                                `driver` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
                                                `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                                `profile_id` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
                                                `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
                                                `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                                `profile_pic` text CHARACTER SET utf8 DEFAULT NULL,
                                                `is_active` tinyint(4) DEFAULT 0,
                                                `fs_account_id` bigint(20) DEFAULT NULL
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

CREATE TABLE `{tableprefix}account_nodes` (
                                                `id` int(11) NOT NULL,
                                                `account_id` int(11) DEFAULT NULL,
                                                `node_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
                                                `node_id` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
                                                `access_token` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                                `name` varchar(350) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                                `added_date` timestamp NULL DEFAULT current_timestamp(),
                                                `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                                `cover` varchar(750) CHARACTER SET utf8 DEFAULT NULL,
                                                `driver` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
                                                `screen_name` varchar(350) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                                `is_active` tinyint(4) DEFAULT 0,
                                                `fs_account_id` bigint(20) DEFAULT NULL
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

CREATE TABLE `{tableprefix}feeds` (
                                                `id` int(11) NOT NULL,
                                                `post_id` bigint(20) DEFAULT NULL,
                                                `node_id` int(11) DEFAULT NULL,
                                                `node_type` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
                                                `driver` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
                                                `is_sended` tinyint(1) DEFAULT 0,
                                                `status` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
                                                `error_msg` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                                `send_time` timestamp NULL DEFAULT current_timestamp(),
                                                `driver_post_id` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
                                                `visit_count` int(11) DEFAULT 0,
                                                `is_seen` tinyint(1) DEFAULT NULL
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

CREATE TABLE `{tableprefix}grouped_accounts` (
                                                `id` int(11) NOT NULL,
                                                `user_id` int(11) DEFAULT NULL,
                                                `account_id` int(11) DEFAULT NULL,
                                                `account_type` varchar(10) COLLATE utf8_general_ci DEFAULT NULL,
                                                `group_id` int(11) DEFAULT NULL
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

ALTER TABLE `{tableprefix}accounts`             ADD PRIMARY KEY (`id`) USING BTREE;
ALTER TABLE `{tableprefix}account_nodes`        ADD PRIMARY KEY (`id`) USING BTREE;
ALTER TABLE `{tableprefix}feeds`                ADD PRIMARY KEY (`id`) USING BTREE;
ALTER TABLE `{tableprefix}grouped_accounts`     ADD PRIMARY KEY (`id`) USING BTREE;

ALTER TABLE `{tableprefix}accounts`             MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `{tableprefix}account_nodes`        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `{tableprefix}feeds`                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `{tableprefix}grouped_accounts`     MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
