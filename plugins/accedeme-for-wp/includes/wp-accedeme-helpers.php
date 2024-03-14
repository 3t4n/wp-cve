<?php

/**
 * Adds our one line of code to the footer.
 *
 * This class defines all code necessary to run the Accedeme widget.
 *
 * @package    wp_accedeme
 * @subpackage wp_accedeme/includes
 * @author     Accedeme
 */
class wp_accedeme_helpers
{
    /**
     * Constructor
     */
    public function __construct() {
    }

	public function accedemeInitTable() {
        global $wpdb;
        $table_name      = $wpdb->prefix . ACCEDEME_TABLE_NAME;
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "
        CREATE TABLE IF NOT EXISTS `$table_name` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `domain_key` VARCHAR(32) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate
        ";
    
        if ( ! function_exists( 'dbDelta' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }
    
        dbDelta( $sql );
    }
    
    public function accedemeRemoveTable() {
        global $wpdb;
        $table_name = $wpdb->prefix . ACCEDEME_TABLE_NAME;
    
        $sql = "DROP TABLE IF EXISTS `$table_name`";
    
        $wpdb->get_results( $sql );
    }
    
    public function accedemeIsTableExist() {
        global $wpdb;
        $table_name  = $wpdb->prefix . ACCEDEME_TABLE_NAME;
		
        $table_exist = false;
        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name ) {
            $table_exist = true;
        }
    
        return $table_exist;
    }

    public function accedemeInsert( $website_key )
    {
        global $wpdb;
        $table_name  = $wpdb->prefix . ACCEDEME_TABLE_NAME;

		$data = array(
						'domain_key' => $website_key,
		);
        $wpdb->insert( $table_name, $data );
    }
    
    public function accedemeGetRemoteWebsiteKey() {
        global $wp_version;

        $website_key = null;

        $apiUrl = 'https://accedeme.com/plugins/wordpress_get_domain_key';
        $apiParameters = array(
            'domain' => parse_url( get_site_url(), PHP_URL_HOST ),
            'name' => get_bloginfo( 'name' ),
            'version' => $wp_version,
        );
    
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body'        => wp_json_encode( $apiParameters ),
            'method'      => 'POST',
			'timeout'     => 15,
			'sslverify'   => false,
        );

        $response = wp_remote_post( $apiUrl, $args );
        $response_code = wp_remote_retrieve_response_message( $response );
    
        if ( $response_code === 'OK' ) {
            $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
    
            if ( $response_body['status'] == 'OK' )
            {
                $website_key = sanitize_text_field( $response_body['data']['domain_key'] );
            }
        }
    
        return $website_key;
    }
    
    public function accedemeGetWebsiteKey() {
        global $wpdb;
        $table_name = $wpdb->prefix . ACCEDEME_TABLE_NAME;


        $website_key = $wpdb->get_var( $wpdb->prepare( " SELECT domain_key FROM $table_name WHERE id = %d ", 1 ) );

		if ( !$website_key )
		{
			$website_key = $this->accedemeGetRemoteWebsiteKey();
		}
				
        return $website_key;
    }
}
