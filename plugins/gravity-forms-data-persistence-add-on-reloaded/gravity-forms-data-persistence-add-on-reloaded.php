<?php
/*
  Plugin Name: Gravity Forms Data Persistence Add-On Reloaded
  Plugin URI: http://asthait.com
  Description: This is a <a href="http://www.gravityforms.com/" target="_blank">Gravity Form</a> plugin. A big limitation with Gravity Form is, in case of big multipage forms, if you close or refresh the page during somewhere midle of some step. all the steps data will loose. this plugin solves that problem. This is an updated version of asthait's plugin.
  Author: Robert Iseley
  Version: 3.3.1
  Author URI: http://www.robertiseley.com
  Orginal Plugin by: asthait
 */

define( 'GFDPVERSION', '3.3.1' );

register_activation_hook( __FILE__, 'ri_gfdp_install' );
function ri_gfdp_install() {
	if(!get_option('gfdp_version')) {
		$users = get_users();
		$forms = RGFormsModel::get_forms();
		foreach($users as $user) {
			foreach($forms as $form) {
				$form = get_object_vars($form);
				if($entry = get_option(ri_getFormOptionKeyForGF($form, $user->data))) {
					set_transient(ri_gfdp_getFormTransientKeyForGF($form, $user->data), $entry);
					delete_option(ri_getFormOptionKeyForGF($form, $user->data));
				}


			}
		}
		update_option('gfdp_version', GFDPVERSION);
	} elseif (get_option('gfdp_version') < GFDPVERSION) {

		update_option('gfdp_version', GFDPVERSION);
	}
}

add_action( 'init', 'ri_gfdp_cookie' );
function ri_gfdp_cookie() {
	if ( isset( $_COOKIE['gfdp'] ) ) {
		setcookie( 'gfdp', $_COOKIE['gfdp'], time()+YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, false );
	} else {
		setcookie( 'gfdp', md5( time()/rand(100,1000) . $_SERVER['REMOTE_ADDR'] ), time()+YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, false );
	}
}

add_action( 'wp_head', 'ri_gfdp_version_head' );
function ri_gfdp_version_head() {
	echo '<!-- Gravity Forms Data Persistence Add-On Reloaded Version ' . GFDPVERSION . ' -->';
}

// Register garlic script for local persistence
add_action( 'wp_enqueue_scripts', 'ri_gfdp_script_register' );
function ri_gfdp_script_register() {
	wp_enqueue_script( 'jquery' );
}

// Render persistence data before form output
add_filter( "gform_pre_render", "ri_pre_populate_the_form", 99, 2 );
function ri_pre_populate_the_form( $form, $ajax ) {
	if ( gfdp_is_persistent( $form ) ) {
		$form['cssClass'] .= ' gfdp';
		$current_page = GFFormDisplay::get_current_page( $form["id"] );
		if ( $current_page == 1 ) {
			if ($data = get_transient( ri_gfdp_getFormTransientKeyForGF( $form ) ) ) {
				$_POST = json_decode( $data, true );
				foreach ( $form['fields'] as $field ) {
					if ( rgar( $field, "allowsPrepopulate" ) ) {
						if ( is_array( rgar( $field, "inputs" ) ) ) {
							foreach ( $field["inputs"] as $input ) {
								if ( ! empty( $_GET[ $input['name'] ] ) ) {
									$_POST[ 'input_' . str_replace( '.', '_', $input['id'] ) ] = $_GET[ $input['name'] ];
								}
							}
						} else {
							if ( ! empty( $_GET[ $field['inputName'] ] ) ) {
								$_POST[ 'input_' . $field['id'] ] = $_GET[ $field['inputName'] ];
							}
						}
					}
				}
			}
		}
	}

	return $form;
}

// Updating data via ajax auto save
add_action( 'wp_ajax_gfdp_save', 'ri_gfdp_ajax' );
function ri_gfdp_ajax() {
	global $wpdb; // this is how you get access to the database
	parse_str( $_POST['form'], $data );
	$form_id = isset( $data['gform_submit'] ) ? $data["gform_submit"] : 0;
	if ( $form_id ) {
		$form_info     = RGFormsModel::get_form( $form_id );
		$is_valid_form = $form_info && $form_info->is_active;
		if ( $is_valid_form ) {
			$form = GFFormsModel::get_form_meta( $form_id );
			$form = GFFormsModel::add_default_properties( $form );
			ri_gfdp_ajax_save( $form );
			echo "Saved";
		} else {
			echo "Invalid Form";
		}
	} else {
		echo "Invalid Form";
	}

	die(); // this is required to terminate immediately and return a proper response
}


//The js for ajax call
add_action( 'gform_enqueue_scripts', 'ri_gfdp_js_enqueue', 90, 2 );
function ri_gfdp_js_enqueue( $form, $is_ajax ) {
	if ( gfdp_is_persistent( $form ) ) {
		add_action( 'wp_print_footer_scripts', 'ri_gfdp_js', 1000 );
	}
}

function ri_gfdp_js() {
	$ajax_interval = apply_filters('gfdp_ajax_interval', 10000);
	?>
	<script type="text/javascript">
		var changed = false;

		function gfdp_events() {
			jQuery('form.gfdp').live('change keyup', function () {
				changed = true;
			})
		}

		jQuery(document).ready(gfdp_events);
		jQuery(document).ajaxComplete(gfdp_events);


		function gfdp_ajax($) {
			if (changed == true) {
				var data = {
					'action': 'gfdp_save',
					'form': jQuery('form.gfdp').serialize()
				};

				jQuery.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'POST',
					data: data,
					success: function (response) {
						changed = false;
					}
				})
			}
        };

		jQuery(document).ready(setInterval(gfdp_ajax, <?php echo $ajax_interval; ?>));
	</script> <?php
}

// Saving data from ajax call
function ri_gfdp_ajax_save( $form, $coming_from_page = '', $current_page = '' ) {
	if ( $form['ri_gfdp_persist'] == 'ajax' ) {
		$transient_key = ri_gfdp_getFormTransientKeyForGF( $form );
		parse_str( $_POST['form'], $data );
		$data = ri_gfdp_sanitize_data( $data, $form );
		set_transient( $transient_key, json_encode( $data ), ri_gfdp_getFormTransientExpiration($form) );
	}
}

function ri_gfdp_sanitize_data( $data, $form ) {
	foreach ( $form['fields'] as $field ) {
		if ( $field['ri_gfdp_no_persist'] ) {
			if ( is_array( $field['inputs'] ) ) {
				foreach ( $field['inputs'] as $input ) {
					$data[ 'input_' . str_replace( '.', '_', $input['id'] ) ] = '';
				}
			} else {
				$data[ 'input_' . $field['id'] ] = '';
			}
		}

	}

	return $data;
}

// Updating persistence data on page change
add_action( "gform_post_paging", "ri_page_changed", 10, 3 );
function ri_page_changed( $form, $coming_from_page, $current_page ) {

	if ( gfdp_is_persistent( $form ) ) {

		$transient_key = ri_gfdp_getFormTransientKeyForGF( $form );
		$data       = ri_gfdp_sanitize_data( $_POST, $form );
		set_transient( $transient_key, json_encode( $data ), ri_gfdp_getFormTransientExpiration($form) );
	}
}

// Updating or clearning persistence data on form submission
add_action( "gform_after_submission", "ri_set_post_content", 10, 2 );
function ri_set_post_content( $entry, $form ) {
	if ( gfdp_is_persistent( $form ) ) {
		//Update form data in wp_options table
		$transient_key = ri_gfdp_getFormTransientKeyForGF( $form );

		if ( $form['isEnablePersistentClear'] || $form['ri_gfdp_persist_clear'] ) {
			delete_transient( $transient_key );
		} else {
			$data = ri_gfdp_sanitize_data( $_POST, $form );
			set_transient( $transient_key, json_encode( $data ), ri_gfdp_getFormTransientExpiration($form) );
		}

		$entry_option_key = ri_getEntryOptionKeyForGF( $form );
		if ( get_option( $entry_option_key ) ) {
			//Delete old entry from GF tables
			if ( isset( $form['ri_gfdp_persist'] ) ) {

				if ( ! $form['ri_gfdp_multiple_entries'] ) {
					RGFormsModel::delete_lead( get_option( $entry_option_key ) );
				}
			} else {
				if ( ! $form['isEnableMulipleEntry'] ) {
					RGFormsModel::delete_lead( get_option( $entry_option_key ) );
				}
			}
		}

		//Update entry in wp_options table
		update_option( $entry_option_key, $entry['id'] );
	}
}

// Create and return option table key for a form and user
function ri_gfdp_getFormTransientKeyForGF( $form, $user = '' ) {
	global $current_user;
	get_currentuserinfo();

	if(!$user) {
		$user = $current_user;
	}
	if ( is_user_logged_in() ) {
		$transiet_key = 'gfdp_' . $form['id'] .'_'.$user->user_login;

		return $transiet_key;
	} else {
		if ( isset( $_COOKIE['gfdp'] ) ) {
			$transiet_key = 'gfdp_' . $form['id'] . '_' . $_COOKIE['gfdp'];

			return $transiet_key;
		}
	}
}

function ri_gfdp_getFormTransientExpiration($form) {
	if($form['ri_gfdp_persist_duration_int']) {
		$duration_int = intval(rgar($form, 'ri_gfdp_persist_duration_int'));
		$duration = intval(rgar($form, 'ri_gfdp_persist_duration'));
		$expiration =  $duration_int * $duration;
		if (is_int($expiration))
			return $expiration;
	}

	return 0;
}

// Create and return option table key for a form and user
function ri_getFormOptionKeyForGF( $form, $user = '' ) {
	global $current_user;
	get_currentuserinfo();

	if(!$user) {
		$user = $current_user;
	}

	if ( is_user_logged_in() ) {
		$option_key = $user->user_login . '_GF_' . $form['id'];

		return $option_key;
	} else {
		if ( isset( $_COOKIE['gfdp'] ) ) {
			$option_key = $_COOKIE['gfdp'] . '_GF_' . $form['id'];

			return $option_key;
		}
	}
}

// Create and return option table key for user form entry
function ri_getEntryOptionKeyForGF( $form, $user = '' ) {
	global $current_user;
	get_currentuserinfo();

	if(!$user) {
		$user = $current_user;
	}

	if ( is_user_logged_in() ) {
		$option_key = $user->user_login . '_GF_' . $form['id'] . '_entry';

		return $option_key;
	} else {
		if ( isset( $_COOKIE['gfdp'] ) ) {
			$option_key = $_COOKIE['gfdp'] . '_GF_' . $form['id'] . '_entry';

			return $option_key;
		}
	}
}

//Add persistent settings to the form settings
add_filter( "gform_form_settings", "ri_persistency_settings", 50, 2 );
function ri_persistency_settings( $form_settings, $form ) {

	// create settings on position 50 (right after Admin Label)
	$tr_persistent = '
        <tr>
            <td colspan="2"><h4 class="gf_settings_subgroup_title">Persistence</h4></td>
        </tr>
		<tr>
			<th>Persistence ' . gform_tooltip( 'ri_gfdp_persist', '', true ) . ' </th>
			<td>
				<select name="ri_gfdp_persist" id="ri_gfdp_persist">
					<option value="off" ' . selected( rgar( $form, "ri_gfdp_persist" ), 'off', false ) . '>Off</option>
					<option value="submit_only" ' . selected( rgar( $form, "ri_gfdp_persist" ), 'submit_only', false ) . '>Save data on page change/submit only</option>
					<option value="ajax" ' . selected( rgar( $form, "ri_gfdp_persist" ), 'ajax', false ) . '>Save data with ajax</option>
				</select>
			</td>
        </tr>';

	$tr_persistent .= '
        <tr>
        	<th>Persistence Duration ' . gform_tooltip( "ri_gfdp_persist_duration", '', true ) . ' </th>

			<td>
            <input type="text" name="ri_gfdp_persist_duration_int" id="ri_gfdp_persist_duration_int" value="' .  rgar( $form, "ri_gfdp_persist_duration_int" ) . '" />
            <select name="ri_gfdp_persist_duration" id="ri_gfdp_persist_duration">
					<option value="'.MINUTE_IN_SECONDS.'" ' . selected( rgar( $form, "ri_gfdp_persist_duration" ), MINUTE_IN_SECONDS, false ) . '>Minutes</option>
					<option value="'.HOUR_IN_SECONDS.'" ' . selected( rgar( $form, "ri_gfdp_persist_duration" ), HOUR_IN_SECONDS, false ) . '>Hours</option>
					<option value="'.DAY_IN_SECONDS.'" ' . selected( rgar( $form, "ri_gfdp_persist_duration" ), DAY_IN_SECONDS, false ) . '>Days</option>
					<option value="'.WEEK_IN_SECONDS.'" ' . selected( rgar( $form, "ri_gfdp_persist_duration" ), WEEK_IN_SECONDS, false ) . '>Weeks</option>
					<option value="'.YEAR_IN_SECONDS.'" ' . selected( rgar( $form, "ri_gfdp_persist_duration" ), YEAR_IN_SECONDS, false ) . '>Years</option>
				</select>
			</td>
        </tr>';

	$tr_persistent .= '
        <tr>
        	<th>Guest Persistence ' . gform_tooltip( "ri_gfdp_persist_guest", '', true ) . ' </th>

			<td>
            <input type="checkbox" name="ri_gfdp_persist_guest" id="ri_gfdp_persist_guest" ' . checked( rgar( $form, "ri_gfdp_persist_guest" ), '1', false ) . '" value="1" />
            <label for="ri_gfdp_persist_guest"> Enable persistency for guest users</label>
			</td>
        </tr>';

	$tr_persistent .= '
        <tr>
        	<th>Multiple Entries ' . gform_tooltip( "ri_gfdp_multiple_entries", '', true ) . ' </th>
        	<td>
            <input type="checkbox" name="ri_gfdp_multiple_entries" id="ri_gfdp_multiple_entries" ' . checked( rgar( $form, "ri_gfdp_multiple_entries" ), '1', false ) . '" value="1" />
            <label for="ri_gfdp_multiple_entries">Allow multiple entries</label>
			</td>
        </tr>';

	$tr_persistent .= '
        <tr>
        	<th>Clear Persistence ' . gform_tooltip( "ri_gfdp_clear_persist", '', true ) . ' </th>
        	
			<td>
            <input type="checkbox" name="ri_gfdp_persist_clear" id="ri_gfdp_persist_clear" ' . checked( rgar( $form, "ri_gfdp_persist_clear" ), '1', false ) . '" value="1" />
            <label for="ri_gfdp_persist_clear"> Clear persistence on submit</label>
			</td>
        </tr>';

	$tr_persistent .= '
        <tr>
        	<th>Purge Persistence Data ' . gform_tooltip( "ri_gfdp_purge_data", '', true ) . ' </th>

			<td>
	            <button id="purge_data" type="button">PURGE</button>
	            <script type="text/javascript">

					function gfdp_purge_ajax($) {

						if(confirm("Are you sure you want to delete all persistent data for this form?")) {
							var data = {
								"action": "gfdp_purge",
								"form_id": "' . $form['id'] . '"
							};

							jQuery.ajax({
								url: "' . admin_url( 'admin-ajax.php' ) . '",
								type: "POST",
								data: data,
								success: function (response) {
									alert("All data purged for this form");
								}
							})
						}
			        };
	                jQuery("#purge_data").click(gfdp_purge_ajax);
				</script>
			</td>
	    </tr>';

	$form_settings["Form Options"]['persistent'] = $tr_persistent;
	return $form_settings;
}

add_filter( 'gform_pre_form_settings_save', 'ri_gfdp_save_form_settings' );
function ri_gfdp_save_form_settings( $form ) {

	//Remove old setting names
	unset( $form['isPersistent'] );
	unset( $form['isEnableMulipleEntry'] );
	unset( $form['isEnablePersistentClear'] );

	//update settings
	$form['ri_gfdp_persist']                = rgpost( 'ri_gfdp_persist' );
	$form['ri_gfdp_persist_duration_int']   = rgpost( 'ri_gfdp_persist_duration_int' );
	$form['ri_gfdp_persist_duration']       = rgpost( 'ri_gfdp_persist_duration' );
	$form['ri_gfdp_multiple_entries']       = rgpost( 'ri_gfdp_multiple_entries' );
	$form['ri_gfdp_persist_clear']          = rgpost( 'ri_gfdp_persist_clear' );
	$form['ri_gfdp_persist_guest']          = rgpost( 'ri_gfdp_persist_guest' );

	return $form;

}

;

// Action to inject supporting script to the form editor page
add_action( "gform_advanced_settings", "ri_editor_script_persistency" );
function ri_editor_script_persistency() {
	?>
	<script type='text/javascript'>
		if (typeof form != 'undefined') {
			if (typeof form.isPersistent != 'undefined') {
				jQuery("#ri_gfdp_persist").val('submit_only');
			}
			if (typeof form.isEnableMulipleEntry != 'undefined') {
				jQuery("#ri_gfdp_multiple_entries").attr("checked", form.isEnableMulipleEntry);
			}
			if (typeof form.isEnablePersistentClear != 'undefined') {
				jQuery("#ri_gfdp_persist_clear").attr("checked", form.isEnablePersistentClear);
			}
		}
	</script>
	<?php
}

add_action( 'gform_field_advanced_settings', 'ri_gfdp_advanced_settings', 10, 2 );
function ri_gfdp_advanced_settings( $position, $form_id ) {
	if ( $position == 550 ) {
		?>
		<li class="field_ri_gfdp_no_persist_setting">
			<input type="checkbox" id="ri_gfdp_no_persist" name="ri_gfdp_no_persist"
			       onclick="SetFieldProperty('ri_gfdp_no_persist', this.checked);"/>
			<label for="ri_gfdp_no_persist" class="inline">
				<?php _e( 'Do not allow persistence', 'ri_gfdp' ); ?>
				<?php gform_tooltip( 'ri_gfdp_no_persist' ); ?>
			</label>
		</li>
		<?php
	}
}

//Action to inject supporting script to the form editor page
add_action( "gform_editor_js", "ri_gfdp_editor_script", 11 );
function ri_gfdp_editor_script() {
	?>
	<script type='text/javascript'>
		//adding setting to fields of type "text"
		//fieldSettings["text"] += ", .field_ri_gfdp_no_persist_setting";

		//binding to the load field settings event to initialize the checkbox
		jQuery(document).bind("gform_load_field_settings", function (event, field, form) {
			jQuery("#ri_gfdp_no_persist").attr("checked", field["ri_gfdp_no_persist"] == true);
		});
	</script>
	<?php
}

// Filter to add a new tooltip
add_filter( 'gform_tooltips', 'ri_add_persistency_tooltips' );
function ri_add_persistency_tooltips( $tooltips ) {
	$tooltips["ri_gfdp_persist"]                = "<h6>Persistency</h6>Select to save users progress with form so they may continue at another time.";
	$tooltips["ri_gfdp_persist_guest"] = "<h6>Guest Persitency</h6>Persist Data for users not logged into WordPress. This is done by an identifiier cookie.";
	$tooltips["ri_gfdp_multiple_entries"]       = "<h6>Multiple Entries Allowed</h6>This will allow multiple entry from same user. User can not edit their last and the previous entry not removed from the entry list";
	$tooltips['ri_gfdp_no_persist']             = '<h6>No Persist</h6>Checking this will removed this field(s) from the persistence data. User will have to re-enter information upon returning to the form. This does not affect the submission of an entry. Useful for sensitive information.';
	$tooltips['ri_gfdp_clear_persist']          = '<h6>Clear Persist</h6>This option will delete the persistence data when a form is submitted. Allow the user to return to a fresh blank form.';
	$tooltips['ri_gfdp_persist_duration']          = '<h6>Persistence Duration</h6>The duration you want the data to be available to the user. Leave blank for no limit.';
	$tooltips['ri_gfdp_purge_data']          = '<h6>Purge Data</h6>Clears all persistence data related to this form from the database';

	return $tooltips;
}

function gfdp_is_persistent( $form ) {
	if ( isset( $form['ri_gfdp_persist'] ) ) {
		if ( $form['ri_gfdp_persist'] == 'off' || empty( $form['ri_gfdp_persist'] ) ) {
			return false;
		}

		if ( ! is_user_logged_in() && ( ! isset( $form['ri_gfdp_persist_guest'] ) || empty( $form['ri_gfdp_persist_guest'] ) ) ) {
			return false;
		}

		return true;
	} else {
		// Check for old setting names and only allow for logged in users until they update settings to current names
		if ( isset( $form['isPersistent'] ) && ! empty( $form['isPersistent'] ) && is_user_logged_in() ) {
			return true;
		}
	}

	return false;
}


add_action( 'wp_ajax_gfdp_purge', 'ri_gfdp_purge_data' );
function ri_gfdp_purge_data() {
	$users = get_users();

	foreach($users as $user) {
		$form['id'] = $_POST['form_id'];
		delete_transient(ri_gfdp_getFormTransientKeyForGF($form, $user->data));

	}
	echo "purged";
	die();
}