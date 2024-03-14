<?php

class Meow_Modules_SeoEngine_Readability
{

	function getVowels( $language ) {
		$vowels_array = [
			'english' => [ 'a', 'e', 'i', 'o', 'u', 'y' ],
			'french' => [ 'a', 'e', 'i', 'o', 'u', 'y', 'â', 'ê', 'î', 'ô', 'û', 'à', 'è', 'ù', 'ë', 'ï', 'ü', 'ÿ', 'æ', 'œ' ],
			'spanish' => [ 'a', 'e', 'i', 'o', 'u', 'á', 'é', 'í', 'ó', 'ú', 'ü' ],
			'german' => [ 'a', 'e', 'i', 'o', 'u', 'ä', 'ö', 'ü' ],
			'italian' => ['a', 'e', 'i', 'o', 'u', 'à', 'è', 'ì', 'ò', 'ù'],
			'portuguese' => ['a', 'e', 'i', 'o', 'u', 'á', 'é', 'í', 'ó', 'ú'],
			'turkish' => ['a', 'e', 'ı', 'i', 'o', 'ö', 'u', 'ü'],
			'swedish' => ['a', 'e', 'i', 'o', 'u', 'å', 'ä', 'ö'],

			'hindi' => ['अ', 'आ', 'इ', 'ई', 'उ', 'ऊ', 'ऋ', 'ए', 'ऐ', 'ओ', 'औ'],
			'japanese' => ['あ', 'い', 'う', 'え', 'お', 'か', 'き', 'く', 'け', 'こ', 'さ', 'し', 'す', 'せ', 'そ', 'た', 'ち', 'つ', 'て', 'と', 'な', 'に', 'ぬ', 'ね', 'の', 'は', 'ひ', 'ふ', 'へ', 'ほ', 'ま', 'み', 'む', 'め', 'も', 'や', 'ゆ', 'よ', 'ら', 'り', 'る', 'れ', 'ろ', 'わ', 'を', 'ん'],
		];

		$vowels = array_key_exists( strtolower( $language ), $vowels_array ) ? $vowels_array[ strtolower( $language ) ] : $vowels_array[ 'english' ];
	
		return $vowels;
	}

	function getWordTailByLanguage( $language ) {
		$word_tail_array = [
			'english' => [ 'es', 'ed', 'le' ],
			'french' => [ 'es', 'é', 'ée', 'ées', 'és', 'è', 'èe', 'èes', 'ès', 'er', 'ers', 'ez', 'ée', 'ées', 'és', 'è', 'èe', 'èes', 'ès', 'er', 'ers', 'ez', 'é', 'ée', 'ées', 'és', 'è', 'èe', 'èes', 'ès', 'er', 'ers', 'ez', 'é', 'ée', 'ées', 'és', 'è', 'èe', 'èes', 'ès', 'er', 'ers', 'ez', 'é', 'ée', 'ées', 'és', 'è', 'èe', 'èes', 'ès', 'er', 'ers', 'ez', 'é', 'ée', 'ées', 'és', 'è', 'èe', 'èes', 'ès', 'er', 'ers', 'ez', 'é', 'ée', 'ées', 'és', 'è', 'èe', 'èes', 'ès', 'er', 'ers', 'ez', 'é', 'ée', 'ées', 'és', 'è', 'èe', 'èes', 'ès', 'er', 'ers', 'ez' ],
			'spanish' => [ 'ar', 'er', 'ir' ],
			'german' => [ 'en', 'ung', 'heit', 'keit', 'schaft', 'ung', 'ung', 'lich' ],
			'italian' => [ 'are', 'ere', 'ire', 'zione', 'zione', 'zione' ],
			'portuguese' => [ 'ar', 'er', 'ir', 'ção', 'ção', 'ção' ],
			'turkish' => [ 'mak', 'mek', 'tir', 'tır', 'dir', 'dır', 'ç', 'ce', 'ceğiz', 'ceksiniz' ],
			'swedish' => [ 'ar', 'er', 'or', 'ning', 'ning', 'het' ],
			'hindi' => [ 'कर', 'ना', 'करना', 'ले', 'ली', 'ले', 'लो', 'ली', 'ली', 'ले', 'लें', 'लों' ],
			'japanese' => [ 'する', 'ない', 'ました', 'てる', 'ている', 'できる', 'れる', 'いる', 'いない', 'なさい', 'ます', 'たち', 'てる', 'ている', 'できる', 'れる', 'いる', 'いない', 'なさい', 'ました', 'たち', 'てる', 'ている', 'できる', 'れる', 'いる', 'いない', 'なさい', 'たり', 'った', 'って', 'して', 'ったり', 'てる', 'ている', 'できる', 'れる', 'いる', 'いない', 'なさい', 'ます', 'たり', 'った', 'って', 'して' ],
		];
	
		$word_tails = array_key_exists( strtolower( $language ), $word_tail_array ) ? $word_tail_array[ strtolower( $language ) ] : $word_tail_array[ 'english' ];
	
		return $word_tails;
	}

    // Mainly for English.
	function count_syllables( $word ) {
		$word = strtolower( trim( $word ) );
		if ( strlen( $word ) <= 3 ) { return 1; }

		$language = get_option( 'seo_kiss_options', null )[ 'seo_engine_language' ] ?? 'English';

		$word = preg_replace( '/[^a-z]/is', '', $word );
		$vowels = $this->getVowels( $language );
		$syllables = 0;
		$was_vowel = false;

		for ( $i = 0; $i < strlen( $word ); $i++ ) {
			$is_vowel = in_array( $word[$i], $vowels );
			if ( $is_vowel && !$was_vowel ){ $syllables++; }
			$was_vowel = $is_vowel;
		}

		$tail = substr( $word, -2 );

		//syllables for tail in the language
		$word_tails = $this->getWordTailByLanguage( $language );
		if ( in_array( $tail, $word_tails ) ) { $syllables--; }

		if ( $syllables == 0) $syllables = 1;

		return $syllables;
	}

    function calculate_readability( $content ) {
		$content = wp_kses_post( $content );

		// Clean up content. Only keep the text.
		$content = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $content );
		$content = preg_replace( '/<style\b[^>]*>(.*?)<\/style>/is', '', $content );
		$content = preg_replace( '/<head\b[^>]*>(.*?)<\/head>/is', '', $content );
		$content = preg_replace( '/<[^>]*>/', '', $content );

		$content = preg_replace( '/\[.*?\]/', '', $content );
		$content = preg_replace( '/<.*?>/', '', $content );
		$content = preg_replace( '/\s+/', ' ', $content );

		$content = trim( $content );

		$word_count = str_word_count( $content );
		$readability = [
			'flesch_kincaid' => 0,
			'grade' => 'unknown',
		];
	
		if ( $word_count == 0 ) {
			return 0;
		}
	
		$sentences = preg_split( '/(?<=[.?!])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY );
		$sentence_count = count( $sentences );
	
		$syllables = 0;
		$words = explode( ' ', $content );
		foreach ( $words as $word ) {
			$syllables += $this->count_syllables( $word );
		}
	
		$flesch_kincaid = round( 206.835 - ( 1.015 * ( $word_count / $sentence_count ) ) - ( 84.6 * ( $syllables / $word_count ) ), 2 );
		$flesch_kincaid = min( max( $flesch_kincaid, 0 ), 100 );

		$readability[ 'flesch_kincaid' ] = $flesch_kincaid;

		switch( $readability[ 'flesch_kincaid' ] ){
			case ( $readability[ 'flesch_kincaid' ] < 10 ):
				$readability[ 'grade' ] = 'extremely difficult to read best understood by university graduates.';
				break;
			case ( $readability[ 'flesch_kincaid' ] < 30 ):
				$readability['grade'] = 'very difficult to read, best understood by university graduates.';
				break;
			case ( $readability[ 'flesch_kincaid' ] < 50 ):
				$readability['grade'] = 'difficult to read.';
				break;
			case ( $readability[ 'flesch_kincaid' ] < 60 ):
				$readability['grade'] = 'fairly difficult to read.';
				break;
			case ( $readability[ 'flesch_kincaid' ] < 70 ):
				$readability[ 'grade' ] = 'easily understood by 13- to 15-year-old students.';
				break;
			case ( $readability['flesch_kincaid'] < 80 ):
				$readability[ 'grade' ] = 'fairly easy to read.';
				break;
			case ( $readability[ 'flesch_kincaid' ] < 90 ):
				$readability[ 'grade' ] = 'easy to read. Conversational English for consumers.';
				break;
			case ( $readability[ 'flesch_kincaid' ] <= 100 ):
				$readability[ 'grade' ] = 'very easy to read. Easily understood by an average 11-year-old student.';
				break;
		}

		return $readability;
	}
}