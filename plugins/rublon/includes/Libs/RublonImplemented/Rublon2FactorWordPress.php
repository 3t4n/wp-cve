<?php

namespace Rublon_WordPress\Libs\RublonImplemented;

use Rublon\Rublon;
use RublonHelper;


class Rublon2FactorWordPress extends Rublon
{

    public function canUserActivate()
    {

        return (!RublonHelper::isSiteRegistered() && current_user_can('manage_options'));

    }


    public function getLang()
    {

        return RublonHelper::getBlogLanguage();

    }


    public function getAPIDomain()
    {

        return RublonHelper::getAPIDomain();

    }


}
