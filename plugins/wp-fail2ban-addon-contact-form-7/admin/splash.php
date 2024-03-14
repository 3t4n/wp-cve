<?php declare(strict_types=1);
/**
 * Admin - splash
 *
 * @package wp-fail2ban-addon-contact-form-7
 * @since   1.1.0
 */
namespace    com\wp_fail2ban\addons\ContactForm7;

use function org\lecklider\charles\wordpress\wp_fail2ban\{
    logo_box,
    readme
};

defined('ABSPATH') or exit;

/**
 * Splash
 *
 * @since  1.4.0    Update forum link
 * @since  1.3.0    Updated to match new style
 * @since  1.1.0
 *
 * @return void
 */
function splash()
{
    $utm = '?utm_source=about&utm_medium=about&utm_campaign='.WP_FAIL2BAN_ADDON_CF7_VER;

    $logo_box = [
        'title' => 'WP fail2ban add-on<br>for Contact Form 7',
        'logo'  => plugins_url('assets/icon.png', WP_FAIL2BAN_ADDON_CF7_FILE),
        'links' => [
            'Blog'      => "https://addons.wp-fail2ban.com/blog/{$utm}&addon=contact-form-7",
            'Guide'     => "https://life-with.wp-fail2ban.com/add-ons/contact-form-7/{$utm}",
            'Support'   => "https://forums.invis.net/c/wp-fail2ban-contact-form-7/support/{$utm}"
        ]
    ];
    ?>
<div class="wrap" id="wp-fail2ban">
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div class="meta-box-sortables ui-sortable">
          <div class="postbox">
            <h2>Version <?=WP_FAIL2BAN_ADDON_CF7_VER?></h2>
            <div class="inside">
              <section>
                <h3>Comments? Suggestions? Need Help?</h3>
                <p>There&rsquo;s a <a href="<?=$logo_box['links']['Support']?>" rel="noopener" target="_blank">forum for that</a>!</p>
                <p>Please note that <b>support</b> is <b>ONLY</b> available via the <b><a href="<?=$logo_box['links']['Support']?>" rel="noopener" target="_blank">forum</a></b>.</p>
                <h6>Thanks for using WP fail2ban!</h6>
              </section>
              <hr>
              <section>
    <?php

    if (function_exists('org\lecklider\charles\wordpress\wp_fail2ban\readme')) {
        readme(WP_FAIL2BAN_ADDON_CF7_VER_SHORT, WP_FAIL2BAN_ADDON_CF7_DIR.'/readme.txt');
    } else {
        echo '<h1>Please upgrade to the latest version of <em>WP fail2ban</em>.</h1>';
    }

    ?>
              </section>
            </div>
          </div>
        </div>
      </div>
      <div id="postbox-container-1" class="postbox-container">
        <div class="meta-box-sortables">
    <?php

    if (function_exists('org\lecklider\charles\wordpress\wp_fail2ban\logo_box')) {
        logo_box($logo_box);
    }

    ?>
        </div>
      </div>
    </div>
  </div>
</div>

    <?php
}

