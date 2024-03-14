<?php

namespace WPPayForm\App\Http\Requests;

use WPPayForm\Framework\Foundation\RequestGuard;

class UserRequest extends RequestGuard
{
    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }
}
