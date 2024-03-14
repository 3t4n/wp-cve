<?php

class RL21CFile{

    public const TTL_LONG = "long";
    public const TTL_SHORT = "short";

    private $fp_long = '';
    private $fp_short = '';

    public function __construct($request_url)
    {
        $hash = md5($request_url);
        $this->fp_long =  RL21UtilWP::get_cache_dir(self::TTL_LONG).DIRECTORY_SEPARATOR.$hash; 
        $this->fp_short =  RL21UtilWP::get_cache_dir(self::TTL_SHORT).DIRECTORY_SEPARATOR.$hash; 
    }

    public function exists($ttl, $shouldBeAfter=0){
        $fp = $ttl==RL21CFile::TTL_LONG ? $this->fp_long.'_c': $this->fp_short.'_c';
        $fe = file_exists($fp);
        if($fe && $shouldBeAfter && $shouldBeAfter>631152000){
            $mt = filemtime($fp);
            if($mt && $mt<$shouldBeAfter){
                //post is modified after cache was generated
                $fe = false;
            }
        }
        return $fe;
    }
    
    public function delete($ttl){
        $fp = $ttl==RL21CFile::TTL_LONG ? $this->fp_long: $this->fp_short;
        $count = 0;
        if(is_file($fp.'_c') && @unlink($fp.'_c')){
            $count++;
        }
        if(is_file($fp.'_h') && @unlink($fp.'_h')){
            $count++;
        }
        return $count;
    }

    public function invalidate(){
        $fp = $this->fp_long.'_c';
        if(is_file($fp)) {
            $content = file_get_contents($fp);
            if($content!==false){
                $content = str_ireplace(['"rlCacheRebuild": "N"', '"rlCacheRebuild":"N"'], '"rlCacheRebuild": "Y"', $content);
            }
            RabbitLoader_21_Util_Core::fpc($fp, $content, WP_DEBUG);
        }
    }


    public function &get($ttl, $type){
        $fp = $ttl==RL21CFile::TTL_LONG ? $this->fp_long: $this->fp_short;
        $content = '';
        if(file_exists($fp.'_'.$type)){
            $content = file_get_contents($fp.'_'.$type);
        }
        return $content;
    }

    public function serve(){
        if($this->exists(RL21CFile::TTL_LONG)){
            $content = file_get_contents($this->fp_long.'_c');
            if ($content!==false){
                if (RabbitLoader_21_Core::strHasValidClosingTags($content)) {
                    if(file_exists($this->fp_long.'_h')){
                        //header is optional
                        RabbitLoader_21_Util_Core::send_headers(file_get_contents($this->fp_long.'_h'));
                    }
                    RabbitLoader_21_Core::sendHeader('x-rl-cache: hit', true);
                    echo $content;
                    return true;
                }
            }
        }
        return false;
    }

    public function save($ttl, &$content, &$headers){
        $fp = $ttl==RL21CFile::TTL_LONG ? $this->fp_long: $this->fp_short;
        $headers = json_encode($headers, JSON_INVALID_UTF8_IGNORE);
        $count = 0;
        if(RabbitLoader_21_Util_Core::fpc($fp.'_h', $headers, WP_DEBUG)){
            $count++;
        }
        if(RabbitLoader_21_Util_Core::fpc($fp.'_c', $content, WP_DEBUG)){
            $count++;
        }
        return $count;
    }
}

?>