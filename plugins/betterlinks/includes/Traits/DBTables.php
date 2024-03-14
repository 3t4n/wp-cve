<?php
namespace BetterLinks\Traits;

trait DBTables
{
    public function createBetterLinksTable()
    {
        $table_name = $this->wpdb->prefix . 'betterlinks';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            ID bigint(20) unsigned NOT NULL auto_increment,
            link_author bigint(20) unsigned NOT NULL default '0',
            link_date datetime NOT NULL default CURRENT_TIMESTAMP,
            link_date_gmt datetime NOT NULL default CURRENT_TIMESTAMP,
            link_title text NOT NULL,
            link_slug varchar(200) NOT NULL default '',
            link_note text NOT NULL,
            link_status varchar(20) NOT NULL default 'publish',
            nofollow varchar(10),
            sponsored varchar(10),
            track_me varchar(10),
            param_forwarding varchar(10),
            param_struct varchar(255) default NULL,
            redirect_type varchar(255) default '307',
            target_url text default NULL,
            short_url varchar(255) default NULL,
            link_order tinyint(11) default 0,
            link_modified datetime NOT NULL default CURRENT_TIMESTAMP,
            link_modified_gmt datetime NOT NULL default CURRENT_TIMESTAMP,
			wildcards boolean NOT NULL default 0,
			expire text default NULL,
			dynamic_redirect text default NULL,
            favorite varchar(255),
            PRIMARY KEY  (ID),
            KEY link_slug (link_slug(191)),
            KEY type_status_date (link_status,link_date,ID),
            KEY link_author (link_author),
            KEY link_order (link_order)
        ) $this->charset_collate;";
        dbDelta($sql);
    }

    public function createBetterTermsTable()
    {
        $table_name = $this->wpdb->prefix . 'betterlinks_terms';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            ID bigint(20) unsigned NOT NULL auto_increment,
            term_name text NOT NULL,
            term_slug varchar(200) NOT NULL default '',
            term_type varchar(15) NOT NULL,
            term_order tinyint(11) default 0,
            PRIMARY KEY  (ID),
            KEY term_slug (term_slug(191)),
            key term_type (term_type),
            key term_order (term_order)
        ) $this->charset_collate;";
        dbDelta($sql);
    }

    public function createBetterTermsRelationshipsTable()
    {
        $table_name = $this->wpdb->prefix . 'betterlinks_terms_relationships';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            ID bigint(20) unsigned NOT NULL auto_increment,
            term_id bigint(20) default 0,
            link_id bigint(20) default 0,
            PRIMARY KEY  (ID),
            KEY term_id (term_id),
            key link_id (link_id)
        ) $this->charset_collate;";
        dbDelta($sql);
    }
    
    public function createBetterClicksTable()
    {
        $table_name = $this->wpdb->prefix . 'betterlinks_clicks';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            ID bigint(20) unsigned NOT NULL auto_increment,
            link_id bigint(20) NOT NULL,
            ip 	varchar(255) NULL,
            browser varchar(255) NULL,
            os varchar(255) NULL,
            device varchar(20) NULL,
            brand_name VARCHAR(20) NULL,
            model VARCHAR(20) NULL,
            bot_name VARCHAR(20) NULL,
            browser_type VARCHAR(20) NULL,
            os_version VARCHAR(20) NULL,
            browser_version VARCHAR(20) NULL,
            `language` VARCHAR(10) NULL,
            referer varchar(255) NULL,
            host varchar(255) NULL,
            uri varchar(255) NULL,
            click_count tinyint(4) NOT NULL default 0, 
            visitor_id varchar(25) NULL,
            click_order tinyint(11) default 0,
            created_at datetime NOT NULL default CURRENT_TIMESTAMP,
            created_at_gmt datetime NOT NULL default CURRENT_TIMESTAMP,
            rotation_target_url varchar(255) NULL,
            PRIMARY KEY  (ID),
            KEY ip (ip),
            key link_id (link_id),
            key click_order (click_order)
        ) $this->charset_collate;";
        dbDelta($sql);
    }
    
    public function createBetterLinkMetaTable()
    {
        $table_name = $this->wpdb->prefix . 'betterlinkmeta';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            meta_id bigint(20) unsigned NOT NULL auto_increment,
            link_id bigint(20) unsigned NOT NULL default '0',
            meta_key varchar(255) NOT NULL default '',
            meta_value longtext NOT NULL default '',
            PRIMARY KEY  (meta_id),
            KEY link_id (link_id),
            KEY meta_key (meta_key)
        ) $this->charset_collate;";
        dbDelta($sql);
    }

    public function createBetterLinkPasswordTable() {
        $table_name = $this->wpdb->prefix . 'betterlinks_password';
        $ref_table_name = $this->wpdb->prefix . 'betterlinks';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT NOT NULL AUTO_INCREMENT,
            `link_id` bigint(20) unsigned NOT NULL,
            `password` VARCHAR(255),
            `status` BOOLEAN,
            `allow_contact` BOOLEAN default false,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`link_id`) REFERENCES $ref_table_name (`ID`)
                ON DELETE CASCADE
        ) $this->charset_collate;";
        dbDelta($sql);
    }

    public function modifyBetterLinksTable() {
        $table_name = $this->wpdb->prefix . 'betterlinks';
        $sql = "ALTER TABLE {$table_name}
            MODIFY target_url text default null,
            MODIFY link_date datetime NOT NULL default CURRENT_TIMESTAMP,
            MODIFY link_date_gmt datetime NOT NULL default CURRENT_TIMESTAMP,
            MODIFY link_modified datetime NOT NULL default CURRENT_TIMESTAMP,
            MODIFY link_modified_gmt datetime NOT NULL default CURRENT_TIMESTAMP;";
        $this->wpdb->query($sql);
    }

    public function modifyBetterLinksClicksTable() {
		global $wpdb;

		// todo: 👇 if `device` column exists that means we already have added device, brand_name, model, bot_name, browser_type, os_version, browser_version, language column
		// todo: so, we need to skip the betterlinks_clicks table alterations
		$check_column_exists_sql = sprintf( 'select `column_name` from information_schema.columns where table_schema="%1$s" and table_name="%2$sbetterlinks_clicks" and column_name="device";', DB_NAME, $wpdb->prefix );
		$result                  = $wpdb->query( $check_column_exists_sql );

		if ( ! $result ) {
			$table_name = $wpdb->prefix . 'betterlinks_clicks';
			$sql        = "ALTER TABLE {$table_name}
                MODIFY created_at datetime NOT NULL default CURRENT_TIMESTAMP,
                MODIFY created_at_gmt datetime NOT NULL default CURRENT_TIMESTAMP,
                ADD COLUMN `device` VARCHAR(20) NULL AFTER `os`,
                ADD COLUMN `brand_name` VARCHAR(20) NULL AFTER `device`,
                ADD COLUMN `model` VARCHAR(20) NULL AFTER `brand_name`,
                ADD COLUMN `bot_name` VARCHAR(20) NULL AFTER `model`,
                ADD COLUMN `browser_type` VARCHAR(20) NULL AFTER `bot_name`,
                ADD COLUMN `os_version` VARCHAR(20) NULL AFTER `browser_type`,
                ADD COLUMN `browser_version` VARCHAR(20) NULL AFTER `os_version`,
                ADD COLUMN `language` VARCHAR(10) NULL AFTER `browser_version`;";

			$wpdb->query( $sql );
		}
	}
}
