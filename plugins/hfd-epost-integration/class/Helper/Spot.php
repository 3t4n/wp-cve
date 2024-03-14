<?php
/**
 * Created by PhpStorm.
 * Date: 6/6/18
 * Time: 2:04 PM
 */
namespace Hfd\Woocommerce\Helper;

use Hfd\Woocommerce\Container;

class Spot
{
    const CACHE_KEY = 'betanet_epost_spot_listing';

    protected $cities = array();

    public function getSpots()
    {
        $spots = $this->loadCache();

        if( $spots ){
            return $spots;
        }
		
		$args = array(
			'timeout' => 15,
		);
		
		// add bearer token into request
        $setting = Container::get('Hfd\Woocommerce\Setting');
        $authToken = $setting->get('betanet_epost_hfd_auth_token');
        if( $authToken ){
			$args['headers'] = array( 'Authorization' => 'Bearer '.$authToken );
        }
		$response = wp_remote_get( $this->getServiceUrl(), $args );
		$response = wp_remote_retrieve_body( $response );
		
        $xml = simplexml_load_string( $response, 'SimpleXMLElement', LIBXML_NOCDATA );
        $arrResponse = json_decode(json_encode($xml), true);

        $spots = array();
        if( isset( $arrResponse['spots']['spot_detail'] ) && !empty( $arrResponse['spots']['spot_detail'] ) ){
            foreach( $arrResponse['spots']['spot_detail'] as $spot ){
                $spots[$spot['n_code']] = $spot;
            }
        }

        $this->saveCache($spots);

        return $spots;
    }

    public function getSpotsByCity($city)
    {
        $cache = Container::get('Hfd\Woocommerce\Cache');
        $cacheKey = self::CACHE_KEY . '_' . md5($city);
        $spots = $cache->get($cacheKey);
        if( $spots ){
            return $spots;
        }
		
		$args = array(
			'timeout' => 15,
		);
		$response = wp_remote_get( $this->getServiceUrl( $city ), $args );
		$response = wp_remote_retrieve_body( $response );
		
        $xml = simplexml_load_string( $response, 'SimpleXMLElement', LIBXML_NOCDATA );
        $arrResponse = json_decode( json_encode( $xml ), true );

        $spots = array();
        if (empty($arrResponse['message']) && !empty($arrResponse['spots']['spot_detail'])) {
            $_spots = $arrResponse['spots']['spot_detail'];
            if (!empty($_spots['n_code'])) {
                $_spots = array($_spots);
            }
            foreach ($_spots as $spot) {
                $spots[$spot['n_code']] = $spot;
            }
        }

        $cache->save($cacheKey, $spots);

        return $spots;
    }

    /**
     * @return mixed
     */
    protected function loadCache()
    {
        $cache = Container::get('Hfd\Woocommerce\Cache');
        $spots = $cache->get(self::CACHE_KEY);

        return $spots;
    }

    protected function saveCache($data)
    {
        $cache = Container::get('Hfd\Woocommerce\Cache');
        $cache->save(self::CACHE_KEY, $data);

        return $this;
    }

    /**
     * @param string $city
     * @return mixed
     */
    public function getServiceUrl($city = 'all')
    {
        $setting = Container::get('Hfd\Woocommerce\Setting');
        $url = $setting->get('betanet_epost_service_url');
        $parsed = parse_url($url);

        parse_str($parsed['query'], $query);
        $query['ARGUMENTS'] = '-A'. ($city ? $city : 'all');
        $url = $parsed['scheme'] . '://' . $parsed['host'] . $parsed['path'] . '?' . http_build_query($query);

        return $url;
    }

    public function getCities()
    {
        if ($this->cities) {
            return $this->cities;
        }

        return $this->loadCities();
    }

    protected function loadCities()
    {
        $file = HFD_EPOST_PATH . '/data/city.csv';
        if (!file_exists($file)) {
            return array();
        }

        $fp = fopen($file,"r");
        while ($row = fgetcsv($fp)) {
            $this->cities[] = $row[0];
        }
        fclose($fp);

        sort($this->cities);
        return $this->cities;
    }
}