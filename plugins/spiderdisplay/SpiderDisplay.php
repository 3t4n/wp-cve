<?php
/*
Plugin Name: SpiderDisplay 蜘蛛痕迹记录
Plugin URI: http://fyljp.com/archives/48.html
Description: 记录搜索引擎蜘蛛访问的页面地址、时间等信息
Version: 1.9.1
Author: 闪电
Author URI: http://www.fyljp.com
License: GPLv2
*/

/*  Copyright 2019  闪电  (email : fyljp@foxmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

register_activation_hook( __FILE__, 'sd_spiderdisplay_install');

function sd_spiderdisplay_install()
{
    add_option("SpiderDisplayVersion", "3");
    global $wpdb;
    $sd_spiderdisplay_table_name = $wpdb->prefix . "spiderdisplay";
    require_once(ABSPATH . "wp-admin/includes/upgrade.php"); 
    dbDelta("CREATE TABLE IF NOT EXISTS `" . $sd_spiderdisplay_table_name . "` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `visittime` datetime NOT NULL,
        `spidername` varchar(50) COLLATE utf8_bin NOT NULL,
        `visiturl` varchar(255) COLLATE utf8_bin NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
}

register_deactivation_hook( __FILE__, 'sd_spiderdisplay_uninstall');
function sd_spiderdisplay_uninstall()
{
    $sd_spiderdisplay_table_name = $wpdb->prefix . "spiderdisplay";
    delete_option("SpiderDisplayVersion");
    require_once(ABSPATH . "wp-admin/includes/upgrade.php"); 
    dbDelta("DROP TABLE " . $sd_spiderdisplay_table_name);
}

function myplugin_init() {
    load_plugin_textdomain( 'SpiderDisplay', false, dirname( plugin_basename( __FILE__ ) ) ); 
  }
add_action('plugins_loaded', 'myplugin_init');

add_action('admin_menu','sd_spiderdisplay_add_setting_menu');

function sd_spiderdisplay_add_setting_menu()
{
    add_menu_page(__("Overall","SpiderDisplay") . " - " . __("SpiderDisplay","SpiderDisplay"),__("SpiderDisplay","SpiderDisplay"), "administrator", __FILE__,"sd_spiderdisplay_general"); 
    add_submenu_page(__FILE__,__("Detail","SpiderDisplay") . " - " . __("SpiderDisplay","SpiderDisplay"),__("Detail","SpiderDisplay"), 'administrator', "SpiderDisplay_All", 'sd_spiderdisplay_all');
}


//详细页面
function sd_spiderdisplay_all()
{
    global $wpdb;
    $sd_spiderdisplay_table_name = $wpdb->prefix . "spiderdisplay";
    $spiderpage = 0;
    $spiderresult;
    $idcount = 0;
    if(isset($_GET["spiderpage"]))
    {
        $spiderpage = (int)sanitize_text_field($_GET["spiderpage"]);
    }
    if(!isset($_GET["spidername"]))
    {
        $spiderresult = $wpdb->get_results( "SELECT * FROM  `$sd_spiderdisplay_table_name` WHERE 1 ORDER BY `visittime` DESC LIMIT " . $spiderpage*10 .",100" );  
    }else{
        $spiderresult = $wpdb->get_results( "SELECT * FROM  `$sd_spiderdisplay_table_name` WHERE `spidername`= '" . sanitize_text_field($_GET["spidername"]) . "' ORDER BY `visittime` DESC LIMIT " . $spiderpage*10 .",100" ); 
    }
    $spidernamerows = $wpdb->get_results( "SELECT `spidername` FROM  `$sd_spiderdisplay_table_name` WHERE 1 GROUP BY `spidername`");  
    echo "<h2><a href=\"admin.php?page=SpiderDisplay_All\">" . __("All","SpiderDisplay") . "</a>";  
    foreach($spidernamerows as $items)
    {
        echo " | <a href=\"admin.php?page=SpiderDisplay_All&spidername=" . $items->spidername . "\">" . $items->spidername ."</a>"; 
    }
    echo "</h2>";
    ?>
    <style>
        table {
            border-collapse: collapse;
        }

        table, td, th {
            border: 1px solid black;
        }
    </style>
        <table border="1">
            <tr>
                <th><?php _e("SearchEngine","SpiderDisplay"); ?></th>
                <th><?php _e("time","SpiderDisplay"); ?></th>
                <th><?php _e("url","SpiderDisplay"); ?></th>
            </tr>
                <?php
                    foreach($spiderresult as $items)
                    {
                        echo "<tr>";
                        echo "<td>" . $items->spidername . "</td>";
                        echo "<td>" . $items->visittime . "</td>";
                        echo "<td><a href=\"" . esc_url($items->visiturl) . "\" target=\"_blank\">" . esc_url($items->visiturl) . "</a></td>";
                        echo "</tr>";
                    }
                ?>
        </table>
    <?php
    
    echo "<h2>";
    if(!isset($_GET["spidername"]))
    {
        echo "<a href=\"admin.php?page=SpiderDisplay_All&spiderpage=" . (esc_html($spiderpage) -1) . "\">" . __("Previous","SpiderDisplay") ."</a>"; 
        echo " >>> ";
        echo "<a href=\"admin.php?page=SpiderDisplay_All&spiderpage=" . (esc_html($spiderpage) +1) . "\">" . __("Next","SpiderDisplay") . "</a>";
    }else{
        echo "<a href=\"admin.php?page=SpiderDisplay_All&spidername=" . esc_html($_GET["spidername"]) . "&spiderpage=" . (esc_html($spiderpage) -1) . "\">" . __("Next","SpiderDisplay") . "</a>"; 
        echo " >>> ";
        echo "<a href=\"admin.php?page=SpiderDisplay_All&spidername=" . esc_html($_GET["spidername"]) . "&spiderpage=" . (esc_html($spiderpage) +1) . "\">" . __("Next","SpiderDisplay") . "</a>";
    }
    echo "</h2>";
    
}

//总览界面
function sd_spiderdisplay_general(){
    if(!current_user_can("manage_options"))
    {
        echo "Forbidden";
        return;
    }
    global $wpdb;
    $deday = 3;
    $sd_spiderdisplay_table_name = $wpdb->prefix . "spiderdisplay";
    if(isset($_POST["deday"]))
    {
        $deday = sanitize_text_field($_POST["deday"]);
    }
    if(isset($_POST["del"]) && $_POST["del"] >= 1)
    {
        $del = sanitize_text_field($_POST["del"]);
        $del_num = $wpdb->query("DELETE FROM `" . $sd_spiderdisplay_table_name . "` WHERE `visittime` < '" . date("Y-m-d H:i:s",strtotime("-$del day")) . "'");
        echo "<h3>" . $del_num . __(" Records has been deleted.","SpiderDisplay") . "</h3>";
    }
    echo "<h1>" . __("Overall","SpiderDisplay") . "</h1>";
    ?>
    <form action="" method="post">
        <h3><?php _e("Query the last","SpiderDisplay"); ?> <input type="text" name="deday" value="<?php echo $deday; ?>"> <?php _e("days records","SpiderDisplay"); ?> <input type="submit" value="<?php _e("ok","SpiderDisplay"); ?>"></h3>
    </form>
    <form action="" method="post">
        <h3><?php _e("Delete Records before","SpiderDisplay");?> <input type="text" name="del" value=""> <?php _e("days","SpiderDisplay"); ?><input type="submit" value="<?php _e("ok","SpiderDisplay"); ?>"></h3>
    </form>
    <?php
    $spidercountall = $wpdb->get_results("SELECT `spidername`, COUNT(*) as 'allnum' FROM  `" . $sd_spiderdisplay_table_name . "`  WHERE 1 GROUP BY `spidername` ORDER BY  `allnum` DESC");  
    ?>
    <style>
        table {
            border-collapse: collapse;
        }

        table, td, th {
            border: 1px solid black;
        }
    </style>
    <table border="1">
        <tr>
            <th><?php _e("SearchEngine","SpiderDisplay"); ?></th>
            <th><?php _e("times","SpiderDisplay"); ?></th>
        </tr>
        <?php
        foreach($spidercountall as $items)
        {
            echo "<tr>";
            echo "<td><a href=\"admin.php?page=SpiderDisplay_All&spidername=" . $items->spidername . "\">" . $items->spidername . "</a></td>";
            echo "<td>" . $items->allnum . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <?php
}

//计数
add_action('wp_footer', 'sd_spiderdisplay_count');
function sd_spiderdisplay_count() {
    global $wpdb;
    $sd_spiderdisplay_table_name = $wpdb->prefix . "spiderdisplay";
    $s_uri = sanitize_text_field($_SERVER["REQUEST_URI"]);
    $s_hua = sanitize_text_field($_SERVER["HTTP_USER_AGENT"]);
    $bot_name = show_spider_name($s_hua);
    if($bot_name != null)
    {
        $data_arr = array(
            "visittime" => current_time('mysql',0),
            "spidername" => $bot_name,
            "visiturl" => $s_uri
        );
        
        $wpdb->insert($sd_spiderdisplay_table_name,$data_arr);
    }
}

function show_spider_name($user_agent)
{
    if(stripos($user_agent,"baiduspider"))
    {
        return __("Baidu","SpiderDisplay");
    }else if(stripos($user_agent,"googlebot"))
    {
        return __("Google","SpiderDisplay");
    }else if(stripos($user_agent,"360Spider"))
    {
        return __("360","SpiderDisplay");
    }else if(stripos($user_agent,"YisouSpider"))
    {
        return __("Sm","SpiderDisplay");
    }else if(stripos($user_agent,"YoudaoBot"))
    {
        return __("Youdao","SpiderDisplay");
    }else if(stripos($user_agent,"Sogou"))
    {
        return __("Sogou","SpiderDisplay");
    }else if(stripos($user_agent,"bingbot"))
    {
        return __("Bing","SpiderDisplay");
    }else if(stripos($user_agent,"ia_archiver"))
    {
        return __("Alexa","SpiderDisplay");
    }else if(stripos($user_agent,"YandexBot"))
    {
        return __("Yandex","SpiderDisplay");
    }else if(stripos($user_agent,"wordpress"))
    {
        return __("Wordpress","SpiderDisplay");
    }else if(stripos($user_agent,"bot"))
    {
        return __("Others","SpiderDisplay");
    }else{
        return null;
    }
}

?>
