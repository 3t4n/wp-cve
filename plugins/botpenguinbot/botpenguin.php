<?php
/**
 * Plugin Name: Botpenguin
 * Plugin URI: https://botpenguin.com
 * Description: BotPenguin is an AI powered chatbot platform that enables you to quickly and easily build incredible chatbots to communicate and engage your customers on website, Facebook messenger and other platforms.
 * Version: 1.4
 * Author: BotPenguin
 * Author URI: https://botpenguin.com
 * License: GPL2
 */


add_action('wp_head', 'BOTPENGUIN_function');

function BOTPENGUIN_function()
{
  $textvar = get_option('test_plugin_variable', 'botpenguin bot script');
//  echo "<script id='Ym90cGVuZ3VpbkFwaQ' src='https://cdn.botpenguin.com/bot.js?apiKey=${textvar}' defer></script>";
  echo "<script id='BotPenguin-messenger-widget' src='https://cdn.botpenguin.com/botpenguin.js' defer>${textvar}</script>";
}

/**
 * Activate the plugin.
*/ 
register_activation_hook(__FILE__, 'BOTPENGUIN_activate');
add_action('admin_init', 'BOTPENGUIN_redirect');

function BOTPENGUIN_activate() {
    add_option('BOTPENGUIN_do_activation_redirect', true);
}

function BOTPENGUIN_redirect() {
    if (get_option('BOTPENGUIN_do_activation_redirect', false)) {
        delete_option('BOTPENGUIN_do_activation_redirect');
        wp_redirect('admin.php?page=botpenguinbot%2Fbotpenguin.php');
    }
}

add_action('admin_menu', 'BOTPENGUIN_admin_menu');

function BOTPENGUIN_admin_menu()
{
	 add_menu_page('BotPenguin', 'BotPenguin', 'manage_options', __FILE__, 'BOTPENGUIN_footer_text_admin_page','dashicons-format-chat');
  //add_management_page('BotPenguin', 'BotPenguin', 'manage_options', __FILE__, 'BOTPENGUIN_footer_text_admin_page');
}

function BOTPENGUIN_footer_text_admin_page()
{

  $textvar = get_option('test_plugin_variable', 'botpenguin bot script');
  if (isset($_POST['change-clicked'])) {
 
if (!isset($_POST['my_botpenguin_update_setting'])) die("<br><br>Hmm .. looks like you didn't send any credentials.. No CSRF for you! ");
if (!wp_verify_nonce($_POST['my_botpenguin_update_setting'],'botpenguin-update-setting')) die("<br><br>Hmm .. looks like you didn't send any credentials.. No CSRF for you! ");

    $footertext = esc_url_raw($_POST['footertext']);
    $footerval = explode('//',$footertext);
    update_option('test_plugin_variable', end($footerval));
    $textvar = get_option('test_plugin_variable', 'botpenguin bot script');
  }

  ?>
<div class="wrap">
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div class="postbox">
          <div class="inside">
          <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/logo.png'; ?>" alt="BotPenguin">
            <h1>
              BotPenguin - Settings
              </h1>
            <h3 class="cc-labels"><?php _e('Instructions: ', 'BotPenguin'); ?></h3>

            <p>1.
              <?php _e('If you do not have a BotPenguin account, please log on to <a href="https://app.botpenguin.com" target="_blank">BotPenguin</a>, and sign up', 'BotPenguin'); ?>
            </p>
			<p>2.
              <?php _e('Create your website chatbot, choosing a template or from scratch. Check out this video if you need help in building the chatflow.', 'BotPenguin'); ?>
            </p>
			<p>
			<a href="https://www.youtube.com/watch?v=r_djmDuX988" target="_blank">How to create an effective chatflow using BotPenguin</a>
			</p>
            <p>3.
              <?php _e('Once you are ready, you can click on install, then choose WordPress, and obtain the key snippet.', 'BotPenguin'); ?>
            </p>
			<p>4.
              <?php _e('Paste it below and you are done.', 'BotPenguin'); ?>
            </p>
			<p>
				<a class="add-new-h2" target="_blank" href="<?php echo esc_url("https://help.botpenguin.com/botpenguin-resource-center/how-botpenguin-works/install-your-website-chatbot/install-website-bot-on-wordpress"); ?>">
                <?php _e('Read Tutorial', 'BotPenguin'); ?>
              </a> <a class="add-new-h2" target="_blank" href="<?php echo esc_url("https://youtu.be/ZRtAz78LSOI"); ?>">
                <?php _e('Watch Tutorial', 'BotPenguin'); ?></a>
			</p>
            <h3 class="cc-labels" for="script"><?php _e('BotPenguin Key Snippet:', 'BotPenguin'); ?></h3>
            <form action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
              <input style="width:100%" type="text" class="regular-text" value="<?php echo esc_html($textvar); ?>" placeholder="Paste your BotPenguin Key here, it resembles e056149d872d8429c7hduofhuhu8e56fb193dcc6c933f5" name="footertext">
              <input name="change-clicked" type="hidden" value="1" />
              <input name="my_botpenguin_update_setting" type="hidden" value="<?php echo wp_create_nonce('botpenguin-update-setting'); ?>" />
              <br />
              <br />
              <input class="button button-primary" type="submit" value="<?php _e('Save settings', 'BotPenguin'); ?>" />
            </form>
          </div>
        </div>
      </div>
      <?php require_once('sidebar.php'); ?>
    </div>
  </div>
</div>

<?php
}
?>