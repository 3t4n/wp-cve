<?php
namespace GSBEH;

// if direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Handles the all kind of data scrapping.
 * 
 * @since 2.0.12
 */
class Scrapper {

    /**
     * Scrapes data from given url.
     * 
     * @since 2.0.12
     * 
     * @param  string $url    The url to scrape.
     * @return mixed  $result Scraped data.
     */
    public function scrape( $url ) {

        $transient_key = 'gs_behance_scrap_data_' . base64_encode( $url );

        $result = get_transient( $transient_key );

        if( $result != false ) return $result;

        $response = wp_remote_get( $url,
            array(
                'sslverify' => false,
                'timeout'   => 60,
                'headers'   => [
                    'X-Requested-With' => 'XMLHttpRequest',
                ],
            )
        );

        $xml    = wp_remote_retrieve_body( $response );
        $result = json_decode( $xml, true );

        set_transient( $transient_key, $result, DAY_IN_SECONDS );

        return $result;
    }

    /**
     * Checks if it's a section data.
     * 
     * @since 2.0.12
     * 
     * @param  array $shots Scraped behance shots
     * @return bool         checks given behance shots are from sections.
     */
    public function is_section( $shots ) {
        return array_key_exists( 'section_content', (array) $shots );
    }

    /**
     * Checks if it's a profile data.
     * 
     * @since 2.0.12
     * 
     * @param  array $shots Scraped behance shots
     * @return bool         checks given behance shots are profiles data.
     */
    public function is_profile( $shots ) {
        return array_key_exists( 'profile', (array) $shots ) && array_key_exists( 'activeSection', $shots['profile'] ) &&  array_key_exists( 'work', $shots['profile']['activeSection'] ) && array_key_exists( 'projects', $shots['profile']['activeSection']['work'] );
    }

    /**
     * Fiters behance shots based on the condition.
     * 
     * @since 2.0.12
     * 
     * @param array  $gs_behance_shots Scraped behance shots.
     * @return bool                    Checks given behance shots are profiles data.
     */
    public function filter_shots( $gs_behance_shots ) {
        if ( $this->is_section( $gs_behance_shots ) ) {
            $gs_behance_shots = $gs_behance_shots[ 'section_content' ];
        }
        
        if ( $this->is_profile( $gs_behance_shots ) ) {
            $gs_behance_shots = $gs_behance_shots['profile']['activeSection']['work']['projects'];
        }

        return $gs_behance_shots;
    }
}
