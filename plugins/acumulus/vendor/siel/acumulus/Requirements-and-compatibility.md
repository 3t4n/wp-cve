Requirements for the Acumulus web shop extensions
=================================================

Note that this information will be updated regularly, but will be out-of-date
even faster. So the quick changelog here should give you an idea of the
recentness of this information.

Updates:

- System requirements: still valid for 7.6.0.
- Supported shops and versions: december 2022.
- Supported PHP versions: december 2022.

System Requirements
-------------------

- PHP:
    * Minimum version: 7.4, older versions WILL fail.
    * Recommended version: 8.0, soon - probably plugin version 8.2, this will
      become the minimally required version and older versions WILL fail.
    * Note: due to an error in plugin version 7.2, all PHP versions below 7.4
      will already fail in that plugin version (solved in plugin version 7.3.0)
    * Note: See the PHP versions table further below for which PHP version can
      be used for which shop.
- Database:
    * MySql: 5.6 or later.
    * MariaDb: 10.0 or later.

Supported shops and versions
----------------------------
The tags below are also used further on in the changelog to indicate to which
shop(s) a change applies (if no tag is mentioned, ALL may be assumed).

Notes:

- The supported versions column is tricky. We only use the latest (locally
  installed) version of a shop for testing. New features of our module may use
  features of the shop as were available when our new feature was developed.
  As it is often not clear when a new API feature became available, this may
  unknowingly lead to our module failing on older versions of the shop (before
  that now used feature was available), and thus to us not updating the column
  "Supported versions" below.

| Tag | Shop             | Version used for testing | Supported versions                  |
|-----|------------------|--------------------------|-------------------------------------|
| ALL | All web shops.   |                          |                                     |
| HS  | HikaShop (JOO)   | 4.7.1 (starter)          | >= 3.0.0                            |
| JOO | Joomla (+ HS)    | 4.3.4                    | >= 3.6                              |
| MA  | Magento          | 2.4.6 (community)        | >= 2.4, 2.3 might still work        |
| OC  | OpenCart         | 3.0.3.7                  | >= 3.0                              |
|     |                  | 4.x.y.z                  | Not yet tested/supported            |
| PS  | PrestaShop       | 1.7.8.7                  | >= 1.7.6                            |
| VM  | VirtueMart (JOO) | 3                        | >= 3.0                              |
|     |                  | 4.0.7                    | >= 4.0.5                            |
| JOO | Joomla (+ VM)    | 3.10                     | >= 3.6                              |
|     |                  | 4.3.4                    | >= 4.2.5                            |
| WC  | WooCommerce      | 8.4.0                    | >= 5.0 (3 and 4 may work)           |
|     | WordPress        | 6.4.2                    | >= 5.9 (earlier versions WILL fail) |

Supported PHP versions
----------------------
This is an overview of which PHP version can be used with the supported shops.
This overview is not updated with each release of this plugin so may be a bit outdated.

Notes:

- Latest, or almost latest, point releases are used of the listed PHP versions.
- PHP 8.0 and 8.1 are only tested with Joomla 4.
- WooCommerce is listed in combination with WordPress 6.4.x. Other plugins gave
  warnings in my test environment, but those were paid plugins that I cannot
  upgrade, and thus for which I have an outdated version.
- Other installed plugins/modules may not have been updated to the latest
  PHP version as supported by the shop or our plugin, or may, on the contrary,
  already require a newer version than the shop does. This table cannot keep
  track of that.

| Tag | Shop Version      | 7.4 | 8.0 | 8.1 | 8.2 | Remarks                               |
|-----|-------------------|-----|-----|-----|-----|---------------------------------------|
| JOO | 3.10.x            | ✅ | ❌ | ❌ | ❌ | Many warnings on PHP 8.1              |
| JOO | 4.2.8             | ❌ | ✅ | ✅ | ❓ | Will warn not to use PHP 7.4          |
| HS  | 4.7.1 (starter)   | ✅ | ✅ | ✅ | ❓ |                                       |
| MA  | 2.4.6 (community) | ❌ | ❌ | ✅ | ✅ | Contains PHP 8 constructs             |
| OC  | 3.0.3.7           | ✅ | ✅ | ❌ | ❌ | Many warnings on PHP 8.1              |
|     | 4.x.y.z           | ❌ | ✅ | ✅ | ❓ | Not yet supported                     |
| PS  | 1.7.8.7           | ✅ | ❌ | ❌ | ❌ | Fatal runtime errors on PHP 8         |
|     | 8.0.1             | ✅ | ✅ | ✅ | ❓ | Warnings get logged on PHP 8.1        |
| VM  | 3.x.y             | ✅ | ❌ | ❌ | ❌ |                                       |
|     | 4.2.0             | ✅ | ✅ | ✅ | ❓ | VM 4.2 still produces warnings on 8.1 |
| WC  | 8.4.0             | ✅ | ✅ | ❓ | ❌ | Many warnings from other plugins      |
