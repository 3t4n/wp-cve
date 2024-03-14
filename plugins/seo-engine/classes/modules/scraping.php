<?php

class Meow_Modules_SeoEngine_Scraping
{
    public static function scrap_bing_request( $search ){
		$language = get_option( 'kseo_kiss_options', null )[ 'seo_engine_language' ] ?? 'English';
		$url = "https://www.bing.com/search?q=" . $search;
		$response = wp_remote_get( $url );

		// Scraping
		$dom = new DOMDocument();
		@$dom->loadHTML( $response['body'] );
		$finder = new DomXPath( $dom );

		$titles = [];
		$nodes = $finder->query( "//li[@class='b_algo']//h2" );
		foreach ( $nodes as $node ) {
			$titles[] = $node->nodeValue;
		}

		$excerpts = [];
		$nodes = $finder->query( "//li[@class='b_algo']//div[@class='b_caption']" );
		foreach ( $nodes as $node ) {

			$spans = $node->getElementsByTagName('span');
			foreach ($spans as $span) {
				$span->parentNode->removeChild($span);
			}

			$excerpts[] = $node->nodeValue;
		}

		$slugs = [];
		$nodes = $finder->query( "//li[@class='b_algo']//cite" );
		foreach ( $nodes as $node ) {
			$slug = substr($node->nodeValue, strrpos($node->nodeValue, '/') + 1);
			$slug = str_replace( '...', '', $slug);
			$slugs[] = $slug;
		}

		global $mwai;
		if (is_null( $mwai ) || !isset( $mwai ) ) {
			return "Missing AI Engine.";
		}
		
		$prompt = sprintf( "Given the search term: \"%s\", the following are the top results on Bing. Titles : \"%s\". Excerpts : \"%s\". Slugs : \"%s\". Create only one of each new, SEO-friendly title, excerpt and slug based on the top results. Return a response in the following format: \"title: <title>, excerpt: <excerpt>, slug: <slug>\".", $search, implode( ', ', $titles ), implode( ', ', $excerpts ), implode( ', ', $slugs ) );
		$prompt = sprintf( '%s (Respond in "%s")', $prompt, $language );

		$ai_suggestion = $mwai->simpleTextQuery( $prompt, [ 'max_tokens' => 150 ] );

		return $ai_suggestion;
	}
}