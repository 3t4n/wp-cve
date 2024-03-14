<?php
namespace YTP\Database;

use YTP\Database\Table;

class Init{
   
    public static function get_tables(){
        return [
            Presets::class,
        ];
    }

    public function register(){
        foreach(self::get_tables() as $class){
            $table = self::instantiate($class);
            if(method_exists($table, 'install')){
                $table->install();
            }
        }
    }

    public static function drop(){
        foreach(self::get_tables() as $class){
            $table = self::instantiate($class);
            if(method_exists($table, 'uninstall')){
                $table->uninstall();
            }
        }
        return true;
    }


    private static function instantiate($class){
        return new $class(new Table);
    }
}




