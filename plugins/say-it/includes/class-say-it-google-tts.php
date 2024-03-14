<?php

// Imports the Cloud Client Library
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

class Say_It_Google_TTS {

	/*---------------------------------
	 *  Class attributes
	 *--------------------------------*/
	private $plugin_name;
	private $options;
	private $google_tts_client;
	private $google_tts_error;
	public $enabled = false;

	/*---------------------------------
	 *  Init the class
	 *--------------------------------*/
	public function __construct($plugin_name, $options) {
		$this->plugin_name = $plugin_name;
		$this->options = $options;
		$this->google_tts_error = null;

		if( isset($this->options['google_tts_key'] ) && ! empty( $this->options['google_tts_key'] ) ){
			$this->google_tts_client = $this->authenticate_google_service();
		}
	}

	public function get_google_tts_error(){
		return $this->google_tts_error;
	}

	/*---------------------------------
	 *  Authenticate to google API
	 *--------------------------------*/
	public function authenticate_google_service() {
		try{
			$json_credentials = json_decode(html_entity_decode($this->options['google_tts_key']), true);
			$google_tts_client = new TextToSpeechClient(['credentials' => $json_credentials]);
			$this->enabled = true;
			return $google_tts_client;
		} catch (Throwable $e) {
			$this->google_tts_error = $e->getMessage();
			return null;
		}
	}


	/*---------------------------------
	 *  Get MP3 from google API
	 *--------------------------------*/
	public function get_google_mp3($text, $lang="en-US", $sex="male", $speed=1, $custom_voice=null){

		// Get a uniq md5 based filepath
		$uploads = wp_upload_dir();

		$file_name = md5( strtolower( $text ) );
		$speed_folder_name = str_replace('.', '-', $speed);
		
		if($custom_voice){
			$relative_path = "/sayit_cache/$lang/$custom_voice/$speed_folder_name";
		}else{
			$relative_path = "/sayit_cache/$lang/$sex/$speed_folder_name";
		}

		$upload_url = $uploads['baseurl'] . $relative_path;
		$upload_path = $uploads['basedir'] . $relative_path;

		$file_path = "$upload_path/$file_name.mp3";
		$file_url = "$upload_url/$file_name.mp3";

		// If the file already exist, just return it !
		if(file_exists($file_path)){
			return $file_url;
		}
		
		// If the file doesn't exist and we don't have tts_client, we return null
		if(!isset($this->google_tts_client)){
			return null;
		}

		// Make upload dir if no-exist
		wp_mkdir_p( $upload_path );

		// sets text to be synthesised
		$synthesis_input = new SynthesisInput();
		$synthesis_input->setText($text);

		// build the voice request
		$voice = new VoiceSelectionParams();
		$voice->setLanguageCode($lang);
		if($sex == "male"){
			$voice->setSsmlGender(SsmlVoiceGender::MALE);
		}else{
			$voice->setSsmlGender(SsmlVoiceGender::FEMALE);
		}
		if(isset($custom_voice)){
			$voice->setName($custom_voice);
		}
		
		// select the type of audio file you want returned
		$audioConfig = new AudioConfig();
		$audioConfig->setAudioEncoding(AudioEncoding::MP3);
		$audioConfig->setSpeakingRate($speed); /* between 0.25 and 4 */

		// perform text-to-speech request on the text input with selected voice
		// parameters and audio file type
		$response = $this->google_tts_client->synthesizeSpeech($synthesis_input, $voice, $audioConfig);
		$audioContent = $response->getAudioContent();

		// the response's audioContent is binary
		file_put_contents($file_path, $audioContent);
		return $file_url;
	}


	/*---------------------------------
	 *  Get languages from google API
	 *--------------------------------*/
	public function get_google_languages()
	{
		if(!isset($this->google_tts_client)) return array();

		// perform list voices request
		$response = $this->google_tts_client->listVoices();
		$voices = $response->getVoices();

		// Init the return array
		$formated_voices = Array();
		$language_codes = include 'language_codes.php';

		foreach ($voices as $voice) {

			$voice_name = $voice->getName();
			$ssmlVoiceGender = ['UNSPECIFIED', 'MALE', 'FEMALE', 'NEUTRAL'];
			$gender = $voice->getSsmlGender();
			$voice_gender = $ssmlVoiceGender[$gender];

			// display the supported language codes for this voice. example: 'en-US'
			foreach ($voice->getLanguageCodes() as $languageCode) {
				if(!isset($language_codes[$languageCode])){continue;};
				if(!isset($formated_voices[$languageCode])){
					$formated_voices[$languageCode] = Array(
						'formated' => $language_codes[$languageCode],
						'voices' => Array()
					);
				}
				array_push($formated_voices[$languageCode]['voices'], array(
					'name' => $voice_name,
					'gender' => $voice_gender
				));
				
			}

		}
		
		return $formated_voices;
	}


	/*------------------------------
	 *  Get voices from google API
	 *-----------------------------*/
	public function get_voices()
	{
		if(!isset($this->google_tts_client)) return array();

		// perform list voices request
		$response = $this->google_tts_client->listVoices();
		$voices = $response->getVoices();

		// Init the return array
		$formated_voices = Array();

		foreach ($voices as $voice) {

			$voice_name = $voice->getName();
			$ssmlVoiceGender = ['UNSPECIFIED', 'MALE', 'FEMALE', 'NEUTRAL'];
			$gender = $voice->getSsmlGender();
			$voice_gender = $ssmlVoiceGender[$gender];

			foreach ($voice->getLanguageCodes() as $languageCode) {
				array_push($formated_voices, array(
					'language' => $languageCode,
					'name' => $voice_name,
					'gender' => $voice_gender
				));
				
			}

		}

		return $formated_voices;
	}
}
