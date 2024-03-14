<?php
namespace WP_Reactions\Lite;

class Update {

	public static function init() {
		new self();
	}

	public function __construct() {
		$this->update();
	}

	function update()
	{
		global $wpdb;
		$db_version = get_option('wpra_lite_db_version', 1000);
		$plugin_version = get_option('wpra_lite_version', 1000);

		// ----------------------------- HANDLE DATABASE RELATED UPDATES ----------------------------------- //

		if (version_compare(2, $db_version, '>')) {
			Config::$current_options['emojis']['reaction7'] = 7;
			update_option( WPRA_LITE_OPTIONS, json_encode(Config::$current_options));
		}

		if ( version_compare( '1.3.0', $plugin_version, '>' ) ) {
			$react_tbl = "{$wpdb->prefix}wpreactions_reacted_users";
			if (!Helper::isColumnExist($react_tbl, 'source')) {
				$wpdb->query( "ALTER TABLE $react_tbl ADD source varchar(50) NOT NULL DEFAULT 'global'" );
			}
			if (!Helper::isColumnExist($react_tbl, 'emoji_id')) {
				$wpdb->query( "ALTER TABLE $react_tbl ADD emoji_id SMALLINT NOT NULL DEFAULT 0" );
			}
			if (!Helper::isColumnExist($react_tbl, 'user_id')) {
				$wpdb->query( "ALTER TABLE $react_tbl ADD user_id BIGINT(20) NOT NULL DEFAULT 0" );
			}

			// find all metas and update to reflect new data model
			$metas = $wpdb->get_results( "select post_id, meta_value from {$wpdb->prefix}postmeta where meta_key = '_wpra_start_counts'" );
			foreach ( $metas as $meta ) {
				if ( empty( $meta->meta_value ) ) continue;
				$fake_counts = maybe_unserialize( $meta->meta_value );
				if ( ! empty( $fake_counts ) ) {
					$new_data   = [];
					foreach ( Config::$current_options['emojis'] as $r => $emoji_id ) {
						$new_data[ $emoji_id ] = isset( $fake_counts[ $r ] ) ? $fake_counts[ $r ] : 0;
					}
					update_post_meta( $meta->post_id, '_wpra_start_counts', $new_data );
				}
			}

			// get all reacts and adapt to new data model
			$reacts = $wpdb->get_results("select id, reacted_to from $react_tbl");
			foreach ($reacts as $react) {
				$emoji_id = str_replace('reaction', '', $react->reacted_to);
				$wpdb->update(
					$react_tbl,
					['emoji_id' => $emoji_id, 'reacted_to' => 'emoji-' . $emoji_id],
					['id' => $react->id]
				);
			}

			Config::$current_options['emojis'] = [1,2,3,4,5,6,7];
			Config::$current_options['social_platforms']['telegram'] = 'false';
			Config::$current_options['social_labels']['telegram'] = 'Telegram';
			update_option( WPRA_LITE_OPTIONS, json_encode(Config::$current_options));
		}

		if ( version_compare( '1.3.6', $plugin_version, '>' ) ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}wpreactions_reacted_users ADD sgc_id BIGINT(20) NOT NULL DEFAULT 0" );
		}

        if ( version_compare( '1.3.7', $plugin_version, '>' ) ) {
            $wpdb->query( "ALTER TABLE {$wpdb->prefix}wpreactions_reacted_users DROP reacted_to" );
        }

		// update latest version and db versions
		update_option('wpra_lite_db_version', WPRA_LITE_DB_VERSION);
		update_option('wpra_lite_version', WPRA_LITE_VERSION);
	}

}