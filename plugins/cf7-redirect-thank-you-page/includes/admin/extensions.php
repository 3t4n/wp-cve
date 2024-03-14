<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// display other plugins page
function cf7rl_extensions_page()	{
	setlocale( LC_MONETARY, get_locale() );
	$extensions     = cf7rl_get_extensions();
	$extensions 	= array_reverse($extensions);
	$tags           = '<a><em><strong><blockquote><ul><ol><li><p>';
	$length         = 55;
	
	$table_output = "";
	
	$table_output .= "<div class='wrap about-wrap cf7rl-about-wrapp'>";
		$table_output .= "<h3>";
			$table_output .= __( 'You may be interested in our other popular Contact Form 7 plugins:', 'cf7rl' );
		$table_output .= "</h3>";
		
		$table_output .= "<div class='cf7rl-extension-wrapper grid3'>";
		
			foreach ( $extensions as $key => $extension ) :
				$the_excerpt = '';
				$slug        = $extension->info->slug;
				$price       = false;
				$link        = 'https://wpplugin.org/downloads/' . $slug .'/';
				$link        = esc_url( add_query_arg( array(
					'utm_source'   => 'plugin-extensions-page',
					'utm_medium'   => 'plugin',
					'utm_campaign' => 'cf7rl_extensions_page',
					'utm_content'  => $extension->info->title
				), $link ) );
				
				if ( ! empty( $extension->info->excerpt ) ) {
					$the_excerpt = $extension->info->excerpt;
				}
				
				$the_excerpt   = strip_shortcodes( strip_tags( stripslashes( $the_excerpt ), $tags ) );
				$the_excerpt   = preg_split( '/\b/', $the_excerpt, $length * 2+1 );
				$excerpt_waste = array_pop( $the_excerpt );
				$the_excerpt   = implode( $the_excerpt );
				
                $table_output .= "<article class='col'>";
                   $table_output .= " <div class='cf7rl-extension-item'>";
                        $table_output .= "<div class='cf7rl-extension-item-img'>";
                           $table_output .= " <a href='$link' target='_blank'><img src='"; $table_output .= $extension->info->thumbnail; $table_output .= "' /></a>";
                        $table_output .= "</div>";
                        $table_output .= "<div class='cf7rl-extension-item-desc'>";
                            $table_output .= "<p class='cf7rl-extension-item-heading'>"; $table_output .= $extension->info->title; $table_output .= "</p>";
                            $table_output .= "<div class='cf7rl-extension-item-excerpt'>";
                            	$table_output .= "<p>$the_excerpt;</p>";
                            $table_output .= "</div>";
                            $table_output .= "<div class='cf7rl-extension-buy-now'>";
								$table_output .= "<a href='$link;' class='button-primary' target='_blank'>"; ; $table_output .= __( 'Learn More', 'cf7rl' ); $table_output .= "</a>";
                            $table_output .= "</div>";
                        $table_output .= "</div>";
                    $table_output .= "</div>";
                $table_output .= "</article>";
			endforeach;
		$table_output .= "</div>";
	$table_output .= "</div>";
	
	return $table_output;

}


/* Retrieve the published extensions from wpplugin.org and store within transient. */
function cf7rl_get_extensions()	{
	$extensions = get_transient( '_cf7rl_extensions_feeda' );

	if ( false === $extensions || doing_action( 'cf7rl_daily_scheduled_events' ) ) {
		$route    = esc_url( 'https://wpplugin.org/edd-api/v2/products/?category=CF7' );
		$number   = 20;
		$endpoint = add_query_arg( array( 'number' => $number ), $route );
		$response = wp_remote_get( $endpoint );
		
		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$body    = wp_remote_retrieve_body( $response );
			$content = json_decode( $body );
			
			if ( is_object( $content ) && isset( $content->products ) ) {
				set_transient( '_cf7rl_extensions_feeda', $content->products, DAY_IN_SECONDS / 2 ); // Store for 24 hours
				$extensions = $content->products;
			}
		}
	}

	return $extensions;
}
add_action( 'cf7rl_daily_scheduled_events', 'cf7rl_get_extensions' );
