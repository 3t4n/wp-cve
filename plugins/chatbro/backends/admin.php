<?php

defined('ABSPATH') or die('No script kiddies please!');

require_once(__DIR__ . '/../common/admin/interfaces.php');

class CBroWPAdminBackend implements ICBroAdminBackend
{
  function has_permission_editor()
  {
    return true;
  }

  function has_shortcodes()
  {
    return true;
  }

  function get_login_url()
  {
    return wp_login_url(get_permalink());
  }

  function additional_fields($type)
  {
    switch ($type) {
      case 'settings':
        wp_nonce_field("chatbro_save_settings", "chb-sec");
        ?>
        <input name="action" type="hidden" value="chatbro_save_settings">
        <?php
        break;
      case 'create-chat':
        wp_nonce_field("chatbro_create_chat", "chb-sec-create-chat");
        ?>
        <input name="action" type="hidden" value="chatbro_create_chat">
        <?php
        break;
      case 'delete-chat':
        wp_nonce_field("chatbro_delete_chat", "chb-sec-delete-chat");
        ?>
        <input name="action" type="hidden" value="delete">
        <?php
        break;
      case 'update-chat':
        wp_nonce_field("chatbro_update_chat", "chb-sec-update-chat");
        ?>
        <input name="action" type="hidden" value="chatbro_update_chat">
        <?php
        break;
      case 'get-chats':
        wp_nonce_field("chatbro_get_chats", "chb-sec-get-chats");
        ?>
        <input name="action" type="hidden" value="chatbro_get_chats">
        <?php
        break;
    }
  }

  function check_token($type)
  {
    switch ($type) {
      case 'settings':
        return wp_verify_nonce($_POST['chb-sec'], "chatbro_save_settings") ? true : false;
      case 'create-chat':
        return wp_verify_nonce($_POST['chb-sec-create-chat'], "chatbro_create_chat") ? true : false;
      case 'update-chat':
        return wp_verify_nonce($_POST['chb-sec-update-chat'], "chatbro_update_chat") ? true : false;
      case 'delete-chat':
        return wp_verify_nonce($_POST['chb-sec-delete-chat'], "chatbro_delete_chat") ? true : false;
      case 'get-chats':
        return wp_verify_nonce($_POST['chb-sec-get-chats'], "chatbro_get_chats") ? true : false;
    }
  }


  function render_permissions()
  {
    ?>
    <div id="permissions-group" class="form-group">
      <label class="control-label col-sm-2">
        <?php _e("Permissions", "chatbro"); ?>
      </label>
      <div class="col-sm-10">
        <table id="chatbro-permissions" class="table table-active table-striped">
          <tr>
            <th>
              <?php _e("Role", "chatbro"); ?>
            </th>
            <th>
              <?php _e("View", "chatbro"); ?>
            </th>
            <th>
              <?php _e("Ban", "chatbro"); ?>
            </th>
            <th>
              <?php _e("Delete", "chatbro"); ?>
            </th>
          </tr>
          <?php
          foreach (get_editable_roles() as $name => $info) {
            $ctrlViewId = "chatbro_" . $name . "_view";
            $ctrlBanId = "chatbro_" . $name . "_ban";
            $ctrlDeleteId = "chatbro_" . $name . "_delete";

            $role = get_role($name);

            $chkView = $role->has_cap(CBroPermissions::cap_view) ? "checked" : "";
            $chkBan = $role->has_cap(CBroPermissions::cap_ban) ? "checked" : "";
            $chkDelete = $role->has_cap(CBroPermissions::cap_delete) ? "checked" : "";
            ?>
            <tr>
              <td>
                <?php echo $info["name"] ?>
              </td>
              <td><input id="<?php _e($ctrlViewId); ?>" name="<?php _e($ctrlViewId); ?>" type="checkbox" <?php echo $chkView; ?>></td>
              <td><input id="<?php _e($ctrlBanId); ?>" name="<?php _e($ctrlBanId); ?>" <?php echo $chkBan; ?> type="checkbox">
              </td>
              <td><input id="<?php _e($ctrlDeleteId); ?>" name="<?php _e($ctrlDeleteId); ?>" type="checkbox" <?php echo $chkDelete; ?>></td>
            </tr>
            <?php
          }
          ?>
        </table>
      </div>
    </div>
    <?php
  }

  function get_chat_help_tips()
  {
    ob_start();
    ?>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php _e('Shortcodes', 'chatbro'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php
          _e('Use shortcode <span>[chatbro]</span> to add the chat widget to the desired place of your page or post.', 'chatbro');
          ?>
        </p>
        <h5>
          <?php _e('Supported shortcode attributes:', 'chatbro'); ?>
        </h5>
        <ul>
          <li>
            <?php
            // Translators: Attribute name "static" and attribut value "true" shouldn't be translated
            _e('<em><b>id</b></em> &ndash; chat ID. For the main chat, this parameter should be omitted.', 'chatbro');
            ?>
          </li>
          <li>
            <?php
            // Translators: Attribute name "static" and attribut value "true" shouldn't be translated
            _e('<em><b>static</b></em> &ndash; static not movable chat widget (default <em>true</em>).', 'chatbro');
            ?>
          </li>
          <li>
            <?php
            // Translators: Attribute name "registered_only" and attribut value "false" shouldn't be translated
            _e('<em><b>registered_only</b></em> &ndash; display chat widget to logged in users only (default <em>false</em>). If this attribute is explicitly set it precedes the global <em>"Display chat to guests"</em> setting value.', 'chatbro');
            ?>
          </li>
        </ul>
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php _e('On fly creation', 'chatbro'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php _e('For example you want different chats for different pages of your site. In case you have video hosting site you can make different chat for each video. Generate these chats on the fly using the child chats feature.', 'chatbro'); ?>
          <?php _e('Child chat is a chat that uses the settings of another (parent) chat. This is done in order to automate the process of creating your chats.', 'chatbro'); ?>
        </p>

        <h5>
          How to use it?
        </h5>

        <p>
          <?php _e('Simply click on the "On fly creation" button, and then paste this shortcode in the right place. When the page loads, the chat will be created automatically.', 'chatbro'); ?>

        </p>

        <p>
          <?php _e('Child chats are inserted through a special shortcode:', 'chatbro'); ?>
          <br />
          <b>[chatbro id="_CHAT_ID_" child="true" title="_CHAT_TITLE_"]</b>
        </p>

        <ul>
          <li>
            <?php
            _e('<em><b>id</b></em> &ndash; ID of parent chat. The chat will copy the settings of this chat.', 'chatbro');
            ?>
          </li>
          <li>
            <?php
            _e('<em><b>child</b></em> &ndash; tells the system to create a child chat.', 'chatbro');
            ?>
          </li>
          <li>
            <?php
            _e('<em><b>title</b></em> &ndash; unique title that to be used as chat identifier. This means that this chat will be associated with this title. 2 shortcodes with the same title will load 2 identical chats.', 'chatbro');
            ?>
          </li>
          <li>
            <?php
            _e('<em><b>ext_id</b></em> &ndash; optional additional indentificator. If you want to have two or more different chats with equal titles add this parameter. Timestamp is perfect for this.', 'chatbro');
            ?>
          </li>
        </ul>

        <h5>
          <?php _e('What do child chats inherit?', 'chatbro'); ?>
        </h5>
        <ul>
          <li>-
            <?php _e('Visual settings;', 'chatbro'); ?>
          </li>
          <li>-
            <?php _e('Authorization settings;', 'chatbro'); ?>
          </li>
          <li>-
            <?php _e('Privacy settings;', 'chatbro'); ?>
          </li>
          <li>-
            <?php _e('Message filter function;', 'chatbro'); ?>
          </li>
          <li>-
            <?php _e('Tariff, balance.', 'chatbro'); ?>
          </li>
        </ul>
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php _e('Synchronization with messengers', 'chatbro'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php _e('Chatbro allows you to synchronize messages between the website and popular messengers like Telegram or VK.', 'chatbro'); ?>
        </p>
        <h5>
          <?php _e('How to sync up Telegram group/supergroup?', 'chatbro'); ?>
        </h5>
        <p>
          <?php _e("Add @ChatbroBot to telegram group or supergroup. The bot will send a link in a private message where you can link the web chat and the telegram group/supergroup. If you don't have contact with our bot until this moment, it will not be able to send you a private message and will send a link to the group/supergroup.", 'chatbro'); ?>
        </p>
        <h5>
          <?php _e('How to sync up the Telegram channel?', 'chatbro'); ?>
        </h5>
        <p>
          <?php _e('Add @ChatbroBot to the telegram channel as an administrator and type "/sync" in the channel. The bot will send a sync link to this channel.', 'chatbro'); ?>
        </p>
        <h5>
          <?php _e('How to sync VK conversation with the web chat?', 'chatbro'); ?>
        </h5>
        <p>
          <a href="https://vk.com/@chatbro-sinhronizaciya-s-vk" target='_blank'>
            <?php _e('Detail instruction', 'chatbro'); ?>
          </a>
          <?php _e('how to add bot and sync it.', 'chatbro'); ?>
        </p>
        <h5>
          <?php _e('Can I add my bot to synchronize messages?', 'chatbro'); ?>
        </h5>
        <p>
          <?php _e('Yes you can. It will work just like the our one and you will can to control over it. To add a bot, follow these steps:', 'chatbro'); ?>
        <ul>
          <li>
            <?php _e('1. Go to ChatBro profile tab;', 'chatbro'); ?>
          </li>
          <li>
            <?php _e('2. Click to "Bots" tab;', 'chatbro'); ?>
          </li>
          <li>
            <?php _e('3. Click the "Add your bot" button, select the bot type and enter the authorization data. Then click the "Add" button. If the data is correct, the bot will be added and automatically turned on.', 'chatbro'); ?>
          </li>
        </ul>
        </p>
        <br />
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php _e('Message filtering', 'chatbro'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php _e('You can set up flexible message filtering on our server side or use a send delay.', 'chatbro'); ?>
        </p>
        <h5>
          <?php _e('How to configure?', 'chatbro'); ?>
        </h5>
        <p>
          <?php _e('Go to the chat editor, then the "Restrictions" block, and click "Edit function". In the editor that opens, at the bottom right, there will be a general example button.', 'chatbro'); ?>
        </p>
        <br />
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php _e('Spoofing protection', 'chatbro'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php _e('Spoofing is the situation when a person or a program successfully masks under another way of falsifying data and receives illegal benefits. For example, in the chat one person can write under the guise of another. The spoofing protection excludes such situations. Chatbro allows you to make sure that the chat is not displayed on other sites besides yours and exclude sending messages from other resources.', 'chatbro'); ?>
        </p>
        <h5>
          <?php _e('How to configure?', 'chatbro'); ?>
        </h5>
        <p>
          <?php _e('This feature is enabled and configured in the plugin by default.', 'chatbro'); ?>
        </p>
        <br />
      </div>
    </div>
    <?php

    $text = ob_get_contents();
    ob_clean();

    return $text;
  }

  function get_settings_help_tips()
  {
    ob_start();
    ?>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php _e('Account ID', 'chatbro'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php
          _e('Your current profile and chats are identified by your account ID. Please keep it in a safe place. Subsequently, when deleting the plugin or when switching to another CMS, you can easily restore all your chats.', 'chatbro');
          ?>
        </p>
      </div>
    </div>
    <?php

    $text = ob_get_contents();
    ob_clean();

    return $text;
  }

  function generated_scripts()
  {
    ?>
    <script>
      var cBroGlobals = {
        saveSettingsUrl: ajaxurl,
        createChatUrl: ajaxurl,
        deleteChatUrl: ajaxurl,
        updateChatUrl: ajaxurl,
        getChatsUrl: ajaxurl,
        getFaqUrl: ajaxurl,
      }
    </script>
    <?php
  }

  function save_permissions()
  {
    global $_POST;

    foreach (get_editable_roles() as $name => $info) {
      $viewCap = $_POST['chatbro_' . $name . '_view'] == 'on' ? true : false;
      $banCap = $_POST['chatbro_' . $name . '_ban'] == 'on' ? true : false;
      $deleteCap = $_POST['chatbro_' . $name . '_delete'] == 'on' ? true : false;

      $role = get_role($name);

      if ($viewCap)
        $role->add_cap(CBroPermissions::cap_view);
      else
        $role->remove_cap(CBroPermissions::cap_view);

      if ($banCap)
        $role->add_cap(CBroPermissions::cap_ban);
      else
        $role->remove_cap(CBroPermissions::cap_ban);

      if ($deleteCap)
        $role->add_cap(CBroPermissions::cap_delete);
      else
        $role->remove_cap(CBroPermissions::cap_delete);
    }
  }

  function create_chat()
  {
    return CBroApi::make_api_request("/chats/create/", false);
  }

  function get_chats()
  {
    return CBroApi::make_api_request("/chats/get-all/", false);
  }

  function delete_chat($chat_id, $force = false)
  {
    $data = array(
      'chatId' => $chat_id,
      'force' => $force
    );
    return CBroApi::make_api_request("/chats/delete/", $data);
  }
}

?>