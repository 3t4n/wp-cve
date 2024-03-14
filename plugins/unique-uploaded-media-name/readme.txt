=== Unique Uploaded Media Name ===
Contributors: sharkar
Donate link: https://akhaura.info
Tags: unique, media, rename, unique media name, unique upload media name, upload, file, files, random, string, unique uploaded media name
Requires at least: 3.5
Tested up to: 5.2.1
Stable tag: 1.0.1
Requires PHP: 5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Unique uploaded media names by adding some extra random string

== Description ==

Unique uploaded media names by adding some extra random string.

By default, WordPress upload file name slugs at it are. Sometimes if any file name conflicts, WordPress adds extra suffix at the end of the slug name.

Example: `my-image.jpg` and if the file name is similar, `my-image-1.jpg` `my-image-2.jpg` and so on.

By installing this plugin, every uploaded media will have unique values as a suffix at the end of the uploaded media name. Default uploaded string value is 15 character and have zero possibility of file name confliction. `Uploaded file name + Unique String`

**Example**

* my-image-475445-SIu7oQGW.jpg
* my-image-311629-TFuNSyHH.jpg

**Features**

* Adds unique suffix at the end of the uploaded file name. e.g.: my-image-`311629-W3dRonmw`.jpg
* Generated strings are very unique and less possibility of confliction
* Remove Accents `Convert to ASCII`
* Crypto Rand Secure with `openssl_random_pseudo_bytes`
* Added more extra switch conditions to use other string generator. (`Numeric`, `Alpha Numeric`, `No Zero Numeric`, `All String`, `Hexadecimal`, `Capital`, `Alpha` and `Distinct`). Just edit line number `75` and `76`.
* converts unnecessary space, underscore and special characters to hyphen character.
* Consumes very low memory
* Single file `3.02KB`
* Open source license, modify and distribute with your own. `GPLv2 or later`
* Tested with `WordPress 5.2.1`

== Installation ==

1. Upload the entire `unique-uploaded-media-name` folder to the `/wp-content/plugins/` directory. (If the folder name changes, rename the folder name with your own.)
2. Activate the plugin through the ‘Plugins’ menu in WordPress.
3. That's it. future uploaded media will be generated with unique strings


== Frequently Asked Questions ==

Do you have questions or issues with Unique Uploaded Media Name? Just send an email to [sharkar@akhaura.info](mailto:sharkar@akhaura.info) . I will be happy to answer your queries.

== Screenshots ==

1. screenshot-1.png

== Changelog ==

= 1.0 =
* First commit

= 1.0.1 =
* Support WordPress 5.2.1

== Upgrade Notice ==

### 1.0.1 ###
Support WordPress 5.2.1 (Latest version of WordPress)