<?php

use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Model\ModelSliders;


class Aarambha_DS_Smart_Slider
{

    /**
     * We're deleting the tutorial slider.
     */
    public static function delete()
    {
        $application = ApplicationSmartSlider3::getInstance();
        $applicationType = $application->getApplicationTypeFrontend();

        $sliderModel = new ModelSliders($applicationType);
        $sliderModel->deletePermanently(1);
    }
}
