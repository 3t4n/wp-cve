<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.olark.com
 * @since      1.0.4
 *
 * @package    Olark_Wp
 * @subpackage Olark_Wp/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

	</br>
	<form method="post" name="olark_options" action="options.php">

  <?php
    //Grab all options
    $options = get_option($this->plugin_name);

    // Olark Site ID
    $olark_site_ID = $options['olark_site_ID'];
		$enable_olark = $options['enable_olark'];
		$enable_cartsaver = (isset($options['enable_cartsaver']) && !empty($options['enable_cartsaver'])) ? 1 : 0;
		$start_expanded = $options['start_expanded'];
		$detached_chat = $options['detached_chat'];
    $override_lang = $options['override_lang'];
		$olark_lang = $options['olark_lang'];
		$olark_api = $options['olark_api'];
		$olark_mobile = $options['olark_mobile'];

    ?>

    <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
    ?>

	  <div class="form-table">
	  <h2><?php esc_html_e('My Olark Site ID', $this->plugin_name); ?></h2>
	  <p>If you don't know your site ID, you can get it from the Olark website on the <a href="https://www.olark.com/install?rid=integration_plugin_wordpress">install page</a></p>
	  <fieldset>
	  <label for="<?php echo $this->plugin_name; ?>-olark_site_ID">
      <p><input type="text" id="<?php echo $this->plugin_name; ?>-olark_site_ID" name="<?php echo $this->plugin_name; ?>[olark_site_ID]" value="<?php if(!empty($olark_site_ID)) echo $olark_site_ID; ?>"/></p>
	  </label></fieldset>
	  <h4><input type="checkbox" id="<?php echo $this->plugin_name; ?>-enable_olark" name="<?php echo $this->plugin_name; ?>[enable_olark]" value="1" <?php checked($enable_olark, 1); ?>/><label for="<?php echo $this->plugin_name; ?>-enable_olark"><?php esc_attr_e('Enable Olark on my site', $this->plugin_name); ?></label></h4>

    <?php
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (class_exists('WooCommerce')): ?>
	  <h4><input type="checkbox" id="<?php echo $this->plugin_name; ?>-enable_cartsaver" name="<?php echo $this->plugin_name; ?>[enable_cartsaver]" value="1" <?php checked($enable_cartsaver, 1); ?>/><label for="<?php echo $this->plugin_name; ?>-enable_cartsaver"><?php esc_attr_e('Enable Olark Cart Saver', $this->plugin_name); ?><sup>BETA</sup></label></h4>
    <p>To customise the Cart Saver go to the <a href="https://www.olark.com/extensions/cartsaver?rid=integration_plugin_wordpress">Cart Saver settings on the Olark website</a>.</p>
    <?php endif; ?>

    <h2><strong>NOTE:</strong> Changing the below settings will override your settings within the Olark website.</h2>

	  <h4><input type="checkbox" id="<?php echo $this->plugin_name; ?>-olark_mobile" name="<?php echo $this->plugin_name; ?>[olark_mobile]" value="1" <?php checked($olark_mobile, 1); ?>/><label for="<?php echo $this->plugin_name; ?>-olark_mobile"><?php esc_attr_e('Show Olark on mobile devices', $this->plugin_name); ?></label></h4>

	  <h4><input type="checkbox" id="<?php echo $this->plugin_name; ?>-start_expanded" name="<?php echo $this->plugin_name; ?>[start_expanded]" value="1" <?php checked($start_expanded, 1); ?>/><label for="<?php echo $this->plugin_name; ?>-start_expanded"><?php esc_attr_e('Start with the chatbox expanded', $this->plugin_name); ?></label></h4>

	  <h4><input type="checkbox" id="<?php echo $this->plugin_name; ?>-detached_chat" name="<?php echo $this->plugin_name; ?>[detached_chat]" value="1" <?php checked($detached_chat, 1); ?>/><label for="<?php echo $this->plugin_name; ?>-detached_chat"><?php esc_attr_e('Detach the chatbox', $this->plugin_name); ?></label></h4>

  </br>


	  <h2><?php esc_html_e('Translate the chatbox', $this->plugin_name); ?></h2>

    <h4><input type="checkbox" id="<?php echo $this->plugin_name; ?>-override_lang" name="<?php echo $this->plugin_name; ?>[override_lang]" value="1" <?php checked($override_lang, 1); ?>/><label for="<?php echo $this->plugin_name; ?>-override_lang"><?php esc_attr_e('Override all language settings with the selection below', $this->plugin_name); ?></label></h4>

	  <fieldset>
	  <label for="<?php echo $this->plugin_name; ?>-olark_lang">
	  <select id="<?php echo $this->plugin_name; ?>-olark_lang" name="<?php echo $this->plugin_name; ?>[olark_lang]" value=" <?php selected($olark_lang);?>"/>
		<option value="en-US" <?php selected( $olark_lang, 'en-US' ); ?>>English</option>
		<option value="fr-FR" <?php selected( $olark_lang, 'fr-FR' ); ?>>French</option>
		<option value="es-ES" <?php selected( $olark_lang, 'es-ES' ); ?>>Spanish</option>
    <option value="it-IT" <?php selected( $olark_lang, 'it-IT' ); ?>>Italian</option>
    <option value="nl-NL" <?php selected( $olark_lang, 'nl-NL' ); ?>>Dutch</option>
    <option value="pt-BR" <?php selected( $olark_lang, 'pt-BR' ); ?>>Portuguese (Brazilian)</option>
    <option value="ru-RU" <?php selected( $olark_lang, 'ru-RU' ); ?>>Russian</option>
    <option value="sv-SE" <?php selected( $olark_lang, 'sv-SE' ); ?>>Swedish</option>
    <option value="tr-TR" <?php selected( $olark_lang, 'tr-TR' ); ?>>Turkish</option>
    <option value="zh-CN" <?php selected( $olark_lang, 'zh-CN' ); ?>>简体中文</option>
		</select>
		</label>
	  </fieldset>

	<p>To customise the text fully, go to <a href="https://www.olark.com/customize/behavior?rid=integration_plugin_wordpress">behaviour settings on the Olark website</a></p>

	</br>

	<h2><?php esc_html_e('Advanced API Settings', $this->plugin_name); ?></h2>
	<p><?php esc_html_e('If you wish to add Olark api calls, you may do so here. Do not include script tags. This is recommended for advanced users only.', $this->plugin_name); ?></p>
  <p>You can see our full list of available API calls <a href="https://www.olark.com/api?rid=integration_plugin_wordpress">here</a>.</p>
	<fieldset>
	<label for="<?php echo $this->plugin_name; ?>-olark_api">
    <p><textarea cols="80" rows="8" id="<?php echo $this->plugin_name; ?>-olark_api" name="<?php echo $this->plugin_name; ?>[olark_api]" placeholder="<?php esc_attr_e('Enter your api calls here. Do not use script tags.', $this->plugin_name); ?>"/><?php if(!empty($olark_api)) echo $olark_api; ?></textarea></p>
	</label></fieldset>

	<?php submit_button(); ?>
	</div>

  </form>
  <script>
    var langSelect = document.getElementById('olark-wp-olark_lang');
    var overrideBox = document.getElementById('olark-wp-override_lang');

    langSelect.disabled = !overrideBox.checked;

    var updateLangSelect = function(event){
      if(event.target.checked == true){
        langSelect.disabled = false;
      }
      else{
        langSelect.disabled = true;
      }
    }

    overrideBox.addEventListener("change", updateLangSelect);

  </script>

</div><!--wrap-->
