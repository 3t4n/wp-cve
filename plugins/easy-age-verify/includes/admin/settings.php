<?php

// Don't access this directly, please
if ( ! defined( 'ABSPATH' ) ) {exit;}

/**
* Add clear_cache function that detects and triggers various cache plugins
*
* @since 1.2
*/
if( ! function_exists('evav_clear_cache') ) {
	function evav_clear_cache() {
		$evav_cleared = FALSE;
		// WP Rocket
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
			echo "<div class='notice notice-success is-dismissible'><p>WP Rocket cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// W3 Total Cache : w3tc
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
			echo "<div class='notice notice-success is-dismissible'><p>W3TC cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// WP Super Cache : wp-super-cache
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
			echo "<div class='notice notice-success is-dismissible'><p>WP Super Cache cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// WP Fastest Cache
		if( function_exists('wpfc_clear_all_cache') ) {
			wpfc_clear_all_cache(true);
			echo "<div class='notice notice-success is-dismissible'><p>WP Fastest Cache cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// WPEngine
		if ( class_exists( 'WpeCommon' ) && method_exists( 'WpeCommon', 'purge_memcached' ) ) {
			WpeCommon::purge_memcached();
			WpeCommon::purge_varnish_cache();
			echo "<div class='notice notice-success is-dismissible'><p>WPEngine cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// SG Optimizer by Siteground
		if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			sg_cachepress_purge_cache();
			echo "<div class='notice notice-success is-dismissible'><p>SiteGround Optimizer cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// LiteSpeed
		if( class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all') ) {
			LiteSpeed_Cache_API::purge_all();
			echo "<div class='notice notice-success is-dismissible'><p>LiteSpeed cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// Cache Enabler
		if( class_exists('Cache_Enabler') && method_exists('Cache_Enabler', 'clear_total_cache') ) {
			Cache_Enabler::clear_total_cache();
			echo "<div class='notice notice-success is-dismissible'><p>Cache Enabler cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// Pagely
		if ( class_exists('PagelyCachePurge') && method_exists('PagelyCachePurge','purgeAll') ) {
			PagelyCachePurge::purgeAll();
			echo "<div class='notice notice-success is-dismissible'><p>Pagely cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// Autoptimize
		if( class_exists('autoptimizeCache') && method_exists( 'autoptimizeCache', 'clearall') ) {
			autoptimizeCache::clearall();
			echo "<div class='notice notice-success is-dismissible'><p>Autoptimize cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// Comet cache
		if( class_exists('comet_cache') && method_exists('comet_cache', 'clear') ) {
			comet_cache::clear();
			echo "<div class='notice notice-success is-dismissible'><p>Comet Cache cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		// Hummingbird Cache
		if( class_exists('\Hummingbird\WP_Hummingbird') && method_exists('\Hummingbird\WP_Hummingbird', 'flush_cache') ) {
			\Hummingbird\WP_Hummingbird::flush_cache();
			echo "<div class='notice notice-success is-dismissible'><p>Hummingbird cache cleared.</p></div>";
			$evav_cleared = TRUE;
		}
		if (! $evav_cleared == TRUE) {
			echo "<div class='notice notice-success is-dismissible'><p>NOTE: Please be sure to clear any page caches for new settings to display.</p></div>";
		}
	}
}

/**
 * Define the settings page.
 *
 * @since 0.1
 */
function evav_settings_page() {
    global $evav_fs;
    ?>

	<div class="wrap">
		<div class="evav-options-column1">
			<div class="evav-headerDiv" >
				<a href="https://5starplugins.com/" target="_blank"><img class="evav-headerImg" src="<?php echo plugin_dir_url(__FILE__) . '../../includes/images/banner.jpg';?>"></a>
			</div>

	        <h1 class="evav-header-title"><?php esc_html_e( 'Easy Age Verify', 'easy-age-verify' ) ?></h1>

			<?php settings_errors(); ?>

			<?php if ( isset( $_GET['settings-updated'] ) ) {
				wp_cache_flush();
				evav_clear_cache();
			} ?>

			<form action="options.php" method="post" class="evav-settings-form">
				<?php settings_fields( 'easy-age-verify' ); ?>
				<?php do_settings_sections( 'easy-age-verify' ); ?>
				<?php submit_button(); ?>
			</form>

			<div class="evav-preview-note">
				<?php
					echo sprintf( __( '<strong>Clear Cookie:</strong> Popup stopped after clicking yes? <br/>', 'easy-age-verify' ));
					echo sprintf( __( 'Detects if a cookie is set in your browser from this plugin and clears it. Refresh this page to recheck.', 'easy-age-verify' ));?>
				<p/>
				<button id="evav-clear-cookie" onclick='return evav_clear_cookie();' disabled>No Cookie Set</button>
			</div>
		</div>
		<div class="evav-premium-column2">
			<?php
				if ( $evav_fs->is_not_paying() ) {
					echo evav_display_upgrade_features();
				}
			?>
		</div>
	</div>
	<div class="evav-footer-notes">
		<?php echo sprintf( __( 'Need Help? Click the  icon on the bottom right to search our <a href="https://support.5starplugins.com/collection/69-easy-age-verify" target="_blank">Knowledge Base</a> or to send an email for Premium support.', 'easy-age-verify' )); ?>
		<p/><?php echo sprintf( __( 'Read: <a href="https://support.5starplugins.com/article/79-my-age-verify-window-is-not-popping-up"  target="_blank">My age window isn\'t showing.</a>', 'easy-age-verify' ));?>
		<p/><?php echo sprintf( __( '<a href="/wp-admin/admin.php?page=easy-age-verify-contact">Contact Us</a> to report a bug, suggest a feature, ask a billing question, or request Premium support.', 'easy-age-verify' )); ?>
		<p/><?php echo sprintf( __( '<a href="https://5starplugins.com/get-support/" target=_blank>Visit The Support Center</a> for more.', 'easy-age-verify' ) ); ?>
		<p/><?php echo sprintf( __( 'Use Age Verify with Pretty Simple Popup for <a href="https://get.5starplugins.com/pspforageverify/" target="_blank">perfectly timed marketing popups</a>.', 'easy-age-verify' )); ?>
		<p/><?php echo sprintf( __( 'Like this? <a href="http://wordpress.org/support/view/plugin-reviews/easy-age-verify/?rate=5#new-post" target=_blank>Rate This Plugin</a>', 'easy-age-verify' )); ?>
		<p/><?php echo sprintf( __( 'Developed and supported by <a href="%s" target=_blank>5 Star Plugins</a> in San Diego, CA', 'easy-age-verify' ), esc_url('https://5starplugins.com/')); ?> <img class="footerLogo" src="<?php echo plugins_url( 'images/5StarPlugins_Logo80x80.png', dirname(__FILE__) );?>" width="20">
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
function evav_settings_callback_section_general() {
	// Something should go here
}

/**
 * Prints the "require for" settings field.
 *
 * @since 0.2
 */
function evav_settings_callback_require_for_field() { ?>
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Require Verification For:', 'easy-age-verify' ); ?></span>
		</legend>
		<label>
			<input type="radio" name="_evav_require_for" value="site" <?php checked( 'site', get_option( '_evav_require_for', 'site' ) ); ?>/>
			 <?php esc_html_e( 'Entire site', 'easy-age-verify' ); ?><br />
		</label>
		<br />
		<label>
			<input type="radio" name="_evav_require_for" value="content" <?php checked( 'content', get_option( '_evav_require_for', 'site' ) ); ?>/>
			 <?php esc_html_e( 'Specific content', 'easy-age-verify' ); ?>
		</label>
	</fieldset>
<?php }


/**
 * Prints the "who to verify" settings field.
 *
 * @since 0.1
 */
function evav_settings_callback_always_verify_field() {
    $option = get_option( '_evav_always_verify' );
    $checked =  checked( 'disabled', $option , false );
    if( ! $option && $option !==  'disabled' )
    {$checked =  checked( 'disabled', $option , false );}
    ?>
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Enable Verification:', 'easy-age-verify' ); ?></span>
		</legend>
        <label>
			<input type="radio" name="_evav_always_verify" value="disabled" <?php echo $checked ?>/>
			 <?php esc_html_e( 'Disable verification to all visitors', 'easy-age-verify' ); ?>
		</label>
        <br />
        <label>
			<input type="radio" name="_evav_always_verify" value="admin-only" <?php checked( 'admin-only', get_option( '_evav_always_verify', true) ); ?>/>
			 <?php esc_html_e( '[TESTING MODE] Show only to logged-in Admins', 'easy-age-verify' ); ?>
		</label>
        <br />
		<label>
			<input type="radio" name="_evav_always_verify" value="guests" <?php checked( 'guests', get_option( '_evav_always_verify', true ) ); ?>/>
			 <?php esc_html_e( 'Show verification except to logged-in users', 'easy-age-verify' ); ?>
		</label>
		<br />
		<label>
			<input type="radio" name="_evav_always_verify" value="all" <?php checked( 'all', get_option( '_evav_always_verify', true ) ); ?>/>
			 <?php esc_html_e( 'Show verification to all visitors', 'easy-age-verify' ); ?>
		</label>
	</fieldset>
<?php }

/**
 * Prints the "adult type" settings field.
 *
 * @since 0.1
 */
function evav_settings_callback_adult_type_field() {
    $option = get_option( '_evav_adult_type' );
    $checked =  checked( 'disabled', $option , false );
    if( ! $option && $option !==  'disabled' )
    {$checked =  checked( 'disabled', $option , false );}
    ?>

	<fieldset id="_evav_adult_type_radio">
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Select verification type:', 'easy-age-verify' ); ?></span>
		</legend>
        <label>
			<input type="radio" name="_evav_adult_type" value="adult" <?php checked( 'adult', get_option( '_evav_adult_type', 'adult' ) ); ?>/>
			 <?php esc_html_e( 'Adults Only 18+', 'easy-age-verify' ); ?>
		</label>
		<?php
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/EAV_Standard_Adult-2022.jpg" class="thickbox">Standard Screenshot</a> | ';
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/EAV_Premium-Adult-2022.jpg" class="thickbox">Premium Screenshot</a>';
		?>
        <br />
		<label>
			<input type="radio" name="_evav_adult_type" value="alcohol" <?php checked( 'alcohol', get_option( '_evav_adult_type', 'alcohol' ) ); ?>/>
			 <?php esc_html_e( 'Alcohol', 'easy-age-verify' ); ?>
		</label>
		<?php
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/EAV_Standard_Alcohol-2022.jpg" class="thickbox">Standard Screenshot</a> | ';
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/EAV_Premium-Alcohol-2022.jpg" class="thickbox">Premium Screenshot</a>';
		?>
		<br />
		<label>
			<input type="radio" name="_evav_adult_type" value="vape" <?php checked( 'vape', get_option( '_evav_adult_type', 'vape' ) ); ?>/>
			 <?php esc_html_e( 'Vape', 'easy-age-verify' ); ?>
		</label>
		<?php
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/EAV_Standard_Vape-2022.jpg" class="thickbox">Standard Screenshot</a> | ';
				echo '<a href="https://5starplugins.com/wp-content/uploads/2022/08/EAV_Premium-Vape-2022.jpg" class="thickbox">Premium Screenshot</a>';
		?>
		<br />
	</fieldset>
<?php }

/**
 * Prints the minimum age settings field.
 *
 * @since 0.1
 */
function evav_settings_callback_minimum_age_field() { ?>

	<input name="_evav_minimum_age" type="number" id="_evav_minimum_age" step="1" min="10" class="small-text" value="<?php echo esc_attr( get_option( '_evav_minimum_age', '21' ) ); ?>" /> <?php esc_html_e( 'years old or older to view this site', 'easy-age-verify' ); ?>

<?php }

/**
 * Prints the cookie duration settings field.
 *
 * @since 0.1
 */
function evav_settings_callback_cookie_duration_field() { ?>

	<input name="_evav_cookie_duration" type="number" id="_evav_cookie_duration" step="15" min="15" class="small-text" value="<?php echo esc_attr( get_option( '_evav_cookie_duration', '720' ) ); ?>" /> <?php esc_html_e( 'minutes', 'easy-age-verify' ); ?>

<?php }

/**
 * Prints the membership settings field.
 *
 * @since 0.1
 */
function evav_settings_callback_membership_field() { ?>

	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Membership', 'easy-age-verify' ); ?></span>
		</legend>
		<label for="_evav_membership">
			<input name="_evav_membership" type="checkbox" id="_evav_membership" value="1" <?php checked( 1, get_option( '_evav_membership', 1 ) ); ?>/>
			 <?php esc_html_e( 'Require users to confirm their age before registering to this site', 'easy-age-verify' ); ?>
		</label>
	</fieldset>

<?php }

/**
 * Prints the modal heading settings field.
 *
 * @since 0.1
 */
function evav_settings_callback_heading_field() {
    $key = '_evav_heading';
    $text =  __("", 'easy-age-verify' );

    if( empty( get_option($key) ) ) {

	    switch(get_option( '_evav_adult_type')) {
	    case 'adult':
	        $text =  __("Please verify you are 18 years or older to enter.", 'easy-age-verify' );
		    break;
	    case 'alcohol':
	        $text =  __("Are you of legal drinking age 21 or older?", 'easy-age-verify' );
		    break;
	    case 'vape':
	        $text =  __("Are you of legal smoking age?", 'easy-age-verify' );
		    break;
	    default:
	        $text =  __("Please verify you are 18 years or older to enter.", 'easy-age-verify' );
		    break;
		}
	    update_option($key,$text);
    }

    $message = esc_attr( get_option( $key, $text) );
	?>
    <input name="_evav_heading" type="text" id="_evav_heading" maxlength="50"
           value="<?php echo $message; ?>"
           class="regular-text"/>
	<?php
}

/**
 * Prints the modal Disclaimer settings field.
 *
 * @since 0.1
 */
function evav_settings_callback_disclaimer_field() {
    $key = '_evav_disclaimer';
    $text =  __("", 'easy-age-verify' );

    if( empty( get_option($key) ) ) {

	    switch(get_option( '_evav_adult_type')) {
	    case 'adult':
	        $text =  __("WARNING ADULT CONTENT!\nThis website is intended for adults only and may contain content of an adult nature or age restricted, explicit material, which some viewers may find offensive. By entering you confirm that you are 18+ years and are not offended by viewing such material. If you are under the age of 18, if such material offends you or it is illegal to view in your location please exit now.", 'easy-age-verify' );
		    break;
	    case 'alcohol':
	        $text =  __("THE ALCOHOL PRODUCTS ON THIS WEBSITE ARE INTENDED FOR ADULTS ONLY.\nBy entering this website, you certify that you are of legal drinking age in the location in which you reside (age 21+ in the United States).", 'easy-age-verify' );
		    break;
	    case 'vape':
	        $text =  __("THE PRODUCTS ON THIS WEBSITE ARE INTENDED FOR ADULTS OF LEGAL SMOKING AGE.\nBy entering this website, you certify that you are of legal smoking age in the location in which you reside (age 18+, 19+ and 21+ in some areas).", 'easy-age-verify' );
		    break;
	    default:
	        $text =  __("WARNING ADULT CONTENT!\nThis website is intended for adults only and may contain content of an adult nature or age restricted, explicit material, which some viewers may find offensive. By entering you confirm that you are 18+ years and are not offended by viewing such material. If you are under the age of 18, if such material offends you or it is illegal to view in your location please exit now.", 'easy-age-verify' );
		    break;
		}
	    update_option($key,$text);
    }

    $message = esc_attr( get_option( $key, $text) );

    printf('<textarea name="%1$s" id="%1$s" maxlength="400" rows="6" class="regular-text"/>%2$s</textarea>',
        '_evav_disclaimer',
        $message
    );
}


/**
 * Outputs the "cache-buster AJAX" option settings field.
 *
 * @since 1.6
 */
function evav_settings_callback_ajax_check() {
    ?>
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Realtime Settings Check', 'easy-age-verify' ); ?></span>
		</legend>
		<label>
			<input type="checkbox" name="_evav_ajax_check" value="evav-ajax-check" <?php checked( 'evav-ajax-check', get_option( '_evav_ajax_check', true ) ); ?>/>
			 <?php esc_html_e( 'Confirm "Enable Verification" settings before showing popup', 'easy-age-verify' ); ?>
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
function evav_settings_callback_section_display() {

	//echo '<p>' . esc_html__( 'These settings change the look of your overlay. You can use <code>%s</code> to display the minimum age number from the setting above.', 'easy-age-verify' ) . '</p>';
}

/**
 * Prints the input type settings field.
 *
 * @since 0.1
 */
function evav_settings_callback_input_type_field() { ?>

	<select name="_evav_input_type" id="_evav_input_type">
		<option value="dropdowns" <?php selected( 'dropdowns', get_option( '_evav_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Date dropdowns', 'easy-age-verify' ); ?></option>
		<option value="inputs" <?php selected( 'inputs', get_option( '_evav_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Inputs', 'easy-age-verify' ); ?></option>
		<option value="checkbox" <?php selected( 'checkbox', get_option( '_evav_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Confirm checkbox', 'easy-age-verify' ); ?></option>
	</select>

<?php } ?>
