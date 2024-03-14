<?php
if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.');
class ResAds_AdStat_DB 
{
    /**
     * DB Instance
     * @var wpdb
     */
    private $wpdb;
    /**
     * Construct
     * @global wpdb $wpdb
     */
    public function __construct() 
    {        
        global $wpdb;
        $this->wpdb = $wpdb;
    }    
    /**
     * Plus statistic of an ad
     * @param int $ad_id
     * @param int $views
     * @param int $clicks
     * @return boolean
     */
    public function plus($ad_id, $views, $clicks)
    {
        if(is_numeric($ad_id) && is_numeric($views) && is_numeric($clicks))
        {
            $insert_update = $this->wpdb->query(
                    $this->wpdb->prepare('INSERT INTO ' . $this->wpdb->prefix . 'resads_ad_statistik (resads_ad_ad_id, adstat_date, adstat_views, adstat_clicks) VALUES (%d, NOW(), %d, %d) ON DUPLICATE KEY UPDATE adstat_views = adstat_views + %d, adstat_clicks = adstat_clicks + %d',
                    $ad_id, $views, $clicks, $views, $clicks)
                    );
            if($insert_update !== false)
                return $insert_update;
        }
    }
    /**
     * Create Table
     */
    public function create_database_table()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $sql = "CREATE TABLE " . $this->wpdb->prefix . "resads_ad_statistik (
            adstat_id bigint(20) NOT NULL AUTO_INCREMENT,
            resads_ad_ad_id int(10) unsigned NOT NULL,
            adstat_date date DEFAULT NULL,
            adstat_views int(10) unsigned NOT NULL DEFAULT '0',
            adstat_clicks int(10) unsigned NOT NULL DEFAULT '0',
            PRIMARY KEY (adstat_id),
            UNIQUE KEY resads_ad_ad_id (resads_ad_ad_id, adstat_date)
          ) DEFAULT CHARSET=utf8;";
        dbDelta($sql);
    }
    /**
     * Delete Table
     */
    public function delete_database_table()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS " . $this->wpdb->prefix . 'resads_ad_statistik');
    }
}
?>