# SendWP

The easy solution to transactional email in WordPress.

## Local Development

The SendWP Server URL can be overriden in `wp-config.php` by adding the following:

```php
define('SENDWP_SERVER_URL', 'https://wordpress.local');
```

When developing with a local instance of the SendWP server, be sure to filter `https_ssl_verify`.

```php
add_filter( 'https_local_ssl_verify', '__return_false' );
add_filter( 'https_ssl_verify', '__return_false' );
```