<?php
class WPFingerprint_Transport_Wporg{
  private $url = 'https://downloads.wordpress.org/plugin-checksums/{slug}/{version}.json';
  private $diff_url = 'https://plugins.trac.wordpress.org/browser/{slug}/tags/{version}/{file}?format=txt';
  private $diff_trunk_url = 'https://plugins.trac.wordpress.org/browser/{slug}/trunk/{file}?format=txt';
  private $transport_name = 'WordPress.org';

  private $options = array(
    'checksum' => true,
    'diff' => false,
    'submit' => false
  );

  public function get_name()
  {
    return $this->transport_name;
  }

  public function get_plugin_checksums( $plugin, $version ) {
		$url = str_replace(
			array(
				'{slug}',
				'{version}',
			),
			array(
				$plugin,
				$version,
			),
			$this->url
		);
		$args = array(
			'timeout' => 30,
      'headers' => array(
        'Accept' => 'application/json'
      ),
		);
    $response = wp_remote_get( $url, $args);
    $response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
			return false;
		}
		$body = json_decode(  wp_remote_retrieve_body($response), true );
		if ( ! is_array( $body ) || ! isset( $body['files'] ) || ! is_array( $body['files'] ) ) {
			return false;
		}
		return $this->clean_checksums($body['files']);
	}

  public function clean_checksums($body)
  {
    $cleaned = array();
    foreach($body as $filename => $checksums)
    {
      $cleaned[$filename] = $checksums['sha256'];
    }
    return $cleaned;
  }

  public function get_option($option)
  {
    if(isset($this->options[$option])){
      return $this->options[$option];
    }
    return false;
  }

  public function get_plugin_file( $plugin, $version, $file )
  {
    if(isset($file))
    {
      $file = ltrim($file,'/');
    }

    $url = str_replace(
			array(
				'{slug}',
				'{version}',
        '{file}'
			),
			array(
				$plugin,
				$version,
        $file
			),
			$this->diff_url
		);
    $args = array(
			'timeout' => 30,
      'headers' => array(
      'Accept' => 'text/plain'
      ),
		);

    $response = wp_remote_get( $url, $args);
    $response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
      if(404 == $response_code)
      {
        //Let's try the trunk
        $url = str_replace(
    			array(
    				'{slug}',
            '{file}'
    			),
    			array(
    				$plugin,
            $file
    			),
    			$this->diff_trunk_url
    		);
        $args = array(
    			'timeout' => 30,
          'headers' => array(
          'Accept' => 'text/plain'
          ),
    		);
        $response = wp_remote_get( $url, $args);
        $response_code = wp_remote_retrieve_response_code( $response );
        if( 200 !== $response_code )
        {
          return false;
        }
      }
      else{
        return false;
      }
		}
    return wp_remote_retrieve_body($response);
  }

}
