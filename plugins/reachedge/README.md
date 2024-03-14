# LOCALiQ WordPress 4 Tracking Plugin
A WordPress 4 plugin to install the [LOCALiQ](https://localiq.com) tracking code.

## Features

* Enables the [LOCALiQ](https://localiq.com) tracking functionality on WordPress sites.

## Installation

1. Download the [latest stable
   release](http://github.com/reachlocal/localiq-wordpress-4x-tracking-plugin/releases/latest).
2. In the WordPress dashboard, navigate to the *Plugins* page and click *Add New*, then click *Upload Plugin*.
3. Browse to the 'localiq-wordpress-4x-tracking-plugin-VERSION.zip' file, then select *Open* and click *Install Now*.
4. When the upload and installation completes successfully, click on *Activate Plugin*.

### Entering your tracking code ID

1. In the WordPress dashboard, navigate to the *Settings* menu.
3. Select the *LOCALiQ Tracking Code* option from the menu.
2. Enter your tracking code ID into the ID field, and click the *Save Changes* button.

## Prep a Release

To prep a release of this plugin you need to perform the following steps.

1. Update the `reachedge-tracking-plugin.php` file's `Version: X.Y.Z`
   declaration in the header comment to have the new version. We follow
   [semantic versioning](http://semver.org).
2. Update the `README.txt` with a proper entry in the *Changelog* section &
   the `Stable tag: X.Y.Z` entry with the new version.
3. Commit the above changes with a message saying "Bump version to vX.Y.Z"
   where vX.Y.Z is the new version.
4. Tag the "Bump version to vX.Y.Z" commit with the new version, ex: `git tag
   vX.Y.Z`
5. Push changes up including tags, ex: `git push && git push --tags`
6. Verify GitHub properly created your release for you on the [releases page](https://github.com/reachlocal/reachedge-wordpress-4x-tracking-plugin/releases).
7. Update that releases notes with the content you included in the *Changelog* section of the `README.txt` file by going to the [tags page](http://github.com/reachlocal/localiq-wordpress-4x-tracking-plugin/tags) and clicking 'Add release notes' and putting your release notes in the description.

## License

The ReachLocal Tracking Plugin is licensed under the MIT license.

> Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

> The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

A copy of the license is included in the Extension at `LICENSE.txt`.
