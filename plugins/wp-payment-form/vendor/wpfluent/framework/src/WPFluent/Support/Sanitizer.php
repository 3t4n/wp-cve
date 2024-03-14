<?php

namespace WPPayForm\Framework\Support;

Class Sanitizer
{
	public static function sanitizeEmail($arg)
	{
		return sanitize_email($arg);
	}

	public static function sanitizeFileName($arg)
	{
		return sanitize_file_name($arg);
	}

	public static function sanitizeHtmlClass($arg)
	{
		return sanitize_html_class($arg);
	}

	public static function sanitizeKey($arg)
	{
		return sanitize_key($arg);
	}

	public static function sanitizeMeta($arg)
	{
		return sanitize_meta($arg);
	}

	public static function sanitizeMimeType($arg)
	{
		return sanitize_mime_type($arg);
	}

	public static function sanitizeOption($arg)
	{
		return sanitize_option($arg);
	}

	public static function sanitizeSqlOrderby($arg)
	{
		return sanitize_sql_orderby($arg);
	}

	public static function sanitizeTextField($arg)
	{
		return sanitize_text_field($arg);
	}

	public static function sanitizeTitle($arg)
	{
		return sanitize_title($arg);
	}

	public static function sanitizeTitleForQuery($arg)
	{
		return sanitize_title_for_query($arg);
	}

	public static function sanitizeTitleWithDashes($arg)
	{
		return sanitize_title_with_dashes($arg);
	}

	public static function sanitizeUser($arg)
	{
		return sanitize_user($arg);
	}

	public static function wpFilterPostKses($arg)
	{
		return wp_filter_post_kses($arg);
	}

	public static function wpFilterNohtmlKses($arg)
	{
		return wp_filter_nohtml_kses($arg);
	}

	public static function escAttr($arg)
	{
		return esc_attr($arg);
	}

	public static function escHtml($arg)
	{
		return esc_html($arg);
	}

	public static function escJs($arg)
	{
		return esc_js($arg);
	}

	public static function escTextarea($arg)
	{
		return esc_textarea($arg);
	}

	public static function escUrl($arg)
	{
		return esc_url($arg);
	}

	public static function escUrlRaw($arg)
	{
		return esc_url_raw($arg);
	}

	public static function escXml($arg)
	{
		return esc_xml($arg);
	}

	public static function kses($arg)
	{
		return wp_kses($arg);
	}

	public static function ksesPost($arg)
	{
		return wp_kses_post($arg);
	}

	public static function ksesData($arg)
	{
		return wp_kses_data($arg);
	}

	public static function escHtml__($arg)
	{
		return esc_html__($arg);
	}

	public static function escAttr__($arg)
	{
		return esc_attr__($arg);
	}

	public static function escHtmlE($arg)
	{
		return esc_html_e($arg);
	}

	public static function escAttrE($arg)
	{
		return esc_attr_e($arg);
	}

	public static function escHtmlX($arg)
	{
		return esc_html_x($arg);
	}

	public static function escAttrX($arg)
	{
		return esc_attr_x($arg);
	}

	public static function sanitize(array $data = [], array $rules = [])
	{
		$array = $result = [];

		foreach ($rules as $key => $callbacks) {

			if (!$callbacks) continue;

			$callbacks = is_array($callbacks) ? $callbacks : [$callbacks];

			$array[$key] = $callbacks;

            if (str_contains($key, '*')) {
                $array = static::substituteWildcardKeys($array, $key, $data);
            }

            foreach ($array as $k => $callbacks) {

	            $callbacks = static::mayBeFixCallbacks($callbacks);

	            if (($value = Arr::get($data, $k)) !== null) {
	                $callbacks = is_callable($callbacks) ? [$callbacks] : $callbacks;

	                while ($callback = static::getCallback(array_shift($callbacks))) {
	                    if (is_array($value)) {
	                        $value = array_map($callback, $value);
	                    } else {
	                        $value = $callback($value);
	                    }
	                }

	                Arr::set($result, $k, $value);
	            }
	        }
		}

		return $result;
	}

	/**
     * Normalize wildcard rules to dotted rule, i.e:
     * key_one.*.key_two.*.key_three becomes:
     * key_one.0.key_two.0.key_three.0
     * key_one.0.key_two.1.key_three.0
     * depending on the data array.
     * 
     * @param  array $array
     * @param  string $field
     * @return array
     */
    protected static function substituteWildcardKeys($array, $field, $data)
    {
        $callback = $array[$field];

        $keys = array_map(function($v) {
            return trim($v, '.');
        }, explode('*', $field));

        $key = array_shift($keys);
        
        if ($key && ($val = Arr::get($data, $key)) && is_array($val)) {
            
            $dotted = array_keys(Arr::dot($val, $key . '.'));
            
            foreach ($dotted as $dottedField) {
                
                $r = preg_replace('/[0-9]+/', '*', $dottedField);
                
                if (preg_match("/{$field}/", $r)) {
                    
                    $array[$dottedField] = $callback;
                    
                    if (isset($array[$field])) {
                        unset($array[$field]);
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Check and fix if callbacks are given
     * as: callback1|callback2\callback3.
     * 
     * @param  array|string $callbacks
     * @return array
     */
    protected static function mayBeFixCallbacks($callbacks)
    {
        $nonFunctionCallables = $functionCallables = [];
            
        foreach ($callbacks as $cb) {
            if (is_callable($cb)) {
                $nonFunctionCallables[] = $cb;
            } elseif (is_string($cb)) {
                 $functionCallables[] = explode('|', $cb);
            } elseif (is_array($cb) && !str_contains($cb[0], '::')) {
                $functionCallables[] = $cb[0];
            } elseif (is_array($cb) && str_contains($cb[0], '::')) {
                $nonFunctionCallables[] = explode('::', $cb[0]);
            }
        }

        $callbacks = array_merge(
            Arr::flatten($functionCallables), $nonFunctionCallables
        );

        return $callbacks;
    }

	protected static function getCallback($callback)
	{
		if ($callback) {
			
			if ($cb = static::methodExists($callback)) {
				$callback = $cb;
			}
		}
		
		return $callback;
	}

	protected static function methodExists($method)
	{
		$suffix = '';

		if (Str::endsWith($method, '__')) {
			$suffix = '__';
		}
		
		$method = Str::camel($method) . $suffix;

		if (method_exists(static::class, $method)) {
			return [static::class, $method];
		}
	}
}
