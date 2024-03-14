<?php
/**
 * Show Original Republish Data.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Republish info class.
 */
class REVIVESO_RepublishInfo
{
	use REVIVESO_Hooker;
    use REVIVESO_SettingsData;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->filter( 'the_content', 'show_republish_info', $this->do_filter( 'published_date_priority', 10 ) );
	}

	/**
	 * Show original publish info.
	 * 
	 * @param string  $content  Original Content
	 * @return string $content  Filtered Content
	 */
	public function show_republish_info( $content ) {
        // get WordPress date time format
        $get_df = get_option( 'date_format' );
    	$get_tf = get_option( 'time_format' );
		
		$format = $this->do_filter( 'republish_info_date_format', $get_df . ' @ ' . $get_tf );

    	$reviveso_show_pubdate = $this->get_data( 'reviveso_republish_position' );
    	$reviveso_text = wp_kses_post( $this->get_data( 'reviveso_republish_position_text' ) );
        
		$reviveso_original_pub_date = $this->get_meta( get_the_ID(), '_reviveso_original_pub_date' );
    	if ( ! empty( $reviveso_original_pub_date ) ) {
    		$local_date = date_i18n( $this->do_filter( 'published_date_format', $format ), strtotime( $reviveso_original_pub_date ) );
        
			$dateline = '<p id="reviveso-pubdate" class="reviveso-pubdate reviveso-pubdate-container">';
    		$dateline .= '<span class="reviveso-label">' . $reviveso_text . '</span><span class="reviveso-time">' . $local_date;
    		$dateline .= '</p>';
		}

		if ( ! isset( $dateline ) ) {
			return $content;
		}
	
		if ( $reviveso_show_pubdate === 'before_content' ) {
        	$return_content = $dateline . $content;
        } elseif ( $reviveso_show_pubdate === 'after_content' ) {
        	$return_content = $content . $dateline;
	    }else{
			$return_content = $content;
		}
		
    	return $this->do_filter( 'republish_info_content', $return_content, $content, $dateline );
    }
}