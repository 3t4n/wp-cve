<?php
class ChainedTextCaptcha {
	// verify the captcha
	static function verify($question, $answer) {
		$answer = stripslashes($answer);
		
		$captcha_questions = get_option('chained_text_captcha');
		$captcha_questions = explode("\n", $captcha_questions);
		
		$question = base64_decode($question);
		
		foreach($captcha_questions as $captcha_question) {
			list($q, $a) = explode("=", $captcha_question);
			$q = trim($q);		
			$q = stripslashes($q);
			$a = stripslashes($a);
				
			if(strcmp($q, $question) == 0 and strcasecmp(trim($a), trim($answer)) == 0) return true;
		}		
		
		// in any other case return false
		return false;
	}
	
	// generate the captcha
	static function generate( $quiz_id ) {
		$captcha_questions = get_option('chained_text_captcha');
		$captcha_questions = explode("\n", $captcha_questions);
		
		// just get random
		shuffle($captcha_questions);
		$question = $captcha_questions[0];
		list($q, $a) = explode("=", $question);
		$q = stripslashes($q);
		
		return trim($q)." <input type='text' name='chained_text_captcha_answer' id='chainedTextCaptchaAnswer{$quiz_id}'>\n<input type='hidden' name='chained_text_captcha_question' value=\"".base64_encode(trim($q))."\">"; 
	}
}
