<?php
//if uninstall not called from WordPress, then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	exit();
}

global $wpdb;

$table_name = $wpdb->prefix . "mlw_quotes";
$results = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

$table_name = $wpdb->prefix . "mlw_quotes_cate";
$results = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

delete_option('mlw_quotes_version');
delete_option('mlw_advert_shows');

$my_query = new WP_Query( array('post_type' => 'quote') );
if( $my_query->have_posts() )
{
	while( $my_query->have_posts() )
	{
		$my_query->the_post();
		wp_delete_post( get_the_ID(), true);
	}
}
?>
