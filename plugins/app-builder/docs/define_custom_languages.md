Add this to file wp-config.php

```php

define( 'APP_BUILDER_LANGUAGES', serialize( array(
	'en' => array(
		'code'          => 'en',
		'language_code' => 'en',
		'native_name'   => 'English',
	),
	'ar' => array(
		'code'          => 'ar',
		'language_code' => 'ar',
		'native_name'   => 'Arabic',
	)
) ) );

```