<?php
/**
 * @author karrikas
 */
class install
{
    var $debug = true;
    var $option_version_name = 'banner_manager_db_version';
    var $plugin_version = '1.4';

    private $table_banners;
    private $table_groups;
    private $table_stats;

    /**
     * Install banner-manager db.
     */
    public function __construct()
    {
        global $wpdb;

        // if the versión are same end install
        if($this->get_version()==$this->plugin_version)
        {
            if(!$this->debug) return;
        }

        // taulen izenak zehaztu
        $this->table_banners     = BM_TABLE_BANNERS;
        $this->table_groups     = BM_TABLE_GROUPS;
        $this->table_stats         = BM_TABLE_STATS;

        // upgrade tabla gehitu
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // plugina actibatzean executatu
        $this->create_tables();

        // plugina eguneratu behar duen bertsiora
        //$this->update_tables( $this->get_version() );

        // bertsioa gorde
        $this->set_version( $this->plugin_version );
    }

    /**
     * Create needed tables
     */
    public function create_tables()
    {
        global $wpdb;

        // GROUPS
        $sql = 'drop table '. $this->table_groups;
        dbDelta($sql);

        if($wpdb->get_var("show tables like '{$this->table_groups}'") != $this->table_groups) {
            $sql = "
                CREATE TABLE {$this->table_groups} (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    groups VARCHAR( 50 ) NOT NULL,
                    width INT NOT NULL,
                    height INT NOT NULL
                ) ENGINE=MyISAM ;
            ";

            dbDelta($sql);
        }

        // BANNERS
        $sql = 'drop table '. $this->table_banners;
        dbDelta($sql);

        if($wpdb->get_var("show tables like '{$this->table_banners}'") != $this->table_banners) {
            $sql = "
            CREATE TABLE {$this->table_banners} (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                id_category INT NOT NULL,
                title VARCHAR( 255 ) NOT NULL ,
                src VARCHAR( 255 ) NOT NULL ,
                link VARCHAR( 255 ) NOT NULL ,
                blank BOOLEAN NOT NULL,
                active BOOLEAN NOT NULL DEFAULT '1',
                groupkey VARCHAR( 30 ) NOT NULL
            );
            ";
            dbDelta($sql);
        }

        // STATS
        $sql = 'drop table '. $this->table_stats;
        dbDelta($sql);

        if($wpdb->get_var("show tables like '{$this->table_stats}'") != $this->table_stats) {
            $sql = "
            CREATE TABLE {$this->table_stats} (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                id_banner INT NOT NULL ,
                day DATE NOT NULL ,
                views INT NOT NULL ,
                clicks INT NOT NULL
            );
            ";
            dbDelta($sql);
        }
    }

    /**
     * Modifi initial sql
     * @param string $version
     */
    private function update_tables( $version )
    {
        switch( $version )
        {
            case '0.1':
                // add width and height
                // gaizki egindako eguneraketa bat tarteko
            case '0.2':
                // add width and height
                $sql = "
                CREATE TABLE {$this->table_banners} (
                    width INT NOT NULL,
                    height INT NOT NULL
                );
                ";
                dbDelta($sql);

            case '1.1':
                // aurreko eguneraketa zuzendu
                // add width and height
                $sql = "
                CREATE TABLE {$this->table_groups} (
                    width INT NOT NULL,
                    height INT NOT NULL
                );
                ";
                dbDelta($sql);
            case '1.2':
                // bannerra actibatu eta desatibatu
                $sql = "
                CREATE TABLE {$this->table_banners} (
                    active BOOLEAN NOT NULL DEFAULT '1'
                );
                ";
                dbDelta($sql);
            case '1.3':
                // taldekatu bannerrak ez errepikatzeko
                $sql = "
                CREATE TABLE {$this->table_banners} (
                    groupkey VARCHAR( 30 ) NOT NULL
                );
                ";
                $x = dbDelta($sql);


            case '1.4':

        }
    }

    /**
     * Get instaled version.
     */
    public function get_version()
    {
        return get_option($this->option_version_name);
    }

    /**
     * Set new version.
     */
    public function set_version( $version )
    {
        delete_option($this->option_version_name);
        add_option($this->option_version_name, $version);
    }
}

function call_install()
{
    new install();
}

// evento when activate plugin
register_activation_hook( BM_PLUGIN_FILE, 'call_install');

// debug install
add_action('init', 'call_install');
