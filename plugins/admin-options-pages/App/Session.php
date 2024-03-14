<?php

namespace AOP\App;

use AOP\Lib\Illuminate\Support\Collection;

class Session
{
    private static $instance;
    private static $optionName =  Plugin::PREFIX_ . 'session_handler';

    public static function allOptions()
    {
        if (is_null(self::$instance)) {
            add_option(self::$optionName, [], '', 'no');

            self::$instance = get_option(self::$optionName);
        }

        return self::$instance;
    }

    public static function get($sessionItem)
    {
        if (!Collection::make(self::allOptions())->has($sessionItem)) {
            return false;
        }

        return self::allOptions()[$sessionItem];
    }

    public static function add(array $sessionItem)
    {
        update_option(self::$optionName, $sessionItem);
    }

    public static function delete($sessionItem)
    {
        // $options = self::allOptions();

        // dump(self::allOptions());

        $options = Collection::make(self::allOptions())->filter(function ($item, $key) use ($sessionItem) {
            // dump($sessionItem);
            // dump($item);
            // dump($key);

            return $sessionItem !== $key;
        })->toArray();

        // dump($options);

        // wp_die();

        // dump(self::allOptions());

        // unset(self::allOptions()[$sessionItem]);

        // dump(self::allOptions());

        // wp_die();

        // dump(get_option(self::$optionName));

        update_option(self::$optionName, $options);

        // dump(get_option(self::$optionName));

        // wp_die();
    }

    public static function destroy()
    {
        update_option(self::$optionName, []);
    }
}
