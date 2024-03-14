<?php

class Zwt_wp_linkpreviewer_Db
{
    const KEY_HASH_MD_5 = "hash_md5";
    private $DB_VERSION = 1;

    public function check_update_db()
    {
        if (get_site_option(Zwt_wp_linkpreviewer_Constants::$OPTION_KEY_TABLE_VERSION) != $this->DB_VERSION) {
            $this->init_db();
            update_option(Zwt_wp_linkpreviewer_Constants::$OPTION_KEY_TABLE_VERSION, $this->DB_VERSION);
        }
    }

    public function insertEntry($url, $title, $description)
    {
        global $wpdb;
        $wpdb->insert(
            self::tableName(),
            array(
                'date' => current_time('mysql'),
                self::KEY_HASH_MD_5 => md5($url),
                'title' => $title,
                'description' => $description,
                'url' => $url
            )
        );
    }

    public function getEntries()
    {
        global $wpdb;
        $tableName = $this->tableName();
        return $wpdb->get_results("SELECT hash_md5, url, title, description, date, OCTET_LENGTH(img_compact) as img_compact_len  FROM $tableName ORDER BY url ASC", ARRAY_A);
    }


    public function getEntry($url)
    {
        return $this->getEntryForHash(md5($url));
    }

    public function getEntryForHash($hash_md5)
    {
        global $wpdb;
        $tableName = $this->tableName();
        $result = $wpdb->get_row("SELECT title, description, hash_md5, url, OCTET_LENGTH(img_full) as img_full_len, OCTET_LENGTH(img_compact) as img_compact_len FROM $tableName WHERE hash_md5 = '$hash_md5'");
        if ($result) {
            return $result;
        }
        return null;
    }

    public function deleteEntry($hash_md5)
    {
        global $wpdb;
        $tableName = $this->tableName();
        return $wpdb->delete($tableName, array(self::KEY_HASH_MD_5 => $hash_md5));
    }


    public function updateImg($url, $img_url, $img_full, $img_compact)
    {
        global $wpdb;
        $tableName = $this->tableName();
        $wpdb->update($tableName, array(
            'date' => current_time('mysql'),
            'img_url' => $img_url,
            'img_full' => $img_full,
            'img_compact' => $img_compact),
            array(self::KEY_HASH_MD_5 => md5($url)));
    }

    public function get_img_full($hash_md5)
    {
        global $wpdb;
        $tableName = $this->tableName();
        $result = $wpdb->get_row("SELECT img_full FROM $tableName WHERE hash_md5 = '$hash_md5'");
        if ($result) {
            return $result->img_full;
        }
        return null;
    }

    public function get_img_compact($hash_md5)
    {
        global $wpdb;
        $tableName = $this->tableName();
        $result = $wpdb->get_row("SELECT img_compact FROM $tableName WHERE hash_md5 = '$hash_md5'");
        if ($result) {
            return $result->img_compact;
        }
        return null;
    }


    public function init_db()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = self::create_table_sql(self::tableName(), $charset_collate);
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option(Zwt_wp_linkpreviewer_Constants::$OPTION_KEY_TABLE_VERSION, $this->DB_VERSION);
    }

    public function drop_db()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS " . self::tableName());
    }

    private function tableName()
    {
        global $wpdb;
        return $wpdb->prefix . Zwt_wp_linkpreviewer_Constants::$DB_TABLE_NAME;
    }

    private function create_table_sql($table_name, $charset_collate)
    {
        return "CREATE TABLE $table_name (
		hash_md5 varchar(32) NOT NULL,
		url varchar(255) DEFAULT '' NOT NULL,
		title text NULL,
		description text NULL,
		img_url text NULL,
		img_full MEDIUMBLOB NULL,
		img_compact MEDIUMBLOB NULL,
    	date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (hash_md5)
	) $charset_collate;";
    }


}
