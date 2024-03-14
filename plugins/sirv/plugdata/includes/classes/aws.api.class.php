<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class MagicToolbox_AmazonS3_Helper
{
    private $key;
    private $secretKey;
    private $bucket;
    private $host;
    private $date;
    private $curlInfo;
    public $authMessage;
    public $muteTime;


    public function __construct($params)
    {
        $this->key        = $params['key'];
        $this->secretKey = $params['secret_key'];
        $this->bucket     = $params['bucket'];
        $this->host       = isset($params['host']) ? $params['host'] : 's3.sirv.com';
        $this->date       = gmdate('D, d M Y H:i:s T');
        $this->authMessage = '';
        $this->muteTime = 0;

        return true;
    }


    public function checkCredentials()
    {
        $request = ['verb' => 'HEAD', 'resource' => '/' . $this->bucket];
        $result = $this->sendRequest($request);
        return $this->curlInfo['http_code'] == '200';
    }


    public function listBuckets()
    {
        $request = array('verb' => 'GET', 'bucket' => $this->bucket, 'resource' => '/');
        $result = $this->sendRequest($request);
        $xml = simplexml_load_string($result);

        if($xml === false || !isset($xml->Buckets->Bucket))
            return false;

        $buckets = array();
        foreach($xml->Buckets->Bucket as $bucket)
            $buckets[] = (string) $bucket->Name;
        return $buckets;
    }


    public function getBucketContents($prefix = null, $marker = null, $delimeter = null, $max_keys = null)
    {
        $dirs = array();
        $contents = array();
        $bucket = $this->bucket;

        do
        {
            $q = array();
            if(!is_null($prefix)) $q[] = 'prefix=' . $prefix;
            if(!is_null($marker)) $q[] = 'marker=' . $marker;
            if(!is_null($delimeter)) $q[] = 'delimeter=' . $delimeter;
            if(!is_null($max_keys)) $q[] = 'max-keys=' . $max_keys;
            $q = implode('&', $q);
            if(strlen($q) > 0)
                $q = '?' . $q;

            $request = array('verb' => 'GET', 'resource' => "/$bucket/$q");
            $result = $this->sendRequest($request);
            $xml = simplexml_load_string($result);

            if($xml === false)
                return false;

            foreach ($xml->CommonPrefixes as $prefixItem) {
                $dirs[] = array('Prefix' => (string) $prefixItem->Prefix);
            }

            foreach($xml->Contents as $item)
                $contents[] = array('Key' => (string) $item->Key, 'LastModified' => (string) $item->LastModified, 'ETag' => (string) $item->ETag, 'Size' => (string) $item->Size);

            $marker = (string) $xml->Marker;
        }
        while((string) $xml->IsTruncated == 'true' && is_null($max_keys));

        return array("bucket"      => $bucket,
                    "current_dir" => urldecode($prefix),
                    "contents"    => $contents,
                    "dirs"        => $dirs
                    );
    }


    public function createFolder($folderPath)
    {
        if(!file_exists('empty.jpg')) fclose(fopen('empty.jpg','x'));
            $url = $this->uploadFile($folderPath . 'empty.jpg', 'empty.jpg');
            if($url['sirv_path']!='') {
                $this->deleteFile($url['sirv_path']);
            }
    }

    //some encoded symbols broken upload files on a sirv
    private function clean_symbols($str){
        $str = str_replace('%40', '@', $str);
        $str = str_replace('%5D', '[', $str);
        $str = str_replace('%5B', ']', $str);
        $str = str_replace('%7B', '{', $str);
        $str = str_replace('%7D', '}', $str);
        $str = str_replace('%2A', '*', $str);
        $str = str_replace('%3E', '>', $str);
        $str = str_replace('%3C', '<', $str);
        $str = str_replace('%24', '$', $str);
        $str = str_replace('%3D', '=', $str);
        $str = str_replace('%2B', '+', $str);
        $str = str_replace('%28', '(', $str);
        $str = str_replace('%29', ')', $str);

        return $str;
    }


    public function uploadFile($sirv_path, $fs_path, $web_accessible = false, $headers = null)
    {
        // Some useful headers you can set manually by passing in an associative array...
        // Cache-Control
        // Content-Type
        // Content-Disposition (alternate filename to present during web download)
        // Content-Encoding
        // x-amz-meta-*
        // x-amz-acl (private, public-read, public-read-write, authenticated-read)


        //fix for unicode symbols in filename
        /* $path_info = pathinfo($sirv_path);
        //fix dirname if uploaded throuth browser
        $path_info['dirname'] = $path_info['dirname'] == '.' ? '' : '/' . $path_info['dirname'];
        $encoded_sirv_path = $path_info['dirname'] . '/' . rawurlencode($path_info['basename']);

        $encoded_sirv_path = $this->clean_symbols($encoded_sirv_path); */

        $encoded_sirv_path = $this->encodePath($sirv_path);

        $request = array(
            'verb'        => 'PUT',
            'bucket'      => $this->bucket,
            'resource'    => $encoded_sirv_path,
            'content-md5' => $this->base64(md5_file($fs_path))
        );

        $fh = fopen($fs_path, 'r');
        $curl_opts = array(
            'CURLOPT_PUT'           => true,
            'CURLOPT_INFILE'        => $fh,
            'CURLOPT_INFILESIZE'    => filesize($fs_path),
            'CURLOPT_CUSTOMREQUEST' => 'PUT'
        );

        if(is_null($headers))
            $headers = array();

        $headers['Content-MD5'] = $request['content-md5'];

        if($web_accessible === true && !isset($headers['x-amz-acl']))
            $headers['x-amz-acl'] = 'public-read';

        if(!isset($headers['Content-Type']))
        {
            $ext = pathinfo($fs_path, PATHINFO_EXTENSION);
            $headers['Content-Type'] = isset($this->mimeTypes[$ext]) ? $this->mimeTypes[$ext] : 'application/octet-stream';
        }
        $request['content-type'] = $headers['Content-Type'];

        $result = $this->sendRequest($request, $headers, $curl_opts);

        fclose($fh);
        $isFileUploaded = $this->curlInfo['http_code'] == '200';

        $full_url = $isFileUploaded ? 'https://' . $this->bucket . '.sirv.com/' . $sirv_path : '';
        $sirv_url = $isFileUploaded ? $sirv_path : '';

        if($full_url == ''){
            return array();
        } else {
            return array('full_url' => $full_url, 'sirv_path' => $sirv_path);
        }
    }


    protected function encodePath($path){
        $path_info = pathinfo($path);

        //fix dirname if uploaded throuth browser
        $path_info['dirname'] = $path_info['dirname'] == '.' ? '' : '/' . $path_info['dirname'];
        $encoded_path = $path_info['dirname'] . '/' . rawurlencode($path_info['basename']);

        return $this->clean_symbols($encoded_path);
    }

    public function copyFile($sirv_path, $sirv_path_copy, $web_accessible = false, $headers = null){

        $request = array(
            'verb' => 'PUT',
            'bucket' => $this->bucket,
            'resource' => $sirv_path_copy
        );

        $curl_opts = array(
            'CURLOPT_PUT' => true,
            'CURLOPT_CUSTOMREQUEST' => 'PUT'
        );

        if(is_null($headers))
            $headers = array();

        $headers['x-amz-acl'] = 'ACL';
        $headers['x-amz-copy-source'] = $this->bucket . $sirv_path;

        $result = $this->sendRequest($request, $headers, $curl_opts);

        return $this->curlInfo['http_code'] == '200';

    }

    public function renameFile($sirv_path, $sirv_path_copy)
    {
        return $this->copyFile($sirv_path, $sirv_path_copy) && $this->deleteFile($sirv_path);
    }


    public function deleteFile($s3_path)
    {
        $request = array('verb' => 'DELETE', 'bucket' => $this->bucket, 'resource' => "/$s3_path");
        $this->sendRequest($request);
        return $this->curlInfo['http_code'] == '204';
    }


    public function deleteFiles($keys) {

        $contents = '<'.'?xml version="1.0"?>'."\n".'<Delete xmlns="http://s3.amazonaws.com/doc/2006-03-01/"><Object><Key>'.implode('</Key></Object><Object><Key>', $keys).'</Key></Object><Quiet>true</Quiet></Delete>'."\n";

        $contentMd5 = base64_encode(md5($contents, true));

        $request = array(
            'verb' => 'POST',
            'bucket' => $this->bucket,
            'resource' => "/?delete",
            'content-md5' => $contentMd5,
            'content-type' => "application/xml",
        );

        $filesize = strlen($contents);
        $fh = fopen('php://temp', 'wb+');
        fwrite($fh, $contents);
        rewind($fh);

        $curl_opts = array(
            'CURLOPT_CUSTOMREQUEST' => 'POST',
            'CURLOPT_UPLOAD' => TRUE,
            'CURLOPT_INFILE' => $fh,
            'CURLOPT_INFILESIZE' => $filesize,
        );

        $headers = array(
            'Content-Type' => 'application/xml',
            'Content-MD5' => $contentMd5,
        );

        $result = $this->sendRequest($request, $headers, $curl_opts);
        fclose($fh);
        return $this->curlInfo['http_code'] == '200';
    }


    public function getObjectInfo($s3_path)
    {
        $request = array('verb' => 'HEAD', 'bucket' => $this->bucket, 'resource' => "/$s3_path");
        $curl_opts = array('CURLOPT_HEADER' => true, 'CURLOPT_NOBODY' => true);
        $result = $this->sendRequest($request, null, $curl_opts);
        $xml = @simplexml_load_string($result);

        if($xml !== false)
            return false;

        preg_match_all('/^(\S*?): (.*?)$/ms', $result, $matches);
        $info = array();
        for($i = 0; $i < count($matches[1]); $i++)
            $info[$matches[1][$i]] = $matches[2][$i];

        if(!isset($info['Last-Modified']))
            return false;

        return $info;
    }


     public function getTrafficStats($from, $to)
    {
        //$key = '/sirv%3Aapi%3Astats/dataTransferTotals';
        $key = '/sirv:api:stats/dataTransferTotals';
        $headers = array('Range' => $from . '/' . $to);
        $request = array('verb' => 'GET', 'bucket' => $this->bucket, 'resource'=> $key);
        $curl_opts = array('CURLOPT_HEADER' => true, 'CURLOPT_HEADER' => 0);
        $result = $this->sendRequest($request, $headers, $curl_opts);

        return $result;
    }

    public function getStats()
    {
        //$key = '/sirv%3Aapi%3Astats/storage';
        $key = '/sirv:api:stats/storage';
        $request = array('verb' => 'GET', 'bucket' => $this->bucket, 'resource'=> $key);
        $curl_opts = array('CURLOPT_HEADER' => true, 'CURLOPT_HEADER' => 0);
        $result = $this->sendRequest($request, null, $curl_opts);

        return $result;
    }

    public function getAccountInfo()
    {
        //$key = '/sirv%3Aapi%3Aaccounts/info';
        $key = '/sirv:api:accounts/info';
        $request = array('verb' => 'GET', 'bucket' => $this->bucket, 'resource'=> $key);
        $curl_opts = array('CURLOPT_HEADER' => true, 'CURLOPT_HEADER' => 0);
        $result = $this->sendRequest($request, null, $curl_opts);

        return $result;
    }


    private function muteRequests($timestamp){
        update_option('SIRV_MUTE', $timestamp, 'no');
    }

    public function isMuted(){
        $mute_timestamp = get_option('SIRV_MUTE');
        $ltime = $mute_timestamp ? (int) $mute_timestamp : 0;
        $this->muteTime = $ltime;
        return ( $ltime > time() );
    }


    private function sendRequest($request, $headers = null, $curl_opts = null)
    {
        if ($this->isMuted()) {
            $this->curlInfo = array('http_code' => 429);
            $this->authMessage = 'Limits requests reached. Retry after ' . date("Y-m-d H:i:s T P", $this->muteTime);

            return false;
        }

        if(is_null($headers))
            $headers = array();

        $headers['Date'] = $this->date;
        $headers['Authorization'] = 'AWS ' . $this->key . ':' . $this->signature($request, $headers);
        foreach($headers as $k => $v)
            $headers[$k] = "$k: $v";

       	$host = isset($request['bucket']) ? $request['bucket'].'.'.$this->host : $this->host;

        $uri = 'http://' . $host . $request['resource'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request['verb']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);

        if(is_array($curl_opts))
        {
            foreach($curl_opts as $k => $v)
                curl_setopt($ch, constant($k), $v);
        }

        $result = curl_exec($ch);
        $this->curlInfo = curl_getinfo($ch);

        if ($this->curlInfo['http_code'] == 429){
            if( preg_match('/Retry after ([0-9]{4}\-[0-9]{2}\-[0-9]{2}.*?\([a-z]{1,}\))/ims', $result, $t) ){
                $time = strtotime($t[1]);
                $this->muteRequests($time);
                $this->authMessage = 'Limits requests reached. Retry after ' . $t[1];
            }else{
                preg_match('/.*<message>(.*?)<\/message>.*/ims', $result, $m);
                $this->authMessage = !empty($m) ? $m[1] : 'Limits requests reached';
            }
        } else {
            $this->authMessage = '';
        }

        curl_close($ch);
        return $result;
    }

    private function signature($request, $headers = null)
    {
		if(is_null($headers))
			$headers = array();

        $CanonicalizedAmzHeadersArr = array();
        $CanonicalizedAmzHeadersStr = '';
        foreach($headers as $k => $v)
        {
            $k = strtolower($k);

            if(substr($k, 0, 5) != 'x-amz') continue;

            if(isset($CanonicalizedAmzHeadersArr[$k]))
                $CanonicalizedAmzHeadersArr[$k] .= ',' . trim($v);
            else
                $CanonicalizedAmzHeadersArr[$k] = trim($v);
        }
        ksort($CanonicalizedAmzHeadersArr);

        foreach($CanonicalizedAmzHeadersArr as $k => $v)
            $CanonicalizedAmzHeadersStr .= "$k:$v\n";

        if(isset($request['bucket'])) {
        	$request['resource'] = '/' . $request['bucket'] . $request['resource'];
        }

        $str  = $request['verb'] . "\n";
        $str .= isset($request['content-md5']) ? $request['content-md5'] . "\n" : "\n";
        $str .= isset($request['content-type']) ? $request['content-type'] . "\n" : "\n";
        $str .= isset($request['date']) ? $request['date']  . "\n" : $this->date . "\n";
        //$str .= $CanonicalizedAmzHeadersStr . preg_replace('/\?.*/', '', $request['resource']);
        $str .= $CanonicalizedAmzHeadersStr.preg_replace('#\?(?!delete$).*$#is', '', $request['resource']);

        $sha1 = $this->hasher($str);
        return $this->base64($sha1);
    }

    // Algorithm adapted (stolen) from http://pear.php.net/package/Crypt_HMAC/)
    private function hasher($data)
    {
        $key = $this->secretKey;
        if(strlen($key) > 64)
            $key = pack('H40', sha1($key));
        if(strlen($key) < 64)
            $key = str_pad($key, 64, chr(0));
        $ipad = (substr($key, 0, 64) ^ str_repeat(chr(0x36), 64));
        $opad = (substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64));
        return sha1($opad . pack('H40', sha1($ipad . $data)));
    }

    private function base64($str)
    {
        $ret = '';
        for($i = 0; $i < strlen($str); $i += 2)
            $ret .= chr(hexdec(substr($str, $i, 2)));
        return base64_encode($ret);
    }

    private function match($regex, $str, $i = 0)
    {
        if(preg_match($regex, $str, $match) == 1)
            return $match[$i];
        else
            return false;
    }

    private $mimeTypes = array("323" => "text/h323", "acx" => "application/internet-property-stream", "ai" => "application/postscript", "aif" => "audio/x-aiff", "aifc" => "audio/x-aiff", "aiff" => "audio/x-aiff",
    "asf" => "video/x-ms-asf", "asr" => "video/x-ms-asf", "asx" => "video/x-ms-asf", "au" => "audio/basic", "avi" => "video/quicktime", "axs" => "application/olescript", "bas" => "text/plain", "bcpio" => "application/x-bcpio", "bin" => "application/octet-stream", "bmp" => "image/bmp",
    "c" => "text/plain", "cat" => "application/vnd.ms-pkiseccat", "cdf" => "application/x-cdf", "cer" => "application/x-x509-ca-cert", "class" => "application/octet-stream", "clp" => "application/x-msclip", "cmx" => "image/x-cmx", "cod" => "image/cis-cod", "cpio" => "application/x-cpio", "crd" => "application/x-mscardfile",
    "crl" => "application/pkix-crl", "crt" => "application/x-x509-ca-cert", "csh" => "application/x-csh", "css" => "text/css", "dcr" => "application/x-director", "der" => "application/x-x509-ca-cert", "dir" => "application/x-director", "dll" => "application/x-msdownload", "dms" => "application/octet-stream", "doc" => "application/msword",
    "dot" => "application/msword", "dvi" => "application/x-dvi", "dxr" => "application/x-director", "eps" => "application/postscript", "etx" => "text/x-setext", "evy" => "application/envoy", "exe" => "application/octet-stream", "fif" => "application/fractals", "flr" => "x-world/x-vrml", "gif" => "image/gif",
    "gtar" => "application/x-gtar", "gz" => "application/x-gzip", "h" => "text/plain", "hdf" => "application/x-hdf", "hlp" => "application/winhlp", "hqx" => "application/mac-binhex40", "hta" => "application/hta", "htc" => "text/x-component", "htm" => "text/html", "html" => "text/html",
    "htt" => "text/webviewhtml", "ico" => "image/x-icon", "ief" => "image/ief", "iii" => "application/x-iphone", "ins" => "application/x-internet-signup", "isp" => "application/x-internet-signup", "jfif" => "image/pipeg", "jpe" => "image/jpeg", "jpeg" => "image/jpeg", "jpg" => "image/jpeg",
    "js" => "application/x-javascript", "latex" => "application/x-latex", "lha" => "application/octet-stream", "lsf" => "video/x-la-asf", "lsx" => "video/x-la-asf", "lzh" => "application/octet-stream", "m13" => "application/x-msmediaview", "m14" => "application/x-msmediaview", "m3u" => "audio/x-mpegurl", "man" => "application/x-troff-man",
    "mdb" => "application/x-msaccess", "me" => "application/x-troff-me", "mht" => "message/rfc822", "mhtml" => "message/rfc822", "mid" => "audio/mid", "mny" => "application/x-msmoney", "mov" => "video/quicktime", "movie" => "video/x-sgi-movie", "mp2" => "video/mpeg", "mp3" => "audio/mpeg",
    "mpa" => "video/mpeg", "mpe" => "video/mpeg", "mpeg" => "video/mpeg", "mpg" => "video/mpeg", "mpp" => "application/vnd.ms-project", "mpv2" => "video/mpeg", "ms" => "application/x-troff-ms", "mvb" => "application/x-msmediaview", "nws" => "message/rfc822", "oda" => "application/oda",
    "p10" => "application/pkcs10", "p12" => "application/x-pkcs12", "p7b" => "application/x-pkcs7-certificates", "p7c" => "application/x-pkcs7-mime", "p7m" => "application/x-pkcs7-mime", "p7r" => "application/x-pkcs7-certreqresp", "p7s" => "application/x-pkcs7-signature", "pbm" => "image/x-portable-bitmap", "pdf" => "application/pdf", "pfx" => "application/x-pkcs12",
    "pgm" => "image/x-portable-graymap", "pko" => "application/ynd.ms-pkipko", "pma" => "application/x-perfmon", "pmc" => "application/x-perfmon", "pml" => "application/x-perfmon", "pmr" => "application/x-perfmon", "pmw" => "application/x-perfmon", "png" => "image/png", "pnm" => "image/x-portable-anymap", "pot" => "application/vnd.ms-powerpoint", "ppm" => "image/x-portable-pixmap",
    "pps" => "application/vnd.ms-powerpoint", "ppt" => "application/vnd.ms-powerpoint", "prf" => "application/pics-rules", "ps" => "application/postscript", "pub" => "application/x-mspublisher", "qt" => "video/quicktime", "ra" => "audio/x-pn-realaudio", "ram" => "audio/x-pn-realaudio", "ras" => "image/x-cmu-raster", "rgb" => "image/x-rgb",
    "rmi" => "audio/mid", "roff" => "application/x-troff", "rtf" => "application/rtf", "rtx" => "text/richtext", "scd" => "application/x-msschedule", "sct" => "text/scriptlet", "setpay" => "application/set-payment-initiation", "setreg" => "application/set-registration-initiation", "sh" => "application/x-sh", "shar" => "application/x-shar",
    "sit" => "application/x-stuffit", "snd" => "audio/basic", "spc" => "application/x-pkcs7-certificates", "spl" => "application/futuresplash", "src" => "application/x-wais-source", "sst" => "application/vnd.ms-pkicertstore", "stl" => "application/vnd.ms-pkistl", "stm" => "text/html", "svg" => "image/svg+xml", "sv4cpio" => "application/x-sv4cpio",
    "sv4crc" => "application/x-sv4crc", "t" => "application/x-troff", "tar" => "application/x-tar", "tcl" => "application/x-tcl", "tex" => "application/x-tex", "texi" => "application/x-texinfo", "texinfo" => "application/x-texinfo", "tgz" => "application/x-compressed", "tif" => "image/tiff", "tiff" => "image/tiff",
    "tr" => "application/x-troff", "trm" => "application/x-msterminal", "tsv" => "text/tab-separated-values", "txt" => "text/plain", "uls" => "text/iuls", "ustar" => "application/x-ustar", "vcf" => "text/x-vcard", "vrml" => "x-world/x-vrml", "wav" => "audio/x-wav", "wcm" => "application/vnd.ms-works",
    "wdb" => "application/vnd.ms-works", "wks" => "application/vnd.ms-works", "wmf" => "application/x-msmetafile", "wps" => "application/vnd.ms-works", "wri" => "application/x-mswrite", "wrl" => "x-world/x-vrml", "wrz" => "x-world/x-vrml", "xaf" => "x-world/x-vrml", "xbm" => "image/x-xbitmap", "xla" => "application/vnd.ms-excel",
    "xlc" => "application/vnd.ms-excel", "xlm" => "application/vnd.ms-excel", "xls" => "application/vnd.ms-excel", "xlt" => "application/vnd.ms-excel", "xlw" => "application/vnd.ms-excel", "xof" => "x-world/x-vrml", "xpm" => "image/x-xpixmap", "xwd" => "image/x-xwindowdump", "z" => "application/x-compress", "zip" => "application/zip");
}
