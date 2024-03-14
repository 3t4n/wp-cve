<?php

namespace WpifyWooDeps\Wpify\Core\Traits;

use ReflectionClass;
use WpifyWooDeps\Wpify\Core\Exceptions\InexistentPropertyException;
use WpifyWooDeps\Wpify\Core\Exceptions\ReadOnlyException;
trait BaseObjectTrait
{
    /**
     * @param $name
     *
     * @return bool|mixed
     */
    public function __get($name)
    {
        $func = "get_{$name}";
        if (\method_exists($this, $func)) {
            return $this->{$func}();
        }
        $func = 'get' . \ucfirst($name);
        if (\method_exists($this, $func)) {
            return $this->{$func}();
        }
        $func = "is_{$name}";
        if (\method_exists($this, $func)) {
            return $this->{$func}();
        }
        $func = 'is' . \ucfirst($name);
        if (\method_exists($this, $func)) {
            return $this->{$func}();
        }
        if (isset($GLOBALS[$name])) {
            return $GLOBALS[$name];
        }
        return \false;
    }
    /**
     * @param $name
     * @param $value
     *
     * @throws InexistentPropertyException
     * @throws ReadOnlyException
     */
    public function __set($name, $value)
    {
        $func = "set_{$name}";
        if (\method_exists($this, $func)) {
            $this->{$func}($value);
            return;
        }
        $func = 'set' . \ucfirst($name);
        if (\method_exists($this, $func)) {
            return $this->{$func}($value);
        }
        $func = "get_{$name}";
        if (\method_exists($this, $func) || \method_exists($this, 'get' . \ucfirst($name))) {
            throw new ReadOnlyException(\sprintf('Property %s is read-only', $name));
        }
        $func = "is_{$name}";
        if (\method_exists($this, $func) || \method_exists($this, 'is' . \ucfirst($name))) {
            throw new ReadOnlyException(\sprintf('Property %s is read-only', $name));
        }
        if (isset($GLOBALS[$name])) {
            $GLOBALS[$name] = $value;
            return;
        }
        throw new InexistentPropertyException(\sprintf('Inexistent property: %s', $name));
    }
    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $func = "get_{$name}";
        if (\method_exists($this, $func)) {
            return \true;
        }
        $func = 'get' . \ucfirst($name);
        if (\method_exists($this, $func)) {
            return $this->{$func}();
        }
        $func = "is_{$name}";
        if (\method_exists($this, $func)) {
            return \true;
        }
        $func = 'is' . \ucfirst($name);
        if (\method_exists($this, $func)) {
            return $this->{$func}();
        }
        return isset($GLOBALS[$name]);
    }
    public function get_class_name()
    {
        return (new ReflectionClass($this))->getShortName();
    }
    public function get_full_class_name()
    {
        return '\\' . \ltrim((new ReflectionClass($this))->getName(), '\\');
    }
    public function get_file_name()
    {
        return (new ReflectionClass($this))->getFileName();
    }
}
