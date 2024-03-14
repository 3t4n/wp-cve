<?php
// 2023-08 upd: 2023-10-17
if (!defined('ABSPATH')) {
	die('Invalid request.');
}
if(is_multisite())
  return;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (function_exists('is_plugin_active')){


    $bill_plugins_to_check = array(
        'antihacker/antihacker.php',        
        'wp-memory/wpmemory.php',  
        'wptools/wptools.php',  
        'stopbadbots/stopbadbots.php'   
    );


    foreach ($bill_plugins_to_check as $plugin_path) {
        if (is_plugin_active($plugin_path)) 
        return;
    }

}



class Bill_Class_Diagnose
{
    private static $instance = null;

 

   
    private function __construct(
        $notification_url,
        $notification_url2,
        $plugin_text_domain,
        $plugin_slug
    ) {
        $this->notification_url = $notification_url; // Store the first notification URL
        $this->notification_url2 = $notification_url2; // Store the second notification URL
        $this->plugin_text_domain = $plugin_text_domain; // Store the text domain
        $this->plugin_slug = $plugin_slug;
        $this->global_variable_has_errors = $this->bill_check_errors_today();
        $this->global_variable_memory = $this->check_memory();
        $this->global_plugin_slug = $plugin_slug;
        add_action("admin_notices", [$this, "show_dismissible_notification"]);
        add_action("admin_notices", [$this, "show_dismissible_notification2"]);
        add_action("admin_bar_menu", [$this, "add_site_health_link_to_admin_toolbar"], 999);
        add_action("admin_head", [$this,"custom_help_tab"]);
        $memory = $this->global_variable_memory;
        if (
            $memory["free"] < 30 or
            $memory["percent"] > 85 or
            $this->global_variable_has_errors
        ) {
            add_filter("site_health_navigation_tabs", [
                $this,
                "site_health_navigation_tabs",
            ]);
            add_action("site_health_tab_content", [
                $this,
                "site_health_tab_content",
            ]);
        }
    }
    public static function get_instance(
        $notification_url,
        $notification_url2,
        $plugin_text_domain,
        $plugin_slug
    ) {
        if (self::$instance === null) {
            self::$instance = new self(
                $notification_url,
                $notification_url2,
                $plugin_text_domain,
                $plugin_slug
            );
        }
        return self::$instance;
    }

    public function check_memory()
    {
        $wpmemory["limit"] = (int) ini_get("memory_limit");
        $wpmemory["usage"] = function_exists("memory_get_usage")
            ? round(memory_get_usage() / 1024 / 1024, 0)
            : 0;
        if (!defined("WP_MEMORY_LIMIT")) {
            $wpmemory["msg_type"] = "notok";
            return;
        }
        $wpmemory["wp_limit"] = trim(WP_MEMORY_LIMIT);
        if ($wpmemory["wp_limit"] > 9999999) {
            $wpmemory["wp_limit"] = $wpmemory["wp_limit"] / 1024 / 1024;
        }
        if (!is_numeric($wpmemory["usage"])) {
            $wpmemory["msg_type"] = "notok";
            return;
        }
        if (!is_numeric($wpmemory["limit"])) {
            $wpmemory["msg_type"] = "notok";
            return;
        }
        if ($wpmemory["limit"] > 9999999) {
            $wpmemory["limit"] = $wpmemory["limit"] / 1024 / 1024;
        }
        if ($wpmemory["usage"] < 1) {
            $wpmemory["msg_type"] = "notok";
            return;
        }
        $wplimit = $wpmemory["wp_limit"];
        $wplimit = substr($wplimit, 0, strlen($wplimit) - 1);
        $wpmemory["wp_limit"] = $wplimit;
        $wpmemory["percent"] = $wpmemory["usage"] / $wpmemory["wp_limit"];
        $wpmemory["color"] = "font-weight:normal;";
        if ($wpmemory["percent"] > 0.7) {
            $wpmemory["color"] = "font-weight:bold;color:#E66F00";
        }
        if ($wpmemory["percent"] > 0.85) {
            $wpmemory["color"] = "font-weight:bold;color:red";
        }
        $wpmemory["free"] = $wpmemory["wp_limit"] - $wpmemory["usage"];
        $wpmemory["msg_type"] = "ok";
        return $wpmemory;
    }
    public function bill_check_errors_today()
    {
        $bill_count = 0;
        $bill_themePath = get_theme_root();
        $error_log_path = trim(ini_get("error_log"));
        if (
            !is_null($error_log_path) and
            $error_log_path != trim(ABSPATH . "error_log")
        ) {
            $bill_folders = [
                $error_log_path,
                ABSPATH . "error_log",
                ABSPATH . "php_errorlog",
                plugin_dir_path(__FILE__) . "/error_log",
                plugin_dir_path(__FILE__) . "/php_errorlog",
                $bill_themePath . "/error_log",
                $bill_themePath . "/php_errorlog",
            ];
        } else {
            $bill_folders = [
                ABSPATH . "error_log",
                ABSPATH . "php_errorlog",
                plugin_dir_path(__FILE__) . "/error_log",
                plugin_dir_path(__FILE__) . "/php_errorlog",
                $bill_themePath . "/error_log",
                $bill_themePath . "/php_errorlog",
            ];
        }
        $bill_admin_path = str_replace(
            get_bloginfo("url") . "/",
            ABSPATH,
            get_admin_url()
        );
        array_push($bill_folders, $bill_admin_path . "/error_log");
        array_push($bill_folders, $bill_admin_path . "/php_errorlog");
        $bill_plugins = array_slice(scandir(plugin_dir_path(__FILE__)), 2);
        foreach ($bill_plugins as $bill_plugin) {
            if (is_dir(plugin_dir_path(__FILE__) . "/" . $bill_plugin)) {
                array_push(
                    $bill_folders,
                    plugin_dir_path(__FILE__) . "/" . $bill_plugin . "/error_log"
                );
                array_push(
                    $bill_folders,
                    plugin_dir_path(__FILE__) . "/" . $bill_plugin . "/php_errorlog"
                );
            }
        }
        $bill_themes = array_slice(scandir($bill_themePath), 2);
        foreach ($bill_themes as $bill_theme) {
            if (is_dir($bill_themePath . "/" . $bill_theme)) {
                array_push(
                    $bill_folders,
                    $bill_themePath . "/" . $bill_theme . "/error_log"
                );
                array_push(
                    $bill_folders,
                    $bill_themePath . "/" . $bill_theme . "/php_errorlog"
                );
            }
        }
        foreach ($bill_folders as $bill_folder) {
            if (trim(empty($bill_folder))) {
                continue;
            }
            foreach (glob($bill_folder) as $bill_filename) {
                if (strpos($bill_filename, "backup") != true) {
                    $bill_count++;
                    $marray = $this->bill_read_file($bill_filename, 20);
                    if (gettype($marray) != "array" or count($marray) < 1) {
                        continue;
                    }
                    if (count($marray) > 0) {
                        for ($i = 0; $i < count($marray); $i++) {
                            // [05-Aug-2021 08:31:45 UTC]
                            if (
                                substr($marray[$i], 0, 1) != "[" or
                                empty($marray[$i])
                            ) {
                                continue;
                            }
                            $pos = strpos($marray[$i], " ");
                            $string = trim(substr($marray[$i], 1, $pos));
                            if (empty($string)) {
                                continue;
                            }
                            $last_date = strtotime($string);
                            // 2 days...
                            if (time() - $last_date < 60 * 60 * 24 * 2) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
    public function show_dismissible_notification()
    {
        if ($this->is_notification_displayed_today()) {
            return;
        }
        $memory = $this->global_variable_memory;
        if ($memory["free"] > 30 and $wpmemory["percent"] < 85) {
            return;
        }
        $message = __("Our plugin", $this->plugin_text_domain);
        $message .= ' ('.$this->plugin_slug.') ' ;
        $message .= __("cannot function properly because your WordPress Memory Limit is too low. Your site will experience serious issues, even if you deactivate our plugin.", $this->plugin_text_domain);
        $message .=
            '<a href="' .
            esc_url($this->notification_url) .
            '">' .
            " " .
            __("Learn more", $this->plugin_text_domain) .
            "</a>";
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p style="color: red;">' . wp_kses_post($message) . "</p>";
        echo "</div>";
    }
    public function show_dismissible_notification2()
    {
        if ($this->is_notification_displayed_today()) {
            return;
        }
        if ($this->global_variable_has_errors) {
                $message = __("Your site has errors.", $this->plugin_text_domain);
                $message .= __("Our plugin", $this->plugin_text_domain);
                $message .= ' ('.$this->plugin_slug.') ' ;
                $message .= __("can't function as intended. Errors, including JavaScript errors, may lead to visual problems or disrupt functionality, from minor glitches to critical site failures. Promptly address these issues before continuing.", $this->plugin_text_domain);
                $message .=
                '<a href="' .
                esc_url($this->notification_url2) .
                '">' .
                " " .
                __("Learn more", $this->plugin_text_domain) .
                "</a>";
            echo '<div class="notice notice-error is-dismissible">';
            //$content_with_formatting = wpautop($content);
            echo '<p style="color: red;">' . wp_kses_post($message) . "</p>";
            echo "</div>";
        }
    }
    // Helper function to check if a notification has been displayed today
    public function is_notification_displayed_today()
    {
        $last_notification_date = get_option("bill_show_warnings");
        $today = date("Y-m-d");
        return $last_notification_date === $today;
    }
    // Add Tab
    public function site_health_navigation_tabs($tabs)
    {
        // translators: Tab heading for Site Health navigation.
        $tabs["Critical Issues"] = esc_html_x(
            "Critical Issues",
            "Site Health",
            $this->plugin_text_domain
        );
        return $tabs;
    }


    // Add Content
    public function site_health_tab_content($tab)
    {
        if(!function_exists('bill_strip_strong99'))
        {
            function bill_strip_strong99($htmlString)
            {
                // return $htmlString;
                // Use preg_replace para remover as tags <strong>
                $textWithoutStrongTags = preg_replace(
                    "/<strong>(.*?)<\/strong>/i",
                    '$1',
                    $htmlString
                );

                return $textWithoutStrongTags;
            }
        }
        // Do nothing if this is not our tab.
        if ("Critical Issues" !== $tab) {
            return;
        } ?>
            <div class="wrap health-check-body, privacy-settings-body" >

            <p style="border: 1px solid #000; padding: 10px;">
            <strong>
            <?php 
                echo esc_attr__("Displaying the latest recurring errors from your error log file and eventually alert about low WordPress memory limit is a courtesy of plugin", $this->plugin_text_domain);
                echo ': '.$this->global_plugin_slug.'. ';
                echo esc_attr__("Disabling our plugin does not stop the errors from occurring; 
                it simply means you will no longer be notified here that they are happening, but they can still harm your site.", $this->plugin_text_domain);
            ?>
            </strong>
            </p>

            <h3 style="color: red;">
            <?php 
            echo esc_attr__("Potential Problems", $this->plugin_text_domain);
            ?>
            </h3>
            <?php
            $memory = $this->global_variable_memory;
            $wpmemory = $memory;
            if ($memory["free"] < 30 or $wpmemory["percent"] > 85) { ?> 
            <h2 style="color: red;">
            <?php $message = __("Low WordPress Memory Limit", $this->plugin_text_domain);?>
        </h2>
    <?php
    $mb = "MB";
    echo "<b>";
    echo "WordPress Memory Limit: " .
        esc_attr($wpmemory["wp_limit"]) .
        esc_attr($mb) .
        "&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;";
    $perc = $wpmemory["usage"] / $wpmemory["wp_limit"];
    if ($perc > 0.7) {
        echo '<span style="color:' . esc_attr($wpmemory["color"]) . ';">';
    }
    echo esc_attr__("Your usage now", $this->plugin_text_domain) .
        ": " .
        esc_attr($wpmemory["usage"]) .
        "MB &nbsp;&nbsp;&nbsp;";
    if ($perc > 0.7) {
        echo "</span>";
    }
    echo "|&nbsp;&nbsp;&nbsp;" .
        esc_attr__("Total Php Server Memory", $this->plugin_text_domain) .
        " : " .
        esc_attr($wpmemory["limit"]) .
        "MB";
    echo "</b>";
    echo "</center>";
    echo "<hr>";
    $free = $wpmemory["wp_limit"] - $wpmemory["usage"];
    echo '<p>';
    echo esc_attr__("Your WordPress Memory Limit is too low, which can lead to critical issues on your site due to insufficient resources. Promptly address this issue before continuing.", $this->plugin_text_domain);
    echo '</b>';
    ?>
            </b>
            <a href= "https://wpmemory.com/fix-low-memory-limit/">
            <?php 
            echo esc_attr__("Learn More", $this->plugin_text_domain);
            ?>
            </a>
            </p>
            <br>
            <?php }
            ?>
            <?php
            if ($this->global_variable_has_errors) { ?>
                       <h2 style="color: red;">
                       <?php
                       echo esc_attr__("Site Errors", $this->plugin_text_domain);
                       ?> 
                    </h2>
            <p>
            <?php 
            echo esc_attr__("Your site has experienced errors for the past 2 days. These errors, including JavaScript issues, can result in visual problems or disrupt functionality, ranging from minor glitches to critical site failures. JavaScript errors can terminate JavaScript execution, leaving all subsequent commands inoperable.", $this->plugin_text_domain);
            ?> 
            <a href= "https://wptoolsplugin.com/site-language-error-can-crash-your-site/">
            <?php 
            echo esc_attr__("Learn More", $this->plugin_text_domain);
            ?>
            </a>
            </p>
            <?php
            $bill_count = 0;
            define("plugin_dir_path(__FILE__)", plugin_dir_path(__FILE__));
            $bill_themePath = get_theme_root();
            $error_log_path = trim(ini_get("error_log"));
            if (
                !is_null($error_log_path) and
                $error_log_path != trim(ABSPATH . "error_log")
            ) {
                $bill_folders = [
                    $error_log_path,
                    ABSPATH . "error_log",
                    ABSPATH . "php_errorlog",
                    plugin_dir_path(__FILE__) . "/error_log",
                    plugin_dir_path(__FILE__) . "/php_errorlog",
                    $bill_themePath . "/error_log",
                    $bill_themePath . "/php_errorlog",
                ];
            } else {
                $bill_folders = [
                    ABSPATH . "error_log",
                    ABSPATH . "php_errorlog",
                    plugin_dir_path(__FILE__) . "/error_log",
                    plugin_dir_path(__FILE__) . "/php_errorlog",
                    $bill_themePath . "/error_log",
                    $bill_themePath . "/php_errorlog",
                ];
            }
            $bill_admin_path = str_replace(
                get_bloginfo("url") . "/",
                ABSPATH,
                get_admin_url()
            );
            array_push($bill_folders, $bill_admin_path . "/error_log");
            array_push($bill_folders, $bill_admin_path . "/php_errorlog");
            $bill_plugins = array_slice(scandir(plugin_dir_path(__FILE__)), 2);
            foreach ($bill_plugins as $bill_plugin) {
                if (is_dir(plugin_dir_path(__FILE__) . "/" . $bill_plugin)) {
                    array_push(
                        $bill_folders,
                        plugin_dir_path(__FILE__) . "/" . $bill_plugin . "/error_log"
                    );
                    array_push(
                        $bill_folders,
                        plugin_dir_path(__FILE__) . "/" . $bill_plugin . "/php_errorlog"
                    );
                }
            }
            $bill_themes = array_slice(scandir($bill_themePath), 2);
            foreach ($bill_themes as $bill_theme) {
                if (is_dir($bill_themePath . "/" . $bill_theme)) {
                    array_push(
                        $bill_folders,
                        $bill_themePath . "/" . $bill_theme . "/error_log"
                    );
                    array_push(
                        $bill_folders,
                        $bill_themePath . "/" . $bill_theme . "/php_errorlog"
                    );
                }
            }
            echo "<br />";
            echo esc_attr__("This is a partial list of the errors found.", $this->plugin_text_domain);
            echo "<br />";

            foreach ($bill_folders as $bill_folder) {
                foreach (glob($bill_folder) as $bill_filename) {
                    if (strpos($bill_filename, "backup") != true) {
                        echo "<strong>";
                        echo esc_attr($bill_filename);
                        echo "</strong>";
                        $bill_count++;
                        $marray = $this->bill_read_file($bill_filename, 3000);
                        if (gettype($marray) != "array" or count($marray) < 1) {
                            continue;
                        }
                        $total = count($marray);
                        if (count($marray) > 0) {
                            echo '<textarea style="width:99%;" id="anti_hacker" rows="12">';

                            if ($total > 1000) {
                                $total = 1000;
                            }
        
                            for ($i = 0; $i < $total; $i++) {
                                if (strpos(trim($marray[$i]), "[") !== 0) {
                                    continue; // Skip lines without correct date format
                                }
        
                                $logs = [];
        
                                $line = trim($marray[$i]);
                                if (empty($line)) {
                                    continue;
                                }
        
                                //  stack trace
                                //[30-Sep-2023 11:28:52 UTC] PHP Stack trace:
                                $pattern = "/PHP Stack trace:/";
                                if (preg_match($pattern, $line, $matches)) {
                                    continue;
                                }
                                $pattern =
                                    "/\d{4}-\w{3}-\d{4} \d{2}:\d{2}:\d{2} UTC\] PHP \d+\./";
                                if (preg_match($pattern, $line, $matches)) {
                                    continue;
                                }
                                //  end stack trace
        
                                // Javascript ?
                                if (strpos($line, "Javascript") !== false) {
                                    $is_javascript = true;
                                } else {
                                    $is_javascript = false;
                                }
        
                                if ($is_javascript) {
                                    $matches = [];
        
                                    // die($line);
        
                                    $apattern = [];
                                    $apattern[] =
                                        "/(Error|Syntax|Type|TypeError|Reference|ReferenceError|Range|Eval|URI|Error .*?): (.*?) - URL: (https?:\/\/\S+).*?Line: (\d+).*?Column: (\d+).*?Error object: ({.*?})/";
        
                                    //$apattern[] =
                                    //    "/(Error|Syntax|Type|TypeError|Reference|ReferenceError|Range|Eval|URI|Error .*?): (.*?) - URL: (https?:\/\/\S+).*?Line: (\d+)/";
        
        
                                    $apattern[] =
                                        "/(SyntaxError|Error|Syntax|Type|TypeError|Reference|ReferenceError|Range|Eval|URI|Error .*?): (.*?) - URL: (https?:\/\/\S+).*?Line: (\d+)/";
                    
                                    // Google Maps !
                                    //$apattern[] = "/Script error(?:\. - URL: (https?:\/\/\S+))?/i";
        
                                    $pattern = $apattern[0];
        
                                    for ($j = 0; $j < count($apattern); $j++) {
                                        if (
                                            preg_match($apattern[$j], $line, $matches)
                                        ) {
                                            $pattern = $apattern[$j];
                                            break;
                                        }
                                    }
        
                                    /*
                                        //$pattern = "/Line: (\d+)/";
                                         preg_match($pattern, $line, $matches);
                                        print_r($matches);
                                        die('------------xxx---------------');
                                        die($line);
                                        */
        
                                    if (preg_match($pattern, $line, $matches)) {
                                        $matches[1] = str_replace(
                                            "Javascript ",
                                            "",
                                            $matches[1]
                                        );
        
                                        if (count($matches) == 2) {
                                            $log_entry = [
                                                "Date" => substr($line, 1, 20),
                                                "Message Type" => "Script error",
                                                "Problem Description" => "N/A",
                                                "Script URL" => $matches[1],
                                                "Line" => "N/A",
                                            ];
                                        } else {
                                            $log_entry = [
                                                "Date" => substr($line, 1, 20),
                                                "Message Type" => $matches[1],
                                                "Problem Description" => $matches[2],
                                                "Script URL" => $matches[3],
                                                "Line" => $matches[4],
                                            ];
                                        }
        
        
        
        
                                        $script_path = $matches[3];
                                        $script_info = pathinfo($script_path);
                
                
                                        // Dividir o nome do script com base em ":"
                                        $parts = explode(":", $script_info["basename"]);
                
                                        // O nome do script agora está na primeira parte
                                        $scriptName = $parts[0];
                
                                        $log_entry["Script Name"] = $scriptName; // Get the script name
                
                                        $log_entry["Script Location"] =
                                            $script_info["dirname"]; // Get the script location
                
                                        if($log_entry["Script Location"] == 'http:' or $log_entry["Script Location"] == 'https:' )
                                          $log_entry["Script Location"] = $matches[3];
        
        
        
        
        
        
        
        
        
        
        
        
        
                                        if (
                                            strpos(
                                                $log_entry["Script URL"],
                                                "/wp-content/plugins/"
                                            ) !== false
                                        ) {
                                            // O erro ocorreu em um plugin
                                            $parts = explode(
                                                "/wp-content/plugins/",
                                                $log_entry["Script URL"]
                                            );
                                            if (count($parts) > 1) {
                                                $plugin_parts = explode("/", $parts[1]);
                                                $log_entry["File Type"] = "Plugin";
                                                $log_entry["Plugin Name"] =
                                                    $plugin_parts[0];
                                             //   $log_entry["Script Location"] =
                                              //      "/wp-content/plugins/" .
                                             //       $plugin_parts[0];
                                            }
                                        } elseif (
                                            strpos(
                                                $log_entry["Script URL"],
                                                "/wp-content/themes/"
                                            ) !== false
                                        ) {
                                            // O erro ocorreu em um tema
                                            $parts = explode(
                                                "/wp-content/themes/",
                                                $log_entry["Script URL"]
                                            );
                                            if (count($parts) > 1) {
                                                $theme_parts = explode("/", $parts[1]);
                                                $log_entry["File Type"] = "Theme";
                                                $log_entry["Theme Name"] =
                                                    $theme_parts[0];
                                               // $log_entry["Script Location"] =
                                               //     "/wp-content/themes/" .
                                               //     $theme_parts[0];
                                            }
                                        } else {
                                            // Caso não seja um tema nem um plugin, pode ser necessário ajustar o comportamento aqui.
                                            //$log_entry["Script Location"] = $matches[1];
                                        }
        
                                        // Extrair o nome do script do URL
                                        $script_name = basename(
                                            parse_url(
                                                $log_entry["Script URL"],
                                                PHP_URL_PATH
                                            )
                                        );
                                        $log_entry["Script Name"] = $script_name;
        
                                        //echo $line."\n";
        
                                        // Exemplo de saída:
                                        if (isset($log_entry["Date"])) {
                                            echo "DATE: {$log_entry["Date"]}\n";
                                        }
                                        if (isset($log_entry["Message Type"])) {
                                            echo "MESSAGE TYPE: (Javascript) {$log_entry["Message Type"]}\n";
                                        }
                                        if (isset($log_entry["Problem Description"])) {
                                            echo "PROBLEM DESCRIPTION: {$log_entry["Problem Description"]}\n";
                                        }
        
                                        if (isset($log_entry["Script Name"])) {
                                            echo "SCRIPT NAME: {$log_entry["Script Name"]}\n";
                                        }
                                        if (isset($log_entry["Line"])) {
                                            echo "LINE: {$log_entry["Line"]}\n";
                                        }
                                        if (isset($log_entry["Column"])) {
                                            //	echo "COLUMN: {$log_entry['Column']}\n";
                                        }
                                        if (isset($log_entry["Error Object"])) {
                                            //	echo "ERROR OBJECT: {$log_entry['Error Object']}\n";
                                        }
                                        if (isset($log_entry["Script Location"])) {
                                            echo "SCRIPT LOCATION: {$log_entry["Script Location"]}\n";
                                        }
                                        if (isset($log_entry["Plugin Name"])) {
                                            echo "PLUGIN NAME: {$log_entry["Plugin Name"]}\n";
                                        }
                                        if (isset($log_entry["Theme Name"])) {
                                            echo "THEME NAME: {$log_entry["Theme Name"]}\n";
                                        }
        
                                        echo "------------------------\n";
                                        continue;
                                    } else {
                                        // echo "-----------x-------------\n";
                                        echo $line;
                                        echo "\n-----------x------------\n";
                                    }
                                    continue;
                                    // END JAVASCRIPT
                                } else {
                                    /* ----- PHP // */
        
        
                                    // continue;
        
        
                                    $apattern = [];
                                    $apattern[] =
                                        "/^\[.*\] PHP (Warning|Error|Notice|Fatal error|Parse error): (.*) in \/([^ ]+) on line (\d+)/";
                                    $apattern[] =
                                        "/^\[.*\] PHP (Warning|Error|Notice|Fatal error|Parse error): (.*) in \/([^ ]+):(\d+)$/";
        
                                    $pattern = $apattern[0];
        
                                    for ($j = 0; $j < count($apattern); $j++) {
                                        if (
                                            preg_match($apattern[$j], $line, $matches)
                                        ) {
                                            $pattern = $apattern[$j];
                                            break;
                                        }
                                    }
        
                                    if (preg_match($pattern, $line, $matches)) {
                                        //die(var_export($matches));
        
                                        /*              
                                            0 => '[29-Sep-2023 11:44:22 UTC] PHP Parse error:  syntax error, unexpected \'preg_match\' (T_STRING) in /home/realesta/public_html/wp-content/plugins/wptools/functions/functions.php on line 2066',
                                            1 => 'Parse error',
                                            2 => ' syntax error, unexpected \'preg_match\' (T_STRING)',
                                            3 => 'home/realesta/public_html/wp-content/plugins/wptools/functions/functions.php',
                                            4 => '2066',
                                            */
        
                                        $log_entry = [
                                            "Date" => substr($line, 1, 20), // Extract date from line
                                            "News Type" => $matches[1],
                                            "Problem Description" => bill_strip_strong99(
                                                $matches[2]
                                            ),
                                        ];
        
        
        
                                        $script_path = $matches[3];
                                        $script_info = pathinfo($script_path);
        
                                        // Dividir o nome do script com base em ":"
                                        $parts = explode(":", $script_info["basename"]);
        
                                        // O nome do script agora está na primeira parte
                                        $scriptName = $parts[0];
        
                                        $log_entry["Script Name"] = $scriptName; // Get the script name
        
                                        $log_entry["Script Location"] =
                                            $script_info["dirname"]; // Get the script location
        
                                        $log_entry["Line"] = $matches[4];
        
        
        
                                        // Check if the "Script Location" contains "/plugins/" or "/themes/"
                                        if (
                                            strpos(
                                                $log_entry["Script Location"],
                                                "/plugins/"
                                            ) !== false
                                        ) {
                                            // Extract the plugin name
                                            $parts = explode(
                                                "/plugins/",
                                                $log_entry["Script Location"]
                                            );
                                            if (count($parts) > 1) {
                                                $plugin_parts = explode("/", $parts[1]);
                                                $log_entry["File Type"] = "Plugin";
                                                $log_entry["Plugin Name"] =
                                                    $plugin_parts[0];
                                            }
                                        } elseif (
                                            strpos(
                                                $log_entry["Script Location"],
                                                "/themes/"
                                            ) !== false
                                        ) {
                                            // Extract the theme name
                                            $parts = explode(
                                                "/themes/",
                                                $log_entry["Script Location"]
                                            );
                                            if (count($parts) > 1) {
                                                $theme_parts = explode("/", $parts[1]);
                                                $log_entry["File Type"] = "Theme";
                                                $log_entry["Theme Name"] =
                                                    $theme_parts[0];
                                            }
                                        }
                                    } else {
                                        // stack trace...
                                        $pattern = "/\[.*?\] PHP\s+\d+\.\s+(.*)/";
                                        preg_match($pattern, $line, $matches);
        
                                        if (!preg_match($pattern, $line)) {
                                            echo "-----------y-------------\n";
                                            echo $line;
                                            echo "\n-----------y------------\n";
                                        }
                                        continue;
                                    }
        
                                    //$in_error_block = false; // End the error block
                                    $logs[] = $log_entry; // Add this log entry to the array of logs
        
                                    foreach ($logs as $log) {
                                        if (isset($log["Date"])) {
                                            echo "DATE: {$log["Date"]}\n";
                                        }
                                        if (isset($log["News Type"])) {
                                            echo "MESSAGE TYPE: {$log["News Type"]}\n";
                                        }
                                        if (isset($log["Problem Description"])) {
                                            echo "PROBLEM DESCRIPTION: {$log["Problem Description"]}\n";
                                        }
        
                                        // Check if the 'Script Name' key exists before printing
                                        if (
                                            isset($log["Script Name"]) and
                                            !empty(trim($log["Script Name"]))
                                        ) {
                                            echo "SCRIPT NAME: {$log["Script Name"]}\n";
                                        }
        
                                        // Check if the 'Line' key exists before printing
                                        if (isset($log["Line"])) {
                                            echo "LINE: {$log["Line"]}\n";
                                        }
        
                                        // Check if the 'Script Location' key exists before printing
                                        if (isset($log["Script Location"])) {
                                            echo "SCRIPT LOCATION: {$log["Script Location"]}\n";
                                        }
        
                                        // Check if the 'File Type' key exists before printing
                                        if (isset($log["File Type"])) {
                                            // echo "FILE TYPE: {$log['File Type']}\n";
                                        }
        
                                        // Check if the 'Plugin Name' key exists before printing
                                        if (
                                            isset($log["Plugin Name"]) and
                                            !empty(trim($log["Plugin Name"]))
                                        ) {
                                            echo "PLUGIN NAME: {$log["Plugin Name"]}\n";
                                        }
        
                                        // Check if the 'Theme Name' key exists before printing
                                        if (isset($log["Theme Name"])) {
                                            echo "THEME NAME: {$log["Theme Name"]}\n";
                                        }
        
                                        echo "------------------------\n";
                                    }
                                }
                                // end if PHP ...
                            } // end for...
        
                            echo "</textarea>";




                        }
                        echo "<br />";
                    }
                }
            }
            }


            echo "</div>";
    }





    public function bill_read_file($file, $lines)
    {
        try {
            $handle = fopen($file, "r");
        } catch (Exception $e) {
            return "";
        }
        if (!$handle) {
            return "";
        }
        $linecounter = $lines;
        $pos = -2;
        $beginning = false;
        $text = [];
        while ($linecounter > 0) {
            $t = " ";
            // acha ultima quebra de linha indo para traz... 
            // partindo da ultima posicao menos 1.
            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    // chegou no inicio?
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }
            $linecounter--;
            // chegou no inicio?
            if ($beginning) {
                rewind($handle);
            }
            $line = fgets($handle);
            if ($line === false) {
                break; // Não há mais linhas para ler
            }
            $text[] = $line;
            if ($beginning) {
                break;
            }
        }
        fclose($handle);
        return $text;
    }


    public function add_site_health_link_to_admin_toolbar($wp_admin_bar)
    {
            $logourl = plugin_dir_url(__FILE__) . "bell.png";
            $wp_admin_bar->add_node([
                "id" => "site-health",
                "title" =>
                    '<span style="background-color: #ff0000; color: #fff; display: flex; align-items: center; padding: 0px 10px  0px 10px; ">' .
                    '<span style="border-radius: 50%; padding: 4px; display: inline-block; width: 20px; height: 20px; text-align: center; font-size: 12px; background-color: #ff0000; background-image: url(\'' .
                    esc_url($logourl) .
                    '\'); background-repeat: no-repeat; background-position: 0 6px; background-size: 20px;"></span> ' .
                    '<span style="background-color: #ff0000; color: #fff;">Site Health Issues</span>' .
                    "</span>",
                "href" => admin_url("site-health.php?tab=Critical+Issues"),
            ]);
    }

    public function custom_help_tab()
    {
        $screen = get_current_screen();
        // Verifique se você está na página desejada
        if ("site-health" === $screen->id) {
            // Adicione uma guia de ajuda
            $message = esc_attr__("These are critical issues that can have a significant impact on your site's performance. They can cause many plugins and functionalities to malfunction and, in some cases, render your site completely inoperative, depending on their severity. Address them promptly.", $this->plugin_text_domain);
            $screen->add_help_tab([
                "id" => "custom-help-tab",
                "title" => "Critical Issues",
                "content" =>
                    "<p>".esc_attr($message)."</p>",
            ]);
        }
    }
   // add_action("admin_head", "custom_help_tab");
} // end class
/*
$plugin_slug = "database-backup"; // Replace with your actual text domain
$plugin_text_domain = "database-backup"; // Replace with your actual text domain
$notification_url = "https://wpmemory.com/fix-low-memory-limit/";
$notification_url2 =
    "https://billplugin.com/site-language-error-can-crash-your-site/";
*/
$diagnose_instance = Bill_Class_Diagnose::get_instance(
    $notification_url,
    $notification_url2,
    $plugin_text_domain,
    $plugin_slug
);
update_option("bill_show_warnings", date("Y-m-d"));