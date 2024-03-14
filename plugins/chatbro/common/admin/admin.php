<?php

require_once(__DIR__ . '/../core/backendable.php');
require_once(__DIR__ . '/../permissions/permissions.php');
require_once(__DIR__ . '/../utils/utils.php');
require_once(__DIR__ . '/../chats/chats.php');
require_once('exceptions.php');

class CBroAdmin extends CBroBackendable
{
  public static function display()
  {
    self::get_instance()->display_admin();
  }

  private function display_admin()
  {
    CBroUtils::enque_style('chatbro.css?1');
    CBroUtils::enque_script('chatbro.js?1');

    $guid = CBroSettings::get(CBroSettings::guid);

    ?>
    <div id="chatbro-admin">
      <?php
      self::get_backend()->generated_scripts();
      $this->render_tabs();
      ?>
      <div class="tab-content">
        <?php
        $this->render_my_chats();
        $this->render_constructor_tab();
        $this->render_prices_tab();
        $this->render_settings_tab($guid);
        $this->render_contact_us_tab();
        $this->render_translates();
        ?>
      </div>
    </div>
    <?php
  }

  private function render_translates()
  {
    ?>
    <span id="chatbro-translates" style="display:none;" data-dtall="<?php _e('Yes, delete them all', 'chatbro'); ?>"
      data-scd="<?php _e('Shortcode copied', 'chatbro'); ?>" data-chbd="<?php _e('Chat has been deleted', 'chatbro'); ?>"
      data-chbu="<?php _e('Chat has been updated', 'chatbro'); ?>"
      data-ausd="<?php _e('Are you sure you want to delete the chat?', 'chatbro'); ?>">

    </span>
    <?php
  }

  private function render_tabs()
  {
    ?>
    <ul id="chatbro-settings-tabs" class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active">
        <a id="chatbro-tab-chats" href="#chatbro-my-chats" aria-controls="chatbro-my-chats" role="tab" data-toggle="tab">
          <span class="glyphicon glyphicon-th-list"></span>
          <span class="tab-title hidden-xs">
            <?php _e("My chats", 'chatbro'); ?>
          </span>
        </a>
      </li>
      <li role="presentation">
        <a id="chatbro-tab-profile" href="#chatbro-constructor" aria-controls="chatbro-constructor" role="tab"
          data-toggle="tab">
          <span class="glyphicon glyphicon-wrench"></span>
          <span class="tab-title hidden-xs">
            <?php _e("ChatBro profile", 'chatbro'); ?>
          </span>
        </a>
      </li>
      <li role="presentation">
        <a id="chatbro-tab-prices" href="#chatbro-prices" aria-controls="chatbro-prices" role="tab" data-toggle="tab">
          <span class="glyphicon glyphicon-euro"></span>
          <span class="tab-title hidden-xs">
            <?php _e("Prices", 'chatbro'); ?>
          </span>
        </a>
      </li>
      <li role="presentation">
        <a id="chatbro-tab-settings" href="#chatbro-plugin-settings" aria-controls="chatbro-plugin-settings" role="tab"
          data-toggle="tab">
          <span class="glyphicon glyphicon-cog"></span>
          <span class="tab-title hidden-xs">
            <?php _e("Plugin Settings", 'chatbro'); ?>
          </span>
        </a>
      </li>
      <li role="presentation">
        <a id="chatbro-tab-contacts" href="#chatbro-contact-us" aria-controls="chatbro-contact-us" role="tab"
          data-toggle="tab">
          <span class="glyphicon glyphicon-question-sign"></span>
          <span class="tab-title hidden-xs">
            <?php _e("Help", "chatbro"); ?>
          </span>
        </a>
      </li>
    </ul>
    <hr>
    <?php
  }

  private function render_my_chats()
  {
    ?>
    <div role="tabpanel" class="tab-pane fade in active" id="chatbro-my-chats">
      <?php $this->render_chat_editor_modal(); ?>

      <div class="row">
        <div class="col-lg-8">
          <?php $this->render_chats_control(); ?>
          <hr>
          <h4>My chats</h4>
          <?php
          $this->render_chats();
          ?>
          <div id="chatbro-my-chats-error" style="display:none;" role="alert"></div>
        </div>
        <?php $this->get_chat_help_tips(); ?>
      </div>
    </div>
    <?php
  }

  private function render_chats_control()
  {
    ?>
    <form id="chatbroChatsControl" class="form-horizontal" data-toggle="validator" role="form">
      <?php self::get_backend()->additional_fields('create-chat'); ?>
      <div class="chats-control">
        <div class="form-group">
          <div class="col-xs-12">
            <button class="btn btn-default" id="newChat" type="button"
              data-saving-text="<i class='fa fa-circle-o-notch fa-spin'></i> Creating">
              <?php _e("Create a new chat", "chatbro"); ?>
            </button>
          </div>
        </div>
      </div>
    </form>
    <?php
  }

  private function render_constructor_tab()
  {
    try {
      $token = CBroApi::get_token()->get_value();
      $locale = CBroUtils::get_locale();
      ?>
      <div role="tabpanel" class="tab-pane fade in" id="chatbro-constructor">
        <div id="chatbro-need-to-reload" class="chatbro-block">
          <?php _e("Please refresh the page to access the profile.", "chatbro"); ?>
        </div>
        <iframe id="chatbro-constructor-iframe" name="chatbro-constructor-iframe"></iframe>
        <form id="chatbro-load-constructor" target="chatbro-constructor-iframe"
          action="https://www.chatbro.com/account/?cms=wordpress" method="POST">

          <input type="hidden" name="at" value="<?php echo $token; ?>">
          <input type="hidden" name="avatarUrl" value="<?php echo CBroUser::avatar_url(); ?>">
          <input type="hidden" name="userFullName" value="<?php echo CBroUser::display_name(); ?>">
          <input type="hidden" name="userProfileUrl" value="<?php echo CBroUser::profile_url(); ?>">
        </form>
        <script>
          jQuery("#chatbro-load-constructor").submit();
        </script>
      </div>
      <?php
    } catch (CBroApiError $ex) {
      ?>
      <div role="tabpanel" class="tab-pane fade in" id="chatbro-constructor">
        <?php _e("The service ChatBro is currently unavailable. Please try again later.", "chatbro"); ?>
      </div>
      <?php
    }
  }

  private function render_prices_tab()
  {
    try {
      $token = CBroApi::get_token()->get_value();
      $locale = CBroUtils::get_locale();
      ?>
      <div role="tabpanel" class="tab-pane fade in" id="chatbro-prices">
        <iframe id="chatbro-prices-iframe" name="chatbro-prices-iframe"></iframe>
        <form id="chatbro-load-prices" target="chatbro-prices-iframe"
          action="https://www.chatbro.com/prices/?cms=wordpress" method="POST">
        </form>
        <script>
          jQuery("#chatbro-load-prices").submit();
        </script>
      </div>
      <?php
    } catch (CBroApiError $ex) {
      ?>
      <div role="tabpanel" class="tab-pane fade in" id="chatbr-prices">
        <?php _e("The service ChatBro is currently unavailable. Please try again later.", "chatbro"); ?>
      </div>
      <?php
    }
  }

  private function render_settings_tab($guid)
  {
    ?>
    <div role="tabpanel" class="tab-pane fade in" id="chatbro-plugin-settings">
      <?php $this->render_guid_confirmation_modal(); ?>

      <div class="row">
        <div class="col-lg-8">
          <div id="chatbro-message" role="alert"></div>
          <?php
          $this->render_settings_form($guid);
          ?>
        </div>
        <?php $this->get_settings_help_tips(); ?>
      </div>
    </div>
    <?php
  }

  private function render_contact_us_tab()
  {
    ?>
    <div id="chatbro-contact-us" role="tabpanel" class="tab-pane fade in">
      <div class="row">
        <div id="chatbro-faq-panel" class="col-xs-12 col-md-4">
          <div class="chatbro-block">
            <h4>
              <?php _e("ChatBro plugin", "chatbro"); ?>
            </h4>

            <p>
              <?php _e("ChatBro is a web messenger that can be synchronized with Telegram and VK.", "chatbro"); ?>
            </p>

            <ul>
              <li>
                <a href="https://www.chatbro.com">
                  <?php _e("Our site", "chatbro"); ?>
                </a>
              </li>
              <li>
                <a href="https://www.chatbro.com/documentation">
                  <?php _e("Documentation", "chatbro"); ?>
                </a>
              </li>
              <li>
                <a href="https://www.chatbro.com/faq">
                  <?php _e("FAQ", "chatbro"); ?>
                </a>
              </li>
            </ul>
          </div>
          <div class="chatbro-block">
            <h4>
              <?php _e("Contacts", "chatbro"); ?>
            </h4>
            <p>
              <?php _e("Contact us if you have any questions.", "chatbro"); ?>
            </p>
            <ul>
              <li>
                <a href="https://t.me/chatbrohelp">Telegram</a>
              </li>
              <li>
                <a href="mailto:kostiuchenko.kir@gmail.com">Email</span></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="chatbro-chat-panel col-xs-12 col-md-4">
          <div id="chatbro-news-chat"></div>
        </div>
        <div class="chatbro-chat-panel col-xs-12 col-md-4">
          <div id="chatbro-support-chat"></div>
        </div>
      </div>
      <script id="chatBroEmbedCode">
        /* Chatbro Widget Embed Code Start */
        function ChatbroLoader(chats, async) { async = !1 !== async; var params = { embedChatsParameters: chats instanceof Array ? chats : [chats], lang: navigator.language || navigator.userLanguage, needLoadCode: 'undefined' == typeof Chatbro, embedParamsVersion: localStorage.embedParamsVersion, chatbroScriptVersion: localStorage.chatbroScriptVersion }, xhr = new XMLHttpRequest; xhr.withCredentials = !0, xhr.onload = function () { eval(xhr.responseText) }, xhr.onerror = function () { console.error('Chatbro loading error') }, xhr.open('GET', 'https://www.chatbro.com/embed.js?' + btoa(unescape(encodeURIComponent(JSON.stringify(params)))), async), xhr.send() }
        /* Chatbro Widget Embed Code End */
        ChatbroLoader([{
          encodedChatId: '<?php echo self::get_support_chat_id(); ?>',
          chatWidth: '100%',
          chatHeight: '100%',
          isStatic: true,
          containerDivId: 'chatbro-support-chat'
        }, {
          encodedChatId: '<?php echo self::get_news_chat_id(); ?>',
          chatWidth: '100%',
          chatHeight: '100%',
          isStatic: true,
          containerDivId: 'chatbro-news-chat',
          allowSendMessages: false

        }]);
      </script>
    </div>
    <?php
  }

  static private function get_news_chat_id()
  {
    return '38QJt';
  }

  static private function get_support_chat_id()
  {
    $chats = array(
      'en' => '083y',
      'ru' => '47cs'
    );

    $locale = CBroUtils::get_locale();

    if (isset($chats[$locale]))
      return $chats[$locale];

    return $chats['en'];
  }

  private function render_chat_editor_modal()
  {
    ?>
    <div id="chb-chat-editor-modal-wrapper">
      <div class="modal fade" id="chb-chat-editor-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="chb-chat-editor-modal-container">
              <div id="chbChatEditorIframeLoader">
                <?php _e('Loading...', 'chatbro'); ?>
              </div>
              <iframe id="chbChatEditorIframe" class="modal-body" style="opacity: 0"></iframe>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  private function render_guid_confirmation_modal()
  {
    ?>
    <div id="chb-confirm-guid-modal-wrapper">
      <div class="modal fade" id="chb-confirm-guid-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">
                <?php _e("You are about to change the Account ID", "chatbro"); ?>
              </h4>
            </div>
            <p class="modal-body">
              <?php
              _e('Please be noticed that your current profile and chats are identified by your Account ID and if you lose it there
              will be no way to restore access to you current profile unless you have registered an account at
              <a href="https://www.chatbro.com">ChatBro.com</a>. Please make sure that you have saved your old Account ID and fully understand
              what are you going to do.', 'chatbro');
              ?>
            <p id="chb-old-key">
              <?php _e("Your old Account ID: <span></span>", "chatbro"); ?>
            </p>
            </p>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                <?php _e("Cancel", "chatbro"); ?>
              </button>
              <button type="button" class="btn btn-primary">
                <?php _e("Change Account ID", "chatbro"); ?>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  private function render_settings_form($guid)
  {
    ?>
    <form id="chatbro-settings-form" class="form-horizontal chatbro-block" data-toggle="validator" role="form">
      <?php self::get_backend()->additional_fields('settings'); ?>
      <input id="chb-login-url" name="chb-login-url" type="hidden"
        value="<?php echo self::get_backend()->get_login_url(); ?>">
      <input id="chb-sec-key" name="chb-sec-key" type="hidden" value="<?php echo $guid ?>">

      <?php
      foreach (CBroSettings::iterator() as $name => $setting) {
        $this->render_field($setting->get_params());
      }

      self::get_backend()->render_permissions();
      ?>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button id="chatbro-save" type="button" class="btn btn-primary"
            data-saving-text="<i class='fa fa-circle-o-notch fa-spin'></i> Saving Changes">
            <?php _e('Save Changes', 'chatbro'); ?>
          </button>
        </div>
      </div>
    </form>
    <?php
  }

  private function get_chat_help_tips()
  {
    $help_text = self::get_backend()->get_chat_help_tips();

    if (!$help_text)
      return;

    ?>
    <div id="chatbro-shortcode-tip" class="col-lg-4">
      <h4>
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        <span id="cbro-useful-tip">
          <?php _e("Useful Tips", "chatbro"); ?>
        </span>
      </h4>
      <?php echo $help_text; ?>
    </div>
    <?php
  }

  private function get_settings_help_tips()
  {
    $help_text = self::get_backend()->get_settings_help_tips();

    if (!$help_text)
      return;

    ?>
    <div id="chatbro-shortcode-tip" class="col-lg-4">
      <h4>
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        <span id="cbro-useful-tip">
          <?php _e("Useful Tips", "chatbro"); ?>
        </span>
      </h4>
      <?php echo $help_text; ?>
    </div>
    <?php
  }

  private function render_field($args)
  {
    if (array_key_exists('hidden', $args) && $args['hidden'] == true)
      return;

    $id = $args['id'];
    $type = $args['type'];
    $label = $args['label'];
    $active = $args['active'];

    if (!$active)
      return;

    ?>
    <div id="<?php echo $id; ?>-group" class="form-group">
      <?php
      if ($type == CBroInputType::checkbox)
        $this->render_checkbox($id, $label);
      else
        $this->render_other($id, $label, $args);
      ?>
    </div>
    <?php
  }

  private function render_checkbox($id, $label)
  {
    $checked = CBroSettings::get($id) ? 'checked="checked"' : '';
    ?>

    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input id="<?php echo $id; ?>" type="checkbox" name="<?php echo $id; ?>" <?php echo $checked; ?>>
          <?php _e($label, 'chatbro'); ?>
        </label>
      </div>
    </div>
    <?php
  }

  private function render_other($id, $label, $args)
  {
    ?>
    <label for="<?php echo $id; ?>" class="col-sm-2 control-label"><?php _e($label, 'chatbro'); ?></label>
    <div class="col-sm-10">
      <?php
      if (array_key_exists('addon', $args))
        $this->render_addon($id, $args);
      else
        $this->render_control($id, $args);

      ?>
      <div class="help-block with-errors"></div>
      <?php

      if (array_key_exists('help_block', $args)) {
        $help_block = $args['help_block'];
        ?>
        <div class="input-group">
          <span class="help-block">
            <?php _e($help_block, 'chatbro'); ?>
          </span>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
  }

  private function render_chats()
  {
    ?>
    <div class="chatbro-chats-block-wrapper">
      <?php self::get_backend()->additional_fields('get-chats'); ?>
      <?php self::get_backend()->additional_fields('update-chat'); ?>
      <?php self::get_backend()->additional_fields('delete-chat'); ?>
      <div id="chatbroChatsBlock">
        <?php _e('Loading...', 'chatbro'); ?>
      </div>
    </div>
    <?php
  }

  public static function render_chat_alert($alert)
  {
    $type = $alert['type'];
    $message = $alert['message'];

    switch ($type) {
      case 'success':
        ?>
        <div class="chatbro-alert alert alert-success" role="alert">
          <?php echo $message; ?>
        </div>
        <?php
        break;
      case 'danger':
        ?>
        <div class="chatbro-alert alert alert-danger" role="alert">
          <?php echo $message; ?>
        </div>
        <?php
        break;
      case 'info':
        ?>
        <div class="chatbro-alert alert alert-info" role="alert">
          <?php echo $message; ?>
        </div>
        <?php
        break;
      case 'infoDeclined':
        ?>
        <s>
          <div class="chatbro-alert alert alert-info" role="alert">
            <?php echo $message; ?>
          </div>
        </s>
        <?php
        break;
      case 'default':
        ?>
        <div class="chatbro-alert alert alert-default" role="alert">
          <?php echo $message; ?>
        </div>
        <?php
        break;
    }
  }

  private static function render_chat_connection($connection, $dem)
  {

    if ($dem === 'web') {
      ?>
      <div class="chatbro-alert alert alert-default" role="alert">
        <?php echo $connection . ' ' . $dem; ?>
      </div>
      <?php
    } elseif ($connection != 0) {
      ?>
      <div class="chatbro-alert alert alert-default" role="alert">
        <?php echo $connection . ' ' . $dem; ?>
      </div>
      <?php
    }
  }

  private static function render_chat_costs($cost, $dem)
  {
    ?>
    <div class="chatbro-alert alert alert-default" role="alert">
      <?php echo '$' . $cost . ' ' . $dem; ?>
    </div>
    <?php

  }


  public static function render_chat_block($chat)
  {
    ?>
    <div class="chatbro-admin-chat">
      <div class="chatbro-admin-chat-wrap">
        <div class="chatbro-admin-chat-header">
          <div class="chatbro-admin-chat-header-name">
            <?php echo $chat->get_title() ?>
          </div>
          <div class="chatbro-admin-chat-header-controls">
            <button class="btn btn-default chatbro-admin-chat-header-edit" data-id="<?php echo $chat->get_id() ?>">
              <?php echo _e('Edit', 'chatbro') ?>
            </button>
            <button class="btn btn-default chatbro-admin-chat-header-delete" data-id="<?php echo $chat->get_id() ?>">
              <?php echo _e('Delete', 'chatbro') ?>
            </button>
          </div>
        </div>
        <div class="chatbro-admin-chat-body">
          <?php
          if ($chat->get_alerts()) {
            ?>
            <div>
              <?php
              foreach (json_decode($chat->get_alerts(), true) as $alert) {
                self::render_chat_alert($alert);
              }
              ?>
            </div>
            <hr>
            <?php
          }

          if ($chat->get_connections()) {
            ?>
            <div>
              <?php echo _e('Online', 'chatbro'); ?>:
              <?php
              $connections = json_decode($chat->get_connections(), true);
              self::render_chat_connection($connections[0], 'web');
              self::render_chat_connection($connections[1], 'vk');
              self::render_chat_connection($connections[2], 'telegram');
              ?>
            </div>
            <?php
          }

          if ($chat->get_costs()) {
            ?>
            <div>
              <?php echo _e('Approx costs:', 'chatbro'); ?>:
              <?php
              $costs = json_decode($chat->get_costs(), true);
              self::render_chat_costs($costs[0], 'per day');
              self::render_chat_costs($costs[1], 'per week');
              self::render_chat_costs($costs[2], 'per month');
              ?>
            </div>
            <?php
          }

          if ($chat->get_costs() || $chat->get_connections()) {
            ?>
            <hr>
            <?php
          }

          if (CBroSettings::get(CBroSettings::enable_shortcodes)) {
            ?>
            <div class="chatbro-admin-chat-body-shortcode">
              <button class="btn btn-default chatbro-shortcode-copy" data-id="<?php echo $chat->get_id() ?>">
                <?php echo _e('Copy shortcode', 'chatbro') ?>
              </button>
              <button class="btn btn-default chatbro-shortcode-child-copy" data-id="<?php echo $chat->get_id() ?>">
                <?php echo _e('On fly creation', 'chatbro') ?>
              </button>
            </div>
            <hr>
            <?php
          }
          ?>
          <div>
            <div class="chatbro-chat-setting">
              <?php _e('Display chat to guests', 'chatbro'); ?>
              <?php
              $checked = $chat->get_display_to_guests() ? 'checked="checked"' : '';
              ?>
              <input id="chatbro_chat_display_to_guests-<?php echo $chat->get_id() ?>"
                class="chatbro_chat_display_to_guests" type="checkbox" name="" <?php echo $checked; ?>
                data-id="<?php echo $chat->get_id() ?>">
            </div>
            <div class="chatbro-chat-setting">
              <span>
                <?php _e("Show popup chat:", 'chatbro'); ?>
              </span>

              <div id="chatbro-display-setting-dropdown-<?php echo $chat->get_id() ?>"
                class="dropdown chatbro-display-setting-dropdown">
                <button id="chatbro-display-setting-<?php echo $chat->get_id() ?>" class="dropdown-toggle" type="button"
                  data-toggle="dropdown" data-id="<?php echo $chat->get_id() ?>" style="text-decoration: underline;"
                  aria-expanded="true">
                  <?php echo _e($chat->get_display_text(), 'chatbro'); ?>
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="chatbro-display-setting-<?php echo $chat->get_id() ?>">
                  <li>
                    <div class="chatbro-display-setting" data-cbdisplaysetting="everywhere">
                      <?php _e("Everywhere", 'chatbro'); ?>
                    </div>
                    <div class="chatbro-display-setting" data-cbdisplaysetting="frontpage_only">
                      <?php _e("Front page only", 'chatbro'); ?>
                    </div>
                    <div class="chatbro-display-setting" data-cbdisplaysetting="except_listed">
                      <?php _e("Everywhere except those listed", 'chatbro'); ?>
                    </div>
                    <div class="chatbro-display-setting" data-cbdisplaysetting="only_listed">
                      <?php _e("Only the listed pages", 'chatbro'); ?>
                    </div>
                    <div class="chatbro-display-setting" data-cbdisplaysetting="disable">
                      <?php _e("Disable", 'chatbro'); ?>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="chatbro-chat-setting" style="flex-direction: column; align-items: flex-end;">
              <?php
              $show = ($chat->get_display() === 'except_listed' || $chat->get_display() === 'only_listed') ? '' : 'style="display:none;"';
              ?>
              <textarea class="chatbro-chat-selected-pages" id="chatbro-csp-<?php echo $chat->get_id() ?>" name="" <?php echo $show; ?> class="form-control" data-id="<?php echo $chat->get_id() ?>"
                placeholder="<?php _e("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are /2012/10/my-post for a single post and /2012/* for a group of posts. The path should always start with a forward slash(/).", 'chatbro'); ?>"
                rows="6"><?php echo $chat->get_selected_pages() ?></textarea>

              <button id="chatbro-csp-save-<?php echo $chat->get_id() ?>"
                class="btn btn-default chatbro-chat-selected-pages-save" data-id="<?php echo $chat->get_id() ?>">
                <?php _e("Save", 'chatbro'); ?>
              </button>
            </div>
          </div>
          <?php
          if ($chat->get_created_at()) {
            ?>
            <hr>
            <div style="text-align: right;">
              <?php _e("Created at", 'chatbro'); ?>:
              <?php
              echo date('jS \of F Y', $chat->get_created_at());
              ?>
            </div>
            <?php
          }
          ?>

        </div>
      </div>
    </div>
    <?php
  }

  private function render_addon($id, $args)
  {
    $addon = $args['addon'];
    ?>
    <div class="input-group">
      <span class="input-group-addon">
        <?php echo $addon; ?>
      </span>
      <?php $this->render_control($id, $args); ?>
    </div>
    <?php
  }

  private function render_control($id, $args)
  {
    $value = CBroSettings::get($id);
    $required = (array_key_exists('required', $args) && $args['required']) ? "required " : "";

    switch ($args['type']) {
      case CBroInputType::text:
        $pattern = array_key_exists('pattern', $args) ? "pattern=\"{$args['pattern']}\" " : "";
        $pattern_error = (array_key_exists('pattern_error', $args) ? ('data-pattern-error="' . __($args['pattern_error'], 'chatbro') . '" ') : "");
        ?>
        <input id="<?php echo $id; ?>" name=" <?php echo $id; ?>" type="text" class="form-control" value="<?php echo $value; ?>"
          <?php echo "{$required}{$pattern}{$pattern_error}"; ?>>
        <span class=" field-icon form-control-feedback glyphicon" aria-hidden="true"></span>
        <?php
        break;

      case CBroInputType::textarea:
        ?>
        <textarea id="<?php echo $id; ?>" name=" <?php echo $id; ?>" class="form-control" cols="80" rows="6" <?php echo $required; ?>><?php echo $value; ?></textarea>
        <?php
        break;

      case CBroInputType::select:
        ?>
        <select id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="form-control" <?php echo $required; ?>>
          <?php
          foreach ($args['options'] as $val => $desc) {
            $desc = __($desc, 'chatbro');
            $selected = $val == $value ? 'selected="selected"' : '';
            echo "<option {$selected} name=\"$id\" value=\"{$val}\">{$desc}</option>";
          }
          ?>
        </select>
        <?php
        break;
    }
  }

  private static function check_access($type)
  {
    if (!self::get_backend()->check_token($type))
      throw new AccessException('{"success":false,"message":"' . __("Session timed out, please refresh the page.", "chatbro") . '","msg_type":"time_out"}');

    if (!CBroPermissions::can_manage_settings())
      throw new AccessException('{"success":false,"message":"' . __("You are not allowed to modify settings", "chatbro") . '","msg_type":"error"}');
  }

  private static function sync_chats()
  {
    try {
      $result = self::get_backend()->get_chats();
    } catch (CBroApiError $ex) {
      return json_encode(self::json_error($ex->getMessage()));
    }

    if (isset($result['success']) && $result['success'] == true && isset($result['chats'])) {
      $chats = json_decode($result['chats'], true);
      $need_to_update = false;
      $default_chat = CBroChats::get_default_chat();

      foreach ($chats as $item) {
        $title = $item['title'];
        $id = $item['id'];
        $guid = $item['guid'];
        $connections = $item['connections'];
        $costs = $item['costs'];
        $alerts = $item['alerts'];
        $created_at = $item['createdAt'];

        try {
          $ch = CBroChats::get($id);
        } catch (CBroChatNotFound $ex) {
          $ch = CBroChats::create_chat($id, $guid, false);
        }

        if (isset($default_chat) && $guid == $default_chat->get_guid() && CBroChats::$main_chat_id == 1) {
          $ch = $default_chat;
          CBroChats::$main_chat_id = $id;
          CBroChats::change_chat_id($ch, $id);
          $need_to_update = true;
        }

        if ($title !== $ch->get_title()) {
          $ch->set_title($title);
          $need_to_update = true;
        }

        if ($created_at !== $ch->get_created_at()) {
          $ch->set_created_at($created_at);
          $need_to_update = true;
        }

        // Save if something changed
        if ($need_to_update) {
          CBroChats::update($ch);
          CBroChats::save();
        }

        // There is no need to save it to the database
        $ch->set_connections($connections);
        $ch->set_costs($costs);
        $ch->set_alerts($alerts);
      }
    }
  }

  public static function get_chats()
  {
    try {
      self::check_access('get-chats');
      $errors = self::sync_chats();

      if (!is_null($errors)) {
        $errors = json_decode($errors, true);
        if (isset($errors['message'])) {
          ?>
          <div class="bs-callout-small bs-callout-small-danger">
            <?php
            echo $errors['message'];
            ?>
          </div>
          <?php
        }
      }
      ?>

      <div id="chatbroChatsBlockOther">
        <?php

        if (CBroChats::isEmpty()) {
          ?>
          <div class="bs-callout-small bs-callout-small-default">
            <?php
            echo __("You have no chats.");;
            ?>
          </div>
          <?php
        } else {

          foreach (CBroChats::iterator() as $c => $chat) {
            self::render_chat_block($chat);
          }
        }
        ?>
      </div>
      <?php

    } catch (AccessException $e) {
      return $e->getMessage();
    }
  }

  private static function json_error($msg)
  {
    $reply = array('success' => false);
    $reply['message'] = $msg;

    return $reply;
  }

  public static function create_chat()
  {
    try {
      self::check_access('create-chat');

      try {
        $result = self::get_backend()->create_chat();

      } catch (CBroApiError $ex) {
        return json_encode(self::json_error($ex->getMessage()));
      }

      if (
        isset($result['success'])
        && isset($result['id'])
        && isset($result['guid'])
      ) {
        $chat_id = $result['id'];
        $chat_guid = $result['guid'];

        CBroChats::create_chat($chat_id, $chat_guid);

        $reply = array('success' => true);
        $reply['id'] = $chat_id;

        return json_encode($reply);

      } else {
        return json_encode(self::json_error(__("Something went wrong. Try again later.")));
      }

    } catch (AccessException $ex) {
      return $ex->getMessage();
    }
  }

  public static function delete_chat()
  {
    try {
      self::check_access('delete-chat');

      $id = CBroUtils::get_request_var(CBroChat::id);
      $force = CBroUtils::get_request_var("force");

      if (is_null($id)) {
        return json_encode(self::json_error("Invalid chatId"));
      }

      try {
        $result = self::get_backend()->delete_chat($id, $force);
      } catch (CBroApiError $ex) {
        return json_encode(self::json_error($ex->getMessage()));
      }

      if (isset($result['success'])) {
        CBroChats::delete($id);
        CBroChats::save();
        $reply = array('success' => true);
        return json_encode($reply);
      }

      if (
        isset($result['error'])
        && isset($result['useforce'])
      ) {
        return json_encode($result);
      }


    } catch (AccessException $ex) {
      return $ex->getMessage();
    }
  }

  public static function update_chat()
  {
    try {
      self::check_access('update-chat');
      $id = CBroUtils::get_request_var(CBroChat::id);
      $display = CBroUtils::get_request_var(CBroChat::display);
      $display_to_guests = CBroUtils::get_request_var(CBroChat::display_to_guests);
      $selected_pages = CBroUtils::get_request_var(CBroChat::selected_pages);

      $chat = CBroChats::get($id);

      if ($display) {
        $chat->set_display($display);
      }

      if (!is_null($display_to_guests)) {
        $chat->set_display_to_guest($display_to_guests);
      }
      if ($selected_pages) {
        $chat->set_selected_pages($selected_pages);
      }

      CBroChats::update($chat);
      CBroChats::save();
      $reply = array('success' => true);
      return json_encode($reply);

    } catch (AccessException $ex) {
      return $ex->getMessage();
    }
  }

  public static function save_settings()
  {
    try {
      self::check_access('settings');

      $messages = array('fields' => array());
      $new_vals = array();
      $account_id_changed = false;

      foreach (CBroSettings::iterator() as $id => $setting) {
        $value = CBroUtils::get_request_var($id);
        $value = $value === null ? false : $value;

        try {
          $value = $setting->sanitize($value);
          // guid changed
          if ($setting->id() == CBroSettings::guid && $value !== CBroSettings::get(CBroSettings::guid)) {
            $account_id_changed = true;
          }
          if ($setting->id() == CBroSettings::secret && $value !== CBroSettings::get(CBroSettings::secret)) {
            $account_id_changed = true;
          }

        } catch (CBroSanitizeError $e) {
          if ($e->type() == CBroSanitizeError::Error)
            $messages['fields'][$id] = array('message' => $e->getMessage(), 'type' => $e->type());

          $messages['fatal'] = $e->getMessage();
          $value = $setting->get();
        }

        $new_vals[$id] = $value;
      }

      $reply = array('success' => true);

      if (array_key_exists('fatal', $messages)) {
        $reply['success'] = false;
        $reply['message'] = $messages['fatal'];
        $reply['msg_type'] = 'error';
      } else {
        foreach ($messages['fields'] as $m) {
          if ($m['type'] == 'error') {
            $reply['success'] = false;
            break;
          }
        }
      }

      if ($reply['success']) {
        foreach ($new_vals as $option => $value) {
          CBroSettings::set_sanitized($option, $value);
        }

        try {
          self::get_backend()->save_permissions();
        } catch (CBroPermissionsSaveError $e) {
          $reply['success'] = false;
          $reply['message'] = $e->getMessage();
          $reply['msg_type'] = 'error';

          return json_encode($reply);
        }

        $reply['message'] = "<strong>" . __("Settings was successfuly saved", "chatbro") . "</strong>";
        $reply['msg_type'] = "success";
      }

      if (count($messages['fields']))
        $reply['field_messages'] = $messages['fields'];

      // Drop if account_id has been changed
      if ($account_id_changed) {
        self::deleteChats();
        self::deleteApi();
      }

      return json_encode($reply);

    } catch (AccessException $ex) {
      return $ex->getMessage();
    }
  }

  public static function deleteChats()
  {
    CBroChats::deleteAll();

  }

  public static function deleteApi()
  {
    CBroApi::remove_tokens();
  }

  public static function has_shortcodes()
  {
    return self::get_backend()->has_shortcodes();
  }
}

?>