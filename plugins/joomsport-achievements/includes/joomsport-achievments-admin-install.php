<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'pages' . DIRECTORY_SEPARATOR . 'joomsport-achievments-page-extrafields.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'pages' . DIRECTORY_SEPARATOR . 'joomsport-achievments-page-stages.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'pages' . DIRECTORY_SEPARATOR . 'joomsport-achievments-page-resultfields.php';

class JoomSportAchievmentsAdminInstall {
    
    public static function init(){
        self::joomsport_languages();
        add_action( 'admin_menu', array('JoomSportAchievmentsAdminInstall', 'create_menu') );
        
        self::_defineTables();
    }


    public static function create_menu() {

        add_menu_page( __('JoomSport Achievements', 'joomsport-achievements'), __('JoomSport Achievements', 'joomsport-achievements'),
            'manage_options', 'joomsport_achievments', array('JoomSportAchievmentsAdminInstall', 'action'),
            plugins_url( '../assets/images/cup.png', __FILE__ ) );
        add_submenu_page( 'joomsport_achievments', __( 'League', 'joomsport-achievements' ), __( 'Leagues', 'joomsport-achievements' ), 'manage_options', 'edit-tags.php?taxonomy=jsprt_achv_league&post_type=jsprt_achv_season');
        
        $obj = JoomSportAchievmentsExtraField_Plugin::get_instance();
        $hook = add_submenu_page( 'joomsport_achievments', __( 'Extra field', 'joomsport-achievements' ), __( 'Extra fields', 'joomsport-achievements' ), 'manage_options', 'jsprtachv-page-extrafields', function(){ $obj = JoomSportAchievmentsExtraField_Plugin::get_instance();$obj->plugin_settings_page();});      
	add_action( "load-$hook", function(){ $obj = JoomSportAchievmentsExtraField_Plugin::get_instance();$obj->screen_option();}  );
        add_submenu_page( 'options.php', __( 'Extra field New', 'joomsport-achievements' ), __( 'Extra field New', 'joomsport-achievements' ), 'manage_options', 'jsprtachv-extrafields-form', array('JoomSportAchievmentsExtraFieldsNew_Plugin', 'view'));
        
        $obj = JoomSportAchievmentsStages_Plugin::get_instance();
        $hook = add_submenu_page( 'joomsport_achievments', __( 'Stage Category', 'joomsport-achievements' ), __( 'Stage Categories', 'joomsport-achievements' ), 'manage_options', 'jsprtachv-page-gamestages', function(){ $obj = JoomSportAchievmentsStages_Plugin::get_instance();$obj->plugin_settings_page();});      
	add_action( "load-$hook", function(){ $obj = JoomSportAchievmentsStages_Plugin::get_instance();$obj->screen_option();}  );
        add_submenu_page( 'options.php', __( 'Stage Category New', 'joomsport-achievements' ), __( 'Stage Category New', 'joomsport-achievements' ), 'manage_options', 'jsprtachv-gamestages-form', array('JoomSportAchievmentsStagesNew_Plugin', 'view'));
        
        $obj = JoomSportAchievmentsResultFields_Plugin::get_instance();
        $hook = add_submenu_page( 'joomsport_achievments', __( 'Result Field', 'joomsport-achievements' ), __( 'Result fields', 'joomsport-achievements' ), 'manage_options', 'jsprtachv-page-resfields', function(){ $obj = JoomSportAchievmentsResultFields_Plugin::get_instance();$obj->plugin_settings_page();});      
	add_action( "load-$hook", function(){ $obj = JoomSportAchievmentsResultFields_Plugin::get_instance();$obj->screen_option();}  );
        add_submenu_page( 'options.php', __( 'Result Field New', 'joomsport-achievements' ), __( 'Result Field New', 'joomsport-achievements' ), 'manage_options', 'jsprtachv-resfields-form', array('JoomSportAchievmentsResultFieldsNew_Plugin', 'view'));
        
        // javascript
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-uidp-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

        add_action('admin_enqueue_scripts', array('JoomSportAchievmentsAdminInstall', 'joomsport_admin_js'));
        add_action('admin_enqueue_scripts', array('JoomSportAchievmentsAdminInstall', 'joomsport_admin_css'));

        wp_enqueue_style('jsachvcssfont','//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
        
    }

    public static function joomsport_fe_wp_head(){
        global $post,$post_type;
        $jsArray = array("jsprt_achv_season","jsprt_achv_stage","jsprt_achv_team","jsprt_achv_player");
        if(in_array($post_type, $jsArray) || isset($_REQUEST['wpjoomsport']) || get_query_var('joomsport_tournament') || get_query_var('joomsport_matchday') || get_query_var('joomsport_club')){
             wp_enqueue_script('jsbootstrap-js','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',array ( 'jquery' ));

             wp_enqueue_script('jstablesorter',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/jquery.tablesorter.min.js');
             //wp_enqueue_script('jsselect2',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/select2.min.js');
             //wp_enqueue_script('jsjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/joomsport.js');

             wp_enqueue_style('jscssbtstrpachv',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/btstrp.css');
             wp_enqueue_style('jscssjoomsportachv',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport_achv.css');
             /*wp_enqueue_style('jscssbracket',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/drawBracket.css');
             wp_enqueue_style('jscssnailthumb',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/jquery.nailthumb.1.1.css');
             wp_enqueue_style('jscsslightbox',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/lightbox.css');
             wp_enqueue_style('jscssselect2',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/select2.min.css');
              */
             wp_enqueue_style('jscssfont','//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
              
            
             wp_enqueue_script('jquery-ui-datepicker');
             wp_enqueue_style('jquery-uidp-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        }
        
     }

    public static function action(){
    
    }
    
    public static function joomsport_languages() {
            $locale = apply_filters( 'plugin_locale', get_locale(), 'joomsport-achievements' );

            load_textdomain( 'joomsport-achievements', plugin_basename( dirname( __FILE__ ) . "/../languages/joomsport-achievements-$locale.mo" ));
            load_plugin_textdomain( 'joomsport-achievements', false, plugin_basename( dirname( __FILE__ ) . "/../languages" ) );
    }
    public static function joomsport_admin_js(){
        global $post_type;
        wp_enqueue_script( 'joomsport-achv-jchosen-js', plugins_url('../assets/js/chosen.jquery.min.js', __FILE__),array('jquery') );
        
        wp_enqueue_script( 'joomsport-achv-sorttable-js', plugins_url('../assets/js/jquery.sorttable.js', __FILE__),array('jquery', 'jquery-ui-sortable') );
        
        wp_enqueue_script( 'joomsport-achv-common-js', plugins_url('../assets/js/common.js', __FILE__) );
        
        wp_enqueue_media();
    }
    
    public static function joomsport_admin_css(){
        global $post_type;
        wp_enqueue_style( 'joomsport-achv-common-css', plugins_url('../assets/css/common.css', __FILE__) );
        wp_enqueue_style( 'joomsport-achv-jchosen-css', plugins_url('../assets/css/chosen.min.css', __FILE__) );
        
    }
    
    public static function _defineTables()
    {
        global $wpdb;
        $wpdb->jsprtachv_ef = $wpdb->prefix . 'jsprtachv_extra_fields';
        $wpdb->jsprtachv_ef_select = $wpdb->prefix . 'jsprtachv_extra_select';
        $wpdb->jsprtachv_stages = $wpdb->prefix . 'jsprtachv_stages';
        $wpdb->jsprtachv_stages_val = $wpdb->prefix . 'jsprtachv_stages_val';
        $wpdb->jsprtachv_country = $wpdb->prefix . 'jsprtachv_country';
        $wpdb->jsprtachv_results_fields = $wpdb->prefix . 'jsprtachv_results_fields';
        $wpdb->jsprtachv_stage_result = $wpdb->prefix . 'jsprtachv_stage_result';
    }

    public static function _installdb(){
        global $wpdb;
        flush_rewrite_rules();
        self::_defineTables();
        
        include_once( ABSPATH.'/wp-admin/includes/upgrade.php' );

        $charset_collate = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
                if ( ! empty($wpdb->charset) )
                        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                if ( ! empty($wpdb->collate) )
                        $charset_collate .= " COLLATE $wpdb->collate";
        }
        $create_ef_sql = "CREATE TABLE {$wpdb->jsprtachv_ef} (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `name` varchar(255) NOT NULL DEFAULT '',
                                        `published` char(1) NOT NULL DEFAULT '1',
                                        `type` char(1) NOT NULL DEFAULT '0',
                                        `ordering` int(11) NOT NULL DEFAULT '0',
                                        `field_type` char(1) NOT NULL DEFAULT '0',
                                        `faccess` varchar(1) NOT NULL DEFAULT '0',
                                        PRIMARY KEY ( `id` )) $charset_collate;";
        maybe_create_table( $wpdb->jsprtachv_ef, $create_ef_sql );
        
        $create_ef_select_sql = "CREATE TABLE {$wpdb->jsprtachv_ef_select} (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `fid` int(11) NOT NULL default '0',
                                        `sel_value` varchar(255) NOT NULL default '',
                                        `eordering` int(11) NOT NULL default '0',
                                        PRIMARY KEY  (`id`),
                                        KEY `fid` (`fid`)) $charset_collate;";
        maybe_create_table( $wpdb->jsprtachv_ef_select, $create_ef_select_sql );
        
        $create_ef_sql = "CREATE TABLE {$wpdb->jsprtachv_stages} (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `name` varchar(255) NOT NULL DEFAULT '',
                                        `published` char(1) NOT NULL DEFAULT '1',
                                        `ordering` int(11) NOT NULL DEFAULT '0',
                                        `devide` VARCHAR(1) NOT NULL DEFAULT '0',
                                        PRIMARY KEY ( `id` )) $charset_collate;";
        maybe_create_table( $wpdb->jsprtachv_stages, $create_ef_sql );
        
        $create_ef_select_sql = "CREATE TABLE {$wpdb->jsprtachv_stages_val} (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `fid` int(11) NOT NULL default '0',
                                        `sel_value` varchar(255) NOT NULL default '',
                                        `eordering` int(11) NOT NULL default '0',
                                        PRIMARY KEY  (`id`),
                                        KEY `fid` (`fid`)) $charset_collate;";
        maybe_create_table( $wpdb->jsprtachv_stages_val, $create_ef_select_sql );
        
        
        $create_ef_sql = "CREATE TABLE {$wpdb->jsprtachv_results_fields} (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `name` varchar(255) NOT NULL DEFAULT '',
                                        `published` char(1) NOT NULL DEFAULT '1',
                                        `ordering` int(11) NOT NULL DEFAULT '0',
                                        `field_type` char(1) NOT NULL DEFAULT '0',
                                        `faccess` varchar(1) NOT NULL DEFAULT '0',
                                        `options` TEXT NOT NULL DEFAULT '',
                                        PRIMARY KEY ( `id` )) $charset_collate;";
        maybe_create_table( $wpdb->jsprtachv_results_fields, $create_ef_sql );
        $is_col = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->jsprtachv_results_fields} LIKE 'complex'");
        
        if (empty($is_col)) {
            $wpdb->query("ALTER TABLE ".$wpdb->jsprtachv_results_fields." ADD `complex` VARCHAR(1) NOT NULL DEFAULT '0'");
        }
        
        $create_ef_select_sql = "CREATE TABLE {$wpdb->jsprtachv_country} (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `ccode` varchar(2) NOT NULL default '',
                                        `country` varchar(200) NOT NULL default '',
                                        PRIMARY KEY  (`id`)) $charset_collate;";
        maybe_create_table( $wpdb->jsprtachv_country, $create_ef_select_sql );
        
        $create_ef_sql = "CREATE TABLE {$wpdb->jsprtachv_stage_result} (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `stage_id` int(11) NOT NULL,
                                        `partic_id` int(11) NOT NULL,
                                        `rank` SMALLINT NOT NULL DEFAULT '0',
                                        `points` DOUBLE NOT NULL DEFAULT '0',
                                        PRIMARY KEY ( `id` )) $charset_collate;";
        maybe_create_table( $wpdb->jsprtachv_stage_result, $create_ef_sql );
        
        if(!$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->jsprtachv_country}")){
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 1,'ccode' => 'AF', 'country' => 'Afghanistan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 2,'ccode' => 'AX', 'country' => 'Aland Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 3,'ccode' => 'AL', 'country' => 'Albania'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 4,'ccode' => 'DZ', 'country' => 'Algeria'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 5,'ccode' => 'AS', 'country' => 'American Samoa'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 6,'ccode' => 'AD', 'country' => 'Andorra'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 7,'ccode' => 'AO', 'country' => 'Angola'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 8,'ccode' => 'AI', 'country' => 'Anguilla'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 9,'ccode' => 'AQ', 'country' => 'Antarctica'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 10,'ccode' => 'AG', 'country' => 'Antigua and Barbuda'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 11,'ccode' => 'AR', 'country' => 'Argentina'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 12,'ccode' => 'AM', 'country' => 'Armenia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 13,'ccode' => 'AW', 'country' => 'Aruba'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 14,'ccode' => 'AU', 'country' => 'Australia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 15,'ccode' => 'AT', 'country' => 'Austria'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 16,'ccode' => 'AZ', 'country' => 'Azerbaijan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 17,'ccode' => 'BS', 'country' => 'Bahamas'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 18,'ccode' => 'BH', 'country' => 'Bahrain'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 19,'ccode' => 'BD', 'country' => 'Bangladesh'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 20,'ccode' => 'BB', 'country' => 'Barbados'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 21,'ccode' => 'BY', 'country' => 'Belarus'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 22,'ccode' => 'BE', 'country' => 'Belgium'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 23,'ccode' => 'BZ', 'country' => 'Belize'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 24,'ccode' => 'BJ', 'country' => 'Benin'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 25,'ccode' => 'BM', 'country' => 'Bermuda'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 26,'ccode' => 'BT', 'country' => 'Bhutan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 27,'ccode' => 'BO', 'country' => 'Bolivia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 28,'ccode' => 'BA', 'country' => 'Bosnia and Herzegovina'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 29,'ccode' => 'BW', 'country' => 'Botswana'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 30,'ccode' => 'BV', 'country' => 'Bouvet Island'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 31,'ccode' => 'BR', 'country' => 'Brazil'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 32,'ccode' => 'IO', 'country' => 'British Indian Ocean Territory'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 33,'ccode' => 'BN', 'country' => 'Brunei Darussalam'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 34,'ccode' => 'BG', 'country' => 'Bulgaria'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 35,'ccode' => 'BF', 'country' => 'Burkina Faso'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 36,'ccode' => 'BI', 'country' => 'Burundi'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 37,'ccode' => 'KH', 'country' => 'Cambodia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 38,'ccode' => 'CM', 'country' => 'Cameroon'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 39,'ccode' => 'CA', 'country' => 'Canada'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 40,'ccode' => 'CV', 'country' => 'Cape Verde'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 41,'ccode' => 'KY', 'country' => 'Cayman Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 42,'ccode' => 'CF', 'country' => 'Central African Republic'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 43,'ccode' => 'TD', 'country' => 'Chad'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 44,'ccode' => 'CL', 'country' => 'Chile'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 45,'ccode' => 'CN', 'country' => 'China'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 46,'ccode' => 'CX', 'country' => 'Christmas Island'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 47,'ccode' => 'CC', 'country' => 'Cocos (Keeling) Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 48,'ccode' => 'CO', 'country' => 'Colombia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 49,'ccode' => 'KM', 'country' => 'Comoros'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 50,'ccode' => 'CG', 'country' => 'Congo'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 51,'ccode' => 'CD', 'country' => 'Congo, The Democratic Republic of the'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 52,'ccode' => 'CK', 'country' => 'Cook Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 53,'ccode' => 'CR', 'country' => 'Costa Rica'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 54,'ccode' => 'CI', 'country' => 'Cote D\'Ivoire'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 55,'ccode' => 'HR', 'country' => 'Croatia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 56,'ccode' => 'CU', 'country' => 'Cuba'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 57,'ccode' => 'CY', 'country' => 'Cyprus'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 58,'ccode' => 'CZ', 'country' => 'Czech Republic'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 59,'ccode' => 'DK', 'country' => 'Denmark'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 60,'ccode' => 'DJ', 'country' => 'Djibouti'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 61,'ccode' => 'DM', 'country' => 'Dominica'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 62,'ccode' => 'DO', 'country' => 'Dominican Republic'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 63,'ccode' => 'EC', 'country' => 'Ecuador'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 64,'ccode' => 'EG', 'country' => 'Egypt'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 65,'ccode' => 'SV', 'country' => 'El Salvador'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 66,'ccode' => 'GQ', 'country' => 'Equatorial Guinea'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 67,'ccode' => 'ER', 'country' => 'Eritrea'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 68,'ccode' => 'EE', 'country' => 'Estonia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 69,'ccode' => 'ET', 'country' => 'Ethiopia'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 70,'ccode' => 'FK', 'country' => 'Falkland Islands (Malvinas)'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 71,'ccode' => 'FO', 'country' => 'Faroe Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 72,'ccode' => 'FJ', 'country' => 'Fiji'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 73,'ccode' => 'FI', 'country' => 'Finland'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 74,'ccode' => 'FR', 'country' => 'France'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 75,'ccode' => 'GF', 'country' => 'French Guiana'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 76,'ccode' => 'PF', 'country' => 'French Polynesia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 77,'ccode' => 'TF', 'country' => 'French Southern Territories'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 78,'ccode' => 'GA', 'country' => 'Gabon'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 79,'ccode' => 'GM', 'country' => 'Gambia'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 80,'ccode' => 'GE', 'country' => 'Georgia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 81,'ccode' => 'DE', 'country' => 'Germany'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 82,'ccode' => 'GH', 'country' => 'Ghana'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 83,'ccode' => 'GI', 'country' => 'Gibraltar'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 84,'ccode' => 'GR', 'country' => 'Greece'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 85,'ccode' => 'GL', 'country' => 'Greenland'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 86,'ccode' => 'GD', 'country' => 'Grenada'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 87,'ccode' => 'GP', 'country' => 'Guadeloupe'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 88,'ccode' => 'GU', 'country' => 'Guam'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 89,'ccode' => 'GT', 'country' => 'Guatemala'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 90,'ccode' => 'GG', 'country' => 'Guernsey'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 91,'ccode' => 'GN', 'country' => 'Guinea'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 92,'ccode' => 'GW', 'country' => 'Guinea-Bissau'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 93,'ccode' => 'GY', 'country' => 'Guyana'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 94,'ccode' => 'HT', 'country' => 'Haiti'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 95,'ccode' => 'HM', 'country' => 'Heard Island and McDonald Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 96,'ccode' => 'VA', 'country' => 'Holy See (Vatican City State)'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 97,'ccode' => 'HN', 'country' => 'Honduras'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 98,'ccode' => 'HK', 'country' => 'Hong Kong'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 99,'ccode' => 'HU', 'country' => 'Hungary'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 100,'ccode' => 'IS', 'country' => 'Iceland'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 101,'ccode' => 'IN', 'country' => 'India'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 102,'ccode' => 'ID', 'country' => 'Indonesia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 103,'ccode' => 'IR', 'country' => 'Iran, Islamic Republic of'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 104,'ccode' => 'IQ', 'country' => 'Iraq'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 105,'ccode' => 'IE', 'country' => 'Ireland'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 107,'ccode' => 'IL', 'country' => 'Israel'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 108,'ccode' => 'IT', 'country' => 'Italy'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 109,'ccode' => 'JM', 'country' => 'Jamaica'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 110,'ccode' => 'JP', 'country' => 'Japan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 111,'ccode' => 'JE', 'country' => 'Jersey'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 112,'ccode' => 'JO', 'country' => 'Jordan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 113,'ccode' => 'KZ', 'country' => 'Kazakhstan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 114,'ccode' => 'KE', 'country' => 'Kenya'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 115,'ccode' => 'KI', 'country' => 'Kiribati'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 116,'ccode' => 'KP', 'country' => 'Korea, Democratic People\'s Republic of'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 117,'ccode' => 'KR', 'country' => 'Korea, Republic of'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 118,'ccode' => 'KW', 'country' => 'Kuwait'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 119,'ccode' => 'KG', 'country' => 'Kyrgyzstan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 120,'ccode' => 'LA', 'country' => 'Lao People\'s Democratic Republic'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 121,'ccode' => 'LV', 'country' => 'Latvia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 122,'ccode' => 'LB', 'country' => 'Lebanon'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 123,'ccode' => 'LS', 'country' => 'Lesotho'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 124,'ccode' => 'LR', 'country' => 'Liberia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 125,'ccode' => 'LY', 'country' => 'Libyan Arab Jamahiriya'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 126,'ccode' => 'LI', 'country' => 'Liechtenstein'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 127,'ccode' => 'LT', 'country' => 'Lithuania'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 128,'ccode' => 'LU', 'country' => 'Luxembourg'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 129,'ccode' => 'MO', 'country' => 'Macao'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 130,'ccode' => 'MK', 'country' => 'Macedonia, The Former Yugoslav Republic of'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 131,'ccode' => 'MG', 'country' => 'Madagascar'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 132,'ccode' => 'MW', 'country' => 'Malawi'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 133,'ccode' => 'MY', 'country' => 'Malaysia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 134,'ccode' => 'MV', 'country' => 'Maldives'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 135,'ccode' => 'ML', 'country' => 'Mali'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 136,'ccode' => 'MT', 'country' => 'Malta'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 137,'ccode' => 'MH', 'country' => 'Marshall Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 138,'ccode' => 'MQ', 'country' => 'Martinique'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 139,'ccode' => 'MR', 'country' => 'Mauritania'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 140,'ccode' => 'MU', 'country' => 'Mauritius'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 141,'ccode' => 'YT', 'country' => 'Mayotte'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 142,'ccode' => 'MX', 'country' => 'Mexico'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 143,'ccode' => 'FM', 'country' => 'Micronesia, Federated States of'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 144,'ccode' => 'MD', 'country' => 'Moldova, Republic of'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 145,'ccode' => 'MC', 'country' => 'Monaco'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 146,'ccode' => 'MN', 'country' => 'Mongolia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 147,'ccode' => 'ME', 'country' => 'Montenegro'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 148,'ccode' => 'MS', 'country' => 'Montserrat'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 149,'ccode' => 'MA', 'country' => 'Morocco'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 150,'ccode' => 'MZ', 'country' => 'Mozambique'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 151,'ccode' => 'MM', 'country' => 'Myanmar'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 152,'ccode' => 'NA', 'country' => 'Namibia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 153,'ccode' => 'NR', 'country' => 'Nauru'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 154,'ccode' => 'NP', 'country' => 'Nepal'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 155,'ccode' => 'NL', 'country' => 'Netherlands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 157,'ccode' => 'NC', 'country' => 'New Caledonia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 158,'ccode' => 'NZ', 'country' => 'New Zealand'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 159,'ccode' => 'NI', 'country' => 'Nicaragua'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 160,'ccode' => 'NE', 'country' => 'Niger'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 161,'ccode' => 'NG', 'country' => 'Nigeria'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 162,'ccode' => 'NU', 'country' => 'Niue'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 163,'ccode' => 'NF', 'country' => 'Norfolk Island'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 164,'ccode' => 'MP', 'country' => 'Northern Mariana Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 165,'ccode' => 'NO', 'country' => 'Norway'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 166,'ccode' => 'OM', 'country' => 'Oman'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 167,'ccode' => 'PK', 'country' => 'Pakistan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 168,'ccode' => 'PW', 'country' => 'Palau'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 169,'ccode' => 'PS', 'country' => 'Palestinian Territory, Occupied'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 170,'ccode' => 'PA', 'country' => 'Panama'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 171,'ccode' => 'PG', 'country' => 'Papua New Guinea'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 172,'ccode' => 'PY', 'country' => 'Paraguay'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 173,'ccode' => 'PE', 'country' => 'Peru'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 174,'ccode' => 'PH', 'country' => 'Philippines'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 175,'ccode' => 'PN', 'country' => 'Pitcairn'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 176,'ccode' => 'PL', 'country' => 'Poland'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 177,'ccode' => 'PT', 'country' => 'Portugal'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 178,'ccode' => 'PR', 'country' => 'Puerto Rico'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 179,'ccode' => 'QA', 'country' => 'Qatar'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 180,'ccode' => 'RE', 'country' => 'Reunion'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 181,'ccode' => 'RO', 'country' => 'Romania'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 182,'ccode' => 'RU', 'country' => 'Russian Federation'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 183,'ccode' => 'RW', 'country' => 'Rwanda'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 185,'ccode' => 'SH', 'country' => 'Saint Helena'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 186,'ccode' => 'KN', 'country' => 'Saint Kitts and Nevis'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 187,'ccode' => 'LC', 'country' => 'Saint Lucia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 188,'ccode' => 'MF', 'country' => 'Saint Martin'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 189,'ccode' => 'PM', 'country' => 'Saint Pierre and Miquelon'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 190,'ccode' => 'VC', 'country' => 'Saint Vincent and the Grenadines'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 191,'ccode' => 'WS', 'country' => 'Samoa'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 192,'ccode' => 'SM', 'country' => 'San Marino'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 193,'ccode' => 'ST', 'country' => 'Sao Tome and Principe'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 194,'ccode' => 'SA', 'country' => 'Saudi Arabia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 195,'ccode' => 'SN', 'country' => 'Senegal'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 196,'ccode' => 'RS', 'country' => 'Serbia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 197,'ccode' => 'SC', 'country' => 'Seychelles'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 198,'ccode' => 'SL', 'country' => 'Sierra Leone'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 199,'ccode' => 'SG', 'country' => 'Singapore'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 200,'ccode' => 'SK', 'country' => 'Slovakia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 201,'ccode' => 'SI', 'country' => 'Slovenia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 202,'ccode' => 'SB', 'country' => 'Solomon Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 203,'ccode' => 'SO', 'country' => 'Somalia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 204,'ccode' => 'ZA', 'country' => 'South Africa'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 205,'ccode' => 'GS', 'country' => 'South Georgia and the South Sandwich Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 206,'ccode' => 'ES', 'country' => 'Spain'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 207,'ccode' => 'LK', 'country' => 'Sri Lanka'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 208,'ccode' => 'SD', 'country' => 'Sudan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 209,'ccode' => 'SR', 'country' => 'Suriname'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 210,'ccode' => 'SJ', 'country' => 'Svalbard and Jan Mayen'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 211,'ccode' => 'SZ', 'country' => 'Swaziland'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 212,'ccode' => 'SE', 'country' => 'Sweden'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 213,'ccode' => 'CH', 'country' => 'Switzerland'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 214,'ccode' => 'SY', 'country' => 'Syrian Arab Republic'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 215,'ccode' => 'TW', 'country' => 'Taiwan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 216,'ccode' => 'TJ', 'country' => 'Tajikistan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 217,'ccode' => 'TZ', 'country' => 'Tanzania, United Republic of'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 218,'ccode' => 'TH', 'country' => 'Thailand'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 219,'ccode' => 'TL', 'country' => 'Timor-Leste'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 220,'ccode' => 'TG', 'country' => 'Togo'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 221,'ccode' => 'TK', 'country' => 'Tokelau'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 222,'ccode' => 'TO', 'country' => 'Tonga'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 223,'ccode' => 'TT', 'country' => 'Trinidad and Tobago'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 224,'ccode' => 'TN', 'country' => 'Tunisia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 225,'ccode' => 'TR', 'country' => 'Turkey'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 226,'ccode' => 'TM', 'country' => 'Turkmenistan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 227,'ccode' => 'TC', 'country' => 'Turks and Caicos Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 228,'ccode' => 'TV', 'country' => 'Tuvalu'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 229,'ccode' => 'UG', 'country' => 'Uganda'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 230,'ccode' => 'UA', 'country' => 'Ukraine'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 231,'ccode' => 'AE', 'country' => 'United Arab Emirates'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 232,'ccode' => 'GB', 'country' => 'United Kingdom'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 233,'ccode' => 'US', 'country' => 'United States'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 234,'ccode' => 'UM', 'country' => 'United States Minor Outlying Islands'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 235,'ccode' => 'UY', 'country' => 'Uruguay'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 236,'ccode' => 'UZ', 'country' => 'Uzbekistan'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 237,'ccode' => 'VU', 'country' => 'Vanuatu'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 238,'ccode' => 'VE', 'country' => 'Venezuela'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 239,'ccode' => 'VN', 'country' => 'Viet Nam'),array("%d","%s","%s"));      
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 240,'ccode' => 'VG', 'country' => 'Virgin Islands, British'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 241,'ccode' => 'VI', 'country' => 'Virgin Islands, U.S.'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 242,'ccode' => 'WF', 'country' => 'Wallis And Futuna'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 243,'ccode' => 'EH', 'country' => 'Western Sahara'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 244,'ccode' => 'YE', 'country' => 'Yemen'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 245,'ccode' => 'ZM', 'country' => 'Zambia'),array("%d","%s","%s"));
            $wpdb->insert($wpdb->jsprtachv_country,array('id' => 246,'ccode' => 'ZW', 'country' => 'Zimbabwe'),array("%d","%s","%s"));

        }
        
        $is_col = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->jsprtachv_ef} LIKE 'display_table'");

        if (empty($is_col)) {
          $wpdb->query("ALTER TABLE ".$wpdb->jsprtachv_ef." ADD `display_table` VARCHAR(1) NULL DEFAULT '0'");
        }
    }
    
}

add_action( 'init', array( 'JoomSportAchievmentsAdminInstall', 'init' ), 4);
add_action( 'wp_enqueue_scripts', array('JoomSportAchievmentsAdminInstall','joomsport_fe_wp_head') );

add_action('init', 'joomsport_achievments_myStartSessionJS', 1);
function joomsport_achievments_myStartSessionJS() {
    if(!session_id()) {
        @session_start(
            array('read_and_close' => true)
        );
    }
}

add_filter( 'custom_menu_order', 'wpsejs_joomsport_achv_submenu_order' );

function wpsejs_joomsport_achv_submenu_order( $menu_ord ) 
{
    global $submenu;

    $sort_array = array(
        __('Leagues','joomsport-achievements'),
        _x( 'Seasons', 'Admin menu name Seasons', 'joomsport-achievements' ),
        __('Stages','joomsport-achievements'),
       
        _x('Players','Admin menu name Players','joomsport-achievements'),

        __('Extra fields','joomsport-achievements'),
        __('Stage Categories','joomsport-achievements'),
        __('Result fields','joomsport-achievements')
    );

    $arr = array();
    if(count($sort_array)){
        foreach ($sort_array as $sarr) {
            if(isset($submenu['joomsport_achievments']) && count($submenu['joomsport_achievments'])){
                foreach ($submenu['joomsport_achievments'] as $sub) {
                    if($sub[0] == $sarr){
                        $arr[] = $sub;
                    }
                }
            }
        }
    }
    
    $submenu['joomsport_achievments'] = $arr;

    return $menu_ord;
}

if(!function_exists('joomsportachv_set_current_menu')){

    function joomsportachv_set_current_menu($parent_file){
        global $submenu_file, $current_screen, $pagenow, $plugin_page;

        $ptypes = array("jsprt_achv_stage","jsprt_achv_season","jsprt_achv_player");
        // Set the submenu as active/current while anywhere in your Custom Post Type (nwcm_news)
        if(in_array($current_screen->post_type,$ptypes)) {

            if($pagenow == 'post.php'){
                
                $submenu_file = 'edit.php?post_type='.$current_screen->post_type;
                
            }

            

            $parent_file = 'joomsport_achievments';

        }
        if($current_screen->id == 'admin_page_jsprtachv-extrafields-form'){
            $parent_file = 'joomsport_achievments';
            $submenu_file = 'jsprtachv-page-extrafields';
            $plugin_page = 'jsprtachv-page-extrafields';
        }
        if($current_screen->id == 'admin_page_jsprtachv-gamestages-form'){
            $parent_file = 'joomsport_achievments';
            $submenu_file = 'jsprtachv-page-gamestages';
            $plugin_page = 'jsprtachv-page-gamestages';
        }
        if($current_screen->id == 'admin_page_jsprtachv-resfields-form'){
            $parent_file = 'joomsport_achievments';
            $submenu_file = 'jsprtachv-page-resfields';
            $plugin_page = 'jsprtachv-page-resfields';
        }
       
        
        return $parent_file;

    }

    add_filter('parent_file', 'joomsportachv_set_current_menu',10,1);

}


function jsarch_deactivation_popup() {
    $ignorePop = get_option('jsarch_deactivation_popup',0);
    if(!$ignorePop){
        wp_enqueue_style( 'wp-pointer' );
        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_script( 'utils' ); // for user settings
    ?>
        <script type="text/javascript">
        jQuery('tr[data-slug="joomsport-achievements"] .deactivate a').click(function(){
            var content_html = '<h3><?php echo __('Please share the reason of deactivation.','joomsport-achievements')?></h3>';
            content_html += '<div class="jsportPopUl"><ul style="overflow:hidden;">';
            content_html += '<li><input id="jsDeactivateReason1" type="radio" name="jsDeactivateReason" value="1" /><label for="jsDeactivateReason1"><?php echo __('Plugin is too complicated','joomsport-achievements')?></label><textarea name="jsDeactivateReason1_text" id="jsDeactivateReason1_text" placeholder="<?php echo __('What step did actually stop you?','joomsport-achievements')?>"></textarea></li>';
            content_html += '<li><input id="jsDeactivateReason2" type="radio" name="jsDeactivateReason" value="2" /><label for="jsDeactivateReason2"><?php echo __('I miss some features','joomsport-achievements')?></label><textarea name="jsDeactivateReason2_text" id="jsDeactivateReason2_text" placeholder="<?php echo __('What features did you miss?','joomsport-achievements')?>"></textarea></li>';
            content_html += '<li><input id="jsDeactivateReason3" type="radio" name="jsDeactivateReason" value="3" /><label for="jsDeactivateReason3"><?php echo __('I found the other plugin','joomsport-achievements')?></label><textarea name="jsDeactivateReason3_text" id="jsDeactivateReason3_text" placeholder="<?php echo __('What plugin did you prefer?','joomsport-achievements')?>"></textarea></li>';
            content_html += '<li><input id="jsDeactivateReason4" type="radio" name="jsDeactivateReason" value="4" /><label for="jsDeactivateReason4"><?php echo __('It is not working as expected','joomsport-achievements')?></label><textarea name="jsDeactivateReason4_text" id="jsDeactivateReason4_text" placeholder="<?php echo __('What was wrong?','joomsport-achievements')?>"></textarea></li>';
            content_html += '<li><input id="jsDeactivateReason5" type="radio" name="jsDeactivateReason" value="5" /><label for="jsDeactivateReason5"><?php echo __('Other','joomsport-achievements')?></label><textarea name="jsDeactivateReason5_text" id="jsDeactivateReason5_text" placeholder="<?php echo __('What is the reason?','joomsport-achievements')?>"></textarea></li>';
            content_html += '</ul></div>';
            content_html += '<div style="text-align:center;"><?php echo __('THANK YOU IN ADVANCE!','joomsport-achievements')?></div>';
            content_html += '<p><input id="jsDeactivateOpt1" type="checkbox" name="jsDeactivateOpt1" value="1" /><label for="jsDeactivateOpt1"><?php echo __('Do not show again','joomsport-achievements')?></label></p>';
            content_html += '<p><a id="jsportPopSkip" class="button" href="'+jQuery('tr[data-slug="joomsport-achievements"] .deactivate a').attr('href')+'"><?php echo __('Skip','joomsport-achievements')?></a>';
            content_html += '<a id="jsportPopSend" class="button-primary button" href="'+jQuery('tr[data-slug="joomsport-achievements"] .deactivate a').attr('href')+'"><?php echo __('Send','joomsport-achievements')?></a></p>';    
            content_html += '<p class="joomsportPopupPolicy"><a href="http://joomsport.com/send-form-privacy.html" target="_blank"><?php echo __('Send Form Privacy Policy','joomsport-achievements')?></a></p>';
                jQuery('tr[data-slug="joomsport-achievements"] .deactivate a').pointer({
                    content: content_html,
                    position: {
                        my: 'left top',
                        at: 'center bottom',
                        offset: '-1 0'
                    },
                    close: function() {
                        //
                    }
                }).pointer('open');
            return false;
            });
        </script><?php
    }
}
add_action( 'admin_footer', 'jsarch_deactivation_popup' );

add_action( 'wp_ajax_jsarch-updoption', 'jsarch_update_option' );
function jsarch_update_option() {
    $option_name = 'jsarch_deactivation_popup';
    $option = intval($_POST['option']);
    

    update_option( $option_name, $option );
    die();
}

add_action( 'wp_ajax_jsarch-senddeactivation', 'jsarch_senddeactivation' );
function jsarch_senddeactivation() {
    global $current_user;
    get_currentuserinfo();
    if($current_user->user_email){
        $ch_type = intval($_POST['ch_type']);
        $reason = '';
        switch($ch_type){
            case '1':
                $reason = __('Plugin is too complicated','joomsport-achievements');
                break;
            case '2':
                $reason = __('I miss some features','joomsport-achievements');
                break;
            case '3':
                $reason = __('I found the other plugin','joomsport-achievements');
                break;
            case '4':
                $reason = __('It is not working as expected','joomsport-achievements');
                break;
            case '5':
                $reason = __('Other','joomsport-achievements');
                break;
        }
        $ch_text = ($_POST['ch_text']);
        $to = 'deactivate-ach@beardev.com';
        $subject = 'JoomSport Achievements Deactivation';
        $body = $reason . ":<br /><br />" . $ch_text;
        $headers = array('Content-Type: text/html; charset=UTF-8','FROM:'.$current_user->user_email);

        wp_mail( $to, $subject, $body, $headers );
    }
    die();
}