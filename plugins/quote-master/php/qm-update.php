<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Checks To See If Update Is Required. If So, Updates The Plugin's Backend
 *
 * @since 7.0.0
 * @return void
 */
function qm_update()
{
	global $quote_master;
	$data = $quote_master->version;
	if ( ! get_option('mlw_quotes_version'))
	{
		add_option('mlw_quotes_version' , $data);
	}
	elseif (get_option('mlw_quotes_version') != $data)
	{
		// unhook this function so it doesn't loop infinitely
		remove_action( 'save_post', 'qm_post_quote_save', 10, 3 );

		global $wpdb;
		$table_name = $wpdb->prefix . "mlw_quotes_cate";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name)
		{
			$all_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE deleted=0 ORDER BY category_id DESC" );
			foreach($all_data as $category)
			{
				$results = wp_insert_term(
					$category->category,
				  'quote_category'
				);
				if( is_wp_error( $results ) ) {
					error_log($results->get_error_message());
				}
				else
				{
					error_log("All Clear for creating taxonomy!");
				}
			}
			$results = $wpdb->query( "DROP TABLE IF EXISTS ".$table_name );
		}

		$table_name = $wpdb->prefix . "mlw_quotes";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name)
		{
			global $current_user;
			get_currentuserinfo();
			$all_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE deleted=0 ORDER BY quote_id DESC" );
			foreach($all_data as $quote)
			{
  			$args = array(
  			  'post_title'    => $quote->quote,
  			  'post_content'  => $quote->quote,
  			  'post_status'   => 'publish',
  			  'post_author'   => $current_user->ID,
  			  'post_type' => 'quote'
  			);
  			$quote_id = wp_insert_post( $args );
  			add_post_meta( $quote_id, 'quote_author', $quote->author, true );
				add_post_meta( $quote_id, 'source', $quote->source, true );
				$term = get_term_by('name', $quote->category, 'quote_category');
				if (!$term)
				{
					error_log("No term found");
				}
				$results = wp_set_object_terms( $quote_id, $term->slug, 'quote_category' );
				if( is_wp_error( $results ) ) {
					error_log($results->get_error_message());
				}
				else
				{
					error_log("All Clear for assigning taxonomy!");
				}
			}
			$results = $wpdb->query( "DROP TABLE IF EXISTS ".$table_name );
		}

		// re-hook this function
		add_action( 'save_post', 'qm_post_quote_save', 10, 3);

		update_option('mlw_quotes_version' , $data);
		if(!isset($_GET['activate-multi']))
    {
			wp_safe_redirect( admin_url( 'index.php?page=qm_about' ) );
			exit;
    }
	}
	if ( ! get_option('mlw_advert_shows'))
	{
		add_option('mlw_advert_shows' , 'true');
	}
}
?>
