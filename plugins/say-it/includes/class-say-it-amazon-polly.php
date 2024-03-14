<?php

class Say_It_Amazon_Polly {

	/*---------------------------------
	 *  Class attributes
	 *--------------------------------*/
	private $plugin_name;
	private $options;
    private $amazon_aws_client;
    private $amazon_aws_error;
    private $amazon_voices;
	public $enabled = false;

	/*---------------------------------
	 *  Init the class
	 *--------------------------------*/
	public function __construct($plugin_name, $options) {
		$this->plugin_name = $plugin_name;
		$this->options = $options;

		if( isset($this->options['amazon_polly_key'] ) && ! empty( $this->options['amazon_polly_key'] ) ){
			$this->amazon_aws_client = $this->authenticateAmazonAWS();
		}
	}

	/*---------------------------------
	 *  Authenticate to Amazon
	 *--------------------------------*/
	public function authenticateAmazonAWS() {
		try{
			$connection = [
				'region'      => $this->options['amazon_polly_region'],
				'version'     => '2016-06-10',
				'credentials' => [
					'key'    => $this->options['amazon_polly_key'],
					'secret' => $this->options['amazon_polly_secret'],
				],
			];
			$client = new \Aws\Polly\PollyClient($connection);
			$this->amazon_voices = $client->describeVoices();
			$this->enabled = true;
			return $client;
		} catch (Throwable $e) {
			$this->amazon_aws_error = $e->getMessage();
			return null;
		}
	}

	public function get_aws_errors(){
		return $this->amazon_aws_error;
	}

	public function get_voices(){
		if($this->enabled){
			return $this->amazon_voices;
		}else{
			return null;
		}
	}

	/*---------------------------------
	 *  Get mp3 from amazon polly
	 *--------------------------------*/
	public function get_mp3($text, $voice = null){
		if(!$this->enabled){
			return null;
		}

		if ($voice === null) {
			$voice = $this->options['amazon_voice'];
		}

		// Fix accented voices
		if($voice == 'Léa') $voice = 'Lea';
		if($voice == 'Penélope') $voice = 'Penelope';
		if($voice == 'Céline') $voice = 'Celine';
		if($voice == 'Dóra') $voice = 'Dora';
		if($voice == 'Inês') $voice = 'Ines';
		

		// Get a uniq md5 based filepath
		$uploads = wp_upload_dir();

		$file_name = md5( strtolower( $text ) );
		
		$relative_path = "/sayit_cache/polly/$voice";

		$upload_url = $uploads['baseurl'] . $relative_path;
		$upload_path = $uploads['basedir'] . $relative_path;

		$file_path = "$upload_path/$file_name.mp3";
		$file_url = "$upload_url/$file_name.mp3";

		// If the file already exist, just return it !
		if(file_exists($file_path)){
			return $file_url;
		}

		// If the file doesn't exist and we don't have aws client, we return null
		if(!isset($this->amazon_aws_client)){
			return null;
		}

		// Make upload dir if no-exist
		wp_mkdir_p( $upload_path );

		$polly_args = [
			'OutputFormat' => 'mp3',
			'Text'         => $text,
			'TextType'     => 'text',
			'VoiceId'      => $voice,
		];
		
		$result = $this->amazon_aws_client->synthesizeSpeech($polly_args);
		$audioContent = $result->get('AudioStream')->getContents();

		// We upload the mp3 in his place
		file_put_contents($file_path, $audioContent);
		return $file_url;
	}

}
