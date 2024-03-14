<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\Includes\CountryNames;
use FluentSupport\Framework\Request\Request;

class OptionsController extends Controller
{

    public function getCountries()
    {
        return [
            'countries' => CountryNames::get()
        ];
    }
}
