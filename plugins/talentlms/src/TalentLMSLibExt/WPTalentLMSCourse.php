<?php

namespace TalentlmsIntegration\TalentLMSLibExt;

use TalentLMS_ApiRequestor;
use TalentLMS_Course;

class WPTalentLMSCourse extends TalentLMS_Course
{
    public static function gotoCourse($params)
    {
        $url = self::_instanceUrlByParams('gotoCourse', $params);
        $url .= ',2fa:true';
        $response = TalentLMS_ApiRequestor::request('get', $url);

        return $response;
    }
}
