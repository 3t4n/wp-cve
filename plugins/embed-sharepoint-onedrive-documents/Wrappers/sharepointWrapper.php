<?php

namespace MoSharePointObjectSync\Wrappers;

class sharepointWrapper{

    private static $sps;

    public static function getSPSWrapper(){
        if(!isset(self::$sps)){
            self::$sps = new sharepointWrapper();
        }
        return self::$sps;
    }

    public static function mo_sps_array_get_sharepoint_user_profile($user_details){
        
        $user_profile = [];

        if(isset($user_details['d']['UserProfileProperties']['results'])){
            foreach($user_details['d']['UserProfileProperties']['results'] as $result){
                if(isset($result['Key']) && isset($result['Value'])){
                    $user_profile[$result['Key']] = $result['Value'];
                }
            }
        }

        return $user_profile;
    }
}