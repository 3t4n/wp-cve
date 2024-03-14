<?php

class WDIInstagram {
  private $wdi_options = NULL;
  private $cache = NULL;
  private $wdi_authenticated_users_list = NULL;
  private $account_data = NULL;

  public $feed_id;
  public $conditions = array();
  public $iter;

  function __construct() {
    require_once("WDICache.php");
    $this->cache = new WDICache();
    $this->wdi_options = get_option("wdi_instagram_options");
    if ( isset($this->wdi_options["wdi_authenticated_users_list"]) ) {
      $this->wdi_authenticated_users_list = json_decode($this->wdi_options["wdi_authenticated_users_list"], TRUE);
    }

    $this->iter = WDILibrary::get('iter', 0, 'intval', 'POST' );
    $this->feed_id = WDILibrary::get('feed_id', 0, 'intval', 'POST' );
    $this->conditions = $this->get_filters( $this->feed_id );
  }

  /**
   * Get condition filters for feed
   *
   * @param integer $feed_id
   *
   * @return array current feed filter data
   */
  public function get_filters( $feed_id ) {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery */
    $row = $wpdb->get_row($wpdb->prepare("SELECT conditional_filter_enable, conditional_filter_type, conditional_filters FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE id = %d", $feed_id), ARRAY_A);
    if( !empty($row) && $row['conditional_filter_enable'] ) {
      $conditional_filters = json_decode($row['conditional_filters'], 1);
      if( empty($conditional_filters) ) {
        return array();
      }
      return array(
        'conditional_filter_enable' => $row['conditional_filter_enable'],
        'conditional_filter_type' => $row['conditional_filter_type'],
        'conditional_filters' => json_decode($row['conditional_filters'], 1),
      );
    } else {
      return array();
    }
  }

  /**
   * Filter media data according to the filter conditions
   *
   * @param array $medias all medias got from endpoint
   * @param array $filters hashtag conditions for feed
   * @param string $type condition type AND / OR
   *filterUserMedia
   * @return array filtered media
   */
  public function filterUserMedia( $response, $filters, $type ) {
    return array();
  }

  /**
   * Get user media from cache
   *
   * @param string $user_name connected username
   * @param integer $fee_id
   *
   * @return string comments data
  */
  public function getUserMedia( $user_name, $feed_id ) {
    $wdi_requests_success = intval(get_option('wdi_cache_success_'.$feed_id, 0));
    if ( isset($this->wdi_authenticated_users_list) && is_array($this->wdi_authenticated_users_list) && isset($this->wdi_authenticated_users_list[$user_name]) ) {
      $cache_data = $this->cache->get_cache_data($feed_id.'_0');
      if ( isset($cache_data) && $cache_data["success"] && isset($cache_data["cache_data"]) && $wdi_requests_success) {
        $return_data = $this->join_feed_cache($feed_id);
        $cache_data['data'] = $this->sortData( $feed_id, $return_data);
        return wp_json_encode($cache_data);
      } else {
        return wp_json_encode(array('data'=>''));
      }
    }
  }

  /**
   * Sorting data according options
   *
   * @param integer $feed_id
   * @param array $cache_datas connected username
   *
   * @return array $cache_datas sorted array
  */
  public function sortData( $feed_id, $cache_datas ) {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery */
    $result = $wpdb->get_row($wpdb->prepare("SELECT sort_images_by, display_order FROM ". esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE id = %d", intval($feed_id)), ARRAY_A );
    switch ($result['sort_images_by']) {
      case 'date':
        if( $result['display_order'] == 'asc' ) {
          array_multisort (array_column($cache_datas, 'created_time'), SORT_ASC, $cache_datas);
        } else {
          array_multisort (array_column($cache_datas, 'created_time'), SORT_DESC, $cache_datas);
        }
        break;
      case 'likes':
        if( $result['display_order'] == 'asc' ) {
          array_multisort (array_column($cache_datas, 'likes'), SORT_ASC, $cache_datas);
        } else {
          array_multisort (array_column($cache_datas, 'likes'), SORT_DESC, $cache_datas);
        }
        break;
      case 'comments':
        if( $result['display_order'] == 'asc' ) {
          array_multisort (array_column($cache_datas, 'comments'), SORT_ASC, $cache_datas);
        } else {
          array_multisort (array_column($cache_datas, 'comments'), SORT_DESC, $cache_datas);
        }
        break;
      default:
        shuffle($cache_datas);
    }
    return $cache_datas;
  }

  /**
   * Get comments from endpoint or from cache
   *
   * @param string $user_name connected username
   * @param string $media_id media id
   *
   * @return string comments data
   */
  public function getRecentMediaComments( $user_name, $media_id ) {
    $data['data'] = '';
    $data['meta']['code'] = 200;

    return wp_json_encode($data);
  }

  /**
   * Get all cache datas for feed and merge in one array
   *
   * @param int $feed_id
   *
   * @return array merged data
  */
  public function join_feed_cache($feed_id) {
    $data = array();
    for( $i = 0; $i < 50; $i++ ) {
      $cache_data = $this->cache->get_cache_data($feed_id.'_'.$i);
      if ( isset($cache_data) && $cache_data["success"] && isset($cache_data["cache_data"]) ) {
          $cache_data = base64_decode($cache_data["cache_data"]);
          $cache_data = json_decode($cache_data, 1);
          $data = array_merge( $data, $cache_data['data'] );
      } else {
          break;
      }
    }
    return $data;
  }

  /**
   * Get medias from cache
   *
   * @param string $feed_id
   *
   * @return string
   */
  public function getTagRecentMedia( $feed_id ) {
    $cache_data = $this->cache->get_cache_data($feed_id.'_0');
    $wdi_requests_success = intval(get_option('wdi_cache_success_'.$feed_id, 0));
    if ( isset($cache_data) && $cache_data["success"] && isset($cache_data["cache_data"]) && $wdi_requests_success ) {
        $return_data['data'] = $this->join_feed_cache($feed_id);
        return wp_json_encode($return_data);
    } else {
        return wp_json_encode(array('data'=>''));
    }
  }

  public function wdi_getHashtagId( $tagname = '', $user_name = '' ) {
    $this->account_data = $this->wdi_authenticated_users_list[$user_name];
    $url = 'https://graph.facebook.com/v12.0/ig_hashtag_search/?user_id=' . $this->account_data["user_id"] . '&q=' . $tagname . '&access_token=' . $this->account_data["access_token"];
    $args = array();
    //wp_remote_get is a native WordPress function
    /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get */
    $response = wp_remote_get($url, $args);
    $hashtag_id = '';
    if ( !isset($response->errors) && is_array($response) && isset($response['body']) ) {
      $response = json_decode($response['body'], TRUE);
      if ( !empty($response['data']) && !empty($response['data'][0]) && !empty($response['data'][0]['id']) ) {
        $return_data['meta']['code'] = 200;
        $hashtag_id = $response['data'][0]['id'];
      }
      else {
        $return_data['meta']['code'] = 400;
        $return_data['meta']['message'] = $response['error']['error_user_msg'];
      }
    }
    else {
      $return_data['meta']['code'] = 400;
      $return_data['meta']['message'] = 'Bad Request';
    }
    $return_data['tag_id'] = $hashtag_id;

    return $return_data;
  }

  private function convertPersonalData( $data ) {
    $carousel_media_ids = array();
    $converted_data = array(
      "data" => array(),
      "pagination" => array(),
    );
    if ( is_array($data) ) {
      if ( isset($data["paging"]) ) {
        $converted_data["pagination"] = array(
          "cursors" => array(
            "after" => $data["paging"]["cursors"]["after"],
          ),
          "next_url" => (isset($data["paging"]["next"]) ? $data["paging"]["next"] : ""),
        );
      }
      if ( isset($data["data"]) ) {
        foreach ( $data["data"] as $key => $media ) {
          if ( $media["media_type"] == "IMAGE" ) {
            $media_type = "image";
          }
          elseif ( $media["media_type"] == "VIDEO" ) {
            $media_type = "video";
          }
          else {
            $media_type = "carousel";
          }
          if ( isset($media["like_count"]) ) {
            $like_count = intval($media["like_count"]);
          }
          else {
            $like_count = 0;
          }
          $converted = array(
            "id" => (isset($media["id"]) ? $media["id"] : ""),
            "user" => array(
              "id" => "",
              "full_name" => "",
              "profile_picture" => "",
              "username" => "",
            ),
            "created_time" => (isset($media["timestamp"]) ? $media["timestamp"] : ""),
            "caption" => array(
              "id" => "",
              "text" => (isset($media["caption"]) ? $media["caption"] : ""),
              "created_time" => "",
              "from" => array(
                "id" => "",
                "full_name" => "",
                "profile_picture" => "",
                "username" => "",
              ),
            ),
            "user_has_liked" => ($like_count > 0),
            "likes" => isset($media["like_count"]) ? $media["like_count"] : 0, // media.like_count
            "tags" => array(),
            "filter" => "Normal",
            "comments" => isset($media["comments_count"]) ? $media["comments_count"] : 0, // media.comments_count
            "media_type" => $media["media_type"],
            "type" => $media_type,
            "link" => (isset($media["permalink"]) ? $media["permalink"] : ""),
            "location" => NULL,
            "attribution" => NULL,
            "users_in_photo" => array(),
          );
          if ( $media["media_type"] === "IMAGE" ) {
            $converted["images"] = array(
              "thumbnail" => array(
                "width" => 150,
                "height" => 150,
                "url" => (isset($media["media_url"]) ? $media["media_url"] : ""),
              ),
              "low_resolution" => array(
                "width" => 320,
                "height" => 320,
                "url" => (isset($media["media_url"]) ? $media["media_url"] : ""),
              ),
              "standard_resolution" => array(
                "width" => 1080,
                "height" => 1080,
                "url" => (isset($media["media_url"]) ? $media["media_url"] : ""),
              ),
            );
            $converted['thumbnail'] = isset($media["media_url"]) ? $media["media_url"] : '';
          }
          else if ( $media["media_type"] === "VIDEO" ) {
            $converted["videos"] = array(
              "standard_resolution" => array(
                "width" => 640,
                "height" => 800,
                "url" => (isset($media["media_url"]) ? $media["media_url"] : ""),
              ),
              "low_bandwidth" => array(
                "width" => 480,
                "height" => 600,
                "url" => (isset($media["media_url"]) ? $media["media_url"] : ""),
              ),
              "low_resolution" => array(
                "width" => 480,
                "height" => 600,
                "url" => (isset($media["media_url"]) ? $media["media_url"] : ""),
              ),
            );
            $converted['thumbnail'] = isset($media["thumbnail_url"]) ? $media["thumbnail_url"] : '';
          }

          /**
           * Set to global media object the carousel media data as key carousel-media.
           *
           * @param response               =>  Global media object
           * @param carusel_media_ids      =>  Child ids
           * @param ind                    =>  index counter
           *
           */
          if ( $media["media_type"] === "CAROUSEL_ALBUM" ) {
            $carousel_media = $this->getMediaChildren($media["id"]);
            $converted["carousel_media"] = $carousel_media;
            $converted["thumbnail"] = $carousel_media[0]["thumbnail"];
            array_push($carousel_media_ids, array( 'index' => $key, "media_id" => $media["id"] ));
          }
          array_push($converted_data["data"], $converted);
        }
      }
    }
    return $converted_data;
  }

  /**
   * Convert hashtag data
   *
   * @param data array
   *
   * @return array
  */
  private function convertHashtagData( $data ) {
    $converted_data = array(
      'data' => array(),
      'pagination' => array(),
    );

    return $converted_data;
  }

  /**
   * Get media children id by gallery id.
   *
   * @param media_id =>  Media id
   *
   * @return array of founded child media data
   */
  private function getMediaChildren( $media_id ) {

    $carousel_media = array();
    $api_url = 'https://graph.instagram.com/v12.0/';
    if ( $this->account_data["type"] === "business" ) {
      $api_url = 'https://graph.facebook.com/v12.0/';
    }
    $api_url .= $media_id . '/children?access_token=' . $this->account_data["access_token"];
    $fields = 'id,media_type,media_url,permalink,thumbnail_url,username,timestamp';
    $api_url .= '&fields='.$fields;
    //wp_remote_get is a native WordPress function
    /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get */
    $response = wp_remote_get($api_url, array());
    if ( is_array($response) && isset($response["body"]) && $this->isJson($response["body"]) ) {
      $medias = json_decode($response["body"], TRUE);
      if ( is_array($medias) && isset($medias["data"]) ) {
        foreach ( $medias["data"] as $media_data ) {
          if ( isset($media_data["media_type"]) && $media_data["media_type"] === "IMAGE" ) {
            $child_media = array(
              "images" => array(
                "thumbnail" => array(
                  "width" => 150,
                  "height" => 150,
                  "url" => $media_data["media_url"],
                ),
                "low_resolution" => array(
                  "width" => 320,
                  "height" => 320,
                  "url" => $media_data["media_url"],
                ),
                "standard_resolution" => array(
                  "width" => 640,
                  "height" => 640,
                  "url" => $media_data["media_url"],
                ),
              ),
              "users_in_photo" => array(),
              "type" => "image",
              "thumbnail" => $media_data["media_url"],
            );
          }
          else {
            $child_media = array(
              "videos" => array(
                "standard_resolution" => array(
                  "width" => 640,
                  "height" => 800,
                  "url" => esc_url_raw($media_data["media_url"]),
                  "id" => $media_data["id"],
                ),
                "low_bandwidth" => array(
                  "width" => 480,
                  "height" => 600,
                  "url" => esc_url_raw($media_data["media_url"]),
                  "id" => $media_data["id"],
                ),
                "low_resolution" => array(
                  "width" => 640,
                  "height" => 800,
                  "url" => esc_url_raw($media_data["media_url"]),
                  "id" => $media_data["id"],
                ),
              ),
              "users_in_photo" => array(),
              "type" => "video",
              "thumbnail" => $media_data["thumbnail_url"],
            );
          }
          array_push($carousel_media, $child_media);
        }
      }
    }
    return $carousel_media;
  }

  public function wdi_set_preload_cache_data( $user_name, $feed_id, $endpoint, $tag_id, $tag_name ) {
    if ( $tag_id === 'false' || (isset($this->wdi_authenticated_users_list) && is_array($this->wdi_authenticated_users_list) && isset($this->wdi_authenticated_users_list[$user_name])) ) {
      $next_url = WDILibrary::get('next_url', '', 'esc_url_raw', 'POST');
      $iter = WDILibrary::get('iter', 0, 'intval');
      if ( $next_url != '' ) {
        $baseUrl = $next_url;
        $this->account_data = $this->wdi_authenticated_users_list[$user_name];
      }
      else {
        $this->account_data = $this->wdi_authenticated_users_list[$user_name];
        $user_id = $this->account_data["user_id"];
        $access_token = $this->account_data["access_token"];
        if( $tag_id === 'false' ) {
            $api_url = 'https://graph.instagram.com/v12.0/';
            $media_fields = 'id,media_type,media_url,permalink,thumbnail_url,username,caption,timestamp';
            if ( $this->account_data["type"] === "business" ) {
              $api_url = 'https://graph.facebook.com/v12.0/';
              $media_fields = 'id,media_type,media_url,permalink,thumbnail_url,username,caption,timestamp,ig_id,is_comment_enabled,like_count,comments_count,owner,shortcode';
            }
            $baseUrl = $api_url . $user_id . '/media/?fields=' . $media_fields . '&limit=100&access_token=' . $access_token;
        } else {
            $baseUrl = $this->wdi_getHashtagData($user_name, $endpoint, $tag_id);
        }
      }
      //If the internet connection is poor, the standard 5 seconds will not be enough
      $args = array(
        'timeout' => 60,//phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
        'sslverify' => FALSE
      );
      if (strpos($baseUrl, 'https://graph.facebook.com/') === 0 || strpos($baseUrl, 'https://graph.instagram.com/') === 0 ) {
        //wp_remote_get is a native WordPress function
        /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get */
        $response = wp_remote_get($baseUrl, $args);
      } else {
        wp_die();
      }
      if ( !isset($response->errors) && is_array($response) && isset($response["body"]) ) {
        $data = json_decode($response["body"], TRUE);
        if ( !empty($data['data']) ) {
          foreach ($data['data'] as $key => $ig_object) {
            if ($ig_object['media_type'] === "VIDEO") {
              $oembedApiUrl = "https://graph.facebook.com/v12.0/instagram_oembed?url=" . $ig_object['permalink'] . "&access_token=" . $this->account_data["access_token"];
              //wp_remote_get is a native WordPress function
              /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get */
              $oembedData = wp_remote_get($oembedApiUrl, $args);
              if ( !isset($oembedData->errors) && is_array($oembedData) && isset($oembedData["body"]) ) {
                $oembedData = json_decode($oembedData["body"], TRUE);
                if ( !empty($oembedData['thumbnail_url']) ) {
                  if (!array_key_exists('media_url', $ig_object)) { // if no media url(Copyright) change type to image
                    $data['data'][$key]['media_type'] = "IMAGE";
                    $data['data'][$key]['media_url'] = $oembedData['thumbnail_url'];
                  } else { //
                    $data['data'][$key]['thumbnail_url'] = $oembedData['thumbnail_url'];
                  }
                }
              }
            }
            else if ($ig_object['media_type'] === "CAROUSEL_ALBUM") {
              if ( !empty($ig_object['children']['data']) ) {
                foreach ( $ig_object['children']['data'] as $carousel_key => $carousel_child ) {
                  if ( $carousel_child['media_type'] === "VIDEO" ) {
                    $childOembedApiUrl = "https://graph.facebook.com/v12.0/instagram_oembed?url=" . $carousel_child['permalink'] . "&access_token=" . $this->account_data["access_token"];
                    //wp_remote_get is a native WordPress function
                    /* phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get */
                    $childOembedData = wp_remote_get($childOembedApiUrl, $args);
                    if ( !isset($childOembedData->errors) && is_array($childOembedData) && isset($childOembedData["body"]) ) {
                      $childOembedData = json_decode($childOembedData["body"], TRUE);
                      if ( !empty($childOembedData['thumbnail_url']) ) {
                        if ( !array_key_exists('media_url', $carousel_child) ) { // if no media url(Copyright) change type to image
                          $data['data'][$key]['children']['data'][$carousel_key]['media_type'] = "IMAGE";
                          $data['data'][$key]['children']['data'][$carousel_key]['media_url'] = $childOembedData['thumbnail_url'];
                        }
                        else { // if ok just add thumb url for VIDEO type
                          $data['data'][$key]['children']['data'][$carousel_key]['thumbnail'] = $childOembedData['thumbnail_url'];
                        }
                      }
                    }
                  }
                }
              }
            }
          }
          if ( !empty($this->conditions) ) {
              $temp_data = $data;
              $temp_data['data'] = $this->filterUserMedia( $data, $this->conditions['conditional_filters'], $this->conditions['conditional_filter_type'] );
              if($tag_id !== 'false') {
                $current_data = $this->convertHashtagData($temp_data);
              } else {
                $current_data = $this->convertPersonalData($temp_data);
              }
          } elseif( $tag_id !== 'false' ) {
              $current_data = $this->convertHashtagData($data);
          } else {
              $current_data = $this->convertPersonalData($data);
          }

          /* Remove current feed cache data */
          if ( $iter == 0 ) {
              $this->cache->reset_feed_cache( $feed_id );
          }

          $current_data = wp_json_encode($current_data);


          $this->cache->set_cache_data($feed_id.'_'.$iter, base64_encode($current_data));

          $return_data['next_url'] = '';
          if ( isset($data['paging']['next']) && $data['paging']['next'] != '' ) {
            $return_data['next_url'] = $data['paging']['next'];
            $return_data['iter'] = $iter;
          }

          $wdi_cache_request_count = isset($this->wdi_options['wdi_cache_request_count']) ? intval($this->wdi_options['wdi_cache_request_count']) : 10;

          /* Check if all requests done or limi of requests exceed keep option 1 */
          if( $return_data['next_url'] == '' ||  $iter == ($wdi_cache_request_count-1) ) {
            update_option('wdi_cache_success_'.$feed_id, 1, 1);
          }
          return wp_json_encode($return_data);
        }
        else {
          $return_data['next_url'] = '';
          $return_data['iter'] = $iter;
          return wp_json_encode($return_data);
        }
      }
    }
  }

  public function wdi_getHashtagData($user_name, $endpoint, $tag_id) {
    return '';
  }

  private function get_feed_list($data, $cron = TRUE){
    $feed_list = array();
    if($cron){
      if ( isset($data) && is_array($data) ) {
        foreach ( $data as $feed ) {
          if ( isset($feed->feed_users) ) {
            $endpoint = $feed->hashtag_top_recent;
            $feed_users = json_decode($feed->feed_users);
            $feed_data = array(
              "feed_list"=>array(),
            );
            if ( is_array($feed_users)) {
              foreach ( $feed_users as $user ) {
                if ( $user->username[0] === "#" ) {
                  $tag_name = str_replace("#", "", $user->username);
                  $feed_arr = array(
                    "tag_name" =>$tag_name,
                    "type" =>"tag",
                    "endpoint" =>$endpoint,
                  );
                }else{
                  $feed_arr = array(
                    "tag_name" =>$user->username,
                    "type" =>"user",
                    "endpoint" =>$endpoint,
                  );
                  $feed_data["user_name"] = $user->username;
                }
                array_push($feed_data["feed_list"], $feed_arr);
              }
            }
            array_push($feed_list, $feed_data);
          }
        }
      }
    }else{
      $feed_data = array(
        "feed_list"=>array(),
      );
      if ( is_array($data)) {
        foreach ( $data as $user ) {
          if ( $user["username"][0] === "#" ) {
            $tag_name = str_replace("#", "", $user["username"]);
            $feed_arr = array(
              "tag_name" =>$tag_name,
              "type" =>"tag",
              "endpoint" =>1,
            );
          }else{
            $feed_arr = array(
              "tag_name" =>$user["username"],
              "type" =>"user",
              "endpoint" =>1,
            );
            $feed_data["user_name"] = $user["username"];
          }
          array_push($feed_data["feed_list"], $feed_arr);
        }
      }
      array_push($feed_list, $feed_data);
    }
    return $feed_list;
  }

  private function isJson( $string ) {
    json_decode($string);

    return (json_last_error() == JSON_ERROR_NONE);
  }
}