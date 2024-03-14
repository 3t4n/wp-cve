<?php

namespace TalentlmsIntegration;

class ShortCodes implements Services\PluginService
{

    public function register(): void
    {
        add_shortcode(
            'talentlms-courses',
            array($this, 'tlms_courseList')
        );
    }

    public function tlms_courseList()
    {
        $categories = Utils::tlms_selectCategories();
        $courses = Utils::tlms_selectCourses();
        $dateFormat = Utils::tlms_getDateFormat(true);

        ob_start();
        require_once TLMS_BASEPATH . '/templates/talentlms_courses.php';
        return ob_get_clean();
    }
}
