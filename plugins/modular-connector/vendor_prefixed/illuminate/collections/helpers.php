<?php

namespace Modular\ConnectorDependencies;

use Modular\ConnectorDependencies\Illuminate\Support\Arr;
use Modular\ConnectorDependencies\Illuminate\Support\Collection;
if (!\function_exists('Modular\\ConnectorDependencies\\collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Support\Collection
     * @internal
     */
    function collect($value = null)
    {
        return new Collection($value);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\data_fill')) {
    /**
     * Fill in data where it's missing.
     *
     * @param  mixed  $target
     * @param  string|array  $key
     * @param  mixed  $value
     * @return mixed
     * @internal
     */
    function data_fill(&$target, $key, $value)
    {
        return data_set($target, $key, $value, \false);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\data_get')) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed  $target
     * @param  string|array|int|null  $key
     * @param  mixed  $default
     * @return mixed
     * @internal
     */
    function data_get($target, $key, $default = null)
    {
        if (\is_null($key)) {
            return $target;
        }
        $key = \is_array($key) ? $key : \explode('.', $key);
        foreach ($key as $i => $segment) {
            unset($key[$i]);
            if (\is_null($segment)) {
                return $target;
            }
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (!\is_array($target)) {
                    return \Modular\ConnectorDependencies\value($default);
                }
                $result = [];
                foreach ($target as $item) {
                    $result[] = \Modular\ConnectorDependencies\data_get($item, $key);
                }
                return \in_array('*', $key) ? Arr::collapse($result) : $result;
            }
            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (\is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return \Modular\ConnectorDependencies\value($default);
            }
        }
        return $target;
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\data_set')) {
    /**
     * Set an item on an array or object using dot notation.
     *
     * @param  mixed  $target
     * @param  string|array  $key
     * @param  mixed  $value
     * @param  bool  $overwrite
     * @return mixed
     * @internal
     */
    function data_set(&$target, $key, $value, $overwrite = \true)
    {
        $segments = \is_array($key) ? $key : \explode('.', $key);
        if (($segment = \array_shift($segments)) === '*') {
            if (!Arr::accessible($target)) {
                $target = [];
            }
            if ($segments) {
                foreach ($target as &$inner) {
                    data_set($inner, $segments, $value, $overwrite);
                }
            } elseif ($overwrite) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif (Arr::accessible($target)) {
            if ($segments) {
                if (!Arr::exists($target, $segment)) {
                    $target[$segment] = [];
                }
                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || !Arr::exists($target, $segment)) {
                $target[$segment] = $value;
            }
        } elseif (\is_object($target)) {
            if ($segments) {
                if (!isset($target->{$segment})) {
                    $target->{$segment} = [];
                }
                data_set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || !isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];
            if ($segments) {
                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite) {
                $target[$segment] = $value;
            }
        }
        return $target;
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\head')) {
    /**
     * Get the first element of an array. Useful for method chaining.
     *
     * @param  array  $array
     * @return mixed
     * @internal
     */
    function head($array)
    {
        return \reset($array);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\last')) {
    /**
     * Get the last element from an array.
     *
     * @param  array  $array
     * @return mixed
     * @internal
     */
    function last($array)
    {
        return \end($array);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     * @internal
     */
    function value($value, ...$args)
    {
        return $value instanceof \Closure ? $value(...$args) : $value;
    }
}
