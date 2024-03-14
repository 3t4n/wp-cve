<?php
class shareCount {
  private $url,$timeout;
  function __construct($url,$timeout=10) {
    $this->url=rawurlencode($url);
    $this->timeout=$timeout;
    $this->rawurl=$url;
  }
  function get_twitter() {
//    $json_string = $this->file_get_contents_curl('http://urls.api.twitter.com/1/urls/count.json?url=' . $this->url);
//    $json = json_decode($json_string, true);
//    return isset($json['count'])?intval($json['count']):0;
  }
  function get_linkedin() {
    $json_string = $this->file_get_contents_curl("https://www.linkedin.com/countserv/count/share?url=$this->rawurl&format=json");
    $json = json_decode($json_string, true);
    return isset($json['count'])?intval($json['count']):0;
  }
  function get_facebook() {
    $json_string = $this->file_get_contents_curl('http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls='.$this->url);
    $json = json_decode($json_string, true);
    return isset($json[0]['total_count'])?intval($json[0]['total_count']):0;
  }
  function get_google_plus()  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode($this->url).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    $curl_results = curl_exec ($curl);
    curl_close ($curl);
    $json = json_decode($curl_results, true);
    return isset($json[0]['result']['metadata']['globalCounts']['count'])?intval( $json[0]['result']['metadata']['globalCounts']['count'] ):0;
  }
  function get_stumbleupon() {
    $json_string = $this->file_get_contents_curl('http://www.stumbleupon.com/services/1.01/badge.getinfo?url='.$this->url);
    $json = json_decode($json_string, true);
    return isset($json['result']['views'])?intval($json['result']['views']):0;
  }
  function get_delicious() {
    $json_string = $this->file_get_contents_curl('http://feeds.delicious.com/v2/json/urlinfo/data?url='.$this->url);
    $json = json_decode($json_string, true);
    return isset($json[0]['total_posts'])?intval($json[0]['total_posts']):0;
  }
  function get_pinterest() {
    $return_data = $this->file_get_contents_curl('http://api.pinterest.com/v1/urls/count.json?url='.$this->url);
    $json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
    $json = json_decode($json_string, true);
    return isset($json['count'])?intval($json['count']):0;
  }
  function get_reddit() {
    $json_string = $this->file_get_contents_curl('http://www.reddit.com/api/info.json?url='.$this->url);
    $json = json_decode($json_string, true);
    return isset($json['data']['children'][0]['data']['score'])?intval($json['data']['children'][0]['data']['score']):0;
  }
  function get_vk() {
    $data = $this->file_get_contents_curl('http://vk.com/share.php?act=count&url='.$this->url);
    $shares = array();
    preg_match( '/^VK\.Share\.count\(\d, (\d+)\);$/i', $data, $shares );
    return isset($shares[1])?intval($shares[1]):0;
  }
  function get_digg() {
      ini_set('user_agent', 'wpfai plugin'); # Change this!
      $page = $this->url;
      $apicall = 'http://services.digg.com/stories/';
      $apicall .= '?link='.urlencode($page);
      $apicall .= '&appkey='.urlencode('http://'.$_SERVER['HTTP_HOST']);
      $apicall .= '&type=xml';
      $result = (array) simplexml_load_file($apicall);
      if(!empty($result['story'])){
          $result['story'] = (array) $result['story'];
          return (!empty($result['story']['@attributes']['diggs']))? intval($result['story']['@attributes']['diggs']) : 0;
      } else {
          return 0;
      }
  }
  function get_xing() {
    $url = urlencode($this->url);
    $btn = 'https://www.xing-share.com/app/share?op=get_share_button;counter=top;url='.$url;
    $file= file_get_contents($btn);
    
    $dom = new DomDocument();
    $dom->loadHTML($file);
    $finder = new DomXPath($dom);
    $classname="xing-count";
    $nodes = $finder->query("//*[contains(@class, '$classname')]");
    if (!is_null($nodes)) {
      foreach ($nodes as $node) {
        return ($node->nodeValue);
      }
    }
  }
  function get_tumblr() {
    return false;
  }
  function get_weibo() {
    return false;
  }
  function get_renren() {
    return false;
  }
  function get_envelope() {
    return false;
  }
  private function file_get_contents_curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
    $cont = curl_exec($ch);
    if(!curl_error($ch)) {
      return $cont;
    }

  }
}