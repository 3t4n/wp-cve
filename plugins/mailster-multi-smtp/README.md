# Mailster Multi SMTP

Contributors: everpress, xaverb  
Tags: mailster, newsletter, delivery, deliverymethod, email, smtp, multi-smtp  
Requires at least: 3.8  
Tested up to: 6.1  
Stable tag: 1.3.1  
License: GPLv2 or later

## Description

Allows to use multiple SMTP connection for the Mailster Newsletter Plugin

> This Plugin requires [Mailster Newsletter Plugin for WordPress](https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=readme&utm_term=Multi+SMTP)

## Installation

1. Upload the entire `mailster-multi-smtp` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings => Newsletter => Delivery and select the `Multi SMTP` tab
4. Enter your credentials
5. Send a testmail

## Changelog

### 1.3.1

-   do not use `create_function` as they are no longer supported.

### 1.3

-   escape some unescaped strings

### 1.2

-   updated file structure
-   added tests

### 1.1

added: option to allow self signed certificates

### 1.0

-   initial release

## Additional Info

This Plugin requires [Mailster Newsletter Plugin for WordPress](https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=readme&utm_term=Multi+SMTP)
