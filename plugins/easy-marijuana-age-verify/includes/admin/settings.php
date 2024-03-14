<?php

// Don't access this directly, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define the settings page.
 *
 * @since 0.1
 */

	if( ! function_exists('emav_clear_cache') ) {
		function emav_clear_cache() {
			$emav_cleared = FALSE;
			// WP Rocket
			if ( function_exists( 'rocket_clean_domain' ) ) {
			  rocket_clean_domain();
			  echo "<div class='notice notice-success is-dismissible'><p>WP Rocket cache cleared.</p></div>";
	          $emav_cleared = TRUE;
			}
			// W3 Total Cache : w3tc
			if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			  w3tc_pgcache_flush();
			  echo "<div class='notice notice-success is-dismissible'><p>W3TC cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// WP Super Cache : wp-super-cache
			if ( function_exists( 'wp_cache_clear_cache' ) ) {
			  wp_cache_clear_cache();
			  echo "<div class='notice notice-success is-dismissible'><p>WP Super Cache cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// WP Fastest Cache
			if( function_exists('wpfc_clear_all_cache') ) {
			  wpfc_clear_all_cache(true);
			  echo "<div class='notice notice-success is-dismissible'><p>WP Fastest Cache cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// WPEngine
			if ( class_exists( 'WpeCommon' ) && method_exists( 'WpeCommon', 'purge_memcached' ) ) {
			  WpeCommon::purge_memcached();
			  WpeCommon::purge_varnish_cache();
			  echo "<div class='notice notice-success is-dismissible'><p>WPEngine cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// SG Optimizer by Siteground
			if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			  sg_cachepress_purge_cache();
			  echo "<div class='notice notice-success is-dismissible'><p>SiteGround Optimizer cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// LiteSpeed
			if( class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all') ) {
			  LiteSpeed_Cache_API::purge_all();
			  echo "<div class='notice notice-success is-dismissible'><p>LiteSpeed cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// Cache Enabler
			if( class_exists('Cache_Enabler') && method_exists('Cache_Enabler', 'clear_total_cache') ) {
			  Cache_Enabler::clear_total_cache();
			  echo "<div class='notice notice-success is-dismissible'><p>Cache Enabler cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// Pagely
			if ( class_exists('PagelyCachePurge') && method_exists('PagelyCachePurge','purgeAll') ) {
			  PagelyCachePurge::purgeAll();
			  echo "<div class='notice notice-success is-dismissible'><p>Pagely cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// Autoptimize
			if( class_exists('autoptimizeCache') && method_exists( 'autoptimizeCache', 'clearall') ) {
			  autoptimizeCache::clearall();
			  echo "<div class='notice notice-success is-dismissible'><p>Autoptimize cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// Comet cache
			if( class_exists('comet_cache') && method_exists('comet_cache', 'clear') ) {
			  comet_cache::clear();
			  echo "<div class='notice notice-success is-dismissible'><p>Comet Cache cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			// Hummingbird Cache
			if( class_exists('\Hummingbird\WP_Hummingbird') && method_exists('\Hummingbird\WP_Hummingbird', 'flush_cache') ) {
			  \Hummingbird\WP_Hummingbird::flush_cache();
			  echo "<div class='notice notice-success is-dismissible'><p>Hummingbird cache cleared.</p></div>";
			  $emav_cleared = TRUE;
			}
			if (! $emav_cleared == TRUE) {
				echo "<div class='notice notice-success is-dismissible'><p>NOTE: Please be sure to clear any page caches for new settings to display.</p></div>";
			}

		}
	}

function emav_settings_page() { ?>
	<div class="wrap">
		<div class="emav-options-column1">
			<div class="emav-headerDiv" >
			<a href="https://5starplugins.com/" target="_blank"><img class="emav-headerImg" src="<?php echo plugin_dir_url(__FILE__) . '../../includes/images/banner.jpg';?>"></a>
			</div>

			<h1 class="emav-header-title"><?php esc_html_e( ' Marijuana Age Verify', 'easy-marijuana-age-verify' ) ?></h1>

			<?php settings_errors(); ?>

			<?php if ( isset( $_GET['settings-updated'] ) ) {
				wp_cache_flush();
				emav_clear_cache();
			} ?>
			<form action="options.php" method="post" class="emav-settings-form">
				<?php settings_fields( 'easy-marijuana-age-verify' ); ?>
				<?php do_settings_sections( 'easy-marijuana-age-verify' ); ?>
				<?php submit_button(); ?>
			</form>

			<div class="emav-preview-note">
				<?php echo sprintf( __( '<strong>Clear Cookie:</strong> Popup stopped after clicking yes? <br/> Detects if a cookie is set in your browser from this plugin and clears it. Refresh this page to recheck.', 'easy-marijuana-age-verify' ));?>
				<p/>
				<button id="emav-clear-cookie" onclick='return emav_clear_cookie();' disabled>No Cookie Set</button>
			</div>
		</div>
		<div class="emav-premium-column2">
		<?php
			if ( emav_fs()->is_not_paying() ) {
				echo emav_display_upgrade_features();
			}
		?>
		</div>
	</div>
	<div class="emav-footer-notes">
		<?php echo sprintf( __( 'Need Help? Click the  icon on the bottom right to search our <a href="https://support.5starplugins.com/collection/47-easy-marijuana-age-verify" target="_blank">Knowledge Base</a> or to send an email for Premium support.', 'easy-marijuana-age-verify' )); ?>
		<p/><?php echo sprintf( __( 'Read: <a href="https://support.5starplugins.com/article/142-my-age-verify-window-is-not-popping-up"  target="_blank">My age window isn\'t showing.</a>', 'easy-marijuana-age-verify' ));?>
		<p/><?php echo sprintf( __( '<a href="/wp-admin/admin.php?page=easy-marijuana-age-verify-contact">Contact Us</a> to report a bug, suggest a feature, ask a billing question, or request Premium support.', 'easy-marijuana-age-verify' )); ?>
		<p/><?php echo sprintf( __( '<a href="https://5starplugins.com/get-support/" target="_blank">Visit The Support Center</a> for more.', 'easy-marijuana-age-verify' ) ); ?>
		<p/><?php echo sprintf( __( 'Use Age Verify with Pretty Simple Popup for <a href="https://get.5starplugins.com/pspforageverify/" target="_blank">perfectly timed marketing popups</a>.', 'easy-marijuana-age-verify' )); ?>
		<p/><?php echo sprintf( __( 'Like this? <a href="http://wordpress.org/support/view/plugin-reviews/easy-marijuana-age-verify/?rate=5#new-post"  target="_blank">Rate This Plugin</a>', 'easy-marijuana-age-verify' )); ?>
		<br />
		<p/><?php echo sprintf( __('Developed and supported by <a href="https://5starplugins.com/"  target="_blank">5 Star Plugins</a> in San Diego, CA', 'easy-marijuana-age-verify')); ?> <img class="footerLogo" src="<?php echo plugins_url( 'images/5StarPlugins_Logo80x80.png', dirname(__FILE__) );?>" width="20">
	</div>
<?php }

/**********************************************************/
/******************** General Settings ********************/
/**********************************************************/

/**
 * Prints the general settings section heading.
 *
 * @since 0.1
 */
function emav_settings_callback_section_general() {
	// Something should go here
}

/**
 * Prints the modal heading settings field.
 *
 * @since 0.1
 */
function emav_settings_callback_age_header_field() {

	?>
	<input name="_emav_age_header" type="text" id="_emav_age_header" maxlength="50"
		   value="<?php echo esc_attr( get_option( '_emav_age_header', __( 'Please verify your age to enter.', 'easy-marijuana-age-verify' ) ) ); ?>"
		   class="regular-text"/>

	<?php
}

/**
 * Prints the "require for" settings field.
 *
 * @since 0.2
 */
function emav_settings_callback_require_for_field() { ?>
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Require verification for', 'easy-marijuana-age-verify' ); ?></span>
		</legend>
		<label>
			<input type="radio" name="_emav_require_for" value="site" <?php checked( 'site', get_option( '_emav_require_for', 'site' ) ); ?>/>
			 <?php esc_html_e( 'Entire site', 'easy-marijuana-age-verify' ); ?><br />
		</label>
		<br />
		<label>
			<input type="radio" name="_emav_require_for" value="content" <?php checked( 'content', get_option( '_emav_require_for', 'site' ) ); ?>/>
			 <?php esc_html_e( 'Specific content', 'easy-marijuana-age-verify' ); ?>
		</label>
	</fieldset>
<?php }

/**
 * Prints the "who to verify" settings field.
 *
 * @since 0.1
 */
function emav_settings_callback_always_verify_field() {
	$option = get_option( '_emav_always_verify' );
	$checked =  checked( 'disabled', $option , false );
	if( ! $option && $option !==  'disabled' ) {$checked =  checked( 'disabled', $option , false );}
	?>
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Enable verification:', 'easy-marijuana-age-verify' ); ?></span>
		</legend>
		<label>
			<input type="radio" name="_emav_always_verify" value="disabled" <?php echo $checked; ?>/>
			 <?php esc_html_e( 'Disable', 'easy-marijuana-age-verify' ); ?>
		</label>
		<br />
		<label>
			<input type="radio" name="_emav_always_verify" value="admin-only" <?php checked( 'admin-only', get_option( '_emav_always_verify', 'admin-only' ) ); ?>/>
			 <?php esc_html_e( '[TESTING MODE] Admins only', 'easy-marijuana-age-verify' ); ?>
		</label>
		<br />
		<label>
			<input type="radio" name="_emav_always_verify" value="guests" <?php checked( 'guests', get_option( '_emav_always_verify', 'guests' ) ); ?>/>
			 <?php esc_html_e( 'Show except to logged-in users', 'easy-marijuana-age-verify' ); ?>
		</label>
		<br />
		<label>
			<input type="radio" name="_emav_always_verify" value="all" <?php checked( 'all', get_option( '_emav_always_verify', 'all' ) ); ?>/>
			 <?php esc_html_e( 'Show to all visitors', 'easy-marijuana-age-verify' ); ?>
		</label>
	</fieldset>
<?php }

function emav_settings_callback_ask_visitors_field() { ?>
	<fieldset class="emav-age-header-option">
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Verify For:', 'easy-marijuana-age-verify' ); ?></span>
		</legend>
		<label>
			<input type="radio" name="_emav_user_age_verify_option" value="1" <?php checked( '1', get_option( '_emav_user_age_verify_option', '1' ) ); ?>/>
			 <?php esc_html_e( '21+ Recreational', 'easy-marijuana-age-verify' ); ?>
		</label>
		<?php if(!function_exists('emav_premium_verify_option')){
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Standard-21-Rec-2022.jpg" class="thickbox">Standard Screenshot</a> | ';
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-21-Rec-2022.jpg" class="thickbox">Premium Screenshot</a>';
			} else {
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-21-Rec-2022.jpg" class="thickbox">Premium Screenshot</a>';
			} ?>
		<br />
		<label>
			<input type="radio" name="_emav_user_age_verify_option" value="2" <?php checked( '2', get_option( '_emav_user_age_verify_option', '2' ) ); ?>/>
			 <?php esc_html_e( '19+ Recreational', 'easy-marijuana-age-verify' ); ?>
		</label>
		<?php if(!function_exists('emav_premium_verify_option')){
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Standard-19-Rec-2022.jpg" class="thickbox">Standard Screenshot</a> | ';
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-19-Rec-2022.jpg" class="thickbox">Premium Screenshot</a>';
			} else {
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-19-Rec-2022.jpg" class="thickbox">Premium Screenshot</a>';
			} ?>
		<br />
		<label>
			<input type="radio" name="_emav_user_age_verify_option" value="3" <?php checked( '3', get_option( '_emav_user_age_verify_option', '3' ) ); ?>/>
			 <?php esc_html_e( '18+ Recreational', 'easy-marijuana-age-verify' ); ?>
		</label>
		<?php if(!function_exists('emav_premium_verify_option')){
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Standard-18-Rec-2022.jpg" class="thickbox">Standard Screenshot</a> | ';
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-18-Rec-2022.jpg" class="thickbox">Premium Screenshot</a>';
			} else {
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-18-Rec-2022.jpg" class="thickbox">Premium Screenshot</a>';
			} ?>
		<br />
		<label>
			<input type="radio" name="_emav_user_age_verify_option" value="4" <?php checked( '4', get_option( '_emav_user_age_verify_option', '4' ) ); ?>/>
			 <?php esc_html_e( '18+ Medical', 'easy-marijuana-age-verify' ); ?>
		</label>
		<?php if(!function_exists('emav_premium_verify_option')){
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Standard-18-Med-2022.jpg" class="thickbox">Standard Screenshot</a> | ';
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-18-Med-2022.jpg" class="thickbox">Premium Screenshot</a>';
			} else {
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-18-Med-2022.jpg" class="thickbox">Premium Screenshot</a>';
			} ?>
		<br />
		 <?php
		if(function_exists('emav_premium_verify_option')) {
			echo emav_premium_verify_option();
		} else { ?>
			   <label>
				 <span style="margin-left: 23px;font-style: italic;"><?php esc_html_e( '18+ Medical/21+ Recreational (upgrade to unlock)', 'easy-marijuana-age-verify' ); ?></span>
				</label> <a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-Med-Rec-Combo-2022.jpg"
				class="thickbox">Premium Screenshot</a>
		<?php }
		if(function_exists('emav_premium_opentext_option')) {
			echo emav_premium_opentext_option();
		} else { ?>
			<br />
			<label>
				<span style="margin-left: 23px;font-style: italic;"><?php esc_html_e( 'Free-form Custom Text (upgrade to unlock)', 'easy-marijuana-age-verify' ); ?></span>
			</label> <a href="https://5starplugins.com/wp-content/uploads/2022/08/MAV_Premium-Freeform-2022.jpg"
				class="thickbox">Premium Screenshot</a>
		<?php } ?>
		</fieldset>
	<?php }

/**
 * Prints the minimum age settings field.
 *
 * @since 0.1
 */
function emav_settings_callback_minimum_age_field() { ?>

	<input name="_emav_minimum_age" type="number" id="_emav_minimum_age" step="1" min="10" class="small-text" value="<?php echo esc_attr( get_option( '_emav_minimum_age', '21' ) ); ?>" /> <?php esc_html_e( 'years old or older to view this site', 'easy-marijuana-age-verify' ); ?>

<?php }

/**
 * Prints the cookie duration settings field.
 *
 * @since 0.1
 */
function emav_settings_callback_cookie_duration_field() { ?>

	<input name="_emav_cookie_duration" type="number" id="_emav_cookie_duration" step="15" min="15" class="small-text" value="<?php echo esc_attr( get_option( '_emav_cookie_duration', '720' ) ); ?>" /> <?php esc_html_e( 'minutes', 'easy-marijuana-age-verify' ); ?>

<?php }

/**
 * Prints the membership settings field.
 *
 * @since 0.1
 */
function emav_settings_callback_membership_field() { ?>

	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Membership', 'easy-marijuana-age-verify' ); ?></span>
		</legend>
		<label for="_emav_membership">
			<input name="_emav_membership" type="checkbox" id="_emav_membership" value="1" <?php checked( 1, get_option( '_emav_membership', 1 ) ); ?>/>
			 <?php esc_html_e( 'Require users to confirm their age before registering to this site', 'easy-marijuana-age-verify' ); ?>
		</label>
	</fieldset>

<?php }

/**
 * Prints the modal Disclaimer settings field.
 *
 * @since 0.1
 */
function emav_settings_callback_disclaimer_field() {
	$key = '_emav_disclaimer';
	$text =  "";

	if( empty( get_option($key) ) )
		update_option($key,$text);

	$message = esc_attr( get_option( $key, $text) );

	printf('<textarea name="%1$s" id="%1$s" maxlength="250" rows="6" class="regular-text"/>%2$s</textarea>',
		'_emav_disclaimer',
		$message
	);
}

/**
 * Outputs the "cache-buster AJAX" option settings field.
 *
 * @since 1.3
 */
function emav_settings_callback_ajax_check() {
    ?>
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Realtime Settings Check', 'easy-marijuana-age-verify' ); ?></span>
		</legend>
		<label>
			<input type="checkbox" name="_emav_ajax_check" value="emav-ajax-check" <?php checked( 'emav-ajax-check', get_option( '_emav_ajax_check', true ) ); ?>/>
			 <?php esc_html_e( 'Confirm "Enable Verification" settings before showing popup', 'easy-marijuana-age-verify' ); ?>
		</label>
	</fieldset>
<?php }

/**********************************************************/
/******************** Display Settings ********************/
/**********************************************************/

/**
 * Prints the display settings section heading.
 *
 * @since 0.1
 */
function emav_settings_callback_section_display() {

	//echo '<p>' . esc_html__( 'These settings change the look of your overlay. You can use <code>%s</code> to display the minimum age number from the setting above.', 'easy-marijuana-age-verify' ) . '</p>';
}

/**
 * Prints the input type settings field.
 *
 * @since 0.1
 */
function emav_settings_callback_input_type_field() { ?>

	<select name="_emav_input_type" id="_emav_input_type">
		<option value="dropdowns" <?php selected( 'dropdowns', get_option( '_emav_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Date dropdowns', 'easy-marijuana-age-verify' ); ?></option>
		<option value="inputs" <?php selected( 'inputs', get_option( '_emav_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Inputs', 'easy-marijuana-age-verify' ); ?></option>
		<option value="checkbox" <?php selected( 'checkbox', get_option( '_emav_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Confirm checkbox', 'easy-marijuana-age-verify' ); ?></option>
	</select>

<?php } ?>
