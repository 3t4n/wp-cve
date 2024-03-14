[![pipeline status](https://gitlab.com/wpdesk/wp-builder/badges/master/pipeline.svg)](https://gitlab.com/wpdesk/wp-builder/pipelines)
[![coverage report](https://gitlab.com/wpdesk/wp-builder/badges/master/coverage.svg?job=unit+test+lastest+coverage)](https://gitlab.com/wpdesk/wp-builder/commits/master)
[![Latest Stable Version](https://poser.pugx.org/wpdesk/wp-builder/v/stable)](https://packagist.org/packages/wpdesk/wp-builder)
[![Total Downloads](https://poser.pugx.org/wpdesk/wp-builder/downloads)](https://packagist.org/packages/wpdesk/wp-builder)
[![Latest Unstable Version](https://poser.pugx.org/wpdesk/wp-builder/v/unstable)](https://packagist.org/packages/wpdesk/wp-builder)
[![License](https://poser.pugx.org/wpdesk/wp-builder/license)](https://packagist.org/packages/wpdesk/wp-builder)

# WP Builder

wp-builder library defines the interfaces and abstracts to create WordPress plugins. 

## Requirements

PHP 5.6 or later.

## Installation via Composer

In order to install the bindings via [Composer](http://getcomposer.org/) run the following command:

```bash
composer require wpdesk/wp-builder
```

## Example usage

Use the following code in WordPress plugin's main .php file:

```php
<?php

class ContentExtender implements \WPDesk\PluginBuilder\Plugin\Hookable {

	public function hooks() {
		add_filter( 'the_content', [ $this, 'append_sample_text_to_content' ] );
	}
	
	/**
	 * @param string $content
	 * @return string
 	 */
	public function append_sample_text_to_content( $content ) {
		return $content . ' Sample text';
	}
}

class ExamplePlugin extends \WPDesk\PluginBuilder\Plugin\AbstractPlugin implements \WPDesk\PluginBuilder\Plugin\HookableCollection {

	use \WPDesk\PluginBuilder\Plugin\HookableParent;
	
	public function hooks() {
		$this->add_hookable( new ContentExtender() );
		
		$this->hooks_on_hookable_objects();
	}

}

$plugin_info = new WPDesk_Plugin_Info();
$plugin_info->set_plugin_name( 'Example Plugin' );

$example_plugin = new ExamplePlugin( $plugin_info ); 

```
