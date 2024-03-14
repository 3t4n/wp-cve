<?php

namespace FluentConnect\App\Http\Controllers;

use FluentConnect\Framework\Request\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        return [
            'message' => 'Welcome to WPFluent.'
        ];
    }
}
