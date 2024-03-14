Add this to file wp-config.php

```php

define( 'APP_BUILDER_CURRENCIES', serialize( array(
	'USD' => array(
		'currency'     => 'USD',
		'symbol'       => '$',
		'position'     => 'right_space',
		'thousand_sep' => 'right_space',
		'decimal_sep'  => '.',
		'num_decimals' => 2,
	),
	'VND' => array(
		'currency'     => 'VND',
		'symbol'       => 'đ',
		'position'     => 'right_space',
		'thousand_sep' => 'right_space',
		'decimal_sep'  => '.',
		'num_decimals' => 2,
	),
) ) );

```