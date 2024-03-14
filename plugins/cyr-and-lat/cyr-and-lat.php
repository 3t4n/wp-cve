<?php
/*
Plugin Name: Webcraftic Cyr to Lat reloaded
Plugin URI: https://wordpress.org/plugins/cyr-and-lat/
Description: Converts Cyrillic characters in post and term slugs to Latin characters. Useful for creating human-readable URLs. Allows to use both of cyrillic and latin slugs.
Author: Webcraftic
Author URI: http://webcraftic.com
Version: 1.2.0
*/

// Exit if accessed directly
defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

if ( defined( 'WCTLR_PLUGIN_ACTIVE' ) ) {
	return;
}

define( 'WCTLR_PLUGIN_ACTIVE', true );

define( 'WCTLR_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'WCTLR_PLUGIN_BASE', plugin_basename( __FILE__ ) );
define( 'WCTLR_PLUGIN_URL', plugins_url( null, __FILE__ ) );


class WCTLR_Plugin {
	
	protected $is_ru_segment = false;
	
	public function __construct() {
		require_once WCTLR_PLUGIN_DIR . '/includes/functions.php';
		require_once WCTLR_PLUGIN_DIR . '/includes/3rd-party.php';
		
		$this->is_ru_segment = in_array( get_locale(), array( 'ru_RU', 'bel', 'kk', 'uk', 'bg', 'bg_BG', 'ka_GE' ) );
		
		add_filter( 'plugin_row_meta', array( $this, 'setPluginMeta' ), 10, 2 );
		add_filter( 'sanitize_title', 'wbcr_ctlr_sanitize_title', 9 );
		add_filter( 'sanitize_file_name', 'wbcr_ctlr_sanitize_title' );
		add_filter( 'init', array( $this, 'init' ) );
	}
	
	public function init() {
		require_once WCTLR_PLUGIN_DIR . '/includes/admin-notices.php';
		WCTLR_Admin_Notices::instance( __FILE__ );
		
		if ( isset( $_GET['wctlr_convert_existing_slugs'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'convert_exising_slugs' ) ) {
				wp_die( 'Not enough permissions for you to perform this action.' );
			}
			
			$this->convertExistingSlugs();
			
			$url = remove_query_arg( array( 'wctlr_convert_existing_slugs', '_wpnonce' ) );
			$url = add_query_arg( 'wctlr_success_converted_slugs', 1, site_url( $url ) );
			
			wp_redirect( $url );
			die();
		}
		
		if ( isset( $_GET['wctlr_success_converted_slugs'] ) ) {
			add_action( 'admin_notices', array( $this, 'showSuccessContvertNotice' ) );
		}
	}
	
	public function showSuccessContvertNotice() {
		$close_url = remove_query_arg( array( 'wctlr_success_converted_slugs' ) );
		?>
        <div class="notice notice-success">
			<?php if ( $this->is_ru_segment ): ?>
                <p>Постоянные ссылки Ваших записей, страниц, рубрик, меток и других сущностей были успешно преобразованы
                    в латиницу! Вы можете <a href="<?= esc_url( $close_url ) ?>">закрыть уведомление</a>.</p>
			<?php else: ?>
                <p><?php printf( __( 'Permalinks to your posts, pages, cats, tags and other entities have been successfully converted to Latin! You can <a href="%s">close</a> the notification.', 'cyr-to-lat-reloaded' ), esc_url( $close_url ) ); ?></p>
			<?php endif; ?>
        </div>
		<?php
	}
	
	/**
	 * Link in plugin metadata
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function setPluginMeta( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			if ( $this->is_ru_segment ) {
				$links[] = '<a href="https://youtu.be/fNRWy-1aZmA" target="_blank">Видео инструкция</a>';
				$links[] = '<a href="https://forum.webcraftic.com" target="_blank">Служба поддержки</a>';
			} else {
				$links[] = '<a href="https://forum.webcraftic.com" target="_blank">' . __( 'Support', 'cyr-to-lat-reloaded' ) . '</a>';
			}
		}
		
		return $links;
	}
	
	/**
	 * Translates for old entries, headings, tags in which the slug has already been created,
	 * this method also provides the ability to rollback and compatibility with the Cyrlitera plugin.
	 *
	 * @since 1.1.1
	 * @return void
	 */
	public function convertExistingSlugs() {
		global $wpdb;
		
		$posts = $wpdb->get_results( "SELECT ID, post_name FROM {$wpdb->posts} WHERE post_name REGEXP('[^_A-Za-z0-9\-]+') AND post_status IN ('publish', 'future', 'private')" );
		
		foreach ( (array) $posts as $post ) {
			$sanitized_name = wbcr_ctlr_sanitize_title( urldecode( $post->post_name ) );
			
			if ( $post->post_name != $sanitized_name ) {
				add_post_meta( $post->ID, 'wbcr_wp_old_slug', $post->post_name );
				
				$wpdb->update( $wpdb->posts, array( 'post_name' => $sanitized_name ), array( 'ID' => $post->ID ), array( '%s' ), array( '%d' ) );
			}
		}
		
		$terms = $wpdb->get_results( "SELECT term_id, slug FROM {$wpdb->terms} WHERE slug REGEXP('[^_A-Za-z0-9\-]+') " );
		
		foreach ( (array) $terms as $term ) {
			$sanitized_slug = wbcr_ctlr_sanitize_title( urldecode( $term->slug ) );
			
			if ( $term->slug != $sanitized_slug ) {
				update_option( 'wbcr_wp_term_' . $term->term_id . '_old_slug', $term->slug, false );
				$wpdb->update( $wpdb->terms, array( 'slug' => $sanitized_slug ), array( 'term_id' => $term->term_id ), array( '%s' ), array( '%d' ) );
			}
		}
		
		// Plugin integration Advanced custom fields
		// Our integration uses the translation of already created slugs of forums and topics.
		wbcr_ctlr_conver_asgaros_forum_existing_slugs();
		
		// Plugin integration BuddyPress
		// Our integration makes use of the translation of already created slugs of groups.
		wbcr_ctlr_convert_buddypress_existings_slugs();
	}
	
}

new WCTLR_Plugin();
?>
