# LB Back To Top - WordPress #
**Contributors:** leobaiano  
**Donate link:** http://lbideias.com.br/donate  
**Tags:** back to top, WordPress
**Requires at least:** 3.8  
**Tested up to:** 3.8  
**Stable tag:** 1.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Including button that takes the user to the top of the page.

## Description ##

This plugin WordPress includes a button that appears when the user scrolls the scroll bar when you click the button the user is taken to the top of the site.

Visit the [Screenshots](http://wordpress.org/plugins/lb-back-to-top/screenshots/) tab to see an example of the plugin running.

### Credits ###

* JS Script [Jonathan Schnittger](http://www.developerdrive.com/)

## Installation ##

To install just follow the installation steps of most WordPress plugin's:

e.g.

1. Download the file lb-back-to-top.zip;
2. Unzip the file on your computer;
3. Upload folder lb-back-to-top, you just unzip to `/wp-content/plugins/` directory;
4. Activate the plugin through the 'Plugins' menu in WordPress;
1. Be happy.

Important: This plugin uses the wp_footer action, then it is necessary that it be called in your theme.

## Frequently Asked Questions ##

### I activated the plugin but the button does not appear when you scroll the scroll bar of my site. ###

The plugin uses the wp_footer action, make sure it is called in your theme. Usually the action is called the footer file of the theme with the wp_footer () code;

## Screenshots ##

###1. Button back to top in action###
![back to top #1](https://raw.github.com/leobaiano/lb-back-to-top/master/screenshot-1.png)


## Changelog ##

### 1.0 2013-01-02 ###

* Creation of the plugin, the initial version.

## Upgrade Notice ##

### 1.1 ###

The next version will include translation of the text that appears on the button and the ability to style with CSS, or replace the button with an image.