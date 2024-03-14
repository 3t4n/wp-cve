<?php
/*
 * Plugin Name: Lead Champion
 * Plugin URI: https://www.leadchampion.com
 * Description: This plugin allows an easy integration of Lead Champion discover and Lead Champion booster on sites running WordPress.
 * Version: 25.03.14
 * Author: Lead Champion Team
 * Text Domain: lead-champion-discover
 * Domain Path: /i18n/
 * Author URI: https://www.leadchampion.com
 * Copyright 2016-2021  Lead Champion  (email : tech@leadchampion.com)
 * License: GPL
 *
 *
 * __() returns the translated text
 * _e() display in page (echo) the translated text
 */


function lcd_validate_cookieMode( $value ) {
	//error_log('LCD: lcd_validate_cookieMode='.$value);
	if ( $value == 'on' || $value == 'off' || $value == 'ckbot' || $value == 'iub' ) {
		return $value;
	}
	return 'auto';
}

if (get_option('lcd_site_id') == '101') {	// special value for resetting to defaults
	error_log('deleting LCD options lcd_site_id and cookieMode');
	delete_option('lcd_site_id');
	delete_option('cookieMode');
}

/*
 * Settings
 */
define('lcd_default_site_id','0');

if (!get_option('cookieMode')) {
	add_option('cookieMode','auto');
} else {
	update_option('cookieMode', lcd_validate_cookieMode( get_option('cookieMode') ) );
}
if (!get_option('lcd_site_id')) {
    if (get_option('key_lcd_site_id')) {
        // recuperiamo il valore col vecchio nome usato nelle versioni 1.x
        add_option('lcd_site_id',get_option('key_lcd_site_id'));
    } else {
        error_log('lcd_site_id not defined, use default');
        add_option('lcd_site_id', lcd_default_site_id);
    }
}

error_log('LCD: lcd_site_id='.get_option('lcd_site_id').' cookieMode='.get_option('cookieMode'));

function leadchampion_load_plugin_textdomain() {
	// caricamento delle stringhe nazionalizzate
	load_plugin_textdomain( 'lead-champion-discover', FALSE, basename( dirname( __FILE__ ) ) . '/i18n/' );
}
add_action( 'plugins_loaded', 'leadchampion_load_plugin_textdomain' );

function lcd_validate_site_id( $value ) {
	if (strlen($value) >= 3) {
		return $value;
	}
	error_log('lcd_validate_site_id error strlen='.strlen($value));
	return '';
}	

// Create an option page for settings
add_action('admin_menu', 'add_lcd_option_page');

/*
 * Hook in the options page function
 */
function add_lcd_option_page() {
	global $wpdb;
	add_menu_page(__('Lead Champion Options','lead-champion-discover'), 'Lead Champion', 8, basename(__FILE__), 'lcd_option_page', plugins_url( 'images/lcd_favicon.png', __FILE__ ),61);
}

function lcd_option_page() {
	if( isset($_POST['lcd_update']) ) {
		if ( wp_verify_nonce($_POST['lcd-nonce-key'], 'wp_lcd') ) {
			$lcd_site_id = $_POST['lcd_site_id'];
			//error_log('POST-lcd_site_id preValidate='.$lcd_site_id);
			$lcd_site_id = lcd_validate_site_id(sanitize_key($lcd_site_id));
			//error_log('POST-lcd_site_id afterValidate='.$lcd_site_id);
			update_option('lcd_site_id', $lcd_site_id );

			$cookieMode = lcd_validate_cookieMode(sanitize_key($_POST['cookieMode']));
			update_option('cookieMode', $cookieMode );
		}
		// Give an updated message
		echo '<div class="updated fade"><p><strong>';
		_e('Lead Champion - Settings successfully saved!','lead-champion-discover');
		//echo '<br>site id: ' . get_option('lcd_site_id') . ' - cookies mode: ' . get_option('cookieMode');
		echo '</strong></p></div>';		
	}

	// Output the options page
	?>
	<div class="wrap">
		<form method="post" action="admin.php?page=wp_lcd.php">
			<input type="hidden" name="lcd-nonce-key" value="<?php echo wp_create_nonce('wp_lcd'); ?>" />
			<h2><img style="vertical-align:middle;padding:5px;height:35px;margin:0 10px 20px 0;background-color:white;" src="<?php echo plugins_url('images/LeadChampionLogoName.svg', __FILE__ );?>"/><?php _e('Settings','lead-champion-discover'); ?></h2>
			<?php
			if( get_option('lcd_site_id') == lcd_default_site_id ) {
				echo '<div style="margin:10px auto; border:3px red solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">';
				_e('Lead Champion is active, but you do not enter a valid siteID.',
				   'lead-champion-discover'
				);
				echo '</div>';
			}?>

			<h3><?php
				printf(	__('In order to activate Lead Champion you have to enter the ID of your site. You can find it in your account at the path <a href="%s" target="_blank">Settings/Properties</a> labelled as "Site ID".<br><br>You do not have an account? Register <a href="%s" target="_blank">here</a> and activate your Lead Champion!',
						'lead-champion-discover'
					),
					'https://discover.leadchampion.com/#/configProperties',
					'https://discover.leadchampion.com/registration.html'
				);
			?></h3>

			<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
			<tr>
				<th valign="top" style="padding-top:15px;">
					<label for="lcd_site_id"><?php _e('Your Site ID','lead-champion-discover');?>:</label>
					<input type="number" min="100" max="99999" style="width:100px;" name="lcd_site_id" id="lcd_site_id" value="<?php echo(get_option('lcd_site_id')) ?>" />
					<!--
					<p style="margin: 5px 10px;"><?php _e('Enter your Lead Champion site ID.','lead-champion-discover');?></p>
					-->
				</th>
			</tr>
			</table>

			<table class="form-table" cellspacing="2" style="width:100%;max-width:999px;">
			<tr>
				<th valign="top" style="padding-top:10px;padding-bottom:0;">
					Lead Champion Cookieless
				</th>
			</tr>
			<tr>
				<td><?php
				_e('It is possible to activate Lead Champion in <b><i>cookieless</i></b> mode - in which case the system does not make any use of cookies - while maintaining both the recognition of the companies that visit the site and the aggregation of the pages viewed in a single visit.<br>If the site has consent management services, it is possible to activate Lead Champion in <b><i>cookieless</i></b> mode with an additional code compatible with the consent managers so that if the visitor accepts the use of cookies, they are automatically activated for Lead Champion as well, allowing the recognition of returning visitors too.',
						'lead-champion-discover'
					);
				?></td>
			</tr>
			<tr>
				<td><?php
					_e('Choose the type of Lead Champion cookie management you want to activate on your site:',
						'lead-champion-discover'
					);
					//echo(' cookieMode='.get_option('cookieMode'))
				?></td>
			</tr>
			<tr>
				<td style="padding-left:30px;padding-top:0;">
					<input type="radio" name="cookieMode" value="on"    id="ckMode-on"    <?php echo(get_option('cookieMode') == 'on'    ? 'checked': '') ?>>
					<label for="ckMode-on"><?php _e('Lead Champion in always-on cookie mode.','lead-champion-discover'); ?></label>
					<br>
					<input type="radio" name="cookieMode" value="iub"   id="ckMode-iub"   <?php echo(get_option('cookieMode') == 'iub'   ? 'checked': '') ?>>
					<label for="ckMode-iub"><?php _e('Lead Champion in cookieless mode with activation of cookies through the Iubenda&nbsp;&reg; manager','lead-champion-discover'); ?></label>
					<br>
					<input type="radio" name="cookieMode" value="ckbot" id="ckMode-ckbot" <?php echo(get_option('cookieMode') == 'ckbot' ? 'checked': '') ?>>
					<label for="ckMode-ckbot"><?php _e('Lead Champion in cookieless mode with activation of cookies through the Cookiebot&nbsp;&reg; manager','lead-champion-discover'); ?></label>
					<br>
					<input type="radio" name="cookieMode" value="off"   id="ckMode-off"   <?php echo(get_option('cookieMode') == 'off'   ? 'checked': '') ?>>
					<label for="ckMode-off"><?php _e('Lead Champion in cookieless mode with cookies always disabled','lead-champion-discover'); ?></label>
					<br>
					<input type="radio" name="cookieMode" value="auto"  id="ckMode-auto"  <?php echo(get_option('cookieMode') == 'auto'  ? 'checked': '') ?>>
					<label for="ckMode-auto"><?php _e('Lead Champion in cookieless mode with predisposition for activation.','lead-champion-discover'); ?></label>
					<br>
					<ul>
					<label><?php _e('Upon acceptance of the cookies by the visitor, it is possible to activate Lead Champion cookies by running the following code:','lead-champion-discover'); ?></label>
					<pre>
&lt;script type="text/javascript"&gt;
   window._lcCookie='on';
&lt;/script&gt;
					</pre>
					</ul>
				</td>
			</tr>
			</table>

			<p class="submit">
				<input class="button button-primary" type="submit" name="lcd_update" value="<?php _e('Save','lead-champion-discover');?>" />
			</p>
		</form>
	</div>
	<?php
}

function insert_lcd_script() {
	$cookieMode = get_option('cookieMode');

	if ($cookieMode == 'off' || $cookieMode == 'on') {
		$lcCookie = $cookieMode;
	} else {
		$lcCookie = 'auto';
	}
	//error_log('insert_lcd_script: cookieMode='.get_option('cookieMode').'-'.$cookieMode.' lcCookie='.$lcCookie);

	echo "<!-- BEGIN Lead Champion tag with cookieMode=".$cookieMode." from WP -->\n";
	echo "<script type=\"text/javascript\">\n";
	echo "   window._lcCookie='".$lcCookie."';\n";
	echo "   window._lcSiteid=". esc_html(get_option('lcd_site_id')) . ";\n";
	echo "   var _lcScript = document.createElement('script');\n";
	echo "   _lcScript.src='//cdn.leadchampion.com/leadchampion.js?sid=window._lcSiteid';\n";
	echo "   _lcScript.async=1;\n";
	echo "   if(document.body){\n";
	echo "      document.body.appendChild(_lcScript);\n";
	echo "   }else{\n";
	echo "      document.getElementsByTagName('head')[0].appendChild(_lcScript);\n";
	echo "   }\n";
	echo "</script>\n";
	echo "<!-- END Lead Champion tag -->\n";

	// add cookieManager custom code
	if ($cookieMode == 'iub') {
		//error_log('insert_lcd_script: IUB code');
		echo "<!-- BEGIN Lead Champion IUBENDA compatibility tag from WP -->\n";
		echo "<script async type=\"text/plain\" class=\"_iub_cs_activate-inline\" data-iub-purposes=\"5\" >\n";
		echo "   /* when marketing cookies have been accepted */\n";
		echo "   window._lcCookie = 'on';\n";
		echo "</script>\n";
		echo "<!-- END   Lead Champion IUBENDA compatibility tag -->\n";
	}
	if ($cookieMode == 'ckbot') {
		//error_log('insert_lcd_script: CKBOT code');
		echo "<!-- BEGIN Lead Champion COOKIEBOT compatibility tag from WP -->\n";
		echo "<script type=\"text/javascript\">\n";
		echo "   window.addEventListener('CookiebotOnAccept', function (e) {\n";
     		echo "      if (Cookiebot.consent.marketing) {\n";
		echo "         /* when marketing cookies have been accepted */\n";
		echo "         window._lcCookie = 'on';\n";
		echo "      }\n";
		echo "   }, false);\n";
		echo "</script>\n";
		echo "<!-- END   Lead Champion COOKIEBOT compatibility tag -->\n";
	}
}

if (get_option('lcd_site_id') != lcd_default_site_id) {
	add_action('wp_head', 'insert_lcd_script');
}
?>
