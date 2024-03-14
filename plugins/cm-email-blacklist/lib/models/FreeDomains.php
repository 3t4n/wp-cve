<?php

class CMEB_FreeDomains {

    const TABLE_NAME = 'cmeb_free_domains';
    const MENU_OPTION = 'cmeb_free_domains_menu';
    const OPTION_DB_VERSION = 'cmeb_free_domains_ver';
    const CURRENT_VERSION = '1.1';
    const LAST_UPDATE ='last_free_domain_list_update';

    public static function isValid($domain) {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . self::TABLE_NAME . " WHERE '" . esc_sql($domain) . "' LIKE REPLACE(domain, '*', '%')";
        $count = $wpdb->get_var($sql);
        return ($count == 0);
    }

    public static function install() {
        global $wpdb;
        self::uninstall();
        $table_name1 = $wpdb->prefix . self::TABLE_NAME;
        $sql = "CREATE TABLE `" . $table_name1 . "` (
            `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
            `domain` VARCHAR(100) NOT NULL,
            PRIMARY KEY (`id`)
	);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        $html = wp_remote_get('http://svn.apache.org/repos/asf/spamassassin/trunk/rules/20_freemail_domains.cf');
        $regex = '/^freemail_domains (.*)/m';
        
        $process_string =$html['body'];
        $matches = array();
        preg_match_all($regex,$process_string,$matches);
        $domain_nr=0;
        $queries = array();
        foreach ($matches[1] as $shortlist) {
             $initialValues = explode(' ', $shortlist);
             foreach ($initialValues as $domain) {
                 $queries[] = $wpdb->prepare('(%s)', $domain);
                 $domain_nr++;
             }
        }
        if (!empty($queries)) {
            $sql = 'INSERT INTO ' . $table_name1 . ' (domain) VALUES ' . implode(', ', $queries);
            $wpdb->query($sql);
        }
        update_option(self::LAST_UPDATE, 'List last update: '.current_time( 'mysql' ).' /  Number of domains in list: '.$domain_nr);
        update_option(self::OPTION_DB_VERSION, self::CURRENT_VERSION);
    }

    public static function _processAdminRequest() {
        switch ($_POST['updateFD']) {
            case 'update':
                self::updateRepo();
                break;
        }
    }

    public static function updateRepo() {

        self::install();
    }

    public static function uninstall() {
        global $wpdb;
        $table_name1 = $wpdb->prefix . self::TABLE_NAME;
        $sql = "DROP TABLE IF EXISTS `" . $table_name1 . "`";
        $wpdb->query($sql);
    }

    public static function getFreeDomainsList() {
        global $wpdb;
        $sql = "SELECT * FROM " . $wpdb->prefix . self::TABLE_NAME . ' ORDER BY domain ASC';
        return $wpdb->get_results($sql);
    }

}
