<?php
/**
 * @file
 * ExtraWatch - Real-time visitor dashboard and stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 53
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2021 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

class ExtraWatchURLHelper {

    const USER_AGENT = 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6';

    private $extraWatchCMSSpecific;

    public function __construct(ExtraWatchCMSSpecific $extraWatchCMSSpecific) {
        $this->extraWatchCMSSpecific = $extraWatchCMSSpecific;
    }


    public function doURLRequest($url, $postParams = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0); // Fail on errors
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        if ($postParams) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;

    }


    public function extractDomainName() {
        $hostname = @$_SERVER['HTTP_HOST'];

        $url = @parse_url($this->extraWatchCMSSpecific->getCMSURL());
        $liveSitePath = $url['path'];
        if ($this->isSSL()) {
            return "https://".$hostname.$liveSitePath;
        }
        return "http://".$hostname.$liveSitePath;
    }

    private function isSSL() {
        return (!empty($_SERVER['HTTPS']) && @$_SERVER['HTTPS'] != 'off');
    }




}