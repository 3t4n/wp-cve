<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/public
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
 */

/**
 * The ajax related functionality of the plugin.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/public
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 */
class Free_Comments_For_Wordpress_Vuukle_Ajax {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin all needed properties in one place
	 *
	 * @since  5.0
	 * @access protected
	 * @var    array $attributes The array containing main attributes of the plugin.
	 */
	protected $attributes;

	/**
	 * Main settings option name
	 *
	 * @since  5.0
	 * @access protected
	 * @var    string $settings_name Main settings option_name
	 */
	protected $settings_name;

	/**
	 * Main settings option name
	 *
	 * @since  5.0
	 * @access protected
	 * @var    string $settings_name Main settings option_name
	 */
	protected $app_id_setting_name;

	/**
	 * Property for storing Vuukle App Id
	 *
	 * @since  5.0
	 * @access protected
	 * @var    string $app_id App Id
	 */
	protected $app_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @var array $attributes The array containing main attributes of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $attributes ) {
		$this->attributes          = $attributes;
		$this->plugin_name         = $this->attributes['name'];
		$this->version             = $this->attributes['version'];
		$this->settings_name       = $this->attributes['settings_name'];
		$this->app_id_setting_name = $this->attributes['app_id_setting_name'];
	}

	/**
	 * Method saves comments to DB.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function saveCommentToDb() {
		$nonce = ! empty( $_POST['_wpnonce'] ) ? sanitize_key( $_POST['_wpnonce'] ) : '';
		if ( wp_verify_nonce( $nonce ) ) {
			global $wpdb;
			$comment_post_ID      = ! empty( $_POST['comment_post_ID'] ) ? (int) sanitize_text_field( $_POST['comment_post_ID'] ) : null;
			$comment_author       = ! empty( $_POST['comment_author'] ) ? sanitize_text_field( $_POST['comment_author'] ) : null;
			$comment_author_email = ! empty( $_POST['comment_author'] ) ? sanitize_email( $_POST['comment_author'] ) : null;
			$comment_author_url   = ! empty( $_POST['comment_author_url'] ) ? esc_url_raw( $_POST['comment_author_url'] ) : null;
			$comment_content      = ! empty( $_POST['comment_content'] ) ? wp_kses_post( $_POST['comment_content'] ) : null;
			$comment_type         = ! empty( $_POST['comment_type'] ) ? sanitize_text_field( $_POST['comment_type'] ) : null;
			$comment_parent_ID    = ! empty( $_POST['comment_parent_ID'] ) ? (int) sanitize_text_field( $_POST['comment_parent_ID'] ) : null;
			$user_id              = ! empty( $_POST['user_id'] ) ? (int) sanitize_text_field( $_POST['user_id'] ) : null;
			$comment_author_IP    = ! empty( $_POST['comment_author_IP'] ) ? rest_is_ip_address( $_POST['comment_author_IP'] ) : null;
			$comment_agent        = ! empty( $_POST['comment_agent'] ) ? sanitize_text_field( $_POST['comment_agent'] ) : null;
			$comment_date         = ! empty( $_POST['comment_date'] ) ? sanitize_text_field( $_POST['comment_date'] ) : null;
			$comment_approved     = ! empty( $_POST['comment_approved'] ) ? sanitize_text_field( $_POST['comment_approved'] ) : null;
			$comment_karma        = ! empty( $_POST['comment_karma'] ) ? (int) sanitize_text_field( $_POST['comment_karma'] ) : null;
			$data                 = array(
				'comment_post_ID'      => $comment_post_ID,
				'comment_author'       => $comment_author,
				'comment_author_email' => $comment_author_email,
				'comment_author_url'   => $comment_author_url,
				'comment_content'      => $comment_content,
				'comment_type'         => $comment_type,
				'comment_parent'       => $comment_parent_ID,
				'user_id'              => $user_id,
				'comment_author_IP'    => $comment_author_IP,
				'comment_agent'        => $comment_agent,
				'comment_date'         => date( 'Y-m-d H:i:s', strtotime( $comment_date ) ),
				'comment_date_gmt'     => gmdate( 'Y-m-d H:i:s', strtotime( $comment_date ) ),
				'comment_approved'     => $comment_approved,
				'comment_karma'        => $comment_karma,
			);
			$id                   = wp_insert_comment( $data );
			$comment_ID           = (int) $_POST['comment_ID'];
			//TODO check this , seems unknown thing, cause there are also comments_meta
			$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "comments SET comment_ID=%s WHERE comment_ID=%s", $comment_ID, $id ) );
			exit;
		}
	}


	/**
	 * This function ensures quick registration.
	 */
	public function quickRegister() {
		// Nonce check
		$nonce = ! empty( $_POST['_wpnonce'] ) ? sanitize_key( $_POST['_wpnonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce ) ) {
			wp_die();
		}
		$responseApiKey = Free_Comments_For_Wordpress_Vuukle_Helper::quickRegister( $this->app_id_setting_name, $this->attributes['log_dir'] );
		esc_html_e( $responseApiKey );
		wp_die();
	}

	/**
	 * Comments exporting from the admin general tab.
	 * This works per page, and generates xml file
	 *
	 */
	public function exportComments() {
		// Nonce check
		$nonce = ! empty( $_GET['_wpnonce'] ) ? sanitize_key( $_GET['_wpnonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce ) ) {
			die();
		}
		// Define vars
		global $wpdb;
		$amount_comments = (int) sanitize_text_field( $_GET['amount_comments'] );
		$offset          = (int) sanitize_text_field( $_GET['offset'] );
		$files_dir       = $this->attributes['upload_dir_path'] . 'files/';
		if ( ! is_dir( $files_dir ) ) {
			wp_mkdir_p( $files_dir );
		} elseif ( $offset == 0 ) {
			Free_Comments_For_Wordpress_Vuukle_Helper::cleanDir( $files_dir );
		}
		$limit        = $amount_comments;
		$offset_start = $offset * $limit;
		$query        = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "comments LIMIT %d, %d", $offset_start, $limit );
		$comments     = $wpdb->get_results( $query, OBJECT_K );
		if ( empty( $comments ) ) {
			if ( $offset == 0 ) {
				echo json_encode( array(
					'result'  => - 1,
					'link'    => '',
					'message' => 'Comments not found in database.'
				) );
				exit;
			}
			$time          = gmdate( 'Y-m-d' );
			$file_name     = get_bloginfo( 'name' ) . '-commment-export-' . $time . '.zip';
			$file_path_zip = $files_dir . $file_name;
			$zip           = new ZipArchive();
			$zip_name      = $file_path_zip;
			if ( $zip->open( $zip_name, ZIPARCHIVE::CREATE ) !== true ) {
				echo json_encode( array(
					'result'  => - 1,
					'link'    => '',
					'message' => 'Sorry ZIP creation failed at this time'
				) );
				exit;
			} else {
				// Add to zip
				for ( $i = 0; $i < $offset; $i ++ ) {
					$zip->addFile( $files_dir . $i . '_comments.xml', $i . '_comments.xml' );
				}
				$zip->close();
				// Remove single files
				// Duplicated loop, just because cant remove at same time, needed to be after close
				for ( $i = 0; $i < $offset; $i ++ ) {
					unlink( $files_dir . $i . '_comments.xml' );
				}
			}
			echo wp_json_encode( array(
				'result' => 0,
				'link'   => $this->attributes['upload_dir_url'] . 'files/' . $file_name
			) );
		} else {
			$xml = '<?xml version="1.0" encoding="UTF-8" ?>
            <rss version="2.0"
            xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
            xmlns:content="http://purl.org/rss/1.0/modules/content/"
            xmlns:wfw="http://wellformedweb.org/CommentAPI/"
            xmlns:dc="http://purl.org/dc/elements/1.1/"
            xmlns:wp="http://wordpress.org/export/1.2/">
            <channel>
            <wp:wxr_version>1.2</wp:wxr_version>';
			foreach ( $comments as $comment ) {
				$comment_post     = get_post( $comment->comment_post_ID );
				$author_id        = $comment_post->post_author;
				$author_post_name = get_the_author_meta( 'display_name', $author_id );
				$categories       = get_the_category( $comment_post->ID );
				$string_caegorty  = '';
				if ( ! empty( $categories ) ) {
					foreach ( $categories as $category ) {
						$string_caegorty .= '<category domain="post_tag" nicename="' . $category->name . '"><![CDATA[' . $category->name . ']]></category>';
					}
				}
				$date_comment = gmdate( 'Y-m-d', strtotime( $comment->comment_date ) ) . 'T' . gmdate( 'H:i:s', strtotime( $comment->comment_date ) );
				$xml          .= '<item>
                    <dc:creator><![CDATA[' . $author_post_name . ']]></dc:creator>
                    <wp:post_type><![CDATA[post]]></wp:post_type>
                    <wp:comment_status><![CDATA[open]]></wp:comment_status>
                    <wp:comment_date><![CDATA[' . $date_comment . ']]></wp:comment_date>
                    ' . $string_caegorty . '
                    <link><![CDATA[' . get_permalink( $comment->comment_post_ID ) . ']]></link>
                    <title><![CDATA[' . get_the_title( $comment->comment_post_ID ) . ']]></title>
                    <wp:post_id><![CDATA[' . $comment->comment_post_ID . ']]></wp:post_id>
                    <wp:comment>
                    <wp:comment_date><![CDATA[' . $date_comment . ']]></wp:comment_date>
                    <wp:comment_id><![CDATA[' . $comment->comment_ID . ']]></wp:comment_id>
                    <wp:comment_author><![CDATA[' . $comment->comment_author . ']]></wp:comment_author>
                    <wp:comment_content><![CDATA[' . $comment->comment_content . ']]></wp:comment_content>
                    <wp:comment_approved><![CDATA[' . $comment->comment_approved . ']]></wp:comment_approved>
					<wp:comment_parent><![CDATA[' . $comment->comment_parent . ']]></wp:comment_parent>
                    </wp:comment>
                    </item>';
			}
			$xml           .= '</channel>
            </rss>';
			$file_path_xml = $files_dir . $offset . '_comments.xml';
			file_put_contents( $file_path_xml, $xml );
			$offset ++;
			echo wp_json_encode( array( 'result' => $offset ) );
		}
		wp_die();
	}
}
