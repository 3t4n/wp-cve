<?php

namespace FluentSupport\App\Http\Requests;

use FluentSupport\Framework\Foundation\RequestGuard;

class AgentCreateRequest extends RequestGuard
{
    public function rules()
    {
        return [
        	'email' => 'required|email',
            'first_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'first_name.required' => 'First name is required'
        ];
    }
}
