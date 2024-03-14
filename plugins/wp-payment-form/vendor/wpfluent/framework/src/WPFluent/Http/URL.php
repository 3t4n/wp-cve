<?php

namespace WPPayForm\Framework\Http;

class URL
{
	/**
	 * Get the current URL
	 * 
	 * @return string
	 */
	public function current()
	{
		return get_site_url() . rtrim(preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']), '/');
	}

	/**
	 * Parse a url from uncompiled route
	 * 
	 * @param  string $url
	 * @param  array  $params
	 * @return string
	 */
	public function parse(string $url, array $params)
	{
	    return preg_replace_callback('#\{[a-zA-Z0-9-_]+\}#', function($matches) use ($params) {
	        foreach ($matches as $match) {
	            $match = str_replace(['{', '}'], ['', ''], $match);
	            if (isset($params[$match])) {
	                return $params[$match];
	            }
	        }
	    }, $url);
	}

	/**
	 * Returns the string representation of the URL object
	 * 
	 * @return string Current URL.
	 */
	public function __toString()
	{
		return $this->current();
	}
}
