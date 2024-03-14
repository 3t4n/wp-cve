<?php
/*
Plugin Name: janolaw AGB Hosting
Plugin URI: https://www.janolaw.de/internetrecht/agb/agb-hosting-service/
Description: This Plugin get hosted legal documents provided by janolaw AG for Web-Shops and Pages.
Version: 4.4.7
Author: Jan Giebels
Text Domain: janolaw-agb-hosting
Domain Path: /languages
Author URI: https://www.giebels.biz
License: GPL2
*/
?>
<?php
/*  Copyright 2023  Jan Giebels  (email : info@giebels.biz)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
$janolaw_version = '4.4.7';
load_plugin_textdomain('janolaw-agb-hosting', false, "/wp-content/plugins/janolaw-agb-hosting/lang/");
add_action('plugins_loaded', 'wan_load_textdomain');

function wan_load_textdomain() {
	load_plugin_textdomain( 'janolaw-agb-hosting', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

function janolaw_agb_menu() {
	add_options_page('janolaw AGB Hosting', 'janolaw AGB Hosting', 'administrator', basename(__FILE__), 'janolaw_plugin_options');
	add_action( 'admin_init', 'register_janolaw_settings' );
}

function register_janolaw_settings() {
	//register our settings
	register_setting( 'janolaw-settings-group', 'janolaw_user_id' );
	register_setting( 'janolaw-settings-group', 'janolaw_shop_id' );
	register_setting( 'janolaw-settings-group', 'janolaw_cache_path' );
	register_setting( 'janolaw-settings-group', 'janolaw_cache_clear' );
	register_setting( 'janolaw-settings-group', 'janolaw_language' );
	register_setting( 'janolaw-settings-group', 'janolaw_language_default' );
	register_setting( 'janolaw-settings-group', 'janolaw_agb_page' );
	register_setting( 'janolaw-settings-group', 'janolaw_imprint_page' );
	register_setting( 'janolaw-settings-group', 'janolaw_widerruf_page' );
	register_setting( 'janolaw-settings-group', 'janolaw_widerrufform_page' );
	register_setting( 'janolaw-settings-group', 'janolaw_privacy_page' );
	register_setting( 'janolaw-settings-group', 'janolaw_agb_page_id' );
	register_setting( 'janolaw-settings-group', 'janolaw_imprint_page_id' );
	register_setting( 'janolaw-settings-group', 'janolaw_widerruf_page_id' );
	register_setting( 'janolaw-settings-group', 'janolaw_widerrufform_page_id' );
	register_setting( 'janolaw-settings-group', 'janolaw_privacy_page_id' );
	register_setting( 'janolaw-settings-group', 'janolaw_version' );
	register_setting( 'janolaw-settings-group', 'janolaw_pdf_top' );
	register_setting( 'janolaw-settings-group', 'janolaw_pdf_bottom' );
	register_setting( 'janolaw-settings-group', 'janolaw_woomail_order_widerruf' );
	register_setting( 'janolaw-settings-group', 'janolaw_woomail_order_agb' );
	register_setting( 'janolaw-settings-group', 'janolaw_woomail_order_widerrufform' );
	register_setting( 'janolaw-settings-group', 'janolaw_woomail_order_datenschutz' );
	register_setting( 'janolaw-settings-group', 'janolaw_plugin_version' );
	global $janolaw_version;
	update_option( "janolaw_plugin_version", $janolaw_version );
}

function janolaw_server_check() {
	$base_url = 'https://www.janolaw.de/agb-service/shops/';
	$user_id = get_option('janolaw_user_id');
	$shop_id = get_option('janolaw_shop_id');

	$message = '';
	$headers = @get_headers($base_url.'/'.$user_id.'/'.$shop_id.'/');

	if (isset($headers[0]) && ($headers[0] == 'HTTP/1.1 404 Not Found')) {
		$message = "<div id='setting-error-settings_updated' class='error settings-error'>".__("janolaw server <u>not</u> avaiable","janolaw-agb-hosting")."</div>";
	} else {
		# check for version 1
		$headers = @get_headers($base_url.'/'.$user_id.'/'.$shop_id.'/legaldetails_include.html');
		if (isset($headers[0]) && ($headers[0] != 'HTTP/1.1 404 Not Found')) {
			update_option( "janolaw_version", 1 );
		}
		# check for version 2
		$headers = @get_headers($base_url.'/'.$user_id.'/'.$shop_id.'/de/legaldetails_include.html');
		if (isset($headers[0]) && ($headers[0] != 'HTTP/1.1 404 Not Found')) {
			update_option( "janolaw_version", 2 );
		}
		# check for version 3
		$headers = @get_headers($base_url.'/'.$user_id.'/'.$shop_id.'/de/legaldetails.pdf');
		if (isset($headers[0]) && ($headers[0] != 'HTTP/1.1 404 Not Found')) {
			update_option( "janolaw_version", 3 );
		}

		# check for version 3 + multilingual
		$headers = @get_headers($base_url.'/'.$user_id.'/'.$shop_id.'/fr/legaldetails.pdf');
		if (isset($headers[0]) && ($headers[0] != 'HTTP/1.1 404 Not Found')) {
			update_option( "janolaw_version", 4 );
		}

		if (get_option('janolaw_version') <= 2) {
			$message .= "<div id='setting-error-settings_updated' class='updated settings-error'>".__("Please update to version 3 of this service. You can do this by simply answering the questions of your services on the janolaw customer backend again, as they may have changed!<br />Your documents are than automatically updated after finishing the questions and you are good to go.<br /><br/> Please contact janolaw support for further questions and assistance.","janolaw-agb-hosting")."</div>";
		}
		if (get_option('janolaw_version') == 3) {
			$message .= "<div id='setting-error-settings_updated' class='updated settings-error'>".__("Your service is capable of upgrading to multilanguage documents.<br /><br/> Please contact janolaw support for further questions and assistance.","janolaw-agb-hosting")."</div>";
		}
	}
 	return $message;
}

function janolaw_plugin_options() {
	# check permission
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.', 'janolaw-agb-hosting') );
	}

	# predefine cache path if not entered yet
	$cachepath = get_option('janolaw_cache_path');
	if (!$cachepath) {
		$cachepath = "/tmp";
	}

	# predefine language if not entered yet
	$language = get_option('janolaw_language');
	if (!$language) {
		$language = "de";
		update_option( "janolaw_language", "de" );
	}
	# predefine language if not entered yet
	$language = get_option('janolaw_language_default');
	if (!$language) {
		$language = "de";
		update_option( "janolaw_language_default", "de" );
	}
	
	# create pages if not exist and checked to create
	
	if (get_option('janolaw_agb_page')) {
		$post = array(
			'ID' => get_option('janolaw_agb_page_id'),
			'comment_status' => 'closed',
			'post_content' => '[janolaw_agb]',
			'post_name' => 'agb',
			'post_status' => 'publish',
			'post_title' => __('Allgemeine Gesch&auml;ftsbedingungen','janolaw-agb-hosting'),
			'post_type' => 'page'
		);
		$id = wp_insert_post( $post );
		update_option( "janolaw_agb_page_id", $id );
	}
	if (get_option('janolaw_imprint_page')) {
		$post = array(
				'ID' => get_option('janolaw_imprint_page_id'),
				'comment_status' => 'closed',
				'post_content' => '[janolaw_impressum]',
				'post_name' => 'imprint',
				'post_status' => 'publish',
				'post_title' => __('Impressum','janolaw-agb-hosting'),
				'post_type' => 'page'
		);
		$id = wp_insert_post( $post );
		update_option( "janolaw_imprint_page_id", $id );
	}
	if (get_option('janolaw_widerruf_page')) {
		$post = array(
				'ID' => get_option('janolaw_widerruf_page_id'),
				'comment_status' => 'closed',
				'post_content' => '[janolaw_widerrufsbelehrung]',
				'post_name' => 'widerrufsbelehrung',
				'post_status' => 'publish',
				'post_title' => __('Widerrufsbelehrung','janolaw-agb-hosting'),
				'post_type' => 'page'
		);
		$id = wp_insert_post( $post );
		update_option( "janolaw_widerruf_page_id", $id );
	}
	if (get_option('janolaw_widerrufform_page')) {
		$post = array(
				'ID' => get_option('janolaw_widerrufform_page_id'),
				'comment_status' => 'closed',
				'post_content' => '[janolaw_widerrufsformular]',
				'post_name' => 'widerrufsformular',
				'post_status' => 'publish',
				'post_title' => __('Widerrufsformular','janolaw-agb-hosting'),
				'post_type' => 'page'
		);
		$id = wp_insert_post( $post );
		update_option( "janolaw_widerrufform_page_id", $id );
	}
	if (get_option('janolaw_privacy_page')) {
		$post = array(
				'ID' => get_option('janolaw_privacy_page_id'),
				'comment_status' => 'closed',
				'post_content' => '[janolaw_datenschutzerklaerung]',
				'post_name' => 'privacy',
				'post_status' => 'publish',
				'post_title' => __('Datenschutzerkl&auml;rung','janolaw-agb-hosting'),
				'post_type' => 'page'
		);
		$id = wp_insert_post( $post );
		update_option( "janolaw_privacy_page_id", $id );
	}

	if ( isset( $_GET['settings-updated'] ) ) {
		janolaw_api_call();
	}

?>

<div class="wrap">
	<h2>janolaw AGB Hosting</h2>
		<?php
			echo janolaw_server_check();
			$versionnumber = get_option('janolaw_version');
		?>
	<a href='https://www.janolaw.de/ueber_janolaw/kunden-werben-kunden.html?mtm_campaign=plugin-banner&mtm_source=wordpress#menu' target='_blank'>
		<img src='https://janolaw.de/system/modules/de.janolaw.site/resources/images/plugin_banner.png' alt='Empfehlen Sie uns weiter!' title='Empfehlen Sie uns weiter!' />
	</a></br>
	<div id='setting-error-settings_updated' class='update-nag settings-error'><b>
		<?= __('Please download & read the documentation !! -> ', 'janolaw-agb-hosting'); ?> <a href="https://api.janolaw.de/docs/plugins/wordpress/pdf/<?= get_option('janolaw_user_id') ?>/<?= get_option('janolaw_shop_id') ?>" target="_blank"><?= __('PDF Documentation', 'janolaw-agb-hosting'); ?></a></b>
	</div>

	<form method="post" action="options.php">
		<?php settings_fields( 'janolaw-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">janolaw User ID</th>
				<td><input type="text" name="janolaw_user_id" value="<?= get_option('janolaw_user_id'); ?>" /><br />
					 <small><?= __('Your janolaw User ID is issued by janolaw AG by registering at', 'janolaw-agb-hosting'); ?>
					<a href="https://www.janolaw.de/myjanolaw/agb-service/" target="_blank"><?= __('janolaw	AGB Hosting Service', 'janolaw-agb-hosting'); ?></a></small></td>
			</tr>
			<tr valign="top">
				<th scope="row">janolaw Shop ID</th>
				<td><input type="text" name="janolaw_shop_id" value="<?= get_option('janolaw_shop_id'); ?>" /><br />
					<small><?= __('Your janolaw Shop ID is issued by janolaw AG by registering at', 'janolaw-agb-hosting'); ?>
					<a href="https://www.janolaw.de/myjanolaw/agb-service/" target="_blank"><?= __('janolaw	AGB Hosting Service', 'janolaw-agb-hosting'); ?></a></small></td>
			</tr>
		</table>

		<br />
		<h3 class="title"><?= __('Settings', 'janolaw-agb-hosting'); ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?= __('Cache Path', 'janolaw-agb-hosting'); ?></th>
				<td>
					<?php 
						if (is_writeable($cachepath)) {
							$cachepathcheck = "<img src='".site_url()."/wp-content/plugins/janolaw-agb-hosting/images/ok.png' />" . __('Path is writable', 'janolaw-agb-hosting');
						} elseif (is_writeable(get_home_path()."wp-content/plugins/janolaw-agb-hosting")) {
							$cachepathcheck = "<img src='".site_url()."/wp-content/plugins/janolaw-agb-hosting/images/ok.png' />" . __('Path is writable, but alternative path is used.', 'janolaw-agb-hosting');
							$cachepath = get_home_path()."wp-content/plugins/janolaw-agb-hosting";
						} else {
							$cachepathcheck = "<img src='".site_url()."/wp-content/plugins/janolaw-agb-hosting/images/error.png' />" . __('Path is NOT writable and no writable path could be detected. Please contact your system administrator.', 'janolaw-agb-hosting');
						}
					?>
					<input type="text" name="janolaw_cache_path" value="<?= $cachepath ?>" /><br />
					<small><?= __('Path to store cached documents e.g. /tmp for Unix based systems like Linux', 'janolaw-agb-hosting'); ?></small><br />
					<small><?= $cachepathcheck ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?= __('Clear Cache', 'janolaw-agb-hosting'); ?></th>
				<td>
					<input type="checkbox" name="janolaw_cache_clear" value="1" <?= checked( 1, get_option('janolaw_cache_clear'), false ) ?> /> 
					<small><?= __('Check to clear cache & refresh from server by next pagecall', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>

			<?php if ($versionnumber == 4): ?>
			<tr valign="top">
				<th scope="row"><?= __('Language', 'janolaw-agb-hosting'); ?></th>
				<td>
					<select name="janolaw_language">
						<option value="auto" <?= selected( 'auto', get_option('janolaw_language'), false ) ?>><?= __('Automatic', 'janolaw-agb-hosting'); ?></option>
						<option value="de" <?= selected( 'de', get_option('janolaw_language'), false ) ?>><?= __('German', 'janolaw-agb-hosting'); ?></option>
						<option value="gb" <?= selected( 'gb', get_option('janolaw_language'), false ) ?>><?= __('English', 'janolaw-agb-hosting'); ?></option>
						<option value="fr" <?= selected( 'fr', get_option('janolaw_language'), false ) ?>><?= __('French', 'janolaw-agb-hosting'); ?></option>
					</select><br />
					<small><?= __('Select language for pages', 'janolaw-agb-hosting'); ?></small><br />
					<small><?= __('If not set to \'auto\' the selected language will be used.', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?= __('Default language', 'janolaw-agb-hosting'); ?></th>
				<td>
					<select name="janolaw_language_default">
						<option value="de" <?= selected( 'de', get_option('janolaw_language_default'), false ) ?>><?= __('German', 'janolaw-agb-hosting'); ?></option>
						<option value="gb" <?= selected( 'gb', get_option('janolaw_language_default'), false ) ?>><?= __('English', 'janolaw-agb-hosting'); ?></option>
						<option value="fr" <?= selected( 'fr', get_option('janolaw_language_default'), false ) ?>><?= __('French', 'janolaw-agb-hosting'); ?></option>
					</select><br />
					<small><?= __('Set default language for pages, if no matching language could be found.', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<?php else: ?>
			<tr valign="top">
				<th scope="row"><?= __('Language', 'janolaw-agb-hosting'); ?></th>
				<td><b><?= __('Your service is capable of upgrading to multilanguage documents.<br /><br/> Please contact janolaw support for further questions and assistance.', 'janolaw-agb-hosting'); ?></b>
				</td>
			</tr>
			<?php endif; ?>

			<?php if ($versionnumber >= 3): ?>
			<tr valign="top">
				<th scope="row"><?= __('PDF top download', 'janolaw-agb-hosting'); ?></th>
				<td>
					<input type="checkbox" name="janolaw_pdf_top" value="1" <?= checked( 1, get_option('janolaw_pdf_top'), false ) ?> /> 
					<small><?= __('Include PDF download links on top of pages content', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?= __('PDF bottom download', 'janolaw-agb-hosting'); ?></th>
				<td>
					<input type="checkbox" name="janolaw_pdf_bottom" value="1" <?= checked( 1, get_option('janolaw_pdf_bottom'), false ) ?> /> 
					<small><?= __('Include PDF download links on bottom of pages content', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<?php else: ?>
			<tr valign="top">
				<th scope="row"><?= __('PDF support', 'janolaw-agb-hosting'); ?></th>
				<td>
					<small><?= __('Include PDF download links on top/bottom of pages content for download. Upgrade to version 3 to use this!', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<?php endif; ?>
		</table>

		<br />
		<h3 class="title"><?= __('WooCommerce integration', 'janolaw-agb-hosting'); ?></h3>
		<?php if (class_exists( 'WooCommerce' )): ?>
			<table class="form-table">
			<tr valign="top">
				<th scope="row"><?= __('Emailattachments', 'janolaw-agb-hosting'); ?><br /><small><?= __('Please clear cache once you setup WooCommerce  integration for the first time!', 'janolaw-agb-hosting'); ?></small></th>
				<td>
				<input type="checkbox" name="janolaw_woomail_order_agb" value ="1" <?= checked( 1, get_option('janolaw_woomail_order_agb'), false ) ?> /> <small><?= __('Attach AGB', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<tr>
				<th scope="row"></th>
				<td>
				<input type="checkbox" name="janolaw_woomail_order_widerruf" value ="1" <?= checked( 1, get_option('janolaw_woomail_order_widerruf'), false ) ?> /> <small><?= __('Attach Widerrufsbelehrung', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<tr>
				<th scope="row"></th>
				<td>
				<input type="checkbox" name="janolaw_woomail_order_widerrufform" value ="1" <?= checked( 1, get_option('janolaw_woomail_order_widerrufform'), false ) ?> /> <small><?= __('Attach Widerrufsbelehrungsformular', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<tr>
				<th scope="row"></th>
				<td>
				<input type="checkbox" name="janolaw_woomail_order_datenschutz" value ="1" <?= checked( 1, get_option('janolaw_woomail_order_datenschutz'), false ) ?> /> <small><?= __('Attach Datenschutzerklärung', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			</table>
		<?php else: ?>
			<?= __('WooCommerce is not activated. Please activate WooCommerce to setup janolaw WooCommerce integration to Emails.', 'janolaw-agb-hosting'); ?>
		<?php endif; ?>








		<h3 class="title"><?= __('Page creation', 'janolaw-agb-hosting'); ?></h3>

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?= __('Create Page AGB', 'janolaw-agb-hosting'); ?></th>
				<td><input type="hidden" name="janolaw_agb_page_id" value ="<?= get_option('janolaw_agb_page_id'); ?>" />
				<input type="checkbox" name="janolaw_agb_page" value ="1" /> <small><?= __('Create a static page with pagetag included', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?= __('Create Page Imprint', 'janolaw-agb-hosting'); ?></th>
				<td><input type="hidden" name="janolaw_imprint_page_id" value ="<?= get_option('janolaw_imprint_page_id'); ?>" />
					<input type="checkbox" name="janolaw_imprint_page" value ="1" /> <small><?= __('Create a static page with pagetag included', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?= __('Create Page Widerruf', 'janolaw-agb-hosting'); ?></th>
				<td><input type="hidden" name="janolaw_widerruf_page_id" value ="<?= get_option('janolaw_widerruf_page_id'); ?>" />
				<input type="checkbox" name="janolaw_widerruf_page" value ="1" /> <small><?= __('Create a static page with pagetag included', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>


			<?php if ($versionnumber == 3): ?>
			<tr valign="top">
				<th scope="row"><?= __('Create Page Widerrufsformular', 'janolaw-agb-hosting'); ?></th>
				<td><input type="hidden" name="janolaw_widerrufform_page_id" value ="<?= get_option('janolaw_widerrufform_page_id'); ?>" />
				<input type="checkbox" name="janolaw_widerrufform_page" value ="1" /> <small><?= __('Create a static page with pagetag included', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<?php else: ?>
			<tr valign="top">
				<th scope="row"><?= __('Create Page Widerrufsformular', 'janolaw-agb-hosting'); ?></th>
				<td><small><?= __('Upgrade to version 3 to use the Widerrufsformular or insert the document by copy & paste', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<?php endif; ?>


			<tr valign="top">
				<th scope="row"><?= __('Create Page Privacy', 'janolaw-agb-hosting'); ?></th>
				<td><input type="hidden" name="janolaw_privacy_page_id" value ="<?= get_option('janolaw_privacy_page_id'); ?>" />
				<input type="checkbox" name="janolaw_privacy_page"
					value ="1" /> <small><?= __('Create a static page with pagetag included', 'janolaw-agb-hosting'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><h3><?= __('Howto', 'janolaw-agb-hosting'); ?></h3></th>
				<td><?= __('Check the Checkbox of the desired document to create the page automatically.', 'janolaw-agb-hosting'); ?>
				<br /><br />
					<?= __('Insert one of the following Tags into any page to display the refering janolaw document:', 'janolaw-agb-hosting'); ?>
						<blockquote>
						[janolaw_agb]<br />
						[janolaw_impressum]<br />
						[janolaw_widerrufsbelehrung]<br />
						
						[janolaw_widerrufsformular]<br />
						[janolaw_datenschutzerklaerung]
						</blockquote>
						<?php if ($versionnumber == 4): ?>
					<?= __('Alternatively insert one of the following <u>hardcoded</u> Tags into any page to display the refering janolaw document:', 'janolaw-agb-hosting'); ?>
						<blockquote>
						[janolaw_agb_XX]<br />
						[janolaw_impressum_XX]<br />
						[janolaw_widerrufsbelehrung_XX]<br />
						[janolaw_widerrufsformular_XX]<br />
						[janolaw_datenschutzerklaerung_XX]<br /><br />
						<?= __('where XX referes to the choosen language. Currently there are "de, "gb", "fr" supported.', 'janolaw-agb-hosting'); ?>
						<?php endif; ?>
						</blockquote>
					</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary"
				value="<?= __('Save Changes', 'janolaw-agb-hosting'); ?>" />
		</p>
	</form>
</div>

<?php
}

function attach_documents_to_woo_mail ($attachments , $id, $object) {
	$cachepath = get_option('janolaw_cache_path');
	
	if ( empty( $object ) ) {
        return $attachments;
    }
	if (get_option('janolaw_woomail_order_agb')) {
		$content =  _get_document('agb');
		array_push($attachments, $cachepath.'/agb.pdf');
	}
	if (get_option('janolaw_woomail_order_widerruf')) {
		$content =  _get_document('widerrufsbelehrung');
		array_push($attachments, $cachepath.'/widerrufsbelehrung.pdf');
	}
	if (get_option('janolaw_woomail_order_widerrufform')) {
		$content =  _get_document('widerrufsformular');
		array_push($attachments, $cachepath.'/widerrufsformular.pdf');
	}
	if (get_option('janolaw_woomail_order_datenschutz')) {
		$content =  _get_document('datenschutzerklaerung');
		array_push($attachments, $cachepath.'/datenschutzerklaerung.pdf');
	}

	return $attachments;
}

function _get_document($type, $language = null) {
	$cache_clear = 0;
	$user_id = get_option('janolaw_user_id');
	$shop_id = get_option('janolaw_shop_id');	
	$cache_path = get_option('janolaw_cache_path');
	$cache_clear = get_option('janolaw_cache_clear');
	$cache_time = 43200;
	$base_url = 'https://www.janolaw.de/agb-service/shops/';
	$cache_clear_msg = '';

	// use language for hardcoded tags
	if (null === $language) {
		$language = get_option('janolaw_language');

		# language autodetect
		if ($language == 'auto') {
			$lang = 'de';
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			}
			switch ($lang) {
		    	case "fr":
		        	$language = 'fr';
		        	break;
			    case "en":
			        $language = 'gb';
			        break;
			    default:
			    	$language = 'de';
			    	if (get_option('janolaw_language_default')) {
			    		$language = get_option('janolaw_language_default');
			    	}
			        break;
			}
		}
	}


	# clear cache and force refresh from server
	if ($cache_clear == 1) {
		foreach (glob($cache_path.'/'.$user_id.$shop_id.'*') as $filename) {
   			unlink($filename);
		}
		$cache_clear_msg = "<div style='border: border-left: #3ADF00 6px solid; padding-left: 10px;'>".__("Cleared cached documents!","janolaw-agb-hosting")."</div>";
		update_option( "janolaw_cache_clear", 0 );
	}

	# document type translation from v1 to v2/3 of service
	$pdf_naming_type = $type;
	$translation = array (
		'agb' => 'terms',
		'impressum' => 'legaldetails',
		'widerrufsbelehrung' => 'revocation',
		'datenschutzerklaerung' => 'datasecurity',
		'widerrufsformular' => 'model-withdrawal-form'
	);
	if (get_option('janolaw_version') >= 3) {
		# translate type
		$type = $translation[$type];
		$base_path = $base_url.$user_id.'/'.$shop_id.'/'.$language.'/'.$type;
		$cache_file = $cache_path.'/'.$user_id.$shop_id.'janolaw_'.$language.'_'.$type.'.html';
	} else {
		$base_path = $base_url.$user_id.'/'.$shop_id.'/'.$type;
		$cache_file = $cache_path.'/'.$user_id.$shop_id.'janolaw_'.$type.'.html';
	}


	# check if file exists at cache path
	if (file_exists($cache_file)) {
		if (filectime($cache_file)+$cache_time<=time()) {
			# get fresh version from server 
			if ($file = janolaw_url_get_contents($base_path.'_include.html')) {
				unlink ($cache_file);
				$fp = fopen($cache_file, 'w');
				fwrite($fp, $file);
				fclose($fp);
				// get fresh PDF files too for woocommerce integration
				if ( class_exists( 'WooCommerce' ) ) {
					$pdffile = janolaw_url_get_contents($base_path.'.pdf');
					file_put_contents($cache_path.'/'.$pdf_naming_type.'.pdf', $pdffile);
				}
			}
		}
	} else {
		$file = janolaw_url_get_contents($base_path.'_include.html');
		file_put_contents($cache_file, $file);

		// get fresh PDF files too for woocommerce integration
    	if ( class_exists( 'WooCommerce' ) ) {
    		$pdffile = janolaw_url_get_contents($base_path.'.pdf');
			file_put_contents($cache_path.'/'.$pdf_naming_type.'.pdf', $pdffile);
		}
	}
	# PDF Links
	$pdftop = '';
	$pdfbottom = '';
	if (get_option('janolaw_pdf_top') == 1) {
		$pdftop = "<a class='janolaw-pdflink' href='".$base_path.".pdf' target='_blank'>".__("Download as PDF","janolaw-agb-hosting")."</a><br /><br />";
	}
	if (get_option('janolaw_pdf_bottom') == 1) {
		$pdfbottom = "<br /><br /><a class='janolaw-pdflink' href='".$base_path.".pdf' target='_blank'>".__("Download as PDF","janolaw-agb-hosting")."</a>";
	}
	# extract text
	if ($file = file_get_contents($cache_file)) {
		return $cache_clear_msg . $pdftop . $file . $pdfbottom;
	} else {
		return "<div style='border: #DF0101 1px solid; border-left: #DF0101 6px solid; padding-left: 10px; '>".__("Ein Fehler ist aufgetreten! Bitte &uuml;berpr&uuml;fen Sie ihre janolaw UserID und ShopID in Ihrer Konfiguration und ob der Cache Pfad beschreibbar ist!","janolaw-agb-hosting")." # $language # $type # $base_path # $cache_file </div>";
	}
}

function janolaw_url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        $output = file_get_contents($Url);
        return $output;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

function janolaw_get_agb_page() {
	$content =  _get_document('agb');
	return $content;
}
function janolaw_get_impressum_page() {
	$content =  _get_document('impressum');
	return $content;
}
function janolaw_get_widerrufsbelehrung_page() {
	$content =  _get_document('widerrufsbelehrung');
	return $content;
}
function janolaw_get_widerrufsformular_page() {
	$content =  _get_document('widerrufsformular');
	return $content;
}
function janolaw_get_datenschutzerklaerung_page() {
	$content =  _get_document('datenschutzerklaerung');
	return $content;
}

function janolaw_get_agb_page_de() {
	$content =  _get_document('agb', 'de');
	return $content;
}
function janolaw_get_impressum_page_de() {
	$content =  _get_document('impressum', 'de');
	return $content;
}
function janolaw_get_widerrufsbelehrung_page_de() {
	$content =  _get_document('widerrufsbelehrung', 'de');
	return $content;
}
function janolaw_get_widerrufsformular_page_de() {
	$content =  _get_document('widerrufsformular', 'de');
	return $content;
}
function janolaw_get_datenschutzerklaerung_page_de() {
	$content =  _get_document('datenschutzerklaerung', 'de');
	return $content;
}

function janolaw_get_agb_page_gb() {
	$content =  _get_document('agb', 'gb');
	return $content;
}
function janolaw_get_impressum_page_gb() {
	$content =  _get_document('impressum', 'gb');
	return $content;
}
function janolaw_get_widerrufsbelehrung_page_gb() {
	$content =  _get_document('widerrufsbelehrung', 'gb');
	return $content;
}
function janolaw_get_widerrufsformular_page_gb() {
	$content =  _get_document('widerrufsformular', 'gb');
	return $content;
}
function janolaw_get_datenschutzerklaerung_page_gb() {
	$content =  _get_document('datenschutzerklaerung', 'gb');
	return $content;
}

function janolaw_get_agb_page_fr() {
	$content =  _get_document('agb', 'fr');
	return $content;
}
function janolaw_get_impressum_page_fr() {
	$content =  _get_document('impressum', 'fr');
	return $content;
}
function janolaw_get_widerrufsbelehrung_page_fr() {
	$content =  _get_document('widerrufsbelehrung', 'fr');
	return $content;
}
function janolaw_get_widerrufsformular_page_fr() {
	$content =  _get_document('widerrufsformular', 'fr');
	return $content;
}
function janolaw_get_datenschutzerklaerung_page_fr() {
	$content =  _get_document('datenschutzerklaerung', 'fr');
	return $content;
}

function janolaw_api_call_upgrade() {
	global $janolaw_version;
	if (get_option('janolaw_plugin_version'))
	{
		$current = get_option('janolaw_plugin_version');
	} else {
		register_setting( 'janolaw-settings-group', 'janolaw_plugin_version' );
		$current = 0;
	}

	if ($current != $janolaw_version)
	{
		# update plugin version number
		update_option( "janolaw_plugin_version", $janolaw_version );
		janolaw_api_call();
	}
}

function janolaw_api_call() {
	$url = 'https://api.janolaw.de/pluginsettings';
	$ch = curl_init($url);
	$jsonData = array(
		'userID' => get_option('janolaw_user_id'),
		'shopID' => get_option('janolaw_shop_id'),
		'plugin' => 'Wordpress',
		'domain' => site_url(),
		'pluginversion' => get_option('janolaw_plugin_version'),
		'cmsversion' => 'Wordpress ' . get_bloginfo( 'version' ),
		'settings' => '<b>Cache-Path:</b> ' . get_option('janolaw_cache_path') . '<br />'.
						'<b>Language:</b> ' . get_option('janolaw_language') . '<br />'.
						'<b>Language def.:</b> ' . get_option('janolaw_language_default') . '<br />'.
						'<b>PDF top:</b> ' . get_option('janolaw_pdf_top') . '<br />'.
						'<b>PDF bottom:</b> ' . get_option('janolaw_pdf_bottom') . '<br />',
		'misc' => '<b>Woo att. Widerruf:</b> ' . (get_option('janolaw_woomail_order_widerruf') == 1 ? 'Yes' : 'No' ). '<br />'.
					'<b>Woo att. AGB.:</b> ' . (get_option('janolaw_woomail_order_agb') == 1 ? 'Yes' : 'No' ). '<br />'.
					'<b>Woo att. Form.:</b> ' . (get_option('janolaw_woomail_order_widerrufform') == 1 ? 'Yes' : 'No' ). '<br />'.
					'<b>Woo att. DSGVO.:</b> ' . (get_option('janolaw_woomail_order_datenschutz') == 1 ? 'Yes' : 'No' ). '<br />',
	);
	$jsonDataEncoded = json_encode($jsonData);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_exec($ch);
}

add_action('admin_menu', 'janolaw_agb_menu');
add_action('upgrader_process_complete', 'janolaw_api_call_upgrade', 10, 2);

add_filter( 'woocommerce_email_attachments', 'attach_documents_to_woo_mail', 10, 3);
add_shortcode( 'janolaw_agb', 'janolaw_get_agb_page' );
add_shortcode( 'janolaw_impressum', 'janolaw_get_impressum_page' );
add_shortcode( 'janolaw_widerrufsbelehrung', 'janolaw_get_widerrufsbelehrung_page' );
add_shortcode( 'janolaw_widerrufsformular', 'janolaw_get_widerrufsformular_page' );
add_shortcode( 'janolaw_datenschutzerklaerung', 'janolaw_get_datenschutzerklaerung_page' );

add_shortcode( 'janolaw_agb_de', 'janolaw_get_agb_page_de' );
add_shortcode( 'janolaw_impressum_de', 'janolaw_get_impressum_page_de' );
add_shortcode( 'janolaw_widerrufsbelehrung_de', 'janolaw_get_widerrufsbelehrung_page_de' );
add_shortcode( 'janolaw_widerrufsformular_de', 'janolaw_get_widerrufsformular_page_de' );
add_shortcode( 'janolaw_datenschutzerklaerung_de', 'janolaw_get_datenschutzerklaerung_page_de' );

add_shortcode( 'janolaw_agb_gb', 'janolaw_get_agb_page_gb' );
add_shortcode( 'janolaw_impressum_gb', 'janolaw_get_impressum_page_gb' );
add_shortcode( 'janolaw_widerrufsbelehrung_gb', 'janolaw_get_widerrufsbelehrung_page_gb' );
add_shortcode( 'janolaw_widerrufsformular_gb', 'janolaw_get_widerrufsformular_page_gb' );
add_shortcode( 'janolaw_datenschutzerklaerung_gb', 'janolaw_get_datenschutzerklaerung_page_gb' );

add_shortcode( 'janolaw_agb_fr', 'janolaw_get_agb_page_fr' );
add_shortcode( 'janolaw_impressum_fr', 'janolaw_get_impressum_page_fr' );
add_shortcode( 'janolaw_widerrufsbelehrung_fr', 'janolaw_get_widerrufsbelehrung_page_fr' );
add_shortcode( 'janolaw_widerrufsformular_fr', 'janolaw_get_widerrufsformular_page_fr' );
add_shortcode( 'janolaw_datenschutzerklaerung_fr', 'janolaw_get_datenschutzerklaerung_page_fr' );

?>