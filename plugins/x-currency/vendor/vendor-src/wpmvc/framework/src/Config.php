<?php

namespace XCurrency\WpMVC;

class Config
{
    protected static array $configs = [];
    public function get(string $config_key)
    {
        $keys = \explode('.', $config_key);
        $config = $this->get_config(\array_shift($keys));
        foreach ($keys as $key) {
            if (!isset($config[$key])) {
                return null;
            }
            $config = $config[$key];
        }
        return $config;
    }
    protected function get_config(string $config_file)
    {
        if (isset(static::$configs[$config_file])) {
            return static::$configs[$config_file];
        }
        $config_file_path = App::get_dir("config/{$config_file}.php");
        $config = (include $config_file_path);
        static::$configs[$config_file] = $config;
        return $config;
    }
}
