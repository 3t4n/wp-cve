<?php

namespace FluentSupport\App\Http\Requests;

use FluentSupport\Framework\Foundation\RequestGuard;

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
