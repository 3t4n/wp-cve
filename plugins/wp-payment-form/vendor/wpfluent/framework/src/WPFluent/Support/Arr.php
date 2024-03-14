<?php

namespace WPPayForm\Framework\Support;

use Closure;
use ArrayAccess;
use WPPayForm\Framework\Support\Helper;
use WPPayForm\Framework\Support\Collection;
use WPPayForm\Framework\Support\MacroableTrait;

class Arr
{
    use MacroableTrait;

    /**
     * Makes a collection from array
     * 
     * @param  array $array
     * @return WPPayForm\Framework\Support\Collection
     */
    public static function of(array $array)
    {
        return Helper::collect($array);
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed  $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param  array  $array
     * @param  string  $key
     * @param  mixed  $value
     * @return array
     */
    public static function add($array, $key, $value)
    {
        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }

        return $array;
    }

    public static function isTrue($array, $key)
    {
        $value = self::get($array, $key);
        if (is_bool($value)) {
            return $value;
        }
        if ($value == 'false' || $value == '0' || !$value) {
            return false;
        }
        return true;
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param  iterable  $array
     * @return array
     */
    public static function collapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            } elseif (!is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return array_merge([], ...$results);
    }

    /**
     * Cross join the given arrays, returning all possible permutations.
     *
     * @param  iterable  ...$arrays
     * @return array
     */
    public static function crossJoin(...$arrays)
    {
        $results = [[]];

        foreach ($arrays as $index => $array) {
            $append = [];

            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;

                    $append[] = $product;
                }
            }

            $results = $append;
        }

        return $results;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param  array  $array
     * @return array
     */
    public static function divide($array)
    {
        return [array_keys($array), array_values($array)];
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  iterable  $array
     * @param  string  $prepend
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Convert a flatten "dot" notation array into an expanded array.
     *
     * @param  iterable  $array
     * @return array
     */
    public static function undot($array)
    {
        $results = [];

        foreach ($array as $key => $value) {
            static::set($results, $key, $value);
        }

        return $results;
    }

    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    public static function except($array, $keys)
    {
        static::forget($array, $keys);

        return $array;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int  $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof Enumerable) {
            return $array->has($key);
        }

        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param  iterable  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public static function first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return Helper::value($default);
            }

            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return Helper::value($default);
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public static function last($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? Helper::value($default) : end($array);
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  iterable  $array
     * @param  int  $depth
     * @return array
     */
    public static function flatten($array, $depth = INF)
    {
        $result = [];

        foreach ($array as $item) {
            $item = $item instanceof Collection ? $item->all() : $item;

            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1
                    ? array_values($item)
                    : static::flatten($item, $depth - 1);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return void
     */
    public static function forget(&$array, $keys)
    {
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (static::exists($array, $key)) {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);

            // clean up before each pass
            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (!static::accessible($array)) {
            return Helper::value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? Helper::value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return Helper::value($default);
            }
        }

        return $array;
    }

    /**
     * Check if an item or items (using key) exist in an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|array  $keys
     * @return bool
     */
    public static function has($array, $keys)
    {
        $keys = (array) $keys;

        if (!$array || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Determine if any of the keys exist in an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|array  $keys
     * @return bool
     */
    public static function hasAny($array, $keys)
    {
        if (is_null($keys)) {
            return false;
        }

        $keys = (array) $keys;

        if (!$array) {
            return false;
        }

        if ($keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            if (static::has($array, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param  array  $array
     * @return bool
     */
    public static function isAssoc(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * Determines if an array is a list.
     *
     * An array is a "list" if all array keys are sequential integers starting from 0 with no gaps in between.
     *
     * @param  array  $array
     * @return bool
     */
    public static function isList($array)
    {
        return !self::isAssoc($array);
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    public static function only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param  iterable  $array
     * @param  string|array|int|null  $value
     * @param  string|array|null  $key
     * @return array
     */
    public static function pluck($array, $value, $key = null)
    {
        $results = [];

        [$value, $key] = static::explodePluckParameters($value, $key);

        foreach ($array as $item) {
            $itemValue = Helper::dataGet($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = Helper::dataGet($item, $key);

                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string) $itemKey;
                }

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param  string|array  $value
     * @param  string|array|null  $key
     * @return array
     */
    protected static function explodePluckParameters($value, $key)
    {
        $value = is_string($value) ? explode('.', $value) : $value;

        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);

        return [$value, $key];
    }

    /**
     * Push an item onto the beginning of an array.
     *
     * @param  array  $array
     * @param  mixed  $value
     * @param  mixed  $key
     * @return array
     */
    public static function prepend($array, $value, $key = null)
    {
        if (func_num_args() == 2) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param  array  $array
     * @param  string|int  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function pull(&$array, $key, $default = null)
    {
        $value = static::get($array, $key, $default);

        static::forget($array, $key);

        return $value;
    }

    /**
     * Convert the array into a query string.
     *
     * @param  array  $array
     * @return string
     */
    public static function query($array)
    {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * Get one or a specified number of random values from an array.
     *
     * @param  array  $array
     * @param  int|null  $number
     * @param  bool|false  $preserveKeys
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function random($array, $number = null, $preserveKeys = false)
    {
        $requested = is_null($number) ? 1 : $number;

        $count = count($array);

        if ($requested > $count) {
            throw new InvalidArgumentException(
                "You requested {$requested} items, but there are only {$count} items available."
            );
        }

        if (is_null($number)) {
            return $array[array_rand($array)];
        }

        if ((int) $number === 0) {
            return [];
        }

        $keys = array_rand($array, $number);

        $results = [];

        if ($preserveKeys) {
            foreach ((array) $keys as $key) {
                $results[$key] = $array[$key];
            }
        } else {
            foreach ((array) $keys as $key) {
                $results[] = $array[$key];
            }
        }

        return $results;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array  $array
     * @param  string|null  $key
     * @param  mixed  $value
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        foreach ($keys as $i => $key) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Shuffle the given array and return the result.
     *
     * @param  array  $array
     * @param  int|null  $seed
     * @return array
     */
    public static function shuffle($array, $seed = null)
    {
        if (is_null($seed)) {
            shuffle($array);
        } else {
            mt_srand($seed);
            shuffle($array);
            mt_srand();
        }

        return $array;
    }

    /**
     * Sort the array using the given callback or "dot" notation.
     *
     * @param  array  $array
     * @param  callable|array|string|null  $callback
     * @return array
     */
    public static function sort($array, $callback = null)
    {
        return Collection::make($array)->sortBy($callback)->all();
    }

    /**
     * Recursively sort an array by keys and values.
     *
     * @param  array  $array
     * @param  int  $options
     * @param  bool  $descending
     * @return array
     */
    public static function sortRecursive($array, $options = SORT_REGULAR, $descending = false)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = static::sortRecursive($value, $options, $descending);
            }
        }

        if (static::isAssoc($array)) {
            $descending
                ? krsort($array, $options)
                : ksort($array, $options);
        } else {
            $descending
                ? rsort($array, $options)
                : sort($array, $options);
        }

        return $array;
    }

    /**
     * Conditionally compile classes from an array into a CSS class list.
     *
     * @param  array  $array
     * @return string
     */
    public static function toCssClasses($array)
    {
        $classList = static::wrap($array);

        $classes = [];

        foreach ($classList as $class => $constraint) {
            if (is_numeric($class)) {
                $classes[] = $constraint;
            } elseif ($constraint) {
                $classes[] = $class;
            }
        }

        return implode(' ', $classes);
    }

    /**
     * Filter the array using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return array
     */
    public static function where($array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Filter items where the value is not null.
     *
     * @param  array  $array
     * @return array
     */
    public static function whereNotNull($array)
    {
        return static::where($array, function ($value) {
            return !is_null($value);
        });
    }

    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    public static function wrap($value)
    {
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * Maps a function to all non-iterable elements of an array or an object.
     *
     * This is similar to `array_walk_recursive()` but acts upon objects too.
     *
     * @param mixed    $value    The array, object, or scalar.
     * @param callable $callback The function to map onto $value.
     * @see https://developer.wordpress.org/reference/functions/map_deep/
     * 
     * @return mixed The value with the callback applied to all non-arrays and non-objects inside it.
     */
    public static function map($value, $callback)
    {
        return map_deep($value, $callback);
    }

    /**
     * Check if the value(s) exist in an array using "dot" notation.
     *
     * @param  array $array
     * @param  string|array  $values
     * @return bool
     */
    public static function contains(array $array, $values)
    {
        $result = [];
        
        $values = is_array($values) ? $values : [$values];

        foreach ($values as $value) {

            if (in_array($value, $array)) {
                $result[] = $value;
                continue;
            }

            $segments = explode('.', $value);

            $value = array_pop($segments);

            $nested = (array) static::get($array, implode('.', $segments));

            if ($nested && in_array($value, $nested)) {
                $result[] = $value;
            }
        }

        return count($result) === count($values);
    }

    /**
     * Check if the any value exist in an array using "dot" notation.
     *
     * @param  array $array
     * @param  string|array  $values
     * @return bool
     */
    public static function containsAny(array $array, $values)
    {
        $result = [];
        
        $values = is_array($values) ? $values : [$values];

        foreach ($values as $value) {

            if (in_array($value, $array)) {
                return true;
            }

            $segments = explode('.', $value);

            $value = array_pop($segments);

            $nested = (array) static::get($array, implode('.', $segments));

            if ($nested && in_array($value, $nested)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compare two nested arrays side by side
     * @param  array $array1
     * @param  array $array2
     * @param  array $path
     * @return array
     */
    public static function compare($array1, $array2, $path = [])
    {
        $differences = [];

        foreach ($array1 as $key => $value1) {
            // Check if the key exists in the second array
            if (!array_key_exists($key, $array2)) {
                $differences[implode('.', array_merge($path, [$key]))] = [
                    'array_1' => $value1,
                    'array_2' => null,
                ];
            } else {
                // If the value is an array, recursively compare
                if (is_array($value1) && is_array($array2[$key])) {
                    $differences = array_merge($differences, static::compare(
                        $value1, $array2[$key], array_merge($path, [$key])
                    ));
                } else {
                    // Compare values
                    if ($value1 !== $array2[$key]) {
                        $differences[implode('.', array_merge($path, [$key]))] = [
                            'array_1' => $value1,
                            'array_2' => $array2[$key],
                        ];
                    }
                }
            }
        }

        // Check for keys in the second array that are not in the first array
        foreach ($array2 as $key => $value2) {
            if (!array_key_exists($key, $array1)) {
                $differences[implode('.', array_merge($path, [$key]))] = [
                    'array_1' => null,
                    'array_2' => $value2,
                ];
            }
        }

        return $differences;
    }

    /**
     * Merge the items from the first array into the
     * second array if the second array is missing it.
     * 
     * @param  array &$array1
     * @param  array &$array2
     * @return array         
     */
    public static function mergeMissing(&$array1, &$array2)
    {
        foreach ($array1 as $key => $value1) {
            // If the key exists in the second array
            if (array_key_exists($key, $array2)) {
                // If the value is an array, recursively add missing items
                if (is_array($value1) && is_array($array2[$key])) {
                    static::mergeMissing($value1, $array2[$key]);
                }
            } else {
                // If the key doesn't exist in the second array,
                // then add it with the corresponding value
                $array2[$key] = $value1;
            }
        }

        return $array2;
    }

    /**
     * Return matching items from array (similar to mysql's %LIKE%)
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function like($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~'. preg_quote($pattern, '~') . '~i';
        }

        return preg_grep($pattern, $array);
    }

    /**
     * Return non-matching items from array (similar to mysql's NOT %LIKE%)
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function notLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~'. preg_quote($pattern, '~') . '~i';
        }

        return preg_grep($pattern, $array, PREG_GREP_INVERT);
    }

    /**
     * Return matching starting of items from array (similar to mysql's %LIKE)
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function startsLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~^'. preg_quote($pattern, '~') . '~i';
        }

        return preg_grep($pattern, $array);
    }

    /**
     * Return non-matching starting of items from array (similar to mysql's NOT %LIKE)
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function DoesNotStartLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern = '~^(?!' . preg_quote($pattern, '~') . ')~i';
        }

        return preg_grep($pattern, $array);
    }

    /**
     * Return matching ending of items from array (similar to mysql's LIKE%)
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function endsLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~'. preg_quote($pattern, '~') . '$~i';
        }

        return preg_grep($pattern, $array);
    }

    /**
     * Return non-matching ending of items from array (similar to mysql's NOT LIKE%)
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function DoesNotEndLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~'. preg_quote($pattern, '~') . '$~i';
        }

        return preg_grep($pattern, $array, PREG_GREP_INVERT);
    }

    /**
     * Return matching items from array by keys
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function keysLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~'. preg_quote($pattern, '~') . '~i';
        }

        $values = [];
        
        $keys = preg_grep($pattern, array_keys($array));
        
        foreach ($keys as $key) {
            $values[$key] = $array[$key];
        }
        
        return $values;
    }

    /**
     * Return non-matching items from array by keys
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function keysNotLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~'. preg_quote($pattern, '~') . '~i';
        }

        $values = [];
        
        $keys = preg_grep($pattern, array_keys($array), 1);
        
        foreach ($keys as $key) {
            $values[$key] = $array[$key];
        }
        
        return $values;
    }

    /**
     * Return matching starting of items from array by keys
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function keysStartLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~^'. preg_quote($pattern, '~') . '~i';
        }

        $values = [];
        
        $keys = preg_grep($pattern, array_keys($array));
        
        foreach ($keys as $key) {
            $values[$key] = $array[$key];
        }
        
        return $values;
    }

    /**
     * Return non-matching starting of items from array by keys
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function keysDoesNotStartLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern = '~^(?!' . preg_quote($pattern, '~') . ')~i';
        }

        $values = [];
        
        $keys = preg_grep($pattern, array_keys($array));
        
        foreach ($keys as $key) {
            $values[$key] = $array[$key];
        }
        
        return $values;
    }

    /**
     * Return matching ending of items from array by keys
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function keysEndLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~'. preg_quote($pattern, '~') . '$~i';
        }

        $values = [];
        
        $keys = preg_grep($pattern, array_keys($array));
        
        foreach ($keys as $key) {
            $values[$key] = $array[$key];
        }
        
        return $values;
    }

    /**
     * Return non-matching ending of items from array by keys
     * 
     * @param  string|regex $pattern
     * @param  array $array
     * @return array|false
     */
    public static function keysDoesNotEndLike($pattern, $array)
    {
        if (@preg_match($pattern, '') === false) {
            $pattern =  '~'. preg_quote($pattern, '~') . '$~i';
        }

        $values = [];
        
        $keys = preg_grep($pattern, array_keys($array), PREG_GREP_INVERT);
        
        foreach ($keys as $key) {
            $values[$key] = $array[$key];
        }
        
        return $values;
    }
}
