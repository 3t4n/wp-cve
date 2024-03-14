<?php 
if(!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if(!function_exists('bbp_voting_hook_setting')) {
    function bbp_voting_hook_setting($value) {
        global $bbp_voting_hooks;
		global $bbp_voting_default_option_values;
        $setting = current_filter();
        $type = $bbp_voting_hooks[$setting];
        $setting_value = get_option( $setting, $bbp_voting_default_option_values[$setting] );
        if($type == 'bool') {
            return $setting_value === 'true' ? true : false;
        } else {
            if(!empty($setting_value)) return sanitize_text_field($setting_value);
            return $value;
        }
    }
}

if(!function_exists('bbp_voting_get_plugin_install_link')) {
	function bbp_voting_get_plugin_install_link( $plugin, $action = 'install-plugin' ) {
		return wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => $plugin
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$plugin
		);
	}
}
if(!function_exists('bbp_voting_get_plugin_activate_link')) {
	function bbp_voting_get_plugin_activate_link( $plugin, $action = 'activate' ) {
		if ( strpos( $plugin, '/' ) ) {
			$plugin = str_replace( '\/', '%2F', $plugin );
		}
		$url = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
		$_REQUEST['plugin'] = $plugin;
		$url = wp_nonce_url( $url, $action . '-plugin_' . $plugin );
		return $url;
	}
}


if(!function_exists('bbp_voting_field')) {
	function bbp_voting_field( $key, $name, $label = '', $description = '', $type = 'bool', $pro = false ) {
		$name_and_id = 'name="'. $key .'" id="'. $key .'"';
		$teaser = defined('BBPVOTINGPRO') ? false : $pro;
		$attributes = $teaser ? 'disabled' : $name_and_id;
		?>
		<tr valign="top">
			<th scope="row">
				<?php echo $name; ?>:
				<?php if($pro) { ?>
					<a href="https://wpforthewin.com/product/bbpress-voting-pro/" target="_blank"><span class="bbp-voting-pro-badge bbp-voting-pro-green">Pro</span></a>
				<?php } ?>
			</th>
			<td>
				<?php if($type == 'bool') { ?>
					<input type="checkbox" <?php echo $attributes; ?> value="true" <?php if(apply_filters($key, false) == 'true') echo 'checked'; ?> />
				<?php } elseif(is_array($type)) { ?>
					<select <?php echo $attributes; ?>>
						<?php foreach($type as $option) { ?>
							<option value="<?php echo $option; ?>"<?php if(apply_filters($key, false) == $option) echo ' selected'; ?>><?php echo ucwords(str_replace('-', ' ', $option)); ?></option>
						<?php } ?>
					</select>
				<?php } else { ?>
					<input type="<?php echo $type; ?>" <?php echo $attributes; ?> value="<?php echo esc_attr(apply_filters($key, false)); ?>" data-lpignore="true" />
				<?php } ?>
				<?php if(!empty($label)) { ?>
					<label for="<?php echo $key; ?>"><?php echo $label; ?></label>
				<?php } ?>
				<?php if(!empty($description)) { ?>
					<p class="description"><?php echo $description; ?></p>
				<?php } ?>
			</td>
		</tr>
		<?php 
	}
}

if(!function_exists('bbp_voting_get_current_post_type')) {
	function bbp_voting_get_current_post_type() {
		$this_post_id = bbp_get_reply_id() ?: bbp_get_topic_id();
		return get_post_type($this_post_id);
	}
}

if(!function_exists('bbp_voting_get_post_type_by_id')) {
	function bbp_voting_get_post_type_by_id($post_id) {
		$this_post_id = bbp_get_reply_id($post_id) ?: bbp_get_topic_id($post_id);
		return get_post_type($this_post_id);
	}
}

if(!function_exists('bbp_voting_parse_args')) {
	function bbp_voting_parse_args( $args, $defaults = array(), $filter_key = '' ) {

		// Setup a temporary array from $args
		if ( is_object( $args ) ) {
			$r = get_object_vars( $args );
		} elseif ( is_array( $args ) ) {
			$r =& $args;
		} else {
			$r = array();
			wp_parse_str( $args, $r );
		}

		// Passively filter the args before the parse
		if ( ! empty( $filter_key ) ) {
			$r = apply_filters( "bbp_voting_before_{$filter_key}_parse_args", $r, $args, $defaults );
		}

		// Parse
		if ( is_array( $defaults ) && ! empty( $defaults ) ) {
			$r = array_merge( $defaults, $r );
		}

		// Aggressively filter the args after the parse
		if ( ! empty( $filter_key ) ) {
			$r = apply_filters( "bbp_voting_after_{$filter_key}_parse_args", $r, $args, $defaults );
		}

		// Return the parsed results
		return $r;
	}
}