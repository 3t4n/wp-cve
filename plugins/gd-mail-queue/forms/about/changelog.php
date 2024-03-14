<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="d4p-group d4p-group-changelog">
    <h3><?php esc_html_e("Version", "gd-mail-queue"); ?> 4</h3>
    <div class="d4p-group-inner">
        <h4>Version: 4.2.1 / august 9 2023</h4>
        <ul>
            <li><strong>edit</strong> improved the function for detecting HTML tags in content</li>
            <li><strong>fix</strong> duplicated merge of the KSES wide tags and attributes lists</li>
            <li><strong>fix</strong> rare issue when the plain text processing deals with NULL value</li>
            <li><strong>fix</strong> fixed regex for the conversion of text links</li>
        </ul>

        <h4>Version: 4.2 / july 16 2023</h4>
        <ul>
            <li><strong>new</strong> plugin icon for the dashboard, about and menu</li>
            <li><strong>edit</strong> changed order of processing for the HTMLfy of plain content</li>
            <li><strong>edit</strong> changed HTMLfy strip method to allow basic A and BR tags</li>
            <li><strong>edit</strong> preprocess plain text only if it contains HTML</li>
            <li><strong>fix</strong> stripped required tags when HTMLfy process is in strip mode</li>
            <li><strong>fix</strong> url in plain text email ends up with encoded entities</li>
        </ul>

        <h4>Version: 4.1 / june 26 2023</h4>
        <ul>
            <li><strong>new</strong> system requirements: plugin requires WordPress 5.5</li>
            <li><strong>new</strong> system requirements: PHPMailer class 6.1 or newer</li>
            <li><strong>new</strong> expanded list of email types detection for WordPress core</li>
            <li><strong>edit</strong> updates to the code to support only one PHPMailer class</li>
            <li><strong>remove</strong> dropping support for older versions of PHPMailer class</li>
            <li><strong>fix</strong> queue message logging error for long results messages</li>
            <li><strong>fix</strong> queue sometimes fails to mark message as failed</li>
        </ul>

        <h4>Version: 4.0 / june 9 2023</h4>
        <ul>
            <li><strong>new</strong> plugin tested with WordPress up to 6.2</li>
            <li><strong>new</strong> system requirements: plugin requires PHP 7.3</li>
            <li><strong>new</strong> system requirements: plugin requires WordPress 5.2</li>
            <li><strong>new</strong> option to control HTMLfy pre-processing of plain text</li>
            <li><strong>new</strong> run KSES filter when adding email into queue</li>
            <li><strong>new</strong> run KSES filter when saving email into the log</li>
            <li><strong>new</strong> logged email preview with proper data escaping</li>
            <li><strong>new</strong> filters to control pre-processing of emails going to queue</li>
            <li><strong>new</strong> process logged email preview with KSES before displaying</li>
            <li><strong>new</strong> filter for HTMLfy pre-processing control for KSES</li>
            <li><strong>edit</strong> many more additional content escaping for display</li>
            <li><strong>edit</strong> various small updates to improve PHP code standards</li>
            <li><strong>edit</strong> d4pLib 2.8.15</li>
            <li><strong>fix</strong> unauthenticated stored cross-site scripting vulnerability</li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-changelog">
    <h3><?php esc_html_e("Version", "gd-mail-queue"); ?> 3</h3>
    <div class="d4p-group-inner">
        <h4>Version: 3.9.3 / august 26 2022</h4>
        <ul>
            <li><strong>edit</strong> no longer check for super admin but use activate_plugins cap</li>
            <li><strong>fix</strong> issue with saving plugin settings in multisite environments</li>
        </ul>

        <h4>Version: 3.9.2 / may 17 2022</h4>
        <ul>
            <li><strong>new</strong> plugin tested with WordPress 6.0</li>
        </ul>

        <h4>Version: 3.9.1 / december 3 2021</h4>
        <ul>
            <li><strong>new</strong> plugin tested with WordPress up to 5.8</li>
            <li><strong>edit</strong> d4pLib 2.8.14</li>
            <li><strong>fix</strong> phpmailer error handling not catching some errors</li>
        </ul>

        <h4>Version: 3.9 / april 16 2021</h4>
        <ul>
            <li><strong>new</strong> system requirements: plugin requires WordPress 5.0</li>
            <li><strong>new</strong> queue processing settings: show current PHP timeout limit</li>
            <li><strong>edit</strong> main queue function improved to better handle from email and name</li>
            <li><strong>edit</strong> d4pLib 2.8.13</li>
            <li><strong>fix</strong> from email and name get overwritten by queue processing in some cases</li>
            <li><strong>fix</strong> admin side panels grid rows count not saving properly</li>
        </ul>

        <h4>Version: 3.8 / september 5 2020</h4>
        <ul>
            <li><strong>new</strong> added 7 more email types detection for WordPress core</li>
            <li><strong>new</strong> support for email types detection for Asgaros Forum plugin</li>
            <li><strong>new</strong> support for email types detection for Contact Form 7 plugin</li>
            <li><strong>new</strong> log entry popup shows Info tab with more important information</li>
            <li><strong>edit</strong> log now showing the email sending engine with status</li>
        </ul>

        <h4>Version: 3.7 / july 28 2020</h4>
        <ul>
            <li><strong>new</strong> support for WordPress 5.5 and new PHPMailer class</li>
            <li><strong>new</strong> use class alias to support new and old PHPMailer classes</li>
            <li><strong>edit</strong> various code quality improvements</li>
            <li><strong>edit</strong> removed some obsolete functions and code blocks</li>
            <li><strong>edit</strong> d4pLib 2.8.12</li>
            <li><strong>fix</strong> problem with function to normalize emails</li>
            <li><strong>fix</strong> cleanup functions not taking into account blog ID</li>
            <li><strong>fix</strong> few PHP strict mode warnings</li>
        </ul>

        <h4>Version: 3.6 / june 20 2020</h4>
        <ul>
            <li><strong>new</strong> dashboard widget to show latest email sending errors</li>
            <li><strong>new</strong> options to set sleep periods for batch and each email</li>
            <li><strong>new</strong> queue function now has support for 'from' field</li>
            <li><strong>new</strong> support for email types detection for Rank Math plugin</li>
            <li><strong>edit</strong> various improvements to queue test tool</li>
        </ul>

        <h4>Version: 3.5.1 / june 10 2020</h4>
        <ul>
            <li><strong>edit</strong> updated database schema due to the problem with column lengths</li>
            <li><strong>fix</strong> regression related to the cron job interval saving</li>
        </ul>

        <h4>Version: 3.5 / june 9 2020</h4>
        <ul>
            <li><strong>new</strong> phpmailer smtp services listed on same settings page</li>
            <li><strong>new</strong> support for email types detection for WP Members plugin</li>
            <li><strong>new</strong> bulk retry option in the email log for failed emails</li>
            <li><strong>new</strong> auto requeue locked emails not sent due to the server error</li>
            <li><strong>new</strong> reorganization of the plugin settings panels</li>
            <li><strong>new</strong> using SCSS file as a base for the CSS file</li>
            <li><strong>new</strong> reorganized CSS and JS files</li>
            <li><strong>edit</strong> improved queue box on the plugin dashboard with more information</li>
            <li><strong>edit</strong> improved htmlfy main method with additional arguments</li>
            <li><strong>edit</strong> improved bulk operation messages and counts displayed</li>
            <li><strong>edit</strong> various improvements to the JavaScript</li>
            <li><strong>edit</strong> retried emails have new retry status</li>
            <li><strong>edit</strong> d4pLib 2.8.10</li>
        </ul>

        <h4>Version: 3.4.2 / april 7 2020</h4>
        <ul>
            <li><strong>new</strong> tested with PHP 7.4</li>
            <li><strong>edit</strong> d4pLib 2.8.5</li>
            <li><strong>fix</strong> minor issue with with the PHP 7.4 deprecations</li>
        </ul>

        <h4>Version: 3.4.1 / november 2 2019</h4>
        <ul>
            <li><strong>fix</strong> email type detection related to the GD Topic Polls plugin</li>
        </ul>

        <h4>Version: 3.4 / september 28 2019</h4>
        <ul>
            <li><strong>new</strong> validate email object for missing attachments before queue processing</li>
            <li><strong>new</strong> color coded log rows for the failed and queued emails</li>
            <li><strong>new</strong> email log: action to retry sending emails that failed previously</li>
            <li><strong>edit</strong> various updates and expansions to the universal core email class</li>
            <li><strong>edit</strong> queue test is now sending proper from and from name values</li>
            <li><strong>edit</strong> various updates to the plugin readme file including more FAQ entries</li>
            <li><strong>edit</strong> improved queue error detection that happens before the sending attempt</li>
            <li><strong>edit</strong> few small updates to the emails log processing</li>
            <li><strong>edit</strong> d4pLib 2.7.8</li>
            <li><strong>fix</strong> adding to log can set wrong status for emails sent through queue</li>
            <li><strong>fix</strong> in some cases reply_to value doesn't get stored in the queue</li>
            <li><strong>fix</strong> some minor problems with logging the direct emails</li>
            <li><strong>fix</strong> add to log database method doesn't log message value</li>
        </ul>

        <h4>Version: 3.3 / july 22 2019</h4>
        <ul>
            <li><strong>new</strong> improved detection of the plain text email content</li>
            <li><strong>new</strong> option to control detection of the plain text email content</li>
            <li><strong>new</strong> option to fix the plugin content type when using HTML</li>
            <li><strong>new</strong> various additional new actions and filters for more control</li>
            <li><strong>new</strong> buddypress: force use of the wp_mail to send plain text emails only</li>
            <li><strong>edit</strong> updated plugin icon for the WordPress menus</li>
            <li><strong>edit</strong> remove some unused PHPMailer parameters from mirroring</li>
            <li><strong>edit</strong> d4pLib 2.7.5</li>
            <li><strong>fix</strong> saving failed message in log fails if message is too long</li>
        </ul>

        <h4>Version: 3.2 / june 26 2019</h4>
        <ul>
            <li><strong>new</strong> mail type detection: support for GD Topic Polls</li>
            <li><strong>new</strong> phpmailer updated to use core email class for email building</li>
            <li><strong>edit</strong> various updates to readme and extra plugin information</li>
            <li><strong>edit</strong> d4pLib 2.7.3</li>
        </ul>

        <h4>Version: 3.1 / june 18 2019</h4>
        <ul>
            <li><strong>new</strong> universal core email class for various operations</li>
            <li><strong>new</strong> set reply to email and name globaly in wp_mail</li>
            <li><strong>new</strong> htmlfy expanded with the website tagline tag</li>
            <li><strong>new</strong> htmlfy expanded with the website link tag</li>
            <li><strong>edit</strong> queue function: sets char set and content type if missing</li>
            <li><strong>edit</strong> queue test now sets char set to UTF-8</li>
            <li><strong>edit</strong> various minor tweaks and improvements</li>
            <li><strong>edit</strong> overall improved detection of the HTML emails</li>
            <li><strong>edit</strong> d4pLib 2.7.2</li>
            <li><strong>fix</strong> email log: HTML tag displayed for non-HTML emails</li>
            <li><strong>fix</strong> queue function: not setting the content type for the email</li>
            <li><strong>fix</strong> dashboard: incorrect status for the mailer intercept</li>
            <li><strong>fix</strong> from name global: invalid check for changing From Name</li>
        </ul>

        <h4>Version: 3.0.1 / june 15 2019</h4>
        <ul>
            <li><strong>edit</strong> fully updated about page for the version 3.0</li>
            <li><strong>edit</strong> various updates to the settings labels and information</li>
            <li><strong>fix</strong> missing core engines registration action point</li>
            <li><strong>fix</strong> missing PHPMailer services registration action point</li>
        </ul>

        <h4>Version: 3.0 / june 14 2019</h4>
        <ul>
            <li><strong>new</strong> option to pause email sending throug wp_mail</li>
            <li><strong>new</strong> plugin dashboard completly reorganized</li>
            <li><strong>new</strong> plugin dashboard: wp mail status box</li>
            <li><strong>new</strong> plugin dashboard: mail log status box</li>
            <li><strong>new</strong> database tables for emails, log and email/log relationship</li>
            <li><strong>new</strong> log emails send by wp_mail, queue or both</li>
            <li><strong>new</strong> emails log panel with overview of all logged emails</li>
            <li><strong>new</strong> emails log panel with option to delete from log</li>
            <li><strong>new</strong> emails log panel with popup dialog for email preview</li>
            <li><strong>new</strong> daily maintenance with support for log cleanup</li>
            <li><strong>new</strong> fake PHPMailer class now implements magic methods</li>
            <li><strong>new</strong> mirror PHPMailer class captures more information</li>
            <li><strong>new</strong> detect email type: support for WP error recovery mode email</li>
            <li><strong>new</strong> email preheader tag: choose the value to generate</li>
            <li><strong>new</strong> filter that can be used to pause wp_mail sending</li>
            <li><strong>new</strong> filter that can be used to control queue descision</li>
            <li><strong>new</strong> additional filters and actions for various things</li>
            <li><strong>edit</strong> additional information on the plugin dashboard</li>
            <li><strong>edit</strong> improved plugin settings organization</li>
            <li><strong>edit</strong> reset tool support for clearing the email log tables</li>
            <li><strong>edit</strong> d4pLib 2.7.1</li>
            <li><strong>fix</strong> email preheader tag set to wrong value</li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-changelog">
    <h3><?php esc_html_e("Version", "gd-mail-queue"); ?> 2</h3>
    <div class="d4p-group-inner">
        <h4>Version: 2.1.2 / may 30 2019</h4>
        <ul>
            <li><strong>fix</strong> wrong links for the update and install notifications in network mode</li>
            <li><strong>fix</strong> wrong admin menu action used when in the network mode</li>
        </ul>

        <h4>Version: 2.1.1 / may 26 2019</h4>
        <ul>
            <li><strong>fix</strong> wrong database table name for the queue cleanup process</li>
        </ul>

        <h4>Version: 2.1 / may 22 2019</h4>
        <ul>
            <li><strong>new</strong> option to use flexible limit when sending queued emails</li>
            <li><strong>new</strong> action run after each email has been sent through queue</li>
            <li><strong>new</strong> filter that can be used to pause the queue processing</li>
            <li><strong>new</strong> option on advanced settings panel to pause the queue processing</li>
            <li><strong>new</strong> export tool: select what to export: settings and/or statistics</li>
            <li><strong>edit</strong> export tool: improved import of settings from file as proper array</li>
            <li><strong>edit</strong> dashboard: improved display of the queue related information</li>
            <li><strong>edit</strong> improved the descriptions for various plugin settings</li>
            <li><strong>edit</strong> d4pLib 2.6.4</li>
            <li><strong>fix</strong> export tool: statistics data problem caused by the JSON import</li>
            <li><strong>fix</strong> export tool: wrong file name for the plugin settings export JSON file</li>
        </ul>

        <h4>Version: 2.0.1 / may 8 2019</h4>
        <ul>
            <li><strong>edit</strong> check if the template file exists before attempting to load</li>
            <li><strong>fix</strong> display of the last queue timestamp conversion error</li>
            <li><strong>fix</strong> default option for the HTML template was wrong</li>
        </ul>

        <h4>Version: 2.0 / may 6 2019</h4>
        <ul>
            <li><strong>new</strong> support for queue email send engines</li>
            <li><strong>new</strong> email send engine: phpmailer</li>
            <li><strong>new</strong> phpmailer support for using SMTP for sending</li>
            <li><strong>new</strong> set from email and name globaly in wp_mail</li>
            <li><strong>new</strong> additional information on the dashboard for queue</li>
            <li><strong>new</strong> tools to test email sending and adding to queue</li>
            <li><strong>new</strong> detect email type for emails sent by BuddyPress</li>
            <li><strong>new</strong> includes defuse encryption library</li>
            <li><strong>edit</strong> few changes in some of the filters and actions</li>
            <li><strong>edit</strong> better organization of the plugin settings panels</li>
            <li><strong>edit</strong> improvements to the function for adding to queue</li>
            <li><strong>edit</strong> various loading and initialization improvements</li>
            <li><strong>fix</strong> few issues when preparing email to send in queue</li>
            <li><strong>fix</strong> few problems with function for adding to queue</li>
            <li><strong>fix</strong> plugin settings export not working</li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-changelog">
    <h3><?php esc_html_e("Version", "gd-mail-queue"); ?> 1</h3>
    <div class="d4p-group-inner">
        <h4>Version: 1.0 / may 2 2019</h4>
        <ul>
            <li><strong>new</strong> first official version</li>
        </ul>
    </div>
</div>
