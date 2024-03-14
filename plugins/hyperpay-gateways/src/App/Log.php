<?php

namespace Hyperpay\Gateways\App;

use Hyperpay\Gateways\Main;

class Log
{

    public static function load()
    {

        $files = scandir(Main::ROOT_PATH . "/logs");
        $files = \array_filter($files, function ($name) {
            return !preg_match('/^[.]/', $name);
        });
        $current_file =  isset($_GET['file']) ?  $_GET['file'] : end($files);

       
        if(empty($files)){
            self::print_error("There is no files in the log directory");
            return;
        }

        if($current_file && file_exists(Main::ROOT_PATH . "/logs/$current_file")){
            $content = file_get_contents(Main::ROOT_PATH . "/logs/$current_file");
        }else{
            self::print_error("Current file dose not exists");
            $content = '';
        }

        wp_enqueue_script('code-editor');
        wp_enqueue_style('code-editor');

        $content_label = _e('Selected file content:'); 
        $url = $_SERVER["REQUEST_URI"];



        return View::render('logs.html', \compact('content_label', 'content' ,'current_file' , 'files' ,'url' ));

    }


    private static function print_error($error)
    {
        ?>
            <div class="notice notice-error">
                <p>
                    <?php echo $error;?>
                </p>
            </div>
        <?php
    }

    private static function fileName()
    {
        $today = date("Y-m-d");
        return Main::ROOT_PATH . "/logs/$today.log";
    }

    public static function write($msg)
    {
        $log_file = static::fileName();
        $time = date("H:i:s");
        $message = print_r($msg, true);
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);
        if (isset($bt[2])) {
            $caller = $bt[2];
            $file = $caller['file'];
            $line = $caller['line'];
            $title = "[$time $file:$line]";
            $footer = print_r($bt[2]['args'], true);
        } else {
            $title = "[$time]";
            $footer = print_r($bt, true);
        }

        error_log("$title\n$message\n$footer\n", 3, $log_file);
    }

    public static function removeExpiredLogs()
    {

        $dir = Main::ROOT_PATH . "/logs";
        $log_resources = opendir($dir);

        while (($file = readdir($log_resources)) !== false) {
            if (!preg_match('/^[.]/', $file)) {
                $path = $dir  . "/$file";
                if (filemtime($path) < strtotime('-14 days')) {  // if file age > 14 days
                    unlink($path);  // remove it
                }
            }
        }
    }
}
