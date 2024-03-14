<?php
if ( ! function_exists('site_url_no_domain')):
/**
 * Same as site_url() but return path without domain name
 * @return string
 */
function site_url_no_domain($path = '') {
	return preg_replace('%^http://[^/]*%', '', site_url($path, 'http'));
}
endif;