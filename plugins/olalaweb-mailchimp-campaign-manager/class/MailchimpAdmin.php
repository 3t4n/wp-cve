<?php

if (!class_exists('MailchimpAdmin')):
  /**
   * MailChimp Campaigns API
   *
   * Make use of the great WordPress Settings API
   * @see https://codex.wordpress.org/Creating_Options_Pages#Example_.232
   *
   * @author Matthieu Scarset <m@matthieuscarset.com>
   * @see http://matthieuscarset.com/
   * @version 1.0.0
   */
  class MailchimpAdmin extends MailchimpCampaignsManager {

    /**
     * Start up
     */
    public function __construct() {
      parent::__construct();
      add_action('contextual_help', array($this, 'help_tab'), 10, 3);
      add_action('admin_menu', array($this, 'add_plugin_page'));
      add_action('admin_init', array($this, 'admin_init'));

      add_action('updated_option', function ($key, $old_value, $new_value) {
        // Do things on update.
        if ($key == 'mailchimp_campaigns_manager_settings') {
          $this->action_update_option($old_value, $new_value);
        }
      }, 10, 3);

      $this->is_pro    = mailchimp_campaigns_manager_is_pro();
      $this->is_free   = !$this->is_pro && (isset($this->settings['api_key']) && !empty($this->settings['api_key']));
      $this->post_type = isset($this->settings['cpt_name']) && !empty($this->settings['cpt_name']) ? $this->settings['cpt_name'] : MCC_DEFAULT_CPT;
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
      // Tab under Newsletter menu.
      if (!$this->is_pro) {
        add_submenu_page(
          'edit.php?post_type=' . $this->post_type,
          $this->is_pro ? 'Summary' : 'Import',
          $this->is_pro ? 'Summary' : 'Import',
          MCC_CUSTOM_CAPABILITY,
          'mailchimp_campaigns_manager-import',
          array($this, 'create_import_page')
        );
      }
      add_submenu_page(
        'edit.php?post_type=' . $this->post_type,
        'Settings',
        'Settings',
        MCC_CUSTOM_CAPABILITY,
        'mailchimp_campaigns_manager-admin',
        array($this, 'create_admin_page')
      );
    }

    /**
     * Options page callback
     */
    public function create_import_page() {
      $page_title = 'Mailchimp Campaigns';
      $page_title .= ($this->is_pro) ? ' (Premium)' : '';

      echo '<div class="wrap">';
      echo '<h1>' . mailchimp_campaigns_manager_logo('display: inline-block; vertical-align:middle; height: 40px; margin-right: 10px') . ' ' . $page_title . '</h1>';

      if (!$this->is_pro && !$this->is_free) {
        // Need to configure plugin.
        $setting_page_url = 'edit.php?post_type=' . $this->post_type . '&page=mailchimp_campaigns_manager-admin';
        echo '<div class="error notice">';
        echo '<p>';
        echo _e('Cannot use this functionality yet. Please configure this plugin first.', MCC_TEXT_DOMAIN) . '&nbsp;';
        echo '<a href="' . $setting_page_url . '">' . __('Go to settings', MCC_TEXT_DOMAIN) . '</a>';
        echo '</p>';
        echo '</div>';
        return;
      }

      if (!$this->is_pro) {
        // Get total items.
        $count  = 0;
        $result = wp_count_posts($this->post_type);
        foreach (get_object_vars($result) as $key => $value) {
          $count += (in_array($key, array('publish', 'future', 'draft', 'pending', 'private', 'trash')) ? $value : 0);
        }

        echo '<h2>Summary</h2>';

        // Display TOTAL in Mailchimp vs TOTAL in WordPress.
        echo '<div id="mailchimp_campaigns_manager_summary">';
        echo '<p><span id="campaign-count">' . $count . '</span> campaigns already imported from Mailchimp.</p>';
        echo '</div>';

        // Display PRO banner.
        echo mailchimp_campaigns_manager_banner();

        echo '<form method="post" action="">';
        submit_button('Import all', 'primary', 'mailchimp_campaigns_manager_import', FALSE);
        echo '&nbsp;';
        submit_button('Recalculate total', 'secondary', 'mailchimp_campaigns_manager_recalculate', FALSE);
        echo '</form>';

        // Placeholder for Ajax return.
        echo '<div id="mailchimp_campaigns_manager_placeholder"></div>';
      }

      echo '</div>';
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
      $page_title = 'Mailchimp Campaigns';
      $page_title .= ($this->is_pro) ? ' (Premium)' : '';

      echo '<div class="wrap">';
      echo '<h1>' . mailchimp_campaigns_manager_logo('display: inline-block; vertical-align:middle; height: 40px; margin-right: 10px') . ' ' . $page_title . '</h1>';
      echo '<form method="post" action="options.php">';

      // This prints out all hidden setting fields
      settings_fields('mailchimpcampaign_option_group');
      do_settings_sections('mailchimpcampaign-admin');

      // Display submit button.
      submit_button('Save settings', 'primary', 'submit-form', FALSE);

      echo '</form>';
      echo '</div>';
    }

    /**
     * Register and add settings
     */
    public function admin_init() {
      // Global admin settings.
      register_setting(
        'mailchimpcampaign_option_group',
        'mailchimp_campaigns_manager_settings',
        array($this, 'sanitize')
      );

      add_settings_section(
        'mailchimp_campaigns_manager_settings_pro',
        __('Premium settings', MCC_TEXT_DOMAIN),
        array($this, 'print_section_pro'),
        'mailchimpcampaign-disabled' // 'mailchimpcampaign-admin'
      );

      add_settings_section(
        'mailchimp_campaigns_manager_settings_status',
        '',
        array($this, 'print_section_status'),
        'mailchimpcampaign-admin'
      );
      add_settings_section(
        'mailchimp_campaigns_manager_settings_free',
        __('Free API settings', MCC_TEXT_DOMAIN),
        array($this, 'print_section_info'),
        'mailchimpcampaign-admin'
      );
      add_settings_section(
        'mailchimp_campaigns_manager_settings_section',
        __('Custom settings', MCC_TEXT_DOMAIN),
        array($this, 'print_section_info'),
        'mailchimpcampaign-admin'
      );

      add_settings_field(
        'api_key',
        'Mailchimp API key (free)',
        array($this, 'field_api_key_callback'),
        'mailchimpcampaign-admin',
        'mailchimp_campaigns_manager_settings_free',
        array('label_for' => 'mailchimp_campaigns_manager_api_key')
      );
      add_settings_field(
        'api_authname',
        'Mailchimp Username',
        array($this, 'field_api_authname_callback'),
        'mailchimpcampaign-admin',
        'mailchimp_campaigns_manager_settings_free',
        array('label_for' => 'mailchimp_campaigns_manager_api_authname')
      );

      add_settings_field(
        'cpt_name',
        __('Custom Post Type', MCC_TEXT_DOMAIN),
        array($this, 'field_cpt_name_callback'),
        'mailchimpcampaign-admin',
        'mailchimp_campaigns_manager_settings_section',
        array('label_for' => 'mailchimp_campaigns_manager_cpt_name')
      );

      // Lab
      add_settings_field(
        'show_preview',
        __('Show preview in admin', MCC_TEXT_DOMAIN),
        array($this, 'field_show_preview_callback'),
        'mailchimpcampaign-admin',
        'mailchimp_campaigns_manager_settings_section',
        array('label_for' => 'mailchimp_campaigns_manager_show_preview')
      );

      // Pro
      add_settings_field(
        'rest_user',
        'Synchronization user',
        array($this, 'field_rest_user_callback'),
        'mailchimpcampaign-admin',
        'mailchimp_campaigns_manager_settings_pro',
        array('label_for' => 'mailchimp_campaigns_manager_rest_user')
      );
      add_settings_field(
        'license_expiration',
        'License expiration date',
        array($this, 'field_license_expiration_callback'),
        'mailchimpcampaign-admin',
        'mailchimp_campaigns_manager_settings_pro',
        array('label_for' => 'mailchimp_campaigns_manager_license_expiration')
      );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input) {
      $new_input = array();

      // Saving REST User.
      $new_input['rest_user']          = isset($input['rest_user']) ? sanitize_text_field($input['rest_user']) : NULL;
      $new_input['license_expiration'] = isset($input['license_expiration']) ? sanitize_text_field($input['license_expiration']) : NULL;

      if (isset($input['cpt_name'])) {
        $new_input['cpt_name'] = sanitize_title(sanitize_text_field($input['cpt_name']));
        // @todo Notify distant app about new Custom Post Type.
      }

      // Saving API KEY
      if (isset($input['api_key'])) {
        $new_input['api_key'] = sanitize_text_field($input['api_key']);
        $new_input['api_key'] = $this->check_api_key($new_input['api_key']);
      }

      if (isset($input['api_authname'])) {
        $new_input['api_authname'] = sanitize_text_field($input['api_authname']);
      }

      // Import campaigns
      $api_key_current     = (isset($this->settings['api_key']) && !empty($this->settings['api_key'])) ? $this->settings['api_key'] : null;
      $api_key_has_changed = isset($new_input['api_key']) && ($new_input['api_key'] != $api_key_current) ? true : false;
      if (isset($input['import']) && !$api_key_has_changed) {
        if ($this->test()) {
          $this->import();
        }

      }

      if (isset($input['show_preview']) && $input['show_preview'] == '1') {
        $new_input['show_preview'] = true;
      }

      return $new_input;
    }

    /**
     * Check API KEY format
     */
    public function check_api_key($key) {
      if (strpos($key, '-') === false) {
        return;
      } else {
        return $key;
      }
    }

    /**
     * Print the Section text
     */
    public function print_section_pro() {
      print '<p>';
      if ($this->is_pro) {
        print '<span>' . __('Congrats! You are Mailchimp Manager Premium.', MCC_TEXT_DOMAIN) . '</span>';
      } else {
        print '<span>' . __('You are using Mailchimp Manager as a free user.', MCC_TEXT_DOMAIN) . '</span>' . '<br>';
        print '<span>' . __('Get faster and automatic updates from Mailchimp to WordPress with a Premium account.', MCC_TEXT_DOMAIN) . '</span>' . '<br>';
      }
      print '</p>';
    }

    /**
     * Print the Section text
     */
    public function print_section_status() {
      $premium_button_style = 'border: 1px solid #6b3590; background-color: #6b3590; color: white; text-shadow: none;';

      print '<div style="margin-bottom: 40px;">';

      // Open Ajax placeholder.
      print '<div id="mailchimp_campaigns_manager_placeholder">';

      if ($this->is_pro) {
        $app_url = mailchimp_campaigns_manager_get_app_url();
        print '<p class="description">';
        // print '<span class="button" style="margin-left: 5px; ' . $premium_button_style . '" href="' . $app_url . '">' . __('Premium', MCC_TEXT_DOMAIN) . '</span>';
        print '<button id="mailchimp_campaigns_manager_update_app" type="button" class="button button-primary">' . __('Update subscription', MCC_TEXT_DOMAIN) . '</button>';
        print '<button id="mailchimp_campaigns_manager_disconnect_app" type="button" class="button button-primary" style="margin-left: 5px;">' . __('Disconnect', MCC_TEXT_DOMAIN) . '</button>';
        print '</p>';
      } else {
        $app_url = mailchimp_campaigns_manager_get_app_url('/user/register');
        // printf('<a class="button button-mailchimp-premium" href="%s">Become a Premium</a>', $app_url);
        // print '<button id="mailchimp_campaigns_manager_connect_app" type="button" class="button button-primary" style="">' . __('Connect', MCC_TEXT_DOMAIN) . '</button>';
        print '</p>';
      }

      // Close Ajax placeholder.
      print '</div>';

      print '</div>';
    }
    /**
     * Print the Section text
     */
    public function print_section_info() {
      print __('Enter your Mailchimp settings below:', MCC_TEXT_DOMAIN);
    }

    /***************************************************
     * Form fields
     ***************************************************/

    /**
     * Pro fields
     */
    public function field_rest_user_callback() {
      $all_users      = get_users();
      $specific_users = array();
      $selected_user  = isset($this->settings['rest_user']) ? $this->settings['rest_user'] : NULL;
      foreach ($all_users as $user) {
        if ($user->has_cap(MCC_CUSTOM_CAPABILITY) && !$user->has_cap('manage_options')) {
          // No admin.
          // Only Mailchimp Manager user roles.
          $specific_users[$user->user_email] = $user->user_login;
        }
      }

      print '<select class="select" id="rest_user" name="mailchimp_campaigns_manager_settings[rest_user]">';
      print '<option value="">' . __('- Select a User -') . '</option>';
      foreach ($specific_users as $email => $username) {
        $selected = ($selected_user == $email) ? ' selected="selected"' : '';
        print '<option value="' . $email . '"' . $selected . '>' . $username . '</option>';
      }
      print '</select>';

      if (empty($specific_users)) {
        print '<p class="description">';
        print __('No users in the list?') . '<br>';
        print __('Create a new user <strong>with the "Mailchimp Manager" role</strong>.') . '<br>';
        printf('<a href="%s">Add a new "Mailchimp Manager" user</a>', admin_url('user-new.php'));
        print '</p>';
      }
    }
    public function field_license_expiration_callback() {
      printf(
        '<input class="code" type="hidden" id="license_expiration" name="mailchimp_campaigns_manager_settings[license_expiration]" value="%s" />',
        isset($this->settings['license_expiration']) ? esc_attr($this->settings['license_expiration']) : ''
      );
      if ($this->is_pro) {
        $settings = get_option('mailchimp_campaigns_manager_settings', []);
        print '<p class="description">' . __('Subscription expires on:', MCC_TEXT_DOMAIN) . ' <strong>' . date_i18n(get_option('date_format'), $settings['license_expiration']) . '</strong></p>';
      } else {
        print '<p class="description">';
        print '<span>' . __('You are using the free version of this plugin.') . '</span><br />';
        print '</p>';
      }
    }

    /**
     * Free fields
     */
    public function field_api_authname_callback() {
      printf(
        '<input class="code" type="text" id="api_authname" name="mailchimp_campaigns_manager_settings[api_authname]" value="%s" />',
        isset($this->settings['api_authname']) ? esc_attr($this->settings['api_authname']) : ''
      );
      print '<p class="description">' . __('Let Mailchimp knows who you are', MCC_TEXT_DOMAIN) . ' :)</p>';
    }
    public function field_api_key_callback() {
      printf(
        '<input class="code" type="text" id="api_key" name="mailchimp_campaigns_manager_settings[api_key]" value="%s" />',
        isset($this->settings['api_key']) ? esc_attr($this->settings['api_key']) : ''
      );
      print
      '<p class="description">' .
      __('Don\'t know how to get a MailChimp API key?', MCC_TEXT_DOMAIN) . '&nbsp;' .
      '<a target="_blank" href="https://admin.mailchimp.com/account/api/">' . __('Get your key', MCC_TEXT_DOMAIN) . '</a>' .
        '</p>';
    }
    /**
     * Other fields
     */
    public function field_cpt_name_callback() {
      $placeholder = __('Default: ' . MCC_DEFAULT_CPT, MCC_TEXT_DOMAIN);
      printf(
        '<input class="code" type="text" id="cpt_name" name="mailchimp_campaigns_manager_settings[cpt_name]" value="%s" placeholder="' . $placeholder . '" />',
        esc_attr($this->post_type)
      );
      print
      '<p class="description">' .
      __('Lowercase only with no special character nor space.', MCC_TEXT_DOMAIN) .
      '<br/>' .
      __('Refresh permalinks after change (<a href="options-permalink.php">Permalinks</a> > Click save).', MCC_TEXT_DOMAIN) .
        '</p>';
    }
    public function field_show_preview_callback() {
      $checked = (isset($this->settings['show_preview']) && $this->settings['show_preview'] === true) ? ' checked' : '';
      echo '<input type="checkbox" id="show-preview" name="mailchimp_campaigns_manager_settings[show_preview]" value="1"' . $checked . ' />' .
        ' Activate campaigns preview in admin screens (experimental)';
    }

    /*
     * Help tab for admin screens
     */
    public function help_tab($contextual_help, $screen_id, $screen) {
      if ($this->post_type == $screen->id || $screen_id == 'settings_page_mailchimp_campaigns_manager-admin') {
        $screen = get_current_screen();
        $screen->add_help_tab(array(
          'id'      => $screen->id,
          'title'   => __('Help'),
          'content' => __('You can import your mailchimp campaigns from the settings page (Settings > Mailchimp Campaign).', MCC_TEXT_DOMAIN),
        ));
      }
    }

    /**
     * Do stuff on option update
     *
     * @todo
     */
    public function action_update_option($old_value, $value) {
      $post_type_has_changed = (!isset($old_value['cpt_name']) || !isset($value['cpt_name'])) || ($old_value['cpt_name'] != $value['cpt_name']);
      $rest_user_has_changed = (!isset($old_value['rest_user']) || !isset($value['rest_user'])) || ($old_value['rest_user'] != $value['rest_user']);

      // Update cache if custom post type machine name has changed.
      if ($post_type_has_changed) {
        flush_rewrite_rules();
      }

      // Update pro information.
      if ($rest_user_has_changed && $this->is_pro) {
        mailchimp_campaigns_manager_update_app();
      }

    }

  }
endif;
