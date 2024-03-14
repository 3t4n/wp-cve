<?php

namespace FluentConnect\App\Http\Controllers;

use FluentConnect\App\Models\FeedRunner;
use FluentConnect\Framework\Request\Request;

class LogsController extends Controller
{
    public function get(Request $request)
    {
        $runners = FeedRunner::with(['trigger', 'actionLogs'])
            ->orderBy('id', 'DESC')
            ->paginate();

        return [
            'logs' => $runners
        ];

    }
}


