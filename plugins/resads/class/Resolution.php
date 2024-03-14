<?php
if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.');
class ResAds_Resolution
{
    /**
     * Construct
     */
    public function __construct() 
    {

    }
    /**
     * Get Device bei width and height
     * @param int $width
     * @param int $height
     * @return array
     */
    public function get_device($width = false, $height = false)
    {
        $devices = array('smartphone' => 0, 'tablet' => 0, 'desktop' => 0);
        
        if($width > 0 && $height > 0)
        {
            if($width <= 400)
                $devices['smartphone'] = 1;
            elseif($width > 400 && $width <= 1000)
                $devices['tablet'] = 1;
            elseif($width > 1000)
                $devices['desktop'] = 1;
        }
        else
        {
            if(wp_is_mobile())
                $devices['smartphone'] = 1;
            else
                $devices['desktop'] = 1;
        }
        return $devices;
    }
    /**
     * Get resolutions by height and width
     * @param int $width
     * @param int $height
     * @return array
     */
    public function get_correct_resolutions($width = false, $height = false)
    {
        if(!$width && !$height)
        {
            $width = $this->check_cookie_width();
            $height =$this->check_cookie_height();
        }

        $devices = $this->get_device($width, $height);
        if($devices)
        {
            $key = array_search(1, $devices);
            if($key)
            {
                $Resolution_DB = new ResAds_Resolution_DB();
                $method = 'get_all_' . $key;
                if(method_exists($Resolution_DB, $method))
                {
                    return $Resolution_DB->$method();
                }
            }
        }
    }
    /**
     * returns cookie resads_browser_width if exists
     * @return int
     */
    public function check_cookie_width()
    {
        if(isset($_COOKIE['resads_browser_width']) && is_numeric($_COOKIE['resads_browser_width']))
            return $_COOKIE['resads_browser_width'];
    }
    /**
     * returns cookie resads_browser_height if exists
     * @return int
     */
    public function check_cookie_height()
    {
        if(isset($_COOKIE['resads_browser_height']) && is_numeric($_COOKIE['resads_browser_height']))
            return $_COOKIE['resads_browser_height'];
    }
}
/**
 * Resolution DB
 */
class ResAds_Resolution_DB 
{
    /**
     * Datenbank-Instanz
     * @var wpdb $wpdb
     */
    private $wpdb;
    /**
     * Construct
     */
    public function __construct() 
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }   
    /**
     * Erstellt die Datenbank Tabelle
     */
    public function create_database_table()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $sql = "CREATE TABLE " . $this->wpdb->prefix . "resads_resolution (
            res_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            res_banner_width int(4) unsigned NOT NULL,
            res_banner_height int(4) unsigned NOT NULL,
            res_smartphone int(1) unsigned NOT NULL,
            res_tablet int(1) unsigned NOT NULL,
            res_desktop int(1) unsigned NOT NULL,
            PRIMARY KEY  (res_id),
            UNIQUE KEY resads_resolution_width_height (res_banner_width, res_banner_height))";
        dbDelta($sql);
    }
    /**
     * Activate Inserts
     */
    public function activate_inserts()
    {
        $sql = "INSERT INTO " . $this->wpdb->prefix . "resads_resolution (res_id, res_banner_width, res_banner_height, res_smartphone, res_tablet, res_desktop) VALUES
            (1, 300, 250, 1, 1, 1),
            (2, 336, 280, 0, 1, 1),
            (3, 728, 90, 0, 0, 1),
            (4, 970, 90, 0, 0, 1),
            (5, 468, 60, 0, 1, 1),
            (6, 234, 60, 1, 1, 0),
            (7, 120, 60, 1, 0, 0),
            (8, 300, 60, 1, 0, 0),
            (9, 120, 600, 0, 1, 1),
            (10, 160, 600, 0, 1, 1),
            (11, 250, 250, 1, 1, 1),
            (12, 200, 200, 1, 1, 1),
            (13, 125, 125, 1, 1, 1),
            (14, 88, 31, 1, 1, 1),
            (15, 300, 50, 1, 0, 0),
            (16, 300, 600, 0, 1, 1)";
        $this->wpdb->query($sql);
    }
    /**
     * Update Inserts
     */
    public function update_inserts()
    {
        /** Banner 300x600 hinzufuegen */
        $banner_300_600 = $this->wpdb->get_row('SELECT * FROM ' . $this->wpdb->prefix . 'resads_resolution WHERE res_banner_width = 300 AND res_banner_height = 600');
        if($banner_300_600 == NULL)
            $this->wpdb->query('INSERT INTO ' . $this->wpdb->prefix . 'resads_resolution (res_banner_width, res_banner_height, res_smartphone, res_tablet, res_desktop) VALUES (300, 600, 0, 1, 1)');
        
        $this->wpdb->query('UPDATE ' . $this->wpdb->prefix . 'resads_resolution SET res_desktop = 1 WHERE res_banner_width = 300 AND res_banner_height = 600');
        
        
        $banner_320_50 = $this->wpdb->get_row('SELECT * FROM ' . $this->wpdb->prefix . 'resads_resolution WHERE res_banner_width = 320 AND res_banner_height = 50');
        if($banner_320_50 == NULL)
            $this->wpdb->query('INSERT INTO ' . $this->wpdb->prefix . 'resads_resolution (res_banner_width, res_banner_height, res_smartphone, res_tablet, res_desktop) VALUES (320, 50, 1, 0, 0)');
        
        
    }
    /**
     * Loescht die Datenbank Tabelle
     */
    public function delete_database_table()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS " . $this->wpdb->prefix . 'resads_resolution');
    }
    /**
     * Returns an Resolution by Banner-Size
     * @param int $width
     * @param int $height
     * @return array
     */
    public function get_by_banner_size($width, $height)
    {
        if(is_numeric($width) && $width > 0 && is_numeric($height) && $height > 0)
        {
            return $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->wpdb->prefix . 'resads_resolution WHERE res_banner_width = %d AND res_banner_height = %d', $width, $height), ARRAY_A);
        }
    }    
    /**
     * Returns all resoultions
     * @return array
     */
    public function get_all()
    {
        return $this->wpdb->get_results('SELECT * FROM ' . $this->wpdb->prefix . 'resads_resolution ORDER BY res_banner_width', ARRAY_A);
    }
    /**
     * Returns all where smartphone is true
     * @return array
     */
    public function get_all_smartphone()
    {
        return $this->wpdb->get_results('SELECT * FROM ' . $this->wpdb->prefix . 'resads_resolution WHERE res_smartphone = 1', ARRAY_A);
    }
    /**
     * Returns all where tablet is true
     * @return array
     */
    public function get_all_tablet()
    {
        return $this->wpdb->get_results('SELECT * FROM ' . $this->wpdb->prefix . 'resads_resolution WHERE res_tablet = 1', ARRAY_A);
    }
    /**
     * Returns all where desktop is true
     * @return array
     */
    public function get_all_desktop()
    {
        return $this->wpdb->get_results('SELECT * FROM ' . $this->wpdb->prefix . 'resads_resolution WHERE res_desktop = 1', ARRAY_A);
    }
}
?>