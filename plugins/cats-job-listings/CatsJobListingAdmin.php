<?php

class CATSJobListingAdmin
{
    private $options;

    private $version;

    private $pluginSlug;

    public function __construct()
    {
        $this->pluginSlug = 'cats-job-listings';

        $this->initHooks();
    }

    private function initHooks()
    {
        $plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_{$plugin}", array($this, 'pluginSettingsLink'));
        add_action('admin_menu', array($this,'buildAdminMenu'));
        add_action('admin_init', array($this,'initPluginPage'));
    }

    public function pluginSettingsLink($links)
    {
        $settings_link = '<a href="options-general.php?page=' . $this->pluginSlug . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function buildAdminMenu()
    {
        add_options_page(
            'CATS Job Listings',
            'CATS Job Listings',
            'manage_options',
            $this->pluginSlug,
            array($this, 'buildAdminPage')
        );
    }

    public function buildAdminPage()
    {
        $upgraded = false;
        if (isset($_GET['upgraded']) && $_GET['upgraded'] === 'true') {
            update_option('cats-version', '2');
            $upgraded = true;
        }

        $this->options = get_option('cats-options');
        $version = get_option('cats-version');
        $this->version = $version ? $version : '1';
        ?>
        <div class="wrap">
            <div class="update-nag">An account at <a target="_blank" href="https://catsone.com">catsone.com</a> is required to use this plug-in.</div>
            <?php if ($this->version === '1'): ?>
                <div class="notice-error notice"><p>You are using an old version of the Job Listings widget. The new version (v2) has addtional functionality including filters and title search. Upgrading is permanent and may also require you to update your styles for the job listings. <br><br> <a href="<?php menu_page_url($this->pluginSlug, true); ?>&upgraded=true" id="cats-upgrade-to-v2">Upgrade Now</a></p></div><br>
            <?php endif; ?>
            <?php if ($upgraded): ?>
                <div class="updated settings-error notice is-dismissible"><p>Successfully upgraded to v2.</p></div><br>
            <?php endif; ?>
            <form method="post" action="options.php">
                <?php
                settings_fields('cats-option-group-1');
                do_settings_sections($this->pluginSlug);
                submit_button();
                ?>
            </form>
        </div>
        <div class="wrap">
            <p>Once you have your settings entered, you can use the <b>[catsone]</b> shortcode on any page you would like your job listings to appear.</p>
            <p>For finer control, you may use parameters to override your default settings, e.g. <b>[catsone portal_id="555555" domain="catsone.com" subdomain="mysubdomain"]</b></p>
        </div>
        <?php
    }

    public function initPluginPage()
    {
        register_setting(
            'cats-option-group-1',
            'cats-options',
            array($this, 'sanitize')
        );

        add_settings_section(
            'cats-settings-section-1',
            'CATS Job Listings',
            function() {
                echo '<br>Enter your settings below:';
            },
            $this->pluginSlug
        );

        add_settings_field(
            'url',
            'Subdomain <i>(required)</i>',
            array($this, 'urlSettingCallback'),
            $this->pluginSlug,
            'cats-settings-section-1'
        );

        add_settings_field(
            'portal-id',
            'Portal ID',
            array($this, 'portalIdSettingCallback'),
            $this->pluginSlug,
            'cats-settings-section-1'
        );
    }

    public function sanitize($input)
    {
        $sanitizedInput = array();
        if (isset($input['url']['subdomain'])) {
            $sanitizedInput['url']['subdomain'] = sanitize_text_field($input['url']['subdomain']);
        }
        if (isset($input['portal-id'])) {
            $sanitizedInput['portal-id'] = sanitize_text_field($input['portal-id']);
        }
        if (isset($input['url']['domain'])) {
            $sanitizedInput['url']['domain'] = sanitize_text_field($input['url']['domain']);
        }

        return $sanitizedInput;
    }


    public function urlSettingCallback()
    {
        printf(
            '<input type="text" id="cats-subdomain-setting" name="cats-options[url][subdomain]" value="%s" required>
             <select name="cats-options[url][domain]" id="cats-domain-setting" style="vertical-align:initial;">
                <option value="catsone.com" %s>.catsone.com</option>
                <option value="catsone.nl" %s>.catsone.nl</option>
             </select>',
            isset( $this->options['url']["subdomain"] ) ? esc_attr( $this->options['url']['subdomain']) : '',
            $this->options["url"]["domain"] !== "catsone.nl" ? "selected" : "",
            $this->options["url"]["domain"] === "catsone.nl" ? "selected" : ""
        );
    }

    public function portalIdSettingCallback()
    {
        printf(
            '<input type="text" id="cats-portal-id-setting" name="cats-options[portal-id]" value="%s" ><br>
             <i>Leave blank to use your default portal.</i>',
            isset( $this->options['portal-id'] ) ? esc_attr( $this->options['portal-id']) : ''
        );
    }
}
