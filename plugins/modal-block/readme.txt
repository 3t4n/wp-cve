=== Modal Guten Block ===

Contributors: merbmerb
Plugin Name: Modal Block
Plugin URI: https://github.com/merbird/modal-block
Tags: gutenberg, modal, popup, window, block
Author: Mark Bird
Donate link: https://paypal.me/SupportBodDev
Requires at least: 5.0
Tested up to: 6.3.1
Requires PHP: 5.6
Stable tag: trunk
Version: 2.1
License: GPLv3

This plugin provides a Gutenberg Modal / Popup Block.

== Description ==

This project provides a modal / popup block for the  WordPress Gutenberg editor.

- Multiple methods for triggering modal including button, text link, image link, external class, and page load.
- User definable modal content using Gutenberg blocks, for example, image, paragraph etc. 
- Support for multiple modal window sizes.
- Modal window transitions for fade, left, right, up down.
- Manual trigger modal initialization by calling bodModal.initModal()
- Custom events for before open, after open, before close, after close so custom code can be used.
- Lots of customizable options including Trigger Button Label, Button Color, Trigger Text, Trigger Text Size, Trigger Image, Trigger Class, Modal Delay, Trigger Element Alignment, Overlay Background Color, Title Text Size, Title Font Color, Title Background Color, Title Padding, Border Radius.
- Supports multiple modals on the same page.
- ADA compliant.
- Modal to modal links.
- Multiple ways to close the modal. 
    - Escape key
    - Close button 'X' in the top right of the screen.
    - Close button 'X' in the modal title.
    - Close button at the bottom on the modal content.
- Uses create-guton-block for easy config.

== Installation ==

From your WordPress dashboard

1. **Visit** Plugins > Add New
2. **Search** for "Modal Block"
3. **Install** the "Modal Block" plugin
4. **Activate** "Modal Block" from your Plugins page
5. **Insert Block** on your Gutenberg Editor and select "Modal Block" which is located in Widgets.

== Frequently Asked Questions ==

== Screenshots ==

1. Sample modal window.
2. Modal Guten Block in editor. 

== Changelog ==
= 2.1 =
* Transition effects (fade, left, right, up, down) and 'Custom Events' for before modal open, after open, before close, after close
* Manual trigger modal initialization by calling bodModal.initModal()
= 2.0 =
* Modal background images. Separate options into Trigger, Modal, Title and Content panels. Title close X ability to define size. Trigger based on URL text.
= 1.5 =
* modal to modal links, text align title, title close button
= 1.4.3 =
* Option to disable close modal on Escape key press.
= 1.4.2 =
* Fixed issue where clicking on nested content in edit mode resulted in modal edit content box closing.  
* When modal opened focus on first element but do not scroll to it. Stops modal opening at bottom.
= 1.4.1 =
* Fix for when we trigger on image and image is smaller than medium size. In this case we default in the full size image.
= 1.4 =
* Make ADA compliant including using button instead of span, setting / returning focus and focus trapping.
* Add custom class to dialog when modal opens.
* Add toggle option to disable close on overlay click.
= 1.3 =
* Optional display only once for timer based modals. Modal Id links timer modals on different pages to say they are the same. Also ability to say how long before modal is shown again.
= 1.2 =
* Change icon to use SVG - use alt tag from trigger image - optional close btn in modal 
= 1.1 =
* Update for deprecated wp.editor (changed to wp.blockEditor) and core/editor (changed to core/block-editor).
= 1.0.0 (8/19/2019) =
* First release

== Donations ==

If you like the plugin, consider a donation to support further development. [Click here](https://paypal.me/SupportBodDev)