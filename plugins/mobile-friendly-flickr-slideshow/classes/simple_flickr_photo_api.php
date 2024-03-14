<?php
class SimpleFlickrPhotoApi {
    private $base_url = 'https://api.flickr.com/services/rest/';
    private $api_key;
    private $transient_cache_time;
    private $cache_count;
    private $users;
    private $photosets;

    public function __construct( $api_key, $cache_time = 3600 ) {
        if (ini_get('precision') < 17) {
            ini_set('precision',17); //use 17-place floating-point precision for base conversions
        }
        if ($cache_time && $cache_time > 0) { //indefinite cache time not allowed
            $this->cache_time( $cache_time );
        } else {
            $this->cache_time( 3600 );
        }
        $this->cache_count = array('hit' => 0, 'miss' => 0);
        $this->api_key = $api_key; 
        $this->users = get_transient('fshow_users') ? get_transient('fshow_users') : array();
        $this->photosets = get_transient('fshow_photosets') ? get_transient('fshow_photosets') : array();
    }

    public function cache_time( $secs = false ) {
        if (is_numeric($secs) && $secs > 0) {
            $this->transient_cache_time = $secs;
        } else {
            return $this->transient_cache_time;
        }
    }

    public function get_user_id_from_username($username) {
        if (isset($this->users[$username])) {
            return $this->users[$username];
        } else {
            $args = array(  'method' => 'flickr.people.findByUsername',
                            'username' => $username );
            $result = $this->call($args);
            if (is_object($result) && isset($result->user)) {
                $user_id = $result->user->id;
                $this->users[$username] = $user_id;
                set_transient('fshow_users', $this->users);
            } else {
                $user_id = false;
            }
            return $user_id;
        }
    }

    public function get_user_id_from_photoset( $photoset_id ) {
        if (isset($this->photosets[$photoset_id])) {
            return $this->photosets[$photoset_id];
        } else {
            $args = array(  'method' => 'flickr.photosets.getInfo',
                            'photoset_id' => $photoset_id );
            $result = $this->call($args);
            if (is_object($result) && isset($result->photoset)) {
                $user_id = $result->photoset->owner;
                $this->photosets[$photoset_id] = $user_id;
                set_transient('fshow_photosets', $this->photosets);
            } else {
                $user_id = false;
            }
            return $user_id;
        }
    }

    public function get_url_parts_from_short_url( $short_url ) {
        $return = array();
        $pattern = '#http[s+]://flic.kr/s/([\S]+)#';
        $matches = array();
        preg_match($pattern, $short_url, $matches);
        if (isset($matches[1])) {
            $return['photosetid'] = SimpleFlickrPhotoApi::base58_decode($matches[1]);
            $return['user_id'] = $this->get_user_id_from_photoset($return['photosetid']);
        }
        return $return;
    }

        public function get_photos( $photoset_id, $user_id = false ) {
            $args = array( 'method' => 'flickr.photosets.getPhotos',
                            'photoset_id' => $photoset_id );
        if ($user_id) {
            $args['user_id'] = $user_id;
        }
        $result = $this->call($args);
        if (is_object($result) && isset($result->photoset)) {
            return $result->photoset->photo;
        } else {
            return false;
        }
    }

    public function get_short_url( $photosetid ) {
        return sprintf('https://flic.kr/s/%s', SimpleFlickrPhotoApi::base58_encode($photosetid));
    }
    
    public function get_cache_stats() {
        return $this->cache_count;
    }

    private function call($args) {
        $args = array_merge($args, array( 'api_key' => $this->api_key,
                                          'format' => 'json',
                                          'nojsoncallback' => '1'));
        $obj = $this->get_json($args);
        if ($obj->cached) {
            $this->cache_count['hit']++;
        } else {
            $this->cache_count['miss']++;
        }
        return $obj;
    }

    private function get_json($args) {
        $url = $this->base_url . '?' . http_build_query($args);
        $result = $this->get($url);
        $obj_to_return = json_decode($result['body']);
        $obj_to_return->cached = $result['cached'];
        return $obj_to_return;
    }

    private function get($url,$params = array()) {
        global $wp_version;
        $params = array_merge($params,  array(
                                                'timeout'     => 30,
                                                'redirection' => 5,
                                                'httpversion' => '1.0',
                                                'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
                                                'blocking'    => true,
                                                'headers'     => array(),
                                                'cookies'     => array(),
                                                'body'        => null,
                                                'compress'    => false,
                                                'decompress'  => true,
                                                'sslverify'   => true,
                                                'stream'      => false,
                                                'filename'    => null
                                       ));
        $params = apply_filters('fshow_wp_remote_get_args',$params);
        $query_hash = md5($url.serialize($params));
        if ( !is_array( $result = get_transient( 'fshow_remote_'.$query_hash ) ) ) {
            $result = wp_remote_get( $url, $params );
            if (is_array($result)) {
                set_transient( 'fshow_remote_'.$query_hash, $result, $this->transient_cache_time );
                $result['cached'] = false;
            } else {
                throw new Exception($result->get_error_message());
            }
        } else {
            $result['cached'] = true;
        }
        return $result;
    }


    /* @url: https://www.flickr.com/groups/api/discuss/72157616713786392/ */
    public static function base58_encode($num) {
        $base_count = 58;
        $alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $encoded = '';
        $div = 1;
     
        while ($num >= $base_count) {
            if (function_exists('bcdiv')) {
                $div = bcdiv("$num","$base_count");
            } else {
                $div = $num / $base_count;
            }
            if (function_exists('bcmod')) {
                $mod = bcmod("$num","$base_count");
            } else {
                $mod = $num % $base_count;
            }
            $encoded = $alphabet[$mod] . $encoded;
            $num = (int)$div;
        }
     
        if ($num >= 0) {
            $encoded = $alphabet[$num] . $encoded;
        }
     
        return $encoded;
    }
     
    public static function base58_decode($str) {
        $alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $base_count = strlen($alphabet);
        $len = strlen($str);
        $decoded = 0;
        $multi = 1;
     
        for ($i = $len - 1; $i >= 0; $i--) {
            $decoded += $multi * strpos($alphabet, $str[$i]);
            $multi = $multi * $base_count;
        }
     
        return $decoded;
    }

}
