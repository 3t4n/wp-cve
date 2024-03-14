<?php

class Meow_Modules_SeoEngine_Ai_Suggestion
{
    public static function prompt($post, $field, $customContent = null, $max_tokens = 150, $meta_key_seo_title = '_kiss_seo_title', $meta_key_seo_excerpt = '_kiss_seo_excerpt' ){

		$language = get_option( 'seo_kiss_options', null )[ 'seo_engine_language' ] ?? 'English';
		$ai_keywords = get_option( 'seo_kiss_options', null )[ 'seo_engine_ai_keywords' ] ?? false;

		$originalContent = $customContent?? Meow_Modules_SeoEngine_Ai_Suggestion::get_post_sample_context( $post );
		$prompt = "";
		$max_tokens = $max_tokens;
		$expected_size = 0;

		switch ($field){
			//EDIT FIELDS
			case 'title':
				$prompt = sprintf( "Given the original title: \"%s\" and a sample from the post content : \"%s\", formulate a new, captivating, SEO-optimized title.", $post->post_title, $originalContent );
				$expected_size = 80;
				break;
			case 'excerpt':
				$prompt = sprintf( "Given the original excerpt: \"%s\" and a sample from the post content : \"%s\", write a concise (2 sentences), engaging and SEO-friendly excerpt.", $post->post_excerpt, $originalContent );
				$expected_size = 200;
				break;
			case 'seo_title':
				$prompt = sprintf( "For the website \"%s\". Given the original SEO title: \"%s\" and a sample from the post content : \"%s\", create a new, SEO-optimized title that will attract more clicks. The format should be \"<short_title_keywords> | <site name>\". Ideally between 40 and 50 characters. It should be absolutely CONCISE and LESS than 70 characters.", get_bloginfo( 'name' ), get_post_meta( $post->ID, $meta_key_seo_title, true ), $originalContent );
				$expected_size = 60;
				break;
			case 'seo_excerpt':
				$prompt = sprintf( "For the website \"%s\". Given the original SEO excerpt: \"%s\" and a sample from the post content : \"%s\", write a new, SEO-friendly excerpt that will entice readers to click. The most important keywords should be at the beginning of the excerpt. Ideally between 80 and 100 characters. It should be absolutely CONCISE and LESS than 120 characters.", get_bloginfo( 'name' ), get_post_meta( $post->ID, $meta_key_seo_excerpt, true ) , $originalContent );
				$expected_size = 100;
				break;
			case 'slug':
				$prompt = sprintf( "Given the original slug: \"%s\" and a sample from the post content : \"%s\". Create a new, SEO-friendly 4 words slug that is less than 64 chars in the format <keyword_1>-<keyword_2>-<keyword_3>-<keyword_4>", $post->post_name, $originalContent );
				$expected_size = 60;
				break;
			
			//MAGIC FIXES
			case 'magic_fix_post_not_updated':
				$prompt = sprintf( "Given the original title: \"%s\" and a sample from the post content : \"%s\", and today's date : \"%s\", create a new, SEO-friendly intro paragraph that will update the post's publish date. The format should be \"<intro_paragraph> <today's date>\".", $post->post_title, $originalContent, date("F j, Y") );
				break;
			case 'magic_fix_post_too_short':
				$prompt = sprintf( "Given the original title: \"%s\" and the post content : \"%s\", create a new, SEO-friendly paragraph that will add more content to the post.", $post->post_title, $originalContent );
				break;
			case 'magic_fix_images_missing_alt_text':
				$prompt = sprintf( "Given the original title: \"%s\" and paragraphe above the image : \"%s\", create a new, SEO-friendly description that will add alt text to the image. (one sentence with 5 to 10 words max). New alt text could be :", $post->post_title, $originalContent );
				break;
			case 'magic_fix_links_missing':
				$prompt = sprintf( "Given the original title: \"%s\" and a sample from the post content : \"%s\", create a new, SEO-friendly paragraph \"You might be interested in\" that will add a few external embedded links to wikipedia articles that are related. The format should be \"Speaking of [keyword], you might be interested in  <a href=\"[https_link_to_article]\" target=\"_blank\">[wikipedia_article]</a>\".", $post->post_title, $originalContent );
				break;
		}

		if ( $ai_keywords ) {
			$keywords = get_post_meta( $post->ID, '_seo_engine_ai_keywords', true );
			if ( !empty( $keywords ) && is_array( $keywords ) ) {
				$prompt = sprintf( '%s. The user wants this keywords to be what guides your suggestion : %s.', $prompt, implode(', ', $keywords) );
			}
		}

		$prompt = sprintf( '%s (Respond in "%s")', $prompt, $language );

		global $mwai;
		if (is_null( $mwai ) || !isset( $mwai ) ) {
			error_log( "[AI Suggestion] Missing AI Engine." );
			return false;
		}

		$ai_suggestion = $mwai->simpleTextQuery( $prompt, [ 'max_tokens' => $max_tokens] );
		$ai_suggestion = Meow_Modules_SeoEngine_Ai_Suggestion::verify_ai_suggestion( $ai_suggestion, $expected_size, $language );

		return $ai_suggestion;
	}

	public static function verify_ai_suggestion( $ai_suggestion, $expected_size, $language ) {

		$is_auto_correct_enabled = get_option( 'seo_kiss_options', null )[ 'seo_engine_ai_auto_correct' ] ?? false;

		if ( strlen( $ai_suggestion ) > $expected_size && $expected_size > 0 && $is_auto_correct_enabled) {
			error_log( "[AI Suggestion] AI Suggestion too long." );

			$prompt = sprintf('In %s, simplistically paraphrase the following "%s" (currently a %d characters string) into a string with less than %d characters, and ensure that its essential concepts and format are retained. Only the reduced form is required. Shortened string is: ', $language, $ai_suggestion, strlen($ai_suggestion), $expected_size);
			
			global $mwai;
			$ai_suggestion = $mwai->simpleTextQuery( $prompt, [ 'max_tokens' => 150] );
		}
		
		return $ai_suggestion;
	}

    public static function get_post_sample_context( $post ){

		$content = strip_tags( $post->post_content );
		$words = str_word_count( $content, 1 );
		$words_count = count( $words );

		// If the post is less than 300 words, get the whole content.
		if ( $words_count < 300 ) {
			return $content;
		}

		// Get a total of 300 words from the post, 100 from the beginning, 100 from the middle and 100 from the end.
		$words_per_section = 100;
		$words_per_section = $words_per_section > $words_count ? $words_count : $words_per_section;
		$words_per_section = $words_per_section < 50 ? 50 : $words_per_section;

		$words_beginning = array_slice( $words, 0, $words_per_section );
		$words_middle = array_slice( $words, floor( $words_count / 2 ) - floor( $words_per_section / 2 ), $words_per_section );
		$words_end = array_slice( $words, $words_count - $words_per_section, $words_per_section );

		$context = sprintf(
			"Beginning of content : \"%s\". Middle of content : \"%s\". End of content : \"%s\".",
			implode( ' ', $words_beginning ),
			implode( ' ', $words_middle ),
			implode( ' ', $words_end )
		);

		return $context;
	}
}