<?php
/**
 * Duplicate Post and Delete Revisions
 * Added security layer not existant in original source
 * afterwards the plugin was removed exactly for that
 *
 * @package AdminTweaks
 * 
 * @author  GD Press Tools (gdragon)
 * http://wordpress.org/plugins/gd-press-tools/
 * 
 */

namespace ADTW;

class HooksListingDupesAndRevs {
	static private $class = null;

	public static function init() {
		if( null === self::$class )
			self :: $class = new self;

		return self :: $class;
	}


	public function __construct() {
            
		$this->check_action_request();
		add_filter( 
            'post_row_actions', 
            [$this, 'post_row_actions'], 
            10, 2 
        );
		add_filter( 
            'page_row_actions', 
            [$this, 'post_row_actions'], 
            10, 2 
        );
	}


    /**
     * Verifies call for action
     * @return void
     */
	private function check_action_request() {
		if( !isset( $_GET["mtt-dups-revs"] ) )
			return;
        check_admin_referer( 'mtt_nonce' );
        
		if( !empty( $_GET["mtt-dups-revs"] ) ) {
            $post_id = intval( $_GET["pid"] );
			switch( $_GET["mtt-dups-revs"] ) {
				case "delrev":
					$counter = $this->delete_revisions( $post_id );
					wp_redirect(
							remove_query_arg(
									array( 'pid', 'mtt-dups-revs', '_wpnonce' ), 
									stripslashes( $_SERVER['REQUEST_URI'] )
							)
					);
					exit();
                break;
				case "duplicate":
					$new_id  = $this->duplicate_post( $post_id );
					if( $new_id > 0 )
						wp_redirect( sprintf(
										"post.php?action=edit&post=%s", 
										$new_id
								) );
					else
						wp_redirect(
								remove_query_arg(
										array( 'pid', 'mtt-dups-revs', '_wpnonce' ), 
										stripslashes( $_SERVER['REQUEST_URI'] )
								)
						);
					exit();
                break;
			}
		}
	}


	private function duplicate_post( $old_id ) {
		global $wpdb, $table_prefix;

		$old_post = $wpdb->get_row( $wpdb->prepare(
				"SELECT * FROM $wpdb->posts WHERE ID = %s", 
				$old_id
		) );

		$old_meta = $wpdb->get_results( $wpdb->prepare( 
				"SELECT * FROM $wpdb->postmeta WHERE post_id = %s", 
				$old_id 
		) );

		$old_term = $wpdb->get_results( $wpdb->prepare( 
				"SELECT * FROM $wpdb->term_relationships WHERE object_id = %s", 
				$old_id 
				) );

		$post_date     = current_time( 'mysql' );
		$post_date_gmt = get_gmt_from_date( $post_date );

		unset( $old_post->ID );
		unset( $old_post->filter );
		unset( $old_post->guid );
		unset( $old_post->post_date_gmt );

		$old_post->post_date = $post_date;
		$old_post->post_status = "draft";
		$old_post->post_title .= " (1)";
		$old_post->post_name .= "-1";
		$old_post->post_modified = $post_date;
		$old_post->post_modified_gmt = $post_date_gmt;
		$old_post->comment_count = "0";

		if( false === $wpdb->insert( $wpdb->posts, get_object_vars( $old_post ) ) )
			return 0;

		$post_ID = (int) $wpdb->insert_id;

		$wpdb->update( $wpdb->posts, 
            array( 'guid' => get_permalink( $post_ID ) ), 
            array( 'ID' => $post_ID ) 
        );

		foreach( $old_meta as $m ) {
			unset( $m->meta_id );
			$m->post_id = $post_ID;
			$wpdb->insert( $wpdb->postmeta, get_object_vars( $m ) );
		}

		foreach( $old_term as $m ) {
			unset( $m->meta_id );
			$m->object_id = $post_ID;
			$wpdb->insert( $wpdb->term_relationships, get_object_vars( $m ) );
		}

		return $post_ID;
	}


	private function delete_revisions( $post_id ) {
		global $wpdb, $table_prefix;

		$wpdb->query( $wpdb->prepare( 
				"DELETE p, t, m FROM $wpdb->posts p 
					LEFT JOIN $wpdb->term_relationships t ON t.object_id = p.ID 
					LEFT JOIN $wpdb->postmeta m ON m.post_id = p.ID 
					WHERE p.post_type = 'revision' 
					AND p.post_parent = %s", 
				$post_id 
		) );
		return $wpdb->rows_affected;
	}


	private function count_revisions( $post_id ) {
		global $wpdb, $table_prefix;
		return $wpdb->get_var( $wpdb->prepare(
				"SELECT count(*) AS revisions 
					FROM $wpdb->posts WHERE post_type = 'revision' 
					AND post_parent = %s", 
				$post_id
		) );
	}


	public function post_row_actions( $actions, $post ) {
		$url = add_query_arg( "pid", $post->ID, stripslashes( $_SERVER['REQUEST_URI'] ) );
        $url_dups = add_query_arg( "mtt-dups-revs", "duplicate", $url );
        $url_revs = add_query_arg( "mtt-dups-revs", "delrev", $url );
        $secure_dups = wp_nonce_url( $url_dups, 'mtt_nonce' );
        $secure_revs = wp_nonce_url( $url_revs, 'mtt_nonce' );

		$actions["duplicate"] = sprintf(
				'<a style="color: #00008b" href="%s" title="%s">%s</a>', 
				$secure_dups, 
				__( "Duplicate", "mtt" ), 
				__( "Duplicate", "mtt" )
		);
		$counter              = $this->count_revisions( $post->ID );
		$sure_msg             = sprintf(
				__( "Are you sure you want to delete the REVISIONS of the post %s?", "mtt" ), 
				$post->post_title
		);
		if( $counter > 0 )
			$actions["revisions"] = sprintf(
					'<a style="color: #cc0000" onclick="if (confirm(\'%s\')) { return true; } return false;" href="%s" title="%s">%s (%s)</a>', 
					__( "Confirm delete of revisions?", "mtt" ), 
					$secure_revs, 
					__( "Delete Revisions", "mtt" ), 
					__( "Delete Revisions", "mtt" ), 
					$counter
			);
		return $actions;
	}

}