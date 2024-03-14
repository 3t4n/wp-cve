# iWorks Rate module #

It will display a welcome message upon plugin activation that offers the user a 5-day introduction email course for the plugin. After 7 days the module will display another message asking the user to rate the plugin on wordpress.org

# How to use it #

1. Insert this repository as **sub-module** into the existing project

2. Include the file `module.php` in your main plugin file.

3. Call the action `wdev-register-plugin` with the params mentioned below.

4. Done!


## Code Example (from Sierotki) ##

```
#!php

<?php
// Load the iWorks-Rate module.
include_once 'vendor/iworks/rate/rate.php';

// Register the current plugin.
do_action(
	'iworks-register-plugin',
    /* plugin ID    */ plugin_basename( __FILE__ ),
    /* Plugin Title */ __( 'iWorks PWA', 'iworks-pwa' ),
    /* Plugin slug  */ 'iworks-pwa'
);
// All done!
```

1. Always same, do not change
2. The plugin title.
3. The plugin slug.


Changelog
---------

##### 2.1.7 (2024-01-16)
* Typo in text domain has been fixed.

##### 2.1.6 (2023-12-18)
* Usage of the `wp_rand()` function has been improved.

##### 2.1.5 (2023-12-03)
* The `iworks_rate_plugin_data` filter has been added.
* Checking nonce for dashbord actions has been added.

##### 2.1.4 (2023-11-30)
* Data input sanitization has been added.
* The defnition of class propery hass been added to avoid deprecated message about creation of dynamic property in PHP 8.2.
* The function `rand()` has been replaced by the function `wp_rand()`.
* The `date()` function has been replced by the `gmdate()' function.

##### 2.1.3 (2023-10-13)
* Data input sanitization has been added.

##### 2.1.2 (2023-03-18)
* A problem with escaping empty strings has been resolved.

##### 2.1.1 (2022-09-01)
* Replced `FILTER_SANITIZE_STRING` by `FILTER_DEFAULT` to avoid PHP 8x warnings.
* Significant increase in sleep time for showing banners.
* Reduced size of `iworks-logo.svg` file.

##### 2.1.0 (2022-02-17)
* Added ability to show "OG — Better Share on Social Media" plugin install proposal.

##### 2.0.6 (2022-01-18)
* Added ability to change slug and title during `iworks-register-plugin`.

##### 2.0.5 (2021-12-20)
* Fixed settigns page url (depend on plugin slug).

##### 2.0.4 (2021-08-11)
* Fixed review url.

##### 2.0.3 (2021-06-29)
* Fixed urls.

##### 2.0.2 (2021-06-24)
* Added "Provide us a coffee" and "Settings" by default.


