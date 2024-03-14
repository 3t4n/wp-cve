=== Dashboard Editor ===
Contributors: Aaron Harun
Donate link: http://anthologyoi.com/about/donate/
Tags: dashboard, admin panel, edit dashboard, dashboard, admin, widget, feeds, integration, edit
Requires at least: 2.0.0
Tested up to: 2.5 (Trunk)
Stable tag: 1.1

With this plugin you can remove sections of the dashboard, add new code, or even add Sidebar Widgets.

== Description ==
 This plugin allows you to add whatever you want to the Wordpress dashboard through PHP and HTML even **Sidebar Widgets**. You can also wipe the entire dashboard or individually remove some of the more irritating sections like the Dev news, Planet Wordpress and the getting started section.

In WordPress 2.5, the code is cleaner, the plugin is more responsive and you can add both "real" sidebar widgets, or add "fake" ones to match the dashboard.

== Installation ==
Basic Installation (If you are new to Wordpress you should probably use this.):

1. Download the zip file
1. Unzip and upload dashboard.php to your *wp-content/plugins/* folder.
1. Go to your dashboard. There will be a new sub-menu item.
1. Select any options you want and add any PHP or HTML code that you want to the textbox.


== Frequently Asked Questions ==

= How do I add Widgets? =
To add a sidebar widget to your dashboard (after selecting the option from the Dashboard management page) use the code:

`<?php dynamic_sidebar('admin');?>`

The Admin sidebar is modified and controlled the exact same way as any other sidebar.

= How do I display UserOnline stats from the WP-UserOnline plugin? =
If you use the WP-UserOnline plugin you can use:
`<?php echo useronline_page();?>`

=My new content doesn't show up nicely. It is pushed to the bottom. =
If you find that your new content doesn’t align nicely you can add:

     ` <div style="float:left; width:460px;">
      //
      //Add all other content here
      //
      </div>`

= Can everyone see the information I add?=
Yes, you can use User Roles ([See Wordpress Codex](http://codex.wordpress.org/Roles_and_Capabilities "Visit the wordpress codex page for user roles.")). Or just use:
`
<?php if(current_user_can('edit_users')){ ?>
// Content you don't want people to see here
<?php } ?>

`
= What else can I add? =
Any code that can be used in a Wordpress Theme can be used in the dashboard.

= I'm on Wordpress 2.0, but none of the settings work. =
Unfortunately, the only way to get the plugin to work on 2.0.x is to completely wipe the dashboard.

== Screenshots ==
1. A screenshot of an edited dashboard with widgets in 2.3.
2. My actual Dashboard in WP 2.5
