<?php
/**
 * Code for the FDP metaboxes.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Adds a box to the main column on the Posts, Pages and Portfolios edit screens.
 */
function eos_dp_add_meta_box() {
	if ( apply_filters( 'eos_dp_user_can_metabox', true ) ) {
		$post_types = get_post_types(
			array(
				'publicly_queryable' => true,
				'public'             => true,
			)
		);
		if ( isset( $post_types['attachment'] ) ) {
			unset( $post_types['attachment'] );
		}
		$screens = array_merge( array( 'page' ), $post_types );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'eos_dp_sectionid',
				esc_attr__( 'Freesoul Deactivate Plugins', 'freesoul-deactivate-plugins' ),
				'eos_dp_meta_box_callback',
				$screen,
				'normal',
				'default'
			);
		}
	}
}

add_action( 'add_meta_boxes', 'eos_dp_add_meta_box' );
// Add metabox to deactivate external plugins on specific pages.
function eos_dp_meta_box_callback( $post ) {
	$params = array(
		'post_id'    => $post->ID,
		'post_type'  => $post->post_type,
		'html_url'   => EOS_DP_PLUGIN_URL . '/inc/html/',
		'is_metabox' => 'true',
	);
	wp_enqueue_script( 'eos-dp-backend-single', EOS_DP_PLUGIN_URL . '/admin/assets/js/fdp-metaboxes-5.0.2.js', array( 'jquery' ) );
	wp_localize_script( 'eos-dp-backend-single', 'eos_dp_js', $params );
	wp_nonce_field( 'eos_dp_meta_boxes', 'eos_dp_meta_boxes_nonce' );
	wp_nonce_field( 'eos_dp_setts', 'eos_dp_setts' );
	$post_types_plugins  = eos_dp_get_option( 'eos_post_types_plugins' );
	$post_types_plugins  = is_array( $post_types_plugins ) && ! empty( $post_types_plugins ) ? $post_types_plugins : eos_dp_post_types_empty();
	$active_plugins      = eos_dp_active_plugins();
	$values_string       = get_post_meta( $post->ID, '_eos_deactive_plugins_key', true );
	$locked              = '';
	$single_settings_url = admin_url( 'admin.php?page=eos_dp_menu' );
	$post_types_url      = admin_url( 'admin.php?page=eos_dp_by_post_type' );
	$rtl                 = is_rtl() ? '-rtl' : '';
	eos_dp_link_style( 'fdp-metabox', EOS_DP_PLUGIN_URL . '/admin/assets/css/fdp-metabox' . $rtl . '.css', 'all' );
	if ( isset( $post->post_type ) ) {
		if ( isset( $post_types_plugins[ $post->post_type ] ) ) {
			$ptp        = $post_types_plugins[ $post->post_type ];
			$locked_ids = isset( $ptp[3] ) ? $ptp[3] : array();
			if ( in_array( $post->ID, $locked_ids ) ) {
				$locked = ' eos-post-locked';
			}
			$plugins_table = eos_dp_plugins_table();
			$arr           = $plugins_table[ $post->post_type ];
			if ( ! $values_string && isset( $ptp[2] ) && $ptp[2] == '1' ) {
				$values_string = $ptp[1];
			}
			if ( isset( $arr[0] ) && ! $arr[0] ) {
				$locked = ' eos-post-locked';
			}
		}
	}
	$values     = explode( ',', $values_string );
	$args       = array( 'fdp_post_id' => $post->ID );
	$post_check = get_site_transient( '_fdp_pro_post_nsg_' . $post->ID );
	if ( $post_check ) {
		?>
		<div id="eos-dp-post-check-error" class="notice notice-error eos-dp-mb-32">
			<h2>
			<?php
			echo wp_kses(
				$post_check,
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			);
			?>
			</h2>
		</div>
		<?php
	}
	$post_type = isset( $post->post_type ) ? $post->post_type : 'post';

	$urls_a         = apply_filters( 'fdp_url_front_options', eos_dp_get_option( 'eos_dp_by_url' ) );
	$from_url      = false;
	$query_pattern = '';
	if ( is_array( $urls_a ) && is_array( $urls_a ) && ! empty( $urls_a ) ) {
		foreach ( $urls_a as $url_a ) {
			if ( isset( $url_a['url'] ) && '' !== $url_a['url'] ) {
				$query_pattern = $url_a['url'];
				$url_a['url']   = str_replace( '[home]', get_home_url(), $url_a['url'] );
				foreach ( array( 'https://', 'http://', 'www.' ) as $search ) {
					$url_a['url'] = str_replace( $search, '', $url_a['url'] );
				}
				$pattern = '/' . str_replace( '/', '\/', str_replace( '*', '(.*)', str_replace( '**', '*', $url_a['url'] ) ) ) . '\s/';
				$pattern = '/' . str_replace( '/', '\/', str_replace( '*', '(.*)', str_replace( '**', '*', $url_a['url'] ) ) ) . '\s/i';
				$pattern = str_replace( '?', '\?', $pattern );
				$pattern = str_replace( '&', '\&', $pattern );
				preg_match( $pattern, get_the_permalink( $post->ID ) . ' ', $matches );
				if ( ! empty( $matches ) && count( $matches ) - 1 === substr_count( $pattern, '(.*)' ) ) {
					$values   = explode( ',', $url_a['plugins'] );
					$from_url = true;
					break;
				}
			}
		}
	}
	?>
	<div class="eos-dp-post-name-wrp right<?php echo esc_attr( $locked ); ?>" style="background:transparent">
		<?php if ( ! $from_url ) { ?>
		<span class="fdp-metabox-opts-pt">
			<a style="font-size:18px;text-decoration:none;color:inherit" href="<?php echo esc_url( add_query_arg( array( 'eos_dp_post_type' => $post->post_type ), admin_url( 'admin.php?page=eos_dp_by_post_type' ) ) ); ?>" target="_blank"><?php esc_html_e( 'Post Types', 'freesoul-deactivate-plugins' ); ?></a>
		</span> /
		<span class="fdp-metabox-opts-s">
			<a style="font-size:18px;text-decoration:none;color:inherit" href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'eos_dp_post_type' => $post_type,
						'eos_dp_post_in'   => $post->ID,
						'single_post'      => true,
					),
					admin_url( 'admin.php?page=eos_dp_menu' )
				)
			);
			?>
			" target="_blank"><?php esc_html_e( 'Singles', 'freesoul-deactivate-plugins' ); ?></a>
		</span>
		<span id="eos_dp_lock_post" name="eos_dp_lock_post" class="eos-dp-lock-post-wrp hover">
			<input class="eos-dp-lock-post" type="checkbox" />
		</span>
		<input type="hidden" id="eos_dp_single_locked" name="eos_dp_single_locked" value="<?php echo ' eos-post-locked' === $locked ? 'locked' : 'unlocked'; ?>" />
		<p class="fdp-single-inactive-msg right"><?php echo wp_kses_post( sprintf( __( 'Plugins deactivated based on the %1$sPost Types settings%2$s.', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( admin_url( 'admin.php?page=eos_dp_by_post_type' ) ) . '" target="_fdp_post_types">', '</a>' ) ); ?></p>
		<p class="fdp-single-active-msg right"><?php wp_kses_post( sprintf( __( 'Plugins deactivated based on the %1$sSingles settings%2$s.', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( admin_url( 'admin.php?page=eos_dp_menu' ) ) . '" target="_fdp_singles">', '</a>' ) ); ?></p>
		<?php } else { ?>
		<p class="fdp-single-active-msg right"><?php wp_kses_post( sprintf( __( 'Plugins deactivated based on the %1$sCustom URLs settings%2$s.', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( admin_url( 'admin.php?page=eos_dp_url&pattern=' . urlencode( $query_pattern ) ) ) . '" target="_fdp_urls">', '</a>' ) ); ?></p>
		<?php } ?>
	</div>
	<div  class="eos-dp-before-metabox-actions"><?php do_action( 'eos_dp_metabox_before_action_buttons' ); ?></div>
	<div class="eos-dp-actions" style="visibility:visible;position:static" data-post-id="<?php echo esc_attr( $post->ID ); ?>">
		<?php
		$themes_list = eos_dp_active_themes_list( false );
		if ( $themes_list ) {
			?>
		<a title="<?php esc_attr_e( 'Select a different Theme ONLY FOR PREVIEW', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-theme-sel" style="border:1px solid #253042 !important"><span class="dashicons dashicons-admin-appearance" style="color:#253042"></span><?php echo $themes_list; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied before returning the output of eos_dp_active_themes_list(). ?></a>
		<?php } ?>
		<a data-page_speed_insights="false" title="<?php esc_attr_e( 'Preview the page loading plugins according the settings you see here', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-preview" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( $args, get_permalink( $post->ID ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank"><span class="dashicons dashicons-search"></span>
		<a data-page_speed_insights="false" title="<?php esc_attr_e( 'Preview the page loading plugins and the theme according the settings you see here and disable JavaScript esecution', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-preview" oncontextmenu="return false;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array_merge( $args, array( 'js' => 'off' ) ), get_permalink( $post->ID ) ), 'eos_dp_preview', 'eos_dp_preview' ) ); ?>" target="_blank">
			<span class="dashicons dashicons-search">
				<span class="eos-dp-no-js">JS</span>
			</span>
		</a>
		<?php do_action( 'eos_dp_metabox_actions' ); ?>
	</div>
	<?php eos_dp_pro_version_notice( 'relative' ); ?>
	<div id="eos-dp-plugins-wrp" class="eos-dp-plugins-metabox eos-dp-plugins-wrp<?php echo esc_attr( $locked ); ?>" style="line-height:2;height:auto;margin-top:0">
		<div style="text-align:<?php echo is_rtl() ? 'left' : 'right'; ?>">
			<span style="display:inline-block;width:10px"></span>
			<span class="eos-dp-active-wrp"><input type="checkbox" /></span><span class="eos-dp-legend-txt"><?php esc_html_e( 'Plugin active', 'freesoul-deactivate-plugins' ); ?> </span>
			<span class="eos-dp-not-active-wrp"><input type="checkbox" checked/></span><span class="eos-dp-legend-txt"><?php esc_html_e( 'Plugin not active', 'freesoul-deactivate-plugins' ); ?></span>
			<input type="hidden" name="eos_dp_admin_meta[_eos_deactive_plugins_key]" id="eos_deactive_plugins" class="checkbox-result" value="<?php echo esc_attr( $values_string ); ?>"/>
		</div>
		<div class="eos-dp-separator-little"></div>
		<table id="fdp-metabox-singles" class="<?php echo ' eos-post-locked' === $locked || $from_url ? '' : 'eos-hidden'; ?>" style="<?php echo $from_url ? 'pointer-events:none;' : ''; ?>column-count:<?php echo esc_attr( max( 1, min( 3, absint( count( $active_plugins ) / 11 ) ) ) ); ?>;display:block;margin-top:32px">
		<?php
		$n = 1;
		foreach ( $active_plugins as $p ) {
			$plugin_name = strtoupper( str_replace( '-', ' ', dirname( $p ) ) );
			?>
			<tr id="eos-dp-plugin-name-<?php echo esc_attr( $n ); ?>" class="eos-theme-checkbox-div" style="margin-bottom:4px" data-path="<?php echo esc_attr( $p ); ?>">
				<td>
					<span class="<?php echo in_array( $p, $values, true ) ? 'eos-dp-not-active-wrp' : 'eos-dp-active-wrp'; ?>">
						<input class="eos-fdp-checkbox" type="checkbox" data-path="<?php echo esc_attr( $p ); ?>" value="<?php echo esc_attr( $p ); ?>"<?php echo in_array( $p, $values, true ) ? ' checked' : ''; ?> onclick="javascript:eos_dp_update_chk_wrp(jQuery(this),jQuery(this).is(':checked'));eos_dp_update_included_checks(this);"/>
					</span>
					<span class="eos-dp-name-th"><?php echo esc_html( $plugin_name ); ?></span>
				</td>
			</tr>
			<?php
			++$n;
		}
		?>
		</table>
		<?php
		if ( isset( $arr ) && isset( $arr[1] ) ) {
			$values = explode( ',', $arr[1] );
			?>
		<table id="fdp-metabox-post-types" class="<?php echo ' eos-post-locked' === $locked || $from_url ? 'eos-hidden' : ''; ?>" style="pointer-events:none;opacity:0.7;column-count:<?php echo esc_attr( max( 1, min( 3, absint( count( $active_plugins ) / 11 ) ) ) ); ?>;display:block;margin-top:32px">
			<?php
			$n = 1;
			foreach ( $active_plugins as $p ) {
				$plugin_name = strtoupper( str_replace( '-', ' ', dirname( $p ) ) );
				?>
			<tr style="margin-bottom:4px">
				<td>
					<span class="<?php echo in_array( $p, $values, true ) ? 'eos-dp-not-active-wrp' : 'eos-dp-active-wrp'; ?>">
						<input class="eos-fdp-checkbox" type="checkbox" data-path="<?php echo esc_attr( $p ); ?>" value="<?php echo esc_attr( $p ); ?>"<?php echo in_array( $p, $values ) ? ' checked' : ''; ?> />
					</span>
					<span><?php echo esc_html( $plugin_name ); ?></span>
				</td>
			</tr>
				<?php
				++$n;
			}
			?>
		</table>
		<?php } ?>




	</div>
	<?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved and object $post the post object.
 * @param object $post The post object.
 */
function eos_dp_save_meta_box_data( $post_id, $post ) {
	if ( ! isset( $_POST['eos_dp_admin_meta'] ) ) {
		return;
	}
	// * Merge user submitted options with fallback defaults.
	$data = wp_parse_args( $_POST['eos_dp_admin_meta'], array( '_eos_deactive_plugins_key' => '' ) ); //@codingStandardsIgnoreLine.
	// * Sanitize in the next cicle.
	foreach ( (array) $data as $key => $value ) {
		$data[ sanitize_key( $key ) ] = sanitize_text_field( $value );
	}
	if ( isset( $data['_eos_deactive_plugins_key'] ) ) {
		$home_url                           = get_option( 'home' );
		$data['_eos_deactive_plugins_key'] .= ',' . EOS_DP_PLUGIN_BASE_NAME;
		if( 'draft' === $post->post_status ) {
			$data['_eos_deactive_plugins_key_draft'] = $data['_eos_deactive_plugins_key'];
		}
		else{
			delete_post_meta( $post_id, '_eos_deactive_plugins_key_draft' );
		}
		$path                               = esc_attr( str_replace( $home_url, '', get_permalink( $post_id ) ) );
		eos_dp_update_url_options( $path, $post_id, sanitize_text_field( $data['_eos_deactive_plugins_key'] ), $post->post_type, sanitize_key( $post->post_status ) );
	}
	eos_dp_save_metaboxes( $data, 'eos_dp_meta_boxes', 'eos_dp_meta_boxes_nonce', $post, 'edit_post' );
}

add_action( 'save_post', 'eos_dp_save_meta_box_data', 10, 2 );
// Save metaboxes.
function eos_dp_save_metaboxes( array $data, $nonce_action, $nonce_name, $post, $capability ) {
	// * Verify the nonce.
	if ( ! isset( $_POST[ $nonce_name ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_name ] ) ), $nonce_action ) ) {
		return;
	}
	// * Don't try to save the data under autosave, ajax, or future post.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}
	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		return;
	}
	$post      = get_post( $post );
	$post_type = $post->post_type;
	// * Don't save if WP is creating a revision (same as DOING_AUTOSAVE?).
	if ( 'revision' === $post_type ) {
		return;
	}
	// * Check that the user is allowed to edit the post.
	if ( ! eos_dp_pro_can_metabox( current_user_can( $capability, $post->ID ) ) ) {
		return;
	}
	// * Cycle through $data, insert value or delete field.
	foreach ( (array) $data as $field => $value ) {
		// * Save $value, or delete if the $value is empty.
		if ( false !== $value ) {
			update_post_meta( $post->ID, $field, $value );
		}
	}
	if ( isset( $_POST['eos_dp_single_locked'] ) ) {
		$post_types_matrix    = eos_dp_get_updated_plugins_table();
		$post_types_matrix_pt = $post_types_matrix[ $post_type ];
		if ( 'locked' === $_POST['eos_dp_single_locked'] ) {
			$post_types_matrix_pt[3] = isset( $post_types_matrix_pt[3] ) ? array_unique( array_merge( $post_types_matrix_pt[3], array( $post->ID ) ) ) : array( $post->ID );
		} elseif ( 'unlocked' === $_POST['eos_dp_single_locked'] && isset( $post_types_matrix_pt[3] ) ) {
			$post_types_matrix_pt[3] = array_unique( array_diff( $post_types_matrix_pt[3], array( $post->ID ) ) );
		}
		$post_types_matrix[ $post_type ] = $post_types_matrix_pt;
		eos_dp_update_option( 'eos_post_types_plugins', $post_types_matrix );
	}
}
