# Mailster Google Analytics

Contributors: everpress, xaverb  
Tags: mailster, analytics, google, google analytics tracking  
Requires at least: 4.6  
Tested up to: 6.4  
Stable tag: 1.5.0  
License: GPLv2 or later  
Requires PHP: 7.2.5

## Description

Integrates Google Analytics with Mailster Newsletter Plugin to track your clicks with the popular Analytics service.
Requires the Mailster Newsletter plugin and the Google Analytics tracking code on the website.

> This Plugin requires [Mailster Newsletter Plugin for WordPress](https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=readme&utm_term=Google+Analytics)

## Installation

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer
2. Activate the plugin

## Frequently Asked Questions

### What does the plugin require to work correctly?

You need the [Mailster Newsletter Plugin for WordPress](https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=readme&utm_term=Google+Analytics)

## Changelog

### 1.5.0

- fixed: utm arguments are now urlencoded
- fixed: do not append if tracking is enabled (will be added on click)
- fixed: warnings on PHP 8.2

### 1.4.0

- added: support for GA4

### 1.3.1

- lowered priority of the filter so adding the utm\_\* args happend at the end.

### 1.3

- improved handling of adding utm\_\* query args
- now works if tracking is disabled.

### 1.2

- added: option to allow tracking external domain

### 1.1

- updated file structure

### 1.0

- initial release
