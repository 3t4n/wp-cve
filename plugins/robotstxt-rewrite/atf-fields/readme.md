# ATF fields helper

### Text field

### Number

### Textarea

### Radio

```php
<?php AtfHtmlHelper::radio(array(
                        'id' => 'receivers',
                        'name' => 'receivers',
                        'value' => '',
                        'vertical' => false,
                        'options' => array(
                            'val1' => 'Label1',
                            'val2' => 'Label2',
                            'val3' => 'Label3',
                            'val4' => 'Label4',
                        )
                    )); ?>
```

`vertical` _(Default: **true**)_ - show in vertical style.
Just add `<br />` after label

`class` _(Default: **empty string**)_ - use to apply your custom styles
or add show as a buttons (styles included). To show in a button style just add class `.check-buttons`

### Checkbox

```php
<?php AtfHtmlHelper::checkbox(array(
                        'id' => 'receivers',
                        'name' => 'receivers',
                        'value' => '',
                        'vertical' => false,
                        'options' => array(
                            'val1' => 'Label1',
                            'val2' => 'Label2',
                            'val3' => 'Label3',
                            'val4' => 'Label4',
                        )
                    )); ?>
```

`vertical` _(Default: **true**)_ - show in vertical style.
Just add `<br />` after label

`class` _(Default: **empty string**)_ - use to apply your custom styles
or add show as a buttons (styles included). To show in a button style just add class `.check-buttons`

### Group
 
```php
 <?php AtfHtmlHelper::group(array(
		 'name' => 'robots_options[allows]',
		 'items' => array(
			 'path' => array(
				 'title' => __('Path', 'robotstxt-rewrite'),
				 'type' => 'text',
				 'desc' => __('Relative path of WordPress installation directory', 'robotstxt-rewrite'),
			 ),
			 'bots' => array(
				 'title' => __('Robots names', 'robotstxt-rewrite'),
				 'type' => 'checkbox',
				 'options' => array(
					 'googlebot' => 'Google',
					 'googlebot-mobile' => 'Google Mobile',
					 'googlebot-image'=> 'Google Images',
					 'Yandex' => 'Yandex',
				 ),
			 ),
			 'allowed' => array(
				 'title' => __('Allow', 'robotstxt-rewrite'),
				 'type' => 'tumbler',
				 'options' => array('plain' => 'Text', 'html' => 'HTML'),
				 'desc' => __('Allow / Disallow', 'robotstxt-rewrite'),
				 'cell_style' => 'text-align: center;',
			 ),
		 ),
		 //use default key for ATF Options 
		 'value' => array(
		 	array(
		 		'path' => '/',
		 		'bots' => 'all',
		 		'allowed' => 1
		 	),
		 	array(
		 		'path' => '/wp-admin/',
		 		'bots' => 'all',
		 		'allowed' => 0
		 	),
		 ),
	 )
 );
 ?>
```
 
 sdf
