--------------------------------------------------------------------------------

yaCAPTCHA: Yet Another CAPTCHA plugin for WordPress based on KCAPTCHA

--------------------------------------------------------------------------------

Version 1.5
by R�my Roy (http://www.remyroy.com)

Plugin home page: http://www.remyroy.com/yacaptcha

--------------------------------------------------------------------------------

yaCAPTCHA is a CAPTCHA plugin for WordPress that helps you block comment spam
from automated bots. In order to post comments, users will have to write down
the characters that are part of an image. Since it is relatively hard for
automated programs to figure out those characters, this will help prevent
comment spam from those programs.

--------------------------------------------------------------------------------

* Requirements

    - WordPress 1.5 or higher.
    - PHP 4.0.6 or higher with GD2 library support.
    - Theme must support the 'comment_form' action.
    - PHP sessions enabled and properly configured.

In case you are wondering, most hosting providers should have those basic
requirements in place.

* Strengths vs other solutions

    - Easy to install
    - Does not require Javascript to work
    - Broad Wordpress version support

* How to install?

    To install the plugin, you must first copy the directory yacaptcha in your
    WordPress plugin directory which must be /wp-content/plugins/. If you are
    using Wordpress 2.7 or higher, you can simply use the plugin installer in
    your site admin section, search for yaCAPTCHA and click on install or
    upload the zip file.

    After the installation, you need to go in your site admin, in the Plugins
    section and activate the yaCAPTCHA plugin.

    It should works flawlessly with the default theme.

* Optional intallation steps

    For Wordpress version lower than 3.0, the default theme and most themes
    place the additionnal comment form items after the submit button. I
    suggest you change it so that it appears before the submit button. Have a
    look at the "How can I customize it?" section. This is not necessary if
    you are using version 3.0 or a higher version of Wordpress.

    The default Wordpress settings regarding comment appearance should be
    tweaked with this plugin. I suggest you uncheck these two options: "An
    administrator must always approve the comment" and "Comment author must
    have a previously approved comment" in the Discussion Settings, "Before a
    comment appears" area.

* Upgrade notice

    Upgrading from any version will make you lose your customizations.

* How can I customize it?

    For Wordpress version lower than 3.0, you can change the location of the
    CAPTCHA field within the comment form by changing the location of the
    'comment_form' call in your theme's comments.php file. The CAPTCHA field
    appears at the same location as the 'comment_form' call. The default
    location for the 'comment_form' call can make it confusing for some people
    because the CAPTCHA will appear after the submit button. I suggest you
    change it so that it appears before the submit button. This is not
    necessary if you are using version 3.0 or a higher version of Wordpress.

    You can customize the messages that are shown by changing the content of
    $yaCaptchaCharInputMsg and $yaCaptchaCharNoMatchMsg in the yacaptcha.php
    file. The default messages should be good enough for most English blogs.

    You can customize the HTML code that is used in the comment section to match
    your theme preferences by changing the code in the yaCaptchaCommentForm
    function in the yacaptcha.php file. The default HTML code match the default
    Wordpress theme.

    You can customize the image properties like how many characters are shown or
    which characters are used by changing values in the kcaptcha_config.php
    file. The default values should be good enough for most people.

* I cannot see the CAPTCHA when I am logged in

    The CAPTCHA is not shown and it is not validated for logged in users. It
    assumes that logged in users are already validated and they will not post
    spam.
    
* It does not work!

    First, make sure that you meet all the requirements. If you are still
    having problems, you can contact me.

--------------------------------------------------------------------------------

Version history

* 1.5 (July 11th, 2010)

    Updated to work with Wordpress 3.0 and the default theme. Plugin
    documentation update. Minor code refactoring.

* 1.4 (April 25th, 2010)

    Updated to work with Wordpress 2.9 . Minor plugin documentation update.

* 1.3.1 (October 19th, 2009)

    Tested to work with Wordpress 2.8.4 and the default theme. Even though it
    was not said to be tested with Wordpress 2.8.x before, version 1.3 should
    have been working correctly for those who tried it. Minor plugin
    documentation update.

* 1.3 (January 28th, 2009)

    Tested to work with Wordpress 1.5 and the default theme. Tested to work with
    Wordpress 2.7 and the default theme. Some code refactoring. Updated plugin
    documentation.

* 1.2.2 (January 19th, 2009)

    Changed files and directories structure to make it work with the new
    upgrading/installation process.

* 1.2.1 (January 19th, 2009)

    Changed files and directories structure to make it work with the new
    upgrading/installation process. Updated plugin documentation.

* 1.2 (January 17th, 2009)

    Tested to work with Wordpress 2.7 and the default theme. Removed the
    redirection after an invalid CAPTCHA (bug fix for 2.7). Updated plugin
    documentation.

* 1.1 (August 5th, 2008)

    Tested to work with Wordpress 2.6 and the default theme.

* 1.0 (May 11th, 2008)

    Updated the CAPTCHA generator, KCAPTCHA, to the latest version. Tested to
    work with Wordpress 2.5.1 and the default theme.

* 0.9 (February 11th, 2008)

    Make all file cases lower to prevent potential problems.  Remove CAPTCHA for
    logged users.

* 0.8 (February 9th, 2008)

    Initial version. Should work flawlessly with WordPress 2.3.2 and the default
    theme.

--------------------------------------------------------------------------------

Thanks

    Thanks to Kruglov Sergei for creating KCAPTCHA, his pretty good CAPTCHA. You
    can visit KCAPTCHA website at http://www.captcha.ru/en/kcaptcha/ .

--------------------------------------------------------------------------------

    Copyright 2008  R�my Roy  (email : remyroy@remyroy.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA