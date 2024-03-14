<?php
/**
 *       @file  qtrOptions.php
 *      @brief  This module contains implementation of _option method used to run code outside of WordPress
 *
 *     @author  Quttera (qtr), contactus@quttera.com
 *
 *   @internal
 *     Created  01/15/2016
 *    Compiler  gcc/g++
 *     Company  Quttera
 *   Copyright  Copyright (c) 2016, Quttera
 *
 * This source code is released for free distribution under the terms of the
 * GNU General Public License as published by the Free Software Foundation.
 * =====================================================================================
 */
class CQtrOptions
{
    public static function Serialize($object){
        if(!function_exists('add_option') ){
            /* outside of WP */
            $output = json_encode($object);
            return $output;
        }else{
            /* inside WP */
            return serialize($object);
        }
    }


    public static function Unserialize($str){
        if(!function_exists('add_option') ){
            /* outside of WP */
            $output = json_decode($str,true);
            //echo "Serialize: ";
            //var_dump($output);
            return $output;
        }else{
            /* inside WP */
            return unserialize($str);
        }
    }

    public static function SaveOption($option, $value, $deprecated, $autoload, $logger=NULL)
    {
        /*
        $logger->Info(sprintf("Storing snapshot from %s",getcwd()));
        */
        $option_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $option;
        $file = fopen( $option_path, "w+");
        if($file == FALSE ){
            if($logger){ 
                $logger->Error(
                    sprintf(
                        "Failed to open [$option_path] from %s",
                        getcwd())); 
            }
            return FALSE; 
        }

        if (flock($file,LOCK_EX)) {
            $rc = fwrite($file, $value );
            if(!$rc){
                if($logger){$logger->Error("Failed to store [$option_path]");}
                flock($file,LOCK_UN);
                fclose($file);
                return FALSE; 
            }
       
            fflush($file);
            flock($file,LOCK_UN);

        } else {
            if($logger){ $logger->Error("Failed to lock access to [$option_path]");}
            fclose($file);
            return FALSE; 
        }

        flock($file,LOCK_UN);
        fclose($file);
        if($logger){ $logger->Info("Successfully saved content of [$option_path]"); }
        return $rc;
    }

    public static function LoadOption($option, $default = false, $logger=NULL)
    {
        /*
        $logger->Info(sprintf("Loading snapshot from %s",getcwd()));
        */
        $output = FALSE;
        $option_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $option;

        if(!is_file($option_path)){
            return $default;
        }

        $file = fopen( $option_path, "r+");
        if(!$file){
            if($logger){ 
                $logger->Error(
                    sprintf(
                        "Failed to open [$option_path] for reading from %s",
                        getcwd())); 
            }
            return FALSE; 
        }

        if (flock($file,LOCK_EX)) {
            fflush($file);
            $output = fread($file,filesize($option_path));
            flock($file,LOCK_UN);
        } else {
            if($logger){ $logger->Error("Failed to lock access to [$option_path]");}
            fclose($file);
            return FALSE; 
        }

        fclose($file);
        if($logger){ 
            $csize = strlen($output);          
            $logger->Info("Successfully loaded content of [$option_path], $csize bytes");
        }
        return $output;
    }

    public static function AddOption($option,$value, $deprecated, $autoload, $logger=NULL)
    {
        $rc = TRUE;
        if(!function_exists('add_option') )
        {
            if($logger){ $logger->Error("add_option is not implemented, using file to manage options");}
            return SaveOption($option,$value, $deprecated, $autoload, $logger);
            /*
            $file = fopen($option,"w") or die("Unable to open file!");
            fwrite($file, $value );
            fflush($file);
            fclose($file);
            */
        }
        else
        {
            $rc = add_option( $option,$value, $deprecated, $autoload );
            if(!$rc){
                if($logger){$logger->Error("Failed to add option $option");}
            }else{
                if($logger){$logger->Info("Content of $option storred successfully");}
            }
        }
        return $rc;
    }

    public static function GetOption($option,$default = false, $logger=NULL)
    {
        $output = FALSE;
        if(!function_exists('get_option') )
        {
            if($logger){$logger->Error("get_option is not implemented, using file to manage options");}
            return LoadOption($option, $default, $logger);
            /*
            if(!is_file($option)){
                return $default;
            }
            $file = fopen( $option,"r+");
            fflush($file);
            $output = fread($file,filesize($option) + 10);
            fclose($file);
            */
        }
        else
        {
            $output = get_option($option);
            if(!$output){
                if($logger){$logger->Error("Failed to load option $option");}
            }else{
                if($logger){$logger->Info("Content of $option retrieved successfully");}
            }
        }
        return $output;
    }


    public static function UpdateOption($option, $value, $logger=NULL)
    {        
        if(!function_exists('update_option') )
        {
            if($logger){$logger->Error("update_option is not implemented, using file to manage options");}
            unset($logger);       
            return self::AddOption($option,$value,NULL,NULL);
        }
        else
        {
            $rc = update_option($option, $value);
            if(!$rc){
                if($logger){$logger->Error("Failed to update option $option");}
            }else{
                if($logger){$logger->Info("Content of $option updated successfully");}
            }
            return $rc;
        }
    }
}

?>
