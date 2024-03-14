# Mailster Gmail Integration

Contributors: everpress, xaverb  
Tags: gmail, google, mailster, deliverymethod, newsletter, mailsteresp, email  
Requires at least: 3.8  
Tested up to: 6.1  
Stable tag: 1.3.1  
License: GPLv2 or later  
PHP Version: 7.4+

## Description

> This Plugin requires [Mailster Newsletter Plugin for WordPress](https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=readme&utm_term=Gmail)

Uses Gmail to deliver emails for the [Mailster Newsletter Plugin for WordPress](https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=readme&utm_term=Gmail).

Read the [Setup Guide](https://kb.mailster.co/send-your-newsletters-via-gmail?utm_campaign=wporg&utm_source=Gmail&utm_medium=readme) to get started.

## Installation

1. Upload the entire `mailster-gmail` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings => Newsletter => Delivery and select the `Gmail` tab
4. Enter your API Key and save the settings
5. Send a testmail

## Screenshots

### 1. Option Interface.

![Option Interface.](https://ps.w.org/mailster-gmail/assets/screenshot-1.png)

## Changelog

### 1.3.1

-   fixed: composer packages with PHP 8.0+

### 1.3.0

-   fixed: issue with third party library on PHP 8.0+
-   added: `mailster_gmail_redirect_url` filter for redirect URL
-   requires now PHP 7.4+

### 1.2

-   Fixed some typo and updated method

### 1.1

-   Update for Mailster 3.0

### 1.0

-   initial release

## Additional Info

This Plugin requires [Mailster Newsletter Plugin for WordPress](https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=readme&utm_term=Gmail)
