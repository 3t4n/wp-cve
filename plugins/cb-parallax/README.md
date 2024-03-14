

<p align="center">
  <a href="https://wordpress.org/plugins/cb-parallax/" target="_blank">
    <img src="./assets/icon-128x128.png" alt="cbParallax Logo" width="128" height="128">
  </a>
</p>

<h2 align="center">cbParallax</h2>
<p align="center">
  A WordPress plugin for a responsive and fullscreen background image with parallax effect.
  <br>
  <a href="https://wordpress.org/plugins/cb-parallax/" target="_blank"><strong>WordPress Plugin Repository Page</strong></a><br>
  <a href="https://downloads.wordpress.org/plugin/cb-parallax.zip" target="_blank"><strong>Download</strong></a>
  <br>
  <br>
  <a href="https://github.com/demispatti/cb-parallax/issues/new?template=bug.md">Report A Bug</a><br>
  <a href="https://github.com/demispatti/cb-parallax/issues/new?template=feature.md&labels=feature">Request Feature</a>
</p>

---
## Table Of Contents
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Documentation](#documentation)
- [Frequently Asked Questions](#documentation)
- [Plugin Support](#plugin-support)
- [Bugs And Feature Requests](#bugs-and-feature-requests)
- [Contributing](#contributing)
- [Versioning](#versioning)
- [Creator](#creator)
- [Copyright And License](#copyright-and-license)

---
## Features
- Custom background image
- One Image for all pages or individual images and effects on a per post basis
- Compatible with posts, pages, products, and many more
- Optional fullscreen background parallax effect
- Works vertically and, for fun, horizontally
- Various overlays to choose from

---
## Requirements
* Your theme must support the core WordPress implementation of the [Custom Backgrounds](https://codex.wordpress.org/Custom_Backgrounds) theme feature.  
* In order to use the parallax feature, I decided to set the minimum required image dimensions to 1920px * 1200px, which covers a fullHD screen with a slight vertical parallax movement ( Image height - viewport height, so 1200px - 1080px gives 120px offset to move the image. I hope you get the point here.).  
* You most likely need to edit some css in order to "uncover" the background image or parts of it respectively. Your theme's layout should be "boxed", or an opacity should be added to the page content container for the background image to be seen.  
* PHP version 5.6 or above.

---
## Installation
1. Upload the `cb-parallax` folder to your `/wp-content/plugins/` directory.
2. Activate the "cbParallax" plugin through the "Plugins" menu in WordPress.
3. Edit a post to add a custom background.

---
## Quick Start
Head over to the settings page and set your background image and the options you like.
Note that you may have to manually remove background color of elements that may cover the background image.

---
## Documentation
Once you've installed the plugin, you'll find help tabs inside the WordPress contextual help system.

---
## Frequently Asked Questions
#### Where do I interact with this plugin and how does it work?
Please visit the plugin help tab for further information.

#### Why doesn't it work with my theme?
Most likely, this is because your theme doesn't support the WordPress `custom-background` theme feature.
This plugin requires that your theme utilize this theme feature to work properly.
Unfortunately, there's just no reliable way for the plugin to overwrite the background if the theme doesn't support this feature.
You'll need to check with your theme author to see if they'll add support or switch to a different theme.

#### My theme supports 'custom-background' but it doesn't work!
That's unlikely.  
Just to make sure, check with your theme author and make sure that they support the WordPress `custom-background` theme feature.  
Also, make sure that no container element is covering the element that holds the background image.

#### How do I add support for this in a theme?
Your theme must support the [Custom Backgrounds] (https://codex.wordpress.org/Custom_Backgrounds) feature for this plugin to work.  
If you're a theme author, consider adding support for this feature if you can make it fit in with your design.  The following is the basic code, but check out the above link.  
	add_theme_support( 'custom-background' );

#### Are there any known limitations?
This is not really a limitation of functionality, but since the background image container wraps the body element, it usually resembles the viewport dimensions. This means, that on themes where the navigation bar is on the side, the sidebar covers a part of the viewport and thus also a part of the image (logic, but noteworthy).

#### Can you help me?
Yes. I have a look at the plugin's support page two or three times a month and I provide some basic support there.

#### Are there any known issues?
Besides the known limitations, no.

---
## Plugin Support
If you need support or have a question, I check the WordPress plugin support section on the [WordPress Plugin Repository](https://wordpress.org/support/plugin/cb-parallax/) once or twice a month.

---
## Bugs And Feature Requests
Have a bug or a feature request? Please first read the [issue guidelines](https://github.com/demispatti/cb-parallax/blob/master/.github/CONTRIBUTING.md#using-the-issue-tracker) and search for existing and closed issues. If your problem or idea is not addressed yet, [please open a new issue](https://github.com/demispatti/cb-parallax/issues/new).

---
## Contributing
Please read through our [contributing guidelines](https://github.com/demispatti/cb-parallax/blob/master/.github/CONTRIBUTING.md). Included are directions for opening issues, coding standards, and notes on development.

Moreover, if your pull request contains JavaScript patches or features, you must include [relevant unit tests](https://github.com/demispatti/cb-parallax/tree/master/js/tests). All HTML and CSS should conform to the [Code Guide](https://github.com/demispatti/code-guide), maintained by [Demis Patti](https://github.com/demispatti).

Editor preferences are available in the [editor config](https://github.com/demispatti/cb-parallax/blob/master/.editorconfig) for easy use in common text editors. Read more and download plugins at <https://editorconfig.org/>.

---
## Versioning
For transparency into our release cycle and in striving to maintain backward compatibility, Bootstrap is maintained under [the Semantic Versioning guidelines](https://semver.org/). Sometimes we screw up, but we adhere to those rules whenever possible.

See [the Releases section of our GitHub project](https://github.com/demispatti/cb-parallax/releases) for changelogs for each release version of Bootstrap. Release announcement posts on [the official Bootstrap blog](https://blog.getbootstrap.com/) contain summaries of the most noteworthy changes made in each release.

---
## Creator
**Demis Patti**
<https://github.com/demispatti>

---
## Copyright and license
Code and documentation copyright 2019 [Demis Patti](https://github.com/demispatti/cb-parallax/graphs/contributors). Code released under the [GPL V2 License](https://github.com/demispatti/cb-parallax/blob/master/LICENSE).