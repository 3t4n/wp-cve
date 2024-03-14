=== Modular Custom CSS ===
Contributors: celloexpressions
Tags: Custom CSS, Customizer, Custom Design, CSS
Requires at least: 4.0
Tested up to: 6.3
Stable tag: 2.1
Description: Manage and live-preview theme-specific and plugin-specific Custom CSS in the Customizer.
License: GPLv2

== Description ==
WordPress core provides custom CSS functionality in the customizer that's specific to the current theme; you can switch themes freely with each theme's additional CSS remaining in place. Sometimes, you need some CSS to apply regardless of the current theme persistently. This plugin adds a plugin CSS option for CSS that's global and persists across theme changes.

With the Customizer, your CSS is instantly live-previewed, offering the ability to see exactly how your site will look before you publish your changes. The plugin CSS option is stored as an `option`. Prior to WordPress 4.7 (which introduced additional CSS in core), theme-specific CSS was stored as a `theme_mod`, in 4.7 and newer this is migrated to the core CSS functionality (which is theme-specific).

== Frequently Asked Questions ==
= Where is the Custom CSS Stored? =
Theme CSS was stored as a `theme_mod` prior to WordPress 4.7, meaning it is a theme-specific option, part of the theme_mods_$theme option in the database. Each theme has its own `theme_mod` for the custom CSS, so if you switch to a new theme, the theme-specific custom CSS will be empty. When you switch back to a previously customized theme, the CSS that you added to it will still be there. In WordPress 4.7 and newer this plugin migrates to the core CSS functionality for theme-specific CSS, which is stored with a custom post type.

Plugin CSS is stored as a regular `option` in the database. It is used for every theme, so it's best used for things that are plugin-related or anything else you want to persist between different themes.

= Why is WordPress 4.0 required? =
The Customizer features many improvements in WordPress 4.0, including the textarea control type that this plugin uses. WordPress 4.9 is recommended, as it includes the new CSS editor with syntax highlighting and other features.

== Screenshots ==
1. Additional CSS Section in the Customizer in WordPress 4.9.

== Installation ==
1. Take the easy route and install through the WordPress plugin installer, OR,
1. Download the .zip file and upload the unzipped folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the Customizer (Appearance -> Customize) to add custom CSS to your site.

== Changelog ==
= 2.1 =
* Leverage the Code Editing control in WordPress 4.9 to add syntax highlighting, hinting, and more.
* Add more-descriptive text to the core theme CSS control, to distinguish it from the plugin CSS control.
* Add plugin textdomain to facilitate translations.

= 2.0 =
* Migrates theme CSS to the core custom CSS functionality in WordPress 4.7.
* Adjusts the plugin CSS option to use the `edit_css` capability introduced in WordPress 4.7. As a result, multisite networks will need to provide this capability to site administrators for them to be able to access CSS now.

= 1.0 =
* First publicly available version of the plugin.
* Requires WordPress 4.0+.

== Upgrade Notice ==
= 2.1 =
* Leverage WordPress 4.9 code editing improvements, including syntax highlighting and code hinting.

= 2.0 =
* Migrate to the core CSS functionality in WordPress 4.7. Multisite installations may require another plugin to matintain site admin access to CSS options.

= 1.0 =
* Initial Public Release