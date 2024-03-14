<?php
/**
 * @author William Sergio Minossi
 * 2023-11-24
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $wpdb;
$wptools_table_name = $wpdb->prefix . 'wptools_errors'; // Replace 'your_table_name' with the actual table name
if ($wpdb->get_var("SHOW TABLES LIKE '$wptools_table_name'") !== $wptools_table_name) {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    // $wptools_table_name = $wpdb->prefix . 'wptools_errors';
    $sql = "CREATE TABLE IF NOT EXISTS $wptools_table_name (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `ip` varchar(50) NOT NULL,
        `error_number` int(11) NOT NULL,
        `error_type` varchar(255) NOT NULL,
        `error_string` text NOT NULL,
        `error_file` varchar(255) NOT NULL,
        `error_line` varchar(10) NOT NULL,
        `file_location` varchar(255) NOT NULL,
        `plugin_name` varchar(255) NOT NULL,
        `theme_name` varchar(255) NOT NULL,
        `error_date` timestamp NOT NULL DEFAULT current_timestamp(),
        `ua` text NOT NULL,
        PRIMARY KEY (`id`),
        INDEX (`error_date`)  
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    // Execute the SQL query
    dbDelta($sql);
}
 wptools_fill_db__errors();
function wptools_fill_db__errors()
{
    global $wpdb;
    $wptools_table_name = $wpdb->prefix . 'wptools_errors'; 
    $ip = '';
    $ua = '';
    $wptools_count = 0;
    if (!defined('WPTOOLSPLUGINPATH')) 
       define("WPTOOLSPLUGINPATH", plugin_dir_path(__FILE__));
    $wptools_themePath = get_theme_root();
    $error_log_path = trim(ini_get("error_log"));
    if (
        !is_null($error_log_path) and
        $error_log_path != trim(ABSPATH . "error_log")
    ) {
        $wptools_folders = [
            $error_log_path,
            ABSPATH . "error_log",
            ABSPATH . "php_errorlog",
            WPTOOLSPLUGINPATH . "/error_log",
            WPTOOLSPLUGINPATH . "/php_errorlog",
            $wptools_themePath . "/error_log",
            $wptools_themePath . "/php_errorlog",
        ];
    } else {
        $wptools_folders = [
            ABSPATH . "error_log",
            ABSPATH . "php_errorlog",
            WPTOOLSPLUGINPATH . "/error_log",
            WPTOOLSPLUGINPATH . "/php_errorlog",
            $wptools_themePath . "/error_log",
            $wptools_themePath . "/php_errorlog",
        ];
    }
    $wptools_admin_path = str_replace(
        get_bloginfo("url") . "/",
        ABSPATH,
        get_admin_url()
    );
    array_push($wptools_folders, $wptools_admin_path . "/error_log");
    array_push($wptools_folders, $wptools_admin_path . "/php_errorlog");
    $wptools_plugins = array_slice(scandir(WPTOOLSPLUGINPATH), 2);
    foreach ($wptools_plugins as $wptools_plugin) {
        if (is_dir(WPTOOLSPLUGINPATH . "/" . $wptools_plugin)) {
            array_push(
                $wptools_folders,
                WPTOOLSPLUGINPATH . "/" . $wptools_plugin . "/error_log"
            );
            array_push(
                $wptools_folders,
                WPTOOLSPLUGINPATH . "/" . $wptools_plugin . "/php_errorlog"
            );
        }
    }
    $wptools_themes = array_slice(scandir($wptools_themePath), 2);
    foreach ($wptools_themes as $wptools_theme) {
        if (is_dir($wptools_themePath . "/" . $wptools_theme)) {
            array_push(
                $wptools_folders,
                $wptools_themePath . "/" . $wptools_theme . "/error_log"
            );
            array_push(
                $wptools_folders,
                $wptools_themePath . "/" . $wptools_theme . "/php_errorlog"
            );
        }
    }
    foreach ($wptools_folders as $wptools_folder) {
        foreach (glob($wptools_folder) as $wptools_filename) {
            if (strpos($wptools_filename, "backup") != true) {
                $wptools_count++;
                $marray = wptools_read_file($wptools_filename, 3000);
                if (gettype($marray) != "array" or count($marray) < 1) {
                    continue;
                }
                $total = count($marray);
                if (count($marray) > 0) {
                    if ($total > 1000) {
                        $total = 1000;
                    }
                    for ($i = 0; $i < $total; $i++) {
                        if (strpos(trim($marray[$i]), "[") !== 0) {
                            continue; // Skip lines without correct date format
                        }
                        $logs = [];
                        $line = trim($marray[$i]);
                        if(empty($line))
                           continue;
                        //  stack trace 
                        //[30-Sep-2023 11:28:52 UTC] PHP Stack trace:
                        $pattern = '/PHP Stack trace:/';
                        if (preg_match($pattern, $line, $matches)) 
                          continue;
                        $pattern = '/\d{4}-\w{3}-\d{4} \d{2}:\d{2}:\d{2} UTC\] PHP \d+\./';
                        if (preg_match($pattern, $line, $matches)) 
                          continue;
                        //  end stack trace 
                        // Javascript ?
                        if (strpos($line, 'Javascript') !== false)
                           $is_javascript = true;
                        else
                           $is_javascript = false;
                        if ($is_javascript) {
                            $matches = [];
                                // die($line);
                                $apattern = [];
                                $apattern[] =
                                "/(Error|Syntax|Type|TypeError|Reference|ReferenceError|Range|Eval|URI|Error .*?): (.*?) - URL: (https?:\/\/\S+).*?Line: (\d+).*?Column: (\d+).*?Error object: ({.*?})/";
                                $apattern[] = "/(Error|Syntax|Type|TypeError|Reference|ReferenceError|Range|Eval|URI|Error .*?): (.*?) - URL: (https?:\/\/\S+).*?Line: (\d+)/";
                                $pattern = $apattern[0];
                                for($j = 0; $j < count($apattern); $j++){
                                  if(preg_match($apattern[$j], $line, $matches)){
                                     $pattern = $apattern[$j];
                                     break;
                                  }
                                }
                                if (preg_match($pattern, $line, $matches)) {
                                    $matches[1] = str_replace(
                                        "Javascript ",
                                        "",
                                        $matches[1]
                                    );
                                    if(count($matches) == 2)
                                    {
                                        $log_entry = [
                                            "Date" => substr($line, 1, 20),
                                            "Message Type" => 'Script error',
                                            "Problem Description" => 'N/A',
                                            "Script URL" => $matches[1],
                                            "Line" => 'N/A',
                                        ];
                                    }
                                    else{
                                        $log_entry = [
                                            "Date" => substr($line, 1, 20),
                                            "Message Type" => $matches[1],
                                            "Problem Description" => $matches[2],
                                            "Script URL" => $matches[3],
                                            "Line" => $matches[4],
                                        ];
                                    }
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
                                            $plugin_parts = explode(
                                                "/",
                                                $parts[1]
                                            );
                                            $log_entry["File Type"] = "Plugin";
                                            $log_entry["Plugin Name"] =
                                                $plugin_parts[0];
                                            $log_entry["Script Location"] =
                                                "/wp-content/plugins/" .
                                                $plugin_parts[0];
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
                                            $theme_parts = explode(
                                                "/",
                                                $parts[1]
                                            );
                                            $log_entry["File Type"] = "Theme";
                                            $log_entry["Theme Name"] =
                                                $theme_parts[0];
                                            $log_entry["Script Location"] =
                                                "/wp-content/themes/" .
                                                $theme_parts[0];
                                        }
                                    } else {
                                        $log_entry["Theme Name"] = '';
                                        $log_entry["Plugin Name"] = '';
                                        if(substr($log_entry["Script URL"],0,4) == 'http'  ) {
                                          $pos = strpos($log_entry["Script URL"], '?');
                                          if($pos !== false)    
                                            $log_entry["Script Location"] = substr($log_entry["Script URL"], 0, $pos);
                                          else 
                                            $log_entry["Script Location"] = $log_entry["Script URL"];
                                        }
                                        else
                                           $log_entry["Script Location"] = $matches[1];




                                           if (!empty($log_entry["Script Location"]) && filter_var($log_entry["Script Location"], FILTER_VALIDATE_URL)) {
                                             $log_entry["Script Location"] = dirname($log_entry["Script Location"]);
                                           }

                                           
                                    }
                                    // Extrair o nome do script do URL
                                    $script_name = basename(
                                        parse_url(
                                            $log_entry["Script URL"],
                                            PHP_URL_PATH
                                        )
                                    );
                                    $log_entry["Script Name"] = $script_name;
                                    $ip = $log_entry["IP"] ?? ''; // Substitua 'IP' pelo nome correto da chave na sua array.
                                    $error_number = $log_entry["Error Number"] ?? ''; // Substitua 'Error Number' pelo nome correto da chave na sua array.
                                    $error_type = $log_entry["Message Type"] ?? ''; // Substitua 'Error Type' pelo nome correto da chave na sua array.
                                    $error_string = $log_entry["Problem Description"] ?? ''; // Substitua 'Error String' pelo nome correto da chave na sua array.
                                    $error_file = $log_entry["Script Name"] ?? ''; // Substitua 'Error File' pelo nome correto da chave na sua array.
                                    $error_line = $log_entry["Line"] ?? ''; // Substitua 'Error Line' pelo nome correto da chave na sua array.
                                    $file_location = $log_entry["Script Location"] ?? ''; // Substitua 'File Location' pelo nome correto da chave na sua array.
                                    $theme_name = $log_entry["Theme Name"] ?? ''; // Substitua 'Theme Name' pelo nome correto da chave na sua array.
                                    $plugin_name = $log_entry["Plugin Name"] ?? ''; // Substitua 'Plugin Name' pelo nome correto da chave na sua array.
                                    $ua = $log_entry["User Agent"] ?? ''; // Substitua 'User Agent' pelo nome correto da chave na sua array.
                                    $error_date = date('Y-m-d H:i:s', strtotime($log_entry["Date"])) ?? current_time('mysql'); // Substitua 'Date' pelo nome correto da chave na sua array.
                                    $existing_record = $wpdb->get_var(
                                        $wpdb->prepare(
                                            "SELECT COUNT(*)
                                            FROM ".$wptools_table_name. "
                                            WHERE `error_date` = %s AND `error_line` = %s",
                                            $error_date, $error_line
                                        )
                                    );
                                    if (!$existing_record) {
                                        // array ( 'Date' => '23-Nov-2023 17:15:53', 'Message Type' => 'Error', 'Problem Description' => 'unterminated regular expression literal', 'Script URL' => 'https://minozzi.eu/wp-admin/admin.php?page=wp-tools', 'Line' => '709', 'Script Location' => 'Error', 'Script Name' => 'admin.php', )
                                        $sql_insert = $wpdb->prepare(
                                            "INSERT INTO ". $wptools_table_name. "
                                            (`ip`, `error_number`, `error_type`, `error_string`, `error_file`, `error_line`, `file_location`, `plugin_name`, `theme_name`, `error_date`, `ua`)
                                            VALUES
                                            (%s, %d, %s, %s, %s, %d, %s, %s, %s, %s, %s)",
                                            $ip, $error_number, $error_type, $error_string, $error_file, $error_line, $file_location, $plugin_name, $theme_name, $error_date, $ua
                                        );
                                         // die(var_export($sql_insert));
                                        $wpdb->query($sql_insert);
                                    }
                                    continue;
                                }
                                continue;
                                // END JAVASCRIPT
                            } 
                            else{
                                /* ----- PHP // */
                                $apattern = [];
                                $apattern[] = "/^\[.*\] PHP (Warning|Error|Notice|Fatal error|Parse error): (.*) in \/([^ ]+) on line (\d+)/";
                                $apattern[] = "/^\[.*\] PHP (Warning|Error|Notice|Fatal error|Parse error): (.*) in \/([^ ]+):(\d+)$/";
                                $pattern = $apattern[0];
                                for($j = 0; $j < count($apattern); $j++){
                                    if(preg_match($apattern[$j], $line, $matches)){
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
                                        "Problem Description" => wptools_strip_strong(
                                            $matches[2]
                                        ),
                                    ];
                                    $script_path = $matches[3];
                                    $script_info = pathinfo($script_path);
                                    // Dividir o nome do script com base em ":"
                                    $parts = explode(':', $script_info["basename"]);
                                    // O nome do script agora está na primeira parte
                                    $scriptName = $parts[0];
                                    $log_entry["Script Name"] =
                                    $scriptName; // Get the script name
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
                                            $log_entry["Theme Name"] = $theme_parts[0];
                                        }
                                    }
                                } else {
                                   // $error_date = date('Y-m-d H:i:s', strtotime($log_entry["Date"]));
                                   if (strpos($line, 'WordPress database error') !== false){
                                     $log_entry["Line"] = '';
                                     $error_line = '';
                                     $log_entry["Problem Description"] = 'WordPress database error';
                                     $log_entry["News Type"] = 'WordPress database error';
                                     $error_string = 'WordPress database error';
                                     $error_type = 'WordPress database error';
                                     $error_file = '';
                                     $file_location = '';
                                     $plugin_name = '';
                                     $theme_name = '';
                                     if(!isset($log_entry["Date"]))
                                         continue;
                                     $error_date = date('Y-m-d H:i:s', strtotime($log_entry["Date"]));
                                   }
                                   else 
                                     continue;
                                }
                                if (strpos($line, 'WordPress database error') === false){  
                                    $ip = $log_entry["ip"] ?? '';
                                    $error_number = $log_entry["error_number"] ?? 0;
                                    $error_type = $log_entry["News Type"] ?? '';
                                    $error_string = $log_entry["Problem Description"] ?? '';
                                    $error_file = $log_entry["Script Name"] ?? '';
                                    $error_line = $log_entry["Line"] ?? '';
                                    $file_location = $log_entry["Script Location"] ?? '';
                                    $plugin_name = $log_entry["Plugin Name"] ?? '';
                                    $theme_name = $log_entry["Theme Name"] ?? '';           
                                    $error_date = date('Y-m-d H:i:s', strtotime($log_entry["Date"]));
                                    $ua = '';  // O valor de 'ua' não foi fornecido na sua array $log, substitua por um valor apropriado se necessário
                                }
                                // die(var_export($wptools_table_name));
                                $existing_record = $wpdb->get_var(
                                    $wpdb->prepare(
                                        "SELECT COUNT(*)
                                        FROM ".$wptools_table_name. " 
                                        WHERE `error_date` = %s AND `error_line` = %s",
                                        $error_date, $error_line
                                    )
                                );
                                if (!$existing_record) {
                                    if(!isset($error_number))
                                      continue;
                                    $sql_insert = $wpdb->prepare(
                                        "INSERT INTO ".$wptools_table_name. "
                                        (`ip`, `error_number`, `error_type`, `error_string`, `error_file`, `error_line`, `file_location`, `plugin_name`, `theme_name`, `error_date`, `ua`)
                                        VALUES
                                        (%s, %d, %s, %s, %s, %d, %s, %s, %s, %s, %s)",
                                        $ip, $error_number, $error_type, $error_string, $error_file, $error_line, $file_location, $plugin_name, $theme_name, $error_date, $ua
                                    );
                                    $result = $wpdb->query($sql_insert);
                                    /*
                                    var_dump($ip);
                                    var_dump($error_number);
                                    var_dump($error_type);
                                    var_dump($error_string);
                                    var_dump($error_file);
                                    var_dump($error_line);
                                    var_dump($file_location);
                                    var_dump('wptools');  // ou você pode usar $plugin_name aqui
                                    var_dump($theme_name);
                                    var_dump($error_date);
                                    var_dump($ua);
                                    */
                                    continue;
                                }
                                else{
                                   // var_dump($error_date);
                                    // die();
                                    continue;
                                }
                        }
                            // end if PHP ...         
                    } // end for...
                }
            }
        }
    }
}
?>