<?php

namespace FluentConnect\App\Http\Requests;

use FluentConnect\Framework\Foundation\RequestGuard;

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
